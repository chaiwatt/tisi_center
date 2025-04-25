<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\ReceiveVolume as receive_volume;
use App\Models\Esurv\ReceiveVolumeLicense;
use App\Models\Esurv\ReceiveVolumeLicenseDetail;
use App\Models\Esurv\TisiLicenseDetail;
use App\Models\Esurv\Tis;
use App\Models\Besurv\HsCode;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;

use Illuminate\Http\Request;

use HP;
use DB;
class ReceiveVolumeController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'esurv_attach/inform_volume/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $user = auth()->user();
        $model = str_slug('receive_volume','-');

        if($user->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
            $filter['filter_tis'] = $request->get('filter_tis', '');
            $filter['perPage'] = $request->get('perPage', 10);
            $filter['sort'] = $request->get('sort','id-desc');

            $Query = new receive_volume;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }


            if ($filter['filter_created_by']!='') {
                $Query = $Query->where('created_by', $filter['filter_created_by']);
            }

            if ($filter['filter_start_month']!='') {
                $Query = $Query->where('inform_month', '>=', $filter['filter_start_month']);
            }

            if ($filter['filter_start_year']!='') {
                $Query = $Query->where('inform_year', '>=', $filter['filter_start_year']);
            }

            if ($filter['filter_end_month']!='') {
                $Query = $Query->where('inform_month', '<=', $filter['filter_end_month']);
            }

            if ($filter['filter_end_year']!='') {
                $Query = $Query->where('inform_year', '<=', $filter['filter_end_year']);
            }

            if ($filter['filter_department']!='') {
                $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
                $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
            }else{
                $subDepartments =[];
            }

            if ($filter['filter_sub_department']!='') {
                $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
            }

            if ($filter['filter_tis']!='') {
                $Query = $Query->where('tb3_Tisno', $filter['filter_tis']);
            }

            //Query
            $receive_volume = $Query->where('state', '!=', 0)->sortable()->latest()->paginate($filter['perPage']) ;

            //สิทธิ์การตรวจตามกลุ่มงานย่อย
            $user_tis = $user->tis->pluck('tb3_Tisno');

            return view('esurv.receive_volume.index', compact('receive_volume', 'filter', 'user_tis', 'subDepartments'));
        }

        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('esurv.receive_volume.create');
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            receive_volume::create($requestData);
            return redirect('esurv/receive_volume')->with('flash_message', 'เพิ่ม receive_volume เรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('view-'.$model)) {
            $previousUrl = app('url')->previous();
            $user = auth()->user();

            $receive_volume = receive_volume::findOrFail($id);
            $receive_volume->consider =  !empty($receive_volume->user_updated->FullName) ?  $receive_volume->user_updated->FullName :   $user->reg_fname.' '.$user->reg_lname;
            $receive_volume->starndard = Tis::where("tb3_Tisno", $receive_volume->tb3_Tisno)->first();

            //ผู้ยื่น
            $applicant = (object)[];
            $applicant->applicant_name = $receive_volume->applicant_name;
            $applicant->tel = $receive_volume->tel;
            $applicant->email = $receive_volume->email;

            //ไฟล์แนบ
            $attachs = json_decode($receive_volume['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            //เลขที่ใบอนุญาต
            $inform_volume_licenses = ReceiveVolumeLicense::where("inform_volume_id", $receive_volume->id)->pluck('tbl_licenseNo', 'id')->toArray();//ที่ถูกเลือก
            $own_licenses = HP::LicenseByTraderTis($receive_volume->created_by, $receive_volume->tb3_Tisno);//ทั้งหมดที่มี ตามมาตรฐาน

            // dd($inform_volume_licenses);
            //ปริมาณการผลิตตามรายละเอียดผลิตภัณฑ์
            $inform_details = [];
            foreach ($inform_volume_licenses as $key => $inform_volume_license) {
              $inform_volume_details = ReceiveVolumeLicenseDetail::where("inform_volume_license_id", $key)->get();//ที่บันทึกไว้
              foreach ($inform_volume_details as $key => $value) {
                $inform_details[$value->elicense_detail_id] = $value;
              }
            }

            //รายละเอียดผลิตภัณฑ์
            $details = [];
            foreach ($inform_volume_licenses as $key => $inform_volume_license) {
              $details[$inform_volume_license] = TisiLicenseDetail::where("licenseNo", $inform_volume_license)->get();
            }

            // dd($details);

            $set_state = (!empty($receive_volume->state) && $receive_volume->state!=1)?'disabled':false;
            return view('esurv.receive_volume.show', compact('receive_volume',
                                                            'applicant',
                                                            'attachs',
                                                            'attach_path',
                                                            'own_licenses',
                                                            'inform_volume_licenses',
                                                            'inform_details',
                                                            'details',
                                                            'set_state',
                                                            'previousUrl'
                                                        ));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('edit-'.$model)) {
            $previousUrl = app('url')->previous();
            $user = auth()->user();

            $receive_volume = receive_volume::findOrFail($id);
            $receive_volume->consider =  !empty($receive_volume->user_updated->FullName) ?  $receive_volume->user_updated->FullName :   $user->reg_fname.' '.$user->reg_lname;
            $receive_volume->starndard = Tis::where("tb3_Tisno", $receive_volume->tb3_Tisno)->first();

            //ผู้ยื่น
            $applicant = (object)[];
            $applicant->applicant_name = $receive_volume->applicant_name;
            $applicant->tel = $receive_volume->tel;
            $applicant->email = $receive_volume->email;

            //ไฟล์แนบ
            $attachs = json_decode($receive_volume['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            //เลขที่ใบอนุญาต
            $inform_volume_licenses = ReceiveVolumeLicense::where("inform_volume_id", $receive_volume->id)->pluck('tbl_licenseNo', 'id')->toArray();//ที่ถูกเลือก
            $own_licenses = HP::LicenseByTraderTis($receive_volume->created_by, $receive_volume->tb3_Tisno);//ทั้งหมดที่มี ตามมาตรฐาน

            //ปริมาณการผลิตตามรายละเอียดผลิตภัณฑ์
            $inform_details = [];
            foreach ($inform_volume_licenses as $key => $inform_volume_license) {
              $inform_volume_details = ReceiveVolumeLicenseDetail::where("inform_volume_license_id", $key)->get();//ที่บันทึกไว้
              foreach ($inform_volume_details as $key => $value) {
                $inform_details[$value->elicense_detail_id] = $value;
              }
            }

            //รายละเอียดผลิตภัณฑ์
            $details = [];
            foreach ($inform_volume_licenses as $key => $inform_volume_license) {
              $details[$inform_volume_license] = TisiLicenseDetail::where("licenseNo", $inform_volume_license)->get();
            }

            // dd($details);

            $set_state = (!empty($receive_volume->state) && $receive_volume->state!=1)?'disabled':false;

            return view('esurv.receive_volume.edit', compact('receive_volume',
                                                             'applicant',
                                                             'attachs',
                                                             'attach_path',
                                                             'own_licenses',
                                                             'inform_volume_licenses',
                                                             'inform_details',
                                                             'details',
                                                             'set_state',
                                                             'previousUrl'
                                                            ));

        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('edit-'.$model)) {

            $this->validate($request, [
                 'state' => 'required'
            ]);

            $requestData = $request->all();
            $requestData['consider'] = auth()->user()->getKey();

            $receive_volume = receive_volume::findOrFail($id);
            $receive_volume->update($requestData);

            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('flash_message', 'แก้ไข receive_volume เรียบร้อยแล้ว!');
            }else{
                return redirect('esurv/receive_volume')->with('flash_message', 'แก้ไข receive_volume เรียบร้อยแล้ว!');
            }


        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new receive_volume;
            receive_volume::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            receive_volume::destroy($id);
          }

          return redirect('esurv/receive_volume')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('receive_volume','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new receive_volume;
          receive_volume::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('esurv/receive_volume')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function add_subdepartment(Request $request)
    {
        $data = SubDepartment::where('did', $request->get('department_id'))->pluck('sub_departname','sub_id');

        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }


    public function report(Request $request)
   {
        $user = auth()->user();
        $model = str_slug('receive_volume','-');

        if($user->can('view-'.$model)) {

            $filter = [];
            $filter['filter_department'] = $request->get('filter_department', '');

            $Query = new receive_volume;
            $Query = $Query->selectRaw('
                        COUNT(DISTINCT (created_by)) as list_name,
                        COUNT(DISTINCT (tb3_Tisno)) as list_tis,
                        COUNT(`esurv_inform_volumes`.id) as total
                    ');

            if ($filter['filter_department']!='') {
                $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
                $receive_volume_id = receive_volume::whereIn('tb3_Tisno', $tis_subdepartments)->pluck('id');
                // dd($receive_volume_id);
                $Sub_Query = ReceiveVolumeLicense::selectRaw('DISTINCT(tbl_licenseNo)')->whereIn("inform_volume_id", $receive_volume_id)->pluck('tbl_licenseNo');
                // dd(count($Sub_Query));
                $cnt_sub_query = count($Sub_Query);
                $Query = $Query->selectRaw("$cnt_sub_query as license_list");
            } else{
                  $Query = $Query->selectRaw('
                (SELECT COUNT(DISTINCT (tbl_licenseNo)) FROM esurv_inform_volume_licenses) as license_list
                    ');
            }
            //Query
            $receive_volume = $Query->sortable()->paginate();

            return view('esurv.receive_volume.report', compact('receive_volume', 'filter'));
        }

        abort(403);

    }

}

<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\ReceiveQualityControl as receive_quality_control;
use App\Models\Esurv\ReceiveQualityControlLicense;
use App\Models\Besurv\HsCode;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;

use Illuminate\Http\Request;

use HP;

class ReceiveQualityControlController extends Controller
{
    private $attach_path; //ที่เก็บไฟล์แนบ

    public function __construct()
    {

        $this->attach_path = 'esurv_attach/inform_quality_control/';

        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $user = auth()->user();
        $model = str_slug('receive_quality_control','-');

        if($user->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_date_start'] = $request->get('filter_date_start', '');
            $filter['filter_date_end'] = $request->get('filter_date_end', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new receive_quality_control;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_created_by']!='') {//ผู้แจ้ง
                $Query = $Query->where('created_by', $filter['filter_created_by']);
            }

            if ($filter['filter_tb3_Tisno']!='') {//มาตรฐาน
                $Query = $Query->where('tb3_Tisno', $filter['filter_tb3_Tisno']);
            }

            if($filter['filter_date_start']!='' && $filter['filter_date_end']!=''){//ช่วงวันที่รับแจ้ง
                $Query = $Query->where('created_at', '>=', HP::convertDate($filter['filter_date_start']).' 00:00:00')
                               ->where('created_at', '<=', HP::convertDate($filter['filter_date_end']).' 23:59:59');
            }

            if ($filter['filter_department']!='') {
                $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
                $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
            }else{
                $subDepartments =  [];
            }

            if ($filter['filter_sub_department']!='') {
                $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
            }

            //query
            $receive_quality_control = $Query->where('state', '!=', 0)->orderby('id','desc')->sortable()->paginate($filter['perPage']);

            //สิทธิ์การตรวจตามกลุ่มงานย่อย
            $user_tis = $user->tis->pluck('tb3_Tisno');
     
            return view('esurv.receive_quality_control.index', compact('receive_quality_control', 'filter', 'user_tis', 'subDepartments'));
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
        $model = str_slug('receive_quality_control','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('esurv.receive_quality_control.create');
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
        $model = str_slug('receive_quality_control','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            receive_quality_control::create($requestData);
            return redirect('esurv/receive_quality_control')->with('flash_message', 'เพิ่ม receive_quality_control เรียบร้อยแล้ว');
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
        $model = str_slug('receive_quality_control','-');
        if(auth()->user()->can('view-'.$model)) {
      
            $user = auth()->user();
            $previousUrl = app('url')->previous();
            $receive_quality_control = receive_quality_control::findOrFail($id);
            $receive_quality_control->consider =    !empty($receive_quality_control->user_updated->FullName) ?  $receive_quality_control->user_updated->FullName : $user->reg_fname.' '.$user->reg_lname;
            $receive_quality_control->inspector = is_null($receive_quality_control['inspector'])?'NULL':$receive_quality_control['inspector'];

            //ไฟล์แนบ
            $attachs = json_decode($receive_quality_control['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            //เลขที่ใบอนุญาต
            $inform_qc_licenses = ReceiveQualityControlLicense::where("inform_quality_control_id", $receive_quality_control->id)->pluck('tbl_licenseNo', 'id')->toArray();//ที่ถูกเลือก
            $own_licenses = HP::LicenseByTraderTis($receive_quality_control->created_by, $receive_quality_control->tb3_Tisno); //ทั้งหมดที่มี ตามมาตรฐาน

            $set_state = (!empty($receive_quality_control->state) && $receive_quality_control->state != 1) ? 'disabled' : false;
            return view('esurv.receive_quality_control.show', compact('receive_quality_control',
                                                            'own_licenses',
                                                            'inform_qc_licenses',
                                                            'attachs',
                                                            'attach_path',
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
        $model = str_slug('receive_quality_control','-');
        if(auth()->user()->can('edit-'.$model)) {

            $user = auth()->user();
            $previousUrl = app('url')->previous();
            $receive_quality_control = receive_quality_control::findOrFail($id);
            $receive_quality_control->consider =    !empty($receive_quality_control->user_updated->FullName) ?  $receive_quality_control->user_updated->FullName : $user->reg_fname.' '.$user->reg_lname;
            $receive_quality_control->inspector = is_null($receive_quality_control['inspector'])?'NULL':$receive_quality_control['inspector'];

            //ไฟล์แนบ
            $attachs = json_decode($receive_quality_control['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            //เลขที่ใบอนุญาต
            $inform_qc_licenses = ReceiveQualityControlLicense::where("inform_quality_control_id", $receive_quality_control->id)->pluck('tbl_licenseNo', 'id')->toArray();//ที่ถูกเลือก
            $own_licenses = HP::LicenseByTraderTis($receive_quality_control->created_by, $receive_quality_control->tb3_Tisno); //ทั้งหมดที่มี ตามมาตรฐาน

            $set_state = (!empty($receive_quality_control->state) && $receive_quality_control->state != 1) ? 'disabled' : false;

            return view('esurv.receive_quality_control.edit', compact('receive_quality_control',
                                                                      'own_licenses',
                                                                      'inform_qc_licenses',
                                                                      'attachs',
                                                                      'attach_path',
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
        $model = str_slug('receive_quality_control','-');
        if(auth()->user()->can('edit-'.$model)) {

            $this->validate($request, [
                 'state' => 'required'
            ]);

            $requestData = $request->all();
            $requestData['consider'] = auth()->user()->getKey();

            $receive_quality_control = receive_quality_control::findOrFail($id);
            $receive_quality_control->update($requestData);

            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('flash_message', 'แก้ไข receive_quality_control เรียบร้อยแล้ว!');
            }else{
                return redirect('esurv/receive_quality_control')->with('flash_message', 'แก้ไข receive_quality_control เรียบร้อยแล้ว!');
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
        $model = str_slug('receive_quality_control','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new receive_quality_control;
            receive_quality_control::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            receive_quality_control::destroy($id);
          }

          return redirect('esurv/receive_quality_control')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('receive_quality_control','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new receive_quality_control;
          receive_quality_control::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('esurv/receive_quality_control')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function report(Request $request)
     {
        $user = auth()->user();
        $model = str_slug('receive_change','-');

        if($user->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_department'] = $request->get('filter_department', '');

            $Query = new receive_quality_control;
            $Query = $Query->selectRaw('
                        COUNT(DISTINCT (created_by)) as list_name,
                        COUNT(DISTINCT (tb3_Tisno)) as list_tis,
                        COUNT(`esurv_inform_quality_controls`.id) as total
                    ');

            if ($filter['filter_department']!='') {
                $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
                $receive_quality_control_id = receive_quality_control::whereIn('tb3_Tisno', $tis_subdepartments)->pluck('id');
                // dd($receive_quality_control_id);
                $Sub_Query = ReceiveQualityControlLicense::selectRaw('DISTINCT(tbl_licenseNo)')->whereIn("inform_quality_control_id", $receive_quality_control_id)->pluck('tbl_licenseNo');
                // dd(count($Sub_Query));
                $cnt_sub_query = count($Sub_Query);
                $Query = $Query->selectRaw("$cnt_sub_query as license_list");
            } else{
                  $Query = $Query->selectRaw('
                (SELECT COUNT(DISTINCT (tbl_licenseNo)) FROM esurv_inform_quality_control_licenses) as license_list
                    ');
            }

            //query
            $receive_quality_control = $Query->where('state', '!=', 0)->sortable()->paginate();


            return view('esurv.receive_quality_control.report', compact('receive_quality_control', 'filter'));
        }
        abort(403);

    }


}

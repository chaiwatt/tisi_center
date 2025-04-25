<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\LicenseCancel as license_cancel;
use App\Models\Esurv\LicenseCancelLicense;

use Illuminate\Http\Request;

use HP;
use Storage;

class LicenseCancelController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'esurv_attach/license_cancel/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new license_cancel;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_tb3_Tisno']!='') {//มาตรฐาน
                $Query = $Query->where('tb3_Tisno', $filter['filter_tb3_Tisno']);
            }

            $license_cancel = $Query->sortable()->with('user_created')
                                                ->with('user_updated')
                                                ->paginate($filter['perPage']);

            $attach_path = $this->attach_path;

            return view('esurv.license_cancel.index', compact('license_cancel', 'filter', 'attach_path'));
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
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('add-'.$model)) {

            $licenses = [];

            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            return view('esurv.license_cancel.create', compact('licenses', 'attachs', 'attach_path'));

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
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'tb3_Tisno' => 'required',
        			'cancel_date' => 'required',
        			'reason_type' => 'required'
        		]);
            
            $requestData = $request->all();
            $requestData['cancel_no'] = $this->RunNumber();//เลขรัน
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['cancel_date'] = ($requestData['cancel_date']!='')?HP::convertDate($requestData['cancel_date']):null;//วันที่ยกเลิก

            //ไฟล์แนบ
            $attachs = [];
            if ($files = $request->file('attachs')) {

              foreach ($files as $key => $file) {

                //Upload File
                $storagePath = Storage::put($this->attach_path, $file);
                $storageName = basename($storagePath); // Extract the filename

                $attachs[] = ['file_name'=>$storageName,
                              'file_client_name'=>$file->getClientOriginalName(),
                              'file_note'=>$requestData['attach_notes'][$key]
                             ];
              }

            }

            $requestData['attach'] = json_encode($attachs);

            $license_cancel = license_cancel::create($requestData);

            $this->SaveLicense($license_cancel, $requestData, $request);//บันทึกข้อมูลใบอนุญาต

            return redirect('esurv/license_cancel')->with('flash_message', 'เพิ่ม license_cancel เรียบร้อยแล้ว');
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
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('view-'.$model)) {
            $license_cancel = license_cancel::findOrFail($id);
            return view('esurv.license_cancel.show', compact('license_cancel'));
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
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('edit-'.$model)) {

            $license_cancel = license_cancel::findOrFail($id);
            $license_cancel['cancel_date'] = HP::revertDate($license_cancel['cancel_date']);

            //เลขที่ใบอนุญาต
            $license_cancel_licenses = LicenseCancelLicense::where("license_cancel_id", $license_cancel->id)->pluck('tbl_licenseNo', 'id')->toArray();//ที่ถูกเลือก
            $licenses = HP::LicenseByTis($license_cancel->tb3_Tisno);//ทั้งหมดที่มี ตามมาตรฐาน

            //ไฟล์แนบ
            $attachs = json_decode($license_cancel['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            return view('esurv.license_cancel.edit', compact('license_cancel', 'license_cancel_licenses', 'licenses', 'attachs', 'attach_path'));

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
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'tb3_Tisno' => 'required',
        			'cancel_date' => 'required',
        			'reason_type' => 'required'
        		]);

            $license_cancel = license_cancel::findOrFail($id);

            if (empty($license_cancel)) {
                Flash::error('ไม่พบข้อมูลการยกเลิกใบอนุญาต');
                return redirect(route('esurv/license_cancel'));
            }

            $input = $request->all();
            $input['updated_by'] =  auth()->user()->getKey(); //user update
            $input['cancel_date'] = ($input['cancel_date']!='')?HP::convertDate($input['cancel_date']):null; //วันที่ยกเลิก

            //ข้อมูลไฟล์แนบ
            $attachs = array_values((array)json_decode($license_cancel->attach));

            //ไฟล์แนบ ที่ถูกกดลบ
            foreach ($attachs as $key => $attach) {

              if(in_array($attach->file_name, $input['attach_filenames'])===false){//ถ้าไม่มีไฟล์เดิมกลับมา
                unset($attachs[$key]);
                Storage::delete($this->attach_path.$attach->file_name);
              }
            }

            //ไฟล์แนบ ข้อความที่แก้ไข
            foreach ($attachs as $key => $attach) {
              $search_key = array_search($attach->file_name, $input['attach_filenames']);
              if($search_key!==false){
                $attach->file_note = $input['attach_notes'][$search_key];
              }
            }

            //ไฟล์แนบ เพิ่มเติม
            if ($files = $request->file('attachs')) {

              $dir = $this->attach_path;
              foreach ($files as $key => $file) {

                //Upload File
                $storagePath = Storage::put($this->attach_path, $file);
                $newFile = basename($storagePath); // Extract the filename

                if($input['attach_filenames'][$key]!=''){//ถ้าเป็นแถวเดิมที่มีในฐานข้อมูลอยู่แล้ว

                  //วนลูปค้นหาไฟล์เดิม
                  foreach ($attachs as $key2 => $attach) {

                    if($attach->file_name == $input['attach_filenames'][$key]){//ถ้าเจอแถวที่ตรงกันแล้ว

                      Storage::delete($this->attach_path.$attach->file_name);//ลบไฟล์เก่า

                      $attach->file_name = $newFile;//แก้ไขชื่อไฟล์ใน object
                      $attach->file_client_name = $file->getClientOriginalName();//แก้ไขชื่อไฟล์ของผู้ใช้ใน object

                      break;
                    }
                  }

                }else{//แถวที่เพิ่มมาใหม่

                  $attachs[] = ['file_name'=>$newFile,
                                'file_client_name'=>$file->getClientOriginalName(),
                                'file_note'=>$input['attach_notes'][$key]
                               ];
                }

              }

            }

            $input['attach'] = json_encode($attachs);

            $license_cancel->update($input);

            $this->SaveLicense($license_cancel, $input);//บันทึกข้อมูลใบอนุญาต

            return redirect('esurv/license_cancel')->with('flash_message', 'แก้ไข license_cancel เรียบร้อยแล้ว!');
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
        $model = str_slug('license_cancel','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new license_cancel;
            license_cancel::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            license_cancel::destroy($id);
          }

          return redirect('esurv/license_cancel')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('license_cancel','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new license_cancel;
          license_cancel::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('esurv/license_cancel')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save License
    */
    private function SaveLicense($license_cancel, $requestData){

        LicenseCancelLicense::where('license_cancel_id', $license_cancel->id)->delete();

        /* บันทึกข้อมูลใบอนุญาต */
        foreach ((array)@$requestData['tbl_licenseNo'] as $tbl_licenseNo) {
          $input_license = [];
          $input_license['tbl_licenseNo'] = $tbl_licenseNo;
          $input_license['license_cancel_id'] = $license_cancel->id;
          LicenseCancelLicense::create($input_license);
        }

    }

    /*
      **** Run เลขยกเลิก
    */
    private function RunNumber(){

      $year = date('Y')+543;

      $license_cancel = license_cancel::where('cancel_no', 'LIKE', "%-$year%")->orderBy('id', 'desc')->first();

      if(is_null($license_cancel)){
        $result = '001-'.$year;
      }else{
        $last_number = (int)$license_cancel->cancel_no;
        $result = str_pad(($last_number+1), 3, '0', STR_PAD_LEFT).'-'.$year;
      }

      return $result;

    }

}

<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\FollowUp as follow_up;
use App\Models\Tis\Standard;
use Illuminate\Http\Request;

use App\Models\Basic\SubDepartment;
use App\Models\Basic\TisiLicense;
use App\Models\Besurv\TisSubDepartment;
use App\Models\Esurv\FollowUpLicense;
use App\Models\Sso\User AS SSO_User;
use HP;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use stdClass;

class FollowUpController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'esurv_attach/follow_up/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('follow_up','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_check_status'] = $request->get('filter_check_status', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
            $filter['filter_reference_number'] = $request->get('filter_reference_number', '');
            $filter['filter_trader_autonumber'] = $request->get('filter_trader_autonumber', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $user_key = auth()->user()->getKey();

            $Query = new follow_up;

            if ($user_key!='') {
                $Query = $Query->whereRaw('(CASE
                                            WHEN (check_status="0" OR check_status="5") THEN created_by = "'.$user_key.'"
                                            ELSE created_by != ""
                                          END)');
            }

            // if ($user_key!='') {
            //     $Query = $Query->where('created_by', $user_key)->where('check_status', '0')->orWhere('check_status', '5');
            //  }

            if ($filter['filter_check_status']!='') {
                $Query = $Query->where('check_status', $filter['filter_check_status']);
            }

            if ($filter['filter_tb3_Tisno']!='') {//มาตรฐาน
                $Query = $Query->where('tb3_Tisno', $filter['filter_tb3_Tisno']);
            }

            if ($filter['filter_reference_number']!='') {  //เลขที่เอกสาร
                $Query =  $Query->where('reference_number','LIKE','%'.$filter['filter_reference_number'].'%');
            }

            if ($filter['filter_trader_autonumber']!='') {  //ผู้รับใบอนุญาต
                $Query = $Query->where('trader_autonumber', $filter['filter_trader_autonumber']);
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

            $follow_up = $Query->sortable()->orderBy('id','DESC')->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('esurv.follow_up.index', compact('follow_up', 'filter', 'subDepartments'));
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
        $model = str_slug('follow_up','-');
        if(auth()->user()->can('add-'.$model)) {
            $previousUrl = app('url')->previous();

            $follow_up = (object)['id'=>'0'];

            $license_by_trader = [];
            $tb3_Tisno = [];
            $refer_doc = [];

            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            $single_attach = (object)['file_name'=>'', 'file_client_name'=>''];

            return view('esurv.follow_up.create', compact('follow_up',
                                                        'license_by_trader',
                                                        'tb3_Tisno',
                                                        'refer_doc',
                                                        'attachs',
                                                        'attach_path',
                                                        'single_attach',
                                                        'previousUrl'));

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

        $model = str_slug('follow_up','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'trader_autonumber' => 'required',
        			'tb3_Tisno' => 'required',
        			// 'factory_name' => 'required',
        			// 'factory_address' => 'required',
        			'check_date' => 'required',
        			'follow_type' => 'required',
        			'quality_control' => 'required',
        			'test_tool_product' => 'required',
        			'show_mark_product' => 'required',
        			'summarize' => 'required',
        			'inspection_result' => 'required',
        			'sampling' => 'required'
            ]);

            if ($request->has('show_mark')) {
                $request->merge(['show_mark' => 1]);
            } else {
                $request->merge(['show_mark' => 0]);
            }

            if ($request->has('show_manufacturer')) {
                $request->merge(['show_manufacturer' => 1]);
            } else {
                $request->merge(['show_manufacturer' => 0]);
            }
             if(isset($request->check_status)){
                $data  = new  stdClass;
                $data->check_status =  !empty($request->check_status) ? (string)$request->check_status : 0 ;
                $data->created_by = auth()->user()->getKey() ;
                $data->date =  date('Y-m-d') ;
                $data->conclude_result =  null ;
                $data->conclude_result_remark =  null ;
                $history[] = $data;
                $status_history = json_encode($history);
              }
            $requestData = $request->all();
			
			$tisi_license = TisiLicense::where('tbl_licenseStatus', '1')->where('tbl_taxpayer', $requestData['trader_autonumber'])->latest('Autono')->first();

			$requestData['tradename'] = !is_null($tisi_license) ? $tisi_license->tbl_tradeName : null ;
            $requestData['status_history'] = !empty($status_history)?$status_history:null; // ประวัติสถานะ
            $requestData['person'] = !empty($requestData['person'])?json_encode($requestData['person']):null;
            $requestData['staff'] = !empty($requestData['staff'])?json_encode($requestData['staff']):null;
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['check_date'] = ($requestData['check_date']!='')?HP::convertDate($requestData['check_date']):null;//วันที่ตรวจ
            $requestData['inspection_result_date_start'] = ($requestData['inspection_result_date_start']!='')?HP::convertDate($requestData['inspection_result_date_start']):null;//วันที่ตรวจสอบผลิตภัณฑ์สำเร็จรูปเริ่ม
            $requestData['inspection_result_date_end'] = ($requestData['inspection_result_date_end']!='')?HP::convertDate($requestData['inspection_result_date_end']):null;//วันที่ตรวจสอบผลิตภัณฑ์สำเร็จรูปสิ้นสุด
            $requestData['show_manufacturer_sub'] = !empty($requestData['show_manufacturer_sub'])?implode(",",$request->show_manufacturer_sub):null;
            $requestData['sub_id'] = auth()->user()->reg_subdepart;

               //ไฟล์แนบ
               $attachs = [];
               if ($files = $request->file('attachs')) {
                  foreach ($files as $key => $file) {
                   //Upload File
                    $storagePath = Storage::put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename

                    $attachs[] =  ['file_name'=>$storageName,
                                  'file_client_name'=>$file->getClientOriginalName(),
                                  'file_note'=>$requestData['attach_notes'][$key]
                                  ];
                  }

               }

               $requestData['attach'] = json_encode($attachs);


                if ($single_file = $request->file('show_manufacturer_image')) {
                    $storagePath = Storage::put($this->attach_path, $single_file);
                    $storageName = basename($storagePath); // Extract the filename

                    $single_attach =  ['file_name'=>$storageName,
                                        'file_client_name'=>$single_file->getClientOriginalName()
                                        ];
                    $requestData['show_manufacturer_image'] = json_encode($single_attach);
                } else {
                    $requestData['show_manufacturer_image'] = null;
                }

            $follow_up = follow_up::create($requestData);
            if(array_key_exists('tbl_licenseNo', $requestData)){
                foreach ($requestData['tbl_licenseNo'] as $item) {
                    $follow_up_license = new FollowUpLicense();
                    $follow_up_license->id_follow_up = $follow_up->id;
                    $follow_up_license->license = $item;
                    $follow_up_license->save();
                }
            }

            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('flash_message', 'เพิ่ม follow_up เรียบร้อยแล้ว!');
            }else{
                return redirect('esurv/follow_up')->with('flash_message', 'เพิ่ม follow_up เรียบร้อยแล้ว!');
            }
        }
        abort(403);
    }

    public function storeFile($files, $name = null)
    {
        $path = $this->attach_path;
        if ($files) {
            //$destinationPath = storage_path($path);
            $destinationPath = Storage::disk()->getAdapter()->getPathPrefix().$path;
            $fileClientOriginal = $files->getClientOriginalName();
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName = ($name ?? $filename).'-'.str_random(2).time() . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $fullFileName);
            $file_certificate_toDB = $fullFileName;

            return $file_certificate_toDB;
        }
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
        $model = str_slug('follow_up','-');
        if(auth()->user()->can('view-'.$model)) {
            $previousUrl = app('url')->previous();

            $follow_up = follow_up::findOrFail($id);
            $q_data = DB::table('tb4_tisilicense');
            $id_data = $q_data->where('tbl_taxpayer', $follow_up->trader_autonumber)->groupBy('tbl_tisiNo')->pluck('tbl_tisiNo');
            if(!is_null($id_data) &&!is_null($follow_up) ){
                $data_q = DB::table('tb3_tis')->select('tb3_Tisno','tb3_TisThainame');
                $tb3_Tisno = $data_q->whereIn('tb3_Tisno', $id_data)
                                    ->where('tb3_Tisno',$follow_up->tb3_Tisno)
                                    ->first();
                $follow_up->tb3_TisThainame = $tb3_Tisno->tb3_TisThainame;
            }
            $follow_up['person'] = json_decode($follow_up['person']);
            $follow_up['staff'] = json_decode($follow_up['staff']);

            $address = '';
            if(!is_null($follow_up)){
                if(!is_null($follow_up->factory_address_no)){
                    $address .=  $follow_up->factory_address_no;
                }
                if(!is_null($follow_up->factory_address_no)){
                    $address .=  ' นิคมอุตสาหกรรม:'.$follow_up->factory_address_industrial_estate;
                }
                if(!is_null($follow_up->factory_address_alley)){
                    $address .=  ' ตรอก/ซอย:'.$follow_up->factory_address_alley;
                }
                if(!is_null($follow_up->factory_address_road)){
                    $address .=  ' ถนน:'.$follow_up->factory_address_road;
                }
                if(!is_null($follow_up->factory_address_village_no)){
                    $address .=  ' หมู่ที่:'.$follow_up->factory_address_village_no;
                }
                if(!is_null($follow_up->basic_rovince)){
                    $address .=  ' จังหวัด:'.!empty($follow_up->basic_rovince->PROVINCE_NAME) ? $follow_up->basic_rovince->PROVINCE_NAME : "";
                }
                if(!is_null($follow_up->basic_amphur)){
                    $address .=  ' อำเภอ/เขต:'.!empty($follow_up->basic_amphur->AMPHUR_NAME) ? $follow_up->basic_amphur->AMPHUR_NAME : "";
                }

                if(!is_null($follow_up->basic_district)){
                    $address .=  ' ตรอก/ซอย:'.!empty($follow_up->basic_district->DISTRICT_NAME) ? $follow_up->basic_district->DISTRICT_NAME : "";
                }
                if(!is_null($follow_up->warehouse_address_zip_code)){
                    $address .=  ' รหัสไปรษณีย์:'.$follow_up->warehouse_address_zip_code;
                }
                if(!is_null($follow_up->warehouse_tel)){
                    $address .=  ' โทรศัพท์:'.$follow_up->warehouse_tel;
                }
                if(!is_null($follow_up->warehouse_fax)){
                    $address .=  ' โทรสาร:'.$follow_up->warehouse_fax;
                }
                if(!is_null($follow_up->warehouse_latitude)){
                    $address .=  ' พิกัดที่ตั้ง (ละติจูด):'.$follow_up->warehouse_latitude;
                }
                if(!is_null($follow_up->warehouse_longitude)){
                    $address .=  ' พิกัดที่ตั้ง (ลองจิจูด):'.$follow_up->warehouse_longitude;
                }
            }
            $follow_up->address = $address;
             //ไฟล์แนบ
            $attachs = json_decode($follow_up['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;
            return view('esurv.follow_up.show', compact('follow_up', 'attachs','attach_path','previousUrl'));

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
        $model = str_slug('follow_up','-');
        if(auth()->user()->can('edit-'.$model)) {
            $previousUrl = app('url')->previous();
            $follow_up = follow_up::findOrFail($id);
            $follow_up['check_date'] = !empty($follow_up['check_date'])?HP::revertDate($follow_up['check_date']):null;
            $follow_up['inspection_result_date_start'] = !empty($follow_up['inspection_result_date_start'])?HP::revertDate($follow_up['inspection_result_date_start']):null;
            $follow_up['inspection_result_date_end'] = !empty($follow_up['inspection_result_date_start'])?HP::revertDate($follow_up['inspection_result_date_end']):null;

            $follow_up['person'] = json_decode($follow_up['person']);
            $follow_up['staff'] = json_decode($follow_up['staff']);
            $follow_up['show_manufacturer_sub'] =  !empty($follow_up->show_manufacturer_sub) ? explode(",",$follow_up->show_manufacturer_sub):null ;


            $license_by_trader = json_decode(HP::LicenseByTraderTis2($follow_up['trader_autonumber'], $follow_up['tb3_Tisno']));

            $follow_up_license = FollowUpLicense::where('id_follow_up', $follow_up->id)->get();

            $arr_test = HP::getArrayFormSecondLevel($follow_up_license->toArray(),'license');

            $q_data = DB::table('tb4_tisilicense');
            $id_data = $q_data->where('tbl_taxpayer', $follow_up->trader_autonumber)->groupBy('tbl_tisiNo')->pluck('tbl_tisiNo');
            $data_q = DB::table('tb3_tis')->select('tb3_Tisno','tb3_TisThainame');
            $tb3_Tisno = $data_q->whereIn('tb3_Tisno', $id_data)->pluck('tb3_TisThainame','tb3_Tisno');
            $id_data2 = $q_data->where('tbl_taxpayer', $follow_up->trader_autonumber)->groupBy('tbl_tradeName')->pluck('tbl_tradeName');
            $data_q2 = DB::table('save_example')->select('id','no');
            $refer_doc = $data_q2->whereIn('licensee', $id_data2)->pluck('no','id');

            //ไฟล์แนบ
            $attachs = json_decode($follow_up['attach']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            $single_attach = json_decode($follow_up['show_manufacturer_image']);

            $single_attach = !is_null($single_attach)?$single_attach:(object)['file_name'=>'', 'file_client_name'=>''];

            // dd($single_attach);
            $people = array("Peter", "Joe", "Glenn", "Cleveland");
            // dd($follow_up);
            // return  $follow_up['show_manufacturer_sub'] ;

            return view('esurv.follow_up.edit', compact('follow_up',
                                                        'license_by_trader',
                                                        'arr_test',
                                                        'tb3_Tisno',
                                                        'refer_doc',
                                                        'attachs',
                                                        'attach_path',
                                                        'single_attach',
                                                        'previousUrl'));

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
        // return  $request;
        $model = str_slug('follow_up','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'trader_autonumber' => 'required',
        			'tb3_Tisno' => 'required',
        			// 'factory_name' => 'required',
        			// 'factory_address' => 'required',
        			// 'check_date' => 'required',
        			// 'follow_type' => 'required',
        			// 'quality_control' => 'required',
        			// 'test_tool_product' => 'required',
        			// 'show_mark_product' => 'required',
        			// 'summarize' => 'required',
        			// 'inspection_result' => 'required',
        			// 'sampling' => 'required'
            ]);

            if ($request->has('show_mark')) {
                $request->merge(['show_mark' => 1]);
            } else {
                $request->merge(['show_mark' => 0]);
            }

            if ($request->has('show_manufacturer')) {
                $request->merge(['show_manufacturer' => 1]);
            } else {
                $request->merge(['show_manufacturer' => 0]);
            }

            $follow_up = follow_up::findOrFail($id);
            $requestData = $request->all();

            if(isset($request->check_status)){

                if($request->check_status == 0 || $request->check_status == 1 || $request->check_status == 5){  //เจ้าหน้าที่

                    //ข้อมูลไฟล์แนบ
                    $attachs = array_values((array)json_decode($follow_up->attach));

                    //ไฟล์แนบ ที่ถูกกดลบ
                    foreach ($attachs as $key => $attach) {
                        if(in_array($attach->file_name, $requestData['attach_filenames'])===false){//ถ้าไม่มีไฟล์เดิมกลับมา
                            unset($attachs[$key]);
                            Storage::delete($this->attach_path.$attach->file_name);
                        }
                    }

                    //ไฟล์แนบ ข้อความที่แก้ไข
                    foreach ($attachs as $key => $attach) {
                        $search_key = array_search($attach->file_name, $requestData['attach_filenames']);
                        if($search_key!==false){
                            $attach->file_note = $requestData['attach_notes'][$search_key];
                        }
                    }

                    //ไฟล์แนบ เพิ่มเติม
                    if ($files = $request->file('attachs')) {

                        $dir = $this->attach_path;
                        foreach ($files as $key => $file) {

                            //Upload File
                            $storagePath = Storage::put($this->attach_path, $file);
                            $newFile = basename($storagePath); // Extract the filename

                            if($requestData['attach_filenames'][$key]!=''){//ถ้าเป็นแถวเดิมที่มีในฐานข้อมูลอยู่แล้ว

                            //วนลูปค้นหาไฟล์เดิม
                            foreach ($attachs as $key2 => $attach) {

                                if($attach->file_name == $requestData['attach_filenames'][$key]){//ถ้าเจอแถวที่ตรงกันแล้ว

                                Storage::delete($this->attach_path.$attach->file_name);//ลบไฟล์เก่า

                                $attach->file_name = $newFile;//แก้ไขชื่อไฟล์ใน object
                                $attach->file_client_name = $file->getClientOriginalName();//แก้ไขชื่อไฟล์ของผู้ใช้ใน object

                                break;
                                }
                            }

                            }else{//แถวที่เพิ่มมาใหม่

                            $attachs[] = ['file_name'=>$newFile,
                                            'file_client_name'=>$file->getClientOriginalName(),
                                            'file_note'=>$requestData['attach_notes'][$key]
                                        ];
                            }

                        }

                    }

                    $requestData['attach'] = json_encode($attachs);

                    if ($single_file = $request->file('show_manufacturer_image')) {
                        //ข้อมูลไฟล์แนบ
                        $for_del = json_decode($follow_up->show_manufacturer_image);
                        if($for_del){
                            Storage::delete($this->attach_path.$for_del->file_name);//ลบไฟล์เก่า
                        }

                        $storagePath = Storage::put($this->attach_path, $single_file);
                        $storageName = basename($storagePath); // Extract the filename

                        $single_attach =  ['file_name'=>$storageName,
                                            'file_client_name'=>$single_file->getClientOriginalName()
                                            ];
                    $requestData['show_manufacturer_image'] = json_encode($single_attach);

                    } else {
                    $requestData['show_manufacturer_image'] = $follow_up->show_manufacturer_image;

                    }

                    $data  = new  stdClass;
                    $data->check_status =  !empty($request->check_status) ? (string)$request->check_status : 0 ;
                    $data->created_by = auth()->user()->getKey() ;
                    $data->conclude_result_remark =  null ;
                    $data->conclude_result = null;
                    $data->date =  date('Y-m-d') ;
                        if(!is_null($follow_up->status_history)){
                            $data_list =  json_decode($follow_up->status_history);
                            foreach($data_list as $itme){
                                $list  = new  stdClass;
                                $list->check_status = (string)$itme->check_status ;
                                $list->created_by =  (string)$itme->created_by ;
                                $list->conclude_result =  @$itme->conclude_result;
                                $list->conclude_result_remark =   @$itme->conclude_result_remark;
                                $list->date = (string)$itme->date ;
                                $history[] = $list ;
                            }
                            $history[] = $data ;
                            $status_history = json_encode($history);
                        }else{
                            $history[] = $data ;
                            $status_history = json_encode($history);
                        }

                  } // จบ เจ้าหน้าที่

                  if($request->check_status == 2){  //ผก.รับรองแล้ว

                    if($request->conclude_result == 'เห็นชอบและโปรดดำเนินการต่อไป'){
                    $requestData['check_status'] = 2;
                    }else{
                    $requestData['check_status'] = 5;
                    }

                    $follow_up->check_status = $requestData['check_status'];
                    $follow_up->conclude_result = $requestData['conclude_result'];
                    $follow_up->conclude_result_remark = $requestData['conclude_result_remark'];
                    $follow_up->assessor = $requestData['assessor'];
                    $follow_up->assessment_date = $requestData['assessment_date'];
                    $follow_up->save();

                    $data  = new  stdClass;
                    $data->check_status =  $requestData['check_status'];
                    $data->created_by = auth()->user()->getKey() ;
                    $data->conclude_result = @$request->conclude_result;
                    $data->conclude_result_remark =  @$request->conclude_result_remark ;
                    $data->date =  date('Y-m-d');

                        if(!is_null($follow_up->status_history)){
                            $data_list =  json_decode($follow_up->status_history);
                            foreach($data_list as $itme){
                                $list  = new  stdClass;
                                $list->check_status = (string)$itme->check_status;
                                $list->created_by =  (string)$itme->created_by;
                                $list->conclude_result =  @$itme->conclude_result;
                                $list->conclude_result_remark =   @$itme->conclude_result_remark;
                                $list->date = (string)$itme->date ;
                                $history[] = $list;
                            }
                            $history[] = $data;
                            $status_history = json_encode($history);
                        }else{
                            $history[] = $data ;
                            $status_history = json_encode($history);
                        }

                  } // จบ ผก.รับรองแล้ว

                }
				
					$tisi_license = TisiLicense::where('tbl_licenseStatus', '1')->where('tbl_taxpayer', $requestData['trader_autonumber'])->latest('Autono')->first();

					$requestData['tradename'] = !is_null($tisi_license) ? $tisi_license->tbl_tradeName : null ;
                    $requestData['status_history'] = !empty($status_history)?$status_history:null; // ประวัติสถานะ
                    $requestData['person'] = !empty($requestData['person'])?json_encode($requestData['person']):null;
                    $requestData['staff'] = !empty($requestData['staff'])?json_encode($requestData['staff']):null;
                    $requestData['updated_by'] = auth()->user()->getKey(); //user update
                    $requestData['check_date'] = !empty($requestData['check_date'])?HP::convertDate($requestData['check_date']):@$follow_up->check_date;//วันที่ตรวจ
                    $requestData['inspection_result_date_start'] = !empty($requestData['inspection_result_date_start'])?HP::convertDate($requestData['inspection_result_date_start']):null;//วันที่ตรวจสอบผลิตภัณฑ์สำเร็จรูปเริ่ม
                    $requestData['inspection_result_date_end'] = !empty($requestData['inspection_result_date_end'])?HP::convertDate($requestData['inspection_result_date_end']):null;//วันที่ตรวจสอบผลิตภัณฑ์สำเร็จรูปสิ้นสุด
                    $requestData['show_manufacturer_sub'] = !empty($requestData['show_manufacturer_sub'])?implode(",",$request->show_manufacturer_sub):null;

                    $follow_up->update($requestData);

                        if(isset($requestData['tbl_licenseNo'])){
                             FollowUpLicense::where('id_follow_up', $id)->delete();
                            foreach ($requestData['tbl_licenseNo'] as $item) {
                                $follow_up_license = new FollowUpLicense();
                                $follow_up_license->id_follow_up = $id;
                                $follow_up_license->license = $item;
                                $follow_up_license->save();
                            }
                        }

            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('flash_message', 'แก้ไข follow_up เรียบร้อยแล้ว!');
            }else{
                return redirect('esurv/follow_up')->with('flash_message', 'แก้ไข follow_up เรียบร้อยแล้ว!');
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
        $model = str_slug('follow_up','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new follow_up;
            follow_up::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            follow_up::destroy($id);
          }

          return redirect('esurv/follow_up')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('follow_up','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new follow_up;
          follow_up::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('esurv/follow_up')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function add_filter_license(Request $request)
    {
        $q_data = DB::table('tb4_tisilicense');
        $id_data = $q_data->where('tbl_taxpayer', $request->get('tb3_Tisno'))->groupBy('tbl_tisiNo')->pluck('tbl_tisiNo');
        $data_q = DB::table('tb3_tis')->select('tb3_Tisno','tb3_TisThainame');
        $data = $data_q->whereIn('tb3_Tisno', $id_data)->get();

        $id_data2 = $q_data->where('tbl_taxpayer', $request->get('tb3_Tisno'))->groupBy('tbl_tradeName')->pluck('tbl_tradeName');
        $data_q2 = DB::table('save_example')->select('id','no');
        $data2 = $data_q2->whereIn('licensee', $id_data2)->get();

        return response()->json([
            "status" => "success",
            'data' => $data,
            'data2' => $data2,
        ]);
    }


    public function add_factory_address_province(Request $request)
    {
        $data = DB::table('amphur')->whereNull('state')->where('PROVINCE_ID', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

    public function add_factory_address_tambon(Request $request)
    {
        $data = DB::table('district')->whereNull('state')->where('AMPHUR_ID', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

    public function add_warehouse_address_province(Request $request)
    {
        $data = DB::table('amphur')->whereNull('state')->where('PROVINCE_ID', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

    public function add_warehouse_address_tambon(Request $request)
    {
        $data = DB::table('district')->whereNull('state')->where('AMPHUR_ID', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }
      public function get_trader_autono(Request $request)
    {
        $data = SSO_User::where('tax_number', $request->get('tax_number'))->value('id');
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

    public function data_sub_department($did_id)
    {
          $data = SubDepartment::where('did', $did_id)->pluck('sub_departname','sub_id');
        return response()->json($data);
    }

       //เลือกลบแบบทั้งหมดได้
       public function delete(Request $request)
       {
         $id_array = $request->input('id');
         $data = follow_up::whereIn('id', $id_array);
         if($data->delete())
         {
             echo 'Data Deleted';
         }

       }
}

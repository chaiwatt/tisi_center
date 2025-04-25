<?php

namespace App\Http\Controllers\Csurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\control_check;
use App\Models\Csurv\ControlCheck;
use App\Models\Csurv\ControlCheckFile;
use App\Models\Csurv\ControlCheckPermission;
use App\Models\Csurv\ControlFreeze;
use App\Models\Csurv\Tis4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use stdClass;
use HP;
class ControlCheckController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/control_check/';
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
        $filter['filter_operation'] = $request->get('filter_operation', '');
        $filter['filter_status'] = $request->get('filter_status', '');
        $filter['filter_start_month'] = $request->get('filter_start_month', '');
        $filter['filter_start_year'] = $request->get('filter_start_year', '');
        $filter['filter_end_month'] = $request->get('filter_end_month', '');
        $filter['filter_end_year'] = $request->get('filter_end_year', '');
        $filter['filter_title']       = $request->get('filter_title', '');

        $Query = new ControlCheck;

        // $operation = ['ส่งให้กองกฏหมายดำเนินการ', 'ไม่ดำเนินการใดๆ'];
        // $Query = $Query->wherein('operation',[1,2]);

        if ($filter['filter_tb3_Tisno'] != '') {
            $id_autono = DB::table('tb4_tisilicense')->where('tbl_tisiNo', $filter['filter_tb3_Tisno'])->groupBy('tbl_taxpayer')->first();
            $Query = $Query->where('tradeName', $id_autono->Autono);
        }
        if ($filter['filter_operation'] != '') {
            $Query = $Query->where('operation', $filter['filter_operation']);
        }
        if ($filter['filter_status'] != '') {
            $Query = $Query->where('status', $filter['filter_status']);
        }
        if ($filter['filter_start_month'] != '') {
            $Query = $Query->where('created_at', '>=', $filter['filter_start_year'] . '-' . $filter['filter_start_month'] . '-01' . ' 00:00:00');
        }

        if ($filter['filter_end_month'] != '') {
            $Query = $Query->where('created_at', '<=', $filter['filter_end_year'] . '-' . $filter['filter_end_month'] . '-01' . ' 00:00:00');
        }

        if($filter['filter_title']!=''){
            $Autonos = Tis4::where('tbl_tradeName', 'like', '%'.$filter['filter_title'].'%')->pluck('tbl_taxpayer');
            $Query = $Query->whereIn('tradeName', $Autonos);
        }

        $control_check = $Query->sortable()->orderby('id','desc')->paginate($filter['perPage']);

        // dd($control_check);

        $temp_num = $control_check->firstItem();

        return view('csurv.control_check.index', compact('control_check', 'filter', 'temp_num'));
    }

    public function create()
    {
        $previousUrl = app('url')->previous();
        return view('csurv.control_check.create',['previousUrl'=>$previousUrl]);
    }

    public function show($id)
    {
        return view('csurv.control_check.show');
    }

    public function edit($id)
    {
        $data = ControlCheck::find($id);
        $previousUrl = app('url')->previous();
        $data_permission = ControlCheckPermission::query()->where('id_check', $id)->get();
        if ($data->officer_name != null) {
            $officer_name = explode(',', $data->officer_name);
        } else {
            $officer_name = null;
        }
        if ($data->ever_warned != null) {
            $ever_warned = explode(',', $data->ever_warned);
        } else {
            $ever_warned = [];
        }
        if ($data->this_operation != null) {
            $this_operation = explode(',', $data->this_operation);
        } else {
            $this_operation = [];
        }
        // if () {
        //     return 'ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ การทำ/การจำหน่าย ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน';
        // } else {
        //     return '55';
        // }
        $data_file = ControlCheckFile::query()->where('id_check', $id)->get();
        $data_file_check = ControlCheckFile::query()->where('id_check', $id)->first();
        $attach_path = $this->attach_path; //path ไฟล์แนบ
        return view('csurv.control_check.edit', ['data' => $data,
                                                'data_permission' => $data_permission,
                                                'officer_name' => $officer_name,
                                                'data_file' => $data_file,
                                                'data_file_check' => $data_file_check,
                                                'ever_warned' => $ever_warned,
                                                'this_operation' => $this_operation,
                                                'previousUrl' => $previousUrl,
                                                'attach_path' => $attach_path
                                                ]);
    }

    public function update(Request $request)
    {
        return redirect('control_check/control_check');
    }

    public function save_data(Request $request)
    {

        if ($request->get('tradeName') == '-เลือกผู้รับใบอนูญาต-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกผู้รับใบอนูญาต!"
            ]);
        }
        if ($request->get('tbl_tisiNo') == '-เลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์!"
            ]);
        }
        if ($request->get('operation') == '') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกการดำเนินการ!"
            ]);
        }
        if(isset($request->status)){
            $data  = new  stdClass;
            $data->status =  !empty($request->status) ? (string)$request->status : 0 ;
            $data->created_by = auth()->user()->getKey() ;
            $data->date =  date('Y-m-d') ;
            $data->poao_approve =  null ;
            $data->poao_approve_text =  null ;
            $history[] = $data;
            $status_history = json_encode($history);
          }

        if(isset($request->ever_warned)){
            $warned = array();
            foreach($request->ever_warned as $itme){
               $warned[] = $itme;
            }
            $rwarned = implode(',', $warned);
            $ever_warned = $rwarned;
        }else{
            $ever_warned = null;
        }
        if(isset($request->this_operation)){
            $operation = array();
            foreach($request->this_operation as $itme){
               $operation[] = $itme;
            }
            $roperation = implode(',', $operation);
            $this_operation = $roperation;
        }else{
            $this_operation = null;
        }
        $data = new ControlCheck([
            'tradeName' => $request->get('tradeName'),
            'tbl_tisiNo' => $request->get('tbl_tisiNo'),
            'located_check' => $request->get('located_check'),
            'located_keep' => $request->get('located_keep'),
            'located_sell' => $request->get('located_sell'),
            'address_no' => $request->get('address_no'),
            'address_industrial_estate' => $request->get('address_industrial_estate'),
            'address_alley' => $request->get('address_alley'),
            'address_road' => $request->get('address_road'),
            'address_village_no' => $request->get('address_village_no'),
            'address_district' => $request->get('address_district'),
            'address_amphoe' => $request->get('address_amphoe'),
            'address_province' => $request->get('address_province'),
            'address_zip_code' => $request->get('address_zip_code'),
            'tel' => $request->get('tel'),
            'fax' => $request->get('fax'),
            'latitude' => $request->get('latitude'),
            'Longitude' => $request->get('Longitude'),
            'checking_date' => $request->get('checking_date'),
            'checking_time' => $request->get('checking_time'),
            'police_station' => $request->get('police_station'),
            'this_checking' => $request->get('this_checking'),
            'location_check' => $request->get('location_check'),
            'remake_location_check' => $request->remake_location_check1,
            'remake_location_check2' => $request->remake_location_check2,
            'production_site' => $request->get('production_site'),
            'production_site_value' => $request->get('production_site_value'),
            'product_not_legally' => $request->get('product_not_legally'),
            'location_keep' => $request->get('location_keep'),
            'product_sell' => $request->get('product_sell'),
            'num_of_hold' => $request->get('num_of_hold'),
            'num_of_freeze' => $request->get('num_of_freeze'),
            'num_of_hold_value' => $request->get('num_of_hold_value'),
            'num_of_freeze_value' => $request->get('num_of_freeze_value'),
            'reference_num' => $request->get('reference_num'),
            'detail_location_offense' => $request->get('detail_location_offense'),
            'detail_product_not_standard' => $request->get('detail_product_not_standard'),
            'premise' => $request->get('premise'),
            'seller_name' => $request->get('seller_name'),
            'seller_address' => $request->get('seller_address'),
            'officer_check' => $request->get('officer_check'),
            'num_of_time' => $request->get('num_of_time'),
            'last_time' => $request->get('last_time'),
            'ever_warning' => $request->get('ever_warning'),
            'ever_warned' => $ever_warned,
            'this_operation' => $this_operation,
            'more_notes' => $request->get('more_notes'),
            'operation' => $request->get('operation'),
            'status' => $request->get('status'),
            'status_history' =>  !empty($status_history) ? $status_history : null ,
            'status_check' => '-',
            'date_now' => $request->get('date_now'),
            'check_officer' => $request->get('check_officer'),
            'police_station_value' => $request->get('police_station_value'),
            'sub_id' => auth()->user()->reg_subdepart,
        ]);
        $check_data = DB::table('control_check')->first();
        if ($check_data != null) {
            $data_sql = DB::table('control_check')->orderByDesc('id')->first();
            $ex1_data = explode('C', $data_sql->auto_id_doc);
            $ex2_data = explode('/', $ex1_data[1]);
            $res_num = (int)$ex2_data[0] + 1;
            if ($res_num > 0 && $res_num < 10) {
                $res_data = '000' . $res_num;
            } elseif ($res_num >= 10 && $res_num < 100) {
                $res_data = '00' . $res_num;
            } elseif ($res_num >= 100 && $res_num < 1000) {
                $res_data = '0' . $res_num;
            } else {
                $res_data = $res_num;
            }
            $data_date = date('y') + 43;
            $data_auto_id_doc = 'C' . $res_data . '/' . $data_date;
        } else {
            $data_date = date('y') + 43;
            $data_auto_id_doc = 'C0001/' . $data_date;
        }
        $data->auto_id_doc = $data_auto_id_doc;
        if ($request->officer_name != null) {
            $name_officer = array();
            for ($i = 0; $i < count($request->officer_name); $i++) {
                $name_officer[] = $request->officer_name[$i];
            }
            $result_name = implode(',', $name_officer);
            $data->officer_name = $result_name;
        }

        if ($data->save()) {
            if ($request->get('sub_license') != null) {
                for ($i = 0; $i < count($request->sub_license); $i++) {
                    $data_table_license = new ControlCheckPermission([
                        'id_check' => $data->id,
                        'license' => $request->sub_license[$i],
                    ]);
                    $data_table_license->save();
                }
            }
            if ($request->num_row_file != null) {
                for ($i = 0; $i < count($request->num_row_file); $i++) {
                    if ($request->hasFile('file' . $i)) {
                        $data_file = new ControlCheckFile();
                        $file = $request->file('file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_check = $data->id;
                        $data_file->remark_file = $request->remark_file[$i];
                        $data_file->save();
                    }
                }
            }
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function update_data(Request $request)
    {

        if ($request->get('tradeName') == '-เลือกผู้รับใบอนูญาต-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกผู้รับใบอนูญาต!"
            ]);
        }
        if ($request->get('tbl_tisiNo') == '-เลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์!"
            ]);
        }

        if ($request->get('operation') == '') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกการดำเนินการ!"
            ]);
        }
        $ControlCheck = ControlCheck::findOrFail($request->get('id'));
        if(isset($request->status)){
            $data  = new  stdClass;
            $data->status =  !empty($request->status) ? (string)$request->status : 0 ;
            $data->created_by = auth()->user()->getKey() ;
            $data->poao_approve_text =  null ;
            $data->poao_approve = null;
            $data->date =  date('Y-m-d') ;

                if(!is_null($ControlCheck->status_history)){
                    $data_list =  json_decode($ControlCheck->status_history);
                    foreach($data_list as $itme){
                        $list  = new  stdClass;
                        $list->status = (string)$itme->status ;
                        $list->created_by =  (string)$itme->created_by ;
                        $list->poao_approve =  @$itme->poao_approve;
                        $list->poao_approve_text =   @$itme->poao_approve_text;
                        $list->date = (string)$itme->date ;
                        $history[] = $list ;
                    }
                    $history[] = $data ;
                    $status_history = json_encode($history);
                }else{
                    $history[] = $data ;
                    $status_history = json_encode($history);
                }
          }

        if(isset($request->ever_warned)){
            $warned = array();
            foreach($request->ever_warned as $itme){
               $warned[] = $itme;
            }
            $rwarned = implode(',', $warned);
            $ever_warned = $rwarned;
        }else{
            $ever_warned = null;
        }
        if(isset($request->this_operation)){
            $operation = array();
            foreach($request->this_operation as $itme){
               $operation[] = $itme;
            }
            $roperation = implode(',', $operation);
            $this_operation = $roperation;
        }else{
            $this_operation = null;
        }

        $data = ControlCheck::find($request->get('id'));
        if ($request->officer_name != null) {
            $name_officer = array();
            for ($i = 0; $i < count($request->officer_name); $i++) {
                $name_officer[] = $request->officer_name[$i];
            }
            $result_name = implode(',', $name_officer);
            $data->officer_name = $result_name;
        }
        $data->tradeName = $request->get('tradeName');
        $data->tbl_tisiNo = $request->get('tbl_tisiNo');
        $data->located_check = $request->get('located_check');
        $data->located_keep = $request->get('located_keep');
        $data->located_sell = $request->get('located_sell');
        $data->address_no = $request->get('address_no');
        $data->address_industrial_estate = $request->get('address_industrial_estate');
        $data->address_alley = $request->get('address_alley');
        $data->address_road = $request->get('address_road');
        $data->address_village_no = $request->get('address_village_no');
        $data->address_district = $request->get('address_district');
        $data->address_amphoe = $request->get('address_amphoe');
        $data->address_province = $request->get('address_province');
        $data->address_zip_code = $request->get('address_zip_code');
        $data->tel = $request->get('tel');
        $data->fax = $request->get('fax');
        $data->latitude = $request->get('latitude');
        $data->Longitude = $request->get('Longitude');
        $data->checking_date = $request->get('checking_date');
        $data->checking_time = $request->get('checking_time');
        $data->police_station = $request->get('police_station');
        $data->police_station_value = $request->get('police_station_value');
        $data->this_checking = $request->get('this_checking');
        $data->location_check = $request->get('location_check');
        $data->remake_location_check = $request->remake_location_check1;
        $data->remake_location_check2 = $request->remake_location_check2;
        $data->production_site = $request->get('production_site');
        $data->product_not_legally = $request->get('product_not_legally');
        $data->location_keep = $request->get('location_keep');
        $data->product_sell = $request->get('product_sell');
        $data->num_of_hold = $request->get('num_of_hold');
        $data->num_of_freeze = $request->get('num_of_freeze');
        $data->num_of_hold_value = $request->get('num_of_hold_value');
        $data->num_of_freeze_value = $request->get('num_of_freeze_value');
        $data->reference_num = $request->get('reference_num');
        $data->detail_location_offense = $request->get('detail_location_offense');
        $data->detail_product_not_standard = $request->get('detail_product_not_standard');
        $data->premise = $request->get('premise');
        $data->seller_name = $request->get('seller_name');
        $data->seller_address = $request->get('seller_address');
        $data->officer_check = $request->get('officer_check');
        $data->num_of_time = $request->get('num_of_time');
        $data->last_time = $request->get('last_time');
        $data->ever_warning = $request->get('ever_warning');
        $data->ever_warned = $ever_warned;
        $data->this_operation = $this_operation;
        $data->more_notes = $request->get('more_notes');
        $data->operation = $request->get('operation');
        $data->status = $request->get('status');
        $data->status_history =  !empty($status_history) ? $status_history : null;
        $data->check_officer = $request->get('check_officer');
        $data->date_now = $request->get('date_now');
        if ($data->save()) {
            if ($request->get('sub_license') != null) {
                $data_license = ControlCheckPermission::query()->where('id_check', $request->get('id'))->get();
                foreach ($data_license as $list_license) {
                    $delete_license = ControlCheckPermission::find($list_license->id);
                    $delete_license->delete();
                }
                for ($i = 0; $i < count($request->sub_license); $i++) {
                    $data_table_license = new ControlCheckPermission([
                        'id_check' => $data->id,
                        'license' => $request->sub_license[$i],
                    ]);
                    $data_table_license->save();
                }
            }
            $del_file_check = ControlCheckFile::query()->where('id_check', $request->get('id'))->first();
            if ($del_file_check != null) {
                $del_file = ControlCheckFile::query()->where('id_check', $request->get('id'))->get();
                foreach ($del_file as $list_del_file) {
                    $data_del_file = ControlCheckFile::find($list_del_file->id);
                    $data_del_file->delete();
                }
            }
            if ($request->file_old != null) {
                for ($i = 0; $i < count($request->file_old); $i++) {
                    $data_file_old = new ControlCheckFile();
                    $data_file_old->file = $request->file_old[$i];
                    $data_file_old->id_check = $request->get('id');
                    $data_file_old->save();
                }
            }
            if ($request->num_row_file != null) {
                for ($i = 0; $i < count($request->num_row_file); $i++) {
                    if ($request->hasFile('file' . $i)) {
                        $data_file = new ControlCheckFile();

                        $file = $request->file('file' . $i);

                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_check = $request->get('id');
                        $data_file->save();
                    }
                }
            }
            $data_file_remark = ControlCheckFile::query()->where('id_check', $request->get('id'))->get();
            foreach ($data_file_remark as $key => $list_remark) {
                $list_remark->remark_file = $request->remark_file[$key];
                $list_remark->save();
            }
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function detail($ID)
    {
        $data = ControlCheck::find($ID);
        $previousUrl = app('url')->previous();
        $data_permission = ControlCheckPermission::query()->where('id_check', $ID)->get();
        if ($data->officer_name != null) {
            $officer_name = explode(',', $data->officer_name);
        } else {
            $officer_name = null;
        }
        if ($data->ever_warned != null) {
            $ever_warned = explode(',', $data->ever_warned);
        } else {
            $ever_warned = [];
        }
        if ($data->this_operation != null) {
            $this_operation = explode(',', $data->this_operation);
        } else {
            $this_operation = [];
        }
        $data_file = ControlCheckFile::query()->where('id_check', $ID)->get();
        $data_file_check = ControlCheckFile::query()->where('id_check', $ID)->first();
        $attach_path = $this->attach_path; //path ไฟล์แนบ
        return view('csurv.control_check.detail', ['data' => $data,
                                                'data_permission' => $data_permission,
                                                'officer_name' => $officer_name,
                                                'data_file' => $data_file,
                                                'data_file_check' => $data_file_check,
                                                'this_operation' => $this_operation,
                                                'ever_warned' => $ever_warned,
                                                'previousUrl' => $previousUrl,
                                                'attach_path' => $attach_path
                                                ]);
    }

    public function update_status(Request $request)
    {

        $data = ControlCheck::find($request->get('id'));
        $std  = new  stdClass;
        if($data->status== 3){
            $data->poao_approve = $request->get('poao_approve');
            $data->poao_approve_text = $request->get('poao_approve_text');
            $data->poao_assessor = $request->get('poao_assessor');
            $data->poao_approve_date = $request->get('poao_approve_date');

            if($data->poao_approve==1){
                    $data->status = 4;// ผอ.รับรองแล้ว
            }else{
                $data->status = 5; //ปรับปรุงแก้ไข
            }
            $std->poao_approve = $data->poao_approve;
            $std->poao_approve_text =   $data->poao_approve_text ;
        }
        if($data->status== 1){
            $data->check_status = $request->get('send_to_poao');
            $data->poko_approve = $request->get('poko_approve');
            $data->poko_approve_text = $request->get('poko_approve_text');
            $data->poko_assessor = $request->get('poko_assessor');
            $data->poko_approve_date = $request->get('poko_approve_date');

                if($data->check_status=="y"){
                    $data->status = 3; //อยู่ระหว่าง ผอ.รับรอง
                }else{
                    if( $data->poko_approve == 1){
                        $data->status = 2; //ผก.รับรองแล้ว
                    }else{
                        $data->status = 5; //ปรับปรุงแก้ไข
                    }
                }
            $std->poao_approve = $data->poko_approve;
            $std->poao_approve_text =   $data->poko_approve_text ;
        }

                $std->status =    $data->status ;
                $std->created_by = auth()->user()->getKey() ;
                $std->date =  date('Y-m-d') ;
                if(!is_null($data->status_history)){
                    $data_list =  json_decode($data->status_history);
                    foreach($data_list as $itme){
                        $list  = new  stdClass;
                        $list->status = (string)$itme->status ;
                        $list->created_by =  (string)$itme->created_by ;
                        $list->poao_approve =  @$itme->poao_approve;
                        $list->poao_approve_text =   @$itme->poao_approve_text;
                        $list->date = (string)$itme->date ;
                        $history[] = $list ;
                    }
                    $history[] = $std ;
                    $status_history = json_encode($history);
                }else{
                    $history[] = $std ;
                    $status_history = json_encode($history);
                }
        $data->status_history = $status_history;
        $data->status_check = auth()->user()->getKey() ;
        if ($data->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function delete_status(Request $request)
    {
        $data = ControlCheck::find($request->get('id'));
        if ($data->delete()) {
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function add_filter_reference_num(Request $request)
    {
        $data = ControlFreeze::query()->where('tradeName', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

    public function download_file($NAME)
    {
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->download($public . $this->attach_path . $NAME);
        $public = public_path();
        $attach_path = $this->attach_path;
       if(HP::checkFileStorage($attach_path. $NAME)){
           HP::getFileStoragePath($attach_path. $NAME);
           $filePath =  response()->download($public.'/uploads/'.$attach_path.$NAME);
            return $filePath;
       }else{
          return 'ไม่พบไฟล์';
       }
    }
        //เลือกลบแบบทั้งหมดได้
        public function delete(Request $request)
        {
          $id_array = $request->input('id');
          $data = ControlCheck::whereIn('id', $id_array);
          if($data->delete())
          {
              echo 'Data Deleted';
          }

        }
}

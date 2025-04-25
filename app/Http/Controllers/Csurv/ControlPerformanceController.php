<?php

namespace App\Http\Controllers\Csurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\control_performance;
use App\Models\Csurv\ControlCheckPermission;
use App\Models\Csurv\ControlPerformance;
use App\Models\Csurv\ControlPerformanceFile;
use App\Models\Csurv\ControlPerformanceOfficer;
use App\Models\Csurv\ControlPerformancePeopleFound;
use App\Models\Csurv\ControlPerformancePermission;
use App\Models\Basic\TisiLicense;
use App\Models\Csurv\Tis4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\DES;
use stdClass;
use HP;
class ControlPerformanceController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/control_performance/';
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage']            = $request->get('perPage', 10);
        $filter['filter_tb3_Tisno']   = $request->get('filter_tb3_Tisno', '');
        $filter['filter_assessment']  = $request->get('filter_assessment', '');
        $filter['filter_status']      = $request->get('filter_status', '');
        $filter['filter_start_month'] = $request->get('filter_start_month', '');
        $filter['filter_start_year']  = $request->get('filter_start_year', '');
        $filter['filter_end_month']   = $request->get('filter_end_month', '');
        $filter['filter_end_year']    = $request->get('filter_end_year', '');
        $filter['filter_title']       = $request->get('filter_title', '');

        $Query = new ControlPerformance;

        $assessment = ['แก้ไขให้เป็นไปตามข้อกำหนด', 'ไม่เป็นไปตามข้อกำหนด ส่งเรื่องให้ กม. ดำเนินการ', 'เป็นไปตามข้อกำหนด'];
        // $Query = $Query->wherein('conclude_result', [0,1,2]);

        if ($filter['filter_tb3_Tisno'] != '') {
            $id_autono = DB::table('tb4_tisilicense')->where('tbl_tisiNo', $filter['filter_tb3_Tisno'])->groupBy('tbl_taxpayer')->first();
            $Query = $Query->where('tradeName', $id_autono->Autono);
        }
        if ($filter['filter_status'] != '') {
            $Query = $Query->where('status', $filter['filter_status']);
        }
        if ($filter['filter_assessment'] != '') {
            $Query = $Query->where('conclude_result', $filter['filter_assessment']);
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

        $control_performance = $Query->sortable()->orderby('id','desc')->paginate($filter['perPage']);


        return view('csurv.control_performance.index', compact('control_performance', 'filter'));
    }

    public function create()
    {
        $previousUrl = app('url')->previous();
        return view('csurv.control_performance.create',['previousUrl'=>$previousUrl]);
    }

    public function show($id)
    {
        return view('csurv.control_performance.show');
    }

    public function edit($id)
    {
        $previousUrl = app('url')->previous();

        $data = ControlPerformance::find($id);
        $data_people_found = ControlPerformancePeopleFound::query()->where('id_perform', $id)->get();
        $data_officer = ControlPerformanceOfficer::query()->where('id_perform', $id)->get();
        $data_permission = ControlPerformancePermission::query()->where('id_perform', $id)->get();
        $data_file = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', null)->get();
        $data_file_check = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', null)->first();
        $data_file_material = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'material')->get();
        $data_file_check_material = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'material')->first();
        $data_file_control_between = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'control_between')->get();
        $data_file_check_control_between = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'control_between')->first();
        $data_file_control_finish = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'control_finish')->get();
        $data_file_check_control_finish = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'control_finish')->first();
        $data_file_control_standard = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'control_standard')->get();
        $data_file_check_control_standard = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'control_standard')->first();
        $data_file_test_machine = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'test_machine')->get();
        $data_file_check_test_machine = ControlPerformanceFile::query()->where('id_perform', $id)->where('type', '=', 'test_machine')->first();

        $q_data = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $id_data = $q_data->where('tbl_taxpayer', 'LIKE', "%{$data->tradeName}%")->pluck('tbl_tisiNo');
        $data_q = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        $data_checkbox = $data_q->where('tbl_tisiNo', $data->tbl_tisiNo)->where('tbl_tisiNo', $id_data)->get();
        $attach_path = $this->attach_path; //path ไฟล์แนบ
//        dd($data_permission);
        return view('csurv.control_performance.edit', ['data' => $data, 'data_people_found' => $data_people_found
            , 'data_officer' => $data_officer, 'data_permission' => $data_permission
            , 'data_file' => $data_file, 'data_file_check' => $data_file_check, 'data_checkbox' => $data_checkbox
            , 'data_material' => $data_file_material, 'data_file_material' => $data_file_check_material
            , 'data_control_between' => $data_file_control_between, 'data_file_control_between' => $data_file_check_control_between
            , 'data_control_finish' => $data_file_control_finish, 'data_file_control_finish' => $data_file_check_control_finish
            , 'data_control_standard' => $data_file_control_standard, 'data_file_control_standard' => $data_file_check_control_standard
            , 'data_test_machine' => $data_file_test_machine, 'data_file_test_machine' => $data_file_check_test_machine
            , 'previousUrl'=> $previousUrl,'attach_path' => $attach_path]);
    }

    public function update(Request $request)
    {
        return redirect('control_performance/control_performance');
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

      if(isset($request->check_status)){
        $data  = new  stdClass;
        $data->status =  !empty($request->check_status) ? (string)$request->check_status : 0 ;
        $data->created_by = auth()->user()->getKey() ;
        $data->date =  date('Y-m-d') ;
        $data->status_res =  null ;
        $data->status_res_remake =  null ;
        $history[] = $data;
        $status_history = json_encode($history);
      }


        $data = new ControlPerformance([
            'tradeName' => $request->get('tradeName'),
            'tbl_tisiNo' => $request->get('tbl_tisiNo'),
            'factory_name' => $request->get('factory_name'),
            'address_no' => $request->get('address_no'),
            'address_industrial_estate' => $request->get('address_industrial_estate'),
            'address_alley' => $request->get('address_alley'),
            'address_road' => $request->get('address_road'),
            'address_village_no' => $request->get('address_village_no'),
            'address_district' => (int)$request->get('address_district'),
            'address_amphoe' => (int)$request->get('address_amphoe'),
            'address_province' => (int)$request->get('address_province'),
            'address_zip_code' => $request->get('address_zip_code'),
            'tel' => $request->get('tel'),
            'fax' => $request->get('fax'),
            'latitude' => $request->get('latitude'),
            'Longitude' => $request->get('Longitude'),
            'checking_date' => $request->get('checking_date'),
            'material_res' => $request->get('material_res'),
            'material_ofsev' => $request->get('material_ofsev'),
            'material_ofsev_remake' => $request->get('material_ofsev_remake'),
            'material_defect' => $request->get('material_defect'),
            'material_defect_remake' => $request->get('material_defect_remake'),
            'control_between_res' => $request->get('control_between_res'),
            'control_between_ofsev' => $request->get('control_between_ofsev'),
            'control_between_ofsev_remake' => $request->get('control_between_ofsev_remake'),
            'control_between_defect' => $request->get('control_between_defect'),
            'control_between_defect_remake' => $request->get('control_between_defect_remake'),
            'control_finish_res' => $request->get('control_finish_res'),
            'control_finish_ofsev' => $request->get('control_finish_ofsev'),
            'control_finish_ofsev_remake' => $request->get('control_finish_ofsev_remake'),
            'control_finish_defect' => $request->get('control_finish_defect'),
            'control_finish_defect_remake' => $request->get('control_finish_defect_remake'),
            'control_standard_res' => $request->get('control_standard_res'),
            'control_standard_ofsev' => $request->get('control_standard_ofsev'),
            'control_standard_ofsev_remake' => $request->get('control_standard_ofsev_remake'),
            'control_standard_defect' => $request->get('control_standard_defect'),
            'control_standard_defect_remake' => $request->get('control_standard_defect_remake'),
            'test_machine_res' => $request->get('test_machine_res'),
            'test_machine_ofsev' => $request->get('test_machine_ofsev'),
            'test_machine_ofsev_remake' => $request->get('test_machine_ofsev_remake'),
            'test_machine_defect' => $request->get('test_machine_defect'),
            'test_machine_defect_remake' => $request->get('test_machine_defect_remake'),
            'conclude_result' => $request->get('conclude_result'),
            'remake' => $request->get('remake'),
            'status' => $request->get('check_status'),
            'status_history' =>  !empty($status_history) ? $status_history : null ,
            'status_check' => '-',
            'date_now' => $request->get('date_now'),
            'check_officer' => $request->get('check_officer'),
            'material_remark' => $request->get('material_remark'),
            'control_between_remark' => $request->get('control_between_remark'),
            'control_finish_remark' => $request->get('control_finish_remark'),
            'control_standard_remark' => $request->get('control_standard_remark'),
            'test_machine_remark' => $request->get('test_machine_remark'),
            'sub_id' => auth()->user()->reg_subdepart,
        ]);
        $check_data = DB::table('control_performance')->first();
        if ($check_data != null) {
            $data_sql = DB::table('control_performance')->orderByDesc('id')->first();
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
            $auto_doc_id_check = 'QC' . $res_data . '/' . $ex2_data[1];
        } else {
            $data_date = date('y') + 43;
            $auto_doc_id_check = 'QC0001/' . $data_date;
        }
        $data->auto_id_doc = $auto_doc_id_check;
        if ($data->save()) {
            if ($request->get('sub_license') != null) {
                for ($i = 0; $i < count($request->sub_license); $i++) {
                    $data_table_license = new ControlPerformancePermission([
                        'id_perform' => $data->id,
                        'license' => $request->sub_license[$i],
                    ]);
                    $data_table_license->save();
                }
            }
            if ($request->num_row_people_found != null) {
                for ($i = 0; $i < count($request->num_row_people_found); $i++) {
                    $data_table_people_found = new ControlPerformancePeopleFound([
                        'id_perform' => $data->id,
                        'full_name' => $request->full_name[$i],
                        'permission' => $request->permission[$i],
                        'people_tel' => $request->people_tel[$i],
                        'people_email' => $request->people_email[$i],
                    ]);
                    $data_table_people_found->save();
                }
            }
            if ($request->num_row_permission != null) {
                for ($i = 0; $i < count($request->num_row_permission); $i++) {
                    $data_table_permission = new ControlPerformanceOfficer([
                        'id_perform' => $data->id,
                        'full_name' => $request->full_name_per[$i],
                    ]);
                    $data_table_permission->save();
                }
            }
            if ($request->num_row_file != null) {
                for ($i = 0; $i < count($request->num_row_file); $i++) {
                    if ($request->hasFile('file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $data->id;

                        $data_file->save();
                    }
                }
            }

            for ($i = 0; $i < count($request->material_note); $i++) {
                if ($request->file('material_file' . $i) != null) {
                    if ($request->hasFile('material_file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('material_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $data->id;
                        $data_file->type = 'material';
                        $data_file->note = $request->material_note[$i];
                        $data_file->save();
                    }
                }
            }
            for ($i = 0; $i < count($request->control_between_note); $i++) {
                if ($request->file('control_between_file' . $i) != null) {
                    if ($request->hasFile('control_between_file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('control_between_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $data->id;
                        $data_file->type = 'control_between';
                        $data_file->note = $request->control_between_note[$i];
                        $data_file->save();
                    }
                }
            }
            for ($i = 0; $i < count($request->control_finish_note); $i++) {
                if ($request->file('control_finish_file' . $i) != null) {
                    if ($request->hasFile('control_finish_file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('control_finish_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $data->id;
                        $data_file->type = 'control_finish';
                        $data_file->note = $request->control_finish_note[$i];
                        $data_file->save();
                    }
                }
            }
            for ($i = 0; $i < count($request->control_standard_note); $i++) {
                if ($request->file('control_standard_file' . $i) != null) {
                    if ($request->hasFile('control_standard_file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('control_standard_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $data->id;
                        $data_file->type = 'control_standard';
                        $data_file->note = $request->control_standard_note[$i];
                        $data_file->save();
                    }
                }
            }
            for ($i = 0; $i < count($request->test_machine_note); $i++) {
                if ($request->file('test_machine_file' . $i) != null) {
                    if ($request->hasFile('test_machine_file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('test_machine_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $data->id;
                        $data_file->type = 'test_machine';
                        $data_file->note = $request->test_machine_note[$i];
                        $data_file->save();
                    }
                }
            }
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function add_filter_License(Request $request)
    {
        // $q_data = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        // $id_data = $q_data->where('Autono', $request->get('tb3_Tisno'))->pluck('tbl_tisiNo');
        // $data_q = DB::table('tb3_tis');
        // $data = $data_q->where('tb3_Tisno', $id_data)->get();
        // return response()->json([
        //     "status" => "success",
        //     'data' => $data,
        // ]);

        $q_data = DB::table('tb4_tisilicense');
        $id_data = $q_data->where('tbl_taxpayer', 'LIKE', "%{$request->get('tb3_Tisno')}%")->groupBy('tbl_tisiNo')->pluck('tbl_tisiNo');
        $data_q = DB::table('tb3_tis')->select('tb3_Tisno','tb3_TisThainame');
        $data = $data_q->whereIn('tb3_Tisno', $id_data)->get();

        $id_data2 = $q_data->where('tbl_taxpayer', 'LIKE', "%{$request->get('tb3_Tisno')}%")->groupBy('tbl_tradeName')->pluck('tbl_tradeName');
        $data_q2 = DB::table('save_example')->select('id','no');
        $data2 = $data_q2->whereIn('licensee', $id_data2)->get();

        return response()->json([
            "status" => "success",
            'data' => $data,
            'data2' => $data2,
        ]);
    }

    public function add_license(Request $request)
    {
//        dd($request->get('tb3_Tisno1'), $request->get('tb3_Tisno2'));
        // $q_data = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        // $id_data = $q_data->where('Autono', $request->get('tb3_Tisno1'))->pluck('tbl_tisiNo');
        // $data_q = DB::table('tb4_tisilicense')->groupBy('tbl_taxpayer');
        // $data = $data_q->where('tbl_tisiNo', $request->get('tb3_Tisno2'))->where('tbl_tisiNo', $id_data)->get();

        //รายการเลขที่ใบอนุญาตตามมาตรฐาน และผปก.
        $licenses = TisiLicense::where("tbl_taxpayer", 'LIKE', "%{$request->get('tb3_Tisno1')}%")->where("tbl_tisiNo", $request->get('tb3_Tisno2'))->where("tbl_licenseStatus",'1')->get();
        // dd($licenses);
        return response()->json([
            "status" => "success",
            'data' => $licenses,
        ]);
    }

    public function add_filter_address_province(Request $request)
    {
        $data = DB::table('amphur')->whereNull('state')->where('PROVINCE_ID', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,

        ]);
    }

    public function add_filter_address_district(Request $request)
    {
        $data = DB::table('district')->whereNull('state')->where('AMPHUR_ID', $request->get('tb3_Tisno'))->get();
        return response()->json([
            "status" => "success",
            'data' => $data,

        ]);
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

        $data = ControlPerformance::find($request->get('id'));

        if($data->status == 2) {
            return response()->json([
                "status" => "status_two",
                "message" => "การตรวจประเมินระบบควบคุมคุณภาพ ผก.รับรองแล้ว"
            ]);
        }


    if(in_array('5', auth()->user()->RoleListId)) { //<!--อยู่ระหว่าง ผก.รับรอง -->
        $data->date_now = $request->get('date_now');
        $data->status_check = $request->get('status_check');
        $data->status_res = $request->get('status_res');
        if($data->status_res  == '1'){
            $status = 2; //ผก.รับรองแล้ว
        }else{
            $status = 5; //ปรับปรุงแก้ไข
        }
        $data->status_res_remake = $request->get('status_res_remake');
                $std  = new  stdClass;
                $std->status =    $data->status ;
                $std->created_by = auth()->user()->getKey() ;
                $std->status_res = $data->status_res;
                $std->status_res_remake =   $data->status_res_remake ;
                $std->date =  date('Y-m-d') ;
                if(!is_null($data->status_history)){
                    $data_list =  json_decode($data->status_history);
                    foreach($data_list as $itme){
                        $list  = new  stdClass;
                        $list->status = (string)$itme->status ;
                        $list->created_by =  (string)$itme->created_by ;
                        $list->status_res =  @$itme->status_res;
                        $list->status_res_remake =   @$itme->status_res_remake;
                        $list->date = (string)$itme->date ;
                        $history[] = $list ;
                    }
                    $history[] = $std ;
                    $status_history = json_encode($history);
                }else{
                    $history[] = $std ;
                    $status_history = json_encode($history);
                }

     }else{ // เจ้าหน้าที่
        if(isset($request->check_status)){
            $std  = new  stdClass;
            $std->status =  !empty($request->check_status) ? (string)$request->check_status : 0 ;
            $std->created_by = auth()->user()->getKey() ;
            $std->status_res_remake =  null ;
            $std->status_res = null;
            $std->date =  date('Y-m-d') ;

                if(!is_null($data->status_history)){
                    $data_list =  json_decode($data->status_history);
                    foreach($data_list as $itme){
                        $list  = new  stdClass;
                        $list->status = (string)$itme->status ;
                        $list->created_by =  (string)$itme->created_by ;
                        $list->status_res =  @$itme->status_res;
                        $list->status_res_remake =   @$itme->status_res_remake;
                        $list->date = (string)$itme->date ;
                        $history[] = $list ;
                    }
                    $history[] = $std ;
                    $status_history = json_encode($history);
                }else{
                    $history[] = $std ;
                    $status_history = json_encode($history);
                }
          }
          //สถานะ
          $status =   !empty($request->check_status) ? $request->check_status : 0;
          $data->status_check = '-';
          $data->conclude_result = $request->get('conclude_result');
          $data->check_officer = $request->get('check_officer');
     }

        $data->tradeName = $request->get('tradeName');
        $data->tbl_tisiNo = $request->get('tbl_tisiNo');
        $data->factory_name = $request->get('factory_name');
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
        $data->material_res = $request->get('material_res');
        $data->material_ofsev = $request->get('material_ofsev');
        $data->material_ofsev_remake = $request->get('material_ofsev_remake');
        $data->material_defect = $request->get('material_defect');
        $data->material_defect_remake = $request->get('material_defect_remake');
        $data->control_between_res = $request->get('control_between_res');
        $data->control_between_ofsev = $request->get('control_between_ofsev');
        $data->control_between_ofsev_remake = $request->get('control_between_ofsev_remake');
        $data->control_between_defect = $request->get('control_between_defect');
        $data->control_between_defect_remake = $request->get('control_between_defect_remake');
        $data->control_finish_res = $request->get('control_finish_res');
        $data->control_finish_ofsev = $request->get('control_finish_ofsev');
        $data->control_finish_ofsev_remake = $request->get('control_finish_ofsev_remake');
        $data->control_finish_defect = $request->get('control_finish_defect');
        $data->control_finish_defect_remake = $request->get('control_finish_defect_remake');
        $data->control_standard_res = $request->get('control_standard_res');
        $data->control_standard_ofsev = $request->get('control_standard_ofsev');
        $data->control_standard_ofsev_remake = $request->get('control_standard_ofsev_remake');
        $data->control_standard_defect = $request->get('control_standard_defect');
        $data->control_standard_defect_remake = $request->get('control_standard_defect_remake');
        $data->test_machine_res = $request->get('test_machine_res');
        $data->test_machine_ofsev = $request->get('test_machine_ofsev');
        $data->test_machine_ofsev_remake = $request->get('test_machine_ofsev_remake');
        $data->test_machine_defect = $request->get('test_machine_defect');
        $data->test_machine_defect_remake = $request->get('test_machine_defect_remake');
        $data->remake = $request->get('remake');
        $data->status = !empty($status) ? $status : 0;
        $data->status_history = !empty($status_history) ? $status_history : null;
        $data->date_now = $request->get('date_now');
        $data->material_remark = $request->get('material_remark');
        $data->control_between_remark = $request->get('control_between_remark');
        $data->control_finish_remark = $request->get('control_finish_remark');
        $data->control_standard_remark = $request->get('control_standard_remark');
        $data->test_machine_remark = $request->get('test_machine_remark');
        if ($data->save()) {
            if ($request->get('sub_license') != null) {
                $data_license = ControlPerformancePermission::query()->where('id_perform', $request->get('id'))->get();
                foreach ($data_license as $list_license) {
                    $delete_license = ControlPerformancePermission::find($list_license->id);
                    $delete_license->delete();
                }
                for ($i = 0; $i < count($request->sub_license); $i++) {
                    $data_table_license = new ControlPerformancePermission([
                        'id_perform' => $request->get('id'),
                        'license' => $request->sub_license[$i],
                    ]);
                    $data_table_license->save();
                }
            }
            if ($request->num_row_people_found != null) {
                $data_people = ControlPerformancePeopleFound::query()->where('id_perform', $request->get('id'))->get();
                foreach ($data_people as $list_people) {
                    $delete_people = ControlPerformancePeopleFound::find($list_people->id);
                    $delete_people->delete();
                }
                for ($i = 0; $i < count($request->num_row_people_found); $i++) {
                    $data_table_people_found = new ControlPerformancePeopleFound([
                        'id_perform' => $request->get('id'),
                        'full_name' => $request->full_name[$i],
                        'permission' => $request->permission[$i],
                        'people_tel' => $request->people_tel[$i],
                        'people_email' => $request->people_email[$i],
                    ]);
                    $data_table_people_found->save();
                }
            }
            if ($request->num_row_permission != null) {
                $data_officer = ControlPerformanceOfficer::query()->where('id_perform', $request->get('id'))->get();
                foreach ($data_officer as $list_fficer) {
                    $delete_officer = ControlPerformanceOfficer::find($list_fficer->id);
                    $delete_officer->delete();
                }
                for ($i = 0; $i < count($request->num_row_permission); $i++) {
                    $data_table_permission = new ControlPerformanceOfficer([
                        'id_perform' => $request->get('id'),
                        'full_name' => $request->full_name_per[$i],
                    ]);
                    $data_table_permission->save();
                }
            }
            $del_file_check = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->first();
            if ($del_file_check != null) {
                $del_file = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->get();
                foreach ($del_file as $list_del_file) {
                    $data_del_file = ControlPerformanceFile::find($list_del_file->id);
                    $data_del_file->delete();
                }
            }
            if ($request->file_old != null) {
                for ($i = 0; $i < count($request->file_old); $i++) {
                    $data_file_old = new ControlPerformanceFile();
                    $data_file_old->file = $request->file_old[$i];
                    $data_file_old->id_perform = $request->get('id');
                    $data_file_old->save();
                }
            }
            if ($request->num_row_file != null) {
                for ($i = 0; $i < count($request->num_row_file); $i++) {
                    if ($request->hasFile('file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $request->get('id');
                        $data_file->save();
                    }
                }
            }

            if ($request->file_material_old != null) {
                for ($i = 0; $i < count($request->file_material_old); $i++) {
                    $data_file_old = new ControlPerformanceFile();
                    $data_file_old->file = $request->file_material_old[$i];
                    $data_file_old->id_perform = $request->get('id');
                    $data_file_old->type = 'material';
                    $data_file_old->save();
                }
            }

            if ($request->material_file_row != null) {
                for ($i = 0; $i < count($request->material_file_row); $i++) {
                    if ($request->hasFile('material_file' . $i)) {
                        $data_file = new ControlPerformanceFile();

                        $file = $request->file('material_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $request->get('id');
                        $data_file->type = 'material';
                        $data_file->save();
                    }
                }
            }

            if ($request->file_control_between_old != null) {
                for ($i = 0; $i < count($request->file_control_between_old); $i++) {
                    $data_file_old = new ControlPerformanceFile();
                    $data_file_old->file = $request->file_control_between_old[$i];
                    $data_file_old->id_perform = $request->get('id');
                    $data_file_old->type = 'control_between';
                    $data_file_old->save();
                }
            }

            if ($request->control_between_file_row != null) {
                for ($i = 0; $i < count($request->control_between_file_row); $i++) {
                    if ($request->hasFile('control_between_file' . $i)) {
                        $data_file = new ControlPerformanceFile();
                        $file = $request->file('control_between_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $request->get('id');
                        $data_file->type = 'control_between';
                        $data_file->save();
                    }
                }
            }
            if ($request->file_control_finish_old != null) {
                for ($i = 0; $i < count($request->file_control_finish_old); $i++) {
                    $data_file_old = new ControlPerformanceFile();
                    $data_file_old->file = $request->file_control_finish_old[$i];
                    $data_file_old->id_perform = $request->get('id');
                    $data_file_old->type = 'control_finish';
                    $data_file_old->save();
                }
            }

            if ($request->control_finish_file_row != null) {
                for ($i = 0; $i < count($request->control_finish_file_row); $i++) {
                    if ($request->hasFile('control_finish_file' . $i)) {
                        $data_file = new ControlPerformanceFile();
                        $file = $request->file('control_finish_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $request->get('id');
                        $data_file->type = 'control_finish';
                        $data_file->save();
                    }
                }
            }
            if ($request->file_control_standard_old != null) {
                for ($i = 0; $i < count($request->file_control_standard_old); $i++) {
                    $data_file_old = new ControlPerformanceFile();
                    $data_file_old->file = $request->file_control_standard_old[$i];
                    $data_file_old->id_perform = $request->get('id');
                    $data_file_old->type = 'control_standard';
                    $data_file_old->save();
                }
            }

            if ($request->control_standard_file_row != null) {
                for ($i = 0; $i < count($request->control_standard_file_row); $i++) {
                    if ($request->hasFile('control_standard_file' . $i)) {
                        $data_file = new ControlPerformanceFile();
                        $file = $request->file('control_standard_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $request->get('id');
                        $data_file->type = 'control_standard';
                        $data_file->save();
                    }
                }
            }
            if ($request->file_test_machine_old != null) {
                for ($i = 0; $i < count($request->file_test_machine_old); $i++) {
                    $data_file_old = new ControlPerformanceFile();
                    $data_file_old->file = $request->file_test_machine_old[$i];
                    $data_file_old->id_perform = $request->get('id');
                    $data_file_old->type = 'test_machine';
                    $data_file_old->save();
                }
            }

            if ($request->test_machine_file_row != null) {
                for ($i = 0; $i < count($request->test_machine_file_row); $i++) {
                    if ($request->hasFile('test_machine_file' . $i)) {
                        $data_file = new ControlPerformanceFile();
                        $file = $request->file('test_machine_file' . $i);

                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $data_file->file = $storageName;
                        $data_file->id_perform = $request->get('id');
                        $data_file->type = 'test_machine';
                        $data_file->save();
                    }
                }
            }
            $data_material_note = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->where('type', '=', 'material')->get();
            foreach ($data_material_note as $key => $list_material) {
                $list_material->note = $request->material_note[$key];
                $list_material->save();
            }
            $data_control_between_note = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->where('type', '=', 'control_between')->get();
            foreach ($data_control_between_note as $key => $list_control_between) {
                $list_control_between->note = $request->control_between_note[$key];
                $list_control_between->save();
            }
            $data_control_finish_note = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->where('type', '=', 'control_finish')->get();
            foreach ($data_control_finish_note as $key => $list_control_finish) {
                $list_control_finish->note = $request->control_finish_note[$key];
                $list_control_finish->save();
            }
            $data_control_standard_note = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->where('type', '=', 'control_standard')->get();
            foreach ($data_control_standard_note as $key => $list_control_standard) {
                $list_control_standard->note = $request->control_standard_note[$key];
                $list_control_standard->save();
            }
            $data_test_machine_note = ControlPerformanceFile::query()->where('id_perform', $request->get('id'))->where('type', '=', 'test_machine')->get();
            foreach ($data_test_machine_note as $key => $list_test_machine) {
                $list_test_machine->note = $request->test_machine_note[$key];
                $list_test_machine->save();
            }
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function detail($ID)
    {
        $previousUrl = app('url')->previous();
        $data = ControlPerformance::find($ID);
        $data_people_found = ControlPerformancePeopleFound::query()->where('id_perform', $ID)->get();
        $data_officer = ControlPerformanceOfficer::query()->where('id_perform', $ID)->get();
        $data_permission = ControlPerformancePermission::query()->where('id_perform', $ID)->get();
        $data_file = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', null)->get();
        $data_file_check = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', null)->first();
        $data_file_material = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'material')->get();
        $data_file_check_material = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'material')->first();
        $data_file_control_between = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'control_between')->get();
        $data_file_check_control_between = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'control_between')->first();
        $data_file_control_finish = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'control_finish')->get();
        $data_file_check_control_finish = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'control_finish')->first();
        $data_file_control_standard = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'control_standard')->get();
        $data_file_check_control_standard = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'control_standard')->first();
        $data_file_test_machine = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'test_machine')->get();
        $data_file_check_test_machine = ControlPerformanceFile::query()->where('id_perform', $ID)->where('type', '=', 'test_machine')->first();
        $attach_path = $this->attach_path; //path ไฟล์แนบ
        return view('csurv.control_performance.detail', ['data' => $data, 'data_people_found' => $data_people_found,'data_file'=>$data_file,'data_file_check'=>$data_file_check
            , 'data_officer' => $data_officer, 'data_permission' => $data_permission
            , 'data_material' => $data_file_material, 'data_file_material' => $data_file_check_material
            , 'data_control_between' => $data_file_control_between, 'data_file_control_between' => $data_file_check_control_between
            , 'data_control_finish' => $data_file_control_finish, 'data_file_control_finish' => $data_file_check_control_finish
            , 'data_control_standard' => $data_file_control_standard, 'data_file_control_standard' => $data_file_check_control_standard
            , 'data_test_machine' => $data_file_test_machine, 'data_file_test_machine' => $data_file_check_test_machine
            , 'previousUrl'=>$previousUrl,'attach_path' => $attach_path]);
    }

    public function update_status(Request $request)
    {
        $data = ControlPerformance::find($request->get('id'));
        $data->date_now = $request->get('date_now');
        $data->status_check = $request->get('status_check');

        $data->status_res = $request->get('status_res');
        if($data->status_res == '1'){
            $data->status = '2'; //ผก.รับรองแล้ว
        }else{
            $data->status = '5'; //ปรับปรุงแก้ไข
        }
        $data->status_res_remake = $request->get('status_res_remake');
                $std  = new  stdClass;
                $std->status =    $data->status ;
                $std->created_by = auth()->user()->getKey() ;
                $std->status_res = $data->status_res;
                $std->status_res_remake =   $data->status_res_remake ;
                $std->date =  date('Y-m-d') ;
                if(!is_null($data->status_history)){
                    $data_list =  json_decode($data->status_history);
                    foreach($data_list as $itme){
                        $list  = new  stdClass;
                        $list->status = (string)$itme->status ;
                        $list->created_by =  (string)$itme->created_by ;
                        $list->status_res =  @$itme->status_res;
                        $list->status_res_remake =   @$itme->status_res_remake;
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
        if ($data->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function delete_status(Request $request)
    {
        $data = ControlPerformance::find($request->id);
        // $data->status = 'ยกเลิก';
        if ($data->delete()) {
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function delete_status_all(Request $request)
    {
        $cut_data = explode(',', $request->get('id'));
        foreach ($cut_data as $list) {
            if ($list != "") {
                $data = ControlPerformance::find($list);
                $data->status = 'ยกเลิก';
                $data->save();
            }
        }
        return response()->json([
            "status" => "success",
        ]);
    }

    public function download_file($NAME){
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->download($public . $this->attach_path.$NAME);
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

    public function preview_file($NAME){
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->file($public . $this->attach_path.$NAME);
        $public = public_path();
        $attach_path = $this->attach_path;
       if(HP::checkFileStorage($attach_path. $NAME)){
           HP::getFileStoragePath($attach_path. $NAME);
           $filePath =  response()->file($public.'/uploads/'.$attach_path.$NAME);
            return $filePath;
       }else{
          return 'ไม่พบไฟล์';
       }
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
      $id_array = $request->input('id');
      $data = ControlPerformance::whereIn('id', $id_array);
      if($data->delete())
      {
          echo 'Data Deleted';
      }

    }
}

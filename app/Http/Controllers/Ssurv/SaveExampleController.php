<?php

namespace App\Http\Controllers\Ssurv;

use HP;
use App\User;

use stdClass;
use Exception;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Section5\Labs;
use PhpOffice\PhpWord\PhpWord;
use App\Models\Ssurv\SaveExample;
use PhpOffice\PhpWord\Style\Font;
use App\Models\Bsection5\TestItem;
use App\Models\Section5\LabsScope;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Shared\Html;
use App\Models\Basic\SubDepartment;

use App\Http\Controllers\Controller;
use App\Models\REsurv\ResultProduct;
use App\Models\Sso\User AS SSO_User;
use App\Models\Ssurv\SaveExampleFile;
use App\Models\Besurv\TisSubDepartment;
use App\Models\Ssurv\SaveExampleDetail;

use App\Models\Ssurv\SaveExampleMaplap;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Models\Ssurv\SaveExampleMapLapDetail;
// use PhpOffice\PhpWord\Writer\HTML\Element\TextRun;

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Section;

class SaveExampleController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'ssurv_attach/save_example/';
    }

    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_search'] = $request->get('filter_search', '');
        $filter['filter_status'] = $request->get('filter_status', '');
        $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
        $filter['filter_submission_date_start'] = $request->get('filter_submission_date_start', '');
        $filter['filter_submission_date_end'] = $request->get('filter_submission_date_end', '');
        $filter['filter_department'] = $request->get('filter_department', '');
        $filter['filter_sub_department'] = $request->get('filter_sub_department', '');

        $Query = new SaveExample;

         if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('licensee', 'LIKE', "%{$filter['filter_search']}%");
                         });
            }

            // var_dump($filter['filter_submission_date_start']);
            // var_dump($filter['filter_submission_date_end']);

        if ($filter['filter_status'] != '') {
            if($filter['filter_status']=='0' || $filter['filter_status']=='ยกเลิก'){
                $Query = $Query->where('status', $filter['filter_status']);
            }else{
                $Query = $Query->whereIn('status', [1,2,3,4]);
            }
        }

            if ($filter['filter_tb3_Tisno'] != '') {
            $Query = $Query->where('tis_standard', $filter['filter_tb3_Tisno']);
        }

            if ($filter['filter_submission_date_start']!='') {
                $filter_submission_date_start = Carbon::createFromFormat("d/m/Y",$filter['filter_submission_date_start'])->addYear(-543)->formatLocalized('%Y-%m-%d');
                $Query = $Query->where('sample_submission_date', '>=', "$filter_submission_date_start");

            }

            if ($filter['filter_submission_date_end']!='') {
                $filter_submission_date_end = Carbon::createFromFormat("d/m/Y",$filter['filter_submission_date_end'])->addYear(-543)->formatLocalized('%Y-%m-%d');
                $Query = $Query->where('sample_submission_date', '<=', "$filter_submission_date_end");
            }

            if ($filter['filter_department']!='') {
                $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tis_standard', $tis_subdepartments);
                $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
            } else {
                $subDepartments =[];
            }

            if ($filter['filter_sub_department']!='') {
                $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tis_standard', $tis_subdepartments);
            }

        $save_example = $Query->orderby('id','desc')->sortable(['sample_submission_date' => 'desc'])->paginate($filter['perPage']);
        $temp_num = $save_example->firstItem();

        $map_lap = SaveExampleMaplap::query()->groupBy('no_example_id')->get();

        return view('ssurv.save_example.index', ['map_lap' => $map_lap], compact('save_example', 'filter', 'temp_num', 'subDepartments'));
    }

    public function create(Request $request)
    {
        $previousUrl = app('url')->previous();
        $tisno = $request->get('tisno');

        return view('ssurv.save_example.create', compact('tisno','previousUrl'));
    }

    public function save_data(Request $request)
    {
        if ($request->get('tis_standard') == 'เลือกมาตรฐาน') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกมาตรฐาน!"
            ]);
        }
        if ($request->get('type_save') == null) {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกรูปแบบการตรวจ!"
            ]);
        }


            if ($request->licensee == 'เลือกผู้รับใบอนุญาต') {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณาเลือกผู้รับใบอนุญาต!"
                ]);
            }
            // if ($request->number != null) {
            //     $error_number = 0;
            //     for ($i = 0; $i < count($request->number); $i++) {
            //         if ($request->number[$i] == null) {
            //             $error_number += 1;
            //         }
            //     }
            //     if ($error_number > 0) {
            //         return response()->json([
            //             "status" => "error",
            //             "message" => "กรุณาระบุจำนวน!"
            //         ]);
            //     }
            // }
            // if ($request->num_ex != null) {
            //     $error_num_ex = 0;
            //     for ($i = 0; $i < count($request->num_ex); $i++) {
            //         if ($request->num_ex[$i] == null) {
            //             $error_num_ex += 1;
            //         }
            //     }
            //     if ($error_num_ex > 0) {
            //         return response()->json([
            //             "status" => "error",
            //             "message" => "กรุณาระบุหมายเลขตัวอย่าง!"
            //         ]);
            //     }
            // }
            if ($request->num_row == null) {

                    return response()->json([
                        "status" => "error",
                        "message" => "กรุณาเลือกรายละเอียดผลิตภัณฑ์อุตสาหกรรม!"
                    ]);
            }
            if ($request->wksselect != null) {

                $error_wksselect = 0;
                for ($i = 0; $i < count($request->wksselect); $i++) {
                    if ($request->wksselect[$i] == 'เลือกชื่อหน่วยตรวจสอบ') {
                        $error_wksselect += 1;
                    }
                }
                if ($error_wksselect > 0) {
                    return response()->json([
                        "status" => "error",
                        "message" => "กรุณาเลือกหน่วยตรวจสอบ!"
                    ]);
                }
            }
            if ($request->sample_pay == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกผู้จ่ายตัวอย่าง!"
                ]);
            }
            if ($request->permission_submiss == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกตำแหน่ง!"
                ]);
            }
            if ($request->tel_submiss == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกเบอร์โทรศัพท์ผู้จ่ายตัวอย่าง!"
                ]);
            }
            if ($request->email_submiss == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอก E-mail ผู้จ่ายตัวอย่าง!"
                ]);
            }
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;
        $save_example = new SaveExample([
            'tis_standard' => $request->get('tis_standard'),
            'licensee' => $request->get('licensee'),
            'verification' => $request->get('verification'),
            'more_details' => $request->get('more_details'),
            'sample_submission' => $request->get('sample_submission'),
            'stored_add' => $request->get('stored_add'),
            'room_anchor' => $request->get('room_anchor'),
            // 'sample_submission_date' => $request->get('sample_submission_date'),
            'sample_submission_date' => $request->get('sample_submission_date')?Carbon::createFromFormat("d/m/Y",$request->get('sample_submission_date'))->addYear(-543)->formatLocalized('%Y-%m-%d'):null,
            'sample_pay' => $request->get('sample_pay'),
            'permission_submiss' => $request->get('permission_submiss'),
            'tel_submiss' => $request->get('tel_submiss'),
            'email_submiss' => $request->get('email_submiss'),
            // 'sample_collect_date' => $request->get('sample_collect_date'),
            'sample_recipient' => $request->get('sample_recipient'),
            'permission_receive' => $request->get('permission_receive'),
            'tel_receive' => $request->get('tel_receive'),
            'email_receive' => $request->get('email_receive'),
            'sample_return' => $request->get('sample_return'),
            'status' => $request->get('check_status')
            // 'status2' => '-',
            // 'status3' => '-',
            // 'user_create' => $full_name,
            // 'user_register' => '-',
            // 'res_status' => '-',
        ]);
        $save_example->licensee_no = $request->get('licensee_no');
        $save_example->type_send = $request->get('type_save');
        $save_example->more_details = $request->get('more_details');

        if ($save_example->save()) {
            $date_auto = date('y') + 43;
            $data_no = SaveExample::find($save_example->id);
            $data_no->no = 'SAM' . $date_auto . '-' . $save_example->id;
            $data_no->save();
            if ($request->num_row != null) {
                for ($i = 0; $i < count($request->num_row); $i++) {
                    $value_num_row = $request->num_row[$i];

                    $data_table = new SaveExampleDetail([
                        'id_example' => $save_example->id,
                        'num_row' => $i + 1,
                        'detail_volume' => $value_num_row,
                        'number' => $request->number[$value_num_row],
                        // 'unit' => $request->unit[$i],
                        'num_ex' => $request->num_ex[$value_num_row],
                        // 'sum' => $request->number[$i],
                        'action' => 'create'
                    ]);
                    $data_table->save();
                }
            }

            if($request->get('type_save') == 'all'){

                if ($request->wsk_row != null) {
                    $count_wsk_row = count($request->wsk_row);

                    for ($i = 0; $i < $count_wsk_row; $i++) {

                        if($i == 0){
                            $status_send = 'ส่ง';
                            $status1 = $request->get('check_status');
                        }else{
                            $status_send = 'รอ';
                            if($request->get('check_status') == '0'){
                                $status1 = $request->get('check_status');
                            }else{
                                $status1 = '-';
                            }
                        }

                        if ($request->input('wkslist_list')[$i] != null && $request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {
                            $k = $i+1;

                            $lap_id = $request->wksselect[$i];
                            $lab = Labs::find($lap_id);//ข้อมูล Lab

                            $id = [];
                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {

                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'name_lap' => ($lab->name ?? null),
                                    'detail_product' => $lap_id,
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                    'status' => $status1,
                                    'example_id' => $save_example->id,
                                    'tis_standard' => $request->tis_standard,
                                    'user_create' => $full_name,
                                    'licensee' => $request->get('licensee'),
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                $test_item_id = $request->input('wkslist_test')[$i.'-'.$n][$test];

                                                $this->save_map_lab_detail($test_item_id,
                                                                           $data_maplap->example_id,
                                                                           $data_maplap->id
                                                                          );

                                            }
                                        }

                                    }
                                }
                            }

                        }elseif ($request->input('wkslist_list')[$i] != null) {
                            $k = $i+1;
                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                    'status' => $status1,
                                    'example_id' => $save_example->id,
                                    'user_create' => $full_name,
                                    'licensee' => $request->get('licensee'),
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                $test_item_id = $request->input('wkslist_test')[$i.'-'.$n][$test];
                                                $this->save_map_lab_detail($test_item_id,
                                                                           $data_maplap->example_id,
                                                                           $data_maplap->id
                                                                          );

                                            }
                                        }

                                    }
                                }
                            }
                        } elseif ($request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {
                            $k = $i+1;

                            $lap_id = $request->wksselect[$i];
                            $lab = Labs::find($lap_id);//ชื่อ Lab

                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'name_lap' => ($lab->name ?? null),
                                'detail_product' => $lap_id,
                                'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                'status' => $status1,
                                'example_id' => $save_example->id,
                                'tis_standard' => $request->tis_standard,
                                'user_create' => $full_name,
                                'licensee' => $request->get('licensee'),
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();

                        } else {
                            $k = $i+1;
                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                'status' => '-',
                                'example_id' => $save_example->id,
                                'user_create' => $full_name,
                                'licensee' => $request->get('licensee'),
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();
                        }

                    }

                }

            } else if($request->get('type_save') == 'some'){
                if ($request->wsk_row != null) {
                    $count_wsk_row = count($request->wsk_row);

                    for ($i = 0; $i < $count_wsk_row; $i++) {

                        $status_send = 'ส่ง';

                        if ($request->input('wkslist_list')[$i] != null && $request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {
                            $k = $i+1;
                            $name_lap1 = explode('|', $request->wksselect[$i]);

                            $lap_id = $request->wksselect[$i];
                            $lab = Labs::find($lap_id);//ชื่อ Lab

                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'name_lap' => ($lab->name ?? null),
                                    'detail_product' => $lap_id,
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                    'status' => $request->get('check_status'),
                                    'example_id' => $save_example->id,
                                    'tis_standard' => $request->tis_standard,
                                    'user_create' => $full_name,
                                    'licensee' => $request->get('licensee'),
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                $test_item_id = $request->input('wkslist_test')[$i.'-'.$n][$test];
                                                $this->save_map_lab_detail($test_item_id,
                                                                           $data_maplap->example_id,
                                                                           $data_maplap->id
                                                                          );

                                            }
                                        }

                                    }
                                }
                            }

                        }elseif ($request->input('wkslist_list')[$i] != null) {
                            $k = $i+1;
                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                    'status' => $request->get('check_status'),
                                    'example_id' => $save_example->id,
                                    'user_create' => $full_name,
                                    'licensee' => $request->get('licensee'),
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                $test_item_id = $request->input('wkslist_test')[$i.'-'.$n][$test];
                                                $this->save_map_lab_detail($test_item_id,
                                                                           $data_maplap->example_id,
                                                                           $data_maplap->id
                                                                          );

                                            }
                                        }

                                    }
                                }
                            }
                        } elseif ($request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {
                            $k = $i+1;
                            $name_lap1 = explode('|', $request->wksselect[$i]);

                            $lap_id = $request->wksselect[$i];
                            $lab = Labs::find($lap_id);//ชื่อ Lab

                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'name_lap' => ($lab->name ?? null),
                                'detail_product' => $lap_id,
                                'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                'status' => $request->get('check_status'),
                                'example_id' => $save_example->id,
                                'tis_standard' => $request->tis_standard,
                                'user_create' => $full_name,
                                'licensee' => $request->get('licensee'),
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();

                        } else {
                            $k = $i+1;
                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'no_example_id' => 'SAM' . $date_auto . '-' . $save_example->id . '-' . $k,
                                'status' => '-',
                                'example_id' => $save_example->id,
                                'user_create' => $full_name,
                                'licensee' => $request->get('licensee'),
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();
                        }

                    }

                }
            }


        } else {
            return response()->json([
                "status" => "error",
                "message" => "เพิ่มข้อมูลไม่สำเร็จ!"
            ]);
        }


        return response()->json([
            "status" => "success",
        ]);

    }

    private function save_map_lab_detail($test_item_id=null, $example_id=null, $maplap_id=null, $test_id=null, $type=null){

        $test_table = new SaveExampleMapLapDetail();
        $test_table->test_item_id = $test_item_id;
        $test_table->example_id   = $example_id;
        $test_table->maplap_id    = $maplap_id;
        $test_table->test_id      = $test_id;
        $test_table->type         = $type;
        $test_table->save();

    }

    public function check_result(Request $request)
    {
        $check_result = ResultProduct::query()->where('tis_standard', $request->get('tis_standard'))->first();
        return response()->json([
            "status" => "success",
            "data" => $check_result
        ]);
    }

    public function show($id)
    {
        $save_example = SaveExample::findOrFail($id);
        return view('ssurv.save_example.show', compact('save_example'));

    }

    public function edit($id)
    {
        $previousUrl = app('url')->previous();

        $data = SaveExample::with([
                                    'details',
                                    'save_example_map_lap'
                                ])->findOrFail($id);
        $data['sample_submission_date'] = $data['sample_submission_date']?Carbon::createFromFormat("Y-m-d", $data['sample_submission_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
        $data_detail = SaveExampleDetail::query()->where('id_example', $id)->get();
        $data_lap1 = SaveExampleMaplap::query()->where('example_id', $id)->where('num_row', '=', '1')->get();
        $data_lap1_sel = SaveExampleMaplap::query()->where('example_id', $id)->where('num_row', '=', '1')->first();
        $data_lap2 = SaveExampleMaplap::query()->where('example_id', $id)->where('num_row', '=', '2')->get();
        $data_lap2_sel = SaveExampleMaplap::query()->where('example_id', $id)->where('num_row', '=', '2')->first();
        $data_lap3 = SaveExampleMaplap::query()->where('example_id', $id)->where('num_row', '=', '3')->get();
        $data_lap3_sel = SaveExampleMaplap::query()->where('example_id', $id)->where('num_row', '=', '3')->first();

        $sizeDetial = !empty($data->licensee_no) ? DB::table('tb4_licensesizedetial')->where('licenseNo', 'like', '%'.$data->licensee_no.'%')->get() : collect([]);
        $data_lap = SaveExampleMaplap::query()->where('example_id', $id)->groupBy('no_example_id')->orderBy('id')->get();


        // $maplap = DB::table('ros_rbasicdata_maplab AS a')
        //             ->select()
        //             ->where('a.tis_number', $data->tis_standard)
        //             ->pluck('a.lab_id');
        // $user = DB::table('tb10_nsw_lite_trader as c')
        //           ->wherein('c.trader_autonumber', $maplap)
        //           ->get();
        $scope_query = LabsScope::where('tis_tisno', $data->tis_standard)->select('lab_id');
        $user_lab = Labs::whereIn('id', $scope_query)->with('section5_labs_scopes')->get();

        return view('ssurv.save_example.edit', [
                    'data' => $data,
                    'data_detail' => $data_detail,
                    'data_lap1' => $data_lap1,
                    'data_lap2' => $data_lap2,
                    'data_lap3' => $data_lap3,
                    'data_lap1_sel' => $data_lap1_sel,
                    'data_lap2_sel' => $data_lap2_sel,
                    'data_lap3_sel' => $data_lap3_sel,
                    'previousUrl' => $previousUrl,
                    'sizeDetial' => $sizeDetial,
                    'data_lab' => $data_lap,
                    'user_lab' => $user_lab
                ]);
    }

    public function delete_example(Request $request)
    {
        $data = SaveExample::find($request->get('id'));
        $data_check = SaveExampleMaplap::query()->where('example_id', $request->get('id'))->first();
        $data_maplap = SaveExampleMaplap::query()->where('example_id', $request->get('id'))->pluck('id');
        $data->status = 'ยกเลิก';
        $data->save();
        if ($data_check != null) {
            foreach ($data_maplap as $id) {
                $data_maplap_id = SaveExampleMaplap::find($id);
                $data_maplap_id->status = 'ยกเลิก';
                $data_maplap_id->save();
            }
        }
        return response()->json([
            "status" => "success",
        ]);
    }

    public function delete_select(Request $request)
    {
        $data = explode(',', $request->get('id'));
        foreach ($data as $list) {
            if ($list != null) {
                $data_main = SaveExample::find($list);
                $data_check = SaveExampleMaplap::query()->where('example_id', $list)->first();
                $data_maplap = SaveExampleMaplap::query()->where('example_id', $list)->pluck('id');
                $data_main->status = 'ยกเลิก';
                $data_main->save();
                if ($data_check != null) {
                    foreach ($data_maplap as $id) {
                        $data_maplap_id = SaveExampleMaplap::find($id);
                        $data_maplap_id->status = 'ยกเลิก';
                        $data_maplap_id->save();
                    }
                }
            }
        }

        return response()->json([
            "status" => "success",
        ]);
    }

    public function delete_detail(Request $request)
    {
        $data = SaveExampleDetail::find($request->get('id'));
        if ($data->delete()) {
            return response()->json([
                "status" => "success",
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "ลบข้อมูลไม่สำเร็จ!"
            ]);
        }
    }

    public function update(Request $request)
    {
        $full_name = auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname;

            if ($request->licensee == 'เลือกผู้รับใบอนุญาต') {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณาเลือกผู้รับใบอนุญาต!"
                ]);
            }

            if ($request->num_row == null) {

                return response()->json([
                    "status" => "error",
                    "message" => "กรุณาเลือกรายละเอียดผลิตภัณฑ์อุตสาหกรรม!"
                ]);
            }
            if ($request->wksselect != null) {

                $error_wksselect = 0;
                for ($i = 0; $i < count($request->wksselect); $i++) {
                    if ($request->wksselect[$i] == 'เลือกชื่อหน่วยตรวจสอบ') {
                        $error_wksselect += 1;
                    }
                }
                if ($error_wksselect > 0) {
                    return response()->json([
                        "status" => "error",
                        "message" => "กรุณาเลือกหน่วยตรวจสอบ!"
                    ]);
                }
            }
            if ($request->sample_pay == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกผู้จ่ายตัวอย่าง!"
                ]);
            }
            if ($request->permission_submiss == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกตำแหน่ง!"
                ]);
            }
            if ($request->tel_submiss == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอกเบอร์โทรศัพท์ผู้จ่ายตัวอย่าง!"
                ]);
            }
            if ($request->email_submiss == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "กรุณากรอก E-mail ผู้จ่ายตัวอย่าง!"
                ]);
            }
        $data_example = SaveExample::find($request->get('example_id'));

        if ($request->get('verification') == 'ตรวจสอบที่โรงงาน') {
            $data_example->verification = $request->get('verification');
            $data_example->sample_submission = '';
            $data_example->stored_add = '';
            $data_example->room_anchor = '';
        } else {
            $data_example->verification = $request->get('verification');
            $data_example->sample_submission = $request->get('sample_submission');
            $data_example->stored_add = $request->get('stored_add');
            $data_example->room_anchor = $request->get('room_anchor');
        }

        $data_example->more_details = $request->get('more_details');
        $data_example->sample_submission_date = $request->get('sample_submission_date')?Carbon::createFromFormat("d/m/Y",$request->get('sample_submission_date'))->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

        $data_example->sample_pay = $request->get('sample_pay');
        $data_example->permission_submiss = $request->get('permission_submiss');
        $data_example->tel_submiss = $request->get('tel_submiss');
        $data_example->email_submiss = $request->get('email_submiss');
        // $data_example->sample_collect_date = $request->get('sample_collect_date');
        $data_example->sample_recipient = $request->get('sample_recipient');
        $data_example->permission_receive = $request->get('permission_receive');
        $data_example->tel_receive = $request->get('tel_receive');
        $data_example->email_receive = $request->get('email_receive');
        $data_example->sample_return = $request->get('sample_return');
        $data_example->status = $request->get('check_status');
        $data_example->type_send = $request->get('type_save');
        if ($data_example->save()) {
            $old_data_detail_ck = SaveExampleDetail::query()->where('id_example',$request->get('example_id'))->first();
            if ($old_data_detail_ck!=null){
                $old_data_detail = SaveExampleDetail::query()->where('id_example',$request->get('example_id'))->get();
                foreach ($old_data_detail as $list_del_data_detail){
                    $del_detail = SaveExampleDetail::find($list_del_data_detail->id);
                    $del_detail->delete();
                }
            }
            if ($request->num_row != null) {
                for ($i = 0; $i < count($request->num_row); $i++) {
                    $value_num_row = $request->num_row[$i];

                    $data_table = new SaveExampleDetail([
                        'id_example' => $data_example->id,
                        'num_row' => $i + 1,
                        'detail_volume' => $value_num_row,
                        'number' => $request->number[$value_num_row],
                        // 'unit' => $request->unit[$i],
                        'num_ex' => $request->num_ex[$value_num_row],
                        // 'sum' => $request->number[$i],
                        'action' => 'create'
                    ]);
                    $data_table->save();
                }
            }

            $old_data_map_ck = SaveExampleMaplap::query()->where('example_id',$request->get('example_id'))->first();
            if ($old_data_map_ck!=null){
                $old_data_map = SaveExampleMaplap::query()->where('example_id',$request->get('example_id'))->get();
                foreach ($old_data_map as $list_del_data_nap){
                    $del_map = SaveExampleMaplap::find($list_del_data_nap->id);
                    $del_map->delete();
                }
            }

            $old_data_map_detail_ck = SaveExampleMaplapDetail::query()->where('example_id',$request->get('example_id'))->first();
            if ($old_data_map_detail_ck!=null){
                $old_data_map_detail = SaveExampleMaplapDetail::query()->where('example_id',$request->get('example_id'))->get();
                foreach ($old_data_map_detail as $old_data_map_details){
                    $del_map_detail = SaveExampleMaplapDetail::find($old_data_map_details->id);
                    $del_map_detail->delete();
                }
            }

            $date_auto = date('y') + 43;

            if($request->get('type_save') == 'all'){

                if ($request->wsk_row != null) {
                    $count_wsk_row = count($request->wsk_row);

                    for ($i = 0; $i < $count_wsk_row; $i++) {

                        if($i == 0){
                            $status_send = 'ส่ง';
                            $status1 = $request->get('check_status');
                        }else{
                            $status_send = 'รอ';
                            if($request->get('check_status') == '0'){
                                $status1 = $request->get('check_status');
                            }else{
                                $status1 = '-';
                            }
                        }

                        if (($request->has('wkslist_list') && array_key_exists($i, $request->input('wkslist_list')) && $request->input('wkslist_list')[$i] != null) &&
                            ($request->has('wksselect') && array_key_exists($i, $request->wksselect) && $request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null)) {
                            $k = $i+1;

                            //$name_lap1 = explode('|', $request->wksselect[$i]);
                            $name_lap1 = $request->wksselect[$i];
                            $lab = Labs::find($name_lap1);

                            $id = [];
                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'name_lap' => (!is_null($lab) ? $lab->lab_name : null),
                                    'detail_product' => (!is_null($lab) ? $lab->id : null),
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                    'status' => $status1,
                                    'example_id' => $data_example->id,
                                    'tis_standard' => $request->tis_standard,
                                    'user_create' => $full_name,
                                    'licensee' => $data_example->licensee,
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                // $value_test_list = explode('|', $request->input('wkslist_test')[$i.'-'.$n][$test]);
                                                $value_test_list = !empty($request->input('wkslist_test')[$i.'-'.$n][$test]);

                                                $test_table = new SaveExampleMapLapDetail();
                                                $test_table->test_id = $value_test_list;
                                                $test_table->example_id = $data_maplap->example_id;
                                                $test_table->maplap_id = $data_maplap->id;
                                                // $test_table->type = $value_test_list[1];
                                                $test_table->save();

                                            }
                                        }

                                    }
                                }
                            }

                        }elseif ($request->input('wkslist_list')[$i] != null) {
                            $k = $i+1;
                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                    'status' => $status1,
                                    'example_id' => $data_example->id,
                                    'user_create' => $full_name,
                                    'licensee' => $data_example->licensee,
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                // $value_test_list = explode('|', $request->input('wkslist_test')[$i.'-'.$n][$test]);
                                                $value_test_list = !empty($request->input('wkslist_test')[$i.'-'.$n][$test]);

                                                $test_table = new SaveExampleMapLapDetail();
                                                $test_table->test_id = $value_test_list;
                                                $test_table->example_id = $data_maplap->example_id;
                                                $test_table->maplap_id = $data_maplap->id;
                                                // $test_table->type = $value_test_list[1];
                                                $test_table->save();

                                            }
                                        }

                                    }
                                }
                            }
                        } elseif ($request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {
                            $k = $i+1;
                            //$name_lap1 = explode('|', $request->wksselect[$i]);

                            $name_lap1 = $request->wksselect[$i];
                            $lab = Labs::find($name_lap1);

                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'name_lap' => (!is_null($lab) ? $lab->lab_name : null),
                                'detail_product' => (!is_null($lab) ? $lab->id : null),
                                'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                'status' => $status1,
                                'example_id' => $data_example->id,
                                'tis_standard' => $request->tis_standard,
                                'user_create' => $full_name,
                                'licensee' => $data_example->licensee,
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();

                        } else {
                            $k = $i+1;
                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                'status' => '-',
                                'example_id' => $data_example->id,
                                'user_create' => $full_name,

                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();
                        }

                    }

                }

            } else if($request->get('type_save') == 'some'){
                if ($request->wsk_row != null) {
                    $count_wsk_row = count($request->wsk_row);

                    for ($i = 0; $i < $count_wsk_row; $i++) {

                        $status_send = 'ส่ง';

                        if ($request->input('wkslist_list')[$i] != null && $request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {
                            $k = $i+1;
                            // $name_lap1 = explode('|', $request->wksselect[$i]);

                            $name_lap1 = $request->wksselect[$i];
                            $lab = Labs::find($name_lap1);

                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'name_lap' => (!is_null($lab) ? $lab->lab_name : null),
                                    'detail_product' => (!is_null($lab) ? $lab->id : null),
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                    'status' => $request->get('check_status'),
                                    'example_id' => $data_example->id,
                                    'tis_standard' => $request->tis_standard,
                                    'user_create' => $full_name,
                                    'licensee' => $data_example->licensee,
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                // $value_test_list = explode('|', $request->input('wkslist_test')[$i.'-'.$n][$test]);
                                                $value_test_list = !empty($request->input('wkslist_test')[$i.'-'.$n][$test]);

                                                $test_table = new SaveExampleMapLapDetail();
                                                $test_table->test_id = $value_test_list;
                                                $test_table->example_id = $data_maplap->example_id;
                                                $test_table->maplap_id = $data_maplap->id;
                                                // $test_table->type = $value_test_list[1];
                                                $test_table->save();

                                            }
                                        }

                                    }
                                }
                            }

                        }elseif ($request->input('wkslist_list')[$i] != null) {
                            $k = $i+1;
                            for ($j = 0; $j < count($request->input('wkslist_list')[$i]); $j++) {
                                $value_wsklist_list = explode('|', $request->input('wkslist_list')[$i][$j]);
                                $n = $value_wsklist_list[0];

                                $data_maplap = new SaveExampleMaplap([
                                    'num_row' => $k,
                                    'detail_product_maplap' => $value_wsklist_list[0],
                                    'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                    'status' => $request->get('check_status'),
                                    'example_id' => $data_example->id,
                                    'user_create' => $full_name,
                                    'licensee' => $data_example->licensee,
                                ]);

                                $data_maplap->status_send = $status_send;
                                $data_maplap->type_send = $request->get('type_save');
                                if($data_maplap->save()){

                                    if(isset($request->input('wkslist_test')[$i.'-'.$n])){

                                        for ($test = 0; $test < count($request->input('wkslist_test')[$i.'-'.$n]); $test++) {
                                            if($request->input('wkslist_test')[$i.'-'.$n][$test] != null){

                                                // $value_test_list = explode('|', $request->input('wkslist_test')[$i.'-'.$n][$test]);
                                                $value_test_list = !empty($request->input('wkslist_test')[$i.'-'.$n][$test]);

                                                $test_table = new SaveExampleMapLapDetail();
                                                $test_table->test_id = $value_test_list;
                                                $test_table->example_id = $data_maplap->example_id;
                                                $test_table->maplap_id = $data_maplap->id;
                                                // $test_table->type = $value_test_list[1];
                                                $test_table->save();

                                            }
                                        }

                                    }
                                }
                            }
                        } elseif ($request->wksselect[$i] != 'เลือกชื่อหน่วยตรวจสอบ' && $request->wksselect[$i] != null) {

                            $k = $i+1;
                            // $name_lap1 = explode('|', $request->wksselect[$i]);
                            $name_lap1 = $request->wksselect[$i];

                            $lab = Labs::find($name_lap1);

                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'name_lap' => (!is_null($lab) ? $lab->name_lap : null),
                                'detail_product' => (!is_null($lab) ? $lab->id : null),
                                'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                'status' => $request->get('check_status'),
                                'example_id' => $data_example->id,
                                'tis_standard' => $request->tis_standard,
                                'user_create' => $full_name,
                                'licensee' => $data_example->licensee,
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();

                        } else {
                            $k = $i+1;
                            $data_maplap = new SaveExampleMaplap([
                                'num_row' => $k,
                                'no_example_id' => 'SAM' . $date_auto . '-' . $data_example->id . '-' . $k,
                                'status' => '-',
                                'example_id' => $data_example->id,
                                'user_create' => $full_name,
                                'licensee' => $data_example->licensee,
                            ]);
                            $data_maplap->status_send = $status_send;
                            $data_maplap->type_send = $request->get('type_save');
                            $data_maplap->save();
                        }

                    }

                }
            }


            return response()->json([
                "status" => "success"
            ]);
        }

    }

    public function destroy($id, Request $request)
    {
        $model = str_slug('save_example', '-');
        if (auth()->user()->can('delete-' . $model)) {

            $requestData = $request->all();

            if (array_key_exists('cb', $requestData)) {
                $ids = $requestData['cb'];
                $db = new save_example;
                save_example::whereIn($db->getKeyName(), $ids)->delete();
            } else {
                save_example::destroy($id);
            }

            return redirect('save_example/save_example')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function get_filter_tb4_License(Request $request)
    {
        $tb3_Tisno = $request->get('tb3_Tisno');

        //ขอบข่ายตามมาตรฐาน
        $query_scope = LabsScope::where('tis_tisno', $tb3_Tisno)->select('lab_id');
        $labs = Labs::whereIn('id', $query_scope)->get();

        $tis = DB::table('tb4_tisilicense')
                 ->select()
                 ->where('tbl_tisiNo', $tb3_Tisno)
                 ->where('tbl_tradeName','<>','')
                 ->groupBy('tbl_tradeName')
                 ->pluck('tbl_tradeName');

        $maplap[] = DB::table('ros_rbasicdata_maplab AS a')
            ->select()
            ->where('a.tis_number', $tb3_Tisno)
            ->pluck('a.lab_id');

        foreach ($maplap as $id) {
            $user[] = DB::table('tb10_nsw_lite_trader as c')->select()
                ->wherein('c.trader_autonumber', $id)
                ->get();
        }

        $unit[] = DB::table('tb3_tis AS a')
            ->select()
            ->where('a.tb3_Tisno', $tb3_Tisno)
            ->pluck('a.unitcode_id');
        foreach ($unit as $id) {
            $unit_list[] = DB::table('tb_unitcode as c')->select()
                ->wherein('c.Auto_num', $id)
                ->pluck('name_unit');
        }
//        dd($tb3_Tisno);
        return response()->json([
            "status" => "success",
            'data' => $tis,
            // 'data_group' => $std_group,
            // 'data_group_id' => $std_group_id,
            // 'data_head' => $std_head,
            // 'data_type' => $std_type,
            // 'data_type_id' => $std_head_id,
            'data_user' => $user,
            'labs' => $labs,
            'data_unit' => $unit_list,
        ]);
    }

    //ดึงรายการทดสอบตาม Lab และ มาตรฐาน
    public function get_lab_test_items(Request $request){

        $lab_id    = $request->get('lab_id');
        $tis_tisno = $request->get('tis_tisno');

        $lab_scope_query = LabsScope::where('tis_tisno', $tis_tisno)->where('lab_id', $lab_id)->select('test_item_id');
        $test_items = TestItem::whereIn('id', $lab_scope_query)->where('input_result', 1)->with('test_item_main')->get();

        $responses = [];
        foreach ($test_items as $test_item) {
            $main_test = $test_item->test_item_main;
            if(!is_null($main_test)){//มีข้อมูลหัวข้อทดสอบ
                if($test_item->id==$main_test->id){//รายการที่วนอยู่คือหัวข้อทดสอบ
                    $responses[$test_item->id] = $test_item->no.' '.$test_item->title;
                }else{
                    $responses[$test_item->id] = $main_test->no.' '.$main_test->title.' -> '.$test_item->no.' '.$test_item->title;
                }
            }else{//ไม่มีหัวข้อทดสอบ
                $responses[$test_item->id] = $test_item->no.' '.$test_item->title;
            }
        }

        return response()->json($responses);
    }


    public function  GetFilterTb4License($standard_id = null)
    {
        $tis = DB::table('tb4_tisilicense')->select()->where('tbl_tisiNo', $standard_id)->where('tbl_tradeName','<>','')->groupBy('tbl_tradeName')->pluck('tbl_tradeName','Autono');
        return response()->json($tis);
    }


    public function get_head(Request $request)
    {
        $head = $request->get('head');

        if ($head != 'เลือกผลิตภัณฑ์ที่ขอรับใบอนุญาต') {
            $test[] = DB::table('ros_rbasicdata_standard_tisi as c')
                ->select()
                ->where('c.tis_number', $head)
                ->pluck('c.id');
            $std_tisi[] = DB::table('ros_rbasicdata_stdhead as c')->select()
                ->where('c.std_hg_id', $head)
                ->get();
            $std_tisi_id[] = DB::table('ros_rbasicdata_stdhead as c')->select()
                ->where('c.std_hg_id', $head)
                ->pluck('c.id');
            foreach ($std_tisi_id as $id) {
                $std_type[] = DB::table('ros_rbasicdata_producttype as c')->select()
                    ->wherein('c.stdhead_id', $id)
                    ->where('c.feature_type', '=', '1')
                    ->get();
            }
            $std_head[] = DB::table('ros_rbasicdata_stdhead AS a')
                ->select()
                ->where('a.std_id', $id)
                ->orderBy('a.ordering')
                ->pluck('a.id');
//            dd($std_tisi_id);

        } else {
            $std_tisi = null;
        }
        return response()->json([
            "status" => "success",
            'data' => $std_tisi,
            'data_type' => $std_type,
            'data_type_id' => $std_tisi_id,
            'data_head' => $test,

        ]);
    }

    public function get_type2(Request $request)
    {
        $id = $request->get('type');
        $std_type[] = DB::table('ros_rbasicdata_producttype_refer as c')->select()
            ->where('c.classify_name_refer', $id)
            ->pluck('c.producttype_id');

        $std_type_check = DB::table('ros_rbasicdata_producttype_refer as c')->select()
            ->where('c.classify_name_refer', $id)
            ->first();
        if ($std_type_check != null) {
            foreach ($std_type as $id) {
                $std_type_test[] = DB::table('ros_rbasicdata_producttype as c')->select()
                    ->wherein('c.id', $id)
                    ->get();
            }
            return response()->json([
                "status" => "success",
                'data' => $std_type_test,
            ]);
        }

    }

    public function get_detail_maplap(Request $request)
    {
        $id = $request->get('id');
        $tis = $request->get('Tis');
        $maplap[] = DB::table('ros_rbasicdata_maplab AS a')
            ->select()
            ->where('a.lab_id', $id)
            ->where('a.tis_number', $tis)
            ->get();
        return response()->json([
            "status" => "success",
            'data' => $maplap,
        ]);
    }

    public function detail($Example_ID)
    {
        $previousUrl = app('url')->previous();

        $data = SaveExample::find($Example_ID);
        $data['sample_submission_date'] = $data['sample_submission_date']?Carbon::createFromFormat("Y-m-d",$data['sample_submission_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
        $data_detail = SaveExampleDetail::query()->where('id_example', $Example_ID)->get();
        $data_lap = SaveExampleMaplap::query()->where('example_id', $Example_ID)->groupby('no_example_id')->get();
        // $data_lap1 = SaveExampleMaplap::query()->where('example_id', $Example_ID)->where('num_row', '=', '1')->get();
        // $data_lap1_sel = SaveExampleMaplap::query()->where('example_id', $Example_ID)->where('num_row', '=', '1')->first();
        // $data_lap2 = SaveExampleMaplap::query()->where('example_id', $Example_ID)->where('num_row', '=', '2')->get();
        // $data_lap2_sel = SaveExampleMaplap::query()->where('example_id', $Example_ID)->where('num_row', '=', '2')->first();
        // $data_lap3 = SaveExampleMaplap::query()->where('example_id', $Example_ID)->where('num_row', '=', '3')->get();
        // $data_lap3_sel = SaveExampleMaplap::query()->where('example_id', $Example_ID)->where('num_row', '=', '3')->first();
        $attach_path = $this->attach_path;
        $single_attach = json_decode($data->single_attach);
        $single_attach = !is_null($single_attach)?$single_attach:(object)['file_name'=>'', 'file_client_name'=>''];

        // dd($single_attach);

        return view('ssurv.save_example.detail', [
            'data' => $data,
            'data_detail' => $data_detail,
            'data_lap' => $data_lap,
            'attach_path' => $attach_path,
            'single_attach' => $single_attach,
            'previousUrl' => $previousUrl
            ]);
    }

    public function save_attach(Request $request){

            $single_attach_example = SaveExample::find($request->get('example_id'));

                if ($single_file = $request->file('single_attach')) {
                    //ข้อมูลไฟล์แนบ
                    $for_del = json_decode($single_attach_example->single_attach);
                    if($for_del){
                        Storage::delete($this->attach_path.$for_del->file_name);//ลบไฟล์เก่า
                    }
                    $storagePath = Storage::put($this->attach_path, $single_file);
                    $storageName = basename($storagePath); // Extract the filename

                    $single_attach =  ['file_name'=>$storageName,
                                        'file_client_name'=>$single_file->getClientOriginalName()
                                        ];
                    $single_attach_example->single_attach = json_encode($single_attach, JSON_UNESCAPED_UNICODE);
               } else {
                    $single_attach_example->single_attach = $single_attach_example->single_attach;

                    }


            $single_attach_example->save();

              return response()->json([
                "status" => "success",
                "id" => $single_attach_example->id,
            ]);
    }


    public function print($example_id)
    {
        $data                = SaveExample::find($example_id);
        $data_detail         = SaveExampleDetail::query()->where('id_example', $example_id)->get();
        $data_lap            = SaveExampleMaplap::query()->where('example_id', $example_id)->groupby('no_example_id')->get();

        $data['data_detail'] = $data_detail;
        $data['data_lap']    = $data_lap;

        if( !empty($data->sample_recipient) ){
            $user            = User::Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"),  str_replace(' ', '', $data->sample_recipient )  )->first();
            $data['data_user_register'] =   !empty( $user )?$user:null;
        }


    //     "tis_standard" => "10-2529"
    // "licensee" => "นายเตี่ยวสุย แซ่แต้"
    // "verification" => "ตรวจสอบที่โรงงาน"
    // "sample_submission" => ""
    // "stored_add" => ""
    // "room_anchor" => ""
    // "sample_submission_date" => "25/12/2019"
    // "sample_pay" => "ฟฟฟ"
    // "permission_submiss" => "ฟฟฟ"
    // "tel_submiss" => "0806075600"
    // "email_submiss" => "a@a.com"
    // "sample_collect_date" => null
    // "sample_recipient" => "Admin TISI"
    // "permission_receive" => "เจ้าหน้าที่ สมอ."
    // "tel_receive" => "0858338944"
    // "email_receive" => "tisi@thai.co.th"
    // "sample_return" => "ไม่รับคืน"
    // "status" => "3"
    // "created_at" => "2019-12-25 09:34:30"
    // "updated_at" => "2019-12-25 10:19:34"
    // "user_create" => "Admin TISI"
    // "remark" => null
    // "user_register" => "SGS Thailand"
    // "remake_assign" => null
    // "remake_report" => null
    // "remake_test" => "ผ่านการทดสอบ แต่มีข้อผิดพลาดนิดหน่อย ทำการปรับแก้"
    // "res_status" => "ผ่าน"
    // "no" => "SAM62-175"
    // "status2" => "ประเมินผลแล้ว"
    // "status3" => "ผก. ประเมินผลแล้ว"
    // "user_assign" => "Admin TISI"
    // "test_date" => null
    // "chief_commit" => "yes"
    // "remark_chief" => "ดำเนินการต่อได้เลย"
    // "type_send" => "some"
    // "licensee_no" => "ท5137-2/10"
    // "user_test" => "SGS Thailand"
    // "user_commit" => "Admin TISI"

        // $data = [
        //     'tis_standard'                => $data->tis_standard,
        //     'licensee'                => $data->licensee,
        //     'licensee_no'                => $data->licensee_no,
        // ];

                // dd($data);


        $pdf = PDF::loadView('ssurv.save_example.pdf.test', $data);
        return $pdf->stream('test-thai.pdf');

        // return view('ssurv.save_example.detail', [
        //     'data' => $data,
        //     'data_detail' => $data_detail,
        //     'data_lap' => $data_lap,
        //     ]);
    }

    public function export_word($example_id)
    {    
        try{
            $save_example        = SaveExample::with([
                                                        'details.license_detail', 
                                                        'save_example_map_lap' => function($query){
                                                            return $query->with([
                                                                            'save_example_map_lap_self' => function($query){
                                                                                return $query->with(['details.test_item.test_item_parent', 'license_detail']);
                                                                            }
                                                                        ])->groupBy('no_example_id');
                                                        }
                                                    ])->find($example_id);

            $map_lap_fist = $save_example->save_example_map_lap->first();
            $details = $save_example->details;
            $map_laps = $save_example->save_example_map_lap;
            $user = User::with('subdepart.department')->where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), str_replace(' ', '', @$save_example->sample_recipient))->first();
            $checkedBox = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
            $unCheckedBox = '<w:sym w:font="Wingdings" w:char="F0A8"/>'; 
            $check1 = (@$save_example->sample_return == 'รับคืน')?$checkedBox:$unCheckedBox;
            $check2 = (@$save_example->sample_return == 'ไม่รับคืน')?$checkedBox:$unCheckedBox;
            $check3 = (@$save_example->type_send == 'all')?$checkedBox:$unCheckedBox;
            $check4 = (@$save_example->type_send == 'some')?$checkedBox:$unCheckedBox;
            $check5 = (@$save_example->verification == 'ตรวจสอบที่หน่วยตรวจสอบ')?$checkedBox:$unCheckedBox;
            $check6 = (@$save_example->verification == 'ตรวจสอบที่โรงงาน')?$checkedBox:$unCheckedBox;
            $check7 = (@$save_example->sample_submission == 'ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง')?$checkedBox:$unCheckedBox;
            $check8 = (@$save_example->sample_submission == 'กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง')?$checkedBox:$unCheckedBox;
            $check9 = (@$save_example->stored_add == 'โรงงาน')?$checkedBox:$unCheckedBox;
            $check10 = (@$save_example->stored_add == 'สมอ. ห้อง')?$checkedBox:$unCheckedBox;
         
            $fontStyle = new Font;
            $templateProcessor = new TemplateProcessor(public_path('/word/Example-Word.docx'));
            $xmlEscaper = new \PhpOffice\PhpWord\Escaper\Xml();
    
            $templateProcessor->setValue('ss_date', HP::revertDateThaiShort(@$save_example->sample_submission_date, true));
            $templateProcessor->setValue('name_lap', @$map_lap_fist->name_lap);
            $templateProcessor->setValue('depart', preg_replace("/[^a-z\d]/i", '', @$user->DepartName));
            $templateProcessor->setValue('s_depart', preg_replace("/[^a-z\d]/i", '', @$user->DepartmentName));
            // $templateProcessor->setValue('depart', preg_replace("/[^a-z\d]/i", '', @$user->DepartmentName));
            $templateProcessor->setValue('ck1', @$check1);
            $templateProcessor->setValue('ck2', @$check2);
            $templateProcessor->setValue('ck3', @$check3);
            $templateProcessor->setValue('ck4', @$check4);
            $templateProcessor->setValue('no', @$save_example->no);
            $templateProcessor->setValue('tis_standard', @$save_example->tis_standard);
            $templateProcessor->setValue('licensee', @$save_example->licensee);
            $templateProcessor->setValue('licensee_no', @$save_example->licensee_no);
            
            $templateProcessor->cloneRow('td_no', $details->count());

            $i = 1;
            foreach ($details as $detail) {
                $templateProcessor->setValue('td_no#'.$i, $i);     
             
                $text_size_detail = new TextRun();
                \PhpOffice\PhpWord\Shared\Html::addHtml($text_size_detail, $detail->SizeDetial);
          
                $templateProcessor->setComplexValue('td_detail#'.$i, $text_size_detail);
                $templateProcessor->setValue('td_num#'.$i, !empty($detail->number)?$detail->number:'-');
                $templateProcessor->setValue('td_numex#'.$i, !empty($detail->num_ex)?$detail->num_ex:'-');
                $i++;
            }
            
            $templateProcessor->cloneRow('tl_no', $map_laps->count());
            $i = 1;       
            foreach ($map_laps as $lap) {
                $templateProcessor->setValue('tl_no#'.$i, $i);
                $templateProcessor->setValue('tl_name_lab#'.$i, !empty($lap->name_lap)?$lap->name_lap:'-');

                $text_self_size_detail = new TextRun();
                \PhpOffice\PhpWord\Shared\Html::addHtml($text_self_size_detail, $lap->SelfSizeDetialExportWord2);
            
                $templateProcessor->setComplexValue('tl_check#'.$i, $text_self_size_detail);
                $templateProcessor->setValue('tl_test#'.$i, !empty($lap->SelfDetailItemExportWord)?$lap->SelfDetailItemExportWord:'-');
                $templateProcessor->setValue('tl_ex_no#'.$i, $lap->no_example_id);
                $i++;
            }

            $templateProcessor->setValue('more_details', @$save_example->more_details);
            $templateProcessor->setValue('ck5', @$check5);
            $templateProcessor->setValue('ck6', @$check6);
            $templateProcessor->setValue('ck7', @$check7);
            $templateProcessor->setValue('ck8', @$check8);
            $templateProcessor->setValue('ck9', @$check9);
            $templateProcessor->setValue('ck10', @$check10);
            $templateProcessor->setValue('room_anchor', @$save_example->room_anchor);
            $templateProcessor->setValue('sample_pay', @$save_example->sample_pay);
            $templateProcessor->setValue('permission_submiss', $xmlEscaper->escape(@$save_example->permission_submiss));
            $templateProcessor->setValue('tel_submiss', @$save_example->tel_submiss);
            $templateProcessor->setValue('email_submiss', @$save_example->email_submiss);
            $templateProcessor->setValue('sample_recipient', @$save_example->sample_recipient);
            $templateProcessor->setValue('permission_receive', @$save_example->permission_receive);
            $templateProcessor->setValue('tel_receive', @$save_example->tel_receive);
            $templateProcessor->setValue('email_receive', @$save_example->email_receive);
            $templateProcessor->setValue('ck11', @$check2);
            $templateProcessor->setValue('ck12', @$check1);
    
            $title = 'ใบรับ - นำส่งตัวอย่าง'.(!empty($save_example->tis_standard)?"($save_example->tis_standard)":null).date('Ymd_His').'.docx';
            $templateProcessor->saveAs(storage_path($title));
            $fontStyle->setName('THSarabunPSK');
            return response()->download(storage_path($title));     
        }catch (Exception $e){
            // dd($e->getMessage());
        }
   
    }

    public function get_filter_tb4_License_no(Request $request)
    {
        $tis_standard = $request->get('tis_standard');
        $tb4_tradeName = $request->get('tb4_tradeName');

        $tis_license_no = DB::table('tb4_tisilicense')->select()->where('tbl_tisiNo', $tis_standard)->where('tbl_tradeName', $tb4_tradeName)->pluck('tbl_licenseNo');
        // $payer = $tis_tax->tbl_taxpayer;

        // if($payer != ""){
        //     $tis_license_no = DB::table('tb4_tisilicense')->select()->where('tbl_taxpayer', $payer)->pluck('tbl_licenseNo');
        // }else{
        //     $tis_license_no = DB::table('tb4_tisilicense')->select()->where('tbl_tradeName', $tb4_tradeName)->pluck('tbl_licenseNo');
        // }


        return response()->json([
            "status" => "success",
            'data' => $tis_license_no,
        ]);
    }

    public function get_item_detail(Request $request)
    {
        $tb4_licenseNo = $request->get('tb4_licenseNo');

        $sizeDetial[] = DB::table('tb4_licensesizedetial')->select()->where('licenseNo', 'like',  '%'.$tb4_licenseNo.'%')->orderBy('itemNo', 'asc')->pluck('sizeDetial')->sortBy('itemNo');
        $autoNo[] = DB::table('tb4_licensesizedetial')->select()->where('licenseNo', 'like',  '%'.$tb4_licenseNo.'%')->orderBy('itemNo', 'asc')->pluck('autoNO')->sortBy('itemNo');

        return response()->json([
            "status" => "success",
            'data' => $sizeDetial,
            'autoNo' => $autoNo,
        ]);
    }

    public function get_result(Request $request)
    {
        $tb3_standard = $request->get('tis_standard');

        $result_product = DB::table('result_product')->select()->where('tis_standard', $tb3_standard)->where('status', '1')->first();

        $result_product_detail  = [];
        $result_product_detail2 = [];
        $result_product_detail3 = [];

        if(!is_null($result_product)){
            $result_product_detail = DB::table('result_product_detail as r')->select()->where('r.id_result', $result_product->id)->pluck('r.id');
            $result_product_detail2[] = DB::table('result_product_detail as r')->select()->where('r.id_result', $result_product->id)->pluck('r.name_result');
            $result_product_detail3[] = DB::table('result_product_detail as r')->select()->where('r.id_result', $result_product->id)->pluck('r.type_result');
        }

        return response()->json([
            "status" => "success",
            'data' => $result_product_detail,
            'autoNo' => $result_product_detail2,
            'type' => $result_product_detail3,
        ]);
    }

    public function add_sub_department(Request $request)
    {
        $department_id = $request->get('department_id');
        // $sub_departments = SubDepartment::where('did', $department_id)->pluck('sub_id');
        // $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno','sub_id');
        $subDepartments = SubDepartment::where('did', $department_id)->pluck('sub_departname','sub_id');


        return response()->json([
            "status" => "success",
            'data' => $subDepartments,
        ]);
    }

}

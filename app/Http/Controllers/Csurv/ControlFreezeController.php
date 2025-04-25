<?php

namespace App\Http\Controllers\Csurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\control_freeze;
use App\Models\Csurv\ControlFreeze;
use App\Models\Csurv\ControlFreezeFreezeList;
use App\Models\Csurv\ControlFreezeSeizureList;
use App\Models\Csurv\Tis4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;


use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use HP;
use SHP;
class ControlFreezeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'csurv_attach/control_freeze/';
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_assessment'] = $request->get('filter_assessment', '');
        $filter['filter_start_month'] = $request->get('filter_start_month', '');
        $filter['filter_start_year'] = $request->get('filter_start_year', '');
        $filter['filter_end_month'] = $request->get('filter_end_month', '');
        $filter['filter_end_year'] = $request->get('filter_end_year', '');
        $filter['filter_auto_id_doc']       = $request->get('filter_auto_id_doc', '');
        $filter['filter_department'] = $request->get('filter_department', '');
        $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
        $filter['filter_document_number']       = $request->get('filter_document_number', '');
        $Query = new ControlFreeze;

        $assessment = ['ยึด/อายัด', 'ถอนยึด/อายัด' , 'ยกเลิก'];
        $Query = $Query->wherein('status', $assessment) ->orderby('id','desc');

        if ($filter['filter_assessment'] != '') {
            $Query = $Query->where('status', $filter['filter_assessment']);
        }
        if ($filter['filter_start_month'] != '') {
            $Query = $Query->where('created_at', '>=', $filter['filter_start_year'] . '-' . $filter['filter_start_month'] . '-01' . ' 00:00:00');
        } 

        if ($filter['filter_end_month'] != '') {
            $Query = $Query->where('created_at', '<=', $filter['filter_end_year'] . '-' . $filter['filter_end_month'] . '-31' . ' 00:00:00');
        }

        if ($filter['filter_auto_id_doc'] != '') {
            $Query = $Query->where('auto_id_doc', 'like', '%'.$filter['filter_auto_id_doc'].'%');
        }
        if ($filter['filter_document_number'] != '') {
            $Query = $Query->where('document_number', 'like', '%'.$filter['filter_document_number'].'%');
        }
        if ($filter['filter_department']!='') {
            $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
            $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
            $Query = $Query->whereIn('tis_standard', $tis_subdepartments);
            $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
        }else{
            $subDepartments = [];
        }
        if ($filter['filter_sub_department']!='') {
            $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
            $Query = $Query->whereIn('tis_standard', $tis_subdepartments);
        }
        // if($filter['filter_title']!=''){
        //     $Autonos = Tis4::where('tbl_tradeName', 'like', '%'.$filter['filter_title'].'%')->pluck('Autono');
        //     $Query = $Query->whereIn('tradeName', $Autonos);
        // }
            
        $control_freeze = $Query->sortable()->paginate($filter['perPage']);
        // return $control_freeze;
        $temp_num = $control_freeze->firstItem();

        return view('csurv.control_freeze.index', compact('control_freeze', 'filter', 'temp_num','subDepartments'));
    }

    public function create()
    {
        $data = new ControlFreeze;
        $data_freeze =  [new ControlFreezeFreezeList];
        $data_seizure = [new ControlFreezeSeizureList];
        //ไฟล์แนบ
        $attachs = json_decode($data['attach']);
        $attachs = !is_null($attachs) && count($attachs) > 0 ? $attachs : [(object)['file_note' => '', 'file_name' => '']];
        $attach_path = $this->attach_path;
        return view('csurv.control_freeze.create',['data' => $data, 
                                                  'data_freeze' => $data_freeze, 
                                                  'data_seizure' => $data_seizure,
                                                  'attachs' => $attachs,
                                                  'attach_path' => $attach_path
                                                ]);
    }
    public function show($id)
    {
        $data = ControlFreeze::find($id);


        $data_freeze = ControlFreezeFreezeList::query()->where('id_freeze', $id)->get();
        $data_seizure = ControlFreezeSeizureList::query()->where('id_freeze', $id)->get();
        //ไฟล์แนบ
        $attachs = json_decode($data['attach']);
        $attachs = !is_null($attachs) && count($attachs) > 0 ? $attachs : [(object)['file_note' => '', 'file_name' => '']];
        $attach_path = $this->attach_path;

        return view('csurv.control_freeze.show', ['data' => $data, 
                                                  'data_freeze' => $data_freeze, 
                                                  'data_seizure' => $data_seizure,
                                                  'attachs' => $attachs,
                                                  'attach_path' => $attach_path
                                                ]);
    }
    public function store(Request $request)
    {

            $requestData = $request->all();

                $status = 'ยึด/อายัด';
                $date_freeze = null;
               $check_officer = auth()->user()->FullName;

    
            $check_data = DB::table('control_freeze')->first();
            if ($check_data != null) {
                $data_sql = DB::table('control_freeze')->orderByDesc('id')->first();
                $ex1_data = explode('S', $data_sql->auto_id_doc);
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
                $auto_id_doc_check = 'S' . $res_data . '/' . $ex2_data[1];
            } else {
                $data_date = date('y') + 43;
                $auto_id_doc_check = 'S0001/' . $data_date;
            }
            // เลขที่เอกสาร
              $requestData['auto_id_doc'] = $auto_id_doc_check;

         
            //ไฟล์แนบ เพิ่มเติม (ไฟล์ใหม่)
            $files = $request->file('attachs');
              if ($files) {
                foreach ($files as $key => $file) {
                    $storagePath = Storage::disk('uploads')->put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename
                    $attachs[] = [
                        'file_name' => $storageName,
                        'file_client_name' => $file->getClientOriginalName(),
                        'file_note' =>  $requestData['attach_notes'][$key] 
                    ];
                    
                }
                $requestData['attach'] = json_encode(array_values($attachs));
            }
          
            $requestData['status'] = $status;
            $requestData['date_freeze'] = $date_freeze;
            $requestData['check_officer'] = $check_officer ;
            $ControlFreeze = ControlFreeze::create($requestData);
            if ($request->get('num_row1') != null) {
                $data_list1 = ControlFreezeSeizureList::query()->where('id_freeze', $ControlFreeze->id)->get();
                foreach ($data_list1 as $list1) {
                    $delete1 = ControlFreezeSeizureList::find($list1->id);
                    $delete1->delete();
                }
                for ($i = 0; $i < count($request->num_row1); $i++) {
                    $data_table1 = new ControlFreezeSeizureList([
                        'id_freeze' => $ControlFreeze->id,
                        'list_seizure' => $request->list_seizure[$i],
                        'amount_seizure' => $request->amount_seizure[$i],
                        'unit_seizure' => $request->unit_seizure[$i],
                        'value_seizure' => $request->value_seizure[$i],
                    ]);
                    $data_table1->save();
                }
            }
            if ($request->get('num_row2') != null) {
                $data_list2 = ControlFreezeFreezeList::query()->where('id_freeze', $ControlFreeze->id)->get();
                foreach ($data_list2 as $list1) {
                    $delete2 = ControlFreezeFreezeList::find($list1->id);
                    $delete2->delete();
                }
                for ($i = 0; $i < count($request->num_row2); $i++) {
                    $data_table2 = new ControlFreezeFreezeList([
                        'id_freeze' => $ControlFreeze->id,
                        'list_freeze' => $request->list_freeze[$i],
                        'amount_freeze' => $request->amount_freeze[$i],
                        'unit_freeze' => $request->unit_freeze[$i],
                        'value_freeze' => $request->value_freeze[$i],
                    ]);
                    $data_table2->save();
                }
            }

            return redirect('csurv/control_freeze')->with('flash_message', 'BudgetSetting added!');
   
    }
    public function edit($id)
    {
        $data = ControlFreeze::find($id);


        $data_freeze = ControlFreezeFreezeList::query()->where('id_freeze', $id)->get();
        $data_seizure = ControlFreezeSeizureList::query()->where('id_freeze', $id)->get();
        //ไฟล์แนบ
        $attachs = json_decode($data['attach']);
        $attachs = !is_null($attachs) && count($attachs) > 0 ? $attachs : [(object)['file_note' => '', 'file_name' => '']];
        $attach_path = $this->attach_path;

        return view('csurv.control_freeze.edit', ['data' => $data, 
                                                  'data_freeze' => $data_freeze, 
                                                  'data_seizure' => $data_seizure,
                                                  'attachs' => $attachs,
                                                  'attach_path' => $attach_path
                                                ]);
    }


    public function update(Request $request, $id)
    {
        $model = str_slug('control_freeze','-');
        if(auth()->user()->can('edit-'.$model)) {


            $ControlFreeze = ControlFreeze::findOrFail($id);
            if ($request->get('check_status') != null) {
                $status = 'ถอนยึด/อายัด';
                $date_freeze = $this->convertDate($request->get('date_freeze'),false);
            } else {
                $status = 'ยึด/อายัด';
                $date_freeze = null;
            }


            $requestData = $request->all();
            $requestData['status'] = $status;
            $requestData['date_freeze'] = $date_freeze;

            //ข้อมูลไฟล์แนบ
            $attachs = array_values((array)json_decode($ControlFreeze->attach));

            //ไฟล์แนบ ที่ถูกกดลบ
            foreach ($attachs as $key => $attach) {

                if (in_array($attach->file_name, isset($requestData['attach_filenames']) ? $requestData['attach_filenames'] : []) === false) {//ถ้าไม่มีไฟล์เดิมกลับมา

                    unset($attachs[$key]);
                    Storage::disk('uploads')->delete($this->attach_path . $attach->file_name);
                }
            }

            //ไฟล์แนบ เพิ่มเติม (ไฟล์ใหม่)
            $files = $request->file('attachs');
              if ($files) {
                foreach ($files as $key => $file) {
                    $storagePath = Storage::disk('uploads')->put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename
                    $attachs[] = [
                        'file_name' => $storageName,
                        'file_client_name' => $file->getClientOriginalName(),
                        'file_note' =>  $requestData['attach_notes'][$key] 
                    ];
                    
                }
                $requestData['attach'] = json_encode(array_values($attachs));
            }

        
      
            $ControlFreeze->update($requestData);

            if ($request->get('num_row1') != null) {
                $data_list1 = ControlFreezeSeizureList::query()->where('id_freeze', $ControlFreeze->id)->get();
                foreach ($data_list1 as $list1) {
                    $delete1 = ControlFreezeSeizureList::find($list1->id);
                    $delete1->delete();
                }
                for ($i = 0; $i < count($request->num_row1); $i++) {
                    $data_table1 = new ControlFreezeSeizureList([
                        'id_freeze' => $ControlFreeze->id,
                        'list_seizure' => $request->list_seizure[$i],
                        'amount_seizure' => $request->amount_seizure[$i],
                        'unit_seizure' => $request->unit_seizure[$i],
                        'value_seizure' => $request->value_seizure[$i],
                    ]);
                    $data_table1->save();
                }
            }
            if ($request->get('num_row2') != null) {
                $data_list2 = ControlFreezeFreezeList::query()->where('id_freeze', $ControlFreeze->id)->get();
                foreach ($data_list2 as $list1) {
                    $delete2 = ControlFreezeFreezeList::find($list1->id);
                    $delete2->delete();
                }
                for ($i = 0; $i < count($request->num_row2); $i++) {
                    $data_table2 = new ControlFreezeFreezeList([
                        'id_freeze' => $ControlFreeze->id,
                        'list_freeze' => $request->list_freeze[$i],
                        'amount_freeze' => $request->amount_freeze[$i],
                        'unit_freeze' => $request->unit_freeze[$i],
                        'value_freeze' => $request->value_freeze[$i],
                    ]);
                    $data_table2->save();
                }
            }



            return redirect('csurv/control_freeze')->with('flash_message', 'แก้ไข standard เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function save_data(Request $request)
    {
        if ($request->get('tis_standard') == '-เลือกมาตรฐาน-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกมาตรฐาน!"
            ]);
        }
        if ($request->get('tradeName') == '-เลือกผู้รับใบอนุญาต-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกผู้รับใบอนูญาต!"
            ]);
        }
        $data = new ControlFreeze([
            'tis_standard' => $request->get('tis_standard'),
            'tradeName' => $request->get('tradeName'),
            'tbl_tisiNo' => $request->get('tbl_tisiNo'),
            'owner' => $request->get('owner'),
            'address_no' => $request->get('address_no'),
            'address_industrial_estate' => $request->get('address_industrial_estate'),
            'address_alley' => $request->get('address_alley'),
            'address_road' => $request->get('address_road'),
            'address_village_no' => $request->get('address_village_no'),
            'address_district' => $request->get('address_district'),
            'address_amphoe' => $request->get('address_amphoe'),
            'address_province' => $request->get('address_province'),
            'address_zip_code' => $request->get('address_zip_code'),
            'id_control' => $request->get('id_control'),
            'total_list_seizure' => $request->get('total_list_seizure'),
            'total_value_seizure' => $request->get('total_value_seizure'),
            'total_list_freeze' => $request->get('total_list_freeze'),
            'total_value_freeze' => $request->get('total_value_freeze'),
            'keep_product_address_no' => $request->get('keep_product_address_no'),
            'keep_product_address_village_no' => $request->get('keep_product_address_village_no'),
            'keep_product_address_alley' => $request->get('keep_product_address_alley'),
            'keep_product_address_road' => $request->get('keep_product_address_road'),
            'keep_product_address_province' => $request->get('keep_product_address_province'),
            'keep_product_address_amphoe' => $request->get('keep_product_address_amphoe'),
            'keep_product_address_district' => $request->get('keep_product_address_district'),
            'keep_product_address_zip_code' => $request->get('keep_product_address_zip_code'),
            'check_officer' => $request->get('check_officer'),
            'date_now' => $request->get('date_now'),
            'date_pluck' => $request->get('date_pluck'),
            'pluck_by' => $request->get('pluck_by'),
            'status' => $request->get('status'),
        ]);
        $check_data = DB::table('control_freeze')->first();
        if ($check_data != null) {
            $data_sql = DB::table('control_freeze')->orderByDesc('id')->first();
            $ex1_data = explode('S', $data_sql->auto_id_doc);
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
            $auto_id_doc_check = 'S' . $res_data . '/' . $ex2_data[1];
        } else {
            $data_date = date('y') + 43;
            $auto_id_doc_check = 'S0001/' . $data_date;
        }

        // เลขที่เอกสาร
        if(isset($request->auto_id_doc) && $request->auto_id_doc != ''){  
            $data->auto_id_doc = $request->auto_id_doc;
        }else{
            $data->auto_id_doc = $auto_id_doc_check;
        }
       
        if ($data->save()) {
            if ($request->num_row1 != null) {
                for ($i = 0; $i < count($request->num_row1); $i++) {
                    $data_table1 = new ControlFreezeSeizureList([
                        'id_freeze' => $data->id,
                        'list_seizure' => $request->list_seizure[$i],
                        'amount_seizure' => $request->amount_seizure[$i],
                        'unit_seizure' => $request->unit_seizure[$i],
                        'value_seizure' => $request->value_seizure[$i],
                    ]);
                    $data_table1->save();
                }
            }
            if ($request->num_row2 != null) {
                for ($i = 0; $i < count($request->num_row2); $i++) {
                    $data_table2 = new ControlFreezeFreezeList([
                        'id_freeze' => $data->id,
                        'list_freeze' => $request->list_freeze[$i],
                        'amount_freeze' => $request->amount_freeze[$i],
                        'unit_freeze' => $request->unit_freeze[$i],
                        'value_freeze' => $request->value_freeze[$i],
                    ]);
                    $data_table2->save();
                }
            }

            $ControlFreeze = ControlFreeze::findOrFail($data->id);
            if(!is_null($ControlFreeze)){
                  //ไฟล์แนบ.
                  $requestData = $request->all();
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
                $ControlFreeze->update($requestData);
            }

            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function update_data(Request $request)
    {
        // dd($request->amount_seizure[0]);
        if ($request->get('tis_standard') == '-เลือกมาตรฐาน-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกมาตรฐาน!"
            ]);
        }
        if ($request->get('tradeName') == '-เลือกผู้รับใบอนุญาต-') {
            return response()->json([
                "status" => "error",
                "message" => "กรุณาเลือกผู้รับใบอนูญาต!"
            ]);
        }
        if ($request->get('check_status') != null) {
            $status = 'ถอนยึด/อายัด';
            $date_freeze = $request->get('date_freeze');
        } else {
            $status = 'ยึด/อายัด';
            $date_freeze = null;
        }
        $data = ControlFreeze::find($request->get('id'));
        $data->tis_standard  = $request->get('tis_standard');
        $data->tradeName = $request->get('tradeName');
        $data->owner = $request->get('owner');
        $data->address_no = $request->get('address_no');
        $data->address_alley = $request->get('address_alley');
        $data->address_road = $request->get('address_road');
        $data->address_village_no = $request->get('address_village_no');
        $data->address_district = $request->get('address_district');
        $data->address_amphoe = $request->get('address_amphoe');
        $data->address_province = $request->get('address_province');
        $data->address_zip_code = $request->get('address_zip_code');
        $data->id_control = $request->get('id_control');
        $data->total_list_seizure = $request->get('total_list_seizure');
        $data->total_value_seizure = $request->get('total_value_seizure');
        $data->total_list_freeze = $request->get('total_list_freeze');
        $data->total_value_freeze = $request->get('total_value_freeze');
        $data->keep_product_address_no = $request->get('keep_product_address_no');
        $data->keep_product_address_village_no = $request->get('keep_product_address_village_no');
        $data->keep_product_address_alley = $request->get('keep_product_address_alley');
        $data->keep_product_address_road = $request->get('keep_product_address_road');
        $data->keep_product_address_province = $request->get('keep_product_address_province');
        $data->keep_product_address_amphoe = $request->get('keep_product_address_amphoe');
        $data->keep_product_address_district = $request->get('keep_product_address_district');
        $data->keep_product_address_zip_code = $request->get('keep_product_address_zip_code');
        $data->date_pluck = $request->get('date_pluck');
        $data->pluck_by = $request->get('pluck_by');
        $data->status = $status;
        $data->date_freeze = $date_freeze;
        $data->officer_freeze = $request->get('officer_freeze');
        
        // เลขที่เอกสาร
        if(isset($request->auto_id_doc) && $request->auto_id_doc != ''){  
            $data->auto_id_doc = $request->auto_id_doc;
        }
        if ($data->save()) {
            if ($request->get('num_row1') != null) {
                $data_list1 = ControlFreezeSeizureList::query()->where('id_freeze', $request->get('id'))->get();
                foreach ($data_list1 as $list1) {
                    $delete1 = ControlFreezeSeizureList::find($list1->id);
                    $delete1->delete();
                }
                for ($i = 0; $i < count($request->num_row1); $i++) {
                    $data_table1 = new ControlFreezeSeizureList([
                        'id_freeze' => $data->id,
                        'list_seizure' => $request->list_seizure[$i],
                        'amount_seizure' => $request->amount_seizure[$i],
                        'unit_seizure' => $request->unit_seizure[$i],
                        'value_seizure' => $request->value_seizure[$i],
                    ]);
                    $data_table1->save();
                }
            }
            if ($request->get('num_row2') != null) {
                $data_list2 = ControlFreezeFreezeList::query()->where('id_freeze', $request->get('id'))->get();
                foreach ($data_list2 as $list1) {
                    $delete2 = ControlFreezeFreezeList::find($list1->id);
                    $delete2->delete();
                }
                for ($i = 0; $i < count($request->num_row2); $i++) {
                    $data_table2 = new ControlFreezeFreezeList([
                        'id_freeze' => $data->id,
                        'list_freeze' => $request->list_freeze[$i],
                        'amount_freeze' => $request->amount_freeze[$i],
                        'unit_freeze' => $request->unit_freeze[$i],
                        'value_freeze' => $request->value_freeze[$i],
                    ]);
                    $data_table2->save();
                }
            }



            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function detail($ID)
    {
        $data = ControlFreeze::find($ID);
        $data_freeze = ControlFreezeFreezeList::query()->where('id_freeze', $ID)->get();
        $data_seizure = ControlFreezeSeizureList::query()->where('id_freeze', $ID)->get();
        //ไฟล์แนบ
        $attachs = json_decode($data['attach']);
        $attachs = !is_null($attachs) && count($attachs) > 0 ? $attachs : [(object)['file_note' => '', 'file_name' => '']];
        $attach_path = $this->attach_path;
        return view('csurv.control_freeze.detail', ['data' => $data, 'data_freeze' => $data_freeze, 'data_seizure' => $data_seizure, 'attachs' => $attachs, 'attach_path' => $attach_path]);
    }

    public function delete_status(Request $request)
    {
        $data = ControlFreeze::find($request->get('id'));
        $data->status = 'ยกเลิก';
        if ($data->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        }
    }

    public function add_filter_address_province($province)
    {
        $data = DB::table('amphur')->whereNull('state')->where('PROVINCE_ID', $province)->pluck('AMPHUR_NAME', 'AMPHUR_ID');
        return response()->json($data);
    }

    public function add_filter_address_district($district)
    {
        $data = DB::table('district')->whereNull('state')->where('AMPHUR_ID', $district)->pluck('DISTRICT_NAME', 'DISTRICT_ID');
        return response()->json($data);
    }

    //แปลงวันที่รูปแบบ 31/01/2561 เป็น 2018-01-31
    function convertDate($date, $minus = true)
    {
        $negative = $minus === true ? 543 : 0;
        $dates = explode('/', $date);
        return count($dates) == 3 ? ($dates['2'] - $negative) . '-' . $dates[1] . '-' . $dates[0] : '-';
    }

}

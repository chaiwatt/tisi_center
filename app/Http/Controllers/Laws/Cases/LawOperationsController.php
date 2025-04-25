<?php

namespace App\Http\Controllers\Laws\Cases;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use HP;
use HP_Law;

use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Cases\LawCasesForm; 

use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Cases\LawCasesOperation;

use App\Models\Law\Basic\LawStatusOperation;
use App\Models\Law\Cases\LawCasesOperationDetail;

use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Cases\MailOperations;



class LawOperationsController extends Controller
{

    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/cases/operations/';
        $this->permission  = str_slug('law-cases-operations','-');
    }

    public function data_list(Request $request)
    {

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_basic_section_id = $request->input('filter_basic_section_id');
        $filter_created_at       = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;
 
        $query =  LawCasesResult::query() 
                                        ->with([
                                            'law_case_to' 
                                        ])
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('law_case_to', function ($query)  use ($search_full) {
                                                                        $query->Where(DB::raw("REPLACE(case_number,' ','')")  , 'LIKE', '%' . $search_full . '%');
                                                              });
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('law_case_to', function ($query)  use ($search_full) {
                                                                        $query->Where(DB::raw("REPLACE(offend_name,' ','')")  , 'LIKE', '%' . $search_full . '%')
                                                                             ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')") , 'LIKE', '%' . $search_full . '%');
                                                              });
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return    $query->whereHas('law_case_to', function ($query)  use ($search_full) {
                                                                 $query->Where(DB::raw("REPLACE(case_number,' ','')")  , 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere(DB::raw("REPLACE(offend_name,' ','')") , 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')") , 'LIKE', '%' . $search_full . '%');
                                                                  });
                                                    break;
                                            endswitch;
                                        }) 
                                        ->when($filter_status, function ($query, $filter_status){
                                                return    $query->whereHas('law_case_to', function ($query)  use ($filter_status) {
                                                            $query->Where('status', $filter_status) ;
                                                            });
                                        })
                                        ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                                return    $query->whereHas('law_case_to', function ($query)  use ($filter_tisi_no) {
                                                            $query->Where('tis_id', $filter_tisi_no) ;
                                                            });
                                        })
                                        ->when($filter_basic_section_id, function ($query, $filter_basic_section_id){
                                            return    $query->whereHas('law_case_to', function ($query)  use ($filter_basic_section_id) {
                                                            $query->where(function($query) use($filter_basic_section_id) {
                                                                    $room_count  =  (array)$filter_basic_section_id;
                                                                        if(count($room_count) > 0){
                                                                            $query->whereJsonContains('law_basic_section_id', (string)$room_count[0]);
                                                                            info (count($room_count));
                                                                            for($i = 1; $i <= count($room_count) - 1; $i++) {
                                                                                $query->orWhereJsonContains('law_basic_section_id',(string)$room_count[$i]);
                                                                            }
                                                                        }else{
                                                                            $query->whereNull('law_basic_section_id');
                                                                        }
                                                                return $query;
                                                            });
                                                        });
                                        }) 
                                        ->when($filter_created_at, function ($query, $filter_created_at){
                                            return    $query->whereHas('law_case_to', function ($query)  use ($filter_created_at) {
                                                            $query->WhereDate('close_date', $filter_created_at) ;
                                                        });
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->whereHas('law_case_to',function($query){
                                                $query->where('lawyer_by', Auth::user()->getKey())
                                                    ->Orwhere('assign_by', Auth::user()->getKey());
                                            });            
                                        });
                                        
        return Datatables::of($query)
                            ->addIndexColumn()
                            // ->addColumn('checkbox', function ($item) {
                            //     if(!empty($item->law_case_to->status_close)  && $item->law_case_to->status_close == 1){
                            //         return '';
                            //     }else{
                            //         return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" 
                            //                 data-id="'.(  !empty($item->law_case_to->id) ? $item->law_case_to->id : '').'"   
                            //                 data-case_number="'.(  !empty($item->law_case_to->case_number) ? $item->law_case_to->case_number : '').'"   
                            //                 data-offend_name="'.(  !empty($item->law_case_to->offend_name) ? $item->law_case_to->offend_name : '').'"   
                            //                 data-offend_taxid="'.(  !empty($item->law_case_to->offend_taxid) ? $item->law_case_to->offend_taxid : '').'"   
                            //                 data-emails="'.(  !empty($item->law_case_to->law_cases_assign_to->EmailName) ? $item->law_case_to->law_cases_assign_to->EmailName : '').'"   
                            //                 value="'. $item->id .'">';
                            //     }
                               
                            // })
                            ->addColumn('case_number', function ($item) {
                                return   !empty($item->law_case_to->case_number) ? $item->law_case_to->case_number : '';
                            })
                            ->addColumn('offend_name', function ($item) {
                                $text  = !empty($item->law_case_to->offend_name) ? $item->law_case_to->offend_name : '';
                                $text  .= !empty($item->law_case_to->offend_taxid) ? '<div>'.$item->law_case_to->offend_taxid.'</div>' : '';
                                return $text;
                            })
                            ->addColumn('total', function ($item) {
                                return  !empty($item->law_case_to->law_cases_impound_to->total_value) ? number_format($item->law_case_to->law_cases_impound_to->total_value,2) : number_format('0',2);
                            })
                              ->addColumn('tis', function ($item) {
                                return  !empty($item->law_case_to->StandardNo) ? $item->law_case_to->StandardNo : '';
                            }) 
                            ->addColumn('law_basic_section', function ($item) {
                                return  !empty($item->OffenseSectionNumber)?$item->OffenseSectionNumber:'N/A'; 
                            })
                            ->addColumn('status', function ($item){ 
                                return  !empty($item->law_case_to->StatusColorHtml) ? $item->law_case_to->StatusColorHtml : '';
                           })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->law_case_to->user_lawyer_to->FullName)   ? $item->law_case_to->user_lawyer_to->FullName: '<i class="text-muted">(รอมอบหมาย)</i>';
                            })
                            ->addColumn('created_at', function ($item) {
                                return  !empty($item->law_case_to->close_date)?HP::DateThai($item->law_case_to->close_date):null;
                            })
                            ->addColumn('action', function ($item) {
                                $btn = '';
                                if(auth()->user()->can('add-'.$this->permission)) {
                                    if((!empty($item->law_case_to->status)  && $item->law_case_to->status == '99')) {
                                        if(!empty($item->law_case_to->status_close)  && $item->law_case_to->status_close == 1){
                                            $btn .=  '<button disabled class="btn btn-icon btn-circle btn-light-success " ><i class="fa fa-pencil "  style="font-size: 1.5em;"></i> </button>'; 
                                        }else{
                                            $btn .=  '<button disabled class="btn btn-icon btn-circle btn-light-info " ><i class="fa fa-pencil "  style="font-size: 1.5em;"></i> </button>';  
                                        }
                                    }else{
                                        if(!empty($item->law_case_to->status_close)  && $item->law_case_to->status_close == 1){
                                            $btn .=  '<a href="'.url('law/cases/operations/'.$item->id).'"  class="btn btn-icon btn-circle btn-light-success " ><i class="fa fa-pencil "  style="font-size: 1.5em;"></i> </a>'; 
                                        }else{
                                            $btn .=  '<a href="'.url('law/cases/operations/'.$item->id.'/edit').'"  class="btn btn-icon btn-circle btn-light-info " ><i class="fa fa-pencil "  style="font-size: 1.5em;"></i> </a>';  
                                        }
                                    }
                                }
                                if(auth()->user()->can('edit-'.$this->permission)) {
                                    if(!empty($item->law_case_to)  && is_null($item->law_case_to->status_close)){
                                        if((!empty($item->law_case_to->status)  && $item->law_case_to->status == '99')) {
                                            $btn .=  ' <span  disabled  class="btn btn-icon btn-circle btn-light-warning">
                                                            <i class="fa fa-lock "  style="font-size: 1.5em;"></i>
                                                        </span>'; 
                                        }else{
                                            $btn .=  ' <span    class="btn btn-icon btn-circle btn-light-warning close_the_case" 
                                                        data-id="'.(  !empty($item->law_case_to->id) ? $item->law_case_to->id : '').'"   
                                                        data-case_number="'.(  !empty($item->law_case_to->case_number) ? $item->law_case_to->case_number : '').'"   
                                                        data-offend_name="'.(  !empty($item->law_case_to->offend_name) ? $item->law_case_to->offend_name : '').'" 
                                                        data-offend_taxid="'.(  !empty($item->law_case_to->offend_taxid) ? $item->law_case_to->offend_taxid : '').'"    
                                                        data-emails="'.(  !empty($item->law_case_to->law_cases_assign_to->EmailName) ? $item->law_case_to->law_cases_assign_to->EmailName : '').'"   
                                                    >
                                                        <i class="fa fa-lock "  style="font-size: 1.5em;"></i>
                                                    </span>'; 
                                        }
                                    } 
                                }
                                return $btn; 

                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawCasesResult)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'status', 'law_basic_section', 'lawyer_name', 'action','offend_name'])
                            ->make(true);
    }

    public function index()
    {
        
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/operations",  "name" => 'บันทึกการดำเนินงานคดี' ],
            ];
            return view('laws.cases.operations.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $result                                 = LawCasesResult::findOrFail($id);
            $license_result                         =  !empty($result->law_case_license_result_to)  ?$result->law_case_license_result_to : null; 

            $cases                                  =  LawCasesForm::where('id', $result->law_case_id )->first();
            if(is_null($cases)){
                $cases = new   LawCasesForm;
            }

            $cases->law_basic_arrest                = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;
            $product_result                         =  !empty($cases->law_cases_impound_to->law_cases_tmpound_product_to->product_result)  ?$cases->law_cases_impound_to->law_cases_tmpound_product_to->product_result : null;             


            $operations = $cases->law_case_operations;

            $result->operations_detail_indict           =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','4'):[]; // บันทึกดำเนินงานอาญา
            $result->operations_detail_product          =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','3'):[]; // บันทึกดำเนินงานอาญา
            $result->operations_detail_license          =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','2'):[];  // บันทึกดำเนินงานปกครอง
            $result->operations_detail_person           =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','1'):[];  // บันทึกดำเนินงานของกลาง
            $result->status                             =  !empty($operations->status) ? $operations->status:2; 
            $result->remark                             =  !empty($operations->remark) ? $operations->remark:null; 
                        
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/operations",  "name" => 'บันทึกการดำเนินงานคดี' ],
                [ "link" => "/law/cases/operations/$id",  "name" => 'แก้ไข' ],
            ];

            return view('laws.cases.operations.show', compact('result', 'cases', 'license_result',  'product_result', 'breadcrumbs'));

        }
        abort(403);
    }


    public function edit($id)
    {
            
        if(auth()->user()->can('edit-'.$this->permission)) {

            $result                                 = LawCasesResult::findOrFail($id);
            $license_result                         =  !empty($result->law_case_license_result_to)  ?$result->law_case_license_result_to : null; 

            $cases                                  =  LawCasesForm::where('id', $result->law_case_id )->first();
            if(is_null($cases)){
                $cases = new   LawCasesForm;
            }

            $cases->law_basic_arrest                = !empty($cases->law_basic_arrest_to->title) ?  $cases->law_basic_arrest_to->title : null;

            $product_result                         =  !empty($cases->law_cases_impound_to->law_cases_tmpound_product_to->product_result)  ?$cases->law_cases_impound_to->law_cases_tmpound_product_to->product_result : null;             


            $operations = $cases->law_case_operations;

            $result->operations_detail_indict           =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','4'):[]; // บันทึกดำเนินงานอาญา
            $result->operations_detail_product          =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','3'):[]; // บันทึกดำเนินงานอาญา
            $result->operations_detail_license          =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','2'):[];  // บันทึกดำเนินงานปกครอง
            $result->operations_detail_person           =  !empty($operations->case_operations_details) ? $operations->case_operations_details->where('operation_type','1'):[];  // บันทึกดำเนินงานของกลาง
            $result->status                             =  !empty($operations->status) ? $operations->status:2; 
            $result->remark                             =  !empty($operations->remark) ? $operations->remark:null; 
                        
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/operations",  "name" => 'บันทึกการดำเนินงานคดี' ],
                [ "link" => "/law/cases/operations/$id/edit",  "name" => 'แก้ไข' ],
            ];

            return view('laws.cases.operations.edit', compact('result', 'cases', 'license_result',  'product_result', 'breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    public function update(Request $request, $id)
    {
        
        if(auth()->user()->can('edit-'.$this->permission)) {
            $requestData  = $request->all();

            $result       = LawCasesResult::findOrFail($id);
            $result->update(['prosecute' => $requestData['prosecute']]);

            $case_number  = !empty($result->law_case_to->case_number) ?  $result->law_case_to->case_number : null; 
            $law_case_operations  =  LawCasesOperation::where('law_cases_id',$result->law_case_id)->first();
            if(is_null($law_case_operations)){
                $law_case_operations             = new LawCasesOperation;
                $law_case_operations->created_by = auth()->user()->getKey();
            }else{
                $law_case_operations->updated_by = auth()->user()->getKey();
            }
            $law_case_operations->law_cases_id  = !empty($result->law_case_id) ?  $result->law_case_id : null;
            $law_case_operations->case_number   = !empty($case_number)?$case_number:null; 
            $law_case_operations->status        = !empty($requestData['status'])?$requestData['status']:null;
            $law_case_operations->remark        = !empty($requestData['remark'])?$requestData['remark']:null;
            $law_case_operations->save();

            $repeater_person  = !empty($requestData['repeater-person'])?$requestData['repeater-person']:[];
            $repeater_license = !empty($requestData['repeater-license'])?$requestData['repeater-license']:[];
            $repeater_product = !empty($requestData['repeater-product'])?$requestData['repeater-product']:[];
            $repeater_indict  = !empty($requestData['repeater-indict'])?$requestData['repeater-indict']:[];

            $list = array_merge( $repeater_person, $repeater_license, $repeater_product, $repeater_indict);

             if( !empty($list) ){
                self::law_cases_operations_detail($list,$case_number,$law_case_operations); 
            }

             return redirect('law/cases/operations/'.$id.'/edit')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
        abort(403);
    }




    public function law_cases_operations_detail($list,$case_number,$law_case_operations)
    {      
     
        $list_id_data = [];
        foreach($list as $lists){
            if(isset($lists['operation_detail_id'])){
                $list_id_data[] = $lists['operation_detail_id'];
            }
        }

        $lists_id = array_diff($list_id_data, [null]);

        $law_case_operation_detail_old =  LawCasesOperationDetail::where('law_case_operations_id',$law_case_operations->id)
                                    ->when($lists_id, function ($query, $lists_id){
                                        return $query->whereNotIn('id', $lists_id);
                                    });


        if(!empty($law_case_operation_detail_old->get()) && count($law_case_operation_detail_old->get()) > 0){
            foreach(  $law_case_operation_detail_old->get()  AS $item ){
                //ลบไฟล์เดิม
                $attachs_operation_detail = $item->attach_file;
                HP_Law::DeleteLawSingleFile($attachs_operation_detail);
            }
            //ลบข้อมูลเดิม
            $law_case_operation_detail_old->delete();
        }

        foreach( $list as $item ){
            $case_operation_detail =  LawCasesOperationDetail::where('id',$item['operation_detail_id'])->first();
            if(is_null($case_operation_detail)){
                $case_operation_detail             = new LawCasesOperationDetail;
                $case_operation_detail->created_by = auth()->user()->getKey();
            }else{
                $case_operation_detail->updated_by = auth()->user()->getKey();
            }

            $case_operation_detail->law_case_operations_id  = $law_case_operations->id;
            $case_operation_detail->operation_type          = !empty( $item['operation_type'])?$item['operation_type']:null;
            $case_operation_detail->operation_date          = !empty( $item['operation_date'] )?HP::convertDate($item['operation_date'],true) : null;
            $case_operation_detail->due_date                = !empty( $item['due_date'] )?HP::convertDate($item['due_date'],true) : null;
            $case_operation_detail->status_job_track_id     = !empty( $item['status_job_track_id'])?$item['status_job_track_id']:null;
            $case_operation_detail->remark                  = !empty( $item['remark'])?$item['remark']:null;
            $case_operation_detail->save();

            //อัพโหลดไฟล์
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            if( isset($item['attachs']) && !empty($item['attachs']) ){
                HP::singleFileUploadLaw(
                    $item['attachs'],
                    $this->attach_path.$case_number,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawCasesOperationDetail)->getTable() ),
                    $case_operation_detail->id,
                    'operations',
                    null
                );
            }
        }
                        

    }
    //  end บันทึกผลพิจารณางานคดี

    public function save_close_assign(Request $request)
    {      
        $message = false;
        if(!empty($request->id) ){
            $id = $request->id;
            $case = LawCasesForm::findOrFail($id);
            if(!is_null($case)){
                $requestData['status_close']   = 0;
                $requestData['close_date']     = date('Y-m-d H:i:s'); 
                $requestData['close_remark']   = !empty($request->remark) ? $request->remark   :  null ;  
                $requestData['close_by']       = auth()->user()->getKey();
                $case->update($requestData); 

              if(!empty($request->email_results) && count(explode(",",$request->email_results)) > 0){

                    $email_results = [];
                    foreach(explode(",",$request->email_results)as $email){
                            if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                                $email_results[] =  $email;
                            }
                    }
            
                if(count($email_results) > 0){
                    $data_app = [
                                    'case'           => $case,
                                    'title'          => "แจ้งปิดงานคดี $case->offend_name - เลขคดี $case->case_number"
                                ]; 
                                
                    $log_email =  HP_Law::getInsertLawNotifyEmail(
                                                                    1,
                                                                    ((new LawCasesForm)->getTable()),
                                                                    $case->id,
                                                                    'แจ้งปิดงานคดี',
                                                                    "แจ้งปิดงานคดี $case->offend_name - เลขคดี $case->case_number",
                                                                    view('mail.Law.Cases.operations', $data_app),
                                                                    null,  
                                                                    null,  
                                                                    json_encode($email_results)   
                                                                );
            
                        $html = new MailOperations($data_app);
                       Mail::to($email_results)->send($html);
                    }
                }
            }
            $message = true; 
        }else if(!empty($request->ids) ){
            $ids = $request->ids;
            $requestData['status_close']   = 0;
            $requestData['close_date']     = date('Y-m-d H:i:s'); 
            $requestData['close_remark']   = !empty($request->remark) ? $request->remark   :  null ;  
            $requestData['close_by']       = auth()->user()->getKey();
            $case = LawCasesForm::whereIn('id',$ids)->update($requestData); 
            $message = true; 
        }



        return response()->json([ 'message' => $message  ]);
    }



}

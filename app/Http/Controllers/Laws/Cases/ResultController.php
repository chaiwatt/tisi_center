<?php

namespace App\Http\Controllers\Laws\Cases;

use HP;
use HP_Law;
use App\User;  

use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Law\Log\LawNotify; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Law\Log\LawLogWorking;

use Illuminate\Support\Facades\Mail; 
use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Cases\LawCasesResult;

use PhpOffice\PhpWord\TemplateProcessor;
use App\Mail\Mail\Law\Result\MailConsider;
use App\Mail\Mail\Law\Result\MailDocument;
use App\Models\Law\Config\LawConfigSection;

use App\Models\Law\Cases\LawCasesBookOffend;
use App\Models\Law\Cases\LawCasesResultSection;

class ResultController extends Controller
{

    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/cases_result';
    }

    public function data_list(Request $request)
    {
        $model = str_slug('law-cases-result','-');

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_basic_section_id = $request->input('filter_basic_section_id');
        $filter_created_at       = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query =  LawCasesForm::query()
                                    ->where(function($query){
                                        $query->whereNotIn('status',['0','99']) ->where('status','>=','2');
                                    })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('ref_no', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('offend_name', 'LIKE', '%' . $search_full . '%')->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where('ref_no', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return $query->whereIn('status',$filter_status);
                                        })
                                        ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                            return $query->where('tis_id', $filter_tisi_no);
                                        })
                                        ->when($filter_basic_section_id, function ($query, $filter_basic_section_id){
                                            return $query->whereJsonContains('law_basic_section_id', $filter_basic_section_id);
                                        })
                                        ->when($filter_created_at, function ($query, $filter_created_at){
                                            return $query->whereDate('created_at', $filter_created_at);
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$model), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->where(function($query){
                                                $query->where('lawyer_by', Auth::user()->getKey())
                                                    ->Orwhere('assign_by', Auth::user()->getKey());
                                            });            
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('ref_no', function ($item) {
                                $text  = !empty($item->ref_no) ? $item->ref_no : '';
                                $text  .= !empty($item->case_number) ? '<div><b>'.$item->case_number.'</b></div>' : '';
                                if(count($item->law_log_working_bounce_many) > 0){
                                    $data_log_working = base64_encode(json_encode($item->WorkingBounceList));
                                    $text  .=  '<div class="bounce tip view-log-modal" data-log="'.$data_log_working.'" data-ref_no="'.$item->ref_no.'">[ประวัติแจ้งแก้ไข]<span class="tooltiptext">คลิกเพื่อดูประวัติการแก้ไข</span> </div>';
                                }
                                return $text;
                          
                            })
                            ->addColumn('offend_name', function ($item) {
                                $text  = !empty($item->offend_name) ? $item->offend_name : '';
                                $text  .= !empty($item->offend_taxid) ? '<br/>'.$item->offend_taxid : '';
                                return $text;
                            })
                            ->addColumn('tis_name', function ($item) {
                                $text  = !empty($item->StandardNo) ? $item->StandardNo : '';
                                $text  .= !empty($item->offend_license_number) ? '<br/>'.$item->offend_license_number : '';
                                return $text;
                            }) 
                            ->addColumn('section', function ($item) {
                                return !empty($item->SectionListName)?$item->SectionListName:null;
                            })
                            ->addColumn('amount_impounds_and_keep', function ($item) {
                                if(empty($item->law_cases_impound_to) || @$item->law_cases_impound_to->impound_status == '2'){
                                    $resault = 'ไม่มี';
                                }else{
                                    $resault = !empty($item->law_cases_impound_to->AmountImpound)?'ยึด '.$item->law_cases_impound_to->AmountImpound:null;
                                    $resault .= $resault?'<br>':null;
                                    $resault .= !empty($item->law_cases_impound_to->AmountKeep)?'อายัด '.$item->law_cases_impound_to->AmountKeep:null;
                                    $resault = !empty($resault)?$resault:'ไม่มี';
                                }
                                return $resault;
                            })
                            ->addColumn('punish', function ($item) {
                                    $OffenseSectionNumber = (!empty($item->law_cases_result_to->OffenseSectionNumber) && is_array($item->law_cases_result_to->OffenseSectionNumber))?implode(' ', $item->law_cases_result_to->OffenseSectionNumber):null;
                                    $resault = !empty($item->SectionListName)?'<span class="text-muted">'.$item->SectionListName.'</span>':null;
                                    $resault .= $resault?'<br/>':null;
                                    $resault .= !empty($OffenseSectionNumber)? '<b>'.$OffenseSectionNumber.'</b>':null;
                                    return $resault;
                            })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->user_lawyer_to->FullName)   ? $item->user_lawyer_to->FullName: '<i class="text-muted">(รอมอบหมาย)</i>';
                            })
                            ->addColumn('status', function ($item) { 
                                return  !empty($item->StatusColorHtml) ? $item->StatusColorHtml : '';
                            }) 
                            ->addColumn('action', function ($item) {
                                 return self::buttonActionLaw($item->id, $item, 'law/cases/results', 'law-cases-result');
                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawCasesForm)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'ref_no', 'offend_name','tis_name', 'lawyer_name', 'status', 'section', 'action', 'amount_impounds_and_keep', 'punish'])
                            ->make(true);
    }

    public static function buttonActionLaw($id, $data, $action_url, $str_slug_name, $show_view = true, $show_edit = true, $show_delete = true)
    {
        $form_action  = '';
        $status       = $data->status;
        $result       = $data->law_cases_result_to;
        $offend_books = $data->offend_books;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('add-' . str_slug($str_slug_name)) && $show_view === true  ):
            $class       =  (  $status  >= 4 )? 'btn-light-success':'btn-light-info';
            $url         =  url('/' . $action_url . '/' . $id .'/document');
            $form_action .= '<a href="' .$url. '" title="บันทึกผลตรวจสอบเอกสาร" class="btn btn-icon btn-circle '.($class ).' m-r-5" ><i class="fa fa-pencil "  style="font-size: 1.5em;"></i></a>';
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true ):
            $class       =  !empty( $result )? 'btn-light-success':'btn-light-primary';
            $url         =  url('/' . $action_url . '/' . $id .'/consider');
            $form_action .= '<a href="' .$url . '" title="บันทึกผลพิจารณางานคดี" class="btn btn-icon btn-circle '.($class ).' m-r-5"> <i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i> </a>';
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('printing-' . str_slug($str_slug_name)) && $show_edit === true ):
            $class       =   !empty( $offend_books )? 'btn-light-success':'btn-light-warning';
            $url         =   url('/' . $action_url . '/' . $id .'/printing');
            $form_action .=  '<a href="' . $url  . '" title="พิมพ์หนังสือแจ้งการกระทำความผิด" class="btn btn-icon btn-circle '.($class ).' m-r-5"> <i class="fa  fa-file-text-o"  style="font-size: 1.5em;"></i> </a>';
        endif;

        return $form_action;
    }

    

    public function index()
    {
        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/results",  "name" => 'ผลพิจารณางานคดี' ],
            ];
            return view('laws.cases.result.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function document($id)
    {
        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('edit-'.$model)) {

            $case                                = LawCasesForm::findOrFail($id);
            $case->reg_email                     = !empty($case->law_cases_assign_to->user_created->reg_email)  ? $case->law_cases_assign_to->user_created->reg_email : null;
            $case->law_basic_arrest_id           = !empty($case->law_basic_arrest_to->title) ?  $case->law_basic_arrest_to->title : null;
            $case->law_basic_offend_type_id      = !empty($case->law_basic_offend_type_to->title) ?  $case->law_basic_offend_type_to->title : null;
            $case->ref_id                        = !empty($case->law_offend_type_to->title) ?  $case->law_offend_type_to->title : null;
            $case->date_impound                  = !empty($case->date_impound) ?  HP::revertDate($case->date_impound,true) : null;
            $case->law_basic_resource_id         = !empty($case->law_basic_resource_to->title) ?  $case->law_basic_resource_to->title : null;

            $law_notify =    LawNotify::where('ref_table',(new LawCasesForm)->getTable())->where('ref_id',$id)->where('name_system','ผลการพิจารณาแจ้งงานคดี')->orderby('id','desc')->first();
     
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/results",  "name" => 'งานคดี' ],
                [ "link" => "/law/cases/results/$id/document",  "name" => 'บันทึกผลตรวจสอบเอกสาร' ],

            ];
 
            return view('laws.cases.result.document', compact('case', 'law_notify', 'breadcrumbs'));
        }
        abort(403);
    }

    public function save_document(Request $request ,$id)
    {      
        
        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('edit-'.$model)) {

            $case = LawCasesForm::findOrFail($id);

            $requestData = [];
            $requestData['status']         = $request->status ?? null;
            $requestData['accept_remark']  = $request->accept_remark ?? null;
            $requestData['accept_at']      =  date('Y-m-d H:i:s');  
            $requestData['accept_by']      = auth()->user()->getKey();
            $case->update($requestData); 

            //Log
            $arr_status = LawCasesForm::status_list();
            HP_Law::InsertLawLogWorking(         
                1,
                ((new LawCasesForm)->getTable()),
                $case->id,
                $case->ref_no ?? null,
                'ผลพิจารณางานคดี',
                'บันทึกผลตรวจสอบเอกสาร',
                (array_key_exists( $requestData['status'], $arr_status )?$arr_status[ $requestData['status'] ]:'-'),
                (!empty($requestData['accept_remark'])?$requestData['accept_remark']:null)
            );

            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            // หลักฐานผลพิจารณา
            if(isset($request->file_document)){
                if ($request->hasFile('file_document')) {
                    HP::singleFileUploadLaw(
                        $request->file('file_document') ,
                        $this->attach_path,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        ((new LawCasesForm)->getTable()),
                        $id,
                        'file_document',
                        'หลักฐานผลพิจารณา',
                        null,
                        true
                    );
                }
            }
      
            
            if(!empty($request->email_results) && count(explode(",",$request->email_results)) > 0){

                $email_results = [];
                foreach(explode(",",$request->email_results)as $email){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                        $email_results[] =  $email;
                    }
                }

                if(count($email_results) > 0){

                    // ช่องทางแจ้งเตือน
                    $channels  = [];
                    if(!empty($request->funnel_system)){
                        $channels[] =  $request->funnel_system;
                    }
                    if(!empty($request->funnel_email)){
                        $channels[] =  $request->funnel_email;
                    }

                    // แจ้งเตือนไปยัง
                    $notify_types  = [];
                    if(!empty($request->owner_email)){
                        $notify_types[] =  $request->owner_email;
                    }
                    if(!empty($request->owner_contact_email)){
                        $notify_types[] =  $request->owner_contact_email;
                    }
                    if(!empty($request->offend_contact_email)){
                        $notify_types[] =  $request->offend_contact_email;
                    }
                    if(!empty($request->reg_email)){
                        $notify_types[] =  $request->reg_email;
                    }
                
                    // ข้อมูล
                    $data_app = [
                                    'case'           => $case,
                                    'title'          => 'แจ้งการพิจารณาข้อมูลงานคดี ของ '.(!empty($case->offend_name)?$case->offend_name:null).' เลขอ้างอิง'.(!empty($case->ref_no)?$case->ref_no:null)
                                ];
                            
                    $log_email =  HP_Law::getInsertLawNotifyEmail(1,
                                                                ((new LawCasesForm)->getTable()),
                                                                $case->id,
                                                                'ผลการพิจารณาแจ้งงานคดี',
                                                                'แจ้งเตือนการพิจารณาข้อมูลงานคดี ของ '.(!empty($case->offend_name)?$case->offend_name:null).' เลขอ้างอิง'.(!empty($case->ref_no)?$case->ref_no:null),
                                                                view('mail.Law.Result.document', $data_app),
                                                                (count($channels) > 0 ?  json_encode($channels)  : null),  
                                                                (count($notify_types) > 0 ?  json_encode($notify_types)  : null),   
                                                                json_encode($email_results)   
                                                                );

                    if( !empty($request->funnel_email) ){ //แจ้งแตือนผ่านเมล
                        $html = new MailDocument($data_app);
                        Mail::to($email_results)->send($html);
                    }
            
                }
            }

            return redirect('law/cases/results')->with('flash_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function log_document(Request $request)
    {

      $id              =   $request->input('id');
      $ref_table       = (new LawCasesForm)->getTable();
      $log_documents = LawLogWorking::where('ref_id',$id)->where('ref_table',$ref_table)->whereIn('title',['บันทึกผลตรวจสอบเอกสาร'])->get();
      $data            = [];
      if(count($log_documents) > 0){
          foreach($log_documents as $log_document){
               $object                         = (object)[]; 
               $object->remark          = !empty($log_document->remark) ?$log_document->remark: '';
               $object->status          = !empty($log_document->status) ? $log_document->status: '';
               $object->created_at    = !empty($log_document->created_at)?HP::DateThai($log_document->created_at):'';
               $object->created_by      = !empty($log_document->CreatedName) ? $log_document->CreatedName: '';
               $data[]                         = $object;
          }
      }
      return response()->json([
                                     'message' => count($log_documents) > 0 ? true : false,
                                     'datas' => $data
                                     ]);

    }

    // start บันทึกผลพิจารณางานคดี
    public function consider($id)
    {
        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('edit-'.$model)) {

            $case = LawCasesForm::findOrFail($id);
            $case->reg_email = !empty($case->law_cases_assign_to->user_created->reg_email)  ? $case->law_cases_assign_to->user_created->reg_email : null;
    
            $result =  LawCasesResult::where('law_case_id', $case->id )->first();
            $law_notify = null;
            if(!is_null($result)){
                $law_notify =    LawNotify::where('ref_table',(new LawCasesResult)->getTable())->where('ref_id',$result->id)->where('name_system','บันทึกผลพิจารณางานคดี')->orderby('id','desc')->first();
            }

            $breadcrumbs = [
                            [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                            [ "link" => "/law/cases/results",  "name" => 'งานคดี' ],
                            [ "link" => "/law/cases/results/$id/consider",  "name" => 'บันทึกผลพิจารณางานคดี' ],
                          ];
            return view('laws.cases.result.consider', compact('case','result', 'law_notify', 'breadcrumbs'));
        }
        abort(403);
    }
        
    public function save_consider(Request $request ,$id)
    {      
      
        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            $case = LawCasesForm::findOrFail($id);

            if( !empty($requestData['status']) && in_array( $requestData['status'], [5,7] ) ){

                //ถ้าว่าง
                if( empty( $case->case_number ) ){
                    $ref_type     =( $case->owner_depart_type == 1)? 'I':'O';
                    //เลขรัน
                    $running_no =  HP::ConfigFormat('LawCasesNumber', (new LawCasesForm)->getTable(), 'case_number', $ref_type, null, null);
                    $check = LawCasesForm::where('case_number', $running_no)->whereNotNull('case_number')->first();
                    if(!is_null($check)){
                        $running_no = self::set_running_no($case); 
                        //   $running_no =   HP::ConfigFormat('LawCasesNumber', (new LawCasesForm)->getTable(), 'case_number', $ref_type, null, null);
                    }
                    $requestData['case_number'] = $running_no;
                }
                $case->case_number = !empty($requestData['case_number'])?$requestData['case_number']:null;
                $case->save();

            }

            if(!empty($requestData['status'])){
                $case->status = !empty($requestData['status'])?$requestData['status']:null;
                $case->save();

                //Log
                $arr_status = LawCasesForm::status_list();
                HP_Law::InsertLawLogWorking(         
                    1,
                    ((new LawCasesForm)->getTable()),
                    $case->id,
                    $case->ref_no ?? null,
                    'ผลพิจารณางานคดี',
                    'บันทึกผลพิจารณางานคดี',
                    (array_key_exists( $requestData['status'], $arr_status )?$arr_status[ $requestData['status'] ]:'-'),
                    (!empty($requestData['remark'])?$requestData['remark']:null)
                );
            }
    
            $result =  LawCasesResult::where('law_case_id', $case->id )->first();
            if(is_null($result)){
                $result             = new LawCasesResult;
                $result->created_by = auth()->user()->getKey();
            }else{
                $result->updated_by = auth()->user()->getKey();
            }

            $result->law_case_id = $case->id;
            if($case->offend_license_type != 1){ // ไม่มีเลขใบอนุญาต
                $result->person     = '1' ;
                $result->license    = '0';
                $result->product    = '1' ;
            }else{
                $result->person     = isset($request->person) ? '1' : '0';
                $result->license    = isset($request->license) ? '1' : '0';
                $result->product    = isset($request->product) ? '1' : '0';
            }
          
            $result->remark     = isset($request->remark) ? $request->remark : null;
            $result->save();
        
            // หลักฐานผลพิจารณา
            if(isset($request->file_consider)){
                if ($request->hasFile('file_consider')) {
                    HP::singleFileUploadLaw(
                        $request->file('file_consider') ,
                        $this->attach_path,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        ((new LawCasesResult)->getTable()),
                        $result->id,
                        'file_consider',
                        'หลักฐานผลพิจารณา'
                    );
                }
             }

            // บันทึกพิจารณาคดี
            if(isset($request->file_consider_result)){
                if ($request->hasFile('file_consider_result')) {
                    HP::singleFileUploadLaw(
                        $request->file('file_consider_result') ,
                        $this->attach_path,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        ((new LawCasesResult)->getTable()),
                        $result->id,
                        'file_consider_result',
                        'บันทึกพิจารณาคดี'
                    );
                }
             }

            // เปรียบเทียบปรับ
            if(isset($request->file_consider_compares)){
                if ($request->hasFile('file_consider_compares')) {
                    HP::singleFileUploadLaw(
                        $request->file('file_consider_compares') ,
                        $this->attach_path,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        ((new LawCasesResult)->getTable()),
                        $result->id,
                        'file_consider_compares',
                        'เปรียบเทียบปรับ'
                    );
                }
             }

             // ข้อเท็จจริงการเปรียบเทียบปรับ
            if(isset($request->file_consider_comparison_facts)){
                if ($request->hasFile('file_consider_comparison_facts')) {
                    HP::singleFileUploadLaw(
                        $request->file('file_consider_comparison_facts') ,
                        $this->attach_path,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        ((new LawCasesResult)->getTable()),
                        $result->id,
                        'file_consider_comparison_facts',
                        'ข้อเท็จจริงการเปรียบเทียบปรับ'
                    );
                }
             }

            //ไฟล์แนบอื่นๆ
            if(isset( $requestData['repeater-attach'] ) ){
                $attachs = $requestData['repeater-attach'];
                foreach( $attachs as $file ){
                    if( isset($file['file_other']) && !empty($file['file_other']) ){
                        HP::singleFileUploadLaw(
                            $file['file_other'],
                            $this->attach_path,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Law',
                            (  (new LawCasesResult)->getTable() ),
                            $result->id,
                            'file_other',
                            'หลักฐานผลพิจารณา(อื่นๆ)'
                        );
                    }
                }
            }
 
            if( isset($request['section'])  && !is_null($request['section'])){
                self::law_cases_result_section($result,$request['section']); 
            }

                        
            if(!empty($request->email_results) && count(explode(",",$request->email_results)) > 0){

                $email_results = [];
                foreach(explode(",",$request->email_results)as $email){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                        $email_results[] =  $email;
                    }
                }

                if(count($email_results) > 0){

                    // ช่องทางแจ้งเตือน
                    $channels  = [];
                    if(!empty($request->funnel_system)){
                        $channels[] =  $request->funnel_system;
                    }
                    if(!empty($request->funnel_email)){
                        $channels[] =  $request->funnel_email;
                    }

                    // แจ้งเตือนไปยัง
                    $notify_types  = [];
                    if(!empty($request->owner_email)){
                        $notify_types[] =  $request->owner_email;
                    }
                    if(!empty($request->owner_contact_email)){
                        $notify_types[] =  $request->owner_contact_email;
                    }
                    if(!empty($request->offend_contact_email)){
                        $notify_types[] =  $request->offend_contact_email;
                    }
                    if(!empty($request->reg_email)){
                        $notify_types[] =  $request->reg_email;
                    }
             
                    // ข้อมูล
                    $data_app = [
                                    'case'           => $case,
                                    'result'         => $result,
                                    'title'          => 'แจ้งบันทึกผลพิจารณางานคดี ของ '.(!empty($case->offend_name)?$case->offend_name:null).' เลขอ้างอิง '.(!empty($case->ref_no)?$case->ref_no:null)
                                ];

                    $log_email =  HP_Law::getInsertLawNotifyEmail(
                                                                    1,
                                                                    ((new LawCasesResult)->getTable()),
                                                                    $result->id,
                                                                    'บันทึกผลพิจารณางานคดี',
                                                                    'แจ้งบันทึกผลพิจารณางานคดี ของ '.(!empty($case->offend_name)?$case->offend_name:null).' เลขอ้างอิง '.(!empty($case->ref_no)?$case->ref_no:null),
                                                                    view('mail.Law.Result.consider', $data_app),
                                                                    (count($channels) > 0 ?  json_encode($channels)  : null),  
                                                                    (count($notify_types) > 0 ?  json_encode($notify_types)  : null),   
                                                                    json_encode($email_results)   
                                                                );
                 
                    if( !empty($request->funnel_email) ){ //แจ้งแตือนผ่านเมล
                        $html = new MailConsider($data_app);
                        Mail::to($email_results)->send($html);
                    }
                }
            } 

            return redirect('law/cases/results')->with('flash_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function consider_punish(Request $request)
    {      
                $message = false;
                $config_section  = [];
            if(!empty($request->id) ){
                 $id = $request->id;
                 $config_section =  LawConfigSection::select('section_id','power')->whereJsonContains('section_relation', $id)->get();
                    if(count($config_section) > 0){
                        $message = true; 
                    }
            }
            return response()->json([
                                     'message'        => $message,
                                     'config_section' => $config_section
                                    ]);
    }

    public function check_case_number(Request $request)
    {      
        $message = false; 
        if(!empty($request->id) ){

             $id          = $request->id;
             $case_number = $request->case_number;
             $case_number_total =  LawCasesForm::whereNotIn('id',[$id])->where('case_number', $case_number)->get();
                    if(count($case_number_total) > 0){
                        $message = true; 
                    }
            }
            return response()->json([
                                     'message'  => $message
                                    ]);
    }

    public function law_cases_result_section($result,$lists)
    {      
     
        $list_id_data = [];
        if(isset($lists['id'])){
            foreach($lists['id'] as $item ){
            $list_id_data[] = $item;
            }
        }
        $lists_id = array_diff($list_id_data, [null]); 

        //ลบข้อมูลเดิม
        LawCasesResultSection::where('law_case_result_id',$result->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();
          
        foreach($lists['punish_id'] as $key => $item ){
 
                $section =  LawCasesResultSection::where('id', $lists['id'][$key])->first();
            if(is_null($section)){
                $section             = new LawCasesResultSection;
            }
            $section->law_case_result_id = $result->id;
            $section->section      = !empty( $lists['section_id'][$key])?$lists['section_id'][$key]:null;
            $section->punish       =  $item;
            $section->power        = !empty( $lists['power_id'][$key])?$lists['power_id'][$key]:null;
            $section->created_by  = auth()->user()->getKey();
            $section->save();

        }             

    }
    //  end บันทึกผลพิจารณางานคดี

    //  start  พิมพ์หนังสือแจ้งการกระทำความผิด
    public function printing($id)
    {
        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('edit-'.$model)) {


            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/results",  "name" => 'งานคดี' ],
                [ "link" => "/law/cases/results/$id/printing",  "name" => ' พิมพ์หนังสือแจ้งการกระทำความผิด' ],
            ];

            $lawcases = LawCasesForm::findOrFail($id);

            if( is_null($lawcases->law_cases_result_to) ){
                return redirect('law/cases/results')->with('results_error_message', 'กรุณาบันทึกผลพิจารณางานคดี');
            }

            return view('laws.cases.result.printing', compact('lawcases','breadcrumbs'));
        }
        abort(403);
    }
        
    public function save_printing(Request $request ,$id)
    {      

        $model = str_slug('law-cases-result','-');
        if(auth()->user()->can('edit-'.$model)) {
            $case = LawCasesForm::findOrFail($id);
            if(!is_null($case)){
                $result =  LawCasesResult::where('law_case_id', $case->id )->first();

                $requestData = $request->all();

                if(!is_null($result)){

                    $requestBook['law_case_result_id'] = $result->id;
                    $requestBook['law_cases_id']       = $case->id;

                    $requestBook['offend_act']         = (!empty($requestData['repeater-act']) && !empty(array_diff( array_column($requestData['repeater-act'], 'offend_act'), [null] )))?array_diff( array_column( $requestData['repeater-act'] , 'offend_act'), [null] ):null;
                    $requestBook['offend_report']      = (!empty($requestData['repeater-report']) && !empty(array_diff( array_column($requestData['repeater-report'], 'offend_report'), [null] )))?array_diff( array_column( $requestData['repeater-report'] , 'offend_report'), [null] ):null;

                    $requestBook['book_date']          =  !empty($requestData['book_date'])?$requestData['book_date']:null;
                    $requestBook['book_title']         =  !empty($requestData['book_title'])?$requestData['book_title']:null;
                    $requestBook['lawyer_id']          =  !empty($requestData['lawyer_id'])?$requestData['lawyer_id']:null;
                    $requestBook['book_to']            =  !empty($requestData['book_to'])?$requestData['book_to']:null;
                    $requestBook['offend_found']       =  !empty($requestData['offend_found'])?$requestData['offend_found']:null;
                    $requestBook['book_enclosure']     =  (!empty($requestData['repeater-enclosure']) && !empty(array_diff( array_column($requestData['repeater-enclosure'], 'book_enclosure'), [null] )))?array_diff( array_column( $requestData['repeater-enclosure'] , 'book_enclosure'), [null] ):null;

                    $book_offend                       =  LawCasesBookOffend::where('law_case_result_id', $result->id )->first();

                    if( is_null($book_offend)){
                        $requestBook['created_by']     = auth()->user()->getKey();
                        $book_offend                   = LawCasesBookOffend::create($requestBook);
                    }else{
                        $requestBook['updated_by']     = auth()->user()->getKey();
                        $book_offend->update( $requestBook );
                    }

                    //ถ้าเลขที่หนังสือว่าง
                    if( empty( $book_offend->book_number ) ){
                        //เลขรัน
                        $running_no =  HP::ConfigFormat('LawCasesBookCharges', (new LawCasesBookOffend)->getTable(), 'book_number', null, null, null);
                        $check = LawCasesBookOffend::where('book_number', $running_no)->whereNotNull('book_number')->first();
                        if(!is_null($check)){
                            $running_no =  HP::ConfigFormat('LawCasesBookCharges', (new LawCasesBookOffend)->getTable(), 'book_number', null, null, null);
                        }
                        $requestData['book_number'] = $running_no;

                        $book_offend->book_number = !empty($requestData['book_number'])?$requestData['book_number']:null;
                        $book_offend->save();
                    }

                }
            }
            return redirect('law/cases/results/'.$id.'/printing')->with('printing_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    	// start 
   public function set_running_no($case) { 

         if($case->owner_depart_type == '1'){
            $ref_type  =  'I';
         }else{
            $ref_type  =  'O';
         }

        $running_no =   HP::ConfigFormat('LawCasesNumber', (new LawCasesForm)->getTable(), 'case_number', $ref_type, null, null);
        
		try {
            $running_no =  null;
            $today      = date('Y-m-d');
            $dates      = explode('-', $today);
    
            $case_number =  LawCasesForm::select(
                                                DB::raw("MAX(case_number) AS case_number")
                                                )->where('owner_depart_type', $case->owner_depart_type)
                                                ->whereNotNull('case_number')
                                                ->where(function($query) use($dates){
                                                      $query->whereYear('created_at',$dates[0]);
                                                })
                                                ->value('case_number');
             if(!is_null($case_number)){

                $number      = explode('-', $case_number);
                if(!empty($number) && count($number) == '2'){
                   
                    $i = 1;
                    start:
      
                    if($i <= 3){
                        $no          = $number[1] + $i;
                        $no          = str_pad($no, 4, '0', STR_PAD_LEFT);
                        $book_number =  'C'.$ref_type.(date('y')+43).'-'.$no;
                    
                        $check = LawCasesBookOffend::where('book_number', $book_number)->whereNotNull('book_number')->value('book_number');   
                        if(is_null($check)){
                             $running_no  = $book_number;
                            goto end;
                        }else{
                            $i ++;
                            goto start;
                        }
                 
                     }else{
                          $i ++;
                          goto start;
                     }
      
                     end:
                   
                }
             }
   
             return  $running_no;
   
		   
		} catch (Exception $ex) {
  
			  return  $running_no;
  	  	}
	}
      // end 

}

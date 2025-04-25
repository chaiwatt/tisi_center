<?php

namespace App\Http\Controllers\Laws\Cases;

use HP;
use HP_Law;
use App\User;
use Form;
use Carbon\Carbon;
use App\Http\Requests;
use App\Models\Basic\Tis;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

use App\Models\Basic\SubDepartment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 


use App\Mail\Mail\Law\Cases\MailClose;
use App\Models\Law\Cases\LawCasesForm;
use App\Mail\Mail\Law\Cases\MailAssigns;
use App\Models\Law\Cases\LawCasesAssign;

class AssignController extends Controller
{
    private $permission;
    private $permission_track;
    public function __construct()
    {
        $this->middleware('auth');
        $this->permission       = str_slug('law-cases-assign','-');
        $this->permission_track = str_slug('law-cases-tracks','-');
    }
    

    public function data_list(Request $request)
    {

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = ($request->input('filter_status') == "0")?'-1':$request->input('filter_status');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_basic_section_id = $request->input('filter_basic_section_id');
        $filter_created_at       = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $filter_assign_status    = $request->input('filter_assign_status');
        $filter_close_status     = $request->input('filter_close_status');
        $view_all = auth()->user()->can('view_all-'.$this->permission);

        $query =  LawCasesForm::query()
                                    ->whereNotIn('status', ['0','99','98'])
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
                                                case "4":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->whereHas('user_assign_to', function ($query) use($search_full) {
                                                        $query->where(DB::Raw("REPLACE(reg_fname,' ','')"),  'LIKE', "%$search_full%")
                                                              ->orwhere(DB::Raw("REPLACE(reg_lname,' ','')"),  'LIKE', "%$search_full%");
                                                    });
                                                    break;
                                                case "5":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->whereHas('user_lawyer_to', function ($query) use($search_full) {
                                                        $query->where(DB::Raw("REPLACE(reg_fname,' ','')"),  'LIKE', "%$search_full%")
                                                              ->orwhere(DB::Raw("REPLACE(reg_lname,' ','')"),  'LIKE', "%$search_full%");
                                                    });
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where('ref_no', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                    ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                            })        
                                                            ->OrwhereHas('user_assign_to', function ($query) use($search_full) {
                                                                $query->where(DB::Raw("REPLACE(reg_fname,' ','')"),  'LIKE', "%$search_full%")
                                                                      ->orwhere(DB::Raw("REPLACE(reg_lname,' ','')"),  'LIKE', "%$search_full%");
                                                            })
                                                            ->OrwhereHas('user_lawyer_to', function ($query) use($search_full) {
                                                                $query->where(DB::Raw("REPLACE(reg_fname,' ','')"),  'LIKE', "%$search_full%")
                                                                      ->orwhere(DB::Raw("REPLACE(reg_lname,' ','')"),  'LIKE', "%$search_full%");
                                                            });

                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                            return $query->where('tis_id', $filter_tisi_no);
                                        })
                                        ->when($filter_basic_section_id, function ($query, $filter_basic_section_id){
                                            return $query->whereJsonContains('law_basic_section_id', (array)$filter_basic_section_id);
                                        })
                                        ->when($filter_created_at, function ($query, $filter_created_at){
                                            return $query->whereDate('created_at', $filter_created_at);
                                        })
                                        ->when($filter_assign_status, function ($query, $filter_assign_status){
                                            if( $filter_assign_status == '-1'){
                                                return  $query->where(function ($query2){
                                                    $query2->whereNull('assign_by')->OrwhereNull('lawyer_by');
                                                 });

                                            }else{
                                                return  $query->where(function ($query2){
                                                    $query2->whereNotNull('assign_by')->OrwhereNotNull('lawyer_by');
                                                 });
                                            }
                                        })
                                        ->when($filter_close_status, function ($query, $filter_close_status){
                                            if( $filter_close_status == '-1'){
                                                return $query->where('status_close', 0);
                                            }else if(  $filter_close_status == '99' ){
                                                return $query->whereNull('status_close');  
                                            }else{
                                                return $query->where('status_close',$filter_close_status);
                                            }
                                        })
                                        ->when(!$view_all, function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->where(function($query){
                                                $query->where('lawyer_by', Auth::user()->getKey())
                                                      ->Orwhere('assign_by', Auth::user()->getKey());
                                            });            
                                        });
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {

                                if( is_null($item->status_close) || !in_array($item->status_close, [1]) ){

                                    $data_input =  'data-ref_no="'.$item->ref_no.'"';
                                    $data_input .= 'data-offend_name="'.$item->offend_name.'"'; 
                                    $data_input .= 'data-offend_taxid="'.$item->offend_taxid.'"';
                                    $data_input .= 'data-sub_department_id="'.( !empty($item->law_cases_assign_to->sub_department_id) ? $item->law_cases_assign_to->sub_department_id : '').'" ';
                                    $data_input .= 'data-assign_by="'.$item->assign_by.'"';
                                    $data_input .= 'data-lawyer_by="'.$item->lawyer_by.'"';

                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" '.( $data_input ).' value="'. $item->id .'">';
                                }
                            })
                            ->addColumn('ref_no', function ($item) {
                                $startDate = Carbon::parse( $item->created_at )->format('Y-m-d');
                                $endDate   = Carbon::parse( (!empty($item->close_date) && $item->status_close == 1)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                $title =  'วันที่แจ้ง : '.(!empty($item->created_at) ?  HP::DateThai( $item->created_at ):' - ');
                                $title .= "\nวันที่ปิดงานคดี : ".(!empty($item->close_date) ? HP::DateThai( $item->close_date ):' - ');
                           
                                $html = '<div><small class="text-'.( (!empty($item->close_date) && $item->status_close == 1)?'success':'warning' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</small></div>';
                                $text  = !empty($item->ref_no) ? $item->ref_no : '';
                                $text  .= !empty($item->case_number) ? '<br/><b>'.$item->case_number.'</b>' : '';
                                return $text.$html;
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
                            ->addColumn('law_basic_section', function ($item) {
                                return !empty($item->SectionListName)?($item->SectionListName):'N/A';
                            })
                            ->addColumn('owner_department_name', function ($item) {
                                return $item->owner_department_name;
                            })
                            ->addColumn('assign_name', function ($item) {
                                if( !empty($item->user_assign_to) ){//มอบหมายแล้ว
                                    $startDate = Carbon::parse( $item->assign_at )->format('Y-m-d');
                                    $endDate   = Carbon::parse( (!empty($item->close_date) && $item->status_close == 1)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                    $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                    $title =  'วันที่มอบหมาย : '.(!empty($item->assign_at) ?  HP::DateThai( $item->assign_at ):' - ');
                                    $title .= "\nวันที่ปิดงานคดี : ".((!empty($item->close_date) && $item->status_close == 1) ? HP::DateThai( $item->close_date ):' - ');
                            
                                    $html = '<div><small class="text-'.( (!empty($item->close_date) && $item->status_close == 1)?'success':'warning' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</small></div>';

                                    return $item->user_assign_to->FullName.$html;
                                }else  if( count($item->UserAssignsArray) > 0 ){

                                          $input_select = '<select name="assign_name"  class="form-control assign_name select2" style="width:150px;" data-id="'.$item->id.'" >';
                                          $input_select .= '<option value="" >รอมอบหมาย</option>';
                                          foreach($item->UserAssignsArray as $assign){
                                            $input_select .= '<option value="'.$assign->id.'" data-email="'.$assign->email.'" >'.$assign->title.'</option>';
                                          }
                                          $input_select    .= '</select>';
                                       return     $input_select;
                                        //   return Form::select('assign_name',$item->UserAssignsArray, $item->assign_by, ['class' => 'form-control assign_name select2', 'data-id'=>$item->id, 'style'=>"width:150px;"]);
                                }else{ //มอบหมายแล้ว 

                                    $data_input =  'data-id="'.$item->id.'"';
                                    $data_input .= 'data-ref_no="'.$item->ref_no.'"';
                                    $data_input .= 'data-offend_name="'.$item->offend_name.'"'; 
                                    $data_input .= 'data-offend_taxid="'.$item->offend_taxid.'"';
                                    $data_input .= 'data-sub_department_id="'.( !empty($item->law_cases_assign_to->sub_department_id) ? $item->law_cases_assign_to->sub_department_id : '').'" ';
                                    $data_input .= 'data-assign_by="'.$item->assign_by.'"';
                                    $data_input .= 'data-lawyer_check="'.$item->lawyer_check.'"';
                                    $data_input .= 'data-lawyer_by="'.$item->lawyer_by.'"';

                                    return '<a  href="javascript:void(0)" class="text-muted single_assign"  '.( $data_input ).'  ><u>(รอมอบหมาย)</u></a>';

                                }
                            
                            })
                            ->addColumn('lawyer_name', function ($item) {

                                if( !empty($item->user_lawyer_to) ){//มอบหมายแล้ว
                                    $startDate = Carbon::parse( $item->lawyer_at )->format('Y-m-d');
                                    $endDate   = Carbon::parse( (!empty($item->close_date) && $item->status_close == 1)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                    $lits  = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                    $title =  'วันที่ได้รับมอบหมาย : '.(!empty($item->lawyer_at) ?  HP::DateThai( $item->lawyer_at ):' - ');
                                    $title .= "\nวันที่ปิดงานคดี : ".((!empty($item->close_date) && $item->status_close == 1) ? HP::DateThai( $item->close_date ):' - ');
                               
                                    $html = '<div><small class="text-'.( (!empty($item->close_date) && $item->status_close == 1)?'success':'warning' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</small></div>';
                                    return $item->user_lawyer_to->FullName.$html;
                                }else  if( count($item->UserLawyerArray) > 0 ){
                                    $input_select = '<select name="lawyer_name"  class="form-control lawyer_name select2" style="width:150px;" data-id="'.$item->id.'" >';
                                    $input_select .= '<option value="" >รอมอบหมาย</option>';
                                    foreach($item->UserLawyerArray as $lawyer){
                                      $input_select .= '<option value="'.$lawyer->id.'" data-email="'.$lawyer->email.'" >'.$lawyer->title.'</option>';
                                    }
                                    $input_select    .= '</select>';
                                 return     $input_select;
                                    // return Form::select('lawyer_name',$item->UserLawyerArray, $item->lawyer_by, ['class' => 'form-control lawyer_name select2', 'data-id'=>$item->id, 'style'=>"width:150px;"]);
                                 }else{ //มอบหมายแล้วelse{//ยังไม่ได้มอบหมาย

                                    $data_input =  'data-id="'.$item->id.'"';
                                    $data_input .=  'data-ref_no="'.$item->ref_no.'"';
                                    $data_input .= 'data-offend_name="'.$item->offend_name.'"'; 
                                    $data_input .= 'data-offend_taxid="'.$item->offend_taxid.'"';
                                    $data_input .= 'data-sub_department_id="'.( !empty($item->law_cases_assign_to->sub_department_id) ? $item->law_cases_assign_to->sub_department_id : '').'" ';
                                    $data_input .= 'data-assign_by="'.$item->assign_by.'"';
                                    $data_input .= 'data-lawyer_check="'.$item->lawyer_check.'"';
                                    $data_input .= 'data-lawyer_by="'.$item->lawyer_by.'"';

                                    return '<a  href="javascript:void(0)" class="text-muted single_assign "  '.( $data_input ).'  ><u>(รอมอบหมาย)</u></a>';
                                }
                             


                            })
                            ->addColumn('status', function ($item){ 
                                return  !empty($item->StatusColorHtml) ? $item->StatusColorHtml : '';
                            })
                            ->addColumn('view', function ($item) {
                                $url     = auth()->user()->can('view-'.$this->permission)?url('law/cases/assigns/'.$item->id):'javascript:void(0)';
                                $allowed = auth()->user()->can('view-'.$this->permission)?'':'not-allowed';

                                return  '<a  href="'.(  $url  ).'" class="btn btn-icon btn-circle btn-light-info '.( $allowed ).'"><i class="fa fa-info-circle" style="font-size: 1.5em;"></i></a>';
                            })
                            ->addColumn('follow', function ($item) {

                                $url     = auth()->user()->can('view-'.$this->permission)?url('law/cases/tracks/'.$item->id):'javascript:void(0)';
                                $allowed = auth()->user()->can('view-'.$this->permission)?'':'not-allowed';

                                return  '<a  href="'.( $url ).'"  class="btn btn-icon btn-circle btn-light-info '.($allowed).'" ><i class="fa fa-search" style="font-size: 1.5em;"></i> </a>'; 
                            })
                            ->addColumn('approve', function ($item) {
                                if($item->approve_type == 1){
                                    $data_input =   'data-id="'.($item->id).'"';
                                    return '<a  href="javascript:void(0)" class="show_approve"  '.( $data_input ).' >'.$item->ApproveTypeText.'<br><u class="text-muted"> ('.$item->SubDepartShortname.')</u></a>';
                                }else{
                                    return  !empty($item->ApproveTypeText) ? $item->ApproveTypeText:'ยังไม่ส่งเรื่องพิจารณา';
                                }
                            })
                            ->addColumn('action', function ($item) {

                                $close_by        =  (!empty($item->close_date) && !empty($item->close_by)) ?  $item->CloseName.' | '.HP::revertDate($item->close_date, true) :auth()->user()->Fullname.' | '.HP::revertDate( date('Y-m-d'), true);
                                $data_input      =  'data-id="'.$item->id.'"';
                                $data_input      .= 'data-ref_no="'.$item->ref_no.'"';
                                $data_input      .= 'data-offend_name="'.$item->offend_name.'"';
                                $data_input      .= 'data-owner_taxid="'.$item->owner_taxid.'"';
                                $data_input      .= 'data-owner_email="'.$item->owner_email.'"';
                                $data_input      .= 'data-remark="'.$item->close_remark.'"';
                                $data_input     .=  'data-lawyer_check="'.$item->lawyer_check.'"';
                                $data_input     .=  'data-lawyer_by="'.$item->lawyer_by.'"';
                             

                                $icon            = '<i class="fa fa-check" style="font-size: 1.5em;" ></i>';

                                if(!is_null($item->status_close)){ //นิติกรแจ้งปิดงาน หรือ ปิดงานไปแล้ว
                                    $html  = '<button  type="button" class="btn btn-icon btn-circle   '.( ( !is_null($item->status_close) && $item->status_close == 1 )?'btn-light-success':'btn-light-primary' ).' close_the_case btn-xs circle" '.( $data_input ).'>'.($icon).'</button>';
                                }else{
                                    $html  = '<button  type="button" class="btn btn-icon btn-circle btn-light-primary btn-xs circle" onclick="return close_alert()" '.( $data_input ).'>'.($icon).'</button>';   
                                }
                                $html .= ( !is_null($item->status_close) && $item->status_close == 0 )?'<div><span class="text-primary">[แจ้งปิดงาน]</span></div>':( ( !is_null($item->status_close) && $item->status_close == 1 )?'<div><span class="text-success">[ปิดงาน]</span></div>':'' );
                                return $html;
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
                            ->rawColumns(['checkbox', 'ref_no', 'offend_name','tis_name', 'lawyer_name', 'assign_name', 'status', 'follow', 'view', 'action','approve','law_basic_section'])
                            ->make(true);
    }


    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/assigns",  "name" => 'มอบหมายงานคดีผลิตภัณฑ์ฯ' ],
            ];

            if(auth()->user()->can('view-'.$this->permission_track)) {
                $visible_follow = true;
            }else{
                $visible_follow = false;
            }
            return view('laws.cases.assign.index',compact('breadcrumbs','visible_follow'));
        }
        abort(403);
    }
 
    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $case                                = LawCasesForm::findOrFail($id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/assigns",  "name" => 'มอบหมายงานคดีผลิตภัณฑ์ฯ' ],
                [ "link" => "/law/cases/assigns/$id",  "name" => 'รายละเอียด' ],

            ];

            return view('laws.cases.assign.show', compact('case','breadcrumbs'));
        }
        abort(403);
    }

    public function save_assign(Request $request)
    {      
        $message = false;
        if(!empty($request->ids) && count($request->ids) > 0){//มอบหมายจาก checkbox
            $department_name  = SubDepartment::selectRaw('sub_departname')->where('sub_id',$request->sub_department_id)->value('sub_departname');
          
            foreach($request->ids as $id){
                $case = LawCasesForm::findOrFail($id);
                if(!is_null($case)){
                        $user_ids = [];
                     if($case->assign_id  !=  $request->assign_id){
                         $user_ids[$request->assign_id] =  $request->assign_id;
                     }

                     if(!empty($request->lawyer_ids)){
                        $user_ids[$request->lawyer_ids] =  $request->lawyer_ids;
                     }
                
                    $requestData['assign_by']                 =  $request->assign_id ?? null;
                    $requestData['lawyer_by']                 =  $request->lawyer_ids ?? null;
                    $requestData['lawyer_check']              =  $request->lawyer_check ?? null;
               
                    if(!empty($case->status) && $case->status <= '2'){ //ห้ามย้อนสถานะ
                        $requestData['status']  = 2;
                    }
                    if(($case->assign_by  !=  $request->assign_id) || empty($case->assign_at)){
                        $requestData['assign_at']  =  date('Y-m-d'); 
                    }

                    if(($case->lawyer_by  !=  $request->lawyer_ids) || empty($case->lawyer_at)){
                        $requestData['lawyer_at']   =  date('Y-m-d'); 
                    }
                  
                    $case->update($requestData); 
       
                    // log 
                    $log = new LawCasesAssign;
                    $log->law_case_id        = $id;
                    $log->sub_department_id  = $request->sub_department_id ?? null;
                    $log->assign_by          = $request->assign_id ?? null;
                    $log->lawyer_check       = $request->lawyer_check ?? null;
                    $log->lawyer_by          = $request->lawyer_ids ?? null;
                    $log->created_by         = auth()->user()->getKey();
                    $log->save();


                    if( count($user_ids) > 0 ){
                         foreach($user_ids as $user_id){
                                $user = User::findOrFail($user_id);
                                if(!empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                                    $data_app = [
                                                    'case'           => $case,
                                                    'assign'         => $log,
                                                    'user'           => $user,
                                                    'title'          => "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no"
                                             ]; 
                                    $log_email =  HP_Law::getInsertLawNotifyEmail(1,
                                                                            ((new LawCasesAssign)->getTable()),
                                                                            $log->id,
                                                                            'แจ้งมอบหมายงานคดี',
                                                                            "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no",
                                                                            view('mail.Law.Cases.assigns', $data_app),
                                                                            null,  
                                                                            null,  
                                                                            $user->reg_email
                                                                            );
                                    $html = new MailAssigns($data_app);
                                     Mail::to($user->reg_email)->send($html);
                                }
                         }        
                    }


                }
            }
            $message = true;

        }else if(!empty($request->id)){//มอบหมายจาก record

            $case = LawCasesForm::findOrFail($request->id);
            if(!is_null($case)){
                    $user_ids = [];
                 if($case->assign_id  !=  $request->assign_id){
                     $user_ids[$request->assign_id] =  $request->assign_id;
                 }

                 if(!empty($request->lawyer_ids)){
                    $user_ids[$request->lawyer_ids] =  $request->lawyer_ids;
                 }
            
                $requestData['assign_by']                 =  $request->assign_id ?? null;
                $requestData['lawyer_by']                 =  $request->lawyer_ids ?? null;
                $requestData['lawyer_check']              =  $request->lawyer_check ?? null;
         
                if(!empty($case->status) && $case->status <= '2'){ //ห้ามย้อนสถานะ
                    $requestData['status']  = 2;
                }
                if(($case->assign_by  !=  $request->assign_id) || empty($case->assign_at)){
                    $requestData['assign_at']  =  date('Y-m-d'); 
                }

                if(($case->lawyer_by  !=  $request->lawyer_ids) || empty($case->lawyer_at)){
                    $requestData['lawyer_at']   =  date('Y-m-d'); 
                }
              
                $case->update($requestData); 
   
                // log 
                $log = new LawCasesAssign;
                $log->law_case_id        = $request->id;
                $log->sub_department_id  = $request->sub_department_id ?? null;
                $log->assign_by          = $request->assign_id ?? null;
                $log->lawyer_check       = $request->lawyer_check ?? null;
                $log->lawyer_by          = $request->lawyer_ids ?? null;
                $log->created_by         = auth()->user()->getKey();
                $log->save();


                if( count($user_ids) > 0 ){
                     foreach($user_ids as $user_id){
                            $user = User::findOrFail($user_id);
                            if(!empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                                $data_app = [
                                                'case'           => $case,
                                                'assign'         => $log,
                                                'user'           => $user,
                                                'title'          => "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no"
                                         ]; 
                            HP_Law::getInsertLawNotifyEmail(1,
                                                                        ((new LawCasesAssign)->getTable()),
                                                                        $log->id,
                                                                        'แจ้งมอบหมายงานคดี',
                                                                        "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no",
                                                                        view('mail.Law.Cases.assigns', $data_app),
                                                                        null,  
                                                                        null,  
                                                                        $user->reg_email
                                                                        );
                                $html = new MailAssigns($data_app);
                                 Mail::to($user->reg_email)->send($html);
                            }
                     }        
                }
                $message = true;

            }
            
        }

        return response()->json(['message' => $message ]);
    }

    public function save_select_assign(Request $request)
    {      
        $message = false;
        if(!empty($request->assign_id)){//มอบหมาย

            $case = LawCasesForm::findOrFail($request->id);
            if(!is_null($case)){
                    $user_ids = [];
                 if($case->assign_id  !=  $request->assign_id){
                     $user_ids[$request->assign_id] =  $request->assign_id;
                 }
  
                $requestData['assign_by']                 =  $request->assign_id ?? null;

                if(!empty($case->status) && $case->status <= '2'){ //ห้ามย้อนสถานะ
                    $requestData['status']  = 2;
                }

                if(($case->assign_by  !=  $request->assign_id) || empty($case->assign_at)){
                    $requestData['assign_at']  =  date('Y-m-d'); 
                }
 
              
                $case->update($requestData); 

                $reg_subdepart  = User::where('runrecno',$request->assign_id)->value('reg_subdepart');
                // log 
                $log = new LawCasesAssign;
                $log->law_case_id        = $case->id;
                $log->sub_department_id  = $reg_subdepart ?? null;
                $log->assign_by          = $request->assign_id ?? null;
                $log->lawyer_by          =  null;
                $log->created_by         = auth()->user()->getKey();
                $log->save();

                if( count($user_ids) > 0 ){
                     foreach($user_ids as $user_id){
                            $user = User::findOrFail($user_id);
                            if(!empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                                $data_app = [
                                                'case'           => $case,
                                                'assign'         => $log,
                                                'user'           => $user,
                                                'title'          => "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no"
                                         ]; 
                            HP_Law::getInsertLawNotifyEmail(1,
                                                                        ((new LawCasesAssign)->getTable()),
                                                                        $log->id,
                                                                        'แจ้งมอบหมายงานคดี',
                                                                        "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no",
                                                                        view('mail.Law.Cases.assigns', $data_app),
                                                                        null,  
                                                                        null,  
                                                                        $user->reg_email
                                                                        );
                                $html = new MailAssigns($data_app);
                                 Mail::to($user->reg_email)->send($html);
                            }
                     }        
                }
                $message = true;
            }
            
        }else  if(!empty($request->lawyer_by)){//นิติกรผู้รับผิดชอบ

            $case = LawCasesForm::findOrFail($request->id);
            if(!is_null($case)){
                    $user_ids = [];
                 if($case->lawyer_by  !=  $request->lawyer_by){
                     $user_ids[$request->lawyer_by] =  $request->lawyer_by;
                 }
  
                   $requestData['lawyer_by']      =  $request->lawyer_by ?? null;

                 if(!empty($case->status) && $case->status <= '2'){ //ห้ามย้อนสถานะ
                     $requestData['status']       = 2;
                 }
 
                 if(($case->lawyer_by  !=  $request->lawyer_ids) || empty($case->lawyer_at)){
                     $requestData['lawyer_at']    =  date('Y-m-d'); 
                 }
              
                $case->update($requestData); 

                $reg_subdepart  = User::where('runrecno',$request->lawyer_by)->value('reg_subdepart');
                // log 
                $log = new LawCasesAssign;
                $log->law_case_id        = $case->id;
                $log->sub_department_id  = $reg_subdepart ?? null;
                $log->lawyer_by          = $request->lawyer_by ?? null;
                $log->assign_by          =  null;
                $log->created_by         = auth()->user()->getKey();
                $log->save();

                if( count($user_ids) > 0 ){
                     foreach($user_ids as $user_id){
                            $user = User::findOrFail($user_id);
                            if(!empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                                $data_app = [
                                                'case'           => $case,
                                                'assign'         => $log,
                                                'user'           => $user,
                                                'title'          => "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no"
                                         ]; 
                            HP_Law::getInsertLawNotifyEmail(1,
                                                                        ((new LawCasesAssign)->getTable()),
                                                                        $log->id,
                                                                        'แจ้งมอบหมายงานคดี',
                                                                        "แจ้งมอบหมายงานคดี ของ $case->offend_name เลขอ้างอิง $case->ref_no",
                                                                        view('mail.Law.Cases.assigns', $data_app),
                                                                        null,  
                                                                        null,  
                                                                        $user->reg_email
                                                                        );
                                $html = new MailAssigns($data_app);
                                 Mail::to($user->reg_email)->send($html);
                            }
                     }        
                }
                $message = true;
            }
            
        }

        return response()->json(['message' => $message ]);
    }

    
 
    public function save_close_assign(Request $request)
    {      
        $message = false;
        $requestData = $request->all();
       
        if(!empty($request->id) ){
            $id = $request->id;
            $case = LawCasesForm::findOrFail($id);
            if(!is_null($case)){
                $requestData['status_close']   = 1;
                $requestData['status']         = 15;
                $requestData['close_date']     = date('Y-m-d H:i:s'); 
                $requestData['close_remark']   = !empty($request->remark) ? $request->remark   :  null ;  
                $requestData['close_by']       = auth()->user()->getKey();
                $case->update($requestData); 

                if( isset($requestData['send_mail']) && $requestData['send_mail'] == 1 ){//แจ้งเตือนไปยังผู้แจ้งงานคดี
                    $this->send_mail($case,$request);
                }
            }
            $message = true; 
        }
        return response()->json([ 'message' => $message  ]);
    }

    

    public function send_mail($case, $request){   

        if(isset($request->mail_list) && count($request->mail_list) > 0){
  
            $mail_lists = [];
            foreach($request->mail_list as $email){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$mail_lists)){
                    $mail_lists[] =  $email;
                }
            }

            if(count($mail_lists) > 0){

                $users = User::whereIn('reg_email',$mail_lists)->get();
                $reg_email = [];
                if(!empty($users) && count($users) > 0){
  
                    foreach($users as $user){

                        $url = '';
                        if($user->can('view-'.$this->permission)){//เช็ตสิทธิ์ ดู url
                            $url .= 'สามารถติดตามตรวจสอบ <a href="' . url('law/cases/tracks/'.$case->id).'">คลิกที่นี่</a>';
                        }
          
                        $data_app =  [  // ข้อมูล
                            'case'  => $case,
                            'url'   => $url,
                            'title' => "e-Legal : แจ้งปิดงานคดี ของ $case->offend_name เลขคดี $case->case_number"
                        ];
                            $html = new MailClose($data_app);   
                            Mail::to($user->reg_email)->send($html);
                            $reg_email[] =  $user->reg_email;
                        
            
                    }
                }

                $data_app_log =  [  // ข้อมูล
                    'case'  => $case,
                    'url'   => ' ',
                    'title' => "e-Legal : แจ้งปิดงานคดีของ $case->offend_name เลขคดี $case->case_number"
                ];

                HP_Law::getInsertLawNotifyEmail(1,
                                               ((new LawCasesForm)->getTable()),
                                               $case->id,
                                               'มอบหมายงานคดีผลิตภัณฑ์ฯ',
                                               "e-Legal : แจ้งปิดงานคดี ของ $case->offend_name เลขคดี $case->case_number",
                                               view('mail.Law.Cases.close', $data_app_log),
                                               null,  
                                               null,   
                                               json_encode($mail_lists)   
                                           );

                $mail_not_url = array_diff($mail_lists,$reg_email);

                $html_not_url = new MailClose($data_app_log);   
                Mail::to($mail_not_url)->send($html_not_url);

            }
        }
    }

}
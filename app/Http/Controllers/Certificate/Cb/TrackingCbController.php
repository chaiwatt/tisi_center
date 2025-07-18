<?php

namespace App\Http\Controllers\Certificate\Cb;

use HP;

use Storage;
use App\User;
use stdClass;
use Carbon\Carbon;
use App\AttachFile; 

use Illuminate\Http\Request;
 
use App\Helpers\EpaymentDemo;

use App\Models\Basic\Feewaiver; 
use Yajra\Datatables\Datatables;
use App\Mail\Tracking\ReportMail; 
use Illuminate\Support\Facades\DB;

use App\Mail\Tracking\PayInOneMail;
use App\Mail\Tracking\ReceiverMail;
use App\Http\Controllers\Controller;
use App\Mail\Tracking\PayInTwoMail; 
use App\Models\Certificate\Tracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use App\Mail\Tracking\AssignStaffMail;
use App\Mail\Tracking\InformPayInOne; 
use App\Mail\Tracking\InspectiontMail;

use App\Models\Bcertify\SettingConfig;
use App\Models\Certify\SetStandardUser;

use App\Models\Certify\EpaymentBillTest;
use App\Models\Certificate\TrackingReview;
use App\Models\Certificate\TrackingStatus;
use App\Models\Certify\SetStandardUserSub;
use App\Models\Certificate\TrackingAssigns;
use App\Models\Certificate\TrackingReport; 
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingHistory; 
use App\Models\Certificate\TrackingPayInOne;
use App\Models\Certificate\TrackingPayInTwo;
use App\Models\Certify\ApplicantCB\CertiCb; 
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingInspection;

use App\Models\Certify\ApplicantCB\CertiCBCheck;
use App\Models\Certify\ApplicantCB\CertiCBExport; 
use App\Models\Certify\ApplicantCB\CertiCBFileAll;
use App\Models\Certify\ApplicantCB\CertiCbHistory;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Mail\Tracking\RequestEditCbIbScopeFromTracking;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsDate;

class TrackingCbController extends Controller
{


   
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/trackingcb';
    }


    public function index(Request $request)
    { 
   
        $model = str_slug('trackingcb','-');
        if(auth()->user()->can('view-'.$model)) {


            $user =   User::where('runrecno',auth()->user()->runrecno)->first();
            if(!is_null($user) && $user->IsGetIdRoles() == 'false'){  //ไม่ใช่ admin , ผอ , ลท
                $select_users  = User::where('reg_subdepart',$user->reg_subdepart)  //มอบ เจ้าหน้าที่ รับผิดชอบ  สก.
                                ->whereNotIn('runrecno',[$user->runrecno])
                                ->select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                                ->orderbyRaw('CONVERT(title USING tis620)')
                                ->pluck('title','runrecno');
             }else{
    
                 $select_users  = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                                ->whereIn('reg_subdepart',[1803])
                                ->orderbyRaw('CONVERT(title USING tis620)')
                                ->pluck('title','runrecno');
             }
        
            return view('certificate.cb.tracking-cb.index', compact('select_users'));
        }
        abort(403);

    }


    public function data_list(Request $request)
    {
        $userLogIn  = Auth::check()?Auth::user():null;
        $roles      = !empty( $userLogIn ) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $model      = str_slug('trackingcb', '-');

        $filter_search      = $request->input('filter_search');
        $filter_certificate = $request->input('filter_certificate');
        $filter_status_id   = $request->input('filter_status_id');
        $filter_start_date  = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date   = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;

        //ตั้งค่าการตรวจติดตามใบรับรอง             
        $setting_config  = SettingConfig::where('grop_type','cb')->first();
        $from_filed      = !empty($setting_config->from_filed)?$setting_config->from_filed:null;
        $condition_check = !empty($setting_config->condition_check)?$setting_config->condition_check:null;
        $warning_day     = !empty($setting_config->warning_day)?$setting_config->warning_day:null;
        $check_first     = !empty($setting_config->check_first)?$setting_config->check_first:null;
   
   
        $app_certi_cb_id  = CertiCBCheck::where('user_id',auth()->user()->runrecno)->select('app_certi_cb_id'); // เช็คเจ้าหน้าที่ LAB
   
        $app_certi_cb_export = CertiCBExport::LeftJoin((new CertiCb)->getTable()." AS app_certi_cb", 'app_certi_cb.id', '=', 'app_certi_cb_export.app_certi_cb_id')
                                            ->leftJoin((new Tracking)->getTable(), function($query) {
                                                $query->on('app_certi_tracking.ref_id', 'app_certi_cb_export.id')->where('app_certi_tracking.ref_table',(new CertiCBExport)->getTable());
                                            })
                                            ->LeftJoin((new TrackingStatus)->getTable()." AS app_certi_tracking_status", 'app_certi_tracking_status.id', '=', 'app_certi_tracking.status_id')
                                            ->where(function($query){
                                                $query->where('app_certi_cb_export.status',4)->WhereNull('app_certi_cb_export.status_revoke'); //เงือนไขการแสดงข้อมูล
                                            })
                                            ->where( function($query) {
                                                $query->whereNull('app_certi_tracking.id');
                                            })
                                           

                                            ->where( function($query)  use($userLogIn, $roles, $app_certi_cb_id ) {
                                                if( in_array( 31 , $roles ) && Auth::check() && in_array( $userLogIn->IsGetIdRoles() , ['false'] )   ){ //ไม่ใช่ admin , ผอ , ลท ที่มี Role 31
                                                    // dd('a');
                                                    $scope_ids =  CertiCBExport::LeftJoin((new CertiCb)->getTable()." AS app", 'app.id', '=', 'app_certi_cb_export.app_certi_cb_id')
                                                                                ->leftJoin((new SetStandardUserSub)->getTable().' AS user_sub','user_sub.standard_id','app.type_standard')
                                                                                ->leftJoin((new SetStandardUser)->getTable().' AS user_set','user_set.id','user_sub.standard_user_id')
                                                                                ->where('user_set.sub_department_id', $userLogIn->reg_subdepart  )
                                                                                ->select('app_certi_cb_export.id');
                                                    $query->whereIn('app_certi_cb_export.id',$scope_ids);
                                                // }else{
                                                    }else if( in_array( $userLogIn->IsGetIdRoles() , ['false'] ) ){
                                                    //  dd('b');
                                                    $query->whereIn('app_certi_cb_export.app_certi_cb_id',$app_certi_cb_id);
                                                }
                                            })
                                            
                                            // ->get();
                                            // dd($app_certi_cb_export);
                                            ->when($setting_config, function ($query) use ($from_filed, $condition_check, $warning_day, $check_first){
                                                switch ( $from_filed ):
                                                    case "1": //วันที่ออกใบรับรอง
                                                        if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                                            return $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(app_certi_cb_export.date_start), INTERVAL 6 MONTH),CURDATE())' ), '<=', $warning_day);
                                                        }else{
                                                            return $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(app_certi_cb_export.date_start), INTERVAL '.$condition_check.' MONTH),CURDATE())' ), '<=', $warning_day);
                                                        }
                                                        break;
                                                    case "3": //วันที่ตรวจล่าสุด
                                                    if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                                        return  $query->whereHas('app_certi_cb_auditors', function($query)use ($warning_day){
                                                                    $query->whereHas('app_certi_cb_auditors_date', function($query) use ($warning_day){
                                                                        $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(end_date), INTERVAL 6 MONTH),CURDATE())'), '<=', $warning_day);
                                                                    });
                                                        });
                                                    }else{
                                                        return  $query->whereHas('app_certi_cb_auditors', function($query)use ($condition_check, $warning_day){
                                                                    $query->whereHas('app_certi_cb_auditors_date', function($query) use ( $condition_check, $warning_day){
                                                                        $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(end_date), INTERVAL '.$condition_check.' MONTH),CURDATE())'), '<=', $warning_day);
                                                                    });
                                                        });
                                                    }
                                                        break;
                                                endswitch;
                                            })

                                            //filter
                                            ->when($filter_search, function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search );
                                                $query->where( function($query) use($search_full) {
                                                    $query->where(DB::Raw("REPLACE(app_certi_cb_export.certificate ,' ','')"),  'LIKE', "%$search_full%")
                                                            ->OrWhere(DB::raw("REPLACE(app_certi_tracking.reference_refno,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrWhere(DB::raw("REPLACE(app_certi_cb.name,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrWhere(DB::raw("REPLACE(app_certi_cb.cb_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                        });
                                            })
                                            ->when($filter_certificate, function ($query, $filter_certificate){
                                                return $query->where('app_certi_cb_export.certificate', $filter_certificate);
                                            })
                                            ->when($filter_status_id, function ($query, $filter_status_id){
                                                return $query->where('app_certi_tracking.status_id', $filter_status_id);
                                            })
                                            ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date, $from_filed){

                                                $start_date =  $this->config_date($filter_start_date);
                                                $end_date   =  $this->config_date($filter_end_date);
                                                if($from_filed == 1){
                                                    if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                        return $query->whereBetween('app_certi_cb_export.date_start',[$start_date,$end_date]);
                                                    }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                        return $query->whereDate('app_certi_cb_export.date_start',$start_date);
                                                    }
                                                }else if($from_filed == 3){
                                                    if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                        $auditors_id = CertiCBAuditorsDate::whereBetween('end_date',[$start_date,$end_date])->select('auditors_id');
                                                        $app_certi_cb_id = CertiCBAuditors::whereIn('id',$auditors_id)->select('app_certi_cb_id');
                                                        return $query->whereIn('app_certi_cb_export.app_certi_cb_id', $app_certi_cb_id);
                            
                                                    }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                        $auditors_id = CertiCBAuditorsDate::whereDate('end_date',$start_date)->select('auditors_id');
                                                        $app_certi_cb_id = CertiCBAuditors::whereIn('id',$auditors_id)->select('app_certi_cb_id');
                                                        return $query->whereIn('app_certi_cb_export.app_certi_cb_id', $app_certi_cb_id);
                                                    }
                                                }
                                            })
                                            ->select(
                                                DB::raw('"1" AS type'),
                                                DB::raw('app_certi_cb_export.id AS id'),
                                                DB::raw('app_certi_cb_export.certificate AS certificate'),
                                                DB::raw('app_certi_cb_export.date_start AS date_start'),
                                                DB::raw('app_certi_tracking.reference_refno AS reference_refno'),
                                                DB::raw('app_certi_tracking.status_id AS status_id'),
                                                DB::raw('app_certi_tracking_status.title AS status_title'),
                                                DB::raw('app_certi_cb.name_standard AS org_name'),
                                                DB::raw('app_certi_cb.id AS app_certi_cb_id'),
                                                DB::raw('app_certi_cb.cb_name AS cb_name')
                                            );

                                            // dd($app_certi_cb_export->get());

        $app_certi_tracking = Tracking::LeftJoin((new CertiCBExport)->getTable()." AS app_certi_cb_export", 'app_certi_cb_export.id', '=', 'app_certi_tracking.ref_id')
                                        ->LeftJoin((new CertiCb)->getTable()." AS app_certi_cb", 'app_certi_cb.id', '=', 'app_certi_cb_export.app_certi_cb_id')
                                        ->LeftJoin((new TrackingStatus)->getTable()." AS app_certi_tracking_status", 'app_certi_tracking_status.id', '=', 'app_certi_tracking.status_id')
                                        ->where(function($query){
                                            $query->WhereNotNull('status_id')->where('ref_table',(new CertiCBExport)->getTable());
                                        })
                                        ->where( function($query)  use($userLogIn, $roles, $app_certi_cb_id ) {
                                            if( in_array( 31 , $roles ) && Auth::check() && in_array( $userLogIn->IsGetIdRoles() , ['false'] )   ){ //ไม่ใช่ admin , ผอ , ลท ที่มี Role 31
                                                $scope_ids =  CertiCBExport::LeftJoin((new CertiCb)->getTable()." AS app", 'app.id', '=', 'app_certi_cb_export.app_certi_cb_id')
                                                                            ->leftJoin((new SetStandardUserSub)->getTable().' AS user_sub','user_sub.standard_id','app.type_standard')
                                                                            ->leftJoin((new SetStandardUser)->getTable().' AS user_set','user_set.id','user_sub.standard_user_id')
                                                                            ->where('user_set.sub_department_id', $userLogIn->reg_subdepart  )
                                                                            ->select('app_certi_cb_export.id');
                                                $query->whereIn('app_certi_cb_export.id',$scope_ids);
                                            // }else{
                                             }else if( in_array( $userLogIn->IsGetIdRoles() , ['false'] ) ){
                                                $query->whereHas('assigns_to', function($query) {
                                                            $query->where('user_id',  auth()->user()->runrecno);
                                                        });
                                            }
                                        })
                                        ->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search );
                                            $query->where( function($query) use($search_full) {
                                                $query->where(DB::Raw("REPLACE(app_certi_cb_export.certificate ,' ','')"),  'LIKE', "%$search_full%")
                                                        ->OrWhere(DB::raw("REPLACE(app_certi_tracking.reference_refno,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(app_certi_cb.name,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(app_certi_cb.cb_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                    });
                                        })   
                                        ->when($filter_certificate, function ($query, $filter_certificate){
                                            return $query->where('app_certi_cb_export.certificate', $filter_certificate);
                                        })
                                        ->when($filter_status_id, function ($query, $filter_status_id){
                                            return $query->where('app_certi_tracking.status_id', $filter_status_id);
                                        })
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date, $from_filed){

                                            $start_date =  $this->config_date($filter_start_date);
                                            $end_date   =  $this->config_date($filter_end_date);
                                            if($from_filed == 1){
                                                if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                    return $query->whereBetween('app_certi_cb_export.date_start',[$start_date,$end_date]);
                                                }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                    return $query->whereDate('app_certi_cb_export.date_start',$start_date);
                                                }
                                            }else if($from_filed == 3){
                                                if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                    $auditors_id = CertiCBAuditorsDate::whereBetween('end_date',[$start_date,$end_date])->select('auditors_id');
                                                    $app_certi_cb_id = CertiCBAuditors::whereIn('id',$auditors_id)->select('app_certi_cb_id');
                                                    return $query->whereIn('app_certi_cb_id', $app_certi_cb_id);
                        
                                                }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                    $auditors_id = CertiCBAuditorsDate::whereDate('end_date',$start_date)->select('auditors_id');
                                                    $app_certi_cb_id = CertiCBAuditors::whereIn('id',$auditors_id)->select('app_certi_cb_id');
                                                    return $query->whereIn('app_certi_cb_id', $app_certi_cb_id);
                                                }
                                            }
                                        })
                                        ->select(
                                            DB::raw('"2" AS type'),
                                            DB::raw('app_certi_tracking.id AS id'),
                                            DB::raw('app_certi_cb_export.certificate AS certificate'),
                                            DB::raw('app_certi_cb_export.date_start AS date_start'),
                                            DB::raw('app_certi_tracking.reference_refno AS reference_refno'),
                                            DB::raw('app_certi_tracking.status_id AS status_id'),
                                            DB::raw('app_certi_tracking_status.title AS status_title'),
                                            DB::raw('app_certi_cb.name_standard AS org_name'),
                                            DB::raw('app_certi_cb.id AS app_certi_cb_id'),
                                            DB::raw('app_certi_cb.cb_name AS cb_name')
                                        );

                                        // dd($app_certi_tracking->get());

        $query =  $app_certi_cb_export->union($app_certi_tracking);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {           
                                if(!empty($item->status_id)  && $item->status_id >= 8){
                                    return '';
                                }else{
                                     return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'" data-status="'.( !empty($item->status_id) ? $item->status_id : '1').'" data-tracking_id="'.( $item->type == 2 ? $item->id: '').'" >';
                                }
                            })
                            ->addColumn('reference_refno', function ($item) {
                                return  !empty($item->reference_refno)? $item->reference_refno:'<em class="text-muted">อยู่ระหว่างรับเรื่อง</em>';;
                            }) 
                            ->addColumn('certificate_no', function ($item) {
                                return   !empty($item->certificate)? $item->certificate:'';
                            })
                            ->addColumn('cb_name', function ($item) {
                                return   !empty($item->cb_name)? $item->cb_name:'';
                                // return  'dfgdg';
                            }) 
                            ->addColumn('name_standard', function ($item) {
                                return  !empty($item->org_name)? $item->org_name:'';
                            }) 
                            ->addColumn('assign', function ($item) {
                                if( in_array( $item->type , [2] ) ){
                                    $tracking = Tracking::where('id',$item->id)->first();
                                    return !empty($tracking->assigns_to->user_assign->FullName)? $tracking->assigns_to->user_assign->FullName:'';
                                }
                            })
                            ->addColumn('status', function ($item) {
                                if(!empty($item->status_id) && $item->status_id == 3){
                                    $data_input =  'data-id="'.( !empty($item->id) ? $item->id : '').'"';
                                    $data_input .= 'data-refno="'.( !empty($item->reference_refno) ? $item->reference_refno : '').'" ';
                                    return !empty($item->status_title) ?  '<button type="button" class="modal_status btn btn-secondary"  '.( $data_input ).' ">'. (!empty($item->status_title)? $item->status_title:'รอดำเนินการตรวจ'). '</button>':'';
                                }else{
                                    return   !empty($item->status_title)? $item->status_title:'รอดำเนินการตรวจ';
                                }
                            })
                            ->addColumn('certificate_newfile', function ($item) {
                                if(($item->type == 1)){
                                    $data = CertiCBExport::where('id',$item->id)->first();
                                }else{
                                    $tracking = Tracking::where('id',$item->id)->first();
                                    $data = $tracking->certificate_export_to;
                                }
                                if(!empty($data->certificate_newfile)){
                                    $text =   '<a href="'. ( url('funtions/get-view').'/'.$data->certificate_path.'/'.$data->certificate_newfile.'/'.$data->certificate.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                                     <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                              </a> ';
                                }else if(!empty($data->attachs)){
                                      $text =   '<a href="'. ( url('certify/check/file_client').'/'.$data->attachs.'/'. ( !empty($data->attachs_client_name) ? $data->attachs_client_name :  basename($data->attachs)  )).'" target="_blank">
                                                      '. HP::FileExtension($data->attachs).' 
                                              </a> ';
                                }else{
                                    $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$data->ref_id.'/1')).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
                                }
                                return  $text;
                            })
                            ->addColumn('end_date', function ($item) use ($from_filed, $condition_check, $check_first){
                                if(($item->type == 1)){
                                    $data = CertiCBExport::where('id',$item->id)->first();
                                }else{
                                    $tracking = Tracking::where('id',$item->id)->first();
                                    $data = $tracking->certificate_export_to;
                                }

                                switch ( $from_filed ):
                                    case "1": //วันที่ออกใบรับรอง
                                        if(!empty($item->date_start)){
                                        $date_start = $item->date_start;
                                            if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน      
                                                $config_date_start = date('Y-m-d', strtotime('+6 months', strtotime($date_start)));
                                            }else{
                                                $config_date_start =  date('Y-m-d', strtotime('+'.$condition_check.' months', strtotime($date_start)));
                                            }
                                            return !empty($config_date_start) ? HP::DateThai($config_date_start):'-';
                                        }                    
                                        break;
                                    case "3": //วันที่ตรวจล่าสุด
                                        $cb_auditors = CertiCBAuditors::where('app_certi_cb_id',$data->id)->orderby('id','desc')->first();
                                        $end_date = '';
                                        if(!empty($cb_auditors)){
                                            $cb_auditors_date = CertiCBAuditorsDate::where('auditors_id',$cb_auditors->id)->first();
                                            $end_date = $cb_auditors_date->end_date;
                                        }
                                        if(!empty($end_date)){

                                            if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                                $config_end_date =  date('Y-m-d', strtotime('+6 months', strtotime($end_date)));
                                            }else{
                                                $config_end_date =  date('Y-m-d', strtotime('+'.$condition_check.' months', strtotime($end_date)));
                                            }
                                            return !empty($config_end_date) ? HP::DateThai($config_end_date):'-';
                                        }
                                        break;
                                        default:
                                                return '-';
                                        break;
                                endswitch;
                            })
                            ->addColumn('action', function ($item) use($model) {
                                if($item->type == 2){
                                    return HP::buttonAction( $item->id, 'certificate/tracking-cb','Certificate\Cb\\TrackingCbController@destroy', 'trackingcb',false,true,false);
                                }else{
                                    return '';
                                }
                            })
                            ->order(function ($query) {
                                // $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'checkbox','certificate_newfile','assign','status', 'action', 'reference_refno']) 
                            ->make(true);
    }

    public function config_date($date)
    {
    $setting_config  = SettingConfig::where('grop_type','cb')->first();
    $from_filed      = !empty($setting_config->from_filed)?$setting_config->from_filed:null;
    $condition_check = !empty($setting_config->condition_check)?$setting_config->condition_check:null;
    $check_first     = !empty($setting_config->check_first)?$setting_config->check_first:null;

    switch ( $from_filed ):
         case "1": //วันที่ออกใบรับรอง
             if(!empty($date)){
                 if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน      
                     $config_date = date('Y-m-d', strtotime('-6 months', strtotime($date)));
                 }else{
                     $config_date =  date('Y-m-d', strtotime('-'.$condition_check.' months', strtotime($date)));
                 }
                 return  $config_date;
             }                    
             break;
         case "3": //วันที่ตรวจล่าสุด
             if(!empty($date)){

                 if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                     $config_date =  date('Y-m-d', strtotime('-6 months', strtotime($date)));
                 }else{
                     $config_date =  date('Y-m-d', strtotime('-'.$condition_check.' months', strtotime($date)));
                 }
                 return  $config_date;
             }
             break;
             default:
                     return null;
             break;
     endswitch;
    }
    
    public function assign_cb(Request $request)
    {

            $checker = $request->input('checker');
            $ids = $request->input('ids');
            $tb = new CertiCBExport;
            if (count($checker) > 0  && count($ids) > 0) {
    
                // ชื่อเจ้าหน้าที่รับผิดชอบตรวจสอบ
                 $reg_fname = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"))
                                    ->whereIn('runrecno',$checker)
                                    ->whereNotNull('reg_fname')
                                    ->pluck('title')
                                    ->toArray();
                 // ส่ง E-mail
                 $reg_email = User::select('reg_email')
                                    ->whereIn('runrecno',$checker)
                                    ->whereNotNull('reg_email')
                                    ->pluck('reg_email')
                                    ->toArray();

                 $reg_name = User::select('runrecno AS id','reg_email',DB::raw("CONCAT(reg_fname,' ',reg_lname) AS reg_fname") )
                                    ->whereIn('runrecno',$checker)
                                    ->get()
                                    ->toArray();


                          
    
                foreach ($ids as $app_id) {

                       $app = Tracking::find($app_id);
                    if ($app){

                        if(is_null($app->reference_refno)){ 

                            $app->reference_refno = HP::ReferenceRefno(1,$app->id);
                            $app->reference_date  =  date('Y-m-d H:i:s');
                            $app->save();
                            
                            $export_cb       = CertiCBExport::findOrFail($app->ref_id);
                            if(!is_null($export_cb)){
                                $export_cb->reference_refno =  $app->reference_refno ;
                                $export_cb->reference_date  =  $app->reference_date ;
                                $export_cb->save();
                            }
                            $reference_refno  = $app->reference_refno;
                         }else{
                            $reference_refno  = $app->reference_refno;
                         }

                         // เช็คคำขอมอบหมายให้เจ้าหน้าที่หรือยัง
                        if($app->status_id < 2 || is_null($app->status_id)){
                           $app->status_id = 2;
                           $app->save();
                        }
                           $app->name =  !empty($app->certificate_export_to->CertiCbTo->name)? $app->certificate_export_to->CertiCbTo->name:'';
                    
                        $this->save_check_history1(1,$reference_refno,$tb->getTable(),$app,2,(count($reg_name) > 0) ? json_encode($reg_name) : null);
                       $examiner = $this->save_check_examiner(1,$reference_refno,$checker,$app);
                       if(count($reg_email) > 0){

                             $data_app = [   'apps'      =>  $app ?? null,
                                            'email'     => auth()->user()->reg_email ?? 'admin@admin.com',
                                            'title'     => 'ขอให้ตรวจสอบคำขอรับบริการยืนยันความสามารถหน่วยรับรอง',
                                            'reg_fname' => (count($reg_fname) > 0) ? implode(", ",$reg_fname) : null
                                         ];
                            
                                    $log_email =  HP::getInsertCertifyLogEmail($reference_refno,
                                                                                $app->id,
                                                                                (new Tracking)->getTable(),
                                                                                $examiner->id ?? null,
                                                                                (new TrackingAssigns)->getTable(),
                                                                                6,
                                                                                'ขอให้ตรวจสอบคำขอรับบริการยืนยันความสามารถหน่วยรับรอง',
                                                                                view('mail.Tracking.assign_staff', $data_app),
                                                                                !empty($app->certificate_export_to->CertiCbTo->created_by)? $app->certificate_export_to->CertiCbTo->created_by:null,   
                                                                                !empty($app->certificate_export_to->CertiCbTo->agent_id)? $app->certificate_export_to->CertiCbTo->agent_id:null, 
                                                                                auth()->user()->getKey(),
                                                                                auth()->user()->reg_email ?? 'admin@admin.com',
                                                                                implode(",",$reg_email));

                                    $html = new AssignStaffMail($data_app);
                                    $mail =  Mail::to($reg_email)->send($html); 
                              
                                    if(is_null($mail) && !empty($log_email)){
                                        HP::getUpdateCertifyLogEmail($log_email->id);
                                    }    

                          
                        }

                      
                    }
                  }
              }
 
        return $request;
    }

    private function save_check_examiner($cer= 1 ,$reference_refno,$checker, $tracking){
        $tb = new CertiCBExport;
        $examiner = null;
        TrackingAssigns::where('tracking_id', $tracking->id)->delete();
        foreach($checker as $key => $item) {
          $input = [];
          $input['certificate_type']        = $cer;
          $input['reference_refno']         = $reference_refno;
          $input['ref_table']               = $tb->getTable();
          $input['ref_id']                  = $tracking->ref_id ?? null;
          $input['tracking_id']             = $tracking->id ?? null;
          $input['user_id']                 = $item;
          $input['created_by'] = auth()->user()->runrecno;
        $examiner =  TrackingAssigns::create($input);
        }
        return $examiner;
      } 

      private function save_check_history1($cer= 1 ,$reference_refno,$getTable, $tracking, $system, $details_one){
        $input = [];
        $input['certificate_type']        = $cer;
        $input['reference_refno']         = $reference_refno;
        $input['ref_table']               = $getTable;
        $input['ref_id']                  = $tracking->ref_id ?? null;
        $input['tracking_id']             = $tracking->id ?? null;
        $input['system']                  = $system;
        $input['details_one']             = $details_one;
        $input['created_by']              = auth()->user()->runrecno;
        TrackingHistory::create($input);
    } 

    public function show($id)
    {
        $model = str_slug('trackingcb','-');
        if(auth()->user()->can('view-'.$model)) {
            $certi_cb = CertiCb::findOrFail($id);

            $history  =  CertiCbHistory::where('app_certi_cb_id',$certi_cb->id)
                                        ->orderby('id','desc')
                                        ->get();
             $attach_path =  'files/applicants/check_files_cb/';//path ไฟล์แนบ
            return view('certificate.cb.tracking-cb.show', compact('certi_cb','history','attach_path'));
        }
        abort(403);
    }


    

    public function delete_file($id)
    {
        $attach = AttachFile::findOrFail($id);
         if (!is_null($attach)) {
            $attach->delete();
            $file = 'true';
       }else{
            $file = 'false';
        }
          return  $file;
    }

 
  public function edit($id)
  {
      $model = str_slug('trackingcb','-');
      if(auth()->user()->can('edit-'.$model)) {
          $tracking = Tracking::findOrFail($id);
          $cer      = $tracking->certificate_export_to;
        
          return view('certificate.cb.tracking-cb.edit', compact('tracking','cer'));
      }
      abort(403);
  }


  public function Pay_In1($id)
  {
      $pay_in = TrackingPayInOne::findOrFail($id);

      if(is_null($pay_in->conditional_type) && !empty($pay_in->auditors_to->StartDateBoardAuditor)){
          $start_date      = $pay_in->auditors_to->StartDateBoardAuditor->start_date ?? null;
          $feewaiver  =  Feewaiver::where('certify',3)
                                ->where('payin1_status',1)
                                ->whereDate('payin1_start_date','<=',$start_date)
                                ->whereDate('payin1_end_date','>=',$start_date)
                                ->first();
        if(!is_null($feewaiver)){
            $pay_in->conditional = 2; // เรียกเก็บค่าธรรมเนียม
        }else{
            $pay_in->conditional = 1; // ยกเว้นค่าธรรมเนียม
        }
    }else{
        $feewaiver = null;
    }

      return view('certificate.cb.tracking-cb.pay_in_one', compact('pay_in','feewaiver'));  
  }


  public function update_payin1(Request $request, $id){

       $arrContextOptions=array();
       $attach_path =  $this->attach_path ;
       $tb = new TrackingPayInOne;
       $config = HP::getConfig();
       $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
       $PayIn = TrackingPayInOne::findOrFail($id);
     

  // try {
  
    if($PayIn->state == null){

                  $PayIn->conditional_type    = $request->conditional_type;
                  $PayIn->created_by          =  auth()->user()->runrecno;
                  $PayIn->state = 1;  // ส่งให้ ผปก.
                  $PayIn->start_date =   isset($request->start_date)?  HP::convertDate($request->start_date,true) : @$PayIn->start_date;
                  $PayIn->amount_bill =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):@$PayIn->amount_bill;
                  $PayIn->save();
                  $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

          if($PayIn->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม  

            $setting_payment = CertiSettingPayment::where('certify',6)->where('payin',1)->where('type',1)->first();

            
               
            if(!is_null($setting_payment) ){
                        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                            $arrContextOptions["ssl"] = array(
                                                            "verify_peer" => false,
                                                            "verify_peer_name" => false,
                                                        );
                        }
                    $timestamp = Carbon::now()->timestamp;
                    $refNo = $PayIn->reference_refno.'-'.$PayIn->auditors_id.''.$timestamp;

                    // $url     =  "$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$PayIn->reference_refno-$PayIn->auditors_id";
                    $url     =  "$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo";

                    // $content = EpaymentDemo::pmt1($refNo, $setting_payment->pid, 'json');

                    $content =  file_get_contents($url, false, stream_context_create($arrContextOptions));
                    $api = json_decode($content);

                    // $file_payin  = self::storeFilePayin($setting_payment,$PayIn->reference_refno,$PayIn->auditors_id,$tb->getTable(),$PayIn->id,'attach_payin1','เรียกเก็บค่าธรรมเนียม');

                    // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                    $host = parse_url($setting_payment->data, PHP_URL_HOST);

                    if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {   
                        $file_payin  = $this->storeFilePayin($setting_payment,$PayIn->reference_refno,$PayIn->auditors_id,$tb->getTable(),$PayIn->id,'attach_payin1','เรียกเก็บค่าธรรมเนียม');
                    }else{
                        $file_payin  = $this->storeFilePayinDemo($setting_payment,$PayIn->reference_refno,$PayIn->auditors_id,$tb->getTable(),$PayIn->id,'attach_payin1','เรียกเก็บค่าธรรมเนียม');
                        
                    }

                    if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                        HP::getFileStoragePath($file_payin->url);
                    }
            
                    // $transaction = HP::TransactionPayIn1($PayIn->id,$tb->getTable(),'6','1',$api,$PayIn->reference_refno.'-'.$PayIn->auditors_id);
                    $transaction = HP::TransactionPayIn1($PayIn->id,$tb->getTable(),'6','1',$api,$PayIn->reference_refno.'-'.$PayIn->auditors_id,$timestamp);

                
                }
            }else  if($PayIn->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

                    $feewaiver  =  Feewaiver::where('certify',3)->first();
                    if(!empty($feewaiver->payin1_file)){
                        $file_types      =   explode('.',  basename($feewaiver->payin1_file)) ;
                        $file_extension  =  end($file_types);
                        $file_size       =   Storage::disk('ftp')->size($feewaiver->payin1_file);
                        $request =  AttachFile::create([
                                                        'tax_number'        => $tax_number,
                                                        'username'          =>     (auth()->user()->FullName ?? null),
                                                        'systems'           => 'Center',
                                                        'ref_table'         => $tb->getTable(),
                                                        'ref_id'            =>  $PayIn->id,
                                                        'url'               => $feewaiver->payin1_file,
                                                        'filename'          => $feewaiver->payin1_file_client_name,
                                                        'new_filename'      => basename($feewaiver->payin1_file),
                                                        'caption'           => 'ยกเว้นค่าธรรมเนียม',
                                                        'size'              => $file_size ?? '0',
                                                        'file_properties'   => $file_extension,
                                                        'section'           => 'attach_payin1',
                                                        'created_by'        => auth()->user()->getKey(),
                                                        'created_at'        => date('Y-m-d H:i:s')
                        ]);

                        if(!is_null($feewaiver) && HP::checkFileStorage($feewaiver->payin1_file)){
                            HP::getFileStoragePath($feewaiver->payin1_file);
                        }
                    }

                    $PayIn->start_date_feewaiver        =  $feewaiver->payin2_start_date ?? null;
                    $PayIn->end_date_feewaiver          =  $feewaiver->payin2_end_date ?? null;
                    $PayIn->save();
    
            }else  if($PayIn->conditional_type == 3){  // ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม
                    $PayIn->detail = $request->detail ?? null;
                    $PayIn->save();
                if($request->attach && $request->hasFile('attach')){
                    $file_payin  =   HP::singleFileUploadRefno(
                                                                $request->file('attach') ,
                                                                $this->attach_path.'/'.$PayIn->reference_refno,
                                                                ( $tax_number),
                                                                (auth()->user()->FullName ?? null),
                                                                'Center',
                                                                ( $tb->getTable() ),
                                                                $PayIn->id,
                                                                'attach_payin1',
                                                                'ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม'
                                                            );

                        if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                            HP::getFileStoragePath($file_payin->url);
                        }
                }
            }
    
              // สถานะ แต่งตั้งคณะกรรมการ
              $auditor = TrackingAuditors::findOrFail($PayIn->auditors_id);
              if(!is_null($auditor)){
                  $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                  $auditor->save();
              }

              // LOG
              $data = TrackingPayInOne::select('id','conditional_type', 'auditors_id', 'amount_bill', 'start_date', 'status','state', 'remark', 'detail', 'start_date_feewaiver', 'end_date_feewaiver')
                                      ->where('id',$id)
                                      ->first();

             $file = [];
              if( !empty($data->FileAttachPayInOne1To->url)){
                  $file['url'] =  $data->FileAttachPayInOne1To->url;
              }
              if( !empty($data->FileAttachPayInOne1To->new_filename)){
                  $file['new_filename'] =  $data->FileAttachPayInOne1To->new_filename;
              }
              if( !empty($data->FileAttachPayInOne1To->filename)){
                  $file['filename'] =  $data->FileAttachPayInOne1To->filename;
              }                          
              TrackingHistory::create([ 
                                    'tracking_id'   => $PayIn->tracking_id ?? null,
                                    'certificate_type'  => 1,
                                    'reference_refno'   => $PayIn->reference_refno ?? null,
                                    'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                    'ref_id'            =>  $PayIn->ref_id ?? null,
                                    'auditors_id'       =>  $PayIn->auditors_id ?? null,
                                    'system'            => 5,
                                    'table_name'        => $tb->getTable(),
                                    'refid'             => $id,
                                    'details_one'       =>  json_encode($data) ?? null,
                                    'file'              =>  (count($file) > 0) ? json_encode($file) : null,
                                    'created_by'            =>  auth()->user()->runrecno
                                    ]);
              // // Mail
              if(!empty($PayIn->certificate_export_to->CertiCbTo->email)){ // แจ้งเตือนผู้ประกอบการ

                     $certi  =  $PayIn->certificate_export_to->CertiCbTo;

                    if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                            $data_app = [
                                              'data'             => $certi,
                                              'pay_in'            => $PayIn,
                                              'attachs'          => !empty($PayIn->FileAttachPayInOne1To->url) && is_file(!empty($PayIn->FileAttachPayInOne1To->url)) ? $PayIn->FileAttachPayInOne1To->url : '',
                                              'url'              => $url.'certify/tracking-cb',
                                              'email'            => 'nsc@tisi.mail.go.th',
                                              'email_cc'         => !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC :  [],
                                              'email_reply'      => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : []
                                        ];
                    
                            $log_email =  HP::getInsertCertifyLogEmail(!empty($PayIn->tracking_to->reference_refno)? $PayIn->tracking_to->reference_refno:null,   
                                                                        $PayIn->tracking_id,
                                                                        (new Tracking)->getTable(),
                                                                        $PayIn->id ?? null,
                                                                        (new TrackingPayInOne)->getTable(),
                                                                        6,
                                                                        'แจ้งค่าบริการในการตรวจประเมิน',
                                                                        view('mail.Tracking.pay_in_one', $data_app),
                                                                        !empty($certi->created_by)? $certi->created_by:null,   
                                                                        !empty($certi->agent_id)? $certi->agent_id:null, 
                                                                        auth()->user()->getKey(),
                                                                        'nsc@tisi.mail.go.th',
                                                                        $certi->email,
                                                                        !empty($certi->DataEmailDirectorCBCC) ?  implode(",",$certi->DataEmailDirectorCBCC) : null,
                                                                        !empty($certi->DataEmailDirectorCBReply) ? implode(",",$certi->DataEmailDirectorCBReply):  null
                                                                    );

                            $html = new PayInOneMail($data_app);
                            $mail =  Mail::to($certi->email)->send($html); 
                        
                            if(is_null($mail) && !empty($log_email)){
                                HP::getUpdateCertifyLogEmail($log_email->id);
                            }    
                    }

               
              }                
      }else{
          if($request->status == 1){
              $PayIn->remark =  null;
              $PayIn->state = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
              $PayIn->status = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว

              $assessment  =  TrackingAssessment::where('auditors_id',$PayIn->auditors_id)->first();
              if(is_null($assessment)){
                  $assessment = new TrackingAssessment;
              }
              $assessment->certificate_type   = 1;
              $assessment->tracking_id        = $PayIn->tracking_id ?? null;
              $assessment->reference_refno    = $PayIn->reference_refno ?? null;
              $assessment->ref_table          = $PayIn->ref_table ?? null;
              $assessment->ref_id             = $PayIn->ref_id ?? null;
              $assessment->auditors_id        = $PayIn->auditors_id ?? null;
              $assessment->name               =  !empty($PayIn->certificate_export_to->cb_name) ? $PayIn->certificate_export_to->cb_name : null;
              $assessment->laboratory_name    =  !empty($PayIn->certificate_export_to->name_standard) ?  $PayIn->certificate_export_to->name_standard  : null;
              $assessment->save();

           }else{
              $PayIn->state = 1;  // ส่งให้ ผปก.
              $PayIn->remark = $request->remark ?? null;
              $PayIn->status = 0;
           }
               $PayIn->save();

              // สถานะ แต่งตั้งคณะกรรมการ
              $auditor = TrackingAuditors::findOrFail($PayIn->auditors_id);
           if(!is_null($auditor)){
              if($PayIn->state == 3){
                  $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
              }else{
                  $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
              }
                 $auditor->save();
           }

           $data = TrackingPayInOne::select('id','conditional_type', 'auditors_id', 'amount_bill', 'start_date', 'status','state', 'remark', 'detail', 'start_date_feewaiver', 'end_date_feewaiver')
                                      ->where('id',$PayIn->id)
                                      ->first();

             $file = [];
              if( !empty($PayIn->FileAttachPayInOne1To->url)){
                  $file['url'] =  $PayIn->FileAttachPayInOne1To->url;
              }
              if( !empty($PayIn->FileAttachPayInOne1To->new_filename)){
                  $file['new_filename'] =  $PayIn->FileAttachPayInOne1To->new_filename;
              }
              if( !empty($PayIn->FileAttachPayInOne1To->filename)){
                  $file['filename'] =  $PayIn->FileAttachPayInOne1To->filename;
              }  

              $attachs_file = [];
              if( !empty($PayIn->FileAttachPayInOne2To->url)){
                  $attachs_file['url'] =  $PayIn->FileAttachPayInOne2To->url;
              }
              if( !empty($PayIn->FileAttachPayInOne2To->new_filename)){
                  $attachs_file['new_filename'] =  $PayIn->FileAttachPayInOne2To->new_filename;
              }
              if( !empty($PayIn->FileAttachPayInOne2To->filename)){
                  $attachs_file['filename'] =  $PayIn->FileAttachPayInOne2To->filename;
              }  
              TrackingHistory::create([ 
                                      'certificate_type'  => 1,
                                      'tracking_id'       => $PayIn->tracking_id ?? null,
                                       'reference_refno'  => $PayIn->reference_refno ?? null,
                                      'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                      'ref_id'            =>  $PayIn->ref_id ?? null,
                                      'auditors_id'       =>  $PayIn->auditors_id ?? null,
                                      'system'            => 5, //Pay-In ครั้งที่ 1
                                      'table_name'        => $tb->getTable(),
                                      'refid'             => $id,
                                      'status'            => $PayIn->status ?? null,
                                      'details_one'       =>  json_encode($data) ?? null,
                                      'attachs'           => (count($file) > 0) ? json_encode($file) : null,
                                      'attachs_file'      =>  (count($attachs_file) > 0) ? json_encode($attachs_file) : null,
                                      'created_by'        =>  auth()->user()->runrecno
                                  ]);

         
    if(!empty($PayIn->certificate_export_to->CertiCbTo->email)){
               $certi  =  $PayIn->certificate_export_to->CertiCbTo;
          if($PayIn->state == 1){  // แจ้งเตือนผู้ประกอบการ  
            
                      if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                            $data_app = [
                                              'data'             => $certi,
                                              'pay_in'            => $PayIn,
                                              'attachs'          => !empty($PayIn->FileAttachPayInOne1To->url) && is_file(!empty($PayIn->FileAttachPayInOne1To->url)) ? $PayIn->FileAttachPayInOne1To->url : '',
                                              'url'              => $url.'certify/tracking-cb',
                                              'email'            => 'nsc@tisi.mail.go.th',
                                              'email_cc'         => !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC :  [],
                                              'email_reply'      => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : []
                                        ];
                    
                            $log_email =  HP::getInsertCertifyLogEmail(!empty($PayIn->tracking_to->reference_refno)? $PayIn->tracking_to->reference_refno:null,   
                                                                        $PayIn->tracking_id,
                                                                        (new Tracking)->getTable(),
                                                                        $PayIn->id ?? null,
                                                                        (new TrackingPayInOne)->getTable(),
                                                                        6,
                                                                        'แจ้งค่าบริการในการตรวจประเมิน',
                                                                        view('mail.Tracking.pay_in_one', $data_app),
                                                                        !empty($certi->created_by)? $certi->created_by:null,   
                                                                        !empty($certi->agent_id)? $certi->agent_id:null, 
                                                                        auth()->user()->getKey(),
                                                                        'nsc@tisi.mail.go.th',
                                                                        $certi->email,
                                                                        !empty($certi->DataEmailDirectorCBCC) ?  implode(",",$certi->DataEmailDirectorCBCC) : null,
                                                                        !empty($certi->DataEmailDirectorCBReply) ? implode(",",$certi->DataEmailDirectorCBReply):  null
                                                                    );

                            $html = new PayInOneMail($data_app);
                            $mail =  Mail::to($certi->email)->send($html); 
                       
                            if(is_null($mail) && !empty($log_email)){
                                HP::getUpdateCertifyLogEmail($log_email->id);
                            }    
                    }

 
          }else{

            $tracking = Tracking::findOrFail($PayIn->tracking_id);
            if(!empty($tracking) && !empty($tracking->certificate_export_to->CertiCbTo)){

                $certi  =  $tracking->certificate_export_to->CertiCbTo;

               if( count($tracking->AssignEmails) > 0){
                            $data_app = [
                                            'PayIn'         => $PayIn,
                                            'data'          => $certi,
                                            'assign'        =>  !empty($tracking->AssignName) ?implode(", ",$tracking->AssignName)   : '',  
                                            'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                            'email_cc'      =>  !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC : [],
                                            'email_reply'   => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : []
                            ];
                    
                            $log_email =  HP::getInsertCertifyLogEmail(!empty($PayIn->tracking_to->reference_refno)? $PayIn->tracking_to->reference_refno:null,   
                                                                        $PayIn->tracking_id,
                                                                        (new Tracking)->getTable(),
                                                                        $PayIn->id ?? null,
                                                                        (new TrackingPayInOne)->getTable(),
                                                                        6,
                                                                        'แจ้งตรวจสอบการชำระค่าบริการในการตรวจประเมิน',
                                                                        view('mail.Tracking.inform_pay_in_one', $data_app),
                                                                        !empty($certi->created_by)? $certi->created_by:null,   
                                                                        !empty($certi->agent_id)? $certi->agent_id:null, 
                                                                        auth()->user()->getKey(),
                                                                        !empty($certi->DataEmailCertifyCenter) ?  implode(",",$certi->DataEmailCertifyCenter) : 'cb@tisi.mail.go.th',
                                                                        implode(",",$tracking->AssignEmails),
                                                                        !empty($certi->DataEmailDirectorCBCC) ?  implode(",",$certi->DataEmailDirectorCBCC) : null,
                                                                        !empty($certi->DataEmailDirectorCBReply) ? implode(",",$certi->DataEmailDirectorCBReply):  null
                                                                    );

                            $html = new InformPayInOne($data_app);
                             $mail =  Mail::to($tracking->AssignEmails)->send($html);  
                      
                            if(is_null($mail) && !empty($log_email)){
                                HP::getUpdateCertifyLogEmail($log_email->id);
                            }    
                 }

             }


          }
        }

       }

        if($request->previousUrl){
          return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/tracking-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
        }

  
  // } catch (\Exception $e) {
  //     return redirect('certificate/tracking-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  // }    

 }
              // สำหรับเพิ่มรูปไปที่ store
  public function storeFilePayinDemo($setting_payment, $app_no = '', $auditor_id = '', $table_name = '', $ref_id = '', $section = '',$attach_text  = '')
  {
           
            //   $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            //   $arrContextOptions=array();
            //   if($auditor_id != ''){
            //       $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no-$auditor_id";
            //       $filename =  'เรียกเก็บค่าธรรมเนียม_'.$app_no.'_'.date('Ymd_hms').'.pdf';
            //   }else{
            //       $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no";
            //       $filename =  'เรียกเก็บค่าธรรมเนียม_'.$app_no.'_'.date('Ymd_hms').'.pdf';
            //   }
            //   if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
            //       $arrContextOptions["ssl"] = array(
            //                                       "verify_peer" => false,
            //                                       "verify_peer_name" => false,
            //                                   );
            //   }

            //   $url_pdf =  file_get_contents($url, false, stream_context_create($arrContextOptions));
            //   if ($url_pdf) {
            //       $attach_path     =  $this->attach_path.'/'.$app_no;
            //       $fullFileName    =  date('Ymd_hms').'.pdf';
            //        $path           =  $attach_path.'/'.$fullFileName;
            //       $storagePath     = Storage::put($path, $url_pdf);
            //       $file_size       = Storage::size($path);
            //       $file_types      =   explode('.',  basename($fullFileName)) ;
            //       $file_extension  =  end($file_types);
            //     $request =  AttachFile::create([
            //                        'tax_number'        => $tax_number,
            //                        'username'          =>     (auth()->user()->FullName ?? null),
            //                        'systems'           => 'Center',
            //                        'ref_table'         => $table_name,
            //                        'ref_id'            => $ref_id,
            //                        'url'               => $path,
            //                        'filename'          => $filename,
            //                        'new_filename'      => $fullFileName,
            //                        'caption'           => $attach_text,
            //                        'size'              => $file_size,
            //                        'file_properties'   => $file_extension,
            //                        'section'           => $section,
            //                        'created_by'        => auth()->user()->getKey(),
            //                        'created_at'        => date('Y-m-d H:i:s')
            //                    ]);
            //       return $request;
                  
                  
            //   }else{
            //       return null;
            //   }

              $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
               $filename =  'เรียกเก็บค่าธรรมเนียม_'.$app_no.'_'.date('Ymd_hms').'.pdf';
           
               $baseUrl = strstr($setting_payment->data, '/api', true);
        
               $url = $baseUrl. '/images/PayIn2.pdf';
        
               // ดาวน์โหลดเนื้อหา PDF (Demo)
            //    $url_pdf = file_get_contents($url);

                    $contextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        if (strpos($setting_payment->data, 'https') === 0) {
            $context = stream_context_create($contextOptions);
            $url_pdf = file_get_contents($url, false, $context);
        } else {
            $url_pdf = file_get_contents($url);
        }
        
              if ($url_pdf) {
                    $attach_path     =  $this->attach_path.'/'.$app_no;
                    $fullFileName    =  date('Ymd_hms').'.pdf';
                    $path           =  $attach_path.'/'.$fullFileName;
                    $storagePath     = Storage::put($path, $url_pdf);
                    $file_size       = Storage::size($path);
                    $file_types      =   explode('.',  basename($fullFileName)) ;
                    $file_extension  =  end($file_types);
                    $request =  AttachFile::create([
                                   'tax_number'        => $tax_number,
                                   'username'          =>     (auth()->user()->FullName ?? null),
                                   'systems'           => 'Center',
                                   'ref_table'         => $table_name,
                                   'ref_id'            => $ref_id,
                                   'url'               => $path,
                                   'filename'          => $filename,
                                   'new_filename'      => $fullFileName,
                                   'caption'           => $attach_text,
                                   'size'              => $file_size,
                                   'file_properties'   => $file_extension,
                                   'section'           => $section,
                                   'created_by'        => auth()->user()->getKey(),
                                   'created_at'        => date('Y-m-d H:i:s')
                               ]);
                    return $request;
            }else{
                return null;
            }
             
   }

         // สำหรับเพิ่มรูปไปที่ store
public function storeFilePayin($setting_payment, $app_no = '', $auditor_id = '', $table_name = '', $ref_id = '', $section = '',$attach_text  = '')
 {
          
             $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
             $arrContextOptions=array();
             if($auditor_id != ''){
                 $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no-$auditor_id";
                 $filename =  'เรียกเก็บค่าธรรมเนียม_'.$app_no.'_'.date('Ymd_hms').'.pdf';
             }else{
                 $url =  "$setting_payment->data?pid=$setting_payment->pid&out=pdf&Ref1=$app_no";
                 $filename =  'เรียกเก็บค่าธรรมเนียม_'.$app_no.'_'.date('Ymd_hms').'.pdf';
             }
             if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                 $arrContextOptions["ssl"] = array(
                                                 "verify_peer" => false,
                                                 "verify_peer_name" => false,
                                             );
             }

             $url_pdf =  file_get_contents($url, false, stream_context_create($arrContextOptions));
             if ($url_pdf) {
                 $attach_path     =  $this->attach_path.'/'.$app_no;
                 $fullFileName    =  date('Ymd_hms').'.pdf';
                  $path           =  $attach_path.'/'.$fullFileName;
                 $storagePath     = Storage::put($path, $url_pdf);
                 $file_size       = Storage::size($path);
                 $file_types      =   explode('.',  basename($fullFileName)) ;
                 $file_extension  =  end($file_types);
               $request =  AttachFile::create([
                                  'tax_number'        => $tax_number,
                                  'username'          =>     (auth()->user()->FullName ?? null),
                                  'systems'           => 'Center',
                                  'ref_table'         => $table_name,
                                  'ref_id'            => $ref_id,
                                  'url'               => $path,
                                  'filename'          => $filename,
                                  'new_filename'      => $fullFileName,
                                  'caption'           => $attach_text,
                                  'size'              => $file_size,
                                  'file_properties'   => $file_extension,
                                  'section'           => $section,
                                  'created_by'        => auth()->user()->getKey(),
                                  'created_at'        => date('Y-m-d H:i:s')
                              ]);
                 return $request;
                 
                 
             }else{
                 return null;
             }
  }



  public function inspection($id)
  {
      $inspection = TrackingInspection::findOrFail($id);

    if(!is_null($inspection)){
           $tracking = $inspection->tracking_to;
          if(is_null($tracking)){
              $tracking = new Tracking;
          }
      }else{
          $tracking = new Tracking;
      }

        //  if($inspection->FileAttachScopeTo == null)
        // {
           

        //     $appId = $inspection->reference_refno;
        

        //     $certiCb = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;
    
        //     $certiCbFileAll = CertiCBAttachAll::where('app_certi_cb_id',$certiCb->id)
        //         ->where('table_name','app_certi_cb')
        //         ->where('file_section',3)
        //         ->latest() // เรียงจาก created_at จากมากไปน้อย
        //         ->first();
    
        //     $filePath = 'files/applicants/check_files_cb/' . $certiCbFileAll->file ;
    
        //     $localFilePath = HP::downloadFileFromTisiCloud($filePath);

        //     // dd($certiIb ,$certiIbFileAll,$filePath,$localFilePath);

        //     $check = AttachFile::where('systems','Center')
        //             ->where('ref_id',$inspection->id)
        //             ->where('ref_table',(new TrackingInspection)->getTable())
        //             ->where('section','file_scope')
        //             ->first();
        //     if($check != null)
        //     {
        //         $check->delete();
        //     }

        //     $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
    
        //     $uploadedFile = new \Illuminate\Http\UploadedFile(
        //         $localFilePath,      // Path ของไฟล์
        //         basename($localFilePath), // ชื่อไฟล์
        //         mime_content_type($localFilePath), // MIME type
        //         null,               // ขนาดไฟล์ (null ถ้าไม่ทราบ)
        //         true                // เป็นไฟล์ที่ valid แล้ว
        //     );
    
            
        //     $attach_path = "files/trackingcb";
        //     // dd($attach_path.'/'.$inspection->reference_refno);
        //     // ใช้ไฟล์ที่จำลองในการอัปโหลด
        //     HP::singleFileUploadRefno(
        //         $uploadedFile,
        //         $attach_path.'/'.$inspection->reference_refno,
        //         ( $tax_number),
        //         (auth()->user()->FullName ?? null),
        //         'Center',
        //         (  (new TrackingInspection)->getTable() ),
        //         $inspection->id,
        //         'file_scope',
        //         null
        //     );

        // }


 
      return view('certificate.cb.tracking-cb.inspection', compact('inspection','tracking'));  
  }


  public function update_inspection(Request $request ,$id)
  {
 
    // try { 
       $inspection                    = TrackingInspection::findOrFail($id);
       $inspection->status            = null;
       $inspection->created_date      = null;
       $inspection->created_by        = auth()->user()->getKey();
       $inspection->save();

       $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
       if ($request->file_scope && $request->hasFile('file_scope')){
                  HP::singleFileUploadRefno(
                      $request->file('file_scope') ,
                      $this->attach_path.'/'.$inspection->reference_refno,
                      ( $tax_number),
                       (auth()->user()->FullName ?? null),
                      'Center',
                      (  (new TrackingInspection)->getTable() ),
                       $inspection->id,
                      'file_scope',
                      null
                  );
         }
         if ($request->file_report && $request->hasFile('file_report')){
              HP::singleFileUploadRefno(
                  $request->file('file_report') ,
                  $this->attach_path.'/'.$inspection->reference_refno,
                  ( $tax_number),
                  (auth()->user()->FullName ?? null),
                  'Center',
                  (  (new TrackingInspection)->getTable() ),
                  $inspection->id,
                  'file_report',
                  null
              );
          }

          $inspection         = TrackingInspection::findOrFail($id);
          $file_scope = [];
          if( !empty($inspection->FileAttachScopeTo->url)){
              $file_scope['url'] =  $inspection->FileAttachScopeTo->url;
          }
          if( !empty($inspection->FileAttachScopeTo->new_filename)){
              $file_scope['new_filename'] =  $inspection->FileAttachScopeTo->new_filename;
          }
          if( !empty($inspection->FileAttachScopeTo->filename)){
              $file_scope['filename'] =  $inspection->FileAttachScopeTo->filename;
          }  

          $file_report = [];
          if( !empty($inspection->FileAttachReportTo->url)){
              $file_report['url'] =  $inspection->FileAttachReportTo->url;
          }
          if( !empty($inspection->FileAttachReportTo->new_filename)){
              $file_report['new_filename'] =  $inspection->FileAttachReportTo->new_filename;
          }
          if( !empty($inspection->FileAttachReportTo->filename)){
              $file_report['filename'] =  $inspection->FileAttachReportTo->filename;
          }  


           TrackingHistory::create([ 
                                      'tracking_id'       => $inspection->tracking_id ?? null,
                                      'certificate_type'  => 1,
                                       'reference_refno'   => $inspection->reference_refno ?? null,
                                      'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                      'ref_id'            =>  $inspection->ref_id ?? null,
                                      'system'            => 8, 
                                      'table_name'        => (new TrackingInspection)->getTable() ,
                                      'refid'             => $id,
                                      'status'            => $inspection->status ?? null,
                                      'details_one'       => $inspection->created_date ?? null,
                                      'attachs'           => (count($file_scope) > 0) ? json_encode($file_scope) : null,
                                      'file'              =>  (count($file_report) > 0) ? json_encode($file_report) : null,
                                      'created_by'        =>  auth()->user()->runrecno
                                  ]);

          $tracking = Tracking::find($inspection->tracking_id);
          if (!is_null($tracking)){ 
              $tracking->status_id = 5; 
              $tracking->save();
          }                   
    
          $config = HP::getConfig();
          $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
          if( !empty($inspection->certificate_export_to->CertiCbTo)){

              $certi = $inspection->certificate_export_to->CertiCbTo;

              if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                $data_app =   [
                                'certi'          =>  $certi,
                                'data'           =>  $inspection ,
                                'export'         =>  $inspection->certificate_export_to ?? '' ,
                                'url'            =>  $url.'certify/tracking-cb',
                                'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'       =>  !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC : [],
                                'email_reply'    =>  !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply :  []
                             ] ;
                      
                  $log_email =  HP::getInsertCertifyLogEmail(!empty($inspection->tracking_to->reference_refno)? $inspection->tracking_to->reference_refno:null,   
                                                              $inspection->tracking_id,
                                                              (new Tracking)->getTable(),
                                                              $inspection->id ?? null,
                                                              (new TrackingInspection)->getTable(),
                                                              6,
                                                              'สรุปผลการตรวจประเมิน',
                                                              view('mail.Tracking.inspectiont', $data_app),
                                                              !empty($certi->created_by)? $certi->created_by:null,   
                                                              !empty($certi->agent_id)? $certi->agent_id:null, 
                                                              auth()->user()->getKey(),
                                                              !empty($certi->DataEmailCertifyCenter) ? implode(",",$certi->DataEmailCertifyCenter) : 'cb@tisi.mail.go.th',
                                                              $certi->email,
                                                              !empty($certi->DataEmailDirectorCBCC) ?  implode(",",$certi->DataEmailDirectorCBCC) : null,
                                                              !empty($certi->DataEmailDirectorCBReply) ? implode(",",$certi->DataEmailDirectorCBReply):  null
                                                          );
  
                      $html = new InspectiontMail($data_app);
                      $mail =  Mail::to($certi->email)->send($html);  
                                                  
                      if(is_null($mail) && !empty($log_email)){
                          HP::getUpdateCertifyLogEmail($log_email->id);
                      }    
            } 
 
       }

       if($request->previousUrl){
          return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/tracking-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
        }

  
  // } catch (\Exception $e) {
  //     return redirect('certificate/tracking-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  // }      
  }

  
    public function requestEditScopeFromTracking(Request $request)
    {
         
        $inspection_id = $request->inspection_id;
        $inspection  = TrackingInspection::findOrFail($inspection_id);
// dd($inspection);
       

        $appId = $inspection->reference_refno;
        $message =  $request->message;
        $trackingAssessment = TrackingAssessment::where('reference_refno',$appId)->first();
        if(TrackingAssessment::where('reference_refno',$appId)->first() != null)
        {
            
            if($trackingAssessment != null)
            {
                $certiCb = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;
                //  dd($certiIb);
                CertiCb::find($certiCb->id)->update([
                    'require_scope_update' => "1"
                ]);

                $app = CertiCb::find($certiCb->id);
                $config   = HP::getConfig();
                $url      = !empty($config->url_acc) ? $config->url_acc : url('');
       
                if(!is_null($app->email)){
                    $data_app =   [
                                'certi'          => $certiCb,
                                'request_message'           => $message ,
                                'url'            => $url.'certify/tracking-cb',
                                'email'          =>  !empty($certiCb->DataEmailCertifyCenter) ? $certiCb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'       =>  !empty($certiCb->DataEmailDirectorCBCC) ? $certiCb->DataEmailDirectorCBCC : [],
                                'email_reply'    =>  !empty($certiCb->DataEmailDirectorCBReply) ? $certiCb->DataEmailDirectorCBReply :  []
                            ] ;           
        
                    $html = new  RequestEditCbIbScopeFromTracking($data_app);
                    $mail = Mail::to($app->email)->send($html);
                }    
            }
        }

        // return redirect()->to('/certificate/tracking-cb/' . $trackingAssessment->tracking_id . '/edit');

    }

  public function update_report(Request $request ,$id)
  {
    // try { 
       $report                    = TrackingReport::findOrFail($id);
       $report->report_date       =  !empty($request->report_date) ?  HP::convertDate($request->report_date,true) : null;
       $report->report_status     =   !empty($request->report_status) ? $request->report_status: null;
       $report->details           =  !empty($request->details) ? $request->details: null;
       $report->start_date        =  !empty($request->start_date) ?  HP::convertDate($request->start_date,true) : null;
       $report->end_date          =  !empty($request->end_date) ?  HP::convertDate($request->end_date,true) : null;
       $report->created_by        =  auth()->user()->getKey();
       $report->save();

       $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
       if ($request->file_loa && $request->hasFile('file_loa')){
                  HP::singleFileUploadRefno(
                      $request->file('file_loa') ,
                      $this->attach_path.'/'.$report->reference_refno,
                      ( $tax_number),
                       (auth()->user()->FullName ?? null),
                      'Center',
                      (  (new TrackingReport)->getTable() ),
                       $report->id,
                      'file_loa',
                      null
                  );
         }
         if ($request->file && $request->hasFile('file')){
           foreach($request->file as $key => $item) {
                  HP::singleFileUploadRefno(
                         $item ,
                      $this->attach_path.'/'.$report->reference_refno,
                      ( $tax_number),
                      (auth()->user()->FullName ?? null),
                      'Center',
                      (  (new TrackingReport)->getTable() ),
                      $report->id,
                      'file',
                       @$request->file_desc[$key] ?? null
                  );
              }
          }

          $file_loa = [];
          if( !empty($report->FileAttachFileLoaTo->url)){
              $file_loa['url'] =  $report->FileAttachFileLoaTo->url;
          }
          if( !empty($report->FileAttachFileLoaTo->new_filename)){
              $file_loa['new_filename'] =  $report->FileAttachFileLoaTo->new_filename;
          }
          if( !empty($report->FileAttachFileLoaTo->filename)){
              $file_loa['filename'] =  $report->FileAttachFileLoaTo->filename;
          }  

          $files = [];
          if( count($report->FileAttachFilesMany) > 0){
              foreach($report->FileAttachFilesMany as $key => $item) {
                  $object = (object)[];
                  $object->url           = $item->url ?? null;
                  $object->new_filename  = $item->new_filename ?? null;
                  $object->filename      = $item->filename ?? null;
                  $object->caption        = $item->caption ?? null;
                  $files[]                = $object;
              }
          }


          $object1 = (object)[];
          $object1->report_date        = $report->report_date ?? null;
          $object1->report_status      = $report->report_status ?? null;
          $object1->details            = $report->details ?? null;
          $object1->start_date         = $report->start_date ?? null;
          $object1->end_date           = $report->end_date ?? null;


           TrackingHistory::create([ 
                                      'tracking_id'       => $report->tracking_id ?? null,
                                      'certificate_type'  => 1,
                                       'reference_refno'   => $report->reference_refno ?? null,
                                      'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                      'ref_id'            =>  $report->ref_id ?? null,
                                      'system'            => 9, 
                                      'table_name'        => (new TrackingReport)->getTable() ,
                                      'refid'             => $id,
                                      'details_one'       => !empty($object1) ? json_encode($object1) : null,
                                      'attachs'           => (count($file_loa) > 0) ? json_encode($file_loa) : null,
                                      'attachs_file'      =>  (count($files) > 0) ? json_encode($files) : null,
                                      'created_by'        =>  auth()->user()->runrecno
                                  ]);
                   
          $tracking = Tracking::find($report->tracking_id);
          if (!is_null($tracking)  &&  $object1->report_status == 1){ 
              $tracking->status_id = 7;
              $tracking->save();
          }                   
    


          if( !empty($report->certificate_export_to->CertiCbTo)  &&  $object1->report_status == 1){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $certi = $report->certificate_export_to->CertiCbTo;
 
            if(!is_null($certi->email)){
                  $mail = new  ReportMail([
                                                  'certi'          => $certi,
                                                  'data'           => $report ,
                                                  'export'         => $report->certificate_export_to ?? '' ,
                                                  'url'            => $url.'certify/tracking-cb',
                                                  'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                                  'email_cc'      =>  !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                                  'email_reply'   => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                                          ]);                
                Mail::to($certi->email)->send($mail);
             }
       }

       if($request->previousUrl){
          return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/tracking-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
        }

  
  // } catch (\Exception $e) {
  //     return redirect('certificate/tracking-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  // }      
  }
         

  public function update_review(Request $request ,$id)
  {
    // try { 

          $review                    = TrackingReview::findOrFail($id);
          $review->review            = !empty($request->review) ? $request->review: null;
          $review->created_by        =  auth()->user()->getKey();
          $review->save();

          $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
          if ($request->evidence && $request->hasFile('evidence')){
                     HP::singleFileUploadRefno(
                         $request->file('evidence') ,
                         $this->attach_path.'/'.$review->reference_refno,
                         ( $tax_number),
                          (auth()->user()->FullName ?? null),
                         'Center',
                         (  (new TrackingReview)->getTable() ),
                          $review->id,
                         'evidence',
                         null
                     );
            }
           if ($request->attach && $request->hasFile('attach')){
                     HP::singleFileUploadRefno(
                         $request->file('attach') ,
                         $this->attach_path.'/'.$review->reference_refno,
                         ( $tax_number),
                          (auth()->user()->FullName ?? null),
                         'Center',
                         (  (new TrackingReview)->getTable() ),
                          $review->id,
                         'attach',
                         null
                     );
            }

             $evidence = [];
             if( !empty($review->FileAttachEvidenceTo->url)){
                 $evidence['url'] =  $review->FileAttachEvidenceTo->url;
             }
             if( !empty($review->FileAttachEvidenceTo->new_filename)){
                 $evidence['new_filename'] =  $review->FileAttachEvidenceTo->new_filename;
             }
             if( !empty($review->FileAttachEvidenceTo->filename)){
                 $evidence['filename'] =  $review->FileAttachEvidenceTo->filename;
             }  


             $attach = [];
             if( !empty($review->FileAttachFilesTo->url)){
                 $attach['url'] =  $review->FileAttachFilesTo->url;
             }
             if( !empty($review->FileAttachFilesTo->new_filename)){
                 $attach['new_filename'] =  $review->FileAttachFilesTo->new_filename;
             }
             if( !empty($review->FileAttachFilesTo->filename)){
                 $attach['filename'] =  $review->FileAttachFilesTo->filename;
             }  


              TrackingHistory::create([ 
                                     'tracking_id'        => $review->tracking_id ?? null,
                                      'certificate_type'  => 1,
                                       'reference_refno'   => $review->reference_refno ?? null,
                                      'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                      'ref_id'            =>  $review->ref_id ?? null,
                                      'system'            => 10, 
                                      'table_name'        => (new TrackingReview)->getTable() ,
                                      'refid'             => $id,
                                      'attachs'           => (count($evidence) > 0) ? json_encode($evidence) : null,
                                      'attachs_file'      =>  (count($attach) > 0) ? json_encode($attach) : null,
                                      'created_by'        =>  auth()->user()->runrecno
                                  ]);


             $tracking = Tracking::find($review->tracking_id);
          if (!is_null($tracking) &&  $review->review == 1){ 
            
            $tracking->status_id = 7;
            $tracking->save();

            //   $tracking->status_id = 9;
            //   $tracking->save();

            //   $pay_in =   TrackingPayInTwo::where('tracking_id',$tracking->id)->where('reference_refno',$tracking->reference_refno)->first();
            //   if(is_null($pay_in)){
            //    $pay_in = new TrackingPayInTwo;
            //   }
            //   $pay_in->tracking_id         = $tracking->id;
            //   $pay_in->ref_id              = $tracking->ref_id;
            //   $pay_in->ref_table           = (new CertiCBExport)->getTable();
            //   $pay_in->certificate_type    = 1;
            //   $pay_in->reference_refno     = $tracking->reference_refno;
            //   $pay_in->save();
            return redirect('certificate/tracking-cb/'.$tracking->id.'/edit')->with('flash_message', 'เรียบร้อยแล้ว!');
          }      
  

       if($request->previousUrl){
          return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/tracking-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
        }

  
  // } catch (\Exception $e) {
  //     return redirect('certificate/tracking-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
  // }      
  }



  public function pay_in2($id)
{
    $pay_in = TrackingPayInTwo::findOrFail($id);
 
     $feewaiver  =  Feewaiver::where('certify',1)
                                ->where('payin2_status',1)
                                ->whereDate('payin2_start_date','<=',date('Y-m-d'))
                                ->whereDate('payin2_end_date','>=',date('Y-m-d'))
                                ->first();
        if(is_null($pay_in->conditional_type)){
            if(!is_null($feewaiver)){
                $pay_in->conditional = 2; // เรียกเก็บค่าธรรมเนียม
            }else{
                $pay_in->conditional = 1; // ยกเว้นค่าธรรมเนียม
            }
        }

    
    return view('certificate.cb.tracking-cb.pay_in_two', compact('pay_in','feewaiver'));  
}    
        
public function update_pay_in2(Request $request ,$id)
{
    // try { 

        $pay_in                     = TrackingPayInTwo::findOrFail($id);
        $tb                         = new TrackingPayInTwo;
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
    if(!is_null($pay_in)){
 if(is_null($pay_in->state)){
                $pay_in->conditional_type    = $request->conditional_type ?? null; 
                $pay_in->state               =  1; 
                $pay_in->report_date         =  isset($request->report_date)?  HP::convertDate($request->report_date,true) : @$pay_in->report_date;
                $pay_in->created_by          =  auth()->user()->runrecno;
                $pay_in->save();
        if($pay_in->conditional_type == 1){ // เรียกเก็บค่าธรรมเนียม

            $setting_payment = CertiSettingPayment::where('certify',6)->where('payin',2)->where('type',1)->first();
            if(!is_null($setting_payment)){
                $arrContextOptions=array();
                if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                            "verify_peer" => false,
                                            "verify_peer_name" => false,
                                        );
                }
                $url    =  "$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$pay_in->reference_refno";
                $content =  file_get_contents($url, false, stream_context_create($arrContextOptions));

                $api = json_decode($content);
                $pay_in->amount_fixed        =   1000;
                $pay_in->amount_fee          =  !empty(str_replace(",","",$api->AmountCert))?str_replace(",","",$api->AmountCert):null;
                $pay_in->save();

                $file_payin  = self::storeFilePayin($setting_payment,$pay_in->reference_refno,'',$tb->getTable(),$pay_in->id,'attach_payin2','เรียกเก็บค่าธรรมเนียม');

                if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                   HP::getFileStoragePath($file_payin->url);
               }

                $transaction = HP::TransactionPayIn2($id,$tb->getTable(),'6','2',$api);
 
            }
        }else  if($pay_in->conditional_type == 2){  // ยกเว้นค่าธรรมเนียม

            $feewaiver  =  Feewaiver::where('certify',3)->first();
            if(!empty($feewaiver->payin1_file)){
                $file_types      =   explode('.',  basename($feewaiver->payin1_file)) ;
                $file_extension  =  end($file_types);
                $file_size       =   Storage::disk('ftp')->size($feewaiver->payin1_file);
                $request =  AttachFile::create([
                                                'tax_number'        => $tax_number,
                                                'username'          => (auth()->user()->FullName ?? null),
                                                'systems'           => 'Center',
                                                'ref_table'         => $tb->getTable(),
                                                'ref_id'            =>  $pay_in->id,
                                                'url'               => $feewaiver->payin1_file,
                                                'filename'          => $feewaiver->payin1_file_client_name,
                                                'new_filename'      => basename($feewaiver->payin1_file),
                                                'caption'           => 'ยกเว้นค่าธรรมเนียม',
                                                'size'              => $file_size ?? '0',
                                                'file_properties'   => $file_extension,
                                                'section'           => 'attach_payin2',
                                                'created_by'        => auth()->user()->getKey(),
                                                'created_at'        => date('Y-m-d H:i:s')
                ]);
            
                if(!is_null($feewaiver) && HP::checkFileStorage($feewaiver->payin1_file)){
                    HP::getFileStoragePath($feewaiver->payin1_file);
                }
            }
            
            $pay_in->start_date_feewaiver        =  $feewaiver->payin2_start_date ?? null;
            $pay_in->end_date_feewaiver          =  $feewaiver->payin2_end_date ?? null;
            $pay_in->save();

        }else  if($pay_in->conditional_type == 3){  // ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม

            $pay_in->detail = $request->detail ?? null;
            $pay_in->save();
            if($request->attach && $request->hasFile('attach')){
            $file_payin  =   HP::singleFileUploadRefno(
                                                        $request->file('attach') ,
                                                        $this->attach_path.'/'.$pay_in->reference_refno,
                                                        ( $tax_number),
                                                        (auth()->user()->FullName ?? null),
                                                        'Center',
                                                        ( $tb->getTable() ),
                                                        $pay_in->id,
                                                        'attach_payin2',
                                                        'ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม'
                                                     );
            
                if(!is_null($file_payin) && HP::checkFileStorage($file_payin->url)){
                    HP::getFileStoragePath($file_payin->url);
               }
          } 
        }

        // LOG
        $data = TrackingPayInTwo::select('id', 'conditional_type', 'amount', 'amount_fee', 'amount_fixed', 'status','report_date', 'detail', 'remark', 'start_date_feewaiver', 'end_date_feewaiver', 'status_cancel','created_cancel','date_cancel')
                                ->where('id',$id)
                                ->first();

        $file = [];
        if( !empty($data->FileAttachPayInTwo1To->url)){
            $file['url'] =  $data->FileAttachPayInTwo1To->url;
        }
        if( !empty($data->FileAttachPayInTwo1To->new_filename)){
            $file['new_filename'] =  $data->FileAttachPayInTwo1To->new_filename;
        }
        if( !empty($data->FileAttachPayInTwo1To->filename)){
            $file['filename'] =  $data->FileAttachPayInTwo1To->filename;
        }   

        TrackingHistory::create([ 
                                'tracking_id'       => $pay_in->tracking_id ?? null,
                                'certificate_type'  => 1,
                                'reference_refno'   => $pay_in->reference_refno ?? null,
                                'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                'ref_id'            =>  $pay_in->ref_id ?? null,
                                'system'            => 11,
                                'table_name'        => $tb->getTable(),
                                'refid'             => $id,
                                'details_one'       =>  json_encode($data) ?? null,
                                'file'              =>  (count($file) > 0) ? json_encode($file) : null,
                                'created_by'            =>  auth()->user()->runrecno
                                ]);



          $tracking = Tracking::find($pay_in->tracking_id);
        if (!is_null($tracking) ){ 
            $tracking->status_id = 10;
            $tracking->save();
        }          


        // Mail
        if(!empty($pay_in->certificate_export_to->CertiCbTo->email)){ // แจ้งเตือนผู้ประกอบการ
            $certi  =  $pay_in->certificate_export_to->CertiCbTo;
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $mail = new  PayInTwoMail([
                                        'data'             => $certi,
                                        'pay_in'            => $pay_in,
                                        'attachs'          => !empty($data->FileAttachPayInTwo1To->url) && is_file(!empty($data->FileAttachPayInTwo1To->url)) ? $data->FileAttachPayInTwo1To->url : '',
                                        'url'              => $url.'certify/tracking-cb',
                                        'email'            => 'nsc@tisi.mail.go.th',
                                        'email_cc'      =>  !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                        'email_reply'   => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                                    ]);
             Mail::to($certi->email)->send($mail);
        }  



 }else{

            if($request->status_confirmed == 1){
                $pay_in->remark =  null;
                $pay_in->state = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
                $pay_in->status = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
             }else{
                $pay_in->state = 1;  // ส่งให้ ผปก.
                $pay_in->detail = $request->detail ?? null;
                $pay_in->status = 0;
             }
                 $pay_in->save();

                 $tracking = Tracking::find($pay_in->tracking_id);
             
                 if (!is_null($tracking) ){ 
                    if($pay_in->state == 1){
                        $tracking->status_id = 10;
                    }else{
                        $tracking->status_id = 12;
                        if(!empty($tracking->certificate_export_to->app_certi_cb_id)){
                            $certi_cb = CertiCb::where('id', $tracking->certificate_export_to->app_certi_cb_id)->first();
                            $report     = TrackingReport::where('tracking_id',$tracking->id)   ->where('certificate_type',1)->where('reference_refno',$tracking->reference_refno)  ->orderby('id','desc')->first();
                            if(!empty($certi_cb) && !empty($report) && !is_null($report->FileAttachFileLoaTo)){
                                    $attach_pdf =  $report->FileAttachFileLoaTo;
                                    if(!empty($attach_pdf->url)){
                                        CertiCBFileAll::where('app_certi_cb_id', $certi_cb->id)->update(['state' => 0]);
                                        $certLabs = CertiCBFileAll::create([
                                            'app_certi_cb_id'      => $certi_cb->id,
                                            'attach_pdf'            => $attach_pdf->url ?? null,
                                            'attach_pdf_client_name'=> $attach_pdf->filename ?? null,
                                            'start_date'            => $report->start_date ?? null,
                                            'end_date'              => $report->end_date ?? null,
                                            'state' => 1
                                        ]);
                                        // แนบท้าย ที่ใช้งาน 
                                        $certi_cb->update([
                                                            'attach_pdf'             => $certLabs->attach_pdf ?? @$certi_cb->attach_pdf,
                                                            'attach_pdf_client_name' => $certLabs->attach_pdf_client_name ?? @$certi_cb->attach_pdf_client_name
                                                         ]);
                                    }
    
                            }
                        }
 
                    }
                     $tracking->save();
                 }          
         
         
 
 
            // LOG
             $data = TrackingPayInTwo::select('id', 'conditional_type', 'amount', 'amount_fee', 'amount_fixed', 'status','report_date', 'detail', 'remark', 'start_date_feewaiver', 'end_date_feewaiver', 'status_cancel','created_cancel','date_cancel')
                                ->where('id',$id)
                                ->first();


                $file = [];
                if( !empty($data->FileAttachPayInTwo1To->url)){
                    $file['url'] =  $data->FileAttachPayInTwo1To->url;
                }
                if( !empty($data->FileAttachPayInTwo1To->new_filename)){
                    $file['new_filename'] =  $data->FileAttachPayInTwo1To->new_filename;
                }
                if( !empty($data->FileAttachPayInTwo1To->filename)){
                    $file['filename'] =  $data->FileAttachPayInTwo1To->filename;
                }   

                $attachs_file = [];
                if( !empty($data->FileAttachPayInTwo2To->url)){
                    $attachs_file['url'] =  $data->FileAttachPayInTwo2To->url;
                }
                if( !empty($data->FileAttachPayInTwo2To->new_filename)){
                    $attachs_file['new_filename'] =  $data->FileAttachPayInTwo2To->new_filename;
                }
                if( !empty($data->FileAttachPayInTwo2To->filename)){
                    $attachs_file['filename'] =  $data->FileAttachPayInTwo2To->filename;
                }  
                TrackingHistory::create([ 
                                        'tracking_id'       => $pay_in->tracking_id ?? null,
                                        'certificate_type'  => 1,
                                         'reference_refno'  => $pay_in->reference_refno ?? null,
                                        'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                        'ref_id'            =>  $pay_in->ref_id ?? null,
                                        'system'            => 11, //Pay-In ครั้งที่ 2
                                        'table_name'        => $tb->getTable(),
                                        'refid'             => $id,
                                        'status'            => $pay_in->status ?? null,
                                        'details_one'       =>  json_encode($data) ?? null,
                                        'file'              =>  (count($file) > 0) ? json_encode($file) : null,
                                        'attachs_file'      =>  (count($attachs_file) > 0) ? json_encode($attachs_file) : null,
                                        'created_by'        =>  auth()->user()->runrecno
                                    ]);

           
        // Mail
        if($pay_in->state == 1  && !empty($pay_in->certificate_export_to->CertiCbTo->email)){ // แจ้งเตือนผู้ประกอบการ
            $certi  =  $pay_in->certificate_export_to->CertiCbTo;
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $mail = new  PayInTwoMail([
                                        'data'             => $certi,
                                        'pay_in'            => $pay_in,
                                        'attachs'          => !empty($data->FileAttachPayInTwo1To->url) && is_file(!empty($data->FileAttachPayInTwo1To->url)) ? $data->FileAttachPayInTwo1To->url : '',
                                        'url'              => $url.'certify/tracking-cb',
                                        'email'            => 'nsc@tisi.mail.go.th',
                                        'email_cc'      =>  !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                                        'email_reply'   => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                                    ]);
              Mail::to($certi->email)->send($mail);
        }  

 }   
    if($request->previousUrl){
        return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
    }else{
        return redirect('certificate/tracking-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
    }


        
    }

    
    // } catch (\Exception $e) {
    //     return redirect('certificate/tracking-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // }      
}

public function append($id)
{
      $tracking = Tracking::find($id);

   if($tracking->status_id != 8){
   
        $tracking                   = Tracking::find($id);
        $tracking->status_id        =  8; // ต่อขอบข่ายเรียบร้อย
        $tracking->save();

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        //    $certiCb = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;
             $certiCb = $tracking->certificate_export_to->applications;
    
            $certiCbFileAll = CertiCBAttachAll::where('app_certi_cb_id',$certiCb->id)
                ->where('table_name','app_certi_cb')
                ->where('file_section',3)
                ->latest() // เรียงจาก created_at จากมากไปน้อย
                ->first();
    
            $filePath = 'files/applicants/check_files_cb/' . $certiCbFileAll->file ;
    
            $localFilePath = HP::downloadFileFromTisiCloud($filePath);

                       $uploadedFile = new \Illuminate\Http\UploadedFile(
                $localFilePath,      // Path ของไฟล์
                basename($localFilePath), // ชื่อไฟล์
                mime_content_type($localFilePath), // MIME type
                null,               // ขนาดไฟล์ (null ถ้าไม่ทราบ)
                true                // เป็นไฟล์ที่ valid แล้ว
            );
    

      $attach_path = "files/trackingcb";
            // dd($attach_path.'/'.$inspection->reference_refno);
            // ใช้ไฟล์ที่จำลองในการอัปโหลด
            HP::singleFileUploadRefno(
                $uploadedFile,
                $attach_path.'/'.$tracking->reference_refno,
                ( $tax_number),
                (auth()->user()->FullName ?? null),
                'Center',
                (  (new Tracking)->getTable() ),
                $tracking->id,
                'attach_pdf',
                null
            );

        // if ($request->attach_pdf && $request->hasFile('attach_pdf')){
        //            HP::singleFileUploadRefno(
        //                $request->file('attach_pdf') ,
        //                $this->attach_path.'/'.$tracking->reference_refno,
        //                ( $tax_number),
        //                 (auth()->user()->FullName ?? null),
        //                'Center',
        //                (  (new Tracking)->getTable() ),
        //                 $tracking->id,
        //                'attach_pdf',
        //                null
        //            );
        //   }
        //  if ($request->attach && $request->hasFile('attach')){
        //            HP::singleFileUploadRefno(
        //                $request->file('attach') ,
        //                $this->attach_path.'/'.$tracking->reference_refno,
        //                ( $tax_number),
        //                 (auth()->user()->FullName ?? null),
        //                'Center',
        //                (  (new Tracking)->getTable() ),
        //                 $tracking->id,
        //                'attach',
        //                null
        //            );
        //   }

           $attach_pdf = [];
           if( !empty($tracking->FileAttachPDFTo->url)){
               $attach_pdf['url'] =  $tracking->FileAttachPDFTo->url;
           }
           if( !empty($tracking->FileAttachPDFTo->new_filename)){
               $attach_pdf['new_filename'] =  $tracking->FileAttachPDFTo->new_filename;
           }
           if( !empty($tracking->FileAttachPDFTo->filename)){
               $attach_pdf['filename'] =  $tracking->FileAttachPDFTo->filename;
           }  


           $attach = [];
           if( !empty($tracking->FileAttachFilesTo->url)){
               $attach['url'] =  $tracking->FileAttachFilesTo->url;
           }
           if( !empty($tracking->FileAttachFilesTo->new_filename)){
               $attach['new_filename'] =  $tracking->FileAttachFilesTo->new_filename;
           }
           if( !empty($tracking->FileAttachFilesTo->filename)){
               $attach['filename'] =  $tracking->FileAttachFilesTo->filename;
           }  


            TrackingHistory::create([ 
                                    'tracking_id'       => $tracking->id ?? null,
                                    'certificate_type'  => 1,
                                     'reference_refno'   => $tracking->reference_refno ?? null,
                                    'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                    'ref_id'            =>  $tracking->ref_id ?? null,
                                    'system'            => 12, 
                                    'table_name'        => (new Tracking)->getTable() ,
                                    'refid'             => $id,
                                    // 'details_one'       =>  !empty($request->start_date)?HP::convertDate($request->start_date,true):null,
                                    // 'details_two'        =>  !empty($request->end_date)?HP::convertDate($request->end_date,true):null,
                                      'details_one' => Carbon::now()->format('Y-m-d'),
                                    'details_two' => Carbon::now()->addYears(2)->format('Y-m-d'),
                                    'attachs'           => (count($attach_pdf) > 0) ? json_encode($attach_pdf) : null,
                                    'attachs_file'      =>  (count($attach) > 0) ? json_encode($attach) : null,
                                    'created_by'        =>  auth()->user()->runrecno
                                ]);

 
      if(!empty($tracking->certificate_export_to->app_certi_cb_id)){
                $certi_cb = CertiCb::find($tracking->certificate_export_to->app_certi_cb_id);
        if(!empty($certi_cb) ){
                // $attach_pdf =  $tracking->FileAttachPDFTo;
                // $attach     =  $tracking->FileAttachFilesTo;
                // if(!empty($attach_pdf->url)){
                //      CertiCBFileAll::where('app_certi_cb_id', $certi_cb->id)->update(['state' => 0]);
                //     $certcb = CertiCBFileAll::create([
                //                                         'app_certi_cb_id'        =>  $certi_cb->id,
                //                                         'attach_pdf'             =>  !empty($attach_pdf->url)?$attach_pdf->url:null,
                //                                         'attach_pdf_client_name' =>  !empty($attach_pdf->filename)?$attach_pdf->filename:null,  
                //                                         'attach'                 =>  !empty($attach->url)?$attach->url:null,
                //                                         'attach_client_name'     =>  !empty($attach->filename)?$attach->filename:null,  
                //                                         // 'start_date'             =>  !empty($request->start_date)?HP::convertDate($request->start_date,true):null,
                //                                         // 'end_date'               =>  !empty($request->end_date)?HP::convertDate($request->end_date,true):null,
                //                                          'start_date'             =>  Carbon::now()->format('Y-m-d'),
                //                                         'end_date'               =>  Carbon::now()->addYears(2)->format('Y-m-d'),
                //                                         'state' => 1
                //                                     ]);
                //     // แนบท้าย ที่ใช้งาน 
                //     //   $certi_cb->update([
                //     //                     'attach_pdf'             => $certcb->attach_pdf ?? @$certcb->attach_pdf,
                //     //                     'attach_pdf_client_name' => $certcb->attach_pdf_client_name ?? @$certcb->attach_pdf_client_name
                //     //                     ]);
                // }

                
                    $json = $this->copyScopeCbFromAttachement($certi_cb->id);
                    $copiedScopes = json_decode($json, true);

                    CertiCBFileAll::where('app_certi_cb_id',$certi_cb->id)
                        // ->whereNotNull('attach_pdf')
                        ->update(['state' => 0]);
                    $certcb = CertiCBFileAll::create([
                                    'app_certi_cb_id'        =>  $certi_cb->id,
                                     'app_no'        =>  $tracking->reference_refno,
                                     'attach_pdf'             =>  $copiedScopes[0]['attachs'],
                                    'attach_pdf_client_name' =>   $copiedScopes[0]['file_client_name'],
                                    'attach'                 =>  null,
                                    'attach_client_name'     =>   null,
                                     'ref_table'     =>   (new Tracking)->getTable(),
                                    'ref_id'     =>    $tracking->id,
                                    // 'start_date'             =>  !empty($request->start_date)?HP::convertDate($request->start_date,true):null,
                                    // 'end_date'               =>  !empty($request->end_date)?HP::convertDate($request->end_date,true):null,
                                    'start_date'             =>  Carbon::now()->format('Y-m-d'),
                                    'end_date'               =>  Carbon::now()->addYears(2)->format('Y-m-d'),
                                    'state' => 1
                                ]);

        }
    }
    }

      $certi_cb = CertiCb::findOrFail($tracking->certificate_export_to->app_certi_cb_id);
      $certicb_file_all = CertiCBFileAll::where('app_certi_cb_id', $certi_cb->id)->orderby('id','desc')->get();
    return view('certificate.cb.tracking-cb.append', compact('tracking', 'certi_cb','certicb_file_all'));  
} 

  public function copyScopeCbFromAttachement($certiCbId)
    {
        $copiedScoped = null;
        $fileSection = null;
    
        $app = CertiCb::find($certiCbId);
    
        $latestRecord = CertiCBAttachAll::where('app_certi_cb_id', $certiCbId)
        ->where('file_section', 3)
        ->where('table_name', 'app_certi_cb')
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();
    
        $existingFilePath = 'files/applicants/check_files_cb/' . $latestRecord->file ;
    
        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files_cb/'.$no.'/';
    
            if (file_exists($localFilePath)) {
                $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );
                $filePath = $attach_path . $dlName;
                if (Storage::disk('ftp')->exists($filePath)) {
                    $list  = new  stdClass;
                    $list->attachs =  $no.'/'.$dlName;
                    $list->file_client_name =  $dlName;
                    $scope[] = $list;
                    $copiedScoped = json_encode($scope);
                } 
                unlink($localFilePath);
            }
        }
    
        return $copiedScoped;
    }


public function update_append(Request $request ,$id)
{
  // try { 

        $tracking                   = Tracking::find($id);
        $tracking->status_id        =  8; // ต่อขอบข่ายเรียบร้อย
        $tracking->save();

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        if ($request->attach_pdf && $request->hasFile('attach_pdf')){
                   HP::singleFileUploadRefno(
                       $request->file('attach_pdf') ,
                       $this->attach_path.'/'.$tracking->reference_refno,
                       ( $tax_number),
                        (auth()->user()->FullName ?? null),
                       'Center',
                       (  (new Tracking)->getTable() ),
                        $tracking->id,
                       'attach_pdf',
                       null
                   );
          }
         if ($request->attach && $request->hasFile('attach')){
                   HP::singleFileUploadRefno(
                       $request->file('attach') ,
                       $this->attach_path.'/'.$tracking->reference_refno,
                       ( $tax_number),
                        (auth()->user()->FullName ?? null),
                       'Center',
                       (  (new Tracking)->getTable() ),
                        $tracking->id,
                       'attach',
                       null
                   );
          }

           $attach_pdf = [];
           if( !empty($tracking->FileAttachPDFTo->url)){
               $attach_pdf['url'] =  $tracking->FileAttachPDFTo->url;
           }
           if( !empty($tracking->FileAttachPDFTo->new_filename)){
               $attach_pdf['new_filename'] =  $tracking->FileAttachPDFTo->new_filename;
           }
           if( !empty($tracking->FileAttachPDFTo->filename)){
               $attach_pdf['filename'] =  $tracking->FileAttachPDFTo->filename;
           }  


           $attach = [];
           if( !empty($tracking->FileAttachFilesTo->url)){
               $attach['url'] =  $tracking->FileAttachFilesTo->url;
           }
           if( !empty($tracking->FileAttachFilesTo->new_filename)){
               $attach['new_filename'] =  $tracking->FileAttachFilesTo->new_filename;
           }
           if( !empty($tracking->FileAttachFilesTo->filename)){
               $attach['filename'] =  $tracking->FileAttachFilesTo->filename;
           }  


            TrackingHistory::create([ 
                                    'tracking_id'       => $tracking->id ?? null,
                                    'certificate_type'  => 1,
                                     'reference_refno'   => $tracking->reference_refno ?? null,
                                    'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                    'ref_id'            =>  $tracking->ref_id ?? null,
                                    'system'            => 12, 
                                    'table_name'        => (new Tracking)->getTable() ,
                                    'refid'             => $id,
                                    // 'details_one'       =>  !empty($request->start_date)?HP::convertDate($request->start_date,true):null,
                                    // 'details_two'        =>  !empty($request->end_date)?HP::convertDate($request->end_date,true):null,
                                      'details_one' => Carbon::now()->format('Y-m-d'),
                                    'details_two' => Carbon::now()->addYears(2)->format('Y-m-d'),
                                    'attachs'           => (count($attach_pdf) > 0) ? json_encode($attach_pdf) : null,
                                    'attachs_file'      =>  (count($attach) > 0) ? json_encode($attach) : null,
                                    'created_by'        =>  auth()->user()->runrecno
                                ]);

 
      if(!empty($tracking->certificate_export_to->app_certi_cb_id)){
                $certi_cb = CertiCb::where('id', $tracking->certificate_export_to->app_certi_cb_id)->first();
        if(!empty($certi_cb) &&  !is_null($tracking->FileAttachPDFTo)){
                $attach_pdf =  $tracking->FileAttachPDFTo;
                $attach     =  $tracking->FileAttachFilesTo;
                if(!empty($attach_pdf->url)){
                     CertiCBFileAll::where('app_certi_cb_id', $certi_cb->id)->update(['state' => 0]);
                    $certcb = CertiCBFileAll::create([
                                                        'app_certi_cb_id'        =>  $certi_cb->id,
                                                        'attach_pdf'             =>  !empty($attach_pdf->url)?$attach_pdf->url:null,
                                                        'attach_pdf_client_name' =>  !empty($attach_pdf->filename)?$attach_pdf->filename:null,  
                                                        'attach'                 =>  !empty($attach->url)?$attach->url:null,
                                                        'attach_client_name'     =>  !empty($attach->filename)?$attach->filename:null,  
                                                        'start_date'             =>  !empty($request->start_date)?HP::convertDate($request->start_date,true):null,
                                                        'end_date'               =>  !empty($request->end_date)?HP::convertDate($request->end_date,true):null,
                                                        'state' => 1
                                                    ]);
                    // แนบท้าย ที่ใช้งาน 
                    //   $certi_cb->update([
                    //                     'attach_pdf'             => $certcb->attach_pdf ?? @$certcb->attach_pdf,
                    //                     'attach_pdf_client_name' => $certcb->attach_pdf_client_name ?? @$certcb->attach_pdf_client_name
                    //                     ]);
                }

        }
    }

   return redirect('certificate/tracking-cb/append/'.$id)->with('flash_message', 'เรียบร้อยแล้ว!');



// } catch (\Exception $e) {
//     return redirect('certificate/tracking-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
// }      
}




  public function check_pay_in(Request $request)
  {
           $arrContextOptions=array();
          $id     =   $request->input('id');
          $payin  =   $request->input('payin');
                                 

     if($payin == '1'){ // pay in ครั้งที่ 1
               $pay_in = TrackingPayInOne::findOrFail($id);
           if(!is_null($pay_in)){ 
            
               $pay_in->start_date     =  isset($request->start_date)?  $request->start_date : null;
               $pay_in->end_date       =  isset($request->start_date) ?  HP::DatePlus($request->start_date,'30') : null;
               $pay_in->amount_bill    =  !empty(str_replace(",","",$request->amount))?str_replace(",","",$request->amount):  null;
               $pay_in->save();

               $setting_payment = CertiSettingPayment::where('certify',6)->where('payin',1)->where('type',1)->first();
               $url    =  "$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$pay_in->reference_refno-$pay_in->auditors_id";
         
               if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                   $arrContextOptions["ssl"] = array(
                                                   "verify_peer" => false,
                                                   "verify_peer_name" => false,
                                               );
               }
               $content =  file_get_contents($url, false, stream_context_create($arrContextOptions));
               $api = json_decode($content);
               if(!is_null($api) && $api->returnCode != '000'){
                   return response()->json([
                                            'message'      =>  false,
                                            'status_error' => HP::getErrorCode($api->returnCode)
                                           ]);
               }else{
                   return response()->json([
                                            'message' =>  true
                                           ]);
               } 
   
           }else{
                    return response()->json([
                                            'message' =>  false
                                           ]);
           }

      }else{
          $pay_in = TrackingPayInTwo::findOrFail($id);
          if(!is_null($pay_in)){ 
              $setting_payment = CertiSettingPayment::where('certify',6)->where('payin',2)->where('type',1)->first();
              if(!is_null($setting_payment)){
                  $url    =  "$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$pay_in->reference_refno";
                      if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                          $arrContextOptions["ssl"] = array(
                                                          "verify_peer" => false,
                                                          "verify_peer_name" => false,
                                                      );
                      }
                          $content =  file_get_contents($url, false, stream_context_create($arrContextOptions));

                          $api = json_decode($content);

                          if(!is_null($api) && $api->returnCode != '000'){
                              return response()->json([
                                                      'message'      =>  false,
                                                      'status_error' => HP::getErrorCode($api->returnCode)
                                                      ]);
                          }else{
                              return response()->json([
                                                      'message' =>  true
                                                      ]);
                          } 
                  }else{
                      return response()->json([
                                              'message' =>  false
                                              ]);
                  } 
           }else{
                    return response()->json([
                                            'message' =>  false
                                           ]);
           }

      }          
  }

  public function modal_status_auditor(Request $request)
  {
      $auditors = TrackingAuditors::select('auditor','step_id','created_at')->where('tracking_id',$request->id)->get();
      if(count($auditors) > 0){
          $datas = [];
          foreach($auditors as $key => $item) {
              $object = (object)[];
              $object->auditor         = $item->auditor ?? '';
              $object->status          = !empty($item->certi_auditors_step_to->title)  ? $item->certi_auditors_step_to->title : '';
              $object->created_at        = HP::DateTimeThai($item->created_at) ?? '-';
              $datas[] = $object;
          }
             return response()->json([
                               'message'  =>  true,
                               'datas'    =>  $datas
                            ]);
      }else{
          return response()->json([
                               'message' =>  false
                            ]);
      }
 
  }

  public function data_certificate(Request $request)
  {                  
      //ตั้งค่าตรวจติดตาม             
      $setting_config  = SettingConfig::where('grop_type','cb')->first();
      $from_filed      = !empty($setting_config->from_filed)?$setting_config->from_filed:null;
      $condition_check = !empty($setting_config->condition_check)?$setting_config->condition_check:null;
      $warning_day     = !empty($setting_config->warning_day)?$setting_config->warning_day:null;
      $check_first     = !empty($setting_config->check_first)?$setting_config->check_first:null;

      $app_certi_cb_id  = CertiCBCheck::where('user_id',auth()->user()->runrecno)->select('app_certi_cb_id'); // เช็คเจ้าหน้าที่ LAB
      $export_id        = Tracking::where('ref_table',(new CertiCBExport)->getTable())
                                  ->where('status_id','8')
                                  ->select('ref_id'); 

      $filter_search     = $request->input('filter_search');
      
      $query = CertiCBExport::query()
                              ->where('status',4)
                              ->WhereNull('status_revoke')
                              ->whereIn('id',$export_id)
                              ->whereIn('app_certi_cb_id',$app_certi_cb_id)
                              ->when($setting_config, function ($query) use ($from_filed, $condition_check, $warning_day, $check_first){
                                switch ( $from_filed ):
                                    case "1": //วันที่ออกใบรับรอง
                                        if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                            return $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(date_start), INTERVAL 6 MONTH),CURDATE())' ), '>', $warning_day);
                                        }else{
                                            return $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(date_start), INTERVAL '.$condition_check.' MONTH),CURDATE())' ), '>', $warning_day);
                                        }
                                        break;
                                    case "3": //วันที่ตรวจล่าสุด
                                        if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                            return  $query->whereHas('app_certi_cb_auditors', function($query)use ($warning_day){
                                                        $query->whereHas('app_certi_cb_auditors_date', function($query) use ($warning_day){
                                                            $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(end_date), INTERVAL 6 MONTH),CURDATE())'), '>', $warning_day);
                                                        });
                                            });
                                        }else{
                                            return  $query->whereHas('app_certi_cb_auditors', function($query)use ($condition_check, $warning_day){
                                                        $query->whereHas('app_certi_cb_auditors_date', function($query) use ( $condition_check, $warning_day){
                                                            $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(end_date), INTERVAL '.$condition_check.' MONTH),CURDATE())'), '>', $warning_day);
                                                        });
                                            });
                                        }
                                        break;
                                endswitch;
                            })
                              ->when($filter_search, function ($query, $filter_search){
                                  $search_full = str_replace(' ', '', $filter_search );
                                    return $query->where('certificate','LIKE', "%".$search_full."%")
                                                 ->Orwhere('accereditatio_no','LIKE', "%".$search_full."%")
                                                 ->OrwhereHas('CertiCbTo', function ($query) use($search_full) {
                                                              $query->where('name_standard', 'LIKE', "%$search_full%");
                                                          });
                                });

      return Datatables::of($query)
                          ->addIndexColumn()
                          ->addColumn('checkbox', function ($item) {
                              return '<input type="checkbox" name="item_certificate[]" class="item_certificate" value="'. $item->id .'">';
                          })
                          ->addColumn('certificate_no', function ($item) {
                              return $item->certificate;
                          })
                          ->addColumn('accereditatio_no', function ($item) {
                              return $item->accereditatio_no;
                          })
                          ->addColumn('name_standard', function ($item) {
                              return !empty($item->CertiCbTo->name_standard)? $item->CertiCbTo->name_standard:'';
                          }) 
                          ->addColumn('date_start', function ($item) {
                              return !empty($item->date_start)? HP::formatDateThaiFull($item->date_start):'';
                          })
                          ->addColumn('date_end', function ($item) {
                              $cb_auditors = CertiCBAuditors::where('app_certi_cb_id',$item->app_certi_cb_id)->orderby('id','desc')->first();
                                  if(!empty($cb_auditors)){
                                      $cb_auditors_date = CertiCBAuditorsDate::where('auditors_id',$cb_auditors->id)->first();
                                      $end_date = $cb_auditors_date->end_date;
                                  }
                              return !empty($end_date)? HP::formatDateThaiFull($end_date):'';
                          })
                          ->addColumn('email', function ($item) {
                              return !empty($item->CertiCbTo->email)? $item->CertiCbTo->email:'';
                          })
                          ->order(function ($query) {
                              $query->orderBy('id', 'DESC');
                          })
                          ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at'])
                          ->make(true);
  }
  
  public function save_check(Request $request) //ตรวจติดตามก่อนกำหนด
  {
    $requestData = $request->all();
    $ids  = !empty($requestData['ids'])?$requestData['ids']:[];

    if(count($ids) > 0){
      $tracking_ids = [];
          foreach($ids as $id){
              $cb_export  =  CertiCBExport::where('id',$id)->first();
              if(!empty($cb_export)){
                  $certi_cb = $cb_export->CertiCbTo;
                  $tracking  = new Tracking;
                  $tracking->certificate_type          =  '1';
                  $tracking->ref_table                 = $cb_export->getTable();
                  $tracking->ref_id                    = $cb_export->id;
                  $tracking->tax_id                    = !empty($certi_cb->tax_id)?$certi_cb->tax_id:null;
                  $tracking->user_id                   = !empty($certi_cb->created_by)?$certi_cb->created_by:null;
                //   $tracking->user_id                   = auth()->user()->getKey();
                  $tracking->status_id                 = ($requestData['send_mail'] == 1)?2:1;
                  $tracking->send_mail                 = ($requestData['send_mail'] == 1)?1:0;
                  $tracking->save();

                  if(!empty($tracking->id)){
                      $tracking_refno = Tracking::where('id',$tracking->id)->first();
                      $tracking_refno->reference_refno = HP::ReferenceRefno(1,$tracking->id);
                      $tracking_refno->reference_date =  date('Y-m-d H:i:s');
                      $tracking_refno->save();

                      $tracking_ids[] = $tracking->id;

                  }
              }

          }
          if(!empty($requestData['send_mail'] ) && $requestData['send_mail'] == 1){
              $this->send_mail($tracking_ids);
          }

          return response()->json(['message' =>  true ]);
      }


  }

  public function save_receiver(Request $request)   //บันทึกรับเรื่องตรวจติดตาม
  {
      $requestData     = $request->all();
      $ids  = !empty($requestData['ids'])?$requestData['ids']:[];

      if(count($ids) > 0){
        $tracking_ids = [];
            foreach($ids as $id){
                $cb_export  =  CertiCBExport::where('id',$id)->first();
                if(!empty($cb_export)){
                    $certi_cb = $cb_export->CertiCbTo;
                    $tracking  = new Tracking;
                    $tracking->certificate_type          =  '1';
                    $tracking->ref_table                 = $cb_export->getTable();
                    $tracking->ref_id                    = $cb_export->id;
                    $tracking->tax_id                    = !empty($certi_cb->tax_id)?$certi_cb->tax_id:null;
                    $tracking->user_id                   = !empty($certi_cb->created_by)?$certi_cb->created_by:null;
                    // $tracking->user_id                   = auth()->user()->getKey();
                    $tracking->status_id                 = ($requestData['send_mail'] == 1)?2:1;
                    $tracking->send_mail                 = ($requestData['send_mail'] == 1)?1:0;
                    $tracking->save();

                    if(!empty($tracking->id)){
                        $tracking_refno = Tracking::where('id',$tracking->id)->first();
                        $tracking_refno->reference_refno = HP::ReferenceRefno(1,$tracking->id);
                        $tracking_refno->reference_date =  date('Y-m-d H:i:s');
                        $tracking_refno->save();

                        $tracking_ids[] = $tracking->id;

                    }
                }

            }
            
            if(!empty($requestData['send_mail'] ) && $requestData['send_mail'] == 1){
                $this->send_mail($tracking_ids);
            }

            return response()->json(['message' =>  true ]);
        }
  }

  public function send_mail($tracking_ids){ //ส่งเมล แจ้งเตือนผู้รับใบรับรอง

      if(count($tracking_ids) > 0){
          foreach($tracking_ids as $tracking_id){
          $tracking  =  Tracking::where('id',$tracking_id)->first();
              if(!empty($tracking->certificate_export_to)){

              $cb_export          = $tracking->certificate_export_to;
              $email              = ($cb_export->CertiCbTo->email ?? null);
              $name               = ($cb_export->CertiCbTo->name_standard ?? null);
              $date_start         = (!empty($cb_export->date_start)? HP::formatDateThaiFull($cb_export->date_start):'');

              $cb_auditors = CertiCBAuditors::where('app_certi_cb_id',$cb_export->CertiCbTo->id)->first();
              if(!empty($cb_auditors)){
                  $cb_auditors_date = CertiCBAuditorsDate::where('auditors_id',$cb_auditors->id)->orderby('id','desc')->first();
                  $end_date = $cb_auditors_date->end_date;
              }
              $date_end  =  !empty($end_date)? HP::formatDateThaiFull($end_date):'';

                  if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
                  $data_app = [
                              'date_start'        => $date_start,
                              'date_end'          => $date_end,
                              'certificate'       => ($cb_export->accereditatio_no ?? ''),
                              'title'             => ('แจ้งการตรวจติดตามใบรับรอง เลขที่ : '.($cb_export->certificate ?? '')),
                              'name'              => $name
                          ];

                  $html = new ReceiverMail($data_app);
                  Mail::to($email)->send($html);
                  }

              }
        
          }
      }
     
  }

}

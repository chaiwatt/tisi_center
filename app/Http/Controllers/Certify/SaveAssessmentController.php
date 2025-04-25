<?php

namespace App\Http\Controllers\Certify;

use HP;
use Storage;
use App\User;
use stdClass;
use Exception;
use Carbon\Carbon;
 
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Besurv\Signer;
use App\Mail\Lab\CreateLabReport;
use App\Mail\Lab\MailToLabExpert;

use App\Mail\Lab\RequestEditScope;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_encode;
use  App\Models\Certify\BoardAuditor;
use App\Mail\Lab\MailLabReportSigner;
use App\Models\Certify\LabReportInfo;
use App\Models\Bcertify\LabCalRequest;
use Illuminate\Support\Facades\Mail;  
use App\Mail\Lab\CertifySaveAssessment;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\Report;
use App\Models\Certify\BoardAuditorDate;
use  App\Models\Certify\BoardAuditorGroup;
use App\Mail\Lab\CertifyConfirmAssessment;
use App\Models\Bcertify\BoardAuditoExpert;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Bcertify\AuditorInformation;
use App\Mail\Lab\CertifyCheckSaveAssessment;
use App\Models\Certificate\LabReportTwoInfo;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\NoticeItem;
use App\Models\Certify\Applicant\Information;
use App\Models\Certify\Applicant\CheckExaminer;
use  App\Models\Certify\BoardAuditorInformation;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Certify\Applicant\AssessmentExaminer;
use App\Models\Certify\SignAssessmentReportTransaction;

class SaveAssessmentController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files/';
    }
 


    public function index(Request $request)
    {
        // dd('ok');
        $keyword = $request->get('search');
        $filter = [];
        $filter['at'] = $request->get('at', '');
        $filter['b'] = $request->get('b', '');
        $filter['s'] = $request->get('s', '');
        $filter['c'] = $request->get('c', '');
        $filter['filter_start_date'] = $request->get('filter_start_date', '');
        $filter['filter_end_date'] = $request->get('filter_end_date', '');
        $filter['q'] = $request->get('q', '');
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['sort'] = $request->get('sort', '');
        $filter['direction'] = $request->get('direction', '');

        

        $ao = new CertiLab;
 
        $arrStatus = HP::DataStatusCertify();
        $branches = collect();

        $app_id = $request->app;
        $app = $app_id ? CertiLab::find($app_id) : null;
        if ($app) {
            $Query = $app->notices();
        } else {
            $Query = new Notice;
        }
             $Query = $Query->select('app_certi_lab_notices.*');
        if ($filter['at']!='') { // ความสามารถห้องปฏิบัติการ
            $Query = $Query->whereHas('applicant', function ($query) use ($filter) {
                $query->where('lab_type', $filter['at']);
            });
            $ao->get_branches($filter['at'])->each(function ($branch) use ($branches) {
                $branches->put($branch->id, $branch->title);
            });
        }

        if ($filter['b']!='' && $filter['at']!='') { // สาขา
            $Query = $Query->where('branch_name', $filter['b']);
        }

        if ($filter['s']!='') { // สถานะคำขอ
            if($filter['s'] == '0'){
                $Query =   $Query->where('draft', $filter['s']);
            }else{
                $Query =   $Query->where('report_status', $filter['s']);
            }
        }

        if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
            $start = Carbon::createFromFormat("d/m/Y",$filter['filter_start_date'])->addYear(-543)->formatLocalized('%Y-%m-%d');
            $end = Carbon::createFromFormat("d/m/Y",$filter['filter_end_date'])->addYear(-543)->formatLocalized('%Y-%m-%d');
            $Query = $Query->whereBetween('created_at', [$start,$end]);

        } elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
             $start = Carbon::createFromFormat("d/m/Y",$filter['filter_start_date'])->addYear(-543)->formatLocalized('%Y-%m-%d');
            $Query = $Query->whereDate('created_at',$start);
        }

        if ($filter['q']!='') { // สถานะคำขอ
                $key = $filter['q'];
                $certiLab  = CertiLab::where('app_no', 'like', '%'.$key.'%')->pluck('id');
                $Query =   $Query->whereIn('app_certi_lab_id',$certiLab);
        }
             //เจ้าหน้าที่ LAB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("28",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
                $check = AssessmentExaminer::where('user_id',auth()->user()->runrecno)->pluck('app_certi_lab_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) { 
                    $Query = $Query->LeftJoin('app_certi_lab_assessments_examiner','app_certi_lab_assessments_examiner.app_certi_lab_id','=','app_certi_lab_notices.app_certi_lab_id')
                                     ->where('user_id',auth()->user()->runrecno);  // LAB เจ้าหน้าที่ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                } 
            }
        // $exa
        $users = User::orderBy('reg_fname')->get();
        $select_users = array();
        foreach ($users as $user) {
            $select_users[$user->runrecno] = $user->reg_fname . ' ' . $user->reg_lname;
        }
        
        // $notices = $Query->orderby('id','desc')->sortable()->paginate($filter['perPage']);
        $notices = $Query->whereHas('applicant')
                 ->orderBy('id', 'desc')
                 ->sortable()
                 ->paginate($filter['perPage']);
        // dd($notices);
        return view('certify.save_assessment.index', compact(
            'notices', 'app','select_users','arrStatus','filter','branches'
        ));
    }

    public function apiGetNotices(Assessment $ca)
    {
        $items = $ca->notice_items()->with('notice.files')->with('reporter')->orderBy('created_at', 'asc')->get();
        return response()->json(compact('items'));
    }


    // public function create(CertiLab $app = null)
    public function create($board_auditor_id=null)
    {
        // dd($board_auditor_id);
        $app = new CertiLab();
        $find_notice = null;
        $NoticeItem = [new NoticeItem];
        if($board_auditor_id != null){
            $appCertiLabAssessment = Assessment::where('auditor_id',$board_auditor_id)->first();
            if($appCertiLabAssessment != null){
                $find_notice = Notice::where('app_certi_assessment_id',$appCertiLabAssessment->id)->first();
                if($find_notice != null){
                    $NoticeItem = $find_notice->items;
                }
            }
        }
        $app_no = [];
        //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
        if(in_array("28",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
            $check = CheckExaminer::where('user_id',auth()->user()->runrecno)->pluck('app_certi_lab_id'); // เช็คเจ้าหน้าที่ LAB
            if(count($check) > 0 ){
                $auditor= BoardAuditor::select('id','app_certi_lab_id','auditor')
                                 ->whereIn('step_id',[6])
                                 ->whereIn('app_certi_lab_id',$check)
                                 ->orderby('id','desc')
                                 ->get();
              if(count($auditor) > 0 ){
                foreach ($auditor as $item){
                  $app_no[$item->id] = $item->auditor . " ( ". @$item->applicant->app_no . " )";
                 }
               } 
             } 
       }else{
            $auditor = BoardAuditor::select('id','app_certi_lab_id','auditor')
                                       ->whereIn('step_id',[6])
                                      ->orderby('id','desc')
                                      ->get();
             if(count($auditor) > 0 ){
               foreach ($auditor as $item){
                    $app_no[$item->id] = $item->auditor . " ( ". @$item->applicant->app_no . " )";
               }
             }
       }

       $groups = BoardAuditor::find($board_auditor_id)->groups;
    
       $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id

       $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล

       foreach ($groups as $group) {
           $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
           $auditors = $group->auditors; // $auditors เป็น Collection

           // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
           if (!isset($statusAuditorMap[$statusAuditorId])) {
               $statusAuditorMap[$statusAuditorId] = [];
           }

           // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
           foreach ($auditors as $auditor) {
               $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
           }
       }

    //    dd($statusAuditorMap);
       
        return view('certify.save_assessment.create', compact('app','NoticeItem','app_no','board_auditor_id','find_notice','statusAuditorMap'));
    }


    public function store(Request $request)
    {

        // dd($request->all());
       
        $auditor = BoardAuditor::findOrFail($request->auditor_id);
        $notice = Notice::where('app_certi_assessment_id',$auditor->assessment_to->id)->first();
        // dd($notice);
        return $this->storeNoticeAndNoticeItem($request);
        // if($notice === null){
        //     return $this->storeNoticeAndNoticeItem($request);
        // }else{
        //     //update
        //     return $this->updateNotice($request);
        // }
    }
    public function storeNoticeAndNoticeItem($request)
    {
        $request->validate([
            'auditor_id' => 'required',
            'savedate' => 'required'
        ]);

       

        // dd($request->all());
    //  try {     
 
        $auditor = BoardAuditor::findOrFail($request->auditor_id);
        $n = Notice::where('app_certi_assessment_id',$auditor->assessment_to->id)->first();

        if(!is_null($auditor) && !empty($auditor->applicant)){
             $app =  $auditor->applicant;

            //  dd($app->check->id);

           
             $notices = $request->input('notice');
             $report = $request->input('report');
             $noks = $request->input('nok');
             $types = $request->input('type');
             $founds = $request->input('found');
             $id = null;
             if($n == null){
                $n = new Notice;
                if(!empty($auditor->assessment_to)){
                    $n->app_certi_assessment_id         = $auditor->assessment_to->id ?? null;
                    $group = AssessmentGroup::where('app_certi_assessment_id',$auditor->assessment_to->id)->where('app_certi_lab_id',$app->id)->first();
                    if(!is_null($group)){
                        $n->app_certi_assessment_group_id   = $group->id ?? null;
                    }
                }
            
                $n->app_certi_lab_id               = $app->id;
                $n->auditor_id                     = $auditor->id;
                $n->assessment_date                =  HP::convertDate($request->savedate,true) ?? null;   
                $n->draft                          = 1;
                $n->degree                         = $request->degree;
                $n->report_status                  = $request->report_status;
                $n->group                          = json_encode($request->auditors ?? []);
                $n->created_by                     =  auth()->user()->runrecno;
                
        
                    if($n->report_status == 1){
                        $n->step = 1; // มีข้อบกพร่อง    
                        $n->main_state = isset($request->main_state) ? 2 : 1;
                    }else{ 
                        $n->step = 3; // 
                        $n->date_record = date('Y-m-d');
                        $n->send_email =null;
                        $n->updated_by  =  auth()->user()->runrecno;
                        $n->status = 1;
                        $n->main_state =   1;
                    }
                

                $copiedScope = $this->copyScopeLabFromAttachement($app);
                if($copiedScope !== null){
                    $n->file_scope = $copiedScope;
                }

          

                $n->save();
                
             }

             $n = Notice::where('app_certi_assessment_id',$auditor->assessment_to->id)->first();

             
             if($n->report_status == 2){ // ไม่มีข้อบกพร่อง
                // if($request->file_scope  && $request->hasFile('file_scope')){    // รายงาน Scope
                //     foreach ($request->file_scope as $key => $itme) {
                //         if(!is_null($itme)){
                //             $list  = new  stdClass;
                //             $list->attachs =   $this->store_File($itme,$app->app_no) ;
                //             $list->attachs_client_name =  HP::ConvertCertifyFileName($itme->getClientOriginalName());
                //             $scope[] = $list;
                //         }
                //     }
                //     $n->file_scope = json_encode($scope);
                //  }

            
            }else{
                if($request->attachs  && $request->hasFile('attachs')){   //  ไฟล์แนบ
                    foreach ($request->attachs as $key => $itme) {
                        if(!is_null($itme)){
                            $list  = new  stdClass;
                            $list->attachs =   $this->store_File($itme,$app->app_no) ;
                            $list->attachs_client_name =  HP::ConvertCertifyFileName($itme->getClientOriginalName());
                            $attachs[] = $list;
                        }
                    }
                        $n->attachs = json_encode($attachs);
                }
                
            } 

            if(($n->degree == 1 || $n->degree == 8) && $n->report_status == 1){
                $n->submit_type = $request->submit_type;
                $nowTimeStamp = Carbon::now()->addDays(15)->timestamp;
                $encodedTimestamp = base64_encode($nowTimeStamp);
                $token = Str::random(30) . '_' . $encodedTimestamp;

                if($n->expert_token == null)
                {
                    $n->expert_token = $token;
                }
               

                if($request->submit_type == "confirm")
                {
                    $n->notice_confirm_date = Carbon::now()->addDays(1);
                }
                $n->save();
            }
             
             NoticeItem::where('app_certi_lab_notice_id',$n->id)->delete();

             if(isset($notices)){
                foreach ($notices as $key => $notice) {
                    if($notice != ''){
                        $item = new NoticeItem;
                        $item->app_certi_lab_notice_id = $n->id;
                        $item->remark = $notice;
                        $item->report = $report[$key];
                        $item->no = $noks[$key];
                        $item->type = $types[$key];
                        $item->reporter_id = $founds[$key];
                        $item->owner_id = auth()->user()->runrecno;
                        $item->save();
                    }
                }
            }

 
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
            


            if(($n->degree == 1 || $n->degree == 8) && $n->report_status == 1){
                
                $n->submit_type = $request->submit_type;

                $nowTimeStamp = Carbon::now()->addDays(15)->timestamp;
                $encodedTimestamp = base64_encode($nowTimeStamp);
                $token = Str::random(30) . '_' . $encodedTimestamp;
                // $n->expert_token = $token;

                if($n->expert_token == null)
                {
                    $n->expert_token = $token;
                }

                // $n->date_duration = Carbon::now()->addDays(91);

                if($request->submit_type == "confirm")
                {
                    $n->notice_confirm_date = Carbon::now()->addDays(1);
                }


                     $this->CertificateHistory($n);  
                   if($n->main_state == 1 ){
                        $auditor->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                        $auditor->save();
                         
                    }else{
                        $auditor->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                        $auditor->save();
                       // สถานะ แต่งตั้งคณะกรรมการ
                       $board_auditor = BoardAuditor::where('app_certi_lab_id',$app->id)
                                                ->whereIn('step_id',[9,10])
                                                ->whereNull('status_cancel')
                                                 ->get(); 
                        if(count($board_auditor) == count($app->certi_auditors_many)){
                            //  สรุปรายงานและเสนออนุกรรมการฯ
                            $json = $this->copyScopeLabFromAttachement($app);
                            $copiedScopes = json_decode($json, true);

                            $report = new Report; 
                            $report->app_certi_assessment_id = $n->app_certi_assessment_id;
                            $report->app_certi_lab_id        = $app->id;
                            $report->file_loa = $copiedScopes[0]['attachs'];
                            $report->file_loa_client_name = $copiedScopes[0]['file_client_name'];
                            $report->save();

                            // $app->review   = 1;
                            $app->status   = 20; 
                            $app->save(); 
                        }
                    }
                    
                    if(!is_null($app->email)){

                        $config = HP::getConfig();
                        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                        $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                        $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
            
                        $data_app =    [
                                        'certi_lab'     => $app,
                                        'data'          => $n ?? '-',
                                        'url'           => $url.'/certify/applicant',
                                        'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                        'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                        'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                                    ];
                    
                        $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $n->id,
                                                                    (new Notice)->getTable(),
                                                                    1,
                                                                    'นำส่งรายงานการตรวจประเมิน',
                                                                    view('mail.Lab.save_assessment', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                                    $app->email,
                                                                    !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                                    null
                                                                    );
            
                          $html = new  CertifySaveAssessment($data_app);
                          $mail = Mail::to($app->email)->send($html);
            
                          if(is_null($mail) && !empty($log_email)){
                            HP::getUpdateCertifyLogEmail($log_email->id);
                          }
                      }
            }else if($n->degree == 4){

                $auditor->step_id = 7; // ผ่านการตรวจสอบประเมิน
                $auditor->save();
                
                $this->LogNoticeConfirm($n);   

                if(!is_null($app->email)){

                    $config = HP::getConfig();
                    $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                    $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
                    $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                    $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
        
                    $data_app =  [
                                    'assessment'    => $n,
                                    'certi_lab'     => $app,
                                    'url'           =>$url.'/certify/applicant',
                                    'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                    'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                    'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                                ];

                    $data_app_to_examine =  [
                            'assessment'    => $n,
                            'certi_lab'     => $app,
                            'url'           =>$url_center.'certify/save_assessment',
                            'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                            'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                            'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                        ];            
                
                    $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                $app->id,
                                                                (new CertiLab)->getTable(),
                                                                $n->id,
                                                                (new Notice)->getTable(),
                                                                1,
                                                                'รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย',
                                                                view('mail.Lab.save_assessment_past', $data_app),
                                                                $app->created_by,
                                                                $app->agent_id,
                                                                auth()->user()->getKey(),
                                                                !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                                $app->email,
                                                                !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                                !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                                null
                                                                );
        
                      $html = new  CertifyConfirmAssessment($data_app);
                      $mail = Mail::to($app->email)->send($html);
                      if(is_null($mail) && !empty($log_email)){
                        HP::getUpdateCertifyLogEmail($log_email->id);
                      }

                      $userIds = $app->CheckExaminers->pluck('user_id')->toArray();
                      $examinerEmails = User::whereIn('runrecno',$userIds)->pluck('reg_email')->toArray();

                      if(count($examinerEmails) == 0 )
                      {
                        $examinerEmails = auth()->user()->reg_email;
                      }

                      $html2 = new CreateLabReport($data_app_to_examine);
                      $mail = Mail::to($examinerEmails)->send($html2);
                      
               }
            }



            $labReportInfo = LabReportInfo::where('app_certi_lab_notice_id',$n->id)->first();

            if($labReportInfo == null && $n->report_status == 1){
                $labReportInfo = new LabReportInfo();
                $labReportInfo->app_certi_lab_notice_id = $n->id;
                $labReportInfo->save();
            }
            

            $labReportTwoInfo = LabReportTwoInfo::where('app_certi_lab_notice_id',$n->id)->first();

            if($labReportTwoInfo == null && $n->report_status == 1){
                $labReportTwoInfo = new LabReportTwoInfo();
                $labReportTwoInfo->app_certi_lab_notice_id = $n->id;
                $labReportTwoInfo->save();
    
            }





            if($n->report_status == 1){
                $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)
                ->where('certificate_type',2)
                ->where('report_type',1)
                ->get(); 
                
                if($signAssessmentReportTransactions->count() == 0){
                    return redirect()->back();
                }else{
                    // return redirect('/certify/save_assessment/' . $n->id . '/assess_edit/' . $app->id)->with('flash_message', 'สร้างเรียบร้อยแล้ว');
                    return redirect('/certify/check_certificate/' . $app->check->id . '/show')->with('flash_message', 'สร้างเรียบร้อยแล้ว');
                }

            }else if($n->report_status == 2){

                // http://127.0.0.1:8081/certify/check_certificate/2027/show

                return redirect('/certify/check_certificate/' . $app->check->id . '/show')->with('flash_message', 'สร้างเรียบร้อยแล้ว');
    
            }


        }

        return redirect('certify/save_assessment')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');

    // } catch (\Exception $e) {
    //     return redirect('certify/save_assessment')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // } 


    }



    public function copyScopeLabFromAttachement($app)
    {
        $copiedScoped = null;
        $fileSection = null;

        if($app->lab_type == 3){
           $fileSection = "61";
        }else if($app->lab_type == 4){
           $fileSection = "62";
        }

        $latestRecord = CertiLabAttachAll::where('app_certi_lab_id', $app->id)
        ->where('file_section', $fileSection)
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();

        $existingFilePath = 'files/applicants/check_files/' . $latestRecord->file ;

        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files/'.$no.'/';

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



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dd('ok');
        $previousUrl = app('url')->previous();
        $notice =  Notice::find($id);
        $app = CertiLab::where('app_no',@$notice->applicant->app_no)->with('trader')->with('assessment.groups')->first();
        $Information = Information::where('app_certi_lab_id',@$app->id)->first();
        $app->name = @$Information->name ?? null;
        $app->assessment_date = HP::revertDate($notice->assessment_date->format('Y-m-d'),true) ?? null;
        $app->DataGroupeTitle = $notice->DataGroupeTitle ?? null;
        return view('certify.save_assessment.show', compact( 'notice',
                                                              'app',
                                                              'previousUrl'
                                                             ));
    }


    public function edit(Notice $notice, CertiLab $app = null)
    {
        // dd('ok');
        $notice->group = json_decode($notice->group);
        $auditors = BoardAuditor::select('id','no')->where('certi_no',@$notice->applicant->app_no)->groupBy('no')->orderby('id','desc')->pluck('no','id');
        if(count($auditors) == 0){
            $auditors = [];
        }
        $NoticeItem = NoticeItem::where('app_certi_lab_notice_id', $notice->id)->get();
        if(count($NoticeItem) == 0){
            $NoticeItem = [new NoticeItem];
        }
        $app = CertiLab::where('app_no',@$notice->applicant->app_no)->with('trader')->with('assessment.groups')->first();

        // dd('ok');
 
        return view('certify.save_assessment.edit', compact('notice', 'app','auditors','NoticeItem'));
    }

    public function assess_edit(Notice $notice, CertiLab $app = null)
    {
    
        $previousUrl = app('url')->previous();
        
        // $notice->group = json_decode($notice->group);
 
        $NoticeItem = NoticeItem::where('app_certi_lab_notice_id', $notice->id)->get();
        if(count($NoticeItem) == 0){
            $NoticeItem = [new NoticeItem];
        }
 
        $find_notice  = $notice;
        
        if(is_null($app)){
            $app =  $notice->applicant;
        }
        if(!empty($find_notice->board_auditor_to)){
            $app->app_no =  $find_notice->board_auditor_to->auditor.'('.$app->app_no.')';
        } 
 
        // dd($find_notice);
        $app->assessment_date = HP::revertDate($notice->assessment_date->format('Y-m-d'),true) ?? null;
        $app->DataGroupeTitle = $notice->DataGroupeTitle ?? null;
        $approveNoticeItems = NoticeItem::where('app_certi_lab_notice_id', $notice->id)
                        ->whereNotNull('attachs')
                        ->where('status',1)
                        ->where('file_status',1)
                        ->get();
        // dd($notice->assessment->id); 
        $report = Report::where('app_certi_assessment_id',$notice->assessment->id)->first();
        return view('certify/save_assessment/form_assess', compact('notice', 'app','NoticeItem','find_notice','previousUrl','approveNoticeItems','report'));
    }

    public function assess_update(Request $request, Notice $notice, CertiLab $app = null)
    {
        


        // dd($notice->id,$notice->degree,$request->all());
// try {   
        
        $config     = HP::getConfig();
        $url        =   !empty($config->url_acc) ? $config->url_acc : url('');
        $auditors   = $notice;

        if($auditors->degree != 5){  // ข้อบกพร่อง/ข้อสังเกต

            $ids = $request->input('id');
            if(isset($ids)){
                foreach ($ids as $key => $itme) {
                    $notice_itme = NoticeItem::where('id',$itme)->first();
                    if(!is_null($notice_itme)){ 
                        $notice_itme->status            = $request->status[$notice_itme->id] ??  @$notice_itme->status;
                        $notice_itme->file_status       = $request->file_status[$notice_itme->id] ??  @$notice_itme->file_status;
                        $notice_itme->comment           = $request->comment[$notice_itme->id] ?? @$notice_itme->comment;
                        $notice_itme->comment_file      = $request->comment_file[$notice_itme->id] ??  @$notice_itme->comment_file;
                        $notice_itme->cause      = $request->cause[$notice_itme->id] ??  @$notice_itme->cause;
                        $notice_itme->save(); 
                    }
                  } 

                //   if($request->hasFile('file_car')){
                if($request->assessment_passed == 1){
                        $auditors->main_state = 1;
                        $auditors->degree = 4;
                        $auditors->date_car = date("Y-m-d"); // วันที่ปิด Car
                        $auditors->report_status = 2; 
                }else{
                    if(isset($request->main_state)){
                        $auditors->main_state =  2 ;
                        $auditors->degree = 8;
                    }else{
                        $auditors->main_state = 1;
                        $auditors->degree = 3;
                    }
                }

                $auditors->date_record =  date('Y-m-d');
            // ไฟล์แนบ
            if($request->attachs && $request->hasFile('attachs')){
                foreach ($request->attachs as $index => $item){
                        $data_file = [];
                        if(!is_null($auditors->attachs)){
                            $data_file = array_values((array)json_decode($auditors->attachs));
                        }
                        foreach ($request->attachs as $key => $itme) {
                            $list  = new  stdClass;
                            $list->attachs =   $this->store_File($itme,$app->app_no) ;
                            $list->attachs_client_name =  HP::ConvertCertifyFileName($itme->getClientOriginalName());
                            $data_file[] = $list;
                        }
                            $attachs = json_encode(array_values($data_file)); 
                }
                $auditors->attachs =   isset($attachs) ? $attachs : @$auditors->attachs;
            }

           // รายงาน Car
            // if($request->file_car && $request->hasFile('file_car')){
            // if($request->assessment_passed == 1){
            //     $auditors->date_car              =  date('Y-m-d') ;
            //     // $auditors->file_car              =   $this->store_File($request->file_car,$app->app_no) ;
            //     // $auditors->file_car_client_name =  HP::ConvertCertifyFileName($request->file_car->getClientOriginalName()) ;
            // }

            if($request->file_scope  && $request->hasFile('file_scope')){    // รายงาน Scope
                foreach ($request->file_scope as $key => $itme) {
                    if(!is_null($itme)){
                        $list                       = new  stdClass;
                        $list->attachs              = $this->store_File($itme,$app->app_no) ;
                        $list->attachs_client_name  = HP::ConvertCertifyFileName($itme->getClientOriginalName());
                        $scope[]                    = $list;
                    }
                    $auditors->file_scope =   isset($scope)   ?  json_encode($scope)  :  null;
                }
             }


                $auditors->save();
                //log
                $this->CertificateHistory($auditors);
                    // สถานะ แต่งตั้งคณะกรรมการ
                    $committee = BoardAuditor::findOrFail($auditors->auditor_id);
                if($auditors->degree == 3){

                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();

                    //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
                    if(!is_null($app->email)){
 
                        $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                        $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
            
                        $data_app =  [
                                    'assessment'        =>  $auditors,
                                    'certi_lab'         =>  $app,
                                    'url'               =>  $url.'/certify/applicant',
                                    'email'             =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                    'email_cc'          =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                    'email_reply'       =>  !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                                    ];
                    
                        $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $auditors->id,
                                                                    (new Notice)->getTable(),
                                                                    1,
                                                                    'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                                    view('mail.Lab.check_save_assessment', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                                    $app->email,
                                                                    !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                                    null
                                                                    );
            
                          $html = new  CertifyCheckSaveAssessment($data_app);
                          $mail = Mail::to($app->email)->send($html); 
                          if(is_null($mail) && !empty($log_email)){
                            HP::getUpdateCertifyLogEmail($log_email->id);
                          }


                     }
                
                    
                }elseif($auditors->degree == 4){

                     $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                     $committee->save();
                     //  Log
                     $this->LogNoticeConfirm($auditors);                
                      //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
                      if(!is_null($app->email)){
                        $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                        $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
            
                        $data_app =  [
                                        'assessment'    => $auditors,
                                        'certi_lab'     => $app,
                                        'url'           => $url.'/certify/applicant',
                                        'email'         => !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                        'email_cc'      => !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                        'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                                    ];
                    
                        $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $auditors->id,
                                                                    (new Notice)->getTable(),
                                                                    1,
                                                                    'รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย',
                                                                    view('mail.Lab.save_assessment_past', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                                    $app->email,
                                                                    !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                                    null
                                                                    );
            
                          $html = new  CertifyConfirmAssessment($data_app);
                         $mail = Mail::to($app->email)->send($html);
                          if(is_null($mail) && !empty($log_email)){
                            HP::getUpdateCertifyLogEmail($log_email->id);
                          }
                     }
                
               
                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();
        
                    $board_auditor = BoardAuditor::where('app_certi_lab_id',$app->id)
                                                    ->whereIn('step_id',[9,10])
                                                    ->whereNull('status_cancel')
                                                    ->get(); 
                    if(count($board_auditor) == count($app->certi_auditors_many)){
                        //  สรุปรายงานและเสนออนุกรรมการฯ
                        $json = $this->copyScopeLabFromAttachement($app);
                        $copiedScopes = json_decode($json, true);
                        $report = new Report; 
                        $report->app_certi_assessment_id = $auditors->app_certi_assessment_id;
                        $report->app_certi_lab_id        = $app->id;
                        $report->file_loa = $copiedScopes[0]['attachs'];
                        $report->file_loa_client_name = $copiedScopes[0]['file_client_name'];
                        $report->save();

                        // $app->review   = 1;
                        $app->status   = 20; 
                        $app->save(); 
                    }
                }

            }

        }else{
                 // รายงาน Scope
                // if($request->file_scope  && $request->hasFile('file_scope')){
                //     foreach ($request->file_scope as $index => $item){
                    
                //             $list                       = new  stdClass;
                //             $list->attachs              = $this->store_File($item,$app->app_no) ;
                //             $list->attachs_client_name  = HP::ConvertCertifyFileName($item->getClientOriginalName());
                //             $scope[]                    = $list;
                //     }
                //     $auditors->file_scope =   isset($scope)   ?  json_encode($scope)  :  null;
                // }

                $copiedScope = $this->copyScopeLabFromAttachement($app);
                if($copiedScope !== null){
                   $auditors->file_scope = $copiedScope;
                }
                
                $auditors->date_record = date('Y-m-d');
                $auditors->send_email = null;
                $auditors->degree = 4;
                $auditors->save();
                 // สถานะ แต่งตั้งคณะกรรมการ
                 $committee = BoardAuditor::findOrFail($auditors->auditor_id); 
                 if(!is_null($committee)){
                     $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                     $committee->save();
                 }    
     
                $this->LogNoticeConfirm($auditors);  
            if(!is_null($app->email)){
                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
    
                $data_app =  [
                                'assessment'    => $auditors,
                                'certi_lab'     => $app->applicant,
                                'url'           => $url.'/certify/applicant',
                                'email'         => !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                            ];
            
                $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                            $app->id,
                                                            (new CertiLab)->getTable(),
                                                            $auditors->id,
                                                            (new Notice)->getTable(),
                                                            1,
                                                            'รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย',
                                                            view('mail.Lab.save_assessment_past', $data_app),
                                                            $app->created_by,
                                                            $app->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                            $app->email,
                                                            !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                            !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                            null
                                                            );
    
                  $html = new  CertifyConfirmAssessment($data_app);
                   $mail = Mail::to($app->email)->send($html);
                  if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                  }

            }
 
        }


                             
        return redirect('certify/save_assessment')->with('flash_message', 'เรียบร้อยแล้ว!');

    // } catch (\Exception $e) {
    //     return redirect('certify/save_assessment')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // } 



    }

    public function assessupdate(Request $request, Notice $notice, CertiLab $app = null)
    {
        
        $Notice = Notice::where('id',$request->find_notice)->first();
        if(!is_null($Notice)){
            $Notice->update(['status'=>$request->status,
                             'remark'=> $request->remark  ?? null
            ]);
           
             $app = CertiLab::where('app_no',@$Notice->applicant->app_no)->first();
            if($Notice->status == 3){
                if(!is_null($app)){
                    $app->update(['status'=>18]);  // update สถานะ
                }
            }

                $json = $this->copyScopeLabFromAttachement($app);
                $copiedScopes = json_decode($json, true);
                // dd($copiedScopes[0]);
                  //  สรุปรายงานและเสนออนุกรรมการฯ
                  $report = new Report; 
                  $report->app_certi_assessment_id = $app->assessment->id ?? null;
                  $report->app_certi_lab_id = $app->id;
                  $report->file_loa = $copiedScopes[0]['attachs'];
                  $report->file_loa_client_name = $copiedScopes[0]['file_client_name'];
                  $report->save();
        }
        return redirect('certify/save_assessment')->with('message', 'เรียบร้อยแล้ว!');
    } 
    public function update(Request $request, Notice $notice, CertiLab $app = null)
    {
       
        // dd($request->all());
        // $request->validate([
        //     'app_no' => 'required',
        //     'group_id' => 'required',
        //     'savedate' => 'required',
        //     'report_status' => 'required|in:1,2',
        //     'draft' => 'required|in:1,0',
        // ]);
  try {   
        $redirectWithApp = true;
        if ($app == null) {
            $redirectWithApp = false;
        }

        $app_no = $request->input('app_no');
        $formApp = CertiLab::where('app_no', $app_no)->first();

        $group = AssessmentGroup::find($request->input('group_id'));

 

        $notices = $request->input('notice');
        $report = $request->input('report');
        $noks = $request->input('nok');
        $types = $request->input('type'); 
        $founds = $request->input('found');
        $draft = $request->input('draft');

        $notice->app_certi_assessment_id = $formApp->assessment->id ?? null;
        $notice->app_certi_lab_id = $formApp->id;
        $notice->app_certi_assessment_group_id = $group->id;
        $notice->assessment_date = $request->savedate?Carbon::createFromFormat("d/m/Y",$request->savedate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
        $notice->draft = $draft;
        $notice->report_status = $request->report_status;
        $notice->group = json_encode($request->auditors ?? []);

        if($request->file && $request->hasFile('file')){   // รายงานการตรวจประเมิน
            $notice->file =    $this->store_File($request->file,$formApp->app_no)   ??  null ;
            $notice->file_client_name =   HP::ConvertCertifyFileName($request->file->getClientOriginalName());
        }


        if($request->report_status == 2){ // ไม่มีข้อบกพร่อง
            // if($request->file_scope && $request->hasFile('file_scope')){    // รายงาน Scope
            //     foreach ($request->file_scope as $key => $itme) {
            //         if(!is_null($itme)){
            //             $list  = new  stdClass;
            //             $list->attachs =   $this->store_File($itme,$formApp->app_no) ;
            //             $list->attachs_client_name =   HP::ConvertCertifyFileName($itme->getClientOriginalName());
            //             $scope[] = $list;
            //         }
            //     }
            //     $notice->file_scope = json_encode($scope);
            // }

            $copiedScope = $this->copyScopeLabFromAttachement($formApp);
            if($copiedScope !== null){
            $notice->file_scope = $copiedScope;
            }

        }else{
            if($request->attachs && $request->hasFile('attachs')){   //  ไฟล์แนบ
                foreach ($request->attachs as $key => $itme) {
                    if(!is_null($itme)){
                        $list  = new  stdClass;
                        $list->attachs =   $this->store_File($itme,$formApp->app_no) ;
                        $list->attachs_client_name =   HP::ConvertCertifyFileName($itme->getClientOriginalName());
                        $attachs[] = $list;
                    }
                }
            $notice->attachs = json_encode($attachs);
            }
        }
    // try {
        if($notice->draft == 1){
            if($notice->report_status == 1){
                $notice->step = 1; // มีข้อบกพร่อง    
                 $formApp->update(['status'=>19]);  // update สถานะ
            }else{
                $notice->status = 2; 
                $notice->step = 3; // 
                $notice->date_record = date('Y-m-d');
                $notice->send_email =null;
                $notice->updated_by = auth()->user()->runrecno; 

                $formApp->update(['status'=>18]);  // update สถาน
                
                $json = $this->copyScopeLabFromAttachement($formApp);
                $copiedScopes = json_decode($json, true);
                //  สรุปรายงานและเสนออนุกรรมการฯ
                $report = new Report; 
                $report->app_certi_assessment_id = $formApp->assessment->id ?? null;
                $report->app_certi_lab_id = $formApp->id;
                $report->file_loa = $copiedScopes[0]['attachs'];
                $report->file_loa_client_name = $copiedScopes[0]['file_client_name'];
                $report->save();
             }
         } 
 

        $notice->save();

           if(isset($notices)){
     
                //ลบที่ถูกกดลบ
                $notices_id = array_diff($request->id, [null]);
                NoticeItem::where('app_certi_lab_notice_id', $notice->id)
                            ->when($notices_id, function ($query, $notices_id){
                                return $query->whereNotIn('id', $notices_id);
                            })->delete();
                foreach ($notices as $key => $itme) {
                    if($itme != ''){
                        $notice_itme = NoticeItem::where('id',$request->id[$key])->first();
                        $attachs = !empty($notice_itme->attachs) ?  $notice_itme->attachs : null ;
                        if(is_null($notice_itme)){
                            $notice_itme = new NoticeItem;
                            $attachs = null;
                        }
    
                        $notice_itme->app_certi_lab_notice_id = $notice->id;
                        $notice_itme->remark = $itme;
                        $notice_itme->report = $report[$key];
                        $notice_itme->no = $noks[$key];
                        $notice_itme->type = $types[$key];
                        $notice_itme->reporter_id = $founds[$key];
                        $notice_itme->owner_id = auth()->user()->runrecno;
                        $notice_itme->save(); 
                    }
                }
            }
          
            $config   = HP::getConfig();
            $url      = !empty($config->url_acc) ? $config->url_acc : url('');
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($formApp->subgroup,$dataMail)  ? $dataMail[$formApp->subgroup] :'admin@admin.com';


            if(!is_null($formApp->email)  && $notice->draft == 1){
                //  ประวัติ
                $this->CertificateHistory($notice);
                $data_app =  [
                            'certi_lab'     => $formApp,
                            'data'          => $notice  ,
                            'url'           => $url.'/certify/applicant',
                            'email'         =>  !empty($formApp->DataEmailCertifyCenter) ? $formApp->DataEmailCertifyCenter : $EMail,
                            'email_cc'      =>  !empty($formApp->DataEmailDirectorLABCC) ? $formApp->DataEmailDirectorLABCC :  $EMail,
                            'email_reply'   => !empty($formApp->DataEmailDirectorLABReply) ? $formApp->DataEmailDirectorLABReply :  $EMail
                            ];
            
                $log_email =  HP::getInsertCertifyLogEmail( $formApp->app_no,
                                                            $formApp->id,
                                                            (new CertiLab)->getTable(),
                                                            $notice->id,
                                                            (new Notice)->getTable(),
                                                            1,
                                                            'นำส่งรายงานการตรวจประเมิน',
                                                            view('mail.Lab.save_assessment', $data_app),
                                                            $formApp->created_by,
                                                            $formApp->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($formApp->DataEmailCertifyCenter) ?  implode(',',(array)$formApp->DataEmailCertifyCenter)  : $EMail,
                                                            $formApp->email,
                                                            !empty($formApp->DataEmailDirectorLABCC) ? implode(',',(array)$formApp->DataEmailDirectorLABCC)   :  $EMail,
                                                            !empty($formApp->DataEmailDirectorLABReply) ?implode(',',(array)$formApp->DataEmailDirectorLABReply)   :  $EMail,
                                                            null
                                                            );
    
                  $html = new  CertifySaveAssessment($data_app);
                  $mail = Mail::to($formApp->email)->send($html);
                  if(is_null($mail) && !empty($log_email)){
                      HP::getUpdateCertifyLogEmail($log_email->id);
                  }
 

            }else{
                   $this->LogNoticeConfirm($notice);   
                   
                 if(!is_null($formApp->email)){
                        $data_app =  [
                                        'assessment'    => $notice,
                                        'certi_lab'     => $formApp,
                                        'url'           => $url.'/certify/applicant',
                                        'email'         => !empty($formApp->DataEmailCertifyCenter) ? $formApp->DataEmailCertifyCenter : $EMail,
                                        'email_cc'      =>  !empty($formApp->DataEmailDirectorLABCC) ? $formApp->DataEmailDirectorLABCC :  $EMail,
                                        'email_reply'   => !empty($formApp->DataEmailDirectorLABReply) ? $formApp->DataEmailDirectorLABReply :  $EMail
                                    ];
                    
                        $log_email =  HP::getInsertCertifyLogEmail( $formApp->app_no,
                                                                    $formApp->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $notice->id,
                                                                    (new Notice)->getTable(),
                                                                    1,
                                                                    'รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย',
                                                                    view('mail.Lab.save_assessment_past', $data_app),
                                                                    $formApp->created_by,
                                                                    $formApp->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    !empty($formApp->DataEmailCertifyCenter) ?  implode(',',(array)$formApp->DataEmailCertifyCenter)  : $EMail,
                                                                    $formApp->email,
                                                                    !empty($formApp->DataEmailDirectorLABCC) ? implode(',',(array)$formApp->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($formApp->DataEmailDirectorLABReply) ?implode(',',(array)$formApp->DataEmailDirectorLABReply)   :  $EMail,
                                                                    null
                                                                    );
            
                        $html = new  CertifyConfirmAssessment($data_app);
                       $mail = Mail::to($formApp->email)->send($html);
                        if(is_null($mail) && !empty($log_email)){
                            HP::getUpdateCertifyLogEmail($log_email->id);
                        }
 
                }
               
              }
         
            return redirect('certify/save_assessment')->with('flash_message', 'เรียบร้อยแล้ว!');

        } catch (\Exception $e) {
            return redirect('certify/save_assessment')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        } 

    }


    public function destroy(Notice $notice, CertiLab $app = null)
    {
        try {
            $notice->items()->delete();
            $notice->delete();

            return redirect()->route('save_assessment.index', ['app' => $app ? $app->id : ''])->with('flash_message', 'ลบเรียบร้อยแล้ว');
        } catch (Exception $x) {
            return back();
        }
    }

    public function destroyMultiple(Request $request, CertiLab $app = null)
    {
        foreach ($request->cb as $id) {
            $notice = Notice::findOrFail($id);
            $notice->items()->delete();
            $notice->delete();
        }

        return redirect()->route('save_assessment.index', ['app' => $app ? $app->id : ''])->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function apiGetApp($id) {
       
       
        $board_auditor = BoardAuditor::where('id', $id)->groupBy('no')->orderby('id','desc')->first();

        if(!is_null($board_auditor)){
            // dd($board_auditor->applicant->check->id);
            return response()->json([
                                'group_id' => $board_auditor->assessment_to->id ?? '',
                                'app'   => $board_auditor->applicant,
                                'check'   => $board_auditor->applicant->check,
                                'created_at' => !empty($board_auditor->created_at) ?  HP::revertDate($board_auditor->created_at->format('Y-m-d'),true) : null  ?? '',
                                'message' =>true
                          ], 200); 
        }else{
             return response()->json([  'message' => false   ], 200); 
        }
 
 
    }

    public function apiRequestEditScope(Request $request)
    {
        $appId = $request->app_id;
        $noticId = $request->notice_id;
        $message = $request->message;

        // dd($appId,$noticId,$message);

        if($noticId != null){
            $notice = Notice::find($noticId);
        }else{
            $notice = Notice::where('app_certi_lab_id',$appId)->where('status','!=',2)->first();
        }

        if($notice != null){
            $noticId = $notice->id;
        }
       
        
        CertiLab::find($appId)->update([
            'require_scope_update' => "1"
        ]);
        $app = CertiLab::find($appId);
        $ao = new CertiLab;
        $history = CertificateHistory::create([
            'app_no'=> $app->app_no ?? null,
            'system'=> isset($system) ? $system : null,
            'table_name'=> $ao->getTable(),
            'status'=> $app->status ?? null,
            'ref_id'=> $app->id,
            'details'=> $message,
            'attachs'=> null,
            'created_by' =>  auth()->user()->runrecno
            ]);

        
            $config   = HP::getConfig();
            $url      = !empty($config->url_acc) ? $config->url_acc : url('');
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
            if(!is_null($app->email)){
                        $data_app =  [
                                        'certi_lab'     => $app,
                                        'request_message'     => $message,
                                        'url'           => $url.'/certify/applicant',
                                        'email'         => !empty($formApp->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                        'email_cc'      =>  !empty($formApp->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                        'email_reply'   => !empty($formApp->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                                    ];
                                    
                        $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                    $app->id,
                                                                    (new CertiLab)->getTable(),
                                                                    $noticId,
                                                                    (new Notice)->getTable(),
                                                                    1,
                                                                    'ขอให้แก้ไขขอบข่าย',
                                                                    view('mail.Lab.mail_request_edit_scope', $data_app),
                                                                    $app->created_by,
                                                                    $app->agent_id,
                                                                    auth()->user()->getKey(),
                                                                    !empty($formApp->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                                    $app->email,
                                                                    !empty($formApp->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                                    !empty($formApp->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                                    null
                                                                    );
            
                        $html = new  RequestEditScope($data_app);
                       $mail = Mail::to($app->email)->send($html);
                        if(is_null($mail) && !empty($log_email)){
                            HP::getUpdateCertifyLogEmail($log_email->id);
                        }
 
                }    

            return response()->json([
                'history' => $history
          ], 200);   
    }


    public function apiRequestEditScopeFromTracking(Request $request)
    {
        $appId = $request->app_id;
        CertiLab::find($appId)->update([
            'require_scope_update' => "1"
        ]);
    }
    public function updateStatus(Request $request) {
        $notice = Notice::find($request->notice_id);
        if ($notice) {
            try {
                $notice->status = $request->status;
                $notice->save();
                return back()->with('flash_message', 'อัพเดทสถานะเรียบร้อยแล้ว');
            } catch (Exception $x) {
                return back();
            }
        }

        return back();
    }
        // สำหรับเพิ่มรูปไปที่ store 
        public function store_File($files, $app_no = 'files_lab',$name =null)
        {
            $no  = str_replace("RQ-","",$app_no);
            $no  = str_replace("-","_",$no);
            if ($files) {
                $attach_path  =  $this->attach_path.$no;
                $file_extension = $files->getClientOriginalExtension();
                $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
                $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
                $fullFileName = str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
    
                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $no.'/'.$storageName;
            }else{
                return null;
            }
    
        }

        public function CertificateHistory($data)
        {
            $ao = new Notice;

            $Notice = Notice::select('assessment_date','step','evidence','draft','status','report_status','group','desc','date_record','updated_by','remark','file_client_name')->where('id',$data->id)->first();
            $NoticeItem = NoticeItem::select('remark','no','cause','type','reporter','reporter_id','status','report','comment','comment_file','attachs','file_status','details','attachs_client_name')->where('app_certi_lab_notice_id',$data->id)->get()->toArray();
            CertificateHistory::create([
                                        'app_no'                => $data->applicant->app_no ?? null,
                                        'system'                => 4,
                                        'table_name'            => $ao->getTable(),
                                        'ref_id'                => $data->id,
                                        'details'               =>  !is_null($Notice) ? json_encode($Notice) : null,
                                        'details_table'         =>  (count($NoticeItem) > 0) ? json_encode($NoticeItem) : null,
                                        'file'                  =>  $data->file ?? null, // รายงานการตรวจประเมิน 
                                        'file_client_name'      =>  $data->file_client_name ?? null, 
                                        'attachs'               =>  $data->attachs ?? null,  //  ไฟล์แนบ
                                        'created_by'            =>  auth()->user()->runrecno
                                      ]);

        }

        
        public function LogNoticeConfirm($data)
        {
            $ao = new Notice;

            $Notice = Notice::select('assessment_date','step','evidence','draft','status','report_status','group','desc','date_record','updated_by','remark')->where('id',$data->id)->first();
            CertificateHistory::create([
                                        'app_no'                =>  $data->applicant->app_no ?? null,
                                        'system'                =>  11,
                                        'table_name'            =>  $ao->getTable(),
                                        'ref_id'                =>  $data->id,
                                        'details'               =>  !is_null($Notice) ? json_encode($Notice) : null,
                                        'file'                  =>  $data->file ?? null, // รายงานการตรวจประเมิน 
                                        'file_client_name'      =>  $data->file_client_name ?? null, 
                                        'attachs'               => $data->attachs ?? null, //  ไฟล์แนบ
                                        'details_table'         => $data->file_scope ?? null, // รายงาน Scope
                                        'details_date'          => $data->file_car ?? null, // ปิด Car 
                                        'attach_client_name'    => $data->file_car_client_name ?? null, // ปิด Car 
                                        'created_by'            =>  auth()->user()->runrecno
                                      ]);

        }
        
        public function RemoveFile($id) {
            $notice =  Notice::find($id);
            $status = '';
            if(!is_null($notice)){
                $notice->update(['file'=> null]);
                $status = 'true';
            }else{
                $status = 'false';
            }
            return response()->json([
                                    'status' => $status,
                                 ]); 
        }
        
        public function RemoveAttachs($id,$keys) {
            $notice =  Notice::find($id);
            $status = '';
            if(!is_null($notice) && !is_null($notice->attachs)){
                $attachs = array_values((array)json_decode($notice->attachs));
                unset($attachs[$keys]);
                $notice->update(['attachs'=>  json_encode(array_values($attachs))]);
                $status = 'true';
            }else{
                $status = 'false';
            }
            return response()->json([
                                    'status' => $status,
                                 ]); 
        }

        public function RemoveFileScope($id) {
            $notice =  Notice::find($id);
            $status = '';
            if(!is_null($notice)){
                $notice->update(['file_scope'=> null]);
                $status = 'true';
            }else{
                $status = 'false';
            }
            return response()->json([
                                    'status' => $status,
                                 ]); 
        }
        
        public function RemoveAttachsCar($id,$keys) {
            $notice =  Notice::find($id);
            $status = '';
            if(!is_null($notice) && !is_null($notice->file_car)){
                $file_car = array_values((array)json_decode($notice->file_car));
                unset($file_car[$keys]);
                $notice->update(['attachs'=>  json_encode(array_values($file_car))]);
                $status = 'true';
            }else{
                $status = 'false';
            }
            return response()->json([
                                    'status' => $status,
                                 ]); 
        }

        public function createLabInfo($notice_id)
        {
            // http://127.0.0.1:8081/certify/save_assessment/create-lab-info/1375
            // สำหรับ admin และเจ้าหน้าที่ lab
          //   if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
          //       abort(403);
          //   }
    
            // $id = 1767;
            $notice = Notice::find($notice_id);
            $assessment = $notice->assessment;
            $app_certi_lab = $notice->applicant;
            $boardAuditor = $assessment->board_auditor_to;
            $id = $boardAuditor->auditor_id;
            
            // dd($notice,$assessment,$boardAuditor,$app_certi_lab);
    
            $groups = $boardAuditor->groups;
    
            $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id
    
            $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
    
            foreach ($groups as $group) {
                $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
                $auditors = $group->auditors; // $auditors เป็น Collection
    
                // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
                if (!isset($statusAuditorMap[$statusAuditorId])) {
                    $statusAuditorMap[$statusAuditorId] = [];
                }
    
                // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
                foreach ($auditors as $auditor) {
                    $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
                }
            }
    
            $uniqueAuditorIds = array_unique($auditorIds);
    
            $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();
    
            $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);
    
            $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
            $dateRange = "";
    
            if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
                if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                    // ถ้าเป็นวันเดียวกัน
                    $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
                } else {
                    // ถ้าเป็นคนละวัน
                    $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                                " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
                }
            }
    
            $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();
            $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
            // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
            if ($boardAuditorExpert && $boardAuditorExpert->expert) {
                // แปลงข้อมูล JSON ใน expert กลับเป็น array
                $categories = json_decode($boardAuditorExpert->expert, true);
            
                // ถ้ามีหลายรายการ
                if (count($categories) > 1) {
                    // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
                    $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
                    $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
                } elseif (count($categories) == 1) {
                    // ถ้ามีแค่รายการเดียว
                    $experts = $categories[0];
                } else {
                    $experts = ''; // ถ้าไม่มีข้อมูล
                }
            
            }
    
            $scope_branch = "";
            if ($certi_lab->lab_type == 3){
                $scope_branch = $certi_lab->BranchTitle;
            }else if($certi_lab->lab_type == 4)
            {
                $scope_branch = $certi_lab->ClibrateBranchTitle;
            }
    
            $data = new stdClass();
    
            $data->header_text1 = '';
            $data->header_text2 = '';
            $data->header_text3 = '';
            $data->header_text4 = $certi_lab->app_no;
            $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
            $data->lab_name = $certi_lab->lab_name;
            $data->scope_branch = $scope_branch;
            $data->app_np = 'ทดสอบ ๑๖๗๑';
            $data->certificate_no = '13-LB0037';
            $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
            $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
            $data->experts = $experts;
            $data->date_range = $dateRange;
            $data->statusAuditorMap = $statusAuditorMap;
    
            $notice = Notice::find($notice_id);
            $assessment = $notice->assessment; 

            
            // dd($statusAuditorMap);
            $app_certi_lab = $notice->applicant;
            $boardAuditor = $assessment->board_auditor_to;
            $id = $boardAuditor->auditor_id;
            $labRequest = null;
            
            if($app_certi_lab->lab_type == 4){
                $labRequest = LabCalRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            }else if($app_certi_lab->lab_type == 3)
            {
                $labRequest = LabTestRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            }

            return view('certify.save_assessment.report', [
                'data' => $data,
                'notice' => $notice,
                'assessment' => $assessment,
                'boardAuditor' => $boardAuditor,
                'certi_lab' => $app_certi_lab,
                'labRequest' => $labRequest,
                'id' => $id
            ]);
        }
  

        public function saveLabInfo(Request $request)
        {
            // dd('add new');
            // รับค่าจาก JSON
            $data = $request->input('data'); // ข้อมูลใน key "data"
            $persons = $request->input('persons'); // ข้อมูลใน key "data"
            $noticeId = $request->input('notice_id');
            $signers = $request->input('signer', []);

            // dd($signers);
            
            $inp_2_2_assessment_on_site = $data[0]['2_2_assessment_on_site'];
            $inp_2_2_assessment_at_tisi = $data[0]['2_2_assessment_at_tisi'];
            $inp_2_2_remote_assessment = $data[0]['2_2_remote_assessment'];
            $inp_2_2_self_declaration = $data[0]['2_2_self_declaration'];

            $inp_2_5_1_structure_compliance = $data[1]['2_5_1_structure_compliance']['value'];
            $inp_2_5_1_central_management_yes = $data[1]['2_5_1_structure_compliance']['2_5_1_central_management_yes'];
            $inp_2_5_1_central_management_no = $data[1]['2_5_1_structure_compliance']['2_5_1_central_management_no'];;
            $inp_2_5_1_quality_policy_yes = $data[1]['2_5_1_structure_compliance']['2_5_1_quality_policy_yes'];;
            $inp_2_5_1_quality_policy_no = $data[1]['2_5_1_structure_compliance']['2_5_1_quality_policy_no'];;
            $inp_2_5_1_risk_assessment_yes = $data[1]['2_5_1_structure_compliance']['2_5_1_risk_assessment_yes'];;
            $inp_2_5_1_risk_assessment_no = $data[1]['2_5_1_structure_compliance']['2_5_1_risk_assessment_no'];;
            $inp_2_5_1_other = $data[1]['2_5_1_structure_compliance']['2_5_1_other']['value'];
            $inp_2_5_1_text_other1 = $data[1]['2_5_1_structure_compliance']['2_5_1_other']['2_5_1_text_other1'];
            $inp_2_5_1_text_other2 = $data[1]['2_5_1_structure_compliance']['2_5_1_other']['2_5_1_text_other2'];
            $inp_2_5_1_issue_found = $data[1]['2_5_1_issue_found']['value'];
            $inp_2_5_1_detail = json_encode($data[1]['2_5_1_issue_found']['2_5_1_detail']);

            $inp_2_5_2_structure_compliance = $data[2]['2_5_2_structure_compliance']['value'];
            $inp_2_5_2_lab_management = $data[2]['2_5_2_structure_compliance']['2_5_2_lab_management']['value'];
            $inp_2_5_2_lab_management_details = $data[2]['2_5_2_structure_compliance']['2_5_2_lab_management']['2_5_2_lab_management_details'];
            $inp_2_5_2_staff_assignment_yes = $data[2]['2_5_2_structure_compliance']['2_5_2_staff_assignment_yes'];
            $inp_2_5_2_staff_assignment_no =$data[2]['2_5_2_structure_compliance']['2_5_2_staff_assignment_no'];
            $inp_2_5_2_responsibility_yes = $data[2]['2_5_2_structure_compliance']['2_5_2_responsibility_yes'];
            $inp_2_5_2_responsibility_no = $data[2]['2_5_2_structure_compliance']['2_5_2_responsibility_no'];
            $inp_2_5_2_other = $data[2]['2_5_2_structure_compliance']['2_5_2_other']['value'];
            $inp_2_5_2_text_other1 = $data[2]['2_5_2_structure_compliance']['2_5_2_other']['2_5_2_text_other1'];
            $inp_2_5_2_text_other2 = $data[2]['2_5_2_structure_compliance']['2_5_2_other']['2_5_2_text_other2'];
            $inp_2_5_2_issue_found = $data[2]['2_5_2_issue_found']['value'];
            $inp_2_5_2_detail =  json_encode($data[2]['2_5_2_issue_found']['2_5_2_detail']);

            // dd($data[1]['2_5_1_issue_found']['2_5_1_detail'],$data[2]['2_5_2_issue_found']['2_5_2_detail']);

            $inp_2_5_3_structure_compliance = $data[3]['2_5_3_structure_compliance']['value'];
            $inp_2_5_3_personnel_qualification_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_personnel_qualification_yes'];
            $inp_2_5_3_personnel_qualification_no = $data[3]['2_5_3_structure_compliance']['2_5_3_personnel_qualification_no'];
            $inp_2_5_3_assign_personnel_appropriately_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_assign_personnel_appropriately_yes'];
            $inp_2_5_3_assign_personnel_appropriately_no = $data[3]['2_5_3_structure_compliance']['2_5_3_assign_personnel_appropriately_no'];
            $inp_2_5_3_training_need_assessment_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_training_need_assessment_yes'];
            $inp_2_5_3_training_need_assessment_no = $data[3]['2_5_3_structure_compliance']['2_5_3_training_need_assessment_no'];
            $inp_2_5_3_facility_and_environment_control_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_facility_and_environment_control_yes'];
            $inp_2_5_3_facility_and_environment_control_no = $data[3]['2_5_3_structure_compliance']['2_5_3_facility_and_environment_control_no'];
            $inp_2_5_3_equipment_maintenance_calibration_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_equipment_maintenance_calibration_yes'];
            $inp_2_5_3_equipment_maintenance_calibration_no = $data[3]['2_5_3_structure_compliance']['2_5_3_equipment_maintenance_calibration_no'];
            $inp_2_5_3_metrology_traceability_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_metrology_traceability_yes'];
            $inp_2_5_3_metrology_traceability_no = $data[3]['2_5_3_structure_compliance']['2_5_3_metrology_traceability_no'];
            $inp_2_5_3_external_product_service_control_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_external_product_service_control_yes'];
            $inp_2_5_3_external_product_service_control_no = $data[3]['2_5_3_structure_compliance']['2_5_3_external_product_service_control_no'];
            $inp_2_5_3_other = $data[3]['2_5_3_structure_compliance']['2_5_3_other']['value'];
            $inp_2_5_3_text_other1 = $data[3]['2_5_3_structure_compliance']['2_5_3_other']['2_5_3_text_other1'];;
            $inp_2_5_3_text_other2 = $data[3]['2_5_3_structure_compliance']['2_5_3_other']['2_5_3_text_other2'];;
            $inp_2_5_3_issue_found = $data[3]['2_5_3_issue_found']['value'];;
            $inp_2_5_3_detail = json_encode($data[3]['2_5_3_issue_found']['2_5_3_detail']);

            $inp_2_5_4_structure_compliance = $data[4]['2_5_4_structure_compliance']['value'];
            $inp_2_5_4_policy_compliance_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_policy_compliance_yes'];
            $inp_2_5_4_policy_compliance_no = $data[4]['2_5_4_structure_compliance']['2_5_4_policy_compliance_no'];
            $inp_2_5_4_metrology_sampling_activity_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_metrology_sampling_activity_yes'];
            $inp_2_5_4_metrology_sampling_activity_no = $data[4]['2_5_4_structure_compliance']['2_5_4_metrology_sampling_activity_no'];
            $inp_2_5_4_procedure_review_request_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_procedure_review_request_yes'];
            $inp_2_5_4_procedure_review_request_no = $data[4]['2_5_4_structure_compliance']['2_5_4_procedure_review_request_no'];
            $inp_2_5_4_decision_rule_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_no'];
            $inp_2_5_4_decision_rule_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_yes']['value'];
            $inp_2_5_4_agreement_customer_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_yes']['2_5_4_agreement_customer_yes'];
            $inp_2_5_4_agreement_customer_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_yes']['2_5_4_agreement_customer_no'];
            $inp_2_5_4_method_verification_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_method_verification_yes'];
            $inp_2_5_4_method_verification_no = $data[4]['2_5_4_structure_compliance']['2_5_4_method_verification_no'];
            $inp_2_5_4_sample_management_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_sample_management_yes'];
            $inp_2_5_4_sample_management_no = $data[4]['2_5_4_structure_compliance']['2_5_4_sample_management_no'];
            $inp_2_5_4_record_management_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_record_management_yes'];
            $inp_2_5_4_record_management_no = $data[4]['2_5_4_structure_compliance']['2_5_4_record_management_no'];
            $inp_2_5_4_uncertainty_evaluation_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_uncertainty_evaluation_yes'];
            $inp_2_5_4_uncertainty_evaluation_no = $data[4]['2_5_4_structure_compliance']['2_5_4_uncertainty_evaluation_no'];
            $inp_2_5_4_result_surveillance_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_result_surveillance_yes'];
            $inp_2_5_4_result_surveillance_no = $data[4]['2_5_4_structure_compliance']['2_5_4_result_surveillance_no'];
            $inp_2_5_4_proficiency_testing_no = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_no'];
            $inp_2_5_4_proficiency_testing_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['value'];
            $inp_2_5_4_test_participation =  $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['value'];
            $inp_2_5_4_test_participation_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_participation_details1'];
            $inp_2_5_4_test_participation_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_participation_details2'];
            $inp_2_5_4_test_calibration = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['value'];
            $inp_2_5_4_calibration_details = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_calibration_details'];
            $inp_2_5_4_acceptance_criteria_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_yes'];
            $inp_2_5_4_acceptance_criteria_no = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_no']['value'];
            $inp_2_5_4_acceptance_criteria1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_no']['2_5_4_acceptance_criteria1'];
            $inp_2_5_4_acceptance_criteria2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_no']['2_5_4_acceptance_criteria2'];
            $inp_2_5_4_lab_comparison = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['value'];
            $inp_2_5_4_lab_comparison_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_details1'];
            $inp_2_5_4_lab_comparison_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_details2'];
            $inp_2_5_4_lab_comparison_test = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['value'];
            $inp_2_5_4_lab_comparison_test_details = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_details'];
            $inp_2_5_4_lab_comparison_test_is_accept_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_yes'];
            $inp_2_5_4_lab_comparison_test_is_accept_no = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_no']['value'];
            $inp_2_5_4_lab_comparison_test_is_accept_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_no']['2_5_4_lab_comparison_test_is_accept_details1'];
            $inp_2_5_4_lab_comparison_test_is_accept_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_no']['2_5_4_lab_comparison_test_is_accept_details2'];
            $inp_2_5_4_test_participation2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation2'];
            $inp_2_5_4_other_methods = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_other_methods']['value'];
            $inp_2_5_4_other_methods_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_other_methods']['2_5_4_other_methods_details1'];
            $inp_2_5_4_other_methods_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_other_methods']['2_5_4_other_methods_details2'];
            $inp_2_5_4_report_approval_review_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_report_approval_review_yes'];
            $inp_2_5_4_report_approval_review_no = $data[4]['2_5_4_structure_compliance']['2_5_4_report_approval_review_no'];
            $inp_2_5_4_decision_rule2_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_no'];
            $inp_2_5_4_decision_rule2_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_yes']['value'];
            $inp_2_5_4_document_for_criteria_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_yes']['2_5_4_document_for_criteria_yes'];
            $inp_2_5_4_document_for_criteria_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_yes']['2_5_4_document_for_criteria_no'];
            $inp_2_5_4_complaint_process_no = $data[4]['2_5_4_structure_compliance']['2_5_4_complaint_process_no'];
            $inp_2_5_4_complaint_process_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_complaint_process_yes']['value'];


            $inp_2_5_4_complaint_number = $data[4]['2_5_4_structure_compliance']['2_5_4_complaint_process_yes']['2_5_4_complaint_number'];
            $inp_2_5_4_non_conformance_process_no = $data[4]['2_5_4_structure_compliance']['2_5_4_non_conformance_process_no'];
            $inp_2_5_4_non_conformance_process_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_non_conformance_process_yes']['value'];
            $inp_2_5_4_non_conformance_number = $data[4]['2_5_4_structure_compliance']['2_5_4_non_conformance_process_yes']['2_5_4_non_conformance_number'];
            $inp_2_5_4_data_control_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_data_control_yes'];
            $inp_2_5_4_data_control_no = $data[4]['2_5_4_structure_compliance']['2_5_4_data_control_no'];
            $inp_2_5_4_data_transfer_control_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_data_transfer_control_yes'];
            $inp_2_5_4_data_transfer_control_no = $data[4]['2_5_4_structure_compliance']['2_5_4_data_transfer_control_no'];
            $inp_2_5_4_other = $data[4]['2_5_4_structure_compliance']['2_5_4_other']['value'];
            $inp_2_5_4_text_other1 = $data[4]['2_5_4_structure_compliance']['2_5_4_other']['2_5_4_text_other1'];
            $inp_2_5_4_text_other2 = $data[4]['2_5_4_structure_compliance']['2_5_4_other']['2_5_4_text_other2'];
            $inp_2_5_4_issue_found = $data[4]['2_5_4_issue_found']['value'];;
            $inp_2_5_4_detail = json_encode($data[4]['2_5_4_issue_found']['2_5_4_detail']);

            $inp_2_5_5_structure_compliance = $data[5]['2_5_5_structure_compliance']['value'];
            $inp_2_5_5_data_control_option_a = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_option_a'];
            $inp_2_5_5_data_control_option_b = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_option_b'];
            $inp_2_5_5_data_control_policy_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_policy_yes'];
            $inp_2_5_5_data_control_policy_no = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_policy_no'];
            $inp_2_5_5_document_control_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_document_control_yes'];
            $inp_2_5_5_document_control_no = $data[5]['2_5_5_structure_compliance']['2_5_5_document_control_no'];
            $inp_2_5_5_record_keeping_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_record_keeping_yes'];
            $inp_2_5_5_record_keeping_no = $data[5]['2_5_5_structure_compliance']['2_5_5_record_keeping_no'];
            $inp_2_5_5_risk_management_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_management_yes'];
            $inp_2_5_5_risk_management_no = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_management_no'];
            $inp_2_5_5_risk_opportunity_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_opportunity_yes'];
            $inp_2_5_5_risk_opportunity_no = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_opportunity_no'];
            $inp_2_5_5_improvement_opportunity_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_improvement_opportunity_yes'];
            $inp_2_5_5_improvement_opportunity_no = $data[5]['2_5_5_structure_compliance']['2_5_5_improvement_opportunity_no'];
            $inp_2_5_5_non_conformance_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_non_conformance_yes'];
            $inp_2_5_5_non_conformance_no = $data[5]['2_5_5_structure_compliance']['2_5_5_non_conformance_no'];
            $inp_2_5_5_internal_audit_no = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_no'];
            $inp_2_5_5_internal_audit_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['value'];
            $inp_2_5_5_audit_frequency = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['2_5_5_audit_frequency'];
            $inp_2_5_5_last_audit_date = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['2_5_5_last_audit_date'];
            $inp_2_5_5_audit_issues = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['2_5_5_audit_issues'];
            $inp_2_5_5_management_review_no = $data[5]['2_5_5_structure_compliance']['2_5_5_management_review_no'];
            $inp_2_5_5_management_review_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_management_review_yes']['value'];
            $inp_2_5_5_last_review_date = $data[5]['2_5_5_structure_compliance']['2_5_5_management_review_yes']['2_5_5_last_review_date'];
            $inp_2_5_5_other = $data[5]['2_5_5_structure_compliance']['2_5_5_other']['value'];
            $inp_2_5_5_text_other1 = $data[5]['2_5_5_structure_compliance']['2_5_5_other']['2_5_5_text_other1'];
            $inp_2_5_5_text_other2 = $data[5]['2_5_5_structure_compliance']['2_5_5_other']['2_5_5_text_other2'];
            $inp_2_5_5_issue_found = $data[5]['2_5_5_issue_found']['value'];
            $inp_2_5_5_detail = json_encode($data[5]['2_5_5_issue_found']['2_5_5_detail']);
            
            $inp_2_5_6_1_1_management_review_no = $data[6]['2_5_6_1_1_management_review_no'];
            $inp_2_5_6_1_1_management_review_yes = $data[6]['2_5_6_1_1_management_review_yes']['value'];
            $inp_2_5_6_1_1_scope_certified_no = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_no'];
            $inp_2_5_6_1_1_scope_certified_yes = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_yes']['value'];
            $inp_2_5_6_1_1_activities_not_certified_yes = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_yes']['2_5_6_1_1_activities_not_certified_yes'];
            $inp_2_5_6_1_1_activities_not_certified_no = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_yes']['2_5_6_1_1_activities_not_certified_no'];
            $inp_2_5_6_1_1_accuracy_yes = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_accuracy_yes'];
            $inp_2_5_6_1_1_accuracy_no = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_accuracy_no']['value'];
            $inp_2_5_6_1_1_accuracy_detail = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_accuracy_no']['2_5_6_1_1_accuracy_detail'];

            $inp_2_5_6_1_2_multi_site_display_no = $data[7]['2_5_6_1_2_multi_site_display_no'];
            $inp_2_5_6_1_2_multi_site_display_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['value'];
            $inp_2_5_6_1_2_multi_site_scope_no = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_no'];
            $inp_2_5_6_1_2_multi_site_scope_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_yes']['value'];
            $inp_2_5_6_1_2_multi_site_activities_not_certified_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_yes']['2_5_6_1_2_multi_site_activities_not_certified_yes'];
            $inp_2_5_6_1_2_multi_site_activities_not_certified_no = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_yes']['2_5_6_1_2_multi_site_activities_not_certified_no'];
            $inp_2_5_6_1_2_multi_site_accuracy_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_accuracy_yes'];
            $inp_2_5_6_1_2_multi_site_accuracy_no = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_accuracy_no']['value'];
            $inp_2_5_6_1_2_multi_site_accuracy_details = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_accuracy_no']['2_5_6_1_2_multi_site_accuracy_details'];

            $inp_2_5_6_1_3_certification_status_yes = $data[8]['2_5_6_1_3_certification_status_yes'];
            $inp_2_5_6_1_3_certification_status_no = $data[8]['2_5_6_1_3_certification_status_no']['value'];
            $inp_2_5_6_1_3_certification_status_details = $data[8]['2_5_6_1_3_certification_status_no']['2_5_6_1_3_certification_status_details'];
   
            $inp_2_5_6_1_4_display_other_no = $data[9]['2_5_6_1_4_display_other_no'];
            $inp_2_5_6_1_4_display_other_yes = $data[9]['2_5_6_1_4_display_other_yes']['value'];
            $inp_2_5_6_1_4_display_other_details = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_display_other_details'];
            $inp_2_5_6_1_4_certification_status_yes = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_certification_status_yes'];
            $inp_2_5_6_1_4_certification_status_no = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_certification_status_no']['value'];
            $inp_2_5_6_1_4_certification_status_details = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_certification_status_no']['2_5_6_1_4_certification_status_details'];
       
            $inp_2_5_6_2_lab_availability_yes = $data[10]['2_5_6_2_lab_availability_yes'];
            $inp_2_5_6_2_lab_availability_no = $data[10]['2_5_6_2_lab_availability_no'];

            $inp_2_5_6_2_1_ilac_mra_display_no = $data[11]['2_5_6_2_1_ilac_mra_display_no'];
            $inp_2_5_6_2_1_ilac_mra_display_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['value'];
            $inp_2_5_6_2_1_ilac_mra_scope_no = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_no'];
            $inp_2_5_6_2_1_ilac_mra_scope_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_yes']['value'];
            $inp_2_5_6_2_1_ilac_mra_disclosure_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_yes']['2_5_6_2_1_ilac_mra_disclosure_yes'];
            $inp_2_5_6_2_1_ilac_mra_disclosure_no = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_yes']['2_5_6_2_1_ilac_mra_disclosure_no'];
            $inp_2_5_6_2_1_ilac_mra_compliance_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_compliance_yes'];
            $inp_2_5_6_2_1_ilac_mra_compliance_no = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_compliance_no']['value'];
            $inp_2_5_6_2_1_ilac_mra_compliance_details = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_compliance_no']['2_5_6_2_1_ilac_mra_compliance_details'];
   
            $inp_2_5_6_2_2_ilac_mra_compliance_no = $data[12]['2_5_6_2_2_ilac_mra_compliance_no'];
            $inp_2_5_6_2_2_ilac_mra_compliance_yes = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['value'];
            $inp_2_5_6_2_2_ilac_mra_compliance_details = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_ilac_mra_compliance_details'];
            $inp_2_5_6_2_2_mra_compliance_yes = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_mra_compliance_yes'];
            $inp_2_5_6_2_2_mra_compliance_no = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_mra_compliance_no']['value'];
            $inp_2_5_6_2_2_mra_compliance_details = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_mra_compliance_no']['2_5_6_2_2_mra_compliance_details'];

            $inp_3_0_assessment_results = $data[13]['3_0_assessment_results']['value'];
            $inp_3_0_issue_count = $data[13]['3_0_assessment_results']['3_0_issue_count'];
            $inp_3_0_remarks_count = $data[13]['3_0_assessment_results']['3_0_remarks_count'];
            $inp_3_0_deficiencies_details = $data[13]['3_0_assessment_results']['3_0_deficiencies_details'];
            $inp_3_0_deficiency_resolution_date = $data[13]['3_0_assessment_results']['3_0_deficiency_resolution_date'];
            $inp_3_0_offer_agreement = $data[13]['3_0_offer_agreement'];

            $persons = json_encode($persons);

            $labReportInfo = LabReportInfo::firstOrNew(['app_certi_lab_notice_id' => $noticeId]);

            $labReportInfo->app_certi_lab_notice_id = $noticeId;
            $labReportInfo->inp_2_2_assessment_on_site = $inp_2_2_assessment_on_site;
            $labReportInfo->inp_2_2_assessment_at_tisi = $inp_2_2_assessment_at_tisi;
            $labReportInfo->inp_2_2_remote_assessment = $inp_2_2_remote_assessment;
            $labReportInfo->inp_2_2_self_declaration = $inp_2_2_self_declaration;

            $labReportInfo->inp_2_5_1_structure_compliance = $inp_2_5_1_structure_compliance;
            $labReportInfo->inp_2_5_1_central_management_yes = $inp_2_5_1_central_management_yes;
            $labReportInfo->inp_2_5_1_central_management_no = $inp_2_5_1_central_management_no;
            $labReportInfo->inp_2_5_1_quality_policy_yes = $inp_2_5_1_quality_policy_yes;
            $labReportInfo->inp_2_5_1_quality_policy_no = $inp_2_5_1_quality_policy_no;
            $labReportInfo->inp_2_5_1_risk_assessment_yes = $inp_2_5_1_risk_assessment_yes;
            $labReportInfo->inp_2_5_1_risk_assessment_no = $inp_2_5_1_risk_assessment_no;
            $labReportInfo->inp_2_5_1_other = $inp_2_5_1_other;
            $labReportInfo->inp_2_5_1_text_other1 = $inp_2_5_1_text_other1;
            $labReportInfo->inp_2_5_1_text_other2 = $inp_2_5_1_text_other2;
            $labReportInfo->inp_2_5_1_issue_found = $inp_2_5_1_issue_found;
            $labReportInfo->inp_2_5_1_detail = $inp_2_5_1_detail;

         

            $labReportInfo->inp_2_5_2_structure_compliance = $inp_2_5_2_structure_compliance;
            $labReportInfo->inp_2_5_2_lab_management = $inp_2_5_2_lab_management;
            $labReportInfo->inp_2_5_2_lab_management_details = $inp_2_5_2_lab_management_details;
            $labReportInfo->inp_2_5_2_staff_assignment_yes = $inp_2_5_2_staff_assignment_yes;
            $labReportInfo->inp_2_5_2_staff_assignment_no = $inp_2_5_2_staff_assignment_no;
            $labReportInfo->inp_2_5_2_responsibility_yes = $inp_2_5_2_responsibility_yes;
            $labReportInfo->inp_2_5_2_responsibility_no = $inp_2_5_2_responsibility_no;
            $labReportInfo->inp_2_5_2_other = $inp_2_5_2_other;
            $labReportInfo->inp_2_5_2_text_other1 = $inp_2_5_2_text_other1;
            $labReportInfo->inp_2_5_2_text_other2 = $inp_2_5_2_text_other2;
            $labReportInfo->inp_2_5_2_issue_found = $inp_2_5_2_issue_found;
            $labReportInfo->inp_2_5_2_detail = $inp_2_5_2_detail;

            $labReportInfo->inp_2_5_3_structure_compliance = $inp_2_5_3_structure_compliance;
            $labReportInfo->inp_2_5_3_personnel_qualification_yes = $inp_2_5_3_personnel_qualification_yes;
            $labReportInfo->inp_2_5_3_personnel_qualification_no = $inp_2_5_3_personnel_qualification_no;
            $labReportInfo->inp_2_5_3_assign_personnel_appropriately_yes = $inp_2_5_3_assign_personnel_appropriately_yes;
            $labReportInfo->inp_2_5_3_assign_personnel_appropriately_no = $inp_2_5_3_assign_personnel_appropriately_no;
            $labReportInfo->inp_2_5_3_training_need_assessment_yes = $inp_2_5_3_training_need_assessment_yes;
            $labReportInfo->inp_2_5_3_training_need_assessment_no = $inp_2_5_3_training_need_assessment_no;
            $labReportInfo->inp_2_5_3_facility_and_environment_control_yes = $inp_2_5_3_facility_and_environment_control_yes;
            $labReportInfo->inp_2_5_3_facility_and_environment_control_no = $inp_2_5_3_facility_and_environment_control_no;
            $labReportInfo->inp_2_5_3_equipment_maintenance_calibration_yes = $inp_2_5_3_equipment_maintenance_calibration_yes;
            $labReportInfo->inp_2_5_3_equipment_maintenance_calibration_no = $inp_2_5_3_equipment_maintenance_calibration_no;
            $labReportInfo->inp_2_5_3_metrology_traceability_yes = $inp_2_5_3_metrology_traceability_yes;
            $labReportInfo->inp_2_5_3_metrology_traceability_no = $inp_2_5_3_metrology_traceability_no;
            $labReportInfo->inp_2_5_3_external_product_service_control_yes = $inp_2_5_3_external_product_service_control_yes;
            $labReportInfo->inp_2_5_3_external_product_service_control_no = $inp_2_5_3_external_product_service_control_no;
            $labReportInfo->inp_2_5_3_other = $inp_2_5_3_other;
            $labReportInfo->inp_2_5_3_text_other1 = $inp_2_5_3_text_other1;
            $labReportInfo->inp_2_5_3_text_other2 = $inp_2_5_3_text_other2;
            $labReportInfo->inp_2_5_3_issue_found = $inp_2_5_3_issue_found;
            $labReportInfo->inp_2_5_3_detail = $inp_2_5_3_detail;

            $labReportInfo->inp_2_5_4_structure_compliance = $inp_2_5_4_structure_compliance;
            $labReportInfo->inp_2_5_4_policy_compliance_yes = $inp_2_5_4_policy_compliance_yes;
            $labReportInfo->inp_2_5_4_policy_compliance_no = $inp_2_5_4_policy_compliance_no;
            $labReportInfo->inp_2_5_4_metrology_sampling_activity_yes = $inp_2_5_4_metrology_sampling_activity_yes;
            $labReportInfo->inp_2_5_4_metrology_sampling_activity_no = $inp_2_5_4_metrology_sampling_activity_no;
            $labReportInfo->inp_2_5_4_procedure_review_request_yes = $inp_2_5_4_procedure_review_request_yes;
            $labReportInfo->inp_2_5_4_procedure_review_request_no = $inp_2_5_4_procedure_review_request_no;
            $labReportInfo->inp_2_5_4_decision_rule_no = $inp_2_5_4_decision_rule_no;
            $labReportInfo->inp_2_5_4_decision_rule_yes = $inp_2_5_4_decision_rule_yes;
            $labReportInfo->inp_2_5_4_agreement_customer_yes = $inp_2_5_4_agreement_customer_yes;
            $labReportInfo->inp_2_5_4_agreement_customer_no = $inp_2_5_4_agreement_customer_no;
            $labReportInfo->inp_2_5_4_method_verification_yes = $inp_2_5_4_method_verification_yes;
            $labReportInfo->inp_2_5_4_method_verification_no = $inp_2_5_4_method_verification_no;
            $labReportInfo->inp_2_5_4_sample_management_yes = $inp_2_5_4_sample_management_yes;
            $labReportInfo->inp_2_5_4_sample_management_no = $inp_2_5_4_sample_management_no;
            $labReportInfo->inp_2_5_4_record_management_yes = $inp_2_5_4_record_management_yes;
            $labReportInfo->inp_2_5_4_record_management_no = $inp_2_5_4_record_management_no;
            $labReportInfo->inp_2_5_4_uncertainty_evaluation_yes = $inp_2_5_4_uncertainty_evaluation_yes;
            $labReportInfo->inp_2_5_4_uncertainty_evaluation_no = $inp_2_5_4_uncertainty_evaluation_no;
            $labReportInfo->inp_2_5_4_result_surveillance_yes = $inp_2_5_4_result_surveillance_yes;
            $labReportInfo->inp_2_5_4_result_surveillance_no = $inp_2_5_4_result_surveillance_no;
            $labReportInfo->inp_2_5_4_proficiency_testing_no = $inp_2_5_4_proficiency_testing_no;
            $labReportInfo->inp_2_5_4_proficiency_testing_yes = $inp_2_5_4_proficiency_testing_yes;
            $labReportInfo->inp_2_5_4_test_participation = $inp_2_5_4_test_participation;
            $labReportInfo->inp_2_5_4_test_participation_details1 = $inp_2_5_4_test_participation_details1;
            $labReportInfo->inp_2_5_4_test_participation_details2 = $inp_2_5_4_test_participation_details2;
            $labReportInfo->inp_2_5_4_test_calibration = $inp_2_5_4_test_calibration;
            $labReportInfo->inp_2_5_4_calibration_details = $inp_2_5_4_calibration_details;
            $labReportInfo->inp_2_5_4_acceptance_criteria_yes = $inp_2_5_4_acceptance_criteria_yes;
            $labReportInfo->inp_2_5_4_acceptance_criteria_no = $inp_2_5_4_acceptance_criteria_no;
            $labReportInfo->inp_2_5_4_acceptance_criteria1 = $inp_2_5_4_acceptance_criteria1;
            $labReportInfo->inp_2_5_4_acceptance_criteria2 = $inp_2_5_4_acceptance_criteria2;
            $labReportInfo->inp_2_5_4_lab_comparison = $inp_2_5_4_lab_comparison;
            $labReportInfo->inp_2_5_4_lab_comparison_details1 = $inp_2_5_4_lab_comparison_details1;
            $labReportInfo->inp_2_5_4_lab_comparison_details2 = $inp_2_5_4_lab_comparison_details2;
            $labReportInfo->inp_2_5_4_lab_comparison_test = $inp_2_5_4_lab_comparison_test;
            $labReportInfo->inp_2_5_4_lab_comparison_test_details = $inp_2_5_4_lab_comparison_test_details;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_yes = $inp_2_5_4_lab_comparison_test_is_accept_yes;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_no = $inp_2_5_4_lab_comparison_test_is_accept_no;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details1 = $inp_2_5_4_lab_comparison_test_is_accept_details1;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details2 = $inp_2_5_4_lab_comparison_test_is_accept_details2;
            $labReportInfo->inp_2_5_4_test_participation2 = $inp_2_5_4_test_participation2;
            $labReportInfo->inp_2_5_4_other_methods = $inp_2_5_4_other_methods;
            $labReportInfo->inp_2_5_4_other_methods_details1 = $inp_2_5_4_other_methods_details1;
            $labReportInfo->inp_2_5_4_other_methods_details2 = $inp_2_5_4_other_methods_details2;
            $labReportInfo->inp_2_5_4_report_approval_review_yes = $inp_2_5_4_report_approval_review_yes;
            $labReportInfo->inp_2_5_4_report_approval_review_no = $inp_2_5_4_report_approval_review_no;
            $labReportInfo->inp_2_5_4_decision_rule2_no = $inp_2_5_4_decision_rule2_no;
            $labReportInfo->inp_2_5_4_decision_rule2_yes = $inp_2_5_4_decision_rule2_yes;
            $labReportInfo->inp_2_5_4_document_for_criteria_yes = $inp_2_5_4_document_for_criteria_yes;
            $labReportInfo->inp_2_5_4_document_for_criteria_no = $inp_2_5_4_document_for_criteria_no;
            $labReportInfo->inp_2_5_4_complaint_process_no = $inp_2_5_4_complaint_process_no;
            $labReportInfo->inp_2_5_4_complaint_process_yes = $inp_2_5_4_complaint_process_yes;
            $labReportInfo->inp_2_5_4_complaint_number = $inp_2_5_4_complaint_number;
            $labReportInfo->inp_2_5_4_non_conformance_process_no = $inp_2_5_4_non_conformance_process_no;
            $labReportInfo->inp_2_5_4_non_conformance_process_yes = $inp_2_5_4_non_conformance_process_yes;
            $labReportInfo->inp_2_5_4_non_conformance_number = $inp_2_5_4_non_conformance_number;
            $labReportInfo->inp_2_5_4_data_control_yes = $inp_2_5_4_data_control_yes;
            $labReportInfo->inp_2_5_4_data_control_no = $inp_2_5_4_data_control_no;
            $labReportInfo->inp_2_5_4_data_transfer_control_yes = $inp_2_5_4_data_transfer_control_yes;
            $labReportInfo->inp_2_5_4_data_transfer_control_no = $inp_2_5_4_data_transfer_control_no;
            $labReportInfo->inp_2_5_4_other = $inp_2_5_4_other;
            $labReportInfo->inp_2_5_4_text_other1 = $inp_2_5_4_text_other1;
            $labReportInfo->inp_2_5_4_text_other2 = $inp_2_5_4_text_other2;
            $labReportInfo->inp_2_5_4_issue_found = $inp_2_5_4_issue_found;
            $labReportInfo->inp_2_5_4_detail = $inp_2_5_4_detail;

            $labReportInfo->inp_2_5_5_structure_compliance = $inp_2_5_5_structure_compliance;
            $labReportInfo->inp_2_5_5_data_control_option_a = $inp_2_5_5_data_control_option_a;
            $labReportInfo->inp_2_5_5_data_control_option_b = $inp_2_5_5_data_control_option_b;
            $labReportInfo->inp_2_5_5_data_control_policy_yes = $inp_2_5_5_data_control_policy_yes;
            $labReportInfo->inp_2_5_5_data_control_policy_no = $inp_2_5_5_data_control_policy_no;
            $labReportInfo->inp_2_5_5_document_control_yes = $inp_2_5_5_document_control_yes;
            $labReportInfo->inp_2_5_5_document_control_no = $inp_2_5_5_document_control_no;
            $labReportInfo->inp_2_5_5_record_keeping_yes = $inp_2_5_5_record_keeping_yes;
            $labReportInfo->inp_2_5_5_record_keeping_no = $inp_2_5_5_record_keeping_no;
            $labReportInfo->inp_2_5_5_risk_management_yes = $inp_2_5_5_risk_management_yes;
            $labReportInfo->inp_2_5_5_risk_management_no = $inp_2_5_5_risk_management_no;
            $labReportInfo->inp_2_5_5_risk_opportunity_yes = $inp_2_5_5_risk_opportunity_yes;
            $labReportInfo->inp_2_5_5_risk_opportunity_no = $inp_2_5_5_risk_opportunity_no;
            $labReportInfo->inp_2_5_5_improvement_opportunity_yes = $inp_2_5_5_improvement_opportunity_yes;
            $labReportInfo->inp_2_5_5_improvement_opportunity_no = $inp_2_5_5_improvement_opportunity_no;
            $labReportInfo->inp_2_5_5_non_conformance_yes = $inp_2_5_5_non_conformance_yes;
            $labReportInfo->inp_2_5_5_non_conformance_no = $inp_2_5_5_non_conformance_no;
            $labReportInfo->inp_2_5_5_internal_audit_no = $inp_2_5_5_internal_audit_no;
            $labReportInfo->inp_2_5_5_internal_audit_yes = $inp_2_5_5_internal_audit_yes;
            $labReportInfo->inp_2_5_5_audit_frequency = $inp_2_5_5_audit_frequency;
            $labReportInfo->inp_2_5_5_last_audit_date = $inp_2_5_5_last_audit_date;
            $labReportInfo->inp_2_5_5_audit_issues = $inp_2_5_5_audit_issues;
            $labReportInfo->inp_2_5_5_management_review_no = $inp_2_5_5_management_review_no;
            $labReportInfo->inp_2_5_5_management_review_yes = $inp_2_5_5_management_review_yes;
            $labReportInfo->inp_2_5_5_last_review_date = $inp_2_5_5_last_review_date;
            $labReportInfo->inp_2_5_5_other = $inp_2_5_5_other;
            $labReportInfo->inp_2_5_5_text_other1 = $inp_2_5_5_text_other1;
            $labReportInfo->inp_2_5_5_text_other2 = $inp_2_5_5_text_other2;
            $labReportInfo->inp_2_5_5_issue_found = $inp_2_5_5_issue_found;
            $labReportInfo->inp_2_5_5_detail = $inp_2_5_5_detail;

            $labReportInfo->inp_2_5_6_1_1_management_review_no = $inp_2_5_6_1_1_management_review_no;
            $labReportInfo->inp_2_5_6_1_1_management_review_yes = $inp_2_5_6_1_1_management_review_yes;
            $labReportInfo->inp_2_5_6_1_1_scope_certified_no = $inp_2_5_6_1_1_scope_certified_no;
            $labReportInfo->inp_2_5_6_1_1_scope_certified_yes = $inp_2_5_6_1_1_scope_certified_yes;
            $labReportInfo->inp_2_5_6_1_1_activities_not_certified_yes = $inp_2_5_6_1_1_activities_not_certified_yes;
            $labReportInfo->inp_2_5_6_1_1_activities_not_certified_no = $inp_2_5_6_1_1_activities_not_certified_no;
            $labReportInfo->inp_2_5_6_1_1_accuracy_yes = $inp_2_5_6_1_1_accuracy_yes;
            $labReportInfo->inp_2_5_6_1_1_accuracy_no = $inp_2_5_6_1_1_accuracy_no;
            $labReportInfo->inp_2_5_6_1_1_accuracy_detail = $inp_2_5_6_1_1_accuracy_detail;

            $labReportInfo->inp_2_5_6_1_2_multi_site_display_no = $inp_2_5_6_1_2_multi_site_display_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_display_yes = $inp_2_5_6_1_2_multi_site_display_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_scope_no = $inp_2_5_6_1_2_multi_site_scope_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_scope_yes = $inp_2_5_6_1_2_multi_site_scope_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_activities_not_certified_yes = $inp_2_5_6_1_2_multi_site_activities_not_certified_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_activities_not_certified_no = $inp_2_5_6_1_2_multi_site_activities_not_certified_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_yes = $inp_2_5_6_1_2_multi_site_accuracy_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_no = $inp_2_5_6_1_2_multi_site_accuracy_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_details = $inp_2_5_6_1_2_multi_site_accuracy_details;

            $labReportInfo->inp_2_5_6_1_3_certification_status_yes = $inp_2_5_6_1_3_certification_status_yes;
            $labReportInfo->inp_2_5_6_1_3_certification_status_no = $inp_2_5_6_1_3_certification_status_no;
            $labReportInfo->inp_2_5_6_1_3_certification_status_details = $inp_2_5_6_1_3_certification_status_details;

            $labReportInfo->inp_2_5_6_1_4_display_other_no = $inp_2_5_6_1_4_display_other_no;
            $labReportInfo->inp_2_5_6_1_4_display_other_yes = $inp_2_5_6_1_4_display_other_yes;
            $labReportInfo->inp_2_5_6_1_4_display_other_details = $inp_2_5_6_1_4_display_other_details;
            $labReportInfo->inp_2_5_6_1_4_certification_status_yes = $inp_2_5_6_1_4_certification_status_yes;
            $labReportInfo->inp_2_5_6_1_4_certification_status_no = $inp_2_5_6_1_4_certification_status_no;
            $labReportInfo->inp_2_5_6_1_4_certification_status_details = $inp_2_5_6_1_4_certification_status_details;

            $labReportInfo->inp_2_5_6_2_lab_availability_yes = $inp_2_5_6_2_lab_availability_yes;
            $labReportInfo->inp_2_5_6_2_lab_availability_no = $inp_2_5_6_2_lab_availability_no;

            $labReportInfo->inp_2_5_6_2_1_ilac_mra_display_no = $inp_2_5_6_2_1_ilac_mra_display_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_display_yes = $inp_2_5_6_2_1_ilac_mra_display_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_scope_no = $inp_2_5_6_2_1_ilac_mra_scope_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_scope_yes = $inp_2_5_6_2_1_ilac_mra_scope_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_disclosure_yes = $inp_2_5_6_2_1_ilac_mra_disclosure_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_disclosure_no = $inp_2_5_6_2_1_ilac_mra_disclosure_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_yes = $inp_2_5_6_2_1_ilac_mra_compliance_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_no = $inp_2_5_6_2_1_ilac_mra_compliance_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_details = $inp_2_5_6_2_1_ilac_mra_compliance_details;

            $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_no = $inp_2_5_6_2_2_ilac_mra_compliance_no;
            $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_yes = $inp_2_5_6_2_2_ilac_mra_compliance_yes;
            $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_details = $inp_2_5_6_2_2_ilac_mra_compliance_details;
            $labReportInfo->inp_2_5_6_2_2_mra_compliance_yes = $inp_2_5_6_2_2_mra_compliance_yes;
            $labReportInfo->inp_2_5_6_2_2_mra_compliance_no = $inp_2_5_6_2_2_mra_compliance_no;
            $labReportInfo->inp_2_5_6_2_2_mra_compliance_details = $inp_2_5_6_2_2_mra_compliance_details;

            $labReportInfo->inp_3_0_assessment_results = $inp_3_0_assessment_results;
            $labReportInfo->inp_3_0_issue_count = $inp_3_0_issue_count;
            $labReportInfo->inp_3_0_remarks_count = $inp_3_0_remarks_count;
            $labReportInfo->inp_3_0_deficiencies_details = $inp_3_0_deficiencies_details;
            $labReportInfo->inp_3_0_deficiency_resolution_date = $inp_3_0_deficiency_resolution_date;
            $labReportInfo->inp_3_0_offer_agreement = $inp_3_0_offer_agreement;

            $labReportInfo->persons = $persons;
            $labReportInfo->status = 2;

            $labReportInfo->save();


            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');

            SignAssessmentReportTransaction::where('report_info_id', $labReportInfo->id)
                                        ->where('certificate_type',2)
                                        ->where('report_type',1)
                                        ->delete();
            foreach ($signers as $signer) {
                // ตรวจสอบความถูกต้องของข้อมูล
                if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }
                SignAssessmentReportTransaction::create([
                    'report_info_id' => $labReportInfo->id,
                    'signer_id' => $signer['signer_id'],
                    'signer_name' => $signer['signer_name'],
                    'signer_position' => $signer['signer_position'],
                    'signer_order' => $signer['id'],
                    'view_url' => $url . '/certify/save_assessment/view-lab-info/'. $noticeId,
                    'certificate_type' => 2,
                    'report_type' => 1,
                    'app_id' => $labReportInfo->notice->applicant->app_no,
                ]);
            }
        

            // ตรวจสอบค่า
            return response()->json([
                'message' => 'Data received successfully',
                'data' => $data
            ]);
        }

        public function viewLabInfo($id)
        {
            // dd('ok');
            $notice = Notice::find($id);
            $labReportInfo = LabReportInfo::where('app_certi_lab_notice_id',$id)->first();
            // $notice = $labReportInfo->notice;
            $assessment = $notice->assessment;
            $app_certi_lab = $notice->applicant;
            $boardAuditor = $assessment->board_auditor_to;
            $id = $boardAuditor->auditor_id;
            
            // dd($notice,$assessment,$boardAuditor,$app_certi_lab);
    
            $groups = $boardAuditor->groups;
    
            $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id
    
            $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
    
            foreach ($groups as $group) {
                $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
                $auditors = $group->auditors; // $auditors เป็น Collection
    
                // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
                if (!isset($statusAuditorMap[$statusAuditorId])) {
                    $statusAuditorMap[$statusAuditorId] = [];
                }
    
                // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
                foreach ($auditors as $auditor) {
                    $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
                }
            }
    
            $uniqueAuditorIds = array_unique($auditorIds);
    
            $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();
    
            $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);
    
            $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
            $dateRange = "";
    
            if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
                if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                    // ถ้าเป็นวันเดียวกัน
                    $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
                } else {
                    // ถ้าเป็นคนละวัน
                    $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                                " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
                }
            }
    
            $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();
            $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
            // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
            if ($boardAuditorExpert && $boardAuditorExpert->expert) {
                // แปลงข้อมูล JSON ใน expert กลับเป็น array
                $categories = json_decode($boardAuditorExpert->expert, true);
            
                // ถ้ามีหลายรายการ
                if (count($categories) > 1) {
                    // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
                    $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
                    $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
                } elseif (count($categories) == 1) {
                    // ถ้ามีแค่รายการเดียว
                    $experts = $categories[0];
                } else {
                    $experts = ''; // ถ้าไม่มีข้อมูล
                }
            
            }
    
            $scope_branch = "";
            if ($certi_lab->lab_type == 3){
                $scope_branch = $certi_lab->BranchTitle;
            }else if($certi_lab->lab_type == 4)
            {
                $scope_branch = $certi_lab->ClibrateBranchTitle;
            }
    
            $data = new stdClass();
    
            $data->header_text1 = '';
            $data->header_text2 = '';
            $data->header_text3 = '';
            $data->header_text4 = $certi_lab->app_no;
            $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
            $data->lab_name = $certi_lab->lab_name;
            $data->scope_branch = $scope_branch;
            $data->app_np = 'ทดสอบ ๑๖๗๑';
            $data->certificate_no = '13-LB0037';
            $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
            $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
            $data->experts = $experts;
            $data->date_range = $dateRange;
            $data->statusAuditorMap = $statusAuditorMap;
    
            // $notice = Notice::find($notice_id);
            $assessment = $notice->assessment;
            // dd($statusAuditorMap);
            $app_certi_lab = $notice->applicant;
            $boardAuditor = $assessment->board_auditor_to;
            $id = $boardAuditor->auditor_id;
            $labRequest = null;
            
            if($app_certi_lab->lab_type == 4){
                $labRequest = LabCalRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            }else if($app_certi_lab->lab_type == 3)
            {
                $labRequest = LabTestRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            }

            $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)
                                                ->where('certificate_type',2)
                                                ->where('report_type',1)
                                                ->get();
            $approveNoticeItems = NoticeItem::where('app_certi_lab_notice_id', $notice->id)
                ->whereNotNull('attachs')
                ->where('status',1)
                ->where('file_status',1)
                ->get();
                

            return view('certify.save_assessment.view-report', [
                'labReportInfo' => $labReportInfo,
                'data' => $data,
                'notice' => $notice,
                'assessment' => $assessment,
                'boardAuditor' => $boardAuditor,
                'certi_lab' => $app_certi_lab,
                'labRequest' => $labRequest,
                'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
                'approveNoticeItems' => $approveNoticeItems,
                'id' => $id
            ]);
        }

        public function updateLabInfo(Request $request)
        {
            // dd('update');
            // รับค่าจาก JSON
            $data = $request->input('data'); // ข้อมูลใน key "data"
            $persons = $request->input('persons'); // ข้อมูลใน key "data"
            $noticeId = $request->input('notice_id');
            $id = $request->input('id');
            $signers = $request->input('signer', []);
            $submitType = $request->input('submit_type');
            
            $inp_2_2_assessment_on_site = $data[0]['2_2_assessment_on_site'];
            $inp_2_2_assessment_at_tisi = $data[0]['2_2_assessment_at_tisi'];
            $inp_2_2_remote_assessment = $data[0]['2_2_remote_assessment'];
            $inp_2_2_self_declaration = $data[0]['2_2_self_declaration'];

            $inp_2_5_1_structure_compliance = $data[1]['2_5_1_structure_compliance']['value'];
            $inp_2_5_1_central_management_yes = $data[1]['2_5_1_structure_compliance']['2_5_1_central_management_yes'];
            $inp_2_5_1_central_management_no = $data[1]['2_5_1_structure_compliance']['2_5_1_central_management_no'];
            $inp_2_5_1_quality_policy_yes = $data[1]['2_5_1_structure_compliance']['2_5_1_quality_policy_yes'];;
            $inp_2_5_1_quality_policy_no = $data[1]['2_5_1_structure_compliance']['2_5_1_quality_policy_no'];;
            $inp_2_5_1_risk_assessment_yes = $data[1]['2_5_1_structure_compliance']['2_5_1_risk_assessment_yes'];;
            $inp_2_5_1_risk_assessment_no = $data[1]['2_5_1_structure_compliance']['2_5_1_risk_assessment_no'];;
            $inp_2_5_1_other = $data[1]['2_5_1_structure_compliance']['2_5_1_other']['value'];
            $inp_2_5_1_text_other1 = $data[1]['2_5_1_structure_compliance']['2_5_1_other']['2_5_1_text_other1'];
            $inp_2_5_1_text_other2 = $data[1]['2_5_1_structure_compliance']['2_5_1_other']['2_5_1_text_other2'];
            $inp_2_5_1_issue_found = $data[1]['2_5_1_issue_found']['value'];
            $inp_2_5_1_detail = json_encode($data[1]['2_5_1_issue_found']['2_5_1_detail']);

            $inp_2_5_2_structure_compliance = $data[2]['2_5_2_structure_compliance']['value'];
            $inp_2_5_2_lab_management = $data[2]['2_5_2_structure_compliance']['2_5_2_lab_management']['value'];
            $inp_2_5_2_lab_management_details = $data[2]['2_5_2_structure_compliance']['2_5_2_lab_management']['2_5_2_lab_management_details'];
            $inp_2_5_2_staff_assignment_yes = $data[2]['2_5_2_structure_compliance']['2_5_2_staff_assignment_yes'];
            $inp_2_5_2_staff_assignment_no =$data[2]['2_5_2_structure_compliance']['2_5_2_staff_assignment_no'];
            $inp_2_5_2_responsibility_yes = $data[2]['2_5_2_structure_compliance']['2_5_2_responsibility_yes'];
            $inp_2_5_2_responsibility_no = $data[2]['2_5_2_structure_compliance']['2_5_2_responsibility_no'];
            $inp_2_5_2_other = $data[2]['2_5_2_structure_compliance']['2_5_2_other']['value'];
            $inp_2_5_2_text_other1 = $data[2]['2_5_2_structure_compliance']['2_5_2_other']['2_5_2_text_other1'];
            $inp_2_5_2_text_other2 = $data[2]['2_5_2_structure_compliance']['2_5_2_other']['2_5_2_text_other2'];
            $inp_2_5_2_issue_found = $data[2]['2_5_2_issue_found']['value'];
            $inp_2_5_2_detail =  json_encode($data[2]['2_5_2_issue_found']['2_5_2_detail']);

            $inp_2_5_3_structure_compliance = $data[3]['2_5_3_structure_compliance']['value'];
            $inp_2_5_3_personnel_qualification_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_personnel_qualification_yes'];
            $inp_2_5_3_personnel_qualification_no = $data[3]['2_5_3_structure_compliance']['2_5_3_personnel_qualification_no'];
            $inp_2_5_3_assign_personnel_appropriately_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_assign_personnel_appropriately_yes'];
            $inp_2_5_3_assign_personnel_appropriately_no = $data[3]['2_5_3_structure_compliance']['2_5_3_assign_personnel_appropriately_no'];
            $inp_2_5_3_training_need_assessment_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_training_need_assessment_yes'];
            $inp_2_5_3_training_need_assessment_no = $data[3]['2_5_3_structure_compliance']['2_5_3_training_need_assessment_no'];
            $inp_2_5_3_facility_and_environment_control_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_facility_and_environment_control_yes'];
            $inp_2_5_3_facility_and_environment_control_no = $data[3]['2_5_3_structure_compliance']['2_5_3_facility_and_environment_control_no'];
            $inp_2_5_3_equipment_maintenance_calibration_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_equipment_maintenance_calibration_yes'];
            $inp_2_5_3_equipment_maintenance_calibration_no = $data[3]['2_5_3_structure_compliance']['2_5_3_equipment_maintenance_calibration_no'];
            $inp_2_5_3_metrology_traceability_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_metrology_traceability_yes'];
            $inp_2_5_3_metrology_traceability_no = $data[3]['2_5_3_structure_compliance']['2_5_3_metrology_traceability_no'];
            $inp_2_5_3_external_product_service_control_yes = $data[3]['2_5_3_structure_compliance']['2_5_3_external_product_service_control_yes'];
            $inp_2_5_3_external_product_service_control_no = $data[3]['2_5_3_structure_compliance']['2_5_3_external_product_service_control_no'];
            $inp_2_5_3_other = $data[3]['2_5_3_structure_compliance']['2_5_3_other']['value'];
            $inp_2_5_3_text_other1 = $data[3]['2_5_3_structure_compliance']['2_5_3_other']['2_5_3_text_other1'];
            $inp_2_5_3_text_other2 = $data[3]['2_5_3_structure_compliance']['2_5_3_other']['2_5_3_text_other2'];
            $inp_2_5_3_issue_found = $data[3]['2_5_3_issue_found']['value'];;
            $inp_2_5_3_detail = json_encode($data[3]['2_5_3_issue_found']['2_5_3_detail']);

            $inp_2_5_4_structure_compliance = $data[4]['2_5_4_structure_compliance']['value'];
            $inp_2_5_4_policy_compliance_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_policy_compliance_yes'];
            $inp_2_5_4_policy_compliance_no = $data[4]['2_5_4_structure_compliance']['2_5_4_policy_compliance_no'];
            $inp_2_5_4_metrology_sampling_activity_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_metrology_sampling_activity_yes'];


            $inp_2_5_4_metrology_sampling_activity_no = $data[4]['2_5_4_structure_compliance']['2_5_4_metrology_sampling_activity_no'];
            $inp_2_5_4_procedure_review_request_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_procedure_review_request_yes'];
            $inp_2_5_4_procedure_review_request_no = $data[4]['2_5_4_structure_compliance']['2_5_4_procedure_review_request_no'];
            $inp_2_5_4_decision_rule_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_no'];
            $inp_2_5_4_decision_rule_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_yes']['value'];
            $inp_2_5_4_agreement_customer_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_yes']['2_5_4_agreement_customer_yes'];
            $inp_2_5_4_agreement_customer_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule_yes']['2_5_4_agreement_customer_no'];
            $inp_2_5_4_method_verification_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_method_verification_yes'];
            $inp_2_5_4_method_verification_no = $data[4]['2_5_4_structure_compliance']['2_5_4_method_verification_no'];
            $inp_2_5_4_sample_management_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_sample_management_yes'];
            $inp_2_5_4_sample_management_no = $data[4]['2_5_4_structure_compliance']['2_5_4_sample_management_no'];
            $inp_2_5_4_record_management_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_record_management_yes'];
            $inp_2_5_4_record_management_no = $data[4]['2_5_4_structure_compliance']['2_5_4_record_management_no'];
            $inp_2_5_4_uncertainty_evaluation_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_uncertainty_evaluation_yes'];
            $inp_2_5_4_uncertainty_evaluation_no = $data[4]['2_5_4_structure_compliance']['2_5_4_uncertainty_evaluation_no'];
            $inp_2_5_4_result_surveillance_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_result_surveillance_yes'];
            $inp_2_5_4_result_surveillance_no = $data[4]['2_5_4_structure_compliance']['2_5_4_result_surveillance_no'];
            $inp_2_5_4_proficiency_testing_no = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_no'];
            $inp_2_5_4_proficiency_testing_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['value'];
            $inp_2_5_4_test_participation =  $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['value'];
            $inp_2_5_4_test_participation_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_participation_details1'];

            $inp_2_5_4_test_participation_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_participation_details2'];
            $inp_2_5_4_test_calibration = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['value'];
            $inp_2_5_4_calibration_details = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_calibration_details'];
            $inp_2_5_4_acceptance_criteria_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_yes'];
            $inp_2_5_4_acceptance_criteria_no = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_no']['value'];
            $inp_2_5_4_acceptance_criteria1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_no']['2_5_4_acceptance_criteria1'];
            $inp_2_5_4_acceptance_criteria2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation']['2_5_4_test_calibration']['2_5_4_acceptance_criteria_no']['2_5_4_acceptance_criteria2'];
            $inp_2_5_4_lab_comparison = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['value'];
            $inp_2_5_4_lab_comparison_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_details1'];
            $inp_2_5_4_lab_comparison_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_details2'];
            $inp_2_5_4_lab_comparison_test = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['value'];
            $inp_2_5_4_lab_comparison_test_details = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_details'];
            $inp_2_5_4_lab_comparison_test_is_accept_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_yes'];
            $inp_2_5_4_lab_comparison_test_is_accept_no = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_no']['value'];
            $inp_2_5_4_lab_comparison_test_is_accept_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_no']['2_5_4_lab_comparison_test_is_accept_details1'];
            $inp_2_5_4_lab_comparison_test_is_accept_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_lab_comparison']['2_5_4_lab_comparison_test']['2_5_4_lab_comparison_test_is_accept_no']['2_5_4_lab_comparison_test_is_accept_details2'];
            $inp_2_5_4_test_participation2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_test_participation2'];
            $inp_2_5_4_other_methods = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_other_methods']['value'];
            $inp_2_5_4_other_methods_details1 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_other_methods']['2_5_4_other_methods_details1'];
            $inp_2_5_4_other_methods_details2 = $data[4]['2_5_4_structure_compliance']['2_5_4_proficiency_testing_yes']['2_5_4_other_methods']['2_5_4_other_methods_details2'];
            $inp_2_5_4_report_approval_review_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_report_approval_review_yes'];
            $inp_2_5_4_report_approval_review_no = $data[4]['2_5_4_structure_compliance']['2_5_4_report_approval_review_no'];
            $inp_2_5_4_decision_rule2_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_no'];

            $inp_2_5_4_decision_rule2_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_yes']['value'];
            $inp_2_5_4_document_for_criteria_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_yes']['2_5_4_document_for_criteria_yes'];
            $inp_2_5_4_document_for_criteria_no = $data[4]['2_5_4_structure_compliance']['2_5_4_decision_rule2_yes']['2_5_4_document_for_criteria_no'];
            $inp_2_5_4_complaint_process_no = $data[4]['2_5_4_structure_compliance']['2_5_4_complaint_process_no'];
            $inp_2_5_4_complaint_process_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_complaint_process_yes']['value'];

            $inp_2_5_4_complaint_number = $data[4]['2_5_4_structure_compliance']['2_5_4_complaint_process_yes']['2_5_4_complaint_number'];

            $inp_2_5_4_non_conformance_process_no = $data[4]['2_5_4_structure_compliance']['2_5_4_non_conformance_process_no'];
            $inp_2_5_4_non_conformance_process_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_non_conformance_process_yes']['value'];
            $inp_2_5_4_non_conformance_number = $data[4]['2_5_4_structure_compliance']['2_5_4_non_conformance_process_yes']['2_5_4_non_conformance_number'];
            $inp_2_5_4_data_control_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_data_control_yes'];
            $inp_2_5_4_data_control_no = $data[4]['2_5_4_structure_compliance']['2_5_4_data_control_no'];
            $inp_2_5_4_data_transfer_control_yes = $data[4]['2_5_4_structure_compliance']['2_5_4_data_transfer_control_yes'];
            $inp_2_5_4_data_transfer_control_no = $data[4]['2_5_4_structure_compliance']['2_5_4_data_transfer_control_no'];
            $inp_2_5_4_other = $data[4]['2_5_4_structure_compliance']['2_5_4_other']['value'];
            $inp_2_5_4_text_other1 = $data[4]['2_5_4_structure_compliance']['2_5_4_other']['2_5_4_text_other1'];
            $inp_2_5_4_text_other2 = $data[4]['2_5_4_structure_compliance']['2_5_4_other']['2_5_4_text_other2'];
            $inp_2_5_4_issue_found = $data[4]['2_5_4_issue_found']['value'];;
            $inp_2_5_4_detail = json_encode($data[4]['2_5_4_issue_found']['2_5_4_detail']);

            $inp_2_5_5_structure_compliance = $data[5]['2_5_5_structure_compliance']['value'];
            $inp_2_5_5_data_control_option_a = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_option_a'];
            $inp_2_5_5_data_control_option_b = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_option_b'];
            $inp_2_5_5_data_control_policy_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_policy_yes'];
            $inp_2_5_5_data_control_policy_no = $data[5]['2_5_5_structure_compliance']['2_5_5_data_control_policy_no'];
            $inp_2_5_5_document_control_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_document_control_yes'];
            $inp_2_5_5_document_control_no = $data[5]['2_5_5_structure_compliance']['2_5_5_document_control_no'];
            $inp_2_5_5_record_keeping_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_record_keeping_yes'];
            $inp_2_5_5_record_keeping_no = $data[5]['2_5_5_structure_compliance']['2_5_5_record_keeping_no'];
            $inp_2_5_5_risk_management_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_management_yes'];
            $inp_2_5_5_risk_management_no = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_management_no'];
            $inp_2_5_5_risk_opportunity_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_opportunity_yes'];
            $inp_2_5_5_risk_opportunity_no = $data[5]['2_5_5_structure_compliance']['2_5_5_risk_opportunity_no'];
            $inp_2_5_5_improvement_opportunity_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_improvement_opportunity_yes'];
            $inp_2_5_5_improvement_opportunity_no = $data[5]['2_5_5_structure_compliance']['2_5_5_improvement_opportunity_no'];
            $inp_2_5_5_non_conformance_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_non_conformance_yes'];
            $inp_2_5_5_non_conformance_no = $data[5]['2_5_5_structure_compliance']['2_5_5_non_conformance_no'];
            $inp_2_5_5_internal_audit_no = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_no'];
            $inp_2_5_5_internal_audit_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['value'];

            $inp_2_5_5_audit_frequency = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['2_5_5_audit_frequency'];
            $inp_2_5_5_last_audit_date = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['2_5_5_last_audit_date'];
            $inp_2_5_5_audit_issues = $data[5]['2_5_5_structure_compliance']['2_5_5_internal_audit_yes']['2_5_5_audit_issues'];
            $inp_2_5_5_management_review_no = $data[5]['2_5_5_structure_compliance']['2_5_5_management_review_no'];
            $inp_2_5_5_management_review_yes = $data[5]['2_5_5_structure_compliance']['2_5_5_management_review_yes']['value'];
            $inp_2_5_5_last_review_date = $data[5]['2_5_5_structure_compliance']['2_5_5_management_review_yes']['2_5_5_last_review_date'];
            $inp_2_5_5_other = $data[5]['2_5_5_structure_compliance']['2_5_5_other']['value'];
            $inp_2_5_5_text_other1 = $data[5]['2_5_5_structure_compliance']['2_5_5_other']['2_5_5_text_other1'];
            $inp_2_5_5_text_other2 = $data[5]['2_5_5_structure_compliance']['2_5_5_other']['2_5_5_text_other2'];
            $inp_2_5_5_issue_found = $data[5]['2_5_5_issue_found']['value'];
            $inp_2_5_5_detail = json_encode($data[5]['2_5_5_issue_found']['2_5_5_detail']);
            
            $inp_2_5_6_1_1_management_review_no = $data[6]['2_5_6_1_1_management_review_no'];
            $inp_2_5_6_1_1_management_review_yes = $data[6]['2_5_6_1_1_management_review_yes']['value'];
            $inp_2_5_6_1_1_scope_certified_no = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_no'];
            $inp_2_5_6_1_1_scope_certified_yes = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_yes']['value'];
            $inp_2_5_6_1_1_activities_not_certified_yes = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_yes']['2_5_6_1_1_activities_not_certified_yes'];
            $inp_2_5_6_1_1_activities_not_certified_no = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_scope_certified_yes']['2_5_6_1_1_activities_not_certified_no'];
            $inp_2_5_6_1_1_accuracy_yes = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_accuracy_yes'];
            $inp_2_5_6_1_1_accuracy_no = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_accuracy_no']['value'];
            $inp_2_5_6_1_1_accuracy_detail = $data[6]['2_5_6_1_1_management_review_yes']['2_5_6_1_1_accuracy_no']['2_5_6_1_1_accuracy_detail'];

            $inp_2_5_6_1_2_multi_site_display_no = $data[7]['2_5_6_1_2_multi_site_display_no'];
            $inp_2_5_6_1_2_multi_site_display_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['value'];
            $inp_2_5_6_1_2_multi_site_scope_no = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_no'];
            $inp_2_5_6_1_2_multi_site_scope_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_yes']['value'];
            $inp_2_5_6_1_2_multi_site_activities_not_certified_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_yes']['2_5_6_1_2_multi_site_activities_not_certified_yes'];
            $inp_2_5_6_1_2_multi_site_activities_not_certified_no = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_scope_yes']['2_5_6_1_2_multi_site_activities_not_certified_no'];
            $inp_2_5_6_1_2_multi_site_accuracy_yes = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_accuracy_yes'];
            $inp_2_5_6_1_2_multi_site_accuracy_no = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_accuracy_no']['value'];
            $inp_2_5_6_1_2_multi_site_accuracy_details = $data[7]['2_5_6_1_2_multi_site_display_yes']['2_5_6_1_2_multi_site_accuracy_no']['2_5_6_1_2_multi_site_accuracy_details'];

            $inp_2_5_6_1_3_certification_status_yes = $data[8]['2_5_6_1_3_certification_status_yes'];
            $inp_2_5_6_1_3_certification_status_no = $data[8]['2_5_6_1_3_certification_status_no']['value'];
            $inp_2_5_6_1_3_certification_status_details = $data[8]['2_5_6_1_3_certification_status_no']['2_5_6_1_3_certification_status_details'];
   
            $inp_2_5_6_1_4_display_other_no = $data[9]['2_5_6_1_4_display_other_no'];
            $inp_2_5_6_1_4_display_other_yes = $data[9]['2_5_6_1_4_display_other_yes']['value'];
            $inp_2_5_6_1_4_display_other_details = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_display_other_details'];
            $inp_2_5_6_1_4_certification_status_yes = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_certification_status_yes'];
            $inp_2_5_6_1_4_certification_status_no = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_certification_status_no']['value'];
            $inp_2_5_6_1_4_certification_status_details = $data[9]['2_5_6_1_4_display_other_yes']['2_5_6_1_4_certification_status_no']['2_5_6_1_4_certification_status_details'];
       
            $inp_2_5_6_2_lab_availability_yes = $data[10]['2_5_6_2_lab_availability_yes'];
            $inp_2_5_6_2_lab_availability_no = $data[10]['2_5_6_2_lab_availability_no'];

            $inp_2_5_6_2_1_ilac_mra_display_no = $data[11]['2_5_6_2_1_ilac_mra_display_no'];
            $inp_2_5_6_2_1_ilac_mra_display_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['value'];
            $inp_2_5_6_2_1_ilac_mra_scope_no = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_no'];
            $inp_2_5_6_2_1_ilac_mra_scope_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_yes']['value'];
            $inp_2_5_6_2_1_ilac_mra_disclosure_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_yes']['2_5_6_2_1_ilac_mra_disclosure_yes'];
            $inp_2_5_6_2_1_ilac_mra_disclosure_no = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_scope_yes']['2_5_6_2_1_ilac_mra_disclosure_no'];
            $inp_2_5_6_2_1_ilac_mra_compliance_yes = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_compliance_yes'];
            $inp_2_5_6_2_1_ilac_mra_compliance_no = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_compliance_no']['value'];
            $inp_2_5_6_2_1_ilac_mra_compliance_details = $data[11]['2_5_6_2_1_ilac_mra_display_yes']['2_5_6_2_1_ilac_mra_compliance_no']['2_5_6_2_1_ilac_mra_compliance_details'];
   
            $inp_2_5_6_2_2_ilac_mra_compliance_no = $data[12]['2_5_6_2_2_ilac_mra_compliance_no'];
            $inp_2_5_6_2_2_ilac_mra_compliance_yes = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['value'];
            $inp_2_5_6_2_2_ilac_mra_compliance_details = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_ilac_mra_compliance_details'];
            $inp_2_5_6_2_2_mra_compliance_yes = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_mra_compliance_yes'];
            $inp_2_5_6_2_2_mra_compliance_no = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_mra_compliance_no']['value'];
            $inp_2_5_6_2_2_mra_compliance_details = $data[12]['2_5_6_2_2_ilac_mra_compliance_yes']['2_5_6_2_2_mra_compliance_no']['2_5_6_2_2_mra_compliance_details'];


            $inp_3_0_assessment_results = $data[13]['3_0_assessment_results']['value'];
            $inp_3_0_issue_count = $data[13]['3_0_assessment_results']['3_0_issue_count'];
            $inp_3_0_remarks_count = $data[13]['3_0_assessment_results']['3_0_remarks_count'];
            $inp_3_0_deficiencies_details = $data[13]['3_0_assessment_results']['3_0_deficiencies_details'];
            $inp_3_0_deficiency_resolution_date = $data[13]['3_0_assessment_results']['3_0_deficiency_resolution_date'];
            $inp_3_0_offer_agreement = $data[13]['3_0_offer_agreement'];

            $persons = json_encode($persons);

            // dd($id);
            $labReportInfo = LabReportInfo::find($id);

            $labReportInfo->app_certi_lab_notice_id = $noticeId;
            $labReportInfo->inp_2_2_assessment_on_site = $inp_2_2_assessment_on_site;
            $labReportInfo->inp_2_2_assessment_at_tisi = $inp_2_2_assessment_at_tisi;
            $labReportInfo->inp_2_2_remote_assessment = $inp_2_2_remote_assessment;
            $labReportInfo->inp_2_2_self_declaration = $inp_2_2_self_declaration;

            $labReportInfo->inp_2_5_1_structure_compliance = $inp_2_5_1_structure_compliance;
            $labReportInfo->inp_2_5_1_central_management_yes = $inp_2_5_1_central_management_yes;
            $labReportInfo->inp_2_5_1_central_management_no = $inp_2_5_1_central_management_no;
            $labReportInfo->inp_2_5_1_quality_policy_yes = $inp_2_5_1_quality_policy_yes;
            $labReportInfo->inp_2_5_1_quality_policy_no = $inp_2_5_1_quality_policy_no;
            $labReportInfo->inp_2_5_1_risk_assessment_yes = $inp_2_5_1_risk_assessment_yes;
            $labReportInfo->inp_2_5_1_risk_assessment_no = $inp_2_5_1_risk_assessment_no;
            $labReportInfo->inp_2_5_1_other = $inp_2_5_1_other;
            $labReportInfo->inp_2_5_1_text_other1 = $inp_2_5_1_text_other1;
            $labReportInfo->inp_2_5_1_text_other2 = $inp_2_5_1_text_other2;
            $labReportInfo->inp_2_5_1_issue_found = $inp_2_5_1_issue_found;
            $labReportInfo->inp_2_5_1_detail = $inp_2_5_1_detail;

            $labReportInfo->inp_2_5_2_structure_compliance = $inp_2_5_2_structure_compliance;
            $labReportInfo->inp_2_5_2_lab_management = $inp_2_5_2_lab_management;
            $labReportInfo->inp_2_5_2_lab_management_details = $inp_2_5_2_lab_management_details;
            $labReportInfo->inp_2_5_2_staff_assignment_yes = $inp_2_5_2_staff_assignment_yes;
            $labReportInfo->inp_2_5_2_staff_assignment_no = $inp_2_5_2_staff_assignment_no;
            $labReportInfo->inp_2_5_2_responsibility_yes = $inp_2_5_2_responsibility_yes;
            $labReportInfo->inp_2_5_2_responsibility_no = $inp_2_5_2_responsibility_no;
            $labReportInfo->inp_2_5_2_other = $inp_2_5_2_other;
            $labReportInfo->inp_2_5_2_text_other1 = $inp_2_5_2_text_other1;
            $labReportInfo->inp_2_5_2_text_other2 = $inp_2_5_2_text_other2;
            $labReportInfo->inp_2_5_2_issue_found = $inp_2_5_2_issue_found;
            $labReportInfo->inp_2_5_2_detail = $inp_2_5_2_detail;

            $labReportInfo->inp_2_5_3_structure_compliance = $inp_2_5_3_structure_compliance;
            $labReportInfo->inp_2_5_3_personnel_qualification_yes = $inp_2_5_3_personnel_qualification_yes;
            $labReportInfo->inp_2_5_3_personnel_qualification_no = $inp_2_5_3_personnel_qualification_no;
            $labReportInfo->inp_2_5_3_assign_personnel_appropriately_yes = $inp_2_5_3_assign_personnel_appropriately_yes;
            $labReportInfo->inp_2_5_3_assign_personnel_appropriately_no = $inp_2_5_3_assign_personnel_appropriately_no;
            $labReportInfo->inp_2_5_3_training_need_assessment_yes = $inp_2_5_3_training_need_assessment_yes;
            $labReportInfo->inp_2_5_3_training_need_assessment_no = $inp_2_5_3_training_need_assessment_no;
            $labReportInfo->inp_2_5_3_facility_and_environment_control_yes = $inp_2_5_3_facility_and_environment_control_yes;
            $labReportInfo->inp_2_5_3_facility_and_environment_control_no = $inp_2_5_3_facility_and_environment_control_no;
            $labReportInfo->inp_2_5_3_equipment_maintenance_calibration_yes = $inp_2_5_3_equipment_maintenance_calibration_yes;
            $labReportInfo->inp_2_5_3_equipment_maintenance_calibration_no = $inp_2_5_3_equipment_maintenance_calibration_no;
            $labReportInfo->inp_2_5_3_metrology_traceability_yes = $inp_2_5_3_metrology_traceability_yes;
            $labReportInfo->inp_2_5_3_metrology_traceability_no = $inp_2_5_3_metrology_traceability_no;
            $labReportInfo->inp_2_5_3_external_product_service_control_yes = $inp_2_5_3_external_product_service_control_yes;
            $labReportInfo->inp_2_5_3_external_product_service_control_no = $inp_2_5_3_external_product_service_control_no;
            $labReportInfo->inp_2_5_3_other = $inp_2_5_3_other;
            $labReportInfo->inp_2_5_3_text_other1 = $inp_2_5_3_text_other1;
            $labReportInfo->inp_2_5_3_text_other2 = $inp_2_5_3_text_other2;
            $labReportInfo->inp_2_5_3_issue_found = $inp_2_5_3_issue_found;
            $labReportInfo->inp_2_5_3_detail = $inp_2_5_3_detail;

            $labReportInfo->inp_2_5_4_structure_compliance = $inp_2_5_4_structure_compliance;
            $labReportInfo->inp_2_5_4_policy_compliance_yes = $inp_2_5_4_policy_compliance_yes;
            $labReportInfo->inp_2_5_4_policy_compliance_no = $inp_2_5_4_policy_compliance_no;
            $labReportInfo->inp_2_5_4_metrology_sampling_activity_yes = $inp_2_5_4_metrology_sampling_activity_yes;
            $labReportInfo->inp_2_5_4_metrology_sampling_activity_no = $inp_2_5_4_metrology_sampling_activity_no;
            $labReportInfo->inp_2_5_4_procedure_review_request_yes = $inp_2_5_4_procedure_review_request_yes;
            $labReportInfo->inp_2_5_4_procedure_review_request_no = $inp_2_5_4_procedure_review_request_no;
            $labReportInfo->inp_2_5_4_decision_rule_no = $inp_2_5_4_decision_rule_no;
            $labReportInfo->inp_2_5_4_decision_rule_yes = $inp_2_5_4_decision_rule_yes;
            $labReportInfo->inp_2_5_4_agreement_customer_yes = $inp_2_5_4_agreement_customer_yes;
            $labReportInfo->inp_2_5_4_agreement_customer_no = $inp_2_5_4_agreement_customer_no;
            $labReportInfo->inp_2_5_4_method_verification_yes = $inp_2_5_4_method_verification_yes;
            $labReportInfo->inp_2_5_4_method_verification_no = $inp_2_5_4_method_verification_no;
            $labReportInfo->inp_2_5_4_sample_management_yes = $inp_2_5_4_sample_management_yes;
            $labReportInfo->inp_2_5_4_sample_management_no = $inp_2_5_4_sample_management_no;
            $labReportInfo->inp_2_5_4_record_management_yes = $inp_2_5_4_record_management_yes;
            $labReportInfo->inp_2_5_4_record_management_no = $inp_2_5_4_record_management_no;
            $labReportInfo->inp_2_5_4_uncertainty_evaluation_yes = $inp_2_5_4_uncertainty_evaluation_yes;
            $labReportInfo->inp_2_5_4_uncertainty_evaluation_no = $inp_2_5_4_uncertainty_evaluation_no;
            $labReportInfo->inp_2_5_4_result_surveillance_yes = $inp_2_5_4_result_surveillance_yes;
            $labReportInfo->inp_2_5_4_result_surveillance_no = $inp_2_5_4_result_surveillance_no;
            $labReportInfo->inp_2_5_4_proficiency_testing_no = $inp_2_5_4_proficiency_testing_no;
            $labReportInfo->inp_2_5_4_proficiency_testing_yes = $inp_2_5_4_proficiency_testing_yes;
            $labReportInfo->inp_2_5_4_test_participation = $inp_2_5_4_test_participation;
            $labReportInfo->inp_2_5_4_test_participation_details1 = $inp_2_5_4_test_participation_details1;
            $labReportInfo->inp_2_5_4_test_participation_details2 = $inp_2_5_4_test_participation_details2;
            $labReportInfo->inp_2_5_4_test_calibration = $inp_2_5_4_test_calibration;
            $labReportInfo->inp_2_5_4_calibration_details = $inp_2_5_4_calibration_details;
            $labReportInfo->inp_2_5_4_acceptance_criteria_yes = $inp_2_5_4_acceptance_criteria_yes;
            $labReportInfo->inp_2_5_4_acceptance_criteria_no = $inp_2_5_4_acceptance_criteria_no;
            $labReportInfo->inp_2_5_4_acceptance_criteria1 = $inp_2_5_4_acceptance_criteria1;
            $labReportInfo->inp_2_5_4_acceptance_criteria2 = $inp_2_5_4_acceptance_criteria2;
            $labReportInfo->inp_2_5_4_lab_comparison = $inp_2_5_4_lab_comparison;
            $labReportInfo->inp_2_5_4_lab_comparison_details1 = $inp_2_5_4_lab_comparison_details1;
            $labReportInfo->inp_2_5_4_lab_comparison_details2 = $inp_2_5_4_lab_comparison_details2;
            $labReportInfo->inp_2_5_4_lab_comparison_test = $inp_2_5_4_lab_comparison_test;
            $labReportInfo->inp_2_5_4_lab_comparison_test_details = $inp_2_5_4_lab_comparison_test_details;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_yes = $inp_2_5_4_lab_comparison_test_is_accept_yes;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_no = $inp_2_5_4_lab_comparison_test_is_accept_no;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details1 = $inp_2_5_4_lab_comparison_test_is_accept_details1;
            $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details2 = $inp_2_5_4_lab_comparison_test_is_accept_details2;
            $labReportInfo->inp_2_5_4_test_participation2 = $inp_2_5_4_test_participation2;
            $labReportInfo->inp_2_5_4_other_methods = $inp_2_5_4_other_methods;
            $labReportInfo->inp_2_5_4_other_methods_details1 = $inp_2_5_4_other_methods_details1;
            $labReportInfo->inp_2_5_4_other_methods_details2 = $inp_2_5_4_other_methods_details2;
            $labReportInfo->inp_2_5_4_report_approval_review_yes = $inp_2_5_4_report_approval_review_yes;
            $labReportInfo->inp_2_5_4_report_approval_review_no = $inp_2_5_4_report_approval_review_no;
            $labReportInfo->inp_2_5_4_decision_rule2_no = $inp_2_5_4_decision_rule2_no;
            $labReportInfo->inp_2_5_4_decision_rule2_yes = $inp_2_5_4_decision_rule2_yes;
            $labReportInfo->inp_2_5_4_document_for_criteria_yes = $inp_2_5_4_document_for_criteria_yes;
            $labReportInfo->inp_2_5_4_document_for_criteria_no = $inp_2_5_4_document_for_criteria_no;
            $labReportInfo->inp_2_5_4_complaint_process_no = $inp_2_5_4_complaint_process_no;
            $labReportInfo->inp_2_5_4_complaint_process_yes = $inp_2_5_4_complaint_process_yes;
            $labReportInfo->inp_2_5_4_complaint_number = $inp_2_5_4_complaint_number;
            $labReportInfo->inp_2_5_4_non_conformance_process_no = $inp_2_5_4_non_conformance_process_no;
            $labReportInfo->inp_2_5_4_non_conformance_process_yes = $inp_2_5_4_non_conformance_process_yes;
            $labReportInfo->inp_2_5_4_non_conformance_number = $inp_2_5_4_non_conformance_number;
            $labReportInfo->inp_2_5_4_data_control_yes = $inp_2_5_4_data_control_yes;
            $labReportInfo->inp_2_5_4_data_control_no = $inp_2_5_4_data_control_no;
            $labReportInfo->inp_2_5_4_data_transfer_control_yes = $inp_2_5_4_data_transfer_control_yes;
            $labReportInfo->inp_2_5_4_data_transfer_control_no = $inp_2_5_4_data_transfer_control_no;
            $labReportInfo->inp_2_5_4_other = $inp_2_5_4_other;
            $labReportInfo->inp_2_5_4_text_other1 = $inp_2_5_4_text_other1;
            $labReportInfo->inp_2_5_4_text_other2 = $inp_2_5_4_text_other2;
            $labReportInfo->inp_2_5_4_issue_found = $inp_2_5_4_issue_found;
            $labReportInfo->inp_2_5_4_detail = $inp_2_5_4_detail;

            $labReportInfo->inp_2_5_5_structure_compliance = $inp_2_5_5_structure_compliance;
            $labReportInfo->inp_2_5_5_data_control_option_a = $inp_2_5_5_data_control_option_a;
            $labReportInfo->inp_2_5_5_data_control_option_b = $inp_2_5_5_data_control_option_b;
            $labReportInfo->inp_2_5_5_data_control_policy_yes = $inp_2_5_5_data_control_policy_yes;
            $labReportInfo->inp_2_5_5_data_control_policy_no = $inp_2_5_5_data_control_policy_no;
            $labReportInfo->inp_2_5_5_document_control_yes = $inp_2_5_5_document_control_yes;
            $labReportInfo->inp_2_5_5_document_control_no = $inp_2_5_5_document_control_no;
            $labReportInfo->inp_2_5_5_record_keeping_yes = $inp_2_5_5_record_keeping_yes;
            $labReportInfo->inp_2_5_5_record_keeping_no = $inp_2_5_5_record_keeping_no;
            $labReportInfo->inp_2_5_5_risk_management_yes = $inp_2_5_5_risk_management_yes;
            $labReportInfo->inp_2_5_5_risk_management_no = $inp_2_5_5_risk_management_no;
            $labReportInfo->inp_2_5_5_risk_opportunity_yes = $inp_2_5_5_risk_opportunity_yes;
            $labReportInfo->inp_2_5_5_risk_opportunity_no = $inp_2_5_5_risk_opportunity_no;
            $labReportInfo->inp_2_5_5_improvement_opportunity_yes = $inp_2_5_5_improvement_opportunity_yes;
            $labReportInfo->inp_2_5_5_improvement_opportunity_no = $inp_2_5_5_improvement_opportunity_no;
            $labReportInfo->inp_2_5_5_non_conformance_yes = $inp_2_5_5_non_conformance_yes;
            $labReportInfo->inp_2_5_5_non_conformance_no = $inp_2_5_5_non_conformance_no;
            $labReportInfo->inp_2_5_5_internal_audit_no = $inp_2_5_5_internal_audit_no;
            $labReportInfo->inp_2_5_5_internal_audit_yes = $inp_2_5_5_internal_audit_yes;
            $labReportInfo->inp_2_5_5_audit_frequency = $inp_2_5_5_audit_frequency;
            $labReportInfo->inp_2_5_5_last_audit_date = $inp_2_5_5_last_audit_date;
            $labReportInfo->inp_2_5_5_audit_issues = $inp_2_5_5_audit_issues;
            $labReportInfo->inp_2_5_5_management_review_no = $inp_2_5_5_management_review_no;
            $labReportInfo->inp_2_5_5_management_review_yes = $inp_2_5_5_management_review_yes;
            $labReportInfo->inp_2_5_5_last_review_date = $inp_2_5_5_last_review_date;
            $labReportInfo->inp_2_5_5_other = $inp_2_5_5_other;
            $labReportInfo->inp_2_5_5_text_other1 = $inp_2_5_5_text_other1;
            $labReportInfo->inp_2_5_5_text_other2 = $inp_2_5_5_text_other2;
            $labReportInfo->inp_2_5_5_issue_found = $inp_2_5_5_issue_found;
            $labReportInfo->inp_2_5_5_detail = $inp_2_5_5_detail;

            $labReportInfo->inp_2_5_6_1_1_management_review_no = $inp_2_5_6_1_1_management_review_no;
            $labReportInfo->inp_2_5_6_1_1_management_review_yes = $inp_2_5_6_1_1_management_review_yes;
            $labReportInfo->inp_2_5_6_1_1_scope_certified_no = $inp_2_5_6_1_1_scope_certified_no;
            $labReportInfo->inp_2_5_6_1_1_scope_certified_yes = $inp_2_5_6_1_1_scope_certified_yes;
            $labReportInfo->inp_2_5_6_1_1_activities_not_certified_yes = $inp_2_5_6_1_1_activities_not_certified_yes;
            $labReportInfo->inp_2_5_6_1_1_activities_not_certified_no = $inp_2_5_6_1_1_activities_not_certified_no;
            $labReportInfo->inp_2_5_6_1_1_accuracy_yes = $inp_2_5_6_1_1_accuracy_yes;
            $labReportInfo->inp_2_5_6_1_1_accuracy_no = $inp_2_5_6_1_1_accuracy_no;
            $labReportInfo->inp_2_5_6_1_1_accuracy_detail = $inp_2_5_6_1_1_accuracy_detail;

            $labReportInfo->inp_2_5_6_1_2_multi_site_display_no = $inp_2_5_6_1_2_multi_site_display_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_display_yes = $inp_2_5_6_1_2_multi_site_display_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_scope_no = $inp_2_5_6_1_2_multi_site_scope_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_scope_yes = $inp_2_5_6_1_2_multi_site_scope_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_activities_not_certified_yes = $inp_2_5_6_1_2_multi_site_activities_not_certified_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_activities_not_certified_no = $inp_2_5_6_1_2_multi_site_activities_not_certified_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_yes = $inp_2_5_6_1_2_multi_site_accuracy_yes;
            $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_no = $inp_2_5_6_1_2_multi_site_accuracy_no;
            $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_details = $inp_2_5_6_1_2_multi_site_accuracy_details;

            $labReportInfo->inp_2_5_6_1_3_certification_status_yes = $inp_2_5_6_1_3_certification_status_yes;
            $labReportInfo->inp_2_5_6_1_3_certification_status_no = $inp_2_5_6_1_3_certification_status_no;
            $labReportInfo->inp_2_5_6_1_3_certification_status_details = $inp_2_5_6_1_3_certification_status_details;

            $labReportInfo->inp_2_5_6_1_4_display_other_no = $inp_2_5_6_1_4_display_other_no;
            $labReportInfo->inp_2_5_6_1_4_display_other_yes = $inp_2_5_6_1_4_display_other_yes;
            $labReportInfo->inp_2_5_6_1_4_display_other_details = $inp_2_5_6_1_4_display_other_details;
            $labReportInfo->inp_2_5_6_1_4_certification_status_yes = $inp_2_5_6_1_4_certification_status_yes;
            $labReportInfo->inp_2_5_6_1_4_certification_status_no = $inp_2_5_6_1_4_certification_status_no;
            $labReportInfo->inp_2_5_6_1_4_certification_status_details = $inp_2_5_6_1_4_certification_status_details;

            $labReportInfo->inp_2_5_6_2_lab_availability_yes = $inp_2_5_6_2_lab_availability_yes;
            $labReportInfo->inp_2_5_6_2_lab_availability_no = $inp_2_5_6_2_lab_availability_no;

            $labReportInfo->inp_2_5_6_2_1_ilac_mra_display_no = $inp_2_5_6_2_1_ilac_mra_display_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_display_yes = $inp_2_5_6_2_1_ilac_mra_display_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_scope_no = $inp_2_5_6_2_1_ilac_mra_scope_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_scope_yes = $inp_2_5_6_2_1_ilac_mra_scope_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_disclosure_yes = $inp_2_5_6_2_1_ilac_mra_disclosure_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_disclosure_no = $inp_2_5_6_2_1_ilac_mra_disclosure_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_yes = $inp_2_5_6_2_1_ilac_mra_compliance_yes;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_no = $inp_2_5_6_2_1_ilac_mra_compliance_no;
            $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_details = $inp_2_5_6_2_1_ilac_mra_compliance_details;

            $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_no = $inp_2_5_6_2_2_ilac_mra_compliance_no;
            $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_yes = $inp_2_5_6_2_2_ilac_mra_compliance_yes;
            $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_details = $inp_2_5_6_2_2_ilac_mra_compliance_details;
            $labReportInfo->inp_2_5_6_2_2_mra_compliance_yes = $inp_2_5_6_2_2_mra_compliance_yes;
            $labReportInfo->inp_2_5_6_2_2_mra_compliance_no = $inp_2_5_6_2_2_mra_compliance_no;
            $labReportInfo->inp_2_5_6_2_2_mra_compliance_details = $inp_2_5_6_2_2_mra_compliance_details;

            $labReportInfo->inp_3_0_assessment_results = $inp_3_0_assessment_results;
            $labReportInfo->inp_3_0_issue_count = $inp_3_0_issue_count;
            $labReportInfo->inp_3_0_remarks_count = $inp_3_0_remarks_count;
            $labReportInfo->inp_3_0_deficiencies_details = $inp_3_0_deficiencies_details;
            $labReportInfo->inp_3_0_deficiency_resolution_date = $inp_3_0_deficiency_resolution_date;
            $labReportInfo->inp_3_0_offer_agreement = $inp_3_0_offer_agreement;

            $labReportInfo->persons = $persons;
            $labReportInfo->status = $submitType;

            $labReportInfo->save();

            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');

            SignAssessmentReportTransaction::where('report_info_id', $labReportInfo->id)
                                            ->where('certificate_type',2)
                                            ->where('report_type',1)
                                            ->delete();
            foreach ($signers as $signer) {
                // ตรวจสอบความถูกต้องของข้อมูล
                if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $labReportInfo->id,
                    'signer_id' => $signer['signer_id'],
                    'signer_name' => $signer['signer_name'],
                    'signer_position' => $signer['signer_position'],
                    'signer_order' => $signer['id'],
                    'view_url' => $url . '/certify/save_assessment/view-lab-info/'. $noticeId,
                    'certificate_type' => 2,
                    'report_type' => 1,
                    'app_id' => $labReportInfo->notice->applicant->app_no,
                ]);
            }

            if ((int)$submitType === 2) {

                $app = $labReportInfo->notice->applicant;

                $config = HP::getConfig();
                $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
    
                $data_app =  [
                                'certi_lab'     => $app,
                                'url'           =>$url_center.'certify/assessment-report-assignment',
                                'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                            ];
          
            
                $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                            $app->id,
                                                            (new CertiLab)->getTable(),
                                                            $labReportInfo->id,
                                                            (new Notice)->getTable(),
                                                            1,
                                                            'ลงนามรายงานตรวจประเมิน',
                                                            view('mail.Lab.mail_lab_report_signer', $data_app),
                                                            $app->created_by,
                                                            $app->agent_id,
                                                            auth()->user()->getKey(),
                                                            !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                            $app->email,
                                                            !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                            !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                            null
                                                            );

                $uniqueSignerIds = collect($signers) // แปลงเป็น Collection
                        ->pluck('signer_id') // ดึงเฉพาะค่า signer_id
                        ->unique() // กรองให้เหลือค่าไม่ซ้ำกัน
                        ->values(); // รีเซ็ต key ของ array
                
                $userIds = Signer::whereIn('id',$uniqueSignerIds)->pluck('user_register_id')->toArray();
                $signerEmails = User::whereIn('runrecno',$userIds)->pluck('reg_email')->toArray();
    
                $html = new MailLabReportSigner($data_app);
                $mail = Mail::to($signerEmails)->send($html);
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
      
            }

            // ตรวจสอบค่า
            return response()->json([
                'message' => 'Data updated successfully',
                'data' => $data
            ]);
        }

        public function checkCompleteReportOneSign(Request $request)
        {
            $noticeId = $request->notice_id;
            $labReportInfo = LabReportInfo::where('app_certi_lab_notice_id' ,$noticeId)->first();
            // if($labReportInfo != null){

            $signedCount = SignAssessmentReportTransaction::where('report_info_id', $labReportInfo->id)
            ->where('certificate_type',2)
            ->where('report_type',1)
            ->where('approval',1)
            ->count();


            $recordCount = SignAssessmentReportTransaction::where('report_info_id', $labReportInfo->id)
                            ->where('certificate_type',2)
                            ->where('report_type',1)
                            ->count();
            // dd($signedCount,$recordCount);

            if($signedCount == 3)
            {
                return response()->json([
                    'message' => true,
                    'record_count' => 99
                ]);
            }else {
                return response()->json([
                    'message' => false,
                    'record_count' => $recordCount
                ]);
            }

           
        }

        public function checkCompleteReportTwoSign(Request $request)
        {
            $noticeId = $request->notice_id;
            $labReportInfo = LabReportTwoInfo::where('app_certi_lab_notice_id' ,$noticeId)->first();


            $signedCount = SignAssessmentReportTransaction::where('report_info_id', $labReportInfo->id)
            ->where('certificate_type',2)
            ->where('report_type',2)
            ->where('approval',1)
            ->count();


            $recordCount = SignAssessmentReportTransaction::where('report_info_id', $labReportInfo->id)
                            ->where('certificate_type',2)
                            ->where('report_type',2)
                            ->count();
            // dd($signedCount,$recordCount);

            if($signedCount == 3)
            {
                return response()->json([
                    'message' => true,
                    'record_count' => 99
                ]);
            }else {
                return response()->json([
                    'message' => false,
                    'record_count' => $recordCount
                ]);
            }

           
        }


        public function viewLabReportTwoInfo($id)
        {
            // dd('ok');
            $notice = Notice::find($id);
            $labReportInfo = LabReportTwoInfo::where('app_certi_lab_notice_id',$notice->id)->first();
            // $notice = $labReportInfo->notice;
            $assessment = $notice->assessment;
            $app_certi_lab = $notice->applicant;
            $boardAuditor = $assessment->board_auditor_to;
            $id = $boardAuditor->auditor_id;
            
            // dd($notice,$assessment,$boardAuditor,$app_certi_lab);
    
            $groups = $boardAuditor->groups;
    
            $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id
    
            $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
    
            foreach ($groups as $group) {
                $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
                $auditors = $group->auditors; // $auditors เป็น Collection
    
                // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
                if (!isset($statusAuditorMap[$statusAuditorId])) {
                    $statusAuditorMap[$statusAuditorId] = [];
                }
    
                // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
                foreach ($auditors as $auditor) {
                    $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
                }
            }
    
            $uniqueAuditorIds = array_unique($auditorIds);
    
            $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();
    
            $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);
    
            $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
            $dateRange = "";
    
            if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
                if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                    // ถ้าเป็นวันเดียวกัน
                    $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
                } else {
                    // ถ้าเป็นคนละวัน
                    $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                                " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
                }
            }
    
            $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();
            $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
            // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
            if ($boardAuditorExpert && $boardAuditorExpert->expert) {
                // แปลงข้อมูล JSON ใน expert กลับเป็น array
                $categories = json_decode($boardAuditorExpert->expert, true);
            
                // ถ้ามีหลายรายการ
                if (count($categories) > 1) {
                    // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
                    $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
                    $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
                } elseif (count($categories) == 1) {
                    // ถ้ามีแค่รายการเดียว
                    $experts = $categories[0];
                } else {
                    $experts = ''; // ถ้าไม่มีข้อมูล
                }
            
            }
    
            $scope_branch = "";
            if ($certi_lab->lab_type == 3){
                $scope_branch = $certi_lab->BranchTitle;
            }else if($certi_lab->lab_type == 4)
            {
                $scope_branch = $certi_lab->ClibrateBranchTitle;
            }
    
            $data = new stdClass();
    
            $data->header_text1 = '';
            $data->header_text2 = '';
            $data->header_text3 = '';
            $data->header_text4 = $certi_lab->app_no;
            $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
            $data->lab_name = $certi_lab->lab_name;
            $data->scope_branch = $scope_branch;
            $data->app_np = 'ทดสอบ ๑๖๗๑';
            $data->certificate_no = '13-LB0037';
            $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
            $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
            $data->experts = $experts;
            $data->date_range = $dateRange;
            $data->statusAuditorMap = $statusAuditorMap;
    
            // $notice = Notice::find($notice_id);
            $assessment = $notice->assessment;
            // dd($statusAuditorMap);
            $app_certi_lab = $notice->applicant;
            $boardAuditor = $assessment->board_auditor_to;
            $id = $boardAuditor->auditor_id;
            $labRequest = null;
            
            if($app_certi_lab->lab_type == 4){
                $labRequest = LabCalRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            }else if($app_certi_lab->lab_type == 3)
            {
                $labRequest = LabTestRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            }

            $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)
                                                ->where('certificate_type',2)
                                                ->where('report_type',2)
                                                ->get();
            $approveNoticeItems = NoticeItem::where('app_certi_lab_notice_id', $notice->id)
                ->whereNotNull('attachs')
                ->where('status',1)
                ->where('file_status',1)
                ->get();
            $labInformation = $app_certi_lab->information;
            return view('certificate.labs.assessment-labs.view-report', [
                'labReportInfo' => $labReportInfo,
                'data' => $data,
                'notice' => $notice,
                'assessment' => $assessment,
                'boardAuditor' => $boardAuditor,
                'certi_lab' => $app_certi_lab,
                'labRequest' => $labRequest,
                'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
                'approveNoticeItems' => $approveNoticeItems,
                'labInformation' => $labInformation[0],
                'id' => $id
            ]);


            // return view('certificate.labs.assessment-labs.view-report', [
            //     'data' => $data,
            //     'assessment' => $assessment,
            //     'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            //    //  'tracking' => $tracking,
            //     'certi_lab' => $app_certi_lab,
            //     'labRequest' => $labRequest,
            //     'labReportInfo' => $labReportInfo,
            //     'labInformation' => $labInformation[0]
            // ]);
        }



     
        public function updateLabReportTwoInfo(Request $request)
        {
            // dd($request->all());
            // รับค่าจาก JSON
             $data = $request->input('data'); // ข้อมูลใน key "data"
             $persons = $request->input('persons'); // ข้อมูลใน key "data"
             $id = $request->input('notice_id');
            //  $id = $request->input('id');
             $signers = $request->input('signer', []);
             $submitType = $request->input('submit_type');
             $notice = Notice::find($id);
            
            // get 2.2
             $inp_2_2_assessment_on_site_chk = $data[0]['inp_2_2_assessment_on_site_chk'];
             $inp_2_2_assessment_at_tisi_chk = $data[0]['inp_2_2_assessment_at_tisi_chk'];
             $inp_2_2_remote_assessment_chk = $data[0]['inp_2_2_remote_assessment_chk'];
             $inp_2_2_self_declaration_chk = $data[0]['inp_2_2_self_declaration_chk'];
             $inp_2_2_bug_fix_evidence_chk = $data[0]['inp_2_2_bug_fix_evidence_chk'];
     
            // get 2.4
             $inp_2_4_defects_and_remarks_text = $data[1]['inp_2_4_defects_and_remarks_text'];
             $inp_2_4_doc_reference_date_text = $data[1]['inp_2_4_doc_reference_date_text'];
             $inp_2_4_doc_sent_date1_text = $data[1]['inp_2_4_doc_sent_date1_text'];
             $inp_2_4_doc_sent_date2_text = $data[1]['inp_2_4_doc_sent_date2_text'];
             $inp_2_4_lab_bug_fix_completed_chk = $data[1]['inp_2_4_lab_bug_fix_completed_chk'];
             $inp_2_4_fix_approved_chk = $data[1]['inp_2_4_fix_approved_chk'];
             $inp_2_4_approved_text = $data[1]['inp_2_4_approved_text'];
             $inp_2_4_remain_text = $data[1]['inp_2_4_remain_text'];
     
             // get 3.0
             $inp_3_lab_fix_all_issues_chk = $data[2]['inp_3_lab_fix_all_issues_chk'];
             $inp_3_lab_fix_some_issues_chk = $data[2]['inp_3_lab_fix_some_issues_chk'];
             $inp_3_approved_text = $data[2]['inp_3_approved_text'];
             $inp_3_remain_text = $data[2]['inp_3_remain_text'];
             $inp_3_lab_fix_failed_issues_chk = $data[2]['inp_3_lab_fix_failed_issues_chk']['value'];
             $inp_3_lab_fix_failed_issues_yes_chk = $data[2]['inp_3_lab_fix_failed_issues_chk']['inp_3_lab_fix_failed_issues_yes_chk'];
             $inp_3_lab_fix_failed_issues_no_chk = $data[2]['inp_3_lab_fix_failed_issues_chk']['inp_3_lab_fix_failed_issues_no_chk'];
     
             $labReportTwoInfo = LabReportTwoInfo::where('app_certi_lab_notice_id',$id)->first();
             $labReportTwoInfo->inp_2_2_assessment_on_site_chk = $inp_2_2_assessment_on_site_chk;
             $labReportTwoInfo->inp_2_2_assessment_at_tisi_chk = $inp_2_2_assessment_at_tisi_chk;
             $labReportTwoInfo->inp_2_2_remote_assessment_chk = $inp_2_2_remote_assessment_chk;
             $labReportTwoInfo->inp_2_2_self_declaration_chk = $inp_2_2_self_declaration_chk;
             $labReportTwoInfo->inp_2_2_bug_fix_evidence_chk = $inp_2_2_bug_fix_evidence_chk;
     
             $labReportTwoInfo->inp_2_4_defects_and_remarks_text = $inp_2_4_defects_and_remarks_text;
             $labReportTwoInfo->inp_2_4_doc_reference_date_text = $inp_2_4_doc_reference_date_text;
             $labReportTwoInfo->inp_2_4_doc_sent_date1_text = $inp_2_4_doc_sent_date1_text;
             $labReportTwoInfo->inp_2_4_doc_sent_date2_text = $inp_2_4_doc_sent_date2_text;
             $labReportTwoInfo->inp_2_4_lab_bug_fix_completed_chk = $inp_2_4_lab_bug_fix_completed_chk;
             $labReportTwoInfo->inp_2_4_fix_approved_chk = $inp_2_4_fix_approved_chk;
             $labReportTwoInfo->inp_2_4_approved_text = $inp_2_4_approved_text;
             $labReportTwoInfo->inp_2_4_remain_text = $inp_2_4_remain_text;
     
             $labReportTwoInfo->inp_3_lab_fix_all_issues_chk = $inp_3_lab_fix_all_issues_chk;
             $labReportTwoInfo->inp_3_lab_fix_some_issues_chk = $inp_3_lab_fix_some_issues_chk;
             $labReportTwoInfo->inp_3_approved_text = $inp_3_approved_text;
             $labReportTwoInfo->inp_3_remain_text = $inp_3_remain_text;
             $labReportTwoInfo->inp_3_lab_fix_failed_issues_chk = $inp_3_lab_fix_failed_issues_chk;
             $labReportTwoInfo->inp_3_lab_fix_failed_issues_yes_chk = $inp_3_lab_fix_failed_issues_yes_chk;
             $labReportTwoInfo->inp_3_lab_fix_failed_issues_no_chk = $inp_3_lab_fix_failed_issues_no_chk;
     
             $labReportTwoInfo->status = $submitType;
     
        
             $labReportTwoInfo->save();
     
             $config = HP::getConfig();
             $url  =   !empty($config->url_center) ? $config->url_center : url('');
     
             SignAssessmentReportTransaction::where('report_info_id', $labReportTwoInfo->id)
                                                 ->where('certificate_type',2)
                                                 ->where('report_type',2)
                                                 ->delete();
            
             foreach ($signers as $signer) {
                 // ตรวจสอบความถูกต้องของข้อมูล
                 if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                     continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                 }
     
                 SignAssessmentReportTransaction::create([
                     'report_info_id' => $labReportTwoInfo->id,
                     'signer_id' => $signer['signer_id'],
                     'signer_name' => $signer['signer_name'],
                     'signer_position' => $signer['signer_position'],
                     'signer_order' => $signer['id'],
                     'view_url' => $url . '/certify/save_assessment/view-lab-report2-info/'. $notice->id ,
                     'certificate_type' => 2,
                     'report_type' => 2,
                     'app_id' => $notice->applicant->app_no,
                 ]);
             }
             
             if ((int)$submitType === 2) {
                //  dd('donllle');
                $labReportTwoInfo->notified_signers = 1;
                $labReportTwoInfo->save();
                 $app = $labReportTwoInfo->notice->applicant;

                 $config = HP::getConfig();
                 $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                 $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
                 $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                 $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
     
                 $data_app =  [
                                 'certi_lab'     => $app,
                                 'url'           =>$url_center.'certify/assessment-report-assignment',
                                 'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                 'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                                 'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                             ];
           
             
                 $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                             $app->id,
                                                             (new CertiLab)->getTable(),
                                                             $labReportTwoInfo->id,
                                                             (new Notice)->getTable(),
                                                             1,
                                                             'ลงนามรายงานตรวจประเมิน',
                                                             view('mail.Lab.mail_lab_report_signer', $data_app),
                                                             $app->created_by,
                                                             $app->agent_id,
                                                             auth()->user()->getKey(),
                                                             !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                             $app->email,
                                                             !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                             !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                             null
                                                             );
 
                 $uniqueSignerIds = collect($signers) // แปลงเป็น Collection
                         ->pluck('signer_id') // ดึงเฉพาะค่า signer_id
                         ->unique() // กรองให้เหลือค่าไม่ซ้ำกัน
                         ->values(); // รีเซ็ต key ของ array
                 
                 $userIds = Signer::whereIn('id',$uniqueSignerIds)->pluck('user_register_id')->toArray();
                 $signerEmails = User::whereIn('runrecno',$userIds)->pluck('reg_email')->toArray();
     
                 $html = new MailLabReportSigner($data_app);
                 $mail = Mail::to($signerEmails)->send($html);
                 if(is_null($mail) && !empty($log_email)){
                     HP::getUpdateCertifyLogEmail($log_email->id);
                 }
       
                
             }
     
     
     
             // dd($request->all(),$labReportTwoInfo);
             return response()->json([
                 'message' => 'Data updated successfully',
                 'data' => $data
             ]);
        }




        
        public function emailToExpert(Request $request)
        {
            // dd($request->all());
            $notice = Notice::find($request->notice_id);
            $expertEmails = $request->selectedEmails;
            $app = $notice->applicant;

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';

            // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
            $data_app =  [
                            'certi_lab'     => $app,
                            'url'           => $url_center.'/create-by-expert/' . $notice->id .'?token='.$notice->expert_token,
                            'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                            'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                            'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                        ];
      
        
            $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                        $app->id,
                                                        (new CertiLab)->getTable(),
                                                        $app->id,
                                                        (new Notice)->getTable(),
                                                        1,
                                                        'เพิ่มรายการข้อบกพร่อง / ข้อสังเกต',
                                                        view('mail.Lab.mail_lab_expert', $data_app),
                                                        $app->created_by,
                                                        $app->agent_id,
                                                        auth()->user()->getKey(),
                                                        !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                        $app->email,
                                                        !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                        !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                        null
                                                        );

            $html = new MailToLabExpert($data_app);
            $mail = Mail::to($expertEmails)->send($html);
            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }
        }
}

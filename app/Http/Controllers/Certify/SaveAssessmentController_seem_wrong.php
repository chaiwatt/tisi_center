<?php

namespace App\Http\Controllers\Certify;

use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\NoticeItem;
use App\Models\Certify\Applicant\Information;
 
use App\Models\Certify\CertificateHistory;
use App\User;
use Carbon\Carbon;
use  App\Models\Certify\BoardAuditor;
use  App\Models\Certify\BoardAuditorGroup;
use  App\Models\Certify\BoardAuditorInformation;

use App\Models\Certify\Applicant\Report;
use App\Models\Certify\Applicant\AssessmentExaminer;
use App\Models\Certify\Applicant\CheckExaminer;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_encode;
use HP;
use stdClass;
use Storage;
use Illuminate\Support\Facades\Mail;  
use App\Mail\Lab\CertifySaveAssessment;
use App\Mail\Lab\CertifyConfirmAssessment;
use App\Mail\Lab\CertifyCheckSaveAssessment;

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
        // dd('here');
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


        
        $notices = $Query ->orderby('id','desc')->sortable()->paginate($filter['perPage']);
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
    public function create($id=null)
    {

        $NoticeItem = [new NoticeItem];
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
 

       $app = new CertiLab();

        return view('certify.save_assessment.create', compact('app','NoticeItem','app_no','id'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'auditor_id' => 'required',
            'savedate' => 'required'
        ]);

        // dd($request->all());
    //  try {     
 
        $auditor = BoardAuditor::findOrFail($request->auditor_id);

        if(!is_null($auditor) && !empty($auditor->applicant)){
             $app =     $auditor->applicant;

 
             $notices = $request->input('notice');
             $report = $request->input('report');
             $noks = $request->input('nok');
             $types = $request->input('type');
             $founds = $request->input('found');
          
     
       
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
            
 
            
             if($request->file && $request->hasFile('file')){   // รายงานการตรวจประเมิน
                $n->file =    $this->store_File($request->file,$app->app_no)  ??  null ;
                $n->file_client_name =  HP::ConvertCertifyFileName($request->file->getClientOriginalName());
             }

             if($n->report_status == 2){ // ไม่มีข้อบกพร่อง
                if($request->file_scope  && $request->hasFile('file_scope')){    // รายงาน Scope
                    foreach ($request->file_scope as $key => $itme) {
                        if(!is_null($itme)){
                            $list  = new  stdClass;
                            $list->attachs =   $this->store_File($itme,$app->app_no) ;
                            $list->attachs_client_name =  HP::ConvertCertifyFileName($itme->getClientOriginalName());
                            $scope[] = $list;
                        }
                    }
                    $n->file_scope = json_encode($scope);
                 }
            
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

             $n->save();


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
                        $item->save();
                    }
                }
            }

 
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
 
            if(($n->degree == 1 || $n->degree == 8) && $n->report_status == 1){
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
                            $report = new Report; 
                            $report->app_certi_assessment_id = $n->app_certi_assessment_id;
                            $report->app_certi_lab_id        = $app->id;
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
               }
            }

          return redirect()->route('save_assessment.index', ['app' => $app ? $app->id : ''])->with('flash_message', 'สร้างเรียบร้อยแล้ว');
        }

        return redirect('certify/save_assessment')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');

    // } catch (\Exception $e) {
    //     return redirect('certify/save_assessment')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    // } 


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
 
        return view('certify.save_assessment.edit', compact('notice', 'app','auditors','NoticeItem'));
    }

    public function assess_edit(Notice $notice, CertiLab $app = null)
    {
        // dd('ok');
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
 
        $app->assessment_date = HP::revertDate($notice->assessment_date->format('Y-m-d'),true) ?? null;
        $app->DataGroupeTitle = $notice->DataGroupeTitle ?? null;
        return view('certify/save_assessment/form_assess', compact('notice', 'app','NoticeItem','find_notice','previousUrl'));
    }

    public function assess_update(Request $request, Notice $notice, CertiLab $app = null)
    {
        
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
                        $notice_itme->save(); 
                    }
                  } 

                  if($request->hasFile('file_car')){
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
            if($request->file_car && $request->hasFile('file_car')){
                $auditors->date_car              =  date('Y-m-d') ;
                $auditors->file_car              =   $this->store_File($request->file_car,$app->app_no) ;
                $auditors->file_car_client_name =  HP::ConvertCertifyFileName($request->file_car->getClientOriginalName()) ;
            }

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
                        $report = new Report; 
                        $report->app_certi_assessment_id = $auditors->app_certi_assessment_id;
                        $report->app_certi_lab_id        = $app->id;
                        $report->save();

                        // $app->review   = 1;
                        $app->status   = 20; 
                        $app->save(); 
                    }
                }

            }

        }else{
                 // รายงาน Scope
                if($request->file_scope  && $request->hasFile('file_scope')){
                    foreach ($request->file_scope as $index => $item){
                    
                            $list                       = new  stdClass;
                            $list->attachs              = $this->store_File($item,$app->app_no) ;
                            $list->attachs_client_name  = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $scope[]                    = $list;
                    }
                    $auditors->file_scope =   isset($scope)   ?  json_encode($scope)  :  null;
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

                  //  สรุปรายงานและเสนออนุกรรมการฯ
                  $report = new Report; 
                  $report->app_certi_assessment_id = $app->assessment->id ?? null;
                  $report->app_certi_lab_id = $app->id;
                  $report->save();
        }
        return redirect('certify/save_assessment')->with('message', 'เรียบร้อยแล้ว!');
    } 
    public function update(Request $request, Notice $notice, CertiLab $app = null)
    {

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
    if($request->file_scope && $request->hasFile('file_scope')){    // รายงาน Scope
        foreach ($request->file_scope as $key => $itme) {
            if(!is_null($itme)){
                $list  = new  stdClass;
                $list->attachs =   $this->store_File($itme,$formApp->app_no) ;
                $list->attachs_client_name =   HP::ConvertCertifyFileName($itme->getClientOriginalName());
                $scope[] = $list;
            }
        }
        $notice->file_scope = json_encode($scope);
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
                
                //  สรุปรายงานและเสนออนุกรรมการฯ
                $report = new Report; 
                $report->app_certi_assessment_id = $formApp->assessment->id ?? null;
                $report->app_certi_lab_id = $formApp->id;
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
            return response()->json([
                                'group_id' => $board_auditor->assessment_to->id ?? '',
                                'app'   => $board_auditor->applicant,
                                'created_at' => !empty($board_auditor->created_at) ?  HP::revertDate($board_auditor->created_at->format('Y-m-d'),true) : null  ?? '',
                                'message' =>true
                          ], 200); 
        }else{
             return response()->json([  'message' => false   ], 200); 
        }
 
 
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
            $NoticeItem = NoticeItem::select('remark','no','type','reporter','reporter_id','status','report','comment','comment_file','attachs','file_status','details','attachs_client_name')->where('app_certi_lab_notice_id',$data->id)->get()->toArray();
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
}

<?php

namespace App\Http\Controllers\Certify\IB;

use HP;
use Storage;

use App\User;
use stdClass;
use Carbon\Carbon;
use App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Certify\IbReportInfo;
use App\Mail\Ib\MailToIbExpert;
use App\Certify\IbReportTwoInfo;
use App\Http\Controllers\Controller; 
use App\Mail\IB\IBSaveAssessmentMail;
use App\Mail\IB\IBCheckSaveAssessment;
use Illuminate\Support\Facades\Mail;    
use App\Mail\IB\IBSaveAssessmentPastMail;
use App\Models\Certify\ApplicantIB\CertiIb;

use App\Models\Certify\ApplicantIB\CertiIBCheck;
use App\Models\Certify\ApplicantIB\CertiIBReport;

use App\Models\Certify\ApplicantIB\CertiIBReview;
use App\Models\Certify\ApplicantIB\CertiIbHistory; 
use App\Models\Certify\ApplicantIB\CertiIBAuditors; 
use App\Models\Certify\ApplicantIB\CertiIBAttachAll; 
use App\Models\Certify\ApplicantIB\CertiIBAuditorsList;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;
use App\Models\Certify\ApplicantIB\AuditorIbRepresentative;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessmentBug;

class SaveAssessmentIbController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files_ib/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        // dd('ok');
        $model = str_slug('saveassessmentib','-');
        if(auth()->user()->can('view-'.$model)) {
         
           $keyword = $request->get('search');
            $filter = [];
       
            $filter['filter_degree'] = $request->get('filter_degree', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

               $Query = new CertiIBSaveAssessment;
               $Query = $Query->select('app_certi_ib_assessment.*');
            if ($filter['filter_degree']!='') {
                if($filter['filter_degree'] == '0'){
                    $Query = $Query->where('bug_report', '!=', '1');
                }else if($filter['filter_degree'] == '1'){
                    $Query = $Query->where('bug_report', '==', $filter['filter_degree']);
                }
            }
            if ($filter['filter_search'] != '') {
                $CertiIb  = CertiIb::where('app_no', 'like', '%'.$filter['filter_search'].'%')->pluck('id');
                $Query = $Query->whereIn('app_certi_ib_id', $CertiIb);
            }
          //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
                $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) { 
                     $Query = $Query->LeftJoin('app_certi_ib_check','app_certi_ib_check.app_certi_ib_id','=','app_certi_ib_assessment.app_certi_ib_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย 
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                } 
            }
            $assessment = $Query->orderby('id','desc')->sortable()
                                ->paginate($filter['perPage']);
        
            return view('certify/ib/save_assessment_ib.index', compact('assessment', 'filter'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $model = str_slug('saveassessmentib','-');
        if(auth()->user()->can('add-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment = new CertiIBSaveAssessment;
             $bug = [new CertiIBSaveAssessmentBug];

             if($id != null)
             {
                 // dd('ok');
                 $assessment = CertiIBSaveAssessment::where('auditors_id',$id)->first();
                 // dd($assessment);
                 if($assessment != null)
                 {
                     $bug = CertiIBSaveAssessmentBug::where('assessment_id',$assessment->id)->get();
                     // dd($bug);
                 }
                 
             }

             $app_no = [];
             //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
                 $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                 if(count($check) > 0 ){
                     $auditor= CertiIBAuditors::select('id','app_certi_ib_id','auditor')
                                      ->whereIn('step_id',[6])
                                      ->whereIn('app_certi_ib_id',$check)
                                      ->orderby('id','desc')
                                      ->get();
                   if(count($auditor) > 0 ){
                     foreach ($auditor as $item){
                       $app_no[$item->id] = $item->auditor . " ( ". @$item->CertiIBCostTo->app_no . " )";
                      }
                    } 
                  } 
            }else{
                 $auditor = CertiIBAuditors::select('id','app_certi_ib_id','auditor')
                                            ->whereIn('step_id',[6])
                                           ->orderby('id','desc')
                                           ->get();
                  if(count($auditor) > 0 ){
                    foreach ($auditor as $item){
                         $app_no[$item->id] = $item->auditor . " ( ". @$item->CertiIBCostTo->app_no . " )";
                    }
                  }
            }

            $certiIBAuditorsLists = CertiIBAuditors::find($id)->CertiIBAuditorsLists;
            $auditor = CertiIBAuditors::find($id);

//    dd($assessment);
  
    // dd($assessment->id);
   
            return view('certify/ib/save_assessment_ib.create',['app_no'=> $app_no,
                                                                'assessment'=>$assessment,
                                                                'bug'=>$bug,
                                                                'auditorId'=> $id,
                                                                'auditor'=> $auditor,
                                                                'previousUrl'=> $previousUrl,
                                                                'certiIBAuditorsLists'=> $certiIBAuditorsLists,
                                                                ]);
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('saveassessmentib','-');
        if(auth()->user()->can('add-'.$model)) {
            $request->validate([
                'app_certi_ib_id' => 'required',
            ]);

            // dd($request->all());

            $request->request->add(['created_by' => auth()->user()->getKey()]); 
            $requestData = $request->all();
            $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
            if($request->bug_report == 1){
                $requestData['main_state'] = isset($request->main_state) ? 2 : 1;
            }else{
                $requestData['main_state'] = 1;
            }
            // $auditors = CertiIBSaveAssessment::create($requestData);
            $CertiIb = CertiIb::findOrFail($request->app_certi_ib_id);
            $tb = new CertiIBSaveAssessment;
              
            // ข้อบกพร่อง/ข้อสังเกต
  

            $assessment = CertiIBSaveAssessment::where('auditors_id',$request->auditors_id)->first();

            // dd($request->all(),$assessment);
           
            if($assessment == null){
                $assessment = CertiIBSaveAssessment::create($requestData);
                $json = $this->copyScopeIbFromAttachement($assessment->app_certi_ib_id);
                $copiedScopes = json_decode($json, true);

                $tbx = new CertiIBSaveAssessment;
                $certi_ib_attach_more = new CertiIBAttachAll();
                $certi_ib_attach_more->app_certi_ib_id      = $assessment->app_certi_ib_id ?? null;
                $certi_ib_attach_more->ref_id               = $assessment->id;
                $certi_ib_attach_more->table_name           = $tbx->getTable();
                $certi_ib_attach_more->file_section         = '2';
                $certi_ib_attach_more->file                 = $copiedScopes[0]['attachs'];
                $certi_ib_attach_more->file_client_name     = $copiedScopes[0]['file_client_name'];
                $certi_ib_attach_more->token                = str_random(16);
                $certi_ib_attach_more->save();

                $ibReportInfo = new IbReportInfo();
                $ibReportInfo->ib_assessment_id = $assessment->id;
                $ibReportInfo->save();

                $ibReportTwoInfo = new IbReportTwoInfo();
                $ibReportTwoInfo->ib_assessment_id = $assessment->id;
                $ibReportTwoInfo->save();

            }

            if(isset($requestData["detail"])){
                $this->storeDetail($assessment,$requestData["detail"]);
            }
    
            // รายงานการตรวจประเมิน
             if($request->file  && $request->hasFile('file')){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $assessment->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $assessment->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '1';
                        $certi_ib_attach_more->file             = $this->storeFile($request->file,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
            }
             if($assessment->bug_report == 2){
                // รายงาน Scope
                if($request->file_scope  && $request->hasFile('file_scope')){
                    foreach ($request->file_scope as $index => $item){
                            $certi_ib_attach_more = new CertiIBAttachAll();
                            $certi_ib_attach_more->app_certi_ib_id  = $assessment->app_certi_ib_id ?? null;
                            $certi_ib_attach_more->ref_id           = $assessment->id;
                            $certi_ib_attach_more->table_name       = $tb->getTable();
                            $certi_ib_attach_more->file_section     = '2';
                            $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                            $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_ib_attach_more->token            = str_random(16);
                            $certi_ib_attach_more->save();
                    }
                }
               // รายงาน สรุปรายงานการตรวจทุกครั้ง
                if($request->file_report  && $request->hasFile('file_report')){
                    foreach ($request->file_report as $index => $item){
                            $certi_ib_attach_more                   = new CertiIBAttachAll();
                            $certi_ib_attach_more->app_certi_ib_id  = $assessment->app_certi_ib_id ?? null;
                            $certi_ib_attach_more->ref_id           = $assessment->id;
                            $certi_ib_attach_more->table_name       = $tb->getTable();
                            $certi_ib_attach_more->file_section     = '3';
                            $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                            $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_ib_attach_more->token            = str_random(16);
                            $certi_ib_attach_more->save();
                    }
                }
    }
            // ไฟล์แนบ
            if($request->attachs  && $request->hasFile('attachs')){
                foreach ($request->attachs as $index => $item){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $assessment->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $assessment->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '4';
                        $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
                }
             }

       //
       //
  
         // สถานะ แต่งตั้งคณะกรรมการ
        $committee = CertiIBAuditors::findOrFail($assessment->auditors_id); 
       if(($assessment->degree == 1 || $assessment->degree == 8) && $assessment->bug_report == 1){
                $assessment->submit_type = $request->submit_type;
                $nowTimeStamp = Carbon::now()->addDays(15)->timestamp;
                $encodedTimestamp = base64_encode($nowTimeStamp);
                $token = Str::random(30) . '_' . $encodedTimestamp;

                if($assessment->expert_token == null)
                {
                    $assessment->expert_token = $token;
                }
               

                if($request->submit_type == "confirm")
                {
                    $assessment->notice_confirm_date = Carbon::now()->addDays(1);
                }
               
                $assessment->save();

                //Log  //  Mail
                $this->set_history_bug($assessment);
                $this->set_mail($assessment,$CertiIb);
               if($assessment->main_state == 1 ){
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();
                     
                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();
                   // สถานะ แต่งตั้งคณะกรรมการ
                   $auditor = CertiIBAuditors::where('app_certi_ib_id',$CertiIb->id)
                                            ->whereIn('step_id',[9,10])
                                            ->whereNull('status_cancel')
                                             ->get(); 
                    if(count($auditor) == count($CertiIb->CertiIBAuditorsManyBy)){
                        $report = new   CertiIBReview;  //ทบทวนฯ
                        $report->app_certi_ib_id  = $CertiIb->id;
                        $report->save();
                        $CertiIb->update(['review'=>1,'status'=>11]);  // ทบทวน
                    }
                }
        }


            if($assessment->degree == 4){
                $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                $committee->save();
                   //  Log
                 $this->set_history($assessment);
                $this->set_mail_past($assessment,$CertiIb);  
            }
            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
            }else{
                return redirect('certify/save_assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
            }
        }
        abort(403);
    }

    public function copyScopeIbFromAttachement($certiIbId)
{
    $copiedScoped = null;
    $fileSection = null;

    $app = CertiIb::find($certiIbId);

    $latestRecord = CertiIBAttachAll::where('app_certi_ib_id', $certiIbId)
    ->where('file_section', 3)
    ->where('table_name', 'app_certi_ib')
    ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
    ->first();

    $existingFilePath = 'files/applicants/check_files_ib/' . $latestRecord->file ;

    // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
    if (HP::checkFileStorage($existingFilePath)) {
        $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
        $no  = str_replace("RQ-","",$app->app_no);
        $no  = str_replace("-","_",$no);
        $dlName = 'scope_'.basename($existingFilePath);
        $attach_path  =  'files/applicants/check_files_ib/'.$no.'/';

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
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('saveassessmentib','-');
        if(auth()->user()->can('view-'.$model)) {
            $assessment = SaveAssessmentIb::findOrFail($id);
            return view('certify/ib.save-assessment-ib.show', compact('assessment'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('saveassessmentib','-');
        if(auth()->user()->can('edit-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment = CertiIBSaveAssessment::findOrFail($id);
            $bug = CertiIBSaveAssessmentBug::where('assessment_id',$id)->get();
            if(count($bug) <= 0){
                $bug = [new CertiIBSaveAssessmentBug];
            }
                $found = [];
                $auditors_id = CertiIBAuditors::where('app_certi_ib_id',$assessment->app_certi_ib_id)->pluck('id');
                if(count($auditors_id) > 0){
                   $auditors_list =   CertiIBAuditorsList::select('user_id','temp_users')
                                                        ->whereIn('auditors_id',$auditors_id)
                                                        ->distinct('user_id')
                                                        ->get();
                    if(count($auditors_list) > 0){
                      foreach ($auditors_list as $index => $item){
                            $found[$item->user_id] =  $item->temp_users ;
                      }

                    }
                }
               
            return view('certify/ib.save_assessment_ib.edit', compact('assessment','bug','found','previousUrl'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('saveassessmentib','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
            if($request->bug_report == 1){
                $requestData['main_state'] = isset($request->main_state) ? 2 : 1;
            }else{
                $requestData['main_state'] = 1;
            }
            $auditors = CertiIBSaveAssessment::findOrFail($id);
            $auditors->update($requestData);
            $CertiIb = CertiIb::findOrFail($auditors->app_certi_ib_id);
            $tb = new CertiIBSaveAssessment;
              
            // ข้อบกพร่อง/ข้อสังเกต
            if(isset($requestData["detail"])){
                $this->storeDetail($auditors,$requestData["detail"]);
            }
    
            // รายงานการตรวจประเมิน
             if($request->file && $request->hasFile('file')){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $auditors->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '1';
                        $certi_ib_attach_more->file             = $this->storeFile($request->file,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
            }
  if($auditors->bug_report == 2){
                // รายงาน Scope
                if($request->file_scope && $request->hasFile('file_scope')){
                    foreach ($request->file_scope as $index => $item){
                            $certi_ib_attach_more                   = new CertiIBAttachAll();
                            $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                            $certi_ib_attach_more->ref_id           = $auditors->id;
                            $certi_ib_attach_more->table_name       = $tb->getTable();
                            $certi_ib_attach_more->file_section     = '2';
                            $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                            $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_ib_attach_more->token            = str_random(16);
                            $certi_ib_attach_more->save();
                    }
                }
               // รายงาน สรุปรายงานการตรวจทุกครั้ง
                if($request->file_report && $request->hasFile('file_report')){
                    foreach ($request->file_report as $index => $item){
                            $certi_ib_attach_more                   = new CertiIBAttachAll();
                            $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                            $certi_ib_attach_more->ref_id           = $auditors->id;
                            $certi_ib_attach_more->table_name       = $tb->getTable();
                            $certi_ib_attach_more->file_section     = '3';
                            $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                            $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_ib_attach_more->token            = str_random(16);
                            $certi_ib_attach_more->save();
                    }
                }
    }
            // ไฟล์แนบ
            if($request->attachs && $request->hasFile('attachs')){
                foreach ($request->attachs as $index => $item){
                        $certi_ib_attach_more                       = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id      = $auditors->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id               = $auditors->id;
                        $certi_ib_attach_more->table_name           = $tb->getTable();
                        $certi_ib_attach_more->file_section         = '4';
                        $certi_ib_attach_more->file                 = $this->storeFile($item,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_ib_attach_more->token = str_random(16);
                        $certi_ib_attach_more->save();
                }
             }

              //
   
         // สถานะ แต่งตั้งคณะกรรมการ
        $committee = CertiIBAuditors::findOrFail($auditors->auditors_id); 
        if(($auditors->degree == 1 || $auditors->degree == 8) && $auditors->bug_report == 1){
                //Log  
                $this->set_history_bug($auditors);
                $this->set_mail($auditors,$CertiIb);   
               if($auditors->main_state == 1 ){
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();
                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();
                    // สถานะ แต่งตั้งคณะกรรมการ
                    $auditor = CertiIBAuditors::where('app_certi_ib_id',$CertiIb->id)
                                                ->whereIn('step_id',[9,10])
                                                ->whereNull('status_cancel')
                                               ->get(); 
                    if(count($auditor) == count($CertiIb->CertiIBAuditorsManyBy)){
                        $report = new   CertiIBReview;  //ทบทวนฯ
                        $report->app_certi_ib_id  = $certi_ib->id;
                        $report->save();
                        $CertiIb->update(['review'=>1,'status'=>11]);  // ทบทวน
                    }
                }
        }


            if($auditors->degree == 4){
                $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                $committee->save();
                //  Log
                $this->set_history($auditors);
                  //  Mail
                $this->set_mail_past($auditors,$CertiIb);  
              
            }


            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
            }else{
                return redirect('certify/save_assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
            }
        }
        abort(403);

    }



    public function DataAssessment($id) {
        $previousUrl = app('url')->previous();
        $assessment = CertiIBSaveAssessment::findOrFail($id);
        return view('certify/ib.save_assessment_ib.form_assessment', compact('assessment','previousUrl'));
    }


    public function UpdateAssessment(Request $request, $id){
 
        $auditors = CertiIBSaveAssessment::findOrFail($id);
        $CertiIb = CertiIb::findOrFail($auditors->app_certi_ib_id);
        // สถานะ แต่งตั้งคณะกรรมการ
        $committee = CertiIBAuditors::findOrFail($auditors->auditors_id); 
        $tb = new CertiIBSaveAssessment;

if($auditors->degree != 5){  // ข้อบกพร่อง/ข้อสังเกต

            $ids = $request->input('id');
            if(isset($ids)){
            foreach ($ids as $key => $item) {
                $bug = CertiIBSaveAssessmentBug::where('id',$item)->first();
               if(!is_null($bug)){ 
                   $bug->status       = $request->status[$bug->id] ??  @$bug->status;
                   $bug->comment      = $request->comment[$bug->id] ?? @$bug->comment;
                   $bug->file_status  = $request->file_status[$bug->id] ??  @$bug->file_status;
                   $bug->file_comment = $request->file_comment[$bug->id] ?? null;
                   $bug->save(); 
               }
             } 

            // if($request->hasFile('file_car')){
            if($request->assessment_passed == 1){
                    $auditors->main_state = 1;
                    $auditors->degree = 4;
                    $auditors->date_car = date("Y-m-d"); // วันที่ปิด Car
                    $auditors->bug_report = 2; 
             }else{
                 if(isset($request->main_state)){
                    $auditors->main_state =  2 ;
                    $auditors->degree = 8;
                  }else{
                    $auditors->main_state = 1;
                    $auditors->degree = 3;
                  }
             }
 
             $auditors->save();
      
      
     
             // รายงานการตรวจประเมิน
            if($request->file && $request->hasFile('file')){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $auditors->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '1';
                        $certi_ib_attach_more->file             = $this->storeFile($request->file,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
            }
if($auditors->main_state == 1){
            // รายงาน Scope
            if($request->file_scope && $request->hasFile('file_scope')){
                foreach ($request->file_scope as $index => $item){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $auditors->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '2';
                        $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
                }
            }
           // รายงาน สรุปรายงานการตรวจทุกครั้ง
            if($request->file_report  && $request->hasFile('file_report')){
                foreach ($request->file_report as $index => $item){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $auditors->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '3';
                        $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
                }
            }
}
            // ไฟล์แนบ
            if($request->attachs && $request->hasFile('attachs')){
                foreach ($request->attachs as $index => $item){
                        $certi_ib_attach_more                   = new CertiIBAttachAll();
                        $certi_ib_attach_more->app_certi_ib_id  = $auditors->app_certi_ib_id ?? null;
                        $certi_ib_attach_more->ref_id           = $auditors->id;
                        $certi_ib_attach_more->table_name       = $tb->getTable();
                        $certi_ib_attach_more->file_section     = '4';
                        $certi_ib_attach_more->file             = $this->storeFile($item,$CertiIb->app_no);
                        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_ib_attach_more->token            = str_random(16);
                        $certi_ib_attach_more->save();
                }
             }

            // รายงาน Car
            if($request->file_car && $request->hasFile('file_car')){
                $certi_ib_attach_more = new CertiIBAttachAll();
                $certi_ib_attach_more->app_certi_ib_id      = $auditors->app_certi_ib_id ?? null;
                $certi_ib_attach_more->ref_id               = $auditors->id;
                $certi_ib_attach_more->table_name           = $tb->getTable();
                $certi_ib_attach_more->file_section         = '5';
                $certi_ib_attach_more->file                 = $this->storeFile($request->file_car,$CertiIb->app_no);
                $certi_ib_attach_more->file_client_name     = HP::ConvertCertifyFileName($request->file_car->getClientOriginalName());
                $certi_ib_attach_more->token                = str_random(16);
                $certi_ib_attach_more->save();
            }


         //  Log
        $this->set_history_bug($auditors);

         if($auditors->degree == 3){

            $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
            $committee->save();
            //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
            $this->set_check_mail($auditors,$CertiIb);  
        }elseif($auditors->degree == 4){
             $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
             $committee->save();
             //  Log
              $this->set_history($auditors);
              //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
              $this->set_mail_past($auditors,$CertiIb);  
        }else{
            $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
            $committee->save();

                      // สถานะ แต่งตั้งคณะกรรมการ
              $auditor = CertiIBAuditors::where('app_certi_ib_id',$CertiIb->id)
                                        ->whereIn('step_id',[9,10])
                                        ->whereNull('status_cancel')
                                        ->get(); 
            if(count($auditor) == count($CertiIb->CertiIBAuditorsManyBy)){
                $report = new   CertiIBReview;  //ทบทวนฯ
                $report->app_certi_ib_id  = $CertiIb->id;
                $report->save();
                $CertiIb->update(['review'=>1,'status'=>11]);  // ทบทวน
            }
        }
    //  return redirect('certify/save_assessment-ib')->with('flash_message', 'เรียบร้อยแล้ว!'); // เข้าสรุปรายงานและเสนออนุกรรมการฯ
     }
 }else{
       // รายงาน Scope
            if($request->file_scope  && $request->hasFile('file_scope')){
                $file_scope = [];
                foreach ($request->file_scope as $index => $item){
                        $data = new stdClass;
                        $data->file = $this->storeFile($item,$CertiIb->app_no);
                        $file_scope[] = $data;
                }
            }
           // รายงาน สรุปรายงานการตรวจทุกครั้ง
            if($request->file_report && $request->hasFile('file_report')){
                         $file_report = [];
                foreach ($request->file_report as $index => $item){
                         $data = new stdClass;
                         $data->file = $this->storeFile($item,$CertiIb->app_no);
                        $file_report[] = $data;
                }
            }
            
        $auditors->degree = 4;
        $auditors->save();

        $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
        $committee->save();
        $tb = new CertiIBSaveAssessment;
       $assessment = CertiIBSaveAssessment::select('name','auditors_id', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                     ->where('id',$id)
                     ->first();
       CertiIbHistory::create([
                                   'app_certi_ib_id'    => $auditors->app_certi_ib_id ?? null,
                                   'auditors_id'        => $assessment->auditors_id ?? null,
                                   'system'             => 8,
                                   'table_name'         => $tb->getTable(),
                                   'ref_id'             => $auditors->id, 
                                   'details_one'        => json_encode($assessment) ?? null,
                                   'details_two'        => null,
                                   'details_three'      => !empty($auditors->FileAttachAssessment1To->file) ? $auditors->FileAttachAssessment1To->file : null,
                                   'file_client_name'   =>  !empty($auditors->FileAttachAssessment1To->file_client_name) ? $auditors->FileAttachAssessment1To->file_client_name : null,
                                   'details_four'       => isset($file_scope) ?  json_encode($file_scope): null,
                                   'attachs'            => isset($file_report) ?  json_encode($file_report): null,
                                   'file'               => (count($auditors->FileAttachAssessment4Many) > 0) ? json_encode($auditors->FileAttachAssessment4Many) : null,
                                   'attachs_car'        => !empty($auditors->FileAttachAssessment5To->file) ? $auditors->FileAttachAssessment5To->file : null, // ปิด car
                                   'attach_client_name' =>  !empty($auditors->FileAttachAssessment5To->file_client_name) ? $auditors->FileAttachAssessment5To->file_client_name : null,
                                   'created_by'         =>  auth()->user()->runrecno
                            ]);

        // $auditors->update(['degree'=>6]);
       //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
        $this->set_mail_past($auditors,$CertiIb);  
}

    if($request->previousUrl){
        return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
    }else{
        return redirect('certify/save_assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
    }
 }


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeDetail($data,$notice) {
 
            $data->CertiIBBugMany()->delete();
            $detail = (array)@$notice;
            foreach ($detail['notice'] as $key => $item) {
                    $bug = new CertiIBSaveAssessmentBug;
                    $bug->assessment_id = $data->id;
                    $bug->remark = $item;
                    $bug->report = $detail["report"][$key] ?? null;
                    $bug->no = $detail["no"][$key] ?? null;
                    $bug->type =  $detail["type"][$key] ?? null;
                    $bug->reporter_id =  $detail["found"][$key] ?? null;
                    $bug->owner_id = auth()->user()->runrecno;
                    $bug->save();
            }
    }


    //พบข้อบกพร่อง/ข้อสังเกต  ผู้ประกอบการ +  ผก.
    public function set_mail($data,$certi_ib) {

         if(!is_null($certi_ib->email)){
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app = [ 
                        'certi_ib'    => $certi_ib,
                        'assessment'  => $data ?? '-',
                        'url'         => $url.'certify/applicant-ib' ?? '-',
                        'email'       =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                        'email_cc'    =>  !empty($certi_ib->DataEmailDirectorIBCC) ? $certi_ib->DataEmailDirectorIBCC : 'ib@tisi.mail.go.th',
                        'email_reply' => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                       ];
                
            $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                    $certi_ib->id,
                                                    (new CertiIb)->getTable(),
                                                    $data->id,
                                                    (new CertiIBSaveAssessment)->getTable(),
                                                    2,
                                                    'นำส่งรายงานการตรวจประเมิน',
                                                    view('mail.IB.save_assessment', $data_app),
                                                    $certi_ib->created_by,
                                                    $certi_ib->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                    $certi_ib->email,
                                                    !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                    !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new IBSaveAssessmentMail($data_app);
            $mail =  Mail::to($certi_ib->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }   
        }
     }

     public function set_check_mail($data,$certi_ib) {

        if(!is_null($certi_ib->email)){
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app = [ 
                        'certi_ib'    => $certi_ib ?? '-',
                        'assessment'  => $data ?? '-',
                        'url'         => $url.'certify/applicant-ib' ?? '-',
                        'email'       => !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                        'email_cc'    => !empty($certi_ib->DataEmailDirectorIBCC) ? $certi_ib->DataEmailDirectorIBCC : 'ib@tisi.mail.go.th',
                        'email_reply' => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                       ];
                
            $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                    $certi_ib->id,
                                                    (new CertiIb)->getTable(),
                                                    $data->id,
                                                    (new CertiIBSaveAssessment)->getTable(),
                                                    2,
                                                    !is_null($data->FileAttachAssessment5To) ? 'แจ้งผลการประเมินหลักฐานการแก้ไขข้อบกพร่อง' : 'แจ้งผลการประเมินแนวทางแก้ไขข้อบกพร่อง',
                                                    view('mail.IB.check_save_assessment', $data_app),
                                                    $certi_ib->created_by,
                                                    $certi_ib->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                    $certi_ib->email,
                                                    !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                    !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new IBCheckSaveAssessment($data_app);
            $mail =  Mail::to($certi_ib->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }   
       }
    }



     //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
     public function set_mail_past($data,$certi_ib) {
 
        if(!is_null($certi_ib->email)){
            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app = [ 
                        'certi_ib'    => $certi_ib,
                        'assessment'  => $data ?? '-',
                        'url'         => $url.'certify/applicant-ib' ?? '-',
                        'email'       =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                        'email_cc'    =>  !empty($certi_ib->DataEmailDirectorIBCC) ? $certi_ib->DataEmailDirectorIBCC : 'ib@tisi.mail.go.th',
                        'email_reply' => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'
                       ];
                
            $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                    $certi_ib->id,
                                                    (new CertiIb)->getTable(),
                                                    $data->id,
                                                    (new CertiIBSaveAssessment)->getTable(),
                                                    2,
                                                    'รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย',
                                                    view('mail.IB.save_assessment_past', $data_app),
                                                    $certi_ib->created_by,
                                                    $certi_ib->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                    $certi_ib->email,
                                                    !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                    !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new IBSaveAssessmentPastMail($data_app);
            $mail =  Mail::to($certi_ib->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }   
 
       }
    }

     
    public function set_history_bug($data) // ข้อบกพร่อง/ข้อสังเกต
    {
        $tb = new CertiIBSaveAssessment;
        $assessment = CertiIBSaveAssessment::select('name', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                      ->where('id',$data->id)
                      ->first();
      
        $bug = CertiIBSaveAssessmentBug::select('report','remark','no','type','reporter_id','details','status','comment','file_status','file_comment','attachs','attach_client_name')
                              ->where('assessment_id',$data->id)
                              ->get()
                              ->toArray();
       CertiIbHistory::create([
                                    'app_certi_ib_id'   => $data->app_certi_ib_id ?? null,
                                    'auditors_id'       => $data->auditors_id ?? null,
                                    'system'            => 7,
                                    'table_name'        => $tb->getTable(),
                                    'ref_id'            => $data->id, 
                                    'details_one'       => json_encode($assessment) ?? null,
                                    'details_two'       => (count($bug) > 0) ? json_encode($bug) : null,
                                    'details_three'     => !empty($data->FileAttachAssessment1To->file) ? $data->FileAttachAssessment1To->file : null,
                                    'file_client_name'  =>  !empty($data->FileAttachAssessment1To->file_client_name) ? $data->FileAttachAssessment1To->file_client_name : null,
                                    'details_four'      => (count($data->FileAttachAssessment2Many) > 0) ? json_encode($data->FileAttachAssessment2Many) : null,
                                    'attachs'           => (count($data->FileAttachAssessment3Many) > 0) ? json_encode($data->FileAttachAssessment3Many) : null,
                                    'file'              =>  (count($data->FileAttachAssessment4Many) > 0) ? json_encode($data->FileAttachAssessment4Many) : null,
                                    'attachs_car'       =>  !empty($data->FileAttachAssessment5To->file) ? $data->FileAttachAssessment5To->file : null, // ปิด car
                                    'attach_client_name'=>  !empty($data->FileAttachAssessment5To->file_client_name) ? $data->FileAttachAssessment5To->file_client_name : null,
                                    'created_by'        =>  auth()->user()->runrecno
                             ]);
   }
   public function set_history($data) //บันทึกผลการตรวจประเมิน
   {
       $tb = new CertiIBSaveAssessment;
       $assessment = CertiIBSaveAssessment::select('name', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                     ->where('id',$data->id)
                     ->first();
      CertiIbHistory::create([
                                   'app_certi_ib_id'    => $data->app_certi_ib_id ?? null,
                                   'auditors_id'        => $data->auditors_id ?? null,
                                   'system'             => 8,
                                   'table_name'         => $tb->getTable(),
                                   'ref_id'             => $data->id, 
                                   'details_one'        => json_encode($assessment) ?? null,
                                   'details_two'        => null,
                                   'details_three'      => !empty($data->FileAttachAssessment1To->file) ? $data->FileAttachAssessment1To->file : null,
                                   'file_client_name'   =>  !empty($data->FileAttachAssessment1To->file_client_name) ? $data->FileAttachAssessment1To->file_client_name : null,
                                   'details_four'       => (count($data->FileAttachAssessment2Many) > 0) ? json_encode($data->FileAttachAssessment2Many) : null,
                                   'attachs'            => (count($data->FileAttachAssessment3Many) > 0) ? json_encode($data->FileAttachAssessment3Many) : null,
                                   'file'               => (count($data->FileAttachAssessment4Many) > 0) ? json_encode($data->FileAttachAssessment4Many) : null,
                                   'attachs_car'        => !empty($data->FileAttachAssessment5To->file) ? $data->FileAttachAssessment5To->file : null, // ปิด car
                                   'attach_client_name' =>  !empty($data->FileAttachAssessment5To->file_client_name) ? $data->FileAttachAssessment5To->file_client_name : null,
                                   'created_by'         => auth()->user()->runrecno
                            ]);
   }
    public function DataCertiIb($id) {
        $auditor = CertiIBAuditors::findOrFail($id);  
        $certi_ib =  CertiIb::findOrFail($auditor->app_certi_ib_id); 
        return response()->json([
           'certi_ib'=> $certi_ib ?? '-' 
        ]);
    }
    public function storeFile($files, $app_no = 'files_ib',$name =null)
    {
            $no  = str_replace("RQ-","",$app_no);
            $no  = str_replace("-","_",$no);
            if ($files) {
                $attach_path  =  $this->attach_path.$no;
                $file_extension = $files->getClientOriginalExtension();
                $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
                $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
                $fullFileName =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $no.'/'.$storageName;
            }else{
                return null;
            }
    }

    
    public function createIbReport($id)
    {
        
        $assessment = CertiIBSaveAssessment::find($id);
        $ibReportInfo = IbReportInfo::where('ib_assessment_id',$id)->first();
        $certi_ib = CertiIb::find($assessment->app_certi_ib_id);

        // dd($ibReportInfo);
        $referenceDocuments = CertiIBAttachAll::where('app_certi_ib_id',$assessment->app_certi_ib_id)
                ->where('ref_id',$assessment->id)
                ->where('file_section','123')
                ->get();
        if($ibReportInfo == null)
        {
            return view('certify.ib.save_assessment_ib.report.index',[
                'assessment' => $assessment,
                'certi_ib' =>$certi_ib,
                'referenceDocuments' => $referenceDocuments
            ]);
        }else{

            $ibReportInfoSigners = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)
                                ->where('certificate_type',1)
                                ->where('report_type',1)
                                ->get();
            // dd('ok');
            return view('certify.ib.save_assessment_ib.report.view',[
                'ibReportInfo' => $ibReportInfo,
                'assessment' => $assessment,
                'certi_ib' =>$certi_ib,
                'referenceDocuments' => $referenceDocuments,
                'ibReportInfoSigners' => $ibReportInfoSigners,
            ]);
        }

    }

    public function addAuditorIbRepresentative(Request $request)
    {
        // dd($request->all());
        // Validate input
        $request->validate([
            'assessment_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);
    
        // Create new AuditorRepresentative record
        $auditor = AuditorIbRepresentative::create([
            'assessment_id' => $request->assessment_id,
            'name' => $request->name,
            'position' => $request->position,
        ]);
    
        // Get updated list
        $auditorIbRepresentatives = AuditorIbRepresentative::where('assessment_id', $request->assessment_id)->get();
        // dd($auditorIbRepresentatives);
        // Return updated list as JSON
        return response()->json(['auditorIbRepresentatives' => $auditorIbRepresentatives]);
    }

    public function deleteAuditorIbRepresentative(Request $request)
    {
        // ตรวจสอบ input
        $request->validate([
            'auditor_id' => 'required|integer',
            'assessment_id' => 'required|integer',
        ]);

        // ลบข้อมูล
        AuditorIbRepresentative::where('id', $request->auditor_id)->delete();

        // ดึงข้อมูลอัปเดต
        $auditorIbRepresentatives = AuditorIbRepresentative::where('assessment_id', $request->assessment_id)->get();

        // ส่งข้อมูลที่อัปเดตกลับไป
        return response()->json(['auditorIbRepresentatives' => $auditorIbRepresentatives]);
    }

    public function addIbReferenceDocument(Request $request)
    {
        // dd($request->all());
        $assessment = CertiIBSaveAssessment::find($request->ref_id);
        
        $certi_ib_attach_more = new CertiIBAttachAll();
        $certi_ib_attach_more->app_certi_ib_id  = $assessment->app_certi_ib_id ?? null;
        $certi_ib_attach_more->ref_id           = $assessment->id;
        $certi_ib_attach_more->table_name       = (new CertiIBAttachAll)->getTable();
        $certi_ib_attach_more->file_section     = '123';
        $certi_ib_attach_more->file             = $this->storeFile($request->file, $assessment->app_no);
        $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
        $certi_ib_attach_more->token            = Str::random(16);
        $certi_ib_attach_more->save();

         // ส่งข้อมูลไฟล์กลับไปที่ JavaScript แบบเดิม
        $referenceDocuments = CertiIBAttachAll::where('app_certi_ib_id', $assessment->app_certi_ib_id)
        ->where('ref_id', $assessment->id)
        ->where('file_section', '123')
        ->get();  // ไม่ต้องแปลงเป็นอาร์เรย์

        return response()->json(['referenceDocuments' => $referenceDocuments]);
    }

    public function deleteIbReferenceDocument(Request $request)
    {
        $assessment = CertiIBSaveAssessment::find($request->assessment_id);
        // ค้นหาข้อมูลเอกสารที่ต้องการลบ
        $referenceDocument = CertiIBAttachAll::find($request->id);

        

        if ($referenceDocument) {
            if (Storage::exists($referenceDocument->file)) {
                Storage::delete($referenceDocument->file);
            }

            // ลบข้อมูลจากฐานข้อมูล
            $referenceDocument->delete();

            // ส่งผลลัพธ์กลับว่าลบสำเร็จ
            // return response()->json(['success' => true]);
        }

        // ส่งข้อมูลไฟล์กลับไปที่ JavaScript แบบเดิม
        $referenceDocuments = CertiIBAttachAll::where('app_certi_ib_id', $assessment->app_certi_ib_id)
        ->where('ref_id', $assessment->id)
        ->where('file_section', '123')
        ->get();  // ไม่ต้องแปลงเป็นอาร์เรย์

        // dd( $referenceDocuments);

        return response()->json(['referenceDocuments' => $referenceDocuments]);
    }

    public function storeIbReport(Request $request)
    {
        // dd($request->all());
        $signers = json_decode($request->input('signer'), true);
        // dd($signers);
        $data = json_decode($request->input('data'), true); // แปลง JSON String เป็น Array
        $id = $request->id;

        IbReportInfo::where('ib_assessment_id',$id)->delete();

        $assessment = CertiIBSaveAssessment::find($id);
        
        // สร้าง array สำหรับ insert
        $insertData = [
            'ib_assessment_id' => $id,
            'eval_riteria_text' => $data[0]['eval_riteria_text'] ?? null,
            'background_history' => $data[0]['background_history'] ?? null, // แปลงเป็น JSON หากเป็น array
            'insp_proc' => $data[0]['insp_proc'] ?? null,
            'evaluation_key_point' => $data[0]['evaluation_key_point'] ?? null,
            'observation' => $data[0]['observation'] ?? null,
            'evaluation_result' => $data[0]['evaluation_result'] ?? null,
            'auditor_suggestion' => $data[0]['auditor_suggestion'] ?? null,
            'status' => $request->status,
        ];
    
        // ดึงข้อมูล evaluation_detail และแมปเข้าไป
        foreach ($data[0]['evaluation_detail'] as $key => $value) {
            $insertData["{$key}_chk"] = $value['chk'] ?? false;
            $insertData["{$key}_eval_select"] = $value['eval_select'] ?? null;
            $insertData["{$key}_comment"] = $value['comment'] ?? null;
        }

        $ibReportInfo = IbReportInfo::create($insertData);

        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');
        SignAssessmentReportTransaction::where('report_info_id', $ibReportInfo->id)
                                        ->where('certificate_type',1)
                                        ->where('report_type',1)
                                        ->delete();
        // dd($signers);
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentReportTransaction::create([
                'report_info_id' => $ibReportInfo->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certify/save_assessment-ib/ib-report-create/'. $id,
                'certificate_type' => 1,
                'report_type' => 1,
                'app_id' => $assessment->CertiIBCostTo->app_no,
            ]);
        }
        return response()->json(['ibReportInfo' => $ibReportInfo]);
    }



    public function createIbReportTwo($id)
    {
       
        $assessment = CertiIBSaveAssessment::find($id);
        $ibReportInfo = IbReportTwoInfo::where('ib_assessment_id',$id)->first();
        $certi_ib = CertiIb::find($assessment->app_certi_ib_id);

        $referenceDocuments = CertiIBAttachAll::where('app_certi_ib_id',$assessment->app_certi_ib_id)
                ->where('ref_id',$assessment->id)
                ->where('file_section','123')
                ->get();
        if($ibReportInfo == null)
        {
            return view('certify.ib.save_assessment_ib.report_two.index',[
                'assessment' => $assessment,
                'certi_ib' =>$certi_ib,
                'referenceDocuments' => $referenceDocuments
            ]);
        }else{

            $ibReportInfoSigners = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)
                                ->where('certificate_type',1)
                                ->where('report_type',1)
                                ->get();

            return view('certify.ib.save_assessment_ib.report_two.view',[
                'ibReportInfo' => $ibReportInfo,
                'assessment' => $assessment,
                'certi_ib' =>$certi_ib,
                'referenceDocuments' => $referenceDocuments,
                'ibReportInfoSigners' => $ibReportInfoSigners,
            ]);
        }

    }

    
    public function storeIbReportTwo(Request $request)
    {
        // dd($request->all());
        $signers = json_decode($request->input('signer'), true);
        $data = json_decode($request->input('data'), true); // แปลง JSON String เป็น Array
        $id = $request->id;

        IbReportTwoInfo::where('ib_assessment_id',$id)->delete();

        $assessment = CertiIBSaveAssessment::find($id);
        
        // สร้าง array สำหรับ insert
        $insertData = [
            'ib_assessment_id' => $id,
            'eval_riteria_text' => $data[0]['eval_riteria_text'] ?? null,
            'background_history' => $data[0]['background_history'] ?? null, // แปลงเป็น JSON หากเป็น array
            'insp_proc' => $data[0]['insp_proc'] ?? null,
            'evaluation_key_point' => $data[0]['evaluation_key_point'] ?? null,
            'observation' => $data[0]['observation'] ?? null,
            'evaluation_result' => $data[0]['evaluation_result'] ?? null,
            'auditor_suggestion' => $data[0]['auditor_suggestion'] ?? null,
            'status' => $request->status,
        ];
    
        // ดึงข้อมูล evaluation_detail และแมปเข้าไป
        foreach ($data[0]['evaluation_detail'] as $key => $value) {
            $insertData["{$key}_chk"] = $value['chk'] ?? false;
            $insertData["{$key}_eval_select"] = $value['eval_select'] ?? null;
            $insertData["{$key}_comment"] = $value['comment'] ?? null;
        }

        $ibReportInfo = IbReportTwoInfo::create($insertData);

        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');
        SignAssessmentReportTransaction::where('report_info_id', $ibReportInfo->id)
                                        ->where('certificate_type',1)
                                        ->where('report_type',2)
                                        ->delete();
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentReportTransaction::create([
                'report_info_id' => $ibReportInfo->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certify/save_assessment-ib/ib-report-two-create/'. $id,
                'certificate_type' => 1,
                'report_type' => 2,
                'app_id' => $assessment->CertiIBCostTo->app_no,
            ]);
        }
        return response()->json(['ibReportInfo' => $ibReportInfo]);
    }


    public function checkCompleteReportOneSign(Request $request)
    {
        $assessmentId = $request->assessment_id;
        $ibReportInfo = IbReportInfo::where('ib_assessment_id' ,$assessmentId)->first();

        $signedCount = SignAssessmentReportTransaction::where('report_info_id', $ibReportInfo->id)
        ->where('certificate_type',1)
        ->where('report_type',1)
        ->where('approval',1)
        ->count();


        $recordCount = SignAssessmentReportTransaction::where('report_info_id', $ibReportInfo->id)
                        ->where('certificate_type',1)
                        ->where('report_type',1)
                        ->count();

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
        // dd($request->all());
        $assessmentId = $request->assessment_id;
        $ibReportInfo = IbReportTwoInfo::where('ib_assessment_id' ,$assessmentId)->first();

        $signedCount = SignAssessmentReportTransaction::where('report_info_id', $ibReportInfo->id)
        ->where('certificate_type',1)
        ->where('report_type',2)
        ->where('approval',1)
        ->count();


        $recordCount = SignAssessmentReportTransaction::where('report_info_id', $ibReportInfo->id)
                        ->where('certificate_type',1)
                        ->where('report_type',2)
                        ->count();
        // dd($recordCount);
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

    public function EmailToIbExpert(Request $request)
    {
        //   dd($request->all());
          $assessment = CertiIBSaveAssessment::find($request->assessmentId);
          $expertEmails = $request->selectedEmails;
        //   dd($request->assessmentId);
          $app = $assessment->CertiIBCostTo;

          

          $config = HP::getConfig();
          $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
          $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
          $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
          $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';

          // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
          $data_app =  [
                          'certi_ib'     => $app,
                          'url'           => $url_center.'/create-by-ib-expert/' . $assessment->id .'?token='.$assessment->expert_token,
                          'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                          'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                          'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                      ];
    
      
          $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                      $app->id,
                                                      (new CertiIb())->getTable(),
                                                      $app->id,
                                                      (new CertiIBSaveAssessment)->getTable(),
                                                      1,
                                                      'เพิ่มรายการข้อบกพร่อง / ข้อสังเกต',
                                                      view('mail.IB.mail_ib_expert', $data_app),
                                                      $app->created_by,
                                                      $app->agent_id,
                                                      auth()->user()->getKey(),
                                                      !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                      $app->email,
                                                      !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                      !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                      null
                                                      );

          $html = new MailToIbExpert($data_app);
          $mail = Mail::to($expertEmails)->send($html);
          if(is_null($mail) && !empty($log_email)){
              HP::getUpdateCertifyLogEmail($log_email->id);
          }
    }
    
}

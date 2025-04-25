<?php

namespace App\Http\Controllers\Certify\CB;

use DB;
use HP;

use Storage;
use App\User;
use stdClass;
use Exception;
use Carbon\Carbon;
use App\Http\Requests;
use App\SaveAssessmentCB;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Certify\CbReportInfo;
use App\Mail\Cb\MailToCbExpert;
use App\Certify\CbReportTwoInfo;
use App\Mail\Lab\MailToLabExpert;
use App\Certify\CbReportInfoSigner;
use App\Http\Controllers\Controller;
use App\Mail\CB\CheckSaveAssessment;
use Illuminate\Support\Facades\Mail;
use App\Mail\CB\CBSaveAssessmentMail;

use App\Mail\CB\CBSaveAssessmentPastMail;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantCB\CertiCBCheck;
use App\Models\Certify\ApplicantCB\CertiCBReport;
use App\Models\Certify\ApplicantCB\CertiCBReview;
use App\Models\Certify\ApplicantCB\CertiCbHistory;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsList;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\ApplicantCB\AuditorRepresentative;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessmentBug;

class SaveAssessmentCBController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files_cb/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('saveassessmentcb','-');
        if(auth()->user()->can('view-'.$model)) {

           $keyword = $request->get('search');
            $filter = [];

            $filter['filter_degree'] = $request->get('filter_degree', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CertiCBSaveAssessment;
            $Query = $Query->select('app_certi_cb_assessment.*');
            if ($filter['filter_degree']!='') {
                if($filter['filter_degree'] == '0'){
                    $Query = $Query->where('bug_report', '!=', '1');
                }else if($filter['filter_degree'] == '1'){
                    $Query = $Query->where('bug_report', '==', $filter['filter_degree']);
                }
            }

            if ($filter['filter_search'] != '') {
                $CertiCb  = CertiCb::where('app_no', 'like', '%'.$filter['filter_search'].'%')->pluck('id');
                $Query = $Query->whereIn('app_certi_cb_id', $CertiCb);
            }
              //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
             if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                     $Query = $Query->LeftJoin('app_certi_cb_check','app_certi_cb_check.app_certi_cb_id','=','app_certi_cb_assessment.app_certi_cb_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }
            $assessment = $Query ->orderby('id','desc')->sortable()

                                ->paginate($filter['perPage']);

            return view('certify/cb/save_assessment_cb.index', compact('assessment', 'filter'));
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


        $model = str_slug('saveassessmentcb','-');
        if(auth()->user()->can('add-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment = new CertiCBSaveAssessment;
            $bug = [new CertiCBSaveAssessmentBug];

            if($id != null)
            {
                // dd('ok');
                $assessment = CertiCBSaveAssessment::where('auditors_id',$id)->first();
                // dd($assessment);
                if($assessment != null)
                {
                    $bug = CertiCBSaveAssessmentBug::where('assessment_id',$assessment->id)->get();
                    // dd($bug);
                }
                
            }

            $app_no = [];
            //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
           if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
               $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
               if(count($check) > 0 ){
                   $auditor= CertiCBAuditors::select('id','app_certi_cb_id','auditor')
                                    ->whereIn('step_id',[6])
                                    ->whereIn('app_certi_cb_id',$check)
                                    ->orderby('id','desc')
                                    ->get();
                 if(count($auditor) > 0 ){
                   foreach ($auditor as $item){
                     $app_no[$item->id] = $item->auditor . " ( ". @$item->CertiCbCostTo->app_no . " )";
                    }
                  }
                }
            }else{
                   $auditor = CertiCBAuditors::select('id','app_certi_cb_id','auditor')
                                            ->whereIn('step_id',[6])
                                           ->orderby('id','desc')
                                           ->get();
                  if(count($auditor) > 0 ){
                    foreach ($auditor as $item){
                         $app_no[$item->id] = $item->auditor . " ( ". @$item->CertiCbCostTo->app_no . " )";
                    }
                  }
             }

            $certiCBAuditorsLists = CertiCBAuditors::find($id)->CertiCBAuditorsLists;
            $auditor = CertiCBAuditors::find($id);


            return view('certify/cb/save_assessment_cb.create',['app_no'=> $app_no,
                                                                'assessment'=>$assessment,
                                                                'bug'=>$bug,
                                                                'previousUrl'=> $previousUrl,
                                                                'auditorId'=> $id,
                                                                'auditor'=> $auditor,
                                                                'certiCBAuditorsLists'=> $certiCBAuditorsLists,
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
        
        $model = str_slug('saveassessmentcb','-');
        if(auth()->user()->can('add-'.$model)) 
        {
            // dd($request->all());
            $request->validate([
                'app_certi_cb_id' => 'required',
                'auditors_id' => 'required',
            ]);

            $request->request->add(['created_by' => auth()->user()->getKey()]);
            $requestData = $request->all();
            $requestData['report_date']    =  HP::convertDate($request->report_date,true) ?? null;

            if($request->bug_report == 1){
                $requestData['main_state'] = isset($request->main_state) ? 2 : 1;
            }else{
                $requestData['main_state'] = 1;
            }

            $assessment = CertiCBSaveAssessment::where('auditors_id',$request->auditors_id)->first();
           
            if($assessment == null){
                $assessment = CertiCBSaveAssessment::create($requestData);
                $json = $this->copyScopeCbFromAttachement($assessment->app_certi_cb_id);
                $copiedScopes = json_decode($json, true);

                $tbx = new CertiCBSaveAssessment;
                $certi_cb_attach_more = new CertiCBAttachAll();
                $certi_cb_attach_more->app_certi_cb_id      = $assessment->app_certi_cb_id ?? null;
                $certi_cb_attach_more->ref_id               = $assessment->id;
                $certi_cb_attach_more->table_name           = $tbx->getTable();
                $certi_cb_attach_more->file_section         = '2';
                $certi_cb_attach_more->file                 = $copiedScopes[0]['attachs'];
                $certi_cb_attach_more->file_client_name     = $copiedScopes[0]['file_client_name'];
                $certi_cb_attach_more->token                = str_random(16);
                $certi_cb_attach_more->save();

                $cbReportInfo = new CbReportInfo();
                $cbReportInfo->cb_assessment_id = $assessment->id;
                $cbReportInfo->save();

                $cbReportTwoInfo = new CbReportTwoInfo();
                $cbReportTwoInfo->cb_assessment_id = $assessment->id;
                $cbReportTwoInfo->save();

            }
            // dd('aha',$assessment);
            $assessmentId = $assessment->id;
            // dd($assessmentId);

            $assessment = CertiCBSaveAssessment::find($assessmentId);
            // dd($assessment);
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
                // dd($assessment  );
            }

            
            // dd($assessment->app_certi_cb_id);

            $CertiCb = CertiCb::findOrFail($assessment->app_certi_cb_id);
            $tb = new CertiCBSaveAssessment;
          
            // ข้อบกพร่อง/ข้อสังเกต
            if(isset($requestData["detail"])){
                // dd($assessment->id);
                $this->storeDetail($assessment,$requestData["detail"]);
            }

            // รายงานการตรวจประเมิน
             if($request->file  && $request->hasFile('file') ){
                    $certi_cb_attach_more                   = new CertiCBAttachAll();
                    $certi_cb_attach_more->app_certi_cb_id  = $assessment->app_certi_cb_id ?? null;
                    $certi_cb_attach_more->ref_id           = $assessment->id;
                    $certi_cb_attach_more->table_name       = $tb->getTable();
                    $certi_cb_attach_more->file_section     = '1';
                    $certi_cb_attach_more->file             = $this->storeFile($request->file,$CertiCb->app_no);
                    $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
                    $certi_cb_attach_more->token            = str_random(16);
                    $certi_cb_attach_more->save();
            }



            if($assessment->bug_report == 2){
                // รายงาน Scope
                if($request->file_scope  && $request->hasFile('file_scope')){
                    foreach ($request->file_scope as $index => $item){
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $assessment->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id               = $assessment->id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->file_section         = '2';
                        $certi_cb_attach_more->file                 = $this->storeFile($item,$CertiCb->app_no);
                        $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_cb_attach_more->token                = str_random(16);
                        $certi_cb_attach_more->save();
                    }
                }
                // รายงาน สรุปรายงานการตรวจทุกครั้ง
                if($request->file_report  && $request->hasFile('file_report')){
                    foreach ($request->file_report as $index => $item){
                        $certi_cb_attach_more                       = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $assessment->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id               = $assessment->id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->file_section         = '3';
                        $certi_cb_attach_more->file                 = $this->storeFile($item,$CertiCb->app_no);
                        $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_cb_attach_more->token                = str_random(16);
                        $certi_cb_attach_more->save();
                    }
                }
            }
            // ไฟล์แนบ
            if($request->attachs  && $request->hasFile('attachs')  && $assessment->bug_report == 1){
                foreach ($request->attachs as $index => $item){
                    $certi_cb_attach_more = new CertiCBAttachAll();
                    $certi_cb_attach_more->app_certi_cb_id      = $assessment->app_certi_cb_id ?? null;
                    $certi_cb_attach_more->ref_id               = $assessment->id;
                    $certi_cb_attach_more->table_name           = $tb->getTable();
                    $certi_cb_attach_more->file_section         = '4';
                    $certi_cb_attach_more->file                 = $this->storeFile($item,$CertiCb->app_no);
                    $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                    $certi_cb_attach_more->token                = str_random(16);
                    $certi_cb_attach_more->save();
                }
            }

            // สถานะ แต่งตั้งคณะกรรมการ
            $committee = CertiCBAuditors::findOrFail($assessment->auditors_id);
            if(in_array($assessment->degree,[1,8])  && $assessment->bug_report == 1){
                //Log
                $this->set_history_bug($assessment);
                //  Mail
                $this->set_mail($assessment,$CertiCb);
                if($assessment->main_state == 1 )
                {
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();
                }
                else
                {
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();

                    // สถานะ แต่งตั้งคณะกรรมการ
                    $auditor = CertiCBAuditors::where('app_certi_cb_id',$CertiCb->id)
                                                ->whereIn('step_id',[9,10])
                                                ->whereNull('status_cancel')
                                                ->get();

                    if(count($auditor) == count($CertiCb->CertiCBAuditorsManyBy))
                    {
                        $report = new   CertiCBReview;  //ทบทวนฯ
                        $report->app_certi_cb_id  = $CertiCb->id;
                        $report->save();
                        $CertiCb->update(['review'=>1,'status'=>11]);  // ทบทวน
                    }
                }
            }

            if($assessment->degree == 4){
                $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                $committee->save();
                $this->set_history($assessment);
                $this->set_mail_past($assessment,$CertiCb);
            }

            

            if($request->previousUrl){
                return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
            }else{
                return redirect('certify/save_assessment-cb')->with('message', 'เรียบร้อยแล้ว!');
            }

        }
        abort(403);
    }


    public function edit($id)
    {
        // dd($id);
        $model = str_slug('saveassessmentcb','-');
        if(auth()->user()->can('edit-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment = CertiCBSaveAssessment::findOrFail($id);
            $bug = CertiCBSaveAssessmentBug::where('assessment_id',$id)->get();
            if(count($bug) <= 0){
                $bug = [new CertiCBSaveAssessmentBug];
            }
                $found = [];
                $auditors_id = CertiCBAuditors::where('app_certi_cb_id',$assessment->app_certi_cb_id)->pluck('id');
                if(count($auditors_id) > 0){
                   $auditors_list =   CertiCBAuditorsList::select('user_id','temp_users')
                                                        ->whereIn('auditors_id',$auditors_id)
                                                        ->distinct('user_id')
                                                        ->get();
                    if(count($auditors_list) > 0){
                      foreach ($auditors_list as $index => $item){
                            $found[$item->user_id] =  $item->temp_users ;
                      }

                    }
                }
            $attach_path = $this->attach_path;//path ไฟล์แนบ
            return view('certify/cb/save_assessment_cb.edit', compact('assessment','bug','found','previousUrl','attach_path'));
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
        $model = str_slug('saveassessmentcb','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
                $requestData = $request->all();
                $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
                if($request->bug_report == 1){
                    $requestData['main_state'] = isset($request->main_state) ? 2 : 1;
                }else{
                    $requestData['main_state'] = 1;
                }
          $tb = new CertiCbSaveAssessment;
            $auditors = CertiCBSaveAssessment::findOrFail($id);
            $auditors->update($requestData);

            $CertiCb = CertiCb::findOrFail($auditors->app_certi_cb_id);
            // ข้อบกพร่อง/ข้อสังเกต
            if(isset($requestData["detail"])){
                $this->storeDetail($auditors,$requestData["detail"]);
            }

            // รายงานการตรวจประเมิน
             if($request->file  && $request->hasFile('file')){
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id  = $auditors->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id           = $auditors->id;
                        $certi_cb_attach_more->table_name       = $tb->getTable();
                        $certi_cb_attach_more->file_section     = '1';
                        $certi_cb_attach_more->file             = $this->storeFile($request->file,$CertiCb->app_no);
                        $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
                        $certi_cb_attach_more->token            = str_random(16);
                        $certi_cb_attach_more->save();
            }


            if($auditors->bug_report == 2){
                // รายงาน Scope
                if($request->file_scope  && $request->hasFile('file_scope')){
                    foreach ($request->file_scope as $index => $item){
                            $certi_cb_attach_more = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id  = $auditors->app_certi_cb_id ?? null;
                            $certi_cb_attach_more->ref_id           = $auditors->id;
                            $certi_cb_attach_more->table_name       = $tb->getTable();
                            $certi_cb_attach_more->file_section     = '2';
                            $certi_cb_attach_more->file             = $this->storeFile($item,$CertiCb->app_no);
                            $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_cb_attach_more->token            = str_random(16);
                            $certi_cb_attach_more->save();
                    }
                }
                // รายงาน สรุปรายงานการตรวจทุกครั้ง
                if($request->file_report  && $request->hasFile('file_report')){
                    foreach ($request->file_report as $index => $item){
                            $certi_cb_attach_more = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id  = $auditors->app_certi_cb_id ?? null;
                            $certi_cb_attach_more->ref_id           = $auditors->id;
                            $certi_cb_attach_more->table_name       = $tb->getTable();
                            $certi_cb_attach_more->file_section     = '3';
                            $certi_cb_attach_more->file             = $this->storeFile($item,$CertiCb->app_no);
                            $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_cb_attach_more->token            = str_random(16);
                            $certi_cb_attach_more->save();
                    }
                }
            }

            // ไฟล์แนบ
            if($request->attachs   && $request->hasFile('attachs') &&  $auditors->bug_report == 1){
                foreach ($request->attachs as $index => $item){
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id  = $auditors->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id           = $auditors->id;
                        $certi_cb_attach_more->table_name       = $tb->getTable();
                        $certi_cb_attach_more->file_section     = '4';
                        $certi_cb_attach_more->file             = $this->storeFile($item,$CertiCb->app_no);
                        $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_cb_attach_more->token = str_random(16);
                        $certi_cb_attach_more->save();
                }
            }


        // สถานะ แต่งตั้งคณะกรรมการ
         $committee = CertiCBAuditors::findOrFail($auditors->auditors_id);
         if(in_array($auditors->degree,[1,8])  && $auditors->bug_report == 1){
            $this->set_history_bug($auditors);
            //  Mail
            $this->set_mail($auditors,$CertiCb);
                if($auditors->main_state == 1 ){
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();

                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();

                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiCBAuditors::where('app_certi_cb_id',$CertiCb->id)
                                            ->whereIn('step_id',[9,10])
                                            ->whereNull('status_cancel')
                                            ->get();

                if(count($auditor) == count($CertiCb->CertiCBAuditorsManyBy)){
                    $report = new   CertiCBReview;  //ทบทวนฯ
                    $report->app_certi_cb_id  = $CertiCb->id;
                    $report->save();
                    $CertiCb->update(['review'=>1,'status'=>11]);  // ทบทวน
                }
                }
          }

          if($auditors->degree == 4){
               $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
               $committee->save();
               $this->set_history($auditors);
               $this->set_mail_past($auditors,$CertiCb);
          }

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certify/save_assessment-cb')->with('message', 'เรียบร้อยแล้ว!');
        }

        }
        abort(403);

    }

    public function DataCertiCb($id) {
        $auditor = CertiCBAuditors::findOrFail($id);
        $certi_cb =  CertiCb::findOrFail($auditor->app_certi_cb_id);
        $certi_cb->tis             = !empty($certi_cb->FormulaTo->title) ?  str_replace("มอก.","",$certi_cb->FormulaTo->title) :'' ;
        $certi_cb->app_certi_cb_id =  @$certi_cb->id ?? null ;

        return response()->json([
           'certi_cb'=> $certi_cb ?? '-'
        ]);
    }
    public function storeDetail($data,$notice) {

        $data->CertiCBBugMany()->delete();
        $detail = (array)@$notice;
        foreach ($detail['notice'] as $key => $item) {
                $bug = new CertiCBSaveAssessmentBug;
                $bug->assessment_id = $data->id;
                $bug->remark        = $item;
                $bug->report        = $detail["report"][$key] ?? null;
                $bug->no            = $detail["no"][$key] ?? null;
                $bug->type          = $detail["type"][$key] ?? null;
                $bug->reporter_id   = $detail["found"][$key] ?? null;
                $bug->owner_id = auth()->user()->runrecno;
                $bug->save();
        }
    }

        //พบข้อบกพร่อง/ข้อสังเกต  ผู้ประกอบการ +  ผก.
    public function set_mail($data,$certi_cb) {
 
        if(!is_null($certi_cb->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app =[
                            'certi_cb'       => $certi_cb ?? '-',
                            'assessment'     => $data ?? '-',
                            'url'            => $url.'certify/applicant-ib' ,
                            'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                            'email_cc'       =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                            'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                        ];

            $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                    $certi_cb->id,
                                                    (new CertiCb)->getTable(),
                                                    $data->id,
                                                    (new CertiCBSaveAssessment)->getTable(),
                                                    3,
                                                    'นำส่งรายงานการตรวจประเมิน',
                                                    view('mail.CB.save_assessment', $data_app),
                                                    $certi_cb->created_by,
                                                    $certi_cb->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                    $certi_cb->email,
                                                    !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                    !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new CBSaveAssessmentMail($data_app);
            $mail =  Mail::to($certi_cb->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }
       }
    }
    public function set_check_mail($data,$certi_cb) {
 
        if(!is_null($certi_cb->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app = [
                            'certi_cb'       => $certi_cb ?? '-',
                            'assessment'     => $data ?? '-',
                            'url'            => $url.'certify/applicant-ib',
                            'email'          => !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                            'email_cc'       => !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                            'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                        ];

            $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                    $certi_cb->id,
                                                    (new CertiCb)->getTable(),
                                                    $data->id,
                                                    (new CertiCBSaveAssessment)->getTable(),
                                                    3,
                                                    !is_null($data->FileAttachAssessment5To) ? 'แจ้งผลการประเมินหลักฐานการแก้ไขข้อบกพร่อง' : 'แจ้งผลการประเมินแนวทางแก้ไขข้อบกพร่อง',
                                                    view('mail.CB.check_save_assessment', $data_app),
                                                    $certi_cb->created_by,
                                                    $certi_cb->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                    $certi_cb->email,
                                                    !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                    !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new CheckSaveAssessment($data_app);
            $mail =  Mail::to($certi_cb->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }

       }
    }


     //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
     public function set_mail_past($data,$certi_cb) {
 
        if(!is_null($certi_cb->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app = [
                            'certi_cb'      => $certi_cb ?? '-',
                            'assessment'    => $data ?? '-',
                            'url'           => $url.'certify/applicant-ib' ,
                            'email'         =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                            'email_cc'      =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                            'email_reply'   => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                        ];

            $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                    $certi_cb->id,
                                                    (new CertiCb)->getTable(),
                                                    $data->id,
                                                    (new CertiCBSaveAssessment)->getTable(),
                                                    3,
                                                    'รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย',
                                                    view('mail.CB.save_assessment_past', $data_app),
                                                    $certi_cb->created_by,
                                                    $certi_cb->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                    $certi_cb->email,
                                                    !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                    !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new CBSaveAssessmentPastMail($data_app);
            $mail =  Mail::to($certi_cb->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }
 
       }
    }
    public function set_history_bug($data)
    {
        $tb = new CertiCBSaveAssessment;
        $assessment = CertiCBSaveAssessment::select('name','auditors_id', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                      ->where('id',$data->id)
                      ->first();

        $bug = CertiCBSaveAssessmentBug::select('report','remark','cause','no','type','reporter_id','details','status','comment','file_status','file_comment','attachs')
                              ->where('assessment_id',$data->id)
                              ->get()
                              ->toArray();
       CertiCbHistory::create([
                                    'app_certi_cb_id'   => $data->app_certi_cb_id ?? null,
                                    'auditors_id'       =>  $data->auditors_id ?? null,
                                    'system'            => 7,
                                    'table_name'        => $tb->getTable(),
                                    'ref_id'            => $data->id,
                                    'details_one'       =>  json_encode($assessment) ?? null,
                                    'details_two'       =>  (count($bug) > 0) ? json_encode($bug) : null,
                                    'details_three'     =>  !empty($data->FileAttachAssessment1To->file) ? $data->FileAttachAssessment1To->file : null,
                                    'file_client_name'  =>  !empty($data->FileAttachAssessment1To->file_client_name) ? $data->FileAttachAssessment1To->file_client_name : null,
                                    'details_four'      =>  (count($data->FileAttachAssessment2Many) > 0) ? json_encode($data->FileAttachAssessment2Many) : null,
                                    'attachs'           => (count($data->FileAttachAssessment3Many) > 0) ? json_encode($data->FileAttachAssessment3Many) : null,
                                    'file'              =>   (count($data->FileAttachAssessment4Many) > 0) ? json_encode($data->FileAttachAssessment4Many) : null,
                                    'attachs_car'       =>   !empty($data->FileAttachAssessment5To->file) ? $data->FileAttachAssessment5To->file : null, // ปิด car
                                    'attach_client_name'=>  !empty($data->FileAttachAssessment5To->file_client_name) ? $data->FileAttachAssessment5To->file_client_name : null,
                                    'created_by'        =>  auth()->user()->runrecno
                             ]);
   }
   public function set_history($data)
   {
       $tb = new CertiCBSaveAssessment;
       $assessment = CertiCBSaveAssessment::select('name','auditors_id', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                     ->where('id',$data->id)
                     ->first();
      CertiCbHistory::create([
                                   'app_certi_cb_id'    => $data->app_certi_cb_id ?? null,
                                   'auditors_id'        =>  $data->auditors_id ?? null,
                                   'system'             => 8,
                                   'table_name'         => $tb->getTable(),
                                   'ref_id'             => $data->id,
                                   'details_one'        =>  json_encode($assessment) ?? null,
                                   'details_two'        =>   null,
                                   'details_three'      =>  !empty($data->FileAttachAssessment1To->file) ? $data->FileAttachAssessment1To->file : null,
                                   'file_client_name'   =>  !empty($data->FileAttachAssessment1To->file_client_name) ? $data->FileAttachAssessment1To->file_client_name : null,
                                   'details_four'       =>  (count($data->FileAttachAssessment2Many) > 0) ? json_encode($data->FileAttachAssessment2Many) : null,
                                   'attachs'            => (count($data->FileAttachAssessment3Many) > 0) ? json_encode($data->FileAttachAssessment3Many) : null,
                                   'file'               =>  (count($data->FileAttachAssessment4Many) > 0) ? json_encode($data->FileAttachAssessment4Many) : null,
                                   'attachs_car'        =>  !empty($data->FileAttachAssessment5To->file) ? $data->FileAttachAssessment5To->file : null, // ปิด car
                                   'attach_client_name' =>  !empty($data->FileAttachAssessment5To->file_client_name) ? $data->FileAttachAssessment5To->file_client_name : null,
                                   'created_by'         =>  auth()->user()->runrecno
                            ]);
   }

 public function DataAssessment($id) {
    // dd($id);
    $previousUrl = app('url')->previous();
    $assessment = CertiCbSaveAssessment::findOrFail($id);
    $attach_path = $this->attach_path;//path ไฟล์แนบ

    return view('certify/cb.save_assessment_cb.form_assessment', compact('assessment','previousUrl','attach_path'));
 }
public function UpdateAssessment(Request $request, $id)
{
    // dd($request->all());
    $auditors = CertiCbSaveAssessment::findOrFail($id);
    $tb = new CertiCbSaveAssessment;
    $CertiCb = CertiCb::findOrFail($auditors->app_certi_cb_id);

    try {
        if($auditors->degree != 5)
        {
            $ids = $request->input('id');
            if(isset($ids))
            {
                foreach ($ids as $key => $item) {
                        $bug = CertiCbSaveAssessmentBug::where('id',$item)->first();
                    if(!is_null($bug)){
                        $bug->status        = $request->status[$bug->id] ??  @$bug->status;
                        $bug->comment       = $request->comment[$bug->id] ?? @$bug->comment;
                        $bug->file_status   = $request->file_status[$bug->id] ??  @$bug->file_status;
                        $bug->file_comment  = $request->file_comment[$bug->id] ?? null;
                        $bug->cause  = $request->cause[$bug->id] ?? null;
                        // $bug->details =   null; //  แนวทางการแก้ไข
                        $bug->save();
                    }
                }

                // if($request->hasFile('file_car')){
                if($request->assessment_passed == 1){
                            $auditors->main_state   = 1;
                            $auditors->degree       = 4;
                            $auditors->date_car     = date("Y-m-d"); // วันที่ปิด Car
                            $auditors->bug_report   = 2;
                }else{
                        if(isset($request->main_state)){
                            $auditors->main_state   =  2 ;
                            $auditors->degree       = 8;
                        }else{
                            $auditors->main_state   = 1;
                            $auditors->degree       = 3;
                        }
                }
                $auditors->save();

                // รายงานการตรวจประเมิน
                if($request->file  &&  $request->hasFile('file'))
                {
                    $certi_cb_attach_more                   = new CertiCBAttachAll();
                    $certi_cb_attach_more->app_certi_cb_id  = $auditors->app_certi_cb_id ?? null;
                    $certi_cb_attach_more->ref_id           = $auditors->id;
                    $certi_cb_attach_more->table_name       = $tb->getTable();
                    $certi_cb_attach_more->file_section     = '1';
                    $certi_cb_attach_more->file             = $this->storeFile($request->file,$CertiCb->app_no);
                    $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
                    $certi_cb_attach_more->token            = str_random(16);
                    $certi_cb_attach_more->save();
                }

                if($auditors->main_state == 1){
                    // รายงาน Scope
                    if($request->file_scope &&  $request->hasFile('file_scope'))
                    {
                        foreach ($request->file_scope as $index => $item){
                            $certi_cb_attach_more                       = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id      = $auditors->app_certi_cb_id ?? null;
                            $certi_cb_attach_more->ref_id               = $auditors->id;
                            $certi_cb_attach_more->table_name           = $tb->getTable();
                            $certi_cb_attach_more->file_section         = '2';
                            $certi_cb_attach_more->file                 = $this->storeFile($item,$CertiCb->app_no);
                            $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_cb_attach_more->token                = str_random(16);
                            $certi_cb_attach_more->save();
                        }
                    }
                    // รายงาน สรุปรายงานการตรวจทุกครั้ง
                    if($request->file_report &&  $request->hasFile('file_report')){
                        foreach ($request->file_report as $index => $item){
                            $certi_cb_attach_more                       = new CertiCBAttachAll();
                            $certi_cb_attach_more->app_certi_cb_id      = $auditors->app_certi_cb_id ?? null;
                            $certi_cb_attach_more->ref_id               = $auditors->id;
                            $certi_cb_attach_more->table_name           = $tb->getTable();
                            $certi_cb_attach_more->file_section         = '3';
                            $certi_cb_attach_more->file                 = $this->storeFile($item,$CertiCb->app_no);
                            $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                            $certi_cb_attach_more->token                = str_random(16);
                            $certi_cb_attach_more->save();
                        }
                    }
                }

                // ไฟล์แนบ
                if($request->attachs &&  $request->hasFile('attachs')){
                    foreach ($request->attachs as $index => $item){
                        $certi_cb_attach_more                       = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id      = $auditors->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id               = $auditors->id;
                        $certi_cb_attach_more->table_name           = $tb->getTable();
                        $certi_cb_attach_more->file_section         = '4';
                        $certi_cb_attach_more->file                 = $this->storeFile($item,$CertiCb->app_no);
                        $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $certi_cb_attach_more->token                = str_random(16);
                        $certi_cb_attach_more->save();
                    }
                }

                // รายงาน Car
                if($request->file_car &&  $request->hasFile('file_car')){
                    $certi_cb_attach_more                       = new CertiCBAttachAll();
                    $certi_cb_attach_more->app_certi_cb_id      = $auditors->app_certi_cb_id ?? null;
                    $certi_cb_attach_more->ref_id               = $auditors->id;
                    $certi_cb_attach_more->table_name           = $tb->getTable();
                    $certi_cb_attach_more->file_section         = '5';
                    $certi_cb_attach_more->file                 = $this->storeFile($request->file_car,$CertiCb->app_no);
                    $certi_cb_attach_more->file_client_name     = HP::ConvertCertifyFileName($request->file_car->getClientOriginalName());
                    $certi_cb_attach_more->token                = str_random(16);
                    $certi_cb_attach_more->save();
                }

                //  Log
                $this->set_history_bug($auditors);
                // สถานะ แต่งตั้งคณะกรรมการ
                $committee = CertiCBAuditors::findOrFail($auditors->auditors_id);
                if($auditors->degree == 3)
                {
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();
                    $this->set_check_mail($auditors,$CertiCb);
                }elseif($auditors->degree == 4){
                    $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                    $committee->save();
                //  Log
                $this->set_history($auditors);
                //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
                $this->set_mail_past($auditors,$CertiCb);

                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();
                    // สถานะ แต่งตั้งคณะกรรมการ
                    $auditor = CertiCBAuditors::where('app_certi_cb_id',$CertiCb->id)
                                                ->whereIn('step_id',[9,10])
                                                ->whereNull('status_cancel')
                                                ->get();

                    if(count($auditor) == count($CertiCb->CertiCBAuditorsManyBy)){
                        $report                   = new  CertiCBReview;  //ทบทวนฯ
                        $report->app_certi_cb_id  = $CertiCb->id;
                        $report->save();
                        $CertiCb->update(['review'=>1,'status'=>11]);  // ทบทวน
                    }
                }

            }
            }
            else
            {
                // รายงาน Scope
                if($request->file_scope  && $request->hasFile('file_scope')){
                    $file_scope = [];
                    foreach ($request->file_scope as $index => $item){
                        $data                   = new stdClass;
                        $data->file             = $this->storeFile($item,$CertiCb->app_no);
                        $data->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $file_scope[] = $data;
                    }
                }
            // รายงาน สรุปรายงานการตรวจทุกครั้ง
                if($request->file_report  && $request->hasFile('file_report'))
                {
                    $file_report = [];
                    foreach ($request->file_report as $index => $item){
                        $data                   = new stdClass;
                        $data->file             = $this->storeFile($item,$CertiCb->app_no);
                        $data->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
                        $file_report[] = $data;
                    }
                }

                $auditors->degree = 4;
                $auditors->save();
                $committee = CertiCBAuditors::findOrFail($auditors->auditors_id);
                $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                $committee->save();
                $tb = new CertiCBSaveAssessment;
                $assessment = CertiCBSaveAssessment::select('name','auditors_id', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                        ->where('id',$id)
                        ->first();
                CertiCbHistory::create([
                                    'app_certi_cb_id'    =>  $auditors->app_certi_cb_id ?? null,
                                    'auditors_id'        =>  $assessment->auditors_id ?? null,
                                    'system'             =>  8,
                                    'table_name'         =>  $tb->getTable(),
                                    'ref_id'             =>  $auditors->id,
                                    'details_one'        =>  json_encode($assessment) ?? null,
                                    'details_two'        =>  null,
                                    'details_three'      =>  !empty($auditors->FileAttachAssessment1To->file) ? $auditors->FileAttachAssessment1To->file : null,
                                    'file_client_name'   =>  !empty($auditors->FileAttachAssessment1To->file_client_name) ? $auditors->FileAttachAssessment1To->file_client_name : null,
                                    'details_four'       =>  isset($file_scope) ?  json_encode($file_scope): null,
                                    'attachs'            =>  isset($file_report) ?  json_encode($file_report): null,
                                    'file'               =>  (count($auditors->FileAttachAssessment4Many) > 0) ? json_encode($auditors->FileAttachAssessment4Many) : null,
                                    'attachs_car'        =>  !empty($auditors->FileAttachAssessment5To->file) ? $auditors->FileAttachAssessment5To->file : null, // ปิด car
                                    'attach_client_name' =>  !empty($auditors->FileAttachAssessment5To->file_client_name) ? $auditors->FileAttachAssessment5To->file_client_name : null,
                                    'created_by'         =>  auth()->user()->runrecno
                                ]);

            // $auditors->update(['degree'=>6]);
            //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
            $this->set_mail_past($auditors,$CertiCb);
        }


        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certify/save_assessment-cb')->with('message', 'เรียบร้อยแล้ว!');
        }

    } catch (\Exception $e) {
        return redirect('certify/save_assessment-cb/assessment/'.$auditors->id.'/edit')->with('message', 'เกิดข้อผิดพลาด!');
    }

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


        // สำหรับเพิ่มรูปไปที่ store
        public function storeFile($files, $app_no = 'files_cb', $name = null)
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

        public function EmailToCbExpert(Request $request)
        {
              // dd($request->all());
              $assessment = CertiCBSaveAssessment::find($request->assessmentId);
              $expertEmails = $request->selectedEmails;
            //   dd($request->assessmentId);
              $app = $assessment->CertiCBCostTo;

              
  
              $config = HP::getConfig();
              $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
              $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
              $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
              $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
  
              // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
              $data_app =  [
                              'certi_cb'     => $app,
                              'url'           => $url_center.'/create-by-cb-expert/' . $assessment->id .'?token='.$assessment->expert_token,
                              'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                              'email_cc'      =>  !empty($app->DataEmailDirectorLABCC) ? $app->DataEmailDirectorLABCC :  $EMail,
                              'email_reply'   => !empty($app->DataEmailDirectorLABReply) ? $app->DataEmailDirectorLABReply :  $EMail
                          ];
        
          
              $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                          $app->id,
                                                          (new CertiCb())->getTable(),
                                                          $app->id,
                                                          (new CertiCBSaveAssessment)->getTable(),
                                                          1,
                                                          'เพิ่มรายการข้อบกพร่อง / ข้อสังเกต',
                                                          view('mail.CB.mail_cb_expert', $data_app),
                                                          $app->created_by,
                                                          $app->agent_id,
                                                          auth()->user()->getKey(),
                                                          !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                          $app->email,
                                                          !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                          !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                          null
                                                          );
  
              $html = new MailToCbExpert($data_app);
              $mail = Mail::to($expertEmails)->send($html);
              if(is_null($mail) && !empty($log_email)){
                  HP::getUpdateCertifyLogEmail($log_email->id);
              }
        }

        
        public function viewCbInfo($id)
        {

            $assessment = CertiCBSaveAssessment::find($id);
            $cbReportInfo = CbReportInfo::where('cb_assessment_id',$id)->first();

            $app_certi_cb = $assessment->CertiCBCostTo;
            $boardAuditor = $assessment->CertiCBAuditorsTo;
            $id = $boardAuditor->auditor_id;
    
            $certiCBAuditorsLists = $boardAuditor->CertiCBAuditorsLists;
    
            $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id
    
            $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
    
            // foreach ($groups as $group) {
            //     $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
            //     $auditors = $group->auditors; // $auditors เป็น Collection
    
            //     // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
            //     if (!isset($statusAuditorMap[$statusAuditorId])) {
            //         $statusAuditorMap[$statusAuditorId] = [];
            //     }
    
            //     // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
            //     foreach ($auditors as $auditor) {
            //         $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
            //     }
            // }
    
            // $uniqueAuditorIds = array_unique($auditorIds);
    
            // $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();
    
            // $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);
    
            // $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
            // $dateRange = "";
    
            // if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
            //     if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
            //         // ถ้าเป็นวันเดียวกัน
            //         $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
            //     } else {
            //         // ถ้าเป็นคนละวัน
            //         $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
            //                     " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
            //     }
            // }
    
            // $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();
            // $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
            // // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
            // if ($boardAuditorExpert && $boardAuditorExpert->expert) {
            //     // แปลงข้อมูล JSON ใน expert กลับเป็น array
            //     $categories = json_decode($boardAuditorExpert->expert, true);
            
            //     // ถ้ามีหลายรายการ
            //     if (count($categories) > 1) {
            //         // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
            //         $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
            //         $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
            //     } elseif (count($categories) == 1) {
            //         // ถ้ามีแค่รายการเดียว
            //         $experts = $categories[0];
            //     } else {
            //         $experts = ''; // ถ้าไม่มีข้อมูล
            //     }
            
            // }
    
            // $scope_branch = "";
            // if ($certi_lab->lab_type == 3){
            //     $scope_branch = $certi_lab->BranchTitle;
            // }else if($certi_lab->lab_type == 4)
            // {
            //     $scope_branch = $certi_lab->ClibrateBranchTitle;
            // }
    
            // $data = new stdClass();
    
            // $data->header_text1 = '';
            // $data->header_text2 = '';
            // $data->header_text3 = '';
            // $data->header_text4 = $certi_lab->app_no;
            // $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
            // $data->lab_name = $certi_lab->lab_name;
            // $data->scope_branch = $scope_branch;
            // $data->app_np = 'ทดสอบ ๑๖๗๑';
            // $data->certificate_no = '13-LB0037';
            // $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
            // $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
            // $data->experts = $experts;
            // $data->date_range = $dateRange;
            // $data->statusAuditorMap = $statusAuditorMap;
    
            // // $notice = Notice::find($notice_id);
            // $assessment = $notice->assessment;
            // // dd($statusAuditorMap);
            // $app_certi_lab = $notice->applicant;
            // $boardAuditor = $assessment->board_auditor_to;
            // $id = $boardAuditor->auditor_id;
            // $labRequest = null;
            
            // if($app_certi_lab->lab_type == 4){
            //     $labRequest = LabCalRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            // }else if($app_certi_lab->lab_type == 3)
            // {
            //     $labRequest = LabTestRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
            // }

            // $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->get();
            // $approveNoticeItems = NoticeItem::where('app_certi_lab_notice_id', $notice->id)
            //     ->whereNotNull('attachs')
            //     ->where('status',1)
            //     ->where('file_status',1)
            //     ->get();
            // return view('certify.cb.save_assessment_cb.view-report', [
            //     'labReportInfo' => $labReportInfo,
            //     'data' => $data,
            //     'notice' => $notice,
            //     'assessment' => $assessment,
            //     'boardAuditor' => $boardAuditor,
            //     'certi_lab' => $app_certi_lab,
            //     'labRequest' => $labRequest,
            //     'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            //     'approveNoticeItems' => $approveNoticeItems,
            //     'id' => $id
            // ]);
        }

        public function createCbReport($id)
        {
            $assessment = CertiCBSaveAssessment::find($id);
            $cbReportInfo = CbReportInfo::where('cb_assessment_id',$id)->first();
            $certi_cb = CertiCb::find($assessment->app_certi_cb_id);

            $referenceDocuments = CertiCBAttachAll::where('app_certi_cb_id',$assessment->app_certi_cb_id)
                    ->where('ref_id',$assessment->id)
                    ->where('file_section','123')
                    ->get();
            if($cbReportInfo == null)
            {
                return view('certify.cb.save_assessment_cb.report.index',[
                    'assessment' => $assessment,
                    'certi_cb' =>$certi_cb,
                    'referenceDocuments' => $referenceDocuments
                ]);
            }else{

                $cbReportInfoSigners = SignAssessmentReportTransaction::where('report_info_id',$cbReportInfo->id)
                                    ->where('certificate_type',0)
                                    ->where('report_type',1)
                                    ->get();
                return view('certify.cb.save_assessment_cb.report.view',[
                    'cbReportInfo' => $cbReportInfo,
                    'assessment' => $assessment,
                    'certi_cb' =>$certi_cb,
                    'referenceDocuments' => $referenceDocuments,
                    'cbReportInfoSigners' => $cbReportInfoSigners,
                ]);
            }

        }
        public function storeCbReport(Request $request)
        {
            // dd($request->all());
            $signers = json_decode($request->input('signer'), true);
            // dd($signers);
            $data = json_decode($request->input('data'), true); // แปลง JSON String เป็น Array
            $id = $request->id;

            CbReportInfo::where('cb_assessment_id',$id)->delete();

            $assessment = CertiCBSaveAssessment::find($id);
            
            // สร้าง array สำหรับ insert
            $insertData = [
                'cb_assessment_id' => $id,
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

            $cbReportInfo = CbReportInfo::create($insertData);

            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');
            SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
                                            ->where('certificate_type',0)
                                            ->where('report_type',1)
                                            ->delete();
            // dd($signers);
            foreach ($signers as $signer) {
                // ตรวจสอบความถูกต้องของข้อมูล
                if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $cbReportInfo->id,
                    'signer_id' => $signer['signer_id'],
                    'signer_name' => $signer['signer_name'],
                    'signer_position' => $signer['signer_position'],
                    'signer_order' => $signer['id'],
                    'view_url' => $url . '/certify/save_assessment-cb/cb-report-create/'. $id,
                    'certificate_type' => 0,
                    'report_type' => 1,
                    'app_id' => $assessment->CertiCBCostTo->app_no,
                ]);
            }
            return response()->json(['cbReportInfo' => $cbReportInfo]);
        }
        
        public function createCbReportTwo($id)
        {
            // dd('ok');
            $assessment = CertiCBSaveAssessment::find($id);
            $cbReportInfo = CbReportTwoInfo::where('cb_assessment_id',$id)->first();

            // dd($cbReportInfo);
            $certi_cb = CertiCb::find($assessment->app_certi_cb_id);

            $referenceDocuments = CertiCBAttachAll::where('app_certi_cb_id',$assessment->app_certi_cb_id)
                    ->where('ref_id',$assessment->id)
                    ->where('file_section','123')
                    ->get();
            if($cbReportInfo == null)
            {
                return view('certify.cb.save_assessment_cb.report_two.index',[
                    'assessment' => $assessment,
                    'certi_cb' =>$certi_cb,
                    'referenceDocuments' => $referenceDocuments
                ]);
            }else{

                $cbReportInfoSigners = SignAssessmentReportTransaction::where('report_info_id',$cbReportInfo->id)
                                    ->where('certificate_type',0)
                                    ->where('report_type',2)
                                    ->get();
                $cbReportInfoSignerApprovedAlls = SignAssessmentReportTransaction::where('report_info_id',$cbReportInfo->id)
                                    ->where('certificate_type',0)
                                    ->where('report_type',2)
                                    ->where('approval',1)
                                    ->get();                    
                return view('certify.cb.save_assessment_cb.report_two.view',[
                    'cbReportInfo' => $cbReportInfo,
                    'assessment' => $assessment,
                    'certi_cb' =>$certi_cb,
                    'referenceDocuments' => $referenceDocuments,
                    'cbReportInfoSigners' => $cbReportInfoSigners,
                    'cbReportInfoSignerApprovedAlls' => $cbReportInfoSignerApprovedAlls
                ]);
            }

        }

        public function storeCbReportTwo(Request $request)
        {
            // dd($request->all());
            $signers = json_decode($request->input('signer'), true);
            // dd($signers);
            $data = json_decode($request->input('data'), true); // แปลง JSON String เป็น Array
            $id = $request->id;

            CbReportTwoInfo::where('cb_assessment_id',$id)->delete();

            $assessment = CertiCBSaveAssessment::find($id);
            
            // สร้าง array สำหรับ insert
            $insertData = [
                'cb_assessment_id' => $id,
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

            $cbReportInfo = CbReportTwoInfo::create($insertData);

            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');
            SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
                                            ->where('certificate_type',0)
                                            ->where('report_type',2)
                                            ->delete();
            // dd($signers);
            foreach ($signers as $signer) {
                // ตรวจสอบความถูกต้องของข้อมูล
                if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $cbReportInfo->id,
                    'signer_id' => $signer['signer_id'],
                    'signer_name' => $signer['signer_name'],
                    'signer_position' => $signer['signer_position'],
                    'signer_order' => $signer['id'],
                    'view_url' => $url . '/certify/save_assessment-cb/cb-report-two-create/'. $id,
                    'certificate_type' => 0,
                    'report_type' => 2,
                    'app_id' => $assessment->CertiCBCostTo->app_no,
                ]);
            }
            return response()->json(['cbReportInfo' => $cbReportInfo]);
        }
        

        public function addAuditorRepresentative(Request $request)
        {
            // Validate input
            $request->validate([
                'assessment_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
            ]);
        
            // Create new AuditorRepresentative record
            $auditor = AuditorRepresentative::create([
                'assessment_id' => $request->assessment_id,
                'name' => $request->name,
                'position' => $request->position,
            ]);
        
            // Get updated list
            $auditorRepresentatives = AuditorRepresentative::where('assessment_id', $request->assessment_id)->get();
            // dd($auditorRepresentatives);
            // Return updated list as JSON
            return response()->json(['auditorRepresentatives' => $auditorRepresentatives]);
        }

        public function deleteAuditorRepresentative(Request $request)
        {
            // ตรวจสอบ input
            $request->validate([
                'auditor_id' => 'required|integer',
                'assessment_id' => 'required|integer',
            ]);

            // ลบข้อมูล
            AuditorRepresentative::where('id', $request->auditor_id)->delete();

            // ดึงข้อมูลอัปเดต
            $auditorRepresentatives = AuditorRepresentative::where('assessment_id', $request->assessment_id)->get();

            // ส่งข้อมูลที่อัปเดตกลับไป
            return response()->json(['auditorRepresentatives' => $auditorRepresentatives]);
        }

        public function addReferenceDocument(Request $request)
        {
            // dd($request->all());
            $assessment = CertiCBSaveAssessment::find($request->ref_id);
            
            $certi_cb_attach_more = new CertiCBAttachAll();
            $certi_cb_attach_more->app_certi_cb_id  = $assessment->app_certi_cb_id ?? null;
            $certi_cb_attach_more->ref_id           = $assessment->id;
            $certi_cb_attach_more->table_name       = (new CertiCBAttachAll)->getTable();
            $certi_cb_attach_more->file_section     = '123';
            $certi_cb_attach_more->file             = $this->storeFile($request->file, $assessment->app_no);
            $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($request->file->getClientOriginalName());
            $certi_cb_attach_more->token            = Str::random(16);
            $certi_cb_attach_more->save();

             // ส่งข้อมูลไฟล์กลับไปที่ JavaScript แบบเดิม
            $referenceDocuments = CertiCBAttachAll::where('app_certi_cb_id', $assessment->app_certi_cb_id)
            ->where('ref_id', $assessment->id)
            ->where('file_section', '123')
            ->get();  // ไม่ต้องแปลงเป็นอาร์เรย์

            return response()->json(['referenceDocuments' => $referenceDocuments]);
        }

        public function deleteReferenceDocument(Request $request)
        {
            $assessment = CertiCBSaveAssessment::find($request->assessment_id);
            // ค้นหาข้อมูลเอกสารที่ต้องการลบ
            $referenceDocument = CertiCBAttachAll::find($request->id);

            

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
            $referenceDocuments = CertiCBAttachAll::where('app_certi_cb_id', $assessment->app_certi_cb_id)
            ->where('ref_id', $assessment->id)
            ->where('file_section', '123')
            ->get();  // ไม่ต้องแปลงเป็นอาร์เรย์

            // dd( $referenceDocuments);

            return response()->json(['referenceDocuments' => $referenceDocuments]);
        }

        public function checkCompleteReportOneSign(Request $request)
        {
            $assessmentId = $request->assessment_id;
            $cbReportInfo = CbReportInfo::where('cb_assessment_id' ,$assessmentId)->first();

            $signedCount = SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
            ->where('certificate_type',0)
            ->where('report_type',1)
            ->where('approval',1)
            ->count();


            $recordCount = SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
                            ->where('certificate_type',0)
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
            $assessmentId = $request->assessment_id;
            $cbReportInfo = CbReportTwoInfo::where('cb_assessment_id' ,$assessmentId)->first();

            $signedCount = SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
            ->where('certificate_type',0)
            ->where('report_type',2)
            ->where('approval',1)
            ->count();


            $recordCount = SignAssessmentReportTransaction::where('report_info_id', $cbReportInfo->id)
                            ->where('certificate_type',0)
                            ->where('report_type',2)
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

        

}

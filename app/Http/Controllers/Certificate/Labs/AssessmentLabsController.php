<?php

namespace App\Http\Controllers\Certificate\Labs;

use DB;
use HP; 
use App\User;
use stdClass;
use Carbon\Carbon;
use App\AttachFile;
use App\CertificateExport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\Mail\Lab\MailToLabExpert;
use Yajra\Datatables\Datatables; 
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use Illuminate\Support\Facades\Mail; 

use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use App\Mail\Tracking\MailToLabSurExpert;
use App\Mail\Tracking\SaveAssessmentMail;
use App\Mail\Tracking\CheckSaveAssessment;
use App\Mail\Tracking\MailLabReportSigner;
use App\Models\Certificate\TrackingReview;
use App\Models\Certificate\TrackingAssigns;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingHistory; 
use App\Mail\Tracking\SaveAssessmentPastMail;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingInspection;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certificate\TrackingLabReportOne;
use App\Models\Certificate\TrackingLabReportTwo;
use App\Models\Certificate\TrackingLabReportInfo;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Bcertify\BoardAuditoExpertTracking;
use App\Models\Certificate\TrackingAssessmentBug; 
use App\Models\Certificate\SignAssessmentTrackingReportTransaction;


class AssessmentLabsController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/trackinglabs';
    }

    public function index(Request $request)
    { 
        $model = str_slug('assessmentlabs','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certificate.labs.assessment-labs.index' );
        }
        abort(403);
    } 

    public function data_list(Request $request)
    { 
      $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
 
      $model = str_slug('assessmentlabs', '-');
      $filter_search = $request->input('filter_search');
 
      $filter_bug_report = $request->input('filter_bug_report');

      $filter_start_report_date = !empty($request->get('filter_start_report_date'))?HP::convertDate($request->get('filter_start_report_date'),true):null;
      $filter_end_report_date = !empty($request->get('filter_end_report_date'))?HP::convertDate($request->get('filter_end_report_date'),true):null;
      $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
      $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
      $query = TrackingAssessment::query()
                                    ->where('certificate_type',3) ->where('ref_table',(new CertificateExport)->getTable())
                                      ->when($filter_search, function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search ); 
                                                $query->where(function ($query2) use($search_full) {
                                                  $ids =  TrackingAuditors::select('id')->Where(DB::raw("REPLACE(reference_refno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(auditor,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(no,' ','')"), 'LIKE', "%".$search_full."%")   ;
                                                   $query2->whereIn('auditors_id', $ids);
                                                });
                                         }) 
                                        ->when($filter_bug_report, function ($query, $filter_bug_report){
                                            if($filter_bug_report == '2'){
                                                return  $query->where('bug_report','!=',1);
                                            }else{
                                                return  $query->where('bug_report', $filter_bug_report);
                                            }
                                        })
                                        ->when($filter_start_report_date, function ($query, $filter_start_report_date) use($filter_end_report_date){
                                            if(!is_null($filter_start_report_date) && !is_null($filter_end_report_date) ){
                                                return  $query->whereBetween('report_date',[$filter_start_report_date,$filter_end_report_date]);
                                            }else if(!is_null($filter_start_report_date) && is_null($filter_end_report_date)){
                                                return  $query->whereDate('report_date',$filter_start_report_date);
                                            }
                                        })    
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                            if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                return  $query->whereBetween('created_at',[$filter_start_date,$filter_end_date]);
                                            }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                return  $query->whereDate('created_at',$filter_start_date);
                                            }
                                        }); 
                                  
                                                  
      return Datatables::of($query)
                          ->addIndexColumn()
                          ->addColumn('checkbox', function ($item) {
                              return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                          })
                          ->addColumn('reference_refno', function ($item) {
                              return   !empty($item->reference_refno)? $item->reference_refno:'';
                          }) 
                          ->addColumn('auditor', function ($item) {
                              return   !empty($item->auditors_to->auditor)? $item->auditors_to->auditor:'';
                          })
                          ->addColumn('report_date', function ($item) {
                            return   !empty($item->report_date) ?HP::DateThai($item->report_date):'-';
                          })
                          ->addColumn('status', function ($item) {
                            return    !empty($item->created_by) && !empty($item->StatusTitle) ? $item->StatusTitle:'-';
                          }) 
                          ->addColumn('created_at', function ($item) {
                            return   !empty($item->created_at) ?HP::DateThai($item->created_at):'-';
                          })
                          ->addColumn('full_name', function ($item) {
                            return   !empty($item->user_created->FullName) ? $item->user_created->FullName :'-';
                          })

                          ->addColumn('action', function ($item) use($model) {
                                  return HP::buttonAction( $item->id, 'certificate/assessment-labs','Certificate\labs\\AssessmentLabsController@destroy', 'assessmentlabs',false,true,false);
                          })
                          ->order(function ($query) {
                              $query->orderBy('id', 'DESC');
                          })
                          ->rawColumns([ 'checkbox',    'action']) 
                          ->make(true);
    } 


    public function create()
    {
        $model = str_slug('assessmentlabs','-');
        if(auth()->user()->can('add-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment = new TrackingAssessment;
            $bug = [new TrackingAssessmentBug];
 
            $app_no = [];
            //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
           if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
               $check = TrackingAssigns::where('ref_table', (new CertificateExport)->getTable())
                                    ->where('certificate_type',3)
                                    ->where('user_id',auth()->user()->runrecno)
                                    ->pluck('ref_id'); // เช็คเจ้าหน้าที่ IB
               if(count($check) > 0 ){
                   $auditor= TrackingAuditors::select('id','ref_id','auditor')
                                    ->whereIn('step_id',[12])
                                    ->whereIn('ref_id',$check)
                                    ->orderby('id','desc')
                                    ->get();
                 if(count($auditor) > 0 ){
                   foreach ($auditor as $item){
                     $app_no[$item->id] = $item->auditor . " ( ". @$item->reference_refno . " )";
                    }
                  } 
                } 
            }else{
                   $auditor = TrackingAuditors::select('id','ref_id','auditor')
                                            ->whereIn('step_id',[12])
                                           ->orderby('id','desc')
                                           ->get();
                  if(count($auditor) > 0 ){
                    foreach ($auditor as $item){
                         $app_no[$item->id] = $item->auditor . " ( ". @$item->reference_refno . " )";
                    }
                  }
             }
            
            return view('certificate.labs.assessment-labs.create',['app_no'=> $app_no,
                                                                'assessment'=>$assessment,
                                                                'bug'=>$bug
                                                                ]);
        }
        abort(403);

    }

    public function store(Request $request)
    {
        // dd($request->all());
        $model = str_slug('assessmentlabs','-');
        if(auth()->user()->can('add-'.$model)) {
   // try {    
            $request->validate([
                'auditors_id' => 'required' 
            ]);


            $request->request->add(['created_by' => auth()->user()->getKey()]); 
            $requestData = $request->all();
            $requestData['report_date']    =  HP::convertDate($request->report_date,true) ?? null;
            $requestData['vehicle']        = isset($request->vehicle) ? $request->vehicle : null;
            if($request->bug_report == 1){
                $requestData['main_state'] = isset($request->main_state) ? 2 : 1;
            }else{
                $requestData['main_state'] = 1;
            }
            

            $committee = TrackingAuditors::findOrFail($request->auditors_id); 
            if(!is_null($committee)){
                $requestData['certificate_type']= 3;
                $requestData['reference_refno'] = $committee->reference_refno ?? null;
                $requestData['ref_table']       = $committee->ref_table ?? null;
                $requestData['ref_id']          = $committee->ref_id ?? null;
            }

            $assessment = TrackingAssessment::create($requestData);
 
            // ข้อบกพร่อง/ข้อสังเกต
            if(isset($requestData["detail"])  && $assessment->bug_report == 1){ 
                self::storeDetail($assessment,$requestData["detail"]);
            }   
    
            // รายงานการตรวจประเมิน
             if($request->file  && $request->hasFile('file') ){
                       HP::singleFileUploadRefno(
                              $request->file('file') ,
                              $this->attach_path.'/'.$assessment->reference_refno,
                              ( $tax_number),
                              (auth()->user()->FullName ?? null),
                              'Center',
                              (  (new TrackingAssessment)->getTable() ),
                              $assessment->id,
                              '1',
                              null
                        );
            }
        if($assessment->bug_report == 2){
    
            // รายงาน Scope
            if($request->file_scope  && $request->hasFile('file_scope')){
        
                foreach ($request->file_scope as $index => $item){
                        HP::singleFileUploadRefno(
                            $item ,
                            $this->attach_path.'/'.$assessment->reference_refno,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new TrackingAssessment)->getTable() ),
                            $assessment->id,
                            '2',
                            null
                        );
                }
            }
           // รายงาน สรุปรายงานการตรวจทุกครั้ง
            if($request->file_report  && $request->hasFile('file_report')){
                foreach ($request->file_report as $index => $item){
                            HP::singleFileUploadRefno(
                                $item ,
                                $this->attach_path.'/'.$assessment->reference_refno,
                                ( $tax_number),
                                (auth()->user()->FullName ?? null),
                                'Center',
                                (  (new TrackingAssessment)->getTable() ),
                                $assessment->id,
                                '3',
                                null
                            );
                }
            }
            }
            // ไฟล์แนบ
            if($request->attachs  && $request->hasFile('attachs') ){
                foreach ($request->attachs as $index => $item){
                        HP::singleFileUploadRefno(
                            $item ,
                            $this->attach_path.'/'.$assessment->reference_refno,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new TrackingAssessment)->getTable() ),
                            $assessment->id,
                            '4',
                            null
                        );
                }
            }
 
    
    // สถานะ แต่งตั้งคณะกรรมการ
        $export = CertificateExport::findOrFail($assessment->ref_id);
       if(in_array($assessment->degree,[1,8])  && $assessment->bug_report == 1 && !is_null($export) &&  $assessment->vehicle == 1 ){
                //Log 
                self::set_history_bug($assessment);
                //  Mail
                
                // self::set_mail($assessment);  
                if($request->submit_type == "confirm")
                {
                  $this->set_mail($assessment);   
                }
               if($assessment->main_state == 1 ){
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();
             
                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();
 
                   // สถานะ แต่งตั้งคณะกรรมการ
                    $auditor = TrackingAuditors::where('ref_id',$export->id)
                                                ->where('ref_table',(new CertificateExport)->getTable())
                                                ->where('certificate_type',3)
                                                ->where('reference_refno',$assessment->reference_refno)
                                                ->whereNull('status_cancel') 
                                                ->get(); 
            
                    if(count($auditor) == count($export->auditors_status_cancel_many)){
                        $report = new   TrackingReview;  //ทบทวนฯ
                        $report->ref_id             = $export->id;
                        $report->ref_table          = (new CertificateExport)->getTable();
                        $report->certificate_type   =  3;
                        $report->reference_refno    = $assessment->reference_refno;
                        $report->save();

                        // $export->review     = 1;
                        $export->status_id  = 4;
                        $export->save();

                        $inspection =   TrackingInspection::where('ref_id',$export->id)  ->where('ref_table',(new CertificateExport)->getTable())
                        ->where('certificate_type',3)
                        ->where('reference_refno',$export->reference_refno)
                        ->first();
                        if(is_null($inspection)){
                         $inspection = new TrackingInspection;
                        }
                        $inspection->ref_id              = $export->id;
                        $inspection->ref_table           = (new CertificateExport)->getTable();
                        $inspection->certificate_type    = 3;
                        $inspection->reference_refno     = $export->reference_refno;
                        $inspection->save();
                        $this->addScopeFile($inspection);
                    }
                }

        }

        if($assessment->degree == 4){
             $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
             $committee->save();

    
              // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = TrackingAuditors::where('ref_id',$export->id)
                                            ->where('ref_table',(new CertificateExport)->getTable())
                                            ->where('certificate_type',3)
                                            ->where('reference_refno',$assessment->reference_refno)
                                            ->whereNull('status_cancel') 
                                            ->get(); 
        
    
            if(count($auditor) == count($export->auditors_status_cancel_many)){
                $report = new   TrackingReview;  //ทบทวนฯ
                $report->ref_id             = $export->id;
                $report->ref_table          = (new CertificateExport)->getTable();
                $report->certificate_type   =  3;
                $report->reference_refno    = $assessment->reference_refno;
                $report->save();

                // $export->review     = 1;
                $export->status_id  = 4;
                $export->save();

                $inspection =   TrackingInspection::where('ref_id',$export->id)  ->where('ref_table',(new CertificateExport)->getTable())   ->where('certificate_type',3)  ->where('reference_refno',$export->reference_refno)->first();
                if(is_null($inspection)){
                    $inspection = new TrackingInspection;
                }
                $inspection->ref_id              = $export->id;
                $inspection->ref_table           = (new CertificateExport)->getTable();
                $inspection->certificate_type    = 3;
                $inspection->reference_refno     = $export->reference_refno;
                $inspection->save();
                 $this->addScopeFile($inspection);
            }


             self::set_history($assessment);
             self::set_mail_past($assessment);  
  
        }

        // $trackingLabReportInfo = new TrackingLabReportInfo();
        // $trackingLabReportInfo->tracking_assessment_id = $assessment->id;
        // $trackingLabReportInfo->save();

        $trackingLabReportOne = new TrackingLabReportOne();
        $trackingLabReportOne->tracking_assessment_id = $assessment->id;
        $trackingLabReportOne->save();

        $trackingLabReportTwo = new TrackingLabReportTwo();
        $trackingLabReportTwo->tracking_assessment_id = $assessment->id;
        $trackingLabReportTwo->save();

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-labs')->with('message', 'เรียบร้อยแล้ว!');
        }
  // } catch (\Exception $e) {
    //        return redirect('certificate/assessment-labs')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
    // }
        
        }
        abort(403);
    }

    public function edit(Request $request,$id)
    {
        // dd('edit');
        $assessment                   =  TrackingAssessment::findOrFail($id);
        // dd($assessment );
        $trackingAuditor = TrackingAuditors::find( $assessment->auditors_id);
      
        $boardAuditorMsRecordInfo = $trackingAuditor->boardAuditorTrackingMsRecordInfos->first();

      

        $auditors_statuses= $trackingAuditor->auditors_status_many;
        $statusAuditorMap = [];

         

        foreach ($auditors_statuses as $auditors_status)
        {
            // dd($auditors_status->auditors_list_many);
            $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
            $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection
            // dd($auditors);
            // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
            if (!isset($statusAuditorMap[$statusAuditorId])) {
                $statusAuditorMap[$statusAuditorId] = [];
            }
            // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
            foreach ($auditors as $auditor) {
                
                $statusAuditorMap[$statusAuditorId][] = $auditor->user_id;
            }
        }

// dd($statusAuditorMap);
        $model = str_slug('assessmentlabs','-');
        if(auth()->user()->can('edit-'.$model)) {
          $previousUrl = app('url')->previous();
          $assessment                   =  TrackingAssessment::findOrFail($id);
          $assessment->name             =  !empty($assessment->certificate_export_to->CertiLabTo->name) ? $assessment->certificate_export_to->CertiLabTo->name : null;
          $assessment->laboratory_name  =  !empty($assessment->certificate_export_to->CertiLabTo->lab_name) ? $assessment->certificate_export_to->CertiLabTo->lab_name : null; 
          $assessment->auditor          =  !empty($assessment->auditors_to->auditor) ? $assessment->auditors_to->auditor : null;
          $assessment->auditor_date     =  !empty($assessment->auditors_to->CertiAuditorsDateTitle) ? $assessment->auditors_to->CertiAuditorsDateTitle : null;
          $assessment->auditor_file     =  !empty($assessment->auditors_to->FileAuditors2) ? $assessment->auditors_to->FileAuditors2 : null;
          if(count($assessment->tracking_assessment_bug_many) > 0){ 
            $bug =  $assessment->tracking_assessment_bug_many;
          }else{
            $bug =  [new TrackingAssessmentBug];
          }
    
          
          if(in_array($assessment->degree,[2,3,4,5,7,8])){
            // dd('a');
            return view('certificate.labs.assessment-labs.form_assessment', compact('assessment','statusAuditorMap'));
          }else{
            // dd(count($assessment->tracking_assessment_bug_many));
            return view('certificate.labs.assessment-labs.edit', compact('assessment','bug','statusAuditorMap'));
          }
 
          
        
        }
        abort(403);

    }


    public function checkIsReportSigned(Request $request)
    {
        $signAssessmentTrackingReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $request->tracking_report_info_id)
            ->where('certificate_type', 2)
            ->whereNotNull('signer_id')
            ->where('report_type', 1)
            ->count();

        if ($signAssessmentTrackingReportTransactions == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'ยังไม่ได้สร้างรายงาน'
            ]);
        } else {
            $ApprovedSignAssessmentTrackingReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $request->tracking_report_info_id)
                ->where('certificate_type', 2)
                ->whereNotNull('signer_id')
                ->where('approval', 1)
                ->where('report_type', 1)
                ->count();

            if ($ApprovedSignAssessmentTrackingReportTransactions == $signAssessmentTrackingReportTransactions) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'ลงนามครบ'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'อยู่ระหว่างการลงนามรายงานที่ 1'
                ]);
            }
        }
    }

    public function checkIsReportTwoSigned(Request $request)
    {
        $signAssessmentTrackingReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $request->tracking_report_info_id)
            ->where('certificate_type', 2)
            ->whereNotNull('signer_id')
            ->where('report_type', 2)
            ->count();

        if ($signAssessmentTrackingReportTransactions == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'ยังไม่ได้สร้างรายงาน'
            ]);
        } else {
            $ApprovedSignAssessmentTrackingReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $request->tracking_report_info_id)
                ->where('certificate_type', 2)
                ->whereNotNull('signer_id')
                ->where('approval', 1)
                ->where('report_type', 2)
                ->count();

            if ($ApprovedSignAssessmentTrackingReportTransactions == $signAssessmentTrackingReportTransactions) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'ลงนามครบ'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'อยู่ระหว่างการลงนามรายงานที่ 2'
                ]);
            }
        }
    }

    public function update(Request $request, $id)
    {
    //    dd($request->all());

        $model = str_slug('assessmentlabs','-');
        if(auth()->user()->can('edit-'.$model)) {
 
     // try {
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
            if($request->bug_report == 1){
                $requestData['main_state'] = isset($request->main_state) ? 2 : 1;
            }else{
                $requestData['main_state'] = 1;
            }
            $tb         = new TrackingAssessment;
            $assessment = TrackingAssessment::findOrFail($id);
            // dd($assessment,$request->submit_type);
            $requestData['vehicle']        = isset($request->vehicle) ? $request->vehicle : null;

            if(is_null($assessment->created_by)){
                $requestData['created_by'] = auth()->user()->getKey();
                $requestData['created_at'] = date('Y-m-d H:i:s');
            }

            // dd($requestData);
            $assessment->update($requestData);

            // ข้อบกพร่อง/ข้อสังเกต
            if(isset($requestData["detail"]) && $assessment->bug_report == 1){

                $this->storeDetail($assessment,$requestData["detail"]);
            }

            if(isset($requestData["detail"]) && $assessment->bug_report == 1){

                $nowTimeStamp = Carbon::now()->addDays(15)->timestamp;
                $encodedTimestamp = base64_encode($nowTimeStamp);
                $token = Str::random(30) . '_' . $encodedTimestamp;
                $notice_confirm_date = null;
                if($request->submit_type == "confirm")
                {
                    $notice_confirm_date = Carbon::now()->addDays(1);
                }

                $check = TrackingAssessment::findOrFail($id);
                if($check->expert_token != null)
                {
                    $token = $check->expert_token;
                }


                $check = TrackingAssessment::findOrFail($id);

                if($check->submit_type == 'confirm')
                {
                    TrackingAssessment::findOrFail($id)->update([
                        'expert_token' => $token,
                        'notice_confirm_date' => $notice_confirm_date,
                    ]);
                }else{
                    TrackingAssessment::findOrFail($id)->update([
                        'submit_type' => $request->submit_type,
                        'expert_token' => $token,
                        'notice_confirm_date' => $notice_confirm_date,
                    ]);
                }
            }
    
            // รายงานการตรวจประเมิน
             if($request->file  && $request->hasFile('file')){
                        HP::singleFileUploadRefno(
                              $request->file('file') ,
                              $this->attach_path.'/'.$assessment->reference_refno,
                              ( $tax_number),
                              (auth()->user()->FullName ?? null),
                              'Center',
                              (  (new TrackingAssessment)->getTable() ),
                              $assessment->id,
                              '1',
                              null
                        );
             }


        if($assessment->bug_report == 2){
            // รายงาน Scope
            if($request->file_scope  && $request->hasFile('file_scope')){
                foreach ($request->file_scope as $index => $item){
                          HP::singleFileUploadRefno(
                            $item ,
                            $this->attach_path.'/'.$assessment->reference_refno,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new TrackingAssessment)->getTable() ),
                            $assessment->id,
                            '2',
                            null
                        );
                }
            }
           // รายงาน สรุปรายงานการตรวจทุกครั้ง
            if($request->file_report  && $request->hasFile('file_report')){
                foreach ($request->file_report as $index => $item){
                          HP::singleFileUploadRefno(
                              $item ,
                              $this->attach_path.'/'.$assessment->reference_refno,
                              ( $tax_number),
                              (auth()->user()->FullName ?? null),
                              'Center',
                              (  (new TrackingAssessment)->getTable() ),
                              $assessment->id,
                              '3',
                              null
                          );
                }
            }
    }

    // ไฟล์แนบ
    if($request->attachs   && $request->hasFile('attachs')){
                    foreach ($request->attachs as $index => $item){
                            HP::singleFileUploadRefno(
                                $item ,
                                $this->attach_path.'/'.$assessment->reference_refno,
                                ( $tax_number),
                                (auth()->user()->FullName ?? null),
                                'Center',
                                (  (new TrackingAssessment)->getTable() ),
                                $assessment->id,
                                '4',
                                null
                            );
                    }
    }

 
      // สถานะ แต่งตั้งคณะกรรมการ
        $tracking = Tracking::findOrFail($assessment->tracking_id);
        $committee = TrackingAuditors::findOrFail($assessment->auditors_id); 
        if(in_array($assessment->degree,[1,8])  && $assessment->bug_report == 1 && !is_null($tracking) &&  $assessment->vehicle == 1 )
        {
                  //  Log 
                  self::set_history_bug($assessment);
                  //  Mail
                  if($request->submit_type == "confirm")
                  {
                    $this->set_mail($assessment,$tracking->certificate_export_to);   
                  }
                  
                 if($assessment->main_state == 1 ){
                      $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                      $committee->save();
                  
                  }else{
                      $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                      $committee->save();
                  }

                   
                    // สถานะ แต่งตั้งคณะกรรมการ
                    $auditor = TrackingAuditors::where('tracking_id',$tracking->id) 
                                        ->whereNull('status_cancel') 
                                        ->get(); 
            
                    if(count($auditor) == count($tracking->auditors_status_cancel_many)){
    
                        $tracking->status_id  = 4;
                        $tracking->save();
        
                        $inspection =   TrackingInspection::where('tracking_id',$tracking->id)  ->where('reference_refno',$tracking->reference_refno)->first();
                        if(is_null($inspection)){
                            $inspection = new TrackingInspection;
                        }
                        $inspection->tracking_id         = $tracking->id;
                        $inspection->ref_id              = $tracking->ref_id;
                        $inspection->reference_refno    = $tracking->reference_refno;
                        $inspection->ref_table          = (new CertificateExport)->getTable();
                        $inspection->certificate_type   =  3;
                        $inspection->save();
                        $this->addScopeFile($inspection);

                        }
  
          }
  
          if($assessment->degree == 4 && !is_null($tracking) && !is_null($committee)){

                        $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                        $committee->save();
 
                        // สถานะ แต่งตั้งคณะกรรมการ
                        $auditor = TrackingAuditors::where('tracking_id',$tracking->id) 
                                            ->whereNull('status_cancel') 
                                            ->get(); 
                
                        if(count($auditor) == count($tracking->auditors_status_cancel_many)){

                            $tracking->status_id  = 4;
                            $tracking->save();
            
                            $inspection =   TrackingInspection::where('tracking_id',$tracking->id)  ->where('reference_refno',$tracking->reference_refno)->first();
                            if(is_null($inspection)){
                                $inspection = new TrackingInspection;
                            }
                            $inspection->tracking_id        = $tracking->id;
                            $inspection->ref_id             = $tracking->ref_id;
                            $inspection->reference_refno    =   $tracking->reference_refno;
                            $inspection->ref_table          = (new CertificateExport)->getTable();
                            $inspection->certificate_type   =  3;
                            $inspection->save();
                            $this->addScopeFile($inspection);

                            }

                  self::set_history($assessment);

               if( $assessment->vehicle == 1){
                     self::set_mail_past($assessment,$tracking->certificate_export_to);  
               }
          }

          $trackingLabReportOne = TrackingLabReportOne::where('tracking_assessment_id',$assessment->id)->first();

          if($trackingLabReportOne == null)
          {
            $trackingLabReportOne = new TrackingLabReportOne();
            $trackingLabReportOne->tracking_assessment_id = $assessment->id;
            $trackingLabReportOne->save();
          }

        $trackingLabReportTwo = TrackingLabReportTwo::where('tracking_assessment_id',$assessment->id)->first();

          if($trackingLabReportTwo == null)
          {
            $trackingLabReportTwo = new TrackingLabReportTwo();
            $trackingLabReportTwo->tracking_assessment_id = $assessment->id;
            $trackingLabReportTwo->save();
          }


        if($assessment->degree == 4)
        {
            return redirect('certificate/assessment-labs/view-lab-info/'.$assessment->id)->with('message', 'เรียบร้อยแล้ว!');
        }
        

    
        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-labs')->with('message', 'เรียบร้อยแล้ว!');
        }
    // } catch (\Exception $e) {
    //        return redirect('certificate/assessment-labs')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
    // }
        }
        abort(403);

    }


    public function update_assessment(Request $request, $id)
    {
        // dd($request->all());
        $assessment = TrackingAssessment::findOrFail($id);

   
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
    // try { 
    
 
    
            $ids = $request->input('id');
            if(isset($ids)){
            foreach ($ids as $key => $item) {
                    $bug = TrackingAssessmentBug::where('id',$item)->first(); 
                if(!is_null($bug)){ 
                    $bug->status        = $request->status[$bug->id] ??  @$bug->status;
                    $bug->comment       = $request->comment[$bug->id] ?? @$bug->comment;
                    $bug->file_status   = $request->file_status[$bug->id] ??  @$bug->file_status;
                    $bug->file_comment  = $request->file_comment[$bug->id] ?? null;
                    $bug->cause       = $request->cause[$bug->id] ?? @$bug->cause;
                    $bug->owner_id = auth()->user()->runrecno;
                    $bug->save(); 
                }
             }  
    
              if($request->hasFile('file_car')){ 
                        $assessment->main_state   = 1;
                        $assessment->degree       = 4;
                        $assessment->date_car     = date("Y-m-d"); // วันที่ปิด Car
                        $assessment->bug_report   = 2; 
               }else{
                     if(isset($request->main_state)){
                        $assessment->main_state   =  2 ;
                        $assessment->degree       = 8;
                      }else{
                        $assessment->main_state   = 1;
                        $assessment->degree       = 3;
                      }
               }
                $assessment->save();
      
     
             // รายงานการตรวจประเมิน
            if($request->file  &&  $request->hasFile('file')){
                       HP::singleFileUploadRefno(
                              $request->file('file') ,
                              $this->attach_path.'/'.$assessment->reference_refno,
                              ( $tax_number),
                              (auth()->user()->FullName ?? null),
                              'Center',
                              (  (new TrackingAssessment)->getTable() ),
                              $assessment->id,
                              '1',
                              null
                        );
            }
    
    if($assessment->main_state == 1){
                // รายงาน Scope
                if($request->file_scope &&  $request->hasFile('file_scope')){
                    foreach ($request->file_scope as $index => $item){
                            HP::singleFileUploadRefno(
                                $item ,
                                $this->attach_path.'/'.$assessment->reference_refno,
                                ( $tax_number),
                                (auth()->user()->FullName ?? null),
                                'Center',
                                (  (new TrackingAssessment)->getTable() ),
                                $assessment->id,
                                '2',
                                null
                            );
                    }
                }
               // รายงาน สรุปรายงานการตรวจทุกครั้ง
                if($request->file_report &&  $request->hasFile('file_report')){
                    foreach ($request->file_report as $index => $item){
                           HP::singleFileUploadRefno(
                              $item ,
                              $this->attach_path.'/'.$assessment->reference_refno,
                              ( $tax_number),
                              (auth()->user()->FullName ?? null),
                              'Center',
                              (  (new TrackingAssessment)->getTable() ),
                              $assessment->id,
                              '3',
                              null
                          );
                    }
                }
    }

            // ไฟล์แนบ
            if($request->attachs &&  $request->hasFile('attachs')){
                foreach ($request->attachs as $index => $item){
                            HP::singleFileUploadRefno(
                                $item ,
                                $this->attach_path.'/'.$assessment->reference_refno,
                                ( $tax_number),
                                (auth()->user()->FullName ?? null),
                                'Center',
                                (  (new TrackingAssessment)->getTable() ),
                                $assessment->id,
                                '4',
                                null
                            );
                 }
             }
    
            // รายงาน Car
            if($request->file_car &&  $request->hasFile('file_car')){
                        HP::singleFileUploadRefno(
                            $request->file('file_car') ,
                            $this->attach_path.'/'.$assessment->reference_refno,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new TrackingAssessment)->getTable() ),
                            $assessment->id,
                            '5',
                            null
                        );

            }
    
       //
    
             //  Log
            $this->set_history_bug($assessment);
          // สถานะ แต่งตั้งคณะกรรมการ
             $committee = TrackingAuditors::findOrFail($assessment->auditors_id); 

           if($assessment->degree == 3){
                    if($request->file_car &&  $request->hasFile('file_car')){
                        $committee->step_id    = 7; // ผ่านการตรวจสอบประเมิน
                        $assessment->degree    = 7;
                        $assessment->save();
                    } 
                   $this->set_check_mail($assessment);  
            }elseif($assessment->degree == 4){
                  $committee->step_id = 7; // ผ่านการตรวจสอบประเมิน
                   //  Log
                   $this->set_history($assessment);
                   //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
                   $this->set_mail_past($assessment);  
              
            }else{
                $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
            }

            $committee->save();
            $tracking = Tracking::findOrFail($assessment->tracking_id);
            if(!is_null($tracking)){
                // สถานะ แต่งตั้งคณะกรรมการ
               $auditor = TrackingAuditors::where('tracking_id',$tracking->id) 
                                    ->whereNull('status_cancel') 
                                    ->get(); 
        
                if(count($auditor) == count($tracking->auditors_status_cancel_many)){

                    $tracking->status_id  = 4;
                    $tracking->save();
 
                    $inspection =   TrackingInspection::where('tracking_id',$tracking->id)->where('reference_refno',$tracking->reference_refno)->first();
                    if(is_null($inspection)){
                     $inspection = new TrackingInspection;
                    }
                    $inspection->tracking_id         = $tracking->id;
                    $inspection->ref_id              = $tracking->ref_id;
                    $inspection->ref_table           = (new CertificateExport)->getTable();
                    $inspection->certificate_type    = 3;
                    $inspection->reference_refno     = $tracking->reference_refno;
                    $inspection->save();

                    $this->addScopeFile($inspection);
                }
            }
     
         }
    
    
    
        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-labs')->with('message', 'เรียบร้อยแล้ว!');
        }
    
    // } catch (\Exception $e) {
    //     return redirect('certificate/assessment-labs/'.$assessment->id.'/edit')->with('message', 'เกิดข้อผิดพลาด!');
    //  }
    
    }
    
 public function addScopeFile($inspection)
 {
    
    if($inspection->FileAttachScopeTo == null)
        {
           
            $appId = $inspection->reference_refno;

            $certiLab = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;
    
            $certiLabFileAll = CertLabsFileAll::where('app_certi_lab_id',$certiLab->id)
                ->latest() // เรียงจาก created_at จากมากไปน้อย
                ->first();
                
            $filePath = 'files/applicants/check_files/' . $certiLabFileAll->attach_pdf ;
    
            $localFilePath = HP::downloadFileFromTisiCloud($filePath);

    // dd($certiLab,$inspection,$localFilePath);
            $check = AttachFile::where('systems','Center')
                    ->where('ref_id',$inspection->id)
                    ->where('ref_table',(new TrackingInspection)->getTable())
                    ->where('section','file_scope')
                    ->first();

            if($check != null)
            {
                $check->delete();
            }
    
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
    
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $localFilePath,      // Path ของไฟล์
                basename($localFilePath), // ชื่อไฟล์
                mime_content_type($localFilePath), // MIME type
                null,               // ขนาดไฟล์ (null ถ้าไม่ทราบ)
                true                // เป็นไฟล์ที่ valid แล้ว
            );
    
            $attach_path = "files/trackinglabs";
            // ใช้ไฟล์ที่จำลองในการอัปโหลด
            HP::singleFileUploadRefno(
                $uploadedFile,
                $attach_path.'/'.$inspection->reference_refno,
                ( $tax_number),
                (auth()->user()->FullName ?? null),
                'Center',
                (  (new TrackingInspection)->getTable() ),
                $inspection->id,
                'file_scope',
                null
            );
    
        }
 }

    public function data_certi($id) {                   
        $auditor = TrackingAuditors::findOrFail($id);  
        $auditor->name              = !empty($auditor->certificate_export_to->CertiLabTo->name) ?  str_replace("มอก.","",$auditor->certificate_export_to->CertiLabTo->name) :'' ;
        $auditor->name_standard     = !empty($auditor->certificate_export_to->CertiLabTo->name_standard) ?  str_replace("มอก.","",$auditor->certificate_export_to->CertiLabTo->name_standard) :'' ;
        return response()->json([
                                 'auditor'=> $auditor ?? '-' 
                             ]);
    }

    public function storeDetail($data,$notice) {
 
        $data->tracking_assessment_bug_many()->delete();
        $detail = (array)@$notice;
        foreach ($detail['notice'] as $key => $item) {
                $bug = new TrackingAssessmentBug;
                $bug->assessment_id = $data->id;
                $bug->remark        = $item;
                $bug->report        = $detail["report"][$key] ?? null;
                $bug->no            = $detail["no"][$key] ?? null;
                $bug->type          = $detail["type"][$key] ?? null;
                $bug->reporter_id   = $detail["found"][$key] ?? null;
                $bug->owner_id   = auth()->user()->runrecno;
                $bug->save();
        }
    }

        //พบข้อบกพร่อง/ข้อสังเกต  ผู้ประกอบการ +  ผก.
    public function set_mail($data) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        
         if( !empty($data->certificate_export_to->CertiLabTo)){
             $certi =$data->certificate_export_to->CertiLabTo;

             if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){

                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

                $data_app = [
                                'data'           => $certi,
                                'assessment'     => $data ,
                                'export'         => $data->certificate_export_to ?? '' ,
                                'url'            => $url.'certify/tracking-labs',
                                'tis'            =>  '17025 : ข้อ' ,
                                'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : $EMail,
                                'email_cc'      =>  !empty($certi->DataEmailDirectorLABCC) ? $certi->DataEmailDirectorLABCC :  [],
                                'email_reply'   => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                            ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($data->tracking_to->reference_refno)? $data->tracking_to->reference_refno:null,   
                                                            $data->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $data->id ?? null,
                                                            (new TrackingAssessment)->getTable(),
                                                            4,
                                                            'นำส่งรายงานการตรวจประเมิน',
                                                            view('mail.Tracking.save_assessment', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter  : $EMail,
                                                            $certi->email,
                                                            !empty($certi->DataEmailDirectorLABCC) ?  implode(",",$certi->DataEmailDirectorLABCC) : null,
                                                            !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                        );

                $html = new SaveAssessmentMail($data_app);
                $mail =  Mail::to($certi->email)->send($html);  

                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }    
           }
         }

    }
    public function set_check_mail($data) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        if( !empty($data->certificate_export_to->CertiLabTo)){
            $certi =$data->certificate_export_to->CertiLabTo;
            
            if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){

                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

                $data_app =  [
                            'data'           => $certi,
                            'assessment'     => $data ,
                            'export'         => $data->certificate_export_to ?? '' ,
                            'url'            => $url.'certify/tracking-labs',
                            'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : $EMail,
                            'email_cc'       =>  !empty($certi->DataEmailDirectorLABCC) ? $certi->DataEmailDirectorLABCC :  [],
                            'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                            ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($data->tracking_to->reference_refno)? $data->tracking_to->reference_refno:null,   
                                                            $data->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $data->id ?? null,
                                                            (new TrackingAssessment)->getTable(),
                                                            4,
                                                            !is_null($data->FileAttachAssessment5To) ? 'แจ้งผลการประเมิน' : 'แจ้งผลการประเมินแนวทางแก้ไขข้อบกพร่อง',
                                                            view('mail.Tracking.check_save_assessment', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter   : $EMail,
                                                            $certi->email,
                                                            !empty($certi->DataEmailDirectorLABCC) ?  implode(",",$certi->DataEmailDirectorLABCC) : null,
                                                            !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                        );

                $html = new CheckSaveAssessment($data_app);
                $mail =  Mail::to($certi->email)->send($html);  

                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }    
             }
 
          }
        
    }


     //การตรวจประเมิน   ผู้ประกอบการ +  ผก.
     public function set_mail_past($data) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        if( !empty($data->certificate_export_to->CertiLabTo)){
            $certi =$data->certificate_export_to->CertiLabTo;
            if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){

                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

                $data_app = [
                            'data'           => $certi,
                            'assessment'     => $data ,
                            'url'            => $url.'certify/tracking-labs',
                            'export'         => $data->certificate_export_to ?? '' ,
                            'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : $EMail,
                            'email_cc'       =>  !empty($certi->DataEmailDirectorLABCC) ? $certi->DataEmailDirectorLABCC :  [],
                            'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                            ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($data->tracking_to->reference_refno)? $data->tracking_to->reference_refno:null,   
                                                            $data->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $data->id ?? null,
                                                            (new TrackingAssessment)->getTable(),
                                                            4,
                                                             'แจ้งผลการประเมิน',
                                                            view('mail.Tracking.save_assessment_past', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter :$EMail,
                                                            $certi->email,
                                                            !empty($certi->DataEmailDirectorLABCC) ?  implode(",",$certi->DataEmailDirectorLABCC) : null,
                                                            !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                        );

                $html = new SaveAssessmentPastMail($data_app);
                $mail =  Mail::to($certi->email)->send($html);  
    
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }    
             }
   
       }
    }

    public function set_history_bug($data)
    {
     
        $assessment = TrackingAssessment::select('name','auditors_id', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                      ->where('id',$data->id)
                      ->first();
      
 
        $attachs1 = [];
        if( !empty($data->FileAttachAssessment1To->url)){
          $attachs1['url'] =  $data->FileAttachAssessment1To->url;
        }
        if( !empty($data->FileAttachAssessment1To->new_filename)){
            $attachs1['new_filename'] =  $data->FileAttachAssessment1To->new_filename;
        }
        if( !empty($data->FileAttachAssessment1To->filename)){
            $attachs1['filename'] =  $data->FileAttachAssessment1To->filename;
        }

        $attachs2 =[];
        if(count($data->FileAttachAssessment2Many) > 0 ){
            foreach($data->FileAttachAssessment2Many as $item){
                 $object = (object)[];
                 $object->url           = $item->url ?? null;
                 $object->new_filename  = $item->new_filename ?? null;
                 $object->filename      = $item->filename ?? null;
                 $attachs2[]            = $object;
            }
        }

        $attachs3 =[];
        if(count($data->FileAttachAssessment3Many) > 0 ){
            foreach($data->FileAttachAssessment3Many as $item){
                 $object = (object)[];
                 $object->url           = $item->url ?? null;
                 $object->new_filename  = $item->new_filename ?? null;
                 $object->filename      = $item->filename ?? null;
                 $attachs3[]            = $object;
            }
        }

        $attachs4 =[];
        if(count($data->FileAttachAssessment4Many) > 0 ){
            foreach($data->FileAttachAssessment4Many as $item){
                 $object = (object)[];
                 $object->url           = $item->url ?? null;
                 $object->new_filename  = $item->new_filename ?? null;
                 $object->filename      = $item->filename ?? null;
                 $attachs4[]            = $object;
            }
        }

        $attachs5 = [];
        if( !empty($data->FileAttachAssessment5To->url)){
            $attachs5['url'] =  $data->FileAttachAssessment5To->url;
        }
        if( !empty($data->FileAttachAssessment5To->new_filename)){
            $attachs5['new_filename'] =  $data->FileAttachAssessment5To->new_filename;
        }
        if( !empty($data->FileAttachAssessment5To->filename)){
            $attachs5['filename'] =  $data->FileAttachAssessment5To->filename;
        }


            $bugs = TrackingAssessmentBug::select('report','remark','no','type','reporter_id','details','status','comment','file_status','file_comment','id')
                                        ->where('assessment_id',$data->id)
                                        ->get();
            $datas = [];
            if(count($bugs) > 0) {
                foreach($bugs as $key => $item){
                    $object                 = (object)[];
                    $object->report         = $item->report ?? null;
                    $object->remark         = $item->remark ?? null;
                    $object->no             = $item->no ?? null;
                    $object->type           = $item->type ?? null;
                    $object->reporter_id    = $item->reporter_id ?? null;
                    $object->details        = $item->details ?? null;
                    $object->status         = $item->status ?? null;
                    $object->comment        = $item->comment ?? null;
                    $object->file_status    = $item->file_status ?? null;
                    $object->file_comment   = $item->file_comment ?? null;
                    if(!empty($item->FileAttachAssessmentBugTo)){
                        $attachs = [];
                          if( !empty($item->FileAttachAssessmentBugTo->url)){
                            $attachs['url'] =  $item->FileAttachAssessmentBugTo->url;
                          }
                          if( !empty($item->FileAttachAssessmentBugTo->new_filename)){
                              $attachs['new_filename'] =  $item->FileAttachAssessmentBugTo->new_filename;
                          }
                          if( !empty($item->FileAttachAssessmentBugTo->filename)){
                              $attachs['filename'] =  $item->FileAttachAssessmentBugTo->filename;
                          }
                        $object->attachs    = $attachs;
                    }else{
                        $object->attachs    =  null;
                    }
                    $datas[] = $object;
                }
            }


        TrackingHistory::create([
                                     'tracking_id'       =>  $data->tracking_id ?? null,
                                    'certificate_type'  => 3,
                                    'reference_refno'   => $data->reference_refno ?? null,
                                    'ref_table'         =>  (new CertificateExport)->getTable() ,
                                    'ref_id'            =>  $data->ref_id ?? null,
                                    'auditors_id'       =>  $data->auditors_id ?? null,
                                    'system'            => 6,
                                    'table_name'        => (new TrackingAssessment)->getTable() ,
                                    'refid'             => $data->id,
                                    'details_one'       =>  json_encode($assessment) ?? null,  
                                    'details_two'       =>  (count($datas) > 0) ? json_encode($datas) : null,
                                    'details_three'     =>  (count($attachs1) > 0) ? json_encode($attachs1) : null,
                                    'details_four'      =>  (count($attachs2) > 0) ? json_encode($attachs2) : null,
                                    'attachs'           =>  (count($attachs3) > 0) ? json_encode($attachs3) : null,
                                    'file'              =>  (count($attachs4) > 0) ? json_encode($attachs4) : null,
                                    'attachs_car'       =>  (count($attachs5) > 0) ? json_encode($attachs5) : null,
                                    'created_by'        =>  auth()->user()->runrecno
                             ]);
   }
   public function set_history($data)
   {

 
       $assessment = TrackingAssessment::select('name','auditors_id', 'laboratory_name', 'report_date', 'bug_report', 'degree')
                     ->where('id',$data->id)
                     ->first();
                     $attachs1 = [];
                     if( !empty($data->FileAttachAssessment1To->url)){
                       $attachs1['url'] =  $data->FileAttachAssessment1To->url;
                     }
                     if( !empty($data->FileAttachAssessment1To->new_filename)){
                         $attachs1['new_filename'] =  $data->FileAttachAssessment1To->new_filename;
                     }
                     if( !empty($data->FileAttachAssessment1To->filename)){
                         $attachs1['filename'] =  $data->FileAttachAssessment1To->filename;
                     }
             
                     $attachs2 =[];
                     if(count($data->FileAttachAssessment2Many) > 0 ){
                         foreach($data->FileAttachAssessment2Many as $item){
                              $object = (object)[];
                              $object->url           = $item->url ?? null;
                              $object->new_filename  = $item->new_filename ?? null;
                              $object->filename      = $item->filename ?? null;
                              $attachs2[]            = $object;
                         }
                     }
             
                     $attachs3 =[];
                     if(count($data->FileAttachAssessment3Many) > 0 ){
                         foreach($data->FileAttachAssessment3Many as $item){
                              $object = (object)[];
                              $object->url           = $item->url ?? null;
                              $object->new_filename  = $item->new_filename ?? null;
                              $object->filename      = $item->filename ?? null;
                              $attachs3[]            = $object;
                         }
                     }
             
                     $attachs4 =[];
                     if(count($data->FileAttachAssessment4Many) > 0 ){
                         foreach($data->FileAttachAssessment4Many as $item){
                              $object = (object)[];
                              $object->url           = $item->url ?? null;
                              $object->new_filename  = $item->new_filename ?? null;
                              $object->filename      = $item->filename ?? null;
                              $attachs4[]            = $object;
                         }
                     }
             
                     $attachs5 = [];
                     if( !empty($data->FileAttachAssessment5To->url)){
                       $attachs5['url'] =  $data->FileAttachAssessment5To->url;
                     }
                     if( !empty($data->FileAttachAssessment5To->new_filename)){
                         $attachs5['new_filename'] =  $data->FileAttachAssessment5To->new_filename;
                     }
                     if( !empty($data->FileAttachAssessment5To->filename)){
                         $attachs5['filename'] =  $data->FileAttachAssessment5To->filename;
                     }


               TrackingHistory::create([
                                    'tracking_id'       =>  $data->tracking_id ?? null,
                                    'certificate_type'  => 3,
                                    'reference_refno'   => $data->reference_refno ?? null,
                                    'ref_table'         =>  (new CertificateExport)->getTable() ,
                                    'ref_id'            =>  $data->ref_id ?? null,
                                    'auditors_id'       =>  $data->auditors_id ?? null,
                                    'system'            => 7,
                                    'table_name'        => (new TrackingAssessment)->getTable() ,
                                    'refid'             => $data->id,
                                   'details_one'        =>  json_encode($assessment) ?? null,
                                   'details_two'        =>   null,
                                   'details_three'     =>  (count($attachs1) > 0) ? json_encode($attachs1) : null,
                                   'details_four'      =>  (count($attachs2) > 0) ? json_encode($attachs2) : null,
                                   'attachs'           =>  (count($attachs3) > 0) ? json_encode($attachs3) : null,
                                   'file'              =>  (count($attachs4) > 0) ? json_encode($attachs4) : null,
                                   'attachs_car'       =>  (count($attachs5) > 0) ? json_encode($attachs5) : null,
                                   'created_by'         =>  auth()->user()->runrecno
                            ]);
   }
 
   
   public function emailToExpert(Request $request)
   {

       $assessment = TrackingAssessment::find($request->notice_id);
       $expertEmails = $request->selectedEmails;
       $certi = $assessment->certificate_export_to->CertiLabTo;
       $auditors = TrackingAuditors::find( $assessment->tracking_id);

       $config = HP::getConfig();
       $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
       $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
       $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
       $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

       $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
       $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

       $data_app = [
                   'certi'           => $certi,
                   'auditor'     => $auditors ,
                   'url'            => $url_center.'/create-by-expert-lab-sur/' . $assessment->id .'?token='.$assessment->expert_token,
                   'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : $EMail,
                   'email_cc'       =>  !empty($certi->DataEmailDirectorLABCC) ? $certi->DataEmailDirectorLABCC :  [],
                   'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                   ];

       $log_email =  HP::getInsertCertifyLogEmail(!empty($assessment->tracking_to->reference_refno)? $assessment->tracking_to->reference_refno:null,   
                                                   $assessment->tracking_id,
                                                   (new Tracking)->getTable(),
                                                   $data->id ?? null,
                                                   (new TrackingAssessment)->getTable(),
                                                   4,
                                                    'เพิ่มรายการข้อบกพร่อง / ข้อสังเกต',
                                                   view('mail.Tracking.mail_lab_expert', $data_app),
                                                   !empty($certi->created_by)? $certi->created_by:null,   
                                                   !empty($certi->agent_id)? $certi->agent_id:null, 
                                                   auth()->user()->getKey(),
                                                   !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter :$EMail,
                                                   $certi->email,
                                                   !empty($certi->DataEmailDirectorLABCC) ?  implode(",",$certi->DataEmailDirectorLABCC) : null,
                                                   !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                               );
   
       $html = new MailToLabSurExpert($data_app);
       $mail = Mail::to($expertEmails)->send($html);
       if(is_null($mail) && !empty($log_email)){
           HP::getUpdateCertifyLogEmail($log_email->id);
       }
   }


//    viewLabReportOneInfo

   public function viewLabReportOne($assessment_id)
   {
    // dd('ok');
       $assessment = TrackingAssessment::find($assessment_id);
       $labReportOne = TrackingLabReportOne::with('attachments')->where('tracking_assessment_id',$assessment_id)->first();
       
       $certi_lab = $assessment->certificate_export_to->CertiLabTo;
       $trackingAuditor = TrackingAuditors::find( $assessment->auditors_id);
       $tracking = $assessment->tracking_to;
       
        $auditors_statuses= $trackingAuditor->auditors_status_many;
       
      $statusAuditorMap = [];
      foreach ($auditors_statuses as $auditors_status)
      {
          $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
          $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection

          // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
          if (!isset($statusAuditorMap[$statusAuditorId])) {
              $statusAuditorMap[$statusAuditorId] = [];
          }
          // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
          foreach ($auditors as $auditor) {
              
              $statusAuditorMap[$statusAuditorId][] = $auditor->id;
          }
      }

        $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$trackingAuditor->id)->first();
        $dateRange = "";

        if (!empty($trackingAuditorsDate->start_date) && !empty($trackingAuditorsDate->end_date)) {
            if ($trackingAuditorsDate->start_date == $trackingAuditorsDate->end_date) {
                // ถ้าเป็นวันเดียวกัน
                $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date);
            } else {
                // ถ้าเป็นคนละวัน
                $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date) . 
                            " ถึงวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->end_date);
            }
        }


        $scope_branch = "";
        if ($certi_lab->lab_type == 3){
            $scope_branch =$certi_lab->BranchTitle;
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
        $data->tracking = $tracking;
        // $data->app_no = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        // $data->experts = $experts;

        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;


        $labRequest = null;

            
        if($certi_lab->lab_type == 4){
            $labRequest = LabCalRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        }else if($certi_lab->lab_type == 3)
        {
            $labRequest = LabTestRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        }
        
        $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportOne->id)
                                        ->where('certificate_type',2)
                                        ->where('report_type',1)
                                        ->get();
        $labInformation = $certi_lab->information;
        // dd('a');
        return view('certificate.labs.assessment-labs.report-one.view-report-one', [
            'data' => $data,
            'assessment' => $assessment,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'tracking' => $tracking,
            'certi_lab' => $certi_lab,
            'labRequest' => $labRequest,
            'labReportOne' => $labReportOne,
            'labInformation' => $labInformation[0]
        ]);

       
   }

   public function viewLabInfo($assessment_id)
   {
 
    // http://127.0.0.1:8081/certify/save_assessment/create-lab-info/1375
       // สำหรับ admin และเจ้าหน้าที่ lab
     //   if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
     //       abort(403);
     //   }

       $assessment = TrackingAssessment::find($assessment_id);
       $labReportInfo = TrackingLabReportInfo::where('tracking_assessment_id',$assessment_id)->first();
       
       $certi_lab = $assessment->certificate_export_to->CertiLabTo;
       $trackingAuditor = TrackingAuditors::find( $assessment->auditors_id);
       $tracking = $assessment->tracking_to;
       
        $auditors_statuses= $trackingAuditor->auditors_status_many;
       
        $statusAuditorMap = [];
        foreach ($auditors_statuses as $auditors_status)
        {
            // dd($auditors_status->auditors_list_many);
            $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
            $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection

            // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
            if (!isset($statusAuditorMap[$statusAuditorId])) {
                $statusAuditorMap[$statusAuditorId] = [];
            }
            // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
            foreach ($auditors as $auditor) {
                
                $statusAuditorMap[$statusAuditorId][] = $auditor->id;
            }
        }

        $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$trackingAuditor->id)->first();
        $dateRange = "";

        if (!empty($trackingAuditorsDate->start_date) && !empty($trackingAuditorsDate->end_date)) {
            if ($trackingAuditorsDate->start_date == $trackingAuditorsDate->end_date) {
                // ถ้าเป็นวันเดียวกัน
                $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date);
            } else {
                // ถ้าเป็นคนละวัน
                $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date) . 
                            " ถึงวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->end_date);
            }
        }


        $boardAuditorExpertTracking = BoardAuditoExpertTracking::where('tracking_auditor_id',$assessment->tracking_id)->first();

        $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
        // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
        if ($boardAuditorExpertTracking && $boardAuditorExpertTracking->expert) {
            // แปลงข้อมูล JSON ใน expert กลับเป็น array
            $categories = json_decode($boardAuditorExpertTracking->expert, true);
        
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

        // dd($assessment,$certi_lab,$trackingAuditor,$auditors_statuses,$statusAuditorMap, $trackingAuditorsDate,$dateRange,$experts,$tracking );

        $scope_branch = "";
        if ($certi_lab->lab_type == 3){
            $scope_branch =$certi_lab->BranchTitle;
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
        $data->tracking = $tracking;
        // $data->app_no = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        $data->experts = $experts;

        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;


        $labRequest = null;

            
        if($certi_lab->lab_type == 4){
            $labRequest = LabCalRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        }else if($certi_lab->lab_type == 3)
        {
            $labRequest = LabTestRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        }
        
        $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportInfo->id)
                                        ->where('certificate_type',2)
                                        ->where('report_type',1)
                                        ->get();
        $labInformation = $certi_lab->information;
        return view('certificate.labs.assessment-labs.view-report', [
            'data' => $data,
            'assessment' => $assessment,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'tracking' => $tracking,
            'certi_lab' => $certi_lab,
            'labRequest' => $labRequest,
            'labReportInfo' => $labReportInfo,
            'labInformation' => $labInformation[0]
        ]);

       
   }




    public function updateLabReportOne(Request $request)
    {
        //    dd('ok');
        // ดึง payload และแปลง JSON เป็น array
        $payload = json_decode($request->input('payload'), true);

        // ดึงข้อมูลจาก payload
        $id = $payload['id'] ?? null;
        $data = $payload['data'] ?? [];
        $persons = $payload['persons'] ?? [];
        $assessment = (object) ($payload['assessment'] ?? []);
        $signers = $payload['signer'] ?? [];
        $submit_type = $payload['submit_type'] ?? null;
       
         $id = $assessment->id;

        //  dd($signer);

        // เตรียมข้อมูลสำหรับบันทึก
        $recordData = [
            'tracking_assessment_id' => $id, // ใช้ id จาก payload
            'book_no_text' => isset($data['book_no_text']) ? $data['book_no_text'] : null,
            'audit_observation_text' => isset($data['audit_observation_text']) ? $data['audit_observation_text'] : null,
            'chk_impartiality_yes' => isset($data['chk_impartiality_yes']) && $data['chk_impartiality_yes'] ? 'true' : null,
            'chk_impartiality_no' => isset($data['chk_impartiality_no']) && $data['chk_impartiality_no'] ? 'true' : null,
            'impartiality_text' => isset($data['impartiality_text']) ? $data['impartiality_text'] : null,
            'chk_confidentiality_yes' => isset($data['chk_confidentiality_yes']) && $data['chk_confidentiality_yes'] ? 'true' : null,
            'chk_confidentiality_no' => isset($data['chk_confidentiality_no']) && $data['chk_confidentiality_no'] ? 'true' : null,
            'confidentiality_text' => isset($data['confidentiality_text']) ? $data['confidentiality_text'] : null,
            'chk_structure_yes' => isset($data['chk_structure_yes']) && $data['chk_structure_yes'] ? 'true' : null,
            'chk_structure_no' => isset($data['chk_structure_no']) && $data['chk_structure_no'] ? 'true' : null,
            'structure_text' => isset($data['structure_text']) ? $data['structure_text'] : null,
            'chk_res_general_yes' => isset($data['chk_res_general_yes']) && $data['chk_res_general_yes'] ? 'true' : null,
            'chk_res_general_no' => isset($data['chk_res_general_no']) && $data['chk_res_general_no'] ? 'true' : null,
            'res_general_text' => isset($data['res_general_text']) ? $data['res_general_text'] : null,
            'chk_res_personnel_yes' => isset($data['chk_res_personnel_yes']) && $data['chk_res_personnel_yes'] ? 'true' : null,
            'chk_res_personnel_no' => isset($data['chk_res_personnel_no']) && $data['chk_res_personnel_no'] ? 'true' : null,
            'res_personnel_text' => isset($data['res_personnel_text']) ? $data['res_personnel_text'] : null,
            'chk_res_facility_yes' => isset($data['chk_res_facility_yes']) && $data['chk_res_facility_yes'] ? 'true' : null,
            'chk_res_facility_no' => isset($data['chk_res_facility_no']) && $data['chk_res_facility_no'] ? 'true' : null,
            'res_facility_text' => isset($data['res_facility_text']) ? $data['res_facility_text'] : null,
            'chk_res_equipment_yes' => isset($data['chk_res_equipment_yes']) && $data['chk_res_equipment_yes'] ? 'true' : null,
            'chk_res_equipment_no' => isset($data['chk_res_equipment_no']) && $data['chk_res_equipment_no'] ? 'true' : null,
            'res_equipment_text' => isset($data['res_equipment_text']) ? $data['res_equipment_text'] : null,
            'chk_res_traceability_yes' => isset($data['chk_res_traceability_yes']) && $data['chk_res_traceability_yes'] ? 'true' : null,
            'chk_res_traceability_no' => isset($data['chk_res_traceability_no']) && $data['chk_res_traceability_no'] ? 'true' : null,
            'res_traceability_text' => isset($data['res_traceability_text']) ? $data['res_traceability_text'] : null,
            'chk_res_external_yes' => isset($data['chk_res_external_yes']) && $data['chk_res_external_yes'] ? 'true' : null,
            'chk_res_external_no' => isset($data['chk_res_external_no']) && $data['chk_res_external_no'] ? 'true' : null,
            'res_external_text' => isset($data['res_external_text']) ? $data['res_external_text'] : null,
            'chk_proc_review_yes' => isset($data['chk_proc_review_yes']) && $data['chk_proc_review_yes'] ? 'true' : null,
            'chk_proc_review_no' => isset($data['chk_proc_review_no']) && $data['chk_proc_review_no'] ? 'true' : null,
            'proc_review_text' => isset($data['proc_review_text']) ? $data['proc_review_text'] : null,
            'chk_proc_method_yes' => isset($data['chk_proc_method_yes']) && $data['chk_proc_method_yes'] ? 'true' : null,
            'chk_proc_method_no' => isset($data['chk_proc_method_no']) && $data['chk_proc_method_no'] ? 'true' : null,
            'proc_method_text' => isset($data['proc_method_text']) ? $data['proc_method_text'] : null,
            'chk_proc_sampling_yes' => isset($data['chk_proc_sampling_yes']) && $data['chk_proc_sampling_yes'] ? 'true' : null,
            'chk_proc_sampling_no' => isset($data['chk_proc_sampling_no']) && $data['chk_proc_sampling_no'] ? 'true' : null,
            'proc_sampling_text' => isset($data['proc_sampling_text']) ? $data['proc_sampling_text'] : null,
            'chk_proc_sample_handling_yes' => isset($data['chk_proc_sample_handling_yes']) && $data['chk_proc_sample_handling_yes'] ? 'true' : null,
            'chk_proc_sample_handling_no' => isset($data['chk_proc_sample_handling_no']) && $data['chk_proc_sample_handling_no'] ? 'true' : null,
            'proc_sample_handling_text' => isset($data['proc_sample_handling_text']) ? $data['proc_sample_handling_text'] : null,
            'chk_proc_tech_record_yes' => isset($data['chk_proc_tech_record_yes']) && $data['chk_proc_tech_record_yes'] ? 'true' : null,
            'chk_proc_tech_record_no' => isset($data['chk_proc_tech_record_no']) && $data['chk_proc_tech_record_no'] ? 'true' : null,
            'proc_tech_record_text' => isset($data['proc_tech_record_text']) ? $data['proc_tech_record_text'] : null,
            'chk_proc_uncertainty_yes' => isset($data['chk_proc_uncertainty_yes']) && $data['chk_proc_uncertainty_yes'] ? 'true' : null,
            'chk_proc_uncertainty_no' => isset($data['chk_proc_uncertainty_no']) && $data['chk_proc_uncertainty_no'] ? 'true' : null,
            'proc_uncertainty_text' => isset($data['proc_uncertainty_text']) ? $data['proc_uncertainty_text'] : null,
            'chk_proc_validity_yes' => isset($data['chk_proc_validity_yes']) && $data['chk_proc_validity_yes'] ? 'true' : null,
            'chk_proc_validity_no' => isset($data['chk_proc_validity_no']) && $data['chk_proc_validity_no'] ? 'true' : null,
            'proc_validity_text' => isset($data['proc_validity_text']) ? $data['proc_validity_text'] : null,
            'chk_proc_reporting_yes' => isset($data['chk_proc_reporting_yes']) && $data['chk_proc_reporting_yes'] ? 'true' : null,
            'chk_proc_reporting_no' => isset($data['chk_proc_reporting_no']) && $data['chk_proc_reporting_no'] ? 'true' : null,
            'proc_reporting_text' => isset($data['proc_reporting_text']) ? $data['proc_reporting_text'] : null,
            'chk_proc_complaint_yes' => isset($data['chk_proc_complaint_yes']) && $data['chk_proc_complaint_yes'] ? 'true' : null,
            'chk_proc_complaint_no' => isset($data['chk_proc_complaint_no']) && $data['chk_proc_complaint_no'] ? 'true' : null,
            'proc_complaint_text' => isset($data['proc_complaint_text']) ? $data['proc_complaint_text'] : null,
            'chk_proc_nonconformity_yes' => isset($data['chk_proc_nonconformity_yes']) && $data['chk_proc_nonconformity_yes'] ? 'true' : null,
            'chk_proc_nonconformity_no' => isset($data['chk_proc_nonconformity_no']) && $data['chk_proc_nonconformity_no'] ? 'true' : null,
            'proc_nonconformity_text' => isset($data['proc_nonconformity_text']) ? $data['proc_nonconformity_text'] : null,
            'chk_proc_data_control_yes' => isset($data['chk_proc_data_control_yes']) && $data['chk_proc_data_control_yes'] ? 'true' : null,
            'chk_proc_data_control_no' => isset($data['chk_proc_data_control_no']) && $data['chk_proc_data_control_no'] ? 'true' : null,
            'proc_data_control_text' => isset($data['proc_data_control_text']) ? $data['proc_data_control_text'] : null,
            'chk_res_selection_yes' => isset($data['chk_res_selection_yes']) && $data['chk_res_selection_yes'] ? 'true' : null,
            'chk_res_selection_no' => isset($data['chk_res_selection_no']) && $data['chk_res_selection_no'] ? 'true' : null,
            'res_selection_text' => isset($data['res_selection_text']) ? $data['res_selection_text'] : null,
            'chk_res_docsystem_yes' => isset($data['chk_res_docsystem_yes']) && $data['chk_res_docsystem_yes'] ? 'true' : null,
            'chk_res_docsystem_no' => isset($data['chk_res_docsystem_no']) && $data['chk_res_docsystem_no'] ? 'true' : null,
            'res_docsystem_text' => isset($data['res_docsystem_text']) ? $data['res_docsystem_text'] : null,
            'chk_res_doccontrol_yes' => isset($data['chk_res_doccontrol_yes']) && $data['chk_res_doccontrol_yes'] ? 'true' : null,
            'chk_res_doccontrol_no' => isset($data['chk_res_doccontrol_no']) && $data['chk_res_doccontrol_no'] ? 'true' : null,
            'res_doccontrol_text' => isset($data['res_doccontrol_text']) ? $data['res_doccontrol_text'] : null,
            'chk_res_recordcontrol_yes' => isset($data['chk_res_recordcontrol_yes']) && $data['chk_res_recordcontrol_yes'] ? 'true' : null,
            'chk_res_recordcontrol_no' => isset($data['chk_res_recordcontrol_no']) && $data['chk_res_recordcontrol_no'] ? 'true' : null,
            'res_recordcontrol_text' => isset($data['res_recordcontrol_text']) ? $data['res_recordcontrol_text'] : null,
            'chk_res_riskopportunity_yes' => isset($data['chk_res_riskopportunity_yes']) && $data['chk_res_riskopportunity_yes'] ? 'true' : null,
            'chk_res_riskopportunity_no' => isset($data['chk_res_riskopportunity_no']) && $data['chk_res_riskopportunity_no'] ? 'true' : null,
            'res_riskopportunity_text' => isset($data['res_riskopportunity_text']) ? $data['res_riskopportunity_text'] : null,
            'chk_res_improvement_yes' => isset($data['chk_res_improvement_yes']) && $data['chk_res_improvement_yes'] ? 'true' : null,
            'chk_res_improvement_no' => isset($data['chk_res_improvement_no']) && $data['chk_res_improvement_no'] ? 'true' : null,
            'res_improvement_text' => isset($data['res_improvement_text']) ? $data['res_improvement_text'] : null,
            'chk_res_corrective_yes' => isset($data['chk_res_corrective_yes']) && $data['chk_res_corrective_yes'] ? 'true' : null,
            'chk_res_corrective_no' => isset($data['chk_res_corrective_no']) && $data['chk_res_corrective_no'] ? 'true' : null,
            'res_corrective_text' => isset($data['res_corrective_text']) ? $data['res_corrective_text'] : null,
            'chk_res_audit_yes' => isset($data['chk_res_audit_yes']) && $data['chk_res_audit_yes'] ? 'true' : null,
            'chk_res_audit_no' => isset($data['chk_res_audit_no']) && $data['chk_res_audit_no'] ? 'true' : null,
            'res_audit_text' => isset($data['res_audit_text']) ? $data['res_audit_text'] : null,
            'chk_res_review_yes' => isset($data['chk_res_review_yes']) && $data['chk_res_review_yes'] ? 'true' : null,
            'chk_res_review_no' => isset($data['chk_res_review_no']) && $data['chk_res_review_no'] ? 'true' : null,
            'res_review_text' => isset($data['res_review_text']) ? $data['res_review_text'] : null,
            'report_display_certification_none' => isset($data['report_display_certification_none']) && $data['report_display_certification_none'] ? 'true' : null,
            'report_display_certification_yes' => isset($data['report_display_certification_yes']) && $data['report_display_certification_yes'] ? 'true' : null,
            'report_scope_certified_only' => isset($data['report_scope_certified_only']) && $data['report_scope_certified_only'] ? 'true' : null,
            'report_scope_certified_all' => isset($data['report_scope_certified_all']) && $data['report_scope_certified_all'] ? 'true' : null,
            'report_activities_not_certified_yes' => isset($data['report_activities_not_certified_yes']) && $data['report_activities_not_certified_yes'] ? 'true' : null,
            'report_activities_not_certified_no' => isset($data['report_activities_not_certified_no']) && $data['report_activities_not_certified_no'] ? 'true' : null,
            'report_accuracy_correct' => isset($data['report_accuracy_correct']) && $data['report_accuracy_correct'] ? 'true' : null,
            'report_accuracy_incorrect' => isset($data['report_accuracy_incorrect']) && $data['report_accuracy_incorrect'] ? 'true' : null,
            'report_accuracy_detail' => isset($data['report_accuracy_detail']) ? $data['report_accuracy_detail'] : null,
            'multisite_display_certification_none' => isset($data['multisite_display_certification_none']) && $data['multisite_display_certification_none'] ? 'true' : null,
            'multisite_display_certification_yes' => isset($data['multisite_display_certification_yes']) && $data['multisite_display_certification_yes'] ? 'true' : null,
            'multisite_scope_certified_only' => isset($data['multisite_scope_certified_only']) && $data['multisite_scope_certified_only'] ? 'true' : null,
            'multisite_scope_certified_all' => isset($data['multisite_scope_certified_all']) && $data['multisite_scope_certified_all'] ? 'true' : null,
            'multisite_activities_not_certified_yes' => isset($data['multisite_activities_not_certified_yes']) && $data['multisite_activities_not_certified_yes'] ? 'true' : null,
            'multisite_activities_not_certified_no' => isset($data['multisite_activities_not_certified_no']) && $data['multisite_activities_not_certified_no'] ? 'true' : null,
            'multisite_accuracy_correct' => isset($data['multisite_accuracy_correct']) && $data['multisite_accuracy_correct'] ? 'true' : null,
            'multisite_accuracy_incorrect' => isset($data['multisite_accuracy_incorrect']) && $data['multisite_accuracy_incorrect'] ? 'true' : null,
            'multisite_accuracy_detail' => isset($data['multisite_accuracy_detail']) ? $data['multisite_accuracy_detail'] : null,
            'certification_status_correct' => isset($data['certification_status_correct']) && $data['certification_status_correct'] ? 'true' : null,
            'certification_status_incorrect' => isset($data['certification_status_incorrect']) && $data['certification_status_incorrect'] ? 'true' : null,
            'certification_status_details' => isset($data['certification_status_details']) ? $data['certification_status_details'] : null,
            'other_certification_status_correct' => isset($data['other_certification_status_correct']) && $data['other_certification_status_correct'] ? 'true' : null,
            'other_certification_status_incorrect' => isset($data['other_certification_status_incorrect']) && $data['other_certification_status_incorrect'] ? 'true' : null,
            'other_certification_status_details' => isset($data['other_certification_status_details']) ? $data['other_certification_status_details'] : null,
            'lab_availability_yes' => isset($data['lab_availability_yes']) && $data['lab_availability_yes'] ? 'true' : null,
            'lab_availability_no' => isset($data['lab_availability_no']) && $data['lab_availability_no'] ? 'true' : null,
            'ilac_mra_display_no' => isset($data['ilac_mra_display_no']) && $data['ilac_mra_display_no'] ? 'true' : null,
            'ilac_mra_display_yes' => isset($data['ilac_mra_display_yes']) && $data['ilac_mra_display_yes'] ? 'true' : null,
            'ilac_mra_scope_no' => isset($data['ilac_mra_scope_no']) && $data['ilac_mra_scope_no'] ? 'true' : null,
            'ilac_mra_scope_yes' => isset($data['ilac_mra_scope_yes']) && $data['ilac_mra_scope_yes'] ? 'true' : null,
            'ilac_mra_disclosure_yes' => isset($data['ilac_mra_disclosure_yes']) && $data['ilac_mra_disclosure_yes'] ? 'true' : null,
            'ilac_mra_disclosure_no' => isset($data['ilac_mra_disclosure_no']) && $data['ilac_mra_disclosure_no'] ? 'true' : null,
            'ilac_mra_compliance_correct' => isset($data['ilac_mra_compliance_correct']) && $data['ilac_mra_compliance_correct'] ? 'true' : null,
            'ilac_mra_compliance_incorrect' => isset($data['ilac_mra_compliance_incorrect']) && $data['ilac_mra_compliance_incorrect'] ? 'true' : null,
            'ilac_mra_compliance_details' => isset($data['ilac_mra_compliance_details']) ? $data['ilac_mra_compliance_details'] : null,
            'other_ilac_mra_compliance_no' => isset($data['other_ilac_mra_compliance_no']) && $data['other_ilac_mra_compliance_no'] ? 'true' : null,
            'other_ilac_mra_compliance_yes' => isset($data['other_ilac_mra_compliance_yes']) && $data['other_ilac_mra_compliance_yes'] ? 'true' : null,
            'other_ilac_mra_compliance_details' => isset($data['other_ilac_mra_compliance_details']) ? $data['other_ilac_mra_compliance_details'] : null,
            'mra_compliance_correct' => isset($data['mra_compliance_correct']) && $data['mra_compliance_correct'] ? 'true' : null,
            'mra_compliance_incorrect' => isset($data['mra_compliance_incorrect']) && $data['mra_compliance_incorrect'] ? 'true' : null,
            'mra_compliance_details' => isset($data['mra_compliance_details']) ? $data['mra_compliance_details'] : null,
            'evidence_mra_compliance_details_1' => isset($data['evidence_mra_compliance_details_1']) ? $data['evidence_mra_compliance_details_1'] : null,
            'evidence_mra_compliance_details_2' => isset($data['evidence_mra_compliance_details_2']) ? $data['evidence_mra_compliance_details_2'] : null,
            'evidence_mra_compliance_details_3' => isset($data['evidence_mra_compliance_details_3']) ? $data['evidence_mra_compliance_details_3'] : null,
            'evidence_mra_compliance_details_4' => isset($data['evidence_mra_compliance_details_4']) ? $data['evidence_mra_compliance_details_4'] : null,
            'offer_agreement_yes' => isset($data['offer_agreement_yes']) && $data['offer_agreement_yes'] ? 'true' : null,
            'offer_agreement_no' => isset($data['offer_agreement_no']) && $data['offer_agreement_no'] ? 'true' : null,
            'offer_ilac_agreement_yes' => isset($data['offer_ilac_agreement_yes']) && $data['offer_ilac_agreement_yes'] ? 'true' : null,
            'offer_ilac_agreement_no' => isset($data['offer_ilac_agreement_no']) && $data['offer_ilac_agreement_no'] ? 'true' : null,
            'status' => $submit_type
        ];

        // บันทึก persons เป็น JSON
        $recordData['persons'] = !empty($persons) ? json_encode($persons) : null;

        // จัดการไฟล์ attachments
        $attachments = $request->file('references');
        $attachedFiles = [];
        if ($attachments) {
            foreach ($attachments as $index => $file) {
                if ($file->isValid()) {
                    $path = $file->store('references', 'public');
                    $attachedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
            }
        } 



    $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');


// จัดการไฟล์ attachments ด้วย HP::singleFileUploadRefno

        $recordData['attached_files'] = !empty($attachedFiles) ? json_encode($attachedFiles) : null;

        // บันทึกข้อมูลลง TrackingLabReportOne
        try {
            $trackingLabReportOne = TrackingLabReportOne::updateOrCreate(
                ['tracking_assessment_id' => $id],
                $recordData
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }


        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');

        // if ($submit_type == "2")
        // {
            //LAB, certificate_type= 2
        SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $trackingLabReportOne->id)
                                            ->where('certificate_type',2)
                                            ->where('report_type',1)
                                            ->delete();
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentTrackingReportTransaction::create([
                'tracking_report_info_id' => $trackingLabReportOne->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certificate/assessment-labs/view-lab-info/'. $assessment->id ,
                'certificate_type' => 2,
                'app_id' => $assessment->reference_refno,
            ]);
        }
        // }


        $attachments = $request->file('references');
        // $attachedFiles = [];
        if ($attachments && $request->hasFile('references')) {
            foreach ($attachments as $index => $file) {
                if ($file->isValid()) {
                    // เรียกใช้ HP::singleFileUploadRefno
                    HP::singleFileUploadRefno(
                        $file,
                        $this->attach_path.'/'.$assessment->reference_refno,
                        $tax_number, // tax_number
                        auth()->user()->FullName ?? null, // ชื่อผู้ใช้
                        'Center', // คงที่
                        (new TrackingLabReportOne)->getTable(), // ชื่อตาราง
                        $trackingLabReportOne->id, // tracking_assessment_id
                        '11111', // คงที่
                        null // ตัวเลือกเพิ่มเติม
                    );
                } 
            }
        } 

        // ส่ง response
        return response()->json([
            'message' => 'ข้อมูลถูกบันทึกเรียบร้อย',
            'tracking_assessment_id' => $id,
            'submit_type' => $submit_type
        ]);
    }


    public function viewLabReportTwo($assessment_id)
   {
    // dd('ok');
       $assessment = TrackingAssessment::find($assessment_id);
       $labReportTwo = TrackingLabReportTwo::with('attachments')->where('tracking_assessment_id',$assessment_id)->first();
       
       $certi_lab = $assessment->certificate_export_to->CertiLabTo;
       $trackingAuditor = TrackingAuditors::find( $assessment->auditors_id);
       $tracking = $assessment->tracking_to;
       
        $auditors_statuses= $trackingAuditor->auditors_status_many;

        
       
      $statusAuditorMap = [];
      foreach ($auditors_statuses as $auditors_status)
      {
          $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
          $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection

          // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
          if (!isset($statusAuditorMap[$statusAuditorId])) {
              $statusAuditorMap[$statusAuditorId] = [];
          }
          // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
          foreach ($auditors as $auditor) {
              
              $statusAuditorMap[$statusAuditorId][] = $auditor->id;
          }
      }

        $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$trackingAuditor->id)->first();
        $dateRange = "";

        if (!empty($trackingAuditorsDate->start_date) && !empty($trackingAuditorsDate->end_date)) {
            if ($trackingAuditorsDate->start_date == $trackingAuditorsDate->end_date) {
                // ถ้าเป็นวันเดียวกัน
                $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date);
            } else {
                // ถ้าเป็นคนละวัน
                $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date) . 
                            " ถึงวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->end_date);
            }
        }


        $scope_branch = "";
        if ($certi_lab->lab_type == 3){
            $scope_branch =$certi_lab->BranchTitle;
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
        $data->tracking = $tracking;
        // $data->app_no = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        // $data->experts = $experts;

        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;


        $labRequest = null;

            
        // if($certi_lab->lab_type == 4){
        //     $labRequest = LabCalRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        // }else if($certi_lab->lab_type == 3)
        // {
        //     $labRequest = LabTestRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        // }
        
        $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportTwo->id)
                                        ->where('certificate_type',2)
                                        ->where('report_type',2)
                                        ->get();
        $labInformation = $certi_lab->information;
        // dd('a');
        // dd($statusAuditorMap);
        return view('certificate.labs.assessment-labs.report-two.view-report-two', [
            'data' => $data,
            'assessment' => $assessment,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'tracking' => $tracking,
            'certi_lab' => $certi_lab,
            // 'labRequest' => $labRequest,
            'labReportTwo' => $labReportTwo,
            'labInformation' => $labInformation[0],
            'audit_date' => HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date)
        ]);

       
   }

   
    public function updateLabReportTwo(Request $request)
    {
        // ดึง payload และแปลง JSON เป็น array
        $payload = json_decode($request->input('payload'), true);

        // ดึงข้อมูลจาก payload
        $id = $payload['id'] ?? null;
        $data = $payload['data'] ?? [];
        $persons = $payload['persons'] ?? [];
        $assessment = (object) ($payload['assessment'] ?? []);
        $signers = $payload['signer'] ?? [];
        $submit_type = $payload['submit_type'] ?? null;
       
        $id = $assessment->id;

        $recordData = [
            'tracking_assessment_id' => $id,
            'observation_count_text' => isset($data['observation_count_text']) ? $data['observation_count_text'] : null,
            'lab_letter_received_date_text' => isset($data['lab_letter_received_date_text']) ? $data['lab_letter_received_date_text'] : null,
            'email_sent_date_tertiary_text' => isset($data['email_sent_date_tertiary_text']) ? $data['email_sent_date_tertiary_text'] : null,
            'email_sent_date_secondary_text' => isset($data['email_sent_date_secondary_text']) ? $data['email_sent_date_secondary_text'] : null,
            'checkbox_corrective_action_completed' => isset($data['checkbox_corrective_action_completed']) && $data['checkbox_corrective_action_completed'] ? 'true' : null,
            'checkbox_corrective_action_incomplete' => isset($data['checkbox_corrective_action_incomplete']) && $data['checkbox_corrective_action_incomplete'] ? 'true' : null,
            'remaining_nonconformities_count_text' => isset($data['remaining_nonconformities_count_text']) ? $data['remaining_nonconformities_count_text'] : null,
            'remaining_nonconformities_list_text' => isset($data['remaining_nonconformities_list_text']) ? $data['remaining_nonconformities_list_text'] : null,
            'checkbox_extend_certification' => isset($data['checkbox_extend_certification']) && $data['checkbox_extend_certification'] ? 'true' : null,
            'checkbox_reject_extend_certification' => isset($data['checkbox_reject_extend_certification']) && $data['checkbox_reject_extend_certification'] ? 'true' : null,
            'reason_for_extension_decision_text' => isset($data['reason_for_extension_decision_text']) ? $data['reason_for_extension_decision_text'] : null,
            'checkbox_submit_remaining_evidence' => isset($data['checkbox_submit_remaining_evidence']) && $data['checkbox_submit_remaining_evidence'] ? 'true' : null,
            'remaining_evidence_items_text' => isset($data['remaining_evidence_items_text']) ? $data['remaining_evidence_items_text'] : null,
            'remaining_evidence_due_date_text' => isset($data['remaining_evidence_due_date_text']) ? $data['remaining_evidence_due_date_text'] : null,
            'checkbox_unresolved_nonconformities' => isset($data['checkbox_unresolved_nonconformities']) && $data['checkbox_unresolved_nonconformities'] ? 'true' : null,
            'checkbox_reduce_scope' => isset($data['checkbox_reduce_scope']) && $data['checkbox_reduce_scope'] ? 'true' : null,
            'checkbox_suspend_certificate' => isset($data['checkbox_suspend_certificate']) && $data['checkbox_suspend_certificate'] ? 'true' : null,
            'status' => $submit_type
        ];

        // dd($recordData);
       
        // บันทึก persons เป็น JSON
        $recordData['persons'] = !empty($persons) ? json_encode($persons) : null;

        // จัดการไฟล์ attachments
        $attachments = $request->file('references');
        $attachedFiles = [];
        if ($attachments) {
            foreach ($attachments as $index => $file) {
                if ($file->isValid()) {
                    $path = $file->store('references', 'public');
                    $attachedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType()
                    ];
                }
            }
        } 


    $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');


        $recordData['attached_files'] = !empty($attachedFiles) ? json_encode($attachedFiles) : null;

        // บันทึกข้อมูลลง TrackingLabReportOne
        try {
            // dd("here");
            $trackingLabReportTwo = TrackingLabReportTwo::updateOrCreate(
                ['tracking_assessment_id' => $id],
                $recordData
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }


        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');

        SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $trackingLabReportTwo->id)
                                            ->where('certificate_type',2)
                                            ->where('report_type',2)
                                            ->delete();
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentTrackingReportTransaction::create([
                'tracking_report_info_id' => $trackingLabReportTwo->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certificate/assessment-labs/view-lab-info/'. $assessment->id ,
                'certificate_type' => 2,
                'report_type' => 2,
                'app_id' => $assessment->reference_refno,
            ]);
        }
        // }


        $attachments = $request->file('references');
        // $attachedFiles = [];
        if ($attachments && $request->hasFile('references')) {
            foreach ($attachments as $index => $file) {
                if ($file->isValid()) {
                    // เรียกใช้ HP::singleFileUploadRefno
                    HP::singleFileUploadRefno(
                        $file,
                        $this->attach_path.'/'.$assessment->reference_refno,
                        $tax_number, // tax_number
                        auth()->user()->FullName ?? null, // ชื่อผู้ใช้
                        'Center', // คงที่
                        (new TrackingLabReportTwo)->getTable(), // ชื่อตาราง
                        $trackingLabReportTwo->id, // tracking_assessment_id
                        '11111', // คงที่
                        null // ตัวเลือกเพิ่มเติม
                    );
                } 
            }
        } 

        // ส่ง response
        return response()->json([
            'message' => 'ข้อมูลถูกบันทึกเรียบร้อย',
            'tracking_assessment_id' => $id,
            'submit_type' => $submit_type
        ]);
    }


   public function updateLabInfo(Request $request)
   {
       // รับค่าจาก JSON
        $data = $request->input('data'); // ข้อมูลใน key "data"
        $persons = $request->input('persons'); // ข้อมูลใน key "data"
        $assessmentId = $request->input('assessment_id');
        $id = $request->input('id');
        $signers = $request->input('signer', []);
        $submitType = $request->input('submit_type');
        $assessment = TrackingAssessment::find($assessmentId);
       
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

        $trackingLabReportInfo = TrackingLabReportInfo::find($id);
        $trackingLabReportInfo->inp_2_2_assessment_on_site_chk = $inp_2_2_assessment_on_site_chk;
        $trackingLabReportInfo->inp_2_2_assessment_at_tisi_chk = $inp_2_2_assessment_at_tisi_chk;
        $trackingLabReportInfo->inp_2_2_remote_assessment_chk = $inp_2_2_remote_assessment_chk;
        $trackingLabReportInfo->inp_2_2_self_declaration_chk = $inp_2_2_self_declaration_chk;
        $trackingLabReportInfo->inp_2_2_bug_fix_evidence_chk = $inp_2_2_bug_fix_evidence_chk;

        $trackingLabReportInfo->inp_2_4_defects_and_remarks_text = $inp_2_4_defects_and_remarks_text;
        $trackingLabReportInfo->inp_2_4_doc_reference_date_text = $inp_2_4_doc_reference_date_text;
        $trackingLabReportInfo->inp_2_4_doc_sent_date1_text = $inp_2_4_doc_sent_date1_text;
        $trackingLabReportInfo->inp_2_4_doc_sent_date2_text = $inp_2_4_doc_sent_date2_text;
        $trackingLabReportInfo->inp_2_4_lab_bug_fix_completed_chk = $inp_2_4_lab_bug_fix_completed_chk;
        $trackingLabReportInfo->inp_2_4_fix_approved_chk = $inp_2_4_fix_approved_chk;
        $trackingLabReportInfo->inp_2_4_approved_text = $inp_2_4_approved_text;
        $trackingLabReportInfo->inp_2_4_remain_text = $inp_2_4_remain_text;

        $trackingLabReportInfo->inp_3_lab_fix_all_issues_chk = $inp_3_lab_fix_all_issues_chk;
        $trackingLabReportInfo->inp_3_lab_fix_some_issues_chk = $inp_3_lab_fix_some_issues_chk;
        $trackingLabReportInfo->inp_3_approved_text = $inp_3_approved_text;
        $trackingLabReportInfo->inp_3_remain_text = $inp_3_remain_text;
        $trackingLabReportInfo->inp_3_lab_fix_failed_issues_chk = $inp_3_lab_fix_failed_issues_chk;
        $trackingLabReportInfo->inp_3_lab_fix_failed_issues_yes_chk = $inp_3_lab_fix_failed_issues_yes_chk;
        $trackingLabReportInfo->inp_3_lab_fix_failed_issues_no_chk = $inp_3_lab_fix_failed_issues_no_chk;

        $trackingLabReportInfo->status = $submitType;

   
        $trackingLabReportInfo->save();

        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');

        SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $trackingLabReportInfo->id)
                                            ->where('certificate_type',2)
                                            ->where('report_type',1)
                                            ->delete();
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentTrackingReportTransaction::create([
                'tracking_report_info_id' => $trackingLabReportInfo->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certificate/assessment-labs/view-lab-info/'. $assessment->id ,
                'certificate_type' => 2,
                'app_id' => $assessment->reference_refno,
            ]);
        }
        
        if ((int)$submitType == 2) {
            // dd('donllle');
            $this->emailToSigner($assessmentId,$signers);
        }



        // dd($request->all(),$trackingLabReportInfo);
        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $data
        ]);
   }

   public function emailToSigner($assessmentId,$signers)
   {

       $assessment = TrackingAssessment::find($assessmentId);
       $certi = $assessment->certificate_export_to->CertiLabTo;
       $auditors = TrackingAuditors::find( $assessment->tracking_id);

       $config = HP::getConfig();
       $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
       $url_center  =  !empty($config->url_center) ? $config->url_center : url('');
       $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
       $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

       $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
       $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';

       $data_app = [
                   'url'            => $url_center.'/certificate/tracking-assessment-report-assignment',
                   'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : $EMail,
                   'email_cc'       =>  !empty($certi->DataEmailDirectorLABCC) ? $certi->DataEmailDirectorLABCC :  [],
                   'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                   ];

       $log_email =  HP::getInsertCertifyLogEmail(!empty($assessment->tracking_to->reference_refno)? $assessment->tracking_to->reference_refno:null,   
                                                   $assessment->tracking_id,
                                                   (new Tracking)->getTable(),
                                                   $data->id ?? null,
                                                   (new TrackingAssessment)->getTable(),
                                                   4,
                                                    'ลงนามรายงานตรวจประเมิน',
                                                   view('mail.Tracking.mail_lab_report_signer', $data_app),
                                                   !empty($certi->created_by)? $certi->created_by:null,   
                                                   !empty($certi->agent_id)? $certi->agent_id:null, 
                                                   auth()->user()->getKey(),
                                                   !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter :$EMail,
                                                   $certi->email,
                                                   !empty($certi->DataEmailDirectorLABCC) ?  implode(",",$certi->DataEmailDirectorLABCC) : null,
                                                   !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
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

}

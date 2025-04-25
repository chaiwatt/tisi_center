<?php

namespace App\Http\Controllers\Certificate\Labs;

use DB;
use HP; 
use App\User;
use stdClass;
use Carbon\Carbon;
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
use App\Models\Certificate\TrackingLabReportInfo;
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

                        $inspection =   TrackingInspection::where('ref_id',$export->id)  ->where('ref_table',(new CertificateExport)->getTable())   ->where('certificate_type',3)  ->where('reference_refno',$export->reference_refno)->first();
                        if(is_null($inspection)){
                         $inspection = new TrackingInspection;
                        }
                        $inspection->ref_id              = $export->id;
                        $inspection->ref_table           = (new CertificateExport)->getTable();
                        $inspection->certificate_type    = 3;
                        $inspection->reference_refno     = $export->reference_refno;
                        $inspection->save();
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
            }


             self::set_history($assessment);
             self::set_mail_past($assessment);  
  
        }

        $trackingLabReportInfo = new TrackingLabReportInfo();
        $trackingLabReportInfo->tracking_assessment_id = $assessment->id;
        $trackingLabReportInfo->save();

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
            return view('certificate.labs.assessment-labs.form_assessment', compact('assessment','statusAuditorMap'));
          }else{
            return view('certificate.labs.assessment-labs.edit', compact('assessment','bug','statusAuditorMap'));
          }
 
          
        
        }
        abort(403);

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

                            }

                  self::set_history($assessment);

               if( $assessment->vehicle == 1){
                     self::set_mail_past($assessment,$tracking->certificate_export_to);  
               }
          }

          $trackingLabReportInfo = TrackingLabReportInfo::where('tracking_assessment_id',$assessment->id)->first();

          if($trackingLabReportInfo == null)
          {
            $trackingLabReportInfo = new TrackingLabReportInfo();
            $trackingLabReportInfo->tracking_assessment_id = $assessment->id;
            $trackingLabReportInfo->save();
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

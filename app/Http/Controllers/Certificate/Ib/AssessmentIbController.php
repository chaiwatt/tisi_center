<?php

namespace App\Http\Controllers\Certificate\Ib;

use DB;
use HP; 

use stdClass;
use Carbon\Carbon;
use App\AttachFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables; 
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use Illuminate\Support\Facades\Mail; 
use App\Mail\Tracking\SaveAssessmentMail;
use App\Mail\Tracking\CheckSaveAssessment;
use App\Mail\Tracking\MailToIbCbSurExpert;
use App\Models\Certificate\TrackingReview;

use App\Models\Certificate\TrackingAssigns;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingHistory; 
use App\Mail\Tracking\SaveAssessmentPastMail;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingInspection;
use App\Models\Certificate\TrackingIbReportOne;
use App\Models\Certificate\TrackingIbReportTwo;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certificate\TrackingAssessmentBug; 
use App\Models\Certify\ApplicantIB\CertiIBExport; 
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;
use App\Models\Certificate\SignAssessmentTrackingReportTransaction;

class AssessmentIbController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/trackingib';
    }

    public function index(Request $request)
    { 
        $model = str_slug('assessmentib','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certificate.ib.assessment-ib.index' );
        }
        abort(403);
    } 

    public function data_list(Request $request)
    { 
      $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
 
      $model = str_slug('assessmentib', '-');
      $filter_search = $request->input('filter_search');
 
      $filter_bug_report = $request->input('filter_bug_report');

      $filter_start_report_date = !empty($request->get('filter_start_report_date'))?HP::convertDate($request->get('filter_start_report_date'),true):null;
      $filter_end_report_date = !empty($request->get('filter_end_report_date'))?HP::convertDate($request->get('filter_end_report_date'),true):null;
      $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
      $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
      $query = TrackingAssessment::query()
                                    ->where('certificate_type',2) ->where('ref_table',(new CertiIBExport)->getTable())
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
                                  return HP::buttonAction( $item->id, 'certificate/assessment-ib','Certificate\Ib\\AssessmentIbController@destroy', 'assessmentib',false,true,false);
                          })
                          ->order(function ($query) {
                              $query->orderBy('id', 'DESC');
                          })
                          ->rawColumns([ 'checkbox',    'action']) 
                          ->make(true);
    } 


    public function create()
    {
        $model = str_slug('assessmentib','-');
        if(auth()->user()->can('add-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment = new TrackingAssessment;
            $bug = [new TrackingAssessmentBug];
 
            $app_no = [];
            //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
           if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
               $check = TrackingAssigns::where('ref_table', (new CertiIBExport)->getTable())
                                    ->where('certificate_type',2)
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
            
            return view('certificate.ib.assessment-ib.create',['app_no'=> $app_no,
                                                                'assessment'=>$assessment,
                                                                'bug'=>$bug
                                                                ]);
        }
        abort(403);

    }

    public function store(Request $request)
    {
   
        $model = str_slug('assessmentib','-');
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
                $requestData['certificate_type']= 2;
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
        $export = CertiIBExport::findOrFail($assessment->ref_id);
       if(in_array($assessment->degree,[1,8])  && $assessment->bug_report == 1 && !is_null($export) &&  $assessment->vehicle == 1 ){
                //Log 
                self::set_history_bug($assessment);
                //  Mail
                self::set_mail($assessment);  
               if($assessment->main_state == 1 ){
                    $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                    $committee->save();
             
                }else{
                    $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                    $committee->save();
 
                   // สถานะ แต่งตั้งคณะกรรมการ
                    $auditor = TrackingAuditors::where('ref_id',$export->id)
                                                ->where('ref_table',(new CertiIBExport)->getTable())
                                                ->where('certificate_type',2)
                                                ->where('reference_refno',$assessment->reference_refno)
                                                ->whereNull('status_cancel') 
                                                ->get(); 
            
                    if(count($auditor) == count($export->auditors_status_cancel_many)){
 

                        // $export->review     = 1;
                        $export->status_id  = 4;
                        $export->save();

                        $inspection =   TrackingInspection::where('ref_id',$export->id)  ->where('ref_table',(new CertiIBExport)->getTable())   ->where('certificate_type',2)  ->where('reference_refno',$export->reference_refno)->first();
                        if(is_null($inspection)){
                         $inspection = new TrackingInspection;
                        }
                        $inspection->ref_id              = $export->id;
                        $inspection->ref_table           = (new CertiIBExport)->getTable();
                        $inspection->certificate_type    = 2;
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
                                            ->where('ref_table',(new CertiIBExport)->getTable())
                                            ->where('certificate_type',2)
                                            ->where('reference_refno',$assessment->reference_refno)
                                            ->whereNull('status_cancel') 
                                            ->get(); 
        
    
            if(count($auditor) == count($export->auditors_status_cancel_many)){
                $report = new   TrackingReview;  //ทบทวนฯ
                $report->ref_id             = $export->id;
                $report->ref_table          = (new CertiIBExport)->getTable();
                $report->certificate_type   =  2;
                $report->reference_refno    = $assessment->reference_refno;
                $report->save();

                // $export->review     = 1;
                $export->status_id  = 4;
                $export->save();

                $inspection =   TrackingInspection::where('ref_id',$export->id)  ->where('ref_table',(new CertiIBExport)->getTable())   ->where('certificate_type',2)  ->where('reference_refno',$export->reference_refno)->first();
                if(is_null($inspection)){
                    $inspection = new TrackingInspection;
                }
                $inspection->ref_id              = $export->id;
                $inspection->ref_table           = (new CertiIBExport)->getTable();
                $inspection->certificate_type    = 2;
                $inspection->reference_refno     = $export->reference_refno;
                $inspection->save();
                $this->addScopeFile($inspection);
            }


             self::set_history($assessment);
             self::set_mail_past($assessment);  
  
        }

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
        }
  // } catch (\Exception $e) {
    //        return redirect('certificate/assessment-ib')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
    // }
        
        }
        abort(403);
    }


public function addScopeFile($inspection)
 {
         if($inspection->FileAttachScopeTo == null)
        {
           

            $appId = $inspection->reference_refno;
        

            $certiIb = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;
    
            $certiIbFileAll = CertiIBAttachAll::where('app_certi_ib_id',$certiIb->id)
                ->where('table_name','app_certi_ib')
                ->where('file_section',3)
                ->latest() // เรียงจาก created_at จากมากไปน้อย
                ->first();
    
            $filePath = 'files/applicants/check_files_ib/' . $certiIbFileAll->file ;
    
            $localFilePath = HP::downloadFileFromTisiCloud($filePath);

            // dd($certiIb ,$certiIbFileAll,$filePath,$localFilePath);

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
    
            
            $attach_path = "files/trackingib";
            // dd($attach_path.'/'.$inspection->reference_refno);
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
    public function edit(Request $request,$id)
    {
        $model = str_slug('assessmentib','-');
        if(auth()->user()->can('edit-'.$model)) {
          $previousUrl = app('url')->previous();
        //   $assessment                   =  TrackingAssessment::findOrFail($id);



        $assessment                   =  TrackingAssessment::findOrFail($id);
        // dd($assessment );
        $trackingAuditor = TrackingAuditors::find( $assessment->auditors_id);
      


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


 
          $assessment->name             =  !empty($assessment->certificate_export_to->CertiIBCostTo->name) ? $assessment->certificate_export_to->CertiIBCostTo->name : null;
          $assessment->laboratory_name  =  !empty($assessment->certificate_export_to->CertiIBCostTo->name_unit) ? $assessment->certificate_export_to->CertiIBCostTo->name_unit : null; 

          $assessment->auditor          =  !empty($assessment->auditors_to->auditor) ? $assessment->auditors_to->auditor : null;
          $assessment->auditor_date     =  !empty($assessment->auditors_to->CertiAuditorsDateTitle) ? $assessment->auditors_to->CertiAuditorsDateTitle : null;
          $assessment->auditor_file     =  !empty($assessment->auditors_to->FileAuditors2) ? $assessment->auditors_to->FileAuditors2 : null;
          if(count($assessment->tracking_assessment_bug_many) > 0){ 
            $bug =  $assessment->tracking_assessment_bug_many;
          }else{
            $bug =  [new TrackingAssessmentBug];
          }
          
          if(in_array($assessment->degree,[2,3,4,5,7,8])){
            return view('certificate.ib.assessment-ib.form_assessment', compact('assessment','statusAuditorMap'));
          }else{
            return view('certificate.ib.assessment-ib.edit', compact('assessment','bug','statusAuditorMap'));
          }
 
          
        
        }
        abort(403);

    }

    public function checkIsReportSigned(Request $request)
    {
        $signAssessmentTrackingReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $request->tracking_report_info_id)
            ->where('certificate_type', 1)
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
                ->where('certificate_type', 1)
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
            ->where('certificate_type', 1)
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
                ->where('certificate_type', 1)
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
        // dd($request->all());
        $model = str_slug('assessmentib','-');
        if(auth()->user()->can('edit-'.$model)) {
 
     // try {
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
                $requestData = $request->all();
                // $requestData['report_date'] =  HP::convertDate($request->report_date,true) ?? null;
                $requestData['report_date'] = Carbon::now();
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

            $assessment->update($requestData);


              // ข้อบกพร่อง/ข้อสังเกต
              if(isset($requestData["detail"]) && $assessment->bug_report == 1){
                self::storeDetail($assessment,$requestData["detail"]);
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
    
            // รายงานการตรวจประเมิน รายงาน 1 ใช้จาก pdf
            //  if($request->file  && $request->hasFile('file')){
            //             HP::singleFileUploadRefno(
            //                   $request->file('file') ,
            //                   $this->attach_path.'/'.$assessment->reference_refno,
            //                   ( $tax_number),
            //                   (auth()->user()->FullName ?? null),
            //                   'Center',
            //                   (  (new TrackingAssessment)->getTable() ),
            //                   $assessment->id,
            //                   '1',
            //                   null
            //             );
            //  }


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
          if(in_array($assessment->degree,[1,8])  && $assessment->bug_report == 1 && !is_null($tracking) &&  $assessment->vehicle == 1 ){
                  //  Log 
                  self::set_history_bug($assessment);
                  //  Mail
                  self::set_mail($assessment,$tracking->certificate_export_to);   
                 if($assessment->main_state == 1 ){
                      $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                      $committee->save();
                  
                  }else{
                      $committee->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
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
                        $inspection->tracking_id         = $tracking->id;
                        $inspection->ref_id              = $tracking->ref_id;
                        $inspection->reference_refno     = $tracking->reference_refno;
                        $inspection->ref_table           = (new CertiIBExport)->getTable();
                        $inspection->certificate_type    = 2;
                        $inspection->save();
                        $this->addScopeFile($inspection);
                    }
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
                        $inspection->tracking_id         = $tracking->id;
                        $inspection->ref_id              = $tracking->ref_id;
                        $inspection->reference_refno     = $tracking->reference_refno;
                        $inspection->ref_table           = (new CertiIBExport)->getTable();
                        $inspection->certificate_type    = 2;
                        $inspection->save();
                        $this->addScopeFile($inspection);
                    }


                  self::set_history($assessment);



               if( $assessment->vehicle == 1){
                     self::set_mail_past($assessment,$tracking->certificate_export_to);  
               }
          }
    
        $check = TrackingIbReportOne::where('tracking_assessment_id',$assessment->id)->first();
        if($check == null)
        {
            $trackingIbReportOne = new TrackingIbReportOne();
            $trackingIbReportOne->tracking_assessment_id = $assessment->id;
            $trackingIbReportOne->save();
        }

        $check2 = TrackingIbReportTwo::where('tracking_assessment_id',$assessment->id)->first();
        if($check2 == null)
        {
            $trackingIbReportTwo = new TrackingIbReportTwo();
            $trackingIbReportTwo->tracking_assessment_id = $assessment->id;
            $trackingIbReportTwo->save();
        }

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
        }

        }
        abort(403);

    }


    public function update_assessment(Request $request, $id){
  
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
                    }else{
                        $committee->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
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
 
                    $inspection =   TrackingInspection::where('tracking_id',$tracking->id)  ->where('reference_refno',$tracking->reference_refno)->first();
                    if(is_null($inspection)){
                     $inspection = new TrackingInspection;
                    }
                    $inspection->tracking_id         = $tracking->id;
                    $inspection->ref_id              = $tracking->ref_id;
                    $inspection->ref_table           = (new CertiIBExport)->getTable();
                    $inspection->certificate_type    = 2;
                    $inspection->reference_refno     = $tracking->reference_refno;
                    $inspection->save();
                    $this->addScopeFile($inspection);
                }
            }
     
         }

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
        }
    
    // } catch (\Exception $e) {
    //     return redirect('certificate/assessment-ib/'.$assessment->id.'/edit')->with('message', 'เกิดข้อผิดพลาด!');
    //  }
    
    }
    


    public function data_certi($id) {                   
        $auditor = TrackingAuditors::findOrFail($id);  
        $auditor->name              = !empty($auditor->certificate_export_to->CertiIBCostTo->name) ?  str_replace("มอก.","",$auditor->certificate_export_to->CertiIBCostTo->name) :'' ;
        $auditor->name_standard     = !empty($auditor->certificate_export_to->CertiIBCostTo->name_standard) ?  str_replace("มอก.","",$auditor->certificate_export_to->CertiIBCostTo->name_standard) :'' ;
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
        
         if( !empty($data->certificate_export_to->CertiIBCostTo)){
             $certi =$data->certificate_export_to->CertiIBCostTo;
             if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                            $data_app = [
                                            'data'          => $certi,
                                            'assessment'    => $data ,
                                            'export'        => $data->certificate_export_to ?? '' ,
                                            'url'           => $url.'certify/tracking-ib',
                                            'tis'           => '17020  : ข้อ' ,
                                            'email'         => !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter  : 'ib@tisi.mail.go.th',
                                            'email_cc'      => !empty($certi->DataEmailDirectorIBCC) ? $certi->DataEmailDirectorIBCC   : [],
                                            'email_reply'   => !empty($certi->DataEmailDirectorIBReply) ? $certi->DataEmailDirectorIBReply : []
                                        ];
                    
                            $log_email =  HP::getInsertCertifyLogEmail(!empty($data->tracking_to->reference_refno)? $data->tracking_to->reference_refno:null,   
                                                                        $data->tracking_id,
                                                                        (new Tracking)->getTable(),
                                                                        $data->id ?? null,
                                                                        (new TrackingAssessment)->getTable(),
                                                                        5,
                                                                        'นำส่งรายงานการตรวจประเมิน',
                                                                        view('mail.Tracking.save_assessment', $data_app),
                                                                        !empty($certi->created_by)? $certi->created_by:null,   
                                                                        !empty($certi->agent_id)? $certi->agent_id:null, 
                                                                        auth()->user()->getKey(),
                                                                        !empty($certi->DataEmailCertifyCenter) ? implode(",",$certi->DataEmailCertifyCenter)  : 'ib@tisi.mail.go.th',
                                                                        $certi->email,
                                                                        !empty($certi->DataEmailDirectorIBCC) ?  implode(",",$certi->DataEmailDirectorIBCC) : null,
                                                                        !empty($certi->DataEmailDirectorIBReply) ? implode(",",$certi->DataEmailDirectorIBReply):  null
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
        if( !empty($data->certificate_export_to->CertiIBCostTo)){
            $certi =$data->certificate_export_to->CertiIBCostTo;
            if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                $data_app = [
                                'data'           => $certi,
                                'assessment'     => $data ,
                                'export'         => $data->certificate_export_to ?? '' ,
                                'url'            => $url.'certify/tracking-ib',
                                'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'      =>  !empty($certi->DataEmailDirectorIBCC) ? $certi->DataEmailDirectorIBCC : [],
                                'email_reply'   => !empty($certi->DataEmailDirectorIBReply) ? $certi->DataEmailDirectorIBReply:  []
                            ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($data->tracking_to->reference_refno)? $data->tracking_to->reference_refno:null,   
                                                            $data->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $data->id ?? null,
                                                            (new TrackingAssessment)->getTable(),
                                                            5,
                                                            !is_null($data->FileAttachAssessment5To) ? 'แจ้งผลการประเมิน' : 'แจ้งผลการประเมินแนวทางแก้ไขข้อบกพร่อง',
                                                            view('mail.Tracking.check_save_assessment', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  implode(",",$certi->DataEmailCertifyCenter)   : 'ib@tisi.mail.go.th',
                                                            $certi->email,
                                                            !empty($certi->DataEmailDirectorIBCC) ?  implode(",",$certi->DataEmailDirectorIBCC) : null,
                                                            !empty($certi->DataEmailDirectorIBReply) ? implode(",",$certi->DataEmailDirectorIBReply):  null
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
        if( !empty($data->certificate_export_to->CertiIBCostTo)){
            $certi =$data->certificate_export_to->CertiIBCostTo;
            if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                $data_app =[
                                'data'           => $certi,
                                'assessment'     => $data ,
                                'url'            => $url.'certify/tracking-ib',
                                'export'         => $data->certificate_export_to ?? '' ,
                                'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                'email_cc'       =>  !empty($certi->DataEmailDirectorIBCC) ? $certi->DataEmailDirectorIBCC : [],
                                'email_reply'    => !empty($certi->DataEmailDirectorIBReply) ? $certi->DataEmailDirectorIBReply : []
                           ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($data->tracking_to->reference_refno)? $data->tracking_to->reference_refno:null,   
                                                            $data->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $data->id ?? null,
                                                            (new TrackingAssessment)->getTable(),
                                                            5,
                                                             'แจ้งผลการประเมิน',
                                                            view('mail.Tracking.save_assessment_past', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ? implode(",",$certi->DataEmailCertifyCenter)  : 'ib@tisi.mail.go.th',
                                                            $certi->email,
                                                            !empty($certi->DataEmailDirectorIBCC) ?  implode(",",$certi->DataEmailDirectorIBCC) : null,
                                                            !empty($certi->DataEmailDirectorIBReply) ? implode(",",$certi->DataEmailDirectorIBReply):  null
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
                                    'certificate_type'  => 2,
                                    'reference_refno'   => $data->reference_refno ?? null,
                                    'ref_table'         =>  (new CertiIBExport)->getTable() ,
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
                                    'certificate_type'  => 2,
                                    'reference_refno'   => $data->reference_refno ?? null,
                                    'ref_table'         =>  (new CertiIBExport)->getTable() ,
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
    // dd($request->all());

       $assessment = TrackingAssessment::find($request->assessment_id);
       $expertEmails = $request->selectedEmails;
       $certi = $assessment->certificate_export_to->CertiIBCostTo;
       $auditors = TrackingAuditors::find( $assessment->tracking_id);

       $config = HP::getConfig();
       $url_center  =  !empty($config->url_center) ? $config->url_center : url('');

        $data_app = [
                'certi'           => $certi,
                'assessment'     => $assessment ,
                'export'         => $data->certificate_export_to ?? '' ,
                'url'            => $url_center.'/create-by-ib-expert-sur/' . $assessment->id .'?token='.$assessment->expert_token,
                'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                'email_cc'      =>  !empty($certi->DataEmailDirectorIBCC) ? $certi->DataEmailDirectorIBCC : [],
                'email_reply'   => !empty($certi->DataEmailDirectorIBReply) ? $certi->DataEmailDirectorIBReply:  []
            ];           


     $log_email =  HP::getInsertCertifyLogEmail(!empty($assessment->reference_refno)? $assessment->reference_refno:null,    
                                                            $assessment->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $assessment->id ?? null,
                                                            (new TrackingAssessment)->getTable(),
                                                            4,
                                                             'แจ้งผลการประเมิน',
                                                            view('mail.Tracking.mail_cb_ib_expert', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ? implode(",",$certi->DataEmailCertifyCenter)  : 'ib@tisi.mail.go.th',
                                                            $certi->email,
                                                            !empty($certi->DataEmailDirectorIBCC) ?  implode(",",$certi->DataEmailDirectorIBCC) : null,
                                                            !empty($certi->DataEmailDirectorIBReply) ? implode(",",$certi->DataEmailDirectorIBReply):  null
                                                        );

       $html = new MailToIbCbSurExpert($data_app);
       $mail = Mail::to($expertEmails)->send($html);
       if(is_null($mail) && !empty($log_email)){
           HP::getUpdateCertifyLogEmail($log_email->id);
       }
   }
  
   
   public function viewIbReportOne($assessment_id)
   {
    // dd('ok');
       $assessment = TrackingAssessment::find($assessment_id);
       $ibReportOne = TrackingIbReportOne::with('attachments')->where('tracking_assessment_id',$assessment_id)->first();
       
       $certi_ib = $assessment->certificate_export_to->applications;

    //    dd($certi_ib->CertiAuditors);
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
            //   dd($auditor);
              $statusAuditorMap[$statusAuditorId][] = $auditor->id;
          }
      }

    //   dd($statusAuditorMap);

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


        
        $data = new stdClass();
    
        $data->header_text1 = '';
        $data->header_text2 = '';
        $data->header_text3 = '';
        $data->header_text4 = $certi_ib->app_no;
        // $data->lab_type = $certi_ib->lab_type == 3 ? 'ทดสอบ' : ($certi_ib->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
        $data->tracking = $certi_ib->tracking;
        // $data->scope_branch = $scope_branch;
        $data->tracking = $tracking;
        // $data->app_no = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_ib->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_ib->get_date);
        // $data->experts = $experts;

        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;


        // $labRequest = null;

        // 1 = IB
        $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$ibReportOne->id)
                                        ->where('certificate_type',1)
                                        ->where('report_type',1)
                                        ->get();
        $ibInformation = $certi_ib->information;
    //    dd($ibInformation);
    // dd($certi_ib->basic_province);
        return view('certificate.ib.assessment-ib.report-one.view-report-one', [
            'data' => $data,
            'assessment' => $assessment,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'tracking' => $tracking,
            'certi_ib' => $certi_ib,
            'trackingAuditor' => $trackingAuditor,
            'ibReportOne' => $ibReportOne,
            'trackingAuditorsDate' => $trackingAuditorsDate,
            // 'ibInformation' => $ibInformation[0]
        ]);

       
   }

    public function updateIbReportOne(Request $request)
   {
    // dd($request->all());
        // $submit_type = $payload['submit_type'] ?? null;
        $signers = json_decode($request->input('signer'), true);
        $assessment = (object) (json_decode($request->input('assessment')) ?? []);

        // dd( $request->file('references'));
       
        $data = json_decode($request->input('data'), true); // แปลง JSON String เป็น Array
        $id = $request->id;
        $assessment = TrackingAssessment::find($id);

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

        $insertData = [
            'tracking_assessment_id' => $id,
            'eval_riteria_text' => $data[0]['eval_riteria_text'] ?? null,
            'background_history' => $data[0]['background_history'] ?? null, // แปลงเป็น JSON หากเป็น array
            'insp_proc' => $data[0]['insp_proc'] ?? null,
            'evaluation_key_point' => $data[0]['evaluation_key_point'] ?? null,
            'observation' => $data[0]['observation'] ?? null,
            'evaluation_result' => $data[0]['evaluation_result'] ?? null,
            'auditor_suggestion' => $data[0]['auditor_suggestion'] ?? null,
            'persons' => $request->persons ?? null,
            'attached_files' => !empty($attachedFiles) ? json_encode($attachedFiles) : null,
            'status' => $request->status,
        ];

        foreach ($data[0]['evaluation_detail'] as $key => $value) {
            $insertData["{$key}_chk"] = $value['chk'] ?? false;
            $insertData["{$key}_eval_select"] = $value['eval_select'] ?? null;
            $insertData["{$key}_comment"] = $value['comment'] ?? null;
        }

        // dd($insertData);

        try {
            $trackingIbReportOne = TrackingIbReportOne::updateOrCreate(
                ['tracking_assessment_id' => $id],
                $insertData
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }

        
        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');
         // 1 = IB
        SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $trackingIbReportOne->id)
                                            ->where('certificate_type',1)
                                            ->where('report_type',1)
                                            ->delete();
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentTrackingReportTransaction::create([
                'tracking_report_info_id' => $trackingIbReportOne->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certificate/assessment-ib/view-ib-info/'. $assessment->id ,
                'certificate_type' => 1,
                'app_id' => $assessment->reference_refno,
            ]);
        }

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        
        $attachments = $request->file('references');
        // dd($attachments);
        // $attachedFiles = [];
        if ($attachments && $request->hasFile('references')) {
            // dd($attachments);
            foreach ($attachments as $index => $file) {
                if ($file->isValid()) {
                    // เรียกใช้ HP::singleFileUploadRefno
                    HP::singleFileUploadRefno(
                        $file,
                        $this->attach_path.'/'.$assessment->reference_refno,
                        $tax_number, // tax_number
                        auth()->user()->FullName ?? null, // ชื่อผู้ใช้
                        'Center', // คงที่
                        (new TrackingIbReportOne)->getTable(), // ชื่อตาราง
                        $trackingIbReportOne->id, // tracking_assessment_id
                        '11111', // คงที่
                        null // ตัวเลือกเพิ่มเติม
                    );
                } 
            }
        } 

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
        }
        // $ibReportInfo = trackingIbReportOne::where('tracking_assessment_id',$id)->first()->update($insertData);
        // dd($insertData);

           return response()->json([
                                 'assessment'=> $assessment ?? [] 
                             ]);
   }

    public function viewIbReportTwo($assessment_id)
   {
    // dd($assessment_id);
       $assessment = TrackingAssessment::find($assessment_id);
       $ibReportTwo = TrackingIbReportTwo::with('attachments')->where('tracking_assessment_id',$assessment_id)->first();
       
       $certi_ib = $assessment->certificate_export_to->applications;

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
            //   dd($auditor);
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


        
        $data = new stdClass();
    
        $data->header_text1 = '';
        $data->header_text2 = '';
        $data->header_text3 = '';
        $data->header_text4 = $certi_ib->app_no;
        // $data->lab_type = $certi_ib->lab_type == 3 ? 'ทดสอบ' : ($certi_ib->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
        $data->tracking = $certi_ib->tracking;
        // $data->scope_branch = $scope_branch;
        $data->tracking = $tracking;
        // $data->app_no = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_ib->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_ib->get_date);
        // $data->experts = $experts;

        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;


        // $labRequest = null;

        // 1 = IB
        $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$ibReportTwo->id)
                                        ->where('certificate_type',1)
                                        ->where('report_type',2)
                                        ->get();


                                         
        $ibInformation = $certi_ib->information;
    //    dd($ibInformation);
    // dd($certi_ib->basic_province);
        return view('certificate.ib.assessment-ib.report-two.view-report-two', [
            'data' => $data,
            'assessment' => $assessment,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'tracking' => $tracking,
            'certi_ib' => $certi_ib,
            'trackingAuditor' => $trackingAuditor,
            'ibReportTwo' => $ibReportTwo,
            'trackingAuditorsDate' => $trackingAuditorsDate,
            // 'ibInformation' => $ibInformation[0]
        ]);

       
   }

   public function updateIbReportTwo(Request $request)
   {
    // dd($request->all());
        // $submit_type = $payload['submit_type'] ?? null;
        $signers = json_decode($request->input('signer'), true);
        $assessment = (object) (json_decode($request->input('assessment')) ?? []);

        // dd( $request->file('references'));
       
        $data = json_decode($request->input('data'), true); // แปลง JSON String เป็น Array
        $id = $request->id;
        $assessment = TrackingAssessment::find($id);

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

        $insertData = [
            'tracking_assessment_id' => $id,
            'eval_riteria_text' => $data[0]['eval_riteria_text'] ?? null,
            'background_history' => $data[0]['background_history'] ?? null, // แปลงเป็น JSON หากเป็น array
            'insp_proc' => $data[0]['insp_proc'] ?? null,
            'evaluation_key_point' => $data[0]['evaluation_key_point'] ?? null,
            'observation' => $data[0]['observation'] ?? null,
            'evaluation_result' => $data[0]['evaluation_result'] ?? null,
            'auditor_suggestion' => $data[0]['auditor_suggestion'] ?? null,
            'persons' => $request->persons ?? null,
            'attached_files' => !empty($attachedFiles) ? json_encode($attachedFiles) : null,
            'status' => $request->status,
        ];

        foreach ($data[0]['evaluation_detail'] as $key => $value) {
            $insertData["{$key}_chk"] = $value['chk'] ?? false;
            $insertData["{$key}_eval_select"] = $value['eval_select'] ?? null;
            $insertData["{$key}_comment"] = $value['comment'] ?? null;
        }

        // dd($insertData);

        try {
            $trackingIbReportTwo = TrackingIbReportTwo::updateOrCreate(
                ['tracking_assessment_id' => $id],
                $insertData
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save data: ' . $e->getMessage()], 500);
        }

        
        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');
         // 1 = IB
        SignAssessmentTrackingReportTransaction::where('tracking_report_info_id', $trackingIbReportTwo->id)
                                            ->where('certificate_type',1)
                                            ->where('report_type',2)
                                            ->delete();
        foreach ($signers as $signer) {
            // ตรวจสอบความถูกต้องของข้อมูล
            if (!isset($signer['signer_id'], $signer['signer_name'], $signer['signer_position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            SignAssessmentTrackingReportTransaction::create([
                'tracking_report_info_id' => $trackingIbReportTwo->id,
                'signer_id' => $signer['signer_id'],
                'signer_name' => $signer['signer_name'],
                'signer_position' => $signer['signer_position'],
                'signer_order' => $signer['id'],
                'view_url' => $url . '/certificate/assessment-ib/view-ib-info/'. $assessment->id ,
                'certificate_type' => 1,
                'report_type' => 2,
                'app_id' => $assessment->reference_refno,
            ]);
        }

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        
        $attachments = $request->file('references');
        // dd($attachments);
        // $attachedFiles = [];
        if ($attachments && $request->hasFile('references')) {
            // dd($attachments);
            foreach ($attachments as $index => $file) {
                if ($file->isValid()) {
                    // เรียกใช้ HP::singleFileUploadRefno
                    HP::singleFileUploadRefno(
                        $file,
                        $this->attach_path.'/'.$assessment->reference_refno,
                        $tax_number, // tax_number
                        auth()->user()->FullName ?? null, // ชื่อผู้ใช้
                        'Center', // คงที่
                        (new TrackingIbReportTwo)->getTable(), // ชื่อตาราง
                        $trackingIbReportTwo->id, // tracking_assessment_id
                        '11111', // คงที่
                        null // ตัวเลือกเพิ่มเติม
                    );
                } 
            }
        } 

        if($request->previousUrl){
            return redirect("$request->previousUrl")->with('message', 'เรียบร้อยแล้ว!');
        }else{
            return redirect('certificate/assessment-ib')->with('message', 'เรียบร้อยแล้ว!');
        }
        // $ibReportInfo = trackingIbReportOne::where('tracking_assessment_id',$id)->first()->update($insertData);
        // dd($insertData);

           return response()->json([
                                 'assessment'=> $assessment ?? [] 
                             ]);
   }
}

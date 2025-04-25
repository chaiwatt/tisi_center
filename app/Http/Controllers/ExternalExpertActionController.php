<?php

namespace App\Http\Controllers;

use HP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certify\Applicant\NoticeItem;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingAssessmentBug;

class ExternalExpertActionController extends Controller
{
    public function createByExpert(Request $request,$notice_id=null)
    {
        // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $token = $request->query('token');
        $notice = Notice::find($notice_id);
        $assessment = $notice->assessment;
        $boardAuditor = $assessment->board_auditor_to;
        $expertToken = $notice->expert_token;
       
        if($token != $expertToken)
        {
            return redirect($url);
        }
       

        $parts = explode('_', $notice->expert_token);
        $randomString = $parts[0]; // ส่วน String แบบสุ่ม
        $encodedTimestamp = $parts[1]; // Timestamp ที่เข้ารหัส
    
        // ถอดรหัส Timestamp โดยใช้ base64_decode
        $originalTimestamp = base64_decode($encodedTimestamp);
    
        // แปลงตัวเลขที่ได้เป็นเวลา (Carbon DateTime)
        $expiryDateTime = Carbon::createFromTimestamp($originalTimestamp);
        
        // ตรวจสอบว่าเวลาปัจจุบันน้อยกว่าเวลา expiryDateTime หรือไม่
        if (Carbon::now()->gt($expiryDateTime)) {
            return redirect($url);
        }

        $app = new CertiLab();
        $NoticeItem = NoticeItem::where('app_certi_lab_notice_id',$notice_id)
        ->whereNull('owner_id')
        ->get();
       
        $app_no = [];

        $auditor = BoardAuditor::select('id','app_certi_lab_id','auditor')
                                       ->whereIn('step_id',[6])
                                      ->orderby('id','desc')
                                      ->get();
        if(count($auditor) > 0 ){
            foreach ($auditor as $item)
            {
                $app_no[$item->id] = $item->auditor . " ( ". @$item->applicant->app_no . " )";
            }
        }

        $board_auditor_id = $boardAuditor->id;

        return view('certify.save_assessment.create-expert', compact('app','NoticeItem','app_no','board_auditor_id','notice','expiryDateTime'));
    }

    public function storeByExpert(Request $request)
    {
        $notice = Notice::find($request->notice_id);

        $notices = $request->notice;
        $report = $request->report;
        $noks = $request->nok;
        $types = $request->type;
        // $founds = $request->input('found');
          
        NoticeItem::where('app_certi_lab_notice_id',$notice->id)
        ->whereNull('owner_id')
        ->delete();

        if(isset($notices)){
           foreach ($notices as $key => $notice) 
           {
               if($notice != ''){
                   $item = new NoticeItem;
                   $item->app_certi_lab_notice_id = $request->notice_id;
                   $item->remark = $notice;
                   $item->report = $report[$key];
                   $item->no = $noks[$key];
                   $item->type = $types[$key];
                   $item->reporter_id = null;
                   $item->save();
               }
           }
       }

        return redirect()->back();
    }

    public function storeByExpertGetApp($id) {
       

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



    public function createByExpertLabSur(Request $request,$assessment_id=null)
    {
   
        // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $token = $request->query('token');
        $assessment  =  TrackingAssessment::findOrFail($assessment_id);
        
        $trackingApp = $assessment->tracking_to;
        $boardAuditor = TrackingAuditors::find( $assessment->tracking_id);
        $expertToken = $assessment->expert_token;
        // dd($assessment->expert_token,$token);
        if($token != $expertToken)
        {
            return redirect($url);
        }
       
     
        $parts = explode('_', $expertToken);
        $randomString = $parts[0]; 
        $encodedTimestamp = $parts[1]; 

        $originalTimestamp = base64_decode($encodedTimestamp);
    

        $expiryDateTime = Carbon::createFromTimestamp($originalTimestamp);
        
        if (Carbon::now()->gt($expiryDateTime)) {
            return redirect($url);
        }

        if ($assessment->submit_type != 'save') {
            return redirect($url);
        }


        // dd($assessment);

       
        $trackingAuditor = TrackingAuditors::find( $assessment->tracking_id);
   
        $trackingApp = $assessment->tracking_to;
      
        $boardAuditorMsRecordInfo = $trackingAuditor->boardAuditorTrackingMsRecordInfos->first();

        // dd($boardAuditorMsRecordInfo);

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

        $model = str_slug('assessmentlabs','-');
        if(auth()->user()->can('edit-'.$model)) {
            $previousUrl = app('url')->previous();
            $assessment                   =  TrackingAssessment::findOrFail($assessment_id);
            $assessment->name             =  !empty($assessment->certificate_export_to->CertiLabTo->name) ? $assessment->certificate_export_to->CertiLabTo->name : null;
            $assessment->laboratory_name  =  !empty($assessment->certificate_export_to->CertiLabTo->lab_name) ? $assessment->certificate_export_to->CertiLabTo->lab_name : null; 
            $assessment->auditor          =  !empty($assessment->auditors_to->auditor) ? $assessment->auditors_to->auditor : null;
            $assessment->auditor_date     =  !empty($assessment->auditors_to->CertiAuditorsDateTitle) ? $assessment->auditors_to->CertiAuditorsDateTitle : null;
            $assessment->auditor_file     =  !empty($assessment->auditors_to->FileAuditors2) ? $assessment->auditors_to->FileAuditors2 : null;

            $bug = TrackingAssessmentBug::where('assessment_id',$assessment_id)
            ->whereNull('owner_id')
            ->get();

            return view('certificate.labs.assessment-labs.create-expert', compact('assessment','bug','statusAuditorMap','expiryDateTime','trackingApp'));

            // dd($trackingApp,$assessment,$NoticeItems,$auditors,$app_no);  
            // return view('certificate.labs.assessment-labs.create-expert', compact('app','NoticeItems','app_no','board_auditor_id','assessment','expiryDateTime'));
        }
    }

    public function storeByExpertLabSur(Request $request)
    {
        // dd("created by expert",$request->all());

        // $notices = $request->notice;
        // $report = $request->report;
        // $noks = $request->nok;
        // $types = $request->type;

        TrackingAssessmentBug::where('assessment_id',$request->assessment_id)
        ->whereNull('owner_id')
        ->delete();
        $detail = $request->detail;
        foreach ($detail['notice'] as $key => $item) {
            $bug = new TrackingAssessmentBug;
            $bug->assessment_id = $request->assessment_id;
            $bug->remark        = $item;
            $bug->report        = $detail["report"][$key] ?? null;
            $bug->no            = $detail["no"][$key] ?? null;
            $bug->type          = $detail["type"][$key] ?? null;
            $bug->reporter_id   = $detail["found"][$key] ?? null;
            $bug->owner_id   = null;

            // dd($request->assessment_id,$item,$detail["report"][$key],$detail["no"][$key],$detail["type"][$key]);
            $bug->save();
        }
        return redirect()->back();
    }
}

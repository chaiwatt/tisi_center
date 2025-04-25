<?php

namespace App\Http\Controllers;

use HP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessmentBug;

class ExternalCBExpertActionController extends Controller
{
    public function createByCbExpert(Request $request,$assessment_id=null)
    {
        // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $token = $request->query('token');
        $assessment = CertiCBSaveAssessment::find($assessment_id);


        $expertToken = $assessment->expert_token;
       
        if($token != $expertToken)
        {
            return redirect($url);
        }
       

        $parts = explode('_', $assessment->expert_token);
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

        // $app = new CertiLab();
        $certiCBSaveAssessmentBugs = CertiCBSaveAssessmentBug::where('assessment_id',$assessment->id)
        ->whereNull('owner_id')
        ->get();
       
        $app_no = [];

        $auditor = CertiCBAuditors::select('id','app_certi_cb_id','auditor')
                                       ->whereIn('step_id',[6])
                                      ->orderby('id','desc')
                                      ->get();
        if(count($auditor) > 0 ){
            foreach ($auditor as $item)
            {
                $app_no[$item->id] = $item->auditor . " ( ". @$item->applicant->app_no . " )";
            }
        }

        $id = $assessment->auditors_id;

        // dd($board_auditor_id);

        // return view('certify.save_assessment.create-cb-expert', compact('app','certiCBSaveAssessmentBugs','app_no','board_auditor_id','assessment','expiryDateTime'));

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
                $bug = CertiCBSaveAssessmentBug::where('assessment_id',$assessment->id)->whereNull('owner_id')->get();
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

        


        return view('certify.save_assessment.create-cb-expert',['app_no'=> $app_no,
                                                            'assessment'=>$assessment,
                                                            'bug'=>$bug,
                                                            'previousUrl'=> $previousUrl,
                                                            'auditorId'=> $id,
                                                            'certiCBAuditorsLists'=> $certiCBAuditorsLists,
                                                            'expiryDateTime' => $expiryDateTime
                                                            ]);
    }


    public function storeByCbExpert(Request $request)
    {
        $assessment = CertiCBSaveAssessment::find($request->assessment_id);

        $report = $request->detail['report'];
        $no = $request->detail['no'];
        $type = $request->detail['type'];
        $notice = $request->detail['notice'];
        // $found = $request->detail['found'];
          
        CertiCBSaveAssessmentBug::where('assessment_id',$assessment->id)
        ->whereNull('owner_id')
        ->delete();

        foreach ($report as $key => $item) {
            $bug = new CertiCBSaveAssessmentBug;
            $bug->assessment_id = $request->assessment_id;
            $bug->remark        = $notice[$key];
            $bug->report        = $report[$key];
            $bug->no            = $no[$key];
            $bug->type          = $type[$key];
            $bug->reporter_id   = null;
            $bug->owner_id = null;
            $bug->save();
        }

        return redirect()->back();
    }

}

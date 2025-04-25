<?php

namespace App\Http\Controllers;

use HP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\Cb\MailToCbExpert;
use App\Mail\Ib\MailToIbExpert;
use Illuminate\Support\Facades\Mail;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\ApplicantIB\CertiIBCheck;
use App\Models\Certify\ApplicantIB\CertiIBAuditors;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessmentBug;

class ExternalIBExpertActionController extends Controller
{
    
    public function createByIbExpert(Request $request,$assessment_id=null)
    {
        // http://127.0.0.1:8081/create-by-expert/1390?token=62FfUJeXwNIBkg9FZmAQwJTO1ODu73_MTczNjc2NTg5Mw==
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        $token = $request->query('token');
        $assessment = CertiIBSaveAssessment::find($assessment_id);


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
        $CertiIBSaveAssessmentBugs = CertiIBSaveAssessmentBug::where('assessment_id',$assessment->id)
        ->whereNull('owner_id')
        ->get();
       
        $app_no = [];

        $auditor = CertiIBAuditors::select('id','app_certi_ib_id','auditor')
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
                $bug = CertiIBSaveAssessmentBug::where('assessment_id',$assessment->id)->whereNull('owner_id')->get();
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
                 $app_no[$item->id] = $item->auditor . " ( ". @$item->CertiIbCostTo->app_no . " )";
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
                     $app_no[$item->id] = $item->auditor . " ( ". @$item->CertiIbCostTo->app_no . " )";
                }
              }
         }

        $certiIBAuditorsLists = CertiIBAuditors::find($id)->CertiIBAuditorsLists;

        


        return view('certify.save_assessment.create-ib-expert',['app_no'=> $app_no,
                                                            'assessment'=>$assessment,
                                                            'bug'=>$bug,
                                                            'previousUrl'=> $previousUrl,
                                                            'auditorId'=> $id,
                                                            'certiIBAuditorsLists'=> $certiIBAuditorsLists,
                                                            'expiryDateTime' => $expiryDateTime
                                                            ]);
    }


    public function storeByIbExpert(Request $request)
    {
        // dd($request->all());
        $assessment = CertiIBSaveAssessment::find($request->assessment_id);

        $report = $request->detail['report'];
        $no = $request->detail['no'];
        $type = $request->detail['type'];
        $notice = $request->detail['notice'];

        // dd($report);
          
        CertiIBSaveAssessmentBug::where('assessment_id',$assessment->id)
        ->whereNull('owner_id')
        ->delete();

        foreach ($report as $key => $item) {
            $bug = new CertiIBSaveAssessmentBug;
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

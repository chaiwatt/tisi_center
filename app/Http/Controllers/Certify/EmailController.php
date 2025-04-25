<?php

namespace App\Http\Controllers\Certify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use HP;
// CB
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;
use App\Models\Certify\ApplicantCB\CertiCBReport;
// IB
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;
use App\Models\Certify\ApplicantIB\CertiIBReport;

// lab
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\Report;

use Illuminate\Support\Facades\Mail; 
use App\Mail\CB\CBCloseCarMail;
use App\Mail\CB\CBAlertReportMail; 
use App\Mail\IB\IBCloseCarMail;
use App\Mail\IB\IBAlertReportMail;
use App\Mail\LAB\LABCloseCarMail;
use App\Mail\LAB\LABAlertReportMail;
class EmailController extends Controller
{

    public function emails(Request $request){
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
     // ใบรับรองหน่วยรับรอง CB
    $assessment_cb =    CertiCBSaveAssessment::whereNotNull('date_car')->whereNotIn('degree',[7,8])->get();
    if(count($assessment_cb) > 0){ 
        foreach($assessment_cb as $key => $item){
              if(!is_null($item->app_certi_cb_id)){
                  $saveassessment_cb  =  CertiCBSaveAssessment::findOrFail($item->id);
                 // E-Mail ผู้ประกอบการ 
                //  $email = $item->CertiCBCostTo->email ?? null; 
                  // E-Mail ผู้ประกอบการ + ผก.
                $email = $item->CertiCBCostTo->EmailChiefAndOperator ?? []; 
                // แจ้งเตือน  E-mail ผู้ประกอบการ + ผก. หลัง 60 วันที่ปิด Car
                $date_cb60 = date ("Y-m-d", strtotime("+ 60 day", strtotime($item->date_car))) ?? null;  
                if(count($email) > 0 && (!is_null($date_cb60)   && $date_cb60 == date('Y-m-d')) ){
                    $mail_cb60 = new CBCloseCarMail(['name'=> !empty($item->CertiCBCostTo->name) ? $item->CertiCBCostTo->name :'-',          
                                                     'app_no'=> !empty($item->CertiCBCostTo->app_no) ? $item->CertiCBCostTo->app_no :'-',
                                                     'auditor' =>  !empty($item->CertiCBAuditorsTo->auditor) ? $item->CertiCBAuditorsTo->auditor : null,
                                                     'number' => 60,
                                                     'email' => 'admin@admin.com',
                                                     'url' =>  $url.'certify/applicant-cb' 
                                                    ]); 
                    Mail::to($email)->send($mail_cb60);
                    $saveassessment_cb->status_car = 1; // แจ้งเตือน  60 วัน
                    $saveassessment_cb->save();
                }
                 // แจ้งเตือน  E-mail ผู้ประกอบการ + ผก. หลัง 90 วันที่ปิด Car
                $date_cb90 = date ("Y-m-d", strtotime("+ 90 day", strtotime($item->date_car))) ?? null;   
                if(count($email) > 0 && (!is_null($date_cb90)   && $date_cb90 == date('Y-m-d')) ){
                    $date_cb90 = new CBCloseCarMail(['name'=> !empty($item->CertiCBCostTo->name) ? $item->CertiCBCostTo->name :'-',          
                                                     'app_no'=> !empty($item->CertiCBCostTo->app_no) ? $item->CertiCBCostTo->app_no :'-',
                                                     'auditor' =>  !empty($item->CertiCBAuditorsTo->auditor) ? $item->CertiCBAuditorsTo->auditor : null,
                                                     'number' => 90,
                                                     'email' => 'admin@admin.com',
                                                     'url' =>  $url.'certify/applicant-cb' 
                                                    ]); 
                    Mail::to($email)->send($date_cb90);
                    $saveassessment_cb->status_car = 2;  // แจ้งเตือน  90 วัน
                    $saveassessment_cb->save();
                }
              }     
         }
      }
 
      $report_cb =    CertiCBReport::whereNotNull('start_date')->whereNull('status_alert')->get();
      if(count($report_cb) > 0){ 
        foreach($report_cb as $key => $item){
             if(!is_null($item->app_certi_cb_id)){
                 $reportCb  =  CertiCBReport::findOrFail($item->id);
                 $emailCB = $item->CertiCBCostTo->CertiEmailDirectorAndLt ?? [];
                 $tokenCB = $item->CertiCBCostTo->token ?? '';
                // แจ้งเตือน  E-mail ลท. + ผก. หลัง 30  วันที่ยืนยันคำขอรับใบรับรอง 
                $date_cb30 = date ("Y-m-d", strtotime("+ 30 day", strtotime($item->start_date))) ?? null;  
                if(count($emailCB) > 0 && (!is_null($date_cb30)   && $date_cb30 == date('Y-m-d')) ){
                    $mail_cb30 = new CBAlertReportMail(['name'=> !empty($item->CertiCBCostTo->name) ? $item->CertiCBCostTo->name :'-',          
                                                        'app_no'=> !empty($item->CertiCBCostTo->app_no) ? $item->CertiCBCostTo->app_no :'-',
                                                        'number' => 30,
                                                        'email' => 'admin@admin.com',
                                                        'url' => url('/certify/check_certificate-cb').'/'.$tokenCB 
                                                     ]); 
                    Mail::to($emailCB)->send($mail_cb30);
                    $reportCb->status_alert = 1;  // แจ้งเตือน  30 วัน
                    $reportCb->save();
                }
             }     
          }
      }
   

     // ใบรับรองหน่วยตรวจสอบ IB
    $assessment_ib =    CertiIBSaveAssessment::whereNotNull('date_car')->whereNotIn('degree',[7,8])->get();
    if(count($assessment_ib) > 0){ 
        foreach($assessment_ib as $key => $item){
              if(!is_null($item->app_certi_ib_id)){
                    $saveassessment_ib  =  CertiIBSaveAssessment::findOrFail($item->id);
                 // E-Mail ผู้ประกอบการ 
                //  $email = $item->CertiCBCostTo->email ?? null; 
                  // E-Mail ผู้ประกอบการ + ผก.
                $email_ib = $item->CertiIBCostTo->EmailChiefAndOperator ?? []; 
                // แจ้งเตือน  E-mail ผู้ประกอบการ + ผก. หลัง 60 วันที่ปิด Car 
                $date_ib60 = date ("Y-m-d", strtotime("+ 60 day", strtotime($item->date_car))) ?? null;   
                if(count($email_ib) > 0 && (!is_null($date_ib60) && $date_ib60 == date('Y-m-d') ) ){
                    $mail_ib60 = new IBCloseCarMail(['name'=> !empty($item->CertiIBCostTo->name) ? $item->CertiIBCostTo->name :'-',          
                                                     'app_no'=> !empty($item->CertiIBCostTo->app_no) ? $item->CertiIBCostTo->app_no :'-',
                                                     'auditor' =>  !empty($item->CertiIBAuditorsTo->auditor) ? $item->CertiIBAuditorsTo->auditor : null,
                                                     'number' => 60,
                                                     'email' => 'admin@admin.com',
                                                     'url' =>  $url.'certify/applicant-ib' 
                                                    ]);                              
                    Mail::to($email_ib)->send($mail_ib60);
                    $saveassessment_ib->status_car = 1; // แจ้งเตือน  60 วัน
                    $saveassessment_ib->save();
                }
                 // แจ้งเตือน  E-mail ผู้ประกอบการ + ผก. หลัง 90 วันที่ปิด Car
                $date_ib90 = date ("Y-m-d", strtotime("+ 90 day", strtotime($item->date_car))) ?? null;   
                if(count($email_ib) > 0 && (!is_null($date_ib90)   && $date_ib90 == date('Y-m-d')) ){
                    $date_ib90 = new IBCloseCarMail(['name'=> !empty($item->CertiIBCostTo->name) ? $item->CertiIBCostTo->name :'-',          
                                                     'app_no'=> !empty($item->CertiIBCostTo->app_no) ? $item->CertiIBCostTo->app_no :'-',
                                                     'auditor' =>  !empty($item->CertiIBAuditorsTo->auditor) ? $item->CertiIBAuditorsTo->auditor : null,
                                                     'number' => 90,
                                                     'email' => 'admin@admin.com',
                                                     'url' =>  $url.'certify/applicant-ib' 
                                                    ]); 
                    Mail::to($email_ib)->send($date_ib90);
                    $saveassessment_ib->status_car = 2;  // แจ้งเตือน  90 วัน
                    $saveassessment_ib->save();
                }
              }     
         }
      }

      $report_ib =    CertiIBReport::whereNotNull('start_date')->whereNull('status_alert')->get();
      if(count($report_ib) > 0){ 
        foreach($report_ib as $key => $item){
             if(!is_null($item->app_certi_ib_id)){
                 $reportIb  =  CertiIBReport::findOrFail($item->id);
                 $emailIB = $item->CertiIBCostTo->CertiEmailDirectorAndLt ?? [];
                 $tokenIB = $item->CertiIBCostTo->token ?? '';
                // แจ้งเตือน  E-mail ลท. + ผก. หลัง 30  วันที่ยืนยันคำขอรับใบรับรอง 
                $date_ib30 = date ("Y-m-d", strtotime("+ 30 day", strtotime($item->start_date))) ?? null;  
                if(count($emailIB) > 0 && (!is_null($date_ib30)   && $date_ib30 == date('Y-m-d')) ){
                    $mail_cb30 = new IBAlertReportMail(['name'=> !empty($item->CertiIBCostTo->name) ? $item->CertiIBCostTo->name :'-',          
                                                        'app_no'=> !empty($item->CertiIBCostTo->app_no) ? $item->CertiIBCostTo->app_no :'-',
                                                        'number' => 30,
                                                        'email' => 'admin@admin.com',
                                                        'url' => url('/certify/check_certificate-ib').'/'.$tokenIB 
                                                     ]); 
                    Mail::to($emailIB)->send($mail_cb30);
                    $reportIb->status_alert = 1;  // แจ้งเตือน  30 วัน
                    $reportIb->save();
                }
             }     
          }
      }

      
     // ใบรับรองหน่วยรับรอง LAB
     $notice_lab =    Notice::whereNotNull('date_car')->whereNotIn('step',[4])->get();
     if(count($notice_lab) > 0){ 
         foreach($notice_lab as $key => $item){
               if(!is_null($item->app_certi_lab_id)){
                   $Notice  =  Notice::findOrFail($item->id);
                // E-Mail ผู้ประกอบการ + ผก.
                 $email_lab = $item->applicant->EmailChiefAndOperator ?? []; 
                 // แจ้งเตือน  E-mail ผู้ประกอบการ + ผก. หลัง 60 วันที่ปิด Car
                 $date_lab60 = date ("Y-m-d", strtotime("+ 60 day", strtotime($item->date_car))) ?? null;  
                 if(count($email_lab) > 0 && (!is_null($date_lab60)   && $date_lab60 == date('Y-m-d')) ){
                     $mail_lab60 = new LABCloseCarMail(['name'=> !empty($item->applicant->BelongsInformation->name) ? $item->applicant->BelongsInformation->name :null,          
                                                        'app_no'=> !empty($item->applicant->app_no) ? $item->applicant->app_no :null,
                                                        'number' => 60,
                                                        'email' => 'admin@admin.com',
                                                        'url' =>  $url.'certify/applicant' 
                                                      ]); 
                     Mail::to($email_lab)->send($mail_lab60);
                     $Notice->status_car = 1; // แจ้งเตือน  60 วัน
                     $Notice->save();
                 }
                  // แจ้งเตือน  E-mail ผู้ประกอบการ + ผก. หลัง 90 วันที่ปิด Car
                 $date_lab90 = date ("Y-m-d", strtotime("+ 90 day", strtotime($item->date_car))) ?? null;   
                 if(count($email_lab) > 0 && (!is_null($date_lab90)   && $date_lab90 == date('Y-m-d')) ){
                     $date_mail90 = new LABCloseCarMail(['name'=> !empty($item->applicant->BelongsInformation->name) ? $item->applicant->BelongsInformation->name :null,  
                                                        'app_no'=> !empty($item->applicant->app_no) ? $item->applicant->app_no :null,
                                                        'number' => 90,
                                                        'email' => 'admin@admin.com',
                                                        'url' =>  $url.'certify/applicant' 
                                                       ]); 
                     Mail::to($email_lab)->send($date_mail90);
                     $Notice->status_car = 2;  // แจ้งเตือน  90 วัน
                     $Notice->save();
                 }
               }     
          }
       }

       $report_lab =    Report::whereNotNull('start_date')->whereNull('status_alert')->get();
   
       if(count($report_lab) > 0){ 
         foreach($report_lab as $key => $item){
              if(!is_null($item->app_certi_lab_id)){
                  $Report  =  Report::findOrFail($item->id);
                  $email_lab = $item->applicant->CertiEmailDirectorAndLt ?? [];
                  $id = $item->applicant->check->id ?? '';
                 // แจ้งเตือน  E-mail ลท. + ผก. หลัง 30  วันที่ยืนยันคำขอรับใบรับรอง 
                 $date_lab30 = date ("Y-m-d", strtotime("+ 30 day", strtotime($item->start_date))) ?? null;  
                 if(count($email_lab) > 0 && (!is_null($date_lab30)   && $date_lab30 == date('Y-m-d')) ){
                     $mail_lab30 = new LABAlertReportMail(['name'=> !empty($item->applicant->BelongsInformation->name) ? $item->applicant->BelongsInformation->name :null,                  
                                                         'app_no'=> !empty($item->applicant->app_no) ? $item->applicant->app_no :null,
                                                         'number' => 30,
                                                         'email' => 'admin@admin.com',
                                                         'url' => url('/certify/check_certificate').'/'.$id .'/show'
                                                      ]); 
                     Mail::to($email_lab)->send($mail_lab30);
                     $Report->status_alert = 1;  // แจ้งเตือน  30 วัน
                     $Report->save();
                 }
              }     
           }
       }

      return 'END';
  }


}
 
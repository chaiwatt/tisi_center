<?php

namespace App\Http\Controllers\API;

use HP_Law;
use App\Models\Sso\User;

use App\Models\Csurv\Tis4;
use Illuminate\Http\Request;
use App\LawLogSendmailSuccess;
use App\Models\Law\Log\LawNotify;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Law\Listen\LawListenMinistry;
use App\Models\Law\Basic\LawDepartmentStakeholder;
use App\Mail\Mail\Law\ListenMinistry\MailListenMinistry;

class SendMailListenMinisTry extends Controller
{

    public function __construct()
    {

    }


    public function run_send_mail(){    
        
      $successe  = LawLogSendmailSuccess::where('ref_table',(new LawListenMinistry)->getTable())->select('ref_id');//log idที่เคยส่งไปแล้ว

      $lawlistministry_ids = LawListenMinistry::when($successe, function ($query, $successe){
                                                        return $query->whereNotIn('id',$successe);
                                                })->pluck('id');
      if(!empty($lawlistministry_ids) && count($lawlistministry_ids) > 0){

          foreach($lawlistministry_ids as $lawlistministry_id){

              $lawlistministry = LawListenMinistry::where('id', $lawlistministry_id)->first();

                if($lawlistministry->mail_status == 1){//ส่งเมล
                    
                  $mail_notifys    = LawNotify::where('ref_table',(new LawListenMinistry)->getTable())->where('ref_id',$lawlistministry_id)->pluck('email')->toArray();//เมลที่ส่งแล้ว
                  $mail_list       = !empty( $lawlistministry->mail_list )?json_decode($lawlistministry->mail_list,true):[];//เมลทั้งหมดที่ต้องส่ง
                  $mail_diffs      = array_diff($mail_list, $mail_notifys); //หาเมลที่ยังไม่ส่ง
                  $mail_slices     = array_slice($mail_diffs,0,10); //ส่งทีละ 10 เมล
                  // dd($mail_slices);      

                    if(!empty($mail_slices) && count($mail_slices) > 0){
                  
                        if(!empty($lawlistministry->status_dear) && $lawlistministry->status_dear == 1){//กรณีไม่แสดงชื่อหน่วยงาน
                          foreach($mail_slices as $email){
                                  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                                      $url  =  url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
                                      // ข้อมูล
                                      $data_app = [
                                                  'dear'            => $lawlistministry->dear,
                                                  'url'             => $url,
                                                  'lawlistministry' => $lawlistministry,
                                                  'title'           => "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no"
                                              ];
                      
                                  HP_Law::getInsertLawNotifyEmail(3,
                                                                  ((new LawListenMinistry)->getTable()),
                                                                  $lawlistministry->id,
                                                                  'จัดทำแบบรับฟังความเห็นฯ',
                                                                  "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no",
                                                                  view('mail.Law.ListenMinistry.listen_ministry', $data_app),
                                                                  null,  
                                                                  null,   
                                                                  $email,
                                                                  !empty($lawlistministry->updated_by)?$lawlistministry->updated_by:(!empty($lawlistministry->created_by)?$lawlistministry->created_by:'')
                                                                  );
                      
                                  $html = new MailListenMinistry($data_app);
                                  Mail::to($email)->send($html);

                                  }
                              }

                        }else{//กรณีแสดงชื่อหน่วยงาน
                            
                            foreach($mail_slices as $email){
                                if(filter_var($email, FILTER_VALIDATE_EMAIL)){

                                    //ค้นหาชื่อหน่วยงานจาก email
                                    $user_sso =  User::where('email',$email)->select('tax_number');
                                    if(!empty($user_sso)){//ชื่อผู้ได้รับใบอนุญาต
                                        $tb4_tisilicense = Tis4::whereIn('tbl_taxpayer',$user_sso)->first();
                                    }
                                    //ชื่อผู้มีส่วนได้ส่วนเสีย
                                    $department_stakeholder = LawDepartmentStakeholder::where('email',$email)->first();

                                    if(!empty($tb4_tisilicense)){//ชื่อผู้ได้รับใบอนุญาต
                                        $applicanttype_id =  User::where('tax_number',$tb4_tisilicense->tbl_taxpayer)->value('applicanttype_id');
                                        $prefix_name =  (!empty($applicanttype_id) && $applicanttype_id == 1) ? 'กรรมการผู้จัดการ ':'';
                                        $dear = $prefix_name.$tb4_tisilicense->tbl_tradeName;

                                    }else if(!empty($department_stakeholder)){//ชื่อผู้มีส่วนได้ส่วนเสีย
                                        $dear = $department_stakeholder->title;
                                    }else{ 
                                        $dear = 'ผู้มีส่วนได้ส่วนเสีย';
                                    }

                                    $url  =  url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
                                    // ข้อมูล
                                    $data_app = [
                                                'dear'            => $dear,
                                                'url'             => $url,
                                                'lawlistministry' => $lawlistministry,
                                                'title'           => "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no"
                                            ];

                                    HP_Law::getInsertLawNotifyEmail(3,
                                                                    ((new LawListenMinistry)->getTable()),
                                                                    $lawlistministry->id,
                                                                    'จัดทำแบบรับฟังความเห็นฯ',
                                                                    "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no",
                                                                    view('mail.Law.ListenMinistry.listen_ministry', $data_app),
                                                                    null,  
                                                                    null,   
                                                                    $email,
                                                                    !empty($lawlistministry->updated_by)?$lawlistministry->updated_by:(!empty($lawlistministry->created_by)?$lawlistministry->created_by:'')
                                                                    );
              
                                    $html = new MailListenMinistry($data_app);
                                    Mail::to($email)->send($html);
                                    
                                }
                            
                            }

                        }
                    
                    }else{//เก็บ log ไอดีที่เคยส่งแล้ว
                        $log_success =  LawLogSendmailSuccess::where('ref_table',(new LawListenMinistry)->getTable())->where('ref_id',$lawlistministry_id)->first();
                        if(is_null($log_success)){
                          $log_success = new LawLogSendmailSuccess;
                        } 
                        $log_success->ref_table          =  (new LawListenMinistry)->getTable();
                        $log_success->ref_id             =  $lawlistministry_id;
                        $log_success->state              =  1;
                        $log_success->save();

                  }
              }
      
          } 
      } 
    }

}

<?php

namespace App\Http\Controllers\API;

use DB;
use HP;
use App\CertificateExport;
use Illuminate\Http\Request;
use App\Helpers\EpaymentDemo;
use App\Mail\CB\CBInformPayInOne;

use App\Mail\IB\IBInformPayInOne;
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\Mail;
use App\Mail\Tracking\InformPayInOne; 

use App\Models\Law\Cases\LawCasesForm; 
use App\Models\Law\Offense\LawOffender;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Mail\Lab\CertifyConfirmedPayIn1;
use App\Mail\Lab\CertifyCostCertificate;
use App\Models\Certify\EpaymentBillTest;


use App\Models\Certify\TransactionPayIn;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;

use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\Certificate\TrackingReport; 
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Law\Cases\LawCasesPayments; 
use App\Models\Certificate\TrackingHistory; 

use App\Models\Certificate\TrackingPayInOne;
use App\Models\Certificate\TrackingPayInTwo;
use App\Models\Certificate\TrackingAuditors; 
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Law\Offense\LawOffenderProduct;

use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\CostCertificate;


use App\Models\Certify\ApplicantCB\CertiCBExport; 
use App\Models\Certify\ApplicantCB\CertiCbHistory;
use App\Models\Certify\ApplicantIB\CertiIBExport; 
use App\Models\Certify\ApplicantIB\CertiIbHistory;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantIB\CertiIBAuditors;
use App\Models\Certify\ApplicantIB\CertiIBPayInOne;
use App\Models\Certify\ApplicantIB\CertiIBPayInTwo;
use App\Models\Certify\ApplicantCB\CertiCBPayInOne; 
use App\Models\Certify\ApplicantCB\CertiCBPayInTwo; 


class Checkbill2Controller extends Controller
{
    public function check_bill(Request $request)
    {
      
        $context = stream_context_create(array( 
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
                ),
            'http' => array( 
                'timeout' => 30 
                ) 
            ) 
        );

        // dd($request->ref1);

        $message    = false;
        $response   =   new TransactionPayIn;
       
        if(!empty($request->ref1)){  // เฉพาะตามคำขอ
            $ref1  = $request->ref1;
            $transaction =  TransactionPayIn::Where(DB::raw("REPLACE(ref1,'-','')"),str_replace('-', '', $ref1))->orderby('id','desc')->first();
            
            if(!is_null($transaction)){

                if($transaction->certify == 1 && $transaction->state == 1){ //  pay in ครั้งที่ 1 lab
                    $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',2)->first();
                }else   if($transaction->certify == 1 && $transaction->state == 2){ //  pay in ครั้งที่ 2 lab
                    $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',2)->where('type',2)->first();
                }else   if($transaction->certify == 2 && $transaction->state == 1){ //  pay in ครั้งที่ 1 IB
                    $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',1)->where('type',2)->first();
                }else   if($transaction->certify == 2 && $transaction->state == 2){ //  pay in ครั้งที่ 2 IB
                    $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',2)->where('type',2)->first();
                }else   if($transaction->certify == 3 && $transaction->state == 1){ //  pay in ครั้งที่ 1 CB
                    $setting_payment = CertiSettingPayment::where('certify',3)->where('payin',1)->where('type',2)->first();
                }else   if($transaction->certify == 3 && $transaction->state == 2){ //  pay in ครั้งที่ 2 CB
                    $setting_payment = CertiSettingPayment::where('certify',3)->where('payin',2)->where('type',2)->first();
                }else   if($transaction->certify == 4 && $transaction->state == 1){ //  pay in ครั้งที่ 1 lab (ติดตามใบรับรอง)
                    $setting_payment = CertiSettingPayment::where('certify',4)->where('payin',1)->where('type',2)->first();
                }else   if($transaction->certify == 4 && $transaction->state == 2){ //  pay in ครั้งที่ 2 lab (ติดตามใบรับรอง)
                    $setting_payment = CertiSettingPayment::where('certify',4)->where('payin',2)->where('type',2)->first();
                }else   if($transaction->certify == 5 && $transaction->state == 1){ //  pay in ครั้งที่ 1 IB (ติดตามใบรับรอง)
                    $setting_payment = CertiSettingPayment::where('certify',5)->where('payin',1)->where('type',2)->first();
                }else   if($transaction->certify == 5 && $transaction->state == 2){ //  pay in ครั้งที่ 2 IB (ติดตามใบรับรอง)
                    $setting_payment = CertiSettingPayment::where('certify',5)->where('payin',2)->where('type',2)->first();
                }else   if($transaction->certify == 6 && $transaction->state == 1){ //  pay in ครั้งที่ 1 CB (ติดตามใบรับรอง)
                    $setting_payment = CertiSettingPayment::where('certify',6)->where('payin',1)->where('type',2)->first();
                }else   if($transaction->certify == 6 && $transaction->state == 2){ //  pay in ครั้งที่ 2 CB (ติดตามใบรับรอง)
                    $setting_payment = CertiSettingPayment::where('certify',6)->where('payin',2)->where('type',2)->first();
                }

            

                if(!is_null($setting_payment)){
                    $refNo = $transaction->ref1;
                    if($transaction->suffix !== null){
                        if($transaction->state == 1){
                            $refNo = $transaction->ref1.$transaction->suffix;
                        }else if($transaction->state == 2)
                        {
                            $refNo = $transaction->ref1.'-'.$transaction->suffix;
                        }
                        
                    }

                    // $epaymentDemoController = new EpaymentDemo();
                    // $epaymentDemoController->pmt2($refNo,null);

                    // $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&ref1=$transaction->ref1", false, $context);
                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&ref1=$refNo", false, $context);
                    $api = json_decode($content,true);      
                    // dd("$setting_payment->data?pid=$setting_payment->pid&out=json&ref1=$refNo",$api);
                    if(!empty($api[0]['error'])){
                        // echo 'ยังไม่มีข้อมูลการชำระ'   ;
                    }elseif(!empty($api[0]['PayList']) && count((array)$api[0]['PayList']) > 0){

                        $PayList =   $api[0]['PayList'][0]; 
                        if($PayList['Status'] == 1){
                            $message = true;
            
                            $PayList['BillCreateDate']          =   !empty($PayList['BillCreateDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['BillCreateDate'])): null;  
                            $PayList['PaymentDate']             =   !empty($PayList['PaymentDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['PaymentDate'])): null;    
                            $PayList['ReceiptCreateDate']       =   !empty($PayList['ReceiptCreateDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['ReceiptCreateDate'])): null;    
                            $PayList['ReconcileDate']           =   !empty($PayList['ReconcileDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['ReconcileDate'])): null;  
                            $PayList['PayAmountBill']           =   !empty($PayList['Amount']) ? str_replace(",","", $PayList['Amount']): null; 
                            $PayList['status_confirmed']        =  1; 
                            unset($PayList['Amount']);
                            unset($PayList['Ref1']);

                            if($transaction->status_confirmed != 1){  
                                $transaction->update($PayList);   

                                //     if($transaction->state == 1 &&  $transaction->table_name ==  (new CostAssessment)->getTable()){
                                //         self::update_status_lab1($transaction->ref_id); // pay in ครั้งที่ 1 lab
                                //     }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CostCertificate)->getTable()){
                                //         self::update_status_lab2($transaction->ref_id); // pay in ครั้งที่ 2 lab
                                //     }else   if($transaction->state == 1 &&  $transaction->table_name ==  (new CertiIBPayInOne)->getTable()){
                                //         self::update_status_ib1($transaction->ref_id); // pay in ครั้งที่ 1 ib
                                //     }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CertiIBPayInTwo)->getTable()){
                                //         self::update_status_ib2($transaction->ref_id); // pay in ครั้งที่ 2 ib
                                //     }else   if($transaction->state == 1 &&  $transaction->table_name ==  (new CertiCBPayInOne)->getTable()){
                                //         self::update_status_cb1($transaction->ref_id); // pay in ครั้งที่ 1 cb
                                //     }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CertiCBPayInTwo)->getTable()){
                                //         self::update_status_cb2($transaction->ref_id); // pay in ครั้งที่ 2 cb
                                //     }else   if($transaction->certify == 4 && $transaction->state == 1){ //  pay in ครั้งที่ 1 lab (ติดตามใบรับรอง)
                                //         self::update_tracking_pay_in1($transaction->ref_id,3,(new CertificateExport)->getTable());  
                                //    }else   if($transaction->certify == 4 && $transaction->state == 2){ //  pay in ครั้งที่ 2 lab (ติดตามใบรับรอง)
                                //        self::update_tracking_pay_in2($transaction->ref_id,3,(new CertificateExport)->getTable());  
                                //    }else   if($transaction->certify == 5 && $transaction->state == 1){ //  pay in ครั้งที่ 1 IB (ติดตามใบรับรอง)
                                //         self::update_tracking_pay_in1($transaction->ref_id,2,(new CertiIBExport)->getTable()); 
                                //    }else   if($transaction->certify == 5 && $transaction->state == 2){ //  pay in ครั้งที่ 2 IB (ติดตามใบรับรอง)
                                //        self::update_tracking_pay_in2($transaction->ref_id,2,(new CertiIBExport)->getTable()); 
                                //    }else   if($transaction->certify == 6 && $transaction->state == 1){ //  pay in ครั้งที่ 1 CB (ติดตามใบรับรอง)
                                //        self::update_tracking_pay_in1($transaction->ref_id,1,(new CertiCBExport)->getTable()); 
                                //    }else   if($transaction->certify == 6 && $transaction->state == 2){ //  pay in ครั้งที่ 2 CB (ติดตามใบรับรอง)
                                //        self::update_tracking_pay_in2($transaction->ref_id,1,(new CertiCBExport)->getTable()); 
                                //    }

                            }

                            $transaction->status = 1; 
                            $transaction->ReceiptCode             =   !empty($transaction->ReceiptCode) ?  $transaction->ReceiptCode : (!empty($transaction->InvoiceCode) ?  $transaction->InvoiceCode : null); 
                            $transaction->receipt_create_date_th =   !empty($transaction->ReceiptCreateDate) ?  HP::DateTimeThai($transaction->ReceiptCreateDate) : (!empty($transaction->PaymentDate) ?  HP::DateTimeThai($transaction->PaymentDate) : null); 
                            $response =  $transaction;
                        }else{
                            $transaction->status = 0; 
                            $response =  $transaction;
                        }
                    } 
                }    
            }
            return response()->json(['message' => $message ,'response'=> $response]);
         

        }else  if(!empty($request->pid) && !empty($request->paydate)){ 
                $pid      = $request->pid;
                $paydate  = $request->paydate;

                if($pid == 8){ // pay in ครั้งที่ 1 lab
                    $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',2)->first();
                }else if($pid == 10){ // pay in ครั้งที่ cb 
                    $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',1)->where('type',2)->first();
                }else if($pid == 9){  // pay in ครั้งที่ 1 lab
                    $setting_payment =  CertiSettingPayment::where('certify',3)->where('payin',1)->where('type',2)->first();
                }else if($pid == 5){  // pay in ครั้งที่ 2 lab
                    $setting_payment =  CertiSettingPayment::where('certify',1)->where('payin',2)->where('type',2)->first();
                }else if($pid == 4){  //  pay in ครั้งที่ 2 ib and cb
                    $setting_payment =  CertiSettingPayment::whereIn('certify',[2,3])->where('payin',2)->where('type',2)->first();
                }
                
              $message = false;
              $response = [];
              $data_key = [];
              $data = [];
              
              $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&paydate=$paydate", false, $context);
              $api = json_decode($content,true);
           
              if(!empty($api[0]['error'])){
                    // echo 'ยังไม่มีข้อมูลการชำระ'   ;
               }elseif(!empty($api[0]['PayList']) && count((array)$api[0]['PayList']) > 0){
                                $data = [];
                        foreach($api[0]['PayList'] as $item){
                                 $transaction =     TransactionPayIn::Where(DB::raw("REPLACE(ref1,'-','')"),str_replace('-', '', $item['Ref1']))->first();
                                if(!is_null($transaction)){
                                    if($item['Status'] == 1){
                            
                                        $item['CGDRef1']                 =   !empty($item['CGDRef1'])  ?  $item['CGDRef1']: null;  
                                        $item['BillCreateDate']          =   !empty($item['BillCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['BillCreateDate'])): null;  
                                        $item['PaymentDate']             =   !empty($item['PaymentDate']) ? date("Y-m-d H:i:s",strtotime( $item['PaymentDate'])): null;    
                                        $item['ReceiptCreateDate']       =   !empty($item['ReceiptCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReceiptCreateDate'])): null;    
                                        $item['ReconcileDate']           =   !empty($item['ReconcileDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReconcileDate'])): null;  
                                        $item['PayAmountBill']           =   !empty($item['Amount']) ? str_replace(",","", $item['Amount']): null; 
                                        $item['status_confirmed']        =  1; 
                                        unset($item['Amount']);
                                        unset($item['Ref1']);

                                        if($transaction->status_confirmed != 1){  

                                            $transaction->update($item);    

                                            if($transaction->state == 1 &&  $transaction->table_name ==  (new CostAssessment)->getTable()){
                                                self::update_status_lab1($transaction->ref_id); // pay in ครั้งที่ 1 lab
                                            }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CostCertificate)->getTable()){
                                                self::update_status_lab2($transaction->ref_id); // pay in ครั้งที่ 2 lab
                                            }else   if($transaction->state == 1 &&  $transaction->table_name ==  (new CertiIBPayInOne)->getTable()){
                                                self::update_status_ib1($transaction->ref_id); // pay in ครั้งที่ 1 ib
                                            }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CertiIBPayInTwo)->getTable()){
                                                self::update_status_ib2($transaction->ref_id); // pay in ครั้งที่ 2 ib
                                            }else   if($transaction->state == 1 &&  $transaction->table_name ==  (new CertiCBPayInOne)->getTable()){
                                                self::update_status_cb1($transaction->ref_id); // pay in ครั้งที่ 1 cb
                                            }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CertiCBPayInTwo)->getTable()){
                                                self::update_status_cb2($transaction->ref_id); // pay in ครั้งที่ 2 cb
                                            }else   if($transaction->certify == 4 && $transaction->state == 1){ //  pay in ครั้งที่ 1 lab (ติดตามใบรับรอง)
                                                self::update_tracking_pay_in1($transaction->ref_id,3,(new CertificateExport)->getTable());  
                                           }else   if($transaction->certify == 4 && $transaction->state == 2){ //  pay in ครั้งที่ 2 lab (ติดตามใบรับรอง)
                                               self::update_tracking_pay_in2($transaction->ref_id,3,(new CertificateExport)->getTable());  
                                           }else   if($transaction->certify == 5 && $transaction->state == 1){ //  pay in ครั้งที่ 1 IB (ติดตามใบรับรอง)
                                                self::update_tracking_pay_in1($transaction->ref_id,2,(new CertiIBExport)->getTable()); 
                                           }else   if($transaction->certify == 5 && $transaction->state == 2){ //  pay in ครั้งที่ 2 IB (ติดตามใบรับรอง)
                                               self::update_tracking_pay_in2($transaction->ref_id,2,(new CertiIBExport)->getTable()); 
                                           }else   if($transaction->certify == 6 && $transaction->state == 1){ //  pay in ครั้งที่ 1 CB (ติดตามใบรับรอง)
                                               self::update_tracking_pay_in1($transaction->ref_id,1,(new CertiCBExport)->getTable()); 
                                           }else   if($transaction->certify == 6 && $transaction->state == 2){ //  pay in ครั้งที่ 2 CB (ติดตามใบรับรอง)
                                               self::update_tracking_pay_in2($transaction->ref_id,1,(new CertiCBExport)->getTable()); 
                                           }
                                        }  
                                
                                        $transaction->status = 1; 
 
                                    }else{
                                        $transaction->status = 0; 
                                    }
                                    if(!array_key_exists($transaction->ref1,$data_key)){
                                        $data[]                         = $transaction;
                                        $data_key[$transaction->ref1]   = $transaction;
                                    }
                                }
                        }
                        if(count($data) > 0){
                            $message = true;
                        }else{
                            $message = false;
                        }
                        $response =  $data;
              } 
              return response()->json(['message' => $message ,'response'=> $response]);

        }else{

            $setting_payment = CertiSettingPayment::select('data','pid')->whereIn('certify',[1,2,3,4,5,6])->where('type',2) ->groupBy('pid')->orderBy('id','asc')->get();

            $paydates = array(date('Y-m-d'),HP::DatePlusBack(date('Y-m-d'),1));

            $message = false;
            $response = [];
            $data_key = [];
            $data = [];
            foreach($paydates as $paydate){
                if(count($setting_payment) > 0){
                    foreach($setting_payment as $payment){
               
                        $content =  file_get_contents("$payment->data?pid=$payment->pid&out=json&paydate=$paydate", false, $context);
    
                        $api = json_decode($content,true);
                      
                        if(!empty($api[0]['error'])){
                            // echo 'ยังไม่มีข้อมูลการชำระ'   ;
                        }elseif(!empty($api[0]['PayList']) && count((array)$api[0]['PayList']) > 0){
                                foreach($api[0]['PayList'] as $item){
                              
                                        $transaction =     TransactionPayIn::Where(DB::raw("REPLACE(ref1,'-','')"),str_replace('-', '',  $item['Ref1']))->orderby('id','desc')->first();
                                        if(!is_null($transaction)){
                                            if($item['Status'] == 1){
                                                $item['BillCreateDate']          =   !empty($item['BillCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['BillCreateDate'])): null;  
                                                $item['PaymentDate']             =   !empty($item['PaymentDate']) ? date("Y-m-d H:i:s",strtotime( $item['PaymentDate'])): null;    
                                                $item['ReceiptCreateDate']       =   !empty($item['ReceiptCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReceiptCreateDate'])): null;    
                                                $item['ReconcileDate']           =   !empty($item['ReconcileDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReconcileDate'])): null;  
                                                $item['PayAmountBill']           =   !empty($item['Amount']) ? str_replace(",","", $item['Amount']): null; 
                                                $item['status_confirmed']        =  1; 
                                                unset($item['Amount']);
                                                unset($item['Ref1']);
    
                                                if($transaction->status_confirmed != 1){  
                                                    $transaction->update($item);    
                                                }
                                             
                                                $transaction->status = 1; 
                                             
                                                if($transaction->state == 1 &&  $transaction->table_name ==  (new CostAssessment)->getTable()){
                                                    self::update_status_lab1($transaction->ref_id); // pay in ครั้งที่ 1 lab
                                                }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CostCertificate)->getTable()){
                                                    self::update_status_lab2($transaction->ref_id); // pay in ครั้งที่ 2 lab
                                                }else   if($transaction->state == 1 &&  $transaction->table_name ==  (new CertiIBPayInOne)->getTable()){
                                                    self::update_status_ib1($transaction->ref_id); // pay in ครั้งที่ 1 ib
                                                }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CertiIBPayInTwo)->getTable()){
                                                    self::update_status_ib2($transaction->ref_id); // pay in ครั้งที่ 2 ib
                                                }else   if($transaction->state == 1 &&  $transaction->table_name ==  (new CertiCBPayInOne)->getTable()){
                                                    self::update_status_cb1($transaction->ref_id); // pay in ครั้งที่ 1 cb
                                                }else   if($transaction->state == 2 &&  $transaction->table_name ==  (new CertiCBPayInTwo)->getTable()){
                                                    self::update_status_cb2($transaction->ref_id); // pay in ครั้งที่ 2 cb
                                                }else   if($transaction->certify == 4 && $transaction->state == 1){ //  pay in ครั้งที่ 1 lab (ติดตามใบรับรอง)
                                                    self::update_tracking_pay_in1($transaction->ref_id,3,(new CertificateExport)->getTable());  
                                               }else   if($transaction->certify == 4 && $transaction->state == 2){ //  pay in ครั้งที่ 2 lab (ติดตามใบรับรอง)
                                                   self::update_tracking_pay_in2($transaction->ref_id,3,(new CertificateExport)->getTable());  
                                               }else   if($transaction->certify == 5 && $transaction->state == 1){ //  pay in ครั้งที่ 1 IB (ติดตามใบรับรอง)
                                                    self::update_tracking_pay_in1($transaction->ref_id,2,(new CertiIBExport)->getTable()); 
                                               }else   if($transaction->certify == 5 && $transaction->state == 2){ //  pay in ครั้งที่ 2 IB (ติดตามใบรับรอง)
                                                   self::update_tracking_pay_in2($transaction->ref_id,2,(new CertiIBExport)->getTable()); 
                                               }else   if($transaction->certify == 6 && $transaction->state == 1){ //  pay in ครั้งที่ 1 CB (ติดตามใบรับรอง)
                                                   self::update_tracking_pay_in1($transaction->ref_id,1,(new CertiCBExport)->getTable()); 
                                               }else   if($transaction->certify == 6 && $transaction->state == 2){ //  pay in ครั้งที่ 2 CB (ติดตามใบรับรอง)
                                                   self::update_tracking_pay_in2($transaction->ref_id,1,(new CertiCBExport)->getTable()); 
                                               }
                                            }else{
                                                $transaction->status = 0; 
                                            }
                                                if(!array_key_exists($transaction->ref1,$data_key)){
                                                    $data[]                         = $transaction;
                                                    $data_key[$transaction->ref1]   = $transaction;
                                                }
                                           
                                        }
                                }
                        } 
    
                    }
                 }

             }
  
    
            if(count($data) > 0){
                $message = true;
            }else{
                $message = false;
            }
            $response =  $data;

            return response()->json(['message' => $message ,'response'=> $response]);
        }
      
    }   


    
    public function update_status_lab1($id){ // pay in ครั้งที่ 1 lab
        $find_cost_assessment  =  CostAssessment::findOrFail($id);  //  ตารางธรรรมเนียม
 
        if(!is_null($find_cost_assessment) && $find_cost_assessment->status_confirmed != 1){
 
            $find_cost_assessment->remark           =  null;
            $find_cost_assessment->state            = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
            $find_cost_assessment->status_confirmed = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
            $find_cost_assessment->save();

            if(!empty($find_cost_assessment->assessment->auditor_id)){
                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = BoardAuditor::findOrFail($find_cost_assessment->assessment->auditor_id);
                if(!is_null($auditor)){
                    if($find_cost_assessment->state == 3){
                        $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                    }else{
                        $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                    }
                    $auditor->save();
                }
            }
          
            $certi_lab = $find_cost_assessment->applicant;
 
            if(!is_null($certi_lab)){
                $cost = CostAssessment::select('amount','report_date','app_certi_assessment_id','remark','conditional_type', 'start_date_feewaiver', 'end_date_feewaiver','detail','status_confirmed')->where('id',$find_cost_assessment->id)->get()->toArray();
                $ao = new CostAssessment;
                $History = CertificateHistory::create([
                                               'app_no'            =>  $certi_lab->app_no ?? null,
                                               'system'            =>  3,
                                               'table_name'        =>  $ao->getTable(),
                                               'status'            =>  $find_cost_assessment->status_confirmed ?? null,
                                               'ref_id'            =>  $find_cost_assessment->id,
                                               'details'           =>  (count($cost) > 0) ? json_encode($cost) : null,
                                               'attachs'           =>  $find_cost_assessment->amount_invoice ?? null,
                                               'attach_client_name'=>  $find_cost_assessment->file_client_name ?? null,
                                               'attachs_file'      =>  $find_cost_assessment->invoice ?? null,
                                               'evidence'          =>  $find_cost_assessment->invoice_client_name ?? null
                                             ]);
    
         
                
                         if(isset($certi_lab->check) && count($certi_lab->check->EmailStaffAssign) > 0 ){ // แจ้งเตือนเจ้าหน้าที่รับผิดชอบ

                                 $data_app = [
                                                'email'         =>  'nsc@tisi.mail.go.th',
                                                'PayIn'         => $find_cost_assessment,
                                                'certi_lab'     => $certi_lab,
                                                'email'         => !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : null,
                                                'email_cc'      => !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  [],
                                                'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  []
                                            ];
                              
                            // $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                                $log_email =  HP::getInsertCertifyLogEmail( $certi_lab->app_no,
                                                                            $certi_lab->id,
                                                                            (new CertiLab)->getTable(),
                                                                            $find_cost_assessment->id,
                                                                            (new CostAssessment)->getTable(),
                                                                            1,
                                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                                            view('mail.Lab.inform_pay_in_one', $data_app),
                                                                            $certi_lab->created_by,
                                                                            $certi_lab->agent_id,
                                                                            null,
                                                                            'nsc@tisi.mail.go.th',
                                                                            !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter :  null,
                                                                            !empty($certi_lab->DataEmailDirectorLABCC) ? implode(',',(array)$certi_lab->DataEmailDirectorLABCC)   :  null,
                                                                            !empty($certi_lab->DataEmailDirectorLABReply) ? implode(',',(array)$certi_lab->DataEmailDirectorLABReply)   :   null,
                                                                            null
                                                                        );

                                $html = new CertifyConfirmedPayIn1($data_app);
                                $mail =  Mail::to($certi_lab->check->EmailStaffAssign)->send($html);
                                if(is_null($mail) && !empty($log_email)){
                                    HP::getUpdateCertifyLogEmail($log_email->id);
                                }
                    
                        }
                       
            }

        }
    }

    public function update_status_lab2($id){
        $costcerti =   CostCertificate::findOrFail($id);
        if(!is_null($costcerti) && $costcerti->status_confirmed != 1){

              $CertiLab = CertiLab::where('id',$costcerti->app_certi_lab_id)->first();
            if(!is_null($CertiLab) && $CertiLab->status < 25){

                $attach         =  $costcerti->attach ?? null ;
                $invoice        =  $costcerti->invoice ?? null ;

                $costcerti->detail           = null;  
                $costcerti->status_confirmed = 1; 
                $costcerti->save();
      
                // $CertiLab->status = 17; 
                
                $CertiLab->status = 25; 
                $CertiLab->save();

                $ao = new CostCertificate;
                $detail_costcerti =   CostCertificate::select('amount','amount_fee','notification_date','detail','start_date_feewaiver','end_date_feewaiver','remark','conditional_type')
                                               ->where('id',$costcerti->id)
                                               ->orderby('id','desc')
                                               ->first();
                CertificateHistory::create([
                                           'app_no'=> $CertiLab->app_no ?? null,
                                           'system'=> 6, // Pay-In ครั้งที่ 2
                                           'table_name'=> $ao->getTable(),
                                           'ref_id'=> $costcerti->id,
                                           'details'=>  json_encode($detail_costcerti) ?? null,
                                           'status'=> $request->status_confirmed ?? null,
                                           'attachs'=>$attach ?? null,
                                           'attachs_file'=> $invoice ?? null
                                        ]);

             }
         }
    }

    public function update_status_ib1($id){ 
        $PayIn = CertiIBPayInOne::findOrFail($id);
        if(!is_null($PayIn) && $PayIn->status  != 1){
            $PayIn->remark =  null;
            $PayIn->state = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
            $PayIn->status = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
            $PayIn->save();

                // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiIBAuditors::findOrFail($PayIn->auditors_id);
             if(!is_null($auditor)){
                if($PayIn->state == 3){
                    $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                }else{
                    $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                }
                   $auditor->save();
             }


             $data = CertiIBPayInOne::select('conditional_type','detail', 'start_date_feewaiver', 'end_date_feewaiver', 'id', 'auditors_id') ->where('id',$id)->first();
                CertiIbHistory::create([
                                        'app_certi_ib_id'   =>  $PayIn->app_certi_ib_id ?? null,
                                        'auditors_id'       =>  $PayIn->auditors_id ?? null,
                                        'system'            => 6, //Pay-In ครั้งที่ 1
                                        'table_name'        => $tb->getTable(),
                                        'ref_id'            => $PayIn->id,
                                        'status'            => $PayIn->status ?? null,
                                        'details_one'       => $PayIn->amount ?? null,
                                        'details_two'       => $PayIn->start_date ?? null,
                                        'details_three'     => $PayIn->CertiIBAuditorsTo->auditor ?? null,
                                        'details_four'      => $PayIn->remark ?? null,
                                        'details_five'      =>  json_encode($data) ?? null,
                                        'attachs'           => $PayIn->FileAttachPayInOne1To->file ?? null,
                                        'attach_client_name'=> $PayIn->FileAttachPayInOne1To->file_client_name ?? null,
                                        'attachs_file'      => $PayIn->FileAttachPayInOne2To->file ?? null,
                                        'evidence'          => $PayIn->FileAttachPayInOne2To->file_client_name ?? null
                                      ]);

            $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);
            if(!empty($certi_ib->EmailStaffAssign) && count($certi_ib->EmailStaffAssign) > 0 ){ // แจ้งเตือนเจ้าหน้าที่รับผิดชอบ
                $data_app =[
                            'PayIn'        => $PayIn,
                            'certi_ib'     => $certi_ib,
                            'url'          =>  url('/certify/check_certificate-ib/' . $certi_ib->token) ,
                            'email'        =>  !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                            'email_cc'     =>  !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? $certi_ib->DataEmailDirectorAndLtIBCC : [],
                            'email_reply'  => !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply :  []
                            ];
                
 
                $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                            $certi_ib->id,
                                                            (new CertiIb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiIBPayInOne)->getTable(),
                                                             2,
                                                            'แจ้งค่าบริการในการตรวจประเมิน',
                                                            view('mail.IB.inform_pay_in_one', $data_app),
                                                            $certi_ib->created_by,
                                                            $certi_ib->agent_id,
                                                            null,
                                                            !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                            implode(',',(array)$certi_ib->EmailStaffAssign),
                                                            !empty($certi_ib->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorAndLtIBCC)   :  null,
                                                            !empty($certi_ib->DataEmailDirectorIBReply) ? implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   null,
                                                            null
                                                        );

                $html = new IBInformPayInOne($data_app);
                $mail =  Mail::to($certi_lab->EmailStaff)->send($html);
        
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
    
          }

               
 
        }

    }

    public function update_status_ib2($id){
          $PayIn = CertiIBPayInTwo::findOrFail($id);
        if(!is_null($PayIn) && $PayIn->status  != 1){
            $certi_ib = CertiIb::findOrFail($PayIn->app_certi_ib_id);
            if(!is_null($certi_ib)){

            $PayIn->degree = 3;
            $PayIn->status = 1;
            $PayIn->save();

            $certi_ib->status = 17;
            $certi_ib->save();

            $data = CertiIBPayInTwo::select('report_date', 'amount','amount_fixed', 'amount_fee', 'degree','status','conditional_type','remark','start_date_feewaiver','end_date_feewaiver')->where('id',$PayIn->id)->first();
            CertiIbHistory::create([
                                    'app_certi_ib_id'       => $PayIn->app_certi_ib_id ?? null,
                                    'system'                => 10,
                                    'table_name'            => $tb->getTable(),
                                    'ref_id'                => $PayIn->id,
                                    'status'                => $PayIn->status ?? null,
                                    'details_one'           =>  json_encode($data) ?? null,
                                    'attachs'               =>  !empty($PayIn->FileAttachPayInTwo1To->file) ? $PayIn->FileAttachPayInTwo1To->file : null,
                                    'attach_client_name'    =>   !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name : null,
                                    'attachs_file'          =>  !empty($PayIn->FileAttachPayInTwo2To->file) ? $PayIn->FileAttachPayInTwo2To->file : null,
                                    'evidence'              =>   !empty($PayIn->FileAttachPayInTwo2To->file_client_name) ? $PayIn->FileAttachPayInTwo2To->file_client_name : null
                                ]);
            }
        }
    }

    public function update_status_cb1($id){
        $tb = new CertiCBPayInOne;
        $PayIn = CertiCBPayInOne::findOrFail($id);
        if(!is_null($PayIn) && $PayIn->status  != 1){
  
            $PayIn->remark =  null;
            $PayIn->state = 3;  //  ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
            $PayIn->status = 1; //ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว
            $PayIn->save();

           // สถานะ แต่งตั้งคณะกรรมการ
                $auditor = CertiCBAuditors::findOrFail($PayIn->auditors_id);
             if(!is_null($auditor)){
                if($PayIn->state == 3){
                    $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
                }else{
                    $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
                }
                   $auditor->save();
             }
                 // LOG
                $data = CertiCBPayInOne::select('conditional_type','detail', 'start_date_feewaiver', 'end_date_feewaiver', 'id','auditors_id')
                                        ->where('id',$id)
                                        ->first();
              CertiCbHistory::create([
                                        'app_certi_cb_id'   =>  $PayIn->app_certi_cb_id ?? null,
                                        'auditors_id'       =>  $PayIn->auditors_id ?? null,
                                        'system'            => 6, //Pay-In ครั้งที่ 1
                                        'table_name'        => $tb->getTable(),
                                        'ref_id'            => $PayIn->id,
                                        'status'            => $PayIn->status ?? null,
                                        'details_one'       => $PayIn->amount ?? null,
                                        'details_two'       => $PayIn->start_date ?? null,
                                        'details_three'     => $PayIn->CertiIBAuditorsTo->auditor ?? null,
                                        'details_four'      => $PayIn->remark ?? null,
                                        'details_five'      =>  json_encode($data) ?? null,
                                        'attachs'           => $PayIn->FileAttachPayInOne1To->file ?? null,
                                        'attach_client_name'=> $PayIn->FileAttachPayInOne1To->file_client_name ?? null,
                                        'attachs_file'      => $PayIn->FileAttachPayInOne2To->file ?? null,
                                        'evidence'          => $PayIn->FileAttachPayInOne2To->file_client_name ?? null 
                                    ]);
          
  

              $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);
            if(!empty($certi_cb->EmailStaffAssign) && count($certi_cb->EmailStaffAssign) > 0 ){ // แจ้งเตือนเจ้าหน้าที่รับผิดชอบ
                $data_app = [
                                'PayIn'        => $PayIn,
                                'certi_cb'     => $certi_cb,
                                'url'          =>  url('/certify/check_certificate-cb/' . $certi_cb->token) ,
                                'email'        =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                'email_cc'     =>  !empty($certi_cb->DataEmailDirectorAndLtIBCC) ? $certi_cb->DataEmailDirectorAndLtIBCC :  [],
                                'email_reply'  => !empty($certi_cb->DataEmailDirectorIBReply) ? $certi_cb->DataEmailDirectorIBReply :  []
                             ];
                
 
                $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no.'-'.@$PayIn->auditors_id,
                                                            $certi_cb->id,
                                                            (new CertiCb)->getTable(),
                                                            $PayIn->id,
                                                            (new CertiCBPayInOne)->getTable(),
                                                            3,
                                                            'ใบรับรองหน่วยรับรอง',
                                                            view('mail.CB.inform_pay_in_one', $data_app),
                                                            $certi_cb->created_by,
                                                            $certi_cb->agent_id,
                                                            null,
                                                            !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                            implode(',',(array)$certi_cb->EmailStaffAssign),
                                                            !empty($certi_cb->DataEmailDirectorAndLtIBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorAndLtIBCC)   :  null,
                                                            !empty($certi_cb->DataEmailDirectorIBReply) ? implode(',',(array)$certi_cb->DataEmailDirectorIBReply)   :   null,
                                                            null
                                                        );

                $html = new CBInformPayInOne($data_app);
                $mail =  Mail::to($certi_cb->EmailStaffAssign)->send($html);
        
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
    
          }




        }
        
    }

    public function update_status_cb2($id){
        $PayIn = CertiCBPayInTwo::findOrFail($id);
        if(!is_null($PayIn) && $PayIn->status  != 1){
            $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);
            if(!is_null($certi_cb)){

                $PayIn->degree = 3;
                $PayIn->report_date = @$PayIn->report_date ?? null;
                $PayIn->status = 1;
                $PayIn->save();

                $certi_cb->status = 17;
                $certi_cb->save();

                $data = CertiCBPayInTwo::select('report_date', 'amount', 'amount_fee', 'degree','status','detail')
                                ->where('id',$id)
                                ->first();
                CertiCbHistory::create([
                                    'app_certi_cb_id'   => $PayIn->app_certi_cb_id ?? null,
                                    'system'            => 10,
                                    'table_name'        => $tb->getTable(),
                                    'ref_id'            => $PayIn->id,
                                    'status'            => $PayIn->status ?? null,
                                    'details_one'       =>  json_encode($data) ?? null,
                                    'attachs'           =>  !empty($PayIn->FileAttachPayInTwo1To->file) ? $PayIn->FileAttachPayInTwo1To->file : null,
                                    'attach_client_name'=>   !empty($PayIn->FileAttachPayInTwo1To->file_client_name) ? $PayIn->FileAttachPayInTwo1To->file_client_name : null,
                                    'attachs_file'      =>  !empty($PayIn->FileAttachPayInTwo2To->file) ? $PayIn->FileAttachPayInTwo2To->file : null,
                                    'evidence'          =>   !empty($PayIn->FileAttachPayInTwo2To->file_client_name) ? $PayIn->FileAttachPayInTwo2To->file_client_name : null
                                    ]);
            }
        }
    } 


    public static function update_tracking_pay_in1($id,$certificate_type = null,$ref_table = null){
    
        $pay_in = TrackingPayInOne::findOrFail($id);
        
    if(!is_null($pay_in) && $pay_in->status != 1 ){

        $pay_in->remark =  null;
        $pay_in->state = 3;  
        $pay_in->status = 1; 
        $pay_in->save();


        $assessment  =  TrackingAssessment::where('auditors_id',$pay_in->auditors_id)->first();
        if(is_null($assessment)){
            $assessment = new TrackingAssessment;
        }
        $assessment->tracking_id        = $pay_in->tracking_id ?? null;
        $assessment->certificate_type   = $certificate_type;
        $assessment->reference_refno    = $pay_in->reference_refno ?? null;
        $assessment->ref_table          = $ref_table ?? null;
        $assessment->ref_id             = $pay_in->ref_id ?? null;
        $assessment->auditors_id        = $pay_in->auditors_id ?? null;
        if($certificate_type == 1){ //cb
            $assessment->name               =  !empty($pay_in->certificate_export_to->CertiCbTo->name) ? $pay_in->certificate_export_to->CertiCbTo->name : null;
            $assessment->laboratory_name    =  !empty($pay_in->certificate_export_to->CertiCbTo->name_standard) ?  $pay_in->certificate_export_to->CertiCbTo->name_standard  : null;
        }else if($certificate_type == 2){ //ib
            $assessment->name               =  !empty($pay_in->certificate_export_to->CertiIBCostTo->name) ? $pay_in->certificate_export_to->CertiIBCostTo->name : null;
            $assessment->laboratory_name    =  !empty($pay_in->certificate_export_to->CertiIBCostTo->name_unit) ?  $pay_in->certificate_export_to->CertiIBCostTo->name_unit  : null;
        }else if($certificate_type == 3){ //lab
            $assessment->name               =  !empty($pay_in->certificate_export_to->CertiLabTo->name) ? $pay_in->certificate_export_to->CertiLabTo->name : null;
            $assessment->laboratory_name    =  !empty($pay_in->certificate_export_to->CertiLabTo->lab_name) ?  $pay_in->certificate_export_to->CertiLabTo->lab_name  : null;
        }
       
       
        $assessment->save();

         // สถานะ แต่งตั้งคณะกรรมการ
        $auditor = TrackingAuditors::findOrFail($pay_in->auditors_id);
        if(!is_null($auditor)){
            if($pay_in->state == 3){
                $auditor->step_id = 6; // ยืนยันการชำระเงินค่าตรวจประเมิน
            }else{
                $auditor->step_id = 4; // แจ้งรายละเอียดค่าตรวจประเมิน
            }
                $auditor->save();
        }

        $file = [];
        if( !empty($pay_in->FileAttachPayInOne1To->url)){
            $file['url'] =  $pay_in->FileAttachPayInOne1To->url;
        }
        if( !empty($pay_in->FileAttachPayInOne1To->new_filename)){
            $file['new_filename'] =  $pay_in->FileAttachPayInOne1To->new_filename;
        }
        if( !empty($pay_in->FileAttachPayInOne1To->filename)){
            $file['filename'] =  $pay_in->FileAttachPayInOne1To->filename;
        }  

        $attachs_file = [];
        if( !empty($pay_in->FileAttachPayInOne2To->url)){
            $attachs_file['url'] =  $pay_in->FileAttachPayInOne2To->url;
        }
        if( !empty($pay_in->FileAttachPayInOne2To->new_filename)){
            $attachs_file['new_filename'] =  $pay_in->FileAttachPayInOne2To->new_filename;
        }
        if( !empty($pay_in->FileAttachPayInOne2To->filename)){
            $attachs_file['filename'] =  $pay_in->FileAttachPayInOne2To->filename;
        }   
        TrackingHistory::create([ 
                                'certificate_type'  => $certificate_type,
                                'tracking_id'       => $pay_in->tracking_id ?? null,
                                'reference_refno'   => $pay_in->reference_refno ?? null,
                                'ref_table'         => $ref_table  ?? null,
                                'ref_id'            =>  $pay_in->ref_id ?? null,
                                'auditors_id'       =>  $pay_in->auditors_id ?? null,
                                'system'            => 5, //Pay-In ครั้งที่ 1
                                'table_name'        =>  (new TrackingPayInOne)->getTable(),
                                'refid'             => $pay_in->id ?? null,
                                'status'            => $pay_in->status ?? null,
                                'details_one'       =>  json_encode($pay_in) ?? null,
                                'attachs'           => (count($file) > 0) ? json_encode($file) : null,
                                'attachs_file'      =>  (count($attachs_file) > 0) ? json_encode($attachs_file) : null 
                            ]);


          $tracking = Tracking::findOrFail($pay_in->tracking_id);
        if(!empty($tracking) && !empty($tracking->certificate_export_to->CertiCbTo) && $certificate_type == 1  && count($tracking->AssignEmails) > 0 ){ //cb
            $certi  =  $tracking->certificate_export_to->CertiCbTo;

             $data_app =  [
                            'PayIn'         => $pay_in,
                            'data'          => $certi,
                            'assign'        =>  !empty($tracking->AssignName) ?implode(", ",$tracking->AssignName)   : '',  
                            'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                            'email_cc'      =>  !empty($certi->DataEmailDirectorCBCC) ? $certi->DataEmailDirectorCBCC :  [],
                            'email_reply'   => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : []
                        ];
                
                $log_email =  HP::getInsertCertifyLogEmail($tracking->app_no.'-'.@$pay_in->auditors_id,
                                                            $tracking->id,
                                                            (new Tracking)->getTable(),
                                                            $pay_in->id,
                                                            (new TrackingPayInOne)->getTable(),
                                                            6,
                                                            'แจ้งตรวจสอบการชำระค่าบริการในการตรวจประเมิน',
                                                            view('mail.Tracking.inform_pay_in_one', $data_app),
                                                            $tracking->created_by,
                                                            $tracking->agent_id,
                                                            null,
                                                            !empty($certi->DataEmailCertifyCenter) ?   implode(",",$certi->DataEmailCertifyCenter) : 'cb@tisi.mail.go.th',
                                                            implode(',',(array)$tracking->AssignEmails),
                                                            !empty($certi->DataEmailDirectorCBCC) ?  implode(",",$certi->DataEmailDirectorCBCC)  : null,
                                                            !empty($certi->DataEmailDirectorCBReply) ?  implode(",",$certi->DataEmailDirectorCBReply)  :  null,
                                                            null
                                                        );

                $html = new InformPayInOne($data_app);
                $mail =  Mail::to($tracking->AssignEmails)->send($html);
        
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
          }else  if(!empty($tracking) && !empty($tracking->certificate_export_to->CertiIBCostTo)  && $certificate_type == 2  && count($tracking->AssignEmails) > 0 ){ //ib
                $certi  =  $tracking->certificate_export_to->CertiIBCostTo;

                $data_app =   [
                                'PayIn'         => $pay_in,
                                'data'          => $certi,
                                'assign'        =>  !empty($tracking->AssignName) ?implode(", ",$tracking->AssignName)   : '',  
                                'email'         =>  !empty($certi->DataEmailCertifyCenter) ? implode(",",$certi->DataEmailCertifyCenter) : 'ib@tisi.mail.go.th',
                                'email_cc'      =>  !empty($certi->DataEmailDirectorIBCC) ? $certi->DataEmailDirectorIBCC :  [],
                                'email_reply'   => !empty($certi->DataEmailDirectorIBReply) ? $certi->DataEmailDirectorIBReply : []
                                ];
                
                $log_email =  HP::getInsertCertifyLogEmail($tracking->app_no.'-'.@$pay_in->auditors_id,
                                                            $tracking->id,
                                                            (new Tracking)->getTable(),
                                                            $pay_in->id,
                                                            (new TrackingPayInOne)->getTable(),
                                                            5,
                                                            'แจ้งตรวจสอบการชำระค่าบริการในการตรวจประเมิน',
                                                            view('mail.Tracking.inform_pay_in_one', $data_app),
                                                            $tracking->created_by,
                                                            $tracking->agent_id,
                                                            null,
                                                            !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                                                            implode(',',(array)$tracking->AssignEmails),
                                                            !empty($certi->DataEmailDirectorIBCC) ?   implode(",",$certi->DataEmailDirectorIBCC)  :  null,
                                                            !empty($certi->DataEmailDirectorIBReply) ?  implode(",",$certi->DataEmailDirectorIBReply)    : null,
                                                            null
                                                        );

                $html = new InformPayInOne($data_app);
                $mail =  Mail::to($tracking->AssignEmails)->send($html);
        
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
         }else  if(!empty($tracking) && !empty($tracking->certificate_export_to->CertiLabTo)  && $certificate_type == 3  && count($tracking->AssignEmails) > 0 ){ //lab
                     $certi  =  $tracking->certificate_export_to->CertiLabTo;
                     $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                     $EMail =  array_key_exists($certi->subgroup,$dataMail)  ? $dataMail[$certi->subgroup] :'admin@admin.com';
                  $data_app =   [
                                'PayIn'         => $pay_in,
                                'data'          => $certi,
                                'assign'        =>  !empty($pay_in->certificate_export_to->AssignName) ?implode(", ",$pay_in->certificate_export_to->AssignName)   : '',  
                                'email'         =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : $EMail,
                                'email_cc'      =>  !empty($certi->DataEmailDirectorLABCC) ? $certi->DataEmailDirectorLABCC : [],
                                'email_reply'   => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                            ];
                
                $log_email =  HP::getInsertCertifyLogEmail($tracking->app_no.'-'.@$pay_in->auditors_id,
                                                            $tracking->id,
                                                            (new Tracking)->getTable(),
                                                            $pay_in->id,
                                                            (new TrackingPayInOne)->getTable(),
                                                            4,
                                                            'แจ้งตรวจสอบการชำระค่าบริการในการตรวจประเมิน',
                                                            view('mail.Tracking.inform_pay_in_one', $data_app),
                                                            $tracking->created_by,
                                                            $tracking->agent_id,
                                                            null,
                                                            !empty($certi->DataEmailCertifyCenter) ? @$certi->DataEmailCertifyCenter  : $EMail,
                                                            implode(',',(array)$tracking->AssignEmails),
                                                            !empty($certi->DataEmailDirectorLABCC) ?  implode(",",$certi->DataEmailDirectorLABCC) :  null,
                                                            !empty($certi->DataEmailDirectorLABReply) ?   implode(",",$certi->DataEmailDirectorLABReply)  : null,
                                                            null
                                                        );

                $html = new InformPayInOne($data_app);
                $mail =  Mail::to($tracking->AssignEmails)->send($html);
        
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }
        }        



        }
 

    }

    public static function update_tracking_pay_in2($id,$certificate_type = null,$ref_table = null){
            // LOG
             $pay_in = TrackingPayInTwo::findOrFail($id);
       if(!is_null($pay_in) && $pay_in->status != 1 ){

            $pay_in->remark =  null;
            $pay_in->state = 3;  
            $pay_in->status = 1; 
            $pay_in->save();

                $file = [];
                if( !empty($pay_in->FileAttachPayInTwo1To->url)){
                    $file['url'] =  $pay_in->FileAttachPayInTwo1To->url;
                }
                if( !empty($pay_in->FileAttachPayInTwo1To->new_filename)){
                    $file['new_filename'] =  $pay_in->FileAttachPayInTwo1To->new_filename;
                }
                if( !empty($pay_in->FileAttachPayInTwo1To->filename)){
                    $file['filename'] =  $pay_in->FileAttachPayInTwo1To->filename;
                }   

                $attachs_file = [];
                if( !empty($pay_in->FileAttachPayInTwo2To->url)){
                    $attachs_file['url'] =  $pay_in->FileAttachPayInTwo2To->url;
                }
                if( !empty($pay_in->FileAttachPayInTwo2To->new_filename)){
                    $attachs_file['new_filename'] =  $pay_in->FileAttachPayInTwo2To->new_filename;
                }
                if( !empty($pay_in->FileAttachPayInTwo2To->filename)){
                    $attachs_file['filename'] =  $pay_in->FileAttachPayInTwo2To->filename;
                }  
                TrackingHistory::create([ 
                                        'certificate_type'  => $certificate_type,
                                         'reference_refno'  => $pay_in->reference_refno ?? null,
                                        'ref_table'         =>  $ref_table  ?? null ,
                                        'ref_id'            =>  $pay_in->ref_id ?? null,
                                        'system'            => 11, //Pay-In ครั้งที่ 2
                                        'table_name'        =>  (new TrackingPayInTwo)->getTable(),
                                        'refid'             =>  $pay_in->id ?? null,
                                        'status'            =>  $pay_in->status ?? null,
                                        'details_one'       =>  json_encode($pay_in) ?? null,
                                        'file'              =>  (count($file) > 0) ? json_encode($file) : null,
                                        'attachs_file'      =>  (count($attachs_file) > 0) ? json_encode($attachs_file) : null,
                                        'created_by'        =>  auth()->user()->runrecno
                                    ]);

            $request = true ;                    
        }else{
            $request = false ;          
        }   
        return $request;

    }


    public function law_checkbill(Request $request){

        $context = stream_context_create(array( 
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
                ),
            'http' => array( 
                'timeout' => 30 
                ) 
            ) 
        );

        $message    = false;
        $response   =   new TransactionPayIn;
      
     
            if(!empty($request->ref1)){  // เฉพาะตามคำขอ
                $ref1          =    $request->ref1;
                $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',2)->first();
                $transaction   =     TransactionPayIn::Where(DB::raw("REPLACE(ref1,'-','')"),str_replace('-', '', $ref1))->orderby('id','desc')->first();
                if(!is_null($setting_payment) && !is_null($transaction)){

                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&ref1=$transaction->ref1", false, $context);
                    $api = json_decode($content,true);
                
                    if(!empty($api[0]['PayList']) && count((array)$api[0]['PayList']) > 0){
                              $PayList =   $api[0]['PayList'][0]; 

                        $payment = LawCasesPayments::where('app_certi_transaction_pay_in_id',$transaction->id)->first();
                           if(!is_null($payment)){

                            
                              if($PayList['Status'] == '1'){
                           
                                 $message = true;
                                    $PayList['BillCreateDate']          =   !empty($PayList['BillCreateDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['BillCreateDate'])): null;  
                                    $PayList['PaymentDate']             =   !empty($PayList['PaymentDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['PaymentDate'])): null;    
                                    $PayList['ReceiptCreateDate']       =   !empty($PayList['ReceiptCreateDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['ReceiptCreateDate'])): null;    
                                    $PayList['ReconcileDate']           =   !empty($PayList['ReconcileDate']) ? date("Y-m-d H:i:s",strtotime( $PayList['ReconcileDate'])): null;  
                                    $PayList['PayAmountBill']           =   !empty($PayList['Amount']) ? str_replace(",","", $PayList['Amount']): null; 
                                    $PayList['status_confirmed']        =  1; 
                                    unset($PayList['Amount']);
                                    unset($PayList['Ref1']);

                                    if($transaction->status_confirmed != 1){  

                                       $transaction->update($PayList);

                                 

                                       if(!is_null($payment)){
                                           $payment->paid_status          = '2';
                                           $payment->paid_date            =   !empty($transaction->ReceiptCreateDate) ?  date("Y-m-d H:i:s",strtotime($transaction->ReceiptCreateDate)) : ( !empty($transaction->PaymentDate) ?  date("Y-m-d H:i:s",strtotime($transaction->PaymentDate)) : null );  
                                           $payment->paid_type            = '1';
                                           $payment->paid_channel         = '1';
                                           $payment->save();

                                            if($payment->ref_table ==  (new LawCasesForm)->getTable()){
                                                $cases    = LawCasesForm::findOrFail($payment->ref_id);
                                                if(!empty($cases) &&  $cases->status  <= '12'){
                                                    $cases->status           = '12' ; //ตรวจสอบการชำระเงินแล้ว;
                                                    $cases->save();
                                                    }
                                            }
                                       }
                                    }
                                    
                                    $transaction->status = 1; 
                                }else{
                                    $transaction->status = 0; 
                                }

                                $object                         = (object)[];
                                $object->status                 =  $transaction->status ;
                                $object->case_number            =  !empty($payment->law_data_to->case_number) ?  $payment->law_data_to->case_number : ''; 
                                $object->ref1                   =  !empty($transaction->Ref_1) ?  $transaction->Ref_1 : ''; 
                                $object->receipt_code           =  !empty($transaction->ReceiptCode) ?  $transaction->ReceiptCode : ''; 
                                $object->receipt_date           =  !empty($transaction->ReceiptCreateDate) ?  HP::DateTimeThai($transaction->ReceiptCreateDate) : ''; 
                                $response                       =  $object;
                         }
                     }
              
                }
                return response()->json(['message' => $message ,'response'=> $response]);
   }else  if(!empty($request->paydate)){ 

                $paydate  = $request->paydate;

                $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',2)->first();

                
              $message = false;
              $response = [];
              $data_key = [];
              $data = [];
              
              $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&paydate=$paydate", false, $context);
              $api = json_decode($content,true);
           
              if(!empty($api[0]['PayList']) && count((array)$api[0]['PayList']) > 0){
                                $data = [];
                        foreach($api[0]['PayList'] as $item){
                                 $transaction =     TransactionPayIn::Where(DB::raw("REPLACE(ref1,'-','')"),str_replace('-', '', $item['Ref1']))->first();
                                if(!is_null($transaction)){
                                  
                                    $payment = LawCasesPayments::where('app_certi_transaction_pay_in_id',$transaction->id)->first();
                                    if(!is_null($payment)){  
                                 
                                        if($item['Status'] == 1){
                                
                                            $item['CGDRef1']                 =   !empty($item['CGDRef1'])  ?  $item['CGDRef1']: null;  
                                            $item['BillCreateDate']          =   !empty($item['BillCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['BillCreateDate'])): null;  
                                            $item['PaymentDate']             =   !empty($item['PaymentDate']) ? date("Y-m-d H:i:s",strtotime( $item['PaymentDate'])): null;    
                                            $item['ReceiptCreateDate']       =   !empty($item['ReceiptCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReceiptCreateDate'])): null;    
                                            $item['ReconcileDate']           =   !empty($item['ReconcileDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReconcileDate'])): null;  
                                            $item['PayAmountBill']           =   !empty($item['Amount']) ? str_replace(",","", $item['Amount']): null; 
                                            $item['status_confirmed']        =  1; 
                                            unset($item['Amount']);
                                            unset($item['Ref1']);

                                            if($transaction->status_confirmed != 1){  
                                                $transaction->update($item);   

                                                    $payment->paid_status          = '2';
                                                    $payment->paid_date            =   !empty($transaction->ReceiptCreateDate) ?  date("Y-m-d H:i:s",strtotime($transaction->ReceiptCreateDate)) : ( !empty($transaction->PaymentDate) ?  date("Y-m-d H:i:s",strtotime($transaction->PaymentDate)) : null );  
                                                    $payment->paid_type            = '1';
                                                    $payment->paid_channel         = '1';
                                                    $payment->save();

                                                    if($payment->ref_table ==  (new LawCasesForm)->getTable()){
                                                        $cases    = LawCasesForm::findOrFail($payment->ref_id);
                                                        if(!empty($cases) &&  $cases->status  <= '12'){
                                                            $cases->status           = '12' ; //ตรวจสอบการชำระเงินแล้ว;
                                                            $cases->save();
                                                         }
                                                    }
                                            
                                            }  

                                            $transaction->status = 1; 
                                                
                                        
                                        }else{
                                            $transaction->status = 0; 
                                        }

                                        if(!array_key_exists($transaction->ref1,$data_key)){
                                            $object                         = (object)[];
                                            $object->status                 =  $transaction->status ;
                                            $object->case_number            =  !empty($payment->law_data_to->case_number) ?  $payment->law_data_to->case_number : ''; 
                                            $object->ref1                   =  !empty($transaction->Ref_1) ?  $transaction->Ref_1 : ''; 
                                            $object->receipt_code           =  !empty($transaction->ReceiptCode) ?  $transaction->ReceiptCode : ''; 
                                            $object->receipt_date           =  !empty($transaction->ReceiptCreateDate) ?  HP::DateTimeThai($transaction->ReceiptCreateDate) : ''; 
                                        
                                            $data[]                         = $object;
                                            $data_key[$transaction->ref1]   = $object;
                                        }
                                 }
                            }
                        }
                        if(count($data) > 0){
                            $message = true;
                        }else{
                            $message = false;
                        }
                        $response =  $data;
              } 
              return response()->json(['message' => $message ,'response'=> $response]);

            }else{

                $payments = LawCasesPayments::Where('paid_status','1')->WhereNull('cancel_status')->whereDate('end_date','>=',date("Y-m-d"))->get();

 
                $message = false;
                $response = [];
                $data_key = [];
                $data = [];
    
                   if(count($payments) > 0 ){
                        foreach($payments as $payment){
                            $transaction =     TransactionPayIn::findOrFail($payment->app_certi_transaction_pay_in_id);
                            if(!is_null($transaction)){

                              
                                if(!empty($payment->law_data_to->law_basic_arrest_id) &&  $payment->law_data_to->law_basic_arrest_id == '1'){ // ไม่มีการจับกุม
                                    $setting_payment = CertiSettingPayment::where('certify',7)->where('payin',1)->where('type',2)->first();
                                }else{ // มีการจับกุม
                                    $setting_payment = CertiSettingPayment::where('certify',8)->where('payin',1)->where('type',2)->first();
                                  
                                }

                                if(!empty($setting_payment)){

                                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&ref1=$transaction->ref1", false, $context);
                                
                                    $api    = json_decode($content,true);
                          
                                    if(!empty($api[0]['PayList']) && count((array)$api[0]['PayList']) > 0){
                                            foreach($api[0]['PayList'] as $item){
                                        
                                                        if($item['Status'] == 1){ 
                                                            $item['BillCreateDate']          =   !empty($item['BillCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['BillCreateDate'])): null;  
                                                            $item['PaymentDate']             =   !empty($item['PaymentDate']) ? date("Y-m-d H:i:s",strtotime( $item['PaymentDate'])): null;    
                                                            $item['ReceiptCreateDate']       =   !empty($item['ReceiptCreateDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReceiptCreateDate'])): null;    
                                                            $item['ReconcileDate']           =   !empty($item['ReconcileDate']) ? date("Y-m-d H:i:s",strtotime( $item['ReconcileDate'])): null;  
                                                            $item['PayAmountBill']           =   !empty($item['Amount']) ? str_replace(",","", $item['Amount']): null; 
                                                            $item['status_confirmed']        =  1; 
                                                            unset($item['Amount']);
                                                            unset($item['Ref1']);
                
                                                            if($transaction->status_confirmed != 1){  
                                                                $transaction->update($item); 
        
                                                                $payment->paid_status          = '2';
                                                                $payment->paid_date            =   !empty($transaction->ReceiptCreateDate) ?  date("Y-m-d H:i:s",strtotime($transaction->ReceiptCreateDate)) : ( !empty($transaction->PaymentDate) ?  date("Y-m-d H:i:s",strtotime($transaction->PaymentDate)) : null );  
                                                                $payment->paid_type            = '1';
                                                                $payment->paid_channel         = '1';
                                                                $payment->save();  
        
                                                                if($payment->ref_table ==  (new LawCasesForm)->getTable()){
                                                                    $cases    = LawCasesForm::findOrFail($payment->ref_id);
                                                                    if(!empty($cases) &&  $cases->status  <= '12'){
                                                                        $cases->status           = '12' ; //ตรวจสอบการชำระเงินแล้ว;
                                                                        $cases->save();
                                                                     }
                                                                }
        
                                                            }
                                                         
                                                               $transaction->status = 1; 
                                                         
         
                                                        }else{
                                                               $transaction->status = 0; 
                                                        }
                                                            if(!array_key_exists($transaction->ref1,$data_key)){
                                                                $object                         = (object)[];
                                                                $object->status                 =  $transaction->status ;
                                                                $object->case_number            =  !empty($payment->law_data_to->case_number) ?  $payment->law_data_to->case_number : ''; 
                                                                $object->ref1                   =  !empty($transaction->Ref_1) ?  $transaction->Ref_1 : ''; 
                                                                $object->receipt_code           =  !empty($transaction->ReceiptCode) ?  $transaction->ReceiptCode : ''; 
                                                                $object->receipt_date           =  !empty($transaction->ReceiptCreateDate) ?  HP::DateTimeThai($transaction->ReceiptCreateDate) : ''; 
                                                               
                                                                $data[]                         = $object;
                                                                $data_key[$transaction->ref1]   = $object;
                                                            }
                                                       
                                                    }
                                            }
                                   }
                              
                              } 
        
                        }
                     }
    
        
                if(count($data) > 0){
                    $message = true;
                }else{
                    $message = false;
                }
                $response =  $data;
    
                return response()->json(['message' => $message ,'response'=> $response]);
            }

        
      
   }



   public function export_excel(Request $request)
   {

       ini_set('max_execution_time', 7200); //120 minutes
       ini_set('memory_limit', '16384M'); //16 GB
 
         $query =  LawOffender::all();

                                    

           //Create Spreadsheet Object
           $spreadsheet = new Spreadsheet();
           $sheet = $spreadsheet->getActiveSheet();

           //หัวรายงาน
           $sheet->setCellValue('A1', 'ข้อมูลผู้กระทำความผิด');
           $sheet->mergeCells('A1:I1');
           $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
           $sheet->getStyle("A1")->getFont()->setSize(16);

           //แสดงวันที่
           $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
           $sheet->mergeCells('A2:I2');
           $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

           $styleArray_header = [
               'font' => [ // จัดตัวอักษร
                   'bold' => true, // กำหนดเป็นตัวหนา
               ],
               'alignment' => [  // จัดตำแหน่ง
                   'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
               ],
               'borders' => [ // กำหนดเส้นขอบ
                   'allBorders' => [ // กำหนดเส้นขอบทั้งหมด
                       'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                   ],
               ],
               'fill' => [ // กำหนดสีพื้นหลัง
                   'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, // รูปแบบพื้นหลัง
                   'rotation' => 90, // กำหนดองศาทิศทางการไล่เฉด
                   'startColor' => [ // สีที่ 1
                       'argb' => 'FFA0A0A0',  // argb คือ Alpha rgb มี 8 ตัว หรือใช้เป็น rgb มี 6 ตัว 
                   ],
                   'endColor' => [ // สีที่ 2
                       'argb' => 'FFFFFFFF',  // argb คือ Alpha rgb มี 8 ตัว หรือใช้เป็น rgb มี 6 ตัว FFFFFF
                   ],
               ],
           ];

           //หัวตาราง
           $sheet->setCellValue('A3', 'ลำดับ');
           $sheet->setCellValue('B3', '13หลัก');
           $sheet->setCellValue('C3', 'ชื่อผู้ประกอบการ'); 
           $sheet->setCellValue('D3', 'เลขลคดี');
           $sheet->setCellValue('E3', 'นิติกรเจ้าคดี');
           $sheet->setCellValue('F3', 'ผลิตภัณฑ์');
           $sheet->setCellValue('G3', 'มูลค่าของกลาง');
           $sheet->setCellValue('H3', 'ค่าปรับ');
           $sheet->setCellValue('I3', 'สถานะ');
 
           $sheet->getStyle('A3:I3')->applyFromArray($styleArray_header);

           $row = 4; //start row
            $i = 1;
       if(count($query) > 0){
           foreach ($query as $key => $item) {
             

               if(count($item->offender_cases) > 0){
                 foreach($item->offender_cases as $cases){
                    $product =  LawOffenderProduct::where('case_number',$cases->case_number)->first();
                    $sheet->setCellValue('A' . $row,$i++);
                    $sheet->setCellValue('B' . $row, !empty($item->taxid)? str_replace("-","",$item->taxid):'');
                    $sheet->setCellValue('C' . $row, !empty($item->name) ? $item->name : '');
                    $sheet->getStyle('B'.$row)
                                             ->getNumberFormat()
                                             ->setFormatCode(
                                                 \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                                           );
                    $sheet->setCellValue('D' . $row, !empty($cases->case_number) ? $cases->case_number : '');
                    $sheet->setCellValue('E' . $row, !empty($cases->LawyerName) ? $cases->LawyerName : '');
                    $sheet->setCellValue('F' . $row,  !empty($product->detail) ? $product->detail : '');
                    $sheet->setCellValue('G' . $row,  !empty($product->total_price) ?  $product->total_price : '');  
                    $sheet->setCellValue('H' . $row,  !empty($cases->total_compare) ?  $cases->total_compare : '');
                    $sheet->setCellValue('I' . $row,  !empty($cases->StatusName) ? $cases->StatusName : '');
                    $row++;
                 }
               }
           
            
           }
       }

       $sheet->getStyle('A4:A'.$row)->getAlignment()->setHorizontal('center');
       $sheet->getStyle('B4:B'.$row)->getAlignment()->setHorizontal('left');
       $sheet->getStyle('C4:C'.$row)->getAlignment()->setHorizontal('left');
       $sheet->getStyle('D4:D'.$row)->getAlignment()->setHorizontal('left');
       $sheet->getStyle('E4:E'.$row)->getAlignment()->setHorizontal('left');
       $sheet->getStyle('F4:F'.$row)->getAlignment()->setHorizontal('left');
       
       $sheet->getStyle('G4:G'.$row)->getAlignment()->setHorizontal('right');
       $sheet->getStyle('H4:H'.$row)->getAlignment()->setHorizontal('right');

       $sheet->getStyle('I4:I'.$row)->getAlignment()->setHorizontal('left');
       
        $last_i = $row;
        $amount = 'G4' . ':G' . $last_i;
        $amount_bill = 'H4'  . ':H' . $last_i;
 

        $sheet->setCellValue('A'.$row, 'รวม');
        $sheet->mergeCells('A'.$row.':E'.$row);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('G'.$row,'=SUM(' . $amount . ')');
        $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal('right');

        
        $sheet->setCellValue('H'.$row,'=SUM(' . $amount_bill . ')');
        $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('right');


             //ใส่ขอบดำ
             $style_borders = [
               'borders' => [ // กำหนดเส้นขอบ
               'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
               ],
               ]
           ];
           $sheet->getStyle('A3:I'.$row)->applyFromArray($style_borders);

           $sheet->getStyle('G4:G'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
           $sheet->getStyle('H4:H'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

           //Set Column Width
           $sheet->getColumnDimension('A')->setAutoSize(true);
           $sheet->getColumnDimension('B')->setAutoSize(true);
           $sheet->getColumnDimension('C')->setAutoSize(true);
           $sheet->getColumnDimension('D')->setAutoSize(true);
           $sheet->getColumnDimension('E')->setAutoSize(true);
           $sheet->getColumnDimension('F')->setAutoSize(true);
           $sheet->getColumnDimension('G')->setAutoSize(true);
           $sheet->getColumnDimension('H')->setAutoSize(true);
           $sheet->getColumnDimension('I')->setAutoSize(true);
   
           $filename = 'ข้อมูลผู้กระทำความผิด_' . date('Hi_dmY') . '.xlsx';
           header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header('Content-Disposition: attachment; filename="' . $filename . '"');
           $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
           $writer->save("php://output");
           exit;

   }

}




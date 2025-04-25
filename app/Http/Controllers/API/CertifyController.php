<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CertificateExport;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\CertificateHistory;
use App\Models\Certify\EpaymentBillTest;

use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\Applicant\AssessmentGroupAuditor;
use App\Models\Certify\ApplicantIB\CertiIBPayInOne;
 
use App\Models\Law\Cases\LawCasesForm;  
use App\Mail\Lab\MailBoardAuditor;
use Illuminate\Support\Facades\Mail;
use HP;
use Mpdf\Mpdf;
use File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use GuzzleHttp\Client; 
class CertifyController extends Controller
{
     public function pmt1(Request $request){
            $response = [];
             $str =  explode("-",$request->Ref1);
            //  dd($str);
            if(count($str) == 4){
              $app_no =  $str['0'].'-'.$str['1'].'-'.$str['2'];
            }else{
              $app_no =  $request->Ref1;
            }  
            $pid    =  $request->pid;
            $certi_cb = CertiCb::where('app_no',$app_no)->first();
            $certi_ib = CertiIb::where('app_no',$app_no)->first(); 
            $certi_lab = CertiLab::where('app_no',$app_no)->first(); 
            $export_lab = CertificateExport::where('reference_refno',$app_no)->first();
            $export_cb  = CertiCBExport::where('reference_refno',$app_no)->first();
            $export_ib  = CertiIBExport::where('reference_refno',$app_no)->first();

        if(!is_null($certi_cb) && $request->out == 'json'){
                $response['pid']                = $pid;
                $response['Ref1']               = $certi_cb->app_no;
                $response['returnCode']         = '000';
                $response['appno']              = $certi_cb->app_no;
                $response['bus_name']           = $certi_cb->name;
                $response['address']            = $certi_cb->address;
                $response['allay']              = $certi_cb->allay;
                $response['village_no']         = $certi_cb->village_no;
                $response['road']               = $certi_cb->road;
                $response['district_id']        = $certi_cb->basic_district->DISTRICT_NAME ?? null;
                $response['amphur_id']          = $certi_cb->basic_amphur->AMPHUR_NAME ?? null;
                $response['province_id']        = $certi_cb->basic_province->PROVINCE_NAME ?? null;
                $response['postcode']           = $certi_cb->postcode;
                $response['email']              = $certi_cb->email;
                $response['app_check']          = "30000";
                $response['vatid']              = "0125542005151";
                $response['Perpose']            =  $certi_cb->StandardChangeTitle ?? null;
                $response['AmountCert']         = "5000";
                $response['billNo']             = "64020900000163";
                $response['CGDRef1']            = "6402090000016310";
                $response['CGDRef2']            = "64041072";
                $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
                $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
                $response['allPaymentAmount']   = "36,000.00";
                $response['amount_bill']        = "36,000.00";
                $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
                $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
                $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
                $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
                $response['app_certi_assessment_id']       = "test";
                $response['status_confirmed']   = "0";
                $response['auditor']            = "คณะ";

                $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
                if(is_null($epay)){
                  $epay = new EpaymentBillTest;
                } 
                $epay->Ref1               =   $request->Ref1;
                $epay->CGDRef1            =  '6402090000016310';
                $epay->Status             =  0;
                $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
                $epay->InvoiceCode        = '0-0000000000000000000000'; 
                $epay->ReceiptCode        =  '123456';
                $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
                $epay->Amount             =  "36000.00";
                $epay->save();

               return response()->json($response);
        }else  if(!is_null($certi_ib) && $request->out == 'json'){
               $response['pid']                = $pid;
                $response['Ref1']               = $certi_ib->app_no;
                $response['returnCode']         = '000';
                $response['appno']              = $certi_ib->app_no;
                $response['bus_name']           = $certi_ib->name;
                $response['address']            = $certi_ib->address;
                $response['allay']              = $certi_ib->allay;
                $response['village_no']         = $certi_ib->village_no;
                $response['road']               = $certi_ib->road;
                $response['district_id']        = $certi_ib->basic_district->DISTRICT_NAME ?? null;
                $response['amphur_id']          = $certi_ib->basic_amphur->AMPHUR_NAME ?? null;
                $response['province_id']        = $certi_ib->basic_province->PROVINCE_NAME ?? null;
                $response['postcode']           = $certi_ib->postcode;
                $response['email']              = $certi_ib->email;
                $response['app_check']          = "30000";
                $response['vatid']              = "0125542005151";
                $response['Perpose']            =  $certi_ib->StandardChangeTitle ?? null;
                $response['AmountCert']         = "5000";
                $response['billNo']             = "64020900000163";
                $response['CGDRef1']            = "6402090000016310";
                $response['CGDRef2']            = "64041072";
                $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
                $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
                $response['allPaymentAmount']   = "36,000.00";
                $response['amount_bill']        = "36,000.00";
                $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
                $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
                $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
                $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
                $response['status']             = "0";
                $response['auditor']            = "คณะ";

                $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
                if(is_null($epay)){
                  $epay = new EpaymentBillTest;
                }
                $epay->Ref1               =   $request->Ref1;
                $epay->CGDRef1            =  '6402090000016310';
                $epay->Status             =  0;
                $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
                $epay->InvoiceCode        = '0-0000000000000000000000'; 
                $epay->ReceiptCode        =  '123456';
                $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
                $epay->Amount             =  "36000.00";
                $epay->save();

               return response()->json($response);
          }else  if(!is_null($certi_lab) && $request->out == 'json'){
                $response['pid']                = $pid;
                $response['Ref1']               = $certi_lab->app_no;
                $response['returnCode']         = '000';
                $response['appno']              = $certi_lab->app_no;
                $response['bus_name']           = $certi_lab->name;
                $response['address']            = $certi_lab->address;
                $response['allay']              = $certi_lab->allay;
                $response['village_no']         = $certi_lab->village_no;
                $response['road']               = $certi_lab->road;
                $response['district_id']        = $certi_lab->basic_district->DISTRICT_NAME ?? null;
                $response['amphur_id']          = $certi_lab->basic_amphur->AMPHUR_NAME ?? null;
                $response['province_id']        = $certi_lab->basic_province->PROVINCE_NAME ?? null;
                $response['postcode']           = $certi_lab->postcode;
                $response['email']              = $certi_lab->email;
                $response['app_check']          = "30000";
                $response['vatid']              = "0125542005151";
                $response['Perpose']            =  $certi_lab->StandardChangeTitle ?? null;
                $response['AmountCert']         = "5000";
                $response['billNo']             = "64020900000163";
                $response['CGDRef1']            = "6402090000016310";
                $response['CGDRef2']            = "64041072";
                $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
                $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
                $response['allPaymentAmount']   = "36,000.00";
                $response['amount_bill']        = "36,000.00";
                $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
                $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
                $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
                $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
                $response['status']             = "0";
                $response['auditor']            = "คณะ";


                $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
                if(is_null($epay)){
                  $epay = new EpaymentBillTest;
                }
                $epay->Ref1               =   $request->Ref1;
                $epay->CGDRef1            =  '6402090000016310';
                $epay->Status             =  0;
                $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
                $epay->InvoiceCode        = '0-0000000000000000000000'; 
                $epay->ReceiptCode        =  '123456';
                $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
                $epay->Amount             =  "36000.00";
                $epay->save();
                
               return response()->json($response);
         } else  if(!is_null($export_lab) && $request->out == 'json'){
          $response['pid']                = $pid;
           $response['Ref1']               = $export_lab->reference_refno;
           $response['returnCode']         = '000';
           $response['appno']              = $export_lab->reference_refno;
           $response['bus_name']           = $export_lab->org_name;
           $response['address']            = $export_lab->address_no;
           $response['allay']              = $export_lab->address_moo;
           $response['village_no']         = $export_lab->address_soi;
           $response['road']               = $export_lab->address_road;
           $response['district_id']        = $export_lab->address_subdistrict ?? null;
           $response['amphur_id']          = $export_lab->address_district ?? null;
           $response['province_id']        = $export_lab->address_province ?? null;
           $response['postcode']           = $export_lab->address_postcode;
           $response['email']              = $export_lab->CertiLabTo->email ?? null;
           $response['app_check']          = "30000";
           $response['vatid']              = "0125542005151";
           $response['Perpose']            =  null;
           $response['AmountCert']         = "5000";
           $response['billNo']             = "64020900000163";
           $response['CGDRef1']            = "6402090000016310";
           $response['CGDRef2']            = "64041072";
           $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
           $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
           $response['allPaymentAmount']   = "36,000.00";
           $response['amount_bill']        = "36,000.00";
           $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
           $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
           $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
           $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
           $response['status']             = "0";
           $response['auditor']            = "คณะ";

           $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
           if(is_null($epay)){
             $epay = new EpaymentBillTest;
           }
           $epay->Ref1               =   $request->Ref1;
           $epay->CGDRef1            =  '6402090000016310';
           $epay->Status             =  0;
           $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
           $epay->InvoiceCode        = '0-0000000000000000000000'; 
           $epay->ReceiptCode        =  '123456';
           $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
           $epay->Amount             =  "36000.00";
           $epay->save();

          return response()->json($response);

          

         }else  if(!is_null($export_cb) && $request->out == 'json'){
          $response['pid']                = $pid;
           $response['Ref1']               = $export_cb->reference_refno;
           $response['returnCode']         = '000';
           $response['appno']              = $export_cb->reference_refno;
           $response['bus_name']           = $export_cb->cb_name;
           $response['address']            = $export_cb->address;
           $response['allay']              = $export_cb->allay;
           $response['village_no']         = $export_cb->village_no;
           $response['road']               = $export_cb->road;
           $response['district_id']        = $export_cb->province_name ?? null;
           $response['amphur_id']          = $export_cb->amphur_name ?? null;
           $response['province_id']        = $export_cb->district_name ?? null;
           $response['postcode']           = $export_cb->postcode;
           $response['email']              = $export_cb->CertiCbTo->email ?? null;
           $response['app_check']          = "30000";
           $response['vatid']              = "0125542005151";
           $response['Perpose']            =  null;
           $response['AmountCert']         = "5000";
           $response['billNo']             = "64020900000163";
           $response['CGDRef1']            = "6402090000016310";
           $response['CGDRef2']            = "64041072";
           $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
           $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
           $response['allPaymentAmount']   = "36,000.00";
           $response['amount_bill']        = "36,000.00";
           $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
           $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
           $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
           $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
           $response['status']             = "0";
           $response['auditor']            = "คณะ";

           $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
           if(is_null($epay)){
             $epay = new EpaymentBillTest;
           }
           $epay->Ref1               =   $request->Ref1;
           $epay->CGDRef1            =  '6402090000016310';
           $epay->Status             =  0;
           $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
           $epay->InvoiceCode        = '0-0000000000000000000000'; 
           $epay->ReceiptCode        =  '123456';
           $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
           $epay->Amount             =  "36000.00";
           $epay->save();

          return response()->json($response);
        }else  if(!is_null($export_ib) && $request->out == 'json'){
          $response['pid']                = $pid;
           $response['Ref1']               = $export_ib->reference_refno;
           $response['returnCode']         = '000';
           $response['appno']              = $export_ib->reference_refno;
           $response['bus_name']           = $export_ib->org_name;
           $response['address']            = $export_ib->address;
           $response['allay']              = $export_ib->allay;
           $response['village_no']         = $export_ib->village_no;
           $response['road']               = $export_ib->road;
           $response['district_id']        = $export_ib->province_name ?? null;
           $response['amphur_id']          = $export_ib->amphur_name ?? null;
           $response['province_id']        = $export_ib->district_name ?? null;
           $response['postcode']           = $export_ib->postcode;
           $response['email']              = $export_ib->CertiIBCostTo->email ?? null;
           $response['app_check']          = "30000";
           $response['vatid']              = "0125542005151";
           $response['Perpose']            =  null;
           $response['AmountCert']         = "5000";
           $response['billNo']             = "64020900000163";
           $response['CGDRef1']            = "6402090000016310";
           $response['CGDRef2']            = "64041072";
           $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
           $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
           $response['allPaymentAmount']   = "36,000.00";
           $response['amount_bill']        = "36,000.00";
           $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
           $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
           $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
           $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
           $response['status']             = "0";
           $response['auditor']            = "คณะ";

           $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
           if(is_null($epay)){
             $epay = new EpaymentBillTest;
           } 
           $epay->Ref1               =   $request->Ref1;
           $epay->CGDRef1            =  '6402090000016310';
           $epay->Status             =  0;
           $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
           $epay->InvoiceCode        = '0-0000000000000000000000'; 
           $epay->ReceiptCode        =  '123456';
           $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
           $epay->Amount             =  "36000.00";
           $epay->save();

          return response()->json($response);

          
         }else if($request->out == 'pdf'){
                $arrContextOptions=array(
                                "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                                ),
                               );
                $content_pdf =  file_get_contents( url('images/Payin2.pdf') , false, stream_context_create($arrContextOptions));
              //Specify that the content has PDF Mime Type
              header("Content-Type: application/pdf");
              //Display it
              echo $content_pdf;
        }

     }


     public function pmt2(Request $request){
       $response = [];

       if(!empty($request->ref1)){
             $epays =  EpaymentBillTest::where('Ref1',$request->ref1)->get();
       } else      if(!empty($request->paydate)){
             $epays =  EpaymentBillTest::whereDate('PaymentDate',$request->paydate)->where('Status',1)->get();
      } else{
            $epays =  EpaymentBillTest::whereDate('PaymentDate',date('Y-m-d'))->where('Status',1)->get();
       }
 
      if(count($epays) > 0){
          $data = [];
          foreach($epays as $key => $epay){
            $list = [];
            $list['Ref1']                  = $epay->Ref1 ?? null;
            $list['CGDRef1']               = $epay->CGDRef1 ?? null;
            $list['Amount']                = $epay->Amount ?? null;
            $list['Status']                = $epay->Status ?? null;
            $list['BankCode']              = $epay->BankCode ?? null;
            $list['BillCreateDate']        = $epay->BillCreateDate ?? null;
            $list['Etc1Data']              = $epay->Etc1Data ?? null;
            $list['Etc2Data']              = $epay->Etc2Data ?? null;
            $list['InvoiceCode']           = $epay->InvoiceCode ?? null;
            $list['PaymentDate']           = $epay->PaymentDate ?? null;
            $list['ReceiptCode']           = $epay->ReceiptCode ?? null;
            $list['ReceiptCreateDate']     = $epay->ReceiptCreateDate ?? null;
            $list['ReconcileDate']         = $epay->ReconcileDate ?? null;
            $list['SourceID']              = $epay->SourceID ?? null;
            $data[]                        =  $list;
          }
          $response['PayList']            =   $data ;
      }


       return response()->json([$response]);
    }



    public function law_pmt1(Request $request){
      $response = [];
 
      $pid    =  $request->pid;
      $case_number    =  $request->Ref1;
  
      $str =  explode("-",$case_number);
   
      if(count($str) == 3){
         $app_no =  $str['0'].'-'.$str['1'];
      }else{
         $app_no =  $request->Ref1;
      }  

      $cases  = LawCasesForm::where('case_number',$app_no)->first();

 
     
      if(!is_null($cases) && !is_null($cases->law_cases_payments_to) && $request->out == 'json'){
           $payments =   $cases->law_cases_payments_to;
          $response['pid']                = $pid;
          $response['Ref1']               = $app_no;
          $response['returnCode']         = '000';
          $response['appno']              = $cases->case_number ?? null;
          $response['bus_name']           = $cases->storage_name  ?? null;
          $response['address']            = $cases->storage_address_no  ?? null;
          $response['allay']              = $cases->storage_moo  ?? null;
          $response['village_no']         = $cases->storage_soi ?? null;
          $response['road']               = $cases->storage_street ?? null;
          $response['district_id']        = $cases->province_name ?? null;
          $response['amphur_id']          = $cases->amphur_name ?? null;
          $response['province_id']        = $cases->district_name ?? null;
          $response['postcode']           = $cases->storage_zipcode ?? null;
          $response['email']              = $cases->offend_email ?? null;
          $response['billNo']             = "64020900000163";
          $response['CGDRef1']            = "6402090000016310";
          $response['CGDRef2']            = "64041072";
          $response['invoiceStartDate']   = $payments->start_date ?? null;
          $response['invoiceEndDate']     = $payments->end_date ?? null;
          $response['allPaymentAmount']   =  number_format($payments->amount,2) ?? null;
          $response['amount_bill']        =  number_format($payments->amount,2) ?? null;
          $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
          $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
          $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
          $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
 
    
         $epay =  EpaymentBillTest::where('Ref1',$request->Ref1)->first();
          if(is_null($epay)){
            $epay = new EpaymentBillTest;
          } 
          $epay->Ref1               =   $request->Ref1;
          $epay->CGDRef1            =  '6402090000016310';
          $epay->Status             =  0;
          $epay->PaymentDate        = $payments->start_date ?? null;
          $epay->InvoiceCode        = '0-0000000000000000000000'; 
          $epay->ReceiptCode        =  '123456';
          $epay->ReceiptCreateDate  =  date('Y-m-d H:i:s'); 
          $epay->Amount             = number_format($payments->amount,2) ?? null;
          $epay->save();

        return response()->json($response);

      
      }else if($request->out == 'pdf'){
       
        try {

          $mpdf = new Mpdf([
                            'format'            => 'A4',
                            'mode'              => 'utf-8',
                            'default_font_size' => '15',
                         ]);   

          $mpdf->SetDefaultBodyCSS('background', "url('images/LAW_Payin.png')");
          $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
          $mpdf->AddPage('P');
          $payments =   $cases->law_cases_payments_to;
          $datas = ['cases'=>$cases,'detail'=>$payments->law_cases_payments_detail_to];
          $html  = view('api/test.pay_law', $datas);
          $mpdf->WriteHTML($html);

           
          $title = "$cases->case_number".'_'.date('Ymd_hms').".pdf";  
          $mpdf->SetTitle($title);
          $path             = public_path('uploads/');
          $attach_path  = 'files/test';
          if(!File::isDirectory($path.$attach_path)){
              File::makeDirectory($path.$attach_path, 0777, true, true);
          }  
          $file_path = $path.$attach_path.'/'.$title;
       
          $mpdf->Output($file_path, "F");

          $arrContextOptions=array(
                                      "ssl"=>array(
                                      "verify_peer"=>false,
                                      "verify_peer_name"=>false,
                                     ),
                                  );
            $content_pdf =  file_get_contents( $path.$attach_path.'/'.$title , false, stream_context_create($arrContextOptions));
            //Specify that the content has PDF Mime Type
            header("Content-Type: application/pdf");
            //Display it
            echo $content_pdf;

        } catch (\Exception $e) {
  
            $arrContextOptions=array(
                              "ssl"=>array(
                              "verify_peer"=>false,
                              "verify_peer_name"=>false,
                              ),
                              );
            $content_pdf =  file_get_contents( url('images/LAW-Payin.pdf') , false, stream_context_create($arrContextOptions));
            //Specify that the content has PDF Mime Type
            header("Content-Type: application/pdf");
            //Display it
            echo $content_pdf;
        }

      }

}


 
    public function mail(Request $request){
        $auditors = BoardAuditor::findOrFail('40');
        return  $this->set_mail($auditors,$auditors->CertiLabs);
      }
    public function set_mail($auditors,$certi_lab) {

      $config = HP::getConfig();
      $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
      $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
      $EMail =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';
      if(!empty($certi_lab->DataEmailDirectorLABCC)){
          $mail_cc = $certi_lab->DataEmailDirectorLABCC;
          array_push($mail_cc,  auth()->user()->reg_email) ;
      }
      if(!is_null($certi_lab->email)){
        $mail = new  MailBoardAuditor([
                                 'email'=>  auth()->user()->email ?? 'admin@admin.com',
                                 'auditors' => $auditors,
                                 'certi_lab'=> $certi_lab,
                                 'url' => $url.'certify/applicant/auditor/'.$certi_lab->token,
                                 'email'=>  !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                                 'email_cc'=>  !empty($mail_cc) ? $mail_cc :  $EMail,
                                 'email_reply' => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                                  ]);
        Mail::to($certi_lab->email)->send($mail);
      }

    }

    public function insert_transaction_payin(Request $request){


        $arrContextOptions=array();
      if(!empty($request->id) && !empty($request->type)){
          $type  = $request->type;
          $id    = $request->id;
          if($type == 'IB'){
            $setting_payment = CertiSettingPayment::where('certify',2)->where('payin',1)->where('type',1)->first();
             $PayIn = CertiIBPayInOne::findOrFail($id);  // IB
             if(!is_null($PayIn)){
                  if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                    $arrContextOptions["ssl"] = array(
                                                    "verify_peer" => false,
                                                    "verify_peer_name" => false,
                                                );
                }
                 $app_no = $PayIn->CertiIBCostTo->app_no;
                $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$app_no-$PayIn->auditors_id", false, stream_context_create($arrContextOptions));

                 $api = json_decode($content);

                $transaction = HP::TransactionPayIn1($PayIn->id,(new CertiIBPayInOne)->getTable(),'2','1',$api,$app_no.'-'.$PayIn->auditors_id);
                echo "update success";
                echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($app_no) ?  $app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                echo '<br/>';
             }
  
          }
      }else{

      }

    
    }


    public function update_status_certify_lab(Request $request){

       $certiLabs =  CertiLab::where('status','>=','9')->get();
 
        if(count($certiLabs) > 0){
            foreach($certiLabs as $lab){
                  if($lab->status == 9){   // รับคำขอ
                      $lab->status = 6;
                      $lab->save();
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                  }else  if($lab->status == 10){   // ประมาณการค่าใช้จ่าย
                      $lab->status = 7;
                      $lab->save();
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                  }else  if($lab->status == 11){   // ขอความเห็นประมาณการค่าใช้จ่าย
                    $lab->status = 8;
                    $lab->save();
                  }else  if($lab->status == 12){   // อยู่ระหว่างแต่งตั้งคณะผู้ตรวจประเมิน
                     $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(!is_null($au)){
                          $au->vehicle =  2;
                          $au->step_id =  1; //อยู่ระหว่างแต่งตั้งคณะผู้ตรวจประเมิน
                          $au->save();  
                      }
                    $lab->status = 9;
                    $lab->save();
                    echo "update success";
                    echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                    echo '<br/>';
                  }else  if($lab->status == 13){   // ขอความเห็นแต่งคณะผู้ตรวจประเมิน

                      $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(is_null($au)){
                          $au = new BoardAuditor;
                      }
                      $au->app_certi_lab_id =  $lab->id;
                      $au->step_id          =  2; //ขอความเห็นแต่งคณะผู้ตรวจประเมิน
                      $au->save();  

                      $ca = Assessment::where('app_certi_lab_id',$lab->id)->where('auditor_id',$au->id)->first();
                      if(is_null($ca)){
                          $ca = new Assessment;
                      }
                      $ca->app_certi_lab_id =  $lab->id;
                      $ca->auditor_id       =  $au->id;
                      $ca->save();   

                      $group = AssessmentGroup::where('app_certi_assessment_id',$ca->id)->where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(is_null($group)){
                          $group = new AssessmentGroup;
                      }
                      $group->app_certi_assessment_id = $ca->id;
                      $group->app_certi_lab_id        = $lab->id ?? null;
                      $group->save();

                  
                      $ga = AssessmentGroupAuditor::where('app_certi_assessment_group_id',$group->id)->where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(is_null($ga)){
                          $ga = new AssessmentGroupAuditor;
                      }
                      $ga->app_certi_assessment_group_id  = $group->id;
                      $ga->app_certi_lab_id               = $lab->id ?? null;
                      $ga->auditor_id                     = $au->id;
                      $ga->save();
              
                      $lab->status = 10;
                      $lab->save();
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                   }else  if($lab->status == 14){   // เห็นชอบการแต่งตั้งคณะผู้ตรวจประเมิน       
                       $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                       if(!is_null($au)){
                        $au->remark = null;
                        $au->step_id =  3; //เห็นชอบการแต่งตั้งคณะผู้ตรวจประเมิน
                        $au->save();  
                          $assessment = Assessment::where('app_certi_lab_id',$lab->id)->where('auditor_id',$au->id)->first();
                          if(!is_null($assessment)){
                                  $cost_ass = CostAssessment::where('app_certi_lab_id',$lab->id)->where('app_certi_assessment_id',$assessment->id)->first();
                              if(is_null($cost_ass)){
                                  $cost_ass = new CostAssessment; 
                                  $cost_ass->app_certi_assessment_id  = $assessment->id  ?? null;
                                  $cost_ass->app_certi_lab_id         = $lab->id;
                                  $cost_ass->save();
                             }
                         }     
                      }
                      $lab->status = 10;
                      $lab->save();
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 15){   // แจ้งรายละเอียดค่าตรวจประเมิน       
                          $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                          if(!is_null($au)){
                          $au->remark = null;
                          $au->step_id =  4; //แจ้งรายละเอียดค่าตรวจประเมิน
                          $au->save();  
                            $assessment = Assessment::where('app_certi_lab_id',$lab->id)->where('auditor_id',$au->id)->first();
                            if(!is_null($assessment)){
                                    $cost_ass = CostAssessment::where('app_certi_lab_id',$lab->id)->where('app_certi_assessment_id',$assessment->id)->first();
                                if(!is_null($cost_ass)){
                                    $cost_ass = new CostAssessment; 
                                    $cost_ass->state      = 1;
                                    $cost_ass->save();
                                }
                            }     
                        }
                        $lab->status = 10;
                        $lab->save();
                        echo "update success";
                        echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                        echo '<br/>';
                    }else  if($lab->status == 16){   // แจ้งหลักฐานการชำระเงิน   

                        $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                        if(!is_null($au)){
                        $au->remark = null;
                        $au->step_id =  5; //แจ้งหลักฐานการชำระเงิน
                        $au->save();  
                          $assessment = Assessment::where('app_certi_lab_id',$lab->id)->where('auditor_id',$au->id)->first();
                          if(!is_null($assessment)){
                                  $cost_ass = CostAssessment::where('app_certi_lab_id',$lab->id)->where('app_certi_assessment_id',$assessment->id)->first();
                              if(!is_null($cost_ass)){
                                  $cost_ass = new CostAssessment; 
                                  $cost_ass->state      = 2;
                                  $cost_ass->save();
                              }
                          }     
                      }
                      $lab->status = 10;
                      $lab->save();    
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 17){   // ยืนยันการชำระเงินค่าตรวจประเมิน  
                      $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(!is_null($au)){
                        $au->step_id =  6; //ยืนยันการชำระเงินค่าตรวจประเมิน
                        $au->save();  
                      }
                    $lab->status = 10;
                    $lab->save();     
                    echo "update success";
                    echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                    echo '<br/>';
                    }else  if($lab->status == 18){   // ผ่านการตรวจสอบประเมิน     

                      $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(!is_null($au)){
                        $au->step_id = 9; // ไม่ผ่านการตรวจสอบประเมิน
                        $au->save();  
                      }
                      $lab->status = 10;
                      $lab->save();     
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 19){   // แก้ไขข้อบกพร่อง/ข้อสังเกต       

                      $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(!is_null($au)){
                        $au->step_id = 8; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                        $au->save();  
                      }
                      $lab->status = 10;
                      $lab->save();     
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 20){   // สรุปรายงานและเสนออนุกรรมการฯ   
                         $notice = Notice::where('app_certi_lab_id',$lab->id)->orderby('id','desc')->first();
                      if(!is_null($notice)){
                        $notice->status = 1;
                        $notice->degree = 7;
                        $notice->save(); 
                      }

                      $au = BoardAuditor::where('app_certi_lab_id',$lab->id)->orderBy('id', 'DESC')->first();
                      if(!is_null($au)){
                        $au->step_id = 10; // แก้ไขข้อบกพร่อง/ข้อสังเกต
                        $au->save();  
                      }
                      $lab->status = 12; 
                      $lab->save();     
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 21){   // รอยืนยันคำขอ       
                      $lab->status = 13; 
                      $lab->save(); 
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 22){   // ยืนยันจัดทำใบรับรอง       
                      $lab->status = 14; 
                      $lab->save(); 
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 23){   // แจ้งรายละเอียดการชำระค่าใบรับรอง       
                      $lab->status = 15;
                      $lab->save();  
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 24){   // แจ้งหลักฐานการชำระค่าใบรับรอง       
                      $lab->status = 16;
                      $lab->save();   
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 25){   // ยืนยันการชำระเงินค่าใบรับรอง       
                      $lab->status = 17;
                      $lab->save();   
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 26){   // ออกใบรับรอง และ ลงนาม       
                      $lab->status = 18;
                      $lab->save();   
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                    }else  if($lab->status == 27){   // ลงนามเรียบร้อย       
                      $lab->status = 18;
                      $lab->save();   
                      echo "update success";
                      echo '-<span style="color:#33cc33;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                      echo '<br/>';
                      
                   }else{
                        echo "update success";
                        echo '-<span style="color:#eea236;">เลขที่ใบสมัคร : '.(  !empty($lab->app_no) ?  $lab->app_no.' วันที่ '.HP::DateTimeThai( date('Y-m-d H:i:s')) : null ).'</span>';
                        echo '<br/>';
                   }
            }
        }
    
    }


    public function export_excel_certilab()
    {
          $datas =      CertificateExport::get();
    
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          //หัวรายงาน
          $sheet->setCellValue('A1', 'รายชื่อใบรับรอง LAB');
          $sheet->mergeCells('A1:H1');
          $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
          $sheet->getStyle("A1")->getFont()->setSize(18);

          $sheet->setCellValue('A2','ข้อมูล ณ วันที่ '.HP::DateTimeFullThai(date('Y-m-d H:i')));
          $sheet->mergeCells('A2:H2');
          $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');


          //หัวตาราง
          $sheet->setCellValue('A3', 'ลำดับ');
          $sheet->setCellValue('B3', 'ผู้ประกอบการ');
          $sheet->setCellValue('C3', 'เลข 13 หลัก');
          $sheet->setCellValue('D3', 'เจ้าหน้าที่รับผิดชอบ');
          $sheet->setCellValue('E3', 'เลขที่ใบรับรอง');
          $sheet->setCellValue('F3', 'วันที่เลขที่ใบรับรอง');
          $sheet->setCellValue('G3', 'วันที่เลขที่ใบรับรอง');
  
 
          $public = public_path();
          $attach_path1 = 'files/applicants/check_files/';
 

          $row = 3;//start row
          $i  = 1;
          foreach ($datas as $item) {
            $certi =    $item->CertiLabTo;
            if(!is_null($certi)){
              $row++;
              $sheet->setCellValue('A'.$row,  $i++);
              $sheet->setCellValue('B'.$row, !empty($certi->name) ? $certi->name : '' );

              $sheet->setCellValue('C'.$row, !empty($certi->tax_id) ? $certi->tax_id : '' );
              $sheet->getStyle('C'.$row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

              $sheet->setCellValue('D'.$row, !empty($certi->check->FullRegName) ? $certi->check->FullRegName : '' );

 
              if(HP::checkFileStorage($attach_path1.'/' .$certi->attach_pdf)){
                $url      =     url('/certify/check_files_lab/'. rtrim(strtr(base64_encode($certi->id), '+/', '-_'), '=') );
                $sheet->setCellValue('E'.$row,  !empty($item->certificate_no) ? $item->certificate_no : '-' )->getHyperLink('E'.$row )->setUrl($url);
                $sheet->getStyle('E'.$row)->applyFromArray(array( 
                                  'font'  => array(
                                      'bold'  => true,
                                      'color' => array('rgb' => '0000FF')
                                  )));
              }else{
                 $sheet->setCellValue('E'.$row, !empty($item->certificate_no) ? $item->certificate_no : '' );
              }

              $sheet->setCellValue('F'.$row, !empty($item->certificate_date_start) ? HP::revertDate($item->certificate_date_start,true) : '' );
              $sheet->setCellValue('G'.$row, !empty($item->certificate_date_end) ? HP::revertDate($item->certificate_date_end,true) : '' ); 
 
            }
          }

          //Set Border Style
          $styleArray = [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000'],
                                ],
                            ]
                        ];
          $sheet ->getStyle('A3:'.'G'.$row)->applyFromArray($styleArray);
          
          //Set Column Width
          $sheet->getColumnDimension('A')->setAutoSize(true);
          $sheet->getColumnDimension('B')->setAutoSize(true);
          $sheet->getColumnDimension('C')->setAutoSize(true);
          $sheet->getColumnDimension('D')->setAutoSize(true);
          $sheet->getColumnDimension('E')->setAutoSize(true);
          $sheet->getColumnDimension('F')->setAutoSize(true);
          $sheet->getColumnDimension('G')->setAutoSize(true);

          $sheet->getStyle('A3:'.'G3')->getAlignment()->setHorizontal('center');
          $sheet->getStyle('A4:'.'A'.$row)->getAlignment()->setHorizontal('center');
          $sheet->getStyle('B4:'.'B'.$row)->getAlignment()->setHorizontal('right');
          $sheet->getStyle('C4:'.'C'.$row)->getAlignment()->setHorizontal('right');
          $sheet->getStyle('D4:'.'D'.$row)->getAlignment()->setHorizontal('right');
          $sheet->getStyle('E4:'.'E'.$row)->getAlignment()->setHorizontal('right');
          $sheet->getStyle('F4:'.'F'.$row)->getAlignment()->setHorizontal('right');
          $sheet->getStyle('G4:'.'G'.$row)->getAlignment()->setHorizontal('right');

          $filename = 'รายชื่อใบรับรอง_'.date('Hi_dmY').'.xlsx';
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="'.$filename.'"');
          $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
          $writer->save("php://output");

          exit;
    }



}

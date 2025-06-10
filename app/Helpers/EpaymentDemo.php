<?php

namespace App\Helpers;

use HP;
use File;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\CertificateExport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\Lab\MailBoardAuditor;
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\EpaymentBillTest;
use App\Models\Law\Cases\LawCasesForm;  
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Certify\Applicant\Assessment;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Certify\Applicant\CostAssessment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Certify\Applicant\AssessmentGroup;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
use App\Models\Certify\ApplicantIB\CertiIBPayInOne;
use App\Models\Certify\Applicant\AssessmentGroupAuditor;

class EpaymentDemo
{


    public static function pmt1($ref1, $pid, $out)
    {
        
        $response = [];
         $str =  explode("-",$ref1);
        if(count($str) == 4){
          $app_no =  $str['0'].'-'.$str['1'].'-'.$str['2'];
        }else{
          $app_no =  $ref1;
        }  

        // dd($app_no);
        
        $pid    =  $pid;
        $certi_cb = CertiCb::where('app_no',$app_no)->first();
        $certi_ib = CertiIb::where('app_no',$app_no)->first(); 
        $certi_lab = CertiLab::where('app_no',$app_no)->first(); 
        $export_lab = CertificateExport::where('reference_refno',$app_no)->first();
        $export_cb  = CertiCBExport::where('reference_refno',$app_no)->first();
        $export_ib  = CertiIBExport::where('reference_refno',$app_no)->first();
        // dd($app_no, $pid, $out);
        // dd($ref1, $pid, $out);
    if(!is_null($certi_cb) && $out == 'json'){
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
            // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
            // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
            // กำหนดวันเริ่มต้นเป็นปัจจุบัน
            $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
            // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
            $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
            $response['allPaymentAmount']   = "36,000.00";
            $response['amount_bill']        = "36,000.00";
            $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
            $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
            $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
            $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
            $response['app_certi_assessment_id']       = "test";
            $response['status_confirmed']   = "0";
            $response['auditor']            = "คณะ";

            $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
            if(is_null($epay)){
              $epay = new EpaymentBillTest;
            } 
            $epay->Ref1               =   $ref1;
            $epay->CGDRef1            =  '6402090000016310';
            $epay->Status             =  0;
            $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
            $epay->InvoiceCode        = '0-0000000000000000000000'; 
            $epay->ReceiptCode        =  '123456';
            $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
            $epay->Amount             =  "36000.00";
            $epay->save();

           return response()->json($response);
    }else  if(!is_null($certi_ib) && $out == 'json'){
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
            // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
            // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
            // กำหนดวันเริ่มต้นเป็นปัจจุบัน
            $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
            // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
            $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
            $response['allPaymentAmount']   = "36,000.00";
            $response['amount_bill']        = "36,000.00";
            $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
            $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
            $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
            $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
            $response['status']             = "0";
            $response['auditor']            = "คณะ";

            $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
            if(is_null($epay)){
              $epay = new EpaymentBillTest;
            }
            $epay->Ref1               =   $ref1;
            $epay->CGDRef1            =  '6402090000016310';
            $epay->Status             =  0;
            $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
            $epay->InvoiceCode        = '0-0000000000000000000000'; 
            $epay->ReceiptCode        =  '123456';
            $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
            $epay->Amount             =  "36000.00";
            $epay->save();

           return response()->json($response);
      }else  if(!is_null($certi_lab) && $out == 'json')
      {

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
            // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
            // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
            // กำหนดวันเริ่มต้นเป็นปัจจุบัน
            $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
            // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
            $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
            $response['allPaymentAmount']   = "36,000.00";
            $response['amount_bill']        = "36,000.00";
            $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
            $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
            $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
            $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
            $response['status']             = "0";
            $response['auditor']            = "คณะ";


            $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();

            if(is_null($epay)){
              $epay = new EpaymentBillTest;
            }
            $epay->Ref1               =   $ref1;
            $epay->CGDRef1            =  '6402090000016310';
            $epay->Status             =  0;
            $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
            $epay->InvoiceCode        = '0-0000000000000000000000'; 
            $epay->ReceiptCode        =  '123456';
            $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
            $epay->Amount             =  "36000.00";
            $epay->save();

           return response()->json($response);

     } else  if(!is_null($export_lab) && $out == 'json'){
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
        // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
        // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
        // กำหนดวันเริ่มต้นเป็นปัจจุบัน
        $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
        // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
        $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
       $response['allPaymentAmount']   = "36,000.00";
       $response['amount_bill']        = "36,000.00";
       $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
       $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
       $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['status']             = "0";
       $response['auditor']            = "คณะ";

       $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
       if(is_null($epay)){
         $epay = new EpaymentBillTest;
       }
       $epay->Ref1               =   $ref1;
       $epay->CGDRef1            =  '6402090000016310';
       $epay->Status             =  0;
       $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
       $epay->InvoiceCode        = '0-0000000000000000000000'; 
       $epay->ReceiptCode        =  '123456';
       $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
       $epay->Amount             =  "36000.00";
       $epay->save();

      return response()->json($response);

      

     }else  if(!is_null($export_cb) && $out == 'json'){
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
      // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
      // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
      // กำหนดวันเริ่มต้นเป็นปัจจุบัน
      $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
      // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
      $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
       $response['allPaymentAmount']   = "36,000.00";
       $response['amount_bill']        = "36,000.00";
       $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
       $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
       $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['status']             = "0";
       $response['auditor']            = "คณะ";

       $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
       if(is_null($epay)){
         $epay = new EpaymentBillTest;
       }
       $epay->Ref1               =   $ref1;
       $epay->CGDRef1            =  '6402090000016310';
       $epay->Status             =  0;
       $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
       $epay->InvoiceCode        = '0-0000000000000000000000'; 
       $epay->ReceiptCode        =  '123456';
       $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
       $epay->Amount             =  "36000.00";
       $epay->save();

      return response()->json($response);
    }else  if(!is_null($export_ib) && $out == 'json'){
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
      // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
      // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
      // กำหนดวันเริ่มต้นเป็นปัจจุบัน
      $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
      // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
      $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
       $response['allPaymentAmount']   = "36,000.00";
       $response['amount_bill']        = "36,000.00";
       $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
       $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
       $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['status']             = "0";
       $response['auditor']            = "คณะ";

       $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
       if(is_null($epay)){
         $epay = new EpaymentBillTest;
       } 
       $epay->Ref1               =   $ref1;
       $epay->CGDRef1            =  '6402090000016310';
       $epay->Status             =  0;
       $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
       $epay->InvoiceCode        = '0-0000000000000000000000'; 
       $epay->ReceiptCode        =  '123456';
       $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
       $epay->Amount             =  "36000.00";
       $epay->save();

      return response()->json($response);

      
     }
     else if(Str::contains($ref1, 'SurLab') && $out == 'json')
     {
      
       $tracking = Tracking::where('reference_refno',$app_no)->first();
       $export_lab = CertificateExport::find($tracking->ref_id);

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
        // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
        // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
        // กำหนดวันเริ่มต้นเป็นปัจจุบัน
        $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
        // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
        $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
       $response['allPaymentAmount']   = "36,000.00";
       $response['amount_bill']        = "36,000.00";
       $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
       $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
       $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
       $response['status']             = "0";
       $response['auditor']            = "คณะ";

       $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
       if(is_null($epay)){
         $epay = new EpaymentBillTest;
       }
       $epay->Ref1               =   $ref1;
       $epay->CGDRef1            =  '6402090000016310';
       $epay->Status             =  0;
       $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
       $epay->InvoiceCode        = '0-0000000000000000000000'; 
       $epay->ReceiptCode        =  '123456';
       $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
       $epay->Amount             =  "36000.00";
       $epay->save();

      //  dd($response);

      return response()->json($response);
     }
     else if(Str::contains($ref1, 'SurIB') && $out == 'json')
        {
          
          $tracking = Tracking::where('reference_refno',$app_no)->first();
          $certib_export = CertiIBExport::find($tracking->ref_id);

          $response['pid']                = $pid;
          $response['Ref1']               = $tracking->reference_refno;
          $response['returnCode']         = '000';
          $response['appno']              = $tracking->reference_refno;
          $response['bus_name']           = $certib_export->name_unit;
          $response['address']            = $certib_export->address;
          $response['allay']              = $certib_export->allay;
          $response['village_no']         = $certib_export->village_no;
          $response['road']               = $certib_export->road;
          $response['district_id']        = $certib_export->district_name ?? null;
          $response['amphur_id']          = $certib_export->amphur_name ?? null;
          $response['province_id']        = $certib_export->province_name ?? null;
          $response['postcode']           = $certib_export->postcode;
          $response['email']              = $certib_export->applications->email ?? null;
          $response['app_check']          = "30000";
          $response['vatid']              = "0125542005151";
          $response['Perpose']            =  null;
          $response['AmountCert']         = "5000";
          $response['billNo']             = "64020900000163";
          $response['CGDRef1']            = "6402090000016310";
          $response['CGDRef2']            = "64041072";
            // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
            // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
            // กำหนดวันเริ่มต้นเป็นปัจจุบัน
            $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
            // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
            $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
          $response['allPaymentAmount']   = "36,000.00";
          $response['amount_bill']        = "36,000.00";
          $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
          $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
          $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
          $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
          $response['status']             = "0";
          $response['auditor']            = "คณะ";

          $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
          if(is_null($epay)){
            $epay = new EpaymentBillTest;
          }
          $epay->Ref1               =   $ref1;
          $epay->CGDRef1            =  '6402090000016310';
          $epay->Status             =  0;
          $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
          $epay->InvoiceCode        = '0-0000000000000000000000'; 
          $epay->ReceiptCode        =  '123456';
          $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
          $epay->Amount             =  "36000.00";
          $epay->save();

          return response()->json($response);
        }

        else if(Str::contains($ref1, 'SurCB') && $out == 'json')
        {
          // dd($ref1);
          $tracking = Tracking::where('reference_refno',$app_no)->first();
          $certcb_export = CertiCBExport::find($tracking->ref_id);

          $response['pid']                = $pid;
          $response['Ref1']               = $tracking->reference_refno;
          $response['returnCode']         = '000';
          $response['appno']              = $tracking->reference_refno;
          $response['bus_name']           = $certcb_export->name_unit;
          $response['address']            = $certcb_export->address;
          $response['allay']              = $certcb_export->allay;
          $response['village_no']         = $certcb_export->village_no;
          $response['road']               = $certcb_export->road;
          $response['district_id']        = $certcb_export->district_name ?? null;
          $response['amphur_id']          = $certcb_export->amphur_name ?? null;
          $response['province_id']        = $certcb_export->province_name ?? null;
          $response['postcode']           = $certcb_export->postcode;
          $response['email']              = $certcb_export->applications->email ?? null;
          $response['app_check']          = "30000";
          $response['vatid']              = "0125542005151";
          $response['Perpose']            =  null;
          $response['AmountCert']         = "5000";
          $response['billNo']             = "64020900000163";
          $response['CGDRef1']            = "6402090000016310";
          $response['CGDRef2']            = "64041072";
            // $response['invoiceStartDate']   = "2021-07-07T16:02:00.00+07:00";
            // $response['invoiceEndDate']     = "2021-08-06T00:00:00.00+07:00";
            // กำหนดวันเริ่มต้นเป็นปัจจุบัน
            $response['invoiceStartDate'] = Carbon::now()->format('Y-m-d\TH:i:s.00P');
            // กำหนดวันสิ้นสุดโดยเพิ่ม 30 วัน
            $response['invoiceEndDate'] = Carbon::now()->addDays(30)->format('Y-m-d\TH:i:s.00P');
          $response['allPaymentAmount']   = "36,000.00";
          $response['amount_bill']        = "36,000.00";
          $response['allAmountTH']        = "สามหมื่นหกพันบาทถ้วน";
          $response['barcodeString']      = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
          $response['barcodeSub']         = "|099400015951015 6402090000016310 64041072 3600000";
          $response['QRCodeString']       = "|099400015951015%0D6402090000016310%0D64041072%0D3600000";
          $response['status']             = "0";
          $response['auditor']            = "คณะ";

          $epay =  EpaymentBillTest::where('Ref1',$ref1)->first();
         
          if(is_null($epay)){
           
            $epay = new EpaymentBillTest;
          }
          $epay->Ref1               =   $ref1;
          $epay->CGDRef1            =  '6402090000016310';
          $epay->Status             =  0;
          $epay->PaymentDate        =  date('Y-m-d H:i:s'); 
          $epay->InvoiceCode        = '0-0000000000000000000000'; 
          $epay->ReceiptCode        =  '123456';
          $epay->ReceiptCreateDate  = date('Y-m-d H:i:s'); 
          $epay->Amount             =  "36000.00";
          $epay->save();
  // dd($epay,$ref1);
          return response()->json($response);
        }
     
     else if($out == 'pdf'){
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
 public function pmt2($ref1,$paydate)
 {
    $response = [];

    if(!empty($ref1)){
          EpaymentBillTest::where('Ref1',$ref1)->update([
            'Status' => 1
          ]);
          $epays =  EpaymentBillTest::where('Ref1',$ref1)->get();
    } else if(!empty($paydate)){
          $epays =  EpaymentBillTest::whereDate('PaymentDate',$paydate)->where('Status',1)->get();
   } else{
         $epays =  EpaymentBillTest::whereDate('PaymentDate',date('Y-m-d'))->where('Status',1)->get();
    }

    // dd( $epays->count(),$ref1);

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


}

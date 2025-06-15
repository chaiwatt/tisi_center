<?php

namespace App\Http\Controllers;

use HP;

use Storage;
use App\User;
use stdClass;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\AttachFile;
use App\CertificateExport;
use App\Helpers\TextHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Certify\CbReportInfo;
use App\Certify\IbReportInfo;
use App\Helpers\EpaymentDemo;
use App\Models\Besurv\Signer;
use App\Certify\CbReportTwoInfo;
use App\Certify\IbReportTwoInfo;
use Yajra\Datatables\Datatables;
use App\Mail\Lab\OtpNofitication;
use App\Services\CreateIbScopePdf;
use Illuminate\Support\Facades\DB;
use App\Services\CreateLabScopePdf;
use App\Models\Certificate\Tracking;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Certify\LabReportInfo;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\SettingConfig;
use App\Services\CreateCbScopeBcmsPdf;
use App\Services\CreateCbScopeIsicPdf;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Certify\SetStandardUser;
use Illuminate\Support\Facades\Artisan;
use App\Mail\CB\CbDocReviewAuditorsMail;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\BoardAuditorDate;
use App\Models\Certify\TransactionPayIn;
use App\Mail\Lab\DirectorSignNotification;
use App\Models\Bcertify\BoardAuditoExpert;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Certificate\TrackingStatus;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Certify\SetStandardUserSub;
use App\Models\Certify\SignCertificateOtp;
use App\Services\CreateCbMessageRecordPdf;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\CertiSettingPayment;
use App\Services\CreateLabMessageRecordPdf;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingPayInOne;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\NoticeItem;
use App\Models\Certify\SendCertificateLists;
use App\Services\CreateTrackingLabReportPdf;
use App\Services\CreateCbAssessmentReportPdf;
use App\Services\CreateIbAssessmentReportPdf;
use App\Models\Certificate\CbDocReviewAuditor;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingInspection;
use App\Services\CreateLabAssessmentReportPdf;
use App\Models\Certify\Applicant\CheckExaminer;
use App\Jobs\QueSendEmailGeneratePayInOneLabJob;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certify\Applicant\CertiLabExport;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\MessageRecordTransaction;
use App\Services\CreateCbAssessmentReportTwoPdf;
use App\Services\CreateIbAssessmentReportTwoPdf;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certify\Applicant\CostCertificate;
use App\Services\CreateLabAssessmentReportTwoPdf;
use App\Http\Controllers\API\Checkbill2Controller;
use App\Models\Bcertify\BoardAuditoExpertTracking;
use App\Models\Certify\Applicant\CertifyTestScope;
use App\Services\CreateTrackingCbMessageRecordPdf;
use App\Services\CreateTrackingIbMessageRecordPdf;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBPayInTwo;
use App\Services\CreateTrackingLabMessageRecordPdf;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\Applicant\CertifyLabCalibrate;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Services\CreateTrackingCbAssessmentReportOnePdf;
use App\Services\CreateTrackingCbAssessmentReportTwoPdf;
use App\Services\CreateTrackingIbAssessmentReportOnePdf;
use App\Services\CreateTrackingIbAssessmentReportTwoPdf;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;
use App\Services\CreateTrackingLabAssessmentReportOnePdf;
use App\Services\CreateTrackingLabAssessmentReportTwoPdf;
use App\Models\Certificate\SignAssessmentTrackingReportTransaction;

class MyTestController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {

        $this->attach_path = 'files/applicants/check_files/';
    }
    public function callCheckBill($ref1)
    {
         // สร้าง Request Object และเพิ่มข้อมูลที่ต้องการส่งไป
        $request = new Request();
        $request->merge(['ref1' => $ref1]); // ใส่ข้อมูล 'refNo'
        // dd($request->all());
        $checkbillController = new Checkbill2Controller();
        return $checkbillController->check_bill($request);
    }


    public function callCreateBill()
    {
        $ref1 = "SurLab-68-331-3311737346387";
        $pid = 26;
        $out = "json";
        $epaymentDemoController = new EpaymentDemo();
        return $epaymentDemoController->pmt1($ref1, $pid, $out);
    }

    public function index()
    {

        $nowTimeStamp = Carbon::now()->addDays(15)->timestamp;

        // สร้าง Token โดยใช้ base64_encode
        $encodedTimestamp = base64_encode($nowTimeStamp);

        // สร้าง Token พร้อมข้อมูลสุ่ม
        $token = Str::random(30) . '_' . $encodedTimestamp;

        $parts = explode('_', $token);
        $randomString = $parts[0]; // ส่วน String แบบสุ่ม
        $encodedTimestamp = $parts[1]; // Timestamp ที่เข้ารหัส

        // ถอดรหัส Timestamp โดยใช้ base64_decode
        $originalTimestamp = base64_decode($encodedTimestamp);

        // แปลงตัวเลขที่ได้เป็นเวลา (Carbon DateTime)
        $expiryDateTime = Carbon::createFromTimestamp($originalTimestamp);
        
        // ตรวจสอบว่าเวลาปัจจุบันน้อยกว่าเวลา expiryDateTime หรือไม่
        if (Carbon::now()->lt($expiryDateTime)) {
            dd($token, $nowTimeStamp, $originalTimestamp, Carbon::now(), $expiryDateTime);
        }else{
            dd('expire');
        }
    }


    public function getmaillist()
    {
        $board = BoardAuditor::find(1712);

        $signerEmails = $board->messageRecordTransactions()
                      ->with('signer.user')
                      ->get()
                      ->pluck('signer.user.reg_email')
                      ->filter() // กรองค่า null ออก
                      ->unique()
                      ->toArray();
        dd($signerEmails);
        
    }

    public function getCompayInfo()
    {
      $certiLab = CertiLab::find(1879);
      dd($certiLab->BelongsInformation);
    }
    public function apiPmt1()
    {
      $content =  file_get_contents('http://127.0.0.1:8082/api/v1/pmt1.php?pid=4&out=json&Ref1=IB-67-004', false, stream_context_create(array()));
     
      $api = json_decode($content,true);
      dd($api) ;
    }


    public function apiPdf()
    {
      $content_pdf =  file_get_contents('http://127.0.0.1:8082/images/Payin2.pdf', false, stream_context_create(array()));
      // dd($content_pdf);
      //Specify that the content has PDF Mime Type
      header("Content-Type: application/pdf");
      //Display it
      echo $content_pdf;
    }

    public function downloadpdf()
    {
      $url = 'http://127.0.0.1:8082/images/Payin2.pdf';
      $app_no = 'RQ-TEST-66-434';

      // ดาวน์โหลดเนื้อหา PDF
      $pdf_content = file_get_contents($url);

      // ลบ "RQ-" ออกจาก $app_no และเปลี่ยน "-" เป็น "_"
      $no  = str_replace("RQ-","",$app_no);
      $no  = str_replace("-", "_", $no);

      // สร้าง path สำหรับบันทึกไฟล์
      $attach_path  = 'files/applicants/check_files/' . $no;
      $fullFileName = $no . '-' . date('Ymd_His') . '.pdf';

      // บันทึกไฟล์ใน storage path
      $storagePath = Storage::disk('uploads')->put($attach_path . '/' . $fullFileName, $pdf_content);
      // แสดง path เต็มที่ไฟล์ถูกบันทึก
      
      // $fullStoragePath = public_path('uploads/' . $attach_path . '/' . $fullFileName);
      return  $no.'/'.$fullFileName;
    }

   // public function downloadpdf()
   // {
   //    $app_no = 'RQ-TEST-66-434';

   //    // ลบ "RQ-" ออกจาก $app_no และเปลี่ยน "-" เป็น "_"
   //    $no  = str_replace("RQ-", "", $app_no);
   //    $no  = str_replace("-", "_", $no);

   //    // สร้าง path สำหรับบันทึกไฟล์
   //    $attach_path  = 'files/applicants/check_files/' . $no;
   //    $fullFileName = $no . '-' . date('Ymd_His') . '.txt';

   //    // เนื้อหาของ text file ที่ต้องการบันทึก
   //    $text_content = "This is a test file content.";

   //    // บันทึกไฟล์ใน uploads disk
   //    $storagePath = Storage::disk('uploads')->put($attach_path . '/' . $fullFileName, $text_content);

   //    // แสดง path เต็มที่ไฟล์ถูกบันทึก
   //    $fullStoragePath = public_path('uploads/' . $attach_path . '/' . $fullFileName);
   //    dd($fullStoragePath);
   // }


   public function getCalScopeData()
   {

    $latestCertiLab = CertiLab::latest('created_at')->first();

    $company = [];

    if ($latestCertiLab) {
        // ดึง LabCalRequest ที่มี app_certi_lab_id ตรงกับ $latestCertiLab->id (ทุกรายการ)
        $labCalRequests = LabCalRequest::with([
            'labCalTransactions.labCalMeasurements.labCalMeasurementRanges'
        ])->where('app_certi_lab_id', $latestCertiLab->id)->get();

        // สร้างข้อมูลในรูปแบบของ $company
        foreach ($labCalRequests as $key => $labCalRequest) {
            $data = [];
            foreach ($labCalRequest->labCalTransactions as $transaction) {


              $calibration_branch_name_en = null;

              if($transaction->category !== null){
                $calibrationBranch = CalibrationBranch::find($transaction->category);
                if($calibrationBranch!==null)
                {
                  $calibration_branch_name_en  = $calibrationBranch->title_en;
                }
              }

                $instrument_name = null;

                if($transaction->instrument !== null){
                  $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::find($transaction->instrument);
                  if($calibrationBranchInstrumentGroup!==null)
                  {
                    $instrument_name  = $calibrationBranchInstrumentGroup->name;
                  }
                }

                $instrument_two_name = null;

                if($transaction->instrument_two !== null){
                  $calibrationBranchInstrument = CalibrationBranchInstrument::find($transaction->instrument_two);
                  if($calibrationBranchInstrument!==null)
                  {
                    $instrument_two_name  = $calibrationBranchInstrument->name;
                  }
                }

                $transactionData = [
                    'index' => $transaction->index,
                    'category' => $calibration_branch_name_en,
                    'category_th' => $transaction->category_th,
                    'instrument' => $instrument_name,
                    'instrument_two' => $instrument_two_name,
                    'description' => $transaction->description,
                    'standard' => $transaction->standard,
                    'code' => $transaction->code,
                    'key' => $transaction->key,
                    'measurements' => [],
                ];

                foreach ($transaction->labCalMeasurements as $measurement) {
                    $measurementData = [
                        'name' => $measurement->name,
                        'type' => $measurement->type,
                        'ranges' => [],
                    ];

                    foreach ($measurement->labCalMeasurementRanges as $range) {
                        $rangeData = [
                            'description' => $range->description,
                            'range' => $range->range,
                            'uncertainty' => $range->uncertainty,
                        ];

                        $measurementData['ranges'][] = $rangeData;
                    }

                    $transactionData['measurements'][] = $measurementData;
                }

                $data[] = $transactionData;
            }

            // dd($labCalRequest->no);
            // สร้างชุดข้อมูลที่แบ่งตาม id, station_type, lab_type
              $company[] = [
                "id" => $key + 1,  // ให้เพิ่ม 1 เพื่อเริ่มจาก 1
                "station_type" => $key === '0' ? "main" : "branch" . ($key),  // กำหนดประเภท station
                "lab_type" => $labCalRequest->certiLab->lab_type,  // lab_type จาก certiLab
                "app_certi_lab" => $labCalRequest->certiLab,  // lab_type จาก certiLab
                // เพิ่มคีย์ใหม่จากฟิลด์ใน lab_cal_requests
                "no" => trim($labCalRequest->no ?? '') ?: null,
                "moo" => trim($labCalRequest->moo ?? '') ?: null,
                "soi" => trim($labCalRequest->soi ?? '') ?: null,
                "street" => trim($labCalRequest->street ?? '') ?: null,
                "province_name" => trim($labCalRequest->province_name ?? '') ?: null,
                "amphur_name" => trim($labCalRequest->amphur_name ?? '') ?: null,
                "tambol_name" => trim($labCalRequest->tambol_name ?? '') ?: null,
                "postal_code" => trim($labCalRequest->postal_code ?? '') ?: null,
                "no_eng" => trim($labCalRequest->no_eng ?? '') ?: null,
                "moo_eng" => trim($labCalRequest->moo_eng ?? '') ?: null,
                "soi_eng" => trim($labCalRequest->soi_eng ?? '') ?: null,
                "street_eng" => trim($labCalRequest->street_eng ?? '') ?: null,
                "tambol_name_eng" => trim($labCalRequest->tambol_name_eng ?? '') ?: null,
                "amphur_name_eng" => trim($labCalRequest->amphur_name_eng ?? '') ?: null,
                "province_name_eng" => trim($labCalRequest->province_name_eng ?? '') ?: null,

                "scope" => $data

            ];
        }
    }

    // ส่งข้อมูลกลับในรูปแบบ JSON
    return response()->json($company);
   }

    public function getPageList($scopes,$pdfData,$details)
    {

        $pageArray = $this->getFirstPageList($scopes,$pdfData,$details);

        $firstPageArray = $pageArray[0];

        // ดึงค่า index ด้วย array_map และ array access
        $indexes = array_map(function ($item) {
            return $item->index;
        }, $firstPageArray[0]);

        $filteredScopes = array_filter($scopes, function ($item) use ($indexes) {
            return !in_array($item->index, $indexes);
        });
        
        $filteredScopes = array_values($filteredScopes);

        $pageArray = $this->getOtherPageList($filteredScopes,$pdfData,$details);

        $mergedArray = array_merge($firstPageArray, $pageArray);
        return $mergedArray;
    }
    
    public function getFirstPageList($scopes,$pdfData,$details)
    {
        $type = 'I';
        $fontDirs = [public_path('pdf_fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA' 	=>  $type == 'F' ? true : false,
            'PDFAauto'	 =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
            'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
            'margin_left'      => 8, // ระบุขอบด้านซ้าย
            'margin_right'     => 3, // ระบุขอบด้านขวา
            // 'margin_top'       => 97, // ระบุขอบด้านบน
            // 'margin_bottom'    => 40, // ระบุขอบด้านล่าง
            'margin_top'       => 108, // ระบุขอบด้านบน
            'margin_bottom'    => 40, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);         

        $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        
        $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, '', [170, 4]); // กำหนด opacity, , ตำแหน่ง
        $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark

        $footer = view('pdf.scope.calibration.cal-scope-footer', [
            'qrImage' => null,
            'sign1Image' => null,
            'sign2Image' => null,
            'sign3Image' => null
        ]);

        $viewBlade = "pdf.scope.calibration.cal-scope-first-header";

        if ($pdfData->siteType == "multi")
        {
            $viewBlade = "pdf.scope.calibration.cal-scope-first-header-multi";
        }
        // $scopes = $details->scope;
        $header = view($viewBlade, [
          'branchNo' => null,
          'company' => $details,
          'pdfData' => $pdfData
        ]);
        $mpdf->SetHTMLHeader($header,2);
        $mpdf->SetHTMLFooter($footer,2);
        
        $html = view('pdf.scope.calibration.pdf-cal-scope', [
                'scopes' => collect($scopes)
            ]);
        $mpdf->WriteHTML($html);

        // แปลง PDF เป็น String
        $pdfContent = $mpdf->Output('', 'S');

        // ใช้ PdfParser อ่าน PDF จาก String
        $parser = new Parser();
        $pdf = $parser->parseContent($pdfContent);

        $chunks = $this->generateRangesWithData($scopes,$pdf);

        $firstPage = array_slice($chunks, 0, 1);

        $remainingItems = array_slice($chunks, 1);

        return [$firstPage,$remainingItems,$chunks];
    }

    public function getOtherPageList($scope,$pdfData,$details)
    {
        $type = 'I';
        $fontDirs = [public_path('pdf_fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA' 	=>  $type == 'F' ? true : false,
            'PDFAauto'	 =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
            'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
            'margin_left'      => 8, // ระบุขอบด้านซ้าย
            'margin_right'     => 3, // ระบุขอบด้านขวา
            'margin_top'       => 97, // ระบุขอบด้านบน
            'margin_bottom'    => 40, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);         

        // $data = $this->getMeasurementsData()->getData();

        $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
        $mpdf->WriteHTML($stylesheet, 1);

        // $company = $data->main;
        
        $mpdf->SetWatermarkImage(public_path('images/nc_hq.png'), 1, '', [170, 4]); // กำหนด opacity, , ตำแหน่ง
        $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark

        $footer = view('pdf.scope.calibration.cal-scope-footer', [
            'qrImage' => null,
            'sign1Image' => null,
            'sign2Image' => null,
            'sign3Image' => null
        ]);

        $header = view('pdf.scope.calibration.cal-scope-first-header', [
          'company' => $details,
          'pdfData' => $pdfData
        ]);
        $mpdf->SetHTMLHeader($header,2);
        $mpdf->SetHTMLFooter($footer,2);
        
        $html = view('pdf.scope.calibration.pdf-cal-scope', [
                'scopes' => collect($scope)
            ]);
        $mpdf->WriteHTML($html);

        // แปลง PDF เป็น String
        $pdfContent = $mpdf->Output('', 'S');

        // ใช้ PdfParser อ่าน PDF จาก String
        $parser = new Parser();
        $pdf = $parser->parseContent($pdfContent);

        $chunks = $this->generateRangesWithData($scope,$pdf);
        
        // $firstPage = reset($chunks);

        // $remainingItems = array_slice($chunks, 1);

        // dd($chunks,$firstPage,$remainingItems);

        return $chunks;
   
    }

    function generateRangesWithData($data, $pdf)
    {
        $maxNumber = []; // เก็บตัวเลขที่มากที่สุดของแต่ละหน้า

        // ดึงข้อความและค้นหาตัวเลขที่มากที่สุดในแต่ละหน้า
        foreach ($pdf->getPages() as $pageNumber => $page) {
            preg_match_all('/\*(\d+)\*/', $page->getText(), $matches); // ค้นหาตัวเลขในรูปแบบ *number*
            if (!empty($matches[1])) {
                $maxNumber[$pageNumber + 1] = max($matches[1]); // เก็บเลขที่มากที่สุดในหน้า
            }
        }
        // สร้างช่วงข้อมูลตาม maxNumber และดึงค่าจาก $data
        $start = 0;
        return array_map(function ($end) use (&$start, $data) {
            $range = range($start, (int)$end); // สร้างช่วง index
            $start = (int)$end + 1; // อัปเดตค่าเริ่มต้นสำหรับช่วงถัดไป
            return array_map(function ($index) use ($data) {
                return $data[$index] ?? null; // ดึงค่าจาก $data ตาม index
            }, $range);
        }, $maxNumber);
    }

  public function generatePdfLabScope()
  {
      $siteType = "single";
      $data = $this->getCalScopeData()->getData();
      
      // dd($data);
      if(count($data) > 1){
          $siteType = "multi";
      }
      $mpdfArray = []; 

    // วนลูปข้อมูล
      foreach ($data as $key => $details) {

        $scopes = $details->scope;

          // ใช้ array_map เพื่อดึงค่าของ 'key' จากแต่ละรายการใน $scopes
          $keys = array_map(function ($item) {
            return $item->key;
          }, $scopes);

          // ใช้ array_unique เพื่อลบค่าซ้ำใน $keys
          $uniqueKeys = array_unique($keys);

          $pdfData =  (object)[
            'certificate_no' => 'xx-LBxxx',
            'acc_no' => '',
            'book_no' => '',
            'from_date_th' => '',
            'from_date_en' => '',
            'to_date_th' => '',
            'to_date_en' => '',
            'uniqueKeys' => $uniqueKeys,
            'siteType' => $siteType
        ];

          // dd($uniqueKeys);

          $scopePages = $this->getPageList($scopes,$pdfData,$details);
          
          $type = 'I';
          $fontDirs = [public_path('fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
          $fontData = [
              'thsarabunnew' => [
                  'R' => "THSarabunNew.ttf",
                  'B' => "THSarabunNew-Bold.ttf",
                  'I' => "THSarabunNew-Italic.ttf",
                  'BI' => "THSarabunNew-BoldItalic.ttf",
              ],
          ];
  
          if ($siteType == "single") {
              $mpdf = new Mpdf([
                  'PDFA'             => $type == 'F' ? true : false,
                  'PDFAauto'         => $type == 'F' ? true : false,
                  'format'           => 'A4',
                  'mode'             => 'utf-8',
                  'default_font_size'=> '15',
                  'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                  'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                  'default_font'     => 'thsarabunnew',
                  'margin_left'      => 6,
                  'margin_right'     => 5,
                  'margin_top'       => 97,
                  'margin_bottom'    => 40,
                  'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
              ]);
          } else { // multiple
              if($key == 0){
                  // $marginTop = 108;
                  $mpdf = new Mpdf([
                      'PDFA'             => $type == 'F' ? true : false,
                      'PDFAauto'         => $type == 'F' ? true : false,
                      'format'           => 'A4',
                      'mode'             => 'utf-8',
                      'default_font_size'=> '15',
                      'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                      'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                      'default_font'     => 'thsarabunnew',
                      'margin_left'      => 6,
                      'margin_right'     => 5,
                      'margin_top'       => 108,
                      'margin_bottom'    => 40,
                      'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
                  ]);
              }else{
                  $mpdf = new Mpdf([
                      'PDFA'             => $type == 'F' ? true : false,
                      'PDFAauto'         => $type == 'F' ? true : false,
                      'format'           => 'A4',
                      'mode'             => 'utf-8',
                      'default_font_size'=> '15',
                      'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
                      'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
                      'default_font'     => 'thsarabunnew',
                      'margin_left'      => 6,
                      'margin_right'     => 5,
                      'margin_top'       => 85,
                      'margin_bottom'    => 40,
                      'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
                  ]);
              }
            
          }
                
  
          $data = $this->getCalScopeData()->getData();
  
          $stylesheet = file_get_contents(public_path('css/report/lab-scope.css'));
          $mpdf->WriteHTML($stylesheet, 1);
  
          // $mpdf->SetWatermarkImage(public_path(...), opacity, [size], [position]); 
  
          $mpdf->SetWatermarkImage(public_path('images/nc_logo.jpg'), 1, [23, 23], [170, 4]);
  
          $mpdf->showWatermarkImage = true; // เปิดใช้งาน watermark
  
          // เพิ่ม Text Watermark
          $mpdf->SetWatermarkText('Confidential', 0.1); // ระบุข้อความและ opacity
          $mpdf->showWatermarkText = true; // เปิดใช้งาน text watermark
              
          $signImage = public_path('images/sign.jpg');
          $sign1Image = public_path('images/sign1.png');
  
          // $footer = view('pdf.scope.calibration.cal-scope-footer', [
          //     'sign1Image' => null,
          //     'sign2Image' => null,
          //     'sign3Image' => null
          // ]);
          // $mpdf->SetHTMLFooter($footer,2);
  
          $headerBlade = "pdf.scope.calibration.cal-scope-first-header";
          $branchNo = null;

          if ($siteType == "multi")
          {
              $branchNo = $key + 1;
              if ($key == 0){
                  $headerBlade = "pdf.scope.calibration.cal-scope-first-header-multi";
              }else{
                  $headerBlade = "pdf.scope.calibration.cal-scope-first-header-multi-branch";
              }   
          }
          
          foreach ($scopePages as $index => $scopes) {
              if ($index == 0) {
                  $firstPageHeader = view($headerBlade, [
                      'branchNo' => $branchNo,
                      'company' => $details,
                      'pdfData' => $pdfData
                  ]);
                  $mpdf->SetHTMLHeader($firstPageHeader, 2);
                  $html = view('pdf.scope.calibration.pdf-cal-scope', [
                      'scopes' => collect($scopes)
                  ]);
                  $mpdf->WriteHTML($html);
              } else if ($index > 0) {
  
                  $header = view('pdf.scope.calibration.cal-scope-other-header', [
                      'branchNo' => null,
                      'company' => $details,
                      'pdfData' => $pdfData
                  ]);
                  $mpdf->SetHTMLHeader($header, 2);
                  $mpdf->AddPage('', '', '', '', '', 6, 5, 75, 30); 
                  $html = view('pdf.scope.calibration.pdf-cal-scope', [
                      'scopes' => collect($scopes)
                  ]);
                  $mpdf->WriteHTML($html);
              }
          }

          $mpdfArray[$key] = $mpdf;
      }

      $combinedPdf = new \Mpdf\Mpdf([
          'PDFA'             => $type == 'F' ? true : false,
          'PDFAauto'         => $type == 'F' ? true : false,
          'format'           => 'A4',
          'mode'             => 'utf-8',
          'default_font_size'=> '15',
          'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
          'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
          'default_font'     => 'thsarabunnew',
          'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
      ]);

      $combinedPdf->SetImportUse();
      
      // สร้างไฟล์ PDF ชั่วคราวจาก `$mpdfArray`
      $tempFiles = []; // เก็บรายชื่อไฟล์ชั่วคราว
      foreach ($mpdfArray as $key => $mpdf) {
          $tempFileName = "{$key}.pdf"; // เช่น main.pdf, branch0.pdf
          $mpdf->Output($tempFileName, \Mpdf\Output\Destination::FILE); // บันทึก PDF ชั่วคราว
          $tempFiles[] = $tempFileName;
      }

      // รวม PDF
      foreach ($tempFiles as $fileName) {
          $pageCount = $combinedPdf->SetSourceFile($fileName); // เปิดไฟล์ PDF
          for ($i = 1; $i <= $pageCount; $i++) {
              $templateId = $combinedPdf->ImportPage($i);
              $combinedPdf->AddPage();
              $combinedPdf->UseTemplate($templateId);

              // ดึง HTML Footer จาก Blade Template
              $signImage = public_path('images/sign.jpg');
              $footer = view('pdf.scope.calibration.cal-scope-footer', [
                  'sign1Image' => $signImage, // ส่งรูปภาพที่ต้องการใช้
                  'sign2Image' => $signImage,
                  'sign3Image' => $signImage
              ])->render();

              // ตั้งค่า Footer ใหม่สำหรับหน้า PDF
              $combinedPdf->SetHTMLFooter($footer);
          }
      }

      // ส่งออกไฟล์ PDF
      $combinedPdf->Output('combined.pdf', \Mpdf\Output\Destination::INLINE);

      // ลบไฟล์ชั่วคราว
      foreach ($tempFiles as $fileName) {
          unlink($fileName);
      }

  }

  public function create_folder()
  {
    $attach = 'files/applicants/check_files/my_folder';

        // ตรวจสอบว่ามีโฟลเดอร์อยู่หรือไม่ ถ้าไม่มีให้สร้าง
        if (!Storage::disk('uploads')->exists($attach)) {
            Storage::disk('uploads')->makeDirectory($attach);
        }

        // if (!Storage::disk('uploads')->exists($attach)) {
        //     Storage::disk('uploads')->makeDirectory($attach);
        //     chmod(Storage::disk('uploads')->path($attach), 0777); // เปลี่ยน 0755 หากต้องการ
        // }

  }

  public function check_payin()
  {
  
    $today = Carbon::now(); // กำหนดวันปัจจุบัน

    // $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $today)
    //             ->where('invoiceEndDate', '>=', $today)
    //             ->whereNull('status_confirmed')
    //             ->where(function($query) {
    //                 $query->where('ref1', 'like', 'TEST%')
    //                     ->orWhere('ref1', 'like', 'CAL%');
    //             })
    //             ->get();
    $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $today)
    ->where('invoiceEndDate', '>=', $today)
    ->whereNull('status_confirmed')
    ->where('state',1)
    ->where('count','<=',3)
    ->where(function ($query) {
        $query->where('ref1', 'like', 'TEST%')
              ->orWhere('ref1', 'like', 'CAL%');
    })
    ->get();

    dd($transactionPayIns);

    foreach ($transactionPayIns as $transactionPayIn) {
        $ref1 = $transactionPayIn->ref1;
        // dd('jjj');
        $result = $this->callCheckBill($ref1);
        // dd($result);
        // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            // แปลง JsonResponse เป็น array
            $resultArray = $result->getData(true);

            // dd($resultArray);
            
            // ตรวจสอบค่า message
            if (!empty($resultArray['message']) && $resultArray['message'] === true) {
                // ดึงค่าทั้งหมดจาก response
                $response = $resultArray['response'] ?? null;
                
                // ตรวจสอบว่า response เป็น array หลายรายการหรือไม่
                if (is_array($response) && count($response) > 0) {
                    // ใช้ array_map เพื่อดึง ref1
                    $ref1List = array_map(function ($item) {
                        return isset($item['ref1']) ? $item['ref1'] : null;
                    }, $response);

                    // dd($response);
    
                    // กรองเฉพาะ ref1 ที่ไม่เป็น null
                    // $validRef1 = array_filter($ref1List);
    
                    // แสดงผล ref1 ทั้งหมด
                    // $this->info("Valid ref1: " . implode(", ", $validRef1));

                    $appCertiLabCostAssessmentId = $transactionPayIn->ref_id;
                    $costAssessment = CostAssessment::find($appCertiLabCostAssessmentId);
                    $amountInvoice = $costAssessment->amount_invoice;
                    $fileClientName = $costAssessment->file_client_name;

                    
                    if($costAssessment->status_confirmed === null){
                       
                        CostAssessment::find($appCertiLabCostAssessmentId)->update([
                            'invoice' => $amountInvoice,
                            'invoice_client_name' => $fileClientName,
                            'state' => 3,
                            'status_confirmed' => 1,
                            'remark' => null,
                            'condition_pay' => null,
                        ]);

                        $find_cost_assessment = CostAssessment::find($appCertiLabCostAssessmentId);
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
                    }           

                } else {
                    // $this->info("Response is empty or not an array.");
                }
            } else {
                // $this->info("No valid message or response.");
            }
        } else {
            // $this->info("Invalid response type. Expected JsonResponse.");
        }
    }
    

}

public function create_bill()
{
    
    $find_cost_assessment   =  CostAssessment::findOrFail(1644);  //  ตารางธรรรมเนียม
    
    $certi_lab = CertiLab::findOrFail($find_cost_assessment->app_certi_lab_id); // ตารางใบสมัคร
    $app_no          =  $certi_lab->app_no;
   
    $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',1)->first();
    
    $storagePath = $this->storeFilePayinDemo($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
    // dd($storagePath);
}

     // สำหรับเพิ่มรูปไปที่ store
     public function storeFilePayinDemo($setting_payment, $app_no = 'files_lab', $auditor_id = '')
     {
        $baseUrl = strstr($setting_payment->data, '/api', true);

        $url = $baseUrl. '/images/PayIn2.pdf';

        // ดาวน์โหลดเนื้อหา PDF (Demo)
        // $pdf_content = file_get_contents($url);

                $contextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        if (strpos($setting_payment->data, 'https') === 0) {
            $context = stream_context_create($contextOptions);
            $pdf_content = file_get_contents($url, false, $context);
        } else {
            $pdf_content = file_get_contents($url);
        }

        // dd($pdf_content);
             
             $no  = str_replace("RQ-","",$app_no);
             $no  = str_replace("-","_",$no);
         if ($pdf_content) {
             $attach_path  =  $this->attach_path.$no;
             $fullFileName =  $no.'-'.date('Ymd_hms').'.pdf';
             $storagePath = Storage::put($attach_path.'/'.$fullFileName, $pdf_content);


            $filePath = $attach_path .'/'. $fullFileName;
            if (Storage::disk('ftp')->exists($filePath)) {
                // dd('File Path on Server: ' . $filePath);
            } else {
                // dd('File not found on server!');
            }
             return  $no.'/'.$fullFileName;
         }else{
             return null;
         }
      }

      public function CreateLabReport()
      {
          // สำหรับ admin และเจ้าหน้าที่ lab
        //   if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
        //       abort(403);
        //   }
  
        $id = 1767;
          $boardAuditor = BoardAuditor::find($id);
  
          $groups = $boardAuditor->groups;
  
          $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id
  
          $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
  
          foreach ($groups as $group) {
              $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
              $auditors = $group->auditors; // $auditors เป็น Collection
  
              // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
              if (!isset($statusAuditorMap[$statusAuditorId])) {
                  $statusAuditorMap[$statusAuditorId] = [];
              }
  
              // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
              foreach ($auditors as $auditor) {
                  $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
              }
          }
  
          $uniqueAuditorIds = array_unique($auditorIds);
  
          $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();
  
          $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);
  
          $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
          $dateRange = "";
  
          if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
              if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                  // ถ้าเป็นวันเดียวกัน
                  $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
              } else {
                  // ถ้าเป็นคนละวัน
                  $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                              " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
              }
          }
  
          $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();
          $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
          // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
          if ($boardAuditorExpert && $boardAuditorExpert->expert) {
              // แปลงข้อมูล JSON ใน expert กลับเป็น array
              $categories = json_decode($boardAuditorExpert->expert, true);
          
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
  
          $scope_branch = "";
          if ($certi_lab->lab_type == 3){
              $scope_branch = $certi_lab->BranchTitle;
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
          $data->app_np = 'ทดสอบ ๑๖๗๑';
          $data->certificate_no = '13-LB0037';
          $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
          $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
          $data->experts = $experts;
          $data->date_range = $dateRange;
          $data->statusAuditorMap = $statusAuditorMap;
  
  
        //   dd('ok');
          return view('certify.save_assessment.report', [
              'data' => $data,
              'id' => $id
          ]);
      }


      public function CreateLabReportPdf()
      {
        $id = 1;
        $labReportInfo = LabReportInfo::find($id);
        
        $notice = $labReportInfo->notice;
        $assessment = $notice->assessment;

        $app_certi_lab = $notice->applicant;
        $boardAuditor = $assessment->board_auditor_to;
        $id = $boardAuditor->auditor_id;

        // dd($labReportInfo,$notice);
       
        $groups = $boardAuditor->groups;
    
        $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id

        $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล

        foreach ($groups as $group) {
            $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
            $auditors = $group->auditors; // $auditors เป็น Collection

            // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
            if (!isset($statusAuditorMap[$statusAuditorId])) {
                $statusAuditorMap[$statusAuditorId] = [];
            }

            // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
            foreach ($auditors as $auditor) {
                $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
            }
        }

        $uniqueAuditorIds = array_unique($auditorIds);

        $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();

        $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);

        $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
        $dateRange = "";

        if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
            if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                // ถ้าเป็นวันเดียวกัน
                $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
            } else {
                // ถ้าเป็นคนละวัน
                $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                            " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
            }
        }

        $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();
        $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
        // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
        if ($boardAuditorExpert && $boardAuditorExpert->expert) {
            // แปลงข้อมูล JSON ใน expert กลับเป็น array
            $categories = json_decode($boardAuditorExpert->expert, true);
        
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

        $scope_branch = "";
        if ($certi_lab->lab_type == 3){
            $scope_branch = $certi_lab->BranchTitle;
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
        $data->app_np = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        $data->experts = $experts;
        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;

        // $notice = Notice::find($notice_id);
        $assessment = $notice->assessment;
        // dd($statusAuditorMap);
        $app_certi_lab = $notice->applicant;
        $boardAuditor = $assessment->board_auditor_to;
        $id = $boardAuditor->auditor_id;
        $labRequest = null;
        
        if($app_certi_lab->lab_type == 4){
            $labRequest = LabCalRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
        }else if($app_certi_lab->lab_type == 3)
        {
            $labRequest = LabTestRequest::where('app_certi_lab_id',$app_certi_lab->id)->where('type',1)->first();
        }

        $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->get();


        
        $signer = new stdClass();


       
        $signer->signer_1 = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->where('report_type',1)->where('signer_order','1')->first();

        
        $signer->signer_2 = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->where('report_type',1)->where('signer_order','2')->first();
        $signer->signer_3 = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->where('report_type',1)->where('signer_order','3')->first();



        $attach1 = !empty($signer->signer_1->signer->AttachFileAttachTo) ? $signer->signer_1->signer->AttachFileAttachTo : null;
        $attach2 = !empty($signer->signer_2->signer->AttachFileAttachTo) ? $signer->signer_2->signer->AttachFileAttachTo : null;
        $attach3 = !empty($signer->signer_3->signer->AttachFileAttachTo) ? $signer->signer_3->signer->AttachFileAttachTo : null;
        // $attach4 = !empty($signer->signer_4->signer->AttachFileAttachTo) ? $signer->signer_4->signer->AttachFileAttachTo : null;
        // dd($attach1->url);

        $sign_url1 = $this->getSignature($attach1);
        $sign_url2 = $this->getSignature($attach2);
        $sign_url3 = $this->getSignature($attach3);
        // $sign_url4 = $this->getSignature($attach4);


        $signer->signer_url1 = $sign_url1;
        $signer->signer_url2 = $sign_url2;
        $signer->signer_url3 = $sign_url3;
        // $signer->signer_url4 = $sign_url4;

        $type = 'I';
        $fontDirs = [public_path('pdf_fonts/')]; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA' 	=>  $type == 'F' ? true : false,
            'PDFAauto'	 =>  $type == 'F' ? true : false,
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '15',
            'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
            'margin_left'      => 12, // ระบุขอบด้านซ้าย
            'margin_right'     => 15, // ระบุขอบด้านขวา
            'margin_top'       => 15, // ระบุขอบด้านบน
            'margin_bottom'    => 15, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);         

        $mpdf->useDictionaryLBR = false;
        // $mpdf->shrink_tables_to_fit=0;
        $mpdf->SetDefaultBodyCSS('KeepTableProportions', 1);
        $body = view('certify.save_assessment.report-pdf.ia.body', [
            'labReportInfo' => $labReportInfo,
            'data' => $data,
            'notice' => $notice,
            'assessment' => $assessment,
            'boardAuditor' => $boardAuditor,
            'certi_lab' => $app_certi_lab,
            'labRequest' => $labRequest,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'id' => $id,
            'signer' => $signer
        ]);
        $footer = view('certify.save_assessment.report-pdf.ia.footer', []);

        $stylesheet = file_get_contents(public_path('css/report/lab-report.css'));
        $mpdf->WriteHTML($stylesheet, 1);
       
        
        $mpdf->WriteHTML($body,2);

        $mpdf->SetHTMLFooter($footer,2);

        $title = "labreport.pdf";
        
        $mpdf->Output($title, $type);  
                                                    
    }

    public function getSignature($attach)
    {
        
        $existingFilePath = $attach->url;//  'files/signers/3210100336046/tvE4QPMaEC-date_time20241211_011258.png'  ;

        $attachPath = 'bcertify_attach/signer';
        $fileName = basename($existingFilePath) ;// 'tvE4QPMaEC-date_time20241211_011258.png';
        // dd($existingFilePath);

        // ตรวจสอบไฟล์ใน disk uploads ก่อน
        if (Storage::disk('uploads')->exists("{$attachPath}/{$fileName}")) {
            // หากพบไฟล์ใน disk
            $storagePath = Storage::disk('uploads')->path("{$attachPath}/{$fileName}");
            $filePath = 'uploads/'.$attachPath .'/'.$fileName;
            // dd('File already exists in uploads',  $filePath);
            return $filePath;
        } else {
            // หากไม่พบไฟล์ใน disk ให้ไปตรวจสอบในเซิร์ฟเวอร์
            if (HP::checkFileStorage($existingFilePath)) {
                // ดึง path ของไฟล์ที่อยู่ในเซิร์ฟเวอร์
                $localFilePath = HP::getFileStoragePath($existingFilePath);

                // ตรวจสอบว่าไฟล์มีอยู่หรือไม่
                if (file_exists($localFilePath)) {
                    // บันทึกไฟล์ลง disk 'uploads' โดยใช้ subfolder ที่กำหนด
                    $storagePath = Storage::disk('uploads')->putFileAs($attachPath, new \Illuminate\Http\File($localFilePath), $fileName);

                    // ตอบกลับว่าพบไฟล์และบันทึกสำเร็จ
                    $filePath = 'uploads/'.$attachPath .'/'.$fileName;
                    return $filePath;
                    // dd('File exists in server and saved to uploads', $storagePath);
                } else {
                    // กรณีไฟล์ไม่สามารถเข้าถึงได้ใน path เดิม
                    return null;
                }
            } else {
                // ตอบกลับกรณีไม่มีไฟล์ในเซิร์ฟเวอร์
                return null;
            }
        }
        
    }
    public function CreateLabMessageRecordPdfDemo()
    {
        // http://127.0.0.1:8081/certify/auditor/create-lab-message-record-pdf/1754
        $boardAuditor = BoardAuditor::find(1850);
        // dd( $boardAuditor);
        $pdfService = new CreateLabMessageRecordPdf($boardAuditor,"ia");
        $pdfContent = $pdfService->generateBoardAuditorMessageRecordPdf();
    }

    public function upDatePayin()
    {
        $arrContextOptions=array();
        // dd('555555555');
        $expiredTransactionPayIns = $this->CheckPayInExpire();
        // dd($expiredTransactionPayIns);
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn){
                $data = $expiredTransactionPayIn-> ref1;
                $splitData = explode('-', $data); // แยกข้อมูลด้วย '-'
                $lastPart = end($splitData); // ดึงค่าตัวสุดท้าย
                $assessmentId = (int)$lastPart; // แปลงเป็น int
                $assessment = Assessment::find($assessmentId);
                $appCertiLab = $assessment->applicant;
                // dd($appCertiLab);

                $find_cost_assessment   =  $assessment->cost_assessment ;  //  ตารางธรรรมเนียม

                // dd($find_cost_assessment);

                $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',1)->first();

                $app_no =  $appCertiLab->app_no;

                if(!is_null($setting_payment)){
                    // dd('ok');
                    if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                          );
                    }
                    $ao = new CostAssessment;
                    $timestamp = Carbon::now()->timestamp;
                    $refNo = $app_no.'-'.$find_cost_assessment->app_certi_assessment_id.$timestamp;
                    
                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));

                    $api = json_decode($content,false);
                    
                    // if(strpos($setting_payment->data, '127.0.0.1')===0){
                    // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                    $host = parse_url($setting_payment->data, PHP_URL_HOST);

                    if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {
                        
                        $find_cost_assessment->amount_invoice =   $this->storeFilePayin($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
                    }else{
                        
                        $find_cost_assessment->amount_invoice =   $this->storeFilePayinDemo($setting_payment,$app_no,$find_cost_assessment->app_certi_assessment_id);
                    }
                    // dd('aha');
                    $find_cost_assessment->file_client_name =   isset($find_cost_assessment->amount_invoice) ? basename($find_cost_assessment->amount_invoice)  : null;
                    
                    $find_cost_assessment->save();
                    // dd($find_cost_assessment->id);
                    HP::TransactionPayIn1($find_cost_assessment->id,$ao->getTable(),'1','1',$api,$app_no.'-'.$find_cost_assessment->app_certi_assessment_id,$timestamp);
                    
                    // Send email to client and owner
                    QueSendEmailGeneratePayInOneLabJob::dispatch($appCertiLab->id,$find_cost_assessment->id);
                 }
            }
        }
    }  

    public function CheckPayInExpire()
    {
        $this->CancelCertiLab();
        // 
        $today = now(); // กำหนดวันปัจจุบัน

        $expiredTransactionPayIns = TransactionPayIn::where('invoiceEndDate', '<', $today) // ค้นหาที่ invoiceEndDate น้อยกว่า today
            ->whereNull('status_confirmed') // เงื่อนไข status_confirmed เป็น NULL
            ->whereNotNull('suffix') // เงื่อนไข suffix ต้องไม่เป็น NULL
            ->where('count','<',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();
            // dd('aha');
        // Return หรือจัดการข้อมูล
        return $expiredTransactionPayIns;
    }

    public function CancelCertiLab()
    {
        $today = now();
        $expiredTransactionPayIns = TransactionPayIn::where('invoiceEndDate', '<', $today) // ค้นหาที่ invoiceEndDate น้อยกว่า today
            ->where('count','>=',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();
// dd($expiredTransactionPayIns);
        $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();        
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn){
                $data = $expiredTransactionPayIn-> ref1;
                $splitData = explode('-', $data); // แยกข้อมูลด้วย '-'
                $lastPart = end($splitData); // ดึงค่าตัวสุดท้าย
                $assessmentId = (int)$lastPart; // แปลงเป็น int
                $assessment = Assessment::find($assessmentId);
                $appCertiLab = $assessment->applicant;

                CertificateHistory::create([
                    'app_no'=> $appCertiLab->app_no ?? null,
                    'system'=> 9,
                    'table_name'=> 'app_certi_labs',
                    'status'=> 4,
                    'ref_id'=> $appCertiLab->id,
                    'details'=> 'ยกเลิกคำขออัตโนมัติ เนื่องจากไม่มีชำระภายใน 90 วัน',
                    'attachs'=> null,
                    'created_by' =>  $admin->runrecno
                    ]);
                CertiLab::find($appCertiLab->id)->update([
                    'status' => 4
                ]);
            }
        }
    }

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

    public function mergePdf()
    {
        $report_info_id = 11;
        $pdfService = new CreateLabAssessmentReportPdf($report_info_id,"ia");
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }

    
    public function checkPayIn2()
    {
        // type = 2 ใบเสร็จ
        $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',1)->where('type',2)->first();

        $arrContextOptions = array();
  

        if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
            $arrContextOptions["ssl"] = array(
                                    "verify_peer" => false,
                                    "verify_peer_name" => false,
                              );
        }
    
        $today = now(); // กำหนดวันปัจจุบัน

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $today)
            ->where('invoiceEndDate', '>=', $today)
            ->whereNull('status_confirmed')
            ->where('state',2)
            ->where('count','<=',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%')
                      ->orWhere('ref1', 'like', 'CAL%');
            })
            ->get();
        
            // dd($transactionPayIns );
        foreach ($transactionPayIns as $transactionPayIn) {
            // 
            $ref1 = $transactionPayIn->ref1;
            $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
            // dd($result);
            // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
            if ($result instanceof \Illuminate\Http\JsonResponse) {
                // แปลง JsonResponse เป็น array
                $resultArray = $result->getData(true);
        
                // ตรวจสอบค่า message
                if (!empty($resultArray['message']) && $resultArray['message'] === true) {
                   
                    // ดึงค่าทั้งหมดจาก response
                    $response = $resultArray['response'] ?? null;
        
                    // ตรวจสอบว่า response เป็น array หลายรายการหรือไม่
                    if (is_array($response) && count($response) > 0) {
                        // ใช้ array_map เพื่อดึง ref1
                        $ref1List = array_map(function ($item) {
                            return isset($item['ref1']) ? $item['ref1'] : null;
                        }, $response);
        
                        // กรองเฉพาะ ref1 ที่ไม่เป็น null
                        $validRef1 = array_filter($ref1List);
        
                        // แสดงผล ref1 ทั้งหมด
                        // $this->info("Valid ref1: " . implode(", ", $validRef1));

                        $appCertiLabCostCertificateId = $transactionPayIn->ref_id;
                        $costCertificate = CostCertificate::find($appCertiLabCostCertificateId);
                        $amountInvoice = $costCertificate->invoice;
                        $fileClientName = $costCertificate->invoice_client_name;
                        // if($costCertificate->status_confirmed === null){

                        CostCertificate::find($appCertiLabCostCertificateId)->update([
                            'invoice' => $costCertificate->assessment->invoice,
                            'invoice_client_name' => $costCertificate->assessment->invoice_client_name,
                        ]);

                        $find_certi_lab = CertiLab::findOrFail($costCertificate->app_certi_lab_id);
                        $find_certi_lab->status = 24; //แจ้งหลักฐานการชำระค่าใบรับรอง
                        $find_certi_lab->save();

                        $status_confirmed = 1;
                        $costcerti =   CostCertificate::findOrFail($appCertiLabCostCertificateId);
                        $attach =  $costcerti->attach ?? null ;//
                        $invoice =  $costcerti->invoice ?? null ;//
                        $costcerti->update([
                                            'status_confirmed'  =>  1 ?? 2,
                                            'detail'            =>  null,
                                            'condition_pay'     =>   null,
                                            ]);
        
                        $CertiLab = CertiLab::findOrFail($costCertificate->app_certi_lab_id);
                        // dd($CertiLab->purpose_type,$CertiLab->certificate_export_to2);
                            if($CertiLab->purpose_type == 1 || (!is_null($CertiLab) && is_null($CertiLab->certificate_export_to2)) ){ // ขอใบรับรอง
                                
                                $CertiLab->update([ 'status' =>25 ]);   // ยืนยันการชำระเงินค่าใบรับรอง
                            }else{
                                    $CertiLab->update([ 'status' =>28 ]);   // ออกใบรับรอง และ ลงนาม
                            }
                            
                            // เงื่อนไขเช็คมีใบรับรอง 
                            $this->save_certilab_export_mapreq( $CertiLab );
        
                        
                            $ao = new CostCertificate;
                            $detail_costcerti =   CostCertificate::select('amount','amount_fee','notification_date','detail','start_date_feewaiver','end_date_feewaiver','remark','conditional_type')
                                                            ->where('id',$costcerti->id)
                                                            ->orderby('id','desc')
                                                            ->first();

                            // dd($detail_costcerti)                                ;
                            CertificateHistory::create([
                                                        'app_no'        => $CertiLab->app_no ?? null,
                                                        'system'        => 6, // Pay-In ครั้งที่ 2
                                                        'table_name'    => $ao->getTable(),
                                                        'ref_id'        => $costcerti->id,
                                                        'details'       =>  json_encode($detail_costcerti) ?? null,
                                                        'status'        => $status_confirmed ?? null,
                                                        'attachs'       => $attach ?? null,
                                                        'attachs_file'  => $invoice ?? null,
                                                        'created_by'    =>  448
                                                    ]);

                        // }

                    } else {
                        // $this->info("Response is empty or not an array.");
                    }
                } else {
                    // $this->info("No valid message or response.");
                }
            } else {
                // $this->info("Invalid response type. Expected JsonResponse.");
            }
        }
        
        
        
        
        
        // $this->info('ตรวจสอบการชำระเงินระบบ epayment ของ payin2 lab เสร็จสิ้น');
    }



    public function CheckPayInExpireLab2()
    {
        // $this->CancelCertiLab2();
        // 
        $today = now(); // กำหนดวันปัจจุบัน

        $expiredTransactionPayIns = TransactionPayIn::where('invoiceEndDate', '<', $today) // ค้นหาที่ invoiceEndDate น้อยกว่า today
            ->whereNull('status_confirmed') // เงื่อนไข status_confirmed เป็น NULL
            ->whereNotNull('suffix') // เงื่อนไข suffix ต้องไม่เป็น NULL
            ->where('state',2)
            ->where('count','<',3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();
            // dd('aha');
        // Return หรือจัดการข้อมูล
        return $expiredTransactionPayIns;
    }

    public function CancelCertiLab2()
    {
        $today = now();
        $expiredTransactionPayIns = TransactionPayIn::where('invoiceEndDate', '<', $today) // ค้นหาที่ invoiceEndDate น้อยกว่า today
            ->where('count','>=',3)
            ->where('state',2)
            ->whereNull('status_confirmed')
            ->where(function ($query) {
                $query->where('ref1', 'like', 'TEST%') // ref1 เริ่มต้นด้วย TEST
                    ->orWhere('ref1', 'like', 'CAL%'); // หรือ ref1 เริ่มต้นด้วย CAL
            })
            ->get();

        $admin = DB::table('user_register')->where('reg_email', 'admin@admin.com')->first();        
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn){
                $appCertiLab = CertiLab::where('app_no',$expiredTransactionPayIn->ref1)->first();
                HP::CancelCertiLab($appCertiLab,'ยกเลิกคำขออัตโนมัติ เนื่องจากไม่มีชำระภายใน 90 วัน');
            }
        }
    }
    
    public function regenPayin2()
    {
        // dd('ok');
        $arrContextOptions=array();
        $expiredTransactionPayIns = $this->CheckPayInExpireLab2();
        // dd($expiredTransactionPayIns);
        $ao = new CostCertificate;
        $attach_path =  $this->attach_path ;
        if($expiredTransactionPayIns->count() !== 0){
            foreach($expiredTransactionPayIns as $expiredTransactionPayIn)
            {
                $costCertificateId = $expiredTransactionPayIn->ref_id;
                $costcerti = CostCertificate::findOrFail($costCertificateId);
                $setting_payment = CertiSettingPayment::where('certify',1)->where('payin',2)->where('type',1)->first();
                if(!is_null($setting_payment)){
                    if(strpos($setting_payment->data, 'https')===0){//ถ้าเป็น https
                        $arrContextOptions["ssl"] = array(
                                                "verify_peer" => false,
                                                "verify_peer_name" => false,
                                          );
                    }
                    $timestamp = Carbon::now()->timestamp;
                    $CertiLab = CertiLab::where('app_no',$expiredTransactionPayIn->ref1)->first();
                    $refNo = $CertiLab->app_no.'-'.$timestamp;

                    $content =  file_get_contents("$setting_payment->data?pid=$setting_payment->pid&out=json&Ref1=$refNo", false, stream_context_create($arrContextOptions));
                    // dd($content);

                    $api = json_decode($content);
                    
                    // $costcerti->attach              =   $this->storeFilePayin($setting_payment,$CertiLab->app_no);

                    // if(strpos($setting_payment->data, '127.0.0.1')===0){
                    // if (!filter_var(parse_url($setting_payment->data, PHP_URL_HOST), FILTER_VALIDATE_IP)) {
                    $host = parse_url($setting_payment->data, PHP_URL_HOST);

                    if (!filter_var($host, FILTER_VALIDATE_IP) && strpos($host, 'accr2.tisi.go.th') === false) {
                        
                        $costcerti->attach              =   $this->storeFilePayin($setting_payment,$CertiLab->app_no);
                    }else{//ถ้าเป็น 127.0.0 (การทดสอบ)
                        
                        $costcerti->attach              =   $this->storeFilePayinDemo($setting_payment,$CertiLab->app_no);
                    }

                    $costcerti->attach_client_name  =   basename($costcerti->attach);
                    $costcerti->amount_fixed        =   1000;
                    $costcerti->amount_fee          =  !empty(str_replace(",","",$api->AmountCert))?str_replace(",","",$api->AmountCert):null;
                    $costcerti->save();
                    
                    $transaction = HP::TransactionPayIn2($costCertificateId,$ao->getTable(),'1','2',$api,$timestamp);
                    // dd($api);
                    if(HP::checkFileStorage($attach_path.$costcerti->attach)){
                        HP::getFileStoragePath($attach_path.$costcerti->attach);
                    }
               }
            }
        }
    }

    public function quickRandom($length = 6)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
    

    public function demoEmailOtp(Request $request)
    {
        $_refTop = '1iVXCA';
        $_otp = '915175';
        $send  =  SendCertificateLists::find(399);
       
        if(!is_null($send)){
            if( !empty($send->send_certificates_to->signer_to)){
              
                
                    $sign                       =  $send->send_certificates_to->signer_to;
                        $input                  = [];
                        $input['Ref_otp']       =   $_refTop;
                        $input['otp']           =   $_otp;  
                        $input['Req_date']      =    date('Y-m-d H:i:s'); 
                        $input['Req_by']        =   auth()->user()->getKey(); 
                        $input['state']         =  1; 
                        $detail =  SignCertificateOtp::where('Ref_otp',$input['Ref_otp'])->where('otp', $input['otp'])->first();

                        if(is_null($detail)){
                            $otp_sign =  SignCertificateOtp::create($input);
                            
                             SignCertificateOtp::where('Ref_otp',$request->ref_otp)->update(['state'=> 3]);
                            
                            $mail = 'joerocknpc@mail.com';
                            $app = $send->app_cert_to;
                           
                            if($mail !== null){

                                $config = HP::getConfig();
                                $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                                $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
                                $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
                    
                                $data_app =    [
                                                'certi_lab'     => $app,
                                                'otp'           => $otp_sign->otp,
                                                'ref_otp'       => $otp_sign->Ref_otp,
                                                'email'         =>  !empty($app->DataEmailCertifyCenter) ? $app->DataEmailCertifyCenter : $EMail,
                                            ];
                            
                                $log_email =  HP::getInsertCertifyLogEmail( $app->app_no,
                                                                            $app->id,
                                                                            (new CertiLab)->getTable(),
                                                                            $send->id,
                                                                            (new SendCertificateLists)->getTable(),
                                                                            1,
                                                                            'OTP ลงนามใบรับรอง',
                                                                            view('mail.Lab.mail_otp_notification', $data_app),
                                                                            $app->created_by,
                                                                            $app->agent_id,
                                                                            auth()->user()->getKey(),
                                                                            !empty($app->DataEmailCertifyCenter) ?  implode(',',(array)$app->DataEmailCertifyCenter)  : $EMail,
                                                                            $app->email,
                                                                            !empty($app->DataEmailDirectorLABCC) ? implode(',',(array)$app->DataEmailDirectorLABCC)   :  $EMail,
                                                                            !empty($app->DataEmailDirectorLABReply) ?implode(',',(array)$app->DataEmailDirectorLABReply)   :  $EMail,
                                                                            null
                                                                            );
                                  $html = new  OtpNofitication($data_app);
                                  $mail = Mail::to($app->email)->send($html);
                    
                                  if(is_null($mail) && !empty($log_email)){
                                    HP::getUpdateCertifyLogEmail($log_email->id);
                                  }
                              }


                            return response()->json([
                                                    'message' =>  true,
                                                    'Ref_otp' =>  $otp_sign->Ref_otp,
                                                    // 'otp' =>  $otp_sign->otp,
                                                ]);
                            exit;
                        }
                }else{
                    return response()->json([
                                             'message' =>  false 
                                         ]);
                }
        }else{
            return response()->json([
                                     'message' =>  false 
                                 ]);
        }
    } 

    public function directorSignMail()
    {
        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');
        $mail = "jo@mail.com";
        $data_app = [
                        'url' =>  $url.'/certify/send-certificates/create'
                    ];

          $html = new  DirectorSignNotification($data_app);
          $mail = Mail::to($mail)->send($html);
    }

    public function checkNoticeExpire()
    {
        $expiredNotices = Notice::whereNotNull('notice_confirm_date')
                ->where('report_status', 1)
                ->where('degree', '!=', 7)
                ->where('submit_type', "confirm")
                ->whereHas('applicant', function ($query) {
                    $query->where('status', '!=', 4);
                })
                ->whereDoesntHave('applicant.notices', function ($subQuery) {
                    $subQuery->whereRaw('DATE_ADD(notice_confirm_date, INTERVAL notice_duration DAY) > ?', [now()]);
                })
                ->get();

        // Pluck และ Unique
        $uniqueAppCertiLabIds = $expiredNotices->pluck('app_certi_lab_id')->unique();
        $certiLabs = CertiLab::whereIn('id',$uniqueAppCertiLabIds)->get();
        // dd($certiLabs);
        if($certiLabs->count() != 0)
        {
            foreach($certiLabs as $certiLab)
            {
                HP::CancelCertiLab($certiLab,'ยกเลิกคำขออัตโนมัติ เนื่องจากแก้ไขข้อบกพร่องเกิน 90 วัน');
            }
        }
       
    }

 
    public function generateCbScopeBcmsPdf()
    {
     $certi_cb = CertiLab::find(251);
    //  dd($certilab);

     // dd($certilab->DataEmailDirectorLAB);

     $pdfService = new CreateCbScopeBcmsPdf($certi_cb);
     $pdfContent = $pdfService->generatePdf();
    }

    public function generateCbScopeIsicPdf()
    {
     $certi_cb = CertiLab::find(252);


     $pdfService = new CreateCbScopeIsicPdf($certi_cb);
     $pdfContent = $pdfService->generatePdf();
    }

    public function generateCbScopeIbPdf()
    {
     $certi_ib = CertiIb::find(143);


     $pdfService = new CreateIbScopePdf($certi_ib);
     $pdfContent = $pdfService->generatePdf();
    }

    public function generateScopePDF()
    {
     $certilab = CertiLab::find(2141);
    //  dd($certilab);

     // dd($certilab->DataEmailDirectorLAB);

     $pdfService = new CreateLabScopePdf($certilab);
     $pdfContent = $pdfService->generatePdf();
    }


    public function CreateLabMessageRecord()
    {
        $id = 1842;
        // สำหรับ admin และเจ้าหน้าที่ lab
        if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
            abort(403);
        }

        $boardAuditor = BoardAuditor::find($id);

        $groups = $boardAuditor->groups;



        $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล

        foreach ($groups as $group) {
            $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
            $auditors = $group->auditors; // $auditors เป็น Collection

            // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
            if (!isset($statusAuditorMap[$statusAuditorId])) {
                $statusAuditorMap[$statusAuditorId] = [];
            }

            // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
            foreach ($auditors as $auditor) {
                // dd($auditor);
                $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
            }
        }

        // dd($statusAuditorMap);
  

        $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);

        $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$id)->first();
        $dateRange = "";

        if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
            if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                // ถ้าเป็นวันเดียวกัน
                $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
            } else {
                // ถ้าเป็นคนละวัน
                $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                            " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
            }
        }

        $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$id)->first();

        
        $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
        // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
        // dd($boardAuditorExpert->expert);
        if ($boardAuditorExpert && $boardAuditorExpert->expert) {
            // แปลงข้อมูล JSON ใน expert กลับเป็น array
            $categories = json_decode($boardAuditorExpert->expert, true);
        
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
        dd($experts);
        $scope_branch = "";
        if ($certi_lab->lab_type == 3){
            $scope_branch = $certi_lab->BranchTitle;
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
        $data->app_np = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        $data->experts = $experts;
        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;
        $data->fix_text1 = <<<HTML
                    <div class="section-title">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
                    <div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
                    <div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข วันที่ ๔ มีนาคม ๒๕๕๑) การรับรองห้องปฏิบัติการ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)"</div>
                    <div style="text-indent:150px">ข้อ ๖.๑.๒ (๑) แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าคณะผู้ตรวจ ประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม</div>
                    <div style="text-indent:150px">ข้อ ๖.๑.๒ (๒.๑) คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมินเอกสารของห้องปฏิบัติการ และข้อ ๖.๑.๒ (๒.๒) คณะผู้ตรวจประเมินจะตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานตามระบบการบริหารงานและมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง</div>
                    <div style="text-indent:125px">๒.๓ ประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม เรื่อง แนวทางการแต่งตั้งพนักงานเจ้าหน้าที่ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศ ณ วันที่ ๙ กุมภาพันธ์ พ.ศ. ๒๕๖๐) ซึ่งระบุพนักงานเจ้าหน้าที่ต้องมีคุณสมบัติตามข้อ ๑. ถึง ๓. </div>
                    <div style="text-indent:125px">๒.๔ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมในการเป็นผู้มีอำนาจพิจารณาดำเนินการตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (สั่ง ณ วันที่ ๑๓พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ และข้อ ๕.๒ ในกรณีที่ข้าราชการผู้รับมอบอำนาจตามข้อ ๓.ไม่อาจปฏิบัติราชการได้ หรือไม่มีผู้ดำรงตำแหน่งดังกล่าว ให้รองเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมที่กำกับ เป็นผู้พิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</div>
                HTML;

        $data->fix_text2 = <<<HTML
                    <div class="section-title">๓. สาระสำคัญและข้อเท็จจริง</div>
                    <div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองห้องปฏิบัติการ สมอ. มีอำนาจหน้าที่ในการรับรองความสามารถห้องปฏิบัติการ กำหนดให้มีการประเมินเพื่อพิจารณาให้การรับรองความสามารถห้องปฏิบัติการ{{$data->lab_type}} ตามมาตรฐานเลขที่ มอก. 17025-2561</div>
                HTML;
        

        return view('certify.auditor.initial-message-record', [
            'data' => $data,
            'id' => $id
        ]);
    }
    
    // public function CreateTrackingLabMessageRecord()
    // {
    //     $id = 278;
    //     // สำหรับ admin และเจ้าหน้าที่ lab
    //     if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
    //         abort(403);
    //     }

    //     $trackingAuditor = TrackingAuditors::find($id);
    //     $auditors_statuses= $trackingAuditor->auditors_status_many;
    //     $statusAuditorMap = [];
    //     foreach ($auditors_statuses as $auditors_status)
    //     {
    //         // dd($auditors_status->auditors_list_many);
    //         $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
    //         $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection

    //         // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
    //         if (!isset($statusAuditorMap[$statusAuditorId])) {
    //             $statusAuditorMap[$statusAuditorId] = [];
    //         }
    //         // dd($auditors_status);
    //         // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
    //         foreach ($auditors as $auditor) {
                
    //             $statusAuditorMap[$statusAuditorId][] = $auditor->id;
    //         }
    //     }

    //     // dd($statusAuditorMap);

        
    //     $tracking = Tracking::find($trackingAuditor->tracking_id);

    //     $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$id)->first();
    //     $dateRange = "";

    //     if (!empty($trackingAuditorsDate->start_date) && !empty($trackingAuditorsDate->end_date)) {
    //         if ($trackingAuditorsDate->start_date == $trackingAuditorsDate->end_date) {
    //             // ถ้าเป็นวันเดียวกัน
    //             $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date);
    //         } else {
    //             // ถ้าเป็นคนละวัน
    //             $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date) . 
    //                         " ถึงวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->end_date);
    //         }
    //     }

    //     // dd($dateRange );

    //     $boardAuditorExpertTracking = BoardAuditoExpertTracking::where('tracking_auditor_id',$id)->first();

    //     $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
    //     // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
    //     // dd($boardAuditorExpert->expert);
    //     if ($boardAuditorExpertTracking && $boardAuditorExpertTracking->expert) {
    //         // แปลงข้อมูล JSON ใน expert กลับเป็น array
    //         $categories = json_decode($boardAuditorExpertTracking->expert, true);
        
    //         // ถ้ามีหลายรายการ
    //         if (count($categories) > 1) {
    //             // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
    //             $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
    //             $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
    //         } elseif (count($categories) == 1) {
    //             // ถ้ามีแค่รายการเดียว
    //             $experts = $categories[0];
    //         } else {
    //             $experts = ''; // ถ้าไม่มีข้อมูล
    //         }
        
    //     }
        
    //     $certi_lab = $tracking->certificate_export_to->applications;
    //     // dd($certi_lab);
    //     $scope_branch = "";
    //     if ($certi_lab->lab_type == 3){
    //         $scope_branch =$certi_lab->BranchTitle;
    //     }else if($certi_lab->lab_type == 4)
    //     {
    //         $scope_branch = $certi_lab->ClibrateBranchTitle;
    //     }

    //     // dd($certi_lab);
       
    //     $data = new stdClass();


     
    //     $data->header_text1 = '';
    //     $data->header_text2 = '';
    //     $data->header_text3 = '';
    //     $data->header_text4 = $certi_lab->app_no;
    //     $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
    //     $data->lab_name = $certi_lab->lab_name;
    //     $data->scope_branch = $scope_branch;
    //     $data->tracking = $tracking;
    //     $data->app_np = 'ทดสอบ ๑๖๗๑';
    //     $data->certificate_no = '13-LB0037';
    //     $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
    //     $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
    //     $data->experts = $experts;
    //     // $data->certiLabFileAll = $certiLabFileAll;
    //     $data->date_range = $dateRange;
    //     $data->statusAuditorMap = $statusAuditorMap;
    //     $data->fix_text1 = <<<HTML
    //                 <div class="section-title">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
    //                 <div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
    //                 <div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข วันที่ ๔ มีนาคม ๒๕๕๑) การรับรองห้องปฏิบัติการ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)"</div>
    //                 <div style="text-indent:150px">ข้อ ๖.๑.๒ (๑) แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าคณะผู้ตรวจ ประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม</div>
    //                 <div style="text-indent:150px">ข้อ ๖.๑.๒ (๒.๑) คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมินเอกสารของห้องปฏิบัติการ และข้อ ๖.๑.๒ (๒.๒) คณะผู้ตรวจประเมินจะตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานตามระบบการบริหารงานและมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง</div>
    //                 <div style="text-indent:125px">๒.๓ ประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม เรื่อง แนวทางการแต่งตั้งพนักงานเจ้าหน้าที่ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศ ณ วันที่ ๙ กุมภาพันธ์ พ.ศ. ๒๕๖๐) ซึ่งระบุพนักงานเจ้าหน้าที่ต้องมีคุณสมบัติตามข้อ ๑. ถึง ๓. </div>
    //                 <div style="text-indent:125px">๒.๔ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมในการเป็นผู้มีอำนาจพิจารณาดำเนินการตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (สั่ง ณ วันที่ ๑๓พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ และข้อ ๕.๒ ในกรณีที่ข้าราชการผู้รับมอบอำนาจตามข้อ ๓.ไม่อาจปฏิบัติราชการได้ หรือไม่มีผู้ดำรงตำแหน่งดังกล่าว ให้รองเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมที่กำกับ เป็นผู้พิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</div>
    //             HTML;

    //     $data->fix_text2 = <<<HTML
    //                 <div class="section-title">๓. สาระสำคัญและข้อเท็จจริง</div>
    //                 <div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองห้องปฏิบัติการ สมอ. มีอำนาจหน้าที่ในการรับรองความสามารถห้องปฏิบัติการ กำหนดให้มีการประเมินเพื่อพิจารณาให้การรับรองความสามารถห้องปฏิบัติการ{{$data->lab_type}} ตามมาตรฐานเลขที่ มอก. 17025-2561</div>
    //             HTML;

    //     return view('certificate.labs.auditor-labs.initial-message-record', [
    //         'data' => $data,
    //         'id' => $id
    //     ]);
    // }

    // public function genTrackingLabMessageRecordPdf()
    // {
       
    //     $board = TrackingAuditors::find(324);

    //     // dd($board);

    //     // $this->set_mail($board,$board->CertiLabs);
    //     $pdfService = new CreateTrackingLabMessageRecordPdf($board,"ia");
    //     $pdfContent = $pdfService->generateBoardTrackingAuditorMessageRecordPdf();
    // }

    // public function updateTrackingLabPayin1()
    // {



    //     $today = now(); // กำหนดวันปัจจุบัน

    //     $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $today)
    //         ->where('invoiceEndDate', '>=', $today)
    //         ->where(function ($query) {
    //             $query->whereNull('status_confirmed')
    //                   ->orWhere('status_confirmed', 0);
    //         })
    //         ->where('state',1)
    //         ->where('count','<=',3)
    //         ->where(function ($query) {
    //             $query->where('ref1', 'like', 'SurLab%');
    //         })
    //         ->get();

        

    //     foreach ($transactionPayIns as $transactionPayIn) {
    //         $ref1 = $transactionPayIn->ref1;
    //         $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
    //         // dd($result);
    //         // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
    //         if ($result instanceof \Illuminate\Http\JsonResponse) {
    //             // แปลง JsonResponse เป็น array
    //             $resultArray = $result->getData(true);
        
    //             // ตรวจสอบค่า message
    //             if (!empty($resultArray['message']) && $resultArray['message'] === true) {
    //                 // ดึงค่าทั้งหมดจาก response
    //                 $response = $resultArray['response'] ?? null;
        
    //                 // ตรวจสอบว่า response เป็น array หลายรายการหรือไม่
    //                 if (is_array($response) && count($response) > 0) {
    //                     // ใช้ array_map เพื่อดึง ref1
            
                        
    //                     $parts = explode('-', $ref1);
    //                     $trackingBoardAuditorId = end($parts); // ค่าสุดท้ายคือ 336
    //                     $auditor = TrackingAuditors::findOrFail($trackingBoardAuditorId);
    //                     $referenceRefno = implode('-', array_slice($parts, 0, -1));

    //                     // dd($referenceRefno);

    //                     $attachFile = AttachFile::where('section',"attach_payin1")
    //                     ->where('ref_id',$transactionPayIn->ref_id)
    //                     ->first();
    //                     // dd($transactionPayIn->id,$attachFile);
    //                     $url = null;
    //                     $new_filename = null;
    //                     $filename = null;
    //                     if($attachFile != null)
    //                     {
    //                         if($attachFile->url != null)
    //                         {
    //                             $url = $attachFile->url;
    //                         }
    //                         if($attachFile->new_filename != null)
    //                         {
    //                             $new_filename = $attachFile->new_filename;
    //                         }
    //                         if($attachFile->filename != null)
    //                         {
    //                             $filename = $attachFile->filename;
    //                         }
    //                     }
                        
    //                     if ($url != null) {
    //                         if (HP::checkFileStorage($url)) {
    //                             // ดึงไฟล์ที่ดาวน์โหลดมา
    //                             $localFilePath = HP::getFileStoragePath($url);

    //                             $tb       = new TrackingPayInOne;
    //                             $pay_in   = TrackingPayInOne::where('reference_refno',$referenceRefno)->first();
    //                             // $config   = HP::getConfig();
    //                             $app_certi_tracking = Tracking::where('reference_refno',$referenceRefno)->first();
    //                             $taxNumber = $app_certi_tracking->tax_id;

    //                             $filePath = 'files/trackinglabs/' . $referenceRefno;

                              
    //                             $localFilePath = HP::getFileStoragePath($url);

    //                             if (file_exists($localFilePath)) {
    //                                 // จำลองไฟล์อัปโหลด
    //                                 $uploadedFile = new \Illuminate\Http\UploadedFile(
    //                                     $localFilePath,      // Path ของไฟล์
    //                                     basename($localFilePath), // ชื่อไฟล์
    //                                     mime_content_type($localFilePath), // MIME type
    //                                     null,               // ขนาดไฟล์ (null ถ้าไม่ทราบ)
    //                                     true                // เป็นไฟล์ที่ valid แล้ว
    //                                 );
                        
    //                                 // ใช้ไฟล์ที่จำลองในการอัปโหลด
    //                                 $file_payin = HP::singleFileUploadRefno(
    //                                     $uploadedFile,                          // ใช้ไฟล์ที่จำลองแทนไฟล์ input
    //                                     $filePath,
    //                                     $taxNumber,
    //                                     $auditor->no,
    //                                     'ACC',
    //                                     $tb->getTable(),
    //                                     $pay_in->id,
    //                                     'attachs_file',
    //                                     null
    //                                 );
                        
    //                                 // ลบไฟล์ที่ดาวน์โหลดมา
    //                                 unlink($localFilePath);
    //                         }
    //                         // dd($transactionPayIns);
    //                     }
    //                 }
 

    //                 } else {
    //                     dd("Response is empty or not an array.");
    //                 }
    //             } else {
    //                 dd("No valid message or response.");
    //             }
    //         } else {
    //             dd("Invalid response type. Expected JsonResponse.");
    //         }
    //     }
    // }


    // public function trackingLabReportPdf()
    // {
    //     // dd('ok');
    //     $tracking_report_info_id = 3;
    //     $pdfService = new CreateTrackingLabReportPdf($tracking_report_info_id,"ia");
    //     $pdfContent = $pdfService->generateTrackingLabReportPdf();
    // }

    public function trackingDataList()
    {
    
            // dd($request->all());
            $userLogIn  = Auth::check()?Auth::user():null;
            $roles      = !empty( $userLogIn ) ? auth()->user()->roles->pluck('id')->toArray() : []; 

            $model = str_slug('trackinglabs', '-');          
            $filter_search          = null;
            $filter_certificate_no  = null;
            $filter_status_id       = null;
            $filter_start_date      = null;
            $filter_end_date        = null;

    
            //ตั้งค่าการตรวจติดตามใบรับรอง             
            $setting_config  = SettingConfig::where('grop_type','lab')->first();
            $from_filed      = !empty($setting_config->from_filed)?$setting_config->from_filed:null;
            $condition_check = !empty($setting_config->condition_check)?$setting_config->condition_check:null;
            $warning_day     = !empty($setting_config->warning_day)?$setting_config->warning_day:null;
            $check_first     = !empty($setting_config->check_first)?$setting_config->check_first:null;
    
            $app_certi_lab_id  = CheckExaminer::where('user_id',auth()->user()->runrecno)->select('app_certi_lab_id'); // เช็คเจ้าหน้าที่ LAB
    
            $certificate_exports = CertificateExport::LeftJoin((new CertiLab)->getTable()." AS certi_labs", 'certi_labs.id', '=', 'certificate_exports.certificate_for')
                                                    ->leftJoin((new Tracking)->getTable(), function($query) {
                                                        $query->on('app_certi_tracking.ref_id', 'certificate_exports.id')->where('app_certi_tracking.ref_table',(new CertificateExport)->getTable());
                                                    })
                                                    ->LeftJoin((new TrackingStatus)->getTable()." AS app_certi_tracking_status", 'app_certi_tracking_status.id', '=', 'app_certi_tracking.status_id')
                                                    // //เงือนไขการแสดงข้อมูล
                                                    ->where( function($query) {
                                                        // certificate_exports.status สถานะ 4 คือจัดส่งใบรับรองแล้ว
                                                        $query->where('certificate_exports.status',4)
                                                            ->WhereNull('certificate_exports.status_revoke');
                                                    })
                                                    ->where( function($query) {
                                                        $query->whereNull('app_certi_tracking.id');
                                                    })
                                                    ->where('certificate_no','25-LB0003')->get();
                                                    
            dd($certificate_exports->first(),$certificate_exports->first()->CertiLabTo,$certificate_exports->first()->CertiLabTo->certi_auditors,$certificate_exports->first()->board_auditors);
          
                                                    // ->where( function($query)  use($userLogIn, $roles, $app_certi_lab_id ) {
                                                    //     if( in_array( 22 , $roles ) && Auth::check() && in_array( $userLogIn->IsGetIdRoles() , ['false'] )   ){ //ไม่ใช่ admin , ผอ , ลท ที่มี Role 22
    
                                                    //         $scope_ids =  CertificateExport::leftJoin( (new CertifyTestScope)->getTable()." AS test_scope", 'test_scope.app_certi_lab_id','certificate_exports.certificate_for')
                                                    //                                         ->leftJoin((new SetStandardUserSub)->getTable().' AS user_sub','user_sub.test_branch_id','test_scope.branch_id')
                                                    //                                         ->leftJoin((new SetStandardUser)->getTable().' AS user_set','user_set.id','user_sub.standard_user_id')
                                                    //                                         ->where('user_set.sub_department_id', $userLogIn->reg_subdepart  )
                                                    //                                         ->select('certificate_exports.id');
    
                                                                                            
                                                    //         $calibrate_ids =  CertificateExport::leftJoin( (new CertifyLabCalibrate)->getTable()." AS calibrate", 'calibrate.app_certi_lab_id','certificate_exports.certificate_for')
                                                    //                                         ->leftJoin((new SetStandardUserSub)->getTable().' AS user_sub','user_sub.items_id','calibrate.branch_id')
                                                    //                                         ->leftJoin((new SetStandardUser)->getTable().' AS user_set','user_set.id','user_sub.standard_user_id')
                                                    //                                         ->where('user_set.sub_department_id', $userLogIn->reg_subdepart  )
                                                    //                                         ->select('certificate_exports.id');
    
                                                    //         $query->whereIn('certificate_exports.id',$scope_ids)->OrwhereIn('certificate_exports.id',$calibrate_ids);
    
                                                    //     }else if( in_array( $userLogIn->IsGetIdRoles() , ['false'] ) ){
                                                    //         $query->whereIn('certificate_exports.certificate_for',$app_certi_lab_id);
                                                    //     }
                                                    // } )
                                           
                                                    // ->when($setting_config, function ($query) use ($from_filed, $condition_check, $warning_day, $check_first){
                                                    //     switch ( $from_filed ):
                                                    //         case "1": //วันที่ออกใบรับรอง
                                                    //             if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                                    //                 return $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(certificate_exports.certificate_date_start), INTERVAL 6 MONTH),CURDATE())' ), '<=', $warning_day);
                                                    //             }else{
                                                    //                 return $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(certificate_exports.certificate_date_start), INTERVAL '.$condition_check.' MONTH),CURDATE())' ), '<=', $warning_day);
                                                    //             }
                                                    //             break;
                                                    //         case "3": //วันที่ตรวจล่าสุด
                                                    //             if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                                    //                 return  $query->whereHas('board_auditors', function($query)use ($warning_day){
                                                    //                             $query->whereHas('board_auditors_date', function($query) use ($warning_day){
                                                    //                                 $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(end_date), INTERVAL 6 MONTH),CURDATE())'), '<=', $warning_day);
                                                    //                             });
                                                    //                 });
                                                    //             }else{
                                                    //                 return  $query->whereHas('board_auditors', function($query)use ($condition_check, $warning_day){
                                                    //                             $query->whereHas('board_auditors_date', function($query) use ( $condition_check, $warning_day){
                                                    //                                 $query->Where(DB::raw('DATEDIFF(DATE_ADD(DATE(end_date), INTERVAL '.$condition_check.' MONTH),CURDATE())'), '<=', $warning_day);
                                                    //                             });
                                                    //                 });
                                                    //             }
                                                    //             break;
                                                    //     endswitch;
                                                    // })
                                                   
                                                    // // //filter
                                                    // ->when($filter_search, function ($query, $filter_search){
                                                    //     $search_full = str_replace(' ', '', $filter_search );
                                                    //     $query->where( function($query) use($search_full) {
                                                    //         $query->where(DB::Raw("REPLACE(certificate_exports.certificate_no ,' ','')"),  'LIKE', "%$search_full%")
                                                    //                 ->OrWhere(DB::raw("REPLACE(app_certi_tracking.reference_refno,' ','')"), 'LIKE', "%".$search_full."%")
                                                    //                 ->OrWhere(DB::raw("REPLACE(certi_labs.name,' ','')"), 'LIKE', "%".$search_full."%")
                                                    //                 ->OrWhere(DB::raw("REPLACE(certi_labs.lab_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                    //             });
                                                    // })
                                                   
                                                    // ->when($filter_certificate_no, function ($query, $filter_certificate_no){
                                                    //     return $query->where('certificate_exports.certificate_no', $filter_certificate_no);
                                                    // })
                                                    
                                                    // ->when($filter_status_id, function ($query, $filter_status_id){
                                                    //     return $query->where('app_certi_tracking.status_id', $filter_status_id);
                                                    // })

                                                   
                                              
                                                    // ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date, $from_filed){
    
                                                    //     $start_date =  $this->config_date($filter_start_date);
                                                    //     $end_date   =  $this->config_date($filter_end_date);
                                                    //     if($from_filed == 1){
                                                    //         if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                    //             return $query->whereBetween('certificate_exports.certificate_date_start',[$start_date,$end_date]);
                                                    //         }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                    //             return $query->whereDate('certificate_exports.certificate_date_start',$start_date);
                                                    //         }
                                                    //     }else if($from_filed == 3){
                                                    //         if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                    //             $auditors_id = BoardAuditorDate::whereBetween('end_date',[$start_date,$end_date])->select('board_auditors_id');
                                                    //             $app_certi_lab_id = BoardAuditor::whereIn('id',$auditors_id)->select('app_certi_lab_id');
                                                    //             return $query->whereIn('certi_labs.id', $app_certi_lab_id);
                                    
                                                    //         }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                    //             $auditors_id = BoardAuditorDate::whereDate('end_date',$start_date)->select('board_auditors_id');
                                                    //             $app_certi_ib_id = BoardAuditor::whereIn('id',$auditors_id)->select('app_certi_lab_id');
                                                    //             return $query->whereIn('certi_labs.id', $app_certi_ib_id);
                                                    //         }
                                                    //     }
                                                    // })



                                                    // ->select(
                                                    //     DB::raw('"1" AS type'),
                                                    //     DB::raw('certificate_exports.id                       AS id'),
                                                    //     DB::raw('certificate_exports.certificate_no           AS certificate_no'),
                                                    //     DB::raw('certificate_exports.certificate_date_start   AS date_start'),
                                                    //     DB::raw('app_certi_tracking.reference_refno           AS reference_refno'),
                                                    //     DB::raw('app_certi_tracking.status_id                 AS status_id'),
                                                    //     DB::raw('app_certi_tracking_status.title              AS status_title'),
                                                    //     DB::raw('certi_labs.name                              AS org_name'),
                                                    //     DB::raw('certi_labs.id                                AS certi_labs_id'),
                                                    //     DB::raw('certi_labs.lab_name                          AS lab_name'),
                                                    //     DB::raw('certificate_exports.updated_at               AS update_date')
                                                    // );
                
                $app_certi_tracking = Tracking::LeftJoin((new CertificateExport)->getTable()." AS certificate_exports", 'certificate_exports.id', '=', 'app_certi_tracking.ref_id')
                                                ->LeftJoin((new CertiLab)->getTable()." AS certi_labs", 'certi_labs.id', '=', 'certificate_exports.certificate_for')
                                                ->LeftJoin((new TrackingStatus)->getTable()." AS app_certi_tracking_status", 'app_certi_tracking_status.id', '=', 'app_certi_tracking.status_id')
                                                ->LeftJoin((new BoardAuditor)->getTable()." AS board_auditors", 'board_auditors.app_certi_lab_id', '=', 'certi_labs.id')
                                                ->LeftJoin((new BoardAuditorDate)->getTable()." AS board_auditors_date", 'board_auditors_date.board_auditors_id', '=', 'board_auditors.id')
                                                ->where( function($query){
                                                    $query->WhereNotNull('status_id')->Where('app_certi_tracking.ref_table',(new CertificateExport)->getTable());
                                                })
                                                ->where( function($query)  use($userLogIn, $roles, $app_certi_lab_id ) {
                                                    if( in_array( 22 , $roles ) && Auth::check() && in_array( $userLogIn->IsGetIdRoles() , ['false'] )   ){ //ไม่ใช่ admin , ผอ , ลท ที่มี Role 22
                                                        $scope_ids =  CertificateExport::leftJoin( (new CertifyTestScope)->getTable()." AS test_scope", 'test_scope.app_certi_lab_id','certificate_exports.certificate_for')
                                                                                        ->leftJoin((new SetStandardUserSub)->getTable().' AS user_sub','user_sub.test_branch_id','test_scope.branch_id')
                                                                                        ->leftJoin((new SetStandardUser)->getTable().' AS user_set','user_set.id','user_sub.standard_user_id')
                                                                                        ->where('user_set.sub_department_id', $userLogIn->reg_subdepart  )
                                                                                        ->select('certificate_exports.id');
    
                                                        $calibrate_ids =  CertificateExport::leftJoin( (new CertifyLabCalibrate)->getTable()." AS calibrate", 'calibrate.app_certi_lab_id','certificate_exports.certificate_for')
                                                                                        ->leftJoin((new SetStandardUserSub)->getTable().' AS user_sub','user_sub.items_id','calibrate.branch_id')
                                                                                        ->leftJoin((new SetStandardUser)->getTable().' AS user_set','user_set.id','user_sub.standard_user_id')
                                                                                        ->where('user_set.sub_department_id', $userLogIn->reg_subdepart  )
                                                                                        ->select('certificate_exports.id');
    
                                                        $query->whereIn('certificate_exports.id',$scope_ids)->OrwhereIn('certificate_exports.id',$calibrate_ids);
                                                    }else if( in_array( $userLogIn->IsGetIdRoles() , ['false'] )){
                                                        $query->whereHas('assigns_to', function($query) {
                                                                    $query->where('user_id',  auth()->user()->runrecno);
                                                                });
                                                    }
                                                })
                                                ->when($filter_search, function ($query, $filter_search){
                                                    $search_full = str_replace(' ', '', $filter_search );
                                                    $query->where( function($query) use($search_full) {
                                                        $query->where(DB::Raw("REPLACE(certificate_exports.certificate_no ,' ','')"),  'LIKE', "%$search_full%")
                                                                ->OrWhere(DB::raw("REPLACE(app_certi_tracking.reference_refno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(certi_labs.name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(certi_labs.lab_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                                })   
                                                ->when($filter_certificate_no, function ($query, $filter_certificate_no){
                                                    return $query->where('certificate_exports.certificate_no', $filter_certificate_no);
                                                })
                                                ->when($filter_status_id, function ($query, $filter_status_id){
                                                    return $query->where('app_certi_tracking.status_id', $filter_status_id);
                                                })
                                                ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date, $from_filed){
    
                                                    $start_date =  $this->config_date($filter_start_date);
                                                    $end_date   =  $this->config_date($filter_end_date);
                                                    if($from_filed == 1){
                                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                            return $query->whereBetween('certificate_exports.certificate_date_start',[$start_date,$end_date]);
                                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                            return $query->whereDate('certificate_exports.certificate_date_start',$start_date);
                                                        }
                                                    }else if($from_filed == 3){
                                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                            $auditors_id = BoardAuditorDate::whereBetween('end_date',[$start_date,$end_date])->select('board_auditors_id');
                                                            $app_certi_lab_id = BoardAuditor::whereIn('id',$auditors_id)->select('app_certi_lab_id');
                                                            return $query->whereIn('certi_labs.id', $app_certi_lab_id);
                                
                                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                            $auditors_id = BoardAuditorDate::whereDate('end_date',$start_date)->select('board_auditors_id');
                                                            $app_certi_ib_id = BoardAuditor::whereIn('id',$auditors_id)->select('app_certi_lab_id');
                                                            return $query->whereIn('certi_labs.id', $app_certi_ib_id);
                                                        }
                                                    }
                                                })
                                                ->select(
                                                    DB::raw('"2" AS type'),
                                                    DB::raw('app_certi_tracking.id                        AS id'),
                                                    DB::raw('certificate_exports.certificate_no           AS certificate_no'),
                                                    DB::raw('certificate_exports.certificate_date_start   AS date_start'),
                                                    DB::raw('app_certi_tracking.reference_refno           AS reference_refno'),
                                                    DB::raw('app_certi_tracking.status_id                 AS status_id'),
                                                    DB::raw('app_certi_tracking_status.title              AS status_title'),
                                                    DB::raw('certi_labs.name                              AS org_name'),
                                                    DB::raw('certi_labs.id                                AS certi_labs_id'),
                                                    DB::raw('certi_labs.lab_name                          AS lab_name'),
                                                    DB::raw('app_certi_tracking.updated_at                AS update_date')
                                                );
           
                $query =  $certificate_exports->union($app_certi_tracking);
                // $query =  $certificate_exports;
                // dd($filter_status_id );
                return Datatables::of($query)
                        ->with('filter_status_id', $filter_status_id) 
                        ->addIndexColumn()
                        ->addColumn('reference_refno', function ($item) {
                            return   !empty($item->type)? $item->type:'อยู่ระหว่างรับเรื่อง';
                        }) 
                        ->addColumn('checkbox', function ($item) {           
                            if(!empty($item->status_id)  && $item->status_id >= 8){
                                return '';
                            }else{
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'" data-status="'.( !empty($item->status_id) ? $item->status_id : '1').'" data-tracking_id="'.( $item->type == 2 ? $item->id: '').'" >';
                            }
                        })
                        ->addColumn('reference_refno', function ($item) {
                            return  !empty($item->reference_refno)? $item->reference_refno:'<em class="text-muted">อยู่ระหว่างรับเรื่อง</em>';
                        }) 
                        ->addColumn('certificate_no', function ($item) {
                            return  (!empty($item->certificate_no)? $item->certificate_no:'');
                        })
                        ->addColumn('org_name', function ($item) {
                            return  !empty($item->org_name)? $item->org_name:'';
                        }) 
                        ->addColumn('lab_name', function ($item) {
                            return  !empty($item->lab_name)? $item->lab_name:'';
                        }) 
                        ->addColumn('assign', function ($item) {

                            if( in_array( $item->type , [2] ) ){
                                $tracking = Tracking::where('id',$item->id)->first();
                                return !empty($tracking->assigns_to->user_assign->FullName)? $tracking->assigns_to->user_assign->FullName:'';
                            }
        
                        })
                        ->addColumn('status', function ($item) {
                                if(!empty($item->status_id) && $item->status_id == 3){
                                    $data_input =  'data-id="'.( !empty($item->id) ? $item->id : '').'"';
                                    $data_input .= 'data-refno="'.( !empty($item->reference_refno) ? $item->reference_refno : '').'" ';
                                    return !empty($item->status_title) ?  '<button type="button" class="modal_status btn btn-secondary"  '.( $data_input ).' ">'. (!empty($item->status_title)? $item->status_title:'รอดำเนินการตรวจ'). '</button>':'';
                                }else{
                                    return   !empty($item->status_title)? $item->status_title:'รอดำเนินการตรวจ';
                                }
                        })
                        ->addColumn('certificate_newfile', function ($item) {

                            if(($item->type == 1)){
                                $data = CertificateExport::where('id',$item->id)->first();
                            }else{
                                $tracking = Tracking::where('id',$item->id)->first();
                                $data = $tracking->certificate_export_to;
                            }

                            if(!empty($data->certificate_newfile)){
                                $text =   '<a href="'. ( url('funtions/get-view').'/'.$data->certificate_path.'/'.$data->certificate_newfile.'/'.$data->certificate_no.'_'.date('Ymd_hms').'pdf' ).'" target="_blank">
                                                    <img src="'.(asset('images/icon-certification.jpg')).'" width="15px" >
                                            </a> ';
                            }else if(!empty($data->CertiLabTo->attachs)){
                                    $text =   '<a href="'. ( url('certify/check/file_client').'/'.$data->CertiLabTo->attachs.'/'. ( !empty($data->CertiLabTo->attachs_client_name) ? $data->CertiLabTo->attachs_client_name :  basename($data->CertiLabTo->attachs)  )).'" target="_blank">
                                                    '. HP::FileExtension($data->CertiLabTo->attachs).' 
                                            </a> ';
                            }else{
                                $text =  '<a class="btn btn-link" href="'.(url('certify/send-certificates/view-pdf/'.$data->ref_id.'/3')  ).'" target="_blank"> <i class="fa fa-file-pdf-o" style="color:red"></i> </a>'; 
                            }

                            return  $text;
                        })
                        ->addColumn('end_date', function ($item) use ($from_filed, $condition_check, $check_first){
                            // dd($item,$from_filed);
                            switch ( $from_filed ):
                                case "1": //วันที่ออกใบรับรอง
                                    if(!empty($item->date_start)){
                                        
                                    $date_start = $item->date_start;
                                        if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน      
                                            $config_date_start = date('Y-m-d', strtotime('+6 months', strtotime($date_start)));
                                        }else{
                                            $config_date_start =  date('Y-m-d', strtotime('+'.$condition_check.' months', strtotime($date_start)));
                                        }
                                        return !empty($config_date_start) ? HP::DateThai($config_date_start):'-';
                                    }                    
                                    break;
                                case "3": //วันที่ตรวจล่าสุด
                                    if(!empty($item->certi_labs_id)){
                                        $board_auditors = BoardAuditor::where('app_certi_lab_id',$item->certi_labs_id)->first();
                                            if(!empty($board_auditors->id)){
                                                $board_auditors_date = BoardAuditorDate::where('board_auditors_id',$board_auditors->id)->orderby('id','desc')->first();
                                                $end_date = $board_auditors_date->end_date;
                                            }

                                            if(isset($end_date)){
                                                if($check_first == 1){//ตรวจติดตามครั้งแรก 6 เดือน
                                                    $config_end_date =  date('Y-m-d', strtotime('+6 months', strtotime($end_date)));
                                                }else{
                                                    $config_end_date =  date('Y-m-d', strtotime('+'.$condition_check.' months', strtotime($end_date)));
                                                }
                                                return !empty($config_end_date) ? HP::DateThai($config_end_date):'-';
                                            }
                                    }
                                    break;
                                    default:
                                            return '-';
                                    break;
                            endswitch;
                        })
                        ->addColumn('action', function ($item) use($model) {
                            if($item->type == 2){
                                return HP::buttonAction( $item->id, 'certificate/tracking-labs','Certificate\\TrackingLabsController@destroy', 'trackinglabs',false,true,false);
                            }else{
                                return '';
                            }
                        })
                        ->order(function ($query) use ($filter_status_id) {
                            // ตรวจสอบว่า $filter_status_id ไม่เป็น null
                            if ($filter_status_id !== null) {
                                // ถ้าไม่เป็น null ให้จัดเรียงโดยใช้ update_date
                                $query->orderBy('update_date', 'DESC');
                            }
                        })
                        ->rawColumns([ 'checkbox','certificate_newfile','assign','status', 'action', 'reference_refno']) 
                        ->make(true);                                      

    }

    public function downloadFromCloud()
    {

        $signAssessmentReportTransaction = SignAssessmentTrackingReportTransaction::find(63);
        $appId = $signAssessmentReportTransaction->app_id;
        $certiLab = TrackingAssessment::where('reference_refno',$appId)->first()->certificate_export_to->applications;

        // dd($certiLab);

        $certiLabFileAll = CertLabsFileAll::where('app_certi_lab_id',$certiLab->id)->first();

        $filePath = 'files/applicants/check_files/' . $certiLabFileAll->attach_pdf ;

        $localFilePath = HP::downloadFileFromTisiCloud($filePath);

        $trackingInspection = TrackingInspection::where('reference_refno',$appId)->first();






        dd($trackingInspection);

    }

    public function getAttachedFileFromRequest()
    {
        $certificateExport = CertificateExport::where('request_number','CAL-68-007')->first();
        if($certificateExport != null)
        {
            $trackingAssessment = TrackingAssessment::where('ref_table','certificate_exports')->where('ref_id',$certificateExport->id)->first();
            if($trackingAssessment != null)
            {
                $trackingInspection = TrackingInspection::where('reference_refno',$trackingAssessment->reference_refno)->first();
                if($trackingInspection != null)
                {
                    $attachFile = AttachFile::where('ref_table','app_certi_tracking_inspection')->where('ref_id',$trackingInspection->id)->first();
                    
                    if($attachFile->filename != null)
                    {
                        dd($attachFile->filename);
                    }
                }
            }
        }

    }


    public function getDocReviewAuditor()
    {
        $cbDocReviewAuditor = CbDocReviewAuditor::first();
        $certi_cb= CertiCb::find($cbDocReviewAuditor->app_certi_cb_id);
        // dd($certi_cb);
        $this->sendMailAuditorDocReview($certi_cb,$cbDocReviewAuditor);
       
    }

    public function sendMailAuditorDocReview($certi_cb,$cbDocReviewAuditor)
    {
      if(!is_null($certi_cb->email))
      {
  
          $config = HP::getConfig();
          $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
  
          if(!empty($certi_cb->DataEmailDirectorCBCC)){
              $mail_cc = $certi_cb->DataEmailDirectorCBCC;
              array_push($mail_cc, auth()->user()->reg_email) ;
          }
          $auditors = json_decode($cbDocReviewAuditor->auditors, true);
  
          $data_app = [
                        'title'          =>  'แต่งตั้งคณะผู้ตรวจประเมินเอกสาร',
                        'cbDocReviewAuditor'       => $cbDocReviewAuditor,
                        'auditors'       => $auditors,
                        'certi_cb'       => $certi_cb ,
                        'url'            => $url.'certify/applicant-cb' ?? '-',
                        'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                        'email_cc'       =>  !empty($mail_cc) ? $mail_cc : 'cb@tisi.mail.go.th',
                        'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                  ];
  
          $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                  $certi_cb->id,
                                                  (new CertiCb)->getTable(),
                                                  $certi_cb->id,
                                                  (new CbDocReviewAuditor)->getTable(),
                                                  $cbDocReviewAuditor->id,
                                                  'แต่งตั้งคณะผู้ตรวจประเมินเอกสาร',
                                                  view('mail.CB.auditor_doc_review', $data_app),
                                                  $certi_cb->created_by,
                                                  $certi_cb->agent_id,
                                                  auth()->user()->getKey(),
                                                  !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                  $certi_cb->email,
                                                  !empty($mail_cc) ?  implode(',',(array)$mail_cc)  : 'cb@tisi.mail.go.th',
                                                  !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                  null
                                                  );
  
          $html = new CbDocReviewAuditorsMail($data_app);
          $mail =  Mail::to($certi_cb->email)->send($html);
  
          if(is_null($mail) && !empty($log_email)){
              HP::getUpdateCertifyLogEmail($log_email->id);
          } 
      }
    }

    public function copyScopeCbFromAttachement()
    {
        $copiedScoped = null;
        $fileSection = null;

        $appId = 237;
        $latestRecord = CertiCBAttachAll::where('app_certi_cb_id', $appId)
        ->where('file_section', 3)
        ->where('table_name', 'app_certi_cb')
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();
    

        $existingFilePath = 'files/applicants/check_files_cb/' . $latestRecord->file ;

        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            dd($localFilePath);
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files_cb/'.$no.'/';

            if (file_exists($localFilePath)) {
                $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );
                $filePath = $attach_path . $dlName;
                if (Storage::disk('ftp')->exists($filePath)) {
                    $list  = new  stdClass;
                    $list->attachs =  $no.'/'.$dlName;
                    $list->file_client_name =  $dlName;
                    $scope[] = $list;
                    $copiedScoped = json_encode($scope);
                } 
                unlink($localFilePath);
            }
        }

        return $copiedScoped;
    }

    public function textSpliter()
    {
        $text = "ลูกชายส่งข้อความถึงพ่อที่ล่วงลับ แต่กลับได้รับคำตอบอย่างไม่คาดคิด เบื้องหลังเรื่องราวนี้ช่างซาบซึ้งเกินบรรยาย
        แม้โลกนี้จะเต็มไปด้วยความเจ็บปวด แต่โชคดีที่ความรักยังคงอยู่
        ย้อนกลับไปเมื่อวันที่ 26 มีนาคม 2023 นักศึกษาชายคนหนึ่งจากซีอาน มณฑลส่านซี ประเทศจีน ได้ส่งข้อความถึงพ่อที่จากไปเมื่อ 3 ปีก่อน และไม่คาดคิดว่าจะได้รับคำตอบกลับมา
        เขากำลังเผชิญกับความยากลำบากในการสอบเข้าปริญญาโท และด้วยความโศกเศร้า คิดถึงพ่อที่ล่วงลับ จึงตัดสินใจส่งข้อความไปยังเบอร์โทรศัพท์เก่าของพ่อ ก่อนจะได้รับกำลังใจจากคนแปลกหน้า
        คุณเฉา ผู้รับข้อความ เล่าว่า ตอนแรกเขาคิดว่าเป็นการส่งผิด แต่เมื่ออ่านแล้วกลับรู้สึกว่าลูกชายคนนี้อาจกำลังต้องการกำลังใจ เขาอยากส่งให้พ่อ แต่ผมคิดว่าพ่อของเขาคงจากไปแล้ว อาจเป็นเพราะตอนนี้เขากำลังลำบากและต้องการคำปลอบโยน
        หลังจากไตร่ตรอง คุณเฉาตัดสินใจตอบกลับ หวังให้คำพูดของเขาช่วยเติมพลังใจ เสมือนพ่อที่กำลังปลอบโยนลูกให้ก้าวผ่านช่วงเวลาที่ยากลำบาก
        เมื่อได้รับข้อความตอบกลับจากเบอร์โทรศัพท์เก่าของพ่อ ผู้เป็นลูกชายถึงกับกลั้นน้ำตาไว้ไม่อยู่ เขาขอบคุณคุณเฉา บอกว่าจะไม่รบกวนอีก พร้อมสารภาพว่าน้ำตาไหล
        เหตุการณ์อันแสนอบอุ่นนี้สร้างความประทับใจให้กับทุกคนที่ได้รับรู้";
        $textArray = TextHelper::callLonganTokenizeArrayPost($text);

        print($textArray);
    }

    public function demo_html_pdf_editor()
    {
        return view('demo_html_pdf_editor.initial-message-record');
    }

    public function createCbAssessmentReportPdf()
    {
        $lastRecord = CbReportInfo::orderBy('id', 'desc')->first();
        $pdfService = new CreateCbAssessmentReportPdf($lastRecord->id,"ia");
        $pdfContent = $pdfService->generateCbAssessmentReportPdf();
    }

    public function createCbAssessmentReportTwoPdf()
    {
        $lastRecord = CbReportTwoInfo::orderBy('id', 'desc')->first();
        $pdfService = new CreateCbAssessmentReportTwoPdf($lastRecord->id,"ia");
        $pdfContent = $pdfService->generateCbAssessmentReportTwoPdf();
    }

    public function createCbMessageRecordPdf()
    {
        $id = 365;
        $boardAuditor = CertiCBAuditors::find($id);
        // dd($boardAuditor);
        $pdfService = new CreateCbMessageRecordPdf($boardAuditor,"ia");
        $pdfContent = $pdfService->generateBoardAuditorMessageRecordPdf();
    }


    public function runAllSchedules()
    {
        Artisan::call('run:all-schedules');
        return response()->json(['message' => 'All schedules have been run successfully']);
    }

    public function check_payin2_cb()
    {
    // การทดสอบต้องลด invoiceStartDate ลง 1 วัน
      $today = Carbon::today(); // กำหนดวันปัจจุบัน

      $now = Carbon::now();
  
      $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $now)
    ->where('invoiceEndDate', '>=', $now)
    //   ->whereNull('status_confirmed')
    ->where(function ($query) {
        $query->where('status_confirmed', 0)
            ->orWhereNull('status_confirmed');
    })
      ->where('state',2)
      ->where('count','<=',3)
      ->where(function ($query) {
          $query->where('ref1', 'like', 'CB%');
      })
      ->get();
      dd($transactionPayIns);
      
      
      foreach ($transactionPayIns as $transactionPayIn) {
        $ref1 = $transactionPayIn->ref1;
        $result = $this->callCheckBill($ref1); // เรียกฟังก์ชัน
        
        // ตรวจสอบว่า $result เป็น JsonResponse หรือไม่
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            // แปลง JsonResponse เป็น array
            $resultArray = $result->getData(true);
            // dd($resultArray);
            // ตรวจสอบค่า message
            if (!empty($resultArray['message']) && $resultArray['message'] === true) {
                // ดึงค่าทั้งหมดจาก response
                $response = $resultArray['response'] ?? null;
    
                // ตรวจสอบว่า response เป็น array หลายรายการหรือไม่
                if (is_array($response) && count($response) > 0) {
                    // ใช้ array_map เพื่อดึง ref1
                    $ref1List = array_map(function ($item) {
                        return isset($item['ref1']) ? $item['ref1'] : null;
                    }, $response);
    
                    // กรองเฉพาะ ref1 ที่ไม่เป็น null
                    $validRef1 = array_filter($ref1List);
                    $tb = new CertiCBPayInTwo;

                    // $appCertiLabCostCertificateId = $transactionPayIn->ref_id;
                    $PayIn = CertiCBPayInTwo::findOrFail($transactionPayIn->ref_id);
                    $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);
                    $certiCBAttachAll = CertiCBAttachAll::where('table_name', $tb->getTable())
                        ->where('app_certi_cb_id', $PayIn->app_certi_cb_id)
                        ->where('ref_id', $PayIn->id)
                        ->orderBy('created_at', 'desc') // หรือกรณีที่ใช้ฟิลด์อื่นในการจัดลำดับ
                        ->first();


                    $certi_cb = CertiCb::findOrFail($PayIn->app_certi_cb_id);
                    $certi_cb_attach_more                    = new CertiCBAttachAll();
                    $certi_cb_attach_more->app_certi_cb_id   = $certi_cb->id;
                    $certi_cb_attach_more->ref_id            = $PayIn->id;
                    $certi_cb_attach_more->table_name        = $tb->getTable();
                    $certi_cb_attach_more->file              = $certiCBAttachAll->file;
                    $certi_cb_attach_more->file_client_name  = $certiCBAttachAll->file_client_name;
                    $certi_cb_attach_more->file_section      = '2';
                    $certi_cb_attach_more->token             = str_random(16);
                    $certi_cb_attach_more->save();

                    $PayIn->degree = 3 ; 
                    $PayIn->status = null ; 
                    $PayIn->report_date = null ; 
                    $PayIn->status = 2 ; 
                    $PayIn->condition_pay = null ; 
                    $PayIn->save();

                    $certi_cb->status = 17;
                    $certi_cb->save();

                    $transaction_payin  =  TransactionPayIn::where('ref_id',$PayIn->id)->where('table_name', (new CertiCBPayInTwo)->getTable())->orderby('id','desc')->first();
                    if(!is_null($transaction_payin)){
                        $transaction_payin->ReceiptCreateDate     =  Carbon::now(); 
                        $transaction_payin->ReceiptCode           =  '123456' ; 
                        $transaction_payin->save();
                    }
                        

                } else {
                    dd("Response is empty or not an array.");
                }
            } else {
                dd("No valid message or response.");
            }
        } else {
            dd("Invalid response type. Expected JsonResponse.");
        }
    }
    
    }

    public function createLabAssessmentReportTwoPdf()
    {

        $pdfService = new CreateLabAssessmentReportTwoPdf(2,"ia");
        $pdfContent = $pdfService->generateLabReportTwoPdf();
    }

    public function getEmailInfo()
    {
        $sendCertificateList = SendCertificateLists::find(525);
        $app =null;
        if($sendCertificateList->certificate_type == 3){
            $app = $sendCertificateList->app_cert_to;
        }else if($sendCertificateList->certificate_type == 2)
        {
            $app = $sendCertificateList->app_cert_ib_to;
        }else if($sendCertificateList->certificate_type == 1)
        {
            // dd('cb');
            $app = $sendCertificateList->app_cert_cb_to;
        }
        // dd($app);
        $mail = auth()->user()->reg_email;
        $app = $sendCertificateList->app_cert_to;
        // dd($app);
        $dataMailCenter = $app->DataEmailCertifyCenter;
        $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
        $EMail =  array_key_exists($app->subgroup,$dataMail)  ? $dataMail[$app->subgroup] :'admin@admin.com';
        dd($dataMailCenter,$EMail);
    }

    public function check_ib_payin()
    {
        $today = now(); // กำหนดวันปัจจุบัน

        $transactionPayIns = TransactionPayIn::where('invoiceStartDate', '<=', $today)
            ->where('invoiceEndDate', '>=', $today)
            ->where(function ($query) {
                $query->where('status_confirmed', 0)
                    ->orWhereNull('status_confirmed');
            })
            ->where('state', 1)
            ->where('count', '<=', 3)
            ->where(function ($query) {
                $query->where('ref1', 'like', 'IB%');
            })
            ->get();
        dd($transactionPayIns);
    }

    public function createIbAssessmentReportPdf()
    {
        $lastRecord = IbReportInfo::orderBy('id', 'desc')->first();
        // dd($lastRecord->id);
        $pdfService = new CreateIbAssessmentReportPdf($lastRecord->id,"ia");
        $pdfContent = $pdfService->generateIbAssessmentReportPdf();
    }

    public function createIbAssessmentReportTwoPdf()
    {
        $lastRecord = IbReportTwoInfo::orderBy('id', 'desc')->first();
        $pdfService = new CreateIbAssessmentReportTwoPdf($lastRecord->id,"ia");
        $pdfContent = $pdfService->generateIbAssessmentReportTwoPdf();
    }

    public function createTrackingIbMessageRecordPdf()
    {
        $board = TrackingAuditors::find(317);
        // dd($board);
        $pdfService = new CreateTrackingIbMessageRecordPdf($board,"ia");
        $pdfContent = $pdfService->generateBoardTrackingAuditorMessageRecordPdf();
    }

        public function createTrackingCbMessageRecordPdf()
    {
        $board = TrackingAuditors::find(316);
        // dd($board);
        $pdfService = new CreateTrackingCbMessageRecordPdf($board,"ia");
        $pdfContent = $pdfService->generateBoardTrackingAuditorMessageRecordPdf();
    }


    public function createTrackingLabAssessmentReportOnePdf()
    {
        $pdfService = new CreateTrackingLabAssessmentReportOnePdf(179);
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }

    public function createTrackingIbAssessmentReportOnePdf()
    {
        $pdfService = new CreateTrackingIbAssessmentReportOnePdf(180);
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }

    public function createTrackingCbAssessmentReportOnePdf()
    {
        $pdfService = new CreateTrackingCbAssessmentReportOnePdf(181);
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }
    
    public function createTrackingLabAssessmentReportTwoPdf()
    {
        $pdfService = new CreateTrackingLabAssessmentReportTwoPdf(179);
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }

    public function createTrackingIbAssessmentReportTwoPdf()
    {
        $pdfService = new CreateTrackingIbAssessmentReportTwoPdf(180);
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }

    public function createTrackingCbAssessmentReportTwoPdf()
    {
        $pdfService = new CreateTrackingCbAssessmentReportTwoPdf(181);
        $pdfContent = $pdfService->generateLabAssessmentReportPdf();
    }
    
    //    $trackingLabReportTwo = $signAssessmentTrackingReportTransaction->trackingLabReportTwo;
    //                 $pdfService = new CreateTrackingLabAssessmentReportTwoPdf($trackingLabReportTwo->tracking_assessment_id);
    //                 $pdfContent = $pdfService->generateLabAssessmentReportPdf();
}


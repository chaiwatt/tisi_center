<?php

namespace App\Services;
use HP;

use TCPDF;
use Storage;

use App\User;

use stdClass;
use Mpdf\Mpdf;
use Carbon\Carbon;

use App\AttachFile;
use Mpdf\Merger\PdfMerger;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Helpers\EpaymentDemo;
use App\Models\Besurv\Signer;
use Illuminate\Support\Facades\Log;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Certify\LabReportInfo;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\BoardAuditorDate;
use App\Models\Certify\TransactionPayIn;
use App\Models\Bcertify\BoardAuditoExpert;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\CertiSettingPayment;
use App\Services\CreateLabMessageRecordPdf;
use App\Models\Bcertify\LabScopeTransaction;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingAssessment;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certificate\TrackingLabReportTwo;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\MessageRecordTransaction;
use App\Http\Controllers\API\Checkbill2Controller;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Bcertify\HtmlLabMemorandumPdfRequest;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;
use App\Models\Certificate\SignAssessmentTrackingReportTransaction;

class CreateTrackingLabAssessmentReportTwoPdf
{
    protected $assessmentId;


    public function __construct($assessmentId)
    {
        $this->assessmentId = $assessmentId;

    }



    public function generateLabAssessmentReportPdf()
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
            'margin_left'      => 12, // ระบุขอบด้านซ้าย
            'margin_right'     => 15, // ระบุขอบด้านขวา
            'margin_top'       => 15, // ระบุขอบด้านบน
            'margin_bottom'    => 15, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);         


        $mpdf->useDictionaryLBR = false;
        $mpdf->SetDefaultBodyCSS('KeepTableProportions', 1);


        $assessment = TrackingAssessment::find($this->assessmentId);

       
       $labReportTwo = TrackingLabReportTwo::with('attachments')->where('tracking_assessment_id',$this->assessmentId)->first();
        // dd($assessment);
       $certi_lab = $assessment->certificate_export_to->CertiLabTo;
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


        $scope_branch = "";
        if ($certi_lab->lab_type == 3){
            $scope_branch =$certi_lab->BranchTitle;
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
        $data->tracking = $tracking;
        // $data->app_no = 'ทดสอบ ๑๖๗๑';
        $data->certificate_no = '13-LB0037';
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        // $data->experts = $experts;

        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;

        $labRequest = null;
            
        if($certi_lab->lab_type == 4){
            $labRequest = LabCalRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        }else if($certi_lab->lab_type == 3)
        {
            $labRequest = LabTestRequest::where('app_certi_lab_id',$certi_lab->id)->where('type',1)->first();
        }
        
        $signAssessmentReportTransactions = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportTwo->id)
                                        ->where('certificate_type',2)
                                        ->where('report_type',2)
                                        ->get();


        $signer = new stdClass();

        $signer->signer_1 = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportTwo->id)->where('signer_order','1')
                                                        ->where('certificate_type',2)
                                                        ->where('report_type',2)
                                                        ->first();

        
        $signer->signer_2 = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportTwo->id)->where('signer_order','2')
                                                        ->where('certificate_type',2)
                                                        ->where('report_type',2)
                                                        ->first();
        $signer->signer_3 = SignAssessmentTrackingReportTransaction::where('tracking_report_info_id',$labReportTwo->id)->where('signer_order','3')
                                                        ->where('certificate_type',2)
                                                        ->where('report_type',2)
                                                        ->first();

        $attach1 = !empty($signer->signer_1->signer->AttachFileAttachTo) ? $signer->signer_1->signer->AttachFileAttachTo : null;
        $attach2 = !empty($signer->signer_2->signer->AttachFileAttachTo) ? $signer->signer_2->signer->AttachFileAttachTo : null;
        $attach3 = !empty($signer->signer_3->signer->AttachFileAttachTo) ? $signer->signer_3->signer->AttachFileAttachTo : null;

        $sign_url1 = $this->getSignature($attach1);
        $sign_url2 = $this->getSignature($attach2);
        $sign_url3 = $this->getSignature($attach3);

        $signer->signer_url1 = $sign_url1;
        $signer->signer_url2 = $sign_url2;
        $signer->signer_url3 = $sign_url3;

        $labInformation = $certi_lab->information;


        $attachFiles = AttachFile::where('ref_table','tracking_lab_report_twos')->where('ref_id',$labReportTwo->id)->where('section','11111')->get();

        // dd($attachFiles);

        $body = view('certificate.labs.assessment-labs.report-pdf.report-two.body', [
            'data' => $data,
            'assessment' => $assessment,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'tracking' => $tracking,
            'certi_lab' => $certi_lab,
            'labRequest' => $labRequest,
            'labReportTwo' => $labReportTwo,
            'labInformation' => $labInformation[0],
            'signer' => $signer,
            'attachFiles' => $attachFiles,
            'audit_date' => HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date)
        ]);

        $footer = view('certificate.labs.assessment-labs.report-pdf.report-two.footer', []);

        $stylesheet = file_get_contents(public_path('css/report/lab-report.css'));
        $mpdf->WriteHTML($stylesheet, 1);
       
        $mpdf->WriteHTML($body,2);

        $mpdf->SetHTMLFooter($footer,2);


        // $title = "message_record.pdf";

        // $mpdf->Output($title, 'I');
        
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
        $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);

        // อ่านไฟล์ชั่วคราวและแปลงเป็น UploadedFile (จำลองอัปโหลดไฟล์)
        $file = new \Illuminate\Http\UploadedFile(
            $tempFilePath,
            'lab_tracking_report_two.pdf',
            'application/pdf',
            null,
            true // กำหนดให้เป็นไฟล์ที่ผ่านการอัปโหลดจริง
        );

        // กำหนดค่าต่าง ๆ สำหรับอัปโหลด
        $attach_path = 'files/trackinglabs';
        $tax_number = (!empty(auth()->user()->reg_13ID) ? str_replace("-", "", auth()->user()->reg_13ID) : '0000000000000');

        HP::singleFileUploadRefno(
            $file, // ใช้ไฟล์ PDF ที่จำลองการอัปโหลด
            $attach_path . '/' . $assessment->reference_refno,
            $tax_number,
            auth()->user()->FullName ?? null,
            'Center',
            (new TrackingAssessment)->getTable(),
            $assessment->id,
            '3',
            null
        );

        HP::singleFileUploadRefno(
            $file, // ใช้ไฟล์ PDF ที่จำลองการอัปโหลด
            $attach_path . '/' . $assessment->reference_refno,
            $tax_number,
            auth()->user()->FullName ?? null,
            'Center',
            (new TrackingAssessment)->getTable(),
            $assessment->id,
            '5',
            null
        );

        unlink($tempFilePath);

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
}
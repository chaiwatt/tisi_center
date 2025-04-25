<?php

namespace App\Services;
use HP;

use Storage;
use App\User;

use stdClass;

use Mpdf\Mpdf;
use Carbon\Carbon;
use TCPDF;

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
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\MessageRecordTransaction;
use App\Http\Controllers\API\Checkbill2Controller;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Bcertify\HtmlLabMemorandumPdfRequest;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class CreateLabAssessmentReportPdf
{
    protected $labReportInfoId;
    protected $type;

    public function __construct($labReportInfoId,$type)
    {
        $this->labReportInfoId = $labReportInfoId;
        $this->type = $type;
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
        // dd('ok');
        if ($this->type == "ia"){
            $this->ia($mpdf);
        }

    
    }

    public function ia($mpdf)
    {
        $labReportInfo = LabReportInfo::find($this->labReportInfoId);
        
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
        $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
        $data->experts = $experts;
        $data->date_range = $dateRange;
        $data->statusAuditorMap = $statusAuditorMap;

        $assessment = $notice->assessment;
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

        $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)
                                        ->where('certificate_type',2)
                                        ->where('report_type',1)
                                        ->get();

        $signer = new stdClass();

        $signer->signer_1 = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->where('signer_order','1')
                                                        ->where('certificate_type',2)
                                                        ->where('report_type',1)
                                                        ->first();

        
        $signer->signer_2 = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->where('signer_order','2')
                                                        ->where('certificate_type',2)
                                                        ->where('report_type',1)
                                                        ->first();
        $signer->signer_3 = SignAssessmentReportTransaction::where('report_info_id',$labReportInfo->id)->where('signer_order','3')
                                                        ->where('certificate_type',2)
                                                        ->where('report_type',1)
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
        // $signer->signer_url4 = $sign_url4;
        // dd($boardAuditorMsRecordInfo);
        // $body = view('certify.save_assessment.report-pdf.ia.body', [
        //     'data' => $data,
        //     'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo,
        //     'signer' => $signer
        // ]);


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

        // $mpdf->WriteHTML($header,2);
        // $mpdf->SetHTMLFooter($footer);
        // $mpdf->WriteHTML($body, 2);

        // dd('here111');

        // $title = "message_record.pdf";

        // $mpdf->Output($title, 'I');
        
        
        // return;
        $no = str_replace("RQ-", "", $certi_lab->app_no);
        $no = str_replace("-", "_", $no);
    
        $attachPath = '/files/applicants/check_files/' . $no . '/';
        $fullFileName = uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
    
        // สร้างไฟล์ชั่วคราว
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
    
        // บันทึก PDF ไปยังไฟล์ชั่วคราว
        $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);
    
        // ใช้ Storage::putFileAs เพื่อย้ายไฟล์
        Storage::putFileAs($attachPath, new \Illuminate\Http\File($tempFilePath), $fullFileName);
   
        $filePath = $attachPath .'/'. $fullFileName;
        if (Storage::disk('ftp')->exists($filePath)) {
            $storePath = $no  . '/' . $fullFileName;
            $labReportInfo = LabReportInfo::find($labReportInfo->id)->update([
                'file' => $storePath,
                'file_client_name' => 'report' . '_' . $no . '.pdf'
            ]);
            $notice = Notice::find($notice->id)->update([
                'file' => $storePath,
                'file_client_name' => 'report' . '_' . $no . '.pdf'
            ]);
            // dd('File Path on Server: ' . $filePath);
        } else {
            // dd('File not found on server!');
        }
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
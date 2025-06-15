<?php

namespace App\Services;
use HP;
use stdClass;
use Mpdf\Mpdf;
use Smalot\PdfParser\Parser;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Certify\BoardAuditorDate;
use App\Models\Bcertify\BoardAuditoExpert;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Bcertify\HtmlLabMemorandumPdfRequest;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class CreateLabMessageRecordPdf
{
    protected $board_auditor_id;
    protected $type;

    public function __construct($board_auditor,$type)
    {
        $this->board_auditor_id = $board_auditor->id;
        $this->type = $type;
    }



    public function generateBoardAuditorMessageRecordPdf()
    {
        
        $fontDirs = [public_path('pdf_fonts/')];; // เพิ่มไดเรกทอรีฟอนต์ที่คุณต้องการ
        $fontData = [
            'thsarabunnew' => [
                'R' => "THSarabunNew.ttf",
                'B' => "THSarabunNew-Bold.ttf",
                'I' => "THSarabunNew-Italic.ttf",
                'BI' => "THSarabunNew-BoldItalic.ttf",
            ],
        ];

        $mpdf = new Mpdf([
            'PDFA'     => 'F',
            'PDFAauto'     =>  'F',
            'format'            => 'A4',
            'mode'              => 'utf-8',
            'default_font_size' => '16',
            'fontDir'          => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], $fontDirs),
            'fontdata'         => array_merge((new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'], $fontData),
            'default_font'     => 'thsarabunnew', // ใช้ฟอนต์ที่กำหนดเป็นค่าเริ่มต้น
            'margin_left'      => 25, // ระบุขอบด้านซ้าย
            'margin_right'     => 23, // ระบุขอบด้านขวา
            'margin_top'       => 12, // ระบุขอบด้านบน
            'margin_bottom'    => 20, // ระบุขอบด้านล่าง
            'shrink_tables_to_fit'    => 0, // ระบุขอบด้านล่าง
        ]);

        $mpdf->useDictionaryLBR = false;
        $mpdf->SetDefaultBodyCSS('KeepTableProportions', 1);

        if ($this->type == "ia"){
            $this->ia($mpdf);
        }

    
    }

    // public function ia($mpdf)
    // {
    //     $boardAuditor = BoardAuditor::find($this->board_auditor_id);
    //     $boardAuditorMsRecordInfo = $boardAuditor->boardAuditorMsRecordInfos->first();

    //     $groups = $boardAuditor->groups;

    //     $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id

    //     $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล

    //     foreach ($groups as $group) {
    //         $statusAuditorId = $group->status_auditor_id; // ดึง status_auditor_id มาเก็บในตัวแปร
    //         $auditors = $group->auditors; // $auditors เป็น Collection

    //         // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
    //         if (!isset($statusAuditorMap[$statusAuditorId])) {
    //             $statusAuditorMap[$statusAuditorId] = [];
    //         }

    //         // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
    //         foreach ($auditors as $auditor) {
    //             $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
    //         }
    //     }

    //     $uniqueAuditorIds = array_unique($auditorIds);

    //     $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();

    //     $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);

    //     $boardAuditorDate = BoardAuditorDate::where('board_auditors_id',$this->board_auditor_id)->first();
    //     $dateRange = "";

    //     if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
    //         if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
    //             // ถ้าเป็นวันเดียวกัน
    //             $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
    //         } else {
    //             // ถ้าเป็นคนละวัน
    //             $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
    //                         " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
    //         }
    //     }

    //     $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id',$this->board_auditor_id)->first();
    //     $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";

    //     if ($boardAuditorExpert && $boardAuditorExpert->expert) {
    //         // แปลงข้อมูล JSON ใน expert กลับเป็น array
    //         $categories = json_decode($boardAuditorExpert->expert, true);
        
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

    //     $scope_branch = "";
    //     if ($certi_lab->lab_type == 3){
    //         $scope_branch = $certi_lab->BranchTitle;
    //     }else if($certi_lab->lab_type == 4)
    //     {
    //         $scope_branch = $certi_lab->ClibrateBranchTitle;
    //     }

    //     $data = new stdClass();

    //     $data->header_text1 = '';
    //     $data->header_text2 = '';
    //     $data->header_text3 = '';
    //     $data->header_text4 = '';
    //     $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
    //     $data->lab_name = $certi_lab->lab_name;
    //     $data->app_no = $certi_lab->app_no;
    //     $data->scope_branch = $scope_branch;
    //     $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
    //     $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
    //     $data->experts = $experts;
    //     $data->date_range = $dateRange;
    //     $data->statusAuditorMap = $statusAuditorMap;


        // $htmlLabMemorandumRequest = HtmlLabMemorandumPdfRequest::where('type',"ia")->first();

        // $data->fix_text1 = <<<HTML
        //        $htmlLabMemorandumRequest->text1
        //     HTML;

        // $data->fix_text2 = <<<HTML
        //        $htmlLabMemorandumRequest->text2
        //     HTML;


    //     $signer = new stdClass();


       
    //     $signer->signer_1 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id','Signature1')
    //     ->where('certificate_type',2)
    //     ->first();
    //     $signer->signer_2 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id','Signature2')
    //     ->where('certificate_type',2)
    //     ->first();
    //     $signer->signer_3 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id','Signature3')
    //     ->where('certificate_type',2)
    //     ->first();
    //     $signer->signer_4 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id','Signature4')
    //     ->where('certificate_type',2)
    //     ->first();


    //     $attach1 = !empty($signer->signer_1->signer->AttachFileAttachTo) ? $signer->signer_1->signer->AttachFileAttachTo : null;
    //     $attach2 = !empty($signer->signer_2->signer->AttachFileAttachTo) ? $signer->signer_2->signer->AttachFileAttachTo : null;
    //     $attach3 = !empty($signer->signer_3->signer->AttachFileAttachTo) ? $signer->signer_3->signer->AttachFileAttachTo : null;
    //     $attach4 = !empty($signer->signer_4->signer->AttachFileAttachTo) ? $signer->signer_4->signer->AttachFileAttachTo : null;

    //     $sign_url1 = $this->getSignature($attach1);
    //     $sign_url2 = $this->getSignature($attach2);
    //     $sign_url3 = $this->getSignature($attach3);
    //     $sign_url4 = $this->getSignature($attach4);


    //     $signer->signer_url1 = $sign_url1;
    //     $signer->signer_url2 = $sign_url2;
    //     $signer->signer_url3 = $sign_url3;
    //     $signer->signer_url4 = $sign_url4;


    //     $body = view('certify.auditor.ia_lab_message_record_pdf.body', [
    //         'data' => $data,
    //         'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo,
    //         'signer' => $signer
    //     ]);
    //     $footer = view('certify.auditor.ia_lab_message_record_pdf.footer', []);

    //     $mpdf->WriteHTML($body, 2);

        
    //     // return;
    //     $no = str_replace("RQ-", "", $certi_lab->app_no);
    //     $no = str_replace("-", "_", $no);
    
    //     $attachPath = '/files/applicants/check_files/' . $no . '/';
    //     $fullFileName = uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
    
    //     // สร้างไฟล์ชั่วคราว
    //     $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
    
    //     // บันทึก PDF ไปยังไฟล์ชั่วคราว
    //     $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);
    
    //     // ใช้ Storage::putFileAs เพื่อย้ายไฟล์
    //     Storage::putFileAs($attachPath, new \Illuminate\Http\File($tempFilePath), $fullFileName);
   
    //     $filePath = $attachPath .'/'. $fullFileName;
    //     if (Storage::disk('ftp')->exists($filePath)) {
    //         $storePath = $no  . '/' . $fullFileName;
    //         $boardAuditor = BoardAuditor::find($this->board_auditor_id)->update([
    //             'file' => $storePath,
    //             'file_client_name' => 'memorandum' . '_' . $no . '.pdf'
    //         ]);
    //     } 
    // }

public function ia($mpdf)
{
    $boardAuditor = BoardAuditor::find($this->board_auditor_id);
    $boardAuditorMsRecordInfo = $boardAuditor->boardAuditorMsRecordInfos->first() ?? null; // แก้ไข: กัน first() คืนค่า null แล้วเกิด error

    $groups = $boardAuditor->groups;

    $auditorIds = []; // แก้ไข: ใช้จริงแล้วใน array_unique ด้านล่าง
    $statusAuditorMap = [];

    foreach ($groups as $group) {
        $statusAuditorId = $group->status_auditor_id;
        $auditors = $group->auditors;

        if (!isset($statusAuditorMap[$statusAuditorId])) {
            $statusAuditorMap[$statusAuditorId] = [];
        }

        foreach ($auditors as $auditor) {
            $statusAuditorMap[$statusAuditorId][] = $auditor->auditor_id;
            $auditorIds[] = $auditor->auditor_id; // แก้ไข: เพิ่มเพื่อให้ $auditorIds มีข้อมูลจริง
        }
    }

    $uniqueAuditorIds = array_unique($auditorIds);
    $auditorInformations = AuditorInformation::whereIn('id', $uniqueAuditorIds)->get();
    $certi_lab = CertiLab::find($boardAuditor->app_certi_lab_id);

    $boardAuditorDate = BoardAuditorDate::where('board_auditors_id', $this->board_auditor_id)->first();
    $dateRange = "";

    if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
        if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
            $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
        } else {
            $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) .
                        " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
        }
    }

    $boardAuditorExpert = BoardAuditoExpert::where('board_auditor_id', $this->board_auditor_id)->first();
    $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";

    if ($boardAuditorExpert && $boardAuditorExpert->expert) {
        $categories = json_decode($boardAuditorExpert->expert, true);

        if (count($categories) > 1) {
            $lastItem = array_pop($categories);
            $experts = implode(' ', $categories) . ' และ' . $lastItem;
        } elseif (count($categories) == 1) {
            $experts = $categories[0];
        } else {
            $experts = '';
        }
    }

    $scope_branch = '';
    if ($certi_lab->lab_type == 3) {
        $scope_branch = $certi_lab->BranchTitle;
    } elseif ($certi_lab->lab_type == 4) {
        $scope_branch = $certi_lab->ClibrateBranchTitle;
    }

    $data = new stdClass();
    $data->header_text1 = '';
    $data->header_text2 = '';
    $data->header_text3 = '';
    $data->header_text4 = '';
    $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
    $data->lab_name = $certi_lab->lab_name;
    $data->app_no = $certi_lab->app_no;
    $data->scope_branch = $scope_branch;

    $data->register_date = $certi_lab->created_at ? HP::formatDateThaiFullNumThai($certi_lab->created_at) : ''; // แก้ไข: กัน null
    $data->get_date = $certi_lab->get_date ? HP::formatDateThaiFullNumThai($certi_lab->get_date) : ''; // แก้ไข: กัน null
    $data->experts = $experts;
    $data->date_range = $dateRange;
    $data->statusAuditorMap = $statusAuditorMap;

    // $htmlLabMemorandumRequest = HtmlLabMemorandumPdfRequest::where('type', "ia")->first();

    $htmlLabMemorandumRequest = HtmlLabMemorandumPdfRequest::where('type',"ia")->first();

    // $data->fix_text1 = <<<HTML
    //         $htmlLabMemorandumRequest->text1
    //     HTML;

    // $data->fix_text2 = <<<HTML
    //         $htmlLabMemorandumRequest->text2
    //     HTML;

// $data->fix_text1 = <<<HTML
// $htmlLabMemorandumRequest->text1
// HTML;


$data->fix_text1 = <<<HTML
<div class="section-title">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div><div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div><div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองหน่วยตรวจ พ.ศ. ๒๕๖๔ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)"</div><div style="text-indent:150px">ข้อ ๖.๑.๒.๑ (๑) ระบุว่า "แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าผู้ตรวจประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม"</div><div style="text-indent:150px">ข้อ ๖.๑.๒.๑ (๒) ระบุว่า "คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมินเอกสารต่าง ๆ ของหน่วยตรวจ ตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานของหน่วยตรวจโดยพิจารณาหลักฐานและเอกสารที่เกี่ยวข้อง การสัมภาษณ์ รวมทั้งสังเกตการปฏิบัติงาน ตามมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง</div><div style="text-indent:125px">๒.๓ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สั่ง ณ วันที่ ๑๓ พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุว่า "ให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑"</div>
HTML;

$data->fix_text2 = <<<HTML
$htmlLabMemorandumRequest->text2
HTML;


    // $data->fix_text1 = $htmlLabMemorandumRequest ? $htmlLabMemorandumRequest->text1 : ''; // แก้ไข: กัน null
    // $data->fix_text2 = $htmlLabMemorandumRequest ? $htmlLabMemorandumRequest->text2 : ''; // แก้ไข: กัน null

    $signer = new stdClass();
    $signer->signer_1 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id', 'Signature1')->where('certificate_type', 2)->first();
    $signer->signer_2 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id', 'Signature2')->where('certificate_type', 2)->first();
    $signer->signer_3 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id', 'Signature3')->where('certificate_type', 2)->first();
    $signer->signer_4 = MessageRecordTransaction::where('board_auditor_id', $this->board_auditor_id)->where('signature_id', 'Signature4')->where('certificate_type', 2)->first();

    $attach1 = isset($signer->signer_1->signer->AttachFileAttachTo) ? $signer->signer_1->signer->AttachFileAttachTo : null; // แก้ไข: ป้องกัน error
    $attach2 = isset($signer->signer_2->signer->AttachFileAttachTo) ? $signer->signer_2->signer->AttachFileAttachTo : null;
    $attach3 = isset($signer->signer_3->signer->AttachFileAttachTo) ? $signer->signer_3->signer->AttachFileAttachTo : null;
    $attach4 = isset($signer->signer_4->signer->AttachFileAttachTo) ? $signer->signer_4->signer->AttachFileAttachTo : null;

    $signer->signer_url1 = $this->getSignature($attach1);
    $signer->signer_url2 = $this->getSignature($attach2);
    $signer->signer_url3 = $this->getSignature($attach3);
    $signer->signer_url4 = $this->getSignature($attach4);

    $body = view('certify.auditor.ia_lab_message_record_pdf.body', [
        'data' => $data,
        'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo,
        'signer' => $signer
    ]);
    $footer = view('certify.auditor.ia_lab_message_record_pdf.footer', []);

    $mpdf->WriteHTML($body, 2);

    $no = str_replace("RQ-", "", $certi_lab->app_no);
    $no = str_replace("-", "_", $no);

    $attachPath = '/files/applicants/check_files/' . $no . '/';
    $fullFileName = uniqid() . '_' . now()->format('Ymd_His') . '.pdf';

    $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
    $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);
    Storage::putFileAs($attachPath, new \Illuminate\Http\File($tempFilePath), $fullFileName);

    $filePath = $attachPath . $fullFileName; // แก้ไข: ลบ '/' เกินออก

    if (Storage::disk('ftp')->exists($filePath)) {
        $storePath = $no . '/' . $fullFileName;
        BoardAuditor::find($this->board_auditor_id)->update([
            'file' => $storePath,
            'file_client_name' => 'memorandum_' . $no . '.pdf'
        ]);
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
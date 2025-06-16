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
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Bcertify\HtmlLabMemorandumPdfRequest;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsDate;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class CreateCbMessageRecordPdf
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

    public function ia($mpdf)
    {
        $boardAuditor = CertiCBAuditors::find($this->board_auditor_id);
        $boardAuditorMsRecordInfo = $boardAuditor->cbBoardAuditorMsRecordInfos->first();
        // dd($this->board_auditor_id);
        $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id

        $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล
  
  
        $uniqueAuditorIds = array_unique($auditorIds);
  
        $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();
  
        $certi_cb = CertiCb::find($boardAuditor->app_certi_cb_id);
  

        
        $boardAuditorDate = CertiCBAuditorsDate::where('auditors_id',$this->board_auditor_id)->first();
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
        


      $data = new stdClass();

      $data->header_text1 = '';
      $data->header_text2 = '';
      $data->header_text3 = '';
      $data->header_text4 = $certi_cb->app_no;
      $data->lab_type = $certi_cb->lab_type == 3 ? 'ทดสอบ' : ($certi_cb->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
      $data->name_standard = $certi_cb->name_standard;
      $data->app_no = $certi_cb->app_no;
      $data->certificate_no = '13-LB0037';
      $data->register_date = HP::formatDateThaiFullNumThai($certi_cb->created_at);
      $data->get_date = HP::formatDateThaiFullNumThai($certi_cb->get_date);

      $data->date_range = $dateRange;
      $data->statusAuditorMap = $statusAuditorMap;


      $signAssessmentReportTransactions = MessageRecordTransaction::where('board_auditor_id',$this->board_auditor_id)
      ->where('certificate_type',0)
      ->get();


        // dd($signAssessmentReportTransactions);
        $signer = new stdClass();

        $signer->signer_1 = MessageRecordTransaction::where('board_auditor_id',$this->board_auditor_id)->where('signer_order','0')
                        ->where('certificate_type',0)
                        ->first();


        $signer->signer_2 = MessageRecordTransaction::where('board_auditor_id',$this->board_auditor_id)->where('signer_order','1')
                        ->where('certificate_type',0)
                        ->first();
        $signer->signer_3 = MessageRecordTransaction::where('board_auditor_id',$this->board_auditor_id)->where('signer_order','2')
                        ->where('certificate_type',0)
                        ->first();
        $signer->signer_4 = MessageRecordTransaction::where('board_auditor_id',$this->board_auditor_id)->where('signer_order','3')
                        ->where('certificate_type',0)
                        ->first();



        $attach1 = !empty($signer->signer_1->signer->AttachFileAttachTo) ? $signer->signer_1->signer->AttachFileAttachTo : null;
        $attach2 = !empty($signer->signer_2->signer->AttachFileAttachTo) ? $signer->signer_2->signer->AttachFileAttachTo : null;
        $attach3 = !empty($signer->signer_3->signer->AttachFileAttachTo) ? $signer->signer_3->signer->AttachFileAttachTo : null;
        $attach4 = !empty($signer->signer_4->signer->AttachFileAttachTo) ? $signer->signer_4->signer->AttachFileAttachTo : null;

        // dd($attach3);

        $sign_url1 = $this->getSignature($attach1);
        $sign_url2 = $this->getSignature($attach2);
        $sign_url3 = $this->getSignature($attach3);
        $sign_url4 = $this->getSignature($attach4);


        $signer->signer_url1 = $sign_url1;
        $signer->signer_url2 = $sign_url2;
        $signer->signer_url3 = $sign_url3;
        $signer->signer_url4 = $sign_url4;

$data->fix_text1 = <<<HTML
<div class="section-title" style="text-indent:90px;font-weight:bold">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
<div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรองให้ เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
<div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข การรับรองหน่วยตรวจ พ.ศ. ๒๕๖๔ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)" ข้อ ๖.๑.๒.๑ (๑) ระบุว่า "แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าผู้ตรวจประเมิน ผู้ตรวจประเมินด้านวิชาการ และ ผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม" ข้อ ๖.๑.๒.๑ (๒) ระบุว่า "คณะผู้ตรวจ ประเมินจะทบทวนและประเมินและประเมินเอกสารต่าง ๆ ของหน่วยตรวจ ตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานของหน่วยตรวจโดยพิจารณาหลักฐานและเอกสารที่เกี่ยวข้อง การสัมภาษณ์รวม ทั้ง สังเกตการปฏิบัติงาน ตามมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำ ขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง</div>
<div style="text-indent:125px">๒.๓ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจ ให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สั่ง ณ วันที่ ๑๓ พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุว่า "ให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มี อำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑"</div>
HTML;

$data->fix_text2 = <<<HTML
<div class="section-title" style="text-indent:90px;font-weight:bold">๓. สาระสำคัญและข้อเท็จจริง</div>
<div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข การรับรองหน่วยตรวจ พ.ศ. ๒๕๖๔ สำนักงานจะตรวจติดตามผลการรับรองหน่วยตรวจอย่างน้อย ๑ ครั้ง ภายใน ๒ ปี โดยแต่ละครั้งอาจตรวจประเมินเพียงบางส่วนหรือทุกข้อกำหนดก็ได้ตามความเหมาะสมและก่อน ครบรอบการรับรอง ๕ ปี ต้องตรวจประเมินให้ครบทุกข้อกำหนด</div>
HTML;



        // dd($data);

        // dd($boardAuditorMsRecordInfo);
        $body = view('certify.cb.auditor_cb.ia_cb_message_record_pdf.body', [
            'data' => $data,
            'id' => $this->board_auditor_id,
            'signer' => $signer,
            'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo,
            'boardAuditor' => $boardAuditor
        ]);
        $footer = view('certify.cb.auditor_cb.ia_cb_message_record_pdf.footer', []);

        // $mpdf->WriteHTML($header,2);
        // $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($body, 2);

        // $title = "message_record.pdf";

        // $mpdf->Output($title, 'I');
        
        // return;
        $no = str_replace("RQ-", "", $certi_cb->app_no);
        $no = str_replace("-", "_", $no);
    
        $attachPath = '/files/applicants/check_files_cb/' . $no . '/';
        $fullFileName = uniqid() . '_' . now()->format('Ymd_His') . '.pdf';
    
        // สร้างไฟล์ชั่วคราว
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
    
        // บันทึก PDF ไปยังไฟล์ชั่วคราว
        $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);
    
        // ใช้ Storage::putFileAs เพื่อย้ายไฟล์
        Storage::putFileAs($attachPath, new \Illuminate\Http\File($tempFilePath), $fullFileName);
   
        $filePath = $attachPath .'/'. $fullFileName;
        if (Storage::disk('ftp')->exists($filePath)) 
        {
            $storePath = $no  . '/' . $fullFileName;      
            $tb = new CertiCBAuditors;
            $certi_cb_attach_more = new CertiCBAttachAll();
            $certi_cb_attach_more->app_certi_cb_id = $boardAuditor->CertiCbCostTo->id ?? null;
            $certi_cb_attach_more->ref_id = $boardAuditor->id;
            $certi_cb_attach_more->table_name = $tb->getTable();
            $certi_cb_attach_more->file_section =  1;
            $certi_cb_attach_more->file = $storePath;
            $certi_cb_attach_more->file_client_name = 'memorandum' . '_' . $no . '.pdf';
            $certi_cb_attach_more->token = str_random(16);
            $certi_cb_attach_more->save(); 
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
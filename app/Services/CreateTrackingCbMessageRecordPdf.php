<?php

namespace App\Services;
use HP;
use stdClass;
use Mpdf\Mpdf;
use Smalot\PdfParser\Parser;
use App\Models\Certificate\Tracking;
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
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Bcertify\BoardAuditoExpertTracking;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Bcertify\HtmlLabMemorandumPdfRequest;
use App\Models\Certify\MessageRecordTrackingTransaction;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class CreateTrackingCbMessageRecordPdf
{
    protected $board_tracking_auditor_id;
    protected $type;

    public function __construct($board_tracking_auditor,$type)
    {
        $this->board_tracking_auditor_id = $board_tracking_auditor->id;
        $this->type = $type;
    }

    public function generateBoardTrackingAuditorMessageRecordPdf()
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
    
      
         $trackingAuditor = TrackingAuditors::find($this->board_tracking_auditor_id);
          $boardAuditorMsRecordInfo = $trackingAuditor->boardAuditorTrackingMsRecordInfos->first();
          $auditors_statuses= $trackingAuditor->auditors_status_many;
          $statusAuditorMap = [];
          foreach ($auditors_statuses as $auditors_status)
          {
              // dd($auditors_status->auditors_list_many);
              $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
              $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection
  
              // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
              if (!isset($statusAuditorMap[$statusAuditorId])) {
                  $statusAuditorMap[$statusAuditorId] = [];
              }
              foreach ($auditors as $auditor) {
                  
                  $statusAuditorMap[$statusAuditorId][] = $auditor->id;
              }
          }

          
          $tracking = Tracking::find($trackingAuditor->tracking_id);
  
          $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$this->board_tracking_auditor_id)->first();
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
  

          
          $certi_cb = $tracking->certificate_export_to->applications;
        
        $data = new stdClass();
    
      
          $data->header_text1 = '';
          $data->header_text2 = '';
          $data->header_text3 = '';
          $data->header_text4 = $certi_cb->app_no;
          $data->lab_type = $certi_cb->lab_type == 3 ? 'ทดสอบ' : ($certi_cb->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
          $data->name_unit = $certi_cb->name_unit;
          $data->app_no =  $certi_cb->app_no;
          $data->certificate_no = '13-LB0037';
          $data->register_date = HP::formatDateThaiFullNumThai($certi_cb->created_at);
          $data->get_date = HP::formatDateThaiFullNumThai($certi_cb->get_date);

          $data->date_range = $dateRange;
        //   $data->statusAuditorMap = $statusAuditorMap;
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

  
     
        $signer = new stdClass();
       
        $signer->signer_1 = MessageRecordTrackingTransaction::where('ba_tracking_id', $this->board_tracking_auditor_id)
                            ->where('certificate_type',1)
                            ->where('signature_id','Signature1')
                            ->first();
        $signer->signer_2 = MessageRecordTrackingTransaction::where('ba_tracking_id', $this->board_tracking_auditor_id)
                            ->where('certificate_type',1)
                            ->where('signature_id','Signature2')
                            ->first();
        $signer->signer_3 = MessageRecordTrackingTransaction::where('ba_tracking_id', $this->board_tracking_auditor_id)
                            ->where('certificate_type',1)
                            ->where('signature_id','Signature3')
                            ->first();
        $signer->signer_4 = MessageRecordTrackingTransaction::where('ba_tracking_id', $this->board_tracking_auditor_id)
                            ->where('certificate_type',1)
                            ->where('signature_id','Signature4')
                            ->first();


        $attach1 = !empty($signer->signer_1->signer->AttachFileAttachTo) ? $signer->signer_1->signer->AttachFileAttachTo : null;
        $attach2 = !empty($signer->signer_2->signer->AttachFileAttachTo) ? $signer->signer_2->signer->AttachFileAttachTo : null;
        $attach3 = !empty($signer->signer_3->signer->AttachFileAttachTo) ? $signer->signer_3->signer->AttachFileAttachTo : null;
        $attach4 = !empty($signer->signer_4->signer->AttachFileAttachTo) ? $signer->signer_4->signer->AttachFileAttachTo : null;
        // dd($attach1->url);

        $sign_url1 = $this->getSignature($attach1);
        $sign_url2 = $this->getSignature($attach2);
        $sign_url3 = $this->getSignature($attach3);
        $sign_url4 = $this->getSignature($attach4);


        $signer->signer_url1 = $sign_url1;
        $signer->signer_url2 = $sign_url2;
        $signer->signer_url3 = $sign_url3;
        $signer->signer_url4 = $sign_url4;

        // dd($data,$boardAuditorMsRecordInfo,$signer);

        // dd($auditors);
        $auditors =        $tracking->AuditorsManyBy;
        // dd($auditors);
        $body = view('certificate.cb.auditor-cb.pdf.body', [
             'data' => $data,
              'certi_cb' => $certi_cb,
              'id' => $this->board_tracking_auditor_id,
              'auditors' => $auditors,
              'trackingAuditor' => $trackingAuditor,
              'tracking' => $tracking,
              'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo,
            'signer' => $signer
        ]);
        // $footer = view('certificate.labs.auditor-labs.pdf.footer', []);

        // $mpdf->WriteHTML($header,2);
        // $mpdf->SetHTMLFooter($footer);
        $mpdf->WriteHTML($body, 2);

        $title = "message_record.pdf";

        //   $mpdf->Output($title, "I");  

        // สร้างไฟล์ PDF ชั่วคราว
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
        $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);

        // อ่านไฟล์ชั่วคราวและแปลงเป็น UploadedFile (จำลองอัปโหลดไฟล์)
        $file = new \Illuminate\Http\UploadedFile(
            $tempFilePath,
            'document.pdf',
            'application/pdf',
            null,
            true // กำหนดให้เป็นไฟล์ที่ผ่านการอัปโหลดจริง
        );

        // กำหนดค่าต่าง ๆ สำหรับอัปโหลด
        $attach_path = 'files/trackingcb';
        $tax_number = (!empty(auth()->user()->reg_13ID) ? str_replace("-", "", auth()->user()->reg_13ID) : '0000000000000');

        HP::singleFileUploadRefno(
            $file, // ใช้ไฟล์ PDF ที่จำลองการอัปโหลด
            $attach_path . '/' . $trackingAuditor->reference_refno,
            $tax_number,
            auth()->user()->FullName ?? null,
            'Center',
            (new TrackingAuditors)->getTable(),
            $trackingAuditor->id,
            'other_attach',
            null
        );

        // ลบไฟล์ชั่วคราวเมื่อไม่ใช้งานแล้ว
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
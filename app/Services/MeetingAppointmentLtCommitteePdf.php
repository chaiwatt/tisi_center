<?php

namespace App\Services;
use HP;
use stdClass;
use Mpdf\Mpdf;
use App\AttachFile;
use Smalot\PdfParser\Parser;
use App\Models\Certificate\Tracking;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Certificate\MeetingInvitation;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use Illuminate\Support\Facades\Storage;
use App\Certificate\LtMeetingInvitation;
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

class MeetingAppointmentLtCommitteePdf
{
    protected $meetingInvitationId;


    public function __construct($id)
    {
        $this->meetingInvitationId = $id;
    }

    public function generateMeetingAppointmentLtCommitteePdf()
    {
        $meetingInvitation = LtMeetingInvitation::find($this->meetingInvitationId);
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

        // $localFile = $this->getSignature($meetingInvitation->qr_file_path);
        // // dd($localFile );

        //   // ตรวจสอบไฟล์ใน disk uploads ก่อน
        // if (Storage::disk('uploads')->exists("files/meetingqr/1/ZHXieVnWYj-date_time20250618_020630.png")) {
        //     // หากพบไฟล์ใน disk
        //     $storagePath = Storage::disk('uploads')->path("files/meetingqr/1/ZHXieVnWYj-date_time20250618_020630.png");
        //     // $filePath = 'uploads/'.$attachPath .'/'.$fileName;
        //     // dd('File already exists in uploads',  $filePath);
        //     // return $filePath;
        //     dd($storagePath);
        // }else{
        //     dd("no file");
        // } 

        $signer = $meetingInvitation->signer;

       

        $attach = !empty($signer->AttachFileAttachTo) ? $signer->AttachFileAttachTo : null;


        // $filePath = 'files/meetingqr/1/ZHXieVnWYj-date_time20250618_020630.png';



        $signature = $this->getSignature($attach->url);

        //  dd($signature);

        $mpdf->useDictionaryLBR = false;
        $mpdf->SetDefaultBodyCSS('KeepTableProportions', 1);

        $body = view('certify.estandard.committee.body', [
             'meetingInvitation' => $meetingInvitation,
             'signature' => $signature,
        ]);

        $docQr = AttachFile::where('ref_table', (new LtMeetingInvitation)->getTable())
        ->where('ref_id', $meetingInvitation->id)
        ->where('section', 5678)
        ->first();

      

        
                    
        $docQrLocalFilePath = HP::getFileStoragePath($docQr->url);

        // dd($docQrLocalFilePath);

        $publicPath = public_path();                       

        $docQrRelativePath = str_replace($publicPath, '', $docQrLocalFilePath);

        $docQrRelativePath = str_replace('\\', '/', $docQrRelativePath);
        $docQrRelativePath = ltrim($docQrRelativePath, '/');   




        $ggFormQr = AttachFile::where('ref_table', (new LtMeetingInvitation)->getTable())
        ->where('ref_id', $meetingInvitation->id)
        ->where('section', 135)
        ->first();

        //   dd( $ggFormQr);
                    
        $ggFormQrLocalFilePath = HP::getFileStoragePath($ggFormQr->url);

        $publicPath = public_path();                       

        $ggFormQrRelativePath = str_replace($publicPath, '', $ggFormQrLocalFilePath);

        $ggFormQrRelativePath = str_replace('\\', '/', $ggFormQrRelativePath);
        $ggFormQrRelativePath = ltrim($ggFormQrRelativePath, '/');   

        // dd($docQrRelativePath,$ggFormQrRelativePath );


        $footer = view('certify.estandard.committee.footer', [
             'qrCodePath' => $docQrRelativePath,
             'ggFormQrCodePath' => $ggFormQrRelativePath
        ]);
        $mpdf->WriteHTML($body, 2);
        $mpdf->SetHTMLFooter($footer);

        // $title = "message_record.pdf";

        // $mpdf->Output($title, "I");  
     
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
        $mpdf->Output($tempFilePath, \Mpdf\Output\Destination::FILE);

        $file = new \Illuminate\Http\UploadedFile(
            $tempFilePath,
            'document.pdf',
            'application/pdf',
            null,
            true 
        );

        $attach_path = 'files/meeting_invitation';
        $tax_number = (!empty(auth()->user()->reg_13ID) ? str_replace("-", "", auth()->user()->reg_13ID) : '0000000000000');
       
        HP::singleFileUploadRefno(
            $file, 
            $attach_path . '/' . $meetingInvitation->id,
            $tax_number,
            auth()->user()->FullName ?? null,
            'Center',
            (new LtMeetingInvitation)->getTable(),
            $meetingInvitation->id,
            'order_book',
            null
        );

        // dd("oooo");

    }

    public function getSignature($existingFilePath)
    {
        
        // $existingFilePath = $attach->url;//  'files/signers/3210100336046/tvE4QPMaEC-date_time20241211_011258.png'  ;

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
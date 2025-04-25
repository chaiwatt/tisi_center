<?php

namespace App\Services;
use HP;

use TCPDF;
use Storage;

use App\User;

use stdClass;
use Mpdf\Mpdf;
use Carbon\Carbon;

use Mpdf\Merger\PdfMerger;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;


use App\Helpers\EpaymentDemo;
use App\Models\Besurv\Signer;
use App\Certify\IbReportTwoInfo;
use Illuminate\Support\Facades\Log;
use App\Models\Certify\BoardAuditor;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Http;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\BoardAuditorDate;
use App\Models\Certify\TransactionPayIn;
use App\Models\Bcertify\BoardAuditoExpert;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\CertiSettingPayment;
use App\Services\CreateLabMessageRecordPdf;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\MessageRecordTransaction;
use App\Http\Controllers\API\Checkbill2Controller;

use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Bcertify\HtmlLabMemorandumPdfRequest;
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;


class CreateIbAssessmentReportTwoPdf
{
    protected $ibReportInfoId;
    protected $type;

    public function __construct($ibReportInfoId,$type)
    {
        $this->ibReportInfoId = $ibReportInfoId;
        $this->type = $type;
    }

    public function generateIbAssessmentReportTwoPdf()
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
            'margin_left'      => 15, // ระบุขอบด้านซ้าย
            'margin_right'     => 15, // ระบุขอบด้านขวา
            'margin_top'       => 20, // ระบุขอบด้านบน
            'margin_bottom'    => 20, // ระบุขอบด้านล่าง
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
        $ibReportInfo = IbReportTwoInfo::find($this->ibReportInfoId);
        
        $assessment = $ibReportInfo->CertiIBSaveAssessment;
        $certi_ib = $assessment->CertiIBCostTo;
      
        // $referenceDocuments = CertiIBAttachAll::where('app_certi_ib_id',$assessment->app_certi_ib_id)
        //         ->where('ref_id',$assessment->id)
        //         ->where('file_section','123')
        //         ->get();
                
        // $ibReportInfoSigners = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)
        //                         ->where('certificate_type',1)
        //                         ->where('report_type',2)
        //                         ->get();

            
        $data = new stdClass();

        $data->header_text1 = '';
        $data->header_text2 = '';
        $data->header_text3 = '';
        $data->header_text4 = $certi_ib->app_no;
        $data->lab_type = $certi_ib->lab_type == 3 ? 'ทดสอบ' : ($certi_ib->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
        $data->lab_name = $certi_ib->lab_name;

        $data->register_date = HP::formatDateThaiFullNumThai($certi_ib->created_at);
        $data->get_date = HP::formatDateThaiFullNumThai($certi_ib->get_date);

      
        
        
        $signAssessmentReportTransactions = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)
                                            ->where('certificate_type',1)
                                            ->where('report_type',2)
                                            ->get();


        // dd($signAssessmentReportTransactions);
        $signer = new stdClass();

        $signer->signer_1 = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)->where('signer_order','1')
                                                        ->where('certificate_type',1)
                                                        ->where('report_type',2)
                                                        ->first();

        
        $signer->signer_2 = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)->where('signer_order','2')
                                                        ->where('certificate_type',1)
                                                        ->where('report_type',2)
                                                        ->first();
        $signer->signer_3 = SignAssessmentReportTransaction::where('report_info_id',$ibReportInfo->id)->where('signer_order','3')
                                                        ->where('certificate_type',1)
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

        $referenceDocuments = CertiIBAttachAll::where('app_certi_ib_id',$assessment->app_certi_ib_id)
                    ->where('ref_id',$assessment->id)
                    ->where('file_section','123')
                    ->get();
      
        // dd($signer)     ;       
        $body = view('certify.ib.save_assessment_ib.report-two-pdf.ia.body', [
            'ibReportInfo' => $ibReportInfo,
            'data' => $data,
            'assessment' => $assessment,
            'certi_ib' => $certi_ib,
            'signAssessmentReportTransactions' => $signAssessmentReportTransactions,
            'signer' => $signer,
            'referenceDocuments' => $referenceDocuments
        ]);

        $footer = view('certify.ib.save_assessment_ib.report-two-pdf.ia.footer', []);
        $header = view('certify.ib.save_assessment_ib.report-two-pdf.ia.header', [
            'certi_ib' => $certi_ib
        ]);

        $stylesheet = file_get_contents(public_path('css/report/ib-report.css'));
        $mpdf->WriteHTML($stylesheet, 1);
       
        $mpdf->SetHTMLHeader($header,2);
        $mpdf->WriteHTML($body,2);

        // $mpdf->SetHTMLFooter($footer,2);


        // $title = "report.pdf";

        // $mpdf->Output($title, 'I');

        // return;


        $no = str_replace("RQ-", "", $certi_ib->app_no);
        $no = str_replace("-", "_", $no);
    
        $attachPath = '/files/applicants/check_files_ib/' . $no . '/';

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

            $tb = new CertiIBSaveAssessment;
            $certi_ib_attach_more                       = new CertiIBAttachAll();
            $certi_ib_attach_more->app_certi_ib_id      = $assessment->app_certi_ib_id ?? null;
            $certi_ib_attach_more->ref_id               = $assessment->id;
            $certi_ib_attach_more->table_name           = $tb->getTable();
            $certi_ib_attach_more->file_section         = '3';
            $certi_ib_attach_more->file                 = $storePath;
            $certi_ib_attach_more->file_client_name     = 'report' . '_' . $no . '.pdf';
            $certi_ib_attach_more->token                = str_random(16);
            $certi_ib_attach_more->save();

            $certi_ib_attach_more                       = new CertiIBAttachAll();
            $certi_ib_attach_more->app_certi_ib_id      = $assessment->app_certi_ib_id ?? null;
            $certi_ib_attach_more->ref_id               = $assessment->id;
            $certi_ib_attach_more->table_name           = $tb->getTable();
            $certi_ib_attach_more->file_section         = '5';
            $certi_ib_attach_more->file                 = $storePath;
            $certi_ib_attach_more->file_client_name     = 'report' . '_' . $no . '.pdf';
            $certi_ib_attach_more->token                = str_random(16);
            $certi_ib_attach_more->save();
        }      
    
    }

    public function copyScopeIbFromAttachement($certiIbId)
{
    $copiedScoped = null;
    $fileSection = null;

    $app = CertiIb::find($certiIbId);

    $latestRecord = CertiIBAttachAll::where('app_certi_ib_id', $certiIbId)
    ->where('file_section', 3)
    ->where('table_name', 'app_certi_ib')
    ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
    ->first();

    $existingFilePath = 'files/applicants/check_files_ib/' . $latestRecord->file ;

    // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
    if (HP::checkFileStorage($existingFilePath)) {
        $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
        $no  = str_replace("RQ-","",$app->app_no);
        $no  = str_replace("-","_",$no);
        $dlName = 'scope_'.basename($existingFilePath);
        $attach_path  =  'files/applicants/check_files_ib/'.$no.'/';

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



    public function getSignature($attach)
    {
        
        $existingFilePath = $attach->url;//  'files/signers/3210100336046/tvE4QPMaEC-date_time20241211_011258.png'  ;

        $attachPath = 'bcertify_attach/signer';
        $fileName = basename($existingFilePath) ;// 'tvE4QPMaEC-date_time20241211_011258.png';

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
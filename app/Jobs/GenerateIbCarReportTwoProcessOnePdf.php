<?php

namespace App\Jobs;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;
use Illuminate\Support\Facades\File;

class GenerateIbCarReportTwoProcessOnePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 1. Properties ทั้งหมดที่จำเป็น ถูกคัดลอกมาจาก GeneratePdfJob ที่ทำงานได้
    public $htmlContent;
    public $outputPdfPath;
    public $footerTextLeft;
    public $footerTextRight;
    public $fullFileName;
    public $no;
    public $certi_ib_id;
    public $assessment_id;
    public $attachPath; // เพิ่ม property สำหรับเก็บ Path ของ FTP

    /**
     * สร้าง instance ของ Job ใหม่
     *
     * @return void
     */
    public function __construct(
        string $htmlContent,
        string $outputPdfPath,
        string $footerTextLeft,
        string $footerTextRight,
        string $fullFileName,
        string $no,
        int $certi_ib_id,
        ?int $assessment_id,
        string $attachPath // รับ Path ของ FTP มาโดยตรงจาก Controller
    ) {
        $this->htmlContent = $htmlContent;
        $this->outputPdfPath = $outputPdfPath;
        $this->footerTextLeft = $footerTextLeft;
        $this->footerTextRight = $footerTextRight;
        $this->fullFileName = $fullFileName;
        $this->no = $no;
        $this->certi_ib_id = $certi_ib_id;
        $this->assessment_id = $assessment_id;
        $this->attachPath = $attachPath; // กำหนดค่า Path ของ FTP
    }

    /**
     * Execute the job.
     * เมธอดนี้คือการนำโค้ดที่ทำงานได้ทั้งหมดมารวมไว้ในที่เดียว
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info('--- GenerateIbCarReportTwoProcessOnePdf STARTED. ---', ['file' => $this->fullFileName]);

        $diskName = 'uploads';
        $tempHtmlFileName = 'temp_for_pdf_' . time() . '_' . uniqid() . '.html';
        $tempHtmlPath = Storage::disk($diskName)->path($tempHtmlFileName);

        try {
            // -- ส่วนที่ 1: สร้าง PDF (คัดลอกจาก GeneratePdfJob ที่ทำงานได้) --

            $pdfCssPath = public_path('css/pdf.css');
            $finalCss = '';
            if (File::exists($pdfCssPath)) {
                $cssContent = File::get($pdfCssPath);
                $fontPath = public_path('fonts/THSarabunNew.ttf');
                $fontUrlPath = 'file:///' . str_replace('\\', '/', $fontPath);
                $finalCss = preg_replace(
                    "/url\((['\"]?)(\.\.\/|\/)?fonts\/THSarabunNew\.ttf(['\"]?)\)/",
                    "url('{$fontUrlPath}')",
                    $cssContent
                );
            }

            $fullHtml = "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><title>Report</title><style>{$finalCss}</style></head><body>{$this->htmlContent}</body></html>";
            Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);

            $nodePath = config('gen-pdf.node_path', 'node');
            $prefix = config('gen-pdf.command_prefix');
            $command = ($prefix ? $prefix . ' ' : '') .
                escapeshellarg($nodePath) .
                ' --max-old-space-size=4096 ' .
                escapeshellarg(base_path('generate-pdf.js')) . ' ' .
                escapeshellarg($tempHtmlPath) . ' ' .
                escapeshellarg($this->outputPdfPath) . ' ' .
                escapeshellarg($this->footerTextLeft) . ' ' .
                escapeshellarg($this->footerTextRight);

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(120);
            $process->run();

            // -- ส่วนที่ 2: อัปโหลดและบันทึก DB (คัดลอกจาก Logic ใน Controller ที่ทำงานได้) --
            
            // ตรวจสอบว่า Process สำเร็จ และไฟล์ PDF ถูกสร้างขึ้นจริง
            if ($process->isSuccessful() && Storage::disk($diskName)->exists($this->fullFileName)) {
                Log::info('PDF created successfully, proceeding to FTP upload.', ['file' => $this->fullFileName]);
                
                $pdfContent = Storage::disk($diskName)->get($this->fullFileName);

                // อัปโหลดไฟล์ขึ้น FTP โดยใช้ attachPath ที่รับมาจาก Controller
                Storage::disk('ftp')->put($this->attachPath . $this->fullFileName, $pdfContent);
                Log::info('Attempted to upload to FTP.', ['path' => $this->attachPath . $this->fullFileName]);

                // ตรวจสอบว่าไฟล์บน FTP มีอยู่จริง แล้วจึงบันทึกข้อมูลลง DB
                if (Storage::disk('ftp')->exists($this->attachPath . $this->fullFileName)) {
                    Log::info('FTP upload confirmed. Saving to database.', ['file' => $this->fullFileName]);
                    
                    $certiIBSaveAssessment = CertiIBSaveAssessment::find($this->assessment_id);
                 
                    
                    if ($certiIBSaveAssessment) {
                        $storePath = $this->no . '/' . $this->fullFileName;

                        // บันทึกข้อมูลไฟล์แนบ Section 3
                        CertiIBAttachAll::create([
                            'app_certi_ib_id' => $this->certi_ib_id,
                            'ref_id' => $this->assessment_id,
                            'table_name' => (new CertiIBSaveAssessment)->getTable(),
                            'file_section' => '3',
                            'file' => $storePath,
                            'file_client_name' => 'report' . '_' . $this->no . '.pdf',
                            'token' => Str::random(16),
                        ]);

                        // บันทึกข้อมูลไฟล์แนบ Section 1
                        CertiIBAttachAll::create([
                            'app_certi_ib_id' => $this->certi_ib_id,
                            'ref_id' => $this->assessment_id,
                            'table_name' => (new CertiIBSaveAssessment)->getTable(),
                            'file_section' => '5',
                            'file_desc' => 'รายงานปิด Car',
                            'file' => $storePath,
                            'file_client_name' => 'report' . '_' . $this->no . '.pdf',
                            'token' => Str::random(16),
                        ]);
                        Log::info("Ib car report ".'http://127.0.0.1:8081/certify/check/file_ib_client/'.$storePath.'/'. $this->fullFileName);
                        Log::info('Database records created successfully.');

                    } else {
                         Log::warning('CertiIBSaveAssessment not found.', ['app_certi_ib_id' => $this->certi_ib_id]);
                    }

                    // ลบไฟล์ PDF ชั่วคราวออกจาก Local Disk
                    // Storage::disk($diskName)->delete($this->fullFileName);
                    Log::info('Cleaned up local PDF file.', ['file' => $this->fullFileName]);

                } else {
                    Log::error('Failed to find file on FTP after upload.', ['path' => $this->attachPath . $this->fullFileName]);
                }

            } else {
                Log::error('Failed to create PDF.', ['error' => $process->getErrorOutput()]);
            }

        } catch (Exception $e) {
            Log::error('Exception in GenerateIbCarReportTwoProcessOnePdf: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->fail($e);
        } finally {
            // ลบไฟล์ HTML ชั่วคราวทิ้งเสมอ
            if (Storage::disk($diskName)->exists($tempHtmlFileName)) {
                Storage::disk($diskName)->delete($tempHtmlFileName);
            }
        }
    }
}
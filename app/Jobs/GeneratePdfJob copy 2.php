<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // protected $htmlContent;
    // protected $outputPdfPath;
    public $htmlContent;
    public $outputPdfPath;
    public $footerTextLeft;
    public $footerTextRight;
    public $fullFileName;
    public $no;
    public $certi_ib_id;
    public $assessment_app_certi_ib_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct(string $htmlContent, string $outputPdfPath)
    // {
    //     $this->htmlContent = $htmlContent;
    //     $this->outputPdfPath = $outputPdfPath;
    // }

    public function __construct(
        $htmlContent,
        $outputPdfPath,
        $footerTextLeft,
        $footerTextRight,
        $fullFileName,
        $no,
        $certi_ib_id,
        $assessment_app_certi_ib_id
    ) {
        $this->htmlContent = $htmlContent;
        $this->outputPdfPath = $outputPdfPath;
        $this->footerTextLeft = $footerTextLeft;
        $this->footerTextRight = $footerTextRight;
        $this->fullFileName = $fullFileName;
        $this->no = $no;
        $this->certi_ib_id = $certi_ib_id;
        $this->assessment_app_certi_ib_id = $assessment_app_certi_ib_id;
    }

     public function handle(): void
    {
        $diskName = 'uploads';
        $tempHtmlFileName = 'temp_for_pdf_' . time() . '_' . uniqid() . '.html';
        $tempHtmlPath = Storage::disk($diskName)->path($tempHtmlFileName);

        try {
            // --- 2. เตรียม CSS และแปลง Path ของฟอนต์ (เหมือนเดิม) ---
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

            // --- 3. สร้างเนื้อหา HTML ทั้งหมดสำหรับไฟล์ PDF (เหมือนเดิม) ---
            $fullHtml = "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><title>Document</title><style>{$finalCss}</style></head><body>{$this->htmlContent}</body></html>";

            // --- 4. บันทึกไฟล์ HTML ชั่วคราว (เหมือนเดิม) ---
            Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);

            // --- 5. สร้าง Command String สำหรับ Puppeteer (เหมือนเดิม) ---
            $nodePath = config('gen-pdf.node_path', 'node'); // ใส่ default value 'node'
            $prefix = config('gen-pdf.command_prefix');
            $command = ($prefix ? $prefix . ' ' : '') .
                escapeshellarg($nodePath) .
                ' --max-old-space-size=4096 ' .
                escapeshellarg(base_path('generate-pdf.js')) . ' ' .
                escapeshellarg($tempHtmlPath) . ' ' .
                escapeshellarg($this->outputPdfPath) . ' ' .
                escapeshellarg($this->footerTextLeft) . ' ' . // ส่ง footer เข้า command line
                escapeshellarg($this->footerTextRight);

            // --- 6. รัน Process เพื่อสร้างไฟล์ PDF (เหมือนเดิม) ---
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(120);
            $process->run();

            // --- 7. ตรวจสอบผลลัพธ์ และทำขั้นตอนต่อไป **ภายใน Job นี้** ---
            if ($process->isSuccessful() && Storage::disk($diskName)->exists($this->fullFileName)) {
                Log::info('GeneratePdfJob: PDF created successfully.', ['file' => $this->fullFileName]);
                
                $pdfContent = Storage::disk($diskName)->get($this->fullFileName);
                $attachPath = $this->no . '/';

                // --- 8. อัปโหลดไฟล์ขึ้น FTP ---
                Storage::disk('ftp')->put($attachPath . $this->fullFileName, $pdfContent);
                Log::info('GeneratePdfJob: Attempted to upload to FTP.', ['path' => $attachPath . $this->fullFileName]);

                // --- 9. ตรวจสอบว่าไฟล์บน FTP มีอยู่จริง แล้วจึงบันทึกข้อมูลลง DB ---
                if (Storage::disk('ftp')->exists($attachPath . $this->fullFileName)) {
                    Log::info('GeneratePdfJob: FTP upload confirmed. Saving to database.', ['file' => $this->fullFileName]);
                    
                    // หา ref_id จาก certi_ib_id ที่ส่งเข้ามา
                    $certiIBSaveAssessment = CertiIBSaveAssessment::where('app_certi_ib_id', $this->certi_ib_id)->first();
                    
                    if ($certiIBSaveAssessment) {
                        $storePath = $this->no . '/' . $this->fullFileName;

                        // บันทึกข้อมูลลงตาราง CertiIBAttachAll (Section 3)
                        CertiIBAttachAll::create([
                            'app_certi_ib_id' => $this->assessment_app_certi_ib_id,
                            'ref_id' => $certiIBSaveAssessment->id,
                            'table_name' => (new CertiIBSaveAssessment)->getTable(),
                            'file_section' => '3',
                            'file' => $storePath,
                            'file_client_name' => 'report' . '_' . $this->no . '.pdf',
                            'token' => Str::random(16),
                        ]);

                        // บันทึกข้อมูลลงตาราง CertiIBAttachAll (Section 1)
                        CertiIBAttachAll::create([
                            'app_certi_ib_id' => $this->assessment_app_certi_ib_id,
                            'ref_id' => $certiIBSaveAssessment->id,
                            'table_name' => (new CertiIBSaveAssessment)->getTable(),
                            'file_section' => '1',
                            'file' => $storePath,
                            'file_client_name' => 'report' . '_' . $this->no . '.pdf',
                            'token' => Str::random(16),
                        ]);

                        Log::info('GeneratePdfJob: Database records created successfully.');

                    } else {
                         Log::warning('GeneratePdfJob: CertiIBSaveAssessment not found, cannot save attachment records.', ['app_certi_ib_id' => $this->certi_ib_id]);
                    }

                    // --- [สำคัญ] ลบไฟล์ PDF ชั่วคราวออกจาก Local Disk หลังจากอัปโหลดและบันทึก DB สำเร็จ ---
                    Storage::disk($diskName)->delete($this->fullFileName);
                    Log::info('GeneratePdfJob: Cleaned up local PDF file.', ['file' => $this->fullFileName]);

                } else {
                    Log::error('GeneratePdfJob: Failed to upload PDF to FTP.', ['file' => $this->fullFileName]);
                }

            } else {
                // หากการสร้าง PDF ไม่สำเร็จ ให้บันทึกลง log
                Log::error('GeneratePdfJob failed to create PDF: ' . $process->getErrorOutput(), [
                    'command' => $command
                ]);
            }

        } catch (Exception $e) {
            Log::error('Exception in GeneratePdfJob: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            // ทำให้ Job ล้มเหลว เพื่อให้ระบบลองใหม่ (ถ้าตั้งค่าไว้)
            $this->fail($e);
        } finally {
            // --- 10. ลบไฟล์ HTML ชั่วคราวทิ้งเสมอ ไม่ว่าจะสำเร็จหรือล้มเหลว ---
            if (Storage::disk($diskName)->exists($tempHtmlFileName)) {
                Storage::disk($diskName)->delete($tempHtmlFileName);
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    // public function handle()
    // {
    //     // 1. เตรียม CSS และแปลง Path ของฟอนต์
    //     $pdfCssPath = public_path('css/pdf.css');
    //     $finalCss = '';
    //     if (File::exists($pdfCssPath)) {
    //         $cssContent = File::get($pdfCssPath);
    //         $fontPath = public_path('fonts/THSarabunNew.ttf');
    //         $fontUrlPath = 'file:///' . str_replace('\\', '/', $fontPath);
    //         $finalCss = preg_replace(
    //             "/url\((['\"]?)(\.\.\/|\/)?fonts\/THSarabunNew\.ttf(['\"]?)\)/",
    //             "url('{$fontUrlPath}')",
    //             $cssContent
    //         );
    //     }

    //     // 2. สร้างเนื้อหา HTML ทั้งหมดสำหรับไฟล์ PDF
    //     $fullHtml = "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><title>Document</title><style>{$finalCss}</style></head><body>{$this->htmlContent}</body></html>";

    //     // 3. สร้างไฟล์ HTML ชั่วคราว
    //     $diskName = 'uploads';
    //     $tempHtmlFileName = 'temp_for_pdf_' . time() . '_' . uniqid() . '.html';
    //     $tempHtmlPath = Storage::disk($diskName)->path($tempHtmlFileName);

    //     try {
    //         // 4. บันทึกไฟล์ HTML ชั่วคราว
    //         Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);

    //         // --- 5. สร้าง Command String โดยดึงค่าจาก Config (ส่วนที่ปรับปรุง) ---
    //         $nodePath = config('gen-pdf.node_path');
    //         $prefix = config('gen-pdf.command_prefix');

    //         // dd($nodePath,$prefix);

    //         $command = ($prefix ? $prefix . ' ' : '') . // เพิ่ม Prefix ถ้ามีค่า
    //                    escapeshellarg($nodePath) .
    //                    ' --max-old-space-size=4096 ' .
    //                    escapeshellarg(base_path('generate-pdf.js')) . ' ' .
    //                    escapeshellarg($tempHtmlPath) . ' ' .
    //                    escapeshellarg($this->outputPdfPath);

    //         // 6. รันโปรเซส
    //         $process = Process::fromShellCommandline($command);
    //         $process->setTimeout(120);
    //         $process->run();

    //         // 7. หากเกิดข้อผิดพลาด ให้บันทึกลงใน log ของ Laravel
    //         if (!$process->isSuccessful()) {
    //             Log::error('GeneratePdfJob failed: ' . $process->getErrorOutput());
    //         }

    //     } catch (\Exception $e) {
    //         Log::error('Exception in GeneratePdfJob: ' . $e->getMessage());
    //     } finally {
    //         // 8. ลบไฟล์ HTML ชั่วคราวทิ้งไปเสมอ
    //         if (Storage::disk($diskName)->exists($tempHtmlFileName)) {
    //             Storage::disk($diskName)->delete($tempHtmlFileName);
    //         }
    //     }
    // }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $htmlContent;
    protected $outputPdfPath;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $htmlContent, string $outputPdfPath)
    {
        $this->htmlContent = $htmlContent;
        $this->outputPdfPath = $outputPdfPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
  {
        // 1. เตรียม CSS และแปลง Path ของฟอนต์
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

        // 2. สร้างเนื้อหา HTML ทั้งหมดสำหรับไฟล์ PDF
        $fullHtml = "<!DOCTYPE html><html lang='th'><head><meta charset='UTF-8'><title>Document</title><style>{$finalCss}</style></head><body>{$this->htmlContent}</body></html>";

        // 3. สร้างไฟล์ HTML ชั่วคราว
        $diskName = 'uploads';
        $tempHtmlFileName = 'temp_for_pdf_' . time() . '_' . uniqid() . '.html';
        $tempHtmlPath = Storage::disk($diskName)->path($tempHtmlFileName);

        try {
            // 4. บันทึกไฟล์ HTML ชั่วคราว
            Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);

            // --- 5. สร้าง Command String โดยดึงค่าจาก Config (ส่วนที่ปรับปรุง) ---
            $nodePath = config('gen-pdf.node_path');
            $prefix = config('gen-pdf.command_prefix');

            // dd($nodePath,$prefix);

            $command = ($prefix ? $prefix . ' ' : '') . // เพิ่ม Prefix ถ้ามีค่า
                       escapeshellarg($nodePath) .
                       ' --max-old-space-size=4096 ' .
                       escapeshellarg(base_path('generate-pdf.js')) . ' ' .
                       escapeshellarg($tempHtmlPath) . ' ' .
                       escapeshellarg($this->outputPdfPath);

            // 6. รันโปรเซส
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(120);
            $process->run();

            // 7. หากเกิดข้อผิดพลาด ให้บันทึกลงใน log ของ Laravel
            if (!$process->isSuccessful()) {
                Log::error('GeneratePdfJob failed: ' . $process->getErrorOutput());
            }

        } catch (\Exception $e) {
            Log::error('Exception in GeneratePdfJob: ' . $e->getMessage());
        } finally {
            // 8. ลบไฟล์ HTML ชั่วคราวทิ้งไปเสมอ
            if (Storage::disk($diskName)->exists($tempHtmlFileName)) {
                Storage::disk($diskName)->delete($tempHtmlFileName);
            }
        }
    }
}

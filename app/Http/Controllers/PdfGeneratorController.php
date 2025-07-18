<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\GeneratePdfJob;
use Illuminate\Http\Request;
use App\Jobs\CreateTextFileJob;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process; // เพิ่มการ import Process


class PdfGeneratorController extends Controller
{
    /**
     * แสดงหน้า Editor หลัก (ไม่มีการแก้ไข)
     */
    public function showEditor()
    {
        return view('abtest.editor');
    }
 
   /**
     * ฟังก์ชันสำหรับทดสอบการสื่อสารโดยการส่ง Job เข้า Queue
     */
    public function testNodeJsCommunication(Request $request)
    {
        try {
            $diskName = 'uploads';
            $outputFileName = 'test_from_queue_' . time() . '.txt';
            $outputFilePath = Storage::disk($diskName)->path($outputFileName);

            // สร้าง Job และ "ส่ง" (Dispatch) เข้าไปในคิว
            // Controller จะทำงานเสร็จทันที และ Worker จะรับงานนี้ไปทำเบื้องหลัง
            CreateTextFileJob::dispatch($outputFilePath);

            return response()->json([
                'success' => true,
                'message' => 'Job to create a text file has been successfully dispatched! The queue worker will process it shortly.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while dispatching the job.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            // 1. ปิดการทำงานของ Debugbar (ถ้ามี)
            if (class_exists(\Barryvdh\Debugbar\Facade::class)) {
                \Barryvdh\Debugbar\Facade::disable();
            }

            // 2. รับข้อมูล HTML
            $request->validate(['html_content' => 'required|string']);
            $htmlContent = $request->input('html_content');

            // 3. สร้างชื่อและ Path สำหรับไฟล์ PDF ที่จะสร้าง
            $diskName = 'uploads';
            $outputPdfFileName = 'document_' . time() . '_' . uniqid() . '.pdf';
            $outputPdfPath = Storage::disk($diskName)->path($outputPdfFileName);

            // 4. สร้าง Job และ "ส่ง" (Dispatch) เข้าไปในคิว
            // ส่งแค่เนื้อหา HTML และ Path ปลายทางไปให้ Job
            GeneratePdfJob::dispatch($htmlContent, $outputPdfPath);

            // 5. ตอบกลับทันที (สำคัญ: ประสบการณ์ผู้ใช้จะเปลี่ยนไป)
            // เราจะไม่ได้ส่งไฟล์ PDF กลับไปโดยตรง แต่จะส่งข้อความยืนยัน
            // และชื่อไฟล์เพื่อให้ front-end นำไปใช้ต่อ (เช่น แสดงลิงก์ดาวน์โหลด)
            return response()->json([
                'success' => true,
                'message' => 'คำสั่งสร้าง PDF ของคุณถูกส่งเข้าสู่ระบบแล้ว กำลังดำเนินการ...',
                'file_name' => $outputPdfFileName,
                'download_url' => Storage::disk($diskName)->url($outputPdfFileName) // สร้าง URL สำหรับดาวน์โหลด
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการส่งคำสั่งสร้าง PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * สร้างและส่งออกไฟล์ PDF โดยใช้ disk 'uploads'
     */
    public function exportPdf_org(Request $request)
    {
        // หากเจอปัญหา Debugbar รบกวนการสร้าง PDF ในอนาคต สามารถเปิดใช้งานบรรทัดนี้ได้
        // \Debugbar::disable();

        $request->validate(['html_content' => 'required|string']);
        $htmlContent = $request->input('html_content');

        // --- ส่วนของการสร้าง HTML และ CSS (เหมือนเดิม) ---
        $pdfCssPath = public_path('css/pdf.css');
        $finalCss = '';
        if (File::exists($pdfCssPath)) {
            $cssContent = File::get($pdfCssPath);
            $fontPath = public_path('fonts/THSarabunNew.ttf');
            $fontUrlPath = 'file:///' . str_replace('\\', '/', $fontPath);
            $finalCss = str_replace("url('/fonts/THSarabunNew.ttf')", "url('{$fontUrlPath}')", $cssContent);
        }

        $fullHtml = "<!DOCTYPE html>
<html lang='th'>
<head><meta charset='UTF-8'><title>Document</title><style>{$finalCss}</style></head>
<body>{$htmlContent}</body>
</html>";

        // --- ส่วนที่แก้ไข: ตั้งชื่อไฟล์จากวันที่และเวลา ---

        $diskName = 'uploads';
        
        // 2. สร้างชื่อไฟล์จากวันที่และเวลาปัจจุบัน (เช่น 2025-07-18_06-34-00)
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $tempHtmlFileName = "temp_{$timestamp}.html";
        $outputPdfFileName = "document_{$timestamp}.pdf"; // <-- ชื่อไฟล์ PDF ใหม่

        // สร้าง Path ของไฟล์โดยอิงจาก disk 'uploads'
        $tempHtmlPath = Storage::disk($diskName)->path($tempHtmlFileName);
        $outputPdfPath = Storage::disk($diskName)->path($outputPdfFileName);

        try {
            // บันทึกไฟล์ HTML ลงใน disk 'uploads'
            Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);

            // --- ส่วนของการรันคำสั่ง Node.js (เหมือนเดิม) ---
            $nodeScriptPath = base_path('generate-pdf.js');
            $nodeExecutable = 'node';

            $safeTempHtmlPath = escapeshellarg($tempHtmlPath);
            $safeOutputPdfPath = escapeshellarg($outputPdfPath);

            $command = "{$nodeExecutable} " . escapeshellarg($nodeScriptPath) . " {$safeTempHtmlPath} {$safeOutputPdfPath} 2>&1";
            
            $commandOutput = shell_exec($command);

            // ตรวจสอบไฟล์ใน disk 'uploads'
            if (!Storage::disk($diskName)->exists($outputPdfFileName) || !empty($commandOutput)) {
                throw new \Exception('Node.js script failed. Output: ' . ($commandOutput ?: 'No output, but file was not created.'));
            }

            // อ่านไฟล์ PDF จาก disk 'uploads'
            $pdfContent = Storage::disk($diskName)->get($outputPdfFileName);
            return response($pdfContent)->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            return response("เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage(), 500);
        } finally {
            // ลบเฉพาะไฟล์ HTML ชั่วคราว และเก็บไฟล์ PDF ที่สร้างเสร็จแล้วไว้
            Storage::disk($diskName)->delete($tempHtmlFileName);
        }
    }

    public function loadTemplate()
    {
        $templateHtml = '
            <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 75%;">
                </colgroup>
                <tbody>
                    <tr>
                        <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;"><b>หัวข้อ:</b></td>
                        <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;">รายละเอียด...</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;"><b>วันที่:</b></td>
                        <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;">...</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;"><br></td>
                        <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;"><br></td>
                    </tr>
                </tbody>
            </table>
            <p><br></p>
        ';

        return response()->json(['html' => $templateHtml]);
    }
}

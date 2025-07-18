<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
\Barryvdh\Debugbar\Facade::disable();


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
     * สร้างและส่งออกไฟล์ PDF โดยใช้ disk 'uploads' (ฉบับทดสอบการสร้าง HTML)
     */
    public function exportPdf(Request $request)
    {
        // ปิดการทำงานของ Debugbar (แก้ไขถูกต้องแล้ว)
        if (class_exists(\Barryvdh\Debugbar\Facade::class)) {
            \Barryvdh\Debugbar\Facade::disable();
        }

        $request->validate(['html_content' => 'required|string']);
        $htmlContent = $request->input('html_content');

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

        $fullHtml = "<!DOCTYPE html>
<html lang='th'>
<head><meta charset='UTF-8'><title>Document</title><style>{$finalCss}</style></head>
<body>{$htmlContent}</body>
</html>";

        $diskName = 'uploads';
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $tempHtmlFileName = "temp_{$timestamp}.html";

        // --- ขั้นตอนการทดสอบ ---
        try {
            // 1. สร้างไฟล์ HTML ชั่วคราวใน public/uploads
            Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);
            
            // 2. ส่งข้อความยืนยันกลับไป แล้วหยุดการทำงานทันที
            // โค้ดส่วนที่เหลือ (shell_exec, finally) จะไม่ถูกรัน
            return response()->json([
                'status' => 'HTML file created successfully.',
                'message' => 'ไฟล์ HTML ถูกสร้างขึ้นในโฟลเดอร์ uploads แล้ว โปรดตรวจสอบ',
                'file_name' => $tempHtmlFileName,
                'full_path' => Storage::disk($diskName)->path($tempHtmlFileName)
            ]);

        } catch (\Exception $e) {
            // ในกรณีที่แม้แต่การสร้างไฟล์ HTML ก็ยังล้มเหลว
            return response("เกิดข้อผิดพลาดในการสร้างไฟล์ HTML: " . $e->getMessage(), 500);
        }
        // --- จบขั้นตอนการทดสอบ ---
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

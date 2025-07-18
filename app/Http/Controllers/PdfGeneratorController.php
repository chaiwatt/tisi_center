<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


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
     * สร้างและส่งออกไฟล์ PDF โดยใช้ disk 'uploads'
     */
    public function exportPdf(Request $request)
    {
        // \Debugbar::disable();

        $request->validate(['html_content' => 'required|string']);
        $htmlContent = $request->input('html_content');

        $pdfCssPath = public_path('css/pdf.css');
        $finalCss = '';
        if (File::exists($pdfCssPath)) {
            $cssContent = File::get($pdfCssPath);
            $fontPath = public_path('fonts/THSarabunNew.ttf');
            $fontUrlPath = 'file:///' . str_replace('\\', '/', $fontPath);

            // --- ส่วนที่แก้ไข: เพิ่มเงื่อนไขเพื่อจัดการ Path ตามสภาพแวดล้อม ---
            // วิธีที่แนะนำคือการใช้ app()->isLocal() ซึ่งจะตรวจสอบค่า APP_ENV ในไฟล์ .env
            // ซึ่งมีความน่าเชื่อถือกว่าการตรวจสอบจาก IP หรือ Hostname
            if (app()->isLocal()) {
                // สำหรับ Local Environment
                // ใช้ Regular Expression เพื่อให้แน่ใจว่า Path ถูกแปลงอย่างถูกต้องเสมอ
                // ไม่ว่าใน CSS จะใช้ url('/fonts/...') หรือ url('../fonts/...')
                $finalCss = preg_replace(
                    "/url\((['\"]?)(\.\.\/|\/)?fonts\/THSarabunNew\.ttf(['\"]?)\)/",
                    "url('{$fontUrlPath}')",
                    $cssContent
                );
            } else {
                // สำหรับ Production Environment (หรืออื่นๆ)
                // เรายังคงใช้ Logic เดียวกันเพื่อให้มั่นใจว่า Puppeteer จะหาไฟล์ฟอนต์เจอเสมอ
                // เนื่องจากโค้ดนี้มีความยืดหยุ่นและรองรับ Path ได้ทุกรูปแบบ
                $finalCss = preg_replace(
                    "/url\((['\"]?)(\.\.\/|\/)?fonts\/THSarabunNew\.ttf(['\"]?)\)/",
                    "url('{$fontUrlPath}')",
                    $cssContent
                );

                
            }
        }

        // dd($finalCss);

        $fullHtml = "<!DOCTYPE html>
<html lang='th'>
<head><meta charset='UTF-8'><title>Document</title><style>{$finalCss}</style></head>
<body>{$htmlContent}</body>
</html>";

        $diskName = 'uploads';
        
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $tempHtmlFileName = "temp_{$timestamp}.html";
        $outputPdfFileName = "document_{$timestamp}.pdf";

        $tempHtmlPath = Storage::disk($diskName)->path($tempHtmlFileName);
        $outputPdfPath = Storage::disk($diskName)->path($outputPdfFileName);

        try {
            Storage::disk($diskName)->put($tempHtmlFileName, $fullHtml);

            $nodeScriptPath = base_path('generate-pdf.js');
            $nodeExecutable = 'node';

            $safeTempHtmlPath = escapeshellarg($tempHtmlPath);
            $safeOutputPdfPath = escapeshellarg($outputPdfPath);

            $command = "{$nodeExecutable} " . escapeshellarg($nodeScriptPath) . " {$safeTempHtmlPath} {$safeOutputPdfPath} 2>&1";
            
            $commandOutput = shell_exec($command);

            if (!Storage::disk($diskName)->exists($outputPdfFileName) || !empty($commandOutput)) {
                throw new \Exception('Node.js script failed. Output: ' . ($commandOutput ?: 'No output, but file was not created.'));
            }

            $pdfContent = Storage::disk($diskName)->get($outputPdfFileName);
            return response($pdfContent)->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            return response("เกิดข้อผิดพลาดในการสร้าง PDF: " . $e->getMessage(), 500);
        } finally {
            Storage::disk($diskName)->delete($tempHtmlFileName);
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

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
        // ib_final_report_process_one, ib_car_report_one_process_one, , ib_car_report_two_process_one,
        // ib_final_report_process_two, ib_car_report_one_process_two, , ib_car_report_two_process_two,
        $templateType = "ib_final_report_process_two";
        return view('abtest.editor',[
            'templateType' => $templateType
        ]);
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

/**
     * สร้างและส่งออกไฟล์ PDF โดยการส่ง Job เข้า Queue และรอจนเสร็จ
     */
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
            // dd("ok");
            // 4. สร้าง Job และ "ส่ง" (Dispatch) เข้าไปในคิว
            GeneratePdfJob::dispatch($htmlContent, $outputPdfPath);

            // 5. รอให้ไฟล์ถูกสร้างขึ้นโดย Worker (Polling)
            $timeout = 60; // รอสูงสุด 60 วินาที
            $startTime = time();

            while (time() - $startTime < $timeout) {
                if (Storage::disk($diskName)->exists($outputPdfFileName)) {
                    // เมื่อพบไฟล์แล้ว ให้อ่านเนื้อหา
                    $pdfContent = Storage::disk($diskName)->get($outputPdfFileName);
                    
                    // ลบไฟล์ทิ้งหลังจากอ่านแล้ว
                    Storage::disk($diskName)->delete($outputPdfFileName);

                    // ส่งไฟล์ PDF กลับไปให้เบราว์เซอร์แสดงผลโดยตรง
                    return response($pdfContent)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'inline; filename="' . $outputPdfFileName . '"');
                }
                sleep(1); // หน่วงเวลา 1 วินาทีก่อนตรวจสอบอีกครั้ง
            }

            // หากหมดเวลาแล้วยังไม่พบไฟล์ ให้โยน Exception
            throw new \Exception('การสร้างไฟล์ PDF ใช้เวลานานเกินไป (หมดเวลาหลังจาก ' . $timeout . ' วินาที)');

        } catch (\Exception $e) {
            // หากเกิดข้อผิดพลาด ให้ส่งกลับเป็นหน้า Error
            return response("เกิดข้อผิดพลาด: " . $e->getMessage(), 500);
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

   /**
     * โหลดเทมเพลตตามประเภทที่ระบุ และรองรับการส่งข้อมูลแบบหลายหน้า
     */
    public function loadTemplate(Request $request)
    {
        // รับค่า templateType จาก request
        $templateType = $request->input('templateType');
        $pages = []; // เปลี่ยนเป็น Array เพื่อรองรับหลายหน้า

        // ใช้ switch เพื่อเลือก template ตามค่าที่ได้รับ
        switch ($templateType) {
            case 'ib_final_report_process_one':
                // *** ตัวอย่างเทมเพลต 2 หน้า ***
                $pages = [
                    '<h1>เทมเพลตสำหรับ Final Report, Process One</h1><p>กรุณาใส่เนื้อหาสำหรับ page1</p>', // หน้าที่ 1
                    '<h1>เทมเพลตสำหรับ Final Report, Process One</h1><p>กรุณาใส่เนื้อหาสำหรับ page2</p>'  // หน้าที่ 2
                ];
                break;

            case 'ib_car_report_one_process_one':
                $pages = ['<h1>เทมเพลตสำหรับ Car Report One, Process One</h1><p>กรุณาใส่เนื้อหา...</p>'];
                break;

            case 'ib_car_report_two_process_one':
                 $pages = ['<h1>เทมเพลตสำหรับ Car Report Two, Process One</h1><p>กรุณาใส่เนื้อหา...</p>'];
                break;

            case 'ib_final_report_process_two':
                 $pages = ['
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;">
                        <tr>
                            <td colspan="3" style="padding: 10px 0; text-align: center; font-size: 24px; font-weight: bold;">
                                รายงานการตรวจประเมิน ณ สถานประกอบการ
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="width: 18%; padding: 5px 8px; vertical-align: top;"><b>1. หน่วยตรวจ</b> :</td>
                            <td style="width: 77%; padding: 5px 8px; vertical-align: top;">บริษัท ทีเอส อินสเปคชั่น จำกัด</td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="padding: 5px 8px; vertical-align: top;width: 25%;"><b>2. ที่ตั้งสำนักงานใหญ่</b> :</td>
                            <td style="padding: 5px 8px; vertical-align: top;">
                                เลขที่ 1674/3 ซอยเพชรบุรี 36 ถนนเพชรบุรีตัดใหม่ แขวงมักกะสัน เขตราชเทวี กรุงเทพมหานคร<br>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="width: 50%;">โทรศัพท์ : -</td>
                                        <td style="width: 50%;">โทรสาร : -</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                          <tr >
                                <td style="padding: 5px 8px 5px 22px; vertical-align: top; width: 25%;"><b>ที่ตั้งสำนักงานสาขา</b>:</td>
                                <td style="padding: 5px 8px; vertical-align: top;">
                                    -<br>
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                        <tr>
                                            <td style="width: 50%;">โทรศัพท์ : -</td>
                                            <td style="width: 50%;">โทรสาร : -</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="width: 15%; padding: 5px 8px; vertical-align: top;"><b>3. ประเภทการตรวจประเมิน</b> :</td>
                        </tr>
                        <tr>
                            <td style="padding-left:30px">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 50%; padding: 2px;">&#9744; การตรวจประเมินรับรองครั้งแรก</td>
                                        <td style="width: 50%; padding: 2px;">&#9745; การตรวจติดตามผลครั้งที่ 1</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 2px;">&#9744; การตรวจประเมินเพื่อต่ออายุการรับรอง</td>
                                        <td style="width: 50%; padding: 2px;">&#9744; อื่น ๆ</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="width: 32%; padding: 5px 8px; vertical-align: top;"><b>4. สาขาและขอบข่ายการรับรอง</b> :</td>
                            <td style="width: 65%; padding: 5px 8px; vertical-align: top;"> รายละเอียด ดังเอกสารแนบ 1</td>
                        </tr>
                    </table>
                    <b style="font-size: 22px">5. เกณฑ์การตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;(1) ...<br>
                    &nbsp;&nbsp;&nbsp;(2) ...<br>
                    &nbsp;&nbsp;&nbsp;(3) ...<br>
                    <b style="font-size: 22px">6. วันที่ตรวจประเมิน</b> : &nbsp;&nbsp;&nbsp; 25 - 25 มีนาคม 2568<br>
                    <b style="font-size: 22px">7. คณะผู้ตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;(1) ...<br>
                    &nbsp;&nbsp;&nbsp;(2) ...<br>
                    &nbsp;&nbsp;&nbsp;(3) ...<br>
                '];
                break;

            case 'ib_car_report_one_process_two':
                 $pages = ['<h1>เทมเพลตสำหรับ Car Report One, Process Two</h1><p>กรุณาใส่เนื้อหา...</p>'];
                break;

            case 'ib_car_report_two_process_two':
                 $pages = ['<h1>เทมเพลตสำหรับ Car Report Two, Process Two</h1><p>กรุณาใส่เนื้อหา...</p>'];
                break;

            default:
                // หากไม่มี case ไหนตรงเลย ให้ใช้เทมเพลตเริ่มต้น (ในรูปแบบ Array 1 หน้า)
                $pages = ['
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                        <colgroup>
                            <col style="width: 25%;">
                            <col style="width: 75%;">
                        </colgroup>
                        <tbody>
                            <tr>
                                <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;"><b>หัวข้อ:</b></td>
                                <td style="padding: 2px 8px; border: none; font-size: 16pt; line-height: 1.0;">(เทมเพลตเริ่มต้น)</td>
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
                '];
                break;
        }

        // ส่งข้อมูลกลับในรูปแบบ JSON ที่มี key เป็น "pages"
        return response()->json(['pages' => $pages]);
    }
}

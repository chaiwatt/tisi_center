<?php

namespace App\Http\Controllers;
use HP;
use DOMXPath;
use stdClass;
use DOMDocument;
use Carbon\Carbon;
use App\CbHtmlTemplate;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\ApplicantCB\CbTobToun;
use App\Certify\CbReportTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\ApplicantCB\CbDocReviewAssessment;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Mail\CB\CBSignReportNotificationMail;
use App\Certify\ApplicantIB\CbDocReviewReport;
use App\Models\Certificate\CbDocReviewAuditor;
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Certify\ApplicantCB\CertiCBReport;
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsDate;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessmentBug;

class CbPdfGeneratorController extends Controller
{

    public function formatAddress(object $data): string
    {
        $addressParts = [];

        if (!empty($data->hq_address)) { $addressParts[] = 'เลขที่ ' . $data->hq_address; }
        if (!empty($data->hq_moo)) { $addressParts[] = 'หมู่' . $data->hq_moo; }
        if (!empty($data->hq_soi)) { $addressParts[] = 'ซอย' . $data->hq_soi; }
        if (!empty($data->hq_road)) { $addressParts[] = 'ถนน' . $data->hq_road; }

        if (!empty($data->HqProvinceName)) {
            if (str_contains($data->HqProvinceName, 'กรุงเทพ')) {
                if (!empty($data->HqSubdistrictName)) { $addressParts[] = 'แขวง' . $data->HqSubdistrictName; }
                if (!empty($data->HqDistrictName)) { $addressParts[] = 'เขต' . $data->HqDistrictName; }
            } else {
                if (!empty($data->HqSubdistrictName)) { $addressParts[] = 'ตำบล' . $data->HqSubdistrictName; }
                if (!empty($data->HqDistrictName)) { $addressParts[] = 'อำเภอ' . $data->HqDistrictName; }
            }
            $addressParts[] = $data->HqProvinceName;
        }

        if (!empty($data->hq_zipcode)) {
            $addressParts[] = $data->hq_zipcode;
        }

        return implode(' ', $addressParts);
    }

    function formatLocationAddress(object $data): string
    {
        $addressParts = [];

        // เพิ่ม เลขที่, หมู่, ซอย, ถนน
        if (!empty($data->address_number)) { $addressParts[] = 'เลขที่ ' . $data->address_number; }
        if (!empty($data->allay)) { $addressParts[] = 'หมู่' . $data->allay; }
        if (!empty($data->address_soi)) { $addressParts[] = 'ซอย' . $data->address_soi; }
        if (!empty($data->address_street)) { $addressParts[] = 'ถนน' . $data->address_street; }

        // เพิ่ม ตำบล/แขวง, อำเภอ/เขต, จังหวัด
        if (!empty($data->basic_province->PROVINCE_NAME)) {
            if (str_contains($data->basic_province->PROVINCE_NAME, 'กรุงเทพ')) {
                if (!empty($data->district_id)) { $addressParts[] = 'แขวง' . $data->district_id; }
                if (!empty($data->amphur_id)) { $addressParts[] = 'เขต' . $data->amphur_id; }
            } else {
                if (!empty($data->district_id)) { $addressParts[] = 'ตำบล' . $data->district_id; }
                if (!empty($data->amphur_id)) { $addressParts[] = 'อำเภอ' . $data->amphur_id; }
            }
            $addressParts[] = $data->basic_province->PROVINCE_NAME;
        }

        // เพิ่มรหัสไปรษณีย์
        if (!empty($data->postcode)) {
            $addressParts[] = $data->postcode;
        }

        return implode(' ', $addressParts);
    }


    public function loadCbTemplate(Request $request)
    {
        // dd($request->all());
        $id = $request->assessmentId;
       
        $assessment = CertiCBSaveAssessment::find($id);
        $certi_cb = CertiCb::find($request->input('cbId'));

        $templateType = $request->input('templateType');

        $savedReport = CbReportTemplate::where('cb_assessment_id', $id)
                                       ->where('report_type', $templateType)
                                       ->first();
        
        if ($savedReport && !empty($savedReport->template)) {
            // ถ้ามีข้อมูลอยู่แล้ว ให้ส่งข้อมูลนั้นกลับไป
            // ไม่ต้อง decode เพราะเราไม่ได้ encode ตอนบันทึก
            return response()->json([
                'html' => $savedReport->template, 
                'status' => $savedReport->status
            ]);
        } 

        $certi_cb = CertiCb::find($request->input('cbId'));
        $cbName = $certi_cb->name_standard;
        $cbAppNo = $certi_cb->app_no;
        $cbHqAddress = $this->formatAddress($certi_cb);
        $telephone = !empty($certi_cb->hq_telephone) ? $certi_cb->hq_telephone : '-';
        $fax = !empty($certi_cb->hq_fax) ? $certi_cb->hq_fax : '-';

        $cbLocalAddress = $this->formatLocationAddress($certi_cb);
        $localTelephone = !empty($certi_cb->tel) ? $certi_cb->tel : '-';
        $localFax = !empty($certi_cb->tel_fax) ? $certi_cb->tel_fax : '-';

        // 1. สร้างสตริงว่างเพื่อเก็บรายชื่อ
        $auditorsHtml = '';

        // 2. วนลูปข้อมูลผู้ตรวจประเมิน
        if (!empty($assessment->CertiCBAuditorsTo->CertiCBAuditorsLists)) {
             $tableRows = '';
                foreach ($assessment->CertiCBAuditorsTo->CertiCBAuditorsLists as $key => $auditor) {
                    $tableRows .=
                        '<tr>' .
                            '<td style="border: none; vertical-align: top; width: 30px;">' . '&nbsp;&nbsp;&nbsp;(' . ($key + 1) . ')' . '</td>' .
                            '<td style="border: none; vertical-align: top; width: 180px;">' . $auditor->temp_users . '</td>' .
                            '<td style="border: none; vertical-align: top;">' . $auditor->StatusAuditorTo->title . '</td>' .
                        '</tr>';
                }
                $auditorsHtml =
                    '<table style="width: 100%; border-collapse: collapse;">' .
                        $tableRows .
                    '</table>';
        } else {
            $auditorsHtml = '&nbsp;&nbsp;&nbsp;(1) ...<br>&nbsp;&nbsp;&nbsp;(2) ...<br>&nbsp;&nbsp;&nbsp;(2) ...<br>';
        }

        $representativesHtml = '';
        //
        if (!empty($assessment->auditorCbRepresentatives)) {

            $tableRows = '';
            foreach ($assessment->auditorCbRepresentatives as $key => $representative) {
                $tableRows .=
                    '<tr>' .
                        // คอลัมน์สำหรับลำดับที่
                        '<td style="border: none; vertical-align: top; width: 40px;">' . '&nbsp;&nbsp;&nbsp;(' . ($key + 1) . ')' . '</td>' .
                        // คอลัมน์สำหรับชื่อ
                        '<td style="border: none; vertical-align: top; width: 250px;">' . $representative->name . '</td>' .
                        // คอลัมน์สำหรับตำแหน่ง
                        '<td style="border: none; vertical-align: top;">' . $representative->position . '</td>' .
                    '</tr>';
            }
            if($tableRows != '')
            {
                $representativesHtml =
                '<table style="width: 100%; border-collapse: collapse;">' .
                    $tableRows .
                '</table>';
            }else{
                 $representativesHtml = '&nbsp;&nbsp;&nbsp;(1) ...<br>&nbsp;&nbsp;&nbsp;(2) ...<br>&nbsp;&nbsp;&nbsp;(2) ...<br>';
            }
          
        }else {
            $representativesHtml = '&nbsp;&nbsp;&nbsp;(1) ...<br>&nbsp;&nbsp;&nbsp;(2) ...<br>&nbsp;&nbsp;&nbsp;(2) ...<br>';
        }

        $startDate = Carbon::parse($assessment->CertiCBAuditorsTo->app_certi_cb_auditors_date->start_date);
        $endDate = Carbon::parse($assessment->CertiCBAuditorsTo->app_certi_cb_auditors_date->end_date);

        // ฟังก์ชันแปลงเดือนเป็นภาษาไทย (ตามโค้ดที่คุณให้มา)
        $getThaiMonth = function($month) {
            $months = [
                'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
            ];
            return $months[$month] ?? $month;
        };

        // ดึงวัน เดือน และปี (ตามโค้ดที่คุณให้มา)
        $startDay = $startDate->day;
        $startMonth = $getThaiMonth($startDate->format('F'));
        $startYear = $startDate->year + 543;

        $endDay = $endDate->day;
        $endMonth = $getThaiMonth($endDate->format('F'));
        $endYear = $endDate->year + 543;

        $assessmentDate = '';

        // ตรวจสอบและจัดรูปแบบวันที่ (ตามโค้ดที่คุณให้มา)
        if ($startDate->equalTo($endDate)) {
            $assessmentDate = "{$startDay} {$startMonth} {$startYear}";
        } elseif ($startMonth === $endMonth && $startYear === $endYear) {
            $assessmentDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
        } else {
            $assessmentDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
        }


        // 1. ดึงข้อมูลตามที่คุณระบุ
        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id', $certi_cb->id)->first();
        $formattedReviewDate = ''; // กำหนดค่าเริ่มต้น

        // 2. ตรวจสอบว่ามีข้อมูลหรือไม่ก่อนดำเนินการต่อ
        if ($cbDocReviewAuditor) {
            $startDate = Carbon::parse($cbDocReviewAuditor->from_date);
            $endDate = Carbon::parse($cbDocReviewAuditor->to_date);

            // ฟังก์ชันแปลงเดือนเป็นภาษาไทย
            $getThaiMonth = function($month) {
                $months = [
                    'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                    'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                    'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                    'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                ];
                return $months[$month] ?? $month;
            };

            // ดึงวัน เดือน และปี
            $startDay = $startDate->day;
            $startMonth = $getThaiMonth($startDate->format('F'));
            $startYear = $startDate->year + 543;

            $endDay = $endDate->day;
            $endMonth = $getThaiMonth($endDate->format('F'));
            $endYear = $endDate->year + 543;

            // ตรวจสอบและจัดรูปแบบวันที่
            if ($startDate->equalTo($endDate)) {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear}";
            } elseif ($startMonth === $endMonth && $startYear === $endYear) {
                $formattedReviewDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
            } else {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
            }
        } else {
            // กรณีไม่พบข้อมูล
            $formattedReviewDate = '-';
        }

        $finalReportProcessOneSignerNameOne = "";
        $finalReportProcessOneSignerNameTwo = "";
        $finalReportProcessOneSignerNameThree = "";

        $finalReportProcessOneSignerPositionOne = "";
        $finalReportProcessOneSignerPositionTwo = "";
        $finalReportProcessOneSignerPositionThree = "";

        $finalReportProcessOneSignerDateOne = "";
        $finalReportProcessOneSignerDateTwo = "";
        $finalReportProcessOneSignerDateThree = "";


                
        $pages = []; // เปลี่ยนเป็น Array เพื่อรองรับหลายหน้า
//  dd("ok");
        // ใช้ switch เพื่อเลือก template ตามค่าที่ได้รับ
        switch ($templateType) {
           
            case 'cb_final_report_process_two':
                // *** ตัวอย่างเทมเพลต 2 หน้า ***
                $pages = ['
                     <table style="width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 18px;">
                        <tr>
                            <td style="text-align: center; vertical-align: middle; font-size: 24px; font-weight: bold; padding-bottom: 5px;">
                                รายงานการตรวจประเมินผู้ตรวจ
                            </td>
                        </tr>
                    </table>
                     <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 65%">
                            <b>1. ชื่อหน่วยรับรอง :</b> '.$cbName.'
                        </td>
                        <td style="padding: 10px 0; font-size: 22px; width: 35%">
                            <b>คำขอเลขที่ :</b> '.$cbAppNo.' 
                        </td>
                    </tr>
                    </table>
                    <b style="font-size: 22px">2. ขอบข่ายการรับรองระบบงาน : </b> ... <br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระบบงาน : .....<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สาขา : .....<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ขอบข่าย : .....<br> 
                    <b style="font-size: 22px">3. ชื่่อสถานที่ : </b> ... <br> 
                    <b style="font-size: 22px">   ที่ตั้ง : </b> ... <br> 
                    <b style="font-size: 22px">4. ขอบข่ายการตรวจ : </b> ... <br> 
                    <b style="font-size: 22px">5. มาตรฐานที่ใช้ตรวจ : </b> ... <br> 
                    <b style="font-size: 22px">6. วันที่ตรวจประเมิน : </b> ... <br> 
                    <b style="font-size: 22px">7. การตรวจประเมินเพื่อ : </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<input type="checkbox"> การรับรองครั้งแรก&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"> การตรวจติดตามผล ครั้งที่ 1<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<input type="checkbox"> การต่ออายุการรับรองระบบงาน&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"> อื่น ๆ<br> 
                    <b style="font-size: 22px">8. คณะผู้ตรวจประเมินของสำนักงาน : </b> ... <br> 
                    <b style="font-size: 22px">9. คณะผู้ตรวจของหน่วยงาน : </b> ... <br> 
                    <b style="font-size: 22px">10. รายละเอียดการตรวจประเมิน : </b> ... <br> 
                ','
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;ผลการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;จากการตรวจประเมิน .....<br> 
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;สรุปการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;หน่วยรับรองประเมิน .....<br> 
                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                        <tbody>
                            <tr>
                                <!-- Column 1 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameOne.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionOne.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
                                    </div>
                                </td>
                                <!-- Column 2 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameTwo.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionTwo.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
                                    </div>
                                </td>
                                <!-- Column 3 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameThree.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionThree.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    ','
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 18px;">
                        <tr>
                            <td style="text-align: center; vertical-align: middle; font-size: 24px; font-weight: bold;">
                                รายงานการตรวจประเมินผู้ตรวจ
                            </td>
                        </tr>
                    </table>
                     <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 60%">
                            <b>ชื่อผู้ตรวจ :</b> ....
                        </td>
                        <td style="padding: 10px 0; font-size: 22px; width: 40%">
                            <b>ตำแหน่ง :</b> .... 
                        </td>
                    </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 65%">
                            <b>ชื่อหน่วยรับรอง :</b> ....
                        </td>
                        <td style="padding: 10px 0; font-size: 22px; width: 35%">
                            <b>คำขอเลขที่ :</b> .... 
                        </td>
                    </tr>
                    </table>
                    <b style="font-size: 22px">ขอบข่ายการตรวจ : </b> ... <br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระบบงาน : .....<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สาขา : .....<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ขอบข่าย : .....<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วัตถุประสงค์การตรวจ : .....<br> 
                    <b style="font-size: 22px">ข้อกำหนดที่ใช้ตรวจ : </b> ... <br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1) .....<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2) .....<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3) .....<br>
                    <b style="font-size: 22px">ชื่อสถานที่ตรวจ : </b> ... <br> 
                    <b style="font-size: 22px">ที่ตั้ง : </b> ... <br> 
                    <b style="font-size: 22px">วันที่ตรวจ : </b> ... <br> 
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 18px;">
                        <tr>
                            <td style="text-align: center; vertical-align: middle; font-size: 24px; font-weight: bold">
                               ผลการตรวจประเมิน
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: middle; font-size: 22px; ">
                               (ผล: Y = ยอมรับได้ N = ข้อบกพร่อง O = ข้อสังเกต A = แนะนำโดยวาจา N/A=ไม่ประเมินผล)
                            </td>
                        </tr>
                    </table>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 18px; border: 1px solid black">
                        <tr>
                            <td style="text-align: center;vertical-align: middle; font-size: 22px; font-weight: bold; border: 1px solid black">
                               เกณฑ์กำหนด
                            </td>
                             <td style="text-align: center;vertical-align: middle; font-size: 22px; font-weight: bold; border: 1px solid black">
                               ผล
                            </td>
                             <td style="text-align: center;vertical-align: middle; font-size: 22px; font-weight: bold; border: 1px solid black">
                               รายละเอียด
                            </td>
                        </tr>
                         <tr>
                            <td style="width:40%;vertical-align: middle; font-size: 22px;  border: 1px solid black">
                               <br>
                            </td>
                             <td style="width:20%;vertical-align: middle; font-size: 22px; border: 1px solid black">
                                <br>
                            </td>
                             <td style="width:40%;vertical-align: middle; font-size: 22px;  border: 1px solid black">
                                <br>
                            </td>
                        </tr>
                    </table>

                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 18px; border: 1px solid black; margin-top:10px">
                        <tr>
                            <td colspan="2" style="width:33%; vertical-align: middle; font-size: 22px; border: 1px solid black;">
                              <b>ข้อสรุป</b> 
                              <br>
                              <br>
                              <br>
                            </td>
                        </tr>
                        <tr>
                            <td  style="width:50%;vertical-align: middle; font-size: 22px; border: 1px solid black;">
                                ผู้ตรวจประเมินของหน่วยรับรองระบบงาน หน่วยรับรอง : 
                            </td>

                            <td  style=" text-align: center; vertical-align: top; padding: 5px; border: none; font-size: 22px;">
                                <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                    <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                </div>
                                <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                    <p style="margin: 0;">()</p>
                                    <p style="margin: 0;"></p>
                                    <p style="margin: 0;" class="signed_date">วันที่ </p>
                                </div>
                            </td>
                        </tr>
                    </table>   

                 '];
                 
                break;

            case 'cb_car_report_one_process_one':
                $pages = ['
          
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0; font-size: 18px;">
                        <tr>
                            <td style="text-align: center; vertical-align: middle; font-size: 24px; font-weight: bold; padding-bottom: 5px;">
                                รายงานข้อบกพร่อง
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; margin-top: -15px; font-size: 18px;">
                        <tr>
                            <td style="width: 50%; vertical-align: middle;">
                                <img src="https://placehold.co/30x30/EEE/31343C?text=Logo" alt="Logo" style="width: 40px; height: 40px; vertical-align: middle">
                                <span style="font-size: 18px; vertical-align: middle;  font-style: italic;">
                                    สำนักงานคณะกรรมการการมาตรฐานแห่งชาติ
                                </span>
                            </td>
                            <td style="width: 50%; text-align: right; font-size: 18px; vertical-align: center;">
                                หน้า 1/1
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; font-size: 18px; border: 1px solid black;">
                        <!-- Top two-column section -->
                        <tr style="border: 1px solid black;">
                            <td style="width: 50%; border: 1px solid black; padding: 8px; vertical-align: top; line-height: 1.23;">
                                <b style="font-weight: bold;">ชื่อหน่วยรับรอง/หน่วยรับรอง:</b> ...<br>
                                <b style="font-weight: bold;">เลขที่คำขอ:</b> ...<br>
                                <b style="font-weight: bold;">สถานที่ตรวจประเมิน:</b> ...<br>
                                <b style="font-weight: bold;">วันที่:</b> ...
                            </td>
                            <td style="width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                                <b style="font-weight: bold;">รายงานข้อบกพร่องที่:</b> ...<br>
                                <b style="font-weight: bold;">การตรวจประเมินเพื่อ: </b><span><input type="checkbox"> รับรองครั้งแรก</span> <span><input type="checkbox"> ติดตามผลครั้งที่ 1</span>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="padding: 2px; border: none; vertical-align: top;"><input type="checkbox"> ต่ออายุการรับรอง</td>
                                        <td style="padding: 2px; border: none; vertical-align: top;"><input type="checkbox"> อื่นๆ ...</td>
                                    </tr>
                                </table>
                                <b style="font-weight: bold;">การตรวจประเมิน:</b> <span><input type="checkbox"> ขั้นตอนที่ 1</span> <span><input type="checkbox"> ขั้นตอนที่ 2</span><br>
                                <b style="font-weight: bold;">รหัส ISIC / สาขา:</b> ...
                            </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td colspan="2" style="border: 1px solid black; padding: 8px; vertical-align: top;">
                                <b style="font-weight: bold;">ชนิดข้อบกพร่อง:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>มอก.</b> ... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-weight: bold;">ข้อ:</b>...
                            </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td colspan="2" style="border: 1px solid black; padding: 8px;padding-bottom:0px; vertical-align: top; height: 150px;">
                                <b style="font-weight: bold;">รายละเอียดข้อบกพร่อง:</b>
                                <table style="width: 100%; border-collapse: collapse; font-size: 18px; border: none !important;">
                                    <tr>
                                        <td>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                                 <div style="text-align: right; padding: 0px; line-height: 50px;margin-bottom:-15px">
                                    <b style="font-weight: bold; vertical-align: middle;">หัวหน้าคณะผู้ตรวจประเมิน:</b>
                                    <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" alt="" style="height: 30px; object-fit: contain; vertical-align: middle; margin-left: 5px; position: relative; top: -5px;">
                                    <b style="font-weight: bold; vertical-align: middle; margin-left: 15px;">วันที่:</b>
                                    <span style="vertical-align: middle;">...............................</span>
                                </div>
                            </td>
                        </tr>
                        <tr style="border: none;">
                            <td colspan="2" style="border: none; padding: 8px; vertical-align: top;">
                                <div style="margin-top: 10px; padding-left: 5px;">
                                    การรับทราบข้อบกพร่อง ข้าพเจ้าในฐานะที่เป็นผู้แทนของหน่วยรับรอง/หน่วยรับรอง ได้รับทราบและเห็นด้วยกับรายงานข้อบกพร่องข้างต้นแล้ว และตกลงที่จะดำเนินการวิเคราะห์หาสาเหตุของข้อบกพร่อง กำหนดแนวทางการแก้ไขและป้องกันการเกิดซ้ำ และระบุวันแล้วเสร็จ ลงในแบบฟอร์ม FCI-AS08 การเสนอแนวทางการแก้ไขข้อบกพร่องจากการตรวจประเมินหน่วยรับรอง/หน่วยรับรอง และจักจัดส่งให้สำนักงานฯ พิจารณาเป็นที่เรียบร้อยภายใน 30 วัน นับจากวันที่รับทราบรายงานข้อบกพร่องฉบับนี้
                                </div>
                                <div style="margin-top: 10px; padding-left: 5px;">
                                    หมายเหตุ: ...<br><br>
                                </div>
                                <div style="text-align: right; padding: 0px; line-height: 50px;margin-bottom:-20px">
                                    <b style="font-weight: bold; vertical-align: middle;">ผู้แทนของหน่วยรับรอง/หน่วยรับรอง:</b>
                                    <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" alt="" style="height: 30px; object-fit: contain; vertical-align: middle; margin-left: 5px; position: relative; top: -5px;">
                                    <b style="font-weight: bold; vertical-align: middle; margin-left: 15px;">วันที่:</b>
                                    <span style="vertical-align: middle;">...............................</span>
                                </div>
                            </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td colspan="2" style="border: 1px solid black; padding: 8px; vertical-align: top; height: 100px;">
                                <b style="font-weight: bold;">การตรวจสอบการดำเนินการแก้ไข:</b><br>...
                            </td>
                        </tr>
                        <tr style="border: 1px solid black;">
                            <td colspan="2" style="border: 1px solid black; padding: 8px; vertical-align: top;">
                                <b style="font-weight: bold;">ความเห็น:</b> <input type="checkbox"> ปิดข้อบกพร่อง <input type="checkbox"> อื่นๆ ...............................................................
                                <div style="margin-top: 10px;">
                                    <b style="font-weight: bold;">ผู้ตรวจสอบ:</b> .................................................................... วันที่: .................................
                                </div>
                            </td>
                        </tr>
                    </table>

                '];
                break;

            case 'cb_car_report_two_process_one':
                 $pages = ['
                 <div style="text-align:center; font-size: 23px; ">
                    <span style="padding: 10px 0; text-align: center;font-weight: bold;">รายงานการทวนสอบผลการแก้ไขข้อบกพร่อง</span><br>
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">จากการตรวจประเมิน ณ สถานประกอบการหน่วยรับรอง</span><br>
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">ในการตรวจประเมินเพื่อติดตามผลการรับรองระบบงาน ครั้งที่ 1 สาขาหน่วยรับรอง</span>
                 </div>
                  
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 65%">
                            <b>1. ชื่อหน่วยรับรอง :</b> '.$cbName.'
                        </td>
                        <td style="padding: 10px 0; font-size: 22px; width: 35%">
                            <b>คำขอเลขที่ :</b>  '.$cbAppNo.'  
                        </td>
                    </tr>
                </table>
                <b style="font-size: 22px">2. วันตรวจประเมิน : </b> '.$assessmentDate.'  <br> 
                &nbsp;&nbsp;&nbsp;&nbsp;พบข้อบกพร่อง จำนวน .....<br>
                <b style="font-size: 22px">3. วันที่รับเอกสารแจ้งแนวทางแก้ไขข้อบกพร่อง : </b> ... <br> 
                <b style="font-size: 22px">4. วันที่ทวนสอบ : </b> ... <br> 
                <b style="font-size: 22px">5. ผู้ทวนสอบ : </b> ... <br> 
                <b style="font-size: 22px">6. เอกสารที่ใช้ในการทวนสอบ </b> ... <br> 
                &nbsp;&nbsp;&nbsp;&nbsp;6.1 แนวทางการแก้ไข .....<br>
                &nbsp;&nbsp;&nbsp;&nbsp;6.2 หลักฐานการแก้ไข .....<br>
                <b style="font-size: 22px">7. ความเห็นของคณะผู้ตรวจประเมิน </b> ... <br> 
                &nbsp;&nbsp;&nbsp;&nbsp;7.1 แนวทางการแก้ไข .....<br>
                &nbsp;&nbsp;&nbsp;&nbsp;7.2 หลักฐานการแก้ไข .....<br>

                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                        <tbody>
                            <tr>
                                <!-- Column 1 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameOne.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionOne.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
                                    </div>
                                </td>
                                <!-- Column 2 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameTwo.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionTwo.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
                                    </div>
                                </td>
                                <!-- Column 3 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameThree.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionThree.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    


                 '];
                break;


            case 'cb_final_report_process_one':

                 $pages = [
                    '
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px">
                        <tr>
                            <td colspan="3" style="padding: 10px 0; text-align: center; font-size: 24px; font-weight: bold;">
                                รายงานการตรวจประเมิน ณ สถานประกอบการ
                            </td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="width: 18%; padding: 5px 8px; vertical-align: top;"><b>1. หน่วยรับรอง</b> :</td>
                            <td style="width: 77%; padding: 5px 8px; vertical-align: top;">'.$cbName.'</td>
                        </tr>
                    </table>
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="padding: 5px 8px; vertical-align: top;width: 25%;"><b>2. ที่ตั้งสำนักงานใหญ่</b> :</td>
                            <td style="padding: 5px 8px; vertical-align: top;">
                                '.$cbHqAddress.'<br>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="width: 50%;">โทรศัพท์ : '.$telephone.'</td>
                                        <td style="width: 50%;">โทรสาร : '.$fax.'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                          <tr >
                                <td style="padding: 5px 8px 5px 22px; vertical-align: top; width: 25%;"><b>ที่ตั้งสำนักงานสาขา</b>:</td>
                                <td style="padding: 5px 8px; vertical-align: top;">
                                    '.$cbLocalAddress.'<br>
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                        <tr>
                                            <td style="width: 50%;">โทรศัพท์ : '.$localTelephone.'</td>
                                            <td style="width: 50%;">โทรสาร : '.$localFax.'</td>
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
                                        <td style="width: 50%; padding: 2px;"><input type="checkbox"> การตรวจประเมินรับรองครั้งแรก</td>
                                        <td style="width: 50%; padding: 2px;"><input type="checkbox"> การตรวจติดตามผลครั้งที่ 1</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 2px;"><input type="checkbox"> การตรวจประเมินเพื่อต่ออายุการรับรอง</td>
                                        <td style="width: 50%; padding: 2px;"><input type="checkbox"> อื่น ๆ</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                        <tr>
                            <td style="width: 32%; padding: 5px 8px; vertical-align: top;width:180px"><b>4. สาขาและขอบข่ายการรับรอง</b> :</td>
                            <td style="width: 65%; padding: 5px 8px; vertical-align: top;"> รายละเอียด ดังเอกสารแนบ 1</td>
                        </tr>
                    </table>
                    <b style="font-size: 22px">5. เกณฑ์การตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;(1) ...<br>
                    &nbsp;&nbsp;&nbsp;(2) ...<br>
                    &nbsp;&nbsp;&nbsp;(3) ...<br>
                    
                    <b style="font-size: 22px">6. วันที่ตรวจประเมิน</b> : &nbsp;&nbsp;&nbsp; '.$assessmentDate.'<br>
                    <b style="font-size: 22px">7. คณะผู้ตรวจประเมิน</b><br>
                    '.$auditorsHtml.'
                    <b style="font-size: 22px">8. ผู้แทนหน่วยรับรอง</b><br>
                    '.$representativesHtml.'
                    <b style="font-size: 22px">9. เอกสารอ้างอิงที่ใช้ในตรวจประเมิน</b> : &nbsp;&nbsp;&nbsp;'.$formattedReviewDate.'<br>
                    <b style="font-size: 22px">10. รายละเอียดการตรวจประเมิน</b><br>
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;10.1. ความเป็นมา</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บริษัท...

                ','
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;10.2. กระบวนการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คณะผู้ตรวจประเมิน... </b><br>
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;10.3. ประเด็นการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;การเปลี่ยนแปลงที่ผ่านมา </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;โครงสร้างองค์การ.... </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ประเด็นที่สำคัญ ได้แก่ </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. การชี้บงความเสี่ยง.... </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. ผู้ตรวจ.... </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. ผู้จัดการวิชาการ.... </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. การสุ่มแฟ้มงานตรวจ....
                ','

                     <div>
                        &nbsp;&nbsp;<b> 10.4 รายละเอียดผลการตรวจประเมิน</b><br>
                        <table class="table-bordered" style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px; margin-left: -7px;">
                            <thead>
                                <tr>
                                    <th style="width: 220px; border: 1px solid black; padding: 2px 8px; text-align: left; font-weight: bold;">เกณฑ์ที่ใช้ในการตรวจประเมิน</th>
                                    <th style="width: 10px; text-align: center; border: 1px solid black; padding: 2px 4px; font-weight: bold;">รายการที่ตรวจ</th>
                                    <th style="width: 30px; border: 1px solid black; padding: 2px 4px; text-align: center; font-weight: bold;">ผลการตรวจประเมิน</th>
                                    <th style="width: 100px; border: 1px solid black; padding: 2px 4px; text-align: center; font-weight: bold;">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" style="font-weight: bold; background-color: #f9fafb; border: 1px solid black; padding: 2px 8px;">มอก. 17020-2556 และ ILAC-P15: 05/2020</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 4.1 ความเป็นกลางและความเป็นอิสระ</td>
                                    <td style="width: 30px; text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 4.2 การรักษาความลับ</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 5.1 คุณลักษณะที่ต้องการด้านการบริหาร</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 5.2 องค์กรและการบริหาร</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 6.1 บุคลากร</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 6.2 สิ่งอำนวยความสะดวกและเครื่องมือ</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 6.3 การจ้างเหมาช่วง</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 7.1 ขั้นตอนการดำเนินงาน และวิธีการตรวจ</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 7.2 การจัดการตัวอย่างและรายการที่ตรวจ</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 7.3 บันทึกผลการตรวจ</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 7.4 ใบรายงานผลการตรวจและใบรับรองการตรวจ</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 7.5 การร้องเรียนและการอุทธรณ์</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 7.6 กระบวนการร้องเรียนและการอุทธรณ์</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.1 ทางเลือก</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.2 เอกสารระบบบริหารงาน (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.3 การควบคุมเอกสาร (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.4 การควบคุมบันทึก (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.5 การทบทวนระบบบริหารงาน (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.6 การประเมินภายใน (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.7 การปฏิบัติการแก้ไข (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid black; padding: 2px 8px;">ข้อ 8.8 การปฏิบัติการป้องกัน (ทางเลือก A)</td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; background-color: #f9fafb; border: 1px solid black; padding: 2px 8px;"><b>หลักเกณฑ์ วิธีการและเงื่อนไขการรับรองหน่วยรับรอง พ.ศ. 2564</b></td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; background-color: #f9fafb; border: 1px solid black; padding: 2px 8px;"><b>กฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน</b></td>
                                    <td style="text-align: center; vertical-align: middle; border: 1px solid black; padding: 2px 4px;"><input type="checkbox"></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 2px 4px;">สอดคล้อง</td>
                                    <td style="border: 1px solid black; padding: 2px 4px;"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
               

                ','

                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;10.5. ข้อสังเกต</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) ควรพิจารณาทบทวน..... </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) ควรพิจารณาทบทวน.... </b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) ควรพิจารณาทบทวน.... </b><br>
                    <b style="font-size: 22px">11. สรุปผลการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ระบบการบริหารงานคุณภาพ..... </b><br>
                    <b style="font-size: 22px">12. ความเห็น/ข้อเสนอแนะของคณะผู้ตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หน่วยรับรองมีระบบการบริหารงานส่วนใหญ่.....</b><br><br><br><br><br><br>

                    <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                        <tbody>
                            <tr>
                                <!-- Column 1 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" alt="ลายเซ็นต์ นางสาวฮาริสรา คล้ายจุ้ย" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameOne.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionOne.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
                                    </div>
                                </td>
                                <!-- Column 2 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" alt="ลายเซ็นต์ นางสาวเสาวลักษณ์ สินสถาพร" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameTwo.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionTwo.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
                                    </div>
                                </td>
                                <!-- Column 3 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" alt="ลายเซ็นต์ นายวีระศักดิ์ เพ็งหลัง" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameThree.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionThree.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                   
                '];
                break;

            case 'cb_car_report_one_process_two':
                 $pages = ['<h1>เทมเพลตสำหรับ Car Report One, Process Two</h1><p>กรุณาใส่เนื้อหา...</p>'];
                break;

            case 'cb_car_report_two_process_two':
                $pages = ['
                 <div style="text-align:center; font-size: 23px; ">
                    <span style="padding: 10px 0; text-align: center;font-weight: bold;">รายงานการทวนสอบผลการแก้ไขข้อบกพร่อง</span><br>
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">จากการตรวจประเมิน ณ สถานประกอบการหน่วยรับรอง</span><br>
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">ในการตรวจประเมินเพื่อติดตามผลการรับรองระบบงาน ครั้งที่ 1 สาขาหน่วยรับรอง</span>
                 </div>
                  
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 65%">
                            <b>1. ชื่อหน่วยรับรอง :</b> '.$cbName.'
                        </td>
                        <td style="padding: 10px 0; font-size: 22px; width: 35%">
                            <b>คำขอเลขที่ :</b>  '.$cbAppNo.'  
                        </td>
                    </tr>
                </table>
                <b style="font-size: 22px">2. วันตรวจประเมิน : </b> '.$assessmentDate.'  <br> 
                &nbsp;&nbsp;&nbsp;&nbsp;พบข้อบกพร่อง จำนวน .....<br>
                <b style="font-size: 22px">3. วันที่รับเอกสารแจ้งแนวทางแก้ไขข้อบกพร่อง : </b> ... <br> 
                <b style="font-size: 22px">4. วันที่ทวนสอบ : </b> ... <br> 
                <b style="font-size: 22px">5. ผู้ทวนสอบ : </b> ... <br> 
                <b style="font-size: 22px">6. เอกสารที่ใช้ในการทวนสอบ </b> ... <br> 
                &nbsp;&nbsp;&nbsp;&nbsp;6.1 แนวทางการแก้ไข .....<br>
                &nbsp;&nbsp;&nbsp;&nbsp;6.2 หลักฐานการแก้ไข .....<br>
                <b style="font-size: 22px">7. ความเห็นของคณะผู้ตรวจประเมิน </b> ... <br> 
                &nbsp;&nbsp;&nbsp;&nbsp;7.1 แนวทางการแก้ไข .....<br>
                &nbsp;&nbsp;&nbsp;&nbsp;7.2 หลักฐานการแก้ไข .....<br>

                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                        <tbody>
                            <tr>
                                <!-- Column 1 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameOne.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionOne.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
                                    </div>
                                </td>
                                <!-- Column 2 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameTwo.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionTwo.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
                                    </div>
                                </td>
                                <!-- Column 3 -->
                                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">('.$finalReportProcessOneSignerNameThree.')</p>
                                        <p style="margin: 0;">'.$finalReportProcessOneSignerPositionThree.'</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                 '];
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
        return response()->json([
            'pages' => $pages,
            'status' => $savedReport
        ]);
    }

    public function saveHtml(Request $request)
    {

        // dd($request->input('assessmentId'));
        // 1. ตรวจสอบข้อมูลที่ส่งมา
        $validator = Validator::make($request->all(), [
            'html_content' => 'required|string',
            'assessmentId' => 'required|integer',
            'templateType' => 'required|string',
            'status'       => 'required|string',
            'signers'      => 'nullable|array' // << เพิ่มการตรวจสอบ signers (เป็นค่าว่างได้)
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'ข้อมูลไม่ครบถ้วน', 'errors' => $validator->errors()], 422);
        }

        // 2. รับข้อมูลจาก Request
        $htmlContent = $request->input('html_content');        
        $assessmentId = $request->input('assessmentId');
        $reportType = $request->input('templateType');
        $status = $request->input('status');
        $signers = $request->input('signers', []); // << รับข้อมูล signers (ถ้าไม่มีให้เป็น array ว่าง)
        $certiCBSaveAssessment = CertiCBSaveAssessment::find($assessmentId);



       

        // 3. แปลงสัญลักษณ์ checkbox กลับเป็น HTML (หากจำเป็น)
        // หมายเหตุ: หาก Blade ส่ง <input> มาโดยตรง บรรทัดนี้อาจไม่จำเป็น แต่ใส่ไว้เพื่อความปลอดภัย
        $htmlContent = str_replace('☑', '<input type="checkbox" checked="checked">', $htmlContent);
        $htmlContent = str_replace('☐', '<input type="checkbox">', $htmlContent);

        try {
            // 5. บันทึกหรืออัปเดตข้อมูลด้วย updateOrCreate
            CbReportTemplate::updateOrCreate(
                [
                    'cb_assessment_id' => $assessmentId,
                    'report_type'      => $reportType,
                ],
                [
                    'template' => $htmlContent, // บันทึก HTML ดิบลงไปตรงๆ
                    'status'   => $status,
                    'signers'  => json_encode($signers) // << บันทึกข้อมูลผู้ลงนามเป็น JSON
                ]
            );

            // if($reportType == "cb_final_report_process_one" || "cb_car_report_two_process_one" )
            // {
                $report = CbReportTemplate::where('cb_assessment_id',$assessmentId)->where('report_type',$reportType)->first();


                if($reportType == "cb_final_report_process_one")
                {
                    $this->manageSinging($report,$signers,"cb_final_report_process_one",1,$assessmentId);
                }
                else if($reportType == "cb_car_report_two_process_one")
                {
                    $this->manageSinging($report,$signers,"cb_car_report_two_process_one",2,$assessmentId);
                }
                else if($reportType == "cb_final_report_process_two")
                {
                    $this->manageSinging($report,$signers,"cb_final_report_process_two",1,$assessmentId);
                }
                else if($reportType == "cb_car_report_two_process_two")
                {
                    $this->manageSinging($report,$signers,"cb_car_report_two_process_two",2,$assessmentId);
                }


                
                if($status == "final")
                {
                    $this->set_mail($certiCBSaveAssessment,$report,"ลงนามรายงานการตรวจประเมินขั้นตอนที่1");
                }
            // }
           

            // 6. ส่งการตอบกลับเมื่อสำเร็จ
            // return response()->json(['message' => 'บันทึกรายงานสำเร็จ']);

             // ส่ง URL กลับไปใน JSON response

            $redirectUrl = url('/certify/check_certificate-cb/' . $certiCBSaveAssessment->CertiCBCostTo->token .'/show/'.  $certiCBSaveAssessment->CertiCBCostTo->id );
            return response()->json([
                'success' => true,
                'message' => 'บันทึกรายงานสำเร็จ',
                'redirect_url' => $redirectUrl // << ส่ง URL กลับไปด้วย
            ]);

        } catch (\Exception $e) {
            // dd($e->getMessage());
            Log::error('Failed to save CbReportTemplate: ' . $e->getMessage());
            return response()->json(['message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูลลงฐานข้อมูล'], 500);
        }
    }

    public function manageSinging($report,$signers,$template,$report_type,$assessmentId)
    {
        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');


        $certiCb = $report->certiCBSaveAssessment->CertiCBCostTo;
        foreach ($signers as $key => $signer) {
            if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }

            $check = SignAssessmentReportTransaction::where('report_info_id', $report->id)
                            ->where('certificate_type',0)
                            ->where('signer_id',$signer['id'])
                            ->where('signer_order',$signer['sequence'])
                            ->where('report_type',$report_type)
                            ->where('template',$template)
                            ->first();
            if($check == null){

                SignAssessmentReportTransaction::where('report_info_id',$report->id)
                            ->where('certificate_type',0)
                            ->where('signer_order',$signer['sequence'])
                            ->where('report_type',$report_type)
                            ->where('template',$template)
                            ->delete();

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $report->id,
                    'signer_id' => $signer['id'],
                    'signer_name' => $signer['name'],
                    'signer_position' => $signer['position'],
                    'signer_order' => $signer['sequence'],
                    'view_url' => $url . '/certify/show-cb-editor/'. $template . '/' . $assessmentId,
                    'certificate_type' => 0,
                    'report_type' => $report_type,
                    'template' => $template,
                    'app_id' => $report->certiCBSaveAssessment->CertiCBCostTo->app_no,
                ]);
            }

        }
    }


    public function showEditor($templateType,$assessmentId)
    {
        // คุณอาจจะต้องดึงข้อมูล CertiIb หรือ Assessment อีกครั้งถ้าจำเป็น
        // แต่ถ้ามีแค่ ID ก็สามารถส่งไปได้เลย
        
        $certiCBSaveAssessment = CertiCBSaveAssessment::find($assessmentId);
        // dd($certiCbSaveAssessment,$certiCbSaveAssessment->CertiIBCostTo);
        return view('cbpdf.editor', [
            'templateType' => $templateType,
            'cbId' => $certiCBSaveAssessment->CertiCBCostTo->id,
            'assessmentId' => $assessmentId,
            // 'status' => 'draft' // คุณสามารถส่งค่าเริ่มต้นของ status ไปได้ด้วย
        ]);
    }

    public function set_mail($certiCBSaveAssessment,$report,$reportName) 
    {



        $signerIds = SignAssessmentReportTransaction::where('report_info_id', $report->id)
                                    ->where('certificate_type',0)
                                    ->where('report_type',1)
                                    ->pluck('signer_id')
                                    ->toArray();

        $signerEmails = Signer::whereIn('id',$signerIds)->get()->pluck('user.reg_email')->filter()->values();
        $certi_cb = $certiCBSaveAssessment->CertiCBCostTo;

 

            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');



            $data_app = [
                          'reportName'  => $reportName,
                          'certi_cb'       => $certi_cb ,
                          'url'            => $url.'certify/assessment-report-assignment' ?? '-',
                          'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                          'email_cc'       =>  !empty($mail_cc) ? $mail_cc : 'cb@tisi.mail.go.th',
                          'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                    ];

            $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                    $certi_cb->id,
                                                    (new CertiCb)->getTable(),
                                                    $certiCBSaveAssessment->id,
                                                    (new CertiCBSaveAssessment)->getTable(),
                                                    3,
                                                    $reportName,
                                                    view('mail.CB.sign_report_notification', $data_app),
                                                    $certi_cb->created_by,
                                                    $certi_cb->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                    $certi_cb->email,
                                                    !empty($mail_cc) ?  implode(',',(array)$mail_cc)  : 'cb@tisi.mail.go.th',
                                                    !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                    null
                                                    );
            // dd($data_app);
            $html = new CBSignReportNotificationMail($data_app);
            $mail =  Mail::to($signerEmails)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            } 
     
    }

    public function docReviewHtml($id)
    {
        $certiCb = CertiCb::find($id);
        return view('ablonngcbeditor.editor',[
                    'templateType' => "cb_doc_review_template",
                    'certiCbId' => $certiCb->id,
                ]);  
    }

    public function saveDocReviewHtml(Request $request)
    {
        
                // 1. ตรวจสอบข้อมูลที่ส่งมา
        $validator = Validator::make($request->all(), [
            'html_content' => 'required|string',
            'certiCbId' => 'required|integer',
            'templateType' => 'required|string',
            'status'       => 'required|string',
            'signers'      => 'nullable|array' // << เพิ่มการตรวจสอบ signers (เป็นค่าว่างได้)
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'ข้อมูลไม่ครบถ้วน', 'errors' => $validator->errors()], 422);
        }

        // 2. รับข้อมูลจาก Request
        $htmlContent = $request->input('html_content');        
        $certiCbId = $request->input('certiCbId');
        $reportType = $request->input('templateType');
        $status = $request->input('status');
        $signers = $request->input('signers', []); // << รับข้อมูล signers (ถ้าไม่มีให้เป็น array ว่าง)
        $certiCb = CertiCb::find($certiCbId);

        // dd( $signers);

        // 3. แปลงสัญลักษณ์ checkbox กลับเป็น HTML (หากจำเป็น)
        // หมายเหตุ: หาก Blade ส่ง <input> มาโดยตรง บรรทัดนี้อาจไม่จำเป็น แต่ใส่ไว้เพื่อความปลอดภัย
        $htmlContent = str_replace('☑', '<input type="checkbox" checked="checked">', $htmlContent);
        $htmlContent = str_replace('☐', '<input type="checkbox">', $htmlContent);

        // dd($certiCbId, $reportType);

        // $check = CbDocReviewReport::where('app_certi_cb_id',$certiCbId)->where('report_type',$reportType)->first();
        // dd( $check);
        // if($check == null)
        // {
        //     dd("create");
        // }else{

        // }

        CbDocReviewReport::updateOrCreate(
                [
                    'app_certi_cb_id' => $certiCbId,
                    'report_type'      => $reportType,
                ],
                [
                    'template' => $htmlContent,
                    'status'   => $status,
                    'signers'  => json_encode($signers) // แปลง array ของ signers เป็น JSON string
                ]
            );
            
        if($status  == 'final'){
            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');



            foreach ($signers as $key => $signer) {
                if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                $check = SignAssessmentReportTransaction::where('report_info_id', $certiCbId)
                            ->where('certificate_type',0)
                            ->where('signer_id',$signer['id'])
                            ->where('signer_order',$signer['sequence'])
                            ->where('report_type',1)
                            ->where('template',"cb_doc_review_template")
                            ->first();
                
                if($check  == null)
                {
                // dd($check);

                SignAssessmentReportTransaction::where('report_info_id',$certiCbId)
                            ->where('certificate_type',0)
                            ->where('signer_order',$signer['sequence'])
                            ->where('report_type',1)
                            ->where('template',"cb_doc_review_template")
                            ->delete();

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $certiCbId,
                    'signer_id' => $signer['id'],
                    'signer_name' => $signer['name'],
                    'signer_position' => $signer['position'],
                    'signer_order' => $signer['sequence'],
                    'view_url' => $url . '/certify/doc-review-cb-template/'.$certiCb->id ,
                    'certificate_type' => 0,
                    'report_type' => 1,
                    'template' => "cb_doc_review_template",
                    'app_id' => $certiCb->app_no,
                ]);
                }

            }
        }

        // http://127.0.0.1:8081/certify/doc-review-cb-template/270

// http://127.0.0.1:8081/certify/check_certificate-cb/0Poizok8TYZ981uZ/show/270
        // http://127.0.0.1:8081/certify/check_certificate-cb/ARnM37bCYdQI5sJ9

        $redirectUrl = url('/certify/check_certificate-cb/' . $certiCb->token . '/show/' .$certiCb->id );
        return response()->json([
            'success' => true,
            'message' => 'บันทึกรายงานสำเร็จ',
            'redirect_url' => $redirectUrl // << ส่ง URL กลับไปด้วย
        ]);
    }

    public function loadCbDocReviewTemplate(Request $request)
    {
     
       $cbDocReviewReport=  CbDocReviewReport::where('app_certi_cb_id', $request->certiCbId)
                                ->where('report_type', $request->templateType)
                                ->first();

        // $certiCb = CertiCb::find($request->certiCbId);
        if($cbDocReviewReport !== null)
        {
            return response()->json([
                'html' => $cbDocReviewReport->template, 
                'status' => $cbDocReviewReport->status
            ]);
        }   

        $certi_cb = CertiCb::find($request->certiCbId);
        $cbName = $certi_cb->name_standard;
        $cbAppNo = $certi_cb->app_no;
        $cbHqAddress = $this->formatAddress($certi_cb);
        $telephone = !empty($certi_cb->hq_telephone) ? $certi_cb->hq_telephone : '-';
        $fax = !empty($certi_cb->hq_fax) ? $certi_cb->hq_fax : '-';

        $cbLocalAddress = $this->formatLocationAddress($certi_cb);
        $localTelephone = !empty($certi_cb->tel) ? $certi_cb->tel : '-';
        $localFax = !empty($certi_cb->tel_fax) ? $certi_cb->tel_fax : '-';


        $cbHtmlTemplate = CbHtmlTemplate::where('app_certi_cb_id',$certi_cb->id)->first();
        $htmlPages = json_decode($cbHtmlTemplate->html_pages);

        $filteredHtmlPages = [];
        foreach ($htmlPages as $pageHtml) {
            $trimmedPageHtml = trim(strip_tags($pageHtml, '<img>'));
            if (!empty($trimmedPageHtml)) {
                $filteredHtmlPages[] = $pageHtml;
            }
        }
  
        if (empty($filteredHtmlPages)) {
            return response()->json(['message' => 'No valid HTML content to export after filtering empty pages.'], 400);
        }
        $htmlPages = $filteredHtmlPages;

        // dd($htmlPages);

        // สมมติว่า $htmlPages คือ array ที่คุณ dd ออกมา

        // 1. สร้างตัวแปรว่างสำหรับเก็บ HTML ของตารางทั้งหมด
        $allDetailTable = '';

        // 2. วนลูปในแต่ละหน้าของ HTML ที่มี
        foreach ($htmlPages as $pageHtml) {
            // 3. สร้าง DOMDocument เพื่อจัดการ HTML ของหน้านั้นๆ
            $dom = new DOMDocument();
            
            // เพิ่ม meta tag เพื่อบังคับให้ DOMDocument อ่านเป็น UTF-8 (สำคัญมากสำหรับภาษาไทย)
            @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $pageHtml);
            
            $xpath = new DOMXPath($dom);

            // 4. ค้นหา <table> ทั้งหมดที่มี class "detail-table"
            $detailTables = $xpath->query('//table[contains(@class, "detail-table")]');

            // 5. วนลูปตารางที่เจอในหน้านั้นๆ
            foreach ($detailTables as $table) {
                // 6. แปลง Node ของตารางกลับเป็น HTML String แล้วนำมาต่อท้ายตัวแปรหลัก
                $allDetailTable .= $dom->saveHTML($table);
            }
        }



                // 1. ดึงข้อมูลตามที่คุณระบุ
        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id', $certi_cb->id)->first();
        $formattedReviewDate = ''; // กำหนดค่าเริ่มต้น

        // 2. ตรวจสอบว่ามีข้อมูลหรือไม่ก่อนดำเนินการต่อ
        if ($cbDocReviewAuditor) {
            $startDate = Carbon::parse($cbDocReviewAuditor->from_date);
            $endDate = Carbon::parse($cbDocReviewAuditor->to_date);

            // ฟังก์ชันแปลงเดือนเป็นภาษาไทย
            $getThaiMonth = function($month) {
                $months = [
                    'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                    'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                    'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                    'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                ];
                return $months[$month] ?? $month;
            };

            // ดึงวัน เดือน และปี
            $startDay = $startDate->day;
            $startMonth = $getThaiMonth($startDate->format('F'));
            $startYear = $startDate->year + 543;

            $endDay = $endDate->day;
            $endMonth = $getThaiMonth($endDate->format('F'));
            $endYear = $endDate->year + 543;

            // ตรวจสอบและจัดรูปแบบวันที่
            if ($startDate->equalTo($endDate)) {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear}";
            } elseif ($startMonth === $endMonth && $startYear === $endYear) {
                $formattedReviewDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
            } else {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
            }
        } else {
            // กรณีไม่พบข้อมูล
            $formattedReviewDate = '-';
        }



        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id',$certi_cb->id)->first();
      
        // 1. สร้างตัวแปรเริ่มต้น
        $auditorsHtmlString = '';
        $count = 1;

        // 2. แปลงข้อมูล JSON ให้เป็น PHP Array
        $auditorGroups = json_decode($cbDocReviewAuditor->auditors, true);

        if (is_array($auditorGroups)) {
            foreach ($auditorGroups as $group) {
                // ตรวจสอบว่ามี key ที่ต้องการครบถ้วน
                if (isset($group['temp_users']) && is_array($group['temp_users']) && isset($group['status'])) {
                    
                    // 5. ดึงชื่อสถานะ/ตำแหน่ง จาก Helper (เหมือนใน Blade)
                    $statusTitle = '';
                    $statusObject = HP::cbDocAuditorStatus($group['status']);
                    if ($statusObject && isset($statusObject->title)) {
                        $statusTitle = $statusObject->title;
                    }

                    // 6. วนลูปใน temp_users (เหมือน @foreach ที่สอง)
                    foreach ($group['temp_users'] as $userName) {
                        // 7. นำข้อมูลมาต่อกันเป็น HTML string
                        $auditorsHtmlString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {$count}) {$userName}  &nbsp;&nbsp;&nbsp;&nbsp;{$statusTitle}<br>";
                        
                        // 8. เพิ่มค่าตัวนับ
                        $count++;
                    }
                }
            }
        }

        
        $html = 
                '
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px">
                    <tr>
                        <td  style="padding: 10px 0; text-align: left; font-size: 22px; font-weight: bold;">
                            เลขที่คำขอ: '.$certi_cb->app_no.'
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-top:-20px">
                    <tr>
                        <td  style="padding: 10px 0; text-align: center; font-size: 26px; font-weight: bold;">
                            รายงานการประเมินเอกสาร
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                    <tr>
                        <td style=" padding: 5px 8px; vertical-align: top;"><b>1. ผู้ยื่นคำขอ</b> :  '.$certi_cb->name.'</td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width: 25%;"><b>2. ที่ตั้งสำนักงานใหญ่</b> :</td>
                        <td style="padding: 5px 8px; vertical-align: top;">
                            '.$cbHqAddress.'<br>
                            <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                <tr>
                                    <td style="width: 50%;">โทรศัพท์ : '.$telephone.'</td>
                                    <td style="width: 50%;">โทรสาร : '.$fax.'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                        <tr >
                            <td style="padding: 5px 8px 5px 22px; vertical-align: top; width: 25%;"><b>ที่ตั้งสำนักงานสาขา</b>:</td>
                            <td style="padding: 5px 8px; vertical-align: top;">
                                '.$cbLocalAddress.'<br>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="width: 50%;">โทรศัพท์ : '.$localTelephone.'</td>
                                        <td style="width: 50%;">โทรสาร : '.$localFax.'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                </table>
                
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width:180px"><b>3. สาขาและขอบข่ายการรับรอง</b> :</td>
                    </tr>
                </table>
                '.$allDetailTable.'

                <b style="font-size: 22px">4. เกณฑ์การตรวจประเมิน</b><br>
                &nbsp;&nbsp;&nbsp;(1) ...<br>
                &nbsp;&nbsp;&nbsp;(2) ...<br>
                &nbsp;&nbsp;&nbsp;(3) ...<br>
                
                <b style="font-size: 22px">5. วันที่ตรวจประเมิน</b> : &nbsp;&nbsp;&nbsp; '. $formattedReviewDate .'<br>
                <b style="font-size: 22px">6. คณะผู้ตรวจประเมิน</b><br>
                '.$auditorsHtmlString.'
                <b style="font-size: 22px">7. เอกสารอ้างอิงที่ใช้ในการประเมิน</b>: xxx<br>

                <b style="font-size: 22px">8. สรุปผลการประเมิน</b> : &nbsp;&nbsp;&nbsp;<br>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N คือ ไม่สอดคล้องตาม มอก.17020-2556 (ISO/IEC 17020: 2012) และ/หรือเอกสาร ILAC-P15 หรือขาดความชัดเจนในประเด็นที่สำคัญ และหน่วยรับรองต้องแก้ไขและแจ้งผลการแก้ไขให้กับ
คณะผู้ตรวจประเมินทราบก่อนตรวจประเมิน ณ สถานประกอบการของหน่วยรับรอง<br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I  คือ	ต้องจัดส่งข้อมูลหรือเอกสารเพิ่มเติม <br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O คือ ข้อสังเกตซึ่งหน่วยรับรองควรแก้ไข/ปรับปรุง<br><br>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คณะผู้ตรวจประเมินได้ประเมินเอกสารคู่มือคุณภาพ เอกสารขั้นตอนการดำเนินงาน และเอกสารสนับสนุนอื่นๆ ของหน่วยรับรอง โดยอ้างอิงตามข้อกำหนดตามมาตรฐาน มอก.17020-2556 และเอกสาร ILAC-P15 แล้วมีความเห็นว่าเอกสารระบบคุณภาพการให้บริการงานตรวจ ยังมีประเด็นที่ต้องแก้ไข หรือจัดส่งข้อมูล/เอกสารเพิ่มเติม รายละเอียดดังแนบ




                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                    <tbody>
                        <tr>
                            <!-- Column 1 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                            
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่</p>
                                    </div>
                            </td>
                            <!-- Column 2 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่</p>
                                    </div>
                            </td>
                            <!-- Column 3 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ </p>
                                    </div>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            ';
        return response()->json([
            'html' => $html, 
            'status' => null
        ]);
    }

     public function loadDefaultCbDocReviewTemplate(Request $request)
    {
      
 $certi_cb = CertiCb::find($request->certiCbId);
        $cbName = $certi_cb->name_standard;
        $cbAppNo = $certi_cb->app_no;
        $cbHqAddress = $this->formatAddress($certi_cb);
        $telephone = !empty($certi_cb->hq_telephone) ? $certi_cb->hq_telephone : '-';
        $fax = !empty($certi_cb->hq_fax) ? $certi_cb->hq_fax : '-';

        $cbLocalAddress = $this->formatLocationAddress($certi_cb);
        $localTelephone = !empty($certi_cb->tel) ? $certi_cb->tel : '-';
        $localFax = !empty($certi_cb->tel_fax) ? $certi_cb->tel_fax : '-';


        $cbHtmlTemplate = CbHtmlTemplate::where('app_certi_cb_id',$certi_cb->id)->first();
        $htmlPages = json_decode($cbHtmlTemplate->html_pages);

        $filteredHtmlPages = [];
        foreach ($htmlPages as $pageHtml) {
            $trimmedPageHtml = trim(strip_tags($pageHtml, '<img>'));
            if (!empty($trimmedPageHtml)) {
                $filteredHtmlPages[] = $pageHtml;
            }
        }
  
        if (empty($filteredHtmlPages)) {
            return response()->json(['message' => 'No valid HTML content to export after filtering empty pages.'], 400);
        }
        $htmlPages = $filteredHtmlPages;

        // dd($htmlPages);

        // สมมติว่า $htmlPages คือ array ที่คุณ dd ออกมา

        // 1. สร้างตัวแปรว่างสำหรับเก็บ HTML ของตารางทั้งหมด
        $allDetailTable = '';

        // 2. วนลูปในแต่ละหน้าของ HTML ที่มี
        foreach ($htmlPages as $pageHtml) {
            // 3. สร้าง DOMDocument เพื่อจัดการ HTML ของหน้านั้นๆ
            $dom = new DOMDocument();
            
            // เพิ่ม meta tag เพื่อบังคับให้ DOMDocument อ่านเป็น UTF-8 (สำคัญมากสำหรับภาษาไทย)
            @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $pageHtml);
            
            $xpath = new DOMXPath($dom);

            // 4. ค้นหา <table> ทั้งหมดที่มี class "detail-table"
            $detailTables = $xpath->query('//table[contains(@class, "detail-table")]');

            // 5. วนลูปตารางที่เจอในหน้านั้นๆ
            foreach ($detailTables as $table) {
                // 6. แปลง Node ของตารางกลับเป็น HTML String แล้วนำมาต่อท้ายตัวแปรหลัก
                $allDetailTable .= $dom->saveHTML($table);
            }
        }



                // 1. ดึงข้อมูลตามที่คุณระบุ
        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id', $certi_cb->id)->first();
        $formattedReviewDate = ''; // กำหนดค่าเริ่มต้น

        // 2. ตรวจสอบว่ามีข้อมูลหรือไม่ก่อนดำเนินการต่อ
        if ($cbDocReviewAuditor) {
            $startDate = Carbon::parse($cbDocReviewAuditor->from_date);
            $endDate = Carbon::parse($cbDocReviewAuditor->to_date);

            // ฟังก์ชันแปลงเดือนเป็นภาษาไทย
            $getThaiMonth = function($month) {
                $months = [
                    'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                    'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                    'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                    'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                ];
                return $months[$month] ?? $month;
            };

            // ดึงวัน เดือน และปี
            $startDay = $startDate->day;
            $startMonth = $getThaiMonth($startDate->format('F'));
            $startYear = $startDate->year + 543;

            $endDay = $endDate->day;
            $endMonth = $getThaiMonth($endDate->format('F'));
            $endYear = $endDate->year + 543;

            // ตรวจสอบและจัดรูปแบบวันที่
            if ($startDate->equalTo($endDate)) {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear}";
            } elseif ($startMonth === $endMonth && $startYear === $endYear) {
                $formattedReviewDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
            } else {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
            }
        } else {
            // กรณีไม่พบข้อมูล
            $formattedReviewDate = '-';
        }



        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id',$certi_cb->id)->first();
      
        // 1. สร้างตัวแปรเริ่มต้น
        $auditorsHtmlString = '';
        $count = 1;

        // 2. แปลงข้อมูล JSON ให้เป็น PHP Array
        $auditorGroups = json_decode($cbDocReviewAuditor->auditors, true);

        if (is_array($auditorGroups)) {
            foreach ($auditorGroups as $group) {
                // ตรวจสอบว่ามี key ที่ต้องการครบถ้วน
                if (isset($group['temp_users']) && is_array($group['temp_users']) && isset($group['status'])) {
                    
                    // 5. ดึงชื่อสถานะ/ตำแหน่ง จาก Helper (เหมือนใน Blade)
                    $statusTitle = '';
                    $statusObject = HP::cbDocAuditorStatus($group['status']);
                    if ($statusObject && isset($statusObject->title)) {
                        $statusTitle = $statusObject->title;
                    }

                    // 6. วนลูปใน temp_users (เหมือน @foreach ที่สอง)
                    foreach ($group['temp_users'] as $userName) {
                        // 7. นำข้อมูลมาต่อกันเป็น HTML string
                        $auditorsHtmlString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {$count}) {$userName}  &nbsp;&nbsp;&nbsp;&nbsp;{$statusTitle}<br>";
                        
                        // 8. เพิ่มค่าตัวนับ
                        $count++;
                    }
                }
            }
        }

        
        $html = 
                '
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px">
                    <tr>
                        <td  style="padding: 10px 0; text-align: left; font-size: 22px; font-weight: bold;">
                            เลขที่คำขอ: '.$certi_cb->app_no.'
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-top:-20px">
                    <tr>
                        <td  style="padding: 10px 0; text-align: center; font-size: 26px; font-weight: bold;">
                            รายงานการประเมินเอกสาร
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                    <tr>
                        <td style=" padding: 5px 8px; vertical-align: top;"><b>1. ผู้ยื่นคำขอ</b> :  '.$certi_cb->name.'</td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width: 25%;"><b>2. ที่ตั้งสำนักงานใหญ่</b> :</td>
                        <td style="padding: 5px 8px; vertical-align: top;">
                            '.$cbHqAddress.'<br>
                            <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                <tr>
                                    <td style="width: 50%;">โทรศัพท์ : '.$telephone.'</td>
                                    <td style="width: 50%;">โทรสาร : '.$fax.'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                        <tr >
                            <td style="padding: 5px 8px 5px 22px; vertical-align: top; width: 25%;"><b>ที่ตั้งสำนักงานสาขา</b>:</td>
                            <td style="padding: 5px 8px; vertical-align: top;">
                                '.$cbLocalAddress.'<br>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="width: 50%;">โทรศัพท์ : '.$localTelephone.'</td>
                                        <td style="width: 50%;">โทรสาร : '.$localFax.'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                </table>
                
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:-7px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width:180px"><b>3. สาขาและขอบข่ายการรับรอง</b> :</td>
                    </tr>
                </table>
                '.$allDetailTable.'

                <b style="font-size: 22px">4. เกณฑ์การตรวจประเมิน</b><br>
                &nbsp;&nbsp;&nbsp;(1) ...<br>
                &nbsp;&nbsp;&nbsp;(2) ...<br>
                &nbsp;&nbsp;&nbsp;(3) ...<br>
                
                <b style="font-size: 22px">5. วันที่ตรวจประเมิน</b> : &nbsp;&nbsp;&nbsp; '. $formattedReviewDate .'<br>
                <b style="font-size: 22px">6. คณะผู้ตรวจประเมิน</b><br>
                '.$auditorsHtmlString.'
                <b style="font-size: 22px">7. เอกสารอ้างอิงที่ใช้ในการประเมิน</b>: xxx<br>

                <b style="font-size: 22px">8. สรุปผลการประเมิน</b> : &nbsp;&nbsp;&nbsp;<br>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N คือ ไม่สอดคล้องตาม มอก.17020-2556 (ISO/IEC 17020: 2012) และ/หรือเอกสาร ILAC-P15 หรือขาดความชัดเจนในประเด็นที่สำคัญ และหน่วยรับรองต้องแก้ไขและแจ้งผลการแก้ไขให้กับ
คณะผู้ตรวจประเมินทราบก่อนตรวจประเมิน ณ สถานประกอบการของหน่วยรับรอง<br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I  คือ	ต้องจัดส่งข้อมูลหรือเอกสารเพิ่มเติม <br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O คือ ข้อสังเกตซึ่งหน่วยรับรองควรแก้ไข/ปรับปรุง<br><br>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คณะผู้ตรวจประเมินได้ประเมินเอกสารคู่มือคุณภาพ เอกสารขั้นตอนการดำเนินงาน และเอกสารสนับสนุนอื่นๆ ของหน่วยรับรอง โดยอ้างอิงตามข้อกำหนดตามมาตรฐาน มอก.17020-2556 และเอกสาร ILAC-P15 แล้วมีความเห็นว่าเอกสารระบบคุณภาพการให้บริการงานตรวจ ยังมีประเด็นที่ต้องแก้ไข หรือจัดส่งข้อมูล/เอกสารเพิ่มเติม รายละเอียดดังแนบ




                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                    <tbody>
                        <tr>
                            <!-- Column 1 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                            
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่</p>
                                    </div>
                            </td>
                            <!-- Column 2 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่</p>
                                    </div>
                            </td>
                            <!-- Column 3 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ </p>
                                    </div>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            ';
        return response()->json([
            'html' => $html, 
            'status' => null
        ]);
    }


    public function docAssessmentReviewHtml($cbId)
    {
    
        return view('cbpdf.editor-doc-review-assessment', [
            'templateType' => "cb-doc-review-assessment",
            'cbId' => $cbId,
            'status' => 'draft' // คุณสามารถส่งค่าเริ่มต้นของ
        ]);
    
    }

    public function downloadAssessmentReviewHtml(Request $request)
    {
    
       $cbDocReviewAssessment=  CbDocReviewAssessment::where('app_certi_cb_id', $request->cbId)
                                ->where('report_type', $request->templateType)
                                ->first();

        $certiCb = CertiCb::find($request->cbId);
        if($cbDocReviewAssessment !== null)
        {
            // ดึงข้อมูลผู้ลงนามที่อนุมัติแล้ว
            $messageRecordTransactions = MessageRecordTransaction::where('board_auditor_id', $certiCb->id)
                ->where('app_id', $certiCb->app_no)
                ->where('certificate_type', 0)
                ->where('job_type', $request->templateType)
                ->where('approval', 1)
                ->get();

                // ดึง HTML content เริ่มต้น
                $htmlContent = $cbDocReviewAssessment->template;

                // 1. สร้าง DOMDocument เพื่อจัดการ HTML
                $dom = new DOMDocument();
                // เพิ่ม meta tag เพื่อบังคับ UTF-8 ป้องกันภาษาเพี้ยน
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $htmlContent);
                $xpath = new DOMXPath($dom);
                
                // --- ส่วนที่เพิ่มเข้ามา ---
                // นับจำนวนช่องลายเซ็นทั้งหมดที่มีใน Template จาก attribute 'data-signer-id'
                $totalSignerSlots = $xpath->query("//div[@data-signer-id]")->length;

                // นับจำนวนผู้ที่อนุมัติแล้ว
                $approvedSignerCount = $messageRecordTransactions->count();
                // --- สิ้นสุดส่วนที่เพิ่มเข้ามา ---


                // 2. วนลูปเฉพาะผู้ลงนามที่อนุมัติแล้ว
                foreach ($messageRecordTransactions as $transaction) {
                    $signerId = $transaction->signer_id;

                    // 3. ค้นหา Signer และดึง Path ของลายเซ็น
                    $signer = Signer::find($signerId);
                    
                    // ตรวจสอบให้แน่ใจว่าพบ signer และมีไฟล์แนบ
                    if ($signer && $signer->AttachFileAttachTo) {
                        // สมมติว่า $this->getSignature() คืนค่า path ที่ถูกต้อง
                        $signaturePath = $this->getSignature($signer->AttachFileAttachTo);
                        
                        // สร้าง URL ที่สมบูรณ์สำหรับรูปภาพ
                        $fullSignatureUrl = asset($signaturePath);

                        // 4. (แก้ไข) ค้นหา div ของผู้ลงนามใน HTML ทั้งหมด (ไม่ใช่แค่ตัวแรก)
                        $signerDivNodes = $xpath->query("//div[@data-signer-id='{$signerId}']");

                        // 5. (แก้ไข) วนลูป div ทั้งหมดที่เจอสำหรับ signerId นี้
                        foreach ($signerDivNodes as $signerDivNode) {
                            if ($signerDivNode) {
                                // 6. ค้นหา <img> ที่อยู่ภายใน td แม่ของ div นั้น
                                $tdNode = $signerDivNode->parentNode;
                                $imgNode = $xpath->query('.//img', $tdNode)->item(0);

                                if ($imgNode) {
                                    // 7. อัปเดต src ของ <img> ด้วย URL ของลายเซ็น
                                    $imgNode->setAttribute('src', $fullSignatureUrl);
                                }
                            }
                        }
                    }
                }

                // 8. บันทึก HTML ที่แก้ไขแล้วกลับเป็น String
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $updatedHtmlContent = '';
                foreach ($bodyNode->childNodes as $child) {
                    $updatedHtmlContent .= $dom->saveHTML($child);
                }

                // ตรวจสอบว่าจำนวนช่องลายเซ็น > 0 และจำนวนที่อนุมัติเท่ากับจำนวนช่องทั้งหมด
                if ($totalSignerSlots > 0 && $totalSignerSlots === $approvedSignerCount) {
                    // ถ้าเท่ากัน ให้เพิ่ม 'all_signed' => true เข้าไปใน response
                    return response()->json([
                                'html' => $updatedHtmlContent, 
                                'status' => $cbDocReviewAssessment->status,
                                'all_signed' => true
                            ]);
                    $response['all_signed'] = true;
                }else{
                        return response()->json([
                                'html' => $updatedHtmlContent, 
                                'status' => $cbDocReviewAssessment->status,
                                'all_signed' => false
                            ]);
                }

      

            // return response()->json([
            //     'html' => $updatedHtmlContent, 
            //     'status' => $cbDocReviewAssessment->status
            // ]);
        }   

        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id',$request->cbId)->first();
      
        // 1. สร้างตัวแปรเริ่มต้น
        $auditorsHtmlString = '';
        $count = 1;

        // 2. แปลงข้อมูล JSON ให้เป็น PHP Array
        $auditorGroups = json_decode($cbDocReviewAuditor->auditors, true);


        // 3. ตรวจสอบว่าการแปลงสำเร็จและข้อมูลเป็น Array
        if (is_array($auditorGroups)) {

            // 4. วนลูปหลัก (เหมือน @foreach แรก)
            foreach ($auditorGroups as $group) {
                // ตรวจสอบว่ามี key ที่ต้องการครบถ้วน
                if (isset($group['temp_users']) && is_array($group['temp_users']) && isset($group['status'])) {
                    
                    // 5. ดึงชื่อสถานะ/ตำแหน่ง จาก Helper (เหมือนใน Blade)
                    $statusTitle = '';
                    $statusObject = HP::cbDocAuditorStatus($group['status']);
                    if ($statusObject && isset($statusObject->title)) {
                        $statusTitle = $statusObject->title;
                    }

                    // 6. วนลูปใน temp_users (เหมือน @foreach ที่สอง)
                    foreach ($group['temp_users'] as $userName) {
                        // 7. นำข้อมูลมาต่อกันเป็น HTML string
                        $auditorsHtmlString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$count}) {$userName}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$statusTitle}<br>";
                        
                        // 8. เพิ่มค่าตัวนับ
                        $count++;
                    }
                }
            }
        }


        $krut = url('') . '/images/krut.jpg';
                $pages = ['
                        <div style="display: flex; align-items: center; margin-bottom: 0; font-size: 18px;">
                            
                            <div style="width: 250px; flex-shrink: 0;"> 
                                <img src="'.$krut.'" alt="Logo" style="width: 130px; display: block;">
                            </div>

                            <div style="text-align: left; font-size: 34px; font-weight: bold; padding-left: 10px; padding-bottom: 5px;">
                                บันทึกข้อความ
                            </div>
                        </div>


                        <table style="width: 100%; border-collapse: collapse; font-size: 18px;  border-spacing: 0;margin-top:20px">
                            <tr>
                                <td style="font-size: 22px; padding: 5px 0;">
                                    <div style="display: flex; align-items: baseline;">
                                        <div style="display: flex; align-items: baseline; width: 60%;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">ส่วนราชการ</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;สก. รร.</span>
                                        </div>
                                        <div style="display: flex; align-items: baseline; width: 40%; margin-left: 20px;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">โทร</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;1430</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 22px; padding: 5px 0;">
                                    <div style="display: flex; align-items: baseline;">
                                        <div style="display: flex; align-items: baseline; width: 50%;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">ที่</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;</span>
                                        </div>
                                        <div style="display: flex; align-items: baseline; width: 50%; margin-left: 20px;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">วันที่</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;'.HP::formatDateThaiFullNumThai($certiCb->created_at).'</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 22px; display: flex; align-items: baseline; padding: 5px 0;">
                                    <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">เรื่อง</span>
                                    <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;การแต่งตั้งคณะผู้ตรวจประเมินเอกสาร เพื่อการรับรองระบบงาน'.$certiCb->purposeType->name.'ของหน่วยรับรอง'.$certiCb->name.' (คำขอเลขที่ '.$certiCb->app_no.')</span>
                                </td>
                            </tr>
                        </table>

                        <div stye="line-height:5px;font-size:8px">&nbsp;</div>
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">เรียน ผอ.สก. ผ่าน ผก.รร.</span><br><br>
                        
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">1. เรื่องเดิม</span><br>
                        <span style="line-height:20px;font-size:22px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$certiCb->name.' ได้ยื่นคำขอการรับรองระบบงานหน่วยรับรอง ตามมาตรฐานเลขที่ มอก. 17020-2556  ต่อ สก. ผ่านระบบ e-Accreditation ตามคำขอเลขที่ '.$certiCb->app_no.' เมื่อวันที่ '.HP::formatDateThaiFullNumThai(Carbon::now()).'<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.1)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.2)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.3)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.4)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.5)</span>

                        <br>

                        <span style="line-height:20px;font-size:22px;margin:top:20px;font-weight: bold;">2. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1 พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ 4 มีนาคม 2551) มาตรา 28 วรรค 2 บัญญัติว่า “การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด”</span> <br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.2 หลักเกณฑ์ วิธีการ และเงื่อนไขการตรวจประเมินหน่วยรับรอง พ.ศ. 2564 ข้อ 6.1.2.1 (1) ระบุว่า “การแต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าผู้ตรวจประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม” และข้อ 6.1.2.1 (2) ระบุว่า “คณะผู้ตรวจประเมินจะทบทวนและประเมินเอกสารต่าง ๆ ของหน่วยรับรอง ตรวจประเมิน ความสามารถและประสิทธิผลของการดำเนินงานของหน่วยรับรอง รวมทั้งสังเกตการปฏิบัติงานตามมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง”</span> <br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.3 คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทน เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สั่ง ณ วันที่ ๑๓ พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมินตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</span> <br>
                    ','
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">3. สาระสำคัญและข้อเท็จจริง</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการตรวจประเมินหน่วยรับรอง สำนักงานจะตรวจประเมินเอกสารเพื่อพิจารณาถึงความครบถ้วนและความสอดคล้องของระบบการบริหารงานตามมาตรฐานด้านการตรวจสอบและรับรอง และหลักเกณฑ์ วิธีการและเงื่อนไขที่เกี่ยวข้อง</span> <br>
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">4. การดำเนินการ</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รร. ได้สรรหาคณะผู้ตรวจประเมินประกอบด้วย หัวหน้าผู้ตรวจประเมิน เพื่อดำเนินการตรวจประเมินเอกสารของหน่วยรับรอง '.$certiCb->name.' ดังนี้
	                        <br>'. $auditorsHtmlString .'
                        </span> <br>
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">5. ข้อปัญหาอุปสรรค</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการตรวจประเมินหน่วยรับรอง สำนักงานจะตรวจประเมินเอกสารเพื่อพิจารณาถึงความครบถ้วนและความสอดคล้องของระบบการบริหารงานตามมาตรฐานด้านการตรวจสอบและรับรอง และหลักเกณฑ์ วิธีการและเงื่อนไขที่เกี่ยวข้อง</span> <br>

                        <span style="line-height:20px;font-size:22px;font-weight: bold;">6. ข้อพิจารณา</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดนำเรียน ลมอ. พิจารณาลงนามอนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเอกสาร เพื่อขอการรับรองระบบงานของหน่วยรับรองของ '.$certiCb->name.'</span> <br>

                        <span style="line-height:20px;font-size:22px;font-weight: bold;">7. ข้อเสนอ</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนำเรียน ลมอ. เพื่ออนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเอกสารเพื่อขอการรับรองระบบงานของหน่วยรับรองของ '.$certiCb->name.' รายละเอียดดังกล่าวข้างต้น</span> <br>

                        <br>
                        <br>
                     
                        <span style="line-height:20px;font-size:22px;">เรียน  ลมอ.</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สก. ได้ตรวจสอบรายละเอียดการดำเนินการสำหรับการแต่งตั้งคณะผู้ตรวจประเมินดังกล่าวแล้ว สรุปว่าเป็นไปตามหลักเกณฑ์ที่กำหนด
	                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดอนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเอกสาร เพื่อการรับรองระบบงานครั้งแรกของหน่วยรับรอง '.$certiCb->name.' ดังกล่าวข้างต้น</span> <br>

                        <br>
                        <br>
                            
                        <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                            <tbody>
                                <tr>
                                    <!-- Column 1 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <!-- Column 2 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                          <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <!-- Column 3 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                '];
  
        return response()->json([
            'pages' => $pages, 
            'status' => null
        ]);
    }


    public function downloadDefaultDocAssessmentReviewHtml(Request $request)
    {
    
       $cbDocReviewAssessment=  CbDocReviewAssessment::where('app_certi_cb_id', $request->cbId)
                                ->where('report_type', $request->templateType)
                                ->first();

        $certiCb = CertiCb::find($request->cbId);
  

        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id',$request->cbId)->first();
      
        // 1. สร้างตัวแปรเริ่มต้น
        $auditorsHtmlString = '';
        $count = 1;

        // 2. แปลงข้อมูล JSON ให้เป็น PHP Array
        $auditorGroups = json_decode($cbDocReviewAuditor->auditors, true);


        // 3. ตรวจสอบว่าการแปลงสำเร็จและข้อมูลเป็น Array
        if (is_array($auditorGroups)) {
            // เพิ่ม <br> เริ่มต้นถ้ามีข้อมูล
            // if (!empty($auditorGroups)) {
            //     $auditorsHtmlString .= '<br>';
            // }

            // 4. วนลูปหลัก (เหมือน @foreach แรก)
            foreach ($auditorGroups as $group) {
                // ตรวจสอบว่ามี key ที่ต้องการครบถ้วน
                if (isset($group['temp_users']) && is_array($group['temp_users']) && isset($group['status'])) {
                    
                    // 5. ดึงชื่อสถานะ/ตำแหน่ง จาก Helper (เหมือนใน Blade)
                    $statusTitle = '';
                    $statusObject = HP::cbDocAuditorStatus($group['status']);
                    if ($statusObject && isset($statusObject->title)) {
                        $statusTitle = $statusObject->title;
                    }

                    // 6. วนลูปใน temp_users (เหมือน @foreach ที่สอง)
                    foreach ($group['temp_users'] as $userName) {
                        // 7. นำข้อมูลมาต่อกันเป็น HTML string
                        $auditorsHtmlString .= "{$count}) {$userName}  &nbsp;&nbsp;&nbsp;&nbsp;{$statusTitle}<br>";
                        
                        // 8. เพิ่มค่าตัวนับ
                        $count++;
                    }
                }
            }
        }


        $krut = url('') . '/images/krut.jpg';
                $pages = ['
                        <div style="display: flex; align-items: center; margin-bottom: 0; font-size: 18px;">
                            
                            <div style="width: 250px; flex-shrink: 0;"> 
                                <img src="'.$krut.'" alt="Logo" style="width: 130px; display: block;">
                            </div>

                            <div style="text-align: left; font-size: 34px; font-weight: bold; padding-left: 10px; padding-bottom: 5px;">
                                บันทึกข้อความ
                            </div>
                        </div>


                        <table style="width: 100%; border-collapse: collapse; font-size: 18px;  border-spacing: 0;margin-top:20px">
                            <tr>
                                <td style="font-size: 22px; padding: 5px 0;">
                                    <div style="display: flex; align-items: baseline;">
                                        <div style="display: flex; align-items: baseline; width: 60%;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">ส่วนราชการ</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;สก. รร.</span>
                                        </div>
                                        <div style="display: flex; align-items: baseline; width: 40%; margin-left: 20px;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">โทร</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;1430</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 22px; padding: 5px 0;">
                                    <div style="display: flex; align-items: baseline;">
                                        <div style="display: flex; align-items: baseline; width: 50%;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">ที่</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;</span>
                                        </div>
                                        <div style="display: flex; align-items: baseline; width: 50%; margin-left: 20px;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">วันที่</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;'.HP::formatDateThaiFullNumThai($certiCb->created_at).'</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 22px; display: flex; align-items: baseline; padding: 5px 0;">
                                    <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">เรื่อง</span>
                                    <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;การแต่งตั้งคณะผู้ตรวจประเมินเอกสาร เพื่อการรับรองระบบงาน'.$certiCb->purposeType->name.'ของหน่วยรับรอง'.$certiCb->name.' (คำขอเลขที่ '.$certiCb->app_no.')</span>
                                </td>
                            </tr>
                        </table>

                        <div stye="line-height:5px;font-size:8px">&nbsp;</div>

                        <span style="line-height:20px;font-size:22px;font-weight: bold;">1. เรื่องเดิม</span><br>
                        <span style="line-height:20px;font-size:22px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$certiCb->name.' ได้ยื่นคำขอการรับรองระบบงานหน่วยรับรอง ตามมาตรฐานเลขที่ มอก. 17020-2556  ต่อ สก. ผ่านระบบ e-Accreditation ตามคำขอเลขที่ '.$certiCb->app_no.' เมื่อวันที่ '.HP::formatDateThaiFullNumThai(Carbon::now()).'<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.1)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.2)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.3)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.4)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1.5)</span>

                        <br>

                        <span style="line-height:20px;font-size:22px;margin:top:20px;font-weight: bold;">2. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1 พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ 4 มีนาคม 2551) มาตรา 28 วรรค 2 บัญญัติว่า “การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด”</span> <br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.2 หลักเกณฑ์ วิธีการ และเงื่อนไขการตรวจประเมินหน่วยรับรอง พ.ศ. 2564 ข้อ 6.1.2.1 (1) ระบุว่า “การแต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าผู้ตรวจประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม” และข้อ 6.1.2.1 (2) ระบุว่า “คณะผู้ตรวจประเมินจะทบทวนและประเมินเอกสารต่าง ๆ ของหน่วยรับรอง ตรวจประเมิน ความสามารถและประสิทธิผลของการดำเนินงานของหน่วยรับรอง รวมทั้งสังเกตการปฏิบัติงานตามมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง”</span> <br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.3 คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทน เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สั่ง ณ วันที่ ๑๓ พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมินตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</span> <br>
                    ','
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">3. สาระสำคัญและข้อเท็จจริง</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการตรวจประเมินหน่วยรับรอง สำนักงานจะตรวจประเมินเอกสารเพื่อพิจารณาถึงความครบถ้วนและความสอดคล้องของระบบการบริหารงานตามมาตรฐานด้านการตรวจสอบและรับรอง และหลักเกณฑ์ วิธีการและเงื่อนไขที่เกี่ยวข้อง</span> <br>
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">4. การดำเนินการ</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รร. ได้สรรหาคณะผู้ตรวจประเมินประกอบด้วย หัวหน้าผู้ตรวจประเมิน เพื่อดำเนินการตรวจประเมินเอกสารของหน่วยรับรอง '.$certiCb->name.' ดังนี้
	                        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $auditorsHtmlString .'
                        </span> <br>
                        <span style="line-height:20px;font-size:22px;font-weight: bold;">5. ข้อปัญหาอุปสรรค</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการตรวจประเมินหน่วยรับรอง สำนักงานจะตรวจประเมินเอกสารเพื่อพิจารณาถึงความครบถ้วนและความสอดคล้องของระบบการบริหารงานตามมาตรฐานด้านการตรวจสอบและรับรอง และหลักเกณฑ์ วิธีการและเงื่อนไขที่เกี่ยวข้อง</span> <br>

                        <span style="line-height:20px;font-size:22px;font-weight: bold;">6. ข้อพิจารณา</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เพื่อโปรดนำเรียน ลมอ. พิจารณาลงนามอนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเอกสาร เพื่อขอการรับรองระบบงานของหน่วยรับรองของ '.$certiCb->name.'</span> <br>

                        <span style="line-height:20px;font-size:22px;font-weight: bold;">7. ข้อเสนอ</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนำเรียน ลมอ. เพื่ออนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเอกสารเพื่อขอการรับรองระบบงานของหน่วยรับรองของ '.$certiCb->name.' รายละเอียดดังกล่าวข้างต้น</span> <br>

                        <br>
                        <br>
                     
                        <span style="line-height:20px;font-size:22px;">เรียน  ลมอ.</span><br>
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สก. ได้ตรวจสอบรายละเอียดการดำเนินการสำหรับการแต่งตั้งคณะผู้ตรวจประเมินดังกล่าวแล้ว สรุปว่าเป็นไปตามหลักเกณฑ์ที่กำหนด
	                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดอนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเอกสาร เพื่อการรับรองระบบงานครั้งแรกของหน่วยรับรอง '.$certiCb->name.' ดังกล่าวข้างต้น</span> <br>

                        <br>
                        <br>
                            
                        <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                            <tbody>
                                <tr>
                                    <!-- Column 1 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <!-- Column 2 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                          <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <!-- Column 3 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                '];
  
        return response()->json([
            'pages' => $pages, 
            'status' => null
        ]);
    }

    public function saveAssessmentReviewHtml(Request $request)
    {
        // dd($request->all(),json_encode($request->input('signers', [])));
        // 1. ตรวจสอบข้อมูลที่ส่งมา
        $validator = Validator::make($request->all(), [
            'html_content' => 'required|string',
            'templateType' => 'required|string',
            'status'       => 'required|string',
            'signers'      => 'nullable|array' // << เพิ่มการตรวจสอบ signers (เป็นค่าว่างได้)
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'ข้อมูลไม่ครบถ้วน', 'errors' => $validator->errors()], 422);
        }

        // 2. รับข้อมูลจาก Request
        $htmlContent = $request->input('html_content');        
        // $assessmentId = $request->input('assessmentId');
        $reportType = $request->input('templateType');
        $status = $request->input('status');
        $signers = $request->input('signers', []); // << รับข้อมูล signers (ถ้าไม่มีให้เป็น array ว่าง)
        // $certiCbSaveAssessment = CertiIBSaveAssessment::find($assessmentId);
        $certiCb = CertiCb::find($request->input('cbId'));

        // 3. แปลงสัญลักษณ์ checkbox กลับเป็น HTML (หากจำเป็น)
        // หมายเหตุ: หาก Blade ส่ง <input> มาโดยตรง บรรทัดนี้อาจไม่จำเป็น แต่ใส่ไว้เพื่อความปลอดภัย
        $htmlContent = str_replace('☑', '<input type="checkbox" checked="checked">', $htmlContent);
        $htmlContent = str_replace('☐', '<input type="checkbox">', $htmlContent);

        // dd($reportType);
        try {
            // 5. บันทึกหรืออัปเดตข้อมูลด้วย updateOrCreate
            CbDocReviewAssessment::updateOrCreate(
                [
                    'app_certi_cb_id' => $request->input('cbId'),
                    'report_type'      => $reportType,
                ],
                [
                    'template' => $htmlContent, // บันทึก HTML ดิบลงไปตรงๆ
                    'status'   => $status,
                    'signers'  => json_encode($signers) // << บันทึกข้อมูลผู้ลงนามเป็น JSON
                ]
            );


        if($status == "final")
        {
           
            foreach ($signers as $key => $signer) {
                if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                $config = HP::getConfig();
                $url  =   !empty($config->url_center) ? $config->url_center : url('');

                $check = MessageRecordTransaction::where('board_auditor_id',$certiCb->id)
                ->where('signer_id' , $signer['id'])
                ->where('certificate_type' ,0)
                ->where('app_id' ,$certiCb->app_no)
                ->where('signature_id' , $signer['id'])
                ->where('signer_order' , $signer['sequence'])
                ->where('job_type' , $request->templateType)
                ->first();

                

                if($check == null)
                {
                 
                    MessageRecordTransaction::where('board_auditor_id',$certiCb->id)
                    ->where('signer_order',$signer['sequence'])
                    ->where('job_type',$request->templateType)
                    ->delete();

                    MessageRecordTransaction::create([
                        'board_auditor_id' => $certiCb->id,
                        'signer_id' => $signer['id'],
                        'certificate_type' => 0,
                        'app_id' => $certiCb->app_no,
                        'view_url' =>$url . '/certify/cb-doc-assessment-review-html/'. $certiCb->id  ,
                        'signature_id' => $signer['id'],
                        'is_enable' => false,
                        'show_name' => false,
                        'show_position' => false,
                        'signer_name' => $signer['name'],
                        'signer_position' => $signer['position'],
                        'signer_order' => $signer['sequence'],
                        'file_path' => null,
                        'page_no' => 0,
                        'pos_x' => 0,
                        'pos_y' => 0,
                        'linesapce' => 20,
                        'approval' => 0,
                        'job_type' => $request->templateType,
                    ]);
                    // dd($request->all());
                }
            }
        }
// http://127.0.0.1:8081/certify/check_certificate-cb/J7xcpjKuskA5wpcG/show/272
            $redirectUrl = url('/certify/check_certificate-cb/' . $certiCb->token . '/show/' .$certiCb->id  );
            return response()->json([
                'success' => true,
                'message' => 'บันทึกรายงานสำเร็จ',
                'redirect_url' => $redirectUrl // << ส่ง URL กลับไปด้วย
            ]);

        } catch (\Exception $e) {
            dd('Failed to save IbReportTemplate: ' . $e->getMessage());
            return response()->json(['message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูลลงฐานข้อมูล'], 500);
        }
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


    public function summaryReportHtml($id)
    {
        $certiCb = CertiCb::find($id);
        return view('ablonngcbeditor.editor-summary-report',[
                    'templateType' => "cb_summary_report_template",
                    'certiCbId' => $certiCb->id,
                ]);  
    }


    public function loadCbSummaryReportHtmlTemplate(Request $request)
    {
     
       $cbDocReviewReport=  CbDocReviewReport::where('app_certi_cb_id', $request->certiCbId)
                                ->where('report_type', $request->templateType)
                                ->first();

        // $certiCb = CertiCb::find($request->certiCbId);
        if($cbDocReviewReport !== null)
        {
            return response()->json([
                'html' => $cbDocReviewReport->template, 
                'status' => $cbDocReviewReport->status
            ]);
        }   

        $certi_cb = CertiCb::find($request->certiCbId);
        $cbName = $certi_cb->name_standard;
        $cbAppNo = $certi_cb->app_no;
        $cbHqAddress = $this->formatAddress($certi_cb);
        $telephone = !empty($certi_cb->hq_telephone) ? $certi_cb->hq_telephone : '-';
        $fax = !empty($certi_cb->hq_fax) ? $certi_cb->hq_fax : '-';

        $cbLocalAddress = $this->formatLocationAddress($certi_cb);
        $localTelephone = !empty($certi_cb->tel) ? $certi_cb->tel : '-';
        $localFax = !empty($certi_cb->tel_fax) ? $certi_cb->tel_fax : '-';

    
        $cbHtmlTemplate = CbHtmlTemplate::where('app_certi_cb_id',$certi_cb->id)->first();
        $htmlPages = json_decode($cbHtmlTemplate->html_pages);

        $filteredHtmlPages = [];
        foreach ($htmlPages as $pageHtml) {
            $trimmedPageHtml = trim(strip_tags($pageHtml, '<img>'));
            if (!empty($trimmedPageHtml)) {
                $filteredHtmlPages[] = $pageHtml;
            }
        }
  
        if (empty($filteredHtmlPages)) {
            return response()->json(['message' => 'No valid HTML content to export after filtering empty pages.'], 400);
        }
        $htmlPages = $filteredHtmlPages;

        // dd($htmlPages);

        // สมมติว่า $htmlPages คือ array ที่คุณ dd ออกมา

        // 1. สร้างตัวแปรว่างสำหรับเก็บ HTML ของตารางทั้งหมด
        $allDetailTable = '';

        // // 2. วนลูปในแต่ละหน้าของ HTML ที่มี
        // foreach ($htmlPages as $pageHtml) {
        //     // 3. สร้าง DOMDocument เพื่อจัดการ HTML ของหน้านั้นๆ
        //     $dom = new DOMDocument();
            
        //     // เพิ่ม meta tag เพื่อบังคับให้ DOMDocument อ่านเป็น UTF-8 (สำคัญมากสำหรับภาษาไทย)
        //     @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $pageHtml);
            
        //     $xpath = new DOMXPath($dom);

        //     // 4. ค้นหา <table> ทั้งหมดที่มี class "detail-table"
        //     $detailTables = $xpath->query('//table[contains(@class, "detail-table")]');

        //     // 5. วนลูปตารางที่เจอในหน้านั้นๆ
        //     foreach ($detailTables as $table) {
        //         // 6. แปลง Node ของตารางกลับเป็น HTML String แล้วนำมาต่อท้ายตัวแปรหลัก
        //         $allDetailTable .= $dom->saveHTML($table);
        //     }
        // }

            // 2. Loop through each available HTML page
            foreach ($htmlPages as $pageHtml) {
                // 3. Create a DOMDocument to manage the HTML for that page
                $dom = new DOMDocument();
                
                // Add a meta tag to force DOMDocument to read as UTF-8 (crucial for Thai language)
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $pageHtml);
                
                $xpath = new DOMXPath($dom);

                // 4. Find all <table> elements with the class "detail-table"
                $detailTables = $xpath->query('//table[contains(@class, "detail-table")]');

                // 5. Loop through the tables found on that page
                foreach ($detailTables as $table) {
                    // --- Added section to remove <th> ---
                    // 5.1 Find all <th> elements within the current table
                    $headers = $xpath->query('.//th', $table);
                    
                    // 5.2 Loop through and remove each <th>
                    foreach ($headers as $th) {
                        $th->parentNode->removeChild($th);
                    }
                    
                    // 5.3 Set the table's style attribute to width: 100%
                    $table->setAttribute('style', 'width: 100%;');
                    
                    // 6. Convert the modified table node back to an HTML string
                    $allDetailTable .= $dom->saveHTML($table);
                }
            }


                // 1. ดึงข้อมูลตามที่คุณระบุ
        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id', $certi_cb->id)->first();
        $formattedReviewDate = ''; // กำหนดค่าเริ่มต้น

        // 2. ตรวจสอบว่ามีข้อมูลหรือไม่ก่อนดำเนินการต่อ
        if ($cbDocReviewAuditor) {
            $startDate = Carbon::parse($cbDocReviewAuditor->from_date);
            $endDate = Carbon::parse($cbDocReviewAuditor->to_date);

            // ฟังก์ชันแปลงเดือนเป็นภาษาไทย
            $getThaiMonth = function($month) {
                $months = [
                    'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                    'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                    'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                    'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                ];
                return $months[$month] ?? $month;
            };

            // ดึงวัน เดือน และปี
            $startDay = $startDate->day;
            $startMonth = $getThaiMonth($startDate->format('F'));
            $startYear = $startDate->year + 543;

            $endDay = $endDate->day;
            $endMonth = $getThaiMonth($endDate->format('F'));
            $endYear = $endDate->year + 543;

            // ตรวจสอบและจัดรูปแบบวันที่
            if ($startDate->equalTo($endDate)) {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear}";
            } elseif ($startMonth === $endMonth && $startYear === $endYear) {
                $formattedReviewDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
            } else {
                $formattedReviewDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
            }
        } else {
            // กรณีไม่พบข้อมูล
            $formattedReviewDate = '-';
        }



        $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id',$certi_cb->id)->first();
        // dd($cbDocReviewAuditor);
      
        // 1. สร้างตัวแปรเริ่มต้น
        $auditorsHtmlString = '';
        $count = 1;

        if($cbDocReviewAuditor !== null)
        {
            // 2. แปลงข้อมูล JSON ให้เป็น PHP Array
            $auditorGroups = json_decode($cbDocReviewAuditor->auditors, true);

            if (is_array($auditorGroups)) {
                foreach ($auditorGroups as $group) {
                    // ตรวจสอบว่ามี key ที่ต้องการครบถ้วน
                    if (isset($group['temp_users']) && is_array($group['temp_users']) && isset($group['status'])) {
                        
                        // 5. ดึงชื่อสถานะ/ตำแหน่ง จาก Helper (เหมือนใน Blade)
                        $statusTitle = '';
                        $statusObject = HP::cbDocAuditorStatus($group['status']);
                        if ($statusObject && isset($statusObject->title)) {
                            $statusTitle = $statusObject->title;
                        }

                        // 6. วนลูปใน temp_users (เหมือน @foreach ที่สอง)
                        foreach ($group['temp_users'] as $userName) {
                            // 7. นำข้อมูลมาต่อกันเป็น HTML string
                            $auditorsHtmlString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {$count}) {$userName}  &nbsp;&nbsp;&nbsp;&nbsp;{$statusTitle}<br>";
                            
                            // 8. เพิ่มค่าตัวนับ
                            $count++;
                        }
                    }
                }
            }
        }



       $processString = '<b style="font-size: 22px">3. การตรวจประเมิน</b><br>
                &nbsp;&nbsp;&nbsp;<b>3.1	การประเมินเอกสาร (ถ้ามี)</b>: ...<br>
                 <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:10px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width:180px">ประเมินเอกสาร '. $formattedReviewDate .' มีความเห็นว่าเอกสารมียังต้องแก้ไข และเพิ่มเติมข้อมูล ซึ่งมีการจัดส่งเพิ่มเติมก่อนการนัดหมายเพื่อตรวจประเมิน ณ สถานประกอบการ</td>
                    </tr>
                </table>';

        $processString .= "<b>&nbsp;&nbsp;&nbsp;3.2 การตรวจประเมิน ณ สถานประกอบการ (ขั้นตอนที่ 1) <br></b>";

        $processAuditors = CertiCBAuditors::where('app_certi_cb_id',$certi_cb->id)
                        ->where('assessment_type',0)
                        ->whereNull('status_cancel')
                        ->whereNull('is_review_state')
                        ->orderby('id','asc')
                        ->get();
         
        $count = 1;
        $c = 1;
        foreach($processAuditors as $index => $processOneAuditor)
        {
            $boardAuditorDate = CertiCBAuditorsDate::where('auditors_id',$processOneAuditor->id)->first();
            if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
                if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                    // ถ้าเป็นวันเดียวกัน
                    $dateRange = HP::formatDateThai($boardAuditorDate->start_date);
                } else {
                    // ถ้าเป็นคนละวัน
                    $dateRange =  HP::formatDateThai($boardAuditorDate->start_date) . 
                                " - " . HP::formatDateThai($boardAuditorDate->end_date);
                }
            }
            
            $processString .= "&nbsp;&nbsp;&nbsp;<b>วันที่ตรวจประเมิน</b>	: 	".$dateRange." <br>";
            $processString .= "&nbsp;&nbsp;&nbsp;<b>คณะผู้ตรวจประเมิน ครั้งที่". $c." ประกอบด้วย</b> <br>";
            foreach($processOneAuditor->CertiCBAuditorsLists  as $key => $auditor)
            {
                // dd($auditor);
                 $processString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {$count}) {$auditor->temp_users} &nbsp;&nbsp;&nbsp;&nbsp; {$auditor->StatusAuditorTo->title} <br>";
                  $count++;
            }

            $c++;

            $assessment = CertiCbSaveAssessment::where('auditors_id', $processOneAuditor->id)->first();

            // สมมติว่ามี $histories collection อยู่แล้ว
            $histories = $assessment->CertiCBHistorys;

    

            $countSubmit = $histories->filter(function ($history) {
                // ตรวจสอบเบื้องต้นว่ามีข้อมูลหรือไม่
                if (empty($history->details_two)) {
                    return false;
                }
                
                $details = json_decode($history->details_two);

                // ตรวจสอบว่า json_decode สำเร็จและได้ผลลัพธ์เป็น array
                if (is_null($details) || !is_array($details)) {
                    return false;
                }

                // ค้นหารายการแรกสุดใน $details ที่ตรงกับเงื่อนไขอย่างใดอย่างหนึ่ง
                $found = collect($details)->first(function ($item) {
                    // เงื่อนไขที่ 1: ตรวจสอบว่ามี comment และ comment ไม่ใช่ค่า null
                    $hasComment = isset($item->comment) && !is_null($item->comment);
                    
                    // เงื่อนไขที่ 2: ตรวจสอบว่ามี status และ status เท่ากับ 1
                    $hasStatusOne = isset($item->status) && $item->status == 1;
                    
                    // คืนค่า true ถ้าเงื่อนไขใดเงื่อนไขหนึ่งเป็นจริง
                    return $hasComment || $hasStatusOne;
                });

                // ถ้าเจอรายการที่ตรงเงื่อนไข ($found ไม่ใช่ null) ให้ history นี้ผ่าน filter
                return !is_null($found);
            })->count();

                 if ($countSubmit > 0) {
                $countSubmit  = $countSubmit -1;
            }

            // สมมติว่ามี $assessment->CertiCBHistorys อยู่แล้ว
            $histories = $assessment->CertiCBHistorys;
            $firstDate = $histories->min('created_at');
            $lastDate = $histories->max('created_at');
            $fixDateRange = "";
            if($firstDate != "" && $lastDate != "")
            {
               $fixDateRange = HP::formatDateThai($firstDate) .' - '. HP::formatDateThai($firstDate);
            }

            // dd($countSubmit, $firstDate,$lastDate );

            $certiCBSaveAssessmentBug = CertiCBSaveAssessmentBug::where('assessment_id', $assessment->id)
                ->whereNotNull('report')
                ->get();

            $bugCount = $certiCBSaveAssessmentBug->count();

            $noBugChecked = '';
            $hasBugChecked = '';

            if ($bugCount > 0) {
                $hasBugChecked = 'checked';
            } else {
                $noBugChecked = 'checked';
            }

            $processString .= '<br>&nbsp;&nbsp;&nbsp;<b>ผลการตรวจประเมิน ณ สถานประกอบการ</b><br>';
            $processString .= '&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . $noBugChecked . ' disabled> ไม่พบข้อบกพร่องในการตรวจประเมิน<br>';
            $processString .= '&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . $hasBugChecked . ' disabled> พบข้อบกพร่องที่ต้องแก้ไขปรับปรุง จำนวน ' . $bugCount . ' รายการ<br>';

            if ($bugCount > 0) {
                foreach ($certiCBSaveAssessmentBug as $bug) {
                    if (!empty($bug->details)) {
                        $processString .= '&nbsp;&nbsp;&nbsp;&nbsp;- ' . htmlspecialchars($bug->details, ENT_QUOTES, 'UTF-8') . '<br>';
                    }
                }
            }

            $processString .= '&nbsp;&nbsp;&nbsp;<b>การดำเนินการแก้ไขข้อบกพร่อง (ถ้ามี)</b> : ';
            if ($bugCount > 0) {
                
                $processString .= 'จากการตรวจประเมินข้างต้น หน่วยรับรองได้เสนอแนวทางการแก้ไขข้อบกพร่องต่อสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม '.$countSubmit.' ครั้ง ระหว่างวันที่ '.$fixDateRange.' และคณะผู้ตรวจประเมินได้ทวนสอบและยอมรับแนวทางการแก้ไขข้อบกพร่อง ระหว่างวันที่ '.$fixDateRange.' เห็นว่ามีความเพียงพอในการนัดหมายเพื่อตรวจติดตามผลการแก้ไขข้อบกพร่องต่อไป<br>';
        
            }else{
                $processString .= '<br>';
            }

        


            
        }
        $processString .= '<br>';

        /////===================================================


        $processString .= "<b>&nbsp;&nbsp;&nbsp;3.3 การตรวจประเมินความสามารถคณะผู้ตรวจของหน่วยรับรอง (ขั้นตอนที่ 2) <br></b>";

        $processAuditors = CertiCBAuditors::where('app_certi_cb_id',$certi_cb->id)
                        ->where('assessment_type',1)
                        ->whereNull('status_cancel')
                        ->whereNull('is_review_state')
                        ->orderby('id','asc')
                        ->get();
         
        $count = 1;
        $c = 1;
        foreach($processAuditors as $index => $processOneAuditor)
        {
            $boardAuditorDate = CertiCBAuditorsDate::where('auditors_id',$processOneAuditor->id)->first();
            if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
                if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
                    // ถ้าเป็นวันเดียวกัน
                    $dateRange = "ในวันที่ " . HP::formatDateThai($boardAuditorDate->start_date);
                } else {
                    // ถ้าเป็นคนละวัน
                    $dateRange = "วันที่ " . HP::formatDateThai($boardAuditorDate->start_date) . 
                                " - " . HP::formatDateThai($boardAuditorDate->end_date);
                }
            }
            
            $processString .= "&nbsp;&nbsp;&nbsp;<b>วันที่ตรวจประเมิน</b>	: 	".$dateRange." <br>";
            $processString .= "&nbsp;&nbsp;&nbsp;<b>คณะผู้ตรวจประเมิน ครั้งที่". $c." ประกอบด้วย</b> <br>";
            foreach($processOneAuditor->CertiCBAuditorsLists  as $key => $auditor)
            {
                // dd($auditor);
                 $processString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {$count}) {$auditor->temp_users} &nbsp;&nbsp;&nbsp;&nbsp; {$auditor->StatusAuditorTo->title} <br>";
                  $count++;
            }

            $c++;

            $assessment = CertiCbSaveAssessment::where('auditors_id', $processOneAuditor->id)->first();

            // สมมติว่ามี $histories collection อยู่แล้ว
            $histories = $assessment->CertiCBHistorys;

      

            $countSubmit = $histories->filter(function ($history) {
                // ตรวจสอบเบื้องต้นว่ามีข้อมูลหรือไม่
                if (empty($history->details_two)) {
                    return false;
                }
                
                $details = json_decode($history->details_two);

                // ตรวจสอบว่า json_decode สำเร็จและได้ผลลัพธ์เป็น array
                if (is_null($details) || !is_array($details)) {
                    return false;
                }

                // ค้นหารายการแรกสุดใน $details ที่ตรงกับเงื่อนไขอย่างใดอย่างหนึ่ง
                $found = collect($details)->first(function ($item) {
                    // เงื่อนไขที่ 1: ตรวจสอบว่ามี comment และ comment ไม่ใช่ค่า null
                    $hasComment = isset($item->comment) && !is_null($item->comment);
                    
                    // เงื่อนไขที่ 2: ตรวจสอบว่ามี status และ status เท่ากับ 1
                    $hasStatusOne = isset($item->status) && $item->status == 1;
                    
                    // คืนค่า true ถ้าเงื่อนไขใดเงื่อนไขหนึ่งเป็นจริง
                    return $hasComment || $hasStatusOne;
                });

                // ถ้าเจอรายการที่ตรงเงื่อนไข ($found ไม่ใช่ null) ให้ history นี้ผ่าน filter
                return !is_null($found);
            })->count();

                 if ($countSubmit > 0) {
                $countSubmit  = $countSubmit -1;
            }

            // สมมติว่ามี $assessment->CertiCBHistorys อยู่แล้ว
            $histories = $assessment->CertiCBHistorys;
            $firstDate = $histories->min('created_at');
            $lastDate = $histories->max('created_at');
            $fixDateRange = "";
            if($firstDate != "" && $lastDate != "")
            {
               $fixDateRange = HP::formatDateThai($firstDate) .' - '. HP::formatDateThai($firstDate);
            }
  

            // dd($countSubmit, $firstDate,$lastDate );

            $certiCBSaveAssessmentBug = CertiCBSaveAssessmentBug::where('assessment_id', $assessment->id)
                ->whereNotNull('report')
                ->get();

            $bugCount = $certiCBSaveAssessmentBug->count();

            $noBugChecked = '';
            $hasBugChecked = '';

            if ($bugCount > 0) {
                $hasBugChecked = 'checked';
            } else {
                $noBugChecked = 'checked';
            }

            $processString .= '<br>&nbsp;&nbsp;&nbsp;<b>ผลการตรวจประเมิน ณ สถานประกอบการ</b><br>';
            $processString .= '&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . $noBugChecked . ' disabled> ไม่พบข้อบกพร่องในการตรวจประเมิน<br>';
            $processString .= '&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . $hasBugChecked . ' disabled> พบข้อบกพร่องที่ต้องแก้ไขปรับปรุง จำนวน ' . $bugCount . ' รายการ<br>';

            if ($bugCount > 0) {
                foreach ($certiCBSaveAssessmentBug as $bug) {
                    if (!empty($bug->details)) {
                        $processString .= '&nbsp;&nbsp;&nbsp;&nbsp;- ' . htmlspecialchars($bug->details, ENT_QUOTES, 'UTF-8') . '<br>';
                    }
                }
            }
            $processString .= '&nbsp;&nbsp;&nbsp;<b>การดำเนินการแก้ไขข้อบกพร่อง (ถ้ามี)</b> :';

            if ($bugCount > 0) {
                $processString .= 'จากการตรวจประเมินข้างต้น หน่วยรับรองได้เสนอแนวทางการแก้ไขข้อบกพร่องต่อสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม '.$countSubmit.' ครั้ง ระหว่างวันที่ '.$fixDateRange.' และคณะผู้ตรวจประเมินได้ทวนสอบและยอมรับแนวทางการแก้ไขข้อบกพร่อง ระหว่างวันที่ '.$fixDateRange.' เห็นว่ามีความเพียงพอในการนัดหมายเพื่อตรวจติดตามผลการแก้ไขข้อบกพร่องต่อไป<br>';
            }else{
                 $processString .= '<br>';
            }
           
            
        
        }

        $processString .= '<br>';


         $processString .= '<b>4. ข้อคิดเห็นจากการตรวจประเมินขั้นตอนที่ 1 และ 2</b><br>
            &nbsp;&nbsp;&nbsp;จากผลการตรวจประเมินขั้นตอนที่ 1 และขั้นตอนที่ 2 ข้างต้น คณะผู้ตรวจประเมินของสำนักงาน เห็นควรนำเสนอคณะทบทวนการรับรองระบบงานและคณะกรรมการรับรองหน่วยรับรอง เพื่อ <br>
            &nbsp;&nbsp;&nbsp;<input type="checkbox" checked>	รับรองระบบงานสาขาหน่วยรับรอง <br>
            &nbsp;&nbsp;&nbsp;<input type="checkbox">	คงไว้ซึ่งการรับรองระบบงานสาขาหน่วยรับรอง (เสนอคณะกรรมการรับรองหน่วยรับรองเพื่อทราบ) <br>
            &nbsp;&nbsp;&nbsp;<input type="checkbox">	ต่ออายุการรับรองระบบงานสาขาหน่วยรับรอง <br>
            &nbsp;&nbsp;&nbsp;<input type="checkbox">	อื่น ๆ  <br>
            &nbsp;&nbsp;&nbsp;ตามที่ขอ รายละเอียดดังเอกสารแนบ<br>' ;


        $html = 
                '

                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 24px;margin-top:-20px">
                    <tr>
                        <td  style="padding: 10px 0; text-align: center; font-size: 26px; font-weight: bold;">
                            รายงานสรุปผลการตรวจประเมินการรับรองระบบงานหน่วยรับรอง
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-top:-20px">
                    <tr>
                        <td  style="padding: 10px 0; text-align: center; font-size: 24px; font-weight: bold;">
                            การตรวจประเมินเพื่อการรับรอง'.$certi_cb->purposeType->name.'
                        </td>
                    </tr>
                </table>

                 <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-top:-20px">
                    <tr>
                        <td  style="padding: 10px 0;  font-size: 22px; font-weight: bold;">
                            1. ข้อมูลทั่วไป
                        </td>
                    </tr>
                </table>

                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:10px">
                    <tr>
                        <td style=" padding: 5px 8px; vertical-align: top;"><b>1.1 ผู้ยื่นคำขอ</b> :  '.$certi_cb->name.'</td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:10px">
                    <tr>
                        <td style=" padding: 5px 8px; vertical-align: top;"><b>1.2 เลขที่คำขอ</b> :  '.$certi_cb->app_no.'</td>
                    </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:10px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width: 25%;"><b>1.3 ที่ตั้งสำนักงานใหญ่</b> :</td>
                        <td style="padding: 5px 8px; vertical-align: top;">
                            '.$cbHqAddress.'<br>
                            <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                <tr>
                                    <td style="width: 50%;">โทรศัพท์ : '.$telephone.'</td>
                                    <td style="width: 50%;">โทรสาร : '.$fax.'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                        <tr >
                            <td style="padding: 5px 8px; vertical-align: top;width: 25%;"><b>1.4 ที่ตั้งสำนักงานสาขา</b>:</td>
                            <td style="padding: 5px 8px; vertical-align: top;">
                                '.$cbLocalAddress.'<br>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="width: 50%;">โทรศัพท์ : '.$localTelephone.'</td>
                                        <td style="width: 50%;">โทรสาร : '.$localFax.'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                </table>
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:10px">
                    <tr>
                        <td style=" padding: 5px 8px; vertical-align: top;"><b>1.5 วันที่ยื่นคำขอ</b> :  '.HP::formatDateThai($certi_cb->created_at).'</td>
                    </tr>
                </table>
                
                <table style="width: 100%; border-collapse: collapse; table-layout: auto; font-size: 22px;margin-left:10px">
                    <tr>
                        <td style="padding: 5px 8px; vertical-align: top;width:180px"><b>1.6 สาขาและขอบข่ายการรับรอง</b> :</td>
                    </tr>
                </table>
                '.$allDetailTable.'

                <b style="font-size: 22px">2. เกณฑ์ที่ใช้ในการตรวจประเมิน</b><br>
                &nbsp;&nbsp;&nbsp;(2.1) ...<br>
                &nbsp;&nbsp;&nbsp;(2.2) ...<br>
                &nbsp;&nbsp;&nbsp;(2.3) ...<br>

         
                '.$processString.'


<br>
<br>

                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                    <tbody>
                        <tr>
                            <!-- Column 1 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                            
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่</p>
                                    </div>
                            </td>
                            <!-- Column 2 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่</p>
                                    </div>
                            </td>
                            <!-- Column 3 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                    <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                        <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                    </div>
                                    <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                        <p style="margin: 0;">(xxx)</p>
                                        <p style="margin: 0;">xxxx</p>
                                        <p style="margin: 0;" class="signed_date">วันที่ </p>
                                    </div>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            ';
        return response()->json([
            'html' => $html, 
            'status' => null
        ]);
    }


    public function savesummaryReportHtml(Request $request)
    {

        // dd($request->all());
        
                // 1. ตรวจสอบข้อมูลที่ส่งมา
        $validator = Validator::make($request->all(), [
            'html_content' => 'required|string',
            'certiCbId' => 'required|integer',
            'templateType' => 'required|string',
            'status'       => 'required|string',
            'signers'      => 'nullable|array' // << เพิ่มการตรวจสอบ signers (เป็นค่าว่างได้)
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'ข้อมูลไม่ครบถ้วน', 'errors' => $validator->errors()], 422);
        }

        // 2. รับข้อมูลจาก Request
        $htmlContent = $request->input('html_content');        
        $certiCbId = $request->input('certiCbId');
        $reportType = $request->input('templateType');
        $status = $request->input('status');
        $signers = $request->input('signers', []); // << รับข้อมูล signers (ถ้าไม่มีให้เป็น array ว่าง)
        $certiCb = CertiCb::find($certiCbId);

        // dd( $signers);

        // 3. แปลงสัญลักษณ์ checkbox กลับเป็น HTML (หากจำเป็น)
        // หมายเหตุ: หาก Blade ส่ง <input> มาโดยตรง บรรทัดนี้อาจไม่จำเป็น แต่ใส่ไว้เพื่อความปลอดภัย
        $htmlContent = str_replace('☑', '<input type="checkbox" checked="checked">', $htmlContent);
        $htmlContent = str_replace('☐', '<input type="checkbox">', $htmlContent);

        CbDocReviewReport::updateOrCreate(
                [
                    'app_certi_cb_id' => $certiCbId,
                    'report_type'      => $reportType,
                ],
                [
                    'template' => $htmlContent,
                    'status'   => $status,
                    'signers'  => json_encode($signers) // แปลง array ของ signers เป็น JSON string
                ]
            );
            
        if($status  == 'final'){
            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');



            foreach ($signers as $key => $signer) {
                if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                $check = SignAssessmentReportTransaction::where('report_info_id', $certiCbId)
                            ->where('certificate_type',0)
                            ->where('signer_id',$signer['id'])
                            ->where('signer_order',$signer['sequence'])
                            ->where('report_type',1)
                            ->where('template',$request->templateType)
                            ->first();
                
                if($check  == null)
                {
                // dd($check);

                SignAssessmentReportTransaction::where('report_info_id',$certiCbId)
                            ->where('certificate_type',0)
                            ->where('signer_order',$signer['sequence'])
                            ->where('report_type',1)
                            ->where('template',$request->templateType)
                            ->delete();

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $certiCbId,
                    'signer_id' => $signer['id'],
                    'signer_name' => $signer['name'],
                    'signer_position' => $signer['position'],
                    'signer_order' => $signer['sequence'],
                    'view_url' => $url . '/certify/summary-report-cb-template/'.$certiCb->id ,
                    'certificate_type' => 0,
                    'report_type' => 1,
                    'template' => $request->templateType,
                    'app_id' => $certiCb->app_no,
                ]);
                }

            }
        }



        $redirectUrl = url('/certify/check_certificate-cb/' . $certiCb->token . '/show/' .$certiCb->id );
        return response()->json([
            'success' => true,
            'message' => 'บันทึกรายงานสำเร็จ',
            'redirect_url' => $redirectUrl // << ส่ง URL กลับไปด้วย
        ]);
    }
    
    public function tangtungTobtounReviewHtml($cbId)
    {
    
        return view('cbpdf.editor-tobtoun-cb', [
            'templateType' => "cb-tangtung-tobtoun",
            'cbId' => $cbId,
            'status' => 'draft' // คุณสามารถส่งค่าเริ่มต้นของ
        ]);
    
    }


    public function downloadTangtungTobtounHtml(Request $request)
    {
       
       $cbTobToun=  CbTobToun::where('app_certi_cb_id', $request->cbId)
                                ->where('report_type', $request->templateType)
                                ->first();

        $certiCb = CertiCb::find($request->cbId);
        if($cbTobToun !== null)
        {
            // ดึงข้อมูลผู้ลงนามที่อนุมัติแล้ว
            $messageRecordTransactions = MessageRecordTransaction::where('board_auditor_id', $certiCb->id)
                ->where('app_id', $certiCb->app_no)
                ->where('certificate_type', 0)
                ->where('job_type', $request->templateType)
                ->where('approval', 1)
                ->get();

                // ดึง HTML content เริ่มต้น
                $htmlContent = $cbTobToun->template;

                // 1. สร้าง DOMDocument เพื่อจัดการ HTML
                $dom = new DOMDocument();
                // เพิ่ม meta tag เพื่อบังคับ UTF-8 ป้องกันภาษาเพี้ยน
                @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $htmlContent);
                $xpath = new DOMXPath($dom);
                
                // --- ส่วนที่เพิ่มเข้ามา ---
                // นับจำนวนช่องลายเซ็นทั้งหมดที่มีใน Template จาก attribute 'data-signer-id'
                $totalSignerSlots = $xpath->query("//div[@data-signer-id]")->length;

                // นับจำนวนผู้ที่อนุมัติแล้ว
                $approvedSignerCount = $messageRecordTransactions->count();
                // --- สิ้นสุดส่วนที่เพิ่มเข้ามา ---


                // 2. วนลูปเฉพาะผู้ลงนามที่อนุมัติแล้ว
                foreach ($messageRecordTransactions as $transaction) {
                    $signerId = $transaction->signer_id;

                    // 3. ค้นหา Signer และดึง Path ของลายเซ็น
                    $signer = Signer::find($signerId);
                    
                    // ตรวจสอบให้แน่ใจว่าพบ signer และมีไฟล์แนบ
                    if ($signer && $signer->AttachFileAttachTo) {
                        // สมมติว่า $this->getSignature() คืนค่า path ที่ถูกต้อง
                        $signaturePath = $this->getSignature($signer->AttachFileAttachTo);
                        
                        // สร้าง URL ที่สมบูรณ์สำหรับรูปภาพ
                        $fullSignatureUrl = asset($signaturePath);

                        // 4. (แก้ไข) ค้นหา div ของผู้ลงนามใน HTML ทั้งหมด (ไม่ใช่แค่ตัวแรก)
                        $signerDivNodes = $xpath->query("//div[@data-signer-id='{$signerId}']");

                        // 5. (แก้ไข) วนลูป div ทั้งหมดที่เจอสำหรับ signerId นี้
                        foreach ($signerDivNodes as $signerDivNode) {
                            if ($signerDivNode) {
                                // 6. ค้นหา <img> ที่อยู่ภายใน td แม่ของ div นั้น
                                $tdNode = $signerDivNode->parentNode;
                                $imgNode = $xpath->query('.//img', $tdNode)->item(0);

                                if ($imgNode) {
                                    // 7. อัปเดต src ของ <img> ด้วย URL ของลายเซ็น
                                    $imgNode->setAttribute('src', $fullSignatureUrl);
                                }
                            }
                        }
                    }
                }

                // 8. บันทึก HTML ที่แก้ไขแล้วกลับเป็น String
                $bodyNode = $dom->getElementsByTagName('body')->item(0);
                $updatedHtmlContent = '';
                foreach ($bodyNode->childNodes as $child) {
                    $updatedHtmlContent .= $dom->saveHTML($child);
                }

                // ตรวจสอบว่าจำนวนช่องลายเซ็น > 0 และจำนวนที่อนุมัติเท่ากับจำนวนช่องทั้งหมด
                if ($totalSignerSlots > 0 && $totalSignerSlots === $approvedSignerCount) {
                    // ถ้าเท่ากัน ให้เพิ่ม 'all_signed' => true เข้าไปใน response
                    return response()->json([
                                'html' => $updatedHtmlContent, 
                                'status' => $cbTobToun->status,
                                'all_signed' => true
                            ]);
                    $response['all_signed'] = true;
                }else{
                        return response()->json([
                                'html' => $updatedHtmlContent, 
                                'status' => $cbTobToun->status,
                                'all_signed' => false
                            ]);
                }

      

            // return response()->json([
            //     'html' => $updatedHtmlContent, 
            //     'status' => $cbDocReviewAssessment->status
            // ]);
        }   

        $cbTobToun = CbTobToun::where('app_certi_cb_id',$request->cbId)->first();
      
         $auditorsHtmlString = '';
        if($cbTobToun != null)
        {
    // 1. สร้างตัวแปรเริ่มต้น
        
            $count = 1;

            // 2. แปลงข้อมูล JSON ให้เป็น PHP Array
            $auditorGroups = json_decode($cbTobToun->auditors, true);


            // 3. ตรวจสอบว่าการแปลงสำเร็จและข้อมูลเป็น Array
            if (is_array($auditorGroups)) {

                // 4. วนลูปหลัก (เหมือน @foreach แรก)
                foreach ($auditorGroups as $group) {
                    // ตรวจสอบว่ามี key ที่ต้องการครบถ้วน
                    if (isset($group['temp_users']) && is_array($group['temp_users']) && isset($group['status'])) {
                        
                        // 5. ดึงชื่อสถานะ/ตำแหน่ง จาก Helper (เหมือนใน Blade)
                        $statusTitle = '';
                        $statusObject = HP::cbDocAuditorStatus($group['status']);
                        if ($statusObject && isset($statusObject->title)) {
                            $statusTitle = $statusObject->title;
                        }

                        // 6. วนลูปใน temp_users (เหมือน @foreach ที่สอง)
                        foreach ($group['temp_users'] as $userName) {
                            // 7. นำข้อมูลมาต่อกันเป็น HTML string
                            $auditorsHtmlString .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$count}) {$userName}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$statusTitle}<br>";
                            
                            // 8. เพิ่มค่าตัวนับ
                            $count++;
                        }
                    }
                }
            }
        }
       


        $krut = url('') . '/images/krut.jpg';
                $pages = ['
                        <div style="display: flex; align-items: center; margin-bottom: 0; font-size: 18px;">
                            
                            <div style="width: 250px; flex-shrink: 0;"> 
                                <img src="'.$krut.'" alt="Logo" style="width: 130px; display: block;">
                            </div>

                            <div style="text-align: left; font-size: 34px; font-weight: bold; padding-left: 10px; padding-bottom: 5px;">
                                บันทึกข้อความ
                            </div>
                        </div>


                        <table style="width: 100%; border-collapse: collapse; font-size: 18px;  border-spacing: 0;margin-top:20px">
                            <tr>
                                <td style="font-size: 22px; padding: 5px 0;">
                                    <div style="display: flex; align-items: baseline;">
                                        <div style="display: flex; align-items: baseline; width: 60%;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">ส่วนราชการ</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;สก. รร.</span>
                                        </div>
                                        <div style="display: flex; align-items: baseline; width: 40%; margin-left: 20px;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">โทร</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;1430</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 22px; padding: 5px 0;">
                                    <div style="display: flex; align-items: baseline;">
                                        <div style="display: flex; align-items: baseline; width: 50%;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">ที่</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;</span>
                                        </div>
                                        <div style="display: flex; align-items: baseline; width: 50%; margin-left: 20px;">
                                            <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">วันที่</span>
                                            <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;'.HP::formatDateThaiFullNumThai($certiCb->created_at).'</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 22px; display: flex; align-items: baseline; padding: 5px 0;">
                                    <span style="font-weight: bold; white-space: nowrap; margin-right: 10px;">เรื่อง</span>
                                    <span style="border-bottom: 1px dotted #000; flex-grow: 1;">&nbsp;การแต่งตั้งคณะทบทวนการตรวจประเมิน เพื่อการรับรองระบบงาน'.$certiCb->purposeType->name.'ของหน่วยตรวจ'.$certiCb->name.' (คำขอเลขที่ '.$certiCb->app_no.')</span>
                                </td>
                            </tr>
                        </table>

                        <div stye="line-height:5px;font-size:8px">&nbsp;</div>
                         <span style="line-height:20px;font-size:22px;font-weight: bold;">เรียน  ผอ.สก. ผ่าน ผก.รร.</span><br><br>

                        <span style="line-height:20px;font-size:22px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามที่ ลมอ. ได้อนุมัติให้คณะผู้ตรวจประเมินไปตรวจประเมินหน่วยตรวจเพื่อการรับรองระบบงาน  คำขอเลขที่ '.$certiCb->app_no.' ตามมาตรฐาน มอก. 17020 – 2556 ของ '.$certiCb->name.' ซึ่งเป็นหน่วยตรวจประเภท C สำหรับการตรวจประเมิน ณ สถานประกอบการ (ขั้นตอนที่ 1) และการตรวจประเมินความสามารถผู้ตรวจ (ขั้นตอนที่ 2) แล้วนั้น<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เนื่องจากกระบวนการตรวจประเมินเพื่อการรับรองระบบงานหน่วยตรวจ ครบถ้วนแล้ว จึงเห็นควรแต่งตั้งคณะทบทวนการรับรองระบบงานหน่วยตรวจ เพื่อพิจารณาทบทวนผลการดำเนินการ ดังนี้ </span>

                        <br><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1)....
                        <br><br>

                 
                        <span style="line-height:20px;font-size:22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควรขอได้โปรดอนุมัติการแต่งตั้ง คณะทบทวนการรับรองระบบงานสำหรับคำขอดังกล่าวข้างต้น<br>

                        <br>
                        <br>
                            
                        <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                            <tbody>
                                <tr>
                                    <!-- Column 1 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <!-- Column 2 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                          <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                    <!-- Column 3 -->
                                    <td style="width: 25%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                        <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                            <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                        </div>
                                        <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                            <p style="margin: 0;">(xxx)</p>
                                            <p style="margin: 0;">xxxx</p>
                                            <p style="margin: 0;" class="signed_date">วันที่ </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                '];

        return response()->json([
            'pages' => $pages, 
            'status' => null
        ]);
    }


    public function saveTangtungTobtounHtml(Request $request)
    {
        // dd($request->all(),json_encode($request->input('signers', [])));
        // 1. ตรวจสอบข้อมูลที่ส่งมา
        $validator = Validator::make($request->all(), [
            'html_content' => 'required|string',
            'templateType' => 'required|string',
            'status'       => 'required|string',
            'signers'      => 'nullable|array' // << เพิ่มการตรวจสอบ signers (เป็นค่าว่างได้)
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'ข้อมูลไม่ครบถ้วน', 'errors' => $validator->errors()], 422);
        }

        // 2. รับข้อมูลจาก Request
        $htmlContent = $request->input('html_content');        
        // $assessmentId = $request->input('assessmentId');
        $reportType = $request->input('templateType');
        $status = $request->input('status');
        $signers = $request->input('signers', []); // << รับข้อมูล signers (ถ้าไม่มีให้เป็น array ว่าง)
        // $certiCbSaveAssessment = CertiIBSaveAssessment::find($assessmentId);
        $certiCb = CertiCb::find($request->input('cbId'));

        // 3. แปลงสัญลักษณ์ checkbox กลับเป็น HTML (หากจำเป็น)
        // หมายเหตุ: หาก Blade ส่ง <input> มาโดยตรง บรรทัดนี้อาจไม่จำเป็น แต่ใส่ไว้เพื่อความปลอดภัย
        $htmlContent = str_replace('☑', '<input type="checkbox" checked="checked">', $htmlContent);
        $htmlContent = str_replace('☐', '<input type="checkbox">', $htmlContent);

        try {
            // 5. บันทึกหรืออัปเดตข้อมูลด้วย updateOrCreate
            CbTobToun::updateOrCreate(
                [
                    'app_certi_cb_id' => $request->input('cbId'),
                    'report_type'      => $reportType,
                ],
                [
                    'template' => $htmlContent, // บันทึก HTML ดิบลงไปตรงๆ
                    'status'   => $status,
                    'signers'  => json_encode($signers) // << บันทึกข้อมูลผู้ลงนามเป็น JSON
                ]
            );


        if($status == "final")
        {
           
            foreach ($signers as $key => $signer) {
                if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

                $config = HP::getConfig();
                $url  =   !empty($config->url_center) ? $config->url_center : url('');

                $check = MessageRecordTransaction::where('board_auditor_id',$certiCb->id)
                ->where('signer_id' , $signer['id'])
                ->where('certificate_type' ,0)
                ->where('app_id' ,$certiCb->app_no)
                ->where('signature_id' , $signer['id'])
                ->where('signer_order' , $signer['sequence'])
                ->where('job_type' , $request->templateType)
                ->first();

                

                if($check == null)
                {

                     MessageRecordTransaction::where('board_auditor_id',$certiCb->id)
                    ->where('signer_order',$signer['sequence'])
                    ->where('job_type',$request->templateType)
                    ->delete();
                     
                    MessageRecordTransaction::create([
                        'board_auditor_id' => $certiCb->id,
                        'signer_id' => $signer['id'],
                        'certificate_type' => 0,
                        'app_id' => $certiCb->app_no,
                        'view_url' =>$url . '/certify/cb-tangtung-tobtoun-html/'. $certiCb->id  ,
                        'signature_id' => $signer['id'],
                        'is_enable' => false,
                        'show_name' => false,
                        'show_position' => false,
                        'signer_name' => $signer['name'],
                        'signer_position' => $signer['position'],
                        'signer_order' => $signer['sequence'],
                        'file_path' => null,
                        'page_no' => 0,
                        'pos_x' => 0,
                        'pos_y' => 0,
                        'linesapce' => 20,
                        'approval' => 0,
                        'job_type' => $request->templateType,
                    ]);
                    // dd($request->all());
                }
            }
        }
// http://127.0.0.1:8081/certify/check_certificate-cb/J7xcpjKuskA5wpcG/show/272
            $redirectUrl = url('/certify/check_certificate-cb/' . $certiCb->token . '/show/' .$certiCb->id  );
            return response()->json([
                'success' => true,
                'message' => 'บันทึกรายงานสำเร็จ',
                'redirect_url' => $redirectUrl // << ส่ง URL กลับไปด้วย
            ]);

        } catch (\Exception $e) {
            dd('Failed to save IbReportTemplate: ' . $e->getMessage());
            return response()->json(['message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูลลงฐานข้อมูล'], 500);
        }
    }

    public function docResultReviewHtml($id)
    {
        $certiCb = CertiCb::find($id);
        return view('ablonngcbeditor.editor-result-review',[
                    'templateType' => "cb_result_review_template",
                    'certiCbId' => $certiCb->id,
                ]);  
    }

    public function downloadResultReviewHtml(Request $request)
    {
    //   dd($request->all());
       $cbDocReviewReport=  CbDocReviewReport::where('app_certi_cb_id', $request->certiCbId)
                                ->where('report_type', $request->templateType)
                                ->first();

        if($cbDocReviewReport !== null)
        {
            return response()->json([
                'html' => $cbDocReviewReport->template, 
                'status' => $cbDocReviewReport->status
            ]);
        }   

        $certiCb = CertiCb::find($request->certiCbId);

                // 1. สร้างตัวแปรว่างสำหรับเก็บ HTML ของตารางทั้งหมด
        $allDetailTable = '';

        $cbHtmlTemplate = CbHtmlTemplate::where('app_certi_cb_id',$certiCb->id)->first();
        $htmlPages = json_decode($cbHtmlTemplate->html_pages);

        $filteredHtmlPages = [];
        foreach ($htmlPages as $pageHtml) {
            $trimmedPageHtml = trim(strip_tags($pageHtml, '<img>'));
            if (!empty($trimmedPageHtml)) {
                $filteredHtmlPages[] = $pageHtml;
            }
        }
  
        if (empty($filteredHtmlPages)) {
            return response()->json(['message' => 'No valid HTML content to export after filtering empty pages.'], 400);
        }
        $htmlPages = $filteredHtmlPages;


        // 2. วนลูปในแต่ละหน้าของ HTML ที่มี
        foreach ($htmlPages as $pageHtml) {
            // 3. สร้าง DOMDocument เพื่อจัดการ HTML ของหน้านั้นๆ
            $dom = new DOMDocument();
            
            // เพิ่ม meta tag เพื่อบังคับให้ DOMDocument อ่านเป็น UTF-8 (สำคัญมากสำหรับภาษาไทย)
            @$dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $pageHtml);
            
            $xpath = new DOMXPath($dom);

            // 4. ค้นหา <table> ทั้งหมดที่มี class "detail-table"
            $detailTables = $xpath->query('//table[contains(@class, "detail-table")]');

            // 5. วนลูปตารางที่เจอในหน้านั้นๆ
            foreach ($detailTables as $table) {
                // 6. แปลง Node ของตารางกลับเป็น HTML String แล้วนำมาต่อท้ายตัวแปรหลัก
                $allDetailTable .= $dom->saveHTML($table);
            }
        }


        $check1 = "" ;
        $check2 = "" ;
        $check3 = "" ;
        $check4 = "" ;
        $check5 = "" ;
        $check6 = "" ;

        if($certiCb->purposeType->id == 1){
            $check1 = "checked" ;
        }else if($certiCb->purposeType->id == 2)
        {
            $check2 = "checked" ;
        }else if($certiCb->purposeType->id == 3)
        {
            $check3 = "checked" ;
        }else if($certiCb->purposeType->id == 4)
        {
            $check4 = "checked" ;
        }else if($certiCb->purposeType->id == 5)
        {
            $check5 = "checked" ;
        }else if($certiCb->purposeType->id == 6)
        {
            $check6 = "checked" ;
        }
        
        $html = 
                '
                  <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold; font-size: 22px; padding: 10px 0;">
                        แบบฟอร์มรายงานการทบทวนการรับรองระบบงานหน่วยตรวจ
                    </td>
                </tr>
             </table>

             
             <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <td style="width:80%; font-size: 20px; padding: 5px ; border: 1px solid black;">
                         <b>หน่วยตรวจ :</b> '.$certiCb->name.' 
                    </td>
                        <td style="font-size: 20px; padding: 5px; border: 1px solid black;">
                       <b>คำขอเลขที่ :</b> '.$certiCb->app_no.' 
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 20px; padding: 5px; border: 1px solid black;">
                        <b>ผู้ทบทวนการรับรองระบบงาน :</b> ....
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2" style="padding: 2px 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px; vertical-align: top;">
                                    <input type="checkbox" checked style="vertical-align: middle; margin-top: -1px;"> ข้าพเจ้าขอรับรองว่าไม่มีส่วนได้ส่วนเสีย หรือไม่มีความสัมพันธ์ กับ '.$certiCb->name.'
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">
                                    <input type="checkbox"> ข้าพเจ้ามีส่วนได้ส่วนเสีย หรือมีความสัมพันธ์ กับ '.$certiCb->name.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
             </table>

             <table style="width: 100%; border-collapse: collapse;margin-top:10px">
                <tr>
                    <td colspan="2" style="padding: 2px 0;">
                        <table style="width: 100%; border-collapse: collapse; ">
                            <tr>
                                <td style="padding: 5px;">
                                    <b>สาขาการรับรองระบบงาน :</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
             </table>

             '.$allDetailTable.'


             <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 5px;" colspan="3">
                        <b>ประเภทการประเมิน :</b>
                    </td>
                </tr>
                <tr>
                    <table style="width: 100%; border-collapse: collapse;">
      
                        <tr>
                            <td style="width: 40%; padding: 5px; vertical-align: top;">
                                <input type="checkbox" '.$check1.'> การตรวจประเมินเพื่อการรับรองครั้งแรก
                            </td>
                            <td style="width: 30%; padding: 5px; vertical-align: top;">
                                <input type="checkbox"> การตรวจติดตามผล ครั้งที่ .........
                            </td>
                        
                        </tr>

                        <tr>
                        <td style="width: 30%; padding: 5px; vertical-align: top;">
                                <input type="checkbox" '.$check2.'> การตรวจประเมินเพื่อต่ออายุการรับรองระบบงาน
                            </td>
                            <td colspan="3" style="padding: 5px;">
                                <input type="checkbox" '.$check3.'> ขยายขอบข่าย (ช่วงการตรวจ/ข้อกำหนดที่ใช้)
                            </td>
                    
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 5px;">
                                <input type="checkbox"> อื่นๆ
                            </td>
                        
                        </tr>
                    </table>
                </tr>
             </table>
           
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2" style="padding: 2px 0;">
                        <table style="width: 100%; border-collapse: collapse; ">
                            <tr>
                                <td style="padding: 5px;">
                                    <b>หมายเหตุ</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="width: 50%; padding: 2px;">IA หมายถึง การตรวจประเมินเพื่อการรับรองครั้งแรก</td>
                                            <td style="width: 50%; padding: 2px;">EA หมายถึง การตรวจประเมินเพื่อขยายสาขาและขอบข่าย</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">RA หมายถึง การตรวจประเมินเพื่อต่ออายุการรับรองระบบงาน</td>
                                            <td style="padding: 2px;">SA หมายถึง การตรวจติดตามผล</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">Y หมายถึง เห็นด้วยกับที่คณะผู้ประเมิน</td>
                                            <td style="padding: 2px;">N หมายถึง ไม่เห็นด้วยกับที่คณะผู้ประเมิน</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 2px;">N/A หมายถึง ไม่ต้องพิจารณา</td>
                                            <td style="padding: 2px;">
                                                <table style="display: inline-block; vertical-align: middle; margin-right: 4px; border-collapse: collapse;">
                                                    <tr>
                                                        <td >
                                                        <div style="width: 12px; height: 12px; background-color: #7f7f7f;"></div>

                                                        </td>
                                                    </tr>
                                                </table>
                                                หัวข้อบังคับสำหรับการตรวจติดตามผลและต่ออายุ
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


             <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;" class="signer_area_table">
                    <tbody>
                        <tr>
                            <!-- Column 1 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">

                            </td>
                            <!-- Column 2 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                 
                            </td>
                            <!-- Column 3 -->
                            <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 5px; border: none;">
                                <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                    <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne" style="height: 35px; object-fit: contain;">
                                </div>
                                <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                    <p style="margin: 0;">(xxx)</p>
                                    <p style="margin: 0;">xxxx</p>
                                    <p style="margin: 0;" class="signed_date">วันที่ </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            ';
        return response()->json([
            'html' => $html, 
            'status' => null
        ]);
    }

    public function saveResultReviewHtml(Request $request)
    {
        // dd($request->all());
        // 1. ตรวจสอบข้อมูลที่ส่งมา
        $validator = Validator::make($request->all(), [
            'html_content' => 'required|string',
            'certiCbId' => 'required|integer',
            'templateType' => 'required|string',
            'status'       => 'required|string',
            'signers'      => 'nullable|array' // << เพิ่มการตรวจสอบ signers (เป็นค่าว่างได้)
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'ข้อมูลไม่ครบถ้วน', 'errors' => $validator->errors()], 422);
        }

        // 2. รับข้อมูลจาก Request
        $htmlContent = $request->input('html_content');        
        $certiCbId = $request->input('certiCbId');
        $reportType = $request->input('templateType');
        $status = $request->input('status');
        $signers = $request->input('signers', []); // << รับข้อมูล signers (ถ้าไม่มีให้เป็น array ว่าง)
        $certiCb = CertiCb::find($certiCbId);

        // 3. แปลงสัญลักษณ์ checkbox กลับเป็น HTML (หากจำเป็น)
        // หมายเหตุ: หาก Blade ส่ง <input> มาโดยตรง บรรทัดนี้อาจไม่จำเป็น แต่ใส่ไว้เพื่อความปลอดภัย
        $htmlContent = str_replace('☑', '<input type="checkbox" checked="checked">', $htmlContent);
        $htmlContent = str_replace('☐', '<input type="checkbox">', $htmlContent);

        CbDocReviewReport::updateOrCreate(
                [
                    'app_certi_cb_id' => $certiCbId,
                    'report_type'      => $reportType,
                ],
                [
                    'template' => $htmlContent,
                    'status'   => $status,
                    'signers'  => json_encode($signers) // แปลง array ของ signers เป็น JSON string
                ]
            );
            
        if($status  == 'final'){
            $config = HP::getConfig();
            $url  =   !empty($config->url_center) ? $config->url_center : url('');

            SignAssessmentReportTransaction::where('report_info_id', $certiCbId)
                ->where('certificate_type',0)
                ->where('report_type',1)
                ->where('template',$request->templateType)
                ->delete();

            foreach ($signers as $key => $signer) {
                if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                    continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
                }

              SignAssessmentReportTransaction::where('report_info_id',$certiCbId)
                            ->where('certificate_type',0)
                            ->where('signer_order',$key)
                            ->where('report_type',1)
                            ->where('template',$request->templateType)
                            ->delete();

                SignAssessmentReportTransaction::create([
                    'report_info_id' => $certiCbId,
                    'signer_id' => $signer['id'],
                    'signer_name' => $signer['name'],
                    'signer_position' => $signer['position'],
                    'signer_order' => $key,
                    'view_url' => $url . '/certify/cb-doc-result-review-html/'.$certiCb->id ,
                    'certificate_type' => 0,
                    'report_type' => 1,
                    'template' => $request->templateType,
                    'app_id' => $certiCb->app_no,
                ]);
            }
        }


        $certiCb->update(['review' => 2,'status' => 12]);  // สรุปรายงานและเสนออนุกรรมการฯ
        $report = new CertiCBReport;  //สรุปรายงานและเสนออนุกรรมการฯ
        $report->app_certi_cb_id =  $certiCb->id;
        $report->review_approve = "2";
        $report->save();

        
        $json = $this->copyScopeCbFromAttachement($report->app_certi_cb_id);
        $copiedScopes = json_decode($json, true);

        $tb = new CertiCBReport;
        $certi_cb_attach_more = new CertiCBAttachAll();
        $certi_cb_attach_more->app_certi_cb_id      = $report->app_certi_cb_id ?? null;
        $certi_cb_attach_more->ref_id               = $report->id;
        $certi_cb_attach_more->table_name           = $tb->getTable();
        $certi_cb_attach_more->file_section         = '1';
        $certi_cb_attach_more->file                 = $copiedScopes[0]['attachs'];
        $certi_cb_attach_more->file_client_name     = $copiedScopes[0]['file_client_name'];
        $certi_cb_attach_more->token                = str_random(16);
        $certi_cb_attach_more->save();



        $redirectUrl = url('/certify/check_certificate-cb/' . $certiCb->token . '/show/' .$certiCb->id  );
        return response()->json([
            'success' => true,
            'message' => 'บันทึกรายงานสำเร็จ',
            'redirect_url' => $redirectUrl // << ส่ง URL กลับไปด้วย
        ]);
    }

    public function copyScopeCbFromAttachement($certiCbId)
{
    $copiedScoped = null;
    $fileSection = null;

    $app = CertiCb::find($certiCbId);

    $latestRecord = CertiCBAttachAll::where('app_certi_cb_id', $certiCbId)
    ->where('file_section', 3)
    ->where('table_name', 'app_certi_cb')
    ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
    ->first();

    $existingFilePath = 'files/applicants/check_files_cb/' . $latestRecord->file ;

    // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
    if (HP::checkFileStorage($existingFilePath)) {
        $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
        $no  = str_replace("RQ-","",$app->app_no);
        $no  = str_replace("-","_",$no);
        $dlName = 'scope_'.basename($existingFilePath);
        $attach_path  =  'files/applicants/check_files_cb/'.$no.'/';

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

}

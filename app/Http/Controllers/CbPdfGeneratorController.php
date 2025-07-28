<?php

namespace App\Http\Controllers;
use HP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\Certify\CbReportTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Mail\CB\CBSignReportNotificationMail;
use App\Models\Certificate\CbDocReviewAuditor;
use App\Models\Certify\SignAssessmentReportTransaction;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;

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
                            <b>1. ชื่อหน่วยตรวจ :</b> '.$cbName.'
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
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&#9744; การรับรองครั้งแรก&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#9745; การตรวจติดตามผล ครั้งที่ 1<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&#9744; การต่ออายุการรับรองระบบงาน&nbsp;&nbsp;&nbsp;&nbsp;&#9745; อื่น ๆ<br> 
                    <b style="font-size: 22px">8. คณะผู้ตรวจประเมินของสำนักงาน : </b> ... <br> 
                    <b style="font-size: 22px">9. คณะผู้ตรวจของหน่วยงาน : </b> ... <br> 
                    <b style="font-size: 22px">10. รายละเอียดการตรวจประเมิน : </b> ... <br> 
                ','
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;ผลการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp;จากการตรวจประเมิน .....<br> 
                    <b style="font-size: 22px">&nbsp;&nbsp;&nbsp;สรุปการตรวจประเมิน</b><br>
                    &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;หน่วยตรวจประเมิน .....<br> 
                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;">
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
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
                            <b>ชื่อหน่วยตรวจ :</b> ....
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
                                ผู้ตรวจประเมินของหน่วยรับรองระบบงาน หน่วยตรวจ : 
                            </td>

                            <td  style=" text-align: center; vertical-align: top; padding: 5px; border: none; font-size: 22px;">
                                <div style="height: 35px; margin-bottom: 5px; display: flex; justify-content: center; align-items: center;">
                                    <img src="https://placehold.co/200x50/FFFFFF/000000.png?text=Signature&font=parisienne"  style="height: 35px; object-fit: contain;">
                                </div>
                                <div style="border-top: 1px solid #000; padding-top: 5px; display: inline-block; width: 90%;">
                                    <p style="margin: 0;">()</p>
                                    <p style="margin: 0;"></p>
                                    <p style="margin: 0;">วันที่ </p>
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
                                <b style="font-weight: bold;">ชื่อหน่วยรับรอง/หน่วยตรวจ:</b> ...<br>
                                <b style="font-weight: bold;">เลขที่คำขอ:</b> ...<br>
                                <b style="font-weight: bold;">สถานที่ตรวจประเมิน:</b> ...<br>
                                <b style="font-weight: bold;">วันที่:</b> ...
                            </td>
                            <td style="width: 50%; border: 1px solid black; padding: 8px; vertical-align: top;">
                                <b style="font-weight: bold;">รายงานข้อบกพร่องที่:</b> ...<br>
                                <b style="font-weight: bold;">การตรวจประเมินเพื่อ: </b><span>&#9744; รับรองครั้งแรก</span> <span>&#9745; ติดตามผลครั้งที่ 1</span>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
                                    <tr>
                                        <td style="padding: 2px; border: none; vertical-align: top;">&#9744; ต่ออายุการรับรอง</td>
                                        <td style="padding: 2px; border: none; vertical-align: top;">&#9744; อื่นๆ ...</td>
                                    </tr>
                                </table>
                                <b style="font-weight: bold;">การตรวจประเมิน:</b> <span>&#9745; ขั้นตอนที่ 1</span> <span>&#9744; ขั้นตอนที่ 2</span><br>
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
                                    การรับทราบข้อบกพร่อง ข้าพเจ้าในฐานะที่เป็นผู้แทนของหน่วยรับรอง/หน่วยตรวจ ได้รับทราบและเห็นด้วยกับรายงานข้อบกพร่องข้างต้นแล้ว และตกลงที่จะดำเนินการวิเคราะห์หาสาเหตุของข้อบกพร่อง กำหนดแนวทางการแก้ไขและป้องกันการเกิดซ้ำ และระบุวันแล้วเสร็จ ลงในแบบฟอร์ม FCI-AS08 การเสนอแนวทางการแก้ไขข้อบกพร่องจากการตรวจประเมินหน่วยรับรอง/หน่วยตรวจ และจักจัดส่งให้สำนักงานฯ พิจารณาเป็นที่เรียบร้อยภายใน 30 วัน นับจากวันที่รับทราบรายงานข้อบกพร่องฉบับนี้
                                </div>
                                <div style="margin-top: 10px; padding-left: 5px;">
                                    หมายเหตุ: ...<br><br>
                                </div>
                                <div style="text-align: right; padding: 0px; line-height: 50px;margin-bottom:-20px">
                                    <b style="font-weight: bold; vertical-align: middle;">ผู้แทนของหน่วยรับรอง/หน่วยตรวจ:</b>
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
                                <b style="font-weight: bold;">ความเห็น:</b> &#9745; ปิดข้อบกพร่อง &#9744; อื่นๆ ...............................................................
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
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">จากการตรวจประเมิน ณ สถานประกอบการหน่วยตรวจ</span><br>
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">ในการตรวจประเมินเพื่อติดตามผลการรับรองระบบงาน ครั้งที่ 1 สาขาหน่วยตรวจ</span>
                 </div>
                  
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 65%">
                            <b>1. ชื่อหน่วยตรวจ :</b> '.$cbName.'
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

                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;">
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    ','
                    <div style="text-align:center; font-size: 22px; ">
                        <span style="padding: 10px 0; text-align: center;font-weight: bold;">สรุปการพิจารณาแนวทางแก้ไขข้อบกพร่องจากการตรวจประเมิน ณ สถานประกอบการหน่วยตรวจ</span><br>
                        <span style="padding: 10px 0; text-align: center; font-weight: bold;">ในการตรวจประเมินเพื่อติดตามผลการรับรองงาน ครั้งที่ 1 สาขาหน่วยตรวจ</span><br><br>
                        
                    </div>

                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px; border: 1px solid black;">
                            <tr>
                                <td style="width: 33.33%; border: 1px solid black; padding: 10px 0; text-align: center; font-weight: bold; vertical-align: top;">
                                    รายงานการตรวจประเมิน ณ สถานประกอบการ
                                </td>
                                <td style="width: 33.33%; border: 1px solid black; padding: 10px 0; text-align: center; font-weight: bold; vertical-align: top;">
                                    รายงานการตรวจประเมิน ณ สถานประกอบการ
                                </td>
                                <td style="width: 33.33%; border: 1px solid black; padding: 10px 0; text-align: center; font-weight: bold; vertical-align: top;">
                                    รายงานการตรวจประเมิน ณ สถานประกอบการ
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px 3px; vertical-align: top;">
                                    <br>
                                </td>
                                <td style="border: 1px solid black; padding: 5px 3px;  vertical-align: top;">
                                    <br>
                                </td>
                                <td style="border: 1px solid black; padding: 5px 3px; vertical-align: top;">
                                    <br>
                                </td>
                            </tr>
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
                            <td style="width: 18%; padding: 5px 8px; vertical-align: top;"><b>1. หน่วยตรวจ</b> :</td>
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
                    <b style="font-size: 22px">8. ผู้แทนหน่วยตรวจ</b><br>
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
                                    <td style="font-weight: bold; background-color: #f9fafb; border: 1px solid black; padding: 2px 8px;"><b>หลักเกณฑ์ วิธีการและเงื่อนไขการรับรองหน่วยตรวจ พ.ศ. 2564</b></td>
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
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หน่วยตรวจมีระบบการบริหารงานส่วนใหญ่.....</b><br><br><br><br><br><br>

                    <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;">
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
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
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">จากการตรวจประเมิน ณ สถานประกอบการหน่วยตรวจ</span><br>
                    <span style="padding: 10px 0; text-align: center; font-weight: bold;">ในการตรวจประเมินเพื่อติดตามผลการรับรองระบบงาน ครั้งที่ 1 สาขาหน่วยตรวจ</span>
                 </div>
                  
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 22px;">
                    <tr>
                        <td style="padding: 10px 0; font-size: 22px; width: 65%">
                            <b>1. ชื่อหน่วยตรวจ :</b> '.$cbName.'
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

                <table style="width: 100%; border-collapse: collapse; font-size: 20px; border: none; margin-top: 40px;">
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateOne.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateTwo.'</p>
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
                                        <p style="margin: 0;">วันที่ '.$finalReportProcessOneSignerDateThree.'</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    ','
                    <div style="text-align:center; font-size: 16px; ">
                        <span style="padding: 10px 0; text-align: center;font-weight: bold;">สรุปการพิจารณาแนวทางแก้ไขข้อบกพร่องจากการตรวจประเมิน ณ สถานประกอบการหน่วยตรวจ</span><br>
                        <span style="padding: 10px 0; text-align: center; font-weight: bold;">ในการตรวจประเมินเพื่อติดตามผลการรับรองงาน ครั้งที่ 1 สาขาหน่วยตรวจ</span><br><br>
                        
                    </div>

                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 16px; border: 1px solid black;">
                            <tr>
                                <td style="width: 33.33%; border: 1px solid black; padding: 10px 0; text-align: center; font-weight: bold; vertical-align: top;">
                                    รายงานการตรวจประเมิน ณ สถานประกอบการ
                                </td>
                                <td style="width: 33.33%; border: 1px solid black; padding: 10px 0; text-align: center; font-weight: bold; vertical-align: top;">
                                    รายงานการตรวจประเมิน ณ สถานประกอบการ
                                </td>
                                <td style="width: 33.33%; border: 1px solid black; padding: 10px 0; text-align: center; font-weight: bold; vertical-align: top;">
                                    รายงานการตรวจประเมิน ณ สถานประกอบการ
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black; padding: 5px 3px; vertical-align: top;">
                                    <br>
                                </td>
                                <td style="border: 1px solid black; padding: 5px 3px;  vertical-align: top;">
                                    <br>
                                </td>
                                <td style="border: 1px solid black; padding: 5px 3px; vertical-align: top;">
                                    <br>
                                </td>
                            </tr>
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

                //  dd("signer",$signers,$reportType,$report);

                //  if($reportType == "cb_final_report_process_one")
                // {
                //     $this->manageSinging($report,$signers,"cb_final_report_process_one",1);
                // }else if($reportType == "cb_car_report_two_process_one")
                // {
                //     $this->manageSinging($report,$signers,"cb_car_report_two_process_one",2);
                // }

                if($reportType == "cb_final_report_process_one")
                {
                    $this->manageSinging($report,$signers,"cb_final_report_process_one",1);
                }
                else if($reportType == "cb_car_report_two_process_one")
                {
                    $this->manageSinging($report,$signers,"cb_car_report_two_process_one",2);
                }
                else if($reportType == "cb_final_report_process_two")
                {
                    $this->manageSinging($report,$signers,"cb_final_report_process_two",1);
                }
                else if($reportType == "cb_car_report_two_process_two")
                {
                    $this->manageSinging($report,$signers,"cb_car_report_two_process_two",2);
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

    public function manageSinging($report,$signers,$template,$report_type)
    {
        $config = HP::getConfig();
        $url  =   !empty($config->url_center) ? $config->url_center : url('');

        SignAssessmentReportTransaction::where('report_info_id', $report->id)
                                    ->where('certificate_type',0)
                                    ->where('report_type',$report_type)
                                    ->where('template',$template)
                                    ->delete();
        foreach ($signers as $key => $signer) {
            if (!isset($signer['id'], $signer['name'], $signer['position'])) {
                continue; // ข้ามรายการนี้หากข้อมูลไม่ครบถ้วน
            }
            SignAssessmentReportTransaction::create([
                'report_info_id' => $report->id,
                'signer_id' => $signer['id'],
                'signer_name' => $signer['name'],
                'signer_position' => $signer['position'],
                'signer_order' => $key,
                'view_url' => $url . '/certify/show-cb-editor/'. $template . '/' . $report->id,
                'certificate_type' => 0,
                'report_type' => $report_type,
                'template' => $template,
                'app_id' => $report->certiCBSaveAssessment->CertiCBCostTo->app_no,
            ]);
        }
    }


    public function showEditor($templateType,$assessmentId)
    {
        // คุณอาจจะต้องดึงข้อมูล CertiIb หรือ Assessment อีกครั้งถ้าจำเป็น
        // แต่ถ้ามีแค่ ID ก็สามารถส่งไปได้เลย
        $certiCBSaveAssessment = CertiCBSaveAssessment::find($assessmentId);
        // dd($certiIBSaveAssessment,$certiIBSaveAssessment->CertiIBCostTo);
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
}

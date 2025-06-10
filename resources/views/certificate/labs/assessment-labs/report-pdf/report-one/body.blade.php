
<body>
    <div class="lab-header">
        <div class="request_no" style="height: 35px;font-weight:bold" >
            <div class="inline-block w-45 p-0 float-left" > 
                <div style="display:block; width: 200px;height: 28px;float:left;line-height:28px;text-align:center;">
                    คำขอเลขที่ {{$tracking->reference_refno}} 
                </div>
            </div>
            <div class="inline-block w-50 p-0 float-right text-right" >
                <div style="display:block; width: 100px;height: 28px;float:right;line-height:28px;text-align:center;border: 0.5px solid #000;padding-top:5px">
                    รายงานที่ 1
                </div>
            </div>
        </div>
        <div class="report_header" style="font-size:28px" >
            รายงานการตรวจติดตามผลการรับรอง
        </div>
    </div>

    <div class="topic_one" style="margin-left: -5px;margin-top:10px">
        <table>
            <tr>
                <td style="width: 150px; font-weight: bold; vertical-align: top; ">
                    ชื่อห้องปฏิบัติการ :
                </td>
                
                <td>
                    {{$certi_lab->lab_name}}
                </td>
            </tr>
        </table>
    </div>

     <div class="topic_two" style="margin-left: -5px;">
        <table>
            <tr>
                <td style="width: 150px;font-weight:bold;vertical-align:top">
                    ที่ตั้งสำนักงานใหญ่ :
                </td>
                <td>
                   @if ($certi_lab->hq_address !== null) เลขที่ {{$certi_lab->hq_address}} @endif 
                        @if ($certi_lab->hq_moo !== null) หมู่{{$certi_lab->hq_moo}} @endif
                        @if ($certi_lab->hq_soi !== null) ซอย{{$certi_lab->hq_soi}} @endif
                        @if ($certi_lab->hq_road !== null) ถนน{{$certi_lab->hq_road}}  @endif
        
                            @if (strpos($certi_lab->HqProvinceName, 'กรุงเทพ') !== false)
                                <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                                แขวง {{$certi_lab->HqSubdistrictName}} เขต{{$certi_lab->HqDistrictName }} {{$certi_lab->HqProvinceName}}
                            @else
                                <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                                ตำบล{{$certi_lab->HqSubdistrictName}}  อำเภอ{{$certi_lab->HqDistrictName }}  จังหวัด{{$certi_lab->HqProvinceName}}
                            @endif
                    @if ($certi_lab->hq_zipcode !== null) {{$certi_lab->hq_zipcode}}  @endif
                 
                   <div style="margin-left: 300px;">
                        <span style="font-weight: bold">โทรศัพท์:</span><span>@if ($certi_lab->hq_telephone !== null) {{$certi_lab->hq_telephone}}  @endif</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">โทรสาร:</span> <span>@if ($certi_lab->hq_fax !== null) {{$certi_lab->hq_fax}}  @endif</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;font-weight:bold;vertical-align:top">
                    <div style="margin-left: 550px" >&nbsp;&nbsp;&nbsp;ที่ตั้งสำนักงานสาขา :</div>
                    
                </td>
                <td>
                    @if ($certi_lab->address_number !== null) เลขที่ {{$certi_lab->address_number}} @endif 
                        @if ($certi_lab->allay !== null) หมู่{{$certi_lab->allay}} @endif
                        @if ($certi_lab->address_soi !== null) ซอย{{$certi_lab->address_soi}} @endif
                        @if ($certi_lab->address_street !== null) ถนน{{$certi_lab->address_street}}  @endif
        
                            @if (strpos($certi_lab->basic_province->PROVINCE_NAME, 'กรุงเทพ') !== false)
                                <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                                แขวง {{$certi_lab->district_id}} เขต{{$certi_lab->amphur_id }} {{$certi_lab->basic_province->PROVINCE_NAME}}
                            @else
                                <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                                ตำบล{{$certi_lab->district_id}}  อำเภอ{{$certi_lab->amphur_id }}  จังหวัด{{$certi_lab->basic_province->PROVINCE_NAME}}
                            @endif

                    <div style="margin-left: 300px;">
                        <span style="font-weight: bold">โทรศัพท์:</span><span>@if ($certi_lab->tel !== null){{$certi_lab->tel}}  @endif</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">โทรสาร:</span> <span>@if ($certi_lab->tel_fax !== null) {{$certi_lab->tel_fax}}  @endif</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="topic_three" style="margin-left: -5px;margin-top:10px">
        <table>
            <tr>
                <td style="width: 150px; font-weight: bold; vertical-align: top; ">
                    วันที่ตรวจติดตาม :
                </td>
                
                <td>
                     {{HP::formatDateThaiFullPoint($assessment->created_at)}}
                </td>
            </tr>
        </table>
    </div>

    <div class="topic_four" style="margin-left: -5px">
        <div style="margin-left: 5px;margin-top: 10px;font-weight:bold">เจ้าหน้าที่ผู้ตรวจประเมิน: </div>
        <table style="margin-left: 20px; line-height: 1.2;">
            @php
                $index = 0;
            @endphp
            @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                @php
                    $index++;
                @endphp

                @foreach ($auditorIds as $auditorId)
                    @php
                        $info = HP::getExpertTrackingInfo($statusId, $auditorId);
                    @endphp
                        <tr>
                            <td style="width: 250px; padding: 0;">{{$index}}. {{HP::toThaiNumber($info->trackingAuditorsList->temp_users)}}</td>
                            <td style="padding: 0;">{{$info->statusAuditor->title}}</td>
                        </tr>
                @endforeach
            @endforeach
        </table>
    </div>

    <div class="topic_five" style="margin-left: -5px">
        <div style="margin-left: 5px;margin-top: 10px;font-weight:bold">บุคคลที่พบ: </div>
        <table style="margin-left: 20px; line-height: 1.2;">
            @if (!is_null($labReportOne->persons))
                @foreach (json_decode($labReportOne->persons, true) as $person)
                    <tr>
                        <td style="width: 250px; padding: 0;">{{ $person['name'] }}</td>
                        <td style="padding: 0;">{{ $person['position'] }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
         <p style="margin-left: 20px">และเจ้าหน้าที่ที่เกี่ยวข้อง</p>
    </div>
    
    <div class="topic_six" style="margin-left: -5px;margin-top:-15px">
        <div style="margin-left: 5px;margin-top: 10px;font-weight:bold">เอกสารที่อ้างอิง: </div>
        <table style="margin-left: 20px; line-height: 1.2;">
            @foreach ($attachFiles as $attachFile)
                <tr>
                    <td> {{$attachFile->filename}}</td>
                </tr>
            @endforeach
        </table>

    </div>
    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">ขอบข่ายที่ได้รับการรับรอง: </div>
        <div style="margin-left: 15px;">- ใบรับรองเลขที่ {{$tracking->certificate_export_to->certificate_no}} หมายเลขการรับรองที่ {{$tracking->certificate_export_to->accereditatio_no}} ฉบับที่      ออกให้ตั้งแต่วันที่ {{HP::formatDateThaiFullPoint($tracking->certificate_export_to->certificate_date_start)}} ถึงวันที่ {{HP::formatDateThaiFullPoint($tracking->certificate_export_to->certificate_date_end)}} </div>
     
    </div>

    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">ผลการตรวจประเมิน: </div>
        <div style="margin-left: 15px;">การตรวจประเมินครั้งนี้เป็นการตรวจติดตามผลการรับรองความสามารถห้องปฏิบัติการตามมาตรฐานเลขที่ มอก. 17025-2561 สำหรับหมายเลขการรับรองที่ {{$tracking->certificate_export_to->certificate_no}} คณะผู้ตรวจประเมินพบว่า    โดยมีประเด็นสำคัญดังนี้</div>
     
    </div>

    <div>
        <div style="margin-left: 10px;font-weight:bold">การตรวจติดตามผลการรับรอง</div>
            <table border="1" style="border-collapse: collapse; width: 99%;">
                <thead >
                    <tr style="background-color: #eeeaea;">
                        <th >รายการ</th>
                        <th >ผลการตรวจ</th>
                        <th  >หมายเหตุ</th>
                    {{-- </tr>
                    <tr style="background-color: #eeeaea;">
                        <th style="padding: 5px">ผลการตรวจ<br>ประเมิน</th>
                        <th style="padding: 5px">รายการที่ตรวจ</th>
                    </tr> --}}
                </thead>
                <tbody>
                    <tr class="group-header">
                        <td colspan="3" style="font-weight: bold">ส่วนที่ 1 ข้อกำหนด ตามมาตรฐานเลขที่ มอก. 17025-2561</td>
                    </tr>
                     <tr class="group-header">
                        <td colspan="3">4. ข้อกำหนดทั่วไป</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">4.1 ความเป็นกลาง</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_impartiality_no" {{ $labReportOne->chk_impartiality_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_impartiality_yes" {{ $labReportOne->chk_impartiality_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->impartiality_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">4.2 การรักษาความลับ</td>
                        <td style="text-align: center">
                              <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_confidentiality_no" {{ $labReportOne->chk_confidentiality_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_confidentiality_yes" {{ $labReportOne->chk_confidentiality_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                         <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->confidentiality_text}}
                        </td>
                    </tr>
                         <tr>
                        <td style="width: 300px">5. ข้อกำหนดด้านโครงสร้าง</td>
                        <td style="text-align: center">
                              <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_structure_no" {{ $labReportOne->chk_structure_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_structure_yes" {{ $labReportOne->chk_structure_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                         <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->structure_text}}
                        </td>
                    </tr>
                    <tr class="group-header">
                        <td colspan="3">6. ข้อกำหนดด้านทรัพยากร</td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">6.1 ทั่วไป</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_general_no" {{ $labReportOne->chk_res_general_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_general_yes" {{ $labReportOne->chk_res_general_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_general_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">6.2 บุคลากร</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_personnel_no" {{ $labReportOne->chk_res_personnel_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_personnel_yes" {{ $labReportOne->chk_res_personnel_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_personnel_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">6.3 สิ่งอำนวยความสะดวกและภาวะแวดล้อม</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_facility_no" {{ $labReportOne->chk_res_facility_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_facility_yes" {{ $labReportOne->chk_res_facility_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_facility_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">6.4 เครื่องมือ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_equipment_no" {{ $labReportOne->chk_res_equipment_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_equipment_yes" {{ $labReportOne->chk_res_equipment_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_equipment_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">6.5 ความสอบกลับได้ทางมาตรวิทยา</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_traceability_no" {{ $labReportOne->chk_res_traceability_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_traceability_yes" {{ $labReportOne->chk_res_traceability_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_traceability_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">6.6 ผลิตภัณฑ์และบริการจากภายนอก</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_external_no" {{ $labReportOne->chk_res_external_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_external_yes" {{ $labReportOne->chk_res_external_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_external_text}}
                        </td>
                    </tr>

                     <tr class="group-header">
                        <td colspan="3">7. ข้อกำหนดด้านกระบวนการ</td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">7.1 การทบทวนคำขอ ข้อเสนอการประมูล และข้อสัญญา</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_review_no" {{ $labReportOne->chk_proc_review_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_review_yes" {{ $labReportOne->chk_proc_review_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_review_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">7.2 การเลือก การทวนสอบ และการตรวจสอบความใช้ได้ของวิธี</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_method_no" {{ $labReportOne->chk_proc_method_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_method_yes" {{ $labReportOne->chk_proc_method_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_method_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">7.3 การชักตัวอย่าง</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_facility_no" {{ $labReportOne->chk_proc_method_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_sampling_yes" {{ $labReportOne->chk_proc_sampling_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_sampling_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">7.4 การจัดการตัวอย่างทดสอบหรือสอบเทียบ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_sample_handling_no" {{ $labReportOne->chk_proc_sample_handling_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_sample_handling_yes" {{ $labReportOne->chk_proc_sample_handling_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_sample_handling_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">7.5 บันทึกทางด้านวิชาการ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_tech_record_no" {{ $labReportOne->chk_proc_tech_record_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_tech_record_yes" {{ $labReportOne->chk_proc_tech_record_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_tech_record_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">7.6 การประเมินค่าความไม่แน่นอนของการวัด</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_uncertainty_no" {{ $labReportOne->chk_proc_uncertainty_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_uncertainty_yes" {{ $labReportOne->chk_proc_uncertainty_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_uncertainty_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">7.7 การสร้างความมั่นใจในความใช้ได้ของผล</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_validity_no" {{ $labReportOne->chk_proc_validity_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_validity_yes" {{ $labReportOne->chk_proc_validity_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_validity_text}}
                        </td>
                    </tr>
                     <tr>
                        <td style="padding-left: 15px;width: 300px">7.8 การรายงานผล</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_reporting_no" {{ $labReportOne->chk_proc_reporting_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_reporting_yes" {{ $labReportOne->chk_proc_reporting_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_reporting_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">7.9 ข้อร้องเรียน</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_complaint_no" {{ $labReportOne->chk_proc_complaint_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_complaint_yes" {{ $labReportOne->chk_proc_complaint_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_complaint_text}}
                        </td>
                    </tr>                    
                     <tr>
                        <td style="padding-left: 15px;width: 300px">7.10 งานที่ไม่เป็นไปตามข้อกำหนด</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_nonconformity_no" {{ $labReportOne->chk_proc_nonconformity_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_nonconformity_yes" {{ $labReportOne->chk_proc_nonconformity_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_nonconformity_text}}
                        </td>
                    </tr>                   
                    <tr>
                        <td style="padding-left: 15px;width: 300px">7.11 การควบคุมข้อมูลและการจัดการสารสนเทศ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_data_control_no" {{ $labReportOne->chk_proc_data_control_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_data_control_yes" {{ $labReportOne->chk_proc_data_control_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_data_control_text}}
                        </td>
                    </tr>


                      <tr class="group-header">
                        <td colspan="3">8. ข้อกำหนดด้านระบบการบริหารงาน</td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">8.1 การเลือก</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_selection_no" {{ $labReportOne->chk_res_selection_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_selection_yes" {{ $labReportOne->chk_res_selection_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_selection_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">8.2 เอกสารระบบการบริหารงาน (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_docsystem_no" {{ $labReportOne->chk_res_docsystem_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_docsystem_yes" {{ $labReportOne->chk_res_docsystem_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_docsystem_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">8.3 การควบคุมเอกสารระบบการบริหารงาน (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_doccontrol_no" {{ $labReportOne->chk_res_doccontrol_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_doccontrol_yes" {{ $labReportOne->chk_res_doccontrol_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_doccontrol_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">8.4 การควบคุมบันทึก (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_recordcontrol_no" {{ $labReportOne->chk_res_recordcontrol_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_recordcontrol_yes" {{ $labReportOne->chk_res_recordcontrol_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_recordcontrol_text}}
                        </td>
                    </tr>
                           <tr>
                        <td style="padding-left: 15px;width: 300px">8.5 การปฏิบัติการเพื่อระบุความเสี่ยงและโอกาส (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_riskopportunity_no" {{ $labReportOne->chk_res_riskopportunity_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_riskopportunity_yes" {{ $labReportOne->chk_res_riskopportunity_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_riskopportunity_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">8.6 การปรับปรุง (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_improvement_no" {{ $labReportOne->chk_res_improvement_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_improvement_yes" {{ $labReportOne->chk_res_improvement_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_improvement_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">8.7 การปฏิบัติการแก้ไข (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_proc_validity_no" {{ $labReportOne->chk_proc_validity_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_proc_validity_yes" {{ $labReportOne->chk_proc_validity_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->proc_validity_text}}
                        </td>
                    </tr>
                     <tr>
                        <td style="padding-left: 15px;width: 300px">8.8 การตรวจติดตามภายใน (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_corrective_no" {{ $labReportOne->chk_res_corrective_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_corrective_yes" {{ $labReportOne->chk_res_corrective_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_corrective_text}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px;width: 300px">8.9 การทบทวนการบริหาร (ทางเลือก ก)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="chk_res_audit_no" {{ $labReportOne->chk_res_audit_no == 1 ? 'checked="checked"' : '' }}> 
                                ไม่พบ  <input type="checkbox" id="chk_res_audit_yes" {{ $labReportOne->chk_res_audit_yes == 1 ? 'checked="checked"' : '' }}>  พบข้อบกพร่อง 
                            </div>
                        </td>
                        <td style="padding-left: 5px;font-size:22px;width: 200px">
                           {{$labReportOne->res_audit_text}}
                        </td>
                    </tr>                    

                 
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">ส่วนที่ 2 การเฝ้าระวังการฝ่าฝืนหลักเกณฑ์ วิธีการและเงื่อนไขการรับรองห้องปฏิบัติการ ตาม: </div>
        <div style="margin-left: 25px;">(1) กฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 <br>
(2) หลักเกณฑ์ วิธีการและเงื่อนไขการโฆษณาของผู้ประกอบการตรวจสอบและรับรองและ ผู้ประกอบกิจการ <br>
(3) เอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรองสำหรับ ห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01) <br></div>
     
    </div>


    <div>
         <div style="display: inline-block; width:100%; float:left; margin-left:25px"> <b>2.1 การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</b></div>
        <div style="display: inline-block;margin-left:22px; width:100%; float:left">
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_display_certification_none === "1" ? 'checked="checked"' : '' }}></div>
                <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการแสดง </div>
            </div>
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_display_certification_yes === "1" ? 'checked="checked"' : '' }}></div>
                <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการแสดง ดังนี้</div>
            </div>
            <div style="margin-left: 55px">
                <div>
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_scope_certified_only === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">เฉพาะขอบข่ายที่ได้รับการรับรอง</div>
                </div>
                <div>
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_scope_certified_all === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_activities_not_certified_yes === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_activities_not_certified_no === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                </div>
            </div>
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
            </div>
            

            <div style="margin-left: 55px">

                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_accuracy_correct === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->report_accuracy_incorrect === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                        
                        @if ($labReportOne->report_accuracy_detail != "")
                            {{$labReportOne->report_accuracy_detail}} 
                            @else   
                            ...........................................................................................................  
                            @endif
                        
                    </div>
                </div>   
            </div>
        </div>
    </div>
    <div>
        <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>2.2 กรณีได้รับการรับรองห้องปฏิบัติการหลายสถานที่ (Multi-site)</b> การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</div>
        <div style="display: inline-block;margin-left:22px; width:100%; float:left">
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_display_certification_none === "1" ? 'checked="checked"' : '' }}></div>
                <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการแสดง </div>
            </div>
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_display_certification_yes === "1" ? 'checked="checked"' : '' }}></div>
                <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการแสดง ดังนี้</div>
            </div>
            <div style="margin-left: 55px">
                <div>
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_scope_certified_only === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">เฉพาะขอบข่ายที่ได้รับการรับรอง</div>
                </div>
                <div>
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_scope_certified_all === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_activities_not_certified_yes === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_activities_not_certified_no === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                </div>
            </div>
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
            </div>
            

            <div style="margin-left: 55px">

                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_accuracy_correct === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->multisite_accuracy_incorrect === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                        @if ($labReportOne->multisite_accuracy_detail != "")
                        {{$labReportOne->multisite_accuracy_detail}} 
                        @else   
                        ...........................................................................................................  
                        @endif
                        
                    </div>
                </div>   
            </div>
        </div>
    </div>

    <div>
        <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>2.3 กรณีห้องปฏิบัติการสอบเทียบ ป้ายแสดงสถานะการสอบเทียบ</b></div>
        <div style="display: inline-block;margin-left:22px; width:100%; float:left">
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ส่วนที่ 2 (1) – (3) ข้างต้น</div>
            </div>
            

            <div style="margin-left: 55px">

                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->certification_status_correct === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->certification_status_incorrect === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                        @if ($labReportOne->certification_status_details != "")
                        {{$labReportOne->certification_status_details}} 
                        @else   
                        ...........................................................................................................  
                        @endif
                        
                    </div>
                </div>   
            </div>

        </div>
    </div>
            
<div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>2.4 การแสดงการได้รับการรับรองที่อื่น นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</b>  </div>
    <div style="display: inline-block;margin-left:22px; width:100%; float:left">
    <div style="margin-left: 55px">

        <div style="margin-left: 25px">
            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->other_certification_status_correct === "1" ? 'checked="checked"' : '' }}></div>
            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่มี</div>
        </div>
        <div style="margin-left: 25px">
            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->other_certification_status_incorrect === "1" ? 'checked="checked"' : '' }}></div>
            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">มี ระบุ 
                @if ($labReportOne->other_certification_status_details != "")
                {{$labReportOne->other_certification_status_details}} 
                @else   
                ...........................................................................................................  
                @endif
                
            </div>
        </div>   
    </div>
</div>

<div>
   <div style="margin-left:37px"> 
        <div style="display: inline-block; width:100%; float:left ; font-weight:bold"><b>ส่วนที่ 3 การปฏิบัติตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่างประเทศว่าด้วยการรับรองห้องปฏิบัติการ (ILAC) และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01)</b></div>
        
        <table autosize="1"  style="margin-left: 10px">
            <tr>
                <td style="width: 150px;vertical-align:top">
                    <div>ห้องปฏิบัติการ <input type="checkbox" {{ $labReportOne->inp_2_5_6_2_lab_availability_yes === "1" ? 'checked="checked"' : '' }}> มี</div>
                </td>
                <td style="width: 55px;vertical-align:top"><div><input type="checkbox" {{ $labReportOne->inp_2_5_6_2_lab_availability_no === "1" ? 'checked="checked"' : '' }}> ไม่มี</div></td>
                
            </tr>
        </table>
        <div style="display: inline-block; width:100%; float:left; margin-left:10px"> การลงนามในข้อตกลงการใช้เครื่องหมาย ILAC MRA ร่วมกับเครื่องหมายมาตรฐานทั่วไปสำหรับผู้รับใบรับรอง ร่วมกับสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</div>
        <div style="display: inline-block; width:100%; float:left; margin-left:10px"> <b><u>กรณีห้องปฏิบัติการและสำนักงานมีข้อตกลงร่วมกัน</u></b></div>        

        <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>3.1 การแสดงเครื่องหมายร่วม ILAC MRA ในใบรายงานผลการทดสอบ/สอบเทียบ</b>  </div>
        <div style="display: inline-block;margin-left:22px; width:100%; float:left">
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_display_no === "1" ? 'checked="checked"' : '' }}></div>
                <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการแสดง </div>
            </div>
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_display_yes === "1" ? 'checked="checked"' : '' }}></div>
                <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการแสดง ดังนี้</div>
            </div>
            <div style="margin-left: 55px">
                <div>
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_scope_no === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">เฉพาะขอบข่ายที่ได้รับการรับรอง</div>
                </div>
                <div>
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_scope_yes === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_disclosure_yes === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_disclosure_no === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                </div>
            </div>
            <div style="margin-left: 35px">
                <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น</div>
            </div>
            

            <div style="margin-left: 55px">

                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_compliance_correct === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                </div>
                <div style="margin-left: 25px">
                    <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->ilac_mra_compliance_incorrect === "1" ? 'checked="checked"' : '' }}></div>
                    <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                        @if ($labReportOne->ilac_mra_compliance_details != "")
                        {{$labReportOne->ilac_mra_compliance_details}} 
                        @else   
                        ............................................................................................................
                        @endif
                        
                    </div>
                </div>   
            </div>
        </div>

            <div style="display: inline-block; width:100%; float:left; margin-left:25px"> <b>3.2 การแสดงเครื่องหมายร่วม ILAC MRA นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</b>  </div>
            <div style="display: inline-block;margin-left:22px; width:100%; float:left">
                <div style="margin-left: 55px">

                    <div style="margin-left: 25px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->other_ilac_mra_compliance_no === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่มี</div>
                    </div>
                    <div style="margin-left: 25px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->other_ilac_mra_compliance_yes === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">มี ระบุ 
                            @if ($labReportOne->other_ilac_mra_compliance_details != "")
                            {{$labReportOne->other_ilac_mra_compliance_details}} 
                            @else   
                            .......................................................................................................................
                            @endif
                            
                        </div>
                    </div>   
                </div>

                <div style="margin-left: 35px">
                    <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น</div>
                </div>
                

                <div style="margin-left: 56px">

                    <div style="margin-left: 25px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->mra_compliance_correct === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                    </div>
                    <div style="margin-left: 25px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportOne->mra_compliance_incorrect === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                            @if ($labReportOne->mra_compliance_details != "")
                            {{$labReportOne->mra_compliance_details}} 
                            @else   
                            ...........................................................................................................
                            @endif
                            
                        </div>
                    </div>   
                </div>
            </div>
    </div>
</div>

    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">การแสดงเครื่องหมายการรับรองบนรายงานผล: </div>
        <div style="margin-left: 15px;">
 ไม่เป็น <input type="checkbox" {{ $labReportOne->offer_agreement_yes === "1" ? 'checked="checked"' : '' }}> เป็น <input type="checkbox" {{ $labReportOne->offer_agreement_no === "1" ? 'checked="checked"' : '' }}> ไปตามกฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรองสำหรับ ห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01)</div>
     
    </div>

    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">การแสดงเครื่องหมายการรับรองบนรายงานผล: </div>
        <div style="margin-left: 15px;">
 ไม่เป็น <input type="checkbox" {{ $labReportOne->offer_ilac_agreement_yes === "1" ? 'checked="checked"' : '' }}> เป็น <input type="checkbox" {{ $labReportOne->offer_ilac_agreement_yes === "1" ? 'checked="checked"' : '' }}> ไปตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่าง ประเทศด้วยการรับรองห้องปฏิบัติการสำหรับห้องปฏิบัติการและหน่วยตรวจ และเอกสารวิชาการ เรื่องนโยบาย สำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับ ใบรับรอง (TLI-01) (ถ้ามี)</div>
     
    </div>






<div style="page-break-inside: avoid;">
    <table style="width: 100%;text-align:center;margin-top:30px">
        <tr>
            <td style="width:40%"></td>
            <td>  <img src="{{public_path($signer->signer_url1)}}" style="width: 70px" alt=""></td>
        </tr>
        <tr>
            <td style="width:40%"></td>
            <td>{{$signer->signer_1->signer_name}}</td>
        </tr>
        <tr>
            <td></td>
            <td>{{$signer->signer_1->signer_position}}</td>
        </tr>
        <tr>
            <td style="width:40%"></td>
            <td>{{HP::formatDateThaiFullNumThai($signer->signer_1->updated_at)}}</td>
        </tr>
    </table>
    
    <table style="width: 100%;text-align:center;margin-top:20px">
        <tr>
            <td style="width:40%"></td>
            <td>  <img src="{{public_path($signer->signer_url2)}}" style="width: 70px" alt=""></td>
        </tr>
        <tr>
            <td style="width:40%"></td>
            <td>{{$signer->signer_2->signer_name}}</td>
        </tr>
        <tr>
            <td></td>
            <td>{{$signer->signer_2->signer_position}}</td>
        </tr>
        <tr>
            <td style="width:40%"></td>
            <td>{{HP::formatDateThaiFullNumThai($signer->signer_2->updated_at)}}</td>
        </tr>
    </table>
    
    <table style="width: 100%;text-align:center;margin-top:20px">
        <tr>
            <td style="width:40%"></td>
            <td>  <img src="{{public_path($signer->signer_url3)}}" style="width: 70px" alt=""></td>
        </tr>
        <tr>
            <td style="width:40%"></td>
            <td>{{$signer->signer_3->signer_name}}</td>
        </tr>
        <tr>
            <td></td>
            <td>{{$signer->signer_3->signer_position}}</td>
        </tr>
        <tr>
            <td style="width:40%"></td>
            <td>{{HP::formatDateThaiFullNumThai($signer->signer_3->updated_at)}}</td>
        </tr>
    </table>
</div> 



</body>
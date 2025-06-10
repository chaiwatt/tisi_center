
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
                    รายงานที่ 2
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
            @if (!is_null($labReportTwo->persons))
                @foreach (json_decode($labReportTwo->persons, true) as $person)
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
        <div style="margin-left: 15px;">การตรวจประเมินครั้งนี้เป็นการตรวจติดตามการปฏิบัติการแก้ไขข้อบกพร่องและข้อสังเกตจากการตรวจติดตาม ผลการรับรองความสามารถของห้องปฏิบัติการ เมื่อวันที่ {{$audit_date}} ซึ่งพบข้อบกพร่องและข้อสังเกตทั้งสิ้น {{$labReportTwo->observation_count_text}} รายการ คณะผู้ตรวจประเมินได้พิจารณาหลักฐานการปฏิบัติการแก้ไขข้อบกพร่องที่ห้องปฏิบัติการส่งให้สำนักงาน พิจารณา ได้แก่ หนังสือของห้องปฏิบัติการ{{$certi_lab->lab_name}}  ลงรับวันที่ {{$labReportTwo->lab_letter_received_date_text}} /ไปรษณีย์อิเล็กทรอนิกส์ วันที่ {{$labReportTwo->email_sent_date_tertiary_text}} (ถ้ามี) และ {{$labReportTwo->email_sent_date_secondary_text}} (ถ้ามี)</div>
        <div style="margin-left: 50px;margin-top: 10px">โดยมีรายละเอียดดังสรุปการแก้ไขข้อบกพร่องของห้องปฏิบัติการที่แนบ</div>
        <div style="margin-left: 70px"><input type="checkbox" {{ $labReportTwo->checkbox_corrective_action_completed === "1" ? 'checked="checked"' : '' }}> คณะผู้ตรวจประเมินพบว่าห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องได้แล้วเสร็จสอดคล้องตามมาตรฐาน เลขที่ มอก. 17025-2561</div>
        <div style="margin-left: 70px"><input type="checkbox" {{ $labReportTwo->checkbox_corrective_action_incomplete === "1" ? 'checked="checked"' : '' }}> คณะผู้ตรวจประเมินมีความเห็นว่า ห้องปฏิบัติการได้ดำเนินการแก้ไขข้อบกพร่องแล้วเสร็จ จำนวน {{$labReportTwo->remaining_nonconformities_count_text}} รายการ ยังคงเหลือข้อบกพร่อง {{$labReportTwo->remaining_nonconformities_list_text}} รายการ  </div>
    </div>

 
    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">สรุปผลการตรวจติดตามผลการรับรอง: </div>
        <div style="margin-left: 50px;margin-top: 10px">จากผลการตรวจประเมินและผลการปฏิบัติการแก้ไขข้อบกพร่อง คณะผู้ตรวจประเมินเห็นว่า</div>
        <div style="margin-left: 70px"><input type="checkbox" {{ $labReportTwo->checkbox_extend_certification === "1" ? 'checked="checked"' : '' }}> ห้องปฏิบัติการยังคงรักษาระบบการบริหารงาน และการดำเนินงานด้านวิชาการเป็นไปตามมาตรฐานเลขที่ มอก. 17025-2561 เห็นควรให้คงสถานะการรับรองต่อไป ทั้งนี้ หากห้องปฏิบัติการประสงค์จะต่ออายุใบ รับรองจะต้องยื่นคำขอต่ออายุล่วงหน้าไม่น้อยกว่า 120 วัน ก่อนวันที่ใบรับรองสิ้นอายุ</div>
        <div style="margin-left: 70px"><input type="checkbox" {{ $labReportTwo->checkbox_reject_extend_certification === "1" ? 'checked="checked"' : '' }}> ห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะ ผู้ตรวจประเมิน แต่ผลจากการแก้ไขส่งผลกระทบต่อขอบข่ายที่ได้รับการรับรอง จึงเห็นควรนำเสนอคณะ อนุกรรมการพิจารณา รับรองห้องปฏิบัติการ {{$labReportTwo->reason_for_extension_decision_text}} เพื่อพิจารณาลดสาขาและขอบข่ายการรับรองต่อไป</div>
        <div style="margin-left: 70px"><input type="checkbox" {{ $labReportTwo->checkbox_submit_remaining_evidence === "1" ? 'checked="checked"' : '' }}> ห้องปฏิบัติการต้องส่งหลักฐานการแก้ไขข้อบกพร่องที่เหลืออยู่ {{$labReportTwo->remaining_evidence_items_text}} รายการ  ให้คณะผู้ตรวจประเมินพิจารณา ภายในวันที่ {{$labReportTwo->remaining_evidence_due_date_text}} (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่องครั้งแรก) เมื่อพ้นกำหนดระยะเวลา ดังกล่าว หากห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จ อย่างมีประสิทธิผล และเป็นที่ยอมรับของคณะผู้ตรวจประเมิน คณะผู้ตรวจประเมินจะนำเสนอให้คณะอนุกรรมการพิจารณา รับรองห้องปฏิบัติการ{{$certi_lab->lab_name}} พิจารณาให้ห้องปฏิบัติการลดขอบข่าย/พักใช้ใบรับรองต่อไป</div>
        <div style="margin-left: 70px"><input type="checkbox" {{ $labReportTwo->checkbox_unresolved_nonconformities === "1" ? 'checked="checked"' : '' }}> ห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ ยอมรับของคณะผู้ตรวจประเมิน  สมควรนำเสนอคณะอนุกรรมการพิจารณารับรองห้องปฏิบัติการ{{$certi_lab->lab_name}} พิจารณา</div>
        <div style="margin-left: 90px"><input type="checkbox" {{ $labReportTwo->checkbox_reduce_scope === "1" ? 'checked="checked"' : '' }}> ลดสาขาและขอบข่ายการรับรอง (กรณีข้อบกพร่องที่ไม่สามารถแก้ไขได้กระทบความสามารถบาง สาขาการรับรอง)</div>
        <div style="margin-left: 90px"><input type="checkbox" {{ $labReportTwo->checkbox_suspend_certificate === "1" ? 'checked="checked"' : '' }}> พักใช้ใบรับรอง (กรณีข้อบกพร่องที่ไม่สามารถแก้ไขได้กระทบความสามารถต่อการรับรองทั้งหมดที่ ได้รับใบรับรอง)</div>
    </div>

    {{-- <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">การแสดงเครื่องหมายการรับรองบนรายงานผล: </div>
        <div style="margin-left: 15px;">
 ไม่เป็น <input type="checkbox" {{ $labReportTwo->offer_agreement_yes === "1" ? 'checked="checked"' : '' }}> เป็น <input type="checkbox" {{ $labReportTwo->offer_agreement_no === "1" ? 'checked="checked"' : '' }}> ไปตามกฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรองสำหรับ ห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01)</div>
     
    </div>

    <div class="topic_seven">
        <div style="margin-top: 10px;font-weight:bold">การแสดงเครื่องหมายการรับรองบนรายงานผล: </div>
        <div style="margin-left: 15px;">
 ไม่เป็น <input type="checkbox" {{ $labReportTwo->offer_ilac_agreement_yes === "1" ? 'checked="checked"' : '' }}> เป็น <input type="checkbox" {{ $labReportTwo->offer_ilac_agreement_yes === "1" ? 'checked="checked"' : '' }}> ไปตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่าง ประเทศด้วยการรับรองห้องปฏิบัติการสำหรับห้องปฏิบัติการและหน่วยตรวจ และเอกสารวิชาการ เรื่องนโยบาย สำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับ ใบรับรอง (TLI-01) (ถ้ามี)</div>
     
    </div> --}}






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
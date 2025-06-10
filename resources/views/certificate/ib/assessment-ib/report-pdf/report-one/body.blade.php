

<body>
    <div class="lab-header">
        <div class="report_header" style="font-size:25px">
            รายงานการตรวจประเมิน ณ สถานประกอบการ
        </div>
    </div>

    <div class="topic_one" style="margin-left: -5px;margin-top:10px">
        <table>
            <tr>
                <td style="width: 150px; font-weight: bold; vertical-align: top; ">
                    1. หน่วยตรวจ :
                </td>
                
                <td>
                    {{$certi_ib->name_unit}}  
                </td>
            </tr>
        </table>
    </div>

    
    <div class="topic_two" style="margin-left: -5px;">
        <table>
            <tr>
                <td style="width: 150px;font-weight:bold;vertical-align:top">
                    2. ที่ตั้งสำนักงานใหญ่ :
                </td>
                <td>
                   @if ($certi_ib->hq_address !== null) เลขที่ {{$certi_ib->hq_address}} @endif 
                   @if ($certi_ib->hq_moo !== null) หมู่{{$certi_ib->hq_moo}} @endif
                   @if ($certi_ib->hq_soi !== null) ซอย{{$certi_ib->hq_soi}} @endif
                   @if ($certi_ib->hq_road !== null) ถนน{{$certi_ib->hq_road}}  @endif
   
                       @if (strpos($certi_ib->HqProvinceName, 'กรุงเทพ') !== false)
                           <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                           แขวง {{$certi_ib->HqSubdistrictName}} เขต{{$certi_ib->HqDistrictName }} {{$certi_ib->HqProvinceName}}
                       @else
                           <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                           ตำบล{{$certi_ib->HqSubdistrictName}}  อำเภอ{{$certi_ib->HqDistrictName }}  จังหวัด{{$certi_ib->HqProvinceName}}
                       @endif
                   @if ($certi_ib->hq_zipcode !== null) {{$certi_ib->hq_zipcode}}  @endif  
                 
                   <div style="margin-left: 300px;">
                        <span style="font-weight: bold">โทรศัพท์:</span><span>@if ($certi_ib->hq_telephone !== null) {{$certi_ib->hq_telephone}}  @endif</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">โทรสาร:</span> <span>@if ($certi_ib->hq_fax !== null) {{$certi_ib->hq_fax}}  @endif</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;font-weight:bold;vertical-align:top">
                    <div style="margin-left: 550px" >&nbsp;&nbsp;&nbsp;ที่ตั้งสำนักงานสาขา :</div>
                    
                </td>
                <td>
                    @if ($certi_ib->address_number !== null) เลขที่ {{$certi_ib->address_number}} @endif 
                    @if ($certi_ib->allay !== null) หมู่{{$certi_ib->allay}} @endif
                    @if ($certi_ib->address_soi !== null) ซอย{{$certi_ib->address_soi}} @endif
                    @if ($certi_ib->address_street !== null) ถนน{{$certi_ib->address_street}}  @endif
    
                        @if (strpos($certi_ib->basic_province->PROVINCE_NAME, 'กรุงเทพ') !== false)
                            <!-- ถ้า province มีคำว่า "กรุงเทพ" -->
                            แขวง {{$certi_ib->district_id}} เขต{{$certi_ib->amphur_id }} {{$certi_ib->basic_province->PROVINCE_NAME}}
                        @else
                            <!-- ถ้า province ไม่ใช่ "กรุงเทพ" -->
                            ตำบล{{$certi_ib->district_id}}  อำเภอ{{$certi_ib->amphur_id }}  จังหวัด{{$certi_ib->basic_province->PROVINCE_NAME}}
                        @endif
                    @if ($certi_ib->postcode !== null) {{$certi_ib->postcode}}  @endif

                    <div style="margin-left: 300px;">
                        <span style="font-weight: bold">โทรศัพท์:</span><span>@if ($certi_ib->tel !== null) {{$certi_ib->tel}}  @endif</span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">โทรสาร:</span> <span>@if ($certi_ib->tel_fax !== null) {{$certi_ib->tel_fax}}  @endif</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @php
        $standard_changes     =  ['1'=>'ยื่นขอครั้งแรก','2'=>'ต่ออายุใบรับรอง','3'=>'ขยายขอบข่าย','4'=>'การเปลี่ยนแปลงมาตรฐาน','5'=>'ย้ายสถานที่','6'=>'โอนใบรับรอง'];
        $standard_change_id = $certi_ib->standard_change

    @endphp 
    <div class="topic_three">
        <div style="font-weight:bold">3. ประเภทการตรวจประเมิน</div>
        <div style="margin-left: 15px">
            <table>
                <tr>
                    <td style="width: 300px">
                        <input type="checkbox" {{ $certi_ib->standard_change == "1" ? 'checked="checked"' : '' }}>
 การตรวจประเมินรับรองครั้งแรก 
                    </td>
                    <td>
                        <input type="checkbox" > การตรวจติดตาม  
                    </td>
                </tr>
                <tr>
                    <td style="width: 300px">
                        <input type="checkbox" {{ $certi_ib->standard_change == "2" ? 'checked="checked"' : '' }}>
                        การตรวจประเมินเพื่อต่ออายุการรับรอง 
                    </td>
                    <td>
                        <input type="checkbox" {{ ($certi_ib->standard_change != 1 && $certi_ib->standard_change != 2) ? 'checked="checked"' : '' }}> อื่น ๆ  
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="topic_four">
        <div style="margin-top: 10px;font-weight:bold">4. สาขาและขอบข่ายการรับรองระบบงาน :</div>
        <div style="margin-left: 20px">
            @if(count($assessment->FileAttachAssessment2Many) > 0 ) 
                @foreach($assessment->FileAttachAssessment2Many as  $key => $item)
                   <div>({{$key+1}}). <a style="text-decoration: none;" href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                    title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                       {{$item->file_client_name}}
                    </a> </div> 
                @endforeach
            @endif
        </div>
    </div>

    <div class="topic_four">
        <div style="margin-top: 10px;font-weight:bold">5. เกณฑ์ที่ใช้ในการตรวจประเมิน: </div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->eval_riteria_text!!}
        </div>
    </div>

    <div class="topic_four">
        @php
            use Carbon\Carbon;
            
           $startDate = Carbon::parse($trackingAuditorsDate->start_date);
                        $endDate = Carbon::parse($trackingAuditorsDate->end_date);

            // ฟังก์ชันแปลงเดือนเป็นภาษาไทย
            function getThaiMonth($month) {
                $months = [
                    'January' => 'มกราคม', 'February' => 'กุมภาพันธ์', 'March' => 'มีนาคม',
                    'April' => 'เมษายน', 'May' => 'พฤษภาคม', 'June' => 'มิถุนายน',
                    'July' => 'กรกฎาคม', 'August' => 'สิงหาคม', 'September' => 'กันยายน',
                    'October' => 'ตุลาคม', 'November' => 'พฤศจิกายน', 'December' => 'ธันวาคม'
                ];
                return $months[$month] ?? $month;
            }

            // ดึงวัน เดือน และปี
            $startDay = $startDate->day;
            $startMonth = getThaiMonth($startDate->format('F'));
            $startYear = $startDate->year + 543; // แปลงเป็นปี พ.ศ.

            $endDay = $endDate->day;
            $endMonth = getThaiMonth($endDate->format('F'));
            $endYear = $endDate->year + 543; // แปลงเป็นปี พ.ศ.

            // ตรวจสอบว่าเป็นวันเดียวกันหรือไม่
            if ($startDate->equalTo($endDate)) {
                $formattedDate = "{$startDay} {$startMonth} {$startYear}";
            } elseif ($startMonth === $endMonth && $startYear === $endYear) {
                $formattedDate = "{$startDay}-{$endDay} {$startMonth} {$startYear}";
            } else {
                $formattedDate = "{$startDay} {$startMonth} {$startYear} - {$endDay} {$endMonth} {$endYear}";
            }
        @endphp

        <table style="margin-top: 10px;">
            <tr>
                <td style="width: 220px;font-weight:bold;vertical-align:top">
                    6. เกณฑ์ที่ใช้ในการตรวจประเมิน :
                </td>
                <td>
                    {{ $formattedDate }}
                </td>
            </tr>
        </table>

        <div class="topic_four">
            <div style="margin-left: 5px;margin-top: 10px;font-weight:bold">7. คณะผู้ตรวจประเมิน: </div>
              @php
                        $auditors_statuses= $trackingAuditor->auditors_status_many;
                    @endphp
            <table style="margin-left: 20px; line-height: 1.2;">
                {{-- @foreach ($assessment->CertiIBAuditorsTo->CertiIBAuditorsLists as $key => $auditor)
                    <tr>
                        <td style="width: 250px; padding: 0;">({{$key+1}}). {{$auditor->temp_users}}</td>
                        <td style="padding: 0;">{{$auditor->StatusAuditorTo->title}}</td>
                    </tr>
                @endforeach --}}

                 @foreach ($auditors_statuses as $key => $auditors_status)
                          @php
                               $auditors = $auditors_status->auditors_list_many; 
                          @endphp

                          @foreach ($auditors as $auditor)
                               {{-- <p><span style="display: inline-block;width: 300px;">{{$key+1}}. {{$auditor->temp_users}}</span> <span>{{$auditor->temp_departments}}</span> </p> --}}

                            <tr>
                                <td style="width: 250px; padding: 0;">{{$key+1}}. {{$auditor->temp_users}}</td>
                                <td style="padding: 0;">{{$auditor->temp_departments}}</td>
                            </tr>
                          @endforeach
                            
                        @endforeach
            </table>
        </div>

        <div class="topic_four">
            <div style="margin-left: 5px;margin-top: 10px;font-weight:bold">8. ผู้แทนหน่วยตรวจ: </div>
            <table style="margin-left: 20px; line-height: 1.2;">
                {{-- @foreach ($assessment->auditorIbRepresentatives as $key => $auditorIbRepresentative)
                    <tr>
                        <td style="width: 250px; padding: 0;">({{ $key+1 }}). {{ $auditorIbRepresentative->name }}</td>
                        <td style="padding: 0;">{{ $auditorIbRepresentative->position }}</td>
                    </tr>
                @endforeach --}}

                @if (!is_null($ibReportOne->persons))
                @foreach (json_decode($ibReportOne->persons, true) as $person)
                    <tr>
                        <td style="width: 250px; padding: 0;">{{ $person['name'] }}</td>
                        <td style="padding: 0;">{{ $person['position'] }}</td>
                    </tr>
                @endforeach
            @endif
            </table>
        </div>

        <div class="topic_four">
            <div style="margin-top: 10px;font-weight:bold">9. เอกสารอ้างอิงที่ใช้ในการตรวจประเมิน:</div>
            <div style="margin-left: 20px">
                {{-- @if(count($referenceDocuments) > 0 ) 
                    @foreach($referenceDocuments as $key => $item)
                       <div>({{$key+1}}). <a style="text-decoration: none;" href="{{url('certify/check/file_ib_client/'.$item->file.'/'.( !empty($item->file_client_name) ? $item->file_client_name : 'null' ))}}" 
                        title="{{ !empty($item->file_client_name) ? $item->file_client_name :  basename($item->file) }}" target="_blank">
                           {{$item->file_client_name}}
                        </a> </div> 
                    @endforeach
                @endif --}}
                @foreach ($attachFiles as $attachFile)
                <tr>
                    <td> {{$attachFile}}</td>
                </tr>
            @endforeach
            </div>

            {{-- <div style="margin-left: 20px">
                <span style="font-weight: 600">เอกสารแก้ข้อบกพร่อง :</span>
                @if(count($assessment->CertiIBBugMany) > 0 ) 
                    @foreach($assessment->CertiIBBugMany as $key => $item)
                       <div>({{$key+1}}). <a style="text-decoration: none;" href="{{url('certify/check/file_ib_client/'.$item->attachs.'/'.( !empty($item->attach_client_name) ? $item->attach_client_name : basename($item->attachs) ))}}" 
                        title="{{ !empty($item->attach_client_name) ? $item->attach_client_name :  basename($item->attachs) }}" target="_blank">
                           {{$item->attach_client_name}}
                        </a> </div> 
                    @endforeach
                @endif
            </div> --}}
        </div>


    <div class="topic_four">
        <div style="margin-top: 10px;font-weight:bold">10. รายละเอียดการตรวจประเมิน: </div>
        <div style="margin-left: 10px;font-weight:bold">10.1 ความเป็นมา</div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->background_history!!}
        </div>
    </div>
    <div class="topic_four">
        <div style="margin-left: 10px;font-weight:bold">10.2 กระบวนการตรวจประเมิน</div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->insp_proc!!}
        </div>
    </div>

    <div>
        <div style="margin-left: 10px;font-weight:bold">10.4 รายละเอียดการตรวจประเมิน</div>
            <table border="1" style="border-collapse: collapse; width: 99%;">
                <thead >
                    <tr style="background-color: #eeeaea;">
                        <th rowspan="2">เกณฑ์ที่ใช้ในการตรวจประเมิน</th>
                        <th colspan="2">รายการที่ตรวจ</th>
                        <th rowspan="2" style="width: 140px">หมายเหตุ</th>
                    </tr>
                    <tr style="background-color: #eeeaea;">
                        <th style="padding: 5px">ผลการตรวจ<br>ประเมิน</th>
                        <th style="padding: 5px">รายการที่ตรวจ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="group-header">
                        <td colspan="4">มอก. 17020-2556 และ ILAC-P15: 05/2020</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 4.1 ความเป็นกลางและความเป็นอิสระ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_401_chk" {{ $ibReportOne->item_401_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">                               
                            @if ($ibReportOne->item_401_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_401_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif    
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">
                            {{$ibReportOne->item_401_comment}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 4.2 การรักษาความลับ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_402_chk" {{ $ibReportOne->item_402_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_402_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_402_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif    
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">
                            {{$ibReportOne->item_402_comment}}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 5.1 คุณลักษณะที่ข้อกำหนดการบริหาร</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_501_chk" {{ $ibReportOne->item_501_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_501_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_501_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif    
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_501_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 6.1 บุคลากร</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_601_chk" {{ $ibReportOne->item_601_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_601_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_601_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif    
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_601_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 6.2 สิ่งอำนวยความสะดวกและเครื่องมือ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_602_chk" {{ $ibReportOne->item_602_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_602_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_602_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif    
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_602_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 6.3 การจ้างเหมาช่วง</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_603_chk" {{ $ibReportOne->item_603_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_603_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_603_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_603_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 7.1 ขั้นตอนการดำเนินงาน และวิธีการตรวจ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_701_chk" {{ $ibReportOne->item_701_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_701_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_701_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_701_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 7.2 การจัดการตัวอย่างและรายงานที่ตรวจ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_702_chk" {{ $ibReportOne->item_702_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_702_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_702_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_702_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 7.3 บันทึกผลการตรวจ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_703_chk" {{ $ibReportOne->item_703_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_703_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_703_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_703_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 7.4 ใบรายงานผลการตรวจและใบรับรองการตรวจ</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_704_chk" {{ $ibReportOne->item_704_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_704_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_704_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_704_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 7.5 การร้องเรียนและการอุทธรณ์</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_705_chk" {{ $ibReportOne->item_705_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_705_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_705_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_705_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 7.6 กระบวนการร้องเรียนและการอุทธรณ์</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_706_chk" {{ $ibReportOne->item_706_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_706_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_706_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_706_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.1 ทางเลือก</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_801_chk" {{ $ibReportOne->item_801_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_801_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_801_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_801_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.2 เอกสารระบบการบริหารงาน (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_802_chk" {{ $ibReportOne->item_802_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_802_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_802_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_802_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.3 การควบคุมเอกสาร (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_803_chk" {{ $ibReportOne->item_803_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_803_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_803_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_803_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.4 การควบคุมบันทึก (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_804_chk" {{ $ibReportOne->item_804_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_804_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_804_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_804_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.5 การทบทวนระบบการบริหารงาน (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_805_chk" {{ $ibReportOne->item_805_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_805_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_805_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_805_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.6 การประเมินภายใน (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_806_chk" {{ $ibReportOne->item_806_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_806_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_806_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_806_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.7 การฟฎิบัติการแก้ไข (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_807_chk" {{ $ibReportOne->item_807_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_807_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_807_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_807_comment}}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 10px">ข้อ 8.8 การฟฎิบัติการป้องกัน (ทางเลือก A)</td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="item_808_chk" {{ $ibReportOne->item_808_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->item_808_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->item_808_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->item_808_comment}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 500;padding-left: 10px">หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองหน่วยตรวจ พ.ศ.2564 </td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="insp_cert_chk" {{ $ibReportOne->insp_cert_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->insp_cert_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->insp_cert_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->insp_cert_comment}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 500;padding-left: 10px">กฏกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน </td>
                        <td style="text-align: center">
                            <div class="evaluation-checkbox-item">
                                <input type="checkbox" id="reg_std_mark_chk" {{ $ibReportOne->reg_std_mark_chk == 1 ? 'checked="checked"' : '' }}>
                            </div>
                        </td>
                        <td style="text-align: center">
                            @if ($ibReportOne->reg_std_mark_eval_select == 1)
                                สอดคล้อง
                            @elseif($ibReportOne->reg_std_mark_eval_select == 2)
                                ไม่สอดคล้อง
                            @endif
                        </td>
                        <td style="padding-left: 5px;text-align:center;font-size:22px">{{$ibReportOne->reg_std_mark_comment}}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="topic_four" style="margin-top: 15px">
        <div style="margin-left: 10px;font-weight:bold">10.3 ประเด็นสำคัญจากการตรวจประเมิน</div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->evaluation_key_point!!}
        </div>
    </div>
    <div class="topic_four">
        <div style="margin-left: 10px;font-weight:bold">10.5 ข้อสังเกต</div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->observation!!}
        </div>
    </div>
    <div class="topic_four">
        <div style="font-weight:bold">11. สรุปผลการตรวจประเมิน</div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->evaluation_result!!}
        </div>
    </div>
    <div class="topic_four">
        <div style="font-weight:bold">12. ความเห็น/ข้อเสนอแนะของคณะผู้ตรวจประเมิน</div>
        <div style="margin-left: 35px">
            {!!$ibReportOne->auditor_suggestion!!}
        </div>
    </div>

    <div style="text-align: center">
        <table style="margin: 0 auto; width: 100%;">
            <tr>
                <td style="width: 33%; vertical-align: top;">
                    <table style="width: 100%; margin: 30px auto 0; line-height: 1; text-align: center;">
                        <tr>
                            <td></td>
                            <td><img src="{{public_path($signer->signer_url1)}}" style="width: 70px" alt=""></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{$signer->signer_1->signer_name}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 22px">{{$signer->signer_1->signer_position}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 22px">{{HP::formatDateThaiFullNumThai($signer->signer_1->updated_at)}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%; vertical-align: top;">
                    <table style="width: 100%; margin: 30px auto 0; line-height: 1; text-align: center;">
                        <tr>
                            <td></td>
                            <td><img src="{{public_path($signer->signer_url2)}}" style="width: 70px" alt=""></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{$signer->signer_2->signer_name}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 22px">{{$signer->signer_2->signer_position}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 22px">{{HP::formatDateThaiFullNumThai($signer->signer_2->updated_at)}}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%; vertical-align: top;">
                    <table style="width: 100%; margin: 30px auto 0; line-height: 1; text-align: center;">
                        <tr>
                            <td></td>
                            <td><img src="{{public_path($signer->signer_url3)}}" style="width: 70px" alt=""></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{$signer->signer_3->signer_name}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 22px">{{$signer->signer_3->signer_position}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="font-size: 22px">{{HP::formatDateThaiFullNumThai($signer->signer_3->updated_at)}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    

    

    


</body>
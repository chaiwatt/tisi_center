
<body>
    <div class="lab-header">
        <div class="request_no" style="height: 35px;font-weight:bold" >
            <div class="inline-block w-45 p-0 float-left" > 
                <div style="display:block; width: 200px;height: 28px;float:left;line-height:28px;text-align:center;">
                    คำขอเลขที่ {{$certi_lab->app_no}} 
                </div>
            </div>
            <div class="inline-block w-50 p-0 float-right text-right" >
                <div style="display:block; width: 100px;height: 28px;float:right;line-height:28px;text-align:center;border: 0.5px solid #000;padding-top:5px">
                    รายงานที่ 1
                </div>
            </div>
        </div>
        <div class="report_header" style="">
            รายงานการตรวจประเมินความสามารถของห้องปฏิบัติการทดสอบ/สอบเทียบ<br>ตามมาตรฐานเลขที่ มอก. 17025-2561
        </div>
        
        <div class="report_objective">
            <div style="text-align: left; margin-left:100px">
                สำหรับ <input type="checkbox" {{ $certi_lab->purpose_type == 1 ? 'checked="checked"' : '' }}> การขอรับใบรับรองใหม่  <input type="checkbox" {{ $certi_lab->purpose_type == 3 ? 'checked="checked"' : '' }}> การขยาย/ปรับขอบข่ายใบรับรอง <input type="checkbox" {{ $certi_lab->purpose_type == 2 ? 'checked="checked"' : '' }}> ต่ออายุใบรับรอง
            </div>
            <div style="text-align: left; margin-left:148px;margin-top:6px">
                <input type="checkbox"  {{ !in_array($certi_lab->purpose_type, [1, 2, 3]) ? 'checked="checked"' : '' }}> ....................................................................................................................
            </div>
        </div>
    </div>

    <div class="topic_one">
        <div style="margin-top: 10px;font-weight:bold">1. ข้อมูลทั่วไป</div>
        <div style="margin-left: 15px">
            <div><span style="font-weight:bold">ชื่อห้องปฏิบัติการ :</span> <span>{{$certi_lab->lab_name}}</span> </div>
            <div><span style="font-weight:bold">ตั้งอยู่เลขที่ :</span> <span> {{$labScopeTransaction->address_number}} หมู่ที่ {{$labScopeTransaction->village_no}} 
                @if(\Illuminate\Support\Str::contains($labScopeTransaction->address_city_text, 'กรุงเทพ'))
                    แขวง{{$labScopeTransaction->sub_district}} เขต{{$labScopeTransaction->address_district}} {{$labScopeTransaction->address_city_text}} {{$labScopeTransaction->postal_code}} 
                @else
                    ตำบล{{$labScopeTransaction->sub_district}} อำเภอ{{$labScopeTransaction->address_district}} จังหวัด{{$labScopeTransaction->address_city_text}} {{$labScopeTransaction->postal_code}} 
                @endif</span> </div>


                {{-- {{$labScopeTransaction->address_number}} หมู่ที่ {{$labScopeTransaction->village_no}} 
                @if(\Illuminate\Support\Str::contains($labScopeTransaction->address_city_text, 'กรุงเทพ'))
                    แขวง{{$labScopeTransaction->sub_district}} เขต{{$labScopeTransaction->address_district}} {{$labScopeTransaction->address_city_text}} {{$labScopeTransaction->postal_code}} 
                @else
                    ตำบล{{$labScopeTransaction->sub_district}} อำเภอ{{$labScopeTransaction->address_district}} จังหวัด{{$labScopeTransaction->address_city_text}} {{$labScopeTransaction->postal_code}} 
                @endif --}}

            <div><span style="font-weight:bold">วันที่ยื่นคำขอ :</span> <span>{{HP::formatDateThaiFullPoint($certi_lab->get_date)}}</span> </div>
            <div><span style="font-weight:bold">สาขาและขอบข่าย :</span> <span>ตามเอกสารประกอบคำขอของ ห้องปฏิบัติการ{{$certi_lab->lab_name}} ลงวันที่ {{HP::formatDateThaiFullPoint($certi_lab->get_date)}} และ / หรือหนังสือขอแก้ไขขอบข่ายของ ห้องปฏิบัติการ ลงวันที่ {{HP::formatDateThaiFullPoint($notice->date_car)}} (ถ้ามี)/ ขอบข่าย ดังแนบ</span></div>
        </div>
    </div>

    <div class="topic_two">
        <div style="margin-top: 10px;font-weight:bold">2. การตรวจประเมิน</div>
        <div style="margin-left: 15px">
            <div><span>2.1 คณะผู้ตรวจประเมิน ประกอบด้วย :</span> </div>
            <div style="margin-left:20px"> 


                {{-- @php
                    $index = 0;
                @endphp
                @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                    @php
                        $index++;
                    @endphp

                    @foreach ($auditorIds as $auditorId)
                        @php
                            $info = HP::getExpertInfo($statusId, $auditorId);
                        @endphp
                        <div style="display: flex; gap: 10px;">
                            <span style="flex: 0 0 250px;">{{$index}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</span>
                            <span style="flex: 1 0 200px;">{!!$info->statusAuditor->title!!}</span>
                        </div>

                    @endforeach
                @endforeach --}}

               @php
                    $index = 0;
                @endphp

                <table>
                    @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                        @foreach ($auditorIds as $auditorId)
                            @php
                                $index++;
                                $info = HP::getExpertInfo($statusId, $auditorId);
                            @endphp
                            {{-- เปลี่ยนจาก div เป็น tr --}}
                            <tr>
                                {{-- เปลี่ยนจาก span เป็น td และกำหนดความกว้างให้คอลัมน์แรก --}}
                                <td style="width: 250px;">{{$index}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</td>
                                <td>{!!$info->statusAuditor->title!!}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>
            </div>  
        </div>

        <div style="margin-left: 15px">
            <div><span>2.2 รูปแบบการตรวจประเมิน :</span> </div>
            <div style="margin-left:20px"> 
                <div >
                    <div style="display: inline-block; width:90%;"> <input type="checkbox" {{ $labReportInfo->inp_2_2_assessment_on_site === "1" ? 'checked="checked"' : '' }}> ณ ห้องปฎิบัติการ</div>
                </div>
                <div >
                    <div style="display: inline-block; width:35%; float:left"><input type="checkbox" {{ $labReportInfo->inp_2_2_assessment_at_tisi === "1" ? 'checked="checked"' : '' }}> ตรวจประเมิน ณ สมอ. โดยวิธี </div>
                    <div style="display: inline-block; width:60%; float:left"><input type="checkbox" {{ $labReportInfo->inp_2_2_remote_assessment === "1" ? 'checked="checked"' : '' }}> ตรวจประเมินทางไกล (remote assessment)</div>
                </div>
                <div >
                    <div style="display: inline-block; width:100%; margin-left:230px"><input type="checkbox" {{ $labReportInfo->inp_2_2_self_declaration === "1" ? 'checked="checked"' : '' }}> เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)</div>
                </div>
            </div>  
        </div>

        <div style="margin-left: 15px">
            {{-- <div><span>2.3 วันที่ตรวจประเมิน : {{HP::formatDateThaiFullPoint($notice->assessment_date)}}</span> </div> --}}
            <div><span>2.3 วันที่ตรวจประเมิน :
                
                {{-- {{HP::formatDateThaiFullPoint($boardAuditor->board_auditors_date->start_date)}} --}}
                @php
                    // ฟังก์ชันจัดการวันที่ใน Blade
                    $start = new \DateTime($boardAuditor->board_auditors_date->start_date);
                    $end = new \DateTime($boardAuditor->board_auditors_date->end_date);

                    // ชื่อเดือนภาษาไทย
                    $thaiMonths = [
                        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
                        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
                        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
                    ];

                    // ปี พ.ศ.
                    $startYearBE = $start->format('Y') + 543;
                    $endYearBE = $end->format('Y') + 543;

                    // วันและเดือน
                    $startDay = (int)$start->format('j');
                    $endDay = (int)$end->format('j');
                    $startMonth = (int)$start->format('n');
                    $endMonth = (int)$end->format('n');

                    // ตรวจสอบเงื่อนไข
                    if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
                        // กรณีเป็นวันเดียวกัน
                        $formattedDate = "$startDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE";
                    } elseif ($startMonth === $endMonth && $startYearBE === $endYearBE) {
                        // กรณีเป็นเดือนและปีเดียวกัน
                        $formattedDate = "$startDay - $endDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE";
                    } else {
                        // กรณีเป็นคนละเดือนหรือคนละปี
                        $formattedDate = "$startDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE - $endDay {$thaiMonths[$endMonth]} พ.ศ. $endYearBE";
                    }
                @endphp

                {{ $formattedDate }}
            
            
            </span> </div>
            
        </div>

        {{-- <div style="margin-left: 15px"> --}}
            <div style="margin-left: 15px"><span>2.4 บุคคลที่พบ :</span> </div>
            {{-- <div style="margin-left:25px">  --}}
                @php
                    $persons = json_decode($labReportInfo->persons, true);
                @endphp
                {{-- {{$labReportInfo->persons}} --}}
                @foreach ($persons as $key => $person)
                {{-- <div >
                    <div style="display: inline-block; width:35%; float:left">{{$key+1}}. {{$person['name']}}</div>
                    <div style="display: inline-block; width:60%; float:left">ตำแหน่ง  {{$person['position']}}</div>
                </div> --}}
                <table autosize="1"  style="margin-left:35px;padding:0;line-height:1">
                    <tr>
                        <td style="width:220px">{{$key+1}}. {{$person['name']}}</td>
                        <td style="">ตำแหน่ง  {{$person['position']}}</td>
                    </tr>
                </table>

                @endforeach
            {{-- </div>   --}}
        {{-- </div> --}}
    </div>

    {{-- <pagebreak /> --}}

    <!-- ข้อมูลสำหรับหน้าใหม่ -->
    <div class="topic_two">
        <div style="margin-left: 15px">
            <div><span>2.5 ผลการตรวจประเมิน :</span> </div>
            <div style="margin-left: 25px"><span>คณะผู้ตรวจประเมินตรวจประเมินความสามารถของห้องปฏิบัติการตาม มาตรฐานเลขที่ มอก. 17025-2561 ดังนี้</span> </div>
            {{-- ===========ข้อกำหนดทั่วไป============= --}}
            <div style="margin-left: 25px ;font-weight:bold"><span>(1) ข้อกำหนดทั่วไป</span> </div>
            <div style="display: inline-block; width:100%; float:left;margin-left:45px"><input type="checkbox" {{ $labReportInfo->inp_2_5_1_structure_compliance === "1" ? 'checked="checked"' : '' }} > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 4.1-4.2 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมีประสิทธิผล ดังนี้</div>
            {{-- <div style="margin-left:37px"> 
                
                <div style="display: inline-block;margin-left:22px; width:100%; float:left"> --}}
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_central_management_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_central_management_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>มีการดำเนินการอย่างเป็นกลาง โดยมีโครงสร้างและการบริหารจัดการที่ก่อให้เกิดความ เป็นกลาง</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_quality_policy_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_quality_policy_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>ผู้บริหารมีนโยบายคุณภาพที่มุ่งเน้นเรื่องความเป็นกลาง</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_risk_assessment_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_risk_assessment_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การประเมินความเสี่ยงต่อความเป็นกลางครอบคลุมถึงความเสี่ยงที่เกิดขึ้นจากกิจกรรม ของห้องปฏิบัติการ และความเสี่ยงเรื่องความสัมพันธ์ตามขอบเขตหน้าที่ความรับผิดชอบ ของบุคลากรแต่ละตำแหน่งและความสัมพันธ์ในระดับบุคคลสำหรับกิจกรรมต่าง ๆ ของ ห้องปฏิบัติการ</td>
                        </tr>
                    </table>
                    
                    <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-left:70px">
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_1_other === "1" ? 'checked="checked"' : '' }}></div>
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_1_text_other1}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_1_text_other2}}
                            </td>
                        </tr>
                    </table>


    
                {{-- </div>
            </div>   --}}
            {{-- <div style="margin-left:37px;margin-top:10px">  --}}
                @php
                      $data_inp_2_5_1_detail = json_decode($labReportInfo->inp_2_5_1_detail, true);

                    // ตรวจสอบว่ามี key 'lines' หรือไม่
                    // $lines = $data_inp_2_5_1_detail[0]['lines'] ?? [];
                @endphp

                {{-- <div style="display: inline-block; width:100%; float:left"><input type="checkbox" checked="checked" > พบว่า</div> --}}
                {{-- <div style="display: inline-block;margin-left:22px; width:100%; float:left"> --}}
                    <div style="margin-left:70px">
                        <div style="display: inline-block;float:left;width:3%;margin-top:10px"><input type="checkbox" {{ $labReportInfo->inp_2_5_1_issue_found === "1" ? 'checked="checked"' : '' }}> <u>พบว่า</u></div>   
                    </div>

                    @if (!empty($data_inp_2_5_1_detail))
                    @foreach ($data_inp_2_5_1_detail as $item)
                        @php
                            $lines = $item['lines'] ?? [];
                        @endphp
                        @if (!empty($lines))
                        
                            @foreach ($lines as $line)
                                <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:10px;padding:0;line-height:1;margin-left:65px">
                                    <tr style="page-break-inside: auto">
                                        <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                            <span style="visibility: hidden;">|</span>{!!$line!!}
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        @endif
                    @endforeach
                  @else  
                    <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:-10px;margin-left:65px">
                        <tr>
                            <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>
                            </td>
                        </tr>
                    </table>
                @endif


                    
                    <div style="margin-top:10px;margin-left:65px"> 
                        <div style="display: inline-block; width:100%; float:left">ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</div>
                    </div>  

                {{-- </div> --}}
            {{-- </div>   --}}
         
            {{-- ===========ข้อกำหนดด้านโครงสร้าง============= --}}
            <div style="margin-left: 20px ;font-weight:bold"><span>(2) ข้อกำหนดด้านโครงสร้าง</span> </div>
            <div style="display: inline-block; width:100%; float:left;margin-left:45px"><input type="checkbox" checked="checked" > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 5.1 – 5.7 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมี ประสิทธิผล ดังนี้</div>
            {{-- <div style="margin-left:37px">  --}}
                
                {{-- <div style="display: inline-block;margin-left:22px; width:100%; float:left"> --}}


                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_2_lab_management === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="vertical-align:top;text-align:center">
                                @if ($labReportInfo->inp_2_5_2_lab_management_details !== "")
                                {{$labReportInfo->inp_2_5_2_lab_management_details}} 
                                   @else 
                                   ..................................................
                                @endif
                                
                            </td>
                            <td>เป็นผู้บริหารของห้องปฏิบัติการ และ</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">

                        
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_2_staff_assignment_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_2_staff_assignment_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การมอบหมายบุคลากรเพื่อปฏิบัติงานเฉพาะของห้องปฏิบัติการ</td>
                        </tr>
                  
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_2_responsibility_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_2_responsibility_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การระบุความรับผิดชอบ อำนาจหน้าที่ ความสัมพันธ์ของบุคลากรในห้องปฏิบัติการใน Job Description ของแต่ละตำแหน่งงานมีการสื่อสารถึงประสิทธิผลของการปฏิบัติงาน ผ่านการประชุมทบทวนการบริหารงานการประชาสัมพันธ์อื่น ๆ</td>
                        </tr>
               
      
                    </table>

                    <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-left:70px">
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_2_other === "1" ? 'checked="checked"' : '' }}></div>
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_2_text_other1}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_2_text_other2}}
                            </td>
                        </tr>
                    </table>

             
                {{-- </div> --}}
            {{-- </div>   --}}
            {{-- <div style="margin-left:65px;margin-top:10px">  --}}
                @php
                      $data_inp_2_5_2_detail = json_decode($labReportInfo->inp_2_5_2_detail, true);

                    // ตรวจสอบว่ามี key 'lines' หรือไม่
                    
                @endphp

                {{-- <div style="display: inline-block; width:100%; float:left"><input type="checkbox" checked="checked" > พบว่า</div> --}}
                {{-- <div style="display: inline-block;margin-left:65px; width:100%; float:left"> --}}
                    <div style="margin-left:70px">
                        <div style="display: inline-block;float:left;width:3%;margin-top:10px"><input type="checkbox" {{ $labReportInfo->inp_2_5_2_issue_found === "1" ? 'checked="checked"' : '' }}> <u>พบว่า</u></div>   
                    </div>
                    @if (!empty($data_inp_2_5_2_detail))
                    @foreach ($data_inp_2_5_2_detail as $item)
                        @php
                            $lines = $item['lines'] ?? [];
                        @endphp
                        @if (!empty($lines))
                        
                            @foreach ($lines as $line)
                                <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:10px;padding:0;line-height:1;margin-left:65px">
                                    <tr style="page-break-inside: auto">
                                        <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                            <span style="visibility: hidden;">|</span>{!!$line!!}
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        @endif
                    @endforeach
                  @else  
                    <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:-10px;margin-left:65px">
                        <tr>
                            <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>
                            </td>
                        </tr>
                    </table>
                @endif
                   

                    
                    <div style="margin-top:10px;margin-left:65px"> 
                        <div style="display: inline-block; width:100%; float:left">ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</div>
                    </div>  

                {{-- </div> --}}
            {{-- </div>   --}}

            {{-- ===========ข้อกำหนดด้านทรัพยากร============= --}}
            <div style="margin-left: 20px ;font-weight:bold"><span>(3) ข้อกำหนดด้านทรัพยากร</span> </div>
            <div style="display: inline-block; width:100%; float:left;margin-left:45px"><input type="checkbox" {{ $labReportInfo->inp_2_5_3_structure_compliance === "1" ? 'checked="checked"' : '' }} > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 6.1-6.6 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมี ประสิทธิผล ดังนี้</div>
            {{-- <div style="margin-left:55px">  --}}
                
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_personnel_qualification_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_personnel_qualification_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การกำหนดคุณสมบัติหน้าที่ความรับผิดชอบของบุคลากรเพื่อให้มั่นใจว่ามีความสามารถ ในการใช้เครื่องมือ ดำเนินการทดสอบ/สอบเทียบ ควบคุมงาน ประเมินผล ทบทวนและ อนุมัติผลการทดสอบ/สอบเทียบ ได้อย่างถูกต้อง</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_assign_personnel_appropriately_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_assign_personnel_appropriately_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การมอบหมายบุคลากรเพื่อปฏิบัติงานเฉพาะต่าง ๆ ของห้องปฏิบัติการอย่างเหมาะสม รวมถึงบุคลากรที่ระบุการเป็นไปตามข้อกำหนด (ถ้ามี)</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_training_need_assessment_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_training_need_assessment_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การกำหนดความจำเป็นในการฝึกอบรมของแต่ละตำแหน่งงานใน Training Need และ มีการจัดเก็บบันทึกการประเมินผลการฝึกอบรม</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_facility_and_environment_control_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_facility_and_environment_control_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การจัดการสิ่งอำนวยความสะดวกและควบคุมสภาวะแวดล้อมห้องปฏิบัติการได้อย่าง เหมาะสม</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_equipment_maintenance_calibration_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_equipment_maintenance_calibration_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การควบคุมการใช้เครื่องมือ บำรุงรักษา สอบเทียบ และทวนสอบเครื่องมือที่มีผล กระทบต่อผลการทดสอบ/สอบเทียบ ตามขอบข่ายที่ขอรับการรับรองและมีการระบุ สถานะสอบเทียบเครื่องมือชัดเจน</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_metrology_traceability_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_metrology_traceability_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>ความสามารถสอบกลับได้ทางมาตรวิทยาได้อย่างเหมาะสม</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_external_product_service_control_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_external_product_service_control_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การควบคุมผลิตภัณฑ์และบริการจากภายนอกได้อย่างเหมาะสมและมีประสิทธิภาพ (ถ้ามี)</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left:65px">
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_3_other === "1" ? 'checked="checked"' : '' }}></div>
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_3_text_other1}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_3_text_other2}}
                            </td>
                        </tr>
                    </table>
                  

                    @php
                    $data_inp_2_5_3_detail = json_decode($labReportInfo->inp_2_5_3_detail, true);

                  // ตรวจสอบว่ามี key 'lines' หรือไม่
                  
              @endphp

              {{-- <div style="display: inline-block; width:100%; float:left"><input type="checkbox" checked="checked" > พบว่า</div> --}}
              {{-- <div style="display: inline-block;margin-left:65px; width:100%; float:left"> --}}
                  <div style="margin-left:70px">
                      <div style="display: inline-block;float:left;width:3%;margin-top:10px"><input type="checkbox" {{ $labReportInfo->inp_2_5_3_issue_found === "1" ? 'checked="checked"' : '' }}> <u>พบว่า</u></div>   
                  </div>
                  @if (!empty($data_inp_2_5_3_detail))
                  @foreach ($data_inp_2_5_3_detail as $item)
                      @php
                          $lines = $item['lines'] ?? [];
                      @endphp
                      @if (!empty($lines))
                      
                          @foreach ($lines as $line)
                              <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:10px;padding:0;line-height:1;margin-left:65px">
                                  <tr style="page-break-inside: auto">
                                      <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                          <span style="visibility: hidden;">|</span>{!!$line!!}
                                      </td>
                                  </tr>
                              </table>
                          @endforeach
                      @endif
                  @endforeach
                @else  
                  <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:-10px;margin-left:65px">
                      <tr>
                          <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                              <span style="visibility: hidden;">|</span>
                          </td>
                      </tr>
                      <tr>
                          <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                              <span style="visibility: hidden;">|</span>
                          </td>
                      </tr>
                  </table>
              @endif
                 

                  
                  <div style="margin-top:10px;margin-left:65px"> 
                      <div style="display: inline-block; width:100%; float:left">ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</div>
                  </div>  
         
            <div style="margin-left: 20px ;font-weight:bold"><span>(4) ข้อกำหนดด้านกระบวนการ</span> </div>
            <div style="display: inline-block; width:100%; float:left;margin-left:45px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_structure_compliance === "1" ? 'checked="checked"' : '' }} > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 7.1-7.11 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมี ประสิทธิผล ดังนี้</div>
            {{-- <div style="margin-left:55px">  --}}
                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_policy_compliance_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_policy_compliance_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>นโยบายระบุความเป็นไปตามข้อกำหนดหรือมาตรฐานและเกณฑ์ตัดสิน (decision rule)</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_metrology_sampling_activity_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_metrology_sampling_activity_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>กิจกรรมการชักตัวอย่าง</td>
                    </tr>
                </table>
                {{-- <pagebreak> --}}
                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_procedure_review_request_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_procedure_review_request_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>ขั้นตอนการดำเนินงานทบทวนคำขอและสัญญาของห้องปฏิบัติการสามารถตอบสนอง ความต้องการของลูกค้าและเพื่อให้เกิดความเข้าใจตรงกันในเรื่องของข้อกำหนดต่าง ๆ ขีดความสามารถและทรัพยากรที่เพียงพอต่อการปฏิบัติงานและวิธีทดสอบ/สอบเทียบ ที่เหมาะสมรวมถึงเมื่อมีการเปลี่ยนแปลงหรือเบี่ยงเบนจากที่ลูกค้าร้องขอจะดำเนิน การแจ้งลูกค้าทราบทันที</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:72px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_decision_rule_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_decision_rule_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>กรณีลูกค้าขอให้ระบุความเป็นไปตามข้อกำหนดหรือมาตรฐานและเกณฑ์ตัดสิน (decision rule)

                            <table autosize="1"  style="margin-left:0px">
                                <tr>
                                    <td style="width: 40px;vertical-align:top">
                                        <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_agreement_customer_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                                    </td>
                                    <td style="width: 55px;vertical-align:top">
                                        <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_agreement_customer_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div>
                                    </td>
                                    <td>การตกลงและแจ้งกับลูกค้าอย่างชัดเจน</td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

                {{-- <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_agreement_customer_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 55px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_agreement_customer_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div>
                        </td>
                        <td>การตกลงและแจ้งกับลูกค้าอย่างชัดเจน</td>
                    </tr>
                </table> --}}

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_method_verification_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_method_verification_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>ขั้นตอนการดำเนินงานสำหรับการเลือกการทวนสอบและการตรวจสอบความใช้ได้ ของวิธีที่เหมาะสมและเป็นไปตามข้อกำหนด</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_sample_management_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_sample_management_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>ขั้นตอนการดำเนินงานการจัดการตัวอย่าง เพื่อควบคุมการขนส่ง รับ จัดการ ป้องกัน เก็บรักษา ส่งคืนตัวอย่าง รวมทั้งมีระบบการชี้บ่งตัวอย่างรักษาความสมบูรณ์ของ ตัวอย่างตลอดช่วงเวลาที่อยู่ในการทดสอบ/สอบเทียบ มีการบันทึกลักษณะของ ตัวอย่างก่อนการทดสอบ/สอบเทียบ</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_record_management_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_record_management_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>การจัดการบันทึกทางด้านวิชาการได้อย่างเหมาะสมและเป็นไปตามข้อกำหนด</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_uncertainty_evaluation_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_uncertainty_evaluation_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>การประเมินค่าความไม่แน่นอนของการวัด โดยชี้บ่งปัจจัยที่มีผลต่อค่าความไม่แน่นอน ของการวัด</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_result_surveillance_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_result_surveillance_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>ขั้นตอนการดำเนินงานในการเฝ้าระวังความใช้ได้ของผล ได้อย่างเหมาะสม</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_proficiency_testing_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_proficiency_testing_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td><b>การเข้าร่วมการทดสอบความชำนาญ/ การเปรียบเทียบระหว่างห้องปฏิบัติการ</b> </td>
                    </tr>
                </table>

                <div style="display: inline-block;margin-left:65px; width:100%; float:left">
             
                
               
                        
                            <div style="font-weight: bold;">
                            
                                <div style="font-weight: 400">
                                    <div style="margin-left: 25px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_test_participation === "1" ? 'checked="checked"' : '' }} >เข้าร่วมทดสอบความชำนาญซึ่งจัดโดย
                                        @if ($labReportInfo->inp_2_5_4_test_participation_details1 != "")
                                        {{$labReportInfo->inp_2_5_4_test_participation_details1}} 
                                        @else   
                                        ........................................................................................
                                        @endif
                                        
                                        <br>
                                        @if ($labReportInfo->inp_2_5_4_test_participation_details2 != "")
                                        {{$labReportInfo->inp_2_5_4_test_participation_details2}} 
                                        @else   
                                        ............................................................................................................................................................
                                        @endif
                                        
                                        <div style="margin-left:20px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_test_calibration === "1" ? 'checked="checked"' : '' }}  > สำหรับการทดสอบ/สอบเทียบ
                                            
                                            @if ($labReportInfo->inp_2_5_4_calibration_details != "")
                                            {{$labReportInfo->inp_2_5_4_calibration_details}} 
                                            @else   
                                            ..............................................................................................
                                            @endif
                                        </div>
                                        <div style="margin-left:20px">ผล <input type="checkbox" {{ $labReportInfo->inp_2_5_4_acceptance_criteria_yes === "1" ? 'checked="checked"' : '' }} > อยู่ในเกณฑ์การยอมรับ</div>
                                        <div style="margin-left:40px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_acceptance_criteria_no === "1" ? 'checked="checked"' : '' }} > ไม่อยู่ในเกณฑ์การยอมรับ 
                                            @if ($labReportInfo->inp_2_5_4_acceptance_criteria1 != "")
                                            {{$labReportInfo->inp_2_5_4_acceptance_criteria1}} 
                                            @else   
                                            ................................................................................................
                                            @endif
                                            
                                            <br>
                                            @if ($labReportInfo->inp_2_5_4_acceptance_criteria2 != "")
                                            {{$labReportInfo->inp_2_5_4_acceptance_criteria2}} 
                                            @else   
                                            .................................................................................................................................................
                                            @endif
                                        </div>
                                    </div>
                                    <div style="margin-left: 25px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_lab_comparison === "1" ? 'checked="checked"' : '' }}> มีการเปรียบเทียบผลกับห้องปฏิบัติการ
                                        @if ($labReportInfo->inp_2_5_4_lab_comparison_details1 != "")
                                        {{$labReportInfo->inp_2_5_4_lab_comparison_details1}} 
                                        @else   
                                        ....................................................................
                                        @endif
                                        
                                        <br>
                                        @if ($labReportInfo->inp_2_5_4_lab_comparison_details2 != "")
                                        {{$labReportInfo->inp_2_5_4_lab_comparison_details2}} 
                                        @else   
                                        ..........................................................................................................................................
                                        @endif
                                        
                                        <div style="margin-left:20px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_lab_comparison_test === "1" ? 'checked="checked"' : '' }}  >สำหรับการทดสอบ/สอบเทียบ
                                            @if ($labReportInfo->inp_2_5_4_lab_comparison_test_details != "")
                                            {{$labReportInfo->inp_2_5_4_lab_comparison_test_details}} 
                                            @else   
                                            ...............................................................................
                                            @endif
                                            
                                        </div>
                                        <div style="margin-left:20px">ผล <input type="checkbox" {{ $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_yes === "1" ? 'checked="checked"' : '' }}> อยู่ในเกณฑ์การยอมรับ</div>
                                        <div style="margin-left:40px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_no === "1" ? 'checked="checked"' : '' }} > ไม่อยู่ในเกณฑ์การยอมรับ 
                                            @if ($labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details1 != "")
                                            {{$labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details1}} 
                                            @else   
                                            ...............................................................................
                                            @endif
                                            
                                            <br>
                                            @if ($labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details2 != "")
                                            {{$labReportInfo->inp_2_5_4_lab_comparison_test_is_accept_details2}} 
                                            @else   
                                            ................................................................................................................................
                                            @endif
                                            </div>
                                    </div>
                                    <div style="margin-left: 25px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_test_participation2 === "1" ? 'checked="checked"' : '' }} >เข้าร่วมทดสอบความชำนาญ/มีการเปรียบเทียบผลระหว่างห้องปฏิบัติการดังแนบ (กรณีเปรียบเทียบจำนวนมาก)</div>
                                </div>
                            </div>
                        <div style="margin-left:20px">
                            <input type="checkbox" {{ $labReportInfo->inp_2_5_4_other_methods === "1" ? 'checked="checked"' : '' }} > วิธีการอื่น  <span style="font-weight: 400">
                                @if ($labReportInfo->inp_2_5_4_other_methods_details1 != "")
                                {{$labReportInfo->inp_2_5_4_other_methods_details1}} 
                                @else   
                                ......................................................................................................................................
                                @endif
                                
                                <br>
                                @if ($labReportInfo->inp_2_5_4_other_methods_details2 != "")
                                {{$labReportInfo->inp_2_5_4_other_methods_details2}} 
                                @else   
                                .............................................................................................................................................................</span> 
                                @endif
                                
                        </div>
                </div>


                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_report_approval_review_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_report_approval_review_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>การทบทวนและอนุมัติรายงานผลการทดสอบ/สอบเทียบของห้องปฏิบัติการก่อนส่ง รายงานรายงานผลจัดทำได้ถูกต้องไม่คลุมเครือและตรงตามวัตถุประสงค์ตามข้อกำหนด โดยห้องปฏิบัติการจะรับผิดชอบข้อมูลต่าง ๆ ที่อยู่ในรายงานยกเว้นข้อมูลที่จัดเตรียม โดยลูกค้าและชี้บ่งชัดเจนว่าเป็นข้อมูลจากลูกค้า</td>
                    </tr>
                </table>

                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_decision_rule2_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_decision_rule2_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>กรณีระบุความเป็นไปตามข้อกำหนดหรือมาตรฐานและเกณฑ์ตัดสิน (decision rule)

                            <table autosize="1"  style="margin-left: -8px">
                                <tr>
                                    <td style="width: 40px;vertical-align:top">
                                        <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_document_for_criteria_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                                    </td>
                                    <td style="width: 55px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_document_for_criteria_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                                    <td>มีเอกสารเกี่ยวกับเกณฑ์ตัดสินที่ใช้อย่างเหมาะสมและรายงานมีการระบุ การเป็นไปตามข้อกำหนดอย่างชัดเจนและเป็นไปตามข้อกำหนด</td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_complaint_process_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_complaint_process_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>กระบวนการจัดการกับข้อร้องเรียนที่เหมาะสม ปีที่ผ่านมามีข้อร้องเรียนจำนวน 
                            
                            @if ($labReportInfo->inp_2_5_4_complaint_number != "")
                            {{$labReportInfo->inp_2_5_4_complaint_number}} 
                            @else   
                            ........
                            @endif
                             รายการ และมีการดำเนินการเรียบร้อยแล้ว</td>
                    </tr>
                </table>
                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_non_conformance_process_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_non_conformance_process_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>ขั้นตอนการดำเนินงานสำหรับงานที่ไม่เป็นไปตามข้อกำหนด ได้อย่างเหมาะสมปีที่ผ่าน มามีงานที่ไม่เป็นไปตามข้อกำหนด จำนวน 
                            
                            @if ($labReportInfo->inp_2_5_4_non_conformance_number != "")
                            {{$labReportInfo->inp_2_5_4_non_conformance_number}} 
                            @else   
                            ........
                            @endif
                            รายการ และมีการดำเนินการเรียบร้อย แล้ว</td>
                    </tr>
                </table>
                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_data_control_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_data_control_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>การควบคุมข้อมูลและการจัดการระบบสารสนเทศที่จำเป็นต่อการปฏิบัติกิจกรรมต่าง ๆ ของห้องปฏิบัติการได้อย่างเหมาะสม</td>
                    </tr>
                </table>
                <table autosize="1"  style="margin-left:65px">
                    <tr>
                        <td style="width: 40px;vertical-align:top">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_data_transfer_control_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                        </td>
                        <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_data_transfer_control_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                        <td>การคำนวณและการถ่ายโอนข้อมูลได้รับการตรวจสอบอย่างเหมาะสมและเป็นระบบ</td>
                    </tr>
                </table>

                <table autosize="1"  style="page-break-inside: auto; border-collapse: collapse;margin-left:67px">
                    <tr>
                        <td style="width: 20px; vertical-align: top;">
                            <div><input type="checkbox" {{ $labReportInfo->inp_2_5_4_other === "1" ? 'checked="checked"' : '' }}></div>
                        </td>
                        <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                            <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_4_text_other1}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px; vertical-align: top;">
                        </td>
                        <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                            <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_4_text_other2}}
                        </td>
                    </tr>
                </table>
                {{-- <table autosize="1"  style="page-break-inside: auto; border-collapse: collapse;margin-left:70px;">
                    <tr>
                        <td> --}}
                            @php
                                $data_inp_2_5_4_detail = json_decode($labReportInfo->inp_2_5_4_detail, true);
                                // ตรวจสอบว่ามี key 'lines' หรือไม่
                                
                            @endphp
            
                            {{-- <div style="display: inline-block;margin-left:22px; width:100%; float:left"> --}}
                                {{-- <div style=";margin-left:70px"> --}}
                                    <div style="margin-left:70px;margin-top:10px"><input type="checkbox" {{ $labReportInfo->inp_2_5_4_issue_found === "1" ? 'checked="checked"' : '' }}> <u>พบว่า</u></div>   
                                {{-- </div> --}}
                                
                                @if (!empty($data_inp_2_5_4_detail))
                                @foreach ($data_inp_2_5_4_detail as $item)
                                    @php
                                        $lines = $item['lines'] ?? [];
                                    @endphp
                                    @if (!empty($lines))
                                    
                                        @foreach ($lines as $line)
                                            <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:10px;padding:0;line-height:1;margin-left:70px">
                                                <tr style="page-break-inside: auto">
                                                    <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                                        <span style="visibility: hidden;">|</span>{!!$line!!}
                                                    </td>
                                                </tr>
                                            </table>
                                        @endforeach
                                    @endif
                                @endforeach
                              @else  
                                <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:-10px;margin-left:70px">
                                    <tr>
                                        <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                            <span style="visibility: hidden;">|</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                            <span style="visibility: hidden;">|</span>
                                        </td>
                                    </tr>
                                </table>
                            @endif


                            {{-- </div> --}}
                        {{-- </td>
                    </tr>
                </table> --}}
                <div style="margin-top:10px;margin-left:70px"> 
                    <div style="display: inline-block; width:100%; float:left">ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</div>
                </div>  
            {{-- </div>
            </div>   --}}
           
            {{-- ===========ข้อกำหนดระบบการบริหารงาน============= --}}
            <div style="margin-left: 20px ;font-weight:bold"><span>(5) ข้อกำหนดระบบการบริหารงาน</span> </div>

            <div style="display: inline-block; width:100%; float:left;margin-left:45px"><input type="checkbox" {{ $labReportInfo->inp_2_5_5_structure_compliance === "1" ? 'checked="checked"' : '' }} > ห้องปฏิบัติการมีการปฏิบัติตามข้อกำหนด 8.1 – 8.9 ของมาตรฐานเลขที่ มอก. 17025-2561 ได้อย่างมี ประสิทธิผล ดังนี้</div>
            {{-- <div style="margin-left:55px">  --}}
                
                    <table autosize="1" style="margin-left: 70px" >
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_data_control_option_a === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div></div></td>
                            <td>มีระบบการบริหารงานตามทางเลือก A</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_data_control_option_b === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div></td>
                            <td>มีระบบการบริหารงานตามทางเลือก B</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_data_control_policy_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_data_control_policy_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การกำหนดนโยบายเกี่ยวกับระบบบริหารงานคุณภาพไว้เป็นเอกสารครอบคลุมถึง ความสามารถความเป็นกลางและการปฏิบัติงานอย่างสม่ำเสมอและสามารถนำไป ปฏิบัติได้อย่างมีประสิทธิผล</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_document_control_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_document_control_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การควบคุมเอกสารระบบการบริหารงานทั้งภายในและภายนอกอย่างเหมาะสมและ เป็นไปตามข้อกำหนด</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_record_keeping_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_record_keeping_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การจัดทำและจัดเก็บบันทึกต่าง ๆ ที่ชัดเจน เหมาะสม และเป็นไปตามข้อกำหนด</td>
                        </tr>
                    </table>
                    <table autosize="1"  style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_risk_management_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_risk_management_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การพิจารณาจัดการความเสี่ยงและโอกาสที่เกี่ยวข้องกับกิจกรรมของห้องปฏิบัติการซึ่ง เป็นสัดส่วนกับผลกระทบที่อาจเกิดขึ้นโดยมีการวางแผนการปฏิบัติการและมีวิธีการเพื่อ บูรณาการและนำปฏิบัติการไปใช้ในระบบการบริหารงานและประเมินประสิทธิผลของ การปฏิบัติการ</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_risk_opportunity_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_risk_opportunity_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การปฏิบัติการเพื่อจัดการความเสี่ยงและโอกาส</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_improvement_opportunity_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_improvement_opportunity_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การระบุและเลือกโอกาสในการปรับปรุงและนำไปใช้ปฏิบัติ รวมถึงมีการแสวงหา feedback ทั้งทางบวกและทางลบจากลูกค้าและนำไปวิเคราะห์และใช้ในการปรับปรุง ระบบการบริหารงาน กิจกรรมต่าง ๆ ของห้องปฏิบัติการและการบริการลูกค้า</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_non_conformance_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_non_conformance_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>กรณีงานที่ไม่เป็นไปตามข้อกำหนดมีการตอบสนองและดำเนินการเพื่อปฏิบัติการ ควบคุม แก้ไข และ/หรือทบทวนประสิทธิผลของการแก้ไขปรับความเสี่ยงและโอกาส ให้เป็นปัจจุบันรวมถึงการเปลี่ยนแปลงระบบการบริหารงาน เมื่อมีผลกระทบ</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_internal_audit_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_internal_audit_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การกำหนดแผนการตรวจติดตามคุณภาพภายใน ปีละ
                                @if ($labReportInfo->inp_2_5_5_audit_frequency != "")
                                {{$labReportInfo->inp_2_5_5_audit_frequency}} 
                                @else   
                                 ..... 
                                @endif
                                  
                                 ครั้ง ดำเนินการตรวจติดตาม คุณภาพภายในครั้งล่าสุด เมื่อวันที่
                                 @if ($labReportInfo->inp_2_5_5_last_audit_date != "")
                                 {{$labReportInfo->inp_2_5_5_last_audit_date}} 
                                 @else   
                                 ................ 
                                 @endif
                                 
                                  พบข้อบกพร่อง จำนวน
                                  @if ($labReportInfo->inp_2_5_5_audit_issues != "")
                                  {{$labReportInfo->inp_2_5_5_audit_issues}} 
                                  @else   
                                  ...... 
                                  @endif
                                   
                                   รายการ โดยมี การวางแผนและตรวจติดตามคุณภาพ เป็นไปตามที่กำหนด ครอบคลุมทุกกิจกรรม</td>
                        </tr>
                    </table>
                    <table autosize="1" style="margin-left: 70px">
                        <tr>
                            <td style="width: 40px;vertical-align:top">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_management_review_yes === "1" ? 'checked="checked"' : '' }} > มี</div>
                            </td>
                            <td style="width: 65px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_management_review_no === "1" ? 'checked="checked"' : '' }} > ไม่มี</div></td>
                            <td>การกำหนดแผนการทบทวนการบริหารงานอย่างน้อยปีละครั้ง ดำเนินการประชุม ทบทวนการบริหารครั้งล่าสุดเมื่อวันที่
                                 
                                 @if ($labReportInfo->inp_2_5_5_last_review_date != "")
                                  {{$labReportInfo->inp_2_5_5_last_review_date}} 
                                  @else   
                                   ................ 
                                  @endif
                                 โดยมีการทบทวนการ บริหารให้เป็นไปตามที่กำหนดครอบคลุมทุกกิจกรรม</td>
                        </tr>
                    </table>

                    <table autosize="1"  style="page-break-inside: auto; border-collapse: collapse;margin-left:70px">
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                                <div><input type="checkbox" {{ $labReportInfo->inp_2_5_5_other === "1" ? 'checked="checked"' : '' }}></div>
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_5_text_other1}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">
                            </td>
                            <td style="width: 570px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                <span style="visibility: hidden;">|</span>{{$labReportInfo->inp_2_5_5_text_other2}}
                            </td>
                        </tr>
                    </table>
                    {{-- <table autosize="1"  style="page-break-inside: auto; border-collapse: collapse;margin-left:65px;margin-top:20px">
                        <tr>
                            <td> --}}
                                @php
                                    $data_inp_2_5_5_detail = json_decode($labReportInfo->inp_2_5_5_detail, true);
                                    // ตรวจสอบว่ามี key 'lines' หรือไม่
                                    
                                @endphp
                
                                {{-- <div style="display: inline-block;margin-left:22px; width:100%; float:left"> --}}
                                    {{-- <div> --}}
                                        <div style="margin-left:70px;margin-top:10px"><input type="checkbox" {{ $labReportInfo->inp_2_5_5_issue_found === "1" ? 'checked="checked"' : '' }}> <u>พบว่า</u></div>   
                                    {{-- </div> --}}
                                    
                                    @if (!empty($data_inp_2_5_5_detail))
                                        @foreach ($data_inp_2_5_5_detail as $item)
                                            @php
                                                $lines = $item['lines'] ?? [];
                                            @endphp
                                            @if (!empty($lines))
                                            
                                                @foreach ($lines as $line)
                                                    <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:10px;padding:0;line-height:1;margin-left:70px">
                                                        <tr style="page-break-inside: auto">
                                                            <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                                                <span style="visibility: hidden;">|</span>{!!$line!!}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endforeach
                                            @endif
                                        @endforeach
                                      @else  
                                        <table autosize="1"  cellpadding="0" cellspacing="0" style="border-collapse: collapse;margin-top:-10px;margin-left:70px">
                                            <tr>
                                                <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                                    <span style="visibility: hidden;">|</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 590px; border-bottom: 1px dotted black; padding-bottom: 0; line-height: 1.2;">
                                                    <span style="visibility: hidden;">|</span>
                                                </td>
                                            </tr>
                                        </table>
                                    @endif

                                   

    
                                {{-- </div> --}}
                            {{-- </td>
                        </tr>
                    </table> --}}
                    <div style="margin-top:10px;margin-left:65px"> 
                        <div style="display: inline-block; width:100%; float:left">ไม่สอดคล้องตามข้อกำหนด ดังแสดงในรายงานข้อบกพร่อง/ข้อสังเกตที่แนบ</div>
                    </div>  
            {{-- </div> --}}

       

            {{-- ===========กรณีคำขอต่ออายุใบรับรอง============= --}}
            <div style="margin-left: 20px ;font-weight:bold"><span>(6) กรณีคำขอต่ออายุใบรับรอง</span> </div>
            <div style="margin-left:40px"> 
                <div style="display: inline-block; width:100%; float:left ; font-weight:bold"><b>6.1 การเฝ้าระวังการฝ่าฝืนหลักเกณฑ์ วิธีการและเงื่อนไขการรับรองห้องปฏิบัติการ ตาม</b> </div>
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"> (1) กฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงเครื่องหมายมาตรฐาน พ.ศ. 2556 </div>
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"> (2) หลักเกณฑ์ วิธีการและเงื่อนไขการโฆษณาของผู้ประกอบการตรวจสอบและรับรองและผู้ประกอบกิจการ </div>
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"> (3) เอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติตามข้อกำหนดในการแสดงการได้รับการรับรอง สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01) </div>

                {{-- 6.1.1 --}}
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"> <b>6.1.1 การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</b></div>
                <div style="display: inline-block;margin-left:22px; width:100%; float:left">
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_management_review_no === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการแสดง </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_management_review_yes === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการแสดง ดังนี้</div>
                    </div>
                    <div style="margin-left: 55px">
                        <div>
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_scope_certified_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">เฉพาะขอบข่ายที่ได้รับการรับรอง</div>
                        </div>
                        <div>
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_scope_certified_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_activities_not_certified_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_activities_not_certified_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                        </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
                    </div>
                 

                    <div style="margin-left: 55px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_accuracy_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_1_accuracy_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                               
                                @if ($labReportInfo->inp_2_5_6_1_1_accuracy_detail != "")
                                  {{$labReportInfo->inp_2_5_6_1_1_accuracy_detail}} 
                                  @else   
                                  ...........................................................................................................  
                                  @endif
                                
                            </div>
                        </div>   
                    </div>
                </div>
                {{-- 6.1.2 --}}
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>6.1.2 กรณีได้รับการรับรองห้องปฏิบัติการหลายสถานที่ (Multi-site)</b> การแสดงการได้รับการรับรองของห้องปฏิบัติการในใบรายงานผลการทดสอบ/สอบเทียบ</div>
                <div style="display: inline-block;margin-left:22px; width:100%; float:left">
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_display_no === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการแสดง </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_display_yes === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการแสดง ดังนี้</div>
                    </div>
                    <div style="margin-left: 55px">
                        <div>
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_scope_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">เฉพาะขอบข่ายที่ได้รับการรับรอง</div>
                        </div>
                        <div>
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_scope_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_activities_not_certified_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_activities_not_certified_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                        </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
                    </div>
                 

                    <div style="margin-left: 55px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                                @if ($labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_details != "")
                                {{$labReportInfo->inp_2_5_6_1_2_multi_site_accuracy_details}} 
                                @else   
                                ...........................................................................................................  
                                @endif
                                
                            </div>
                        </div>   
                    </div>
                </div>

                {{-- 6.1.3 --}}
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>6.1.3 กรณีห้องปฏิบัติการสอบเทียบ ป้ายแสดงสถานะการสอบเทียบ</b></div>
                <div style="display: inline-block;margin-left:22px; width:100%; float:left">
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
                    </div>
                 

                    <div style="margin-left: 55px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_3_certification_status_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_3_certification_status_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                                @if ($labReportInfo->inp_2_5_6_1_3_certification_status_details != "")
                                {{$labReportInfo->inp_2_5_6_1_3_certification_status_details}} 
                                @else   
                                ...........................................................................................................  
                                @endif
                                
                            </div>
                        </div>   
                    </div>
                    {{-- <div style="margin-left: 55px">
                        <div style="display: inline-block;float:left;width:35%">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" ></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:15px">ถูกต้อง</div>
                        </div>
                        <div style="display: inline-block;float:left;width:60%">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" ></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:15px">ไม่ถูกต้อง ระบุ.....................................................</div>
                        </div>
                        
                    </div> --}}
                </div>

                {{-- 6.1.4 --}}
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>6.1.4 การแสดงการได้รับการรับรองที่อื่น นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</b>  </div>
                <div style="display: inline-block;margin-left:22px; width:100%; float:left">

                    {{-- <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
                    </div> --}}

                    <div style="margin-left: 55px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_4_display_other_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่มี</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_4_display_other_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">มี ระบุ 
                                @if ($labReportInfo->inp_2_5_6_1_4_display_other_details != "")
                                {{$labReportInfo->inp_2_5_6_1_4_display_other_details}} 
                                @else   
                                ...........................................................................................................  
                                @endif
                                
                            </div>
                        </div>   
                    </div>

                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงการได้รับการรับรองเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข ตามข้อ 6.1 (1) – 6.1 (3) ข้างต้น</div>
                    </div>
                 

                    <div style="margin-left: 65px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_4_certification_status_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_1_4_certification_status_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                                @if ($labReportInfo->inp_2_5_6_1_4_certification_status_details != "")
                                {{$labReportInfo->inp_2_5_6_1_4_certification_status_details}} 
                                @else   
                                ...........................................................................................................  
                                @endif
                                
                            </div>
                        </div>   
                    </div>
                    {{-- <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" ></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มี </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" ></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มี ระบุ............................................................................................................................</div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">การแสดงการได้รับการรับรอง การเป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไข และกฎกระทรวง กำหนดลักษณะ การทำ การใช้ และการแสดงถึงเครื่องหมายมาตรฐาน พ.ศ.2556</div>
                    </div>
                    <div style="margin-left: 55px">
                        <div style="display: inline-block;float:left;width:35%">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" ></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:15px">ถูกต้อง</div>
                        </div>
                        <div style="display: inline-block;float:left;width:60%">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" ></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:15px">ไม่ถูกต้อง ระบุ.....................................................</div>
                        </div>
                        
                    </div> --}}
                </div>
            </div> 

            <div style="margin-left:37px"> 
                <div style="display: inline-block; width:100%; float:left ; font-weight:bold"><b>6.2 การปฏิบัติตามประกาศ สมอ. เรื่อง การใช้เครื่องหมายข้อตกลงการยอมรับร่วมขององค์การระหว่าง ประเทศว่าด้วยการรับรองห้องปฏิบัติการ (ILAC) และเอกสารวิชาการ เรื่อง นโยบายสำหรับการปฏิบัติ ตามข้อกำหนดในการแสดงการได้รับการรับรอง สำหรับห้องปฏิบัติการและหน่วยตรวจที่ได้รับใบรับรอง (TLI-01)</b></div>
                
                <table autosize="1"  style="margin-left: 10px">
                    <tr>
                        <td style="width: 150px;vertical-align:top">
                            <div>ห้องปฏิบัติการ <input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_lab_availability_yes === "1" ? 'checked="checked"' : '' }}> มี</div>
                        </td>
                        <td style="width: 55px;vertical-align:top"><div><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_lab_availability_no === "1" ? 'checked="checked"' : '' }}> ไม่มี</div></td>
                       
                    </tr>
                </table>
                <div style="display: inline-block; width:100%; float:left; margin-left:10px"> การลงนามในข้อตกลงการใช้เครื่องหมาย ILAC MRA ร่วมกับเครื่องหมายมาตรฐานทั่วไป สำหรับผู้รับใบรับรอง ร่วมกับสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</div>
                <div style="display: inline-block; width:100%; float:left; margin-left:10px"> <b><u>กรณีห้องปฏิบัติการและสำนักงานมีข้อตกลงร่วมกัน</u></b></div>

                {{-- 6.1.1 --}}
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"><b>6.2.1 การแสดงเครื่องหมายร่วม ILAC MRA ในใบรายงานผลการทดสอบ/สอบเทียบ</b>  </div>
                <div style="display: inline-block;margin-left:22px; width:100%; float:left">
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_display_no === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการแสดง </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_display_yes === "1" ? 'checked="checked"' : '' }}></div>
                        <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการแสดง ดังนี้</div>
                    </div>
                    <div style="margin-left: 55px">
                        <div>
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_scope_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">เฉพาะขอบข่ายที่ได้รับการรับรอง</div>
                        </div>
                        <div>
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_scope_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ทั้งขอบข่ายที่ได้รับและไม่ได้รับการรับรอง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_disclosure_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_disclosure_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:5px">ไม่มีการชี้บ่งถึงกิจกรรมที่ไม่ได้รับการรับรอง</div>
                        </div>
                    </div>
                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น</div>
                    </div>
                 

                    <div style="margin-left: 55px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                                @if ($labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_details != "")
                                {{$labReportInfo->inp_2_5_6_2_1_ilac_mra_compliance_details}} 
                                @else   
                                ...........................................................................................................
                                @endif
                                
                            </div>
                        </div>   
                    </div>
                </div>
                {{-- 6.2.2 --}}
                <div style="display: inline-block; width:100%; float:left; margin-left:25px"> <b>6.2.2 การแสดงเครื่องหมายร่วม ILAC MRA นอกจากในใบรายงานผลการทดสอบ/สอบเทียบ</b>  </div>
                <div style="display: inline-block;margin-left:22px; width:100%; float:left">
                    <div style="margin-left: 55px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่มี</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">มี ระบุ 
                                @if ($labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_details != "")
                                {{$labReportInfo->inp_2_5_6_2_2_ilac_mra_compliance_details}} 
                                @else   
                                ...........................................................................................................
                                @endif
                                
                            </div>
                        </div>   
                    </div>

                    <div style="margin-left: 35px">
                        <div style="display: inline-block;float:left;width:100%;padding-top:-5px;margin-left:5px">แสดงเครื่องหมายร่วม ILAC MRA เป็นไปตามประกาศ สมอ.และเอกสารวิชาการ ข้างต้น</div>
                    </div>
                 

                    <div style="margin-left: 65px">

                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_2_mra_compliance_yes === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ถูกต้อง</div>
                        </div>
                        <div style="margin-left: 25px">
                            <div style="display: inline-block;float:left;width:3%"><input type="checkbox" {{ $labReportInfo->inp_2_5_6_2_2_mra_compliance_no === "1" ? 'checked="checked"' : '' }}></div>
                            <div style="display: inline-block;float:left;width:95%;padding-top:-5px;margin-left:6px">ไม่ถูกต้อง ระบุ 
                                @if ($labReportInfo->inp_2_5_6_2_2_mra_compliance_details != "")
                                {{$labReportInfo->inp_2_5_6_2_2_mra_compliance_details}} 
                                @else   
                                ...........................................................................................................
                                @endif
                                
                            </div>
                        </div>   
                    </div>
                </div>

    

            </div> 
        </div>

    </div>


<div class="topic_three" style="page-break-inside: avoid;">
    <div style="margin-left: 20px ;font-weight:bold"><span>(3) สรุปผลการตรวจประเมิน</span> </div>
    <table autosize="1"  style="margin-left: 40px">
        <tr>
            
            <td style="vertical-align: top"><input type="checkbox" {{ $labReportInfo->inp_3_0_assessment_results === "1" ? 'checked="checked"' : '' }}></td>
            <td style="vertical-align: top">พบข้อบกพร่อง จำนวน
                
                @if ($labReportInfo->inp_3_0_issue_count != "")
                {{$labReportInfo->inp_3_0_issue_count}} 
                @else   
                .....
                @endif
                รายการ และข้อสังเกต จำนวน 
                
                @if ($labReportInfo->inp_3_0_remarks_count != "")
                {{$labReportInfo->inp_3_0_remarks_count}} 
                @else   
                .....
                @endif
                 
                รายการ ดังสำเนา รายงานข้อบกพร่องที่แนบ
                ห้องปฏิบัติการต้องส่งแนวทางและแผนการดำเนินการปฏิบัติการ แก้ไขข้อบกพร่อง ให้สำนักงานพิจารณาภายใน 30 วันนับแต่วันที่ออกรายงานข้อบกพร่องและต้องส่ง หลักฐานการแก้ไขข้อบกพร่องอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน ภายในวันที่ 
                
                @if ($labReportInfo->inp_3_0_deficiency_resolution_date != "")
                {{$labReportInfo->inp_3_0_deficiency_resolution_date}} 
                @else   
                .............
                @endif

                 (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่อง) หากพ้นกำหนดระยะเวลาดังกล่าวห้อง ปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จ อย่างมีประสิทธิผลและเป็นที่ยอมรับ ของคณะผู้ตรวจประเมิน คณะผู้ตรวจประเมินจะนำเสนอให้สำนักงานพิจารณายกเลิกคำขอรับบริการยืนยัน ความสามารถห้องปฏิบัติการของท่านต่อไป กรณีห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมด ได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก. 17025-2561 ภายในระยะเวลาที่กำหนด คณะผู้ตรวจ ประเมินจะนำเสนอคณะอนุกรรมการพิจารณา รับรองห้องปฏิบัติ{{$certi_lab->lab_name}}
                  เพื่อพิจารณา ให้การรับรองต่อไป</td>
        </tr>
        <tr>
            <td style="vertical-align: top"><input type="checkbox" {{ $labReportInfo->inp_3_0_offer_agreement === "1" ? 'checked="checked"' : '' }}></td>
            <td style="vertical-align: top">ห้องปฏิบัติการมีระบบการบริหารงานและการดำเนินงานด้านวิชาการเป็นไปตามมาตรฐานเลขที่ มอก. 17025-2561 ในขอบข่ายที่ขอรับการรับรอง คณะผู้ตรวจประเมินเห็นควรนำเสนอคณะอนุกรรมการ พิจารณารับรองห้องปฏิบัติการ{{$certi_lab->lab_name}} เพื่อพิจารณาให้การรับรองต่อไป</td>
        </tr>
    </table>
    {{-- <div style="margin-left:37px"> 
        
        <div style="display: inline-block; width:100%; float:left;font-weight:bold"><input type="checkbox" checked="checked" > พบข้อบกพร่อง ทั้งสิ้นจำนวน ........ รายการ ดังสำเนารายงานข้อบกพร่องที่แนบ</div>
        <div style="display: inline-block; width:100%; float:left; margin-left: 25px"> ห้องปฎิบัติการต้องส่งแนวทาง และแผนการดำเนินการปฏิบัติการแก้ไขข้อบกพร่อง ให้สำนักงานพิจารณา ภายใน 30 วันนับจากวันที่ได้รับรายงานข้อบกพร่อง และเมื่อครบกำหนดการแก้ไขข้อบกพร่องตามระยะเวลาที่ตกลงกันไว้ ห้องปฏิบัติการต้องส่งหลักฐานการแก้ไขข้อบกพร่องอย่างมีประสิทธิผลให้คณะผู้ตรวจประเมินพิจารณา ณ สำนักงาน ภายในวันที่ 4 กรกฎาคม 2567 (ภายใน 90 วัน นับจากวันที่ได้รับทราบรายงานข้อบกพร่อง)</div>
        <div style="display: inline-block; width:100%; float:left; margin-left: 25px"> หากห้องปฏิบัติการไม่สามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้อย่างมีประสิทธิผลภายในระยะเวลาที่ กำหนดข้างต้น คณะผู้ตรวจประเมินจะนำเสนอให้สำนักงานพิจารณายกเลิกคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการของท่านต่อไป</div>
        <div style="display: inline-block; width:100%; float:left; margin-left: 25px"> กรณีห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก.17025-2561 คณะผู้ตรวจประเมินจะนำเสนอคระอนุกรรมการพิจารณารับรองห้องปฏิบัติการ 1 เพื่อพิจารณาให้การรับรองต่อไป</div>
        <div style="display: inline-block; width:100%; float:left"><input type="checkbox" checked="checked" > ห้องปฏิบัติการมีระบบการบริหารงานและการดำเนินงานด้านวิชาการเป็นไปตามมาตรฐานเลขที่ มอก.17025-2561 ในของข่ายที่ขอรับการรับรอง คณะผู้ตรวจประเมินเห็นควรนำเสนอคณะอนุกรรมการพิจารณา รับรองห้องปฏิบัติการ 1 เพื่อพิจารณาให้การรับรองต่อไป</div>
    </div>   --}}
</div>

{{-- <div class="sign_area" style="margin-top: 70px">
    <div style="display: block;width:60%;text-align:center;float:right">
        (.............................................) <br>
        หัวหน้าคณะผู้ตรวจประเมิน <br>
        วันที่ .............................................................. <br>
    </div>
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
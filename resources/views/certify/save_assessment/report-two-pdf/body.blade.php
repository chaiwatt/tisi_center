
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
                    รายงานที่ 2
                </div>
            </div>
        </div>
        <div class="report_header" style="">
            รายงานการตรวจประเมินความสามารถของห้องปฏิบัติการทดสอบ/สอบเทียบ<br>ตามมาตรฐานเลขที่ มอก. 17025-2561
        </div>
        
        <div class="report_objective">
            <div style="text-align: left; margin-left:20px">
                สำหรับ <input type="checkbox" {{ $certi_lab->purpose_type == 1 ? 'checked="checked"' : '' }}> การขอรับใบรับรองใหม่  <input type="checkbox" {{ $certi_lab->purpose_type == 2 ? 'checked="checked"' : '' }}> การขยาย/ปรับขอบข่ายใบรับรอง <input type="checkbox" {{ $certi_lab->purpose_type == 3 ? 'checked="checked"' : '' }}> ต่ออายุใบรับรอง
            </div>
            <div style="text-align: left; margin-left:68px;margin-top:6px">
                <input type="checkbox"  {{ !in_array($certi_lab->purpose_type, [1, 2, 3]) ? 'checked="checked"' : '' }}> ....................................................................................................................
            </div>
        </div>
    </div>

    <div class="topic_one">
        <div style="margin-top: 10px;font-weight:bold">1. ข้อมูลทั่วไป</div>
        <div style="margin-left: 15px">
            <div><span style="font-weight:bold">ชื่อห้องปฏิบัติการ :</span> <span>{{$certi_lab->lab_name}}</span> </div>
            <div><span style="font-weight:bold">ตั้งอยู่เลขที่ :</span> <span> {{$labInformation->address_headquarters}} หมู่ที่ {{$labInformation->headquarters_alley}}
                @if(\Illuminate\Support\Str::contains($labInformation->headquarters_province, 'กรุงเทพ'))
                    แขวง{{$labInformation->headquarters_district}} เขต{{$labInformation->headquarters_amphur}} {{$labInformation->headquarters_province}} {{$labInformation->headquarters_postcode}} 
                @else
                    ตำบล{{$labInformation->headquarters_district}} อำเภอ{{$labInformation->headquarters_amphur}} จังหวัด{{$labInformation->headquarters_province}} {{$labInformation->headquarters_postcode}}  
                @endif</span> </div>
        </div>
    </div>

    <div class="topic_two">
        <div style="margin-top: 10px;font-weight:bold">2. การตรวจประเมิน</div>
        <div style="margin-left: 15px">
            <div><span>2.1 คณะผู้ตรวจประเมิน ประกอบด้วย :</span> </div>
            <div style="margin-left:20px"> 

                @php
                    $index = 0;
                @endphp

                <table>
                    <tbody>
                        @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                            @foreach ($auditorIds as $auditorId)
                            <tr>
                                @php
                                    $index++;
                                    $info = HP::getExpertInfo($statusId, $auditorId);
                                @endphp
                                {{-- <td style="width:250px"><span >{{$index}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</span></td>    
                                <td><span >{{$info->statusAuditor->title}}</span></td>     --}}
                                <td style="width:250px">{{$index}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</td>
                                <td>{!!$info->statusAuditor->title!!}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>  
        </div>

        <div style="margin-left: 15px">
            <div><span>2.2 รูปแบบการตรวจประเมิน :</span> </div>
            <div style="margin-left:20px"> 
                <div >
                    <div style="display: inline-block; width:90%;"> <input type="checkbox" {{ $labReportInfo->inp_2_2_assessment_on_site_chk === "1" ? 'checked="checked"' : '' }}> ณ ห้องปฎิบัติการ</div>
                </div>
                <div >
                    <div style="display: inline-block; width:39.9%; float:left"><input type="checkbox" {{ $labReportInfo->inp_2_2_assessment_at_tisi_chk === "1" ? 'checked="checked"' : '' }}> ตรวจประเมิน ณ สมอ. โดยวิธี </div>
                    <div style="display: inline-block; width:55%; float:left"><input type="checkbox" {{ $labReportInfo->inp_2_2_remote_assessment_chk === "1" ? 'checked="checked"' : '' }}> ตรวจประเมินทางไกล (remote assessment)</div>
                </div>
                <div >
                    <div style="display: inline-block; width:100%; margin-left:230px"><input type="checkbox" {{ $labReportInfo->inp_2_2_self_declaration_chk === "1" ? 'checked="checked"' : '' }}> เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)</div>
                </div>
                <div >
                    <div style="display: inline-block; width:100%; margin-left:230px"><input type="checkbox" {{ $labReportInfo->inp_2_2_bug_fix_evidence_chk === "1" ? 'checked="checked"' : '' }}> เอกสารรับรองตนเองของห้องปฏิบัติการ (self declaration)</div>
                </div>
            </div>  
        </div>

        <div style="margin-left: 15px">
            <div><span>2.3 วันที่ตรวจประเมิน : 
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
                    if ($startMonth === $endMonth && $startYearBE === $endYearBE) {
                        $formattedDate = "$startDay - $endDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE";
                    } else {
                        $formattedDate = "$startDay {$thaiMonths[$startMonth]} พ.ศ. $startYearBE - $endDay {$thaiMonths[$endMonth]} พ.ศ. $endYearBE";
                    }
                @endphp

                {{ $formattedDate }}
                {{-- {{HP::formatDateThaiFullPoint($assessment->created_at)}} --}}
                
            </span> </div>
        </div>

        <div style="margin-left: 15px;margin-top:10px">
            <div><span>2.4 ผลการตรวจประเมิน</span> </div>
            <div style="margin-left: 25px">การตรวจประเมินครั้งนี้เป็นการตรวจติดตามผลการปฏิบัติการแก้ไขข้อบกพร่องและข้อสังเกตจากการ ตรวจประเมินเมื่อวันที่ {{HP::formatDateThaiFullPoint($assessment->created_at)}} ซึ่งพบข้อบกพร่องและข้อสังเกต จำนวน {{$labReportInfo->inp_2_4_defects_and_remarks_text}} รายการ ห้องปฏิบัติการได้นำส่งหลักฐานผลการปฏิบัติการแก้ไขข้อบกพร่องและข้อสังเกต ตามหนังสือ ({{$certi_lab->lab_name}}) ลงวันที่ {{$labReportInfo->inp_2_4_doc_reference_date_text}} /ไปรษณีย์อิเล็กทรอนิกส์ วันที่ {{$labReportInfo->inp_2_4_doc_sent_date1_text}} (ถ้ามี) และ {{$labReportInfo->inp_2_4_doc_sent_date2_text}} (ถ้ามี)</div>
        </div>
        <div style="margin-left: 15px;margin-top:30px">
            <div style="margin-left: 25px;font-style:italic">โดยมีรายละเอียดผลการตรวจสอบการปฏิบัติการแก้ไขข้อบกพร่องดังเอกสารแนบ</div>
        </div>
        <div style="margin-left: 15px">
            <div style="margin-left: 25px;">คณะผู้ตรวจประเมินพบว่า</div>
            <table autosize="1"  style="margin-left:25px;margin-top:10px">
                <tr>
                    <td style="width: 30px;vertical-align:top">
                        <div><input type="checkbox" {{ $labReportInfo->inp_2_4_lab_bug_fix_completed_chk === "1" ? 'checked="checked"' : '' }} ></div>
                    </td>
                    <td>ห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับ ของคณะผู้ตรวจประเมิน</td>
                </tr>
            </table>
            <table autosize="1"  style="margin-left:25px;">
                <tr>
                    <td style="width: 30px;vertical-align:top">
                        <div><input type="checkbox" {{ $labReportInfo->inp_2_4_fix_approved_chk === "1" ? 'checked="checked"' : '' }} ></div>
                    </td>
                    <td>ห้องปฏิบัติการสามารถแก้ไขข้อบกพร่องได้แล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะ ผู้ตรวจประเมิน จำนวน {{$labReportInfo->inp_2_4_approved_text}} รายการ ยังคงเหลือข้อบกพร่อง {{$labReportInfo->inp_2_4_remain_text}} รายการ</td>
                </tr>
            </table>
        </div>


    </div>

    
    <div class="topic_three">
        <div style="margin-top: 10px;font-weight:bold">3. สรุปผลการตรวจประเมิน :</div>
        <div style="margin-left: 15px">
            <div><span>คณะผู้ตรวจประเมินพบว่า</div>
        </div>
        <table autosize="1"  style="margin-left:25px;margin-top:10px">
            @php
                $textResult = TextHelper::callLonganTokenizePost($certi_lab->lab_name . "ได้ดำเนินการแก้ไขข้อบกพร่องทั้งหมดแล้ว อย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน สมควรนำเสนอคณะอนุกรรมการพิจารณารับรอง" . $certi_lab->lab_name . "เพื่อพิจารณาให้การรับรองความสามารถห้องปฏิบัติการฯ ตามขอบข่ายที่ขอรับการรับรองต่อไป");
                $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);
            @endphp
            <tr>
                <td style="width: 30px;vertical-align:top">
                    <div><input type="checkbox" {{ $labReportInfo->inp_3_lab_fix_all_issues_chk === "1" ? 'checked="checked"' : '' }} ></div>
                </td>
                <td style="display:block;word-spacing: -0.2em;">{!!$textResult!!}</td>
            </tr>
        </table>
        <table autosize="1"  style="margin-left:25px">
            @php
            //  {{$certi_lab->lab_name}}ได้ดำเนินการแก้ไขข้อบกพร่องแล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน จำนวน <input type="text" class="input-no-border" placeholder="" style="width: 80px;text-align:center" name="inp_3_approved_text" id="inp_3_approved_text"> รายการ ยังคงเหลือข้อบกพร่อง <input type="text" class="input-no-border" placeholder="" style="width: 80px;text-align:center" name="inp_3_remain_text" id="inp_3_remain_text"> รายการ ห้องปฏิบัติการต้องส่งหลักฐานการแก้ไขข้อบกพร่องให้คณะผู้ตรวจประเมินพิจารณาภายในวันที่ {{HP::formatDateThaiFullPoint($assessment->date_car)}} (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่องครั้งแรก) ณ สำนักงาน หากห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก. 17025-2561 คณะผู้ตรวจประเมินจะนำเสนอคณะอนุกรรมการพิจารณารับรอง{{$certi_lab->lab_name}} เพื่อพิจารณาให้การรับรองต่อไป
                $textResult = TextHelper::callLonganTokenizePost($certi_lab->lab_name . "ได้ดำเนินการแก้ไขข้อบกพร่องแล้วเสร็จอย่างมีประสิทธิผลและเป็นที่ยอมรับของคณะผู้ตรวจประเมิน จำนวน " . $labReportInfo->inp_3_approved_text . " รายการ ยังคงเหลือข้อบกพร่อง" . $labReportInfo->inp_3_remain_text . " รายการ ห้องปฏิบัติการต้องส่งหลักฐานการแก้ไขข้อบกพร่องให้คณะผู้ตรวจประเมินพิจารณาภายในวันที่ " .HP::formatDateThaiFullPoint($assessment->date_car). " (ภายใน 90 วันนับแต่วันที่ออกรายงานข้อบกพร่องครั้งแรก) ณ สำนักงาน หากห้องปฏิบัติการสามารถดำเนินการแก้ไขข้อบกพร่องทั้งหมดได้แล้วเสร็จสอดคล้องตาม มาตรฐานเลขที่ มอก. 17025-2561 คณะผู้ตรวจประเมินจะนำเสนอคณะอนุกรรมการพิจารณารับรอง" .$certi_lab->lab_name ." เพื่อพิจารณาให้การรับรองต่อไป" );
                $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);
            @endphp
            <tr>
                <td style="width: 30px;vertical-align:top">
                    <div><input type="checkbox" {{ $labReportInfo->inp_3_lab_fix_some_issues_chk === "1" ? 'checked="checked"' : '' }} ></div>
                </td>
                <td style="display:block;word-spacing: -0.2em;">{!!$textResult!!}</td>
            </tr>
        </table>
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
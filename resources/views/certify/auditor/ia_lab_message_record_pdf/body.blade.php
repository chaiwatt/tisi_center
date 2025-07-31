
<div style="position: relative;">
    <div style="display:inline-block;width:15%;float:left;text-align:center;padding: left 0;">
        <div style="position: absolute; top: 68mm; left: 75mm; width: 100mm;">
            <img src="{{public_path('images/certificate-header.jpg')}}" style="width: 70px" alt="">
        </div>
    </div>
    <div style="display:inline-block;width:60%; float:right; padding-top:40px; padding-right: 130px;">
        <div style="margin-left: 150px; font-weight:bold; font-size: 40px">
            บันทึกข้อความ
        </div>
    </div>
</div>

<table style="margin-top: px;margin-bottom: 5px">
    <tr style="display:block">
        <td style="line-height: 0.5;width: 10px; font-size: 28px; font-weight:bold">ส่วนราชการ</td>
        <td style="border-bottom: 1px dotted #000; padding-bottom: 1;line-height: 0.6;width: 280px;padding-left:20px">
            {{$boardAuditorMsRecordInfo->header_text1}}
        </td>
        <td style="line-height: 0.5;width: 20px; font-size: 28px; font-weight:500">โทร</td>
        <td style="border-bottom: 1px dotted #000; padding-bottom: 1;line-height: 0.6;width: 200px;padding-left:40px">
            {{$boardAuditorMsRecordInfo->header_text2}}
        </td>
    </tr>
</table>

<table style="margin-top: 10px;margin-bottom: 5px">
    <tr style="display:block">
        <td style="line-height: 0.5;width: 10px; font-size: 28px; font-weight:bold">ที่</td>
        <td style="border-bottom: 1px dotted #000; padding-bottom: 1;line-height: 0.6;width: 320px;padding-left:20px">
            {{$boardAuditorMsRecordInfo->header_text3}}
        </td>
        <td style="line-height: 0.5;width: 20px; font-size: 28px; font-weight:bold">วันที่</td>
        <td style="border-bottom: 1px dotted #000; padding-bottom: 1;line-height: 0.6;width: 240px;padding-left:40px">
            {{$boardAuditorMsRecordInfo->header_text4}}
        </td>
    </tr>
</table>

<table style="margin-top: 10px;margin-bottom: 5px">
    <tr style="display:block">
        <td style="line-height: 0.5;width: 30px; font-size: 28px; font-weight:bold">เรื่อง</td>
        <td style="border-bottom: 1px dotted #000; padding-bottom: 1;line-height: 0.6;width: 600px;padding-left:20px">
            การแต่งตั้งคณะผู้ตรวจประเมินห้องปฏิบัติการ ( คำขอเลขที่ {{$data->app_no}} )
        </td>
    </tr>
</table>

<div style="position: absolute; top: 65mm; left: 75mm; width: 100mm">
    <div style=";text-align:center">
        <img src="{{public_path($signer->signer_url2)}}" style="width: 70px" alt="">
        <div style="margin-top: -5px;font-size:16px">{{HP::formatDateThaiFullNumThai($signer->signer_2->updated_at)}}</div>
    </div>
    
</div>

<div style="position: relative;margin-top:20px;line-height:1.15">
    <div Style="margin-bottom:5px;">เรียน &nbsp;&nbsp;&nbsp; ผอ.สก. ผ่าน ผก.รป.{{$boardAuditorMsRecordInfo->body_text1}}</div>
    <div style="font-weight: bold;margin-left:90px;">๑. เรื่องเดิม</div>
    <div>
        @php
            $textResult = TextHelper::callLonganTokenizePost("วันที่!" . $data->register_date . "!ห้องปฏิบัติ".str_replace(' ', '!', $data->lab_name)." ได้ยื่นคำขอรับใบรับรองห้องปฏิบัติการ".$data->lab_type." สาขา".$data->scope_branch." ในระบบ E-Accreditation และสามารถรับคำขอได้เมื่อวันที่ ". $data->get_date);
            // แทนที่ '!' ด้วย span ที่ซ่อนด้วย color:#fff
            $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);

        @endphp
   
        <div style="text-indent: 100px;display:block;font-size:22px;word-spacing: -0.2em">{!! $textResult !!}</div>
    </div>

    <div style="font-weight: bold;margin-left:90px ;margin-top:5px;">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
    <div>
        <div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรองให้ เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
        <div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองหน่วยตรวจ พ.ศ. ๒๕๖๔ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)</div>
        <div style="text-indent:150px">ข้อ ๖.๑.๒.๑ (๑) ระบุว่า "แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วยหัวหน้า ผู้ตรวจประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความ เหมาะสม"</div>
        <div style="text-indent:150px">ข้อ ๖.๑.๒.๑ (๒) ระบุว่า "คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมิน เอกสารต่าง ๆ ของหน่วยตรวจ ตรวจประเมินความสามารถและประสิทธิผลของการดำเนินงานของหน่วยตรวจ โดยพิจารณาหลักฐานและเอกสารที่เกี่ยวข้อง การสัมภาษณ์ รวมทั้งสังเกตการปฏิบัติงานตามมาตรฐานการตรวจ สอบและ รับรองที่เกี่ยวข้อง ณ สถาน ประกอบการของผู้ยื่นคำขอและสถานที่ทำการอื่นในสาขาที่ขอรับการ รับรอง</div>
        <div style="text-indent:125px">๒.๓ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจ ให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สั่ง ณ วันที่ ๑๓ พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุว่า "ให้ผู้อำนวยการสำนักงานคณะกรรมการ การมาตรฐานแห่งชาติเป็นผู้มี อำนาจ พิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑"</div>
    </div>
</div>

<pagebreak />
<div style="text-align: center;">
    <div style="text-align: center; margin-top: 20px;">
        <span>-{{ HP::toThaiNumber(2) }}-</span>
    </div>
</div>

    <div style="position: relative;padding-top:10px; line-height:1.15">
        <p><span style="letter-spacing: 0.15px;">เลขาธิการสํานักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมที่กํากับเป็นผู้พิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตาม</span> พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</p>
        <div style="margin-top: 80px; margin-left:520px; margin-bottom: 30px;">๓. ข้อเท็จจริง ...</div>
        
        {{-- {!!$data->fix_text2!!} --}}

        <div class="section-title">๓. สาระสำคัญและข้อเท็จจริง</div><div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข การรับรองหน่วยตรวจ พ.ศ. ๒๕๖๔ สำนักงานจะตรวจติดตามผลการรับรองหน่วยตรวจอย่างน้อย ๑ ครั้ง ภายใน ๒ ปี โดยแต่ละครั้งอาจตรวจประเมินเพียงบางส่วนหรือทุกข้อกำหนดก็ได้ตามความเหมาะสมและก่อน ครบรอบการรับรอง ๕ ปี ต้องตรวจประเมินให้ครบทุกข้อกำหนด</div>
        

        <div style="font-weight: bold;margin-left:90px;margin-top:5px;">๔. การดําเนินการ</div>
        @php
            $textResult = TextHelper::callLonganTokenizePost("รป.".$boardAuditorMsRecordInfo->body_text2." สก. ได้สรรหาคณะผู้ตรวจประเมินประกอบด้วย". str_replace(' ', '!', $data->experts) ." เพื่อดำเนินการตรวจประเมินให้การรับรองห้องปฏิบัติการ และกำหนดการตรวจประเมินห้องปฏิบัติการ".str_replace(' ', '!', $data->lab_name). " ". str_replace(' ', '!', $data->date_range). " ซึ่งเห็นสมควรเสนอแต่งตั้งคณะผู้ตรวจประเมินห้องปฏิบัติการ ดังนี้");
            // แทนที่ '!' ด้วย span ที่ซ่อนด้วย color:#fff
            $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);

        @endphp
   
        <div style="text-indent: 100px;display:block;font-size:22px;word-spacing: -0.2em">{!! $textResult !!}</div>

        <div style="display:inline-block;padding-top:0%;padding-top:20px;padding-bottom:15px">
            <table style="border-collapse: collapse;margin-left:70px;">
                @php
                    $index = 0;
                @endphp
                @foreach ($data->statusAuditorMap as $statusId => $auditorIds)
                    @php
                        $index++;
                    @endphp

                    @foreach ($auditorIds as $auditorId)
                        @php
                            $info = HP::getExpertInfo($statusId, $auditorId);
                       
                            $textResult = TextHelper::callLonganTokenizePost($info->statusAuditor->title);
                            // แทนที่ '!' ด้วย span ที่ซ่อนด้วย color:#fff
                            $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);
                
                        @endphp
                        <tr>
                            <td style="width: 220px">{{HP::toThaiNumber($index)}}. {{$info->auditorInformation->title_th}}{{$info->auditorInformation->fname_th}} {{$info->auditorInformation->lname_th}}</td>
                            <td style="width: 100px">{{$info->auditorInformation->number_auditor}}</td>
                            <td style="padding-left:10px;word-spacing: -0.2em">{!!$textResult!!}</td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>

        <p style="margin-top: 10px;">โดยมีรายละเอียดการตรวจประเมินดังกําหนดการตรวจประเมินที่แนบมาพร้อมนี้</p>

        <div style="font-weight: bold;margin-left:90px;margin-top:5px;">๕. ข้อปัญหา/อุปสรรค</div>
        <p style="margin: top 0;margin-left:105px;">-</p>

        <div style="font-weight: bold;margin-left:90px;margin-top:5px;">๖. ข้อพิจารณา</div>
        <p style="margin: top 0;margin-left:105px;">เพื่อโปรดนําเรียน สมอ. พิจารณาลงนามอนุมัติการแต่งตั้งคณะผู้ตรวจประเมิน</p>

        <div style="font-weight: bold;margin-left:90px;margin-top:5px;">๗. ข้อเสนอ</div>
        <p style="margin: top 0;margin-left:105px;letter-spacing: 0.85px;">จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนําเรียน ลมอ. </p>
        <p style="margin: top 0;letter-spacing: 0.1px;"> <span style="letter-spacing: 0.1px;">เพื่ออนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเพื่อดําเนินการตรวจประเมินให้การรับรองห้องปฏิบัติการ{{$data->lab_type}} ห้องปฏิบัติการ{{$data->lab_name}}</span>รายละเอียดดังข้างต้น</p>

    </div>


    <pagebreak />
    
    <div style="text-align: center;">
        <div style="text-align: center; margin-top: 20px;">
            <span>-{{ HP::toThaiNumber(3) }}-</span>
        </div>
    </div>

<div style="padding-top:30px">
    <table style="width: 100%;text-align:center;">
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
</div>


        <div style="margin-top: 170px; margin-left:520px; margin-bottom: 30px;">เรียน ลมอ.</div>

        <div style="position: relative;padding-top:10px ;line-height:1.18">
            <p style="margin: bottom 0;">เรียน ลมอ.</p>
            <div style="margin-left:90px; letter-spacing: 0.5px;">สก. ได้ตรวจสอบรายละเอียดการดําเนินการสําหรับการแต่งตั้งคณะผู้ตรวจประเมินแล้ว</div>
            <p style="margin: top 0;">ตามหลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองห้องปฏิบัติการที่กําหนด รายละเอียดดังข้างต้น</p>

            <div style="margin-left:90px;margin-top:5px; letter-spacing: 0.15px;">จึงเรียนมาเพื่อโปรดอนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเพื่อดําเนินการตรวจประเมินให้การ</div>
            <div style="letter-spacing: 0.1px;">รับรองห้องปฏิบัติการ{{$data->lab_type}} {{$data->lab_name}} ตามวันและเวลา ดังกล่าว</div>

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
                    <td>ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ</td>
                </tr>
                <tr>
                    <td style="width:40%"></td>
                    <td>{{HP::formatDateThaiFullNumThai($signer->signer_3->updated_at)}}</td>
                </tr>
            </table>
     

            <div style="position: relative;padding-top:110px ;line-height:1.18">
                <p style="margin: top 0;">
                    <input type="checkbox" id="option1" name="committee" style="margin-right: 5px;" checked="checked">
                    <label for="option1" style="border-bottom: 1px dotted black; line-height: 1; margin-bottom: 0;">อนุมัติ</label>
                </p>
                <p style="margin: top 0;padding-bottom: 10px;padding-top:10px;" checked="checked">
                    <input type="checkbox" id="option1" name="committee" style="margin-right: 5px;" checked="checked">
                    <label for="option1" style="border-bottom: 1px dotted black; line-height: 1; margin-bottom: 0;">ดำเนินการต่อไป</label>
                </p>
                <p style="margin: top 0;">
                    <input type="checkbox" id="option1" name="committee" style="margin-right: 5px;">
                    <label for="option1" style="border-bottom: 1px dotted black; line-height: 1; margin-bottom: 0;">..........................</label>
                </p>
            </div>

        </div>

<pagebreak />
<div style="text-align: center;">
    <div style="text-align: center; margin-top: 20px;">
        <span>-{{ HP::toThaiNumber(4) }}-</span>
    </div>
</div>
<div style="padding-top:70px">
    <table style="width: 100%;text-align:center">
        <tr>
            <td>
                <img src="{{public_path($signer->signer_url4)}}" style="width: 70px" alt=""></td>
            <td style="width:45%"></td>
        </tr>
        <tr>
            <td>{{$signer->signer_4->signer_name}}</td>
            <td></td>
        </tr>
        <tr>
            <td >ตำแหน่ง ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ<br>
                ปฏิบัติราชการแทน<br>
                เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</td>
            <td></td>
        </tr>
        <tr>
            <td >{{HP::formatDateThaiFullNumThai($signer->signer_4->updated_at)}}</td>
            <td></td>
        </tr>
    </table>
</div>



 

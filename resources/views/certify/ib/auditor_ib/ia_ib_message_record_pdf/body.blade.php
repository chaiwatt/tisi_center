
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
            {{$boardAuditorMsRecordInfo->header_text4}}
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
            การแต่งตั้งคณะผู้ตรวจประเมินหน่วยตรวจ {{$data->name_standard}} ( คำขอเลขที่ {{$data->app_no}} )
        </td>
    </tr>
</table>

<div style="position: absolute; top: 65mm; left: 75mm; width: 100mm">
    <div style=";text-align:center">
        <img src="{{public_path($signer->signer_url1)}}" style="width: 70px" alt="">
        <div style="margin-top: -5px;font-size:16px">{{HP::formatDateThaiFullNumThai($signer->signer_2->updated_at)}}</div>
    </div>
</div>

<div style="position: relative;margin-top:20px;line-height:1.15">
    <div Style="margin-bottom:5px;">เรียน &nbsp;&nbsp;&nbsp; ผอ.สก. ผ่าน ผก.รต.{{$boardAuditorMsRecordInfo->body_text1}}</div>
    <div style="font-weight: bold;margin-left:90px;margin-top:15px">๑. เรื่องเดิม</div>
    <div>
        @php
            $textResult = TextHelper::callLonganTokenizePost("วันที่!" . $data->register_date . "!".str_replace(' ', '!', $data->name_standard)." ได้ยื่นคำขอรับใบรับรองหน่วยตรวจ ในระบบ E-Accreditation และสามารถรับคำขอได้เมื่อวันที่ ". $data->get_date);
            $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);

        @endphp
   
        <div style="text-indent: 100px;display:block;font-size:22px;word-spacing: -0.2em">{!! $textResult !!}</div>
    </div>
    <div style="margin-top:15px">
        {!!$data->fix_text1!!}
    </div>
  
     
    <div style="margin-top:15px">
        {!!$data->fix_text2!!}
    </div>
    

</div>

<pagebreak />
<div style="text-align: center;">
    <div style="text-align: center; margin-top: 20px;">
        <span>-{{ HP::toThaiNumber(2) }}-</span>
    </div>
</div>

    <div style="position: relative;padding-top:10px; line-height:1.15">

        
        
        

        <div style="font-weight: bold;margin-left:90px;margin-top:5px;">๔. การดําเนินการ</div>
        @php
            $textResult = TextHelper::callLonganTokenizePost("รต.".$boardAuditorMsRecordInfo->body_text2." รต. ได้สรรหาคณะผู้ตรวจประเมินประกอบด้วย หัวหน้าผู้ตรวจประเมิน ผู้ตรวจประเมินและผู้เชี่ยวชาญ เพื่อดำเนินการตรวจประเมินสถานประกอบการ".str_replace(' ', '!', $data->name_standard). " ". str_replace(' ', '!', $data->date_range). " ดังนี้");
            // แทนที่ '!' ด้วย span ที่ซ่อนด้วย color:#fff
            $textResult = str_replace('!', '<span style="color:#fff;">!</span>', $textResult);

        @endphp
   
        <div style="text-indent: 100px;display:block;font-size:22px;word-spacing: -0.2em">{!! $textResult !!}</div>

            <table style="border-collapse: collapse;margin-left:90px;margin-top:-5px">

                    @foreach ($boardAuditor->CertiIBAuditorsLists  as $key => $auditor)
                        <tr>
                            <td style="width: 180px">({{$key+1}}). {{$auditor->temp_users}}</td>
                            <td style="width: 100px">{{$auditor->StatusAuditorTo->title}}</td>
                        </tr>
                    @endforeach
            </table>

        <p style="margin-top: 10px;padding:0px">โดยมีรายละเอียดการตรวจประเมินดังกําหนดการตรวจประเมินที่แนบมาพร้อมนี้

            @if (!is_null($boardAuditor->FileAuditors2) &&  $boardAuditor->FileAuditors2 != '')
            <p style="margin-left:90px;padding-top:-15px">
            (1). <a style="text-decoration: none" href="{{url('certify/check/file_ib_client/'.$boardAuditor->FileAuditors2->file.'/'.( !empty($boardAuditor->FileAuditors2->file_client_name) ? $boardAuditor->FileAuditors2->file_client_name :  basename($boardAuditor->FileAuditors2->file)   ))}}" target="_blank">
                    {{$boardAuditor->FileAuditors2->file_client_name}}
                </a>
            </p>               
        @endif

        </p>
          

        <div style="font-weight: bold;margin-left:90px;">๕. ข้อปัญหา/อุปสรรค</div>
        <p style="margin: top 0;margin-left:105px;">-</p>

        <div style="font-weight: bold;margin-left:90px;margin-top:10px;">๖. ข้อพิจารณา</div>
        <p style="margin: top 0;margin-left:105px;">เพื่อโปรดนําเรียน สมอ. พิจารณาลงนามอนุมัติการแต่งตั้งคณะผู้ตรวจประเมิน</p>

        <div style="font-weight: bold;margin-left:90px;margin-top:10px;">๗. ข้อเสนอ</div>
        {{-- <p style="margin: top 0;margin-left:105px;letter-spacing: 0.85px;">จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนำเรียน ลมอ. เพื่ออนุมัติการแต่งตั้งคณะผู้ตรวจประเมินสถานประกอบการ{{$data->name_standard}} ในวันที่ {{$data->date_range}} รายละเอียดดังข้างต้น</p> --}}
        <div style="text-indent: 100px;display:block;font-size:22px;">จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นเป็นการสมควร ขอได้โปรดนำเรียน ลมอ. เพื่ออนุมัติ การแต่งตั้งคณะผู้ตรวจประเมินสถานประกอบการ{{$data->name_standard}} ในวันที่ {{$data->date_range}} รายละเอียด ดังข้างต้น</div>
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
            <td>  <img src="{{public_path($signer->signer_url2)}}" style="width: 70px" alt=""></td>
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
            <p style="margin: top 0;">ตามหลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองการรับรองหน่วยตรวจ รายละเอียด ดังข้างต้น</p>

            <div style="margin-left:90px;margin-top:5px; letter-spacing: 0.15px;">จึงเรียนมาเพื่อโปรดอนุมัติการแต่งตั้งคณะผู้ตรวจประเมินเพื่อดําเนินการตรวจประเมินให้การ</div>
            <div style="letter-spacing: 0.1px;">การรับรองหน่วยตรวจ {{$data->name_standard}} ตามวันและเวลา ดังกล่าว</div>

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



 

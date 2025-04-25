<style>
    #style{

         padding: 5px;
         border: 5px solid gray;
         margin: 0;
         
    }    
    #customers td, #customers th {
         border: 1px solid #ddd;
         padding: 8px;
         }

     #customers th {
     padding-top: 12px;
     padding-bottom: 12px;
     text-align: left;
     background-color: #66ccff;
     color: #000000;
     }   

</style>

<div id="style">

    <p>
        <b>เรียน :  {{$lawlistministry->dear ?? ''}}</b>
    </p>

    <p>
        <b>เรื่อง : </b>  {{$lawlistministry->title ?? ''}}
    </p>

    <p>{!! str_repeat('&nbsp;', '10')!!}
        ตามที่ท่านได้แสดงความคิดเห็นเกี่ยวกับการกำหนดให้  {{$lawlistministry->tis_name ?? ''}} ต้องเป็นไปตามมาตรฐานเลขที่ มอก.  {{$lawlistministry->tis_no ?? ''}} นั้น
    </p> 

    <p>
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ขอเรียนแจ้งว่า คณะกรรมการมาตรฐานผลิตภัณฑ์อุตสาหกรรม พิจารณาข้อคิเห็นดังกล่าวแล้ว เมื่อวันที่ {{ !empty($lawlistministry->date_diagnosis) ? HP::DateThaiFormal($lawlistministry->date_diagnosis) : '' }} 
    </p> 

    <p>
        วินิจฉัยให้ดำเนินการผลิตภัณฑ์ดังกล่าว ต้องเป็นไปตามมาตรฐานเลขที่ มอก.  {{$lawlistministry->tis_no ?? ''}} 
    </p>

    @php
        $attachs_result= $lawlistministry->AttachFileResult;
    @endphp

    @if (!empty($attachs_result))

        <p>
            หนังสือแจ้งผลวินิจฉัย  <a href="{!! HP::getFileStorage($attachs_result->url) !!}" target="_blank">{!! !empty($attachs_result->filename) ? $attachs_result->filename : '' !!}</a>
        </p>
    @endif

    <p>
        จึงเรียนมาเพื่อโปรดทราบ <br> TISI-LAW <br>
        <hr style="border-top: 1px solid black;">
        e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
    </p> 

    @php
        $config = HP::getConfig(false);
    @endphp

    @if (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 1)  <!-- แสดงข้อมูลติดต่อกลาง -->
        {!! $config->contact_mail_footer  !!}
    @elseif (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 2) <!-- แสดงข้อมูลติดต่อผู้บันทึก -->
        @php
             $name       =  auth()->user()->FullName ?? '';
             $reg_wphone =   auth()->user()->reg_wphone ?? '';
             $reg_email  =  auth()->user()->reg_email ?? '';
        @endphp
        {!! '<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'.$name.'<br>โทร. '.$reg_wphone.'<br>อีเมล '.$reg_email.'</p>' !!}
    @endif
 </div> 



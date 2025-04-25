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
    .indent50 {
    text-indent: 50px;
    } 
    .indent100 {
        text-indent: 100px;
    } 
</style>

<div id="style">
    <p>
        <b>เรียน :  {{$case->owner_contact_name ?? ''}}</b>
    </p>

    <p>
        <b>เรื่อง : </b>แจ้งเตือนการพิจารณาข้อมูลงานคดี ของ {{$case->offend_name ?? ''}} เลขอ้างอิง {{$case->ref_no ?? ''}}
    </p>

    <p class="indent50"> 
            ตามที่คุณได้แจ้งงานคดีผลิตภัณฑ์อุตสาหกรรมผ่านระบบ ของ {{$case->offend_name ?? ''}}  {{ !empty($case->created_at) ?  'เมื่อวันที่ '.HP::DateThaiFormal($case->created_at) : '' }} เลขที่อ้างอิง {{$case->ref_no ?? ''}} นั้น
    </p> 

    <p class="indent50"> 
            นิติกรได้ตรวจสอบข้อมูลงานคดีดังกล่าวแล้ว และขอแจ้งผลการตรวจสอบ ดังนี้
    </p> 

    <p class="indent100"> 
            สถานะ :  {{ !empty($case->StatusText) ? $case->StatusText : ' ' }}
    </p>

    @if (!empty($case->case_number))
        <p class="indent100"> 
            เลขคดี : {{$case->case_number}}
        </p> 
    @endif

    @if (!empty($case->accept_remark))
        <p class="indent100"> 
            หมายเหตุ : {{$case->accept_remark}}
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
 
 

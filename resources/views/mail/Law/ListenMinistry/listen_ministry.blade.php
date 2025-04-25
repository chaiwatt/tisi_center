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
        <b>เรียน :  {{ $dear ?? '' }}</b>
    </p>

    <p>
        <b>เรื่อง : </b>  {{$lawlistministry->title ?? ''}}
    </p>

    <p>{!! str_repeat('&nbsp;', '10')!!}
        ด้วยคณะกรรมการมาตรฐานผลิตภัณฑ์อุตสาหกรรมมีมติเห็นสมควรกำหนดให้ผลิตภัณฑ์อุตสาหกรรม  {{$lawlistministry->tis_name ?? ''}} ต้องเป็นไปตามมาตรฐานเลขที่ มอก.  {{$lawlistministry->tis_no ?? ''}} ซึ่งจะต้องออกกฎกระทรวงในลำดับต่อไป นั้น
    </p> 

    <p>{!! str_repeat('&nbsp;', '10')!!}
        ดังนั้น จึงจัดให้มีการรับฟังความคิดเห็นของผู้มีส่วนได้ส่วนเสียหรือผู้มีประโยชน์เกี่ยวข้อง หากท่านประสงค์จะแสดงความคิดเห็นผ่านแบบรับฟังความเห็นออนไลน์ หรือทำหนังสือถึงสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ภายใน {!! !empty($lawlistministry->amount)?$lawlistministry->amount.' วันนับแต่วันประกาศ':null !!}
    </p> 

    <p>{!! str_repeat('&nbsp;', '60')!!}
        <a  href="{{$url}}" style="display:inline-block;background:#0066cc;color:#ffffff;font-family:Arial;font-size:14px;font-style:normal;font-weight:normal;line-height:100%;margin:0;text-decoration:none;text-transform:none;padding:8px 15px;border-radius:5px" target="_blank">คลิกแสดงความคิดเห็นออนไลน์</a>
    </p> 

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
            $user = !empty($lawlistministry->user_updated)?$lawlistministry->user_updated:(!empty($lawlistministry->user_created)?$lawlistministry->user_created:null)
        @endphp
        @if(!empty($user))
        @php
                $name       = ($user->FullName ?? '');
                $reg_wphone = ($user->reg_wphone ?? '');
                $reg_email  = ($user->reg_email ?? '');
        @endphp
            {!! '<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'.$name.'<br>โทร. '.$reg_wphone.'<br>อีเมล '.$reg_email.'</p>' !!}   
        @endif
    @endif
 </div> 



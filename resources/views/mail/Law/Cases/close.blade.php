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
         เรียน : {!! !empty($case->owner_name) ? '<b>'.$case->owner_name.' </b> (ผู้แจ้งงานคดี)' : '' !!} 
    </p>
    <p>
         เรื่อง : แจ้งปิดงานคดี ของ {!! !empty($case->offend_name) ?  $case->offend_name : '' !!} เลขคดี  {!! !empty($case->case_number) ? $case->case_number : '' !!}
    </p>

     <p class="indent50">
            ตามที่ท่านได้แจ้งงานคดีของ {!! !empty($case->offend_name) ?  $case->offend_name : '' !!} เลขคดี {!! !empty($case->case_number) ? $case->case_number : '' !!} เมือวันที่   {{ !empty($case->created_at) ? HP::DateThaiFormal($case->created_at) : '' }}  นั้น
     </p>
     <p>
         บัดนี้ เจ้าหน้าที่กองกฏหมายได้ดำเนินการเสร็จสิ้นแล้ว  {!! !empty($url) ?  $url : '' !!}
    </p>
    


    <p> 
         จึงเรียนมาเพื่อโปรดทราบ <br> e-Legal <br>
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
         
         
         

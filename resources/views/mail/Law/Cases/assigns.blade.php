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
          <b>เรื่อง </b>  {!!'แจ้งมอบหมายงานคดี' !!}
     </p>
     <p>
          <b>เรียน </b>  {!! !empty($user->FullName) ?  $user->FullName : '' !!}
     </p>

      <p class="indent50">
             ผู้ประกอบการ :  {!! !empty($case->offend_name) ?  $case->offend_name : '' !!}  {!! !empty($case->offend_taxid) ?  ' | '.$case->offend_taxid : '' !!}
      </p>
      <p class="indent50">
             มอบหมายโดย :  {!! !empty(auth()->user()->FullName) ?  auth()->user()->FullName : '' !!}  
      </p>
      <p class="indent50">
             เมื่อวันที่ :  {!! HP::formatDateThaiFull(date("Y-m-d")) !!}  
      </p>
      @if (!empty($case->user_assign_to->FullName))
      <p class="indent50">
             ผู้รับผิดชอบ  :  {!!   $case->user_assign_to->FullName  !!}
      </p>  
      @endif
      @if (!empty($case->user_lawyer_to->FullName))
      <p class="indent50">
             นิติกร  :  {!!   $case->user_lawyer_to->FullName  !!}
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
          
          
          
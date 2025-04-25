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
          <b>เรื่อง </b>  {!!'แจ้งมอบหมายรับงานเข้ากอง' !!}   {!!  !empty($track->title) ?  ' | '.$track->title : '' !!}
     </p>
     <p>
          <b>เรียน </b>  {!! !empty($user->FullName) ?  $user->FullName : '' !!}
     </p>
     <p class="indent50">
             เลขที่หนังสือ : {!! !empty($track->book_no) ?  $track->book_no : '' !!} 
     </p>
     <p class="indent50">
             รับงานเข้ากองเมื่อวันที่ : {!! !empty($track->receive_date) ?  HP::formatDateThaiFull($track->receive_date) : '' !!} 
     </p>
     <p class="indent50">
             เลขรับ :  {!! !empty($track->receive_no) ?  $track->receive_no : '' !!}  
     </p>
     <p class="indent50">
             หน่วยงานเจ้าของเรื่อง :  {!! $deperment_name !!}  
     </p>
     <p class="indent50">
             ประเภทงาน :  {!! !empty($track->JopTypeName) ?  $track->JopTypeName : '' !!} 
     </p>
     <p class="indent50">
             ชื่อเรื่อง :  {!! !empty($track->title) ?  $track->title : '' !!} 
     </p>
     @if (!empty($track->remarks))
     <p class="indent50">
            คำอธิบาย  :  {!!   $track->remarks  !!}
     </p>  
     @endif
     @if( isset($track->file_law_track_receives) && ($track->file_law_track_receives->count() >= 1) )
     <p class="indent50">
             รายละเอียด 
     </p>  
     @foreach ( $track->file_law_track_receives as $Ifile )
     <p class="indent100">
          {!!  !empty($Ifile->caption)?$Ifile->caption.' ':null !!}
          <a href="{!! HP::getFileStorage($Ifile->url) !!}" target="_blank">
               {!! !empty($Ifile->filename) ? $Ifile->filename : '' !!}
          </a>
     </p>  
     @endforeach
     @endif
     @if (!empty($track->user_assign_to->FullName))
     <p class="indent50">
            ผู้รับผิดชอบ  :  {!!   $track->user_assign_to->FullName  !!}
     </p>  
      @endif
      @if (!empty($track->user_lawyer_to->FullName))
      <p class="indent50">
             ผู้มอบหมาย  :  {!!   $track->user_lawyer_to->FullName  !!}
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
          
          
          
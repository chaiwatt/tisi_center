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
          <b>เรื่อง </b> {!! !empty($level_approve->authorize_name) ?  $level_approve->authorize_name : '' !!} 
     </p>
     <p>
          <b>เรียน </b>  {!!  $title !!} 
     </p>
     <p class="indent50">
           ตามที่ {{$case->owner_name ?? ''}}  ได้มีการตรวจพบ {{$case->offend_name ?? ''}} 
      </p>
      <p >
           มีการฝ่าฝืนกฎหมายตามมาตรา {!! !empty($case->SectionListName) ?  $case->SectionListName : '' !!}  {{ !empty($case->offend_date) ?  'เมื่อวันที่ '.HP::DateThaiFormal($case->offend_date) : '' }}   นั้น
      </p> 
      <p class="indent50">
            ขอให้ท่านตรวจสอบเพื่อพิจารณาและบันทึกผลการพิจารณาผ่านระบบงานต่อไป    
             <a  href="{{$url}}" 
               style="display:inline-block;background:#0066cc;color:#ffffff;font-family:Arial;font-size:14px;font-style:normal;font-weight:normal;line-height:100%;margin:0;text-decoration:none;text-transform:none;padding:8px 15px;border-radius:5px" 
               target="_blank">คลิกตรวจสอบ/พิจารณา
             </a>
      </p>
 
     <p> 
          จึงเรียนมาเพื่อโปรดทราบ และดำเนินการต่อไป <br> e-Legal <br>
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
          
          
          
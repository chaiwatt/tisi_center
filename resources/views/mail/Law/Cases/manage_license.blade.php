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
          
@php
     $text             =  !empty($case->offend_name) ? $case->offend_name : '';
     $text             .= !empty($case->offend_taxid) ? ' | '.$case->offend_taxid : ''; 

     $tis              =  !empty($case->tis->tb3_Tisno) ? $case->tis->tb3_Tisno : '';
     $tis              .= !empty($case->tis->tb3_TisThainame) ? ' '.$case->tis->tb3_TisThainame : '';

     $name             =  auth()->user()->FullName ?? '';
     $reg_wphone       =  auth()->user()->reg_wphone ?? '';
     $reg_email        =  auth()->user()->reg_email ?? '';

     $status_arr       = [ '1'=> 'ใช้งานใบอนุญาต', '2'=> 'พักใช้ใบอนุญาต','3'=> 'เพิกถอนใบอนุญาต' ];

     $license_result   =  !empty($case->license_result)?$case->license_result:null;
     $attachs_document = $license_result->FileAttachTo;
@endphp



<div id="style">
     <p>
          <b>เรียน : </b> กรรมการผู้จัดการ {!! $case->offend_name ?? '' !!}
     </p>
     <p>
          <b>เรื่อง : </b>  แจ้งผลการพิจารณาใบอนุญาต ของ {!! !empty($case->offend_name) ?  $case->offend_name : '' !!} เลขอ้างอิง  {!! !empty($case->ref_no) ? $case->ref_no : '' !!}
     </p>
     <p class="indent50">
          ตามที่ {!! $case->offend_name ?? '' !!} เป็นผู้ได้รับใบอนุญาตเลขที่ {!! $case->offend_license_number ?? '' !!} ออกให้ ณ วันที่ {!! !empty($case->tb4_tisilicense->tbl_licenseDate) ? HP::formatDateThaiFull($case->tb4_tisilicense->tbl_licenseDate) : '' !!}
          และได้พบการกระทำความผิดตามมาตรา  {!! !empty($case->SectionListName) ? $case->SectionListName : '' !!} เมือวันที่   {{ !empty($case->created_at) ? HP::DateThaiFormal($case->created_at) : '' }}  นั้น
     </p>
     <p class="indent50"> 
          จึงขอแจ้งผลการพิจารณาใบอนุญาต ดังนี้
     </p> 
     <p class="indent50">  
          <b>สถานะ :  </b> 

          {!! !empty($license_result) && array_key_exists($license_result->status_result, $status_arr )?$status_arr[ $license_result->status_result ]:null !!}

          @if(  !empty($license_result)  && in_array( $license_result->status_result, [2] ) )
               ( ตั้งแต่วันที่ {!! !empty($license_result->date_pause_start) ? HP::formatDateThaiFull($license_result->date_pause_start) : '' !!} - วันที่ {!! !empty($license_result->date_pause_end) ? HP::formatDateThaiFull($license_result->date_pause_end) : '' !!} )
          @endif

          @if(  !empty($license_result)  && in_array( $license_result->status_result, [3] ) )
               {!! !empty($license_result->date_revoke) ? HP::formatDateThaiFull($license_result->date_revoke) : '' !!}
          @endif
     </p> 

     @if (!empty($attachs_document))
          <p class="indent50"> 
               หลักฐานการพิจารณา  <a href="{!! HP::getFileStorage($attachs_document->url) !!}" target="_blank">{!! !empty($attachs_document->filename) ? $attachs_document->filename : '' !!}</a>
          </p> 
     @endif

     <p>
          สามารถตรวจสอบ {!! !empty($url) ?  $url : '' !!}
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
          
          
          
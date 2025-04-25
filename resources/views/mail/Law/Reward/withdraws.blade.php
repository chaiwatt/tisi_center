<style>
#style{
    padding: 5px;
    border: 5px solid gray;
    margin: 0; 
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
    <b>เรื่อง : </b> ผู้มีสิทธิ์ได้รับเงินรางวัล
</p>
<p>
    <b>เรื่อง : </b>  {{$title}}
</p>
<p class="indent50"> 
  ตามที่คุณมีสิทธิ์ได้รับเงินรางวัลจากการคำนวณส่วนแบ่งเงินค่าปรับ ขอแจ้งสถานะการเบิกจ่ายเงินรางวัล
</p>
<p class="indent50"> 
   สถานะ : {{ !empty($withdraws->status)  && $withdraws->status == '2' ? 'เบิกจ่ายเรียบร้อย' : 'อยู่ระหว่างเบิกจ่าย' }} 
 </p>
 <p class="indent50"> 
   เมื่อวันที่ : {{ !empty($withdraws->approve_date) ?  HP::DateThaiFormal($withdraws->approve_date) : '' }} 
 </p>
  <p class="indent50"> 
    หมายเหตุ : จะมีการโอนเงินรางวัลไปยังบัญชีของท่านภายใน 15 วัน <br>
    หากท่านยังไม่ได้รับเงิน กรุณาติดต่อเจ้าหน้าที่กองกฎหมาย  โทร.02 430 6830 ต่อ 2010
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
$name       =  auth()->user()->FullName ?? '';
$reg_wphone =   auth()->user()->reg_wphone ?? '';
$reg_email  =  auth()->user()->reg_email ?? '';
@endphp
{!! '<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'.$name.'<br>โทร. '.$reg_wphone.'<br>อีเมล '.$reg_email.'</p>' !!}
@endif
</div> 
 
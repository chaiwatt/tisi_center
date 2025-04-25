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
    {{$title}}
</p>
<p>
  <b>เรียน : </b>  {!! !empty($recepts->name) ? $recepts->name : '' !!} 
 </p>
<p>
    <b>เรื่อง : </b> เรื่องใบสำคัญรับเงิน
</p>
<p class="indent50"> 
  ตามที่คุณมีสิทธิ์ได้เงินรางวัลจากการคำนวณส่วนแบ่งเงินค่าปรับ ขอแจ้งใบสำคัญรับเงินตามสิ่งที่แนบมาด้วย
</p>
<p class="indent50"> 
    ทั้งนี้ ขอให้ลงนาม และแนบหลักฐานใบสำคัญรับเงินกลับเข้ามาในระบบ  <a href="{{url('/api/v1/reward/receipts/'.base64_encode($recepts->id))}}" class="btn btn-link" target="_blank">คลิก >></a>
</p>
 
 <p> 
   หมายเหตุ : ท่านจะได้รับเงินรางวัลภายหลังเมื่อมีการอนุมัติเบิกจ่าย 
</p>  
    <p>
        จึงเรียนมาเพื่อโปรดทราบ <br> TISI-LAW <br>
        <hr style="border-top: 1px solid black;">
        e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
    </p> 

</div> 
 
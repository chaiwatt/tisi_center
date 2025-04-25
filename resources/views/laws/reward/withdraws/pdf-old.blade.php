<style>
    @page {
        margin:2%;padding:0;
    }
    @page {
    header: page-header;
    footer: page-footer;
}
    body {
        /* font-family: 'THSarabunNew', sans-serif; */
        font-family: 'thiasarabun', sans-serif;
    }
    .content{
            padding-top: 5%;
            padding-left: 2%;
            padding-right: 2%;
            margin: 0px;
            height: 100%;
            top: 10%;
            position: relative;
    
        }
    .tc{
        text-align: center;
    }
    .tl{
        text-align: left;
    }
 
    h1,h2,h3,h4,h5,h6,p{
        padding: 0px;
        margin: 0px;
        line-height: 2em;
    }
    .space{
        height: 20px;
    }
    .space-mini{
        height: 10px;
    }
    b{
        font-weight: bold;
    }
    h1{
        margin-bottom: 10px;
    }
    .w-100{
        width: 100%;
    }
    .tab {
        display:inline-block;
        margin-left: 40px;
    }
    .tr{
        text-align: right;
    }
    .w-66{
        width: 66%;
    }
    .w-70{
        width: 70%;
    }
    .w-33{
        width: 33%;
    }
    .w-15{
        width: 15%;
    }
    .w-50{
        width: 50%;
    }
    table{
        line-height:27px;
    }
    


    .font-10{
        font-size: 10pt;
    }
    .font-11{
        font-size: 11pt;
    }
    .font-12{
        font-size: 12pt;
    }
    .font-13{
        font-size: 13pt;
    }
    .font-14{
        font-size: 14pt;
        padding-bottom: -6px;
    }
    .font-15{
        font-size: 15pt;
    }
    .font-16{
        font-size: 16pt;
        padding-bottom: -6px;
    }
    .font-18{
        font-size: 18pt;
    }
    .font-19{
        font-size: 19pt;
    }
    .font-20{
        font-size: 20pt;
    }
    .font-25{
        font-size: 25pt;
    }
  .free-dot {
            /* border-bottom: thin dotted #000000; 
            line-height: 0px; */
            border-bottom: thin dotted #000000; 
            padding-bottom: -5px;
   }
   .custom-label{
        background: #ffffff;
        border-bottom: thin dotted #ffffff; 
        padding-bottom: 5px;
      }

    .line-height25  {
        line-height:25px;
        padding-top: -5px;
    }
    .box-border {
        border: 1px solid #000000;
    }
    .box-border-right {
        border-right: 1px solid #000000;
    }
    .box-border-left  {
        border-left : 1px solid #000000;
    }
    .box-border-top  {
        border-top : 1px solid #000000;
    }
    .box-border-bottom  {
        border-bottom : 1px solid #000000;
    }

    /* .table>tr>td {
        padding: 0px 8px;
        vertical-align: middle;
    } */

</style>


<div class="content">

    <table width="100%"   > 
        <tr>
        <td width="10%">  </td>
        <td class="font-16" width="80%" align="center"> 
                 <b>ใบเบิกค่าใช้จ่าย (จ่ายผ่านส่วนรายการ)</b>
        </td>
         <td width="10%"> </td>
        </tr>
        <tr>
            <td width="10%">  </td>
            <td class="font-16" width="80%"  align="center"> 
               <b>สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม กระทรวงอุตสาหกรรม</b>
            </td>
             <td width="10%"> </td>
        </tr>
    </table>
    <table width="100%"   > 
        <tr>
            <td width="20%">  </td>
             <td class="font-16" width="20%"  align="right"> 
               <b>กอง/สำนัก/ศูนย์</b>
            </td>
            <td class="font-16 free-dot" width="40%"   align="center"  > 
                   กองกฎหมาย
             </td>
             <td width="20%"> </td>
        </tr>
    </table>
 

 
    <table width="100%"   > 
        <tr>
            <td width="5%" class="font-10"  valign="top"> 
                
            </td>
            <td width="14%" class="font-16" > 
                <b>ชื่อแผนงาน</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->plan_name) ? $withdraws->plan_name : '' !!} 
            </td>
            <td width="14%" class="font-16" > 
                <b>ศูนย์ต้นทุน</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                   {!!  !empty($withdraws->cost_center) ? $withdraws->cost_center : '' !!} 
            </td>
        </tr>
    </table>
    <table width="100%"   > 
        <tr>
            <td width="5%" class="font-10"  > </td>
            <td width="14%" class="font-16" > 
                <b>หมวดหมู่รายจ่าย</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->category) ? $withdraws->category : '' !!} 
            </td>
            <td width="14%" class="font-16"> 
                <b>รหัสปีงบประมาณ</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->year_code) ? $withdraws->year_code : '' !!} 
            </td>
        </tr>
    </table>
    <table width="100%"   > 
        <tr>
            <td width="5%" class="font-10"  > </td>
            <td width="14%" class="font-16"> 
                <b>ชื่อกิจกรรมหลัก</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->activity_main_name) ? $withdraws->activity_main_name : '' !!} 
            </td>
            <td width="14%" class="font-16" > 
                <b>รหัสกิจกรรมหลัก</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->activity_main_code) ? $withdraws->activity_main_code : '' !!} 
            </td>
        </tr>
    </table>
    <table width="100%"   > 
        <tr>
            <td width="5%" class="font-10"  > </td>
            <td width="14%" class="font-16"  > 
                <b>ชื่อกิจกรรมย่อย</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->activity_small_name) ? $withdraws->activity_small_name : '' !!} 
            </td>
            <td width="14%" class="font-16"  > 
                <b>รหัสกิจกรรมย่อย</b>
            </td>
             <td class="font-16 free-dot" width="33%"> 
                {!!  !empty($withdraws->activity_small_code) ? $withdraws->activity_small_code : '' !!} 
            </td>
        </tr>
    </table>
    <br>
    <table width="100%"   > 
        <tr>
            <td width="30%" class="font-16"  align="right"> 
            </td>
             <td class="font-16" width="5%"   align="right"> 
                ชื่อ
            </td>
            <td class="font-16 free-dot" width="35%"   align="center"> 
                
            </td>
            <td class="font-16" width="25%" > 
                ผู้ตัดยอดงบประมาณกอง/สำนัก/ศูนย์
            </td>
        </tr>
    </table>
    <table width="100%"   > 
        <tr>
            <td width="26%" class="font-16"  align="right"> 
            </td>
             <td class="font-16" width="5%"   align="right"> 
                 (
            </td>
            <td class="font-16 free-dot" width="30%"   align="center"> 
                
            </td>
            <td class="font-16" width="29%" > 
                 )
            </td>
        </tr>
    </table>
    <table width="100%"   > 
        <tr>
            <td width="15%" class="font-16"  align="right"> 
            </td>
             <td class="font-16" width="5%"   align="right"> 
                 วันที่
            </td>
            <td class="font-16 free-dot" width="25%"   align="center"> 
                
            </td>
            <td class="font-16" width="30%" > 
           
            </td>
        </tr>
    </table>
 

<table width="100%"   > 
    <tr>
        <td width="5%" class="font-10"   valign="top">  </td>
        <td width="95%"  > 
        
    <table width="100%" class="detail " style="border-collapse: collapse" > 
        <tr>
           <td class="font-16 box-border-left box-border-right box-border-bottom box-border-top"    width="15%"  align="center"> 
               <b>วดป.</b>
           </td>
           <td class="font-16 box-border-right box-border-bottom box-border-top"    width="70%" align="center"> 
               <b>รายการ</b>
            </td>
           <td class="font-16 box-border-bottom box-border-top box-border-right" width="15%"  align="center">
               <b>จำนวนเงิน</b>
           </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left box-border-right"   align="center"> 
                
            </td>
            <td class="font-16 box-border-right"    > 
                &nbsp;เงินรางวัลจากค่าปรับตาม พ.ร.บ. มาตรฐานผลิตภัณฑ์อุตสหกรรม
            </td>
            <td class="font-16 box-border-right"  align="right">
                {!!  !empty($withdraws->law_reward_withdraws_detail_many) ? number_format($withdraws->law_reward_withdraws_detail_many->sum('amount'),2) : '0.00' !!}
            </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left box-border-right " align="center"> 
                
            </td>
            <td class="font-16 box-border-right"   > 
                &nbsp;พ.ศ. {!! (date("Y")+543) !!} จำนวน {!!  !empty($withdraws->law_reward_withdraws_detail_many) ? count($withdraws->law_reward_withdraws_detail_many) : ''; !!} คดี
            </td>
            <td class="font-16 box-border-right  "   align="right">

            </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left box-border-right"> 
                
            </td>
            <td class="font-16 box-border-right"> 
                &nbsp;
            </td>
            <td class="font-16 box-border-right">

            </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left box-border-right"> 
                
            </td>
            <td class="font-16 box-border-right"> 
                &nbsp;
            </td>
            <td class="font-16 box-border-right">

            </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left box-border-right"> 
                
            </td>
            <td class="font-16 box-border-right"> 
                &nbsp;
            </td>
            <td class="font-16 box-border-right">

            </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left  box-border-right  box-border-bottom" align="center"> 
                
            </td>
            <td class="font-16 box-border-right  box-border-bottom"   > 
                &nbsp;
            </td>
            <td class="font-16 box-border-right  box-border-bottom"   align="right">

            </td>
        </tr>
        <tr>
            <td class="font-16 box-border-left box-border-right box-border-bottom" align="center" style="padding:5px;"> 
                 ตัวอักษร
            </td>
            <td class="font-16 box-border-right box-border-bottom"   align="center"> 
                    <table width="100%" style="padding-top: -13px;" >
                        <tr>
                            <td  width="5pt" >

                            </td>
                            <td class="font-16 free-dot"  width="250pt" >
                                {!!  !empty($withdraws->law_reward_withdraws_detail_many) ? HP_Law::TextBathFormat($withdraws->law_reward_withdraws_detail_many->sum('amount')) : '0.00' !!}
                            </td>
                            <td width="5pt" >

                            </td>
                        </tr>
                    </table>
            </td>
            <td class="font-16 box-border-right box-border-bottom"  align="right" style="padding:5px;">
                {!!  !empty($withdraws->law_reward_withdraws_detail_many) ? number_format($withdraws->law_reward_withdraws_detail_many->sum('amount'),2) : '0.00' !!}
            </td>
        </tr>
    </table>
        </td>
    </tr>
</table>

<table width="100%"   > 
    <tr>
        <td width="5%" class="font-10"   valign="top">   </td>
        <td width="95%"  > 
<table    > 
    <tr>
        <td width="410px" class="font-16"> 
            ข้าพเจ้าขอเบิกเงินตามรายการดังปรากฏข้างต้น และได้แนบใบสำคัญรวม 
        </td>
         <td width="40px" class="font-16 free-dot"> 
        </td>
        <td width="200px" class="font-16"> 
            ฉบับ มาเพื่อตรวจจ่ายด้วยแล้ว
        </td>  
    </tr>
</table>
        </td>
    </tr>
</table>

<br>
<table width="100%"  > 
    <tr>
        <td width="45%" class="font-16"  align="right"> 
        </td>
         <td class="font-16" width="5%"   align="right" > 
            ลงชื่อ
        </td>
        <td class="font-16 free-dot" width="40%"   align="center"> 
            {!!  !empty($withdraws->forerunner_created->FullName) ? $withdraws->forerunner_created->FullName : '' !!}
        </td>
        <td class="font-16" width="5%" > 
            ผู้เบิก
        </td>
    </tr>
</table>
 

<table width="100%"   > 
    <tr>
        <td width="5%" class="font-10"   valign="top">  </td>
        <td width="95%"  > 
        
    <table width="100%"  style="border-collapse: collapse;border: 1px solid blac" > 
        <tr>
            <td class=" box-border"  valign="top" width="250pt" > 
             <table > 
                <tr>
                    <td class="font-16"  style="padding-top: 10px;" width="250pt" align="center"> 
                        <b>หักล้างเงินยืม</b>
                    </td>
                </tr>
            </table>
            <table style="padding-top: -15px;"> 
                <tr>
                    <td class="font-14"  width="230pt"  > 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                </tr>
            </table>
            <table style="padding-top: -5px;"> 
                <tr>
                    <td class="font-14"   width="10pt"> 
                        ของ
                    </td>
                    <td class="font-14 free-dot" width="230pt"  > 
                     
                    </td>
                </tr>
            </table>
            <table style="padding-top: -5px;"> 
                <tr>
                    <td class="font-14"  width="65pt" > 
                        ตามใบยื่น (บย.)
                    </td>
                    <td class="font-14 free-dot"  width="82pt"  > 
                        
                    </td>
                    <td class="font-14" width="5pt" align="center"> 
                       R64-
                    </td>
                    <td class="font-14 free-dot"  width="82pt" > 
                    </td>
                </tr>
            </table>
            <table style="padding-top: -5px;"> 
                <tr>
                    <td class="font-14"   width="10pt"> 
                        วันที่
                    </td>
                    <td class="font-14 free-dot"  width="230pt"   > 
                     
                    </td>
                </tr>
            </table>
           </td>
           <td class=" box-border"  valign="top" width="250pt" > 
                <table > 
                    <tr>
                        <td class="font-16"  style="padding-top: 10px;" width="250pt"  > 
                            <b>เรียน ผก.กกค.</b>
                        </td>
                    </tr>
                </table> 
                <table style="padding-top: -5px;"> 
                    <tr>
                        <td class="font-14"  width="230pt"  > 
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;กลุ่มการคลังตรวจสอบแล้ว
                        </td>
                    </tr>
                </table>
                <table style="padding-top: -5px;"> 
                    <tr>
                        <td class="font-14"  width="50pt"  align="right" >
                            ลงชื่อ
                        </td>
                        <td class="font-14 free-dot"  width="150pt"  > 
                            
                        </td>
                        <td class="font-14" width="50pt" > 
                            ผู้ตรวจสอบ
                        </td>
                    </tr>
                </table>
                <table style="padding-top: -5px;"> 
                    <tr>
                        <td class="font-14"  width="50pt"  align="right" >
                            (
                        </td>
                        <td class="font-14 free-dot"  width="150pt"  > 
                            
                        </td>
                        <td class="font-14" width="50pt" > 
                            )
                        </td>
                    </tr>
                </table>
                <table style="padding-top: -5px;"> 
                    <tr>
                        <td class="font-14"   width="40pt"  align="right"> 
                            ตำแหน่ง
                        </td>
                        <td class="font-14 free-dot"  width="180pt"   > 
                         
                        </td>
                        <td class="font-14"  width="20pt"> </td>
                    </tr>
                </table>
                <table style="padding-top: -5px;"> 
                    <tr>
                        <td class="font-14"   width="40pt"  align="right"> 
                            วันที่
                        </td>
                        <td class="font-14 free-dot"  width="180pt"   > 
                         
                        </td>
                        <td class="font-14"  width="20pt"> </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td class=" box-border"  valign="top" width="250pt" > 
              <table > 
                 <tr>
                     <td class="font-16"  style="padding-top: 10px;" width="250pt"  > 
                         <b>เรียน ลมอ.</b>
                     </td>
                 </tr>
             </table>
             <table style="padding-top: -5px;"> 
                 <tr>
                     <td class="font-14"  width="220pt"  > 
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ได้ตรวจสอบหลักฐานการเบิกจ่ายเงินตามใบสำคัญ รวม
                     </td>
                     <td class="font-14 free-dot"  width="15pt"  > 
                          
                    </td>
                    <td class="font-14"  width="10pt"  > 
                        ฉบับ
                   </td>
                 </tr>
             </table>
             <table style="padding-top: -5px;"> 
                 <tr>
                     <td class="font-14"   width="10pt"> 
                         จำนวน
                     </td>
                     <td class="font-14 free-dot" width="40pt"  > 
                      
                     </td>
                     <td class="font-14"   width="5pt"> 
                         บาท
                    </td>
                    <td class="font-14 free-dot" width="40pt"  > 
                     
                    </td>
                    <td class="font-14"   width="120pt"> 
                        สตางค์ ถูกต้องแล้ว เห็นควร
                   </td>
                 </tr>
             </table>
             <table style="padding-top: -5px;"> 
                 <tr>
                     <td class="font-14"  width="65pt" > 
                        อนุมัติให้เบิกจ่ายได้
                     </td>
                 </tr>
             </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"  width="50pt"  align="right" >
                             ลงชื่อ
                         </td>
                         <td class="font-14 free-dot"  width="150pt"  > 
                             
                         </td>
                         <td class="font-14" width="50pt" > 
                             (ผก.กกค.)
                         </td>
                     </tr>
                 </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"  width="50pt"  align="right" >
                             (
                         </td>
                         <td class="font-14 free-dot"  width="150pt"  > 
                             
                         </td>
                         <td class="font-14" width="50pt" > 
                             )
                         </td>
                     </tr>
                 </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"   width="40pt"  align="right"> 
                             ตำแหน่ง
                         </td>
                         <td class="font-14 free-dot"  width="180pt"   > 
                          
                         </td>
                         <td class="font-14"  width="20pt"> </td>
                     </tr>
                 </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"   width="40pt"  align="right"> 
                             วันที่
                         </td>
                         <td class="font-14 free-dot"  width="180pt"   > 
                          
                         </td>
                         <td class="font-14"  width="20pt"> </td>
                     </tr>
                 </table>
            </td>

            <td class=" box-border"  valign="top" width="250pt" > 
                 <table > 
                     <tr>
                         <td class="font-16"  style="padding-top: 10px;" width="250pt"  > 
                             <b>อนุมัติให้จ่ายได้</b>
                         </td>
                     </tr>
                 </table> 
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"  width="50pt"  align="right" >
                             ลงชื่อ
                         </td>
                         <td class="font-14 free-dot"  width="150pt"  > 
                             
                         </td>
                         <td class="font-14" width="50pt" > 
                             (ลมอ.)
                         </td>
                     </tr>
                 </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"  width="50pt"  align="right" >
                             (
                         </td>
                         <td class="font-14 free-dot"  width="150pt"  > 
                             
                         </td>
                         <td class="font-14" width="50pt" > 
                             )
                         </td>
                     </tr>
                 </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"   width="40pt"  align="right"> 
                             ตำแหน่ง
                         </td>
                         <td class="font-14 free-dot"  width="180pt"   > 
                          
                         </td>
                         <td class="font-14"  width="20pt"> </td>
                     </tr>
                 </table>
                 <table style="padding-top: -5px;"> 
                     <tr>
                         <td class="font-14"   width="40pt"  align="right"> 
                             วันที่
                         </td>
                         <td class="font-14 free-dot"  width="180pt"   > 
                          
                         </td>
                         <td class="font-14"  width="20pt"> </td>
                     </tr>
                 </table>
             </td>
         </tr>
    </table>
 
        </td>
    </tr>
</table>
 

<table width="100%"   > 
    <tr>
        <td width="5%" class="font-10"   valign="top">   </td>
         <td width="95%"  > 
            <table> 
                <tr>
                    <td class="font-16"   width="40pt"  align="right"> 
                        เอกสารจากระบบ
                    </td>
                    <td class="font-16 free-dot"  width="150pt"   > 
                     
                    </td>
                    <td class="font-16"  width="100pt"  align="right">  
                        เลขอ้างอิง 
                    </td>
                    <td class="font-16 free-dot"  width="150pt"   > 
                     
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div>




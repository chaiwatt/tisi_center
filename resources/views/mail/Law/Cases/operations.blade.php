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
        <b>เรื่อง : </b> {!!  !empty($case->offend_name) ? 'แจ้งปิดงานคดี '.$case->offend_name : '' !!}
    </p>
    <p>
        <b>เรียน : {!!  !empty($case->law_cases_assign_to->user_created->FullName) ? $case->law_cases_assign_to->user_created->FullName : '' !!}</b>
    </p>

    <p class="indent50"> 
      ตามที่ได้มอบหมายงานคดีของ {!!  !empty($case->offend_name) ? $case->offend_name : '' !!} ให้รับผิดชอบนั้น
    </p> 
    <p>
      บัดนี้ ได้ ดำเนินการเสร็จสิ้น จึงของแจ้งปิดงานคดีดังกล่าว
    </p>
     @if (!empty($case->close_remark))
     <p>
        {!! 'หมายเหตุ : '.$case->close_remark !!}
     </p>
     @endif
     <p>{!! str_repeat('&nbsp;', '60')!!}
        <a  href="{{ url('law/cases/assigns') }}" style="display:inline-block;background:#0066cc;color:#ffffff;font-family:Arial;font-size:14px;font-style:normal;font-weight:normal;line-height:100%;margin:0;text-decoration:none;text-transform:none;padding:8px 15px;border-radius:5px" target="_blank">คลิกดูรายละเอียด</a>
    </p> 
    <p>
        จึงเรียนมาเพื่อโปรดทราบ <br> TISI-LAW <br>
        <hr style="border-top: 1px solid black;">
        e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
    </p> 
    {!! '<p><b>สอบถามข้อมูลเพิ่มเติมได้ที่ : กองกฎหมาย</b> <br>-Tel. : 0-2430-6830 ต่อ 2000 <br/>-E-mail : E-mail. : law2022@tisi.go.th <br/>-Line. : @law2022</p>' !!}
    
</div> 


       
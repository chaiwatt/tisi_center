
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
</head>
<body>
   <div id="style">
        <p>
            <b>เรียน   {{$name}}</b>
        </p>
        <p>
            <b>เรื่อง  แจ้งเตือนผลการประเมิน </b>
        </p>
        <p class="indent50"> 
             ตามที่ท่านได้ยื่นคำขอหน่วยรับรอง  
            หมายคำขอที่  {{ $app_no ?? '-' }}       {{ !is_null($auditor) ? 'คณะผู้ตรวจประเมิน'.$auditor : null}}       
             เมื่อวันที่ {{ HP::formatDateThaiFull(date('Y-m-d')) }} 
             จึงขอแจ้งครบ {{$number??'-'}} วันแล้ว หลังจากเจ้าหน้าที่ได้ทำการตรวจสอบผลการประเมินและปิด Car   
             เพื่อขอความเห็นชอบจาก  {{$name}}
        </p> 
        <p>
           โปรดคลิก
            <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
            เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ <br>
            --------------------------
        </p> 
        <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
        {{-- <p>
            {!!auth()->user()->UserContact!!}
        </p> --}}
    </div> 
</body>
</html>
 

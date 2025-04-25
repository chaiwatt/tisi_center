
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
        text-align: center;
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
      <b>เรียน    {{  !empty($certi_lab->BelongsInformation->name) ?   $certi_lab->BelongsInformation->name   :  ''  }} </b>
  </p>
   <p>
      <b>เรื่อง ขอให้แก้ไขขอบข่าย  </b>
   </p> 
   <p class="indent50"> 
     

ตามที่ท่านได้ยื่นคำขอรับรองห้องปฏิบัติการ
สำหรับคำขอเลขที่    {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
เมื่อวันที่  {{  !empty($certi_lab->created_at) ?  HP::formatDateThaiFull($certi_lab->created_at) :  ''  }}  นั้น
ทางเจ้าหน้าที่จึงขอแจ้งให้ท่านแก้ไขขอบข่าย
<p class="indent50"> 
    โดยมีรายละเอียดดังนี้
  </p>

<p style="text-indent: 80px;">
    {!!$request_message!!}
</p>

โดยเข้าระบบ <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank">E-Accreditation</a> หากท่านมีข้อขัดข้องในองค์ประกอบของขอบข่ายหรือรายละเอียด ดังกล่าวประการใด โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลโดยด่วนด้วย 
 
        <p>
            จึงเรียนมาเพื่อทราบและโปรดดำเนินการ
           {{-- <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
           เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ --}}
            <br>
           --------------------------
        </p>
         <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
        <p>
            {!!auth()->user()->UserContact!!}
       </p>
       
    </div> 
</body>
</html>


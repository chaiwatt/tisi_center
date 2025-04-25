
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
        <b>เรียน   {{  !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name   :  ''  }} </b>
    </p>
    <p>
        <b>เรื่อง   รับคำขอรับบริการ </b>
    </p>

    <p class="indent50"> 
        ตามที่     {{  !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name  :  ''   }}
        ได้ยื่นคำขอรับบริการยืนยันความสามารถห้องปฏิบัติการ
        ผ่านระบบการรับรองระบบงาน   คำขอเลขที่  {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
        เมื่อวันที่    {{  !empty($certi_lab->start_date) ?  HP::formatDateThaiFull($certi_lab->start_date) :  ''  }}  
        นั้น สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้พิจารณาและรับคำขอของ   {{  !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name   :  ''   }}
        คำขอเลขที่   {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
        ลงรับวันที่  {{  !empty($certi_lab->get_date) ?  HP::formatDateThaiFull($certi_lab->get_date) :  ''  }} 
        เรียบร้อยแล้ว
            
    </p>   
    <p>
        จึงเรียนมาเพื่อทราบ
     {{-- โปรดคลิก
        <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
        เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ <br>
        -------------------------- --}}
    </p>
    <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
    <p>
        {!!auth()->user()->UserContact!!}
    </p>
 </div> 
 
</body>
</html>
 

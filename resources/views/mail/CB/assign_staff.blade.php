


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
               <b>เรียน {{  $reg_fname ?? null}}</b>
            </p>
            <p>
                <b>เรื่อง ขอให้ตรวจสอบคำขอรับบริการยืนยันความสามารถหน่วยรับรอง</b>
            </p>
            <p class="indent50"> 
                เพื่อตรวจสอบความถูกต้องครบถ้วนของคำขอรับบริการยืนยันความสามารถหน่วยรับรอง
                และเอกสารประกอบคำขอ  ของ {{  !empty($apps->name) ?  $apps->name  :  ''  }} 
                ผู้ยื่นคำขอเลขที่ {{  $apps->app_no ?? null }}
                เมื่อวันที่  {{  !empty($apps->start_date) ?  HP::formatDateThaiFull($apps->start_date) :  ''  }} 
                และหากผลการพิจารณาเป็นประการใด ขอให้แจ้งผู้ยื่นคำขอทราบผ่านระบบการรับรองระบบงานต่อไปด้วย
            </p>
         <p>
            จึงเรียนมาเพื่อดำเนินการ <br>
            --------------------------
        </p>
        <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
        <p>
            {!!auth()->user()->UserContact !!}
        </p>
    </div> 
</body>
</html>
  
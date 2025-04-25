<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{
            /* width: 60%; */
            padding: 5px;
            border: 5px solid gray;
            margin: 0;
            
       }    
       .customers td, .customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }

        .customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: center;
        background-color: #66ccff;
        color: #000000;
        }   
        
        .center {
            text-align: center;
         }
         .right {
            text-align: right;
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
        <b>เรียน ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ</b>
    </p>
    <p> 
        <b>เรื่อง  OTP สำหรับลงนามใบรับรอง</b>    
    </p>

    {{-- <p class="indent50"> 
        ตามที่    {{  !empty($certi_lab->name) ?  $certi_lab->name   :  ''  }}
        คำขอเลขที่  {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} ได้รับการตรวจประเมินไปแล้วนั้น 
        บัดนี้ผลการตรวจประเมินได้เสร็จสิ้นแล้ว ขอแจ้งเพื่อให้ท่านกรุณาลงนามออกใบรับรอง โดยใช้ OTP= <span style="font-weight: bold">{{$otp}}</span> [รหัสอ้างอิง {{$ref_otp}}] เพื่อใช้ยืนยันลงนามใบรับรองดังกล่าว 
    </p> --}}

    
    <p class="indent50"> 
        ขอแจ้งเพื่อให้ท่านกรุณาลงนามออกใบรับรอง โดยใช้ OTP= <span style="font-weight: bold">{{$otp}}</span> [รหัสอ้างอิง {{$ref_otp}}] เพื่อใช้ยืนยันลงนามใบรับรองดังกล่าว 
    </p>
      

          --------------------------
      </p>
          <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
     <p>
          {!!auth()->user()->UserContact!!}
     </p>
    </div> 
</body> 
</html>


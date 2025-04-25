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
        <b>เรื่อง  การบันทึกข้อบกพร่อง / ข้อสังเกต</b>    
    </p>

    <p class="indent50"> 
        ตามที่    {{  !empty($certi_lab->name) ?  $certi_lab->name   :  ''  }}
        คำขอเลขที่  {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} ได้รับการตรวจประเมินไปแล้วนั้น
        และท่านได้เป็นผู้ออกข้อบกพร่อง / ข้อสังเกต บัดนี้สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ขอแจ้งเพื่อให้ท่านกรุณาบันทึก ข้อบกพร่อง / ข้อสังเกต ดังกล่าว ผ่านลิงก์ชั่วคราว <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank">คลิกที่นี่</a> หากท่านมีข้อขัดข้องในองค์ประกอบดังกล่าวประการใด โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลโดยด่วนด้วย 
        
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


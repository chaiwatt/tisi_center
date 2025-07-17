
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
                    <b>เรียน   {{  !empty($certi_ib->name) ?   $certi_ib->name   :  ''  }} </b>
                </p>
                <p>
                    <b>เรื่อง  ขอให้แก้ไขขอบข่าย  </b>
                </p>
                <p class="indent50"> 
                    ตามที่  {{  !empty($certi_ib->name) ? $certi_ib->name  :  ''  }} 
                    ได้ยื่นคำขอรับบริการยืนยันความสามารถหน่วยตรวจ
                    ผ่านระบบการรับรองระบบงาน  
                    คำขอเลขที่  {{  !empty($certi_ib->name) ?   $certi_ib->app_no  :  ''  }} 
                    เมื่อวันที่  {{  !empty($certi_ib->start_date) ?  HP::formatDateThaiFull($certi_ib->start_date) :  ''  }} 
                    นั้น
                </p>  
                <p class="indent50"> 
                    สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมพิจารณาแล้ว  
                    เห็นควรให้   {{  !empty($certi_ib->name) ?   $certi_ib->name  :  ''  }} 
                </p>  

    
            <p class="indent50">   
                ดำเนินการแก้ไขขอบข่าย ภายใน 5 วัน นับจากวันที่สำนักงานฯแจ้ง เมื่อครบกำหนดแล้วหากท่านยันไม่ดำเนินการ สำนักงานฯ จะถือว่าท่านไม่สงค์จะยื่นคำขอและแจ้งยกเลิกคำขอต่อไป 
            </p>
            <p class="indent100">   
                 เหตุผล :  {{$desc ?? null}}
            </p>
            <p>
                จึงเรียนมาเพื่อพิจารณาดำเนินการต่อไป
                <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
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
 

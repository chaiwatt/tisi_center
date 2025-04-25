
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
       .customers td, .customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }

        .customers th {
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
            <b>เรียน ผู้เชี่ยวชาญการตรวจประเมิน </b>
        </p>
        <p> 
            <b>เรื่อง  {{ $title }}</b>    
        </p>
 

        <p class="indent50"> 
            ตามที่    {{  !empty($data->name) ?  $data->name   :  ''  }}
            เห็นชอบการประมาณการค่าใช้จ่าย คำขอเลขที่  {{  !empty($auditors->reference_refno) ?   $auditors->reference_refno :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ขอแจ้งเพื่อให้ท่านจัดทำบันทึกข้อความการแต่งตั้งคณะกรรมการการตรวจประเมินต่อไป โดยเข้าระบบ <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank">E-Accreditation</a> หากท่านมีข้อขัดข้องในองค์ประกอบของคณะผู้ตรวจประเมินและกำหนดการตรวจประเมิน ดังกล่าวประการใด โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลโดยด่วนด้วย 
            <br>
 
        --------------------------
        </p>
  

        <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
   <p>
        {!! auth()->user()->UserContact !!}
   </p>
    </div> 
</body>
</html>


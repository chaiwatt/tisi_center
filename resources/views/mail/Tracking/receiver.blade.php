


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
            <b>เรียน </b>{{  $name ?? null}}
        </p>
        <p>
            <b>เรื่อง </b>{!! $title !!}
        </p>
            <p class="indent50"> 
                เนื่องจาก {{  $certificate_type ?? null}} หมายเลขการรับรองที่  {{  $certificate ?? null}} ได้รับใบรับรองระบบงานเมื่อ {{  $date_start ?? null}}
                และตรวจติดตามครั้งล่าสุดเมื่อวันที  {{  $date_end ?? null}}
            </p>
            <p class="indent50"> 
                บัดนี้ ทาง สมอ. ขอแจ้งผู้ได้รับใบรับรอง เพื่อดำเนินการตรวจติดตามต่อไป
            </p>
         <p>
            <hr style="border-top: 1px solid black;">
        </p>
        <p>
            {!!auth()->user()->UserContact !!}
        </p>
    </div> 
</body>
</html>
  
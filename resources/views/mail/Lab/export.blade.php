
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
        <b>เรียน  {{ !empty($certi_lab->name) ?   $certi_lab->name   :  '' }} </b>
    </p>
    <p>
        <b>เรื่อง   ใบรับรองห้องปฏิบัติการ </b>
    </p>

    <p class="indent50"> 
        ตามที่  {{ !empty($certi_lab->name) ?   $certi_lab->name   :  ''  }}  ได้ยื่นคำขอรับใบรับรองห้องปฏิบัติการ
    เมื่อวันที่  {{  !empty($certi_lab->start_date) ?  HP::formatDateThaiFull($certi_lab->start_date) :  ''  }} 
    สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ได้ดำเนินการออกใบรับรองระบบงานห้องปฏิบัติการ แล้ว
    โดยท่านสามารถพิมพ์ใบรับรองระบบงานอิเล็กทรอนิกส์ได้ด้วยตนเอง ตามเอกสารที่แนบมา หรือจากระบบใบรับรองระบบงาน e-Accreditation
    </p>  


    <p>
        จึงเรียนมาเพื่อทราบ
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
 

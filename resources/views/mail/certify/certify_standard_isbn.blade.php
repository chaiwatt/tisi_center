<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{
            width: 60%;
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
        <p><b>เรียน :</b> {{ $name ?? '-' }} </p>  
        <p><b>เรื่อง :</b> แจ้งขอเลข ISBN  </p>  
            <p class="indent50"> 
                เนื่องจากได้มีการจัดทำมาตรฐาน   {{ !empty($standard_full) ?   $standard_full  :  '' }}  จึงขอให้ผู้เกี่ยวข้องดำเนินการระบุเลข ISBN
            </p>
            <p class="indent50"> 
                จึงเรียนมาเพื่อทราบและโปรดดำเนินการต่อไป
            </p>
        <p><a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a></p>  
        <p>
            {!!auth()->user()->UserContact!!}
        </p>
    </div> 
</body>
</html>
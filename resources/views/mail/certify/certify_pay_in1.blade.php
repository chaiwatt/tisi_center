

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
   </style>
</head>
<body>
   <div id="style">
        <p style="font-size:30px;"> <img src="{!! asset('plugins/images/anchor.jpg') !!}"  height="50px" width="50px"/> <b> ใบ Pay-in ครั้งที่ 1   </b></p>
        <p><b>เรียน :</b> {{ $name ?? '-' }} </p>  
         <p>คำขอ {{ $app_no ?? '-' }}</p>  
        <p><b>จำนวนเงิน :</b> {{ $amount ?? '-' }}  </p>  
        <p><b>วันที่ :</b> {{ $report_date ?? '-' }} </p>  
        @if($attachs != '-')
          <p><b> ค่าธรรมเนียมใบตรวจประเมิน :</b>    
                <a href="{{ url('certify/check/files/'. basename($attachs)) }}"> 
                {!!  @basename($attachs) ?? '' !!}
             </a> 
          </p>  
        @endif
        <p><b>ตรวจสอบการชำค่าตรวจประเมิน :</b> ยังไม่ได้ชำระเงิน </p>  
        @if($detail != '-')
         <p><b>หมายเหตุ : </b> {{ $detail ?? '-' }} </p>  
        @endif
    </div> 
</body>
</html>


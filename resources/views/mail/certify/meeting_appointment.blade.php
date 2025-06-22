

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
        <p><b>เรียน</b>  คณะกรรมการกำหนดมาตรฐาน </p>  
         <p><b>เรื่อง</b> {{ $mail_subject  }}</p>  
          <p style="text-indent: 50px">
          {!! $mail_body !!}
          </p>
          <p>รายละเอียดตามหนังสือเชิญประชุม</p>
          <a href="{{$order_book_url}}">ดาวน์โหลดหนังสือเชิญ</a>

        <p> จึงเรียนมาเพื่อทราบและโปรดดำเนินการต่อไป </p> 

        <p>
            <b>ข้อมูลติดต่อ</b>
        </p>
        <p>
             {!!auth()->user()->UserContact !!}
       </p>
       
    </div> 
</body>
</html>


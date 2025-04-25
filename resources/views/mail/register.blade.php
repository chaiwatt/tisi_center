

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{
            /* width: 50%; */
            padding: 5px;
            border: 5px solid gray;
            margin: 0;

       }
       .address{
            /* width: 50%; */
            padding: 5px;
            border: 1px solid gray;
            margin: 0;

       }
        #table_th th{
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
           <b>เรียนคุณ	{{@$name}}</b>
        </p>
        <p>
            <b>เรื่อง แจ้งการลงทะเบียนสำเร็จ</b>
        </p>
        <p class="indent50">
          ตามที่ {{@$name}} ได้ลงทะเบียนในระบบ เมื่อ
          {{ HP::formatDateThai(date('Y-m-d')) }}
        </p>
        <p>ขณะนี้ระบบได้เพิ่มท่านไว้ในระบบแล้ว การลงทะเบียนของท่านสำเร็จแล้ว</p>
        <p>username :  {{@$username}} </p>
        <p>password :  {{@$password}} </p>
        <p>
            จึงเรียนมาเพื่อโปรดรับทราบ
             <a href="{{ $link  }}" class="btn btn-link" target="_blank">คลิกเพื่อยืนยันตัวตน</a>
        </p>
        <p>
            ------------------------------------------
            <br>
        </p>
    </div>
</body>
</html>


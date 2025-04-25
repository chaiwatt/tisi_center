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
            .indent-body {
                text-indent: 30px;
            }
        </style>
    </head>
    <body>
        <div id="style">
            <p>
                <b>เรียน {{ $applicant_name }}</b>
            </p>
            <p>
                เรื่อง {{ $subject }}
            </p>
            <p class="indent-body">
                ตามที่ท่านได้ยื่นคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) ห้องปฏิบัติการ
                        {{ $lab_name }}
                        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สมอ.) ได้อนุมัติให้เป็นห้องปฏิบัติการแล้ว
                        โดยมีผลตั้งแต่วันที่ {{ $start_date }} เป็นต้นไป
                        <br>ในการบันทึกผลทดสอบในระบบยื่นคำขอรับใบอนุญาตผ่านระบบอินเทอร์เน็ต (e-License) ท่านสามารถเข้าสู่โดยใช้ข้อมูลดังต่อไปนี้
                        <div>URL : {!! $url !!}</div>
                        <div>Username : {{ $username }}</div>
                        <div>Password : {{ $password }}</div>

                        <br><br>จึงเรียนมาเพื่อทราบ
            </p>

        </div>
    </body>
</html>

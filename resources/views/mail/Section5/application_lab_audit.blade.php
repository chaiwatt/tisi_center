<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            #style{
                padding: 5px;
                /* border: 5px solid gray; */
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
            .no_margin{
                margin: 0px;
            }
        </style>
    </head>
    <body>
        <div id="style">
            <p>
                <b>เรียน {{ $name }}</b>
            </p>
            <p>
                เรื่อง {{ $subject }} (เลขที่คำขอ : {!! $application_no !!})
            </p>
            <p class="indent-body">
                ตามที่ท่านได้ยื่นคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) และทำการตรวจประเมิน เมื่อวันที่ {!! $audit_date !!} เจ้าหน้าที่ได้พิจารณาผลตรวจประเมิน และขอแจ้งผลการตรวจประเมิน โดยมีรายละเอียดดังนี้
            </p>
            <p class="indent-body">
                ผลการตรวจประเมิน : {!! $audit_result !!}
            </p>
            <p class="indent-body">
                รายละเอียดการตรวจประเมิน : {!! $audit_remark !!}
            </p>
            <p class="no_margin">จึงเรียนมาเพื่อโปรดดำเนินการ</p>
            <hr class="no_margin">
            <p class="no_margin">e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ</p>

        </div>
    </body>
</html>

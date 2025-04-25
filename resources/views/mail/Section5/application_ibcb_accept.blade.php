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
        </style>
    </head>
    <body>
        <div id="style">
            <p>
                <b>เรียน {{ $name }}</b>
            </p>
            <p>
                เรื่อง {{ $subject }}
            </p>
            <p class="indent-body">
                @php
                    $body = str_replace('{name}', $name, $body);
                    $body = str_replace('{application_no}', $application_no, $body);
                    $body = str_replace('{application_date}', $application_date, $body);
                    $body = str_replace('{description}', $description, $body);
                    $body = str_replace('{accept_date}', $accept_date, $body);
                @endphp
                {!! $body !!}
            </p>
            <hr class="no_margin">
            <p class="no_margin">e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ</p>
        </div>
    </body>
</html>

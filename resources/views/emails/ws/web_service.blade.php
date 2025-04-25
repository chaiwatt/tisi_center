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

            p {
                margin-top: 0px;
                margin-bottom: 5px;
            }
        </style>
    </head>
    <body>

        <table width="100%">
            <tr>
                <td width="2%" valign="top"><p><b>เรียน </b></p></td>
                <td valign="top"><p><b>{!! isset($learn)?$learn:null !!} </b></p></td>
            </tr>
            <tr>
                <td width="2%" valign="top"><p><b>เรื่อง </b></p></td>
                <td valign="top"><p><b>{!! isset($subject)?$subject:null !!} </b></p></td>
            </tr>
        </table>

        {!! isset($content)?$content:null !!}

        <p><b>{!! isset($study)?$study:'จึงเรียนมาเพื่อทราบ' !!}</b></p>
        <hr>
        <p  style="color:#808080;">
            e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
        </p>

    </body>
</html>

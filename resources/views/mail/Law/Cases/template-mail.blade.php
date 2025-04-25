<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>

            .font-18{
                font-size:18px !important;
            }

            .font-16{
                font-size:16px !important;
            }
        </style>
    </head>
    <body>

        <table width="100%">
            <tr>
                <td class="font-16" width="5%" valign="top"><b>เรียน </b></td>
                <td class="font-16" valign="top"><b>{!! isset($learn)?$learn:null !!} </b></td>
            </tr>
            <tr>
                <td class="font-16" width="5%" valign="top"><b>เรื่อง </b></td>
                <td class="font-16" valign="top"><b>{!! isset($subject)?$subject:null !!} </b></td>
            </tr>
            <tr>
                <td class="font-16" width="5%"></td>
                <td class="font-16" valign="top">
                    {!! isset($content)?$content:null !!}
                </td>
            </tr>
            <tr>
                <td class="font-16" width="5%"></td>
                <td class="font-16" valign="top">
                    <div>จึงเรียนมาเพื่อโปรดทราบ <div>
                    <div>TISI-LAW </div>
                    <hr style="border-top: 1px solid black;">
                    <div>e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ</div>
                </td>
            </tr>
            <tr>
                <td class="font-16" width="5%"></td>
                <td class="font-16" valign="top">
                    @php
                        $config = HP::getConfig(false);
                    @endphp

                    @if (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 1)  <!-- แสดงข้อมูลติดต่อกลาง -->
                        {!! $config->contact_mail_footer  !!}
                    @elseif (!empty($config->contact_mail_footer) && !empty($config->check_contact_mail_footer) && $config->check_contact_mail_footer == 2) <!-- แสดงข้อมูลติดต่อผู้บันทึก -->
                        @php
                            $name       = auth()->user()->FullName ?? '';
                            $reg_wphone = auth()->user()->reg_wphone ?? '';
                            $reg_email  = auth()->user()->reg_email ?? '';
                        @endphp
                        {!! '<p><b>สอบถามข้อมูลเพิ่มเติม</b><br>'.$name.'<br>โทร. '.$reg_wphone.'<br>อีเมล '.$reg_email.'</p>' !!}
                    @endif
                </td>
            </tr>
        </table>



    </body>
</html>


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
       .customers td, .customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }

        .customers th {
        background-color: #66ccff;
        color: #000000;
        }   
        .center {
            text-align: center;
         }
         .right {
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
            <b>เรียน    {{  !empty($certi_cb->name) ? $certi_cb->name   :  ''  }} </b>
        </p>
        <p> 
            <b>เรื่อง  การแต่งตั้งคณะผู้ตรวจประเมิน</b>    
        </p>
 
        <p class="indent50"> 
            ตามที่  {{  !empty($certi_cb->name) ? $certi_cb->name   :  ''  }}
            เห็นชอบการประมาณการค่าใช้จ่าย ของ   {{  !empty($certi_cb->name) ? $certi_cb->name   :  ''  }}
            คำขอเลขที่  {{  !empty($certi_cb->app_no) ?   $certi_cb->app_no  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมขอแจ้งกำหนดการตรวจประเมินเอกสารพร้อมรายชื่อคณะผู้ตรวจประเมิน 
            โดยมีรายละเอียดดังนี้
        </p>

        <p class="indent50"> 
            กำหนดการ  {{ HP::DateThai($cbDocReviewAuditor->from_date) }} ถึง {{ HP::DateThai($cbDocReviewAuditor->to_date) }} 
        </p>

        <table  class="customers">
            <thead>
                    <tr>
                        <th width="5%" >ลำดับ</th>
                        <th width="35%">ชื่อผู้ตรวจประเมิน</th>
                        <th width="25%">หน่วยงาน</th>
                        <th width="25%">สถานะผู้ตรวจประเมิน</th>
                    </tr>
            </thead>
            <tbody>

                @php $count = 1; @endphp
                @foreach($auditors as $auditor)
                    @foreach($auditor['temp_users'] as $index => $user)
                        <tr>
                            <td>{{ $count }}</td> 
                            <td>{{ $user }}</td>
                            <td>{{ $auditor['temp_departments'][$index] !== 'ไม่มีรายละเอียดหน่วยงานโปรดแก้ไข' ? $auditor['temp_departments'][$index] : '' }}</td>
                            <td>
                                @if (HP::cbDocAuditorStatus($auditor['status']) != null)
                                    {{ HP::cbDocAuditorStatus($auditor['status'])->title }}
                                @endif
                            </td>
                        </tr>
                        @php $count++; @endphp
                    @endforeach
                @endforeach

              
            </tbody>
        </table>

        
   <p class="indent50"> 
        จึงเรียนมาเพื่อทราบ และหากท่านมีข้อขัดข้องในองค์ประกอบของคณะผู้ตรวจประเมินเอกสารและกำหนดการตรวจประเมิน ดังกล่าวประการใด โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลโดยด่วนด้วย 
        และขอให้ท่านเข้าระบบ <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank">E-Accreditation</a> เพื่อยื่นยันคณะผู้ตรวจประเมินเอกสารและวันที่ตรวจประเมินเอกสาร ภายหลังจากรับการตรวจประเมินเรียบร้อยแล้ว  จักขอบคุณยิ่ง
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


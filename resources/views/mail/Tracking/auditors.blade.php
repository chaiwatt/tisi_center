
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
            <b>เรียน    {{  !empty($data->name) ? $data->name   :  ''  }} </b>
        </p>
        <p> 
            <b>เรื่อง  {{ $title }}</b>    
        </p>
 
        <p class="indent50"> 
            ตามที่  {{  !empty($data->name) ? $data->name   :  ''  }}
            เห็นชอบการประมาณการค่าใช้จ่าย ของ   {{  !empty($data->name) ? $data->name   :  ''  }}
            คำขอเลขที่  {{  !empty($auditors->reference_refno) ?   $auditors->reference_refno  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมขอแจ้งกำหนดการตรวจประเมินพร้อมรายชื่อคณะผู้ตรวจประเมิน 
            โดยมีรายละเอียดดังนี้
        </p>

        <p class="indent50"> 
            กำหนดการ  {{  !empty($auditors->CertiAuditorsDateTitle ) ?  $auditors->CertiAuditorsDateTitle   :  ''  }}    
        </p>
        @if(count($auditors->auditors_status_many) > 0)
        <table  class="customers">
            <thead>
                    <tr>
                        <th width="5%" >ลำดับ</th>
                        <th width="25%">สถานะผู้ตรวจประเมิน</th>
                        <th width="35%">ชื่อผู้ตรวจประเมิน</th>
                        <th width="25%">หน่วยงาน</th>
                    </tr>
            </thead>
            <tbody> 
                    @php
                        $i = 1;
                    @endphp
                    @foreach($auditors->auditors_status_many as  $item) 
                    @if(count($item->auditors_list_many) > 0)
                      @foreach($item->auditors_list_many as   $item1) 
                          <tr>
                              <td class="center">{{ ($i++ )}}</td>
                              <td>{{ $item1->StatusAuditorTitle ?? null }}</td>
                              <td>{{ $item1->temp_users}}</td>
                              <td>{{ $item1->temp_departments}}</td>
                          </tr>
                       @endforeach
                    @endif
                    @endforeach
              
            </tbody>
        </table>
        @endif

        @if(count($auditors->auditors_status_many) > 0)
        <table  class="customers">
            <thead>
                    <tr>
                        <th width="5%" >ลำดับ</th>
                        <th width="25%">รายละเอียด</th>
                        <th width="20%">จำนวนเงิน</th>
                        <th width="20%">จำนวนวัน</th>
                        <th width="20%">รวม (บาท)</th>
                    </tr>
            </thead>
            <tbody>
                    @php
                        $total = 0;   
                    @endphp
                    @foreach($auditors->auditors_status_many as $key => $item) 
                    @php
                        $amount_date = !empty($item->amount_date) ? $item->amount_date : '0';
                        $amount = !empty($item->amount) ? $item->amount : '0';
                        $sum =  $amount_date * $amount ;
                        $total +=  $sum ;
                    @endphp
                     <tr>
                        <td class="center">{{$key+1}}</td> 
                        <td>{{ $item->StatusAuditorTitle ?? null }}</td>
                        <td class="right">{{ number_format($item->amount,2) ?? null }}</td>
                        <td class="right">{{ $item->amount_date ?? null }}</td>
                        <td class="right">{{ number_format($sum,2)  ?? null }}</td>
                    </tr>
                    @endforeach
            </tbody>
            <footer>
                <tr>
                    <td colspan="4" class="right">รวม</td>
                    <td class="right">
                       {{ !empty($total) ?  number_format($total,2)  :  null }}
                    </td>
                </tr>
            </footer>
        </table>
        @endif
  
 
        
   <p class="indent50"> 
        จึงเรียนมาเพื่อทราบ และหากท่านมีข้อขัดข้องในองค์ประกอบของคณะผู้ตรวจประเมินและกำหนดการตรวจประเมิน ดังกล่าวประการใด โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลโดยด่วนด้วย 
        และขอให้ท่านเข้าระบบ <a href="{{ $url  }}"class="btn btn-link" target="_blank">E-Accreditation</a> เพื่อยื่นยันคณะผู้ตรวจประเมินและวันที่ตรวจประเมิน ภายหลังจากรับการตรวจประเมินเรียบร้อยแล้ว  จักขอบคุณยิ่ง
        <br>
 
        --------------------------
    </p>
        <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
   <p>
        {!! auth()->user()->UserContact !!}
   </p>
    </div> 
</body>
</html>


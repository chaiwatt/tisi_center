
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
        /* padding-top: 12px;
        padding-bottom: 12px; */
        /* text-align: left; */
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
            <b>เรียน    {{  !empty($data->name) ?  $data->name   :  ''  }} </b>
        </p>
        <p> 
            <b>เรื่อง   การประมาณการค่าใช้จ่าย</b>    
        </p>
 
       <p class="indent50"> 
          ตามที่    {{  !empty($data->name) ?   $data->name  :  ''  }} 
          ได้ยื่นคำขอรับบริการยืนยันความสามารถหน่วยรับรอง
          คำขอเลขที่  {{  !empty($pay_in->reference_refno) ?   $pay_in->reference_refno  :  ''  }} 
           ลงรับวันที่  {{  !empty($pay_in->save_date) ?  HP::formatDateThaiFull($pay_in->save_date) :  ''  }}  นั้น
       </p>
       <p class="indent50"> 
         สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมขอแจ้งสรุปสาขา/ขอบข่ายที่ขอรับการรับรองหน่วยรับรอง และประมาณการค่าใช้จ่ายในการตรวจประเมิน โดยมีรายละเอียดดังนี้
       </p>
 
            <table  id="customers" width="60%">
                <thead>
                        <tr>
                            <th width="5%" >#</th>
                            <th width="65%">รายละเอียด</th>
                            <th width="10%">จำนวนเงิน</th>
                            <th width="10%">จำนวนวัน</th>
                            <th width="10%">รวม (บาท)</th>
                        </tr>
                </thead>
                <tbody>
                    @php 
                           $sum_amount = 0;
                   @endphp
                    @if (!@empty($pay_in->auditors_to->auditors_status_many) && count($pay_in->auditors_to->auditors_status_many) > 0)
                              @foreach ($pay_in->auditors_to->auditors_status_many as $key => $item)
                                        @php 
                                        $sum =  str_replace(",","", $item->amount) * $item->amount_date;
                                        $sum_amount += $sum;
                                        @endphp
                                      <tr> 
                                        <td class="center">{{ $key + 1 }}</td>
                                        <td>{{ !is_null($pay_in->StatusAuditorTitle) ? $pay_in->StatusAuditorTitle : null }}</td>
                                        <td class="right">{{ number_format(str_replace(",","", $item->amount),2) }}</td>
                                        <td class="right">{{ $item->amount_date }}</td>
                                        <td class="right"> {{ number_format($sum,2) ?? ''}}</td>
                                      </tr>
                              @endforeach   
                    @endif
                </tbody>
                 <footer>
                    <tr>
                        <td colspan="4" class="text-right">รวม</td>
                        <td class="right">
                            {{ $sum_amount ?? '0.00'}} 
                        </td>
                    </tr>
                </footer>
            </table>

   <p class="indent50"> 
        และหากท่านมีความประสงค์จะเปลี่ยนแปลงสาขา/ขอบข่ายที่ขอรับการรับรองหน่วยรับรอง
        โปรดแจ้งสำนักงานทราบพร้อมระบุเหตุผลภายใน 15 วัน หากพ้นกำหนดเวลาดังกล่าว 
        สำนักงานจะถือว่าท่านยืนยันและยอมรับในสาขา/ขอบข่ายที่ขอรับการรับรองหน่วยรับรอง ตามข้างต้น
   </p>
    <p>
          จึงเรียนมาเพื่อโปรดดำเนินการ  
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


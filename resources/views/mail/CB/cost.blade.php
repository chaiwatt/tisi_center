
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
            <b>เรียน    {{  !empty($certi_cb->name) ?  $certi_cb->name   :  ''  }} </b>
        </p>
        <p> 
            <b>เรื่อง   การประมาณการค่าใช้จ่าย</b>    
        </p>
 
       <p class="indent50"> 
          ตามที่    {{  !empty($certi_cb->name) ?   $certi_cb->name  :  ''  }} 
          ได้ยื่นคำขอรับบริการยืนยันความสามารถหน่วยรับรอง
          คำขอเลขที่  {{  !empty($certi_cb->app_no) ?   $certi_cb->app_no  :  ''  }} 
           ลงรับวันที่  {{  !empty($certi_cb->save_date) ?  HP::formatDateThaiFull($certi_cb->save_date) :  ''  }}  นั้น
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
                    @foreach ($cost->items as $key => $item)
                        @php 
                            $sum =  str_replace(",","", $item->amount) * $item->amount_date;
                            $details =  App\Models\Bcertify\StatusAuditor::where('id',$item->detail)->first();
                        @endphp
                        @if(!empty($details))
                        <tr> 
                            <td class="center">{{ $key + 1 }}</td>
                            <td>{{ !is_null($details) ? $details->title : null }}</td>
                            <td class="right">{{ number_format(str_replace(",","", $item->amount),2) }}</td>
                            <td class="right">{{ $item->amount_date }}</td>
                            <td class="right"> {{ number_format($sum,2) ?? ''}}</td>
                        </tr>
                        @endif 
                    @endforeach
                </tbody>
                 <footer>
                    <tr>
                        <td colspan="4" class="text-right">รวม</td>
                        <td class="right">
                            {{ $cost->SumAmount  ?? ''}} 
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
          {{-- โปรดคลิก <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a> เพื่อตรวจสอบข้อมูลในระบบ 
        <br> --}}
            --------------------------
    </p>
            <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
     <p>
       {!!auth()->user()->UserContact!!}
    </p>
    </div> 
</body>
</html>


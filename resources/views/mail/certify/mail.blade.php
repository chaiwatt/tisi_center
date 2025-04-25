<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{
            width: 60%;
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
   </style>
</head>
<body>
   <div id="style">
        <p style="font-size:30px;"> <img src="{!! asset('plugins/images/anchor.jpg') !!}"  height="50px" width="50px"/> <b> การประมาณค่าใช้จ่าย</b></p>
        <p><dd><b>เลขคำขอ :</b> {{ $app_no ?? '' }} </p>  
        <p><dd><b>หน่วยงาน :</b> {{ $trader_operater_name ?? ''}} </p>  
        @if(count($items) > 0)
        @php 
        $count_date = [];
        $countItem = 0 ;
        @endphp
        @foreach($items['desc'] as $key =>  $item)
            @php $count_date[] = $items['nod'][$key]; @endphp
            @php 
                $countItem += (str_replace(",","", $items['cost'][$key]) * $items['nod'][$key]);
            @endphp
        @endforeach
        <p><dd><b>1 จำนวนวันที่ใช้ตรวจประเมินทั้งหมด  {{ max($count_date) ?? ''}}   วัน</b> </p>  
        <p><dd><b>2. ค่าใช้จ่ายในการตรวจประเมินทั้งหมด  {{ number_format($countItem,2) ?? ''}}  บาท</b> </p>  
        <table class="table table-bordered" id="customers">
            <thead>
                    <tr>
                        <th>#</th>
                        <th>รายละเอียด</th>
                        <th>จำนวนเงิน</th>
                        <th>จำนวนวัน</th>
                        <th>รวม (บาท)</th>
                    </tr>
            </thead>
            <tbody>
                @foreach ($items['desc'] as $key => $data)
                    @php 
                        $sum =  str_replace(",","", $items['cost'][$key]) * $items['nod'][$key];
                        $details =  App\Models\Certify\Applicant\CostDetails::where('id',$data)->first();
                    @endphp
                    <tr> 
                        <td>{{ $key + 1 }}</td>
                        <td>{{ !is_null($details) ? $details->title : null }}</td>
                        <td>{{ number_format(str_replace(",","", $items['cost'][$key]),2) }}</td>
                        <td>{{ number_format($items['nod'][$key]) }}</td>
                        <td> {{ number_format($sum,2) ?? ''}}</td>
                    </tr> 
                @endforeach
            </tbody>
             <footer>
                <tr>
                    <td colspan="4" class="text-right">รวม</td>
                    <td>
                        {{ number_format($countItem,2) ?? ''}} 
                    </td>
                </tr>
            </footer>
        </table>
        @endif
        <p><a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a></p>  
    </div> 
    
</body>
</html>



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
   </style>
</head>
<body>
    <p>
        <b>เรียน   {{  !empty($certi_ib->name) ?   $certi_ib->name   :  ''  }} </b>
    </p>
    <p>
        <b>เรื่อง   สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ </b>
    </p>
    <p class="indent50"> 
        ตามที่ {{  !empty($certi_ib->name) ? $certi_ib->name   :  ''  }}
        ได้ยื่นคำขอรับบริการหน่วยตรวจ
        คำขอเลขที่  {{  !empty($certi_ib->app_no) ?   $certi_ib->app_no  :  ''  }} 
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ประชุม คณะกรรมการ/คณะอนุกรรมการ เมื่อ   {{  !empty($report->report_date) ?  HP::formatDateThaiFull($report->report_date) :  ''  }} นั้น ได้มีมติ ดังนี้
    </p>

    <p class="indent50"> 
        โดยมีรายนามดังต่อไปนี้
    </p>

    @if(!is_null($report->details))
    <p class="indent50"> 
       รายละเอียด  {{ !empty($report->details) ? $report->details : '' }}
    </p>
    @endif

    @if(count($report->FileAttachReport2Many) > 0)
    <p class="indent50"> 
        หลักฐานอื่นๆ
        @foreach ($report->FileAttachReport2Many as $item)
            <p class="indent100">
                {{ $item->file_desc ?? null}}
                <a href="{{ url('certify/check/files_cb/'.$item->file) }}"  target="_blank">
                    {!! !empty($item->file_client_name) ? $item->file_client_name  : $item->file  !!}
                </a>
            </p>    
        @endforeach
    </p>
    @endif

    <p>
        จึงเรียนมาเพื่อทราบ
     {{-- โปรดคลิก
        <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
        เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ <br>
        -------------------------- --}}
    </p>
    <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
    <p>
        {!!auth()->user()->UserContact!!}
    </p>
 </div> 
</body>
</html>
 

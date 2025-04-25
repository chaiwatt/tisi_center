
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
   <div id="style">

    <p>
        <b>เรียน   {{  !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name   :  ''  }}  </b>
    </p>
    <p>
        <b>เรื่อง   สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ </b>
    </p>

    <p class="indent50"> 
        ตามที่ {{  !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name   :  ''  }} 
        ได้ยื่นคำขอรับบริการหน่วยตรวจ
        คำขอเลขที่  {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }}  
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ประชุม คณะกรรมการ/คณะอนุกรรมการ เมื่อ   {{  !empty($report->save_date) ?  HP::formatDateThaiFull($report->save_date) :  ''  }} นั้น ได้มีมติ ดังนี้
    </p>

   
    @if(isset($report) && !is_null($report->file_loa) && $report->file_loa != '')
    <p class="indent50"> 
        หลักฐานอื่นๆ
        <p class="indent100">
             <a href="{{url('certify/check/file_client/'.$report->file_loa.'/'.( !empty($report->file_loa_client_name) ? $report->file_loa_client_name : basename($report->file_loa)  ))}}" target="_blank">
                  {!! !empty($report->file_loa_client_name) ? $report->file_loa_client_name : basename($report->file_loa)  !!}
             </a>
        </p>    
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


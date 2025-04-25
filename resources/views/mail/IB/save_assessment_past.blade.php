
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
        text-align: center;
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
      <b>เรียน    {{  !empty($certi_ib->name) ? $certi_ib->name   :  ''  }}</b>
  </p>
   <p>
      <b>เรื่อง รายงานการปิดข้อบกพร้อง/แจ้งยืนยันขอบข่าย  </b>
   </p> 
   <p class="indent50"> 
     
@if (!is_null($assessment->date_scope_edit))
ตามที่   {{  !empty($certi_ib->name) ?  $certi_ib->name  :  ''  }}
ขอแก้ไขขอบข่าย สำหรับคำขอเลขที่    {{  !empty($certi_ib->app_no) ?   $certi_ib->app_no  :  ''  }} 
เมื่อวันที่ {{  !empty($assessment->date_scope_edit) ?  HP::formatDateThaiFull($assessment->date_scope_edit) :  ''  }}
สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ดำเนินการแล้ว
@else
ตามที่ท่านได้ยื่นคำขอรับรองหน่วยตรวจ
สำหรับคำขอเลขที่    {{  !empty($certi_ib->app_no) ?   $certi_ib->app_no  :  ''  }} 
เมื่อวันที่  {{  !empty($certi_ib->created_at) ?  HP::formatDateThaiFull($certi_ib->created_at) :  ''  }}  นั้น
ทางเจ้าหน้าที่จึงขอแจ้งผ่านการตรวจประเมินและขอความเห็นเรื่อง Scope  จาก   {{  !empty($certi_ib->name) ?  $certi_ib->name  :  ''  }}
@endif
       <p class="indent50"> 
           โดยมีรายละเอียดดังนี้
       </p>
         @if(count($assessment->FileAttachAssessment2Many) > 0)
           <p > 
                   รายงาน Scope :
                   @foreach ($assessment->FileAttachAssessment2Many as $item2)
                   <p class="indent100">
                       <a href="{{ url('certify/check/files_ib/'.$item2->file) }}"  target="_blank">
                         {!!  $item2->file   !!}
                     </a>
                   </p>    
                   @endforeach
          </p>
        @endif 
        @if(count($assessment->FileAttachAssessment3Many) > 0)
        <p > 
                สรุปรายงานการตรวจทุกครั้ง :
                @foreach ($assessment->FileAttachAssessment3Many as $item2)
                <p class="indent100">
                    <a href="{{ url('certify/check/files_ib/'.$item2->file) }}"  target="_blank">
                      {!! $item2->file  !!}
                  </a>
                </p>    
                @endforeach
       </p>
         @endif 
         <p>
          จึงเรียนมาเพื่อทราบและโปรดดำเนินการ
           {{-- <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
           เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ --}}
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


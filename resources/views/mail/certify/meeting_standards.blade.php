

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
        <p><b>เรียน</b>  {{ @$committee_special->committee_group }} </p>  
         <p><b>เรื่อง</b> นัดหมายการประชุม  {{ $meeting_standard->title  }}</p>  
        <p> แจ้งนัดหมายการประชุม   {{ $meeting_standard->title  }} 
             {{ !empty($meeting_standard->start_date) &&   !empty($meeting_standard->end_date)  ?   'ในวันที่ '.HP::DateFormatGroupFullTh($meeting_standard->start_date,$meeting_standard->end_date)  : ''}}  
             {{-- {{ !empty($meeting_standard->start_time) &&   !empty($meeting_standard->end_time)  ?    ' เวลา '.HP::TimeFormat($meeting_standard->start_time,$meeting_standard->end_time).' น.'  : ''}}    --}}
             {{ !empty($meeting_standard->start_time) &&   !empty($meeting_standard->end_time)  ?    ' เวลา '.date("H:i", strtotime($meeting_standard->start_time)).'-'. date("H:i", strtotime($meeting_standard->end_time)) .' น.'  : ''}} 
             {{ $meeting_standard->meeting_place }}
       </p>  
        @if (!empty($meeting_standard->meeting_detail))
             <p> {{ $meeting_standard->meeting_detail }} </p>         
        @endif
        @if (!empty($meeting_standard->AttachFileMeetingStandardAttachTo))
               @php
                    $attachs = $meeting_standard->AttachFileMeetingStandardAttachTo;
               @endphp
               @if (!empty($attachs) && count($attachs) > 0)        
                    @foreach ($attachs as $key=>$attach)
                         <p> 
                              @if($key == 0)
                                   เอกสารประกอบการประชุม : 
                              @else
                                   {!! str_repeat('&nbsp;', 40) !!}
                              @endif
                              <span>
                                   {!! !empty($attach->caption) ? $attach->caption : '' !!}
                                   <a href="{!! HP::getFileStorage($attach->url) !!}" target="_blank">
                                             {!!  $attach->filename ?? '' !!}
                                   </a>
                              </span>
                         </p> 
                    @endforeach
               @endif
       @endif

        <p> จึงเรียนมาเพื่อทราบและโปรดดำเนินการต่อไป </p> 
        <p>
            <b>ข้อมูลติดต่อ</b>
        </p>
        <p>
             {!!auth()->user()->UserContact !!}
       </p>
     
       
    </div> 
</body>
</html>


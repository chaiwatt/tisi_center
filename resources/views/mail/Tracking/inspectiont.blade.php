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
      <b>เรียน    {{  !empty($certi->name) ? $certi->name   :  ''  }}</b>
  </p>
   <p>
      <b>เรื่อง สรุปผลการตรวจประเมิน  </b>
   </p> 
   <p class="indent50"> 
     
 
 
ตามที่ท่านได้ยื่นคำขอรับรองหน่วยตรวจ
สำหรับคำขอเลขที่    {{  !empty($data->reference_refno) ?   $data->reference_refno  :  ''  }} 
เมื่อวันที่  {{  !empty($data->created_at) ?  HP::formatDateThaiFull($data->created_at) :  ''  }}  นั้น
ทางเจ้าหน้าที่จึงขอแจ้งผ่านการตรวจประเมินและขอความเห็นเรื่อง Scope  จาก   {{  !empty($certi->name) ?  $certi->name  :  ''  }}
 
       <p class="indent50"> 
           โดยมีรายละเอียดดังนี้
       </p>
         @if(!is_null($data->FileAttachScopeTo))
           <p > 
                   รายงาน Scope :
                   <p class="indent100">
                          <a href="{{ url('funtions/get-view') . '/' . $data->FileAttachScopeTo->url  . '/' . $data->FileAttachScopeTo->filename }}"  target="_blank" 
                              title="{{  basename($data->FileAttachScopeTo->filename) }}">
                              {{ basename($data->FileAttachScopeTo->filename)  }}
                          </a>
                   </p>    
          </p>
        @endif 
        @if(!is_null($data->FileAttachReportTo))
          <p > 
                    สรุปรายงานการตรวจทุกครั้ง :
                    <p class="indent100">
                              <a href="{{ url('funtions/get-view') . '/' . $data->FileAttachReportTo->url  . '/' . $data->FileAttachReportTo->filename }}"  target="_blank" 
                                        title="{{  basename($data->FileAttachReportTo->filename) }}">
                                        {{ basename($data->FileAttachReportTo->filename)  }}
                              </a>
                    </p>    
          </p>
         @endif 
         <p>
          จึงเรียนมาเพื่อทราบและโปรดดำเนินการ
        
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


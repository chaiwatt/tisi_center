
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
             <b>เรียน    {{  !empty($certi->name) ?   $certi->name  :  ''  }} </b>
        </p>
        <p> 
            <b> เรื่อง   สรุปรายงานเสนอคณะกรรมการ/คณะอนุกรรมการ   </b> 
         </p>
         <p class="indent50"> 
            ตามที่ {{  !empty($certi->name) ? $certi->name   :  ''  }}
            ได้ยื่นคำขอรับบริการหน่วยรับรอง
            คำขอเลขที่  {{  !empty($data->reference_refno) ?   $data->reference_refno  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ประชุม คณะกรรมการ/คณะอนุกรรมการ เมื่อ   {{  !empty($data->report_date) ?  HP::formatDateThaiFull($data->report_date) :  ''  }} นั้น ได้มีมติ ดังนี้
        </p>
        
        <p class="indent50"> 
            โดยมีรายนามดังต่อไปนี้
        </p>

        @if(!is_null($data->details))
        <p class="indent50"> 
           รายละเอียด  {{ !empty($data->details) ? $data->details : '' }}
        </p>
        @endif

        @if(!is_null($data->FileAttachFileLoaTo))
        <p class="indent50"> 
            หลักฐานอื่นๆ
                <p class="indent100">
                    <a href="{{ url('funtions/get-view') . '/' . $data->FileAttachFileLoaTo->url . '/' . $data->FileAttachFileLoaTo->filename  }}"  target="_blank" 
                              title="{{  basename($data->FileAttachFileLoaTo->filename) }}">
                            {{ basename($data->FileAttachFileLoaTo->filename)  }}
                    </a>
                </p>    
        </p>
        @endif
        
        @if(count($data->FileAttachFilesMany) > 0)
        <p class="indent50"> 
            หลักฐานอื่นๆ
            @foreach ($data->FileAttachFilesMany as $item)
                <p class="indent100">
                    {{ $item->caption ?? null}}
                    <a href="{{ url('funtions/get-view') . '/' . $item->url . '/' . $item->filename  }}"  target="_blank" 
                              title="{{  basename($item->filename) }}">
                            {{ basename($item->filename)  }}
                    </a>
                </p>    
            @endforeach
        </p>
        @endif

        <p>
            จึงเรียนมาเพื่อดำเนินการ
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


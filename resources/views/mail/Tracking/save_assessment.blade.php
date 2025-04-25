
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
            <b>เรียน   {{  !empty($data->name) ?  $data->name   :  ''  }} </b>
        </p>
         <p>
            <b>เรื่อง นำส่งรายงานการตรวจประเมิน </b>
         </p> 
         <p class="indent50"> 
            ตามที่   {{ !empty($data->name) ?   $data->name  :  ''  }}
            ได้ยื่นคำขอรับบริการหน่วยรับรอง
            คำขอเลขที่   {{ !empty($export->reference_refno) ?   $export->reference_refno  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมจึงแจ้งผลการพิจารณาแนวทางดังแนบ
        </p>

        <table  id="customers" width="60%">
            <thead>
                    <tr>
                        <th align="center" width="2%">#</th>
                        <th align="center" width="20%">รายงานที่</th>
                        <th align="center"  width="20%">ข้อบกพร่อง/ข้อสังเกต</th>
                        <th align="center" width="20%">  
                             มอก. {{ $tis  }}
                        </th>
                        <th align="center"  width="20%">ประเภท</th>
                    </tr>
            </thead>
            <tbody>
                @if(count($assessment->tracking_assessment_bug_many) > 0 )
                @foreach($assessment->tracking_assessment_bug_many as  $key => $item)
                <tr>
                    <td  align="center">
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {!!  $item->report ?? null   !!}
                    </td>
                    <td>
                        {!! $item->remark ?? null !!}
                    </td>
                    <td>
                        {!!    $item->no ?? null  !!}
                    </td>
                    <td>
                        {!!    ($item->type == 1 ? 'ข้อบกพร่อง' : 'ข้อสังเกต' ) !!}
                    </td>
                </tr>
                 @endforeach  
            @endif
            </tbody>
        </table>
{{-- 
        @if(!empty($assessment->FileAttachAssessment1To->url) && $assessment->FileAttachAssessment1To->url !='')
        <p class="indent50">   
           <b>รายงานการตรวจประเมิน : </b> <a href="{{ url('funtions/get-view') . '/' . $assessment->FileAttachAssessment1To->url . '/' . $assessment->FileAttachAssessment1To->filename  }}"  target="_blank" 
                                            title="{{  basename($assessment->FileAttachAssessment1To->filename) }}">
                                            {{ basename($assessment->FileAttachAssessment1To->filename)  }}
                                         </a>
        </p>
        @endif --}}
         <p>
            จึงเรียนมาเพื่อพิจารณา 
            <br>
           {{-- <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
           เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ <br> --}}
           --------------------------
        </p>
         <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
        <p>
            {!!auth()->user()->UserContact!!}
       </p>
    </div> 
</body>
</html>


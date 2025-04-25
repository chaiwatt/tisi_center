
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
        <b>เรียน   {{  !empty($certi_ib->name) ? $certi_ib->name  :  ''  }} </b>
    </p>
     <p>
        <b>เรื่อง นำส่งรายงานการตรวจประเมิน </b>
     </p> 
     <p class="indent50"> 
        ตามที่   {{ !empty($certi_ib->name) ?$certi_ib->name  :  ''  }}
        ได้ยื่นคำขอรับบริการหน่วยตรวจ
        คำขอเลขที่   {{ !empty($certi_ib->app_no) ?   $certi_ib->app_no  :  ''  }} 
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมจึงขอแจ้งผลการพิจารณาแนวทางดังแนบ
    </p>


    <table  id="customers" width="60%">
        <thead>
                <tr>
                    <th align="center" width="2%">#</th>
                    <th align="center" width="20%">รายงานที่</th>
                    <th align="center"  width="20%">ข้อบกพร่อง/ข้อสังเกต</th>
                    <th align="center" width="20%">  
                         มอก. {{  !empty($assessment->CertiIBAuditorsTo->FormulaTo->title) ?   str_replace("มอก.","",$assessment->CertiIBAuditorsTo->FormulaTo->title) :''  }}
                    </th>
                    <th align="center"  width="20%">ประเภท</th>
                </tr>
        </thead>
        <tbody>
            @if(count($assessment->CertiIBBugMany) > 0 )
            @foreach($assessment->CertiIBBugMany as  $key => $item)
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

    @if(!empty($assessment->FileAttachAssessment1To->file) && $assessment->FileAttachAssessment1To->file !='')
    <p class="indent50">   
       <b>รายงานการตรวจประเมิน : </b> <a href="{{ url('certify/check/files_ib') . '/' . $assessment->FileAttachAssessment1To->file }}"  target="_blank" 
                                        title="{{  basename($assessment->FileAttachAssessment1To->file_client_name) }}">
                                        {{  !empty($assessment->FileAttachAssessment1To->file_client_name) ?  $assessment->FileAttachAssessment1To->file_client_name : basename($assessment->FileAttachAssessment1To->file)  }}
                                     </a>
    </p>
    @endif



         <p>
            จึงเรียนมาเพื่อพิจารณา
           {{-- <a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a>
           เพื่อตรวจสอบข้อมูลในระบบ <br> จึงเรียนมาเพื่อดำเนินการ  --}}
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


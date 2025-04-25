
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
            <b>เรียน    {{  !empty($data->name) ?  $data->name   :  ''  }} </b>
        </p>

    @if (!is_null($assessment->FileAttachAssessment5To))
        <p>
         <b>เรื่อง แจ้งผลการประเมินแ </b>
        </p> 
        <p class="indent50"> 
            ตามที่   {{ !empty($data->name) ?   $data->name  :  '' }}  คำขอเลขที่  {{ !empty($export->reference_refno) ?   $export->reference_refno  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ดำเนินการตรวจสอบ ผ่านผลการตรวจประเมินคณะผู้ตรวจประเมิน  {{ !empty($assessment->auditors_to->auditor) ?   $assessment->auditors_to->auditor  :  ''  }}
        </p>
        @if(!empty($assessment->FileAttachAssessment5To->url) && $assessment->FileAttachAssessment5To->url !='')
        <p class="indent50">   
        <b>รายงานปิด Car  : </b> <a href="{{ url('funtions/get-view') . '/' . $assessment->FileAttachAssessment5To->url  . '/' . $assessment->FileAttachAssessment5To->filename }}"  target="_blank" 
                                        title="{{  basename($assessment->FileAttachAssessment5To->filename) }}">
                                        {{ basename($assessment->FileAttachAssessment5To->filename)  }}
                                </a>
        </p>
    @endif
    @else

    <p>
        <b>เรื่อง  {{ !is_null($assessment->FileAttachAssessment5To) ? 'แจ้งผลการประเมินหลักฐานการแก้ไขข้อบกพร่อง' : 'แจ้งผลการประเมินแนวทางแก้ไขข้อบกพร่อง' }}  </b>
    </p> 
    <p class="indent50"> 
        ตามที่   {{ !empty($data->name) ?   $data->name  :  '' }} 
        {{ !is_null($assessment->FileAttachAssessment5To) ? 'ได้แจ้งหลักฐานการแก้ไข' : 'ได้แจ้งแนวทางแก้ไข' }}
        คำขอเลขที่  {{ !empty($export->reference_refno) ?   $export->reference_refno  :  ''  }} 
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ดำเนินการตรวจสอบ{{ !is_null($assessment->FileAttachAssessment5To) ? 'หลักฐานการแก้ไข' : 'แนวทางแก้ไข' }}ข้อบกพร่องเรียบร้อยแล้ว ดังแนบ
    </p>
    @if(count($assessment->tracking_assessment_bug_many) > 0 )
        <table  id="customers" width="100%">
            <thead>
                    <tr>
                        <th  width="2%">ลำดับ</th>
                        <th  width="10%">รายงานที่</th>
                        <th  width="20%">ผลการประเมินที่พบ</th>
                        <th  width="20%" >แนวทางการแก้ไข</th>
                        <th  width="20%" >ผลการประเมิน</th>
                        <th  width="20%" >หลักฐาน</th>
                    </tr>
            </thead>
            <tbody>
              
                @foreach($assessment->tracking_assessment_bug_many as  $key => $item)
                <tr>
                    <td  align="center">
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {!! $item->report ?? null   !!}
                    </td>
                    <td>
                        {!! $item->remark ?? null !!}
                    </td>
                    <td>
                        {!!  $item->details ?? null  !!}
                    </td>
                    <td>
                        @if ($item->status == 1)
                           ผ่าน
                        @else
                          {{ !empty( $item->comment) ?  'ไม่ผ่าน : '. $item->comment  :  '' }}    
                        @endif
                    </td>
                     <td>
                        @if ($item->status == 1)
                            @if ($item->file_status == 1)
                                ผ่าน
                            @else
                            {{ !empty( $item->file_comment) ?  'ไม่ผ่าน : '. $item->file_comment  :  '' }}    
                            @endif
                        @else
                             -
                        @endif
                    </td>
                </tr>
                 @endforeach  
            </tbody>
        </table>
        @endif
    @endif
         <p>
            จึงเรียนมาเพื่อพิจารณา 
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


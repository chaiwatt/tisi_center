
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
            <b>เรียน    {{  !empty($certi_lab->name) ? $certi_lab->name   :  ''  }} </b>
        </p>
         <p>
            <b>เรื่อง  {{ !is_null($assessment->file_car) ? 'แจ้งผลการประเมินหลักฐานการแก้ไขข้อบกพร่อง' : 'แจ้งผลการประเมินแนวทางแก้ไขข้อบกพร่อง' }}  </b>
         </p> 
         <p class="indent50"> 
            ตามที่   {{ !empty($certi_lab->name) ?   $certi_lab->name   :  '' }} 
            {{ !is_null($assessment->file_car) ? 'ได้แจ้งหลักฐานการแก้ไข' : 'ได้แจ้งแนวทางแก้ไข' }}
            คำขอเลขที่  {{ !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้ดำเนินการตรวจสอบ{{ !is_null($assessment->file_car) ? 'หลักฐานการแก้ไข' : 'แนวทางแก้ไข' }}ข้อบกพร่องเรียบร้อยแล้ว ดังแนบ
        </p>

    @if (!is_null($assessment->file_car))

        @if(!empty($assessment->file_car) && $assessment->file_car !='')
        <p class="indent50">   
        <b>รายงานปิด Car  : </b> 
            <a href="{{url('certify/check/file_client/'.$assessment->file_car.'/'.( !empty($assessment->attach_client_name) ? $assessment->attach_client_name :  basename($assessment->file_car)))}}" 
                title="{{  !empty($assessment->attach_client_name) ? $assessment->attach_client_name :  basename($assessment->file_car)}}"  target="_blank">
                {!! HP::FileExtension($assessment->file_car)  ?? '' !!}
            </a> 
        </p>
        @endif

        @if(!empty($assessment->file_scope))
            @php
                $file_scope =   json_decode($assessment->file_scope);
            @endphp
            @if(!empty($file_scope))
                <p class="indent50">   
                <b>ขอบข่ายที่ขอรับการรับรอง (Scope) : </b> 
                        @foreach ($file_scope as  $item)
                        <p class="indent100">
                            <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)))}}" 
                                title="{{  !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)}}"  target="_blank">
                                {!! HP::FileExtension($item->attachs)  ?? '' !!}
                             
                             </a>
                        </p>
                        @endforeach          
                </p>
            @endif
        @endif
    @else
    @if(count($assessment->items) > 0 )
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
              
                @foreach($assessment->items as  $key => $item)
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
                          {{ !empty( $item->comment) ?  'ไม่ผ่าน : '. $item->comment  :  'ไม่ผ่าน' }}    
                        @endif
                    </td>
                     <td>
                        @if ($item->status == 1)
                            @if ($item->file_status == 1)
                                ผ่าน
                            @else
                            {{ !empty( $item->file_comment) ?  'ไม่ผ่าน : '. $item->file_comment  :  'ไม่ผ่าน' }}    
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


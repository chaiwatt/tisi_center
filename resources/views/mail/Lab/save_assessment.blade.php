
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
        <b>เรียน    {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name   :  ''  }} </b>
    </p>
     <p>
        <b>เรื่อง นำส่งรายงานการตรวจประเมิน </b>
     </p> 
     <p class="indent50"> 
        ตามที่  {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name  :  ''  }}
        ได้ยื่นคำขอรับบริการห้องปฏิบัติการ
        คำขอเลขที่   {{ !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
        สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมจึงขอแจ้งผลการพิจารณาแนวทางดังแนบ
    </p>
    <p class="indent50"> 
        โดยมีรายละเอียดดังนี้
    </p>
    <p class="indent50"> 
        @if (!is_null($data->report_status))
            @if ($data->report_status == 1)
            รายงานข้อพกพร่อง : มี
            @else
            รายงานข้อพกพร่อง : ไม่มี
            @endif
        @else
        รายงานข้อพกพร่อง :  -
        @endif
    </p>

     @if(count($data->items) > 0)    
        <table id="customers" width="90%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="30%">ผลการประเมินที่พบ</th>
                    <th width="20%">ประเภท</th>
                @if(count($data->CertificateHistorys) >= 2)
                    <th width="15%">ผลการพิจารณาแนวทาง</th>
                    <th width="15%">หลักฐาน</th>
                @endif
                </tr>
             </thead>
             <tbody>
                @foreach ($data->items  as $key => $item)
                @php 
                    $type = ['1'=>'ข้อบกพร่อง','2'=>'ข้อสังเกต']; 
                    $status = ['1'=>'','2'=>'ไม่ผ่าน']; 	
                @endphp
                    <tr>
                       <td style="text-align: center;"> {{$key +1 }}</td>
                       <td>{{   $item->remark ?? '-'}}</td>
                       <td>
                           {{ array_key_exists($item->type,$type) ? $type[$item->type] : '-'   }}
                       </td>
                       @if(count($data->CertificateHistorys) >= 2)
                        <td>
                            {{   ($item->status == 1) ? 'ผ่าน' : 'ไม่ผ่าน :'.@$item->comment  }} 
                        </td>
                        <td>
                            @if ($item->status == 1 && !is_null($item->file_status))
                                @if ($item->file_status == 1)
                                    ผ่าน
                                @else
                                 {{ !empty( $item->file_comment) ?  'ไม่ผ่าน : '. $item->file_comment  :  'ไม่ผ่าน' }}    
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
      @endif

      @if(isset($data)  && !is_null($data->attachs)) 
      @php 
      $attachs = json_decode($data->attachs);
      @endphp
       <p  class="indent50">  
           <b>ไฟล์แนบ :</b>
           @foreach($attachs as  $key => $item)
               <p class="indent50">     
                <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->attachs_client_name) ? $item->attachs_client_name : 'null' ))}}" 
                   title=" {{ !empty($item->attachs_client_name) ? $item->attachs_client_name :  basename($item->attachs)}}"  target="_blank">
                  {!!  !empty($item->attachs_client_name) ? $item->attachs_client_name :  @basename($item->attachs) ?? '' !!}
              </a>
               </p>
           @endforeach
       </p>
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


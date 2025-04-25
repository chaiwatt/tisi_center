
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
            <b>เรียน  บริษัท  {{  !empty($certi_cb->name) ? $certi_cb->name   :  ''  }} </b>
        </p>
        <p> 
            <b> เรื่อง  แจ้งค่าบริการในการตรวจประเมิน </b> 
         </p>
        <p style="text-indent: 50px;">
            ตามที่ท่านได้ยื่นคำขอรับบ่ริการ
            หมายเลขคำขอ     {{  !empty($certi_cb->app_no) ?   $certi_cb->app_no  :  ''  }}  
            และ เห็นด้วยกับการแต่งตั้งคณะผู้ตรวจประเมิน
            จึงขอแจ้งค่าบริการในการตรวจประเมิน
        </p> 

        @if ($PayIn->conditional_type == 1)  <!-- เรียกเก็บค่าธรรมเนียม  --> 
                <p>	ค่าธรรมเนียม :
                    <span style="color:#26ddf5;">{{ !empty($PayIn->amount) ?  number_format($PayIn->amount,2).' บาท ' : '0.00' }}</span>
                </p>
            @if (!is_null($PayIn->FileAttachPayInOne1To))
                    <p> ค่าบริการในการตรวจประเมิน :
                        <a href="{{url('certify/check/file_cb_client/'.$PayIn->FileAttachPayInOne1To->file.'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :  basename($PayIn->FileAttachPayInOne1To->file)  ))}}" 
                            title="{{  !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name : basename($PayIn->FileAttachPayInOne1To->file) }}" target="_blank">
                            {!!  !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :  basename($PayIn->FileAttachPayInOne1To->file)  !!}
                        </a> 
                    <p>
            @endif
            
        @elseif($PayIn->conditional_type == 2) <!-- ยกเว้นค่าธรรมเนียม -->
                <p>	หมายเหตุ :
                    <span>{{ !empty($PayIn->remark) ? $PayIn->remark  : null  }}</span>
                </p>
                <p>	วันที่ยกเว้นค่าธรรมเนียม :
                    <span>{{  !empty($PayIn->start_date_feewaiver) && !empty($PayIn->end_date_feewaiver) ? HP::DateFormatGroupTh($PayIn->start_date_feewaiver,$PayIn->end_date_feewaiver) :  '-' }}</span>
                </p>
                @if (!is_null($PayIn->FileAttachPayInOne1To))
                    <p>	เอกสารยกเว้นค่าธรรมเนียม :
                        <a href="{{url('funtions/get-view-file/'.base64_encode($PayIn->FileAttachPayInOne1To->file).'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :  basename($PayIn->FileAttachPayInOne1To->file)  ))}}" target="_blank">
                        {!! !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :  basename($PayIn->FileAttachPayInOne1To->file) !!}
                        </a>
                    </p>
                @endif 

        @elseif($PayIn->conditional_type == 3) <!--  ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม --> 
            <p>	หมายเหตุ :
                <span>{{ !empty($PayIn->remark) ? $PayIn->remark  : null  }}</span>
            </p>
            @if (!is_null($PayIn->FileAttachPayInOne1To))
                    <p> ไฟล์แนบ :
                        <a href="{{url('certify/check/file_cb_client/'.$PayIn->FileAttachPayInOne1To->file.'/'.( !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :  basename($PayIn->FileAttachPayInOne1To->file)  ))}}" 
                            title="{{  !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name : basename($PayIn->FileAttachPayInOne1To->file) }}" target="_blank">
                            {!! !empty($PayIn->FileAttachPayInOne1To->file_client_name) ? $PayIn->FileAttachPayInOne1To->file_client_name :  basename($PayIn->FileAttachPayInOne1To->file)  !!}
                        </a> 
                    <p>
            @endif
        @endif
        
        @if (!empty($PayIn->detail) )
        <p>	หมายเหตุ :
            <span>{{ $PayIn->detail  ?? null  }}</span>
        </p>
        @endif

        <p style="text-indent: 50px;"> ทั้งนี้    {{  !empty($certi_cb->name) ? $certi_cb->name   :  ''  }}   ต้องชำระค่าบริการในการตรวจประเมินภายใน 30 วัน นับจากวันที่ตรวจประเมินแล้วเสร็จ</p>
        <p style="text-indent: 50px;"> จึงเรียนมาเพื่อโปรดดำเนินการ </p>
         <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
         <p>
            {!!   !empty(auth()->user()->UserContact) ? auth()->user()->UserContact  :  ''   !!}
         </p>

  
    </div> 
</body>
</html>

 
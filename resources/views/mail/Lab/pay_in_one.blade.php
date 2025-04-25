
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
      @php
        $formula = App\Models\Bcertify\Formula::Where([['applicant_type',3],['state',1]])->first();
      @endphp
   <div id="style">
    <p> 
        <b>เรียน   {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name   :  ''  }}  </b>
    </p>
      <p> 
        <b> เรื่อง  แจ้งค่าบริการในการตรวจประเมิน </b> 
     </p>
     <p style="text-indent: 50px;">
        ตามที่ท่านได้ยื่นคำขอรับบ่ริการ หมายเลขคำขอ    {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name   :  ''  }}  
        และ เห็นด้วยกับการแต่งตั้งคณะผู้ตรวจประเมิน
        จึงขอแจ้งค่าบริการในการตรวจประเมิน
    </p>
   

        @if ($PayIn->conditional_type == 1)  <!-- เรียกเก็บค่าธรรมเนียม  --> 
                <p>	ค่าธรรมเนียม :
                    <span style="color:#26ddf5;">{{ !empty($PayIn->amount) ?  number_format($PayIn->amount,2).' บาท ' : '0.00' }}</span>
                </p>
            @if (!is_null($PayIn->amount_invoice))
                    <p> ค่าบริการในการตรวจประเมิน :
                        <a href="{{url('certify/check/file_client/'.$PayIn->amount_invoice.'/'.( !empty($PayIn->file_client_name) ? $PayIn->file_client_name :   basename($PayIn->amount_invoice) ))}}" target="_blank">
                            {!! !empty($PayIn->file_client_name) ? $PayIn->file_client_name :   basename($PayIn->amount_invoice)  !!}
                        </a>
                    <p>
            @endif
            
        @elseif($PayIn->conditional_type == 2) <!-- ยกเว้นค่าธรรมเนียม -->
                <p>	หมายเหตุ :
                    <span>{{ !empty($PayIn->remark) ? $PayIn->remark  : null  }}</span>
                </p>
                <p>	วันที่ยกเว้นค่าธรรมเนียม :
                    <span>{{ !empty($PayIn->DateFeewaiver)  ? $PayIn->DateFeewaiver : null   }}</span>
                </p>
                @if (!is_null($PayIn->amount_invoice))
                    <p>	เอกสารยกเว้นค่าธรรมเนียม :
                        <a href="{{url('funtions/get-view-file/'.base64_encode($PayIn->amount_invoice).'/'.( !empty($PayIn->file_client_name) ? $PayIn->file_client_name : basename($PayIn->amount_invoice)  ))}}" target="_blank">
                            {!! !empty($PayIn->file_client_name) ? $PayIn->file_client_name : basename($PayIn->amount_invoice)  !!}
                        </a>
                    </p>
                @endif 

        @elseif($PayIn->conditional_type == 3) <!--  ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม --> 
            <p>	หมายเหตุ :
                <span>{{ !empty($PayIn->remark) ? $PayIn->remark  : null  }}</span>
            </p>
            @if (!is_null($PayIn->amount_invoice))
                    <p> ไฟล์แนบ :
                        <a href="{{url('certify/check/file_client/'.$PayIn->amount_invoice.'/'.( !empty($PayIn->file_client_name) ? $PayIn->file_client_name :   basename($PayIn->amount_invoice) ))}}" target="_blank">
                            {!! !empty($PayIn->file_client_name) ? $PayIn->file_client_name :   basename($PayIn->amount_invoice) !!}
                        </a>
                    <p>
            @endif
        @endif
        
        @if (!empty($PayIn->detail) )
        <p>	หมายเหตุ :
            <span>{{ $PayIn->detail  ?? null  }}</span>
        </p>
        @endif

    <p style="text-indent: 50px;"> ทั้งนี้    {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name   :  ''  }}   ต้องชำระค่าบริการในการตรวจประเมินภายใน 30 วัน นับจากวันที่ตรวจประเมินแล้วเสร็จ</p>
    <p style="text-indent: 50px;"> จึงเรียนมาเพื่อโปรดดำเนินการ </p>
     <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
     <p>
        {!!  !empty(auth()->user()->UserContact) ?  auth()->user()->UserContact   :  ''     !!}
     </p>
    </div> 
</body>
</html>



 @if(!is_null($history->details))
 @php 
      $details =json_decode($history->details);
 @endphp   


<div class="row">
    <div class="col-sm-4 text-right"> <b>เงื่อนไขการชำระเงิน :</b></div>
    <div class="col-sm-7">
        <p>  
         @if (!empty($details[0]->conditional_type))
             @if ($details[0]->conditional_type == 1) <!--  หลักฐานค่าธรรมเนียม -->
                หลักฐานค่าธรรมเนียม
            @elseif($details[0]->conditional_type == 2) <!--  ยกเว้นค่าธรรมเนียม -->  
                ยกเว้นค่าธรรมเนียม
            @elseif($details[0]->conditional_type == 3) <!--  ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม --> 
                ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม
            @endif
        @else 
             หลักฐานค่าธรรมเนียม   
        @endif
        </p>
    </div>
</div>
 



@if (!empty($details[0]->conditional_type))

@if ($details[0]->conditional_type == 1) <!--  หลักฐานค่าธรรมเนียม -->
   
 <div class="row">
    <div class="col-md-4 text-right">
       <p class="text-nowrap">จำนวนเงิน :</p>
    </div>
    <div class="col-md-7">
      <span>{{ !empty($details[0]->amount)  ?  number_format($details[0]->amount,2) : '-'}} บาท</span> 
    </div>
  </div>
   
  <div class="row">
      <div class="col-md-4 text-right">
         <p class="text-nowrap">วันที่แจ้งชำระ :</p>
      </div>
      <div class="col-md-7">
        <span>{{ !empty($details[0]->report_date)  ? HP::DateThai($details[0]->report_date) : '-'}} </span> 
      </div>
   </div>

@if (!is_null($history->attachs))
<div class="row">
    <div class="col-md-4 text-right">
       <p class="text-nowrap">หลักฐานค่าบริการในการตรวจประเมิน :</p>
    </div>
    <div class="col-md-7">
        <a href="{{url('certify/check/file_client/'.$history->attachs.'/'.( !empty($history->attach_client_name) ? $history->attach_client_name :  basename($history->attachs) ))}}" target="_blank">
            {!! HP::FileExtension($history->attachs)  ?? '' !!}
        </a>
    </div>
</div>
@endif  

  
@elseif($details[0]->conditional_type == 2) <!--  ยกเว้นค่าธรรมเนียม -->  
   
<div class="row">
    <div class="col-sm-4 text-right"> <b>วันที่แจ้งชำระ :</b></div>
    <div class="col-sm-7">
        <p>  
            {{  !empty($details[0]->start_date_feewaiver) && !empty($details[0]->end_date_feewaiver) ? HP::DateFormatGroupTh($details[0]->start_date_feewaiver,$details[0]->end_date_feewaiver) :  '-' }}
        </p>
    </div>
 </div>   
@if(!is_null($history->attachs)) 
    <div class="row">
    <div class="col-sm-4 text-right"> <b>ใบแจ้งหนี้ค่าธรรมเนียม :</b></div>
    <div class="col-sm-7 text-left">
        <p>  
            <a href="{{url('funtions/get-view-file/'.base64_encode($history->attachs).'/'.( !empty($history->attach_client_name) ? $history->attach_client_name : basename($history->attachs) ))}}" target="_blank">
                {!! HP::FileExtension($history->attachs)  ?? '' !!}
            </a>
        </p>
    </div>
    </div>
@endif

@elseif($details[0]->conditional_type == 3) <!--  ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม --> 

<div class="row">
    <div class="col-sm-4 text-right"> <b>หมายเหตุ :</b></div>
    <div class="col-sm-7">
        <p>  
            {{  !empty($details[0]->remark)  ? $details[0]->remark :  '-' }}
        </p>
    </div>
 </div>   

@if (!is_null($history->attachs))
<div class="row">
    <div class="col-md-4 text-right">
       <p class="text-nowrap">ไฟล์แนบ :</p>
    </div>
    <div class="col-md-7">
        <a href="{{url('certify/check/file_client/'.$history->attachs.'/'.( !empty($history->attach_client_name) ? $history->attach_client_name :  basename($history->attachs) ))}}" target="_blank">
            {!! HP::FileExtension($history->attachs)  ?? '' !!}
        </a>
    </div>
</div>
@endif  

@endif  

@else 
 
<div class="row">
    <div class="col-md-4 text-right">
       <p class="text-nowrap">จำนวนเงิน :</p>
    </div>
    <div class="col-md-7">
      <span>{{ !empty($details[0]->amount)  ?  number_format($details[0]->amount,2) : '-'}} บาท</span> 
    </div>
  </div>
   
  <div class="row">
      <div class="col-md-4 text-right">
         <p class="text-nowrap">วันที่แจ้งชำระ :</p>
      </div>
      <div class="col-md-7">
        <span>{{ !empty($details[0]->report_date)  ? HP::DateThai($details[0]->report_date) : '-'}} </span> 
      </div>
    </div>

    
@endif


@endif



@if (!is_null($history->attachs_file))
<div class="row">
    <div class="col-md-4 text-right">
       <p class="text-nowrap">หลักฐานการชำระเงินค่าตรวจประเมิน :</p>
    </div>
    <div class="col-md-7">
         <a href="{{url('certify/check/file_client/'.$history->attachs_file.'/'.( !empty($history->evidence) ? $history->evidence :  basename($history->attachs_file) ))}}" target="_blank">
            {!! HP::FileExtension($history->attachs_file)  ?? '' !!}
        </a>
    </div>
</div>
@endif
@if (!is_null($history->status))
 <div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ตรวจสอบการชำค่าตรวจประเมิน :</p> 
    </div>
    <div class="col-md-7">
        <label><input type="radio" {{ ($history->status == 1) ? 'checked' : '' }}   class="check check-readonly" data-radio="iradio_square-green">
            &nbsp;ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว &nbsp;
       </label>
       <br>
       <label><input type="radio"  {{ ($history->status != 1) ? 'checked' : '' }}    class="check check-readonly" data-radio="iradio_square-red"  > 
           &nbsp;ยังไม่ได้ชำระเงิน &nbsp;
       </label>
    </div>
</div> 
@endif
@if(!is_null($history->details))
@php 
     $details =json_decode($history->details);
@endphp   
@if(isset($details[0]->detail) && !is_null($details[0]->detail))
    <div class="row">   
    <div class="col-md-4 text-right">
        <p class="text-nowrap">หมายเหตุ :</p>
    </div>
    <div class="col-md-7">
        <span>{{ !empty($details[0]->detail)  ? $details[0]->detail : '-'}} </span> 
    </div>
    </div>
 @endif
@endif

@if(!is_null($history->date)) 
<div class="row">
<div class="col-md-4 text-right">
    <p class="text-nowrap">วันที่บันทึก :</p>
</div>
<div class="col-md-7">
    {{ @HP::DateThai($history->date) ?? '-' }}
</div>
</div>
@endif




@if(!is_null($history->details_auditors_cancel))
<span class="text-danger">ยกเลิกแต่งตั้งคณะผู้ตรวจประเมิน</span>
<hr>
@php
$auditors_cancel = json_decode($history->details_auditors_cancel);
@endphp
@if(!is_null(HP::UserTitle($auditors_cancel->created_cancel)) )
    <div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ผู้ยกเลิก</p>
    </div>
    <div class="col-md-7 text-left">
            {{HP::UserTitle($auditors_cancel->created_cancel)->FullName}}
    </div>
    </div>
@endif
@if(isset($auditors_cancel->date_cancel))
    <div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">วันที่ยกเลิก</p>
    </div>
    <div class="col-md-7 text-left">
        {{ HP::DateThai($auditors_cancel->date_cancel)  ?? '-'}}
    </div>
    </div>
@endif
@if(isset($auditors_cancel->reason_cancel))
    <div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">เหตุผลที่ยกเลิก</p>
    </div>
    <div class="col-md-7 text-left">
        {{ $auditors_cancel->reason_cancel   ?? '-'}}
    </div>
    </div>
@endif
@endif

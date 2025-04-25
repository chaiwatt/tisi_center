 
@if(!is_null($history->details_one))
@php 
$details_one = json_decode($history->details_one);
@endphp 
@if(!is_null($details_one))

<div class="row">
    <div class="col-sm-4 text-right">
        <p class="text-nowrap">  เงื่อนไขการชำระเงิน :</p>
        </div>
    <div class="col-sm-7">
        <p>  
         @if (!empty($details_one->conditional_type))
             @if ($details_one->conditional_type == 1) <!--  หลักฐานค่าธรรมเนียม -->
                หลักฐานค่าธรรมเนียม
            @elseif($details_one->conditional_type == 2) <!--  ยกเว้นค่าธรรมเนียม -->  
                ยกเว้นค่าธรรมเนียม
            @elseif($details_one->conditional_type == 3) <!--  ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม --> 
                ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม
            @endif
        @else 
             หลักฐานค่าธรรมเนียม   
        @endif
        </p>
    </div>
</div>

@if(!empty($details_one->auditors_id))
@php 
    $auditor = App\Models\Certificate\TrackingAuditors::where('id',$details_one->auditors_id)->first();
@endphp 
@if(!is_null($auditor))
<div class="row">
  <div class="col-md-4 text-right">
      <p class="text-nowrap">ชื่อคณะผู้ตรวจประเมิน :</p>
  </div>
  <div class="col-md-8 text-left">
        {{ $auditor->auditor ?? null }}
  </div>
</div>
@endif

@if(!is_null($auditor))
<div class="row">
  <div class="col-md-4 text-right">
      <p class="text-nowrap">วันที่ตรวจประเมิน :</p>
  </div>
  <div class="col-md-8 text-left">
        {{ $auditor->CertiAuditorsDateTitle ?? null }}
  </div>
</div>
@endif
@endif

@if(!empty($history->amount_bill)) 
<div class="row">
<div class="col-md-4 text-right">
    <p class="text-nowrap">จำนวนเงิน :</p>
</div>
<div class="col-md-8 text-left">
    {{  number_format($history->amount_bill,2).' บาท' ?? null }}
</div>
</div>
@endif


@if ($details_one->conditional_type == 1) <!--  หลักฐานค่าธรรมเนียม -->

@if(!empty($details_one->start_date)) 
<div class="row">
<div class="col-md-4 text-right">
    <p class="text-nowrap"> วันที่แจ้งชำระ :</p>
</div>
<div class="col-md-8 text-left">
    {{ @HP::DateThai($details_one->start_date) ?? '-' }}
</div>
</div>
@endif

@elseif($details_one->conditional_type == 2) <!--  ยกเว้นค่าธรรมเนียม -->

<div class="row">
        <div class="col-sm-4 text-right"> <b>วันที่แจ้งชำระ :</b></div>
        <div class="col-sm-6">
            <p>  
                {{  !empty($details_one->start_date_feewaiver) && !empty($details_one->end_date_feewaiver) ? HP::DateFormatGroupTh($details_one->start_date_feewaiver,$details_one->end_date_feewaiver) :  '-' }}
            </p>
     </div>
 </div>  
@elseif($details_one->conditional_type == 3) <!--  ยกเว้นค่าชำระเงินนอกระบบ, ไม่เรียกชำระเงิน หรือ กรณีอื่นๆธรรมเนียม -->

<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap"> หมายเหตุ :</p>
    </div>
    <div class="col-md-8 text-left">
         {{  !empty($details_one->detail)  ? $details_one->detail :  '-' }}
    </div>
</div>

@endif


@endif
@endif


@if(!is_null($history->file))
@php 
$file = json_decode($history->file);
$label = ['1'=>'ค่าบริการในการตรวจประเมิน','2'=> 'ใบแจ้งหนี้ค่าธรรมเนียม','3'=>'ไฟล์แนบ'];
@endphp 
@if(!is_null($file))

<div class="row">
<div class="col-md-4 text-right">
<p class="text-nowrap">{{  !empty($details_one->conditional_type) && array_key_exists($details_one->conditional_type,$label) ? $label[$details_one->conditional_type] :  'ค่าบริการในการตรวจประเมิน' }} :</p>
</div>
<div class="col-md-8 text-left">
    <p> 
        <a href="{{url('funtions/get-view/'.$file->url.'/'.( !empty($file->filename) ? $file->filename : basename($file->new_filename) ))}}" target="_blank">
            {!! HP::FileExtension($file->filename)  ?? '' !!}
        </a>  
    </p>
</div>
</div>

@endif
@endif

@if(!is_null($history->attachs_file))
@php 
$attachs_file = json_decode($history->attachs_file);
@endphp 
@if(!is_null($attachs_file))

<div class="row">
<div class="col-md-4 text-right">
<p class="text-nowrap">หลักฐานการชำระเงินค่าตรวจประเมิน :</p>
</div>
<div class="col-md-8 text-left">
    <p> 
        <a href="{{url('funtions/get-view/'.$attachs_file->url.'/'.( !empty($attachs_file->filename) ? $attachs_file->filename : basename($attachs_file->new_filename) ))}}" target="_blank">
            {!! HP::FileExtension($attachs_file->filename)  ?? '' !!}
        </a>  
    </p>
</div>
</div>

@endif
@endif

 



@if(!is_null($history->status))
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ตรวจสอบการชำค่าตรวจประเมิน :</p> 
    </div>
    <div class="col-md-8 text-left">
        <label  >   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($history->status == 1 ) ? 'checked' : ' '  }}>  &nbsp; ได้รับการชำระเงินค่าตรวจประเมินเรียบร้อยแล้ว  &nbsp;</label>
        <label >   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($history->status != 1 ) ? 'checked' : ' '  }}>  &nbsp;ยังไม่ได้ชำระเงิน  &nbsp;</label>
    </div>
</div> 
@endif

@if(!empty($details_one->remark)) 
<div class="row">
<div class="col-md-4 text-right">
    <p class="text-nowrap">หมายเหตุ :</p>
</div>
<div class="col-md-8 text-left">
    {{ $details_one->remark ?? '-' }}
</div>
</div>
@endif


@if(!is_null($history->created_at)) 
<div class="row">
<div class="col-md-4 text-right">
    <p class="text-nowrap">วันที่บันทึก :</p>
</div>
<div class="col-md-8 text-left">
    {{ @HP::DateThai($history->created_at) ?? '-' }}
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
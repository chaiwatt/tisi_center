@if(!is_null($history->details_one))
@php 
    $details_one = json_decode($history->details_one);
@endphp 

@if(!is_null($details_one))
@php 
    $auditors = App\Models\Certificate\TrackingAuditors::where('id',$details_one->auditors_id)->first();
@endphp 
@if(!is_null($auditors))

<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">เลขคำขอ :</p>
    </div>
    <div class="col-md-8 text-left">
        <p> 
        {{ !empty($auditors->reference_refno) ? $auditors->reference_refno : null }}
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ชื่อผู้ยื่นคำขอ :</p>
    </div>
    <div class="col-md-8 text-left">
        <p> 
        {{ !empty($details_one->name) ? $details_one->name : null }}
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ชื่อห้องปฏิบัติการ :</p>
    </div>
    <div class="col-md-8 text-left">
        <p> 
        {{ !empty($details_one->laboratory_name) ? $details_one->laboratory_name : null }}
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ชื่อคณะผู้ตรวจประเมิน :</p>
    </div>
    <div class="col-md-8 text-left">
        <p> 
        {{ !empty($auditors->auditor) ? $auditors->auditor : null }}
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">วันที่ตรวจประเมิน :</p>
    </div>
    <div class="col-md-8 text-left">
        <p> 
        {{ !empty($auditors->CertiAuditorsDateTitle) ? $auditors->CertiAuditorsDateTitle : null }}
        </p>
    </div>
</div>

@if (!empty($auditors->FileAuditors2))
@php 
    $file = $auditors->FileAuditors2;
@endphp 
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">กำหนดการตรวจประเมิน :</p>
    </div>
    <div class="col-md-8 text-left">
        <p> 
            <a href="{{url('funtions/get-view/'.$file->url.'/'.( !empty($file->filename) ? $file->filename :  basename($file->url) ))}}" 
                title="{{ !empty($file->filename) ? $file->filename :  basename($file->url) }}" target="_blank">
                {!! HP::FileExtension($file->url)  ?? '' !!}
             </a>
        </p>
    </div>
</div>
@endif


@endif

<hr/>

<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">รายงานข้อบกพร่อง :</p>
    </div>
    <div class="col-md-8 text-left">
        <label>{!! Form::radio('', '1',false  , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp;มี &nbsp;</label>
        <label>{!! Form::radio('', '2',true , ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-red']) !!} &nbsp;ไม่มี &nbsp;</label>
    </div>
</div>
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">วันที่ทำรายงาน :</p>
    </div>
    <div class="col-md-8 text-left">
       {!! !empty($details_one->report_date) ? HP::DateThai($details_one->report_date) : null  !!}
    </div>
</div>


@endif

@endif


@if(!is_null($history->details_three))
@php 
 $details_three = json_decode($history->details_three);
@endphp 
@if(!is_null($details_three))
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">รายงานการตรวจประเมิน :</p>
    </div>
    <div class="col-md-8 text-left">
        <a href="{{url('funtions/get-view/'.$details_three->url.'/'.( !empty($details_three->filename) ? $details_three->filename :  basename($details_three->url) ))}}" 
            title="{{ !empty($details_three->filename) ? $details_three->filename :  basename($details_three->url) }}" target="_blank">
            {!! HP::FileExtension($details_three->url)  ?? '' !!}
        </a>
    </div>
</div>
@endif
@endif

@if(!is_null($history->attachs_car))
@php 
 $attachs_car = json_decode($history->attachs_car);
@endphp 
@if(!is_null($attachs_car))
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">รายงานปิด Car :</p>
    </div>
    <div class="col-md-8 text-left">
        <a href="{{url('funtions/get-view/'.$attachs_car->url.'/'.( !empty($attachs_car->filename) ? $attachs_car->filename :  basename($attachs_car->url) ))}}" 
            title="{{ !empty($attachs_car->filename) ? $attachs_car->filename :  basename($attachs_car->url) }}" target="_blank">
            {!! HP::FileExtension($attachs_car->url)  ?? '' !!}
        </a>
    </div>
</div>
@endif
@endif


@if(!is_null($history->file))
@php 
 $files = json_decode($history->file);
@endphp 
@if(!is_null($files) && count($files) > 0)
<div class="row">
    <div class="col-md-4 text-right">
        <p class="text-nowrap">ไฟล์แนบ :</p>
    </div>
    <div class="col-md-8 text-left">
        @foreach($files as  $key => $item2)
        <a href="{{url('funtions/get-view/'.$item2->url.'/'.( !empty($item2->filename) ? $item2->filename :  basename($item2->url) ))}}" 
                title="{{ !empty($item2->filename) ? $item2->filename :  basename($item2->url) }}" target="_blank">
                {!! HP::FileExtension($item2->url)  ?? '' !!}
        </a>
    @endforeach
    </div>
</div>
@endif
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

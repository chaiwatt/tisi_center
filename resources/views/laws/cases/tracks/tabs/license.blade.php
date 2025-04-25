<div class="form-group" >
    {!! Form::label('', 'เลขที่ใบอนุญาต'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('',  !empty($cases->offend_license_number) ? $cases->offend_license_number :  null , ['class' => 'form-control ', 'disabled' => true ]) !!}
    </div>
</div>

<div class="form-group" >
    {!! Form::label('status_result', 'สถานะใบอนุญาต'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        <label>{!! Form::radio('status_result', '1',  (!empty($license_result->status_result) && in_array($license_result->status_result,[1]) ) ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp;ใช้งาน&nbsp; </label>
        <label>{!! Form::radio('status_result', '2',  (!empty($license_result->status_result) && in_array($license_result->status_result,[2]) ) ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp;พักใช้&nbsp;</label>
        <label>{!! Form::radio('status_result', '3',  (!empty($license_result->status_result) && in_array($license_result->status_result,[3]) ) ?  true : false, ['class'=>'check check-readonly', 'data-radio'=>'iradio_square-green']) !!} &nbsp;เพิกถอน&nbsp; </label>
    </div>
</div>

@if( !empty($license_result->status_result) && in_array($license_result->status_result,[2]) )
    <div class="form-group" >
        {!! Form::label('date_pause_start', 'วันที่เริ่มพักใช้', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('date_pause_start', !empty($license_result->date_pause_start)?HP::revertDate($license_result->date_pause_start,true):null , ['class' => 'form-control  mydatepicker ', 'id' => 'date_pause_start','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off' , 'disabled' => true ] ) !!}
                <i class="icon-calender"></i>
            </div>
        </div>
        {!! Form::label('date_pause_amount', 'จำนวนวันที่พักใช้', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-2">
            {!! Form::text('date_pause_amount', !empty($license_result->date_pause_amount)?$license_result->date_pause_amount:null , ['class' => 'form-control amount_date', 'id' => 'date_pause_amount', 'autocomplete' => 'off' , 'disabled' => true ] ) !!}
        </div>
    </div>
    <div class="form-group" >
        {!! Form::label('date_pause_end', 'สิ้นสุดพักใช้', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-3">
            <div class="inputWithIcon">
                {!! Form::text('date_pause_end', !empty($license_result->date_pause_end)?HP::revertDate($license_result->date_pause_end,true):null , ['class' => 'form-control ', 'id' => 'date_pause_end','placeholder' => 'วว/ดด/ปปปป', 'disabled' => true ]  ) !!}
                <i class="icon-calender"></i>
            </div>
        </div>
    </div>

@elseif( !empty($license_result->status_result) && in_array($license_result->status_result,[3]) )
    @php
        //เหตุผลเพิกถอน
        $option_revoke_type = App\Models\Tb4\TisiCancelReason::where('status' ,1)->select( DB::raw('reason AS title'), 'id' )->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
    @endphp
    <div class="form-group"  >
        {!! Form::label('', 'วันที่เพิกถอน', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-2">
            <div class="inputWithIcon">
                {!! Form::text('date_revoke', !empty($license_result->date_revoke)?HP::revertDate($license_result->date_revoke,true):null , ['class' => 'form-control mydatepicker  ', 'id' => 'date_revoke','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off' , 'disabled' => true ] ) !!}
                <i class="icon-calender"></i>
            </div>
        </div>
    </div>
    <div class="form-group" >
        {!! Form::label('basic_revoke_type_id', 'เหตุผลเพิกถอน', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-4">
            {!! Form::select('basic_revoke_type_id',$option_revoke_type ,  !empty($license_result->basic_revoke_type_id)?$license_result->basic_revoke_type_id:null,  ['class' => 'form-control ', 'placeholder'=>'- เลือกเหตุผลเพิกถอน -' , 'disabled' => true ]) !!}
        </div>
    </div>
@endif

@php
    $attachs_document = $license_result->FileAttachTo;
@endphp

@if( !empty($attachs_document) )
    <div class="form-group">
        <div class="col-md-offset-4">
            <p class="text-muted"> <i> อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{ str_replace('M','',ini_get('upload_max_filesize')) }} MB  </i></p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('case_number', 'หลักฐานผลการพิจารณา'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <a href="{!! HP::getFileStorage($attachs_document->url) !!}" target="_blank">
                {!! !empty($attachs_document->filename) ? $attachs_document->filename : '' !!}
                {!! HP::FileExtension($attachs_document->url) ?? '' !!}
            </a>
        </div>
    </div>
@endif

<div class="form-group" >
    {!! Form::label('remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('remark',!empty($license_result->remark)?$license_result->remark:'', ['class' => 'form-control remark','id' =>'remark', 'rows'=>'3', 'disabled' => true ]); !!}
    </div>
</div>
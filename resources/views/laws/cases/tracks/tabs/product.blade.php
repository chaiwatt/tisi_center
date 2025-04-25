
@php
    $option_process = App\Models\Law\Basic\LawProcessProduct::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
@endphp

<div class="form-group">
    {!! Form::label('result_process_product_id', 'ดําเนินกํารกับผลิตภัณฑ์'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('result_process_product_id',$option_process, !empty($product_result->result_process_product_id)?$product_result->result_process_product_id:null,  ['class' => 'form-control', 'placeholder'=>'- เลือกดําเนินกํารกับผลิตภัณฑ์ -', 'disabled' => true ]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('result_description', 'โดยวิธีการ'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('result_description', !empty($product_result->result_description)?$product_result->result_description:null , ['class' => 'form-control ','disabled' => true, 'rows' => 4]) !!}
    </div>
</div>

<div class="form-group box_condition_response">
    {!! Form::label('result_start_date', 'วันที่มีคําสั่ง'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        <div class="inputWithIcon">
            {!! Form::text('result_start_date', !empty($product_result->result_start_date)? HP::revertDate($product_result->result_start_date, true) : null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','disabled' => true] ) !!}
            <i class="icon-calender"></i>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('result_amount', 'ดําเนินการภายใน/วัน'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('result_amount',  !empty($product_result->result_amount)?$product_result->result_amount:null , ['class' => 'form-control input_number vertical-spin-amount text-right', 'data-bts-button-down-class' => 'btn btn-default btn-outline', 'data-bts-button-up-class' => 'btn btn-default btn-outline','disabled' => true  ]) !!}
    </div>
</div>

<div class="form-group box_condition_response">
    {!! Form::label('result_end_date', 'วันที่เสร็จสิ้น'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-4">
        <div class="inputWithIcon">
            {!! Form::text('result_end_date', !empty($product_result->result_end_date)? HP::revertDate($product_result->result_end_date, true) : null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','disabled' => true] ) !!}
            <i class="icon-calender"></i>
        </div>
    </div>
</div>

@php
    $file_law_result = $product_result->file_law_result;
@endphp

@if( !empty($file_law_result) )
    <div class="form-group">
        <div class="col-md-offset-4">
            <p class="text-muted"> <i> อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{ str_replace('M','',ini_get('upload_max_filesize')) }} MB  </i></p>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('file_result_tisi', 'หลักฐานคําสั่งคณะกรรมการอุตสาหกรรม (กมอ.)'.' : ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-7">
            <a href="{!! HP::getFileStorage($file_law_result->url) !!}" target="_blank" class="m-l-5">
                {!! !empty($file_law_result->filename) ? $file_law_result->filename : '' !!}
                {!! HP::FileExtension($file_law_result->filename)  ?? '' !!}
            </a>
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('result_remark', 'หมายเหตุ'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('result_remark', !empty($product_result->result_remark)?$product_result->result_remark:null  , ['class' => 'form-control ', 'rows' => 4, 'disabled' => true]) !!}
        {!! $errors->first('result_remark', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@php
    $OpStandard       = App\Models\Certify\Standard::selectRaw('CONCAT(std_full," ",std_title) As title, id')->pluck('title', 'id');
    $OpMethod         = App\Models\Basic\Method::where('state',1)->pluck('title','id');
    $OpIndustryTarget = App\Models\Basic\IndustryTarget::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');//อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต
    $OpIcs            = App\Models\Basic\Ics::selectRaw('CONCAT(code," ",title_en) As title, id')->pluck('title', 'id');
    $OpStandardtype   = App\Models\Bcertify\Standardtype::where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
@endphp

<div class="form-group required {{ $errors->has('std_type') ? 'has-error' : ''}}">
    {!! Form::label('std_type', 'ประเภทมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('std_type', $OpStandardtype , null,  ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกประเภทมาตรฐาน-'] ) !!}

        {!! $errors->first('std_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('format_id') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('format_id', 'รูปแบบ :', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-7">
        <label>{!! Form::radio('format_id', '1',null, ['class'=> "check", 'data-radio'=>'iradio_square-green' ,'required'=>'required']) !!} กำหนดใหม่ &nbsp;&nbsp;</label>
        <label>{!! Form::radio('format_id', '2',null, ['class'=> "check", 'id' => 'format_id-2', 'data-radio'=>'iradio_square-green','required'=>'required']) !!} ทบทวน &nbsp;&nbsp;</label>
    </div>
</div>

<div class="form-group {{ $errors->has('standard_id') ? 'has-error' : ''}}" id="box_std" style="display: none;">
    {!! Html::decode(Form::label('', '', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('standard_id',$OpStandard  , null,['class' => 'form-control', 'id'=>'standard_id', 'placeholder'=>'- เลือกมาตรฐาน -']) !!}
        {!! $errors->first('standard_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_no') ? 'has-error' : ''}}">
    {!! Form::label('std_no', 'เลขมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-2">
        {!! Form::text('std_no', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'ระบุเลขมาตรฐาน'] : ['class' => 'form-control', 'placeholder' => 'ระบุเลขมาตรฐาน']) !!}
        {!! $errors->first('std_no', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        {!! Form::text('std_book', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'เล่ม'] : ['class' => 'form-control', 'placeholder' => 'เล่ม']) !!}
        {!! $errors->first('std_book', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-2">
        {!! Form::select('std_year', HP::Years(), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกปีมาตรฐาน-'] : ['class' => 'form-control', 'placeholder' => '-เลือกปีมาตรฐาน-']) !!}
        {!! $errors->first('std_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_title') ? 'has-error' : ''}}">
    {!! Form::label('std_title', 'ชื่อมาตรฐาน:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('std_title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('std_title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_title_en') ? 'has-error' : ''}}">
    {!! Form::label('std_title_en', 'ชื่อมาตรฐาน (eng):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('std_title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('std_title_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('method_id') ? 'has-error' : ''}}">
    {!! Form::label('method_id', 'วิธีการ:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('method_id', $OpMethod , null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกวิธีการ-'] : ['class' => 'form-control', 'placeholder' => '-เลือกวิธีการ-']) !!}
        {!! $errors->first('method_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group  {{ $errors->has('ref_document') ? 'has-error' : ''}}">
    {!! Form::label('ref_document', 'เอกสารอ้างอิง:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('ref_document', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('ref_document', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('reason') ? 'has-error' : ''}}">
    {!! Form::label('reason', 'เหตุผลเเละความจำเป็น:', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('reason', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('reason', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('confirm_time', null ,  ['class' => 'form-control']) !!}
        {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('industry_target') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('industry_target', 'อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::select('industry_target', $OpIndustryTarget , null,  ['class' => 'form-control', 'placeholder' => '- เลือกอุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต -'  ])  !!}
        {!! $errors->first('industry_target', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('ics') ? 'has-error' : ''}}">
    {!! Form::label('ics', 'ICS :', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::select('ics[]', $OpIcs, !empty($standard_ics)?$standard_ics:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'ics', 'data-placeholder'=>'- เลือก ICS -']) !!}
        {!! $errors->first('ics', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_force') ? 'has-error' : ''}}">
    {!! HTML::decode(Form::label('std_force', 'สถานะมาตรฐาน :', ['class' => 'col-md-3  control-label'])) !!}
    <div class="col-md-7">
        <label>{!! Form::radio('std_force', 'ท',null, ['class'=> "check", 'data-radio'=>'iradio_square-green' ,'required'=>'required']) !!} ทั่วไป &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;</label>
        <label>{!! Form::radio('std_force', 'บ',null, ['class'=> "check", 'data-radio'=>'iradio_square-green', 'required'=>'required']) !!} บังคับ &nbsp;&nbsp;</label>
    </div>
</div>

<div class="form-group required {{ $errors->has('std_abstract') ? 'has-error' : ''}}">
    {!! Form::label('std_abstract', 'บทคัดย่อ (TH):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('std_abstract', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=> '2'] : ['class' => 'form-control', 'rows'=> '2']) !!}
        {!! $errors->first('std_abstract', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_abstract_en') ? 'has-error' : ''}}">
    {!! Form::label('std_abstract_en', 'บทคัดย่อ (EN):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::textarea('std_abstract_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'rows'=> '2'] : ['class' => 'form-control', 'rows'=> '2']) !!}
        {!! $errors->first('std_abstract_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>
{!! Form::hidden('id', null ,  ['class' => 'form-control', 'id' => 'id']) !!}

{!! Form::hidden('step_tap', null ,  ['class' => 'form-control', 'id' => 'step_tap']) !!}

{!! Form::hidden('submit', null ,  ['class' => 'form-control', 'id' => 'standard_pdf']) !!}

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

{{-- {{!empty($standard->status_id)}} --}}

{{-- @php
    if (!empty($standard->status_id)) {
        dd($standard);
    }
@endphp --}}

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

{{-- <div class="form-group required {{ $errors->has('std_no') ? 'has-error' : ''}}">
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
</div> --}}

{{-- {{$standard->set_standard_to->estandard_plan_to->estandard_offers_to->id}} --}}

@php
    $offer = $standard->set_standard_to->estandard_plan_to->estandard_offers_to;
@endphp

<div class="form-group required {{ $errors->has('std_no') ? 'has-error' : ''}}">
    <label for="std_no" class="col-md-3 control-label">เลขมาตรฐาน:</label>
    <div class="col-md-2">
        <input type="text" name="std_no" id="std_no" class="form-control" required="required" placeholder="ระบุเลขมาตรฐาน" value="{{ $offer->iso_number }}">
        @if($errors->has('std_no'))<p class="help-block">{{ $errors->first('std_no') }}</p>@endif
    </div>
    <div class="col-md-2">
        <input type="text" name="std_book" id="std_book" class="form-control" placeholder="เล่ม" value="{{ $standard->std_book }}">
        @if($errors->has('std_book'))<p class="help-block">{{ $errors->first('std_book') }}</p>@endif
    </div>
    <div class="col-md-2">
              {!! Form::select('std_year', HP::Years(), null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกปีมาตรฐาน-'] : ['class' => 'form-control', 'placeholder' => '-เลือกปีมาตรฐาน-']) !!}
        {!! $errors->first('std_year', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required {{ $errors->has('std_title') ? 'has-error' : ''}}">
    <label for="std_title" class="col-md-3 control-label">ชื่อมาตรฐาน:</label>
    <div class="col-md-7">
        <input type="text" name="std_title" id="std_title" class="form-control" required="required" value="{{ $offer->standard_name }}">
        @if($errors->has('std_title'))<p class="help-block">{{ $errors->first('std_title') }}</p>@endif
    </div>
</div>

{{-- <div class="form-group required {{ $errors->has('std_title_en') ? 'has-error' : ''}}">
    {!! Form::label('std_title_en', 'ชื่อมาตรฐาน (eng):', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-7">
        {!! Form::text('std_title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('std_title_en', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}

<div class="form-group required {{ $errors->has('std_title_en') ? 'has-error' : ''}}">
    <label for="std_title_en" class="col-md-3 control-label">ชื่อมาตรฐาน (eng):</label>
    <div class="col-md-7">
        <input type="text" name="std_title_en" id="std_title_en" class="form-control" required="required" value="{{ $offer->standard_name_en }}">
        @if($errors->has('std_title_en'))<p class="help-block">{{ $errors->first('std_title_en') }}</p>@endif
    </div>
</div>

<div class="form-group required {{ $errors->has('method_id') ? 'has-error' : ''}}" hidden>
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

{{-- <div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('confirm_time', 'คณะกรรมการเห็นในการประชุมครั้งที่'.' : ', ['class' => 'col-md-3 control-label'])) !!}
    <div class="col-md-7">
        {!! Form::text('confirm_time', null ,  ['class' => 'form-control']) !!}
        {!! $errors->first('confirm_time', '<p class="help-block">:message</p>') !!}
    </div>
</div> --}}

<div class="form-group {{ $errors->has('confirm_time') ? 'has-error' : ''}}" hidden>
    <label for="confirm_time" class="col-md-3 control-label">คณะกรรมการเห็นในการประชุมครั้งที่ :</label>
    <div class="col-md-7">
        <input type="text" name="confirm_time" id="confirm_time" class="form-control" value="{{ old('confirm_time') }}">
        @if($errors->has('confirm_time'))<p class="help-block">{{ $errors->first('confirm_time') }}</p>@endif
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
        {{-- {!! Form::select('ics[]', $OpIcs, !empty($standard_ics)?$standard_ics:null, ['class' => 'select2-multiple', 'multiple'=>'multiple', 'id'=>'ics', 'data-placeholder'=>'- เลือก ICS -']) !!}
        {!! $errors->first('ics', '<p class="help-block">:message</p>') !!} --}}

        <textarea class="form-control" rows="4" name="ics" cols="50" id="ics">{{ $standard->standardIcs()->ics_text}}</textarea>
    </div>
</div>



{{-- <div class="form-group required {{ $errors->has('std_force') ? 'has-error' : ''}}">
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
</div> --}}

<div class="form-group {{ $errors->has('std_force') ? 'has-error' : ''}}" hidden>
    <label class="col-md-3 control-label">สถานะมาตรฐาน :</label>
    <div class="col-md-7">
        <label>
            <input type="radio" name="std_force" value="ท" class="check" data-radio="iradio_square-green" {{ old('std_force') == 'ท' ? 'checked' : '' }}> ทั่วไป &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
        </label>
        <label>
            <input type="radio" name="std_force" value="บ" class="check" data-radio="iradio_square-green" {{ old('std_force') == 'บ' ? 'checked' : '' }}> บังคับ &nbsp;&nbsp;
        </label>
    </div>
</div>

<div class="form-group {{ $errors->has('std_abstract') ? 'has-error' : ''}}" hidden>
    <label for="std_abstract" class="col-md-3 control-label">บทคัดย่อ (TH):</label>
    <div class="col-md-7">
        <textarea name="std_abstract" id="std_abstract" class="form-control" rows="2">{{ old('std_abstract') }}</textarea>
        @if($errors->has('std_abstract'))<p class="help-block">{{ $errors->first('std_abstract') }}</p>@endif
    </div>
</div>

<div class="form-group {{ $errors->has('std_abstract_en') ? 'has-error' : ''}}" hidden>
    <label for="std_abstract_en" class="col-md-3 control-label">บทคัดย่อ (EN):</label>
    <div class="col-md-7">
        <textarea name="std_abstract_en" id="std_abstract_en" class="form-control" rows="2">{{ old('std_abstract_en') }}</textarea>
        @if($errors->has('std_abstract_en'))<p class="help-block">{{ $errors->first('std_abstract_en') }}</p>@endif
    </div>
</div>

{!! Form::hidden('id', null ,  ['class' => 'form-control', 'id' => 'id']) !!}

{!! Form::hidden('step_tap', null ,  ['class' => 'form-control', 'id' => 'step_tap']) !!}

{!! Form::hidden('submit', null ,  ['class' => 'form-control', 'id' => 'standard_pdf']) !!}

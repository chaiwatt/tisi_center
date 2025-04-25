@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อมาตรฐาน (TH)', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('title_en') ? 'has-error' : ''}}">
    {!! Form::label('title_en', 'ชื่อมาตรฐาน (EN)', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tis_no') ? 'has-error' : ''}}">
    {!! Form::label('tis_no', 'เลขที่ มอก.', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('tis_no', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('tis_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('applicant_type') ? 'has-error' : ''}}">
    {!! Form::label('applicant_type', 'ประเภทผู้ยื่น', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('applicant_type', HP::CertifyApplicantTypes(), null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทผู้ยื่นคำขอ -']) !!}
        {!! $errors->first('applicant_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('condition_th') ? 'has-error' : ''}}">
    {!! Form::label('condition_th', 'ข้อกำหนดมาตรฐาน (TH)', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('condition_th', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('condition_th', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('condition_en') ? 'has-error' : ''}}">
    {!! Form::label('condition_en', 'ข้อกำหนดมาตรฐาน (EN)', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('condition_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('condition_en', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('formula'))
            <a class="btn btn-default" href="{{url('/bcertify/formula')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

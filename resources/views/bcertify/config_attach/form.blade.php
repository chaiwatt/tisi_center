@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'ชื่อเอกสารแนบ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('form') ? 'has-error' : ''}}">
    {!! Form::label('form', 'แบบฟอร์มที่นำไปใช้:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('form[]', HP::Forms(), null, ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- เลือกแบบฟอร์ม -']) !!}
        {!! $errors->first('form', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('essential') ? 'has-error' : ''}}">
  {!! Form::label('essential', 'จำเป็นต้องแนบ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    <label>{!! Form::radio('essential', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} ใช่</label>
    <label>{!! Form::radio('essential', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ไม่</label>
    {!! $errors->first('essential', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
  {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label']) !!}
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
    @can('view-'.str_slug('config_attach'))
    <a class="btn btn-default" href="{{url('/bcertify/config_attach')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('AMPHUR_NAME') ? 'has-error' : ''}}">
  {!! Form::label('AMPHUR_NAME', 'ชื่ออำเภอ', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('AMPHUR_NAME', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('AMPHUR_NAME', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('AMPHUR_CODE') ? 'has-error' : ''}}">
  {!! Form::label('AMPHUR_CODE', 'รหัสอำเภอ', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('AMPHUR_CODE', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('AMPHUR_CODE', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('PROVINCE_ID') ? 'has-error' : ''}}">
  {!! Form::label('PROVINCE_ID', 'รหัสอำเภอ', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('PROVINCE_ID', App\Models\Basic\Province::pluck('PROVINCE_NAME', 'PROVINCE_ID'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกจังหวัด -']) !!}
    {!! $errors->first('PROVINCE_ID', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('POSTCODE') ? 'has-error' : ''}}">
  {!! Form::label('POSTCODE', 'รหัสไปรษณีย์', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('POSTCODE', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('POSTCODE', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('amphur'))
    <a class="btn btn-default" href="{{url('/basic/amphur')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

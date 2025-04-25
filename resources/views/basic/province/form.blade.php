@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('PROVINCE_NAME') ? 'has-error' : ''}}">
  {!! Form::label('PROVINCE_NAME', 'ชื่อจังหวัด', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('PROVINCE_NAME', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('PROVINCE_NAME', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('PROVINCE_CODE') ? 'has-error' : ''}}">
  {!! Form::label('PROVINCE_CODE', 'รหัสจังหวัด', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('PROVINCE_CODE', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('PROVINCE_CODE', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('GEO_ID') ? 'has-error' : ''}}">
  {!! Form::label('GEO_ID', 'ภาค', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('GEO_ID', App\Models\Basic\Geography::pluck('GEO_NAME', 'GEO_ID'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกภาค -']) !!}
    {!! $errors->first('GEO_ID', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('province'))
    <a class="btn btn-default" href="{{url('/basic/province')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

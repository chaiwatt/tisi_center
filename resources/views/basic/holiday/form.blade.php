@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'ชื่อวันหยุด', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('title_en') ? 'has-error' : ''}}">
  {!! Form::label('title_en', 'ชื่อวันหยุด EN', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title_en', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title_en', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group required{{ $errors->has('holiday_date') ? 'has-error' : ''}}">
    {!! Form::label('holiday_date', 'วันที่หยุด', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="inputWithIcon">
            {!! Form::text('holiday_date', null, ['class' => 'form-control mydatepicker  text-center', 'required'=>'required','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
            <i class="icon-calender"></i>
        </div>
    </div>
</div>
  
<div class="form-group {{ $errors->has('fis_year') ? 'has-error' : ''}}">
  {!! Form::label('fis_year', 'ปี', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('fis_year', HP::YearRange(date('Y')-2,5), null, ['class' => 'form-control ',  'placeholder'=>'- เลือกปี -','required' => true ]) !!}
    {!! $errors->first('fis_year', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('basic-holiday'))
    <a class="btn btn-default" href="{{url('/basic/holiday')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

<script type="text/javascript">

  $(document).ready(function($) {

    //ปฎิทิน
    $('.mydatepicker').datepicker({
        autoclose: true,
        toggleActive: true,
        language:'th-th',
        format: 'dd/mm/yyyy',
    });

  });
</script>

@endpush

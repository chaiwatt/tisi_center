@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('DISTRICT_CODE') ? 'has-error' : ''}}">
  {!! Form::label('DISTRICT_CODE', 'รหัสตำบล', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('DISTRICT_CODE', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('DISTRICT_CODE', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('DISTRICT_NAME') ? 'has-error' : ''}}">
  {!! Form::label('DISTRICT_NAME', 'ชื่อตำบล', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('DISTRICT_NAME', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('DISTRICT_NAME', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('PROVINCE_ID') ? 'has-error' : ''}}">
  {!! Form::label('PROVINCE_ID', 'จังหวัด', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('PROVINCE_ID', App\Models\Basic\Province::pluck('PROVINCE_NAME', 'PROVINCE_ID'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกจังหวัด -']) !!}
    {!! $errors->first('PROVINCE_ID', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('AMPHUR_ID') ? 'has-error' : ''}}">
  {!! Form::label('AMPHUR_ID', 'อำเภอ/เขต', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('AMPHUR_ID', $amphurs, null, ['class' => 'form-control', 'placeholder'=>'- เลือกอำเภอ -']) !!}
    {!! $errors->first('AMPHUR_ID', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('district'))
    <a class="btn btn-default" href="{{url('/basic/district')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script type="text/javascript">

  jQuery(document).ready(function($) {

    //เมื่อเลือกจังหวัด
    $('#PROVINCE_ID').change(function () {

      $('#AMPHUR_ID').children(":not([value=''])").remove();

      var url = '{{ url('basic/amphur/list') }}/'+$(this).val();
      $.ajax({
        'type': 'GET',
        'url': url,
        'success': function (datas) {

            $.each(datas, function(index, data) {
              $('#AMPHUR_ID').append('<option value="'+index+'">'+data+'</option>');
            });

        }
      });

    });

  });
</script>
@endpush

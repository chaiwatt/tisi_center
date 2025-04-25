@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />

@endpush

<div class="form-group {{ $errors->has('certification_branch_id') ? 'has-error' : ''}}">
  {!! Form::label('certification_branch_id', 'สาขาการรับรอง:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('certification_branch_id', App\Models\Bcertify\CertificationBranch::pluck('title', 'id'), null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกสาขาการรับรอง-']) !!}
    {!! $errors->first('certification_branch_id', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('scope_type') ? 'has-error' : ''}}">
  {!! Form::label('scope_type', 'ขอบข่ายการรับรอง:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('scope_type', HP::Scopes(), null, ['class' => 'form-control', 'required' => 'required', 'placeholder'=>'-เลือกขอบข่ายการรับรอง-']) !!}
    {!! $errors->first('scope_type', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
  {!! Form::label('start_date', 'วันที่เริ่มใช้:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('start_date', null, ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
    {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
  {!! Form::label('end_date', 'วันที่สิ้นสุด:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('end_date', null, ['class' => 'form-control mydatepicker']) !!}
    {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('certification_scope'))
    <a class="btn btn-default" href="{{url('/bcertify/certification_scope')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

<!-- input calendar -->
<script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {

    //ปฎิทิน
    $('.mydatepicker').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });

  });
</script>

@endpush

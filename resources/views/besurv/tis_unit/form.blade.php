@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('tb3_TisThainame') ? 'has-error' : ''}}">
  {!! Form::label('tb3_TisThainame', 'ชื่อมาตรฐาน:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('tb3_TisThainame', null, ['class' => 'form-control', 'disabled'=>'disabled']) !!}
    {!! $errors->first('tb3_TisThainame', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('id_unit') ? 'has-error' : ''}}">
  {!! Form::label('id_unit', 'หน่วยนับ:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('id_unit', App\Models\Basic\UnitCode::pluck('name_unit', 'id_unit'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกหน่วยนับ-']) !!}
    {!! $errors->first('id_unit', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group">
  <div class="col-md-offset-4 col-md-4">

    <button class="btn btn-primary" type="submit">
      <i class="fa fa-paper-plane"></i> บันทึก
    </button>
    @can('view-'.str_slug('tis_unit'))
    <a class="btn btn-default" href="{{url('/besurv/tis_unit')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

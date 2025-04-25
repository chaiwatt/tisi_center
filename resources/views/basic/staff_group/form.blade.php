@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

@endpush

<div class="form-group {{ $errors->has('order') ? 'has-error' : ''}}">
  {!! Form::label('order', 'กลุ่มที่', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('order', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'ชื่อกลุ่ม', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
  {!! Form::label('product_group_id', 'กลุ่มผลิตภัณฑ์/สาขา', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::select('product_group_id[]', App\Models\Basic\ProductGroup::pluck('title', 'id'), null, ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -']) !!}
    {!! $errors->first('product_group_id', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('staff_group'))
    <a class="btn btn-default" href="{{url('/basic/staff_group')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

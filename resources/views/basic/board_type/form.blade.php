@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required {{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'ชื่อประเภทของคณะกรรมการ', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
    {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>
<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
  {!! Form::label('title', 'กลุ่มประเภทคณะกรรมการ', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-6">
          {!! Form::select('expert_group_id', 
                 App\Models\Basic\ExpertGroup::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                   null,
               ['class' => 'form-control',
               'required'=>true,
                 'id'=>'expert_group_id',
               'placeholder'=>'-เลือกกลุ่มประเภทคณะกรรมการ-'])
       !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
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
    @can('view-'.str_slug('board_type'))
    <a class="btn btn-default" href="{{url('/basic/board_type')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

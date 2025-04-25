@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'เลข มอก. :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group required{{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'รายละเอียด :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'required' => 'required', 'rows' => 3]) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group required{{ $errors->has('standard_type[]') ? 'has-error' : ''}}">
    {!! Form::label('standard_type[]', 'ประเภทมาตรฐาน :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('standard_type[]', ['1' => 'IB', '2' => 'CB'], null, ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- ประเภทมาตรฐาน -', 'required' => 'required']) !!}
        {!! $errors->first('standard_type[]', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ :', ['class' => 'col-md-4 control-label']) !!}
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
        @can('view-'.str_slug('bsection5-standard'))
            <a class="btn btn-default" href="{{url('/bsection5/standards')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

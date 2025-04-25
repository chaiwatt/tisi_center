@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'รายการ :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('lab') ? 'has-error' : ''}}">
    {!! Form::label('lab', 'กลุ่มหน่วยงาน :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::checkbox('lab', '1', 
          isset($costestimation) && ($costestimation->lab == 1)  ? true :false, 
        ['class'=>'check lab','data-checkbox'=>"icheckbox_flat-red"]) !!}
        <label for="lab"> &nbsp;LAB</label>
        {!! $errors->first('lab', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('ib') ? 'has-error' : ''}}">
    <div class="col-md-4"></div>
    <div class="col-md-6">
        {!! Form::checkbox('ib', '1', 
        isset($costestimation) && ($costestimation->ib == 1)  ? true :false, 
        ['class'=>'check ib','data-checkbox'=>"icheckbox_flat-red"]) !!}
        <label for="lab"> &nbsp;IB</label>
        {!! $errors->first('ib', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('cb') ? 'has-error' : ''}}">
    <div class="col-md-4"></div>
    <div class="col-md-6">
        {!! Form::checkbox('cb', '1', 
        isset($costestimation) && ($costestimation->cb == 1)  ? true :false, 
        ['class'=>'check cb','data-checkbox'=>"icheckbox_flat-red"]) !!}
        <label for="lab"> &nbsp;CB</label>
        {!! $errors->first('cb', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('costestimation'))
            <a class="btn btn-default" href="{{url('/certify/Cost-Estimation')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush

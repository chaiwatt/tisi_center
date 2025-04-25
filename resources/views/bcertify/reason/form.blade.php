@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'เหตุผลและความจำเป็น'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control', 'maxlength' => '255', 'required' => true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group{{ $errors->has('condition') ? 'has-error' : ''}}">
    {!! Form::label('condition', 'เงื่อนไขอ้างอิง '.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {{ Form::checkbox('condition', '1', isset($reason->condition) && $reason->condition != 1 ? false : true , ['class'=>'switch']) }}
    </div>
</div>

<div class="form-group{{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ '.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {{ Form::checkbox('state', '1',  isset($reason->state) && $reason->state != 1 ? false : true  , ['class'=>'switch']) }}
    </div>
</div>

<div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
    {!! Form::label('created_by', 'ผู้บันทึก'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6" style="padding-top:7px;">
         {!! !empty($reason->user_created->FullName)?$reason->user_created->FullName:auth()->user()->Fullname !!}
    </div>
</div>
<div class="form-group {{ $errors->has('updated_by') ? 'has-error' : ''}}">
    {!! Form::label('updated_by', 'วันทึกบันทึก'.' : ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 " style="padding-top:7px;">
       {!! !empty($reason->created_at)?HP::DateTimeFullThai( $reason->created_at):HP::DateTimeFullThai(date('Y-m-d H:i:s')) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('bcertify-reason'))
            <a class="btn btn-default" href="{{url('/bcertify/reason')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

  <script>
    $(document).ready(function () {
        $(".switch").each(function() {
            new Switchery($(this)[0], {
                color: '#13dafe'
            })
        });
    });
    </script>
@endpush

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'วาระการประชุม'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null,  ['class' => 'form-control','required'=>true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
          <div class="checkbox">
            {!! Form::checkbox('state', '1', !empty($meetingtype->state) && $meetingtype->state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#449d44']) !!}
           </div>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
    {!! Form::label('created_by', 'ผู้สร้าง'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!!  !empty($meetingtype->CreatedName)  ?  $meetingtype->CreatedName  : auth()->user()->FullName !!}
        {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('created_at', 'วันที่สร้าง'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!!  !empty($meetingtype->created_at)  ?  HP::DateTimeFullThai($meetingtype->created_at)  :  HP::DateTimeFullThai(date('Y-m-d H:i:s')) !!}
        {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('meetingtypes'))
            <a class="btn btn-default" href="{{url('/bcertify/meetingtypes')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </div>
</div>

@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script>
    $(document).ready(function () {
        $(".js-switch").each(function() {
           new Switchery($(this)[0], { size: 'small' });
        });


    });

</script>


@endpush

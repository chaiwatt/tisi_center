@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush


<div class="form-group required{{ $errors->has('standard_code') ? 'has-error' : ''}}">
    {!! Form::label('standard_code', 'รหัสประเภทมาตรฐาน'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('standard_code', null,  ['class' => 'form-control','required'=>true]) !!}
        {!! $errors->first('standard_code', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ประเภทมาตรฐาน'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null,  ['class' => 'form-control','required'=>true]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('offertype') ? 'has-error' : ''}}">
    {!! Form::label('offertype', 'ประเภทข้อเสนอ'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('offertype', null,  ['class' => 'form-control','required'=>true]) !!}
        {!! $errors->first('offertype', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('offertype_eng') ? 'has-error' : ''}}">
    {!! Form::label('offertype_eng', 'ประเภทข้อเสนอ (Eng)'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('offertype_eng', null,  ['class' => 'form-control','required'=>false]) !!}
        {!! $errors->first('offertype_eng', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group required{{ $errors->has('department_id') ? 'has-error' : ''}}">
    {!! Form::label('department_id', 'กลุ่มผู้ใช้งานที่รับผิดชอบ'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('department_id',
        App\Models\Besurv\Department::orderbyRaw('CONVERT(depart_name USING tis620)')->pluck('depart_name', 'did'), 
        null, 
        ['class' => 'form-control', 
        'id'=>'filter_department',
        'required'=>false,
        'placeholder' => '-- เลือกกลุ่มผู้ใช้งานที่รับผิดชอบ --']); !!}
        {!! $errors->first('department_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('state', 'สถานะ'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
          <div class="checkbox">
            {!! Form::checkbox('state', '1', !empty($standardtype->state) && $standardtype->state == '1' ?  true : false  , ['class' => 'js-switch', 'data-color'=>'#449d44']) !!}
           </div>
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
    {!! Form::label('created_by', 'ผู้สร้าง'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!!  !empty($standardtype->CreatedName)  ?  $standardtype->CreatedName  : auth()->user()->FullName !!}
        {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Form::label('created_at', 'วันที่สร้าง'.' :', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!!  !empty($standardtype->created_at)  ?  HP::DateTimeFullThai($standardtype->created_at)  :  HP::DateTimeFullThai(date('Y-m-d H:i:s')) !!}
        {!! $errors->first('created_at', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
          <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('standardtypes'))
            <a class="btn btn-default" href="{{url('/bcertify/standardtypes')}}">
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

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'ชื่อความเชี่ยวชาญ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group required{{ $errors->has('department_id[]') ? 'has-error' : ''}}">
    {!! Form::label('department_id[]', 'หน่วยงานที่เกียวข้อง', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
      {!! Form::select('department_id[]', App\Models\Besurv\Department::pluck('depart_name', 'did')->all(), !empty($expertgroup->ExpertDepartmentId)?$expertgroup->ExpertDepartmentId:[], ['class' => 'select2-multiple', 'multiple' => 'multiple', 'data-placeholder'=>'- เลือกประเภทหน่วยงาน -', 'required' => true]) !!}
      {!! $errors->first('department_id[]', '<p class="help-block">:message</p>') !!}
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
<div class="form-group {{ $errors->has('created_by') ? 'has-error' : ''}}">
    {!! Form::label('created_by', 'ผู้บันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6" style="padding-top:7px;">
         {!! !empty($expertgroup->user_created->FullName)?$expertgroup->user_created->FullName:auth()->user()->Fullname !!}
    </div>
</div>
<div class="form-group {{ $errors->has('updated_by') ? 'has-error' : ''}}">
    {!! Form::label('updated_by', 'วันทึกบันทึก', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6 " style="padding-top:7px;">
       {!! !empty($expertgroup->created_at)?HP::DateTimeFullThai( $expertgroup->created_at):HP::DateTimeFullThai(date('Y-m-d H:i:s')) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('expertgroups'))
        <a class="btn btn-default" href="{{url('/basic/expert-groups')}}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
        @endcan
    </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
@endpush
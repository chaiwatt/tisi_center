

<fieldset class="white-box">
    <legend class="legend"><h3>ข้อมูลกลุ่มผู้ใช้งาน</h3></legend>

    <div class="form-group required{{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Form::label('name', 'ชื่อกลุ่มผู้ใช้งาน', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            {!! Form::text('name', !empty( $role->name)? $role->name:null , ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    {!! Form::hidden('level', !empty( $role->level)? $role->level:3 , ['class' => 'form-control']) !!}

    @if( isset($role->id) )
        <div class="form-group">
            <label for="label" class="col-sm-3 control-label">ส่วนการควบคุม</label>
            <div class="col-sm-7">
                <div class="input-group">
                <input type="text" class="form-control" value="{{ $role->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ' }}" disabled="disabled">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                </div>
            </div>
        </div>
    @else
        <div class="form-group required{{ $errors->has('label') ? 'has-error' : ''}}">
            {!! Form::label('label', 'ส่วนการควบคุม', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-7">
                {!! Form::select('label',['staff' => 'เจ้าหน้าที่' , 'trader' => 'ผู้ประกอบการ'], null, ['class' => 'form-control']) !!}
                {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    @endif

    @php
        $setting_system = App\RoleSettingGroup::where('state', 1)->where('displays',1)->pluck('title', 'id');
        $system_ids = null;
        if( isset($role) && !empty($role) && !empty($role->id)  ){
            $system_ids = App\RoleGroup::where('role_id', $role->id )->pluck('setting_systems_id', 'setting_systems_id')->toArray();
        }
    @endphp

    <div class="form-group {{ $errors->has('group') ? 'has-error' : ''}}">
        {!! Form::label('group', 'ระบบงาน', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            {!! Form::select('group[]', $setting_system, (is_array($system_ids) ? $system_ids : null) , ['class' => 'setting_system_multiple', 'multiple' => 'multiple', 'data-placeholder' => '-เลือกระบบงาน-', ]) !!}
            {!! $errors->first('group', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group{{ $errors->has('description') ? 'has-error' : ''}}">
        {!! Form::label('description', 'คำอธิบาย', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            {!! Form::textarea('description', !empty($role->description)?$role->description:null , ['class' => 'form-control', 'rows'=>'3' ]) !!}
            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required{{ $errors->has('state') ? 'has-error' : ''}}">
        {!! Form::label('state', 'สถานะ', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            <label>{!! Form::radio('state', '1', isset($role->state) && $role->state == 1?true:true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'required' => 'required']) !!} เปิด</label>
            <label>{!! Form::radio('state', '0', isset($role->state) && $role->state == 0?true:false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>
            {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
        </div>
    </div>

</fieldset>

@if( isset($role->id) )
    <div class="panel panel-info block4">
        <div class="panel-heading">รายการสิทธิ์การใช้งาน
            <div class="pull-right">
                <a href="#" data-perform="panel-collapse"><i class="ti-plus"></i></a> 
            </div>
        </div>
        <div class="panel-wrapper collapse in" aria-expanded="true">
            <div class="panel-body">

                @if($role->label=='staff')
                
                    @include('role.form.table_staff')
                @elseif($role->label=='trader')
                    @include('role.form.table_trader')
                @endif

            </div>
        </div>
    </div>
@endif

<div class="form-group m-b-0">
    <div class="col-md-12 text-center">
        <button class="btn btn-primary waves-effect waves-light m-t-10" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        <a href="{{ url('role-management') }}" class="btn btn-default waves-effect waves-light m-t-10">
            <i class="fa fa-rotate-left"></i> ยกเลิก
        </a>
    </div>
</div>
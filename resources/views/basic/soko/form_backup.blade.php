@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

<div class="col-md-7">
  <div class="white-box">

    <h3 class="box-title m-b-0">แก้ไขข้อมูลผู้ใช้งาน (สก.)</h3>
    <p class="text-muted m-b-30 font-13"> แก้ไขข้อมูลผู้ประกอบการผู้ใช้งาน (สก.)</p>

    <div class="form-group {{ $errors->has('trader_type') ? 'has-error' : ''}}">
      {!! Form::label('trader_type', 'ประเภท:', ['class' => 'col-md-5 control-label required']) !!}
      <div class="col-md-6">
        {!! Form::select('trader_type', ['นิติบุคคล' => 'นิติบุคคล', 'บุคคลธรรมดา' => 'บุคคลธรรมดา'], null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => '-เลือกประเภท-']) !!}
        {!! $errors->first('trader_type', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group {{ $errors->has('trader_operater_name') ? 'has-error' : ''}}">
      {!! Form::label('trader_operater_name', 'ชื่อผู้ประกอบการ:', ['class' => 'col-md-5 control-label required']) !!}
      <div class="col-md-6">
        {!! Form::text('trader_operater_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('trader_operater_name', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group {{ $errors->has('trader_id') ? 'has-error' : ''}}">
      {!! Form::label('trader_id', 'เลขประจำตัวผู้เสียภาษี:', ['class' => 'col-md-5 control-label required']) !!}
      <div class="col-md-6">
        {!! Form::text('trader_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('trader_id', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group {{ $errors->has('trader_id_register') ? 'has-error' : ''}}">
      {!! Form::label('trader_id_register', 'วันที่จดทะเบียนนิติบุคคล:', ['class' => 'col-md-5 control-label required']) !!}
      <div class="col-md-6">
        {!! Form::text('trader_id_register', null, ['class' => 'form-control mydatepicker']) !!}
        {!! $errors->first('trader_id_register', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

    <div class="form-group {{ $errors->has('agent_email') ? 'has-error' : ''}}">
      {!! Form::label('agent_email', 'อีเมล:', ['class' => 'col-md-5 control-label required']) !!}
      <div class="col-md-6">
        {!! Form::text('agent_email', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('agent_email', '<p class="help-block">:message</p>') !!}
      </div>
    </div>

  </div>
</div>

<div class="col-md-5">

  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title m-b-0">กลุ่มผู้ใช้งาน</h3>
      <p class="text-muted m-b-30 font-13"> จัดการกลุ่มผู้ใช้งาน </p>

        <div class="form-group">
          {!! Form::label('roles', ' ', ['class' => 'col-sm-3 control-label']) !!}
          <div class="col-sm-9">

            @foreach ($roles as $role)
              @if($role->label!='trader')
                @continue
              @endif
              <div class="checkbox checkbox-success">
                  {!! Form::checkbox('roles[]', $role->id, in_array($role->id, $trader_roles), ['class' => 'form-control']) !!}
                  <label for="roles">&nbsp;{{ $role->name }}</label>
              </div>
            @endforeach

          </div>
        </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="white-box">
      <h3 class="box-title m-b-0">เปลี่ยนรหัสผ่าน</h3>
      <p class="text-muted m-b-30 font-13"> ถ้าไม่เปลียนปล่อยว่างไว้ </p>

      <div class="form-group">
        {!! Form::label('password', 'รหัสผ่าน:', ['class' => 'col-sm-5 control-label']) !!}
        <div class="col-sm-7">
          {!! Form::password('password', ['class' => 'form-control']) !!}
          {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="col-md-offset-4 col-md-4">

    <button class="btn btn-primary" type="submit">
      <i class="fa fa-paper-plane"></i> บันทึก
    </button>
    @can('view-'.str_slug('soko'))
    <a class="btn btn-default" href="{{url('/basic/soko')}}">
      <i class="fa fa-rotate-left"></i> ยกเลิก
    </a>
    @endcan
  </div>
</div>

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<!-- input calendar -->
<script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {

    //ปฎิทิน
    $('.mydatepicker').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });

  });
</script>
@endpush

@extends('layouts.master')

@push('css')
<style type="text/css">

</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="row">

    {!! Form::model($user, [
        'method' => 'POST',
        'url' => 'account-settings',
        'class' => 'form-horizontal',
        'files' => false
    ]) !!}

    <div class="col-md-6">
        <div class="white-box">
            <h3 class="box-title m-b-0">ตั้งค่าบัญชีผู้ใช้</h3>
            <p class="text-muted m-b-20 font-13 description-password">&nbsp;</p>

              <div class="form-group">
                {!! Form::label('reg_13ID', 'เลขประจำตัวประชาชน:', ['class' => 'col-sm-5 control-label required']) !!}
                <div class="col-sm-7">
                  {!! Form::text('reg_13ID', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  {!! $errors->first('reg_13ID', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

              <div class="form-group">
                {!! Form::label('reg_fname', 'ชื่อ:', ['class' => 'col-sm-5 control-label required']) !!}
                <div class="col-sm-7">
                  {!! Form::text('reg_fname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  {!! $errors->first('reg_fname', '<p class="help-block">:message</p>') !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('reg_lname', 'สกุล:', ['class' => 'col-sm-5 control-label required']) !!}
                <div class="col-sm-7">
                  {!! Form::text('reg_lname', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  {!! $errors->first('reg_lname', '<p class="help-block">:message</p>') !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('reg_email', 'อีเมล (ชื่อผู้ใช้งาน):', ['class' => 'col-sm-5 control-label required']) !!}
                <div class="col-sm-7">
                  {!! Form::email('reg_email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  {!! $errors->first('reg_email', '<p class="help-block">:message</p>') !!}
                  <p class="text-danger m-b-0" id="reg_email_error"></p>

                    <span class="hide">
                        <input type="text" name="reg_email_error" />
                    </span>
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('reg_phone', 'เบอร์มือถือ:', ['class' => 'col-sm-5 control-label required']) !!}
                <div class="col-sm-7">
                  {!! Form::text('reg_phone', null, ['class' => 'form-control', 'maxlength' => 12, 'required'=>'required']) !!}
                  {!! $errors->first('reg_phone', '<p class="help-block">:message</p>') !!}
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('reg_wphone', 'เบอร์ที่ทำงาน:', ['class' => 'col-sm-5 control-label required']) !!}
                <div class="col-sm-7">
                  {!! Form::text('reg_wphone', null, ['class' => 'form-control', 'maxlength' => 11, 'required'=>'required']) !!}
                  {!! $errors->first('reg_wphone', '<p class="help-block">:message</p>') !!}
                </div>
              </div>

        </div>
    </div>

    <div class="col-md-6">
        <div class="white-box">
            <h3 class="box-title m-b-0">รหัสผ่านสำหรับลงชื่อเข้าใช้งาน</h3>
            <p class="text-muted m-b-20 font-13 description-password"> ถ้าไม่เปลี่ยนปล่อยว่างไว้ </p>

            <div class="form-group">
                {!! Form::label('password', 'รหัสผ่าน:', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
      <div class="text-center">
          <button class="btn btn-primary waves-effect waves-light" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
          </button>
          <a class="btn btn-default waves-effect waves-light" href="{{ url('profile') }}">
            <i class="fa fa-rotate-left"></i> ยกเลิก
          </a>
      </div>
    </div>

    {!! Form::close() !!}

    @if(count($errors) > 0)
        <div class="alert alert-danger">โปรดกรอกข้อมูลให้ครบถ้วนสมบูรณ์</div>
    @endif

  </div>
</div>

@push('js')

<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

<script type="text/javascript">

    $(document).ready(function() {

        @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
        @endif

        @if(!isset($user))
            $('label[for="password"]').addClass('required');
            $('#password').prop('required', true);
            $('.description-password').text('');
        @endif

        $('#reg_email').blur(function(event) {
            check_user();
        });
    });

    function check_user(){
        var id = '{{ isset($user) ? $user->getKey() : '' }}';
        $.ajax('{!! url('user/check_email_repeat/') !!}/'+$('#reg_email').val() + '/' + id)
         .done(function(res) {
            if(res.hasOwnProperty('result')){
                if(res.result==true){//ซ้ำ
                    $('#reg_email_error').text('อีเมลนี้มีในระบบแล้ว');
                    $('input[name="reg_email_error"]').prop('required', true);
                }else{//ไม่ซ้ำ
                    $('#reg_email_error').text('');
                    $('input[name="reg_email_error"]').prop('required', false);
                }
            }
        });
    }


</script>

@endpush

@endsection

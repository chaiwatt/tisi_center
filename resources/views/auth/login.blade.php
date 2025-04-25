@extends('layouts.app')

@section('content')
<style>
    .input-login{
        border-bottom: 1px solid black !important;
    }
</style>
<section id="wrapper" class="login-register">
    <div class="login-box">
        <div class="white-box">

            {{-- <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <b>แจ้งปิดปรับปรุงระบบ : </b><br>วันอาทิตย์ ที่ 26 กุมภาพันธ์ พ.ศ. 2566 เวลา 18.00 น. <u>ถึง</u> วันจันทร์ ที่ 27 กุมภาพันธ์ พ.ศ. 2566 เวลา 08.00 น.
            </div> --}}
            @if (!empty(config('app.login_message_notice')))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <b class="text-dark">{{ config('app.login_message_notice') }}</b>
                </div>
            @endif

            <form class="form-horizontal form-material" id="loginform" method="post" action="{{ route('login') }}">
                {{csrf_field()}}
                <h4 class="box-title font-20 m-b-20">&nbsp;เข้าสู่ระบบ สำหรับเจ้าหน้าที่ สมอ. (SSO)</h4>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input id="email" placeholder="อีเมล์" class="form-control input-login {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->first())
                            <span class="text-danger">
                                {{ $errors->first() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input id="password" type="password" class="form-control input-login {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="รหัสผ่าน">
                        @if ($errors->has('password'))
                            <span class="text-danger">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary pull-left p-t-0">
                            <input type="checkbox" id="checkbox-signup" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="checkbox-signup"> จำการเข้าระบบ </label>
                        </div>
                        <a href="{{ route('password.request') }}" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> ลืมรหัสผ่าน?</a> </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit"> ลงชื่อ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

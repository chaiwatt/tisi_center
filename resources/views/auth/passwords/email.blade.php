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
                <form class="form-horizontal form-material" method="POST"  action="{{ route('password.email') }}">
                    @csrf
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3 class="m-b-0">{{ __('รีเซตรหัสผ่าน') }}</h3>
                            <p class="text-muted m-b-0 font-12">กรอกอีเมลที่ได้ลงทะเบียนไว้กับระบบ </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">

                            <input id="email" placeholder="อีเมล" type="email" class="form-control input-login {{ $errors->has('email') ? ' is-invalid' : '' }}" name="reg_email" value="{{ old('reg_email') }}" required>

                            @if ($errors->has('email'))
                                <span class="text-danger">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-6">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">รีเซต</button>
                        </div>
                        <div class="col-xs-6">
                            <a href="{{ url('login') }}" class="btn btn-default btn-lg btn-block text-uppercase waves-effect waves-light" type="button">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

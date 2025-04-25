@extends('layouts.app')

@section('content')

    <section id="wrapper" class="login-register">
        <div class="login-box">
            <div class="white-box">
                <form class="form-horizontal form-material" method="post" action="{{ route('password.request') }}">
                    {{csrf_field()}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>{{ __('Reset Password') }}</h3>
                        </div>
                    </div>

                    <div class="form-group ">

                        <div class="col-xs-12">
                            <input placeholder="อีเมล" id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="reg_email"
                                   value="{{ $reg_email ?: old('email') }}" required autofocus>

                            @if ($errors->has('reg_email'))
                                <span class="text-danger">
                                    {{ $errors->first('reg_email') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="password" placeholder="รหัสผ่าน" type="password"
                                   class="form-control{{ $errors->has('reg_pword') ? ' is-invalid' : '' }}"
                                   name="reg_pword" required>

                            @if ($errors->has('reg_pword'))
                                <span class="text-danger">
                                        {{ $errors->first('reg_pword') }}
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input placeholder="ยืนยันรหัสผ่าน" id="password-confirm" type="password" class="form-control"
                                   name="reg_pword_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                    type="submit">รีเซต
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

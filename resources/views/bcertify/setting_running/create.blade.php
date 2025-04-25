@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่าเลขรัน (New)</h3>
                    @can('view-'.str_slug('bcertify_setting_running'))
                        <a class="btn btn-success pull-right" href="{{url('/bcertify/setting_running')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::open(['url' => '/bcertify/setting_running', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('bcertify.setting_running.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

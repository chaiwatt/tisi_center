@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขผู้ใช้งานเว็บเซอร์วิส #{{ $web_service->id }}</h3>
                    @can('view-'.str_slug('web_service'))
                        <a class="btn btn-success pull-right" href="{{ url('/ws/web_service') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
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

                    {!! Form::model($web_service, [
                        'method' => 'PATCH',
                        'url' => ['/ws/web_service', $web_service->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('ws.web_service.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

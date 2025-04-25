@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบนำส่งใบรับรองระบบงาน #{{ $sendcertificate->id }}</h3>
                    @can('view-'.str_slug('sendcertificates'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/send-certificates') }}">
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

                    {!! Form::model($sendcertificate, [
                        'method' => 'PATCH',
                        'url' => ['/certify/send-certificates', $sendcertificate->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'form-send-certificates'
                    ]) !!}

                    @include ('certify.send-certificates.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

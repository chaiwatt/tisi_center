@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบนำส่งใบรับรองระบบงาน</h3>
                    @can('view-'.str_slug('sendcertificates'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/send-certificates')}}">
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

                    {!! Form::open(['url' => '/certify/send-certificates','id'=>'form-send-certificates',  'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('certify.send-certificates.form')

                    {!! Form::close() !!}

                    {{-- <form action="/certify/send-certificates" method="POST" id="form-send-certificates" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        @include('certify.send-certificates.form')
                    </form>
                     --}}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มreceive_quality_control</h3>
                    @can('view-'.str_slug('receive_quality_control'))
                        <a class="btn btn-success pull-right" href="{{url('/esurv/receive_quality_control')}}">
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

                    {!! Form::open(['url' => '/esurv/receive_quality_control', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('esurv.receive_quality_control.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

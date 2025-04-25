@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดทำมาตรฐานรับรอง</h3>
                    @can('view-'.str_slug('certifystandard'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/standards')}}">
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

                    {!! Form::open(['url' => '/certify/standards', 'class' => 'form-horizontal', 'files' => true, 'id' => "standard_form", 'target' => "_blank"]) !!}

                    @include ('certify/standards.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

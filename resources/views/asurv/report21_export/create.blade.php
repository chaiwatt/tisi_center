@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่ม report21_export</h3>
                    @can('view-'.str_slug('report21_export'))
                        <a class="btn btn-success pull-right" href="{{url('/report21_export/report21_export')}}">
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

                    {!! Form::open(['url' => '/report21_export/report21_export', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('asurv.report21_export.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

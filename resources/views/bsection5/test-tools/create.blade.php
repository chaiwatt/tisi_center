@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มเครื่องมือทดสอบ</h3>
                    @can('view-'.str_slug('testtools'))
                        <a class="btn btn-success pull-right" href="{{url('/bsection5/test_tools')}}">
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

                    {!! Form::open(['url' => '/bsection5/test_tools', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('bsection5.test-tools.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

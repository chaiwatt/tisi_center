@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มหมวดหมู่ระบบงานหลัก</h3>
                    @can('view-'.str_slug('law-config-system-category'))
                        <a class="btn btn-default pull-right" href="{{url('/law/config/system-category')}}">
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

                    {!! Form::open(['url' => '/law/config/system-category', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('laws.config.system-category.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

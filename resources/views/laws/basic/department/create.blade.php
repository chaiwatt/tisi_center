@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">เพิ่มหน่วยงานต้นเรื่อง</h3>
                    @can('view-'.str_slug('law-departments'))
                        <a class="btn btn-default pull-right" href="{{url('/law/basic/department')}}">
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

                    {!! Form::open(['url' => '/law/basic/department', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('laws.basic.department.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

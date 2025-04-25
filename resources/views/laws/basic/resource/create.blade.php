@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">เพิ่มแหล่งที่มาของราคาผลิตภัณฑ์</h3>
                    @can('view-'.str_slug('law-resource'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/basic/resource') }}">
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

                    {!! Form::open(['url' => '/law/basic/resource', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('laws.basic.resource.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขหน่วยงานตรวจ #{{ $inspector->id }}</h3>
                    @can('view-'.str_slug('inspector'))
                        <a class="btn btn-success pull-right" href="{{ url('/besurv/inspector') }}">
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

                    {!! Form::model($inspector, [
                        'method' => 'PATCH',
                        'url' => ['/besurv/inspector', $inspector->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('besurv.inspector.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

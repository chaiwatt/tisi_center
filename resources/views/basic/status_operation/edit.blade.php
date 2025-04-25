@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขขั้นตอนการดำเนินงาน #{{ $status_operation->id }}</h3>
                    @can('view-'.str_slug('status_operation'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/status_operation') }}">
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

                    {!! Form::model($status_operation, [
                        'method' => 'PATCH',
                        'url' => ['/basic/status_operation', $status_operation->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('basic.status_operation.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

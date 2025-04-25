@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขสถานภาพห้องปฏิบัติการ #{{ $lab_condition->id }}</h3>
                    @can('view-'.str_slug('lab_condition'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/lab_condition') }}">
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

                    {!! Form::model($lab_condition, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/lab_condition', $lab_condition->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.lab_condition.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

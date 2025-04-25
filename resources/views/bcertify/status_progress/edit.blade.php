@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขสถานะการดำเนินงาน #{{ $status_progress->id }}</h3>
                    @can('view-'.str_slug('status_progress'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/status_progress') }}">
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

                    {!! Form::model($status_progress, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/status_progress', $status_progress->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.status_progress.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

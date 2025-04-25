@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขสาขาการสอบเทียบ #{{ $calibration_branch->id }}</h3>
                    @can('view-'.str_slug('calibration_branch'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/calibration_branch') }}">
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

                    {!! Form::model($calibration_branch, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/calibration_branch', $calibration_branch->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.calibration_branch.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

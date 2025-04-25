@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขบันทึกการดำเนินการทางกฏหมาย #{{ $lawoperation->id }}</h3>
                    @can('view-'.str_slug('LawOperation'))
                        <a class="btn btn-success pull-right" href="{{ url('esurv/law-operation') }}">
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

                    {!! Form::model($lawoperation, [
                        'method' => 'PATCH',
                        'url' => ['esurv/law-operation', $lawoperation->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('esurv.law-operation.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

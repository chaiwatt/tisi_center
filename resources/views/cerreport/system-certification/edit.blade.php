@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลใบรับรอง #{{ $certificate->app_no }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('cerreport/system-certification') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($certificate, [
                        'method' => 'PATCH',
                        'url'    => ['cerreport/system-certification', $certificate->id],
                        'class'  => 'form-horizontal',
                        'files'  => true,
                        'id'     => 'MyForm'
                    ]) !!}

                    @include ('cerreport.system-certification.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

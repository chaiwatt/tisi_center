@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่ม ตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)</h3>
                    @can('view-'.str_slug('application-lab-audit'))
                        <a class="btn btn-success pull-right" href="{{url('/section5/application_lab_audit')}}">
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

                    {!! Form::open(['url' => '/section5/application_lab_audit', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('section5.application_lab_audit.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

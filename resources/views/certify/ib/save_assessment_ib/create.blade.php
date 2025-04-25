@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกผลการตรวจประเมิน (IB) maek</h3>
                    @can('view-'.str_slug('saveassessmentib'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/save_assessment-ib')}}">
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

                    {!! Form::open(['url' => '/certify/save_assessment-ib', 'class' => 'form-horizontal', 'files' => true,'id'=>'form_assessment']) !!}

                    @include ('certify/ib.save_assessment_ib.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

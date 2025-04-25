@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกผลการตรวจประเมิน (CB) landing</h3>
                    @can('view-'.str_slug('saveassessmentcb'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/save_assessment-cb')}}">
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

                    {{-- {!! Form::open(['url' => '/certify/save_assessment-cb', 'class' => 'form-horizontal', 'files' => true,'id'=>"form_assessment"]) !!}

                    @include ('certify/cb.save_assessment_cb.form')

                    {!! Form::close() !!} --}}

                    {!! Form::open(['url' =>  route('save_cb_assessment.store'), 'class' => 'form-horizontal','files' => true, 'id' => 'form_assessment']) !!}
                    
                        @include ('certify/cb.save_assessment_cb.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

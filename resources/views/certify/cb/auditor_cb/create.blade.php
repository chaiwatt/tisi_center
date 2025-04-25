@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แต่งตั้งคณะผู้ตรวจประเมิน (CB) #{{ $auditorcb->CertiCBCostTo->app_no}}</h3>
                    @can('view-'.str_slug('AuditorCB'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/auditor-cb')}}">
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

                    {!! Form::open(['url' => '/certify/auditor-cb', 'class' => 'form-horizontal', 'files' => true,'id'=>'form_auditor']) !!}

                    @include ('certify.cb.auditor_cb.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

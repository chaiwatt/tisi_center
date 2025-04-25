@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แต่งตั้งคณะผู้ตรวจประเมิน  (IB) {{ $auditorib->id }}</h3>
                    @can('view-'.str_slug('auditorib'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/auditor-ib') }}">
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

                    {!! Form::model($auditorib, [
                        'method' => 'PATCH',
                        'url' => ['/certify/auditor-ib', $auditorib->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'form_auditor'
                    ]) !!}

                            @include ('certify/ib.auditor_ib.form')     

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

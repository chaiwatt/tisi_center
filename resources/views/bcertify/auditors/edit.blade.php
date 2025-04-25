@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลผู้ตรวจประเมิน  #{{ $information->id }}</h3>
                    @can('view-'.str_slug('auditor'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/auditors') }}">
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

                    {!! Form::model($information, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/auditors', $information->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'commentForm'
                    ]) !!}

                        @include ('bcertify/auditors.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบตรวจสอบคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) #{{ $applicationlab->id }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application_lab_accept') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ</a>
  
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($applicationlab, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application_lab_accept/approve-save', $applicationlab->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('section5.application_lab_accept.form', ['submitButtonText' => 'Update'])
      
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
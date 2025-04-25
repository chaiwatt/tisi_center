@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม #{{ $applicationInspector->id }}</h3>

                    <div class="pull-right">
                        <a class="btn btn-success" href="{{ url('/section5/application_inspectors_accept') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> 
                            กลับ
                        </a>
                    </div>
  
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($applicationInspector, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application_inspectors_accept', $applicationInspector->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('section5.application_inspectors_accept.form', ['submitButtonText' => 'Update'])
      
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
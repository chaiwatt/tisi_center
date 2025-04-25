@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบรับคำขอแต่งตั้งเป็นผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB/CB) #{{ $applicationlab->id }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application_ibcb_accept') }}">
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

                    {!! Form::model($ApplicationIbcb, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application_ibcb_accept/approve-save', $ApplicationIbcb->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('section5.application_ibcb_accept.form', ['submitButtonText' => 'Update'])
      
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
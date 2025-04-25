@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม</h3>
            
                        <a class="btn btn-success pull-right" href="{{url('/csurv/control_freeze')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ</a>
       
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::open(['url' => '/csurv/control_freeze', 'class' => 'form-horizontal', 'files' => true]) !!}
                    
                             @include ('csurv/control_freeze.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

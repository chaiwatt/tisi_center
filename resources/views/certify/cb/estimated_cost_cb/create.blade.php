@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบการประมาณค่าใช้จ่าย (CB) </h3>
                    @can('view-'.str_slug('estimatedcostcb'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/estimated_cost-cb')}}">
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

                    {!! Form::open(['url' => '/certify/estimated_cost-cb', 
                    'class' => 'form-horizontal', 
                    'files' => true,
                    'id' =>'cost_form']) !!}

                    @include ('certify/cb.estimated_cost_cb.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขตั้งค่าการดำเนินการทางกฏหมาย #{{ $masterlawoperation->id }}</h3>
                    @can('view-'.str_slug('MasterLawOperation'))
                        <a class="btn btn-success pull-right" href="{{ url('/setting-law-operations') }}">
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

                    {!! Form::model($masterlawoperation, [
                        'method' => 'PATCH',
                        'url' => ['/setting-law-operations', $masterlawoperation->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('besurv.setting-law-operations.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

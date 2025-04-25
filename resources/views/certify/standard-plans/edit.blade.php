@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดทำแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง #{{ $standardplan->id }}</h3>
                    @can('view-'.str_slug('standardplans'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/standard-plans') }}">
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

                    {!! Form::model($standardplan, [
                        'method' => 'PATCH',
                        'url' => ['/certify/standard-plans', $standardplan->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('certify.standard-plans.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

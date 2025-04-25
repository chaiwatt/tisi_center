@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่าเลขรัน(Edit) #{{ $result->getKey() }}</h3>
                    @can('view-'.str_slug('configs-format-code'))
                        <a class="btn btn-success pull-right" href="{{ url('/config/format-code') }}">
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

                    {!! Form::model($result, [
                        'method' => 'PATCH',
                        'url' => ['/config/format-code', $result->getKey()],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('config.configs-format-code.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
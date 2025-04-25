@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขรูปแบบการกำหนดมาตรฐาน #{{ $set_format->id }}</h3>
                    @can('view-'.str_slug('set_format'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/set_format') }}">
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

                    {!! Form::model($set_format, [
                        'method' => 'PATCH',
                        'url' => ['/basic/set_format', $set_format->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('basic.set_format.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

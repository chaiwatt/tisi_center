@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขมาตรฐาน #{{ $formula->id }}</h3>
                    @can('view-'.str_slug('formula'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/formula') }}">
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

                    {!! Form::model($formula, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/formula', $formula->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.formula.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

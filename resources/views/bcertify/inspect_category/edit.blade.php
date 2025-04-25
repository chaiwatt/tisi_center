@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขหมวดหมู่การตรวจ #{{ $inspect_category->id }}</h3>
                    @can('view-'.str_slug('inspect_category'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/inspect_category') }}">
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

                    {!! Form::model($inspect_category, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/inspect_category', $inspect_category->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.inspect_category.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

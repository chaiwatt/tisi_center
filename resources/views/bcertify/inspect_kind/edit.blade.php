@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขชนิดและช่วงการตรวจ #{{ $inspect_kind->id }}</h3>
                    @can('view-'.str_slug('inspect_kind'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/inspect_kind') }}">
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

                    {!! Form::model($inspect_kind, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/inspect_kind', $inspect_kind->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.inspect_kind.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">พิจารณาคำขอผู้เชี่ยวชาญ   #{{ $registerexperts->id }}</h3>
                    @can('view-'.str_slug('registerexperts'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/register-experts') }}">
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

                    {!! Form::model($registerexperts, [
                        'method' => 'PATCH',
                        'url' => ['/certify/register-experts', $registerexperts->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('certify/register-experts.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

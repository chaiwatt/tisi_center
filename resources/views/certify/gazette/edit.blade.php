@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ประกาศราชกิจจานุเบกษา</h3>
                    @can('view-'.str_slug('gazette'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/gazette') }}">
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

                    {!! Form::model($gazette, [
                        'method' => 'PATCH',
                        'url' => ['/certify/gazette', $gazette->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('certify.gazette.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

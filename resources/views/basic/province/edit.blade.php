@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขจังหวัด #{{ $province->getKey() }}</h3>
                    @can('view-'.str_slug('province'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/province') }}">
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

                    {!! Form::model($province, [
                        'method' => 'PATCH',
                        'url' => ['/basic/province', $province->getKey()],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('basic.province.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขหน่วยนับของมาตรฐาน #{{ $tis_unit->getKey() }}</h3>
                    @can('view-'.str_slug('tis_unit'))
                        <a class="btn btn-success pull-right" href="{{ url('/besurv/tis_unit') }}">
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

                    {!! Form::model($tis_unit, [
                        'method' => 'PATCH',
                        'url' => ['/besurv/tis_unit', $tis_unit->getKey()],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('besurv.tis_unit.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

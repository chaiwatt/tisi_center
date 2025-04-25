@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขreport_volume #{{ $report_volume->id }}</h3>
                    @can('view-'.str_slug('report_volume'))
                        <a class="btn btn-success pull-right" href="{{ url('/report_volume/report_volume') }}">
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

                    {!! Form::model($report_volume, [
                        'method' => 'PATCH',
                        'url' => ['/report_volume/report_volume', $report_volume->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('rsurv.report_volume.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

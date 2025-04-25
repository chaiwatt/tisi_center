@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขreportstandardstatus #{{ $reportstandardstatus->id }}</h3>
                    @can('view-'.str_slug('reportstandardstatus'))
                        <a class="btn btn-success pull-right" href="{{ url('/reportstandardstatus/reportstandardstatus') }}">
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

                    {!! Form::model($reportstandardstatus, [
                        'method' => 'PATCH',
                        'url' => ['/reportstandardstatus/reportstandardstatus', $reportstandardstatus->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('reportstandardstatus.reportstandardstatus.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

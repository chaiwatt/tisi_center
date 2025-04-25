@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขกลุ่มรายงาน Power BI #{{ $ssourl->id }}</h3>
                    @can('view-'.str_slug('SsoUrl'))
                        <a class="btn btn-success pull-right" href="{{ url('/config/report-power-bi-group') }}">
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

                    {!! Form::model($ssourl, [
                        'method' => 'PATCH',
                        'url' => ['/config/report-power-bi-group', $ssourl->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('config/report_power_bi_group.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

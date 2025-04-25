@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขตรวจสอบข้อมูลความเห็น</h3>
                    @can('view-'.str_slug('law-listen-ministry-response'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/listen/ministry-response') }}">
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

                    {!! Form::model($lawlistministryrsponse, [
                        'method' => 'PATCH',
                        'url' => ['/law/listen/ministry-response', $lawlistministryrsponse->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include('laws.listen.ministry-response.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

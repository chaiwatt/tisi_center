@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขคณะทบทวนผลการตรวจประเมิน #{{ $board->id }}</h3>
                    @can('view-'.str_slug('auditor'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/auditor') }}">
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

                    {!! Form::open(['url' => '/certify/board_review/'.$board->id.'/edit', 'class' => 'form-horizontal', 'files' => true, 'method' => 'PUT']) !!}

                    @include ('certify.board_review.form-edit')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

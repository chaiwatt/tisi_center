@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มคณะทบทวนผลการตรวจประเมิน</h3>
                    @can('view-'.str_slug('board'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/board_review')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
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

                    {!! Form::open(['url' => '/certify/board_review', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('certify.board_review.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

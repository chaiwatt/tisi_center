@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">เพิ่มกลุ่มผู้มีสิทธิ์ได้รับเงินรางวัล</h3>
                    @can('view-'.str_slug('law-reward-group'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/basic/reward-group')}}">
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

                    {!! Form::open(['url' => '/law/basic/reward-group', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('laws.basic.reward-group.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

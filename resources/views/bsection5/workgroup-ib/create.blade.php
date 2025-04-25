@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">กลุ่มงาน IB</h3>
                    @can('view-'.str_slug('bsection5-workgroup'))
                        <a class="btn btn-success pull-right" href="{{url('/bsection5/workgroup-ib')}}">
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

                    {!! Form::open(['url' => '/bsection5/workgroup-ib', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('bsection5.workgroup-ib.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

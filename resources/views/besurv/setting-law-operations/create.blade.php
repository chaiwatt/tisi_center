@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มตั้งค่าการดำเนินการทางกฏหมาย</h3>
                    @can('view-'.str_slug('MasterLawOperation'))
                        <a class="btn btn-success pull-right" href="{{url('/setting-law-operations')}}">
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

                    {!! Form::open(['url' => '/setting-law-operations', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('besurv.setting-law-operations.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

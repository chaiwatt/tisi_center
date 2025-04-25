@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มหมวดหมู่ผลิตภัณฑ์</h3>
                    @can('view-'.str_slug('product_category'))
                        <a class="btn btn-success pull-right" href="{{url('/bcertify/product_category')}}">
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

                    {!! Form::open(['url' => '/bcertify/product_category', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('bcertify.product_category.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

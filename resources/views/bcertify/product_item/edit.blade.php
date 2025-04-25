@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขรายการผลิตภัณฑ์ #{{ $product_item->id }}</h3>
                    @can('view-'.str_slug('product_item'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/product_item') }}">
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

                    {!! Form::model($product_item, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/product_item', $product_item->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.product_item.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

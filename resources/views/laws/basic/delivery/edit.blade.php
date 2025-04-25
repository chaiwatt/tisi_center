@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">แก้ไขประเภทการจัดส่ง</h3>
                    @can('view-'.str_slug('law-basic-delivery'))
                    <a class="btn btn-default pull-right" href="{{ url('/law/basic/delivery') }}">
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

                    {!! Form::model($delivery, [
                        'method' => 'PATCH',
                        'url' => ['/law/basic/delivery', $delivery->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' =>'myForm'
                    ]) !!}

                    @include ('laws.basic.delivery.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

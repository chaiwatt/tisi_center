@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มประเภทเอกสารห้องสมุด</h3>
                    @can('view-'.str_slug('law-book-type'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/basic/book-type')}}">
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

                    {!! Form::open(['url' => '/law/basic/book-type', 'class' => 'form-horizontal', 'files' => true, 'id' =>'myForm']) !!}

                    @include ('laws.basic.book-type.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

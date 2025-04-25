@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขหมวดหมู่ห้องสมุด</h3>
                    @can('view-'.str_slug('law-book-group'))
                    <a class="btn btn-default pull-right" href="{{ url('/law/basic/book-group') }}">
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

                    {!! Form::model($bookgroup, [
                        'method' => 'PATCH',
                        'url' => ['/law/basic/book-group', $bookgroup->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' =>'myForm'
                    ]) !!}

                    @include ('laws.basic.book-group.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

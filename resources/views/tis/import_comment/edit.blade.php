@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขนำเข้าข้อมูล ความคิดเห็นต่อร่างกฎกระทรวง #{{ $import_comment->id }}</h3>
                    @can('view-'.str_slug('import-comment'))
                        <a class="btn btn-success pull-right" href="{{ url('tis/import_comment') }}">
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

                    {!! Form::model($import_comment, [
                        'method' => 'PATCH',
                        'url' => ['tis/import_comment', $import_comment->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('tis.import_comment.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตอบรับฟังความคิดเห็น</h3>
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::open(['url' => '/law/listen/ministry/accept-save', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include('laws.listen.ministry-response.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    
@endsection

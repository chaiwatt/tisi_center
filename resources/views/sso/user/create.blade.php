 
@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                {!! Form::open(['url' => '/sso/user-sso', 'class' => 'form-horizontal', 'files' => true]) !!}
                
                    @include('sso.user.form-center')

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection

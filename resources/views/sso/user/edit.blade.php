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

                {!! Form::model($user, [
                    'method' => 'PATCH',
                    'url' => ['/sso/user-sso', $user->getKey()],
                    'class' => 'form-horizontal',
                    'files' => true
                ]) !!}

                    @include('sso.user.form')

                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection

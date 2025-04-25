@extends('layouts.master')

@push('css')
<style type="text/css">

</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="row">

    {!! Form::model($user, [
        'method' => 'PATCH',
        'url' => ['/user/edit', $user->getKey()],
        'class' => 'form-horizontal',
        'files' => true
    ]) !!}

        @include ('users.form')

    {!! Form::close() !!}


    @if(count($errors) > 0)
        <div class="alert alert-danger">โปรดกรอกข้อมูลให้ครบถ้วนสมบูรณ์</div>
    @endif

  </div>
</div>
@endsection

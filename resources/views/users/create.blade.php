@extends('layouts.master')

@push('css')
<style type="text/css">

</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="row">

    {!! Form::open(['method' => 'POST',
                    'url' => ['/user/create'],
                    'class' => 'form-horizontal',
                    'files' => true
        ])
    !!}

    @include ('users.form')

    {!! Form::close() !!}

    @if(count($errors) > 0)
      <div class="alert alert-danger">โปรดกรอกข้อมูลให้ครบถ้วนสมบูรณ์</div>
    @endif

  </div>
</div>
@endsection

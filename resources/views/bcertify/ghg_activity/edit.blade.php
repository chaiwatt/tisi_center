@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขกิจกรรมของ GHG #{{ $ghg_activity->id }}</h3>
                    @can('view-'.str_slug('ghg_activity'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/ghg_activity') }}">
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

                    {!! Form::model($ghg_activity, [
                        'method' => 'PATCH',
                        'url' => ['/bcertify/ghg_activity', $ghg_activity->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('bcertify.ghg_activity.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ติดตามงานคดี</h3>
                    @can('view-'.str_slug('law-cases-tracks'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/cases/tracks') }}">
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

                    {!! Form::model($cases, [
                        'method' => 'PATCH',
                        'url' => ['/law/cases/ministry', $cases->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                            @include ('laws.cases.tracks.form')

                        <div class="clearfix"></div>
                         <a  href="{{ url("law/cases/tracks") }}"  class="btn btn-default btn-lg btn-block">
                            <i class="fa fa-rotate-left"></i>
                           <b>กลับ</b>
                        </a>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แจ้งงานเข้ากองกฎหมาย (แก้ไข)</h3>
                    @can('view-'.str_slug('law-track-receive'))
                        <a class="btn btn-success pull-right" href="{{ url('/law/track/receive') }}">
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

                    {!! Form::model($lawtrackreceive, [
                        'method' => 'PATCH',
                        'url' => ['/law/track/receive', $lawtrackreceive->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('laws.track.receive.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

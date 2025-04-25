@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขการตั้งค่ากลุ่มงานย่อยตามมาตรฐาน</h3>
                    @can('view-'.str_slug('tisuseresurvs'))
                        <a class="btn btn-success pull-right" href="{{ url('/besurv/tis-user-esurvs') }}">
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

                    {!! Form::model($tisuseresurv, [
                        'method' => 'PATCH',
                        'url' => ['/besurv/tis-user-esurvs', $tisuseresurv->getKey()],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('besurv.tis-user-esurvs.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

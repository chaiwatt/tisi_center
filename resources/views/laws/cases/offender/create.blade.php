@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                <h3 class="box-title pull-left">ประวัติการกระทำความผิด (เพิ่ม)</h3>
                    @can('add-'.str_slug('law-cases-offender'))
                        <a class="btn btn-default pull-right" href="{{url('/law/cases/offender')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
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
                    
                    {!! Form::open(['url' => '/law/cases/offender', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('laws.cases.offender.create.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

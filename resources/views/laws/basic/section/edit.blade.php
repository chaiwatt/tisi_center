@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">แก้ไขมาตราความผิด</h3>
                    @can('view-'.str_slug('law-sections'))
                    <a class="btn btn-default pull-right" href="{{ url('/law/basic/section') }}">
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

                    {!! Form::model($law_section, [
                        'method' => 'PATCH',
                        'url' => ['/law/basic/section', $law_section->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('laws.basic.section.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">กลุ่มสิทธิ์ระบบงาน Url #{{ $role_setting_group->id }}</h3>
                    @can('view-'.str_slug('role-setting-group'))
                        <a class="btn btn-success pull-right" href="{{ url('/role-setting-group') }}">
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

                    {!! Form::model($role_setting_group, [
                        'method' => 'PATCH',
                        'url' => ['/role-setting-group', $role_setting_group->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('roles.role-setting-group.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

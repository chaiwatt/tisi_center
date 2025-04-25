@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขกลุ่มเจ้าหน้าที่ #{{ $staff_group->id }}</h3>
                    @can('view-'.str_slug('staff_group'))
                        <a class="btn btn-success pull-right" href="{{ url('/basic/staff_group') }}">
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

                    {!! Form::model($staff_group, [
                        'method' => 'PATCH',
                        'url' => ['/basic/staff_group', $staff_group->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('basic.staff_group.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

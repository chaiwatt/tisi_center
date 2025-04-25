@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">กลุ่มงาน</h3>
                    @can('view-'.str_slug('bsection5-workgroup'))
                        <a class="btn btn-success pull-right" href="{{ url('/bsection5/workgroup-ib') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div id="request-form">
                        {!! Form::model($workgroup, [
                            'method' => 'PATCH',
                            'url' => ['/bsection5/workgroup-ib', $workgroup->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('bsection5.workgroup-ib.form')

                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

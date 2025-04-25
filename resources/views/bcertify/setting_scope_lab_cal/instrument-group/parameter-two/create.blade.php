@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มพารามิเตอร์2</h3>
                    @can('view-'.str_slug('bcertify-scope-lab-cal'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/setting_scope_lab_cal/instrument-group/parameter-two/'.$calibrationBranchInstrumentGroup->id) }}">
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

                    {!! Form::open(['url' => '/bcertify/setting_scope_lab_cal/instrument-group/parameter-two/store/'.$calibrationBranchInstrumentGroup->id, 'class' => 'form-horizontal']) !!}

                    @push('css')
                        <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
                    @endpush

                    <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'พารามิเตอร์2:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('name', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    
                <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                    {!! Form::label('state', 'สถานะ:', ['class' => 'col-md-4 control-label']) !!}
                    <div class="col-md-6">
                        <label>{!! Form::radio('state', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
                        <label>{!! Form::radio('state', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

                        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-4">

                        <button class="btn btn-primary" type="submit">
                        <i class="fa fa-paper-plane"></i> บันทึก
                        </button>
                        @can('view-'.str_slug('bcertify-scope-lab-cal'))
                            <a class="btn btn-default" href="{{url('/bcertify/setting_scope_lab_cal')}}">
                                <i class="fa fa-rotate-left"></i> ยกเลิก
                            </a>
                        @endcan
                    </div>
                </div>

                @push('js')
                <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
                <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
                <!-- input file -->
                <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
                @endpush

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

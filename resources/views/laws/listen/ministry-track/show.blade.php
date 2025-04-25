@push('css')
<link href="{{ asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{ asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
<style>
    .bootstrap-tagsinput > .label {
        line-height: 2.3;
    }
    .bootstrap-tagsinput {
        min-height: 70px;
        border-radius: 0;
        width: 100% !important;
        -webkit-border-radius: 7px;
        -moz-border-radius: 7px;
    }
    .bootstrap-tagsinput input {
        padding: 6px 6px;
    }
    .note-editor.note-frame {
        border-radius: 4px !important;
    }

</style>
@endpush
@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">บันทึกติดตาม/ประกาศราชกิจจา #{{ $lawlistministry->id }}</h3>
                    @can('view-'.str_slug('law-listen-ministry-track'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/listen/ministry-track') }}">
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

                    {!! Form::model($lawlistministry, [
                        'method' => 'PATCH',
                        'url' => ['/law/listen/ministry-track', $lawlistministry->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}


                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="white-box">
                                <legend class="legend">
                                    <h3>แบบรับฟังความเห็นฯ</h3>
                                </legend>
                                <br>
                    
                                @include('laws.listen.ministry.form.make-ministry')

                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="white-box">
                                <legend class="legend">
                                    <h3>ประกาศแบบรับฟังความเห็นฯ</h3>
                                </legend>
                                <br>
                    
                                @include('laws.listen.ministry.form.edit-ministry')

                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="white-box">
                                <legend class="legend">
                                    <h3>สรุปความเห็นร่างกฏกระทรวง</h3>
                                </legend>
                                <br>

                                @include('laws.listen.ministry-track.form.form-ministry-summary')
                       
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="white-box">
                                <legend class="legend">
                                    <h3>แจ้งผลวินิจฉัย</h3>
                                </legend>
                                <br>
                                
                                @include('laws.listen.ministry-track.form.form-modal-result')
                       
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="white-box">
                                <legend class="legend">
                                    <h3>บันทึกติดตามการดำเนินงาน</h3>
                                </legend>
                                <br>       

                                @include('laws.listen.ministry-track.form')
                       
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="white-box">
                                <legend class="legend">
                                    <h3>ประกาศราชกิจจา</h3>
                                </legend>
                                <br>
                                
                                @include('laws.listen.ministry-track.form.form-modal-result')
                       
                            </fieldset>
                        </div>
                    </div>

                    <a  href="{{ url('/law/listen/ministry-track') }}" class="btn btn-default btn-lg btn-block">
                        <i class="fa fa-rotate-left"></i>
                        <b>กลับ</b>
                    </a>
                    {!! Form::close() !!}

       
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
<script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
<script src="{{ asset('js/function.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script>

        $(document).ready(function() {
            //Disable
            $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.box_remove').remove();
           
        });
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }
    </script>
@endpush

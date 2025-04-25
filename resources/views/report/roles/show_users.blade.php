@extends('layouts.master')

    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

@push('css')
    <style>

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท # {{ $roles->id }}</h3>
                    @can('view-'.str_slug('report-roles'))
                        <a class="btn btn-success pull-right" href="{{ url('/report/roles/') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <center>
                                <h2 class="text-dark">กลุ่มบทบาท : {!! $roles->name !!}</h2>
                                <h4 class="text-dark">ส่วนการควบคุม : {!!  !empty($roles->label)?($roles->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ'):'-' !!}</h4>
                            </center>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-sm-12">
                            
                            @if($roles->label=='staff')
                                @include('report.roles.table.staff')
                            @else
                                @include('report.roles.table.trade')
                            @endif

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

@endpush
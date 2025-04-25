@extends('layouts.master')


@push('css')
    <style>
        .td_border{
            border-bottom: 1px solid #ccc;
        }
        td, th{
            padding: 8px;
        }
    
        .lead_cuttom {
            margin-bottom: 18px;
            line-height: 27px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานผลตรวจติดตาม (IB) # {{ $testfactory->id }}</h3>
                    @can('view-'.str_slug('report-test-factory'))
                        <a class="btn btn-success pull-right" href="{{ url('/report/test-factory/') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <center>
                                <h2>รายละเอียดการตรวจโรงงาน</h2>
                            </center>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <fieldset class="white-box">
                                <legend>ข้อมูลคำขอ</legend>
                                @include('report/test-factory.show_factory')
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <fieldset class="white-box">
                                <legend>ประวัติการตรวจโรรงาน</legend>
                                @include('report/test-factory.show_detail')
                            </fieldset>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
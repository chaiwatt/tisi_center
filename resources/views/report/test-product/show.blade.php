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
                    <h3 class="box-title pull-left">รายงานทดสอบ LAB # {{ $testproduct->id }}</h3>
                    @can('view-'.str_slug('report-test-product'))
                        <a class="btn btn-success pull-right" href="{{ url('/report/test-product/') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
   
                            <ul class="nav nav-pills m-b-30">
                                <li class="active">
                                    <a href="#navpills-infomation" data-toggle="tab" aria-expanded="true">รายละเอียดการทดสอบ</a>
                                </li>
                                <li class="">
                                    <a href="#navpills-detail" data-toggle="tab" aria-expanded="false">ผลการทดสอบ</a>
                                </li>
                            </ul>

                            <div class="tab-content br-n pn">
                                <div id="navpills-infomation" class="tab-pane active">
                                    <div class="row">
                                        @include('report/test-product.show_infomation')
                                    </div>
                                </div>
                                <div id="navpills-detail" class="tab-pane">
                                    <div class="row">
                                        @include('report/test-product.show_detail')
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@extends('layouts.master')

@push('css')
    <style>
        .info-box .info-count2 {
            font-size: 25px;
            margin-top: -5px;
            margin-bottom: 5px  
        }

        .colorbox-group-widget .info-color-box .media .info-count2 {
            font-size: 25px;
            margin-bottom: 5px;
            color: #fff
        }

        .info-box .info-count3 {
            font-size: 20px;
            margin-top: -5px;
            margin-bottom: 5px  
        }

        .colorbox-group-widget .info-color-box .media .info-count3 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #fff
        }
    </style>
@endpush

@php 
   $controller = new App\Http\Controllers\FuntionCenter\MenusController;
@endphp

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">


                    @if(HP::check_group_menu($laravelMenuCertify) || HP::check_group_menu($laravelMenuCertificate))
                    <h3 class="box-title">ระบบงานหลัก</h3>
                        @if(auth()->user()->IsGetRolesDirector() == "true" || in_array((int)auth()->user()->reg_subdepart,[1804,1805,1806]))
                        <!-- ระบบตรวจสอบคำขอใบรับรองห้องปฏิบัติการ (LAB)-->
                            @include ('admin.form_lab')
                        @endif
                        @if(auth()->user()->IsGetRolesDirector() == "true" || in_array((int)auth()->user()->reg_subdepart,[1802]))
                        <!-- ระบบตรวจสอบคำขอใบรับรองหน่วยตรวจ (IB) -->
                            @include ('admin.form_ib')
                        @endif
                        @if(auth()->user()->IsGetRolesDirector() == "true" || in_array((int)auth()->user()->reg_subdepart,[1803]))
                            <!-- ระบบตรวจสอบคำขอใบรับรองหน่วยตรวจ (IB) -->
                            @include ('admin.form_cb')
                        @endif
                    @endif

                    @if(HP::check_group_menu($laravelMenuBcertify))
                    <h3 class="box-title">ข้อมูลพื้นฐาน</h3>

                    <div class="row colorbox-group-widget">

                        @can('view-'.str_slug('formula'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/formula') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:200%;">มาตรฐาน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-chemical-weapon"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลมาตรฐาน</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('signer'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/signer') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">ผู้ลงนาม<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-account-edit"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลผู้ลงนาม</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('lab_condition'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/lab_condition') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:200%;">สภาพห้องปฏิบัติการ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-security-home"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสภาพห้องปฏิบัติการ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('calibration_branch'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/calibration_branch') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">สาขาการสอบเทียบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-share-variant"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสาขาการสอบเทียบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('calibration_group'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/calibration_group') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:150%;">หมวดหมู่รายการสอบเทียบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-chart-bubble"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลอหมวดหมู่รายการสอบเทียบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('calibration_item'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/calibration_item') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">รายการสอบเทียบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-chart-timeline"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลรายการสอบเทียบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('test_branch'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/test_branch') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">สาขาการทดสอบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-test-tube"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสาขาการทดสอบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('product_category'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/product_category') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">หมวดหมู่ผลิตภัณฑ์<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-webpack"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลหมวดหมู่ผลิตภัณฑ์</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('product_item'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/product_item') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">รายการผลิตภัณฑ์<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-file-document-box"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลรายการผลิตภัณฑ์</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('test_item'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/test_item') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">รายการทดสอบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-calendar-text"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลรายการทดสอบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('inspect_type'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/inspect_type') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:180%;">ประเภทการตรวจ (IB)<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-package-variant-closed"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ประเภทการตรวจ (IB)</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('inspect_category'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/inspect_category') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">หมวดหมู่การตรวจ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-book-multiple-variant"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลหมวดหมู่การตรวจ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('inspect_branch'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/inspect_branch') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">สาขาการตรวจ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-shuffle-variant"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสาขาการตรวจ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('inspect_kind'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/inspect_kind') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:180%;">ชนิดและช่วงการตรวจ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-link"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลชนิดและช่วงการตรวจ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('certification_branch'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/certification_branch') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">สาขาการรับรอง<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-unity"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสาขาการรับรอง</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('industry_type'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/industry_type') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:180%;">ประเภทอุตสาหกรรม<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-factory"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลประเภทอุตสาหกรรม</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('iaf'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/iaf') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">IAF<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-message-alert"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูล IAF</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('enms'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/enms') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">Enms<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-factory"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูล Enms</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('ghg'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/ghg') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">GHG<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-message-reply-text"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูล GHG</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('status_auditor'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/status_auditor') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:180%;">สถานะผู้ตรวจประเมิน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-account-alert"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสถานะผู้ตรวจประเมิน</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('status_progress'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/status_progress') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:180%;">สถานะการดำเนินงาน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-priority-high"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลสถานะการดำเนินงาน</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                    {{-- </div> --}}

                    {{-- <div class="row colorbox-group-widget"> --}}

                        @can('view-'.str_slug('config_attach'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/config_attach') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">ตั้งค่าเอกสารแนบ<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-attachment"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลตั้งค่าเอกสารแนบ</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('certification_scope'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/certification_scope') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count">ขอบข่ายการรับรอง<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-vector-difference-ba"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ข้อมูลขอบข่ายการรับรอง</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('auditor'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('bcertify/auditors') }}">
                                <div class="white-box">
                                    <div class="media bg-dashboard8">
                                        <div class="media-body">
                                            <h3 class="info-count">ผู้ตรวจประเมิน<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-vector-difference-ba"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ข้อมูลผู้ตรวจประเมิน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('certify_alert_setting'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('certify/alert/setting') }}">
                                <div class="white-box">
                                    <div class="media bg-dashboard8">
                                        <div class="media-body">
                                            <h3 class="info-count" style="font-size:140%;">ตั้งค่าการแจ้งเตือนใบรับรอง<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-account-alert"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ตั้งค่าการแจ้งเตือนข้อมูลใบรับรอง</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endcan

                        @can('view-'.str_slug('tisusercertify'))
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('certify/set-standard-user') }}">
                                <div class="white-box">
                                    <div class="media bg-dashboard8">
                                        <div class="media-body">
                                            <h3 class="info-count" style="font-size:140%;">ตั้งค่ากลุ่มงานตามมาตรฐาน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-settings"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ตั้งค่ากลุ่มงานตามมาตรฐานและสาขา</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan

                    @if (auth()->user()->isAdmin() === true)
                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('certify/authorities-lt') }}">
                                <div class="white-box">
                                    <div class="media bg-dashboard8">
                                        <div class="media-body">
                                            <h3 class="info-count" style="font-size:140%;">ตั้งค่าส่ง E-mail<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-email"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ตั้งค่าส่ง E-mail </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if (auth()->user()->isAdmin() === true)
                    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('basic/feewaiver') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:140%;">ตั้งค่ายกเว้นค่าธรรรมเนียม  <br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-settings"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ตั้งค่ายกเว้นค่าธรรรมเนียม</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if (auth()->user()->isAdmin() === true)
                    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('basic/setting-tracking') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:140%;">ตั้งค่าตรวจติดตามใบรับรอง  <br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-octagram"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ตั้งค่าตรวจติดตามใบรับรอง</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
        
                <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                    <a href="{{ url('certify/dashboard') }}">
                        <div class="white-box">
                            <div class="media bg-dashboard8">
                                <div class="media-body">
                                    <h3 class="info-count" style="font-size:140%;">Dashboard   <br/>
                                        <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-view-dashboard"></i></span>
                                    </h3>
                                    <p class="info-text font-12">แผงควบคุม</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
        
                    {{-- @can('view-'.str_slug('standardformulas'))
                    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                        <a href="{{ url('certify/formulas') }}">
                            <div class="white-box">
                                <div class="media bg-dashboard8">
                                    <div class="media-body">
                                        <h3 class="info-count" style="font-size:140%;">ตั้งค่าสาขาตามมาตรฐาน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-settings"></i></span>
                                        </h3>
                                        <p class="info-text font-12">ตั้งค่าสาขาตามมาตรฐาน (CB)</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan --}}

                    {{-- </div> --}}
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
@endpush

@extends('layouts.master')

@push('css')
    <style>
        .bg-green {
            background-color: #009999!important;
            color: #fff
        }
    </style>
@endpush

@php
    $group_menu_url = App\RoleSettingGroup::where('state',1)->orderby('ordering')->get();
@endphp

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title">หน้าหลัก</h3>

                    @if( count($group_menu_url) >=  1)

                        @php
                            $user = Auth::user()
                                        ->whereHas('roles.role_setting_group', function($q){ $q->where('id', 1); })
                                        ->where(((new App\User)->getKeyName()) ,Auth::user()->getKey() )
                                        ->first();
                        @endphp


                        <div class="row colorbox-group-widget">
                            @foreach ($group_menu_url as $item)

                                @php
                                    $check_elicense =  ( in_array($item->id, [1]) && !empty($user) && $item->role->whereIn('id', $user->roles()->pluck('id')->toArray() )->count() >  0);
                                @endphp

                                @if( ( !in_array($item->id, [1]) && (!empty($item->menu_jsons) && !empty( $item->FileMenuJson ) && HP::CheckMenuItem($item->FileMenuJson->menus[0]->items)))  )
                                    <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                      {{-- <a href="{!! url($item->urls) !!}" target="_blank"> --}}
                                        <a href="{!! url($item->urls) !!}">  
                                            <div class="white-box">
                                                <div class="media {!! $item->colors !!}">
                                                    <div class="media-body">
                                                        <h3 class="info-count">{!! $item->title !!}<br/>
                                                        <span class="pull-right" style="font-size:45px;"><i class="mdi {!! $item->icons !!}"></i></span>
                                                        </h3>
                                                        <p class="info-text font-12">{!! $item->description !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @elseif( empty($item->menu_jsons) )

                                    @if( !in_array($item->id, [1]) || ( in_array($item->id, [1]) && $check_elicense)  )
                                        <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                            <a href="{!! url($item->urls) !!}" target="_blank">
                                                <div class="white-box">
                                                    <div class="media {!! $item->colors !!}">
                                                        <div class="media-body">
                                                            <h3 class="info-count">{!! $item->title !!}<br/>
                                                            <span class="pull-right" style="font-size:45px;"><i class="mdi {!! $item->icons !!}"></i></span>
                                                            </h3>
                                                            <p class="info-text font-12">{!! $item->description !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                   
                                @endif
                 
                            @endforeach
                        </div>

                    @else
                        <div class="row colorbox-group-widget">
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ HP::getConfig()->url_elicense_staff }}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-dashboard1">
                                        <div class="media-body">
                                            <h3 class="info-count">e-License<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-wunderlist"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบออกใบอนุญาต</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="esurv" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-dashboard2">
                                        <div class="media-body">
                                            <h3 class="info-count">e-Surveillance<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-clipboard-text"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบตรวจติดตามออนไลน์</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('tis') }}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-dashboard3">
                                        <div class="media-body">
                                            <h3 class="info-count">มาตรฐาน มอก. <br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-web"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบการกำหนดมาตรฐาน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>
                        </div>

                        <div class="row colorbox-group-widget">

                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('https://appdb.tisi.go.th/TISINSW/trader_login.php') }}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-dashboard4">
                                        <div class="media-body">
                                            <h3 class="info-count">NSW<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-ferry"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบ NSW</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>

                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('certify') }}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-dashboard5">
                                        <div class="media-body">
                                            <h3 class="info-count">รับรองระบบงาน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-certificate"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบการรับรองระบบงาน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>

                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="{{ url('dashboard') }}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-dashboard7">
                                        <div class="media-body">
                                            <h3 class="info-count">Dashboard<br/>
                                            <span class="pull-right" style="font-size:45px;">
                                                <i class="mdi mdi-chart-scatterplot-hexbin"></i>
                                            </span>
                                            </h3>
                                            <p class="info-text font-12" style="text-transform: none;">Data Analytic</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div>

                            {{-- <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                            <a href="https://itisi.go.th/e-license-2019/">
                                <div class="white-box">
                                    <div class="media bg-dashboard6">
                                        <div class="media-body">
                                            <h3 class="info-count">ตรวจโรงงาน<br/>
                                            <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-magnify"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบตรวจโรงงาน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            </div> --}}

                        </div>

                        <div class="row colorbox-group-widget">

                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="{!! url('/accepting-request-section5') !!}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-pink">
                                        <div class="media-body">
                                            <h3 class="info-count">ขึ้นทะเบียนตามมาตรา 5<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-bookmark-plus"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบรับคำขอสำหรับ IB และ LAB</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light" >
                                <a href="{!! url('/standards') !!}" target="_blank">
                                <div class="white-box">
                                    <div class="media bg-green " >
                                        <div class="media-body">
                                            <h3 class="info-count">ระบบมาตรฐาน มตช.<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-table-edit"></i></span>
                                            </h3>
                                            <p class="info-text font-12" >ระบบกำหนดมาตรฐานการตรวจสอบและรับรอง</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>  

                        
                            {{-- <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                    <a href="{!! url('/law') !!}" target="_blank">
                                    <div class="white-box">
                                        <div class="media bg-primary ">
                                            <div class="media-body">
                                                <h3 class="info-count">งานคดี<br/>
                                                    <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-scale-balance"></i></span>
                                                </h3>
                                                <p class="info-text font-12">ระบบบันทึกคดีผลิตภัณฑ์อุตสาหกรรม</p>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div> --}}

                            {{-- <div class="col-md-4 col-sm-6 info-color-box waves-effect waves-light">
                                <a href="https://itisi.go.th/e-license-2019/">
                                <div class="white-box">
                                    <div class="media bg-dashboard7">
                                        <div class="media-body">
                                            <h3 class="info-count">ทดสอบผลิตภัณฑ์<br/>
                                                <span class="pull-right" style="font-size:45px;"><i class="mdi mdi-cube-send"></i></span>
                                            </h3>
                                            <p class="info-text font-12">ระบบทดสอบผลิตภัณฑ์</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div> --}}

                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
@endpush

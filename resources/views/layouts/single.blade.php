<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo01.png')}}">
    <title>บริการอิเล็กทรอนิกส์ สมอ. </title>

    <!-- ===== Bootstrap CSS ===== -->
    <link href="{{asset('bootstrap/dist/css/bootstrap.min.css?20190616')}}" rel="stylesheet">

    <!-- ===== Plugin CSS ===== -->
    <link href="{{asset('plugins/components/toast-master/css/jquery.toast.css?20190616')}}" rel="stylesheet">

    <!-- ===== Select2 CSS ===== -->
    <link href="{{asset('plugins/components/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/custom-select/custom-select.css')}}" rel="stylesheet" type="text/css" />

    <!-- ===== Animation CSS ===== -->
    <link href="{{asset('css/animate.css?20190616')}}" rel="stylesheet">

    <!-- ===== Custom CSS ===== -->
    <link href="{{asset('css/common.css?20190616')}}" rel="stylesheet">

    <!-- ===== jQuery UI ===== -->
    <link href="{{asset('plugins/components/jqueryui/jquery-ui.min.css?20190616')}}" rel="stylesheet" />

    <!-- ===== Parsley js ===== -->
    <link href="{{asset('plugins/components/parsleyjs/parsley.css')}}" rel="stylesheet" />

    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('plugins/components/toastr/css/toastr.css')}}" rel="stylesheet" />

    <!--====== Dynamic theme changing =====-->
    <?php
        $theme_name = 'default';
        $fix_header = false;
        $fix_sidebar = false;
        $theme_layout = 'normal';

        $show_notice = false;

        if(auth()->user()){

            $params = (object)json_decode(auth()->user()->params);

            if(!empty($params->theme_name)){
                if(is_file('css/colors/'.$params->theme_name.'.css')){
                    $theme_name = $params->theme_name;
                }
            }

            if(!empty($params->fix_header) && $params->fix_header=="true"){
                $fix_header = true;
            }

            if(!empty($params->fix_sidebar) && $params->fix_sidebar=="true"){
                $fix_sidebar = true;
            }

            if(!empty($params->theme_layout)){
                $theme_layout = $params->theme_layout;;
            }

            //ระบบที่่ไม่ใช้แจ้งเตือน
            $show_notice = true;
            $path_info = request()->getPathInfo();
            $group_disable_notices = ['/law/'];
            foreach($group_disable_notices as $group_disable_notice){
                if(strpos($path_info, $group_disable_notice)===0){
                    $show_notice = false;
                }
            }

        }

        //ระยะเวลาดึงข้อมูลแจ้งเตือน
        $config = HP::getConfig();
        $refresh_notification = property_exists($config, 'refresh_notification') ? (int)$config->refresh_notification*1000 : 60000 ; //ถ้าไม่ได้ตั้งค่าใช้ 60 วิ

        $active_cookie = Cookie::get('active_cookie');

    ?>

    @if($theme_layout == 'fix-header')
        <link href="{{asset('css/style-fix-header.css?20190616')}}" rel="stylesheet">
        <link href="{{asset('css/colors/'.$theme_name.'.css?20190616')}}" id="theme" data-url="{{ url('') }}" rel="stylesheet">

    @elseif($theme_layout == 'mini-sidebar')
        <link href="{{asset('css/style-mini-sidebar.css?20190616')}}" rel="stylesheet">
        <link href="{{asset('css/colors/'.$theme_name.'.css?20190616')}}" id="theme" data-url="{{ url('') }}" rel="stylesheet">
    @else
        <link href="{{asset('css/style-normal.css?20190616')}}" rel="stylesheet">
        <link href="{{asset('css/colors/'.$theme_name.'.css?20190616')}}" id="theme" data-url="{{ url('') }}" rel="stylesheet">
    @endif

    @stack('css')

    <!-- ===== Iconpicker ===== -->
    <link rel="stylesheet" href="{{asset('plugins/components/bootstrap-iconpicker/bootstrap-iconpicker.min.css?20190616')}}"/>

    <link rel="stylesheet" src="{{asset('plugins/components/sweet-alert2/sweetalert2.min.css')}}">

    <!-- ===== Color CSS ===== -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        div.required label.control-label:after {
            content: " *";
            color: red;
        }

        .ui-front { z-index: 1000 !important; }

        label.required:after
        {
          color: red;
          content: " *";
        }

        @media (min-width: 768px) {
            .extra.collapse li a span.hide-menu {
                display: block !important;
            }

            .extra.collapse.in li a.waves-effect span.hide-menu {
                display: block !important;
            }

            .extra.collapse li.active a.active span.hide-menu {
                display: block !important;
            }

            ul.side-menu li:hover + .extra.collapse.in li.active a.active span.hide-menu {
                display: block !important;
            }
        }

        /*  small font css  */

        /*-----------------*/

        .font-small-1 {
            font-size: 0.7rem !important;
        }

        .font-small-2 {
            font-size: 0.8rem !important;
        }

        .font-small-3 {
            font-size: 0.9rem !important;
        }

        .font-small-4 {
            font-size: 1.0rem !important;
        }

        .font-small-5 {
            font-size: 1.1rem !important;
        }

        /* medium font css */

        /*----------------*/

        .font-medium-1 {
            font-size: 1.0rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
            font-style: normal;
            font-weight:390 !important; /* ปรับให้ตัวบาง */
        }

        .font-medium-2 {
            font-size: 1.2rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-medium-3 {
            font-size: 1.3rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-medium-4 {
            font-size: 1.4rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-medium-5 {
            font-size: 1.5rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-medium-6 {
            font-size: 1.6rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-title-medium-1 {
            font-size: 1.0rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
            font-weight: normal;
            font-weight:400 !important;
        }

        .font-small-4a {
            font-size: 1.0rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
            font-style: normal;
            font-weight:400 !important;
            color: #2980b9;
        }

        .font-medium-1a {
            font-size: 1.1rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
            font-style: normal;
            font-weight:400 !important;
            color: #2980b9;
        }

        .font-medium-1b {
            font-size: 1.1rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
            font-style: normal;
            font-weight:400 !important;
        }

        .font-medium-2a {
            font-size: 1.2rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
            font-weight:600 !important;
            color: #345073 !important;
        }


        /* large font css */

        /*---------------*/

        .font-large-1 {
            font-size: 2rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-large-2 {
            font-size: 3rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-large-3 {
            font-size: 4rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-large-4 {
            font-size: 5rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        .font-large-5 {
            font-size: 6rem !important;
            font-family: 'Kanit', Open Sans, sans-serif;
        }

        /* Font weights */

        .text-bold-300 {
            font-weight: 300;
        }

        .text-bold-350 {
            font-weight: 350;
        }

        .text-bold-400 {
            font-weight: 400;
        }

        .text-bold-450 {
            font-weight: 450;
        }

        .text-bold-500 {
            font-weight: 500;
        }

        .text-bold-550 {
            font-weight: 550;
        }

        .text-bold-600 {
            font-weight: 600;
        }

        .text-bold-650 {
            font-weight: 650;
        }

        .text-bold-700 {
            font-weight: 700;
        }

        .cookie{
            width: 98%;
            height: 100px;
            position: fixed;
            bottom: 30%;
            border-radius: 10px;
            left: 1%;
            padding: 10px 20px;
            z-index: 9999;
            cursor: pointer;
        }

        .cookie .accept {
            background-color: #40CC79;
            color: #fff !important;
            border-radius: 32px;
            padding: 3px 23px;
            /* align-self: center; */
            font-size: 19px;
            margin-top: 2.5%;
            margin-left: 3%;

        }
        .cookie .accept:hover {
            background-color: #30b867;
        }

        .cookie-btn-container{
            position: absolute;
            float: left;
            z-index: 1;
            margin-left: -2%;
            top: 40%;
            transform: translateY(-50%);
        }

        .cookie .cookie_detail{
            margin-left:5% !important;
            margin-right:5% !important;
            margin-bottom:0%;
            font-size: 15pt;
        }

        legend {
            width:inherit; /* Or auto */
            padding:0 10px; /* To give a bit of padding on the left and right */
            border-bottom:none;
        }

        fieldset {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                    box-shadow:  0px 0px 0px 0px #000;
        }

        .modal-xl{
            width: 1140px;
            max-width: 1140px;
        }

        .modal-dialog-scrollable .modal-body {
            max-height: calc(100vh - 212px);
            overflow-y: auto;
        }

        @media (min-width: 992px) {
            .modal-lg,
            .modal-xl {
                max-width: 800px;
            }

            .notification{
                width: auto;
                height: auto;
                position: fixed;
                z-index: 9999;
                top: 90%;
                right: -0.5%;
                cursor: pointer;
            }
        }

        @media (min-width: 1200px) {
            .modal-xl {
                max-width: 1140px;
            }

            .notification{
                width: auto;
                height: auto;
                position: fixed;
                z-index: 9999;
                top: 90%;
                right: -0.5%;
                cursor: pointer;
            }
        }

        @media only screen and (max-width:767px){
            .modal-xl{
                width: auto;
                max-width: 1140px;
            }

            .notification{
                width: auto;
                height: auto;
                position: fixed;
                z-index: 9999;
                top: 90%;
                right: 1%;
                cursor: pointer;
            }

            .dropleft .dropdown-menu {
                position: absolute;
                left: -290%;
                margin-bottom: 10px;
                width: 270px !important;
                max-width: 300px;
                max-height: 500px;
                overflow-y: auto;
            }
        }
        /*   Font style   */

        .icoleaf2 {
            display: inline-block;
            width: 50px;
            height: 50px;
            padding: 5px 12px;
            font-size: 28px;
            border-top-left-radius: 50%;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
        }

        /* _dropdown.scss:73 */
        .dropleft .dropdown-menu {
            position: absolute;
            left: -325%;
            margin-bottom: 10px;
            width: 300px !important;
            max-height: 727px;
            max-width: 300px;
            overflow-y: auto;
        }

        .dropleft .dropdown-menu > li > a{
            white-space:normal;
        }

        .inputWithIcon input[type="text"] {
           padding-right: 5%;
        }

        .inputWithIcon {
           position: relative;
        }

        .inputWithIcon i {
            position: absolute;
            right: 0;
            top: 9%;
            padding-top: 6px;
            padding-right: 12px;
            padding-bottom: 6px;
            padding-left: 8px;
            color: #aaa;
            transition: 0.3s;
            align-items: center;
            font-size: 22px;

        }

        .inputWithIcon.inputIconBg i {
            background-color: #aaa;
            color: #fff;
            padding: 9px 4px;
            border-radius: 4px 0 0 4px;
        }
        .has-dropdown {
            position: relative;
        }
        .btn-light-default {
            background-color: #e5ebec;
            color: #e5ebec !important;
        }
        .btn-light-info {
        background-color: #ccf5f8;
        color: #00CFDD !important;
        }
        .btn-light-info:hover, .btn-light-info.hover {
        background-color: #00CFDD;
        color: #fff !important;
        }
        .btn-light-danger {
        background-color: #f8cccc;
        color: #e73821 !important;
        }
        .btn-light-danger:hover, .btn-light-danger.hover {
        background-color: #e73821;
        color: #fff !important;
        }

        .btn-light-primary {
        background-color: #f8cccc;
        color: #e73821 !important;
        }
        .btn-light-primary:hover, .btn-light-primary.hover {
        background-color: #a7b0ea;
        color: #fff !important;
        }
        .btn-light-success {
        background-color: #c4e3ce;
        color: #28a745 !important;
        }
        .btn-light-success:hover, .btn-light-success.hover {
        background-color: #28a745;
        color: #fff !important;
        }

        .btn-light-warning {
        background-color: #f3e7d0 ;
        color: #ffc107 !important;
        }
        .btn-light-warning:hover, .btn-light-warning.hover {
        background-color: #ffc107;
        color: #fff !important;
        }
  
        .btn-light-primary {
        background-color: #cadaff ;
        color: #000cf5 !important;
        }
        .btn-light-primary:hover, .btn-light-primary.hover {
        background-color: #000cf5;
        color: #fff !important;
        }

        .btn-label-info {
            background: #d6f7fa;
            border-color: transparent;
            color: #00cfdd;
        }
        .btn-label-info:hover {
            background: #00cfdd !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .btn-label-primary {
            background: #e5edfc;
            border-color: transparent;
            color: #5a8dee;
        }
        .btn-label-primary:hover {
            background: #5a8dee !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .btn-label-success {
            background: #dff9ec;
            border-color: transparent;
            color: #39da8a;
        }
        .btn-label-success:hover {
            background: #39da8a !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .btn-label-danger {
            background: #ffe5e5;
            border-color: transparent;
            color: #ff5b5c;
        }
        .btn-label-danger:hover {
            background: #ff5b5c !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .btn-label-warning {
            background: #fff2e1;
            border-color: transparent;
            color: #fdac41;
        }
        .btn-label-warning:hover {
            background: #fdac41 !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .btn-label-dark {
            background: #e2e4e6;
            border-color: transparent;
            color: #495563;
        }
        .btn-label-dark:hover {
            background: #495563 !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .btn-label-secondary {
            background: #e7ebef;
            border-color: transparent;
            color: #69809a;
        }
        .btn-label-secondary:hover {
            background: #69809a !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        /* Divider Css */
        .divider {
            display: block;
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
            margin: 1rem 0;
        }

        .divider .divider-text {
            position: relative;
            display: inline-block;
            padding: 0 1rem;
            background-color: #FFFFFF;
        }

        .divider .divider-text i {
            font-size: 1rem;
        }

        .divider .divider-text:before,
        .divider .divider-text:after {
            content: "";
            position: absolute;
            top: 50%;
            width: 9999px;
            border-top: 1px solid #DFE3E7;
        }

        .divider .divider-text:before {
            right: 100%;
        }

        .divider .divider-text:after {
            left: 100%;
        }

        .divider.divider-left .divider-text {
            float: left;
            padding-left: 0;
        }

        .divider.divider-left .divider-text:before {
            display: none;
        }

        .divider.divider-left-center .divider-text {
            left: -25%;
        }

        .divider.divider-right .divider-text {
            float: right;
            padding-right: 0;
        }

        .divider.divider-right .divider-text:after {
            display: none;
        }

        .divider.divider-right-center .divider-text {
            right: -25%;
        }

        .divider.divider-dotted .divider-text:before,
        .divider.divider-dotted .divider-text:after {
            border-style: dotted;
            border-width: 1px;
            border-top-width: 0;
            border-color: black;
        }

        .divider.divider-dashed .divider-text:before,
        .divider.divider-dashed .divider-text:after {
            border-style: dashed;
            border-width: 1px;
            border-top-width: 0;
            border-color: black;
        }

        /* Divider white */
        .divider.divider-white .divider-text:before,
        .divider.divider-white .divider-text:after {
            border-color: #ffffff !important;
        }

        /* Divider black */
        .divider.divider-black .divider-text:before,
        .divider.divider-black .divider-text:after {
            border-color: #000000 !important;
        }
        /* Divider dark */
        .divider.divider-dark .divider-text:before,
        .divider.divider-dark .divider-text:after {
            border-color: rgba(38,60,85,.5) !important;
        }

        /* Divider primary */
        .divider.divider-primary .divider-text:before,
        .divider.divider-primary .divider-text:after {
            border-color: #5A8DEE !important;
        }

        /* Divider secondary */
        .divider.divider-secondary .divider-text:before,
        .divider.divider-secondary .divider-text:after {
            border-color: #c0c1c2 !important;
        }
        
        /* Divider success */
        .divider.divider-success .divider-text:before,
        .divider.divider-success .divider-text:after {
            border-color: #39DA8A !important;
        }

        /* Divider info */
        .divider.divider-info .divider-text:before,
        .divider.divider-info .divider-text:after {
            border-color: #00CFDD !important;
        }

        /* Divider warning */
        .divider.divider-warning .divider-text:before,
        .divider.divider-warning .divider-text:after {
            border-color: #FDAC41 !important;
        }

        /* Divider danger */
        .divider.divider-danger .divider-text:before,
        .divider.divider-danger .divider-text:after {
            border-color: #FF5B5C !important;
        }

        .alert-bg-primary{
            background-color: #e5edfc;
            border-color: #ceddfa;
            color: #5a8dee;
            border-radius: 0.50rem !important;
        }

        .alert-bg-secondary{
            background-color: #e7ebef;
            border-color: #d2d9e1;
            color: #69809a; 
            border-radius: 0.50rem !important;
        }

        .alert-bg-info{
            background-color: #d6f7fa;
            border-color: #b3f1f5;
            color: #00cfdd;
            border-radius: 0.50rem !important;
        }

        .alert-bg-warning{
            background-color: #fff2e1;
            border-color: #fee6c6;
            color: #fdac41;
            border-radius: 0.50rem !important;
        }

        .alert-bg-danger{
            background-color: #ffe5e5;
            border-color: #ffcece;
            color: #ff5b5c;
            border-radius: 0.50rem !important;
        }

        .alert-bg-success{
            background-color: #dff9ec;
            border-color: #c4f4dc;
            color: #39da8a;
            border-radius: 0.50rem !important;
        }

        .select2-container-multi .select2-choices {
            border-radius: 5px !important;
            background-color: #fff;
            border: 1px solid #d4d8dd;
        }
        .text-orange{
            color: #FFA500
        }
    </style>
</head>

<body class="
  {{ $theme_layout }}
  {{-- @if($fix_header===true) fix-header @endif
  @if($fix_sidebar===true) fix-sidebar @endif" --}}
  >
<!-- ===== Main-Wrapper ===== -->
<div id="wrapper">
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <!-- ===== Top-Navigation ===== -->
{{-- @include('layouts.partials.navbar') --}}
<!-- ===== Top-Navigation-End ===== -->

    <!-- ===== Left-Sidebar ===== -->
{{-- @include('layouts.partials.sidebar') --}}
{{-- @include('layouts.partials.right-sidebar') --}}

{{-- @if ( is_null($active_cookie) )
    @include('layouts.notice.cookie')
@endif --}}

{{-- @if ($show_notice)
    @include('layouts.notice.notification')
@endif --}}

<!-- ===== Left-Sidebar-End ===== -->
    <!-- ===== Page-Content ===== -->
    <div >
{{-- 
        @isset($breadcrumbs)
            <div class="col-sm-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ( $breadcrumbs  as $breadcrumb )
                            <li class="breadcrumb-item  @if(  isset($breadcrumb['link']) && ( URL::current() == url( $breadcrumb['link'] )) ) active  @endif" @if( isset($breadcrumb['link']) && ( URL::current() == url( $breadcrumb['link'] )) )) aria-current="page"  @endif>
                                @if ( isset($breadcrumb['link']) )
                                    <a href="{!! isset($breadcrumb['link'])?url( $breadcrumb['link'] ):'#' !!}" @if(  isset($breadcrumb['link']) && ( URL::current() == url( $breadcrumb['link'] )) ) class="text-muted"  @endif>
                                        {!! !empty($breadcrumb['icon'])?'<i class="'.($breadcrumb['icon']).'"></i> ':'' !!}{!! $breadcrumb['name'] !!}
                                    </a>
                                @else
                                    {!! $breadcrumb['name'] !!}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        @endisset --}}

        @yield('content')

        <footer class="footer t-a-c">
            © 2565 สมอ.
        </footer>
    </div>
    <!-- ===== Page-Content-End ===== -->
</div>
<!-- ===== Main-Wrapper-End ===== -->

<!-- ==============================
    Required JS Files
=============================== -->

<!-- ===== jQuery ===== -->
<script src="{{asset('plugins/components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('plugins/components/jqueryui/jquery-ui.min.js')}}"></script>

<!-- ===== Bootstrap JavaScript ===== -->
<script src="{{asset('bootstrap/dist/js/bootstrap.min.js')}}"></script>

<!-- ===== Slimscroll JavaScript ===== -->
<script src="{{asset('js/jquery.slimscroll.js')}}"></script>

<!-- ===== Wave Effects JavaScript ===== -->
<script src="{{asset('js/waves.js')}}"></script>

<!-- ===== Menu Plugin JavaScript ===== -->
<script src="{{asset('js/sidebarmenu.js')}}"></script>

<!-- ===== Custom JavaScript ===== -->
@if($theme_layout == 'fix-header')
    <script src="{{asset('js/custom-fix-header.js')}}"></script>
@elseif($theme_layout == 'mini-sidebar')
    <script src="{{asset('js/custom-mini-sidebar.js')}}"></script>
@else
    <script src="{{asset('js/custom-normal.js')}}"></script>
@endif

<!-- ===== PARSLEY JS Validation ===== -->
<script src="{{asset('plugins/components/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('plugins/components/parsleyjs/language/th.js')}}"></script>

<!-- ===== Icon Picker JS ===== -->
<script src="{{asset('plugins/components/styleswitcher/jQuery.style.switcher.js')}}"></script>
<script src="{{asset('plugins/components/bootstrap-iconpicker/bootstrap-iconpicker-iconset-all.min.js')}}"></script>
<script src="{{asset('plugins/components/bootstrap-iconpicker/bootstrap-iconpicker.min.js')}}"></script>

<!-- ===== Plugin JS ===== -->
<script src="{{asset('plugins/components/custom-select/custom-select.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<!-- ====== Loading ====== -->
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
{{-- <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script> --}}
<script src="{{asset('plugins/components/sweet-alert2/sweetalert2@11.js')}}"></script>

<!-- input calendar thai -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
<!-- thai extension -->
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
<script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

<script src="{{asset('plugins/components/toastr/js/toastr.min.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {

        @if(\Session::has('mail_ministry_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'บันทึกข้อมูลสำเร็จ',
                text: 'ผลวินิจฉัยเป็นอย่างไร เราจะแจ้งให้ท่านทราบภายหลังผ่านอีเมลที่ระบุไว้',
                showConfirmButton: true,
                confirmButtonText: 'รับทราบ',
            });
        @endif

        check_max_size_file();
        // Stuff to do as soon as the DOM is ready
        $("select:not(.not_select2)").select2();

        //Validate

        if($('form').length>0){
            $('form:first:not(.not_validated)').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
            })
            .on('form:submit', function() {
            return true; // Don't submit form for this demo
            });
        }

        $('#modalCookie1').modal('show');

        $('.acceptcookies').click(function (e) {

            var expDate = new Date();

            var Time = (1440 * 60 * 1000) * 365;
            expDate.setTime(expDate.getTime() + Time ); // add 15 minutes

            expires = "; expires=" + expDate.toUTCString();

            document.cookie = 'active_cookie' + "=" + 'active'  + expires + ';path=/';

            console.log( 'active_cookie' + "=" + 'active'  + expires + ';path=/');

            $('.cookie').remove();

        });


        $('.datepicker').datepicker({
            language:'th-th',
            format:'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        });

        @if($show_notice)

            LoadNotificaion(true);

            setInterval(function(){
                LoadNotificaion(false);
            }, {{ $refresh_notification }});

            $('#dropNotificaton').click(function (e) {
                var id = [];
                $('.input_read_all').each(function(index, element){
                    id.push($(element).val());
                });

                if(id.length > 0){
                    $.ajax({
                        type:"POST",
                        url:  "{{ url('/funtions/read_all/notification') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success:function(data){
                            LoadNotificaion( false );
                        }
                    });
                }

            });

        @endif

        $('#search-btn').on('shown.bs.collapse', function() {

            if(  $("button#search_btn_all").find('span.glyphicon ').length > 0 ){
                $("button#search_btn_all").find('span').removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');
            }else if(  $("button#search_btn_all").find('i.fa ').length > 0  ){
                $("button#search_btn_all").find('i').removeClass('fa-ellipsis-h').addClass('fa-angle-double-up');
            }
        
        });

        $('#search-btn').on('hidden.bs.collapse', function() {
            if(  $("button#search_btn_all").find('span.glyphicon ').length > 0 ){
                $("button#search_btn_all").find('span').removeClass('glyphicon-menu-down').addClass('glyphicon-menu-up');
            }else if(  $("button#search_btn_all").find('i.fa ').length > 0  ){
                $("button#search_btn_all").find('i').removeClass('fa-angle-double-up').addClass('fa-ellipsis-h');
            }
        });
        
    });


    function check_max_size_file() {

        $('.check_max_size_file').bind('change', function() {

            var max_size = "{{ ini_get('upload_max_filesize') }}";
            var res = max_size.replace("M", "");

            if($(this).val() != ''){//ถ้าเลือกไฟล์

                /* ตรวจสอบขาดไฟล์ */
                res = $(this).attr('max-size')!=undefined ? parseInt($(this).attr('max-size')) : res ; //ถ้ามีกำหนดขนาดไฟล์ที่อัพโหลดได้โดยเฉพาะ
                var size = (this.files[0].size)/1024/1024 ; // หน่วย MB
                if(size > res ){
                    Swal.fire(
                        'ขนาดไฟล์เกินกว่า ' + res +' MB',
                        '',
                        'info'
                    );
                    $(this).parent().parent().find('.fileinput-exists').click();
                    $(this).val('');
                    $(this).parent().parent().find('.custom-file-label').html('');
                    return false;
                }

                /* ตรวจสอบนามสกุลไฟล์ */
                if($(this).attr('accept')!=undefined){//ถ้ากำหนดนามสกุลไฟล์ที่อัพโหลดได้ไว้
                    let accepts = $(this).attr('accept').split(',');
                    let names  = this.files[0].name.split('.');//ชื่อเต็มไฟล์
                    let ext    = names.at(-1);//นามสกุลไฟล์
                    let result = false;
                    $.each(accepts, function(index, accept) {
                        if('.'+ext==$.trim(accept)){
                            result = true;
                            return false;
                        }
                    });
                    if(result===false){
                        Swal.fire(
                            'อนุญาตให้อัพโหลดไฟล์นามสกุล '+accepts+' เท่านั้น',
                            '',
                            'info'
                        );
                        $(this).parent().parent().find('.fileinput-exists').click();
                        $(this).val('');
                        $(this).parent().parent().find('.custom-file-label').html('');
                        return false;
                    }
                }
            }

        });

    }

 
    function LoadNotificaion( check  ){

        if( check == true ){
            $('.notification').hide();
        }

        $.ajax({
            url: "{!! url('/funtions/auto-refresh/notification') !!}"
        }).done(function( object ) {
            $('.notifiction_badge').hide();
            $('.notifiction_details').html('');

            if( object.length > 0 ){
                var html = '';
                var i = 0;
                $.each(object, function( index, data ) {

                    var input = '';

                    if(  data.read_all != 1  ){
                        i++;
                        input = '<input type="hidden" class="input_read_all" value="'+(data.id)+'">';
                    }

                    var status = '';

                    if( data.type == 2 ){
                        status += '<span class="h6">คุณได้รับการมอบหมาย </span><br>';
                    }else if( data.type == 3 ){
                        status += '<span class="h6">คุณได้อนุมัติ</span><br>';
                    }else if( data.type == 4  ){
                        status += '<span class="h6">คุณได้บันทึกข้อมูล</span><br>';
                    }else{
                        status += '<span class="h6">สถานะ : '+(data.ref_status)+' </span><br>';
                    }

                    var style = ( data.read != 1 )?'<span class="fa fa-circle text-success m-r-10 pull-right"></span>':'';

                    var details = '<div class="mail-contnet" >';
                        details += '<h5 class="">'+(data.ref_applition_no)+' '+style+'</h5>';
                        details += status;
                        details += '<span class="h6">'+(data.title)+' </span><br>';
                        details += '<span class="time">'+(data.created_ats)+'</span>';
                        details += '</div>';
                    html += '<li ><a href="'+(data.root_site)+'/funtions/redirect/notification/'+(data.id)+'" target="_blank">'+(details)+'</a>'+(input)+'</li>';
                });

                html += '<li role="separator" class="divider"></li>';
                html += '<li class="text-center"><a href="#">ดูทั้งหมด</a></li>';

                if( i == 0){
                    $('.dropleft > .dropdown-menu').css("left", "-500%");
                    $('.notification').css("right", "1%");
                }else{
                    $('.dropleft > .dropdown-menu').css("left", "-325%");
                    $('.notifiction_badge').text(i);
                    $('.notifiction_badge').show();
                }

                $('.notifiction_details').html(html);

                $('.notification').show();

            }else{
                $('.notification').hide();
            }

        });

    }

    function law_confirm_delete() {
        Swal.fire({
            title: 'ลบรายการนี้หรือไม่?',
            text: "เมื่อรายการนี้ถูกลบ คุณไม่สามารถกู้คืนได้ !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#808080',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ยืนยัน',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_law_delete').submit();  
            }
        })
    }

</script>

{{-- <script>
    // ส่ง E-mail แจ้งเตือน จากระบบบันทึกผลการตรวจประเมิน
    function startTime() {
      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m);
      s = checkTime(s);
      if(( m == '00' || m == '30') && s == '00'){
            $.ajax({
                url: '{!! url('certify/email_assessment') !!}',
                method: "get",
            });
      }
      var t = setTimeout(function(){ startTime() }, 500);
    }
    function checkTime(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
    </script> --}}
@stack('js')
</body>

</html>

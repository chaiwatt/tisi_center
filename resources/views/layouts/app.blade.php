<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{asset('plugins/images/favicon.png')}}"> --}}
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/logo01.png')}}">
    <title>
        {{-- {{env('APP_NAME')}} --}}
        บริการอิเล็กทรอนิกส์ สมอ.
    </title>
    <!-- ===== Bootstrap CSS ===== -->
    <link href="{{asset('bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- ===== Plugin CSS ===== -->
    <link href="{{asset('plugins/components/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/components/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <!-- ===== Select2 CSS ===== -->
    <link href="{{asset('plugins/components/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/custom-select/custom-select.css')}}" rel="stylesheet" type="text/css" />
    <!-- ===== Animation CSS ===== -->
    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <!-- ===== Custom CSS ===== -->
    <link href="{{asset('css/style-normal.css?20220704')}}" rel="stylesheet">
    <!-- ===== Color CSS ===== -->
    <link href="{{asset('css/colors/default.css')}}" id="theme" rel="stylesheet">
       <!-- ===== Parsley js ===== -->
    <link href="{{asset('plugins/components/parsleyjs/parsley.css?20200630')}}" rel="stylesheet" />
 
    @stack('css')
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


    </style>
</head>
<body class="fix-header">
<!-- ===== Main-Wrapper ===== -->
@yield('content')

<!-- ===== Main-Wrapper-End ===== -->
<!-- ==============================
    Required JS Files
=============================== -->
<!-- ===== jQuery ===== -->
<script src="{{asset('plugins/components/jquery/dist/jquery.min.js')}}"></script>
<!-- ===== Bootstrap JavaScript ===== -->
<script src="{{asset('bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- ===== Slimscroll JavaScript ===== -->
<script src="{{asset('js/jquery.slimscroll.js')}}"></script>
<!-- ===== Wave Effects JavaScript ===== -->
<script src="{{asset('js/waves.js')}}"></script>
<!-- ===== Menu Plugin JavaScript ===== -->
<script src="{{asset('js/sidebarmenu.js')}}"></script>
<!-- ===== Custom JavaScript ===== -->
{{-- <script src="{{asset('js/custom.js')}}"></script> --}}
<!-- ===== PARSLEY JS Validation ===== -->
<script src="{{asset('plugins/components/parsleyjs/parsley.min.js')}}"></script>
<script src="{{asset('plugins/components/parsleyjs/language/th.js')}}"></script>

<!-- ===== Plugin JS ===== -->
<script src="{{asset('plugins/components/chartist-js/dist/chartist.min.js')}}"></script>
<script src="{{asset('plugins/components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>
<script src="{{asset('plugins/components/sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('plugins/components/sparkline/jquery.charts-sparkline.js')}}"></script>
<script src="{{asset('plugins/components/knob/jquery.knob.js')}}"></script>
<script src="{{asset('plugins/components/easypiechart/dist/jquery.easypiechart.min.js')}}"></script>
<!-- ===== Style Switcher JS ===== -->
<script src="{{asset('plugins/components/styleswitcher/jQuery.style.switcher.js')}}"></script>
<!-- ===== select 2  ===== -->
<script src="{{ asset('plugins/components/custom-select/custom-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/components/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Stuff to do as soon as the DOM is ready
        $("select:not(.not_select2)").select2();
            //Validate
        if($('form').length > 0){
            $('form:first:not(.not_validated)').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
            })
            .on('form:submit', function() {
            return true; // Don't submit form for this demo
            });
        }
    });
</script>
@stack('js')
</body>

</html>

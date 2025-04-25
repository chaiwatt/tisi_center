@push('css')
    <link href="{{ asset('plugins/components/bootstrap-wizard/css/bootstrap-wizard.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <style>
        .wizard-card .disableds {
            pointer-events: none;
        }

        .wizard-container {
            padding-top: 10px !important;
        }
    </style>   

@endpush

@php
    $step_tap_disabled = !empty($standard->status_id)?$standard->status_id:null;

    $step_tap_state    = !empty($standard->step_tap)?$standard->step_tap:1;
@endphp

@include ('certify/standards.tabs.infomation')
<div class="panel panel-info">
    <div class="panel-heading">สถานะการจัดทำมาตรฐาน</div>
    <div class="panel-wrapper collapse in" aria-expanded="true">
        <div class="panel-body panel-body-info">
            <div class="wizard-container form-horizontal">
                <div class="card wizard-card" data-color="blue">
                    <div class="wizard-navigation">
                        <div class="progress-with-circle">
                            <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3" style="width: 21%;"></div>
                        </div>
                        <ul>
                            <li class="{!! $step_tap_state == 1?'active':'' !!}">
                                <a href="#tab-details" data-toggle="tab">
                                    <div class="icon-circle">
                                        <i class="ti-desktop"></i>
                                    </div>
                                    รายละเอียดมาตรฐาน
                                </a>
                            </li>
                            <li class="{!! $step_tap_disabled <= 4?'disableds':'' !!} {!! $step_tap_state == 2?'active':'' !!}">
                                <a href="#tab-isbn" data-toggle="tab">
                                    <div class="icon-circle">
                                        <i class="ti-clipboard"></i>
                                    </div>
                                    เลข ISBN
                                </a>
                            </li>
                            <li  class="{!! $step_tap_disabled <= 5?'disableds':'' !!} {!! $step_tap_state == 3?'active':'' !!}">
                                <a href="#tab-sign" data-toggle="tab">
                                    <div class="icon-circle">
                                        <i class="ti-pencil-alt"></i>
                                    </div>
                                    ลงนามมาตรฐาน
                                </a>
                            </li>
                            <li  class="{!! $step_tap_disabled <= 6?'disableds':'' !!} {!! $step_tap_state == 4?'active':'' !!}">
                                <a href="#tab-gazette" data-toggle="tab">
                                    <div class="icon-circle">
                                        <i class="ti-rss"></i>
                                    </div>
                                    ประกาศราชกิจจานุเบกษา
                                </a>
                            </li>
                        </ul>
                    </div>
                    <br>
                    <div class="tab-content">

                        <div class="tab-pane {!! $step_tap_state == 1?'active':'' !!}" id="tab-details">
                            @include ('certify/standards.tabs.details')
                        </div>

                        <div class="tab-pane {!! $step_tap_state == 2?'active':'' !!}" id="tab-isbn">
                            @include ('certify/standards.tabs.isbn')
                        </div>

                        <div class="tab-pane {!! $step_tap_state == 3?'active':'' !!}" id="tab-sign">
                            @include ('certify/standards.tabs.sign')
                        </div>

                        <div class="tab-pane {!! $step_tap_state == 4?'active':'' !!}" id="tab-gazette">
                            @include ('certify/standards.tabs.gazette')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/components/bootstrap-wizard/js/jquery.bootstrap.wizard.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
            });

            $('#approve_noti_email').tagsinput({
                // itemText: 'label'
            });

            $('.wizard-card').bootstrapWizard({
                'tabClass': 'nav nav-pills',
                'nextSelector': '.btn-next',
                'previousSelector': '.btn-previous',
                onNext: function(tab, navigation, index) {
                	// var $valid = $('.wizard-card form').valid();
                	// if(!$valid) {
                	// 	$validator.focusInvalid();
                	// 	return false;
                	// }
                },

                onInit : function(tab, navigation, index){

                  //check number of tabs and fill the entire row
                  var $total = navigation.find('li').length;
                  $width = 100/$total;

                  navigation.find('li').css('width',$width + '%');

                },

                onTabClick : function(tab, navigation, index){

                    // var $valid = $('.wizard-card form').valid();

                    // if(!$valid){
                    //     return false;
                    // } else{
                    //     return true;
                    // }

                },

                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var $wizard = navigation.closest('.wizard-card');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $($wizard).find('.btn-next').hide();
                        $($wizard).find('.btn-finish').show();
                    } else {
                        $($wizard).find('.btn-next').show();
                        $($wizard).find('.btn-finish').hide();
                    }

                    //update progress
                    var move_distance = 100 / $total;
                    move_distance = move_distance * (index) + move_distance / 2;

                    $wizard.find($('.progress-bar')).css({width: move_distance + '%'});
                    //e.relatedTarget // previous tab

                    $wizard.find($('.wizard-card .nav-pills li.active a .icon-circle')).addClass('checked');
                    $($wizard).find('.btn-next').prop('disabled',false);
                }
	        });

            $(document).on('click', 'li', function () {
                LoadGetTapActive();
            });
            LoadGetTapActive();

            $('.step_save').click(function (e) { 

                var href = $('.wizard-navigation').find('li.active a').attr('href');
   
                var TabDetails = $('#tab-details');
                var TabIsbn    = $('#tab-isbn');
                var TabSign    = $('#tab-sign');
                var TabGazette = $('#tab-gazette');

                if( href == "#tab-details" ){
                    TabDetails.find('input, select, textarea').prop('disabled',false);
                    TabIsbn.find('input, select, textarea').prop('disabled',true);
                    TabSign.find('input, select, textarea').prop('disabled',true);
                    TabGazette.find('input, select, textarea').prop('disabled',true);

                    TabIsbn.find('input, select, textarea').prop('required',false);
                    TabSign.find('input, select, textarea').prop('required',false);
                    TabGazette.find('input, select, textarea').prop('required',false);

                }else if( href == "#tab-isbn" ){
                    TabDetails.find('input, select, textarea').prop('disabled',true);
                    TabIsbn.find('input, select, textarea').prop('disabled',false);
                    TabSign.find('input, select, textarea').prop('disabled',true);
                    TabGazette.find('input, select, textarea').prop('disabled',true);

                    TabDetails.find('input, select, textarea').prop('required',false);
                    TabSign.find('input, select, textarea').prop('required',false);
                    TabGazette.find('input, select, textarea').prop('required',false);

                }else if( href == "#tab-sign" ){
                    TabDetails.find('input, select, textarea').prop('disabled',true);
                    TabIsbn.find('input, select, textarea').prop('disabled',true);
                    TabSign.find('input, select, textarea').prop('disabled',false);
                    TabGazette.find('input, select, textarea').prop('disabled',true);

                    TabDetails.find('input, select, textarea').prop('required',false);
                    TabIsbn.find('input, select, textarea').prop('required',false);
                    TabGazette.find('input, select, textarea').prop('required',false);

                }else if( href == "#tab-gazette" ){
                    TabDetails.find('input, select, textarea').prop('disabled',true);
                    TabIsbn.find('input, select, textarea').prop('disabled',true);
                    TabSign.find('input, select, textarea').prop('disabled',true);
                    TabGazette.find('input, select, textarea').prop('disabled',false);

                    TabDetails.find('input, select, textarea').prop('required',false);
                    TabIsbn.find('input, select, textarea').prop('required',false);
                    TabSign.find('input, select, textarea').prop('required',false);
                }

                $('#standard_form').attr('target', '');
                $('#standard_form').submit();
            });

            $('#standard_form').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {


                $("#standard_form").find("[name='_method']").remove();
                var href = $('.wizard-navigation').find('li.active a').attr('href');

                var TabDetails = $('#tab-details');
                var TabIsbn    = $('#tab-isbn');
                var TabSign    = $('#tab-sign');
                var TabGazette = $('#tab-gazette');

                if( href == "#tab-details" ){
                    TabDetails.find('input, select, textarea').prop('disabled',false);
                    TabIsbn.find('input, select, textarea').prop('disabled',true);
                    TabSign.find('input, select, textarea').prop('disabled',true);
                    TabGazette.find('input, select, textarea').prop('disabled',true);
                }else if( href == "#tab-isbn" ){
                    TabDetails.find('input, select, textarea').prop('disabled',true);
                    TabIsbn.find('input, select, textarea').prop('disabled',false);
                    TabSign.find('input, select, textarea').prop('disabled',true);
                    TabGazette.find('input, select, textarea').prop('disabled',true);
                }else if( href == "#tab-sign" ){
                    TabDetails.find('input, select, textarea').prop('disabled',true);
                    TabIsbn.find('input, select, textarea').prop('disabled',true);
                    TabSign.find('input, select, textarea').prop('disabled',false);
                    TabGazette.find('input, select, textarea').prop('disabled',true);
                }else if( href == "#tab-gazette" ){
                    TabDetails.find('input, select, textarea').prop('disabled',true);
                    TabIsbn.find('input, select, textarea').prop('disabled',true);
                    TabSign.find('input, select, textarea').prop('disabled',true);
                    TabGazette.find('input, select, textarea').prop('disabled',false);
                }

                var formData = new FormData($("#standard_form")[0]);

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/certify/standards/save_standards') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            window.location.href = obj.url;
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'บันทึกไม่สำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                        }

                    }
                });

            });
        });

        function LoadGetTapActive(){

            var href = $('.wizard-navigation').find('li.active a').attr('href');

            var TabDetails = $('#tab-details');
            var TabIsbn    = $('#tab-isbn');
            var TabSign    = $('#tab-sign');
            var TabGazette = $('#tab-gazette');

            TabDetails.find('input, select, textarea').prop('disabled',true);
            TabIsbn.find('input, select, textarea').prop('disabled',true);
            TabSign.find('input, select, textarea').prop('disabled',true);
            TabGazette.find('input, select, textarea').prop('disabled',true);

            var step = 1;
            if( href == "#tab-details" ){
                step = 1;
                TabDetails.find('input, select, textarea').prop('disabled',false);
            }else if( href == "#tab-isbn" ){
                step = 2;
                TabIsbn.find('input, select, textarea').prop('disabled',false);
            }else if( href == "#tab-sign" ){
                step = 3;
                TabSign.find('input, select, textarea').prop('disabled',false);
            }else if( href == "#tab-gazette" ){
                step = 4;
                TabGazette.find('input, select, textarea').prop('disabled',false);
            }
            $('#step_tap').val(step);
        }

        function submit_form(status) {

            var url = "{!! url('certify/standards/cover_pdf') !!}"
                url += "?std_no=" + $('#std_no').val();
                url += "&std_title=" + $('#std_title').val();
                url += "&std_title_en=" + $('#std_title_en').val();
                url += "&isbn_no=" + $('#isbn_no').val();
                url += "&id=" + $('#id').val();
                window.open(url, '_blank');
        }

    </script>
@endpush

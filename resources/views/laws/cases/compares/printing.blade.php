@extends('layouts.master')

@push('css')
    <link href="{{ asset('plugins/components/bootstrap-wizard/css/bootstrap-wizard.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

    <style>
     input:read-only {
            cursor: not-allowed
        }
        textarea:read-only {
            cursor: not-allowed
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดทําหนังสือแจ้งเปรียบเทียบปรับ</h3>
                    @can('view-'.str_slug('law-cases-compares'))
                        <a class="btn btn-wizard-success btn-rounded pull-right" href="{{ url('/law/cases/compares') }}">
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

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend><b>ข้อมูลผู้กระทำความผิด</b>  </legend>
                    
                                <div class="row">
                                    <div class="col-md-5">
                                        {!! HTML::decode(Form::label('created_by_show', 'ชื่อผู้ประกอบการ', ['class' => 'col-md-5 control-label  text-right'])) !!}
                                        <div class="col-md-7 p-t-5 ">
                                            <p class="font-medium-6"> {!!   !empty($lawcases->offend_name) ? $lawcases->offend_name : null !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        {!! HTML::decode(Form::label('created_by_show', 'เลขประจำตัวผู้เสียภาษี', ['class' => 'col-md-5 control-label  text-right'])) !!}
                                        <div class="col-md-7 p-t-5">
                                            <p class="font-medium-6"> {!!   !empty($lawcases->offend_taxid) ? $lawcases->offend_taxid : null !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        {!! HTML::decode(Form::label('created_by_show', 'มอก.', ['class' => 'col-md-5 control-label  text-right'])) !!}
                                        <div class="col-md-7 p-t-5 ">
                                            <p class="font-medium-6"> {!!   !empty($lawcases->tb3_tisno) ? $lawcases->tb3_tisno : null !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        {!! HTML::decode(Form::label('created_by_show', 'ผลิตภัณฑ์', ['class' => 'col-md-5 control-label  text-right'])) !!}
                                        <div class="col-md-7 p-t-5">
                                            <p class="font-medium-6"> {!!   !empty($lawcases->tis->tb3_TisThainame) ? $lawcases->tis->tb3_TisThainame : null !!}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        {!! HTML::decode(Form::label('created_by_show', 'มาตราความผิด', ['class' => 'col-md-5 control-label  text-right'])) !!}
                                        <div class="col-md-7 p-t-5 ">
                                            <p class="font-medium-6"> {!! !empty($lawcases->law_cases_result_to->OffenseSectionNumber)   ?  implode(", ",$lawcases->law_cases_result_to->OffenseSectionNumber)  : null  !!} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        {!! HTML::decode(Form::label('created_by_show', 'การจับกุม', ['class' => 'col-md-5 control-label  text-right'])) !!}
                                        <div class="col-md-7 p-t-5">
                                            <p class="font-medium-6"> {!! !empty($lawcases->law_basic_arrest_to->title) ? $lawcases->law_basic_arrest_to->title : null !!}</p>
                                        </div>
                                    </div>
                                </div>
                    
                            </fieldset>
                        </div>
                    </div>
                    
                    <div class="wizard-container form-horizontal">

                        <div class="card wizard-card" data-color="blue">
                    
                            <div class="wizard-navigation">
                                <div class="progress-with-circle">
                                     <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3" style="width: 21%;"></div>
                                </div>
                                <ul>
                                    <li class="{{ @$lawcases->active1 }}">
                                        <a href="#calculate" data-toggle="tab">
                                            <div class="icon-circle">
                                                <i class="ti-money"></i>
                                            </div>
                                            คำนวน
                                        </a>
                                    </li>
                                    <li class="{{ @$lawcases->active2 }}">
                                        <a href="#fact" data-toggle="tab">
                                            <div class="icon-circle">
                                                <i class="ti-clipboard"></i>
                                            </div>
                                            พิมพ์หนังสือข้อเท็จจริง
                                        </a>
                                    </li>
                                    <li  class="{{ @$lawcases->active3 }}">
                                        <a href="#comparison" data-toggle="tab">
                                            <div class="icon-circle">
                                                <i class="ti-file"></i>
                                            </div>
                                            พิมพ์หนังสือเปรียบเทียบ
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">

                                <div class="tab-pane {{ @$lawcases->active1 }}" id="calculate">
                                    <fieldset class="white-box">
                                        <legend class="legend"> <h4>คำนวน</h4></legend>

                                        {!! Form::model($lawcases, [
                                            'method' => 'PATCH',
                                            'url' => ['/law/cases/compares/calculate-update', $lawcases->id],
                                            'class' => 'form-horizontal',
                                            'files' => true,
                                            'id' =>'calculate_form'
                                        ]) !!}

                                        @include ('laws.cases.compares.forms.calculate')

                                        {!! Form::close() !!}
                    
                                    </fieldset>
                                </div>
                                <div class="tab-pane {{ @$lawcases->active2 }}" id="fact">
                                    <fieldset class="white-box">
                                        <legend class="legend"> <h4>พิมพ์หนังสือข้อเท็จจริง</h4></legend>

                                        {!! Form::model($lawcases, [
                                            'method' => 'PATCH',
                                            'url' => ['/law/cases/compares/fact-update', $lawcases->id],
                                            'class' => 'form-horizontal',
                                            'files' => true,
                                            'id' =>'fact_form'
                                        ]) !!}

                                        @include ('laws.cases.compares.forms.fact')

                                        {!! Form::close() !!}

                                    </fieldset>
                                </div>
                                <div class="tab-pane {{ @$lawcases->active3 }}" id="comparison">
                                    <fieldset class="white-box">
                                        <legend class="legend"> <h4>พิมพ์หนังสือเปรียบเทียบ</h4></legend>
                    
                                        {!! Form::model($lawcases, [
                                            'method' => 'PATCH',
                                            'url' => ['/law/cases/compares/printing-update', $lawcases->id],
                                            'class' => 'form-horizontal',
                                            'files' => true,
                                            'id' =>'printing_form'
                                        ]) !!}

                                        @include ('laws.cases.compares.forms.printing')

                                        {!! Form::close() !!}

                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/bootstrap-wizard/js/jquery.bootstrap.wizard.js') }}"></script>

    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>

    <script src="{{ asset('js/function.js') }}"></script>

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {


            // พิมพ์หนังสือเปรียบเทียบ
            $('#printing_form').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {
                 return true;
            });


            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy'
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

                }
	        });


            IsInputNumber();


        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function IsInputNumber() {
            // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
            String.prototype.replaceAll = function(search, replacement) {
                var target = this;
                return target.replace(new RegExp(search, 'g'), replacement);
            }; 
              
            var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน 
                var s_inum=new String(inum); 
                var num2=s_inum.split("."); 
                var n_inum=""; 
                if(num2[0]!=undefined){
                    var l_inum=num2[0].length; 
                    for(i=0;i<l_inum;i++){ 
                        if(parseInt(l_inum-i)%3==0){ 
                        if(i==0){ 
                            n_inum+=s_inum.charAt(i); 
                        }else{ 
                            n_inum+=","+s_inum.charAt(i); 
                        } 
                        }else{ 
                            n_inum+=s_inum.charAt(i); 
                        } 
                    } 
                }else{
                    n_inum=inum;
                }
                if(num2[1]!=undefined){ 
                    n_inum+="."+num2[1]; 
                }
                return n_inum; 
            } 

            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า 
            $(".input_amount").on("keypress",function(e){
                var eKey = e.which || e.keyCode;
                if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                }
            }); 
            
            // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ 
            $(".input_amount").on("change",function(){
                var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                if(thisVal != ''){
                    if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                        thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                        thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                    }else{ // ถ้าไม่มีคอมม่า
                        thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข 
                    } 
                    thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                    $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                    $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                }else{
                    $(this).val('');
                }
            });
        }
    </script>
@endpush

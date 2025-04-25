@extends('layouts.master')

@push('css')
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/jqueryui/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css">


    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />


    <style>

        .datepicker-switch{
            color: black;
        }

        .prev {
            color: black;
        }
        .next {
            color: black;
        }

        .dow{
            color: black;
        }

        #rootwizard .nav.nav-pills {
            margin-bottom: 25px;
        }

        .label-filter{
            margin-top: 7px;
        }

        .help-block {
            display: block;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .nav-pills>li>a{
            cursor: default;;
            background-color: inherit;
        }
        .nav-pills>li>a:focus,.nav-tabs>li>a:focus, .nav-pills>li>a:hover, .nav-tabs>li>a:hover {
            border: 1px solid transparent!important;
            background-color: inherit!important;
        }

        .has-error .help-block {
            color: #EF6F6C;
        }



        /* for buttton */


        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
            margin-left: 0;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
            right: 0px;
        }



        .onoffswitch1-checkbox:checked + .onoffswitch1-label .onoffswitch1-inner {
            margin-left: 0;
        }

        .onoffswitch1-checkbox:checked + .onoffswitch1-label .onoffswitch1-switch {
            right: 0px;
        }



        .onoffswitch2-checkbox:checked + .onoffswitch2-label .onoffswitch2-inner {
            margin-left: 0;
        }

        .onoffswitch2-checkbox:checked + .onoffswitch2-label .onoffswitch2-switch {
            right: 0px;
        }



        .onoffswitch3-inner > span {
            display: block; float: left; position: relative; width: 50%; height: 30px; padding: 0; line-height: 30px;
            font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
        }

        .onoffswitch3-inner .onoffswitch3-active {
            padding-left: 10px;
            background-color: #EEEEEE; color: #FFFFFF;
        }

        .onoffswitch3-inner .onoffswitch3-inactive {
            padding-right: 10px;
            background-color: #EEEEEE; color: #FFFFFF;
            text-align: right;
        }


        .onoffswitch3-active .onoffswitch3-switch {
            background: #27A1CA; left: 0;
        }
        .onoffswitch3-inactive .onoffswitch3-switch {
            background: #A1A1A1; right: 0;
        }

        .onoffswitch3-active .onoffswitch3-switch:before {
            content: " "; position: absolute; top: 0; left: 18px;
            border-style: solid; border-color: #27A1CA transparent transparent #27A1CA; border-width: 15px 9px;
        }


        .onoffswitch3-inactive .onoffswitch3-switch:before {
            content: " "; position: absolute; top: 0; right: 18px;
            border-style: solid; border-color: transparent #A1A1A1 #A1A1A1 transparent; border-width: 15px 9px;
        }


        .onoffswitch3-checkbox:checked + .onoffswitch3-label .onoffswitch3-inner {
            margin-left: 0;
        }


        .onoffswitch4-checkbox:checked + .onoffswitch4-label .onoffswitch4-inner {
            margin-left: 0;
        }

        .onoffswitch4-checkbox:checked + .onoffswitch4-label .onoffswitch4-switch {
            right: 0px;
        }



        .cmn-toggle
        {
            position: absolute;
            margin-left: -9999px;
            visibility: hidden;
        }

        .cmn-toggle + label
        {
            display: block;
            position: relative;
            cursor: pointer;
            outline: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        input.cmn-toggle-round-flat + label
        {
            padding: 2px;
            width: 75px;
            height: 30px;
            background-color: #dddddd;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:before, input.cmn-toggle-round-flat + label:after
        {
            display: block;
            position: absolute;
            content: "";
        }

        input.cmn-toggle-round-flat + label:before
        {
            top: 2px;
            left: 2px;
            bottom: 2px;
            right: 2px;
            background-color: #fff;
            -webkit-border-radius: 60px;
            -moz-border-radius: 60px;
            -ms-border-radius: 60px;
            -o-border-radius: 60px;
            border-radius: 60px;
            -webkit-transition: background 0.4s;
            -moz-transition: background 0.4s;
            -o-transition: background 0.4s;
            transition: background 0.4s;
        }

        input.cmn-toggle-round-flat + label:after
        {
            top: 4px;
            left: 4px;
            bottom: 4px;
            width: 22px;
            background-color: #dddddd;
            -webkit-border-radius: 52px;
            -moz-border-radius: 52px;
            -ms-border-radius: 52px;
            -o-border-radius: 52px;
            border-radius: 52px;
            -webkit-transition: margin 0.4s, background 0.4s;
            -moz-transition: margin 0.4s, background 0.4s;
            -o-transition: margin 0.4s, background 0.4s;
            transition: margin 0.4s, background 0.4s;
        }

        input.cmn-toggle-round-flat:checked + label
        {
            background-color: #27A1CA;
        }

        input.cmn-toggle-round-flat:checked + label:after
        {
            margin-left: 45px;
            background-color: #27A1CA;
        }

        div.switch5 { clear: both; margin: 0px 0px; }
        div.switch5 > input.switch:empty { margin-left: -999px; }
        div.switch5 > input.switch:empty ~ label { position: relative; float: left; line-height: 1.6em; text-indent: 4em; margin: 0.2em 0px; cursor: pointer; -moz-user-select: none; }
        div.switch5 > input.switch:empty ~ label:before, input.switch:empty ~ label:after { position: absolute; display: block; top: 0px; bottom: 0px; left: 0px; content: "off"; width: 3.6em; height: 1.5em; text-indent: 2.4em; color: rgb(153, 0, 0); background-color: rgb(204, 51, 51); border-radius: 0.3em; box-shadow: 0px 0.2em 0px rgba(0, 0, 0, 0.3) inset; }
        div.switch5 > input.switch:empty ~ label:after { content: " "; width: 1.4em; height: 1.5em; top: 0.1em; bottom: 0.1em; text-align: center; text-indent: 0px; margin-left: 0.1em; color: rgb(255, 136, 136); background-color: rgb(255, 255, 255); border-radius: 0.15em; box-shadow: 0px -0.2em 0px rgba(0, 0, 0, 0.2) inset; transition: all 100ms ease-in 0s; }
        div.switch5 > input.switch:checked ~ label:before { content: "on"; text-indent: 0.5em; color: rgb(102, 255, 102); background-color: rgb(51, 153, 51); }
        div.switch5 > input.switch:checked ~ label:after { margin-left: 2.1em; color: rgb(102, 204, 102); }
        div.switch5 > input.switch:focus ~ label { color: rgb(0, 0, 0); }
        div.switch5 > input.switch:focus ~ label:before { box-shadow: 0px 0px 0px 3px rgb(153, 153, 153); }







        .switch6 {  max-width: 17em;  margin: 0 auto; }
        .switch6-light > span, .switch-toggle > span {  color: #000000; }
        .switch6-light span span, .switch6-light label, .switch-toggle span span, .switch-toggle label {  color: #2b2b2b; }

        .switch-toggle a,
        .switch6-light span span { display: none; }

        .switch6-light { display: block; height: 30px; position: relative; overflow: visible; padding: 0px; margin-left:0px; }
        .switch6-light * { box-sizing: border-box; }
        .switch6-light a { display: block; transition: all 0.3s ease-out 0s; }

        .switch6-light label,
        .switch6-light > span { line-height: 30px; vertical-align: middle;}

        .switch6-light label {font-weight: 700; margin-bottom: px; max-width: 100%;}

        .switch6-light input:focus ~ a, .switch6-light input:focus + label { outline: 1px dotted rgb(136, 136, 136); }
        .switch6-light input { position: absolute; opacity: 0; z-index: 5; }
        .switch6-light input:checked ~ a { right: 0%; }
        .switch6-light > span { position: absolute; left: -100px; width: 100%; margin: 0px; padding-right: 100px; text-align: left; }
        .switch6-light > span span { position: absolute; top: 0px; left: 0px; z-index: 5; display: block; width: 50%; margin-left: 100px; text-align: center; }
        .switch6-light > span span:last-child { left: 50%; }
        .switch6-light a { position: absolute; right: 50%; top: 0px; z-index: 4; display: block; width: 50%; height: 100%; padding: 0px; }


        th{
            color: white;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid">
    @csrf
    <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <a class="btn btn-success pull-right" href="{{route('bcertify.auditor')}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                    <h3 class="box-title pull-left">เพิ่มข้อมูลผู้ตรวจประเมิน</h3>
                    <div class="clearfix"></div>
                    <form id="commentForm" action="{{ route('bcertify.auditor.store') }}"
                          method="POST" enctype="multipart/form-data" class="form-horizontal">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                        <div id="rootwizard">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">ข้อมูลส่วนตัว</a></li>
                                <li><a href="#tab2" data-toggle="tab">การศึกษา</a></li>
                                <li><a href="#tab3" data-toggle="tab">การฝึกอบรม</a></li>
                                <li><a href="#tab4" data-toggle="tab">ความเชี่ยวชาญ</a></li>
                                <li><a href="#tab5" data-toggle="tab">ประสบการณ์การทำงาน</a></li>
                                <li><a href="#tab6" data-toggle="tab">ประสบการณ์การตรวจประเมิน</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label class="col-md-3 control-label label-filter text-right" for="title">คำนำหน้า:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="title" name="title" required>
                                                            <option selected >- เลือกคำนำหน้า (TH) -</option>
                                                            <option value="1">นาย</option>
                                                            <option value="2">นาง</option>
                                                            <option value="3">นางสาว</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="th_fname">ชื่อ (TH):<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control " id="th_fname" name="th_fname" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="th_lname">นามสกุล (TH):<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control " id="th_lname" name="th_lname" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="title_en">คำนำหน้า:<span class="text-danger">*</span> </label>
                                                    <input type="hidden" name="title_en" id="title_en">
                                                    <div class="col-md-9 m-t-10">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="title_en_js" name="title_en_js"  disabled>
                                                            <option selected >- เลือกคำนำหน้า (EN) -</option>
                                                            <option value="1">MR.</option>
                                                            <option value="2">MRS.</option>
                                                            <option value="3">Miss.</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="en_fname">ชื่อ (EN):<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" id="en_fname" name="en_fname" style="text-transform:uppercase" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="en_lname">นามสกุล (EN):<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control " id="en_lname" name="en_lname" style="text-transform:uppercase" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="address">ที่อยู่:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <textarea class="form-control" name="address" id="address" cols="30" rows="3" required></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    @php $provinces = \App\Models\Basic\Province::orderBy('PROVINCE_NAME','asc')->get() @endphp
                                                    <label class="col-md-3 control-label label-filter text-right" for="province">จังหวัด:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9 m-t-10">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="province" name="province" >
                                                            <option selected >- เลือกจังหวัด -</option>
                                                            @foreach($provinces as $province)
                                                                <option value="{{$province->PROVINCE_ID}}" >{{$province->PROVINCE_NAME}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="amphur">อำเภอ/เขต:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9 m-t-10">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="amphur" name="amphur" >
                                                            <option selected >- เลือกอำเภอ/เขต -</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right" for="district">ตำบล/แขวง:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9 m-t-10">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="district" name="district" >
                                                            <option selected >- เลือกตำบล/แขวง -</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-3 control-label label-filter text-right text-nowrap" for="tel">เบอร์โทรศัพท์:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control " id="tel" name="tel" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 m-t-10" style="padding-bottom: 20px">
                                                    <label class="col-md-3 control-label label-filter text-right" for="email">E-mail:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-9">
                                                        <input type="email" class="form-control " id="email" name="email" required>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <label class="col-md-4 control-label label-filter text-right text-nowrap" for="regis_number">เลขทะเบียนผู้ประเมิน: </label>
                                                    <div class="col-md-8">
                                                        {{-- <input type="text" class="form-control text-center" id="regis_number" name="regis_number" readonly placeholder="สร้าง Auto หลังบันทึก"> --}}
                                                        <input type="text" class="form-control text-center" id="regis_number" name="regis_number" placeholder="กรอกเลขทะเบียนผู้ประเมิน">
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    @php $departments = \App\Models\Basic\Department::where('state',1)->get() @endphp
                                                    <label class="col-md-4 control-label label-filter text-right" for="department">หน่วยงาน:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-8 ">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="department" name="department" >
                                                            <option selected >- เลือกหน่วยงาน -</option>
                                                            @foreach($departments as $department)
                                                                <option value="{{$department->id}}" >{{$department->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10 m-b-20">
                                                    <label class="col-md-4 control-label label-filter text-right" for="position">ตำแหน่ง:<span class="text-danger">*</span> </label>
                                                    <div class="col-md-8 ">
                                                        <input type="text" class="form-control " id="position" name="position" >
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10">
                                                    <label class="col-md-4 control-label  text-right" for="choice" style="margin-top: 13px">เจ้าหน้าที่ AB: </label>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <input type="checkbox" id="choice" class="form-control" name="choice" >
                                                            </div>
                                                            <div class="col-md-10 text-left" style="margin-top: 13px;">
                                                                Yes
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-10 m-b-20" style="display: none" id="group_space">
                                                    <label class="col-md-4 control-label label-filter text-right" for="group">กลุ่ม: </label>
                                                    <div class="col-md-8 ">
                                                        <select class="form-control col-md-9 setBorder custom-select mr-sm-2 " id="group" name="group" >
                                                            <option selected value="none">- เลือกกลุ่ม -</option>
                                                            <option value="1">CB</option>
                                                            <option value="2">IB</option>
                                                            <option value="3">LAB 1 //ทดสอบ</option>
                                                            <option value="4">LAB 2 //ทดสอบ</option>
                                                            <option value="5">LAB 3 //สอบเทียบ</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group" >
                                                    <label class="col-md-4 control-label label-filter text-right" for="group">สถานะ: </label>
                                                    <div class="col-md-6" style="margin-top: 15px;">
                                                        <label>{!! Form::radio('onOrOff', '1', true, ['class'=>'check', 'data-radio'=>'iradio_square-green']) !!} เปิด</label>
                                                        <label>{!! Form::radio('onOrOff', '0', false, ['class'=>'check', 'data-radio'=>'iradio_square-red']) !!} ปิด</label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="tab-pane" id="tab2" disabled="disabled">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                                        <div class="col-md-4">
                                            <label class="col-md-4 control-label label-filter" for="year">ปีที่สำเร็จ</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control text-center" id="year" name="year" >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-md-4 control-label label-filter" for="education">วุฒิการศึกษา</label>
                                            <div class="col-md-8">
                                                <select class="form-control col-md-9 setBorder custom-select mr-sm-2 text-center" id="education" name="education" >
                                                    <option selected value="0">- เลือกวุฒิการศึกษา -</option>
                                                    <option value="1">ป.ตรี</option>
                                                    <option value="2">ป.โท</option>
                                                    <option value="3">ป.เอก</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-md-4 control-label label-filter" for="major">สาขา</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control text-center" id="major" name="major" >
                                            </div>
                                        </div>


                                        <div class="col-md-5" style="margin-top: 20px">
                                            <label class="col-md-4 control-label label-filter test-left" for="school">ชื่อสถานศึกษา</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control text-center" id="school" name="school" >
                                            </div>
                                        </div>

                                        @php $contry = \Illuminate\Support\Facades\DB::table('tb_country')->select('*')->get() @endphp
                                        <div class="col-md-4" style="margin-top: 20px">
                                            <label class="col-md-4 control-label label-filter" for="country">ประเทศ</label>
                                            <div class="col-md-8">
                                                <select name="country" id="country" class="form-control">
                                                    <option selected value="-1">- เลือกประเทศ -</option>
                                                    @foreach($contry as $show)
                                                        <option value="{{ $show->id }}" >{{ $show->title }}-{{ $show->title_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3" style="margin-top: 20px">
                                            <div class="pull-right">
                                                <button class="btn btn-success" type="button" id="addItemInformation"><i class="fa fa-plus"></i> เพิ่ม</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorEducation">
                                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <h3 style="margin-top: 15px">ประวัติการศึกษา</h3>
                                    <div class="clearfix"></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" >
                                            <thead>
                                            <tr class="bg-primary text-center" >
                                                <th class="text-center">No.</th>
                                                <th class="text-center">ปีที่สำเร็จ</th>
                                                <th class="text-center">วุฒิการศึกษา</th>
                                                <th class="text-center">สาขา</th>
                                                <th class="text-center">ชื่อสถานศึกษา</th>
                                                <th class="text-center">ประเทศ</th>
                                                <th class="text-center">เครื่องมือ</th>
                                            </tr>
                                            </thead>
                                            <tbody id="information">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>

                                </div>
                                <div class="tab-pane" id="tab3" disabled="disabled">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                                        <div class="col-md-7">
                                            <label class="col-md-3 control-label label-filter text-right" for="subject">ชื่อหลักสูตร:</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control text-center" id="subject" name="subject" >
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="col-md-3 control-label label-filter" for="institution">หน่วยงาน:</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control text-center" id="institution" name="institution" >
                                            </div>
                                        </div>

                                        <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                                            {!! Form::label('start_date', 'วันที่เริ่มอบรม:', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('start_date', null, ['class' => 'form-control mydatepicker']) !!}
                                                {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                                            {!! Form::label('end_date', 'วันที่สิ้นสุด:', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('end_date', null, ['class' => 'form-control mydatepicker']) !!}
                                                {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="margin-top: 20px">
                                            <div class="pull-right">
                                                <button class="btn btn-success" type="button" id="add_history"><i class="fa fa-plus"></i> เพิ่ม</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorTraining">
                                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <h3 style="margin-top: 15px">ประวัติการฝึกอบรม</h3>
                                    <div class="clearfix"></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" >
                                            <thead>
                                            <tr class="bg-primary text-center" >
                                                <th class="text-center">No.</th>
                                                <th class="text-center">วันที่อบรม</th>
                                                <th class="text-center">ชื่อหลักสูตร</th>
                                                <th class="text-center">หน่วยงาน</th>
                                                <th class="text-center"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="history">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>

                                </div>
                                <div class="tab-pane" id="tab4" disabled="disabled">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                                        <div class="col-md-6 m-t-15">
                                            <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_type">ประเภทการตรวจประเมิน:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_type" id="expertise_type">
                                                    <option value="0">เลือกประเภทการตรวจประเมิน</option>
                                                    <option value="1">CB</option>
                                                    <option value="2">IB</option>
                                                    <option value="3">LAB สอบเทียบ</option>
                                                    <option value="4">LAB ทดสอบ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" >
                                            <label class="col-md-3 control-label label-filter text-right" for="expertise_standard">มาตรฐาน:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="expertise_standard" name="expertise_standard" disabled>
                                                    <option selected value="0">เลือกมาตรฐาน</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_branch">
                                            <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_branch">สาขา:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_branch" id="expertise_branch">
                                                    <option>เลือกสาขา</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_scope">
                                            <label class="col-md-3 control-label label-filter text-right text-nowrap" for="expertise_scope">ขอบข่าย:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_scope" id="expertise_scope" disabled>
                                                    <option>เลือกขอบข่าย</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_inspection">
                                            <label class="col-md-3 control-label label-filter text-right text-nowrap" for="expertise_inspection">ประเภทหน่วยตรวจ:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_inspection" id="expertise_inspection">
                                                    <option>เลือกประเภทหน่วยตรวจ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_category">
                                            <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_category">หมวดหมู่การตรวจ:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_category" id="expertise_category">
                                                    <option>เลือกหมวดหมู่การตรวจ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_calibration">
                                            <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_calibration">รายการสอบเทียบ:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_calibration" id="expertise_calibration" disabled>
                                                    <option>เลือกรายการสอบเทียบ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_product">
                                            <label class="col-md-3 control-label label-filter text-right text-nowrap" for="expertise_product">ผลิตภัณฑ์:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_product" id="expertise_product" disabled>
                                                    <option>เลือกผลิตภัณฑ์</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_test">
                                            <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_test">รายการทดสอบ:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_test" id="expertise_test" disabled>
                                                    <option>เลือกรายการทดสอบ</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-7 m-t-15" >
                                            <label class="col-md-4 control-label label-filter text-right" for="specialized_expertise">ความเชี่ยวชาญเฉพาะด้าน:</label>
                                            <div class="col-md-8">
                                                <textarea name="specialized_expertise" id="specialized_expertise" cols="30" rows="3" class="form-control form-control-lg"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-5 m-t-15" >
                                            @php $data = \App\Models\Bcertify\StatusAuditor::where('state',1)->get() @endphp
                                            <label class="col-md-4 control-label label-filter text-right text-nowrap" for="expertise_status">สถานะผู้ตรวจประเมิน:</label>
                                            <div class="col-md-8">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="expertise_status" name="expertise_status" >
                                                    <option selected value="0">เลือกสถานะผู้ตรวจประเมิน</option>
                                                    @foreach($data as $show)
                                                        <option value="{{$show->id}}">{{$show->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <p class="text-danger col-md-4 text-center" style="font-size: 10px">(กดอีกครั้งเพื่อลบ)</p>
                                        </div>
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4" id="total_status">
                                        </div>

                                        <div class="col-md-10"></div>
                                        <div class="col-md-2" style="margin-top: 20px">
                                            <div class="pull-right">
                                                <button class="btn btn-success" type="button" id="add_expertise" disabled><i class="fa fa-plus"></i> เพิ่ม</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorExpertise">
                                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                                        </div>

                                        <div class="clearfix"></div>
                                        <hr>
                                        {{--  Table ความเชี่ยวชาญ CB --}}
                                        <div style="display: none;" id="viewCB">
                                            <h3 class="col-md-12" style="margin-top: 15px; padding: 0px">ข้อมูลความเชี่ยวชาญ (CB)</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive" >
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">ขอบข่าย</th>
                                                        <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_expertise_CB">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{--  Table ความเชี่ยวชาญ IB --}}
                                        <div style="display: none;" id="viewIB">
                                            <h3 style="margin-top: 15px">ข้อมูลความเชี่ยวชาญ (IB)</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">ประเภทหน่วยตรวจ</th>
                                                        <th class="text-center">หมวดหมู่การตรวจ</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_expertise_IB">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{--  Table ความเชี่ยวชาญ LAB สอบเทียบ --}}
                                        <div style="display: none;" id="viewLabExam">
                                            <h3 style="margin-top: 15px">ความเชี่ยวชาญประเภทการตรวจ LAB สอบเทียบ</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">รายการสอบเทียบ</th>
                                                        <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_expertise_lab">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{--  Table ความเชี่ยวชาญ LAB ทดสอบ --}}
                                        <div style="display: none;" id="viewLabTest">
                                            <h3 style="margin-top: 15px">ความเชี่ยวชาญประเภทการตรวจ LAB ทดสอบ</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">ผลิตภัณฑ์</th>
                                                        <th class="text-center">รายการทดสอบ</th>
                                                        <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_expertise_labTest">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>

                                    </div>

                                </div>
                                <div class="tab-pane" id="tab5" disabled="disabled">


                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                                        <div class="col-md-2">
                                            <label class="col-md-3 control-label label-filter text-right" for="experience_year">ปี:</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control text-center" id="experience_year" name="experience_year" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-md-3 control-label label-filter text-right" for="experience_position">ตำแหน่ง:</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control text-center" id="experience_position" name="experience_position" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="col-md-4 control-label label-filter text-right text-nowrap" for="experience_department">หน่วยงาน:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control text-center" id="experience_department" name="experience_department" >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="col-md-4 control-label label-filter text-right text-nowrap" for="experience_character">บทบาทหน้าที่:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control text-center" id="experience_character" name="experience_character" >
                                            </div>
                                        </div>
                                        <div class="col-md-10"></div>
                                        <div class="col-md-2" style="margin-top: 20px">
                                            <div class="pull-right">
                                                <button class="btn btn-success" type="button" id="add_experience" ><i class="fa fa-plus"></i> เพิ่ม</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorWork">
                                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <h3 style="margin-top: 15px">ประสบการณ์การทำงาน</h3>
                                    <div class="clearfix"></div>
                                    <div class="table-responsive">
                                        <table class="table table-striped" >
                                            <thead>
                                            <tr class="bg-primary text-center" >
                                                <th class="text-center">No.</th>
                                                <th class="text-center">ปีที่ทำงาน</th>
                                                <th class="text-center">ตำแหน่ง</th>
                                                <th class="text-center">หน่วยงาน</th>
                                                <th class="text-center">บทบาทหน้าที่</th>
                                                <th class="text-center"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="experience">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clearfix"></div>

                                </div>
                                <div class="tab-pane" id="tab6" disabled="disabled">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                                        <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                                            {!! Form::label('start_check_date', 'วันที่ตรวจประเมิน:', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('start_check_date', null, ['class' => 'form-control mydatepicker']) !!}
                                                {!! $errors->first('start_check_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                                            {!! Form::label('end_check_date', 'ถึง:', ['class' => 'col-md-4 control-label']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('end_check_date', null, ['class' => 'form-control mydatepicker']) !!}
                                                {!! $errors->first('end_check_date', '<p class="help-block">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15">
                                            <label class="col-md-5 control-label label-filter text-right text-nowrap" for="type_of_check">ประเภทการตรวจประเมิน:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center type_of_check"  name="type_of_check" >
                                                    <option selected value="0">เลือกประเภทการตรวจประเมิน</option>
                                                    <option value="1">CB</option>
                                                    <option value="2">IB</option>
                                                    <option value="3">LAB สอบเทียบ</option>
                                                    <option value="4">LAB ทดสอบ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" >
                                            <label class="col-md-3 control-label label-filter text-right" for="check_standard">มาตรฐาน:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_standard" name="check_standard" disabled>
                                                    <option selected>เลือกมาตรฐาน</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- hidden : show when choose ประเภทการตรวจประเมิน --}}
                                        <div class="col-md-6 m-t-15" style="display: none;" id="branch">
                                            <label class="col-md-5 control-label label-filter text-right" for="check_branch">สาขา:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_branch" name="check_branch" >
                                                    <option selected>เลือกสาขา</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none;" id="scope">
                                            <label class="col-md-3 control-label label-filter text-right" for="check_scope">ขอบข่าย:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_scope" name="check_scope" disabled>
                                                    <option selected>เลือกขอบข่าย</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none;" id="calibration">
                                            <label class="col-md-4 control-label label-filter text-right" for="check_calibration">รายการสอบเทียบ:</label>
                                            <div class="col-md-8">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_calibration" name="check_calibration" disabled>
                                                    <option selected>เลือกรายการสอบเทียบ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none;" id="inspection">
                                            <label class="col-md-4 control-label label-filter text-right" for="check_inspection">ประเภทหน่วยตรวจ:</label>
                                            <div class="col-md-8">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_inspection" name="check_inspection" >
                                                    <option selected>เลือกประเภทหน่วยตรวจ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none;" id="category">
                                            <label class="col-md-5 control-label label-filter text-right" for="check_category">หมวดหมู่การตรวจ:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_category" name="check_category" >
                                                    <option selected>เลือกหมวดหมู่การตรวจ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none;" id="product">
                                            <label class="col-md-3 control-label label-filter text-right" for="check_product">ผลิตภัณฑ์:</label>
                                            <div class="col-md-9">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_product" name="check_product"  disabled>
                                                    <option selected>เลือกผลิตภัณฑ์</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 m-t-15" style="display: none;" id="test">
                                            <label class="col-md-5 control-label label-filter text-right" for="check_test">รายการทดสอบ:</label>
                                            <div class="col-md-7">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_test" name="check_test" disabled>
                                                    <option selected>เลือกรายการทดสอบ</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7 m-t-15" >
                                            <label class="col-md-3 control-label label-filter text-right" for="check_role">บทบาทหน้าที่:</label>
                                            <div class="col-md-9">
                                                <textarea name="check_role" id="check_role" cols="30" rows="3" class="form-control form-control-lg"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-5 m-t-15" >
                                            @php $kind = \App\Models\Bcertify\StatusAuditor::where('kind',1)->where('state',1)->get() @endphp
                                            <label class="col-md-4 control-label label-filter text-right" for="check_status">สถานะผู้ประเมิน:</label>
                                            <div class="col-md-8">
                                                <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_status" name="check_status" >
                                                    <option selected value="0">เลือกสถานะผู้ประเมิน</option>
                                                    @foreach($kind as $show)
                                                        <option value="{{$show->id}}" >{{$show->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-10"></div>
                                        <div class="col-md-2" style="margin-top: 20px">
                                            <div class="pull-right">
                                                <button class="btn btn-success" type="button" id="add_check" disabled><i class="fa fa-plus"></i> เพิ่ม</button>
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorAssessment">
                                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                                        </div>

                                        <div class="clearfix"></div>

                                        <hr>
                                        {{--  Table CB --}}
                                        <div id="tableCheckCB" style="display: none;">
                                            <h3 class="col-md-12" style="margin-top: 15px; padding: 0px">ประสบการณ์การตรวจประเมิน CB</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">วันที่ตรวจ</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">ขอบข่าย</th>
                                                        <th class="text-center">บทบาทหน้าที่</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_cb">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{--  Table IB --}}
                                        <div id="tableCheckIB" style="display: none;">
                                            <h3 style="margin-top: 15px">ประสบการณ์การตรวจประเมิน IB</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">วันที่ตรวจ</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">ประเภทหน่วยตรวจ</th>
                                                        <th class="text-center">หมวดหมู่การตรวจ</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">บทบาทหน้าที่</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_ib">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{--  Table LAB สอบเทียบ --}}
                                        <div id="tableCheckLabExam" style="display: none;">
                                            <h3 style="margin-top: 15px">ประสบการณ์การตรวจประเมิน LAB สอบเทียบ</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">วันที่ตรวจ</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">รายการสอบเทียบ</th>
                                                        <th class="text-center">บทบาทหน้าที่</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_labcalibration">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{--  Table LAB ทดสอบ --}}
                                        <div id="tableCheckLabTest" style="display: none;">
                                            <h3 style="margin-top: 15px">ประสบการณ์การตรวจประเมิน LAB ทดสอบ</h3>
                                            <div class="clearfix"></div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" >
                                                    <thead>
                                                    <tr class="bg-primary text-center" >
                                                        <th class="text-center">No.</th>
                                                        <th class="text-center">วันที่ตรวจ</th>
                                                        <th class="text-center">มาตรฐาน</th>
                                                        <th class="text-center">สาขา</th>
                                                        <th class="text-center">ผลิตภัณฑ์</th>
                                                        <th class="text-center">รายการทดสอบ</th>
                                                        <th class="text-center">บทบาทหน้าที่</th>
                                                        <th class="text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="add_labtest">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <input type="hidden" name="education_history" id="education_history">
                                <input type="hidden" name="training" id="training">
                                <input type="hidden" name="data_experience" id="data_experience">
                                <input type="hidden" name="data_all_expertise" id="data_all_expertise">
                                <input type="hidden" name="data_all_check" id="data_all_check">

                                <ul class="pager wizard">
                                    <li class="previous"><a href="#" class="bg-danger text-white" style="border-radius: 5px">ย้อนกลับ</a></li>
                                    <li class="next"><a href="#" class="bg-success text-white" style="border-radius: 5px">ถัดไป</a></li>
                                    {{-- <li class="finish" style="display:none;"><button type="button" class="pull-right btn btn-default" id="submit_form">เสร็จสิ้น</button></li> --}}
                                    <li class=""><button type="button" class="pull-right btn btn-default" id="submit_form" style="margin: 0px 5px;">บันทึก</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>


                    @if(count($errors) > 0)
                        <div class="alert alert-danger">Errors! Please fill form with proper details</div>
                    @endif

                </div>
            </div>
        </div>

        @include('layouts.partials.right-sidebar')
    </div>
@endsection

@push('js')
    {{--    <script src="{{ asset('plugins/components/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>--}}
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/moment/moment.js')}}"></script>
    <script src="{{asset('plugins/components/jqueryui/jquery-ui.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap-wizard/1.2/jquery.bootstrap.wizard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"
            type="text/javascript"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('/js/adduser.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>



    <script>
        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            orientation: 'bottom',
        });
    </script>

    <script>
        // ajax province



        $('#province').on('change',function () {
            const select = $(this).val();
            const _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{route('bcertify.api.province')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function (result){
                    let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                    $('#amphur').empty();
                    $.each(data,function (index,value) {
                        $('#amphur').append('<option value='+value.AMPHUR_ID+' >'+value.AMPHUR_NAME+'</option>');
                    })
                }
            })

        })



        $('#amphur').on('change',function () {
            const select = $(this).val();
            const _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{route('bcertify.api.amphur')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function (result){
                    let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                    $('#district').empty();
                    $.each(data,function (index,value) {
                        $('#district').append('<option value='+value.DISTRICT_ID+' >'+value.DISTRICT_NAME+'</option>');
                    })
                }
            })
        })


        $('#title').on('change',function () {
            const select = $(this).val();
            $('#title_en_js').val(select).change();
        })

        $('#title_en_js').on('change',function () {
            $('#title_en').val($(this).val());
        })



    </script>

    <script>
        @if(\Session::has('message'))
        $.toast({
            heading: 'Success!',
            position: 'top-center',
            text: '{{session()->get('message')}}',
            loaderBg: '#70b7d6',
            icon: 'success',
            hideAfter: 3000,
            stack: 6
        });
        @endif
    </script>


    <script>
        var arr_education = [];
        var arr_history = [];
        var arr_experience = [];

        var check = 0;
        var education ;
        var number_experience = 1;
        $('#choice').on('change', function () {
            if (check === 0) {
                $('#group_space').fadeIn();
                check = 1;
            } else {
                $('#group_space').fadeOut();
                check = 0;
            }
        })


        // สำหรับหน้า การศึกษา

        $('#education').on('change',function () {
            education = $("#education :selected").text();
        });

        $('#addItemInformation').on('click', function () {
            const year = $('#year').val();
            const major = $('#major').val();
            const school = $('#school').val();
            const country = $('#country').val();
            const country_show = $('#country :selected').text();
            const token = Math.random().toString(36).substring(7);
            console.log(education);
            if (year !== "" && major !== "" && school !== "" && country !== "0") {
                $('#showErrorEducation').fadeOut()
                var obj_education = {country_show:country_show,year:year,level_education:education,major_education:major,school_name:school,country:country,token:token};
                arr_education.push(obj_education);
                getToTableEdu();
                $('#education').val(0).change();
                $('#year').val("");
                $('#major').val("");
                $('#education').val("").change();
                $('#school').val("");
                $('#country').val("-1").change();
            }
            else {
                $('#showErrorEducation').fadeIn()
            }
        })

        $(document).on('click','.clickEducation',function () {
            console.log($(this).attr('id'));
            let this_remove_edu = $(this).attr('id');
            let find_edu = arr_education.find(value => value.token === this_remove_edu);
            var index_edu = arr_education.indexOf(find_edu);
            arr_education.splice(index_edu,1);
            getToTableEdu();
            $('#year').val("");
            $('#major').val("");
            $('#education').val("").change();
            $('#school').val("");
            $('#country').val("").change();

        })

        $(document).on('click','.clickEditEducation',function () {
            console.log(arr_education);
            let this_remove_edu = $(this).attr('id');
            let find_edu = arr_education.find(value => value.token === this_remove_edu);
            var valueEducation ;
            if (find_edu['level_education'] === "ป.ตรี"){
                valueEducation = 1;
            }
            else if (find_edu['level_education'] === "ป.โท"){
                valueEducation = 2;
            }
            else if (find_edu['level_education'] === "ป.เอก"){
                valueEducation = 3;
            }
            $('#year').val(find_edu['year']);
            $('#major').val(find_edu['major_education']);
            $('#school').val(find_edu['school_name']);
            $('#country').val(find_edu['country']).change();
            $('#education').val(valueEducation).change();
            var index_edu = arr_education.indexOf(find_edu);
            arr_education.splice(index_edu,1);
            getToTableEdu();

        })

        function getToTableEdu(){
            var count_edu = 1;
            $('#information').empty();
            $.each(arr_education,function (index,value) {
                $('#information').append('<tr>' +
                    '<td class="text-center">'+count_edu+'.</td>' +
                    '<td class="text-center">'+value.year+'</td>' +
                    '<td class="text-center">'+value.level_education+'</td>' +
                    '<td>'+value.major_education+'</td>' +
                    '<td>'+value.school_name+'</td>' +
                    '<td class="text-center">'+value.country_show+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditEducation" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickEducation" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count_edu++;

            })
        }


        // สำหรับหน้า การฝึกอบรม
        $('#add_history').on('click',function () {
            const name_history = $('#subject').val();
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();
            const institution = $('#institution').val();
            const token = Math.random().toString(36).substring(7);
            if (name_history !== "" && start_date !== "" && institution !== "") {
                $('#showErrorTraining').fadeOut();
                var obj_history = {start_training:start_date,end_training:end_date,course_name:name_history,department_name:institution,token:token};
                arr_history.push(obj_history);
                getToTableHistory();
                $('#subject').val("");
                $('#start_date').val("");
                $('#end_date').val("");
                $('#institution').val("");
            }
            else {
                $('#showErrorTraining').fadeIn();
            }

        });

        $(document).on('click','.clickHistory',function () {
            let this_remove_his = $(this).attr('id');
            let find_his = arr_history.find(value => value.token === this_remove_his);
            var index_his = arr_history.indexOf(find_his);
            arr_history.splice(index_his,1);
            getToTableHistory();
            $('#subject').val("");
            $('#start_date').val("");
            $('#end_date').val("");
            $('#institution').val("");
        })

        $(document).on('click','.clickEditTraining',function () {
            console.log(arr_history);
            let this_remove_his = $(this).attr('id');
            let find_his = arr_history.find(value => value.token === this_remove_his);
            var index_his = arr_history.indexOf(find_his);
            arr_history.splice(index_his,1);
            getToTableHistory();
            $('#subject').val(find_his['course_name']);
            $('#start_date').val(find_his['start_training']);
            $('#end_date').val(find_his['end_training']);
            $('#institution').val(find_his['department_name']);
        })

        function getToTableHistory(){
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count_his = 1;
            $('#history').empty();
            $.each(arr_history,function (index,value) {
                const split_first_date = value.start_training.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                const split_second_date = value.end_training.split("/");
                const find_number_second_mount = arr_number_mount.find(element => element === split_second_date[1]);
                const index_second_mount = arr_number_mount.indexOf(find_number_second_mount);
                const show_second_year = parseInt(split_second_date[2])+543;

                // console.log(arr_mount[index_first_mount]);
                $('#history').append('<tr>' +
                    '<td class="text-center">'+count_his+'.</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+" - "+
                    split_second_date[0]+" "+arr_mount[index_second_mount]+" "+show_second_year+'</td>' +
                    '<td>'+value.course_name+'</td>' +
                    '<td>'+value.department_name+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditTraining" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickHistory" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');

                count_his++;

            });
        }


        // สำหรับหน้า ประสบการณ์

        $('#add_experience').on('click',function () {
            const experience_year = $('#experience_year').val();
            const experience_position = $('#experience_position').val();
            const experience_department = $('#experience_department').val();
            const experience_character = $('#experience_character').val();
            const token = Math.random().toString(36).substring(7);

            if (experience_year !== "" && experience_position !== "" && experience_department !== "" && experience_character !== ""){
                $('#showErrorWork').fadeOut();
                var obj_experience = {year:experience_year,position:experience_position,department:experience_department,role:experience_character,token:token};
                arr_experience.push(obj_experience);
                getToTableExperience();
                $('#experience_year').val("");
                $('#experience_position').val("");
                $('#experience_department').val("");
                $('#experience_character').val("");
            }
            else {
                $('#showErrorWork').fadeIn();
            }

        })

        $(document).on('click','.clickEx',function () {
            let this_remove_ex = $(this).attr('id');
            let find_ex = arr_experience.find(value => value.token === this_remove_ex);
            var index_ex = arr_experience.indexOf(find_ex);
            arr_experience.splice(index_ex,1);
            getToTableExperience();
            $('#experience_year').val("");
            $('#experience_position').val("");
            $('#experience_department').val("");
            $('#experience_character').val("");
        })


        $(document).on('click','.clickEditWork',function () {
            console.log(arr_experience);
            let this_remove_ex = $(this).attr('id');
            let find_ex = arr_experience.find(value => value.token === this_remove_ex);
            var index_ex = arr_experience.indexOf(find_ex);
            arr_experience.splice(index_ex,1);
            getToTableExperience();
            $('#experience_year').val(find_ex['year']);
            $('#experience_position').val(find_ex['position']);
            $('#experience_department').val(find_ex['department']);
            $('#experience_character').val(find_ex['role']);
        })

        function getToTableExperience() {
            var count_ex = 1;
            $('#experience').empty()
            $.each(arr_experience,function (index,value) {
                $('#experience').append('<tr>' +
                    '<td class="text-center">'+count_ex+'.</td>' +
                    '<td class="text-center">'+value.year+'</td>' +
                    '<td>'+value.position+'</td>' +
                    '<td>'+value.department+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditWork" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickEx" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');

                count_ex++;

            })

        }


        // Api query มาตราฐาน จาก ประเภทการตรวจประเมิน



        {{--  script ความเชี่ยวชาญ  --}}
        function clearFormExpertise() {
            $('#view_expertise_branch').hide();
            $('#view_expertise_scope').hide();
            $('#view_expertise_inspection').hide();
            $('#view_expertise_category').hide();
            $('#view_expertise_calibration').hide();
            $('#view_expertise_test').hide();
            $('#view_expertise_product').hide();
        }


        var checkEditStatus = 0;
        var branchEditNumber ;
        var standardEditNumber ;
        var typeEditNumber ;
        var catEditNumber ;
        var listCalibration ;
        var productExamNumber ;
        var listTestNumber ;
        var setScopeExpertiseName = "";
        function expertiseApiStandard(select,_token) {
            $.ajax({
                url:"{{route('bcertify.api.standard')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function (result){
                    let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                    $('#expertise_branch').empty();
                    $('#expertise_standard').empty();
                    $("#expertise_standard").prop('disabled', false);
                    // console.log(data);
                    $('#expertise_branch').append('<option value="0">- เลือกสาขา -</option>');
                    $('#expertise_standard').append('<option value="0">- มาตราฐาน -</option>');
                    $.each(data[0],function(index, value){
                        // console.log(value.title);
                        $('#expertise_branch').append('<option value='+value.id+' >'+value.title+'</option>');
                    });
                    $.each(data[1],function(index, value){
                        // console.log(value.title);
                        $('#expertise_standard').append('<option value='+value.id+' >'+value.title+'</option>');
                    });

                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();

                    if (checkEditStatus === 1){
                        $('#expertise_branch').val(branchEditNumber).change();
                        $('#expertise_standard').val(standardEditNumber).change();
                    }

                }
            })
        }

        var check_expertise_type = "";
        $('#expertise_type').on('change',function () {
            console.log($(this).val());
            const select = $(this).val();
            const _token = $('input[name="_token"]').val();

            console.log(select);
            console.log(_token);

            clearFormExpertise();
            $("#add_expertise").prop('disabled', false);
            expertiseApiStandard(select,_token);
            $('#view_expertise_branch').fadeIn();
            check_expertise_type = select;
            if (select === "1"){
                $('#view_expertise_scope').fadeIn();
            }
            else if (select === "2"){
                $('#view_expertise_inspection').fadeIn();
                $('#view_expertise_category').fadeIn();
                $.ajax({
                    url:"{{route('bcertify.api.inspection')}}",
                    method:"POST",
                    data:{select_branch:select,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_inspection").empty();
                        $("#expertise_category").empty();
                        $('#expertise_inspection').append('<option value="0">- เลือกประเภทหน่วยตรวจ -</option>');
                        $('#expertise_category').append('<option value="0">- เลือกหมวดหมู่การตรวจ -</option>');
                        $.each(data[0],function(index, value){
                            $('#expertise_inspection').append('<option value='+value.id+' >'+value.title+'</option>');
                        });
                        $.each(data[1],function(index, value){
                            $('#expertise_category').append('<option value='+value.id+' >'+value.title+'</option>');
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_inspection').val(typeEditNumber).change();
                            $('#expertise_category').val(catEditNumber).change();
                            checkEditStatus = 0;

                        }


                    }
                })
            }
            else if (select === "3"){
                $('#view_expertise_calibration').fadeIn();
            }
            else if (select === "4"){
                $('#view_expertise_test').fadeIn();
                $('#view_expertise_product').fadeIn();
            }
        })



        $('#expertise_branch').on('change',function () {
            console.log($(this).val());
            const select_expertise_branch = $("#expertise_branch :selected").text();
            const _token = $('input[name="_token"]').val();
            if (check_expertise_type === "1"){
                $.ajax({
                    url:"{{route('bcertify.api.scope')}}",
                    method:"POST",
                    data:{select_branch:select_expertise_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_scope").prop('disabled', false);
                        $("#expertise_scope").empty();
                        $('#expertise_scope').append('<option value="0">- เลือกขอบข่าย -</option>');
                        $.each(data,function(index, value){
                            $.each(value,function (index,newValue) {
                                $('#expertise_scope').append('<option value='+newValue.id+'>'+newValue.title+'</option>');
                            })
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_scope').append('<option value='+setScopeExpertiseName+'>'+setScopeExpertiseName+'</option>');
                            $('#expertise_scope').val(setScopeExpertiseName).change();
                            checkEditStatus = 0;
                        }
                    }
                })
            }
            else if (check_expertise_type === "3"){
                $.ajax({
                    url:"{{route('bcertify.api.calibration')}}",
                    method:"POST",
                    data:{select_branch:select_expertise_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_calibration").prop('disabled', false);
                        $("#expertise_calibration").empty();
                        $('#expertise_calibration').append('<option value="0">- เลือกรายการสอบเทียบ -</option>');
                        $.each(data,function(index, value){
                            $('#expertise_calibration').append('<option  value='+value.id+' >'+value.title+'</option>');
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_calibration').val(listCalibration).change();
                            checkEditStatus = 0;

                        }
                    }
                })
            }
            else if (check_expertise_type === "4"){
                $.ajax({
                    url:"{{route('bcertify.api.product')}}",
                    method:"POST",
                    data:{select_branch:select_expertise_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_product").prop('disabled', false);
                        $("#expertise_product").empty();
                        $('#expertise_product').append('<option value="0">เลือกผลิตภัณฑ์</option>');

                        $("#expertise_test").prop('disabled', false);
                        $("#expertise_test").empty();
                        $('#expertise_test').append('<option value="0">เลือกรายการทดสอบ</option>');

                        $.each(data[0],function(index, value){
                            $('#expertise_product').append('<option value='+value.id+' >'+value.title+'</option>');
                        });
                        $.each(data[1],function(index, value){
                            $('#expertise_test').append('<option value='+value.id+' >'+value.title+'</option>');
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_product').val(productExamNumber).change();
                            $('#expertise_test').val(listTestNumber).change();
                            checkEditStatus = 0;

                        }

                    }
                })
            }
        })

        var keep_status = [];
        var keep_value_status_expertise = [];
        $('#expertise_status').on('change',function () {
            $('#total_status').empty();
            if (!keep_status.includes($("#expertise_status :selected").text()) && $(this).val() !== "0"){
                keep_status.push($("#expertise_status :selected").text());
                keep_value_status_expertise.push($(this).val());
                console.log(keep_status);
            }

            $.each(keep_status,function (index,value) {
                $('#total_status').append('<button class="col-md-6 bg-primary text-white text-nowrap text-center clickDelete" id='+value+' style="border: 1px solid blue; padding: 5px 15px ; border-radius: 20px; font-size: 11px">'+value+'</button>');
            })
        })

        $(document).on('click', '.clickDelete', function () {
            var number_index = keep_status.indexOf($(this).attr('id'));
            keep_status.splice(number_index,1);
            keep_value_status_expertise.splice(number_index,1);
            console.log(number_index);
            console.log($(this).attr('id'));
            console.log(keep_status);
            $('#total_status').empty();
            $.each(keep_status,function (index,value) {
                $('#total_status').append('<button class="col-md-6 bg-primary text-white text-nowrap text-center clickDelete" id='+value+' style="border: 1px solid blue; padding: 5px 15px ; border-radius: 20px; font-size: 11px">'+value+'</button>');
            });
            $('#expertise_status').val(0).change();
        })


        // --------------------------------------------------------------------------------------------------------
        var arr_expertise_CB = [];
        var arr_expertise_IB = [];
        var arr_expertise_LabExam = [];
        var arr_expertise_LabTest = [];

        function addToTableExpertiseCB(){
            $('#add_expertise_CB').empty();
            var count = 1;
            $.each(arr_expertise_CB,function (index,value) {
                $('#add_expertise_CB').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.scope_name+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditExpertiseCB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashExpertiseCB" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        function addToTableExpertiseIB(){
            $('#add_expertise_IB').empty();
            var count = 1;
            $.each(arr_expertise_IB,function (index,value) {
                $('#add_expertise_IB').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.cat+'</td>' +
                    '<td>'+value.typeCheck+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditExpertiseIB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashExpertiseIB" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        function addToTableExpertiseLabExam(){
            $('#add_expertise_lab').empty();
            var count = 1;
            $.each(arr_expertise_LabExam,function (index,value) {
                $('#add_expertise_lab').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.listCalibation+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditExpertiseLabExam" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashExpertiseLabExam" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        function addToTableExpertiseLabTest(){
            $('#add_expertise_labTest').empty();
            var count = 1;
            $.each(arr_expertise_LabTest,function (index,value) {
                $('#add_expertise_labTest').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.exProduct+'</td>' +
                    '<td>'+value.testList+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditExpertiseLabTest" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashExpertiseLabTest" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        $('#add_expertise').on('click',function () {
            const show_type = $('#expertise_type').val();
            const show_status = keep_status.join(",");
            const show_value_status_expertise = keep_value_status_expertise.join(',');
            const show_branch = $("#expertise_branch :selected").text();
            const show_branch_value = $("#expertise_branch").val();
            const show_standard = $("#expertise_standard :selected").text();
            const show_standard_value = $("#expertise_standard").val();
            const show_specialized = $('#specialized_expertise').val();
            const token =  Math.random().toString(36).substring(7);
            if (check_expertise_type === "1"){
                const show_scope = $("#expertise_scope :selected").text();
                const show_scope_value = $('#expertise_scope').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_scope !== "" && show_specialized !== "" && $('#expertise_status').val() !== "0" && $('#expertise_scope').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewCB').fadeIn();
                    var obj_expertise_CB = {auditor_status:show_value_status_expertise,show_scope_value:show_scope_value,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_type:show_type,standard:show_standard,showBranch:show_branch,scope_name:show_scope,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_CB.push(obj_expertise_CB);
                    console.log(arr_expertise_CB);
                    addToTableExpertiseCB();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_scope').val(0).change();
                    $('#expertise_status').val(0).change();
                } else {
                    $('#showErrorExpertise').fadeIn();
                }

            }
            else if (check_expertise_type === "2"){
                const show_inspection= $('#expertise_inspection :selected').text();
                const show_inspection_value = $('#expertise_inspection').val();
                const show_category= $('#expertise_category :selected').text();
                const show_category_value = $('#expertise_category').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_category !== "" && show_specialized !== "" && show_inspection !== "" && $('#expertise_status').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewIB').fadeIn();
                    var obj_expertise_IB = {auditor_status:show_value_status_expertise,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_inspection_value:show_inspection_value,show_category_value:show_category_value,show_type:show_type,standard:show_standard,cat:show_category,typeCheck:show_inspection,showBranch:show_branch,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_IB.push(obj_expertise_IB);
                    addToTableExpertiseIB();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_inspection').val(0).change();
                    $('#expertise_category').val(0).change();
                    $('#expertise_status').val(0).change();
                } else {
                    $('#showErrorExpertise').fadeIn();
                }

            }
            else if (check_expertise_type === "3"){
                const show_calibration = $('#expertise_calibration :selected').text();
                const show_calibration_value = $('#expertise_calibration').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_specialized !== "" && show_calibration !== "" && $('#expertise_status').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewLabExam').fadeIn();
                    var obj_expertise_LabExam = {auditor_status:show_value_status_expertise,show_calibration_value:show_calibration_value,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_type:show_type,standard:show_standard,showBranch:show_branch,listCalibation:show_calibration,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_LabExam.push(obj_expertise_LabExam);
                    addToTableExpertiseLabExam();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_calibration').val(0).change();
                    $('#expertise_status').val(0).change();
                } else {
                    $('#showErrorExpertise').fadeIn();
                }
            }
            else if (check_expertise_type === "4"){
                const show_product = $('#expertise_product :selected').text();
                const show_product_value = $('#expertise_product').val();
                const show_test = $('#expertise_test :selected').text();
                const show_test_value = $('#expertise_test').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_specialized !== "" && show_product !== "" && show_test !== "" && $('#expertise_status').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewLabTest').fadeIn();
                    var obj_expertise_LabTest = {auditor_status:show_value_status_expertise,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_product_value:show_product_value,show_test_value:show_test_value,show_type:show_type,standard:show_standard,showBranch:show_branch,exProduct:show_product,testList:show_test,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_LabTest.push(obj_expertise_LabTest);
                    addToTableExpertiseLabTest();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_product').val(0).change();
                    $('#expertise_test').val(0).change();
                    $('#expertise_status').val(0).change();
                }
                else {
                    $('#showErrorExpertise').fadeIn();
                }
            }

            $('#total_status').empty();
            keep_status = [];
            $('#specialized_expertise').val("");
        })


        // click delete expertise CB
        $(document).on('click','.clickTrashExpertiseCB',function () {
            console.log(arr_expertise_CB);
            let this_remove_expertise_cb = $(this).attr('id');
            let find_expertise_cb = arr_expertise_CB.find(value => value.token === this_remove_expertise_cb);
            var index_expertise_cb = arr_expertise_CB.indexOf(find_expertise_cb);
            arr_expertise_CB.splice(index_expertise_cb,1);
            addToTableExpertiseCB();
            if (arr_expertise_CB.length === 0){
                $('#viewCB').fadeOut();
            }
        })
        // click edit expertise CB
        $(document).on('click','.clickEditExpertiseCB',function () {
            console.log(arr_expertise_CB);
            let this_remove_expertise_cb = $(this).attr('id');
            let find_expertise_cb = arr_expertise_CB.find(value => value.token === this_remove_expertise_cb);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_cb['auditor_status'].split(",");
            branchEditNumber = find_expertise_cb['show_branch_value'];
            standardEditNumber = find_expertise_cb['show_standard_value'];
            setScopeExpertiseName = find_expertise_cb['scope_name'];
            $('#expertise_type').val(1).change();
            $('#specialized_expertise').val(find_expertise_cb['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            var index_expertise_cb = arr_expertise_CB.indexOf(find_expertise_cb);
            arr_expertise_CB.splice(index_expertise_cb,1);
            addToTableExpertiseCB();
            if (arr_expertise_CB.length === 0){
                $('#viewCB').fadeOut();
            }
        })

        // click delete expertise IB
        $(document).on('click','.clickTrashExpertiseIB',function () {
            console.log(arr_expertise_IB);
            let this_remove_expertise_ib = $(this).attr('id');
            let find_expertise_ib = arr_expertise_IB.find(value => value.token === this_remove_expertise_ib);
            var index_expertise_ib = arr_expertise_IB.indexOf(find_expertise_ib);
            arr_expertise_IB.splice(index_expertise_ib,1);
            addToTableExpertiseIB();
            if (arr_expertise_IB.length === 0){
                $('#viewIB').fadeOut();
            }
        })
        // click Edit expertise IB
        $(document).on('click','.clickEditExpertiseIB',function () {
            console.log(arr_expertise_IB);
            let this_remove_expertise_ib = $(this).attr('id');
            let find_expertise_ib = arr_expertise_IB.find(value => value.token === this_remove_expertise_ib);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_ib['auditor_status'].split(",");
            branchEditNumber = find_expertise_ib['show_branch_value'];
            standardEditNumber = find_expertise_ib['show_standard_value'];
            typeEditNumber = find_expertise_ib['show_inspection_value'];
            catEditNumber = find_expertise_ib['show_category_value'];
            $('#expertise_type').val(2).change();
            $('#specialized_expertise').val(find_expertise_ib['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            var index_expertise_ib = arr_expertise_IB.indexOf(find_expertise_ib);
            arr_expertise_IB.splice(index_expertise_ib,1);
            addToTableExpertiseIB();
            if (arr_expertise_IB.length === 0){
                $('#viewIB').fadeOut();
            }
        });

        // click delete expertise Lab Exam
        $(document).on('click','.clickTrashExpertiseLabExam',function () {
            console.log(arr_expertise_LabExam);
            let this_remove_expertise_lab_exam = $(this).attr('id');
            let find_expertise_lab_exam = arr_expertise_LabExam.find(value => value.token === this_remove_expertise_lab_exam);
            var index_expertise_lab_exam = arr_expertise_LabExam.indexOf(find_expertise_lab_exam);
            arr_expertise_LabExam.splice(index_expertise_lab_exam,1);
            addToTableExpertiseLabExam();
            if (arr_expertise_LabExam.length === 0){
                $('#viewLabExam').fadeOut();
            }
        })
        // click Edit expertise Lab Exam
        $(document).on('click','.clickEditExpertiseLabExam',function () {
            console.log(arr_expertise_LabExam);
            let this_remove_expertise_lab_exam = $(this).attr('id');
            let find_expertise_lab_exam = arr_expertise_LabExam.find(value => value.token === this_remove_expertise_lab_exam);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_lab_exam['auditor_status'].split(",");
            branchEditNumber = find_expertise_lab_exam['show_branch_value'];
            standardEditNumber = find_expertise_lab_exam['show_standard_value'];
            listCalibration = find_expertise_lab_exam['show_calibration_value'];
            $('#expertise_type').val(3).change();
            $('#specialized_expertise').val(find_expertise_lab_exam['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            var index_expertise_lab_exam = arr_expertise_LabExam.indexOf(find_expertise_lab_exam);
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            arr_expertise_LabExam.splice(index_expertise_lab_exam,1);
            addToTableExpertiseLabExam();
            if (arr_expertise_LabExam.length === 0){
                $('#viewLabExam').fadeOut();
            }
        })


        // click delete expertise Lab Test
        $(document).on('click','.clickTrashExpertiseLabTest',function () {
            console.log(arr_expertise_LabTest);
            let this_remove_expertise_lab_test = $(this).attr('id');
            let find_expertise_lab_test = arr_expertise_LabTest.find(value => value.token === this_remove_expertise_lab_test);
            var index_expertise_lab_test = arr_expertise_LabTest.indexOf(find_expertise_lab_test);
            arr_expertise_LabTest.splice(index_expertise_lab_test,1);
            addToTableExpertiseLabTest();
            if (arr_expertise_LabTest.length === 0){
                $('#viewLabTest').fadeOut();
            }
        })


        // click Edit expertise Lab Test
        $(document).on('click','.clickEditExpertiseLabTest',function () {
            console.log(arr_expertise_LabTest);
            let this_remove_expertise_lab_test = $(this).attr('id');
            let find_expertise_lab_test = arr_expertise_LabTest.find(value => value.token === this_remove_expertise_lab_test);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_lab_test['auditor_status'].split(",");
            branchEditNumber = find_expertise_lab_test['show_branch_value'];
            standardEditNumber = find_expertise_lab_test['show_standard_value'];
            productExamNumber = find_expertise_lab_test['show_product_value'];
            listTestNumber = find_expertise_lab_test['show_test_value'];
            $('#expertise_type').val(4).change();
            $('#specialized_expertise').val(find_expertise_lab_test['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            var index_expertise_lab_test = arr_expertise_LabTest.indexOf(find_expertise_lab_test);
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            arr_expertise_LabTest.splice(index_expertise_lab_test,1);
            addToTableExpertiseLabTest();
            if (arr_expertise_LabTest.length === 0){
                $('#viewLabTest').fadeOut();
            }
        });



        {{--  script ประสบการณ์การตรวจประเมินทั้งหมด  --}}

        function clearForm(){
            $('#branch').hide();
            $('#scope').hide();
            $('#calibration').hide();
            $('#inspection').hide();
            $('#category').hide();
            $('#product').hide();
            $('#test').hide();
        }


        var checkApiEditStatus = 0;
        var branchCheckEditNumber;
        var standardCheckEditNumber;
        var typeCheckEditNumber;
        var categoryCheckEditNumber;
        var calibrationCheckEditNumber;
        var productCheckEditNumber;
        var testCheckEditNumber;
        var setScopeTestName = "";
        function apiStandart(select,_token){
            $.ajax({
                url:"{{route('bcertify.api.standard')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function (result) {
                    $('#check_standard').empty();
                    $("#check_standard").prop('disabled', false);
                    $('#check_branch').empty();
                    let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                    // console.log(data)
                    if (data.length > 1) {
                        for (let i = 0; i <data.length ; i++) {
                            if (i === 0){
                                // console.log(data[0]);
                                $('#check_branch').append('<option value="0">- เลือกสาขา -</option>');
                                $('#check_standard').append('<option value="0">- มาตราฐาน -</option>');
                                $.each(data[0],function(index, value){
                                    // console.log(value.title);
                                    $('#check_branch').append('<option value='+value.id+' >'+value.title+'</option>');
                                });

                            }else {
                                $.each(data[1],function(index, value){
                                    $('#check_standard').append('<option  value='+value.id+' >'+value.title+'</option>');

                                });
                            }
                        }
                    }

                    if (checkApiEditStatus === 1){
                        $('#check_branch').val(branchCheckEditNumber).change();
                        $('#check_standard').val(standardCheckEditNumber).change();
                    }


                }
            });
        }

        let type = "";
        $('.type_of_check').on('change',function () {
            if ($(this).val() !== "") {
                const select = $(this).val();
                const _token = $('input[name="_token"]').val();
                $("#add_check").prop('disabled', false);
                clearForm();
                apiStandart(select,_token);
                $('#branch').fadeIn();
                if (select === "1") {
                    $('#scope').fadeIn();
                }
                else if (select === "2") {
                    $('#inspection').fadeIn();
                    $('#category').fadeIn();
                    $.ajax({
                        url:"{{route('bcertify.api.inspection')}}",
                        method:"POST",
                        data:{select_branch:select,_token:_token},
                        success:function (result) {
                            let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                            $("#check_inspection").empty();
                            $("#check_category").empty();
                            $('#check_inspection').append('<option value="0">- เลือกประเภทหน่วยตรวจ -</option>');
                            $('#check_category').append('<option value="0">- เลือกหมวดหมู่การตรวจ -</option>');
                            $.each(data[0],function(index, value){
                                $('#check_inspection').append('<option  value='+value.id+' >'+value.title+'</option>');
                            });
                            $.each(data[1],function(index, value){
                                $('#check_category').append('<option value='+value.id+' >'+value.title+'</option>');
                            });

                            if (checkApiEditStatus === 1){
                                $('#check_inspection').val(typeCheckEditNumber).change();
                                $('#check_category').val(categoryCheckEditNumber).change();
                                checkApiEditStatus = 0;
                            }

                        }
                    })
                }
                else if (select === "3") {
                    $('#calibration').fadeIn();
                }

                else if (select === "4") {
                    $('#product').fadeIn();
                    $('#test').fadeIn();
                }

                else {
                }
                type = select;
            }
        });



        $('#check_branch').on('change',function () {
            const select_branch = $('#check_branch :selected').text();
            const _token = $('input[name="_token"]').val();
            if (type === "1"){
                $.ajax({
                    url:"{{route('bcertify.api.scope')}}",
                    method:"POST",
                    data:{select_branch:select_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#check_scope").prop('disabled', false);
                        $("#check_scope").empty();
                        $('#check_scope').append('<option value="0">- เลือกสาขา -</option>');
                        $.each(data,function(index, value){
                            $.each(value,function (index,newValue) {
                                $('#check_scope').append('<option>'+newValue.title+'</option>');

                            })
                        });

                        if (checkApiEditStatus === 1){
                            $('#check_scope').append('<option value='+setScopeTestName+' >'+setScopeTestName+'</option>');
                            $('#check_scope').val(setScopeTestName).change();
                            checkApiEditStatus = 0;
                        }
                    }
                })

            }

            else if (type === "3"){
                $.ajax({
                    url:"{{route('bcertify.api.calibration')}}",
                    method:"POST",
                    data:{select_branch:select_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#check_calibration").prop('disabled', false);
                        $("#check_calibration").empty();
                        $('#check_calibration').append('<option value="0">- เลือกรายการสอบเทียบ -</option>');
                        $.each(data,function(index, value){
                            $('#check_calibration').append('<option value='+value.id+'>'+value.title+'</option>');
                        });

                        if (checkApiEditStatus === 1){
                            $('#check_calibration').val(calibrationCheckEditNumber).change();
                            checkApiEditStatus = 0;
                        }
                    }
                })

            }
            else if (type === "4"){
                $.ajax({
                    url:"{{route('bcertify.api.product')}}",
                    method:"POST",
                    data:{select_branch:select_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#check_product").prop('disabled', false);
                        $("#check_product").empty();
                        $('#check_product').append('<option value="0">เลือกผลิตภัณฑ์</option>');

                        $("#check_test").prop('disabled', false);
                        $("#check_test").empty();
                        $('#check_test').append('<option value="0">เลือกรายการทดสอบ</option>');

                        $.each(data[0],function(index, value){
                            $('#check_product').append('<option value='+value.id+'>'+value.title+'</option>');
                        });
                        $.each(data[1],function(index, value){
                            $('#check_test').append('<option value='+value.id+'>'+value.title+'</option>');
                        });

                        if (checkApiEditStatus === 1){
                            $('#check_product').val(productCheckEditNumber).change();
                            $('#check_test').val(testCheckEditNumber).change();
                            checkApiEditStatus = 0;
                        }
                    }
                })

            }
            else {
                console.log("testttt")
            }

            // console.log(select_branch);
        });


        // ############################################################################################################
        var arr_check_cb = [];
        var arr_check_ib = [];
        var arr_check_labexam = [];
        var arr_check_labtest = [];


        function addCheckToTableCB(){
            $('#add_cb').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            console.log(arr_check_cb);
            $.each(arr_check_cb,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_cb').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.scope_name+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditCheckCB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashCheckCB" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count++;
            })

        }
        function addCheckToTableIB(){
            $('#add_ib').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            console.log(arr_check_ib);
            $.each(arr_check_ib,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_ib').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.typeCheck+'</td>' +
                    '<td>'+value.cat+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditCheckIB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashCheckIB" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');
                count++;
            })

        }
        function addCheckToTableLabExam(){
            $('#add_labcalibration').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            $.each(arr_check_labexam,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_labcalibration').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.listCalibation+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditCheckLabExam" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashCheckLabExam" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');

                count++;
            })

        }
        function addCheckToTableLabTest(){
            $('#add_labtest').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            $.each(arr_check_labtest,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_labtest').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.exProduct+'</td>' +
                    '<td>'+value.testList+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                    '<button class="btn clickEditCheckLabTest" type="button" id='+value.token+'><i class="fa fa-pencil-square-o text-info" aria-hidden="true"></i></button>' +
                    '<button class="btn clickTrashCheckLabTest" type="button" id='+value.token+'><i class="fa fa fa-trash-o text-danger" aria-hidden="true"></i></button>' +
                    '</td>' +
                    '</tr>');


                count++;
            })

        }
        $('#add_check').on('click' ,function () {
            const branch = $('#check_branch :selected').text();
            const branch_value = $('#check_branch').val();
            const standard = $('#check_standard :selected').text();
            const standard_value = $('#check_standard').val();
            const start_date = $('#start_check_date').val();
            const end_date = $('#end_check_date').val();
            const role = $('#check_role').val();
            const token = Math.random().toString(36).substring(7);
            const status = $('#check_status').val();
            const show_type = $('#type_of_check').val();
            if (type === "1"){
                const scope = $('#check_scope').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && scope !== "" && $('#check_status').val() !== "0" && $('#check_scope').val() !== "0") {
                    $('#showErrorAssessment').fadeOut()
                    var obj_check_cb = {branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,showBranch:branch,scope_name:scope,role:role,showStaus:status,token:token};
                    $('#tableCheckCB').fadeIn();
                    arr_check_cb.push(obj_check_cb);
                    addCheckToTableCB();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#check_scope').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn()
                }
            }
            else if (type === "2"){
                const type_inspection = $('#check_inspection :selected').text();
                const type_inspection_value = $('#check_inspection').val();
                const category = $('#check_category :selected').text();
                const category_value = $('#check_category').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && type_inspection !== "" && category !== "" && $('#check_status').val() !== "0") {
                    $('#showErrorAssessment').fadeOut()
                    var obj_check_ib = {category_value:category_value,type_inspection_value:type_inspection_value,branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,typeCheck:type_inspection,cat:category,showBranch:branch,role:role,showStaus:status,token:token};
                    $('#tableCheckIB').fadeIn();
                    arr_check_ib.push(obj_check_ib);
                    addCheckToTableIB();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#check_inspection').val(0).change();
                    $('#check_category').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn()
                }
            }
            else if (type === "3"){
                const calibration = $('#check_calibration :selected').text();
                const calibration_value = $('#check_calibration').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && calibration !== "" && $('#check_status').val() !== "0") {
                    $('#showErrorAssessment').fadeOut()
                    var obj_check_labExam = {calibration_value:calibration_value,branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,showBranch:branch,listCalibation:calibration,role:role,showStaus:status,token:token};
                    $('#tableCheckLabExam').fadeIn();
                    arr_check_labexam.push(obj_check_labExam);
                    addCheckToTableLabExam();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#calibration').val(0).change();
                } else {
                    $('#showErrorAssessment').fadeIn()
                }



            }
            else if (type === "4"){
                const product = $('#check_product :selected').text();
                const product_value = $('#check_product').val();
                const lab_test = $('#check_test :selected').text();
                const lab_test_value = $('#check_test').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && product !== "" && lab_test !== "" && $('#check_status').val() !== "0") {
                    $('#showErrorAssessment').fadeOut()
                    var obj_check_labTest = {lab_test_value:lab_test_value,product_value:product_value,branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,showBranch:branch,exProduct:product,testList:lab_test,role:role,showStaus:status,token:token};
                    $('#tableCheckLabTest').fadeIn();
                    arr_check_labtest.push(obj_check_labTest);
                    addCheckToTableLabTest();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#check_product').val(0).change();
                    $('#check_test').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn()
                }


            }
            // $("#add_check").prop('disabled', true);
            $('#check_role').val("");
            $('#start_check_date').val("");
            $('#end_check_date').val("");
        });

        // click delete check CB
        $(document).on('click','.clickTrashCheckCB',function () {
            console.log(arr_check_cb);
            let this_remove_check_cb = $(this).attr('id');
            let find_check_cb = arr_check_cb.find(value => value.token === this_remove_check_cb);
            var index_check_cb = arr_check_cb.indexOf(find_check_cb);
            arr_check_cb.splice(index_check_cb,1);
            addCheckToTableCB();
            if (arr_check_cb.length === 0){
                $('#tableCheckCB').fadeOut();
            }
        })

        // click edit check CB
        $(document).on('click','.clickEditCheckCB',function () {
            console.log(arr_check_cb);
            let this_remove_check_cb = $(this).attr('id');
            let find_check_cb = arr_check_cb.find(value => value.token === this_remove_check_cb);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_cb['branch_value'];
            standardCheckEditNumber = find_check_cb['standard_value'];
            setScopeTestName = find_check_cb['scope_name'];
            $('.type_of_check').val(1).change();
            $('#start_check_date').val(find_check_cb['first_date']);
            $('#end_check_date').val(find_check_cb['second_date']);
            $('#check_role').val(find_check_cb['role']);
            $('#check_status').val(find_check_cb['showStaus']).change();
            var index_check_cb = arr_check_cb.indexOf(find_check_cb);
            arr_check_cb.splice(index_check_cb,1);
            addCheckToTableCB();
            if (arr_check_cb.length === 0){
                $('#tableCheckCB').fadeOut();
            }
        });
        // click delete check IB
        $(document).on('click','.clickTrashCheckIB',function () {
            console.log(arr_check_ib);
            let this_remove_check_ib = $(this).attr('id');
            let find_check_ib = arr_check_ib.find(value => value.token === this_remove_check_ib);
            var index_check_ib = arr_check_ib.indexOf(find_check_ib);
            arr_check_ib.splice(index_check_ib,1);
            addCheckToTableIB();
            if (arr_check_ib.length === 0){
                $('#tableCheckIB').fadeOut();
            }
        });

        // click edit check IB
        $(document).on('click','.clickEditCheckIB',function () {
            console.log(arr_check_ib);
            let this_remove_check_ib = $(this).attr('id');
            let find_check_ib = arr_check_ib.find(value => value.token === this_remove_check_ib);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_ib['branch_value'];
            standardCheckEditNumber = find_check_ib['standard_value'];

            typeCheckEditNumber = find_check_ib['type_inspection_value'];
            categoryCheckEditNumber = find_check_ib['category_value'];

            $('.type_of_check').val(2).change();
            $('#start_check_date').val(find_check_ib['first_date']);
            $('#end_check_date').val(find_check_ib['second_date']);
            $('#check_role').val(find_check_ib['role']);
            $('#check_status').val(find_check_ib['showStaus']).change();
            var index_check_ib = arr_check_ib.indexOf(find_check_ib);
            arr_check_ib.splice(index_check_ib,1);
            addCheckToTableIB();
            if (arr_check_ib.length === 0){
                $('#tableCheckIB').fadeOut();
            }
        });
        // click delete check Lab Exam
        $(document).on('click','.clickTrashCheckLabExam',function () {
            console.log(arr_check_labexam);
            let this_remove_check_lab_exam = $(this).attr('id');
            let find_check_lab_exam = arr_check_labexam.find(value => value.token === this_remove_check_lab_exam);
            var index_check_lab_exam = arr_check_labexam.indexOf(find_check_lab_exam);
            arr_check_labexam.splice(index_check_lab_exam,1);
            addCheckToTableLabExam();
            if (arr_check_labexam.length === 0){
                $('#tableCheckLabExam').fadeOut();
            }
        });
        // click edit check Lab Exam
        $(document).on('click','.clickEditCheckLabExam',function () {
            console.log(arr_check_labexam);
            let this_remove_check_lab_exam = $(this).attr('id');
            let find_check_lab_exam = arr_check_labexam.find(value => value.token === this_remove_check_lab_exam);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_lab_exam['branch_value'];
            standardCheckEditNumber = find_check_lab_exam['standard_value'];
            calibrationCheckEditNumber = find_check_lab_exam['calibration_value'];
            $('.type_of_check').val(3).change();
            $('#start_check_date').val(find_check_lab_exam['first_date']);
            $('#end_check_date').val(find_check_lab_exam['second_date']);
            $('#check_role').val(find_check_lab_exam['role']);
            $('#check_status').val(find_check_lab_exam['showStaus']).change();
            var index_check_lab_exam = arr_check_labexam.indexOf(find_check_lab_exam);
            arr_check_labexam.splice(index_check_lab_exam,1);
            addCheckToTableLabExam();
            if (arr_check_labexam.length === 0){
                $('#tableCheckLabExam').fadeOut();
            }
        });
        // click delete check Lab Test
        $(document).on('click','.clickTrashCheckLabTest',function () {
            console.log(arr_check_labtest);
            let this_remove_check_lab_test = $(this).attr('id');
            let find_check_lab_test = arr_check_labtest.find(value => value.token === this_remove_check_lab_test);
            var index_check_lab_test = arr_check_labtest.indexOf(find_check_lab_test);
            arr_check_labtest.splice(index_check_lab_test,1);
            addCheckToTableLabTest();
            if (arr_check_labtest.length === 0){
                $('#tableCheckLabTest').fadeOut();
            }
        });
        // click edit check Lab Test
        $(document).on('click','.clickEditCheckLabTest',function () {
            console.log(arr_check_labtest);
            let this_remove_check_lab_test = $(this).attr('id');
            let find_check_lab_test = arr_check_labtest.find(value => value.token === this_remove_check_lab_test);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_lab_test['branch_value'];
            standardCheckEditNumber = find_check_lab_test['standard_value'];
            productCheckEditNumber = find_check_lab_test['product_value'];
            testCheckEditNumber = find_check_lab_test['lab_test_value'];
            $('.type_of_check').val(4).change();
            $('#start_check_date').val(find_check_lab_test['first_date']);
            $('#end_check_date').val(find_check_lab_test['second_date']);
            $('#check_role').val(find_check_lab_test['role']);
            $('#check_status').val(find_check_lab_test['showStaus']).change();
            var index_check_lab_test = arr_check_labtest.indexOf(find_check_lab_test);
            arr_check_labtest.splice(index_check_lab_test,1);
            addCheckToTableLabTest();
            if (arr_check_labtest.length === 0){
                $('#tableCheckLabTest').fadeOut();
            }
        });


        // click finish
        $(document).ready(function () {

            var tab_id = $('.tab-content .active').attr('id');

                if(tab_id=="tab1"){
                    $('#submit_form').attr('disabled', true);
                }

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                var tab_id = $('.tab-content .active').attr('id');

                if(tab_id=="tab1"){
                    $('#submit_form').attr('disabled', true);
                } else {
                    $('#submit_form').attr('disabled', false);

                }
});

            $('#submit_form').on('click',function () {
                $('#education_history').val(JSON.stringify(arr_education));
                $('#training').val(JSON.stringify(arr_history));
                $('#data_experience').val(JSON.stringify(arr_experience));

                // expertise
                var arr_all_expertise = [];
                arr_all_expertise.push(arr_expertise_CB,arr_expertise_IB,arr_expertise_LabExam,arr_expertise_LabTest);
                $('#data_all_expertise').val(JSON.stringify(arr_all_expertise));

                var arr_all_check = [];
                arr_all_check.push(arr_check_cb,arr_check_ib,arr_check_labexam,arr_check_labtest);
                $('#data_all_check').val(JSON.stringify(arr_all_check));

                // $('#commentForm').submit();
                document.getElementById("commentForm").submit();

            })
        });


    </script>
@endpush

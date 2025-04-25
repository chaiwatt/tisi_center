@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    {{-- <link href="{{asset('css/multiselect.css')}}" rel="stylesheet"> --}}
        <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />


    <style>

        .label-filter {
            margin-top: 7px;
        }

        /*
          Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
          */
        @media only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px) {

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin: 0 0 1rem 0;
            }

            tr:nth-child(odd) {
                background: #eee;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                /* Now like a table header */
                /*position: absolute;*/
                /* Top/left values mimic padding */
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }

            /*
            Label the data
        You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
            */
            /*td:nth-of-type(1):before { content: "Column Name"; }*/

        }

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .wrapper-detail {
            border: solid 1px silver;
            margin-top: 70px;
            margin-left: 20px;
            margin-right: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        fieldset {
            padding: 20px 20px 0px 20px;
        }

    .center {
        margin: auto;  //ระยะขอบ ผลักอัตโนมัติ
        width: 50%;   //ความกว้าง 50%
        border: 3px solid blue;   //ความหนา รูปแบบ สีของขอบ
        padding: 3%;  //ขยายขอบด้านใน
    }

    </style>
@endpush


@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div id="alert"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                                $user = Auth::user();
                            ?>
                            <h1 class="box-title">ระบบใบรับ - นำส่งตัวอย่าง </h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <form id="form_data" method="post" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <fieldset class="white-box">

                            <div class="p-10"></div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group required">
                                        {!! Form::label('tis_standard', 'มาตรฐาน'.' :', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('tis_standard', HP::TisListSample() ,  ( !empty($tisno)?$tisno:null ), ['class' => 'form-control', 'placeholder' => '- เลือกมาตรฐาน -', 'required' => true, 'autocomplete' => "off", 'onchange' => "remove_fill_all(); set_value_empty1(); add_filter_tb4_License(this);" ]) !!}
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        {!! Form::label('licensee', 'ผู้รับใบอนุญาต'.' :', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('licensee', [] ,  null , ['class' => 'form-control', 'id' => "filter_tb4_License", 'placeholder' => '- เลือกผู้รับใบอนุญาต -', 'required' => true, 'autocomplete' => "off", 'onchange' => "remove_fill_all(); set_value_empty(); add_filter_tb4_License_no(this);" ]) !!}
                                        </div>
                                    </div>


                                    <div class="form-group required">
                                        {!! Form::label('licensee_no', 'ใบอนุญาต'.' :', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('licensee_no', [] ,  null , ['class' => 'form-control', 'id' => "filter_tb4_License_no", 'placeholder' => '- เลือกใบอนุญาต -', 'required' => true, 'autocomplete' => "off", 'onchange' => "remove_fill_all(); set_value_empty(); add_item_detail(this); enableadd();" ]) !!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group m-b-5">
                                        <h5><b>รายละเอียดตัวอย่างผลิตภัณฑ์อุตสาหกรรม</b></h5>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table color-bordered-table primary-bordered-table" id="myTable">
                                            <thead>
                                                <tr>
                                                    <th style="width: 3%; color: white">รายการที่</th>
                                                    <th style="width: 3%; "><input type="checkbox" id="checkall"><span style="color:red">*</span></th>
                                                    <th style="width: 30%; color: white">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                                                    <th style="width: 8%; color: white">จำนวน</th>
                                                    <th style="width: 10%; color: white">หมายเลขตัวอย่าง</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <div id="sum_row" class="col-md-4"></div>
                                        <div id="detail"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group m-b-5">
                                        <div class="col-sm-6 " >
                                            <h5><b>รูปแบบการตรวจ <span style="color:red">*</span> </b>&emsp;
                                                <span>
                                                    <input type="radio" name="type_save" value="all" checked> ทุกรายการทดสอบ
                                                    <input type="radio" name="type_save" value="some"> บางรายการทดสอบ
                                                </span>
                                            </h5>
                                        </div>
                                        <div class="col-sm-6" style="display: flex; justify-content: flex-end">
                                            <button class="btn btn-success btn-sm waves-effect waves-light" name="add" id="add" type="reset" onClick="return false;" disabled>
                                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มหน่วยตรวจ</b>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table color-bordered-table primary-bordered-table" id="myTable2">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%; color: white">ลำดับที่</th>
                                                        <th style="width: 10%; color: white">ชื่อหน่วยตรวจสอบ<span style="color:red">*</span></th>
                                                        <th style="width: 20%; color: white">รายการตรวจ<span style="color:red">*</span></th>
                                                        <th style="width: 22%; color: white">รายการทดสอบ</th>
                                                        <th style="width: 10%; color: white">เลขที่ใบนำส่งตัวอย่าง</th>
                                                        <th style="width: 8%; color: white">สถานะ</th>
                                                        <th style="width: 8%; color: white">ลบ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="vertical-align: text-top;">1<input name="wsk_row[]" value="0" hidden></td>
                                                        <td style="vertical-align: text-top;">
                                                            <div class="form-group sub_file">
                                                                {!! Form::select('wksselect[]', [], null , ['class' => 'form-control', 'placeholder' => '- เลือกชื่อหน่วยตรวจสอบ -', 'id' => 'wksselect_0', 'required' => true]) !!}
                                                            </div>
                                                        </td>
                                                        <td colspan="2">
                                                            <div id="wksdetail0"></div>
                                                        </td>
                                                        <td>
                                                            <div id="wksid0"></div>
                                                        </td>
                                                        <td>
                                                            <div id="wksstate0"></div>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger btn-xs" name="deleterow" id="deleterow1" type="reset" disabled> <i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div id="wkslist"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('more_details', 'รายละเอียดเพิ่มเติม'.' :', ['class' => 'col-md-2 control-label']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('more_details', null,  ['class' => 'form-control', 'rows' => 5]) !!}
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="white-box">

                            <div class="p-10"></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group m-b-10">
                                        <div class="col-sm-8">
                                            <label class="col-sm-3 text-right">การตรวจสอบ : </label>
                                            <div class="col-md-5">
                                                <input type="radio"  class="col-sm-1 checked_radio" name="verification"  value="ตรวจสอบที่หน่วยตรวจสอบ" checked>
                                                <label> ตรวจสอบที่หน่วยตรวจสอบ </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8">
                                            <label class="col-sm-3 text-right"></label>
                                            <div class="col-md-5">
                                                <input type="radio"  class="col-sm-1 checked_radio"  name="verification"  value="ตรวจสอบที่โรงงาน">
                                                <label> ตรวจสอบที่โรงงาน </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="sample_delivery" class="form-group">
                                        <div class="form-group">
                                            <div class="col-sm-8 m-b-10">
                                                <label class="col-sm-3 text-right">การนำส่งตัวอย่าง : </label>
                                                <div class="col-md-7">
                                                    <input type="radio" class="col-sm-1" name="sample_submission" checked value="ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง">
                                                    <label> ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-8 m-b-10">
                                                <label class="col-sm-3 text-right"></label>
                                                <div class="col-md-7">
                                                    <input type="radio" class="col-sm-1" name="sample_submission" value="กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง">
                                                    <label> กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                                <p class="col-sm-2"></p>
                                                <label class="col-sm-3 text-right">โดยเก็บตัวอย่างไว้ที่ </label>
                                                <input type="radio" class="col-sm-1" name="stored_add" value="โรงงาน">
                                                <label class="col-sm-1">โรงงาน</label>
                                                <input type="radio" class="col-sm-1" name="stored_add" value="สมอ. ห้อง">
                                                <label class="col-sm-2">สมอ. ห้อง</label>
                                                <div class="input-group col-sm-2">
                                                    <input type="text" class="form-control pull-right" name="room_anchor">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <p class="center" style="text-align: justify; padding: 10px; border: 2px solid black; line-height: 40px; width: 980px">
                                            ตามเงื่อนไขที่ผู้รับใบอนุญาตต้องปฏิบัติ ตามมาตรา 25 ทวิ สำนักงานขอแจ้งให้ท่านนำส่งตัวอย่างพร้อมชำระค่าใช้จ่ายในการตรวจสอบ<br>
                                            ที่หน่วยตรวจสอบตามที่ระบุไว้ ในใบรับ-นำส่งตัวอย่างนี้ ภายใน 15 วัน นับจากวันที่เก็บตัวอย่าง
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group m-b-10">
                                        <div class="col-sm-6">
                                            <div class="col-sm-4">วันที่เก็บตัวอย่าง :</div>
                                            <div class="input-group date col-sm-7">
                                                <input type="text" class="form-control pull-right datepicker"
                                                    name="sample_submission_date" id="datepicker-time">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group m-b-10">
                                        <div class="col-sm-6">
                                            <div class="col-sm-4">ผู้จ่ายตัวอย่าง  <span style="color:red">*</span> :</div>
                                            <div class="input-group col-sm-7">
                                                <input type="text" class="form-control pull-right" name="sample_pay" id="sample_pay" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" >
                                            <div class="col-sm-4">ตำแหน่ง <span style="color:red">*</span> :</div>
                                            <div class="input-group col-sm-8">
                                                <input type="text" class="form-control pull-right" name="permission_submiss" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-b-10">
                                        <div class="col-sm-6">
                                            <div class="col-sm-4">เบอร์โทรศัพท์ <span style="color:red">*</span> :</div>
                                            <div class="input-group col-sm-7">
                                                <input type="text" class="form-control pull-right" name="tel_submiss" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="col-sm-4 ">Email <span style="color:red">*</span> :</div>
                                            <div class="input-group col-sm-8">
                                                <input type="email" class="form-control pull-right" name="email_submiss" required
                                                    >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group m-b-10">
                                        <div class="col-sm-6" >
                                            <div class="col-sm-4">ผู้รับตัวอย่าง :</div>
                                            <div class="input-group col-sm-7">
                                                <input type="text" class="form-control pull-right" name="sample_recipient" id="sample_recipient" value="{{ $user->reg_fname ? $user->reg_fname.' '.$user->reg_lname : '-'}}" readonly>
                                            </div>
                                        </div>
                                            <div class="col-sm-6" >
                                            <div class="col-sm-4">ตำแหน่ง :</div>
                                            <div class="input-group col-sm-8">
                                                <input type="text" class="form-control pull-right" name="permission_receive">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-b-10">
                                        <div class="col-sm-6" >
                                            <div class="col-sm-4">เบอร์โทรศัพท์ :</div>
                                            <div class="input-group col-sm-7">
                                                <input type="text" class="form-control pull-right" name="tel_receive">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="col-sm-4">Email :</div>
                                            <div class="input-group col-sm-8">
                                                <input type="email" class="form-control pull-right" name="email_receive">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-7">
                                            <label class="col-sm-4">การรับคืนตัวอย่าง : </label>
                                            <input type="radio" name="sample_return" value="ไม่รับคืน" class="col-md-1" checked>
                                            <label class="col-md-2"> ไม่รับคืน </label>
                                            <input type="radio" name="sample_return" value="รับคืน" class="col-md-1">
                                            <label class="col-md-2"> รับคืน </label>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </fieldset>

                        <div id="status_btn"></div>
                        <br>
                        <div align="center">
                            <button class="btn btn-warning btn-lg waves-effect waves-light" type="submit"
                                    style="margin-right: 20px" onclick="add_status_btn('0')">
                                    <i class="fa fa-save"></i>
                                    <b>บันทึกร่าง</b>
                            </button>
                            <button class="btn btn-info btn-lg waves-effect waves-light" type="submit"
                                    style="margin-right: 20px" onclick="add_status_btn('1')">
                                    <i class="fa fa-send"></i>
                                    <b>ส่งรายงาน</b>
                            </button>
                            <a class="btn btn-default btn-lg waves-effect waves-light"
                               href="{{ app('url')->previous() }}">
                                <i class="fa fa-undo"></i>
                                <b> ยกเลิก</b>
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        @endsection

@push('js')

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    {{-- <script src="{{ asset('js/multiselect.min.js') }}"></script> --}}

     <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function() {

            //เมื่อเลือก ชื่อหน่วยตรวจสอบ
            $(document).on('change', '[name="wksselect[]"]', function() {
                get_lab_test_items(this);
            });

        });

        //ดึงรายการทดสอบตาม Lab และ มาตรฐาน
        function get_lab_test_items(lab_select){

            $(lab_select).closest('tr').find('select[name*="wkslist_test"]').html('').trigger('change');
            if($(lab_select).val()!='' && $('#tis_standard').val()!=''){
                $.ajax({
                    type: "GET",
                    url: "{{ url('/ssurv/save_example/get_lab_test_items') }}",
                    data: {
                           lab_id: $(lab_select).val(),
                           tis_tisno: $('#tis_standard').val()
                          },
                    cache: false,
                    success: function (responses) {

                        var option_htmls = Array();
                        $.each(responses, function(index, item) {
                            option_htmls.push('<option value="'+index+'">'+item+'</option>');
                        });

                        $(lab_select).closest('tr').find('select[name*="wkslist_test"]').html(option_htmls.join('')).trigger('change');
                    }
                });
            }

        }

            $(document).on('click','.overAll',function () {
                var objSel = $(this).parent().find('select');
                if($(this).is(':checked') ){
                    $(objSel).children().prop("selected","selected");
                    $(objSel).trigger("change");
                }else{
                    $(objSel).children().removeAttr("selected");
                    $(objSel).trigger("change");
                }
            })

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $(".js-example-tokenizer").select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                })
                function enableadd() {
                    document.getElementById("add").disabled = false;
                }


                $('#form_data').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                }).on('form:submit', function(e) {

                    var check_status =  $("input[name='check_status']").val();

                    //รายละเอียดตัวอย่างผลิตภัณฑ์อุตสาหกรรม
                    var check_detail = false;
                    if($('input.item_chk:checked').length > 0){
                        check_detail = true; //เลือก
                    }

                    //รายการทดสอบ
                    var check_test_item = false;
                    $('#myTable2 > tbody  > tr').each(function(index, tr) {
                        if( $(tr).find('input.list_chk:checked').length > 0  ){
                            check_test_item = true; //เลือก
                        }else{
                            check_test_item = false;
                            return false;
                        }
                    });

                    if( check_detail == false ){
                        alert("กรุณาเลือกรายละเอียดตัวอย่างผลิตภัณฑ์อุตสาหกรรม");
                    }

                    if( check_test_item == false ){
                        alert("กรุณาเลือกรายการตรวจ");
                    }

                    var form_data = new FormData($('#form_data')[0]);

                    if( check_test_item  == true &&  check_detail == true ){

                        $.ajax({
                            type: "POST",
                            url: "{{url('/ssurv/save_example/save')}}",
                            datatype: "script",
                            data: form_data,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.status == "success") {
                                    alert('บันทึกข้อมูลสำเร็จ');
                                    window.location.href = "{{url('/ssurv/save_example')}}"
                                } else if (data.status == "error") {
                                    alert(data.message);
                                } else {
                                    alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                                }
                            }
                        });
                        // $.ajax({
                        //     type: "POST",
                        //     url: "{{url('/ssurv/save_example/check')}}",
                        //     datatype: "script",
                        //     data: form_data,
                        //     contentType: false,
                        //     cache: false,
                        //     processData: false,
                        //     success: function (data) {
                        //         var response = data;
                        //         var list = response.data;
                        //         if (list == null) {
                        //             if (confirm('ยังไม่มีการตั้งค่าผลการทดสอบ ต้องการบันทึกหรือไม่')) {
                        //                 $.ajax({
                        //                     type: "POST",
                        //                     url: "{{url('/ssurv/save_example/save')}}",
                        //                     datatype: "script",
                        //                     data: form_data,
                        //                     contentType: false,
                        //                     cache: false,
                        //                     processData: false,
                        //                     success: function (data) {
                        //                         if (data.status == "success") {
                        //                             alert('บันทึกข้อมูลสำเร็จ');
                        //                             window.location.href = "{!! $previousUrl !!}"
                        //                         } else if (data.status == "error") {
                        //                             alert(data.message);
                        //                         } else {
                        //                             alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                        //                         }
                        //                     }
                        //                 });
                        //             }
                        //         } else {
                        //             $.ajax({
                        //                 type: "POST",
                        //                 url: "{{url('/ssurv/save_example/save')}}",
                        //                 datatype: "script",
                        //                 data: form_data,
                        //                 contentType: false,
                        //                 cache: false,
                        //                 processData: false,
                        //                 success: function (data) {
                        //                     if (data.status == "success") {
                        //                         alert('บันทึกข้อมูลสำเร็จ');
                        //                         window.location.href = "{{url('/ssurv/save_example')}}"
                        //                     } else if (data.status == "error") {
                        //                         alert(data.message);
                        //                     } else {
                        //                         alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                        //                     }
                        //                 }
                        //             });
                        //         }
                        //     }
                        // });
                    }

                });


                $('#add').click(function () {
                    var val = document.getElementById('tis_standard').value;
                    add_map_lap();
                    add_filter_map_list_other(val);
                    add_result_other();
                });

                function testdelete(number_field){
                    var bt = number_field - 1;
                    $('#rowtbtwo'+number_field).remove();
                    if(bt != 1){
                        $( "#deleterow"+ bt ).prop( "disabled", false );
                    }
                };

                function add_map_lap(){
                    var next_num = $('.sub_file').length;
                    var number_field = next_num + 1;
                    $( "#deleterow"+next_num ).prop( "disabled", true );

                    var item_check = $('#myTable').find('input.item_chk').length;

                    var arr = [];
                    var chk1 = [];
                    $.each($('#myTable').find('input.item_chk'), function() {
                        var value = $(this).val();
                        arr.push(value);
                        if($(this).is(':checked')){
                            chk1.push('block');
                        }else{
                            chk1.push('none');
                        }
                    });
                    // alert(JSON.stringify(chk1));
                    var arr_detail = [];
                    $.each($('#myTable').find('input:hidden.num_row_detail'), function() {
                        var value2 = $(this).val();
                        arr_detail.push(value2);
                    });

                    var html_add_lab = '<tr id="rowtbtwo'+ number_field +'">';
                        //start td 1 2
                        html_add_lab += '<td style="vertical-align: text-top;">'+ number_field + '<input name="wsk_row[]" value="'+next_num+'" hidden></td>';
                        html_add_lab += '<td style="vertical-align: text-top;"><div class="form-group sub_file">';
                        html_add_lab += '<select class="form-control wksselect'+ next_num +'" id="wksselect_' + next_num +'" name="wksselect[]" required>';
                        html_add_lab += '<option value="">เลือกชื่อหน่วยตรวจสอบ</option>';
                        html_add_lab += '</select>';
                        html_add_lab += '</div></td>';
                        // end td 1 2

                    // start td 3
                    html_add_lab += '<td style="vertical-align: top; padding-top : 0" colspan="2"><p id="wksdetail' + next_num + '">';
                    for (i = 0; i < item_check; i++) {
                        html_add_lab += '<div style="display:'+chk1[i]+';" class="row" id="lab'+ next_num +'_list'+i+'">';
                        html_add_lab += '<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6"><p align="left"><input type="checkbox" class="list_chk" id="wkslist'+next_num+'_list'+i+'" name="wkslist_list['+next_num+'][]" value="' + arr[i] + '|' + i +'">&nbsp;' + arr_detail[i] + '<br></p></div>';
                        html_add_lab += '<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                        // <textarea rows = "3" name="wkslist_test['+next_num+'][]" ></textarea>
                        html_add_lab += '<select id="testSelect'+next_num+'-'+i+'" name="wkslist_test['+next_num+'-'+arr[i]+'][]" multiple width="100%" class="test'+next_num+'"></select></div>';
                        html_add_lab += '</div>';
                    }
                    html_add_lab += '</p></td>';
                    // end td 3

                    html_add_lab += '<td><div id="wksid' + next_num + '"></div></td>';
                    html_add_lab += '<td><div id="wksstate' + next_num + '"></div></td>';
                    html_add_lab += '<td><div>';
                    html_add_lab += '<button class="btn btn-danger btn-xs" name="deleterow" id="deleterow'+number_field+'" type="reset" onclick="testdelete('+number_field+')"> <i class="fa fa-trash-o" aria-hidden="true"></i> </button>'
                    html_add_lab += '</div></td></tr>'

                    $('#myTable2 tbody').append(html_add_lab);

                    $('.test' + next_num).select2();
                    $('.wksselect' + next_num).select2();
                }

                function add_filter_map_list_other(select_item) {
                    var tb3_Tisno = select_item;
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_filter_tb4_License')}}",
                        datatype: "html",
                        data: {
                            tb3_Tisno: tb3_Tisno,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;

                            //ใส่ข้อมูลลง ชื่อหน่วยตรวจสอบ รายการสุดท้าย
                            var next_num = $('.sub_file').length;
                            var j = next_num-1;
                            $('#wksselect_'+j).html(' <option value="">-เลือกชื่อหน่วยตรวจสอบ-</option>');
                            var labs = response.labs;
                            $.each(labs, function(index, lab) {
                                $('#wksselect_'+j).append('<option value="' + lab.id + '">' + lab.name + '</option>');
                            });

                            // var data_user = response.data_user;
                            // if (data_user[0].length != 0) {
                            //     var next_num = $('.sub_file').length;
                            //     var arr_user = data_user[0];
                            //     // for(j = 1; j <= next_num; j++){
                            //         var j = next_num-1;
                            //         $('#wksselect_'+j).html(' <option>-เลือกชื่อหน่วยตรวจสอบ-</option>');
                            //         for (let i = 0; i < arr_user.length; i++) {
                            //             $('#wksselect_'+j).append('<option value="' + arr_user[i].trader_operater_name + '|' + arr_user[i].trader_autonumber + '|' + tb3_Tisno + '">' + arr_user[i].trader_operater_name + '</option>');
                            //         }
                            //     // }
                            //
                            // }
                        }
                    });

                }

                var temp_fliter_list = [];
                var temp_unit_name;

                function add_filter_tb4_License(select_item) {
                    var tb3_Tisno = select_item.value;
                    $('#filter_tb4_License').html('<option value="">-เลือกผู้รับใบอนุญาต-</option>');
                    $('#filter_tb4_License').select2('val', "");

                    if( tb3_Tisno != '' ){
                        $.ajax({
                            type: "GET",
                            url: "{{url('/ssurv/save_example/get_filter_tb4_License')}}",
                            datatype: "html",
                            data: {
                                tb3_Tisno: tb3_Tisno,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function (data) {
                                var response = data;
                                var list = response.data;
                                if (list.length != 0) {

                                    for (let i = 0; i < list.length; i++) {
                                        $('#filter_tb4_License').append('<option id="filter_tb4_License" name="' + list[i] + '" value="' + list[i] + '">' + list[i] + '</option>');
                                    }
                                } else {
                                    $('#filter_tb4_License').empty();
                                }

                                var next_num = $('.sub_file').length;
                                for(j = 0; j < next_num; j++){
                                    $('#wksselect_'+j).html('<option value="">-เลือกชื่อหน่วยตรวจสอบ-</option>');
                                }
                                var labs = response.labs;
                                $.each(labs, function(index, lab) {
                                    $('#wksselect_0').append('<option value="' + lab.id + '">' + lab.lab_name + '</option>');
                                });

                                var data_unit = response.data_unit;
                                if (data_unit[0].length != 0) {
                                    var arr_unit = data_unit[0];
                                    for (let i = 0; i < arr_user.length; i++) {
                                        // $('#unit_'+ temp_unit).html('<input name="unit[]" value="'+arr_unit[i]+'">');
                                    }
                                    temp_unit_name = arr_unit[0];
                                }
                            }
                        });
                    }

                }

                function remove_fill_all() {
                    $('#filter_std_head_group').empty();
                    $('#filter_head').empty();
                    $('#filter_input1').empty();
                    $('#filter_input2').empty();
                    $('#filter_std_type').empty();
                    $('#filter_std_list').empty();
                    $('#detail_volume').remove();
                    $('#myTable tbody').empty();
                    $('#wksselect1').empty();
                    $('#wksselect2').empty();
                    $('#wksselect3').empty();
                    $('#wksdetail0').empty();
                    temp_count_filter_head = 0;
                    temp_fliter_list = [];
                    temp_input = 0;

                    var next_num = $('.sub_file').length;
                    for(i=2; i <= next_num; i++){
                        $('#rowtbtwo'+i).remove();
                    }
                }

                function set_value_empty1(){
                    $('#checkall').prop('checked', false);

                    $('#myTable').find('input.item_chk').prop('checked', false);

                    $('#number').val('');

                    $('#num_ex').val('');

                    $('#myTable2').find('input.list_chk').prop('checked', false);

                    var next_num = $('.sub_file').length;
                    for(i=0; i < next_num; i++){
                        $('#myTable2').find('input.test'+i).val('');
                        $('#wksselect_'+i).html(' <option  value="">เลือกชื่อหน่วยตรวจสอบ</option>');
                        $("#wksselect_0").select2("val", "");
                    }
                }

                function set_value_empty(){
                    $('#checkall').prop('checked', false);

                    $('#myTable').find('input.item_chk').prop('checked', false);

                    $('#number').val('');

                    $('#num_ex').val('');

                    $('#myTable2').find('input.list_chk').prop('checked', false);

                    var next_num = $('.sub_file').length;
                    for(i=0; i < next_num; i++){
                        $('#myTable2').find('input.test'+i).val('');
                    }
                }

                function remove_fill() {
                    $('#filter_std_type').empty();
                    $('#filter_std_list').empty();
                }

                var temp_input = 0;

                $('.checked_radio').on('click', function () {
                    if ($(this).val() == 'ตรวจสอบที่หน่วยตรวจสอบ') {
                        $('#sample_delivery').fadeIn(700);
                    } else {
                        $('#sample_delivery').fadeOut(700);
                    }
                });

                // $('#datepicker-time').datepicker();
                $('.datepicker').datepicker({language:'th-th',format:'dd/mm/yyyy'})

                // $('#datepicker-time').datepicker({
                //     autoclose: true,
                //     dateFormat: 'dd/mm/yy'
                // }).datepicker("setDate", new Date());

                // $('#datepicker-time2').datepicker({
                //     autoclose: true,
                //     dateFormat: 'dd/mm/yy'
                // }).datepicker("setDate", new Date());

                function add_status_btn(status) {
                    $('#status_btn').html('<input type="text" name="check_status" value="' + status + '" hidden>');
                }

                function add_filter_tb4_License_no(select_item) {
                    var tis_standard = $('#tis_standard').val();
                    var tb4_tradeName = select_item.value;

                    $('#filter_tb4_License_no').html('<option value="" >เลือกใบอนุญาต</option>');
                    $('#filter_tb4_License_no').select2('val', "");

                    if( tis_standard != '' && tb4_tradeName != ''  ){
                        $.ajax({
                            type: "GET",
                            url: "{{url('/ssurv/save_example/get_filter_tb4_License_no')}}",
                            datatype: "html",
                            data: {
                                tis_standard: tis_standard,
                                tb4_tradeName: tb4_tradeName,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function (data) {
                                var response = data;
                                var list = response.data;
                                if (list.length != 0) {
                                    for (let i = 0; i < list.length; i++) {
                                        $('#filter_tb4_License_no').append('<option id="filter_tb4_License_no" name="' + list[i] + '" value="' + list[i] + '">' + list[i] + '</option>');
                                    }
                                } else {
                                    $('#filter_tb4_License_no').empty();
                                }

                            }
                        });
                    }

                }

                function add_item_detail(select_item) {
                    var tb4_licenseNo = select_item.value;
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_item_detail')}}",
                        datatype: "html",
                        data: {
                            tb4_licenseNo: tb4_licenseNo,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {

                            var response = data;
                            var list = response.data;
                            var autoNo = response.autoNo;

                            var c = 1;
                            var arr_type = list[0];
                            var i = 0;
                            $.each(arr_type, function (key, value) {

                                    var html_add_item = '<tr>';
                                    html_add_item += '<td>' + c + '</td>';
                                    html_add_item += '<td><input type="checkbox" name="num_row[]" id="num_row['+i+']" class="item_chk" value="' + autoNo[0][i] + '" onclick="putonlab(';
                                    html_add_item += "'" + i +  "'";
                                    html_add_item += ')"></td>';
                                    html_add_item += '<td style="text-align:left">' + list[0][i] + '<input class="num_row_detail" value="' + list[0][i] + '" hidden></td>';
                                    html_add_item += '<td><input type="text" name="number[' + autoNo[0][i] + ']" class="text" style="width: 40%"></td>';
                                    html_add_item += '<td><input type="text" name="num_ex[' + autoNo[0][i] + ']" class="text" style="width: 80%;"></td>';
                                    html_add_item += '</tr>';
                                    $('#myTable tbody').append(html_add_item);

                                    var html_put_on0 = '<div style="display:none" class="row" id="lab0_list'+i+'">';
                                    html_put_on0 += '<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6"><p align="left"><input type="checkbox" class="list_chk" id="wkslist0_list'+i+'" name="wkslist_list[0][]" value="' + autoNo[0][i] + '|' + i + '">&nbsp;' + list[0][i] + '<br></p></div>';
                                    html_put_on0 += '<div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">';
                                    html_put_on0 += '<input type="checkbox" class="overAll" style="float:left"><span style="float:left; padding-left:7px;">ทดสอบทุกรายการ</span>';

                                    html_put_on0 += '<select id="testSelect0-'+i+'" name="wkslist_test[0-'+autoNo[0][i]+'][]" multiple width="100%" class="test0">'
                                    // html_put_on0 += '<option value="1" selected>Item 1111111111</option>'
                                    // html_put_on0 += '<option value="2" selected>Item 2222222222</option>'
                                    // html_put_on0 += '<option value="3" selected>Item 3333333333</option>'
                                    // html_put_on0 += '<option value="4" selected>Item 4444444444</option>'
                                    // html_put_on0 +=  '<option value="5" selected>Item 5555555555</option>'
                                    html_put_on0 += '</select>'

                                    html_put_on0 += '</div></div>';

                                    $('#wksdetail0').append(html_put_on0);
                                    // document.multiselect('#testSelect1');

                                    c++;
                                    i++;

                            });

                            $('.test0').select2();
                            add_result();

                        }
                    });
                }

                function add_result(){
                    var tis_standard = document.getElementById('tis_standard').value;

                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_result')}}",
                        datatype: "html",
                        data: {
                            tis_standard: tis_standard,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {

                            var response = data;
                            var list = response.data;
                            var autoNo = response.autoNo;
                            var type = response.type;

                            var next_num = $('.test0').length;
                            var arr_type = list;
                            var i = 0;
                            var html_put_on0 = '';

                            $.each(arr_type, function (key, value) {
                                html_put_on0 += '<option name="' + list[i] + '" value="' + list[i] + '|' + type[0][i] + '">' + autoNo[0][i] + '</option>';
                                i++;
                            });
                            for(j = 0; j<next_num; j++){
                                $('#testSelect0-'+j).html(html_put_on0);
                            }

                        }
                    });
                }

                function add_result_other(){
                    var tis_standard = document.getElementById('tis_standard').value;

                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_result')}}",
                        datatype: "html",
                        data: {
                            tis_standard: tis_standard,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {

                            var response = data;
                            var list = response.data;
                            var autoNo = response.autoNo;
                            var type = response.type;

                            var subfile = $('.sub_file').length;
                            var countsubfile = subfile-1;

                            var next_num = $('.test'+countsubfile).length;

                            var arr_type = list;
                            var i = 0;
                            var html_put_on0 = '';
                            $.each(arr_type, function (key, value) {
                                html_put_on0 += '<option name="' + list[i] + '" value="' + list[i] + '|' + type[0][i] + '">' + autoNo[0][i] + '</option>';
                                i++;
                            });
                            for(j = 0; j<next_num; j++){
                                $('#testSelect'+countsubfile+'-'+j).html(html_put_on0);
                            }

                        }
                    });
                }

                $('#checkall').change(function(event) {

                    var x = $('#myTable').find('input.item_chk').length;

                    var next_num = $('.sub_file').length;

                    if($(this).prop('checked')){
                        $('#myTable').find('input.item_chk').prop('checked', true);
                        var j = 0;
                        while(j <= next_num){
                            var i = 0;
                            while (i <= x) {
                                $("#lab"+j+"_list"+i).show();
                                i = i+1;
                            }
                            j= j+1;
                        }
                    }else{
                        $('#myTable').find('input.item_chk').prop('checked', false);
                        var j = 0;
                        while(j <= next_num){
                            var i = 0;
                            while (i <= x) {
                                $("#lab"+j+"_list"+i).hide();
                                i = i+1;
                            }
                            j= j+1;
                        }

                    }

                });

                function putonlab(i) {

                    var next_num = $('.sub_file').length;

                    var checkBox = document.getElementById("num_row["+i+"]");
                    for (check = 0; check < next_num; check++){
                        if (checkBox.checked == true){
                            document.getElementById("lab"+ check +"_list"+i).style.display = "block";
                        } else if (checkBox.checked == false) {
                            document.getElementById("lab"+ check +"_list"+i).style.display = "none";
                            $('#myTable2').find('input[id="wkslist'+check+'_list'+i+'"]').prop('checked', false);
                        }
                    }
                }

                // $('input:radio[name="type_save"]').change(
                // function(){
                //     if (this.checked) {
                //         add_result();
                //     }
                // });

            </script>
    @endpush

@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
    <style>

        legend {
            width: auto; /* Or auto */
            padding: 0 10px; /* To give a bit of padding on the left and right */
            border-bottom: none;
            font-size: 14px;
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
            margin-top: 40px;
            margin-left: 20px;
            margin-right: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .input-custom {
            background: transparent;
            border-top: transparent !important;
            border-left: transparent !important;
            border-right: transparent !important;
            border-bottom: transparent !important;
            color: #8d9498;
        }

        fieldset {
            padding: 20px;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <form id="form_data" method="post" enctype="multipart/form-data">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="col-md-12">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="box-title">ระบบบันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม</h1>
                                <hr class="hr-line bg-primary">
                            </div>
                        </div>

                        <fieldset class="row">
                            <div class="white-box">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center">บันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-7"></div>
                                        <div class="col-sm-5 form-group">
                                            <label class="col-md-4 text-right ">เลขที่เอกสาร</label>
                                            <div class="col-md-8">
                                              {!! Form::text('auto_id_doc', null, ['class' => 'form-control', 'placeholder'=>'Auto']); !!}
                                            </div>
                                        </div>
                                    </div>
                          
                                    <div class="row">
                                        <div class="col-md-12">

    

                                            {{-- <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ชื่อผู้รับใบอนุญาต</label>
                                                    <div class="col-md-6">
                                                        <select name="tradeName"
                                                                id="tis_standard"
                                                                class="form-control"
                                                                onchange="">
                                                            <option>-เลือกผู้รับใบอนุญาต-</option>
                                                            @foreach(HP::get_tb4_name() as $Autono=>$tbl_tradeName)
                                                                <option id="tradeName"
                                                                        value="{{$Autono}}">{{$tbl_tradeName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">มาตรฐาน</label>
                                                    <div class="col-md-6">
                                                       {{ Form::select('tis_standard',
                                                              HP::TisListSample(),
                                                            null,
                                                            ['class' => 'form-control ',
                                                             'id' => 'tis_standard',
                                                             'autocomplete' => "off",
                                                             'placeholder' =>'-เลือกมาตรฐาน-'] )
                                                        }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ชื่อผู้รับใบอนุญาต</label>
                                    
                                                     <div class="col-md-6">
                                                        {{ Form::select('tradeName',
                                                             [],
                                                             null,
                                                             ['class' => 'form-control ',
                                                              'id' => 'filter_tb4_License',
                                                              'autocomplete' => "off",
                                                              'placeholder' =>'-เลือกผู้รับใบอนุญาต-'] )
                                                         }}
                                                    </div>
                                               </div>
                                           </div>
                                           
                                           
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ชื่อเจ้าของ/ผู้แทน </label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="owner" id="" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-sm-1"></label>
                                                    <label class="col-sm-1 text-right">ตั้งอยู่เลขที่</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_no" class="form-control">
                                                    </div>

                                                    <label class="col-sm-1 text-right">หมู่ที่</label>
                                                    <div class="col-sm-2">
                                                        <input type="text"
                                                               name="address_village_no"
                                                               class="form-control">
                                                    </div>

                                                    <label class="col-sm-1 text-right">ตรอก/ซอย</label>
                                                    <div class="col-sm-2">
                                                        <input type="text"
                                                               name="address_alley"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-sm-1"></label>
                                                    <label class="col-sm-1 text-right">ถนน</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="address_road" class="form-control">
                                                    </div>

                                                    <label class="col-sm-1 text-right">จังหวัด</label>
                                                    <div class="col-sm-2">
                                                        <select name="address_province"
                                                                id="address_province"
                                                                class="form-control"
                                                                onchange="add_filter_address_province();remove_filter_address_province()">
                                                            <option>-เลือกจังหวัด-</option>
                                                            @foreach(HP::get_address_province() as $PROVINCE_ID=>$PROVINCE_NAME)
                                                                <option id="address_province"
                                                                        value="{{$PROVINCE_ID}}">{{$PROVINCE_NAME}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <label class="col-sm-1 text-right">อำเภอ/เขต</label>
                                                    <div class="col-sm-2">
                                                        <select name="address_amphoe"
                                                                id="address_amphoe"
                                                                class="form-control"
                                                                onchange="add_filter_address_amphoe();remove_filter_address_amphoe()">
                                                            <option>-เลือกอำเภอ/เขต-</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-sm-1"></label>
                                                    <label class="col-sm-1 text-right">ตำบล/แขวง</label>
                                                    <div class="col-sm-2">
                                                        <select name="address_district"
                                                                id="address_district"
                                                                class="form-control">
                                                            <option>-เลือกตำบล/แขวง-</option>
                                                        </select>
                                                    </div>

                                                    <label class="col-sm-1 text-right">รหัสไปรษณีย์</label>
                                                    <div class="col-sm-2">
                                                        <input type="text"
                                                               name="address_zip_code"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="">
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12 ">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-1"></div>
                                                                        <h3 class="col-md-2">รายการยึด</h3>
                                                                        <div class="col-md-8 m-b-10 text-right">
                                                                            <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                    name="add_data_seize"
                                                                                    id="add_data_seize"
                                                                                    onClick="return false;">
                                                                                <span class="btn-label"><i
                                                                                            class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-10">
                                                            <div class="row">
                                                                <div class="form-group text-center">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered"
                                                                               id="myTable">
                                                                            <thead>
                                                                            <tr bgcolor="#DEEBF7">
                                                                                <th style="width: 1%;">#</th>
                                                                                <th style="width: 15%;">รายการ</th>
                                                                                <th style="width: 5%;">จำนวน</th>
                                                                                <th style="width: 5%;">หน่วย</th>
                                                                                <th style="width: 8%;">มูลค่า</th>
                                                                                <th style="width: 2%;">จัดการ</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="form-group text-center">
                                                                    <label class="m-r-5">รวมรายการยึดทั้งหมด
                                                                        จำนวน</label>
                                                                    <span id="sum_row2"></span>
                                                                    <label class="m-r-20">รายการ</label>

                                                                    <label class="m-r-5">รวมมูลค่า</label>
                                                                    <input style="width: 20%;text-decoration: underline dotted;"
                                                                           class="m-r-5 input-custom text-center"
                                                                           id="total_value_seizure" disabled>
                                                                    <input name="total_value_seizure"
                                                                           id="total_value_seizure2" hidden>
                                                                    <label class="m-l-5">บาท</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-12 ">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-1"></div>
                                                                        <h3 class="col-md-2">รายการอายัด</h3>
                                                                        <div class="col-md-8 m-b-10 text-right">
                                                                            <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                    name="add_data_freeze"
                                                                                    id="add_data_freeze"
                                                                                    onClick="return false;">
                                                                                <span class="btn-label"><i
                                                                                            class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-10">
                                                            <div class="row">
                                                                <div class="form-group text-center">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered"
                                                                               id="myTable2">
                                                                            <thead>
                                                                            <tr bgcolor="#DEEBF7">
                                                                                <th style="width: 1%;">#</th>
                                                                                <th style="width: 15%;">รายการ</th>
                                                                                <th style="width: 5%;">จำนวน</th>
                                                                                <th style="width: 5%;">หน่วย</th>
                                                                                <th style="width: 8%;">มูลค่า</th>
                                                                                <th style="width: 2%;">จัดการ</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="form-group text-center">
                                                                    <label class="m-r-5">รวมรายการอายัดทั้งหมด
                                                                        จำนวน</label>
                                                                    <span id="sum_row"></span>
                                                                    <label class="m-r-20">รายการ</label>

                                                                    <label class="m-r-5">รวมมูลค่า</label>
                                                                    <input style="width: 20%;text-decoration: underline dotted"
                                                                           class="m-r-5 input-custom text-center"
                                                                           id="total_value_freeze" disabled>
                                                                    <input name="total_value_freeze"
                                                                           id="total_value_freeze2" hidden>
                                                                    <label class="m-l-5">บาท</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="row m-b-10">
                                        <div class="form-group">
                                            <label class="col-sm-1"></label>
                                            <label class="col-sm-2">สถานที่เก็บผลิตภัณฑ์ที่ยึด/อายัด</label>
                                        </div>
                                    </div>
                                    <div class="row m-b-10">
                                        <div class="form-group">
                                            <label class="col-sm-1"></label>
                                            <label class="col-sm-1 text-right">ตั้งอยู่เลขที่</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="keep_product_address_no" class="form-control">
                                            </div>

                                            <label class="col-sm-1 text-right">หมู่ที่</label>
                                            <div class="col-sm-2">
                                                <input type="text"
                                                       name="keep_product_address_village_no"
                                                       class="form-control">
                                            </div>

                                            <label class="col-sm-1 text-right">ตรอก/ซอย</label>
                                            <div class="col-sm-2">
                                                <input type="text"
                                                       name="keep_product_address_alley"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-10">
                                        <div class="form-group">
                                            <label class="col-sm-1"></label>
                                            <label class="col-sm-1 text-right">ถนน</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="keep_product_address_road"
                                                       class="form-control">
                                            </div>

                                            <label class="col-sm-1 text-right">จังหวัด</label>
                                            <div class="col-sm-2">
                                                <select name="keep_product_address_province"
                                                        id="address_province2"
                                                        class="form-control"
                                                        onchange="add_filter_address_province2();remove_filter_address_province2()">
                                                    <option>-เลือกจังหวัด-</option>
                                                    @foreach(HP::get_address_province() as $PROVINCE_ID=>$PROVINCE_NAME)
                                                        <option id="address_province2"
                                                                value="{{$PROVINCE_ID}}">{{$PROVINCE_NAME}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <label class="col-sm-1 text-right">อำเภอ/เขต</label>
                                            <div class="col-sm-2">
                                                <select name="keep_product_address_amphoe"
                                                        id="address_amphoe2"
                                                        class="form-control"
                                                        onchange="add_filter_address_amphoe2();remove_filter_address_amphoe2()">
                                                    <option>-เลือกอำเภอ/เขต-</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-30">
                                        <div class="form-group">
                                            <label class="col-sm-1"></label>
                                            <label class="col-sm-1 text-right">ตำบล/แขวง</label>
                                            <div class="col-sm-2">
                                                <select name="keep_product_address_district"
                                                        id="address_district2"
                                                        class="form-control">
                                                    <option>-เลือกตำบล/แขวง-</option>
                                                </select>
                                            </div>

                                            <label class="col-sm-1 text-right">รหัสไปรษณีย์</label>
                                            <div class="col-sm-2">
                                                <input type="text"
                                                       name="keep_product_address_zip_code"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row m-b-10">
                                        <div class="form-group">
                                            <label class="col-md-1"></label>
                                            <label class="col-md-2 text-right">ผู้ตรวจตรวจยึด/อายัด :</label>
                                            <div class="col-sm-5">
                                                <input type="text"
                                                       name="check_officer"
                                                       class="form-control"
                                                       value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                       disabled>
                                                <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                       name="check_officer" hidden>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-20">
                                        <div class="form-group">
                                            <label class="col-md-1"></label>
                                            <label class="col-md-2 text-right">วันที่ตรวจยึด/อายัด :</label>
                                            <div class="col-sm-5">
                                                <input type="text"
                                                       name="date_now"
                                                       class="form-control"
                                                       value="{{date("d/m/Y")}}"
                                                       disabled>
                                                <input value="{{date("d/m/Y")}}" name="date_now" hidden>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="status_btn"></div>
                                    <div class="row m-b-10">
                                        <div class="form-group text-center">
                                            <button class="btn btn-info btn-sm waves-effect waves-light m-r-30"
                                                    type="submit"
                                                    onclick="add_status_btn('ยึด/อายัด')">
                                                <b>บันทึก</b>
                                            </button>
                                            <a class="btn btn-default btn-sm waves-effect waves-light"
                                               href="{{ url('/csurv/control_freeze') }}">
                                                <b>ยกเลิก</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>


                        {{--                        <div style="display: flex; align-items: flex-end;">--}}
                        {{--                            <fieldset class="row wrapper-detail" style="width: 80%">--}}
                        {{--                                <legend> การถอนยึด/อายัด</legend>--}}

                        {{--                                <div>--}}
                        {{--                                    <div class="col-sm-8" style="margin-left: 8%;" align="right">--}}
                        {{--                                        <input type="checkbox" class="col-sm-1">--}}
                        {{--                                        <div class="col-sm-2" align="right"> ถอนยึด/อายัด</div>--}}
                        {{--                                        <input type="text" name="premise" id="datepicker-time-freeze" class="col-sm-3">--}}
                        {{--                                        <div class="col-sm-1" align="right"> โดย</div>--}}
                        {{--                                        <input type="text" name="premise" id="not_premise" class="col-sm-5" disabled>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </fieldset>--}}

                        {{--                            <div style="margin-left: 10%;">--}}
                        {{--                                <div>--}}
                        {{--                                    <button class="btn btn-info btn-sm waves-effect waves-light"--}}
                        {{--                                            style="font-size: 14px; width: 100%"--}}
                        {{--                                            type="submit">บันทึก--}}
                        {{--                                    </button>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="col-sm-12" style="margin-bottom: 10px"></div>--}}
                        {{--                                <div>--}}
                        {{--                                    <a class="btn btn-default btn-sm waves-effect waves-light"--}}
                        {{--                                       style="font-size: 14px; width: 100%"--}}
                        {{--                                       href="{{ url('/csurv/control_freeze') }}">--}}
                        {{--                                        <i class="fa fa-undo"></i><b> ยกเลิก</b>--}}
                        {{--                                    </a>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_freeze/save')}}",
                datatype: "script",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/csurv/control_freeze')}}"
                    } else if (data.status == "error") {
                        // $("#alert").html('<div class="alert alert-danger"><strong>แจ้งเตือน !</strong> ' + data.message + ' <br></div>');
                        alert(data.message)
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });

        });

        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="status" value="' + status + '" hidden>');
        }

        $('#datepicker-time').datepicker({
            autoclose: true
        }).datepicker("setDate", new Date());

        $('#datepicker-time-freeze').datepicker({
            autoclose: true
        }).datepicker("setDate", new Date());

        var temp_row2 = 1;

        function add_input_seize() {

            var next_num = $('.sub_input').length + 1;
            var html_add_item = '<tr class="sub_input">';
            $('#sum_row2').html('<label id="sum_row_val2" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + temp_row2 + '</label><input id="sum_row_val2" type="text" name="total_list_seizure"  value="' + temp_row2 + '" hidden>');

            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row1[]"/><span class="running-no">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="list_seizure[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control" name="amount_seizure[]" OnKeyPress="return chkNumber(this)" value="0"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="unit_seizure[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control pages" name="value_seizure[]" OnKeyPress="return chkNumber(this)" OnChange="chkNum(this)" oninput="totalPgs()" value="0"></td>';
            html_add_item += '<td>' +
                '<a class="btn btn-small btn-danger btn-sm remove-data_seize" onclick="remove_row2(' + temp_row2 + ')"><span class="fa fa-trash"></span></a>' +
                '</td>';
            html_add_item += '</tr>';
            $('#myTable tbody').append(html_add_item);
            temp_row2++;
        }

        function remove_row2(row) {
            temp_row2--;
            $('#sum_row_val2').val(temp_row2 - 1)
            var num = temp_row2 - 1;
            $('#sum_row2').html('<label id="sum_row_val2" type="text" name="sum" style="width: 20%; text-decoration: underline dotted; text-align: center">' + num + '</label><input id="sum_row_val2" type="text" name="total_list_seizure"  value="' + num + '" hidden>');
        }

        $('#add_data_seize').click(function () {
            add_input_seize();
        });

        function chkNumber(ele) {
            var vchar = String.fromCharCode(event.keyCode);
            if ((vchar < '0' || vchar > '9') && (vchar != '.')) return false;
            ele.onKeyPress = vchar;
        }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function chkNum(ele) {
            var num = parseFloat(ele.value.replace(/,/g, ''));
            ele.value = addCommas(num);
        }

        function totalPgs() {
            var out = document.getElementById('total_value_seizure');
            var pgs = document.querySelectorAll('.pages');
            var arr = Array.prototype.map.call(pgs, function (pg) {
                var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                return cnt;
            });

            var total = sum.apply(sum, arr);
            if (total != "NaN") {
                out.value = addCommas(total);
                document.getElementById('total_value_seizure2').value = addCommas(total);
                return total;
            }
        }

        function sum() {
            var res = 0;
            var i = 0;
            var qty = arguments.length;
            while (i < qty) {
                res += arguments[i];
                i++;
            }
            return res;
        }

        $(document).on('click', '.remove-data_seize', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(100);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_input').each(function (index, el) {
                    $(el).find('.running-no').text(index + 1);
                });
                var pgs = document.querySelectorAll('.pages');
                var out = document.getElementById('total_value_seizure');
                var arr = Array.prototype.map.call(pgs, function (pg) {
                    var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                    return cnt;
                });
                var total = sum.apply(sum, arr);
                out.value = addCommas(total);
                document.getElementById('total_value_seizure2').value = addCommas(total);
                return total;

            }, 500);

        });

        var temp_row = 1;

        function add_input_freeze() {

            var next_num = $('.sub_input2').length + 1;
            var html_add_item = '<tr class="sub_input2">';
            $('#sum_row').html('<label id="sum_row_val" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + temp_row + '</label><input id="sum_row_val" type="text" name="total_list_freeze"  value="' + temp_row + '" hidden>');

            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row2[]"/><span class="running-no2">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="list_freeze[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control" name="amount_freeze[]" OnKeyPress="return chkNumber(this)" value="0"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="unit_freeze[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control pages2" name="value_freeze[]" OnKeyPress="return chkNumber(this)" OnChange="chkNum(this)" oninput="totalPgs2()" value="0"></td>';
            html_add_item += '<td>' +
                '<a class="btn btn-small btn-danger btn-sm remove-data_freeze" onclick="remove_row(' + temp_row + ')"><span class="fa fa-trash"></span></a>' +
                '</td>';
            html_add_item += '</tr>';
            $('#myTable2 tbody').append(html_add_item);
            temp_row++;
        }

        function remove_row(row) {
            temp_row--;
            $('#sum_row_val').val(temp_row - 1)
            var num = temp_row - 1;
            $('#sum_row').html('<label id="sum_row_val" type="text" name="sum2" style="width: 20%; text-decoration: underline dotted; text-align: center">' + num + '</label><input id="sum_row_val" type="text" name="total_list_freeze"  value="' + num + '" hidden>');

        }

        $('#add_data_freeze').click(function () {
            add_input_freeze();
        });

        function totalPgs2() {
            var out = document.getElementById('total_value_freeze');
            var pgs = document.querySelectorAll('.pages2');
            var arr = Array.prototype.map.call(pgs, function (pg) {
                var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                return cnt;
            });
            var total = sum2.apply(sum2, arr);
            if (total != "NaN") {
                out.value = addCommas(total);
                document.getElementById('total_value_freeze2').value = addCommas(total);
                return total;
            }
        }

        function sum2() {
            var res = 0;
            var i = 0;
            var qty = arguments.length;
            while (i < qty) {
                res += arguments[i];
                i++;
            }
            return res;
        }

        $(document).on('click', '.remove-data_freeze', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(300);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_input2').each(function (index, el) {
                    $(el).find('.running-no2').text(index + 1);
                });
                var pgs = document.querySelectorAll('.pages2');
                var out = document.getElementById('total_value_freeze');
                var arr = Array.prototype.map.call(pgs, function (pg) {
                    var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                    return cnt;
                });
                var total = sum2.apply(sum2, arr);
                out.value = addCommas(total);
                document.getElementById('total_value_freeze2').value = addCommas(total);
                return total;

            }, 500);
        });

        function add_filter_address_province() {
            var data_val = $("#address_province :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_performance/add_filter_address_province')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var opt;
                    $.each(list, function (key, val) {
                        opt += "<option id=\"address_amphoe\" value='" + val.AMPHUR_ID + "'>" + val.AMPHUR_NAME + "</option>"
                    });
                    $("#address_amphoe").html(opt);
                }
            });
        }

        function remove_filter_address_province() {
            $('#address_amphoe').empty()
        }

        function add_filter_address_amphoe() {
            var data_val = $("#address_amphoe :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_performance/add_filter_address_district')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var opt;
                    $.each(list, function (key, val) {
                        console.log(val.DISTRICT_NAME)
                        opt += "<option id=\"address_district\" value='" + val.DISTRICT_ID + "'>" + val.DISTRICT_NAME + "</option>"
                    });
                    $("#address_district").html(opt);
                }
            });
        }

        function remove_filter_address_amphoe() {
            $('#address_district').empty()
        }

        function add_filter_address_province2() {
            var data_val = $("#address_province2 :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_performance/add_filter_address_province')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var opt;
                    $.each(list, function (key, val) {
                        opt += "<option id=\"address_amphoe2\" value='" + val.AMPHUR_ID + "'>" + val.AMPHUR_NAME + "</option>"
                    });
                    $("#address_amphoe2").html(opt);
                }
            });
        }

        function remove_filter_address_province2() {
            $('#address_amphoe2').empty()
        }

        function add_filter_address_amphoe2() {
            var data_val = $("#address_amphoe2 :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_performance/add_filter_address_district')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var opt;
                    $.each(list, function (key, val) {
                        opt += "<option id=\"address_district2\" value='" + val.DISTRICT_ID + "'>" + val.DISTRICT_NAME + "</option>"
                    });
                    $("#address_district2").html(opt);
                }
            });
        }

        function remove_filter_address_amphoe2() {
            $('#address_district2').empty()
        }

        $(document).ready(function () {
            $('input[type="radio"]').click(function () {
                if ($(this).attr('id') == 'freeze_table') {
                    $('#table_fre').show();
                } else {
                    $('#table_fre').hide();
                }
            });
        });

        $(document).ready(function () {
            $('input[type="radio"]').click(function () {
                if ($(this).attr('id') == 'seize_table') {
                    $('#table_sei').show();
                } else {
                    $('#table_sei').hide();
                }
            });
        });

        jQuery(document).ready(function() {
        $('#tis_standard').change(function(){
            $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('ssurv/save_example/get_filter_tb4_License/list') !!}/" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                             $('#filter_tb4_License').append('<option value="'+index+'">'+data+'</option>');
                         });
                    });
                    $('#filter_tb4_License').select2();
                }else{
                    $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
                }
            });
            $('#tis_standard').change();
        });
        // function add_filter_tb4_License(select_item) {
        //             var tb3_Tisno = select_item.value;
        //     if(tb3_Tisno != ''){
        //         $.ajax({
        //                 type: "GET",
        //                 url: "{{url('/ssurv/save_example/get_filter_tb4_License')}}",
        //                 datatype: "html",
        //                 data: {
        //                     tb3_Tisno: tb3_Tisno,
        //                     '_token': "{{ csrf_token() }}",
        //                 },
        //                 success: function (data) {
        //                     var response = data;
        //                     var list = response.data;
        //                     if (list.length != 0) {
        //                         $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
        //                         for (let i = 0; i < list.length; i++) {
        //                             $('#filter_tb4_License').append('<option id="filter_tb4_License" name="' + list[i] + '" value="' + list[i] + '">' + list[i] + '</option>');
        //                         }
        //                     } else {
        //                         $('#filter_tb4_License').empty();
        //                     }
        //                 }
        //             });
        //         }else{
        //             $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
        //         }
                   

        //         }

    </script>
@endpush

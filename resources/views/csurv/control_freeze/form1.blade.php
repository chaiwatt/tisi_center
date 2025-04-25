


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

        legend {
            width: auto; /* Or auto */
            padding: 0 10px; /* To give a bit of padding on the left and right */
            border-bottom: none;
            font-size: 14px;
        }

        fieldset {
            padding: 20px;
        }

        .input-custom {
            background: transparent;
            border-top: transparent !important;
            border-left: transparent !important;
            border-right: transparent !important;
            border-bottom: transparent !important;
            color: #8d9498;
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
                                <h1 class="box-title">ระบบบันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม </h1>
                                <hr class="hr-line bg-primary">
                            </div>
                        </div>

                        <fieldset class="row">
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center">บันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม</h3>
                                    </div>
                                </div>
                
                                <div class="row">
                                    <div class="col-sm-7">  
                                          <input name="id" type="hidden"  value="{{$data->id}}">
                                    </div>
                                    <div class="col-sm-5 form-group">
                                        <label class="col-md-4 text-right ">เลขที่เอกสาร</label>
                                        <div class="col-md-8">
                                          {!! Form::text('auto_id_doc', @$data->auto_id_doc, ['class' => 'form-control', 'placeholder'=>'Auto']); !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">


                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <label class="col-md-2 text-right">มาตรฐาน</label>
                                                <div class="col-md-6">
                                                   {{ Form::select('tis_standard',
                                                          HP::TisListSample(),
                                                          $data->tis_standard,
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
                                                    <input type="text" name="owner" id="" class="form-control"
                                                           value="{{$data->owner}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <label class="col-sm-1"></label>
                                                <label class="col-sm-1 text-right">ตั้งอยู่เลขที่</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="address_no" class="form-control"
                                                           value="{{$data->address_no}}">
                                                </div>

                                                <label class="col-sm-1 text-right">หมู่ที่</label>
                                                <div class="col-sm-2">
                                                    <input type="text"
                                                           name="address_village_no"
                                                           class="form-control"
                                                           value="{{$data->address_village_no}}">
                                                </div>

                                                <label class="col-sm-1 text-right">ตรอก/ซอย</label>
                                                <div class="col-sm-2">
                                                    <input type="text"
                                                           name="address_alley"
                                                           class="form-control"
                                                           value="{{$data->address_alley}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="form-group">
                                                <label class="col-sm-1"></label>
                                                <label class="col-sm-1 text-right">ถนน</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="address_road" class="form-control"
                                                           value="{{$data->address_road}}">
                                                </div>

                                                <label class="col-sm-1 text-right">จังหวัด</label>
                                                <div class="col-sm-2">
                                                    <select name="address_province"
                                                            id="address_province"
                                                            class="form-control"
                                                            onchange="add_filter_address_province();remove_filter_address_province()">
                                                        @if($data->address_province!='-เลือกจังหวัด-')
                                                            <option value="{{$data->address_province}}" selected>{{HP::gat_province($data->address_province)}}</option>
                                                        @else
                                                            <option>-เลือกจังหวัด-</option>
                                                        @endif
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
                                                        @if($data->address_amphoe!=0)
                                                            <option value="{{$data->address_amphoe}}" selected>{{HP::gat_amphur($data->address_amphoe)}}</option>
                                                        @else
                                                            <option>-เลือกอำเภอ/เขต-</option>
                                                        @endif
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
                                                        @if($data->address_district!=0)
                                                            <option value="{{$data->address_district}}" selected>{{HP::gat_district($data->address_district)}}</option>
                                                        @else
                                                            <option>-เลือกตำบล/แขวง-</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <label class="col-sm-1 text-right">รหัสไปรษณีย์</label>
                                                <div class="col-sm-2">
                                                    <input type="text"
                                                           name="address_zip_code"
                                                           class="form-control"
                                                           value="{{$data->address_zip_code}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <div class="form-group">
                                                                <div class="col-md-1"></div>
                                                                <label class="col-md-2">รายการยึด</label>
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
                                                                <table class="table table-bordered" id="myTable">
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
                                                                    @foreach($data_seizure as $key => $list1)
                                                                        <tr class="sub_input">
                                                                            <td><input type="hidden"
                                                                                       name="num_row1[]"/><span
                                                                                        class="running-no">{{ $loop->iteration}}</span>.
                                                                            </td>
                                                                            <td style="text-align: -webkit-center;">
                                                                                <input type="text"
                                                                                       style="width: 80%;"
                                                                                       class="form-control"
                                                                                       name="list_seizure[]"
                                                                                       value="{{$list1->list_seizure}}">
                                                                            </td>
                                                                            <td style="text-align: -webkit-center;">
                                                                                <input type="text"
                                                                                       style="width: 80%; text-align: right;"
                                                                                       class="form-control"
                                                                                       name="amount_seizure[]"
                                                                                       OnKeyPress="return chkNumber(this)"
                                                                                       value="{{$list1->amount_seizure}}">
                                                                            </td>
                                                                            <td style="text-align: -webkit-center;">
                                                                                <input type="text"
                                                                                       style="width: 80%;"
                                                                                       class="form-control"
                                                                                       name="unit_seizure[]"
                                                                                       value="{{$list1->unit_seizure}}">
                                                                            </td>
                                                                            <td style="text-align: -webkit-center;">
                                                                                <input type="text"
                                                                                       style="width: 80%; text-align: right;"
                                                                                       class="form-control pages"
                                                                                       name="value_seizure[]"
                                                                                       OnKeyPress="return chkNumber(this)"
                                                                                       OnChange="chkNum(this)"
                                                                                       oninput="totalPgs()"
                                                                                       value="{{$list1->value_seizure}}">
                                                                            </td>
                                                                            <td>
                                                                                <a class="btn btn-small btn-danger btn-sm remove-data_seize"
                                                                                   onclick="remove_row2({{ $loop->iteration}})"><span
                                                                                            class="fa fa-trash"></span></a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
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
                                                            <label class="m-r-5">รวมรายการยึดทั้งหมด จำนวน</label>
                                                            <span id="sum_row22"
                                                                  style="width:5%;text-decoration: underline dotted;">{{$data->total_list_seizure}}</span>
                                                            <input name="total_list_seizure"
                                                                   id="total_list_seizure"
                                                                   value="{{$data->total_list_seizure}}" hidden>
                                                            <span id="sum_row2"></span>

                                                            <label class="m-r-20">รายการ</label>

                                                            <label class="m-r-5">รวมมูลค่า</label>
                                                            <input style="width: 15%; text-decoration: underline dotted; text-align: right;"
                                                                   class="m-r-5 input-custom text-center"
                                                                   id="total_value_seizure"
                                                                   value="{{$data->total_value_seizure}}" disabled>
                                                            <input name="total_value_seizure"
                                                                   id="total_value_seizure2"
                                                                   value="{{$data->total_value_seizure}}" hidden>
                                                            <label class="m-l-5">บาท</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="p-40">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12 ">
                                                                <div class="row">
                                                                    <div class="form-group ">
                                                                        <div class="form-group">
                                                                            <div class="col-md-1"></div>
                                                                            <label class="col-md-2">รายการอายัด</label>
                                                                            <div class="col-md-8 m-b-10 text-right">
                                                                                <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                        name="add_data_freeze"
                                                                                        id="add_data_freeze"
                                                                                        onClick="return false;">
                                                                    <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
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
                                                                                @foreach($data_freeze as $list2)
                                                                                    <tr class="sub_input2">
                                                                                        <td><input type="hidden"
                                                                                                   name="num_row2[]"/><span
                                                                                                    class="running-no2">{{ $loop->iteration}}</span>.
                                                                                        </td>
                                                                                        <td style="text-align: -webkit-center;">
                                                                                            <input
                                                                                                    type="text"
                                                                                                    style="width: 80%;"
                                                                                                    class="form-control"
                                                                                                    name="list_freeze[]"
                                                                                                    value="{{$list2->list_freeze}}">
                                                                                        </td>
                                                                                        <td style="text-align: -webkit-center;">
                                                                                            <input
                                                                                                    type="text"
                                                                                                    style="width: 80%; text-align: right;"
                                                                                                    class="form-control"
                                                                                                    name="amount_freeze[]"
                                                                                                    OnKeyPress="return chkNumber(this)"
                                                                                                    value="{{$list2->amount_freeze}}">
                                                                                        </td>
                                                                                        <td style="text-align: -webkit-center;">
                                                                                            <input
                                                                                                    type="text"
                                                                                                    style="width: 80%;"
                                                                                                    class="form-control"
                                                                                                    name="unit_freeze[]"
                                                                                                    value="{{$list2->unit_freeze}}">
                                                                                        </td>
                                                                                        <td style="text-align: -webkit-center;">
                                                                                            <input type="text"
                                                                                                   style="width: 80%; text-align: right;"
                                                                                                   class="form-control pages2"
                                                                                                   name="value_freeze[]"
                                                                                                   OnKeyPress="return chkNumber(this)"
                                                                                                   OnChange="chkNum(this)"
                                                                                                   oninput="totalPgs2()"
                                                                                                   value="{{$list2->value_freeze}}">
                                                                                        </td>
                                                                                        <td>
                                                                                            <a class="btn btn-small btn-danger btn-sm remove-data_freeze"
                                                                                               onclick="remove_row({{ $loop->iteration}})"><span
                                                                                                        class="fa fa-trash"></span></a>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
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
                                                                        <span id="sum_row11"
                                                                              style="width:5%;text-decoration: underline dotted;">{{$data->total_list_freeze}}</span>
                                                                        <input name="total_list_freeze"
                                                                               id="total_list_freeze"
                                                                               value="{{$data->total_list_freeze}}"
                                                                               hidden>
                                                                        <span id="sum_row"></span>
                                                                        <label class="m-r-20">รายการ</label>

                                                                        <label class="m-r-5">รวมมูลค่า</label>
                                                                        <input style="width: 15%;text-decoration: underline dotted; text-align: right; "
                                                                               class="m-r-5 input-custom text-center"
                                                                               id="total_value_freeze"
                                                                               value="{{$data->total_value_freeze}}"
                                                                               disabled>
                                                                        <input name="total_value_freeze"
                                                                               id="total_value_freeze2"
                                                                               value="{{$data->total_value_freeze}}"
                                                                               hidden>
                                                                        <label class="m-l-5">บาท</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2"></div>
                                                        </div>
                                                    </div>

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
                                                                <input type="text" name="keep_product_address_no"
                                                                       class="form-control"
                                                                       value="{{$data->keep_product_address_no}}">
                                                            </div>

                                                            <label class="col-sm-1 text-right">หมู่ที่</label>
                                                            <div class="col-sm-2">
                                                                <input type="text"
                                                                       name="keep_product_address_village_no"
                                                                       class="form-control"
                                                                       value="{{$data->keep_product_address_village_no}}">
                                                            </div>

                                                            <label class="col-sm-1 text-right">ตรอก/ซอย</label>
                                                            <div class="col-sm-2">
                                                                <input type="text"
                                                                       name="keep_product_address_alley"
                                                                       class="form-control"
                                                                       value="{{$data->keep_product_address_alley}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row m-b-10">
                                                        <div class="form-group">
                                                            <label class="col-sm-1"></label>
                                                            <label class="col-sm-1 text-right">ถนน</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="keep_product_address_road"
                                                                       class="form-control"
                                                                       value="{{$data->keep_product_address_road}}">
                                                            </div>

                                                            <label class="col-sm-1 text-right">จังหวัด</label>
                                                            <div class="col-sm-2">
                                                                <select name="keep_product_address_province"
                                                                        id="address_province2"
                                                                        class="form-control"
                                                                        onchange="add_filter_address_province2();remove_filter_address_province2()">
                                                                    @if($data->keep_product_address_province!='-เลือกจังหวัด-')
                                                                        <option value="{{$data->keep_product_address_province}}" selected>{{HP::gat_province($data->keep_product_address_province)}}</option>
                                                                    @else
                                                                        <option>-เลือกจังหวัด-</option>
                                                                    @endif
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
                                                                    @if($data->keep_product_address_amphoe!=0)
                                                                        <option value="{{$data->keep_product_address_amphoe}}" selected>{{HP::gat_amphur($data->keep_product_address_amphoe)}}</option>
                                                                    @else
                                                                        <option>-เลือกอำเภอ/เขต-</option>
                                                                    @endif
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
                                                                    @if($data->keep_product_address_district!==0)
                                                                        <option value="{{$data->keep_product_address_district}}" selected>{{HP::gat_district($data->keep_product_address_district)}}</option>
                                                                    @else
                                                                        <option>-เลือกตำบล/แขวง-</option>
                                                                    @endif
                                                                </select>
                                                            </div>

                                                            <label class="col-sm-1 text-right">รหัสไปรษณีย์</label>
                                                            <div class="col-sm-2">
                                                                <input type="text"
                                                                       name="keep_product_address_zip_code"
                                                                       class="form-control"
                                                                       value="{{$data->keep_product_address_zip_code}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                          
                                                    <div class="row m-b-10">
                                                        <div class="form-group">
                                                            <label class="col-md-1"></label>
                                                            <label class="col-md-2 text-right">ผู้ตรวจประเมิน :</label>
                                                            <div class="col-sm-5">
                                                                <input value="{{$data->check_officer}}"
                                                                       name="check_officer"
                                                                       class="form-control" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row m-b-20">
                                                        <div class="form-group">
                                                            <label class="col-md-1"></label>
                                                            <label class="col-md-2 text-right">วันที่ตรวจประเมิน
                                                                :</label>
                                                            <div class="col-sm-5">
                                                                <input type="text"
                                                                       name="date_now"
                                                                       class="form-control"
                                                                       value="{{$data->date_now}}"
                                                                       disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="row">
                            <fieldset class="row wrapper-detail">
                                <legend> การถอนยึด/อายัด</legend>

                                <div>
                                    <div class="col-sm-8" style="margin-left: 8%;" align="right">
                                        <div class="col-sm-1">
                                            <input class="check" name="check_status" value="1" type="checkbox" data-checkbox="icheckbox_square-green">
                                        </div>
                                        <div class="col-sm-2" align="right"> ถอนยึด/อายัด</div>
                                        <div class="col-sm-3">
                                            <input type="text" name="date_freeze" id="datepicker-time-freeze"
                                                   class="col-sm-3 form-control">
                                        </div>
                                        <div class="col-sm-1" align="right"> โดย</div>
                                        <div class="col-sm-5">
                                            <input type="text" name="officer_freeze" id="not_premise"
                                                   class="col-sm-5 form-control"
                                                   value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                   disabled>
                                        </div>
                                        <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                               name="premise" hidden>
                                    </div>
                                </div>
                                <div id="status_btn"></div>

                                <div class="form-group text-center">
                                    <div class="col-sm-12" style="margin-bottom: 20px"></div>
                                    <button class="btn btn-info btn-sm waves-effect waves-light"
                                            style="font-size: 14px;"
                                            type="submit">บันทึก
                                    </button>
                                    <a class="btn btn-default btn-sm waves-effect waves-light"
                                       style="font-size: 14px;"
                                       href="{{ url('/csurv/control_freeze') }}">
                                        <i class="fa fa-undo"></i><b> ยกเลิก</b>
                                    </a>
                                </div>
                            </fieldset>
                        </div>

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
            window.onload = function () {
                @if($data->address_province!=0)
                add_filter_address_province()
                @endif
                @if($data->address_amphoe!=0)
                add_filter_address_amphoe()
                @endif
                @if($data->keep_product_address_province!=0)
                add_filter_address_province2()
                @endif
                @if($data->keep_product_address_amphoe!=0)
                add_filter_address_amphoe2()
                @endif
            }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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
                        if ('{{$data->address_amphoe}}' != val.AMPHUR_ID) {
                            opt = "<option id=\"address_amphoe\" value='" + val.AMPHUR_ID + "'>" + val.AMPHUR_NAME + "</option>"
                            $("#address_amphoe").append(opt);
                        }
                    });
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
                        if ('{{$data->address_district}}' != val.DISTRICT_ID) {
                            opt = "<option id=\"address_district\" value='" + val.DISTRICT_ID + "'>" + val.DISTRICT_NAME + "</option>"
                            $("#address_district").append(opt);
                        }
                    });
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
                        if ('{{$data->keep_product_address_amphoe}}' != val.AMPHUR_ID) {
                            opt = "<option id=\"address_amphoe2\" value='" + val.AMPHUR_ID + "'>" + val.AMPHUR_NAME + "</option>"
                            $("#address_amphoe2").append(opt);
                        }
                    });
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
                        if ('{{$data->keep_product_address_district}}' != val.DISTRICT_ID) {
                            opt = "<option id=\"address_district2\" value='" + val.DISTRICT_ID + "'>" + val.DISTRICT_NAME + "</option>"
                            $("#address_district2").append(opt);
                        }
                    });
                }
            });
        }

        function remove_filter_address_amphoe2() {
            $('#address_district2').empty()
        }

        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_freeze/update')}}",
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

        function add_date_freeze() {
            $('#datepicker-time-freeze').prop('required', true)
        }

        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="status" value="' + status + '" hidden>');
        }

        $("#datepicker-time-freeze").datepicker({dateFormat: 'dd/mm/yy'}).datepicker("setDate", new Date());

        var temp_row2 = $('.sub_input').length + 1;

        function add_input_seize() {
            $('#sum_row22').remove()
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
            $('#sum_row22').remove()
            temp_row2--;
            $('#sum_row_val2').val(temp_row2 - 1)
            var num = temp_row2 - 1;
            $('#sum_row2').html('<label id="sum_row_val2" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + num + '</label><input id="sum_row_val2" type="text" name="total_list_seizure"  value="' + num + '" hidden>');
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

        var temp_row = $('.sub_input2').length + 1;

        function add_input_freeze() {
            $('#sum_row11').remove()

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
            $('#sum_row11').remove()
            var tem_num = $('.sub_input2').length - 1
            temp_row--;
            $('#sum_row_val').val(temp_row - 1)
            var num = temp_row - 1;
            $('#sum_row').html('<label id="sum_row_val" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + tem_num + '</label><input id="sum_row_val" type="text" name="total_list_freeze"  value="' + tem_num + '" hidden>');
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
                var total = sum.apply(sum, arr);
                out.value = addCommas(total);
                document.getElementById('total_value_freeze2').value = addCommas(total);
                return total;

            }, 500);
        });


        jQuery(document).ready(function() {
        $('#tis_standard').change(function(){
            $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
                if($(this).val()!=""){
                     var tradeName = '<?php  echo !empty($data->tradeName) ? $data->tradeName:null ?>';
                    $.ajax({
                        url: "{!! url('ssurv/save_example/get_filter_tb4_License/list') !!}/" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            var selected = (index == tradeName)?'selected="selected"':'';
                             $('#filter_tb4_License').append('<option value="'+index+'" '+ selected +'>'+data+'</option>');
                         });
                         $('#filter_tb4_License').select2();
                    });
                  
                }else{
                    $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
                }
            });
            $('#tis_standard').change();

        });
    </script>
@endpush

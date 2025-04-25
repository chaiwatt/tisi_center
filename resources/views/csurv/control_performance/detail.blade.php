@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
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

        .dottedUnderline {
            text-decoration: underline dotted;
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

        .select2-chosen {
            font-size: 85%;
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
                        <h3 class="box-title pull-left">ระบบบันทึกการตรวจประเมินระบบควบคุมคุณภาพ</h3>
                        @can('view-'.str_slug('control_performance'))
                        <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                       </a>
                        @endcan
                        <div class="clearfix"></div> 
                        <hr>
          

                        <div class="row form-group">
                            <div class="col-md-12">
                                <div style="border:#cccccc solid 0.1em" class="p-40">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center">บันทึกการตรวจประเมินระบบควบคุมคุณภาพ</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10 m-b-40">
                                            <label class="pull-right ">เลขที่เอกสาร</label>
                                        </div>
                                        <div class="dottedUnderline">
                                            <input name="id"
                                                   value="{{$data->id}}"
                                                   hidden>{{$data->auto_id_doc}}
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md-12">

                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ชื่อผู้รับใบอนุญาต</label>
                                                    <div class="col-md-8">
                                                        <select name="tradeName"
                                                                id="tis_standard"
                                                                class="form-control"
                                                                onchange="add_filter_License();remove_filter_License()"
                                                                disabled>
                                                            <option>{{ HP::get_tb4_name_index2($data->tradeName) }}</option>
                                                            @foreach(HP::get_tb4_tradername_and_oldname() as $tbl_taxpayer=>$tbl_tradeName)
                                                                <option id="tradeName"
                                                                        value="{{$tbl_taxpayer}}">{{$tbl_tradeName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row  m-b-10">
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right">มาตราฐาน</label>
                                                    <div class="col-md-7">
                                                        <select name="tbl_tisiNo"
                                                                id="tbl_tisiNo"
                                                                class="form-control"
                                                                disabled>
                                                            <option>{{ HP::get_tb3_tis_for_select($data->tbl_tisiNo) }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="col-md-3">
                                                            <label>มอก.</label>
                                                        </div>
                                                        <div class="dottedUnderline">
                                                            <div id="mog">{{ $data->tbl_tisiNo }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row  m-b-10">
                                                <div class="form-group">

                                                    <label class="col-md-2 text-right">ใบอนุญาต</label>
                                                    <div class="col-sm-10">
                                                        <input type="checkbox" name="check_all" id="check_all" disabled
                                                               class="check check-readonly" data-checkbox="icheckbox_square-green" checked>
                                                        <label>เลือกทั้งหมด</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 "></label>
                                                    <div class="col-sm-10 p-0">
                                                        <div class="row col-sm-12 p-0" id="license">
                                                            @foreach($data_permission as $list_permission)
                                                                <div class="col-sm-3">
                                                                    <label class="col-sm-12"><input disabled
                                                                                                    type="checkbox"
                                                                                                    name="sub_license[]"
                                                                                                    class="col-sm-2 check check-readonly"
                                                                                                    value="{{$list_permission->license}}"
                                                                                                    checked
                                                                                                    data-checkbox="icheckbox_square-green"> {{$list_permission->license}}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right small">ชื่อโรงงาน</label>
                                                    <div class="col-md-6">
                                                        <input disabled type="text" class="form-control"
                                                               name="factory_name" value="{{$data->factory_name}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">

                                                        <label class="col-sm-2 text-right small">ตั้งอยู่เลขที่</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_no"
                                                                   value="{{$data->address_no}}"
                                                                   disabled
                                                            >
                                                        </div>

                                                        <label class="col-sm-2 text-right small">นิคมอุตสาหกรรม
                                                            (ถ้ามี)</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_industrial_estate"
                                                                   value="{{$data->address_industrial_estate}}"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">

                                                        <label class="col-sm-2 text-right small">ตรอก/ซอย</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_alley"
                                                                   value="{{$data->address_alley}}"
                                                                   disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">ถนน</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_road"
                                                                   value="{{$data->address_road}}"
                                                                   disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">หมู่ที่</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_village_no"
                                                                   value="{{$data->address_village_no}}"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">

                                                        <label class="col-sm-2 text-right small">จังหวัด</label>
                                                        <div class="col-sm-2">
                                                            <select name="address_province"
                                                                    id="address_province"
                                                                    class="form-control"
                                                                    onchange="add_filter_address_province();remove_filter_address_province()"
                                                                    disabled>
                                                                @if($data->address_province!='-เลือกจังหวัด-')
                                                                    <option>{{HP::gat_province($data->address_province)}}</option>
                                                                @else
                                                                    <option>-เลือกจังหวัด-</option>
                                                                @endif
                                                                @foreach(HP::get_address_province() as $PROVINCE_ID=>$PROVINCE_NAME)
                                                                    <option id="address_province"
                                                                            value="{{$PROVINCE_ID}}">{{$PROVINCE_NAME}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">อำเภอ/เขต </label>
                                                        <div class="col-sm-2">
                                                            <select name="address_amphoe"
                                                                    id="address_amphoe"
                                                                    class="form-control"
                                                                    onchange="add_filter_address_amphoe();remove_filter_address_amphoe()"
                                                                    disabled>
                                                                @if($data->address_amphoe!=0)
                                                                    <option>{{HP::gat_amphur($data->address_amphoe)}}</option>
                                                                @else
                                                                    <option>-เลือกอำเภอ/เขต-</option>
                                                                @endif

                                                            </select>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">ตำบล/แขวง </label>
                                                        <div class="col-sm-2">
                                                            <select name="address_district"
                                                                    id="address_district"
                                                                    class="form-control"
                                                                    disabled>
                                                                @if($data->address_district!=0)
                                                                    <option>{{HP::gat_district($data->address_district)}}</option>
                                                                @else
                                                                    <option>-เลือกตำบล/แขวง-</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">

                                                        <label class="col-sm-2 text-right small">รหัสไปรษณีย์</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_zip_code"
                                                                   value="{{$data->address_zip_code}}"
                                                                   disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรศัพท์ </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control" name="tel"
                                                                   value="{{$data->tel}}" disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรสาร </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control" name="fax"
                                                                   value="{{$data->fax}}" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">

                                                        <label class="col-sm-2 text-right small">พิกัดที่ตั้ง
                                                            (ละติจูด)</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control" name="latitude"
                                                                   value="{{$data->latitude}}" disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">พิกัดที่ตั้ง
                                                            (ลองจิจูด)</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="Longitude"
                                                                   value="{{$data->Longitude}}" disabled>
                                                        </div>

                                                        <div class="col-sm-3 text-right small">
                                                            <button class="btn btn-default" onclick="show_map();"
                                                                    disabled>
                                                                ค้นหาจากแผนที่
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">

                                                    <label class="col-sm-2 text-right">วันที่ตรวจ</label>
                                                    <div class="col-sm-3 input-group">
                                                        <input type="text"
                                                               class="form-control pull-right"
                                                               name="checking_date"
                                                               id="datepicker-time"
                                                               value="{{$data->checking_date}}" disabled>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <div class="p-40">
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12 ">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2">บุคคลที่พบ</label>
                                                                        <div class="col-md-6 m-b-10 text-right">
                                                                            <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                    name="add_data"
                                                                                    id="add_data"
                                                                                    onClick="return false;"
                                                                                    disabled>
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

                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="form-group text-center">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered"
                                                                               id="myTable">
                                                                            <thead>
                                                                            <tr bgcolor="#DEEBF7">
                                                                                <th style="width: 1%;">#</th>
                                                                                <th style="width: 10%;">ชื่อ-สกุล</th>
                                                                                <th style="width: 8%;">ตำแหน่ง</th>
                                                                                <th style="width: 8%;">เบอร์โทร</th>
                                                                                <th style="width: 8%;">E-mail</th>
                                                                                <th style="width: 4%;">จัดการ</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($data_people_found as $list_found)
                                                                                <tr class="sub_input">
                                                                                    <td><input type="hidden"
                                                                                               value="{{ $loop->iteration}}"
                                                                                               name="num_row_people_found[]"/><span
                                                                                                class="running-no"> {{ $loop->iteration}} </span>.
                                                                                    </td>
                                                                                    <td style="text-align: -webkit-center;">
                                                                                        <input
                                                                                                type="text"
                                                                                                style="width: 80%; text-align: center"
                                                                                                class="form-control"
                                                                                                name="full_name[]"
                                                                                                value="{{$list_found->full_name}}"
                                                                                                disabled></td>
                                                                                    <td style="text-align: -webkit-center;">
                                                                                        <input
                                                                                                type="text"
                                                                                                style="width: 80%;text-align: center"
                                                                                                class="form-control"
                                                                                                name="permission[]"
                                                                                                value="{{$list_found->permission}}"
                                                                                                disabled></td>
                                                                                    <td style="text-align: -webkit-center;">
                                                                                        <input
                                                                                                type="text"
                                                                                                style="width: 80%;text-align: center"
                                                                                                class="form-control"
                                                                                                name="people_tel[]"
                                                                                                value="{{$list_found->people_tel}}"
                                                                                                disabled>
                                                                                    </td>
                                                                                    <td style="text-align: -webkit-center;">
                                                                                        <input
                                                                                                type="text"
                                                                                                style="width: 80%;text-align: center"
                                                                                                class="form-control"
                                                                                                name="people_email[]"
                                                                                                value="{{$list_found->people_email}}"
                                                                                                disabled>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a class="btn btn-small btn-danger"
                                                                                           onclick="return false;"
                                                                                           disabled><span
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
                                                        <div class="col-md-2"></div>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2">พนักงานเจ้าหน้าที่</label>
                                                                        <div class="col-md-6 m-b-10 text-right">
                                                                            <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                    name="add_staff"
                                                                                    id="add_staff"
                                                                                    onClick="return false;" disabled>
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

                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered"
                                                                               id="myTable2">
                                                                            <thead>
                                                                            <tr bgcolor="#DEEBF7">
                                                                                <th style="width: 1%;">#</th>
                                                                                <th style="width: 10%;">ชื่อ-สกุล</th>
                                                                                {{--<th style="width: 8%;">ตำแหน่ง</th>--}}
                                                                                <th style="width: 4%;">จัดการ</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            @foreach($data_officer as $list_officer)
                                                                                <tr class="sub_input_staff">
                                                                                    <td><input type="hidden"
                                                                                               value="{{ $loop->iteration}}"
                                                                                               name="num_row_permission[]"/><span
                                                                                                class="running-no">{{ $loop->iteration}}</span>.
                                                                                    </td>
                                                                                    <td style="text-align: -webkit-center;">
                                                                                        <select name="license[]"
                                                                                                id="license"
                                                                                                class="form-control"
                                                                                                disabled>
                                                                                            <option>{{$list_officer->full_name}}</option>
                                                                                            @foreach(HP::get_people_found() as $name)
                                                                                                <option id="license"
                                                                                                        value="{{$name->reg_fname . ' ' . $name->reg_lname}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    <td>
                                                                                        <a class="btn btn-small btn-danger"
                                                                                           onclick="return false;"
                                                                                           disabled><span
                                                                                                    class="fa fa-trash"></span></a>
                                                                                    </td>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2"></div>

                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12 ">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <strong class="col-md-4">1. การจัดซื้อและการควบคุมวัตถุดิบ</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-20">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ผลการประเมิน</label>
                                                                        <div class="col-sm-2 ">
                                                                            <input type="radio"
                                                                                   name="material_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="C"
                                                                                   disabled
                                                                                   data-radio="iradio_square-green"
                                                                            <?php echo ($data->material_res == 'C') ? 'checked' : '' ?>
                                                                            >
                                                                            <label>C</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="material_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="NC"
                                                                                   disabled
                                                                                   data-radio="iradio_square-red"
                                                                            <?php echo ($data->material_res == 'NC') ? 'checked' : '' ?>
                                                                            >
                                                                            <label>NC</label>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อสังเกต</label>
                                                                        <div class="col-sm-2 ">
                                                                            <input type="radio"
                                                                                   name="material_ofsev"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->material_ofsev == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="material_ofsev"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->material_ofsev == 'พบ') ? 'checked' : '' ?> >
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note"
                                                                                   name="material_ofsev_remake"
                                                                                   class="form-control"
                                                                                   style="display: none"
                                                                                   value="{{$data->material_ofsev_remake}}"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อบกพร่อง</label>
                                                                        <div class="col-sm-2 ">
                                                                            <input type="radio"
                                                                                   name="material_defect"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->material_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="material_defect"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->material_defect == 'พบ') ? 'checked' : '' ?>>
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note2"
                                                                                   name="material_defect_remake"
                                                                                   class="form-control check-readonly"
                                                                                   style="display: none"
                                                                                   value="{{$data->material_defect_remake}}"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ไฟล์แนบ', ['class' => 'col-md-2 control-label']) !!}
                                                                                <div class="col-md-8">
                                                                                    <button type="button"
                                                                                            class="btn btn-sm btn-success"
                                                                                            id="add-material" disabled>
                                                                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 ">
                                                            <div class="form-group">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-10 m-t-5">
                                                                    @if($data_file_material!=null)
                                                                        @for($i=0;$i<count($data_material);$i++)
                                                                            @if($data_material[$i]->type == 'material')
                                                                                @if($i==0)
                                                                                    <div class="col-md-4">
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                               name="material_note[]"
                                                                                               value="{{$data_material[$i]->note}}"
                                                                                               disabled>
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-control file_material_old">
                                                                                            @if($data_material[$i]->file !='' && HP::checkFileStorage($attach_path.$data_material[$i]->file))
                                                                                             <a disabled  href="{{ HP::getFileStorage($attach_path.$data_material[$i]->file) }}" target="_blank" >
                                                                                              {{ $data_material[$i]->file }}
                                                                                             </a>
                                                                                           @endif
                                                                                            {{-- <a disabled
                                                                                               href="{{url('/csurv/control_performance/download/'.$data_material[$i]->file)}}">{{$data_material[$i]->file}}</a> --}}
                                                                                        </div>

                                                                                        <div id="new_material_file"></div>
                                                                                        <input type="text"
                                                                                               class="file_material_old"
                                                                                               name="file_material_old[]"
                                                                                               value="{{$data_material[$i]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <a class="btn btn-small btn-danger remove_material"
                                                                                           onclick="return false;"
                                                                                           disabled><span
                                                                                                    class="fa fa-trash"></span></a>
                                                                                    </div>
                                                                                @else
                                                                                    <div>
                                                                                        <div class="col-md-4 m-t-5">
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                                   name="material_note[]"
                                                                                                   value="{{$data_material[$i]->note}}"
                                                                                                   disabled>
                                                                                        </div>
                                                                                        <div class="col-md-5  m-t-5">
                                                                                            <div class="form-control file_old"
                                                                                                 id="file">
                                                                                                 @if($data_material[$i]->file !='' && HP::checkFileStorage($attach_path.$data_material[$i]->file))
                                                                                                   <a disabled  href="{{ HP::getFileStorage($attach_path.$data_material[$i]->file) }}" target="_blank" >
                                                                                                     {{ $data_material[$i]->file }}
                                                                                                   </a>
                                                                                                @endif
                                                                                                {{-- <a disabled
                                                                                                   href="{{url('/csurv/control_performance/download/'.$data_material[$i]->file)}}">{{$data_material[$i]->file}}</a> --}}
                                                                                            </div>

                                                                                            <div id="new_file"></div>
                                                                                            <input type="text"
                                                                                                   id="file_old0"
                                                                                                   name="file_material_old[]"
                                                                                                   value="{{$data_material[$i]->file}}"
                                                                                                   hidden>
                                                                                        </div>
                                                                                        <div class="col-md-2 m-t-5">
                                                                                            <a class="btn btn-small btn-danger remove_material"
                                                                                               onclick="return false;"
                                                                                               disabled><span
                                                                                                        class="fa fa-trash"></span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endfor
                                                                    @else
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="material_note[]" disabled>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <input type="file" name="material_file0"
                                                                                       class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div id="material_box">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ความคิดเห็นเพิ่มเติม', ['class' => 'col-md-4 control-label']) !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-2"></div>
                                                                            <div class="col-md-10">
                                                                                <div class="col-md-9">
                                                                        <textarea class="form-control"
                                                                                  name="material_remark"
                                                                                  disabled
                                                                                  rows="4">{{$data->material_remark}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <div class="p-40">
                                                <div class="row form-group">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <strong class="col-md-4">2. การควบคุมระหว่างผลิต</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-20">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ผลการประเมิน</label>
                                                                        <div class="col-sm-2 ">
                                                                            <input type="radio"
                                                                                   name="control_between_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="C"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->control_between_res == 'C') ? 'checked' : '' ?>>
                                                                            <label>C</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_between_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="NC"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->control_between_res == 'NC') ? 'checked' : '' ?>>
                                                                            <label>NC</label>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อสังเกต</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_between_ofsev"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->control_between_ofsev == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_between_ofsev"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->control_between_ofsev == 'พบ') ? 'checked' : '' ?>>
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note3"
                                                                                   name="control_between_ofsev_remake"
                                                                                   class="form-control check-readonly"
                                                                                   style="display: none"
                                                                                   value="{{$data->control_between_ofsev_remake}}"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อบกพร่อง</label>
                                                                        <div class="col-sm-2 ">
                                                                            <input type="radio"
                                                                                   name="control_between_defect"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->control_between_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_between_defect"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->control_between_defect == 'พบ') ? 'checked' : '' ?>>
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note4"
                                                                                   name="control_between_defect_remake"
                                                                                   class="form-control check-readonly"
                                                                                   style="display: none"
                                                                                   value="{{$data->control_between_defect_remake}}"
                                                                                   disabled>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 ">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ไฟล์แนบ', ['class' => 'col-md-2 control-label']) !!}
                                                                                <div class="col-md-8">
                                                                                    <button type="button"
                                                                                            class="btn btn-sm btn-success"
                                                                                            id="add-control_between"
                                                                                            disabled>
                                                                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 ">
                                                            <div class="form-group">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-10 m-t-5">
                                                                    @if($data_file_control_between!=null)
                                                                        @for($i=0;$i<count($data_control_between);$i++)
                                                                            @if($data_control_between[$i]->type == 'control_between')
                                                                                @if($i==0)
                                                                                    <div class="col-md-4">
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                               name="control_between_note[]"
                                                                                               value="{{$data_control_between[$i]->note}}"
                                                                                               disabled>
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-control file_control_between_old">
                                                                                             @if($data_control_between[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_between[$i]->file))
                                                                                               <a disabled  href="{{ HP::getFileStorage($attach_path.$data_control_between[$i]->file) }}" target="_blank" >
                                                                                                  {{ $data_control_between[$i]->file }}
                                                                                               </a>
                                                                                             @endif
                                                                                            {{-- <a disabled
                                                                                               href="{{url('/csurv/control_performance/download/'.$data_control_between[$i]->file)}}">{{$data_control_between[$i]->file}}</a> --}}
                                                                                        </div>

                                                                                        <div id="new_control_between_file"></div>
                                                                                        <input type="text"
                                                                                               class="file_control_between_old"
                                                                                               name="file_control_between_old[]"
                                                                                               value="{{$data_control_between[$i]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <a class="btn btn-small btn-danger remove_control_between"
                                                                                           onclick="return false;"
                                                                                           disabled><span
                                                                                                    class="fa fa-trash"></span></a>
                                                                                    </div>
                                                                                @else
                                                                                    <div>
                                                                                        <div class="col-md-4 m-t-5">
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                                   name="control_between_note[]"
                                                                                                   value="{{$data_control_between[$i]->note}}"
                                                                                                   disabled>
                                                                                        </div>
                                                                                        <div class="col-md-5  m-t-5">
                                                                                            <div class="form-control file_old"
                                                                                                 id="file">
                                                                                                @if($data_control_between[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_between[$i]->file))
                                                                                                  <a disabled  href="{{ HP::getFileStorage($attach_path.$data_control_between[$i]->file) }}" target="_blank" >
                                                                                                    {{ $data_control_between[$i]->file }}
                                                                                                 </a>
                                                                                               @endif
                                                                                                {{-- <a disabled
                                                                                                   href="{{url('/csurv/control_performance/download/'.$data_control_between[$i]->file)}}">{{$data_control_between[$i]->file}}</a> --}}
                                                                                            </div>

                                                                                            <div id="new_file"></div>
                                                                                            <input type="text"
                                                                                                   id="file_old0"
                                                                                                   name="file_control_between_old[]"
                                                                                                   value="{{$data_control_between[$i]->file}}"
                                                                                                   hidden>
                                                                                        </div>
                                                                                        <div class="col-md-2 m-t-5">
                                                                                            <a class="btn btn-small btn-danger remove_control_between"
                                                                                               onclick="return false;"
                                                                                               disabled><span
                                                                                                        class="fa fa-trash"></span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endfor
                                                                    @else
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="control_between_note[]"
                                                                                       disabled>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <input type="file"
                                                                                       name="control_between_file0"
                                                                                       class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div id="control_between_box">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ความคิดเห็นเพิ่มเติม', ['class' => 'col-md-4 control-label']) !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-2"></div>
                                                                            <div class="col-md-10">
                                                                                <div class="col-md-9">
                                                                        <textarea disabled class="form-control"
                                                                                  name="control_between_remark"
                                                                                  rows="4">{{$data->control_between_remark}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <strong class="col-md-4">3. การควบคุมผลิตภัณฑ์สำเร็จรูป</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-20">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ผลการประเมิน</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_finish_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="C"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->control_finish_res == 'C') ? 'checked' : '' ?>
                                                                            >
                                                                            <label>C</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_finish_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="NC"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->control_finish_res == 'NC') ? 'checked' : '' ?>
                                                                            >
                                                                            <label>NC</label>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อสังเกต</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_finish_ofsev"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->control_finish_ofsev == 'ไม่พบ') ? 'checked' : '' ?>
                                                                            >
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_finish_ofsev"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->control_finish_ofsev == 'พบ') ? 'checked' : '' ?>
                                                                            >
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note5"
                                                                                   name="control_finish_ofsev_remake"
                                                                                   class="form-control"
                                                                                   style="display: none"
                                                                                   value="{{$data->control_finish_ofsev_remake}}"
                                                                                   disabled>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อบกพร่อง</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_finish_defect"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->control_finish_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="control_finish_defect"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->control_finish_defect == 'พบ') ? 'checked' : '' ?>>
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note6"
                                                                                   name="control_finish_defect_remake"
                                                                                   class="form-control check-readonly"
                                                                                   style="display: none"
                                                                                   value="{{$data->control_finish_defect_remake}}"
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ไฟล์แนบ', ['class' => 'col-md-2 control-label']) !!}
                                                                                <div class="col-md-8">
                                                                                    <button type="button"
                                                                                            class="btn btn-sm btn-success"
                                                                                            id="add-control_finish"
                                                                                            disabled>
                                                                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-10 m-t-5">
                                                                    @if($data_file_control_finish!=null)
                                                                        @for($i=0;$i<count($data_control_finish);$i++)
                                                                            @if($data_control_finish[$i]->type == 'control_finish')
                                                                                @if($i==0)
                                                                                    <div class="col-md-4">
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                               name="control_finish_note[]"
                                                                                               value="{{$data_control_finish[$i]->note}}"
                                                                                               disabled>
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-control file_control_finish_old">
                                                                                            @if($data_control_finish[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_finish[$i]->file))
                                                                                                <a disabled  href="{{ HP::getFileStorage($attach_path.$data_control_finish[$i]->file) }}" target="_blank" >
                                                                                                {{ $data_control_finish[$i]->file }}
                                                                                                </a>
                                                                                            @endif
                                                                                            {{-- <a disabled
                                                                                               href="{{url('/csurv/control_performance/download/'.$data_control_finish[$i]->file)}}">{{$data_control_finish[$i]->file}}</a> --}}
                                                                                        </div>

                                                                                        <div id="new_control_finish_file"></div>
                                                                                        <input type="text"
                                                                                               class="file_control_finish_old"
                                                                                               name="file_control_finish_old[]"
                                                                                               value="{{$data_control_finish[$i]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <a class="btn btn-small btn-danger remove_control_finish"
                                                                                           onclick="return false;"
                                                                                           disabled><span
                                                                                                    class="fa fa-trash"></span></a>
                                                                                    </div>
                                                                                @else
                                                                                    <div>
                                                                                        <div class="col-md-4 m-t-5">
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                                   name="control_finish_note[]"
                                                                                                   disabled
                                                                                                   value="{{$data_control_finish[$i]->note}}">
                                                                                        </div>
                                                                                        <div class="col-md-5  m-t-5">
                                                                                            <div class="form-control file_old"
                                                                                                 id="file">
                                                                                                @if($data_control_finish[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_finish[$i]->file))
                                                                                                 <a disabled  href="{{ HP::getFileStorage($attach_path.$data_control_finish[$i]->file) }}" target="_blank" >
                                                                                                    {{ $data_control_finish[$i]->file }}
                                                                                                 </a>
                                                                                               @endif
                                                                                                {{-- <a disabled
                                                                                                   href="{{url('/csurv/control_performance/download/'.$data_control_finish[$i]->file)}}">{{$data_control_finish[$i]->file}}</a> --}}
                                                                                            </div>

                                                                                            <div id="new_file"></div>
                                                                                            <input type="text"
                                                                                                   id="file_old0"
                                                                                                   name="file_control_finish_old[]"
                                                                                                   value="{{$data_control_finish[$i]->file}}"
                                                                                                   hidden>
                                                                                        </div>
                                                                                        <div class="col-md-2 m-t-5">
                                                                                            <a class="btn btn-small btn-danger remove_control_finish"
                                                                                               onclick="return false;"
                                                                                               disabled><span
                                                                                                        class="fa fa-trash"></span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endfor
                                                                    @else
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="control_finish_note[]"
                                                                                       disabled>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <input type="file"
                                                                                       name="control_finish_file0"
                                                                                       class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div id="control_finish_box">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ความคิดเห็นเพิ่มเติม', ['class' => 'col-md-4 control-label']) !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group ">
                                                                            <div class="col-md-2"></div>
                                                                            <div class="col-md-10">
                                                                                <div class="col-md-9">
                                                                        <textarea class="form-control"
                                                                                  name="control_finish_remark" disabled
                                                                                  rows="4">{{$data->control_finish_remark}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <div class="p-40">
                                                <div class="row form-group">
                                                    <div class="col-md-12">

                                                        <div class="row">
                                                            <div class="form-group ">
                                                                <div class="form-group">
                                                                    <div class="col-md-2"></div>
                                                                    <strong class="col-md-6">4. การควบคุมผลิตภัณฑ์ที่ไม่เป็นไปตามเกณฑ์ที่กำหนด</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div>
                                                                <div class="form-group m-b-20">
                                                                    <div class="col-md-2"></div>
                                                                    <label class="col-md-2 p-l-30">ผลการประเมิน</label>
                                                                    <div class="col-sm-2">
                                                                        <input type="radio"
                                                                               name="control_standard_res"
                                                                               class="col-sm-2 check check-readonly"
                                                                               value="C"
                                                                               data-radio="iradio_square-green"
                                                                               disabled
                                                                        <?php echo ($data->control_standard_res == 'C') ? 'checked' : '' ?>
                                                                        >
                                                                        <label>C</label>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="radio"
                                                                               name="control_standard_res"
                                                                               class="col-sm-2 check check-readonly"
                                                                               value="NC"
                                                                               data-radio="iradio_square-red"
                                                                               disabled
                                                                        <?php echo ($data->control_standard_res == 'NC') ? 'checked' : '' ?>
                                                                        >
                                                                        <label>NC</label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div>
                                                                <div class="form-group m-b-10">
                                                                    <div class="col-md-2"></div>
                                                                    <label class="col-md-2 p-l-30">ข้อสังเกต</label>
                                                                    <div class="col-sm-2">
                                                                        <input type="radio"
                                                                               name="control_standard_ofsev"
                                                                               value="ไม่พบ"
                                                                               class="col-sm-2 check check-readonly"
                                                                               data-radio="iradio_square-green"
                                                                               disabled
                                                                        <?php echo ($data->control_standard_ofsev == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                        <label>ไม่พบ</label>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="radio"
                                                                               name="control_standard_ofsev"
                                                                               value="พบ"
                                                                               class="col-sm-2 check check-readonly"
                                                                               data-radio="iradio_square-red"
                                                                               disabled
                                                                        <?php echo ($data->control_standard_ofsev == 'พบ') ? 'checked' : '' ?>>
                                                                        <label>พบ (โปรดระบุ)</label>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text"
                                                                               id="note7"
                                                                               name="control_standard_ofsev_remake"
                                                                               class="form-control"
                                                                               style="display: none"
                                                                               value="{{$data->control_standard_ofsev_remake}}"
                                                                               disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div>
                                                                <div class="form-group m-b-10">
                                                                    <div class="col-md-2"></div>
                                                                    <label class="col-md-2 p-l-30">ข้อบกพร่อง</label>
                                                                    <div class="col-sm-2">
                                                                        <input type="radio"
                                                                               name="control_standard_defect"
                                                                               value="ไม่พบ"
                                                                               class="col-sm-2 check check-readonly"
                                                                               data-radio="iradio_square-green"
                                                                               disabled
                                                                        <?php echo ($data->control_standard_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                        <label>ไม่พบ</label>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="radio"
                                                                               name="control_standard_defect"
                                                                               value="พบ"
                                                                               class="col-sm-2 check check-readonly"
                                                                               data-radio="iradio_square-red"
                                                                               disabled
                                                                        <?php echo ($data->control_standard_defect == 'พบ') ? 'checked' : '' ?>>
                                                                        <label>พบ (โปรดระบุ)</label>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text"
                                                                               id="note8"
                                                                               name="control_standard_defect_remake"
                                                                               class="form-control"
                                                                               style="display: none"
                                                                               value="{{$data->control_standard_defect_remake}}"
                                                                               disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="form-group">
                                                                            {!! Form::label('attach_other', 'ไฟล์แนบ', ['class' => 'col-md-2 control-label']) !!}
                                                                            <div class="col-md-8">
                                                                                <button type="button"
                                                                                        class="btn btn-sm btn-success"
                                                                                        id="add-control_standard"
                                                                                        disabled>
                                                                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-md-10 m-t-5">
                                                                @if($data_file_control_standard!=null)
                                                                    @for($i=0;$i<count($data_control_standard);$i++)
                                                                        @if($data_control_standard[$i]->type == 'control_standard')
                                                                            @if($i==0)
                                                                                <div class="col-md-4">
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                           name="control_standard_note[]"
                                                                                           disabled
                                                                                           value="{{$data_control_standard[$i]->note}}">
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <div class="form-control file_control_standard_old">
                                                                                        @if($data_control_standard[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_standard[$i]->file))
                                                                                            <a disabled  href="{{ HP::getFileStorage($attach_path.$data_control_standard[$i]->file) }}" target="_blank" >
                                                                                              {{ $data_control_standard[$i]->file }}
                                                                                            </a>
                                                                                        @endif
                                                                                        {{-- <a disabled
                                                                                           href="{{url('/csurv/control_performance/download/'.$data_control_standard[$i]->file)}}">{{$data_control_standard[$i]->file}}</a> --}}
                                                                                    </div>

                                                                                    <div id="new_control_standard_file"></div>
                                                                                    <input type="text"
                                                                                           class="file_control_standard_old"
                                                                                           name="file_control_standard_old[]"
                                                                                           value="{{$data_control_standard[$i]->file}}"
                                                                                           disabled
                                                                                           hidden>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <a class="btn btn-small btn-danger remove_control_standard"
                                                                                       onclick="return false;"
                                                                                       disabled><span
                                                                                                class="fa fa-trash"></span></a>
                                                                                </div>
                                                                            @else
                                                                                <div>
                                                                                    <div class="col-md-4 m-t-5">
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                               disabled
                                                                                               name="control_standard_note[]"
                                                                                               value="{{$data_control_standard[$i]->note}}">
                                                                                    </div>
                                                                                    <div class="col-md-5  m-t-5">
                                                                                        <div class="form-control file_old"
                                                                                             id="file">
                                                                                            @if($data_control_standard[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_standard[$i]->file))
                                                                                                <a disabled  href="{{ HP::getFileStorage($attach_path.$data_control_standard[$i]->file) }}" target="_blank" >
                                                                                                {{ $data_control_standard[$i]->file }}
                                                                                                </a>
                                                                                            @endif
                                                                                            {{-- <a disabled
                                                                                               href="{{url('/csurv/control_performance/download/'.$data_control_standard[$i]->file)}}">{{$data_control_standard[$i]->file}}</a> --}}
                                                                                        </div>

                                                                                        <div id="new_file"></div>
                                                                                        <input type="text"
                                                                                               id="file_old0"
                                                                                               name="file_control_standard_old[]"
                                                                                               disabled
                                                                                               value="{{$data_control_standard[$i]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <div class="col-md-2 m-t-5">
                                                                                        <a class="btn btn-small btn-danger remove_control_standard"
                                                                                           onclick="return false;"
                                                                                           disabled><span
                                                                                                    class="fa fa-trash"></span></a>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endfor
                                                                @else
                                                                    <div class="form-group">
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control"
                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                   name="control_standard_note[]"
                                                                                   disabled>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <input type="file"
                                                                                   name="control_standard_file0"
                                                                                   class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div id="control_standard_box">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="form-group">
                                                                            {!! Form::label('attach_other', 'ความคิดเห็นเพิ่มเติม', ['class' => 'col-md-4 control-label']) !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="col-md-9">
                                                                        <textarea class="form-control" disabled
                                                                                  name="control_standard_remark"
                                                                                  rows="4">{{$data->control_standard_remark}}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-md-12">

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <strong class="col-md-6">5. การควบคุมเครื่องตรวจ เครื่องวัด และเครื่องทดสอบ</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-20">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ผลการประเมิน</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="test_machine_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="C"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->test_machine_res == 'C') ? 'checked' : '' ?>>
                                                                            <label>C</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="test_machine_res"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   value="NC"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->test_machine_res == 'NC') ? 'checked' : '' ?>>
                                                                            <label>NC</label>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อสังเกต</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="test_machine_ofsev"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-green"
                                                                                   disabled
                                                                            <?php echo ($data->test_machine_ofsev == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="test_machine_ofsev"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   data-radio="iradio_square-red"
                                                                                   disabled
                                                                            <?php echo ($data->test_machine_ofsev == 'พบ') ? 'checked' : '' ?>>
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note9"
                                                                                   name="test_machine_ofsev_remake"
                                                                                   class="form-control check-readonly"
                                                                                   style="display: none"
                                                                                   disabled
                                                                                   value="{{$data->test_machine_ofsev_remake}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div>
                                                                    <div class="form-group m-b-10">
                                                                        <div class="col-md-2"></div>
                                                                        <label class="col-md-2 p-l-30">ข้อบกพร่อง</label>
                                                                        <div class="col-sm-2 ">
                                                                            <input type="radio"
                                                                                   name="test_machine_defect"
                                                                                   value="ไม่พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   disabled
                                                                                   data-radio="iradio_square-green"
                                                                            <?php echo ($data->test_machine_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                            <label>ไม่พบ</label>
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <input type="radio"
                                                                                   name="test_machine_defect"
                                                                                   value="พบ"
                                                                                   class="col-sm-2 check check-readonly"
                                                                                   disabled
                                                                                   data-radio="iradio_square-red"
                                                                            <?php echo ($data->test_machine_defect == 'พบ') ? 'checked' : '' ?>>
                                                                            <label>พบ (โปรดระบุ)</label>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   id="note10"
                                                                                   name="test_machine_defect_remake"
                                                                                   class="form-control"
                                                                                   style="display: none"
                                                                                   disabled
                                                                                   value="{{$data->test_machine_defect_remake}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ไฟล์แนบ', ['class' => 'col-md-2 control-label']) !!}
                                                                                <div class="col-md-8">
                                                                                    <button type="button"
                                                                                            class="btn btn-sm btn-success"
                                                                                            id="add-test_machine"
                                                                                            disabled>
                                                                                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-10 m-t-5">
                                                                    @if($data_file_test_machine!=null)
                                                                        @for($i=0;$i<count($data_test_machine);$i++)
                                                                            @if($data_test_machine[$i]->type == 'test_machine')
                                                                                @if($i==0)
                                                                                    <div class="col-md-4">
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                               name="test_machine_note[]"
                                                                                               disabled
                                                                                               value="{{$data_test_machine[$i]->note}}">
                                                                                    </div>
                                                                                    <div class="col-md-5">
                                                                                        <div class="form-control file_test_machine_old">
                                                                                                @if($data_test_machine[$i]->file !='' && HP::checkFileStorage($attach_path.$data_test_machine[$i]->file))
                                                                                                    <a disabled  href="{{ HP::getFileStorage($attach_path.$data_test_machine[$i]->file) }}" target="_blank" >
                                                                                                    {{ $data_test_machine[$i]->file }}
                                                                                                    </a>
                                                                                                @endif
                                                                                            {{-- <a disabled
                                                                                               href="{{url('/csurv/control_performance/download/'.$data_test_machine[$i]->file)}}">{{$data_test_machine[$i]->file}}</a> --}}
                                                                                        </div>

                                                                                        <div id="new_test_machine_file"></div>
                                                                                        <input type="text"
                                                                                               class="file_test_machine_old"
                                                                                               name="file_test_machine_old[]"
                                                                                               disabled
                                                                                               value="{{$data_test_machine[$i]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <a class="btn btn-small btn-danger remove_test_machine"
                                                                                           onclick="return false;"
                                                                                           disabled><span
                                                                                                    class="fa fa-trash"></span></a>
                                                                                    </div>
                                                                                @else
                                                                                    <div>
                                                                                        <div class="col-md-4 m-t-5">
                                                                                            <input type="text"
                                                                                                   class="form-control"
                                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                                   name="test_machine_note[]"
                                                                                                   disabled
                                                                                                   value="{{$data_test_machine[$i]->note}}">
                                                                                        </div>
                                                                                        <div class="col-md-5  m-t-5">
                                                                                            <div class="form-control file_old"
                                                                                                 id="file">
                                                                                                 @if($data_test_machine[$i]->file !='' && HP::checkFileStorage($attach_path.$data_test_machine[$i]->file))
                                                                                                    <a disabled  href="{{ HP::getFileStorage($attach_path.$data_test_machine[$i]->file) }}" target="_blank" >
                                                                                                    {{ $data_test_machine[$i]->file }}
                                                                                                    </a>
                                                                                                @endif
                                                                                                {{-- <a disabled
                                                                                                   href="{{url('/csurv/control_performance/download/'.$data_test_machine[$i]->file)}}">{{$data_test_machine[$i]->file}}</a> --}}
                                                                                            </div>

                                                                                            <div id="new_file"></div>
                                                                                            <input type="text"
                                                                                                   id="file_old0"
                                                                                                   name="file_test_machine_old[]"
                                                                                                   value="{{$data_test_machine[$i]->file}}"
                                                                                                   hidden>
                                                                                        </div>
                                                                                        <div class="col-md-2 m-t-5">
                                                                                            <a class="btn btn-small btn-danger remove_test_machine"
                                                                                               onclick="return false;"
                                                                                               disabled><span
                                                                                                        class="fa fa-trash"></span></a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endfor
                                                                    @else
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="test_machine_note[]"
                                                                                       disabled>
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <input type="file"
                                                                                       name="test_machine_file0"
                                                                                       class="form-control" disabled>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div id="test_machine_box">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <div class="col-md-2"></div>
                                                                        <div class="col-md-10">
                                                                            <div class="form-group">
                                                                                {!! Form::label('attach_other', 'ความคิดเห็นเพิ่มเติม', ['class' => 'col-md-4 control-label']) !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <div class="col-md-2"></div>
                                                                            <div class="col-md-10">
                                                                                <div class="col-md-9">
                                                                        <textarea class="form-control" disabled
                                                                                  name="test_machine_remark"
                                                                                  rows="4">{{$data->test_machine_remark}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12" id="">
                                <fieldset style="border:#cccccc solid 0.1em" class="p-40">
                                    <legend><h3>ประเมินผลการตรวจ</h3></legend>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-4">
                                                        <input type="radio"
                                                               name="conclude_result"
                                                               value="1"
                                                               class="col-sm-1 check check-readonly"
                                                               data-radio="iradio_square-green"
                                                               disabled
                                                        <?php echo ($data->conclude_result == '1') ? 'checked' : '' ?>
                                                        >
                                                        <label>เป็นไปตามข้อกำหนด</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-6">
                                                        <input type="radio"
                                                               name="conclude_result"
                                                               value="2"
                                                               class="col-sm-1 check check-readonly"
                                                               data-radio="iradio_square-green"
                                                               disabled
                                                        <?php echo ($data->conclude_result == '2') ? 'checked' : '' ?>
                                                        >
                                                        <label>แก้ไขให้เป็นไปตามข้อกำหนด
                                                            (โปรดระบุสิ่งที่ต้องแก้ไข)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display: none" id="show_remark">
                                                <div class="row">
                                                    <div class="form-group m-b-10 col-md-5">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-sm-4">
                                                            <label>สิ่งที่ต้องแก้ไข</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group m-b-10 col-md-5">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-sm-4">
                                                            <textarea name="remake" id="add_remake" cols="100"
                                                                      rows="5" disabled>{{$data->remake}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-6">
                                                        <input type="radio"
                                                               name="conclude_result"
                                                               value="3"
                                                               class="col-sm-1 check check-readonly"
                                                               data-radio="iradio_square-green"
                                                               disabled
                                                        <?php echo ($data->conclude_result == '3') ? 'checked' : '' ?>
                                                        >
                                                        <label>ไม่เป็นไปตามข้อกำหนด ส่งเรื่องให้ กม. ดำเนินการ</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="show_remark">
                                                <div class="row">
                                                    <div class="form-group m-b-10 col-md-6">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-sm-4 ">
                                                            <label>ไฟล์แนบเพิ่มเติม</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-group">
                                                        @if($data_file_check==null)
                                                            <strong class="control-label col-md-2 text-right"> ไฟล์แนบ
                                                                (ถ้ามี) : </strong>
                                                            <div class="col-md-4 sub_file">
                                                                <input type="file" id="file" name="file0"
                                                                       class="form-control">
                                                                <input type="text" name="num_row_file[]" hidden>
                                                            </div>
                                                            <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                    name="add"
                                                                    disabled
                                                                    onClick="return false;">
                                                                <span class="btn-label"><i
                                                                            class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                            </button>
                                                    </div>
                                                    <div class="col-md-5 form-group">
                                                        <div style="color: red;"
                                                             class="control-label col-md-10 text-right">รองรับไฟล์ .pdf
                                                            ขนาดไม่เกิน 10Mb.
                                                        </div>
                                                    </div>
                                                    @else
                                                        @for($j = 0 ; $j < count($data_file) ; $j++)
                                                            @if($j==0)
                                                                <strong class="control-label col-md-2 text-right">
                                                                    ไฟล์แนบ (ถ้ามี)
                                                                    : </strong>
                                                                <div class="col-md-4">
                                                                    <div class="form-control file_old" id="file">
                                                                    @if($data_file[$j]->file !='' && HP::checkFileStorage($attach_path.$data_file[$j]->file))
                                                                        <a disabled  href="{{ HP::getFileStorage($attach_path.$data_file[$j]->file) }}" target="_blank" >
                                                                        {{ $data_file[$j]->file }}
                                                                        </a>
                                                                    @endif
                                                                        {{-- <a disabled
                                                                           href="{{url('/csurv/control_performance/download/'.$data_file[$j]->file)}}">{{$data_file[$j]->file}}</a> --}}
                                                                    </div>

                                                                    <div id="new_file"></div>
                                                                    <input type="text" id="file_old0" name="file_old[]"
                                                                           value="{{$data_file[$j]->file}}" hidden>
                                                                </div>
                                                                <a class="btn btn-small btn-primary "
                                                                   onclick="return false;" disabled><span
                                                                            class="fa fa-trash"></span></a>
                                                                <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                        onClick="return false;" disabled>
                                                                    <span class="btn-label"><i
                                                                                class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                </button>
                                                </div>
                                                @else
                                                    <div class="form-group">
                                                        <span class="running-no"></span>
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-4">
                                                            <div class="form-control" id="file{{$j}}">
                                                             @if($data_file[$j]->file !='' && HP::checkFileStorage($attach_path.$data_file[$j]->file))
                                                                <a disabled  href="{{ HP::getFileStorage($attach_path.$data_file[$j]->file) }}" target="_blank" >
                                                                {{ $data_file[$j]->file }}
                                                                </a>
                                                            @endif
                                                                {{-- <a disabled
                                                                   href="{{url('/csurv/control_performance/download/'.$data_file[$j]->file)}}">{{$data_file[$j]->file}}</a> --}}
                                                            </div>
                                                            <input disabled type="text" id="file_old0" name="file_old[]"
                                                                   value="{{$data_file[$j]->file}}" hidden>
                                                        </div>
                                                        <a class="btn btn-small btn-danger"
                                                           onclick="return false;" disabled><span
                                                                    class="fa fa-trash"></span></a>
                                                    </div>
                                                @endif
                                                @endfor
                                                @endif
                                                <div>
                                                    <div id="file_upload"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-10 m-b-10">
                                                <div class="row">
                                                    <div class="form-group">

                                                        <label class="col-md-3 text-right">ผู้ตรวจประเมิน :</label>
                                                        <div class="col-sm-6">
                                                            <input type="text"
                                                                   name=""
                                                                   class="form-control"
                                                                   value="{{$data->check_officer}}"
                                                                   disabled>
                                                            <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                   name="check_officer" hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10 m-b-40">
                                                <div class="row">
                                                    <div class="form-group">

                                                        <label class="col-md-3 text-right">วันที่ตรวจประเมิน :</label>
                                                        <div class="col-sm-6">
                                                            <input type="text"
                                                                   name=""
                                                                   class="form-control"
                                                                   value="{{$data->date_now}}"
                                                                   disabled>
                                                            <input value="{{date("m/d/Y")}}" name="date_now" hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        @if(isset($data->status_history) && $data->status_history != null)
                        @php
                         $status_history =  json_decode($data->status_history);
                        $status =   ['0' => 'ฉบับร่าง',  '1' => 'อยู่ระหว่าง ผก.รับรอง','2' => 'ผก.รับรองแล้ว', '3' => 'อยู่ระหว่าง ผอ.รับรอง','4' => 'ผอ.รับรองแล้ว',  '5' => 'ปรับปรุงแก้ไข']; 
                        $person =   ['0' => 'ผู้บันทึกร่าง','1' => 'ผู้ส่งรายงาน','2' => 'ผู้ตรวจประเมิน','5' => 'ผู้ตรวจประเมิน']; 
                         $User = App\User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS titels"),'runrecno AS id') ->pluck('titels','id');
                        @endphp
                        <div class="row form-group">
                            <div class="col-md-12">
                                <fieldset style="border:#cccccc solid 0.1em" class="p-40">
                                    <legend><h3>ประวัติประเมินผลการตรวจ</h3></legend> 
                                    @foreach($status_history as $key => $item)
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="white-box">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="#" class="control-label col-md-1 text-right">{{($key+1)}}</label>
                                                        <div class="control-label col-md-11">
                                                            <label for="#" class="control-label col-md-2">สถานะ</label>
                                                            <div class="control-label col-md-10">
                                                                @if(array_key_exists($item->status,$status))
                                                                <strong>{{$status[$item->status]}}</strong> 
                                                                @else 
                                                                @endif
                                                            </div>
                                                        </div>
                                                       @if($item->status_res != null)
                                                        <label for="#" class="control-label col-md-1"></label>
                                                        <div class="control-label col-md-11">
                                                            <label for="#" class="control-label col-md-2">
                                                                   ประเมินผลการตรวจ
                                                            </label>
                                                            <div class="control-label col-md-10">
                                                                <strong>
                                                                        @if($item->status_res == 1)
                                                                           เห็นชอบและโปรดดำเนินการต่อไป
                                                                        @else 
                                                                            {{ 'อื่นๆ'.@$item->status_res_remake}}
                                                                        @endif
                                                                </strong> 
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <label for="#" class="control-label col-md-1"></label>
                                                        <div class="control-label col-md-11">
                                                            <label for="#" class="control-label col-md-2">
                                                                @if(array_key_exists($item->status,$person))
                                                                  {{$person[$item->status]}}
                                                                @else 
                                                                @endif
                                                            </label>
                                                            <div class="control-label col-md-10">
                                                                <strong>
                                                                        {{ !empty($User[$item->created_by]) ? $User[$item->created_by] : null   }}
                                                                </strong> 
                                                            </div>
                                                        </div>
                                                        <label for="#" class="control-label col-md-1"></label>
                                                        <div class="control-label col-md-11">
                                                            <label for="#" class="control-label col-md-2">
                                                                    วันที่
                                                            </label>
                                                            <div class="control-label col-md-10">
                                                                <strong>
                                                                        {{ !empty($item->date) ? HP::DateThai($item->date)  : null   }}
                                                                </strong> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </fieldset>
                            </div>
                        </div>
                        @else
                        @endif
                        @if($data->status == '1')  <!--อยู่ระหว่าง ผก.รับรอง -->
                            <div class="row form-group">
                                <div class="col-md-12" id="">
                                    <fieldset style="border:#cccccc solid 0.1em" class="p-40">
                                        <legend><h3>สำหรับ ผก. รับรอง</h3></legend>
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="row">
                                                    <div class="form-group m-b-10">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-sm-4">
                                                            <input type="radio"
                                                                   name="status_res"
                                                                   value="1"
                                                                   class="col-sm-1 check not_remark3"
                                                                   data-radio="iradio_square-green"
                                                                   checked>
                                                            <label>เห็นชอบและโปรดดำเนินการต่อไป</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group m-b-10">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-sm-4">
                                                            <input type="radio"
                                                                   name="status_res"
                                                                   value="2"
                                                                   class="col-sm-1 check"
                                                                   id="show_remark3"
                                                                   data-radio="iradio_square-green"
                                                            >
                                                            <label>อื่นๆ</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="display: none" id="show_remark4">
                                                    <div class="row">
                                                        <div class="form-group m-b-10 col-md-5">
                                                            <div class="col-md-3"></div>
                                                            <div class="col-sm-4">
                                                                <textarea name="status_res_remake" id="add_remake3"
                                                                          cols="100" rows="5"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-10 m-b-10">
                                                    <div class="row">
                                                        <div class="form-group">

                                                            <label class="col-md-3 text-right">ผู้ตรวจประเมิน :</label>
                                                            <div class="col-sm-6">
                                                                <input type="text"
                                                                       name=""
                                                                       class="form-control"
                                                                       value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                       disabled>
                                                                <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                       name="status_check" hidden>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-10 m-b-40">
                                                    <div class="row">
                                                        <div class="form-group">

                                                            <label class="col-md-3 text-right">วันที่ตรวจประเมิน
                                                                :</label>
                                                            <div class="col-sm-6">
                                                                <input type="text"
                                                                       name=""
                                                                       class="form-control"
                                                                       value="{{date("m/d/Y")}}"
                                                                       disabled>
                                                                <input value="{{date("m/d/Y")}}" name="date_now" hidden>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="status_btn"></div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="form-group text-center">
                                                            <button class="btn bg-primary btn-lg waves-effect waves-light m-r-30"
                                                                    type="submit"
                                                                    onclick="add_status_btn('1')">
                                                                <i class="fa fa-send"></i>
                                                                <b>ส่ง</b>
                                                            </button>
                                                            <a class="btn btn-default btn-lg waves-effect waves-light"
                                                               href="{{ url('/csurv/control_performance') }}">
                                                               <i class="fa fa-close"></i>
                                                                <b>ยกเลิก</b>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        @endif

                        {{-- @if($data->status == 2) --}}
                        <a  href="{{ url("$previousUrl") }}">
                            <div class="alert alert-dark text-center" role="alert">
                                <i class="fa fa-close"></i>
                                <b>กลับ</b>
                            </div>
                        </a> 
                        {{-- @endif --}}
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>


    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
         jQuery(document).ready(function() {
            $('.check-readonly').prop('disabled', true);//checkbox ความคิดเห็น
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css('margin-top', '8px');//checkbox ความคิดเห็น
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.onload = function () {
            @if ($data->status_res == 'อื่นๆ')
            document.getElementById('show_remark2').style.display = 'block';
            $("#show_remark2").prop('required', true);
            @endif
            @if($data->material_ofsev==='พบ')
            document.getElementById('note').style.display = 'block';
            $("#note").prop('required', true);
            @endif
            @if($data->material_defect==='พบ')
            document.getElementById('note2').style.display = 'block';
            $("#note2").prop('required', true);
            @endif
            @if($data->control_between_ofsev==='พบ')
            document.getElementById('note3').style.display = 'block';
            $("#note3").prop('required', true);
            @endif
            @if($data->control_between_defect==='พบ')
            document.getElementById('note4').style.display = 'block';
            $("#note4").prop('required', true);
            @endif
            @if($data->control_finish_ofsev==='พบ')
            document.getElementById('note5').style.display = 'block';
            $("#note5").prop('required', true);
            @endif
            @if($data->control_finish_defect==='พบ')
            document.getElementById('note6').style.display = 'block';
            $("#note6").prop('required', true);
            @endif
            @if($data->control_standard_ofsev==='พบ')
            document.getElementById('note7').style.display = 'block';
            $("#note7").prop('required', true);
            @endif
            @if($data->control_standard_defect==='พบ')
            document.getElementById('note8').style.display = 'block';
            $("#note8").prop('required', false);
            @endif
            @if($data->test_machine_ofsev==='พบ')
            document.getElementById('note9').style.display = 'block';
            $("#note9").prop('required', true);
            @endif
            @if($data->test_machine_defect==='พบ')
            document.getElementById('note10').style.display = 'block';
            $("#note10").prop('required', true);
            @endif
            @if($data->conclude_result==='แก้ไขให้เป็นไปตามข้อกำหนด')
            document.getElementById('show_remark').style.display = 'block';
            $("#add_remake").prop('required', true);
            @endif
        }

        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="check_status" value="' + status + '" hidden>');
        }

        $('#datepicker-time').datepicker({
            autoclose: true
        });

        function show_map() {
            $('#modal-default').modal('show')
            initialize()
        }

        function initialize() {
            var myLatlng = new google.maps.LatLng(13.724717, 100.633072);
            var myOptions = {
                zoom: 7,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            addMarker(myLatlng, 'Default Marker', map);

            map.addListener('click', function (event) {
                addMarker(event.latLng, 'Click Generated Marker', map);
            });
        }

        function handleEvent(event) {
            console.log(event.latLng);
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        }

        function addMarker(latlng, title, map) {
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: title,
                draggable: true
            });

            marker.addListener('drag', handleEvent);
            marker.addListener('dragend', handleEvent);
        }


        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_performance/update_status')}}",
                datatype: "script",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "success") {
                        var url_test =  '{{  !empty($previousUrl) ? $previousUrl:null }}'; 
                        if(url_test != null){
                            var parser = new DOMParser;
                            var dom = parser.parseFromString(url_test,'text/html');
                            var decodedString = dom.body.textContent;
                            window.location.replace(decodedString);
                        }else{
                            window.location.href = "{{url('/csurv/control_performance')}}";
                        }
                    } else if (data.status == "error") {
                        // $("#alert").html('<div class="alert alert-danger"><strong>แจ้งเตือน !</strong> ' + data.message + ' <br></div>');
                        alert(data.message)
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });

        });

        function add_filter_License() {
            var data_val = $("#tis_standard :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_performance/add_filter_License')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    for (let i = 0; i < list.length; i++) {
                        $('#tbl_tisiNo').append('<option id="tbl_tisiNo" value="' + list[i].tbl_taxpayer + '">' + list[i].tbl_tradeName + '<option>')
                        $('#mog').html('<span>' + list[i].tbl_tisiNo + '</span>')
                        $('#license').append('<div class="col-sm-3"><input type="checkbox" name="sub_license[]" class="col-sm-2 check" value="' + list[i].tbl_licenseNo + '"><label class="col-sm-8"> ' + list[i].tbl_licenseNo + '</label></div>')
                    }
                }
            });
        }

        $("#check_all").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        function remove_filter_License() {
            $('#tbl_tradeName').empty()
            $('#license').empty()
        }

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
                    for (let i = 0; i < list.length; i++) {
                        $('#address_amphoe').append('<option id="address_amphoe" value="' + list[i].AMPHUR_ID + '">' + list[i].AMPHUR_NAME + '<option>')
                    }
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
                    for (let i = 0; i < list.length; i++) {
                        $('#address_district').append('<option id="address_district" value="' + list[i].DISTRICT_ID + '">' + list[i].DISTRICT_NAME + '<option>')
                    }
                }
            });
        }

        function remove_filter_address_amphoe() {
            $('#address_district').empty()
        }

        function add_input_person() {
            var next_num = $('.sub_input').length + 1;
            var html_add_item = '<tr class="sub_input">';
            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row_people_found[]"/><span class="running-no">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: center" class="form-control" name="full_name[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;text-align: center" class="form-control" name="permission[]"></td>';
            html_add_item += '<td><a class="btn btn-small btn-danger remove-data" onclick="return false;"><span class="fa fa-trash"></span></a></td>';
            html_add_item += '</tr>';
            $('#myTable tbody').append(html_add_item);
        }

        $('#add_data').click(function () {
            add_input_person();
        });

        $(document).on('click', '.remove-data', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(100);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_input').each(function (index, el) {
                    $(el).find('.running-no').text(index + 1);
                });

            }, 500);
        });

        function add_input_staff() {

            var next_num = $('.sub_input_staff').length + 1;
            var html_add_item = '<tr class="sub_input_staff">';

            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row_permission[]"/><span class="running-no">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><select name="license[]" id="license" class="form-control" >\n' +
                '                                                <option>-เลือกชื่อเจ้าหน้าที่-</option>\n' +
                '                                                @foreach(HP::get_people_found() as $name)\n' +
                '                                                    <option id="license" value="{{$name->reg_fname . ' ' . $name->reg_lname}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>\n' +
                '                                                @endforeach\n' +
                '                                            </select></td>';
            // html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: center;" class="form-control" name="position_result[]" disabled></td>';
            html_add_item += '<td><a class="btn btn-small btn-danger remove-staff" onclick="return false;"><span class="fa fa-trash"></span></a></td>';
            html_add_item += '</tr>';
            $('#myTable2 tbody').append(html_add_item);
        }

        $('#add_staff').click(function () {
            add_input_staff();
        });

        $(document).on('click', '.remove-staff', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(300);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_input_staff').each(function (index, el) {
                    $(el).find('.running-no').text(index + 1);
                });

            }, 500);
        });


        $(document).on('click', '.remove', function () {
            var row_remove = $(this).parent();
            row_remove.remove();
        });

        $('.not_remark3').on('ifChecked', function (event) {
            document.getElementById('show_remark4').style.display = 'none';
            $("#show_remark4").prop('required', false);
        });

        $('#show_remark3').on('ifChecked', function (event) {
            document.getElementById('show_remark4').style.display = 'block';
            $("#show_remark4").prop('required', true);
        });

    </script>
@endpush

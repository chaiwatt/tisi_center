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
                        <div class="white-box">
                            <h3 class="box-title pull-left">ระบบบันทึกการตรวจประเมินระบบควบคุมคุณภาพ</h3>
                               @can('view-'.str_slug('control_performance'))
                               <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                               <i class="icon-arrow-left-circle"></i> กลับ
                              </a>
                           @endcan
                           <div class="clearfix"></div>
                           <hr>
                        <div style="border:#cccccc solid 0.1em" class="p-40">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    {{-- <div style="border: solid 0.1em" class="p-40"> --}}
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
                                                                onclick=""
                                                                onchange="add_filter_License();remove_filter_License();">
                                                            <option value="{{$data->tradeName}}">{{ HP::get_tb4_name_index2($data->tradeName) }}</option>
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
                                                                onclick=""
                                                                onchange="add_license();remove_license();"
                                                                class="form-control">
                                                            <option id="tbl_tisiNo"
                                                                    value="{{$data->tbl_tisiNo}}">{{ HP::get_tb3_tis_for_select($data->tbl_tisiNo) }}</option>
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
                                                        <input type="checkbox" name="check_all" id="check_all"
                                                               class="check" data-checkbox="icheckbox_square-green">
                                                        <label>เลือกทั้งหมด</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2"></label>
                                                    <div class="col-sm-10 p-0">
                                                        <div class="row col-sm-12 p-0" id="license">
                                                            @foreach($data_permission as $list_permission)
                                                                <div class="col-sm-3"><label
                                                                            class="col-sm-12"><input
                                                                                type="checkbox"
                                                                                name="sub_license[]"
                                                                                class="license_ck check"
                                                                                data-checkbox="icheckbox_square-green"
                                                                                value="{{$list_permission->license}}"
                                                                                checked> {{$list_permission->license}}
                                                                    </label></div>
                                                            @endforeach
                                                            @foreach(HP::get_license2($data->tradeName,$data->tbl_tisiNo) as $list)
                                                                @if(HP::get_license_check($data->id,$list->tbl_licenseNo)==null)
                                                                    <div class="col-sm-3"><label
                                                                                class="col-sm-12"><input
                                                                                    type="checkbox"
                                                                                    name="sub_license[]"
                                                                                    class="license_ck check"
                                                                                    data-checkbox="icheckbox_square-green"
                                                                                    value="{{$list->tbl_licenseNo}}"> {{$list->tbl_licenseNo}}
                                                                        </label></div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right small">ชื่อโรงงาน</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="factory_name"
                                                               value="{{$data->factory_name}}">
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
                                                            >
                                                        </div>

                                                        <label class="col-sm-2 text-right small">นิคมอุตสาหกรรม
                                                            (ถ้ามี)</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_industrial_estate"
                                                                   value="{{$data->address_industrial_estate}}">
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
                                                                   value="{{$data->address_alley}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">ถนน</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_road"
                                                                   value="{{$data->address_road}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">หมู่ที่</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="address_village_no"
                                                                   value="{{$data->address_village_no}}">
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
                                                                    onchange="add_filter_address_province();remove_filter_address_province()">
                                                                @if($data->address_province!=0)
                                                                    <option value="{{$data->address_province}}"
                                                                            selected>{{HP::gat_province($data->address_province)}}</option>
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
                                                                    onchange="add_filter_address_amphoe();remove_filter_address_amphoe()">
                                                                @if($data->address_amphoe!=0)
                                                                    <option value="{{$data->address_amphoe}}"
                                                                            selected>{{HP::gat_amphur($data->address_amphoe)}}</option>
                                                                @else
                                                                    <option>-เลือกอำเภอ/เขต-</option>
                                                                @endif

                                                            </select>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">ตำบล/แขวง </label>
                                                        <div class="col-sm-2">
                                                            <select name="address_district"
                                                                    id="address_district"
                                                                    class="form-control">
                                                                @if($data->address_district!=0)
                                                                    <option value="{{$data->address_district}}"
                                                                            selected>{{HP::gat_district($data->address_district)}}</option>
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
                                                                   value="{{$data->address_zip_code}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรศัพท์ </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control" name="tel"
                                                                   value="{{$data->tel}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรสาร </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" class="form-control" name="fax"
                                                                   value="{{$data->fax}}">
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
                                                            <input type="number"
                                                                   step=any
                                                                   class="form-control"
                                                                   name="latitude" id="lat1"
                                                                   value="{{$data->latitude}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">พิกัดที่ตั้ง
                                                            (ลองจิจูด)</label>
                                                        <div class="col-sm-2">
                                                            <input type="number"
                                                                   step=any
                                                                   class="form-control"
                                                                   name="Longitude"
                                                                   id="lng1"
                                                                   value="{{$data->Longitude}}">
                                                        </div>

                                                        <div class="col-sm-4 text-right small">
                                                            <a class="btn btn-default" onclick="show_map();">
                                                                ค้นหาจากแผนที่
                                                            </a>
                                                        </div>
                                                        <div class="modal fade" id="modal-default">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <style>
                                                                            .controls {
                                                                                margin-top: 10px;
                                                                                border: 1px solid transparent;
                                                                                border-radius: 2px 0 0 2px;
                                                                                box-sizing: border-box;
                                                                                -moz-box-sizing: border-box;
                                                                                height: 32px;
                                                                                outline: none;
                                                                                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                                                                            }

                                                                            #pac-input {
                                                                                background-color: #fff;
                                                                                font-size: 15px;
                                                                                font-weight: 300;
                                                                                margin-left: 12px;
                                                                                padding: 0 11px 0 13px;
                                                                                text-overflow: ellipsis;
                                                                                width: 300px;
                                                                            }

                                                                            #pac-input:focus {
                                                                                border-color: #4d90fe;
                                                                            }

                                                                        </style>
                                                                        {{--                                                                        <div id="mapCanvas"--}}
                                                                        {{--                                                                             style="height: 400px;"></div>--}}
                                                                        {{--                                                                        <div id="infoPanel">--}}
                                                                        {{--                                                                            <b>สถานะ Marker :</b>--}}
                                                                        {{--                                                                            <div id="markerStatus"><i>คลิ๊กและลาก--}}
                                                                        {{--                                                                                    Mark.</i></div>--}}
                                                                        {{--                                                                            <b>ตำแหน่งปัจจุบัน:</b>--}}
                                                                        {{--                                                                            <div id="info"></div>--}}
                                                                        {{--                                                                        </div>--}}
                                                                        <input id="pac-input" class="controls"
                                                                               type="text" placeholder="Search Box">
                                                                        <div id="map" style="height: 400px;"></div>
                                                                        <input id="lat2" class="controls" type="text"
                                                                               placeholder="ละติจูด" disabled>
                                                                        <input id="lng2" class="controls" type="text"
                                                                               placeholder="ลองติจูด" disabled>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-success"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">ยืนยัน</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                               value="{{$data->checking_date}}">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    {{-- </div> --}}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-12">
                                    {{-- <div style="border: solid 0.1em" class="p-40"> --}}
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

                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="form-group text-center">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="myTable">
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
                                                                        <td style="text-align: -webkit-center;"><input
                                                                                    type="text"
                                                                                    style="width: 80%; text-align: center"
                                                                                    class="form-control"
                                                                                    name="full_name[]"
                                                                                    value="{{$list_found->full_name}}">
                                                                        </td>
                                                                        <td style="text-align: -webkit-center;"><input
                                                                                    type="text"
                                                                                    style="width: 80%;text-align: center"
                                                                                    class="form-control"
                                                                                    name="permission[]"
                                                                                    value="{{$list_found->permission}}">
                                                                        </td>
                                                                        <td style="text-align: -webkit-center;"><input
                                                                                    type="text"
                                                                                    style="width: 80%;text-align: center"
                                                                                    class="form-control"
                                                                                    name="people_tel[]"
                                                                                    value="{{$list_found->people_tel}}">
                                                                        </td>
                                                                        <td style="text-align: -webkit-center;"><input
                                                                                    type="text"
                                                                                    style="width: 80%;text-align: center"
                                                                                    class="form-control"
                                                                                    name="people_email[]"
                                                                                    value="{{$list_found->people_email}}">
                                                                        </td>
                                                                        <td>
                                                                            <a class="btn btn-small btn-danger remove-data"
                                                                               onclick="return false;"><span
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

                                            <div class="col-md-12 ">
                                                <div class="row">
                                                    <div class="form-group ">
                                                        <div class="form-group">
                                                            <div class="col-md-2"></div>
                                                            <label class="col-md-2">พนักงานเจ้าหน้าที่</label>
                                                            <div class="col-md-6 m-b-10 text-right">
                                                                <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                        name="add_staff"
                                                                        id="add_staff"
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

                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="form-group ">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="myTable2">
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
                                                                            <select name="full_name_per[]" id="license"
                                                                                    class="form-control">
                                                                                <option>{{$list_officer->full_name}}</option>
                                                                                @foreach(HP::get_people_found() as $name)
                                                                                    <option id="full_name_per"
                                                                                            value="{{$name->reg_fname . ' ' . $name->reg_lname}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        <td>
                                                                            <a class="btn btn-small btn-danger remove-staff"
                                                                               onclick="return false;"><span
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
                                                                       class="col-sm-2 check"
                                                                       value="C"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->material_res == 'C') ? 'checked' : '' ?>
                                                                >
                                                                <label>C</label>
                                                            </div>
                                                            <div class="col-sm-2 ">
                                                                <input type="radio"
                                                                       name="material_res"
                                                                       class="col-sm-2 check"
                                                                       value="NC"
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
                                                                       class="col-sm-2 check"
                                                                       data-radio="iradio_square-green"
                                                                       id="not_find_1"
                                                                <?php echo ($data->material_ofsev == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="material_ofsev"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_1"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->material_ofsev == 'พบ') ? 'checked' : '' ?>>
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note"
                                                                       name="material_ofsev_remake"
                                                                       class="form-control"
                                                                       style="display: none"
                                                                       value="{{$data->material_ofsev_remake}}">
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
                                                                       name="material_defect"
                                                                       value="ไม่พบ"
                                                                       class="col-sm-2 check"
                                                                       id="not_find_2"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->material_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="material_defect"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_2"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->material_defect == 'พบ') ? 'checked' : '' ?>>
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note2"
                                                                       name="material_defect_remake"
                                                                       class="form-control"
                                                                       style="display: none"
                                                                       value="{{$data->material_defect_remake}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 ">
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
                                                                                id="add-material">
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
                                                                        <div>
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="material_note[]"
                                                                                       value="{{$data_material[$i]->note}}">
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <div class="form-control file_material_old">
                                                                                    @if($data_material[$i]->file !='' && HP::checkFileStorage($attach_path.$data_material[$i]->file))
                                                                                      <a href="{{ HP::getFileStorage($attach_path.$data_material[$i]->file) }}" target="_blank" >
                                                                                        {{ $data_material[$i]->file }}
                                                                                      </a>
                                                                                     @endif
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_material[$i]->file)}}">{{$data_material[$i]->file}}</a> --}}
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
                                                                                   onclick="return false;"><span
                                                                                            class="fa fa-trash"></span></a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div>
                                                                            <div class="col-md-4 m-t-5">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="material_note[]"
                                                                                       value="{{$data_material[$i]->note}}">
                                                                            </div>
                                                                            <div class="col-md-5  m-t-5">
                                                                                <div class="form-control file_old"
                                                                                     id="file">
                                                                                     @if($data_material[$i]->file !='' && HP::checkFileStorage($attach_path.$data_material[$i]->file))
                                                                                      <a href="{{ HP::getFileStorage($attach_path.$data_material[$i]->file) }}" target="_blank" >
                                                                                        {{ $data_material[$i]->file }}
                                                                                      </a>
                                                                                     @endif
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_material[$i]->file)}}">{{$data_material[$i]->file}}</a> --}}
                                                                                </div>

                                                                                <div id="new_file"></div>
                                                                                <input type="text" id="file_old0"
                                                                                       name="file_material_old[]"
                                                                                       value="{{$data_material[$i]->file}}"
                                                                                       hidden>
                                                                            </div>
                                                                            <div class="col-md-2 m-t-5">
                                                                                <a class="btn btn-small btn-danger remove_material"
                                                                                   onclick="return false;"><span
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
                                                                           name="material_note[]">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="file" name="material_file0"
                                                                           class="form-control check_max_size_file">
                                                                </div>
                                                                <input type="hidden" name="material_file_row[]">
                                                            </div>
                                                        @endif

                                                        <div id="material_box">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 ">
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
                                                                                  name="material_remark"
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
                                    {{-- </div> --}}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    {{-- <div style="border: solid 0.1em" class="p-40"> --}}
                                    <div class="row form-group">
                                        <div class="col-md-12">

                                            <div class="col-md-12 ">
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
                                                                       class="col-sm-2 check"
                                                                       value="C"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->control_between_res == 'C') ? 'checked' : '' ?>
                                                                >
                                                                <label>C</label>
                                                            </div>
                                                            <div class="col-sm-2 ">
                                                                <input type="radio"
                                                                       name="control_between_res"
                                                                       class="col-sm-2 check"
                                                                       value="NC"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->control_between_res == 'NC') ? 'checked' : '' ?>
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
                                                                       name="control_between_ofsev"
                                                                       value="ไม่พบ"
                                                                       class="col-sm-2 check"
                                                                       id="not_find_3"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->control_between_ofsev == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="control_between_ofsev"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_3"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->control_between_ofsev == 'พบ') ? 'checked' : '' ?>>
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note3"
                                                                       name="control_between_ofsev_remake"
                                                                       class="form-control"
                                                                       style="display: none"
                                                                       value="{{$data->control_between_ofsev_remake}}">
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
                                                                       name="control_between_defect"
                                                                       value="ไม่พบ"
                                                                       class="col-sm-2 check"
                                                                       id="not_find_4"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->control_between_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="control_between_defect"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_4"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->control_between_defect == 'พบ') ? 'checked' : '' ?>>
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note4"
                                                                       name="control_between_defect_remake"
                                                                       class="form-control"
                                                                       style="display: none"
                                                                       value="{{$data->control_between_defect_remake}}">
                                                            </div>

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
                                                                            id="add-control_between">
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
                                                                    <div>
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control"
                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                   name="control_between_note[]"
                                                                                   value="{{$data_control_between[$i]->note}}">
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="form-control file_control_between_old">
                                                                                {{-- <a href="{{url('/csurv/control_performance/download/'.$data_control_between[$i]->file)}}">{{$data_control_between[$i]->file}}</a> --}}
                                                                                @if($data_control_between[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_between[$i]->file))
                                                                                <a href="{{ HP::getFileStorage($attach_path.$data_control_between[$i]->file) }}" target="_blank" >
                                                                                  {{ $data_control_between[$i]->file }}
                                                                                </a>
                                                                               @endif
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
                                                                               onclick="return false;"><span
                                                                                        class="fa fa-trash"></span></a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        <div class="col-md-4 m-t-5">
                                                                            <input type="text" class="form-control"
                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                   name="control_between_note[]"
                                                                                   value="{{$data_control_between[$i]->note}}">
                                                                        </div>
                                                                        <div class="col-md-5  m-t-5">
                                                                            <div class="form-control file_old"
                                                                                 id="file">
                                                                                 @if($data_control_between[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_between[$i]->file))
                                                                                 <a href="{{ HP::getFileStorage($attach_path.$data_control_between[$i]->file) }}" target="_blank" >
                                                                                   {{ $data_control_between[$i]->file }}
                                                                                 </a>
                                                                                @endif
                                                                                {{-- <a href="{{url('/csurv/control_performance/download/'.$data_control_between[$i]->file)}}">{{$data_control_between[$i]->file}}</a> --}}
                                                                            </div>

                                                                            <div id="new_file"></div>
                                                                            <input type="text" id="file_old0"
                                                                                   name="file_control_between_old[]"
                                                                                   value="{{$data_control_between[$i]->file}}"
                                                                                   hidden>
                                                                        </div>
                                                                        <div class="col-md-2 m-t-5">
                                                                            <a class="btn btn-small btn-danger remove_control_between"
                                                                               onclick="return false;"><span
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
                                                                       name="control_between_note[]">
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="file" name="control_between_file0"
                                                                       class="form-control check_max_size_file">
                                                            </div>
                                                            <input type="hidden" name="control_between_file_row[]">
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
                                                                        <textarea class="form-control"
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
                                    <div class="row form-group">
                                        <div class="col-md-12">

                                            <div class="col-md-12 ">
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
                                                            <div class="col-sm-2 ">
                                                                <input type="radio"
                                                                       name="control_finish_res"
                                                                       class="col-sm-2 check"
                                                                       value="C"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->control_finish_res == 'C') ? 'checked' : '' ?>
                                                                >
                                                                <label>C</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="control_finish_res"
                                                                       class="col-sm-2 check"
                                                                       value="NC"
                                                                       data-radio="iradio_square-red"
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
                                                            <div class="col-sm-2 ">
                                                                <input type="radio"
                                                                       name="control_finish_ofsev"
                                                                       value="ไม่พบ"
                                                                       class="col-sm-2 check"
                                                                       id="not_find_5"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->control_finish_ofsev == 'ไม่พบ') ? 'checked' : '' ?>
                                                                >
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="control_finish_ofsev"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_5"
                                                                       data-radio="iradio_square-red"
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
                                                                       value="{{$data->control_finish_ofsev_remake}}">
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
                                                                       class="col-sm-2 check"
                                                                       id="not_find_6"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->control_finish_defect == 'ไม่พบ') ? 'checked' : '' ?>>
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="control_finish_defect"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_6"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->control_finish_defect == 'พบ') ? 'checked' : '' ?>>
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note6"
                                                                       name="control_finish_defect_remake"
                                                                       class="form-control"
                                                                       style="display: none"
                                                                       value="{{$data->control_finish_defect_remake}}">
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
                                                                                id="add-control_finish">
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
                                                        @if($data_file_control_finish!=null)
                                                            @for($i=0;$i<count($data_control_finish);$i++)
                                                                @if($data_control_finish[$i]->type == 'control_finish')
                                                                    @if($i==0)
                                                                        <div>
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="control_finish_note[]"
                                                                                       value="{{$data_control_finish[$i]->note}}">
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <div class="form-control file_control_finish_old">
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_control_finish[$i]->file)}}">{{$data_control_finish[$i]->file}}</a> --}}
                                                                                    @if($data_control_finish[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_finish[$i]->file))
                                                                                    <a href="{{ HP::getFileStorage($attach_path.$data_control_finish[$i]->file) }}" target="_blank" >
                                                                                      {{ $data_control_finish[$i]->file }}
                                                                                    </a>
                                                                                   @endif
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
                                                                                   onclick="return false;"><span
                                                                                            class="fa fa-trash"></span></a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div>
                                                                            <div class="col-md-4 m-t-5">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="control_finish_note[]"
                                                                                       value="{{$data_control_finish[$i]->note}}">
                                                                            </div>
                                                                            <div class="col-md-5  m-t-5">
                                                                                <div class="form-control file_old"
                                                                                     id="file">
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_control_finish[$i]->file)}}">{{$data_control_finish[$i]->file}}</a> --}}
                                                                                    @if($data_control_finish[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_finish[$i]->file))
                                                                                    <a href="{{ HP::getFileStorage($attach_path.$data_control_finish[$i]->file) }}" target="_blank" >
                                                                                      {{ $data_control_finish[$i]->file }}
                                                                                    </a>
                                                                                   @endif
                                                                                </div>

                                                                                <div id="new_file"></div>
                                                                                <input type="text" id="file_old0"
                                                                                       name="file_control_finish_old[]"
                                                                                       value="{{$data_control_finish[$i]->file}}"
                                                                                       hidden>
                                                                            </div>
                                                                            <div class="col-md-2 m-t-5">
                                                                                <a class="btn btn-small btn-danger remove_control_finish"
                                                                                   onclick="return false;"><span
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
                                                                           name="control_finish_note[]">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="file" name="control_finish_file0"
                                                                           class="form-control check_max_size_file">
                                                                </div>
                                                                <input type="hidden" name="control_finish_file_row[]">
                                                            </div>
                                                        @endif

                                                        <div id="control_finish_box">

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
                                                            <div class="form-group ">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-10">
                                                                    <div class="col-md-9">
                                                                        <textarea class="form-control"
                                                                                  name="control_finish_remark"
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
                                    {{-- </div> --}}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    {{-- <div style="border: solid 0.1em" class="p-40"> --}}
                                    <div class="row form-group">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="form-group">
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
                                                                   class="col-sm-2 check"
                                                                   value="C"
                                                                   data-radio="iradio_square-green"
                                                            <?php echo ($data->control_standard_res == 'C') ? 'checked' : '' ?>
                                                            >
                                                            <label>C</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="radio"
                                                                   name="control_standard_res"
                                                                   class="col-sm-2 check"
                                                                   value="NC"
                                                                   data-radio="iradio_square-red"
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
                                                                   class="col-sm-2 check"
                                                                   id="not_find_7"
                                                                   data-radio="iradio_square-green"
                                                            <?php echo ($data->control_standard_ofsev == 'ไม่พบ') ? 'checked' : '' ?>
                                                            >
                                                            <label>ไม่พบ</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="radio"
                                                                   name="control_standard_ofsev"
                                                                   value="พบ"
                                                                   class="col-sm-2 check"
                                                                   id="find_7"
                                                                   data-radio="iradio_square-red"
                                                            <?php echo ($data->control_standard_ofsev == 'พบ') ? 'checked' : '' ?>
                                                            >
                                                            <label>พบ (โปรดระบุ)</label>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input type="text"
                                                                   id="note7"
                                                                   name="control_standard_ofsev_remake"
                                                                   class="form-control"
                                                                   style="display: none"
                                                                   value="{{$data->control_standard_ofsev_remake}}">
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
                                                                   class="col-sm-2 check"
                                                                   id="not_find_8"
                                                                   data-radio="iradio_square-green"
                                                            <?php echo ($data->control_standard_defect == 'ไม่พบ') ? 'checked' : '' ?>
                                                            >
                                                            <label>ไม่พบ</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="radio"
                                                                   name="control_standard_defect"
                                                                   value="พบ"
                                                                   class="col-sm-2 check"
                                                                   id="find_8"
                                                                   data-radio="iradio_square-red"
                                                            <?php echo ($data->control_standard_defect == 'พบ') ? 'checked' : '' ?>
                                                            >
                                                            <label>พบ (โปรดระบุ)</label>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <input type="text"
                                                                   id="note8"
                                                                   name="control_standard_defect_remake"
                                                                   class="form-control"
                                                                   style="display: none"
                                                                   value="{{$data->control_standard_defect_remake}}">
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
                                                                            id="add-control_standard">
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
                                                    @if($data_file_control_standard!=null)
                                                        @for($i=0;$i<count($data_control_standard);$i++)
                                                            @if($data_control_standard[$i]->type == 'control_standard')
                                                                @if($i==0)
                                                                    <div>
                                                                        <div class="col-md-4">
                                                                            <input type="text" class="form-control"
                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                   name="control_standard_note[]"
                                                                                   value="{{$data_control_standard[$i]->note}}">
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="form-control file_control_standard_old">
                                                                                {{-- <a href="{{url('/csurv/control_performance/download/'.$data_control_standard[$i]->file)}}">{{$data_control_standard[$i]->file}}</a> --}}
                                                                                    @if($data_control_standard[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_standard[$i]->file))
                                                                                        <a href="{{ HP::getFileStorage($attach_path.$data_control_standard[$i]->file) }}" target="_blank" >
                                                                                            {{ $data_control_standard[$i]->file }}
                                                                                        </a>
                                                                                    @endif
                                                                            </div>

                                                                            <div id="new_control_standard_file"></div>
                                                                            <input type="text"
                                                                                   class="file_control_standard_old"
                                                                                   name="file_control_standard_old[]"
                                                                                   value="{{$data_control_standard[$i]->file}}"
                                                                                   hidden>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <a class="btn btn-small btn-danger remove_control_standard"
                                                                               onclick="return false;"><span
                                                                                        class="fa fa-trash"></span></a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        <div class="col-md-4 m-t-5">
                                                                            <input type="text" class="form-control"
                                                                                   placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                   name="control_standard_note[]"
                                                                                   value="{{$data_control_standard[$i]->note}}">
                                                                        </div>
                                                                        <div class="col-md-5  m-t-5">
                                                                            <div class="form-control file_old"
                                                                                 id="file">
                                                                                {{-- <a href="{{url('/csurv/control_performance/download/'.$data_control_standard[$i]->file)}}">{{$data_control_standard[$i]->file}}</a> --}}
                                                                                    @if($data_control_standard[$i]->file !='' && HP::checkFileStorage($attach_path.$data_control_standard[$i]->file))
                                                                                        <a href="{{ HP::getFileStorage($attach_path.$data_control_standard[$i]->file) }}" target="_blank" >
                                                                                            {{ $data_control_standard[$i]->file }}
                                                                                        </a>
                                                                                    @endif
                                                                            </div>

                                                                            <div id="new_file"></div>
                                                                            <input type="text" id="file_old0"
                                                                                   name="file_control_standard_old[]"
                                                                                   value="{{$data_control_standard[$i]->file}}"
                                                                                   hidden>
                                                                        </div>
                                                                        <div class="col-md-2 m-t-5">
                                                                            <a class="btn btn-small btn-danger remove_control_standard"
                                                                               onclick="return false;"><span
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
                                                                       name="control_standard_note[]">
                                                            </div>
                                                            <div class="col-md-5">
                                                                <input type="file" name="control_standard_file0"
                                                                       class="form-control check_max_size_file">
                                                            </div>
                                                            <input type="hidden" name="control_standard_file_row[]">
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
                                                                        <textarea class="form-control"
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
                                                            <div class="col-sm-2 ">
                                                                <input type="radio"
                                                                       name="test_machine_res"
                                                                       class="col-sm-2 check"
                                                                       value="C"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->test_machine_res == 'C') ? 'checked' : '' ?>
                                                                >
                                                                <label>C</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="test_machine_res"
                                                                       class="col-sm-2 check"
                                                                       value="NC"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->test_machine_res == 'NC') ? 'checked' : '' ?>
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
                                                                       name="test_machine_ofsev"
                                                                       value="ไม่พบ"
                                                                       class="col-sm-2 check"
                                                                       id="not_find_9"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->test_machine_ofsev == 'ไม่พบ') ? 'checked' : '' ?>
                                                                >
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="test_machine_ofsev"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_9"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->test_machine_ofsev == 'พบ') ? 'checked' : '' ?>
                                                                >
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note9"
                                                                       name="test_machine_ofsev_remake"
                                                                       class="form-control"
                                                                       style="display: none"
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
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="test_machine_defect"
                                                                       value="ไม่พบ"
                                                                       class="col-sm-2 check"
                                                                       id="not_find_10"
                                                                       data-radio="iradio_square-green"
                                                                <?php echo ($data->test_machine_defect == 'ไม่พบ') ? 'checked' : '' ?>
                                                                >
                                                                <label>ไม่พบ</label>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <input type="radio"
                                                                       name="test_machine_defect"
                                                                       value="พบ"
                                                                       class="col-sm-2 check"
                                                                       id="find_10"
                                                                       data-radio="iradio_square-red"
                                                                <?php echo ($data->test_machine_defect == 'พบ') ? 'checked' : '' ?>
                                                                >
                                                                <label>พบ (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <input type="text"
                                                                       id="note10"
                                                                       name="test_machine_defect_remake"
                                                                       class="form-control"
                                                                       style="display: none"
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
                                                                                id="add-test_machine">
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
                                                        @if($data_file_test_machine!=null)
                                                            @for($i=0;$i<count($data_test_machine);$i++)
                                                                @if($data_test_machine[$i]->type == 'test_machine')
                                                                    @if($i==0)
                                                                        <div>
                                                                            <div class="col-md-4">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="test_machine_note[]"
                                                                                       value="{{$data_test_machine[$i]->note}}">
                                                                            </div>
                                                                            <div class="col-md-5">
                                                                                <div class="form-control file_test_machine_old">
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_test_machine[$i]->file)}}">{{$data_test_machine[$i]->file}}</a> --}}
                                                                                    @if($data_test_machine[$i]->file !='' && HP::checkFileStorage($attach_path.$data_test_machine[$i]->file))
                                                                                        <a href="{{ HP::getFileStorage($attach_path.$data_test_machine[$i]->file) }}" target="_blank" >
                                                                                            {{ $data_test_machine[$i]->file }}
                                                                                        </a>
                                                                                    @endif
                                                                                </div>

                                                                                <div id="new_test_machine_file"></div>
                                                                                <input type="text"
                                                                                       class="file_test_machine_old"
                                                                                       name="file_test_machine_old[]"
                                                                                       value="{{$data_test_machine[$i]->file}}"
                                                                                       hidden>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <a class="btn btn-small btn-danger remove_test_machine"
                                                                                   onclick="return false;"><span
                                                                                            class="fa fa-trash"></span></a>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div>
                                                                            <div class="col-md-4 m-t-5">
                                                                                <input type="text" class="form-control"
                                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"
                                                                                       name="test_machine_note[]"
                                                                                       value="{{$data_test_machine[$i]->note}}">
                                                                            </div>
                                                                            <div class="col-md-5  m-t-5">
                                                                                <div class="form-control file_old"
                                                                                     id="file">
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_test_machine[$i]->file)}}">{{$data_test_machine[$i]->file}}</a> --}}
                                                                                    @if($data_test_machine[$i]->file !='' && HP::checkFileStorage($attach_path.$data_test_machine[$i]->file))
                                                                                        <a href="{{ HP::getFileStorage($attach_path.$data_test_machine[$i]->file) }}" target="_blank" >
                                                                                            {{ $data_test_machine[$i]->file }}
                                                                                        </a>
                                                                                    @endif
                                                                                </div>

                                                                                <div id="new_file"></div>
                                                                                <input type="text" id="file_old0"
                                                                                       name="file_test_machine_old[]"
                                                                                       value="{{$data_test_machine[$i]->file}}"
                                                                                       hidden>
                                                                            </div>
                                                                            <div class="col-md-2 m-t-5">
                                                                                <a class="btn btn-small btn-danger remove_test_machine"
                                                                                   onclick="return false;"><span
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
                                                                           name="test_machine_note[]">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="file" name="test_machine_file0"
                                                                           class="form-control check_max_size_file">
                                                                </div>
                                                                <input type="hidden" name="test_machine_file_row[]">
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
                                                                        <textarea class="form-control"
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
                                    {{-- </div> --}}
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
                                                                class="col-sm-1 check not_remark"
                                                                data-radio="iradio_square-green"
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
                                                                class="col-sm-1 check"
                                                                id="show_remark"
                                                                data-radio="iradio_square-green"
                                                            <?php echo ($data->conclude_result == '2') ? 'checked' : '' ?>
                                                            >
                                                            <label>แก้ไขให้เป็นไปตามข้อกำหนด
                                                                (โปรดระบุสิ่งที่ต้องแก้ไข)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="display: none" id="show_remark2">
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
                                                                        rows="5">{{$data->remake}}</textarea>
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
                                                                class="col-sm-1 check not_remark"
                                                                data-radio="iradio_square-green"
                                                            <?php echo ($data->conclude_result == '3') ? 'checked' : '' ?>
                                                            >
                                                            <label>ไม่เป็นไปตามข้อกำหนด ส่งเรื่องให้ กม. ดำเนินการ</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="row">
                                                        <div class="form-group m-b-10 col-md-6">
                                                            <div class="col-md-2"></div>
                                                            <div class="col-sm-4 ">
                                                                <label>ไฟล์แนบเพิ่มเติม</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                        @if($data_file_check==null)
                                                            <div class="form-group">
                                                                <strong class="control-label col-md-2 text-right"> ไฟล์แนบ
                                                                    (ถ้ามี) : </strong>
                                                                <div class="col-md-1">
                                                                    <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                            name="add_upload"
                                                                            id="add_upload"
                                                                            onClick="return false;">
                                                                        <span class="btn-label"><i
                                                                                    class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                    </button>
                                                                </div>
                                                                <div class="col-md-5 form-group">
                                                                    <div style="color: red;"
                                                                        class="control-label col-md-10 text-left">รองรับไฟล์
                                                                        .pdf
                                                                        ขนาดไม่เกิน 10Mb.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row sub_file">
                                                                <div class="form-group m-b-5 col-md-12">
                                                                    <label class="col-md-2"></label>
                                                                    <div class="col-sm-4">
                                                                        <input type="file"
                                                                            name="file0"
                                                                            id="file0" class="form-control check_max_size_file"
                                                                        >
                                                                        <input type="text"
                                                                            name="num_row_file[]"
                                                                            hidden>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            @for($j = 0 ; $j < count($data_file) ; $j++)
                                                                @if($j==0)
                                                                    <div class="form-group">
                                                                        <strong class="control-label col-md-2 text-right">
                                                                            ไฟล์แนบ (ถ้ามี)
                                                                            : </strong>
                                                                        <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                name="add_upload"
                                                                                id="add_upload"
                                                                                onClick="return false;">
                                                                            <span class="btn-label"><i
                                                                                        class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                        </button>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group m-b-5 col-md-12">
                                                                            <label class="col-md-2"></label>
                                                                            <div class="col-sm-4 " id="file0">
                                                                                <div class="form-control">
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_file[$j]->file)}}"><span style="font-size: small">{{$data_file[$j]->file}}</span></a> --}}
                                                                                    @if($data_file[$j]->file !='' && HP::checkFileStorage($attach_path.$data_file[$j]->file))
                                                                                        <a href="{{ HP::getFileStorage($attach_path.$data_file[$j]->file) }}" target="_blank" >
                                                                                            {{ $data_file[$j]->file }}
                                                                                        </a>
                                                                                    @endif
                                                                                </div>
                                                                                <input type="text"
                                                                                    name="num_row_file[]"
                                                                                    hidden>
                                                                                <input type="text" id="file_old0"
                                                                                    name="file_old[]"
                                                                                    value="{{$data_file[$j]->file}}"
                                                                                    hidden>
                                                                            </div>
                                                                            <a class="btn btn-small btn-danger remove"
                                                                            onclick="return false;"><span
                                                                                        class="fa fa-trash"></span></a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        <div class="form-group">
                                                                            <span class="running-no"></span>
                                                                            <div class="col-md-2"></div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-control"
                                                                                    id="file{{$j}}">
                                                                                    {{-- <a href="{{url('/csurv/control_performance/download/'.$data_file[$j]->file)}}"><span style="font-size: small">{{$data_file[$j]->file}}</span></a> --}}
                                                                                    @if($data_file[$j]->file !='' && HP::checkFileStorage($attach_path.$data_file[$j]->file))
                                                                                        <a href="{{ HP::getFileStorage($attach_path.$data_file[$j]->file) }}" target="_blank" >
                                                                                            {{ $data_file[$j]->file }}
                                                                                        </a>
                                                                                   @endif
                                                                                </div>
                                                                                <input type="text" id="file_old0"
                                                                                    name="file_old[]"
                                                                                    value="{{$data_file[$j]->file}}"
                                                                                    hidden>
                                                                            </div>
                                                                            <a class="btn btn-small btn-danger remove"
                                                                            onclick="return false;"><span
                                                                                        class="fa fa-trash"></span></a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endfor
                                                        @endif

                                                    <div>
                                                        <div id="file_upload"></div>
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
                                                                        value="{{date("m/d/Y")}}"
                                                                        disabled>
                                                                    <input value="{{date("m/d/Y")}}"
                                                                        name="date_now" hidden>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        @if(!in_array('5', auth()->user()->RoleListId) && !in_array('7', auth()->user()->RoleListId))  <!--อยู่ระหว่าง ผก.รับรอง -->
                                                <div id="status_btn"></div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="form-group text-center">
                                                            <button class="btn bg-primary btn-lg waves-effect waves-light m-r-30"
                                                                    type="submit"
                                                                    onclick="add_status_btn('1')">
                                                                <i class="fa fa-send"></i>
                                                                <b>ส่งรายงาน</b>
                                                            </button>
                                                            <button class="btn btn-info btn-lg waves-effect waves-light m-r-30"
                                                                    type="submit"
                                                                    onclick="add_status_btn('0')">
                                                                    <i class="fa fa-save"></i>
                                                                <b>บันทึกร่าง</b>
                                                            </button>
                                                            <a class="btn btn-default btn-lg waves-effect waves-light"
                                                            href="{{ url("$previousUrl") }}">
                                                            <i class="fa fa-close"></i>
                                                                <b>ยกเลิก</b>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                        @endif

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
                                                                            {{ 'อื่นๆ '.@$item->status_res_remake}}
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
                        
                      @if(in_array('5', auth()->user()->RoleListId) && $data->status != '5')  <!--อยู่ระหว่าง ผก.รับรอง -->
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
                                                               href="{{ url("$previousUrl") }}">
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

                        </div>


                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // This example adds a search box to a map, using the Google Place Autocomplete
        // feature. People can enter geographical searches. The search box will return a
        // pick list containing a mix of places and predicted search terms.
        var markers = [];

        function initAutocomplete() {
            var lat = document.getElementById('lat1').value;
            var lng = document.getElementById('lng1').value;
            if (lat === '') {
                lat = 13.7563309;
            }
            if (lng === '') {
                lng = 100.50176510000006;
            }

            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: parseFloat(lat), lng: parseFloat(lng)},
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            markers = new google.maps.Marker({
                position: {lat: parseFloat(lat), lng: parseFloat(lng)},
                title: 'Point A',
                map: map
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });

            google.maps.event.addListener(map, 'click', function (event) {
                // markers.setMap(null);

                markers = new google.maps.Marker({
                    position: {lat: event.latLng.lat(), lng: event.latLng.lng()},
                    map: map,
                });
                var lat1 = document.getElementById('lat1');
                var lat2 = document.getElementById('lat2');
                lat1.value = event.latLng.lat();
                lat2.value = event.latLng.lat();
                var lng1 = document.getElementById('lng1');
                var lng2 = document.getElementById('lng2');
                lng1.value = event.latLng.lng();
                lng2.value = event.latLng.lng();
            });

            searchBox.addListener('places_changed', function () {
                // marker.setMap(null);
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                markers.setMap(null);

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
                    var lat1 = document.getElementById('lat1');
                    var lat2 = document.getElementById('lat2');
                    lat1.value = place.geometry.location.lat();
                    lat2.value = place.geometry.location.lat();
                    var lng1 = document.getElementById('lng1');
                    var lng2 = document.getElementById('lng2');
                    lng1.value = place.geometry.location.lng();
                    lng2.value = place.geometry.location.lng();
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };

                    markers = new google.maps.Marker({
                        position: {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()},
                        map: map,
                    });

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            // [END region_getplaces]
        }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkwr5rmzY9btU08sQlU9N0qfmo8YmE91Y&libraries=places&callback=initAutocomplete"
            async defer></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>


    {{--    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>--}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.onload = function () {
            // add_license();
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
            document.getElementById('show_remark2').style.display = 'block';
            $("#add_remake").prop('required', true);
            @endif
            @if($data->address_province!=0)
            add_filter_address_province()
            @endif
            @if($data->address_amphoe!=0)
            add_filter_address_amphoe()
            @endif
        }

        $(document).on('click', '.remove_old', function () {
            var next_num = $('.sub_file').length;
            $('.file_old').remove();
            $('#file_old0').remove();
            $('#new_file').html('<input type="file" class="form-control sub_file check_max_size_file"  id="file" name="file' + next_num + '"><input name="num_row_file[]" hidden>')
            check_max_size_file();
        });

        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="check_status" value="' + status + '" hidden>');
        }

        $('#datepicker-time').datepicker({
            dateFormat: 'dd/mm/yy',
            autoclose: true
        });

        function show_map() {
            $('#modal-default').modal('show')
        }


        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_performance/update')}}",
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
                    } else if (data.status == "error" || data.status == "status_two" ) {
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
                    $('#tbl_tisiNo').empty();
                    var response = data;
                    var list = response.data;
                    var opt = "<option value=''>-เลือกมาตรฐาน-</option>";
                    $.each(list, function (key, val) {
                        opt += "<option  value='" + val.tb3_Tisno + "'>" + val.tb3_Tisno + ' : ' + val.tb3_TisThainame + "</option>";
                    });
                    $('#tbl_tisiNo').append(opt).trigger("change");
                }
            });
        }

        function add_license() {
            var data_val1 = $("#tis_standard :selected").val();
            var data_val2 = $("#tbl_tisiNo :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_performance/add_license')}}",
                datatype: "html",
                data: {
                    tb3_Tisno1: data_val1,
                    tb3_Tisno2: data_val2,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var tisiNoShow = list[0]?list[0].tbl_tisiNo:'';
                    $('#mog').append('<span>' + tisiNoShow + '</span>')
                    $.each(list, function (key, val) {
                        $('#license').append('<div class="col-sm-3"><label class="col-sm-12"><input type="checkbox" class="license_ck check"  name="sub_license[]" value="' + val.tbl_licenseNo + '">' + val.tbl_licenseNo + '</label></div>')
                    });
                    $('.license_ck').iCheck({
                        checkboxClass: 'icheckbox_square-green',
                    });
                }
            });
        }

        function remove_filter_License() {
            $('#mog').empty()
            $('#license').empty()
            if ($("#tbl_tisiNo :selected").val()) {
                $('#tbl_tisiNo')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="-เลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์-">-เลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์-</option>')
                    .val('-เลือกเลขมาตรฐาน/ชื่อผลิตภัณฑ์-')
                ;
            }

        }

        function remove_license() {
            $('#mog').empty()
            $('#license').empty()
        }

        $('#check_all').on('ifChecked', function (event) {
            $('.license_ck').iCheck('check');
        });
        $('#check_all').on('ifUnchecked', function (event) {
            $('.license_ck').iCheck('uncheck');
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
                        if('{{$data->address_amphoe}}'!=val.AMPHUR_ID){
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
                        if('{{$data->address_district}}'!=val.DISTRICT_ID){
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

        function add_input_person() {
            var next_num = $('.sub_input').length + 1;
            var html_add_item = '<tr class="sub_input">';
            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row_people_found[]"/><span class="running-no">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: center" class="form-control" name="full_name[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;text-align: center" class="form-control" name="permission[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;text-align: center" class="form-control" name="people_tel[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;text-align: center" class="form-control" name="people_email[]"></td>';
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
            html_add_item += '<td style="text-align: -webkit-center;"><select name="full_name_per[]" id="full_name_per" class="form-control" >\n' +
                '                                                <option>-เลือกชื่อเจ้าหน้าที่-</option>\n' +
                '                                                @foreach(HP::get_people_found() as $name)\n' +
                '                                                    <option id="full_name_per" value="{{$name->reg_fname . ' ' . $name->reg_lname}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>\n' +
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


        function add_file_upload() {
            var next_num = $('.sub_file').length;

            var html_add_item = ' <div class="row ">' +
                '<div class="form-group m-b-5 col-md-12">' +
                '<label class="col-md-2"></label>' +
                '<div class="col-sm-4">' +
                '<input type="file"' +
                '       name="file' + next_num + '"' +
                '       id="file' + next_num + '" class="form-control sub_file check_max_size_file"' +
                '       >' +
                '<input type="text"' +
                '       name="num_row_file[]" ' +
                '       hidden>' +
                '</div>' +
                '<a class="btn btn-small btn-danger remove" onclick="return false;">' + '<span class="fa fa-trash"></span>' + '</a>' +
                '</div>' +
                '</div>';
            $('#file_upload').append(html_add_item);

            var uploadField1 = document.getElementById("file" + next_num);
            uploadField1.onchange = function () {
                if (this.files[0].size > 10485760) {
                    alert("ไฟล์มีขนาดใหญ่เกินไป");
                    this.value = "";
                }
                ;
            };
        }

        $("#add_upload").click(function () {
            add_file_upload();
        });



        @if(!in_array('5', auth()->user()->RoleListId)) // เจ้าหน้าที่
         //     ประเมินผลการตรวจ
            var uploadField = document.getElementById("file0");
                uploadField.onchange = function () {
                if (this.files[0].size > 10485760) {
                    alert("ไฟล์มีขนาดใหญ่เกินไป");
                    this.value = "";
                }
                ;
        };
        @endif



        $(document).on('click', '.remove', function () {
            var row_remove = $(this).parent().parent();
            row_remove.remove();
            $('.sub_file').each(function (index, el) {
                var num = index
                $(el).find('.sub_file').attr("name", "file" + 2);
            });
        });

        $('.not_remark').on('ifChecked', function (event) {
            document.getElementById('show_remark2').style.display = 'none';
            $("#add_remake").prop('required', false);
        });

        $('#show_remark').on('ifChecked', function (event) {
            document.getElementById('show_remark2').style.display = 'block';
            $("#add_remake").prop('required', true);
        });

        $('#not_find_1').on('ifChecked', function (event) {
            document.getElementById('note').style.display = 'none';
            $("#note").prop('required', false);
        });

        $('#find_1').on('ifChecked', function (event) {
            document.getElementById('note').style.display = 'block';
            $("#note").prop('required', true);
        });

        $('#not_find_2').on('ifChecked', function (event) {
            document.getElementById('note2').style.display = 'none';
            $("#note2").prop('required', false);
        });

        $('#find_2').on('ifChecked', function (event) {
            document.getElementById('note2').style.display = 'block';
            $("#note2").prop('required', true);
        });

        $('#not_find_3').on('ifChecked', function (event) {
            document.getElementById('note3').style.display = 'none';
            $("#note3").prop('required', false);
        });

        $('#find_3').on('ifChecked', function (event) {
            document.getElementById('note3').style.display = 'block';
            $("#note3").prop('required', true);
        });

        $('#not_find_4').on('ifChecked', function (event) {
            document.getElementById('note4').style.display = 'none';
            $("#note4").prop('required', false);
        });

        $('#find_4').on('ifChecked', function (event) {
            document.getElementById('note4').style.display = 'block';
            $("#note4").prop('required', true);
        });

        $('#not_find_5').on('ifChecked', function (event) {
            document.getElementById('note5').style.display = 'none';
            $("#note5").prop('required', false);
        });

        $('#find_5').on('ifChecked', function (event) {
            document.getElementById('note5').style.display = 'block';
            $("#note5").prop('required', true);
        });

        $('#not_find_6').on('ifChecked', function (event) {
            document.getElementById('note6').style.display = 'none';
            $("#note6").prop('required', false);
        });

        $('#find_6').on('ifChecked', function (event) {
            document.getElementById('note6').style.display = 'block';
            $("#note6").prop('required', true);
        });

        $('#not_find_7').on('ifChecked', function (event) {
            document.getElementById('note7').style.display = 'none';
            $("#note7").prop('required', false);
        });

        $('#find_7').on('ifChecked', function (event) {
            document.getElementById('note7').style.display = 'block';
            $("#note7").prop('required', true);
        });

        $('#not_find_8').on('ifChecked', function (event) {
            document.getElementById('note8').style.display = 'none';
            $("#note8").prop('required', false);
        });

        $('#find_8').on('ifChecked', function (event) {
            document.getElementById('note8').style.display = 'block';
            $("#note8").prop('required', true);
        });

        $('#not_find_9').on('ifChecked', function (event) {
            document.getElementById('note9').style.display = 'none';
            $("#note9").prop('required', false);
        });

        $('#find_9').on('ifChecked', function (event) {
            document.getElementById('note9').style.display = 'block';
            $("#note9").prop('required', true);
        });

        $('#not_find_10').on('ifChecked', function (event) {
            document.getElementById('note10').style.display = 'none';
            $("#note10").prop('required', false);
        });

        $('#find_10').on('ifChecked', function (event) {
            document.getElementById('note10').style.display = 'block';
            $("#note10").prop('required', true);
        });

        function add_material_file_upload() {
            var next_num = $('.sub_file_material').length;

            var html_add_item = '<div><div class="col-md-4 m-t-5 " >\n' +
                '                                                                <input type="text" class="form-control"\n' +
                '                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"\n' +
                '                                                                       name="material_note[]">\n' +
                '                                                            </div>\n' +
                '                                                            <div class="col-md-5 m-t-5 sub_file_material">\n' +
                '                                                                <input type="file" name="material_file' + next_num + '"' +
                '                                                                       class="form-control new_file_material check_max_size_file">' +
                '<input type="hidden" name="material_file_row[]">' +
                '                                                            </div>' +
                '<div class="col-md-2 m-t-5">' +
                '<a class="btn btn-small btn-danger remove_material" onclick="return false;"><span class="fa fa-trash"></span></a>' +
                '</div></div>';
            $('#material_box').append(html_add_item);
            check_max_size_file();
        }

        $("#add-material").click(function () {
            add_material_file_upload();
        });

        $(document).on('click', '.remove_material', function () {
            var row_remove = $(this).parent().parent();
            row_remove.remove();
            $('.sub_file_material').each(function (index, el) {
                var num = index
                $(el).find('.new_file_material').attr("name", "material_file" + num);
            });
        });

        $(document).on('click', '.remove_material_old', function () {
            $('.file_material_old').remove();
            $('#new_material_file').html('<div class="sub_file_material"><input type="file" class="form-control check_max_size_file"  id="file" name="material_file0"> <input type="hidden" name="material_file_row[]"></div>')
            $('.sub_file_material').each(function (index, el) {
                var num = index
                $(el).find('.new_file_material').attr("name", "material_file" + num);
            });
            check_max_size_file();
        });

        function add_test_machine_file_upload() {
            var next_num = $('.sub_file_test_machine').length;

            var html_add_item = '<div><div class="col-md-4 m-t-5 " >\n' +
                '                                                                <input type="text" class="form-control"\n' +
                '                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"\n' +
                '                                                                       name="test_machine_note[]">\n' +
                '                                                            </div>\n' +
                '                                                            <div class="col-md-5 m-t-5 sub_file_test_machine">\n' +
                '                                                                <input type="file" name="test_machine_file' + next_num + '"' +
                '                                                                       class="form-control new_file_test_machine check_max_size_file">' +
                '<input type="hidden" name="test_machine_file_row[]">' +
                '                                                            </div>' +
                '<div class="col-md-2 m-t-5">' +
                '<a class="btn btn-small btn-danger remove_test_machine" onclick="return false;"><span class="fa fa-trash"></span></a>' +
                '</div></div>';
            $('#test_machine_box').append(html_add_item);
            check_max_size_file();
        }

        $("#add-test_machine").click(function () {
            add_test_machine_file_upload();
        });

        $(document).on('click', '.remove_test_machine', function () {
            var row_remove = $(this).parent().parent();
            row_remove.remove();
            $('.sub_file_test_machine').each(function (index, el) {
                var num = index
                $(el).find('.new_file_test_machine').attr("name", "test_machine_file" + num);
            });
        });

        $(document).on('click', '.remove_test_machine_old', function () {
            $('.file_test_machine_old').remove();
            $('#new_test_machine_file').html('<div class="sub_file_test_machine"><input type="file" class="form-control check_max_size_file"  id="file" name="test_machine_file0"> <input type="hidden" name="test_machine_file_row[]"></div>')
            $('.sub_file_test_machine').each(function (index, el) {
                var num = index
                $(el).find('.new_file_test_machine').attr("name", "test_machine_file" + num);
            });
            check_max_size_file();
        });

        function add_control_standard_file_upload() {
            var next_num = $('.sub_file_control_standard').length;

            var html_add_item = '<div><div class="col-md-4 m-t-5 " >\n' +
                '                                                                <input type="text" class="form-control"\n' +
                '                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"\n' +
                '                                                                       name="control_standard_note[]">\n' +
                '                                                            </div>\n' +
                '                                                            <div class="col-md-5 m-t-5 sub_file_control_standard">\n' +
                '                                                                <input type="file" name="control_standard_file' + next_num + '"' +
                '                                                                       class="form-control new_file_control_standard check_max_size_file">' +
                '<input type="hidden" name="control_standard_file_row[]">' +
                '                                                            </div>' +
                '<div class="col-md-2 m-t-5">' +
                '<a class="btn btn-small btn-danger remove_control_standard" onclick="return false;"><span class="fa fa-trash"></span></a>' +
                '</div></div>';
            $('#control_standard_box').append(html_add_item);
            check_max_size_file();
        }

        $("#add-control_standard").click(function () {
            add_control_standard_file_upload();
        });

        $(document).on('click', '.remove_control_standard', function () {
            var row_remove = $(this).parent().parent();
            row_remove.remove();
            $('.sub_file_control_standard').each(function (index, el) {
                var num = index
                $(el).find('.new_file_control_standard').attr("name", "control_standard_file" + num);
            });
        });

        $(document).on('click', '.remove_control_standard_old', function () {
            $('.file_control_standard_old').remove();
            $('#new_control_standard_file').html('<div class="sub_file_control_standard"><input type="file" class="form-control check_max_size_file"  id="file" name="control_standard_file0"><input type="hidden" name="control_standard_file_row[]"></div>')
            $('.sub_file_control_standard').each(function (index, el) {
                var num = index
                $(el).find('.new_file_control_standard').attr("name", "control_standard_file" + num);
            });
            check_max_size_file();
        });

        function add_control_finish_file_upload() {
            var next_num = $('.sub_file_control_finish').length;

            var html_add_item = '<div><div class="col-md-4 m-t-5 " >\n' +
                '                                                                <input type="text" class="form-control"\n' +
                '                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"\n' +
                '                                                                       name="control_finish_note[]">\n' +
                '                                                            </div>\n' +
                '                                                            <div class="col-md-5 m-t-5 sub_file_control_finish">\n' +
                '                                                                <input type="file" name="control_finish_file' + next_num + '"' +
                '                                                                       class="form-control new_file_control_finish check_max_size_file">' +
                '<input type="hidden" name="control_finish_file_row[]">' +
                '                                                            </div>' +
                '<div class="col-md-2 m-t-5">' +
                '<a class="btn btn-small btn-danger remove_control_finish" onclick="return false;"><span class="fa fa-trash"></span></a>' +
                '</div></div>';
            $('#control_finish_box').append(html_add_item);
            check_max_size_file();
        }

        $("#add-control_finish").click(function () {
            add_control_finish_file_upload();
        });

        $(document).on('click', '.remove_control_finish', function () {
            var row_remove = $(this).parent().parent();
            row_remove.remove();
            $('.sub_file_control_finish').each(function (index, el) {
                var num = index
                $(el).find('.new_file_control_finish').attr("name", "control_finish_file" + num);
            });
        });

        $(document).on('click', '.remove_control_finish_old', function () {
            $('.file_control_finish_old').remove();
            $('#new_control_finish_file').html('<div class="sub_file_control_finish"><input type="file" class="form-control check_max_size_file"  id="file" name="control_finish_file0"><input type="hidden" name="control_finish_file_row[]"></div>')
            $('.sub_file_control_finish').each(function (index, el) {
                var num = index
                $(el).find('.new_file_control_finish').attr("name", "control_finish_file" + num);
            });
            check_max_size_file();
        });

        function add_control_between_file_upload() {
            var next_num = $('.sub_file_control_between').length;

            var html_add_item = '<div><div class="col-md-4 m-t-5 " >\n' +
                '                                                                <input type="text" class="form-control"\n' +
                '                                                                       placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)"\n' +
                '                                                                       name="control_between_note[]">\n' +
                '                                                            </div>\n' +
                '                                                            <div class="col-md-5 m-t-5 sub_file_control_between">\n' +
                '                                                                <input type="file" name="control_between_file' + next_num + '"' +
                '                                                                       class="form-control new_file_control_between check_max_size_file">' +
                '<input type="hidden" name="control_between_file_row[]">' +
                '                                                            </div>' +
                '<div class="col-md-2 m-t-5">' +
                '<a class="btn btn-small btn-danger remove_control_between" onclick="return false;"><span class="fa fa-trash"></span></a>' +
                '</div></div>';
            $('#control_between_box').append(html_add_item);
            check_max_size_file();
        }

        $("#add-control_between").click(function () {
            add_control_between_file_upload();
        });

        $(document).on('click', '.remove_control_between', function () {
            var row_remove = $(this).parent().parent();
            row_remove.remove();
            $('.sub_file_control_between').each(function (index, el) {
                var num = index
                $(el).find('.new_file_control_between').attr("name", "control_between_file" + num);
            });
        });

        $(document).on('click', '.remove_control_between_old', function () {
            $('.file_control_between_old').remove();
            $('#new_control_between_file').html('<div class="sub_file_control_between"><input type="file" class="form-control check_max_size_file"  id="file" name="control_between_file0"><input type="hidden" name="control_between_file_row[]"></div>')
            $('.sub_file_control_between').each(function (index, el) {
                var num = index
                $(el).find('.new_file_control_between').attr("name", "control_between_file" + num);
            });
            check_max_size_file();
        });

        $(document).ready(function() {
        $("input[name=status_res]").on("ifChanged",function(){
          status_status_res();
          });
          status_status_res();
        function status_status_res(){
              var row = $("input[name=status_res]:checked").val();
                $('#show_remark4').hide(400);
              if(row == "2"){
                $('#show_remark4').show(200);
              } else{
                $('#show_remark4').hide(400);
              }
          }
        });


    </script>
@endpush

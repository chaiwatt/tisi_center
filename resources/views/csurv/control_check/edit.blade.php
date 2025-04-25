@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/timepicker/bootstrap-timepicker.min.css?20190616')}}" rel="stylesheet">
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
                        <h3 class="box-title pull-left">ระบบบันทึกการตรวจควบคุมฯ</h3>
                        @can('view-'.str_slug('control_check'))
                           <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                           <i class="icon-arrow-left-circle"></i> กลับ
                           </a>
                       @endcan
                       <div class="clearfix"></div> 
                       <hr>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div style="border: solid 0.1em" class="p-40">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="text-center">บันทึกการตรวจควบคุมฯ</h3>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right required">ชื่อผู้รับใบอนุญาต</label>
                                                    <div class="col-md-8">
                                                        <select name="tradeName"
                                                                id="tis_standard"
                                                                class="form-control"
                                                                onclick=""
                                                                onchange="add_filter_License();remove_filter_License();add_filter_reference_num();remove_filter_reference_num()">
                                                            <option value="{{$data->tradeName}}">{{ HP::get_tb4_name_index2($data->tradeName) }}</option>
                                                            @foreach(HP::get_tb4_tradername_and_oldname() as $tbl_taxpayer=>$tbl_tradeName)
                                                                <option id="tradeName"
                                                                        value="{{$tbl_taxpayer}}">{{$tbl_tradeName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right required">มาตราฐาน</label>
                                                    <div class="col-md-7">
                                                        <select name="tbl_tisiNo"
                                                                id="tbl_tisiNo"
                                                                onclick=""
                                                                onchange="add_license();remove_license();"
                                                                class="form-control">
                                                            <option value="{{$data->tbl_tisiNo}}">{{ HP::get_tb3_tis_for_select($data->tbl_tisiNo) }}</option>
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
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ใบอนุญาต</label>
                                                    <div class="col-sm-8">
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
                                                                @if(HP::get_license_check2($data->id,$list->tbl_licenseNo)==null)
                                                                    <div class="col-sm-3"><label
                                                                                class="col-sm-12"><input
                                                                                    type="checkbox"
                                                                                    name="sub_license[]"
                                                                                    data-checkbox="icheckbox_square-green"
                                                                                    class="license_ck check"
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
                                                    <label class="col-md-2 text-right">สถานที่ตรวจ</label>
                                                    <div class="col-sm-2">
                                                        <input type="checkbox"
                                                               class="check"
                                                               name="located_check"
                                                               value="สถานที่ผลิต"
                                                               data-checkbox="icheckbox_square-green"
                                                               id="location_gen"
                                                        <?php echo ($data->located_check == 'สถานที่ผลิต') ? 'checked' : '' ?>>
                                                        <label>สถานที่ผลิต</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="checkbox"
                                                               class="check"
                                                               name="located_keep"
                                                               value="สถานที่เก็บ"
                                                               id="location_keep1"
                                                               data-checkbox="icheckbox_square-green"
                                                        <?php echo ($data->located_keep == 'สถานที่เก็บ') ? 'checked' : '' ?>>
                                                        <label>สถานที่เก็บ</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="checkbox"
                                                               class="check"
                                                               name="located_sell"
                                                               value="สถานที่จำหน่าย"
                                                               id="located_sell1"
                                                               data-checkbox="icheckbox_square-green"
                                                        <?php echo ($data->located_sell == 'สถานที่จำหน่าย') ? 'checked' : '' ?>>
                                                        <label>สถานที่จำหน่าย</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">
                                                        <label class="col-sm-2 text-right small">ตั้งอยู่เลขที่</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="address_no" class="form-control"
                                                                   value="{{$data->address_no}}">
                                                        </div>
                                                        <label class="col-sm-2 text-right small">นิคมอุตสาหกรรม<br>(ถ้ามี)</label>
                                                        <div class="col-sm-6">
                                                            <input type="text"
                                                                   name="address_industrial_estate"
                                                                   class="form-control"
                                                                   value="{{$data->address_industrial_estate}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">
                                                        <label class="col-sm-2 text-right small">หมู่ที่</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   name="address_village_no"
                                                                   class="form-control"
                                                                   value="{{$data->address_village_no}}">
                                                        </div>
                                                        <label class="col-sm-2 text-right small">ตรอก/ซอย</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   name="address_alley"
                                                                   class="form-control"
                                                                   value="{{$data->address_alley}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">ถนน</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="address_road" class="form-control"
                                                                   value="{{$data->address_road}}">
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
                                                                @if($data->address_province!='-เลือกจังหวัด-')
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
                                                                   name="address_zip_code"
                                                                   class="form-control"
                                                                   value="{{$data->address_zip_code}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรศัพท์ </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="tel" class="form-control"
                                                                   value="{{$data->tel}}">
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรสาร </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="fax" class="form-control"
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

                                                        <div class="col-sm-4 text-right">
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
                                                    <label class="col-md-2 text-right">ชื่อพนักงาน/เจ้าหน้าที่</label>
                                                    <div class="col-md-6">
                                                        {{--                                                        {!! Form::select('tradeName', HP::get_tb4_name(), '-เลือกผู้รับใบอนูญาต-', ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ได้รับใบอนูญาต-']); !!}--}}
                                                        <select name="officer_name[]"
                                                                id="officer_name"
                                                                class="select2 select2-multiple"
                                                                multiple>
                                                            @if($officer_name!=null)
                                                                @foreach($officer_name as $list_name)
                                                                    <option value="{{HP::get_people_found_old($list_name)->runrecno}}"
                                                                            selected>{{HP::get_people_found_old($list_name)->reg_fname . ' ' . HP::get_people_found_old($list_name)->reg_lname}}</option>
                                                                @endforeach
                                                                @foreach(HP::get_people_found_old_no($officer_name) as $name)
                                                                    <option value="{{$name->runrecno}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>
                                                                @endforeach
                                                            @else
                                                                @foreach(HP::get_people_found() as $name)
                                                                    <option value="{{$name->runrecno}}">{{$name->reg_fname . ' ' . $name->reg_lname}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-sm-2 text-right">วันที่ตรวจ</label>
                                                    <div class="col-sm-6">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                class="form-control pull-right"
                                                                name="checking_date"
                                                                id="datepicker-time"
                                                                value="{{ $data->checking_date }}">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>

                                                            <input type="text"
                                                                class="form-control timepicker"
                                                                name="checking_time"
                                                                value="{{$data->checking_time}}">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-clock-o"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row m-b-10 m-t-40">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">อยู่ในท้องที่ของสถานีตำรวจ</label>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" name="police_station"
                                                               value="{{$data->police_station}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-1"></label>
                                                    <label class="col-md-1 text-right">การตรวจครั้งนี้</label>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check"
                                                               name="this_checking"
                                                               value="ไม่มีเจ้าหน้าที่ตำรวจมาร่วมด้วย"
                                                               data-radio="iradio_square-green"
                                                        <?php echo ($data->this_checking == 'ไม่มีเจ้าหน้าที่ตำรวจมาร่วมด้วย') ? 'checked' : '' ?>>
                                                        <label>ไม่มีเจ้าหน้าที่ตำรวจมาร่วมด้วย</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check"
                                                               name="this_checking"
                                                               value="มีเจ้าหน้าที่แต่ไม่มีหมายค้น"
                                                               data-radio="iradio_square-green"
                                                        <?php echo ($data->this_checking == 'มีเจ้าหน้าที่แต่ไม่มีหมายค้น') ? 'checked' : '' ?>>
                                                        <label>มีเจ้าหน้าที่แต่ไม่มีหมายค้น</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check"
                                                               name="this_checking"
                                                               value="มีหมายค้นพร้อมด้วยเจ้าหน้าที่ตำรวจ"
                                                               data-radio="iradio_square-green"
                                                        <?php echo ($data->this_checking == 'มีหมายค้นพร้อมด้วยเจ้าหน้าที่ตำรวจ') ? 'checked' : '' ?>>
                                                        <label>มีหมายค้นพร้อมด้วยเจ้าหน้าที่ตำรวจ</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-1"></label>
                                                    <label class="col-md-1 text-right">การตรวจสถานที่</label>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check"
                                                               name="location_check"
                                                               id="check_located"
                                                               value="พบเจ้าของผู้ประกอบการชื่อ"
                                                               data-radio="iradio_square-green"
                                                        <?php echo ($data->location_check == 'พบเจ้าของผู้ประกอบการชื่อ') ? 'checked' : '' ?>>
                                                        <label>พบเจ้าของผู้ประกอบการชื่อ</label>
                                                    </div>
                                                    <div class="col-sm-3" id="find_name">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="remake_location_check1"
                                                               data-radio="iradio_square-green"
                                                               value="{{$data->remake_location_check}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2"></label>
                                                    <div class="col-sm-3 ">
                                                        <input type="radio"
                                                               class="check"
                                                               name="location_check"
                                                               id="not_check_located"
                                                               value="ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ"
                                                               data-radio="iradio_square-red"
                                                        <?php echo ($data->location_check == 'ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ') ? 'checked' : '' ?>>
                                                        <label>ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ</label>
                                                    </div>
                                                    <div class="col-sm-3" id="not_find_name" hidden>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="remake_location_check2"
                                                               value="{{$data->remake_location_check2}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="located_gen" hidden>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row m-b-25 m-t-10">
                                                    <div class="form-group">
                                                        <h3 class="col-md-2 text-right">รายงานการเข้าตรวจ</h3>
                                                    </div>
                                                </div>
                                                <div class="row m-b-10">
                                                    <div class="form-group">
                                                        <label class="col-md-1"></label>
                                                        <div class="col-sm-1">

                                                            <label>สถานที่ผลิต</label>
                                                        </div>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check pro"
                                                                           name="production_site"
                                                                           value="ไม่มีการทำและร่องรอยที่แสดงว่ามีการทำแต่ประการใด"
                                                                           data-radio="iradio_square-green"
                                                                    <?php echo ($data->production_site == 'ไม่มีการทำและร่องรอยที่แสดงว่ามีการทำแต่ประการใด') ? 'checked' : '' ?>>
                                                                    <label>ไม่มีการทำและร่องรอยที่แสดงว่ามีการทำแต่ประการใด</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-2"></label>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check pro"
                                                                           name="production_site"
                                                                           data-radio="iradio_square-green"
                                                                           value="ไม่มีการทำแต่มีเครื่องมือและอุปกรณ์ที่ใช้ในการทำผลิตภัณฑ์อุตสาหกรรม"
                                                                    <?php echo ($data->production_site == 'ไม่มีการทำแต่มีเครื่องมือและอุปกรณ์ที่ใช้ในการทำผลิตภัณฑ์อุตสาหกรรม') ? 'checked' : '' ?>>
                                                                    <label>ไม่มีการทำแต่มีเครื่องมือและอุปกรณ์ที่ใช้ในการทำผลิตภัณฑ์อุตสาหกรรม</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-2"></label>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check pro"
                                                                           name="production_site"
                                                                           data-radio="iradio_square-green"
                                                                           value="พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ"
                                                                    <?php echo ($data->production_site == 'พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ') ? 'checked' : '' ?>>
                                                                    <label>พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ</label>
                                                                    @if($data->production_site == 'พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ')
                                                                        <input type="text" name="police_station_value"
                                                                               class="form-control"
                                                                               value="{{$data->police_station_value}}">
                                                                    @else
                                                                        <input type="text" name="police_station_value"
                                                                               class="form-control"
                                                                               value="">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-2"></label>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check pro"
                                                                           name="production_site"
                                                                           data-radio="iradio_square-green"
                                                                           value="มีการทำผลิตภัณฑ์อุตสาหกรรมถูกต้องตามกฏหมาย"
                                                                    <?php echo ($data->production_site == 'มีการทำผลิตภัณฑ์อุตสาหกรรมถูกต้องตามกฏหมาย') ? 'checked' : '' ?>>
                                                                    <label>มีการทำผลิตภัณฑ์อุตสาหกรรมถูกต้องตามกฏหมาย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-2"></label>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check"
                                                                           name="production_site"
                                                                           id="invalid_product"
                                                                           data-radio="iradio_square-green"
                                                                           value="มีการทำผลิตภัณฑ์อุตสาหกรรมไม่ถูกต้องตามกฏหมาย คือ"
                                                                    <?php echo ($data->production_site == 'มีการทำผลิตภัณฑ์อุตสาหกรรมไม่ถูกต้องตามกฏหมาย คือ') ? 'checked' : '' ?>
                                                                    >
                                                                    <label>มีการทำผลิตภัณฑ์อุตสาหกรรมไม่ถูกต้องตามกฏหมาย
                                                                        คือ</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-3"></label>
                                                        <div class="col-md-8" id="Licensed" hidden>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-4 m-b-10">
                                                                        <input type="radio"
                                                                               class="check"
                                                                               name="product_not_legally"
                                                                               value="ไม่มีใบอนุญาต"
                                                                               data-radio="iradio_square-green"
                                                                        <?php echo ($data->product_not_legally == 'ไม่มีใบอนุญาต') ? 'checked' : '' ?>>
                                                                        <label>ไม่มีใบอนุญาต</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-5 m-b-10">
                                                                        <input type="radio"
                                                                               class="check"
                                                                               name="product_not_legally"
                                                                               data-radio="iradio_square-green"
                                                                               value="มีใบอนุญาต แต่ทำนอกเหนือจากที่ระบุไว้ในใบอนุญาต"
                                                                        <?php echo ($data->product_not_legally == 'มีใบอนุญาต แต่ทำนอกเหนือจากที่ระบุไว้ในใบอนุญาต') ? 'checked' : '' ?>>
                                                                        <label>มีใบอนุญาต
                                                                            แต่ทำนอกเหนือจากที่ระบุไว้ในใบอนุญาต</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-5 m-b-10">
                                                                        <input type="radio"
                                                                               class="check"
                                                                               data-radio="iradio_square-green"
                                                                               name="product_not_legally"
                                                                               value="มีใบอนุญาต แต่มีเหตุผลอันควรเชื่อว่าไม่เป็นมาตรฐาน"
                                                                        <?php echo ($data->product_not_legally == 'มีใบอนุญาต แต่มีเหตุผลอันควรเชื่อว่าไม่เป็นมาตรฐาน') ? 'checked' : '' ?>>
                                                                        <label>มีใบอนุญาต
                                                                            แต่มีเหตุผลอันควรเชื่อว่าไม่เป็นมาตรฐาน</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-5 m-b-10">
                                                                        <input type="radio"
                                                                               class="check"
                                                                               data-radio="iradio_square-green"
                                                                               name="product_not_legally"
                                                                               value="มีใบอนุญาต แต่ไม่แสดงเครื่องหมายมาตรฐาน"
                                                                        <?php echo ($data->product_not_legally == 'มีใบอนุญาต แต่ไม่แสดงเครื่องหมายมาตรฐาน') ? 'checked' : '' ?>>
                                                                        <label>มีใบอนุญาต
                                                                            แต่ไม่แสดงเครื่องหมายมาตรฐาน</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="located_keep" hidden>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row m-b-10 m-t-40">
                                                    <div class="form-group">
                                                        <label class="col-md-1"></label>
                                                        <div class="col-sm-10">

                                                            <label>สถานที่เก็บ/สถานที่จำหน่าย</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-b-10 ">
                                                    <div class="col-md-8">
                                                        <label class="col-md-2"></label>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check"
                                                                           name="location_keep"
                                                                           id="not_industrial_sell"
                                                                           data-radio="iradio_square-green"
                                                                           value="ไม่มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย"
                                                                    <?php echo ($data->location_keep == 'ไม่มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย') ? 'checked' : '' ?>>
                                                                    <label>ไม่มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-2"></label>
                                                        <div class="row ">
                                                            <div class="form-group">
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="radio"
                                                                           class="check"
                                                                           name="location_keep"
                                                                           id="industrial_sell"
                                                                           data-radio="iradio_square-green"
                                                                           value="มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย ดังนี้"
                                                                    <?php echo ($data->location_keep == 'มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย ดังนี้') ? 'checked' : '' ?>
                                                                    >
                                                                    <label>มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย
                                                                        ดังนี้</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <label class="col-md-3"></label>
                                                        <div class="col-md-8" id="industrial_products" hidden>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-10 m-b-10">
                                                                        <input type="radio"
                                                                               class="check freeze_and_seize"
                                                                               data-radio="iradio_square-green"
                                                                               name="product_sell"
                                                                               value="เป็นผลิตภัณฑ์อุตสาหกรรมที่ถูกต้องตามกฏหมาย"
                                                                        <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่ถูกต้องตามกฏหมาย') ? 'checked' : '' ?>>
                                                                        <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่ถูกต้องตามกฏหมาย</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-10 m-b-10">
                                                                        <input type="radio"
                                                                               class="check freeze_and_seize"
                                                                               data-radio="iradio_square-green"
                                                                               name="product_sell"
                                                                               value="เป็นผลิตภัณฑ์อุตสาหกรรมที่เชื่อได้ว่ามีไว้ก่อนกฏหมายใช้บังคับหรือผลิตภัณฑ์ชำรุด"
                                                                        <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่เชื่อได้ว่ามีไว้ก่อนกฏหมายใช้บังคับหรือผลิตภัณฑ์ชำรุด') ? 'checked' : '' ?>>
                                                                        <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่เชื่อได้ว่ามีไว้ก่อนกฏหมายใช้บังคับหรือผลิตภัณฑ์ชำรุด</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-10 m-b-10">
                                                                        <input type="radio"
                                                                               class="check freeze_and_seize"
                                                                               data-radio="iradio_square-green"
                                                                               name="product_sell"
                                                                               value="เป็นผลิตภัณฑ์อุตสาหกรรมที่มีเหตุอันควรเชื่อได้ว่าไม่เป็นไปตามกฏหมาย"
                                                                        <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่มีเหตุอันควรเชื่อได้ว่าไม่เป็นไปตามกฏหมาย') ? 'checked' : '' ?>>
                                                                        <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่มีเหตุอันควรเชื่อได้ว่าไม่เป็นไปตามกฏหมาย</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-sm-10 m-b-10">
                                                                        <input type="radio"
                                                                               class="check"
                                                                               id="freeze_and_seize2"
                                                                               data-radio="iradio_square-green"
                                                                               name="product_sell"
                                                                               value="เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย"
                                                                        <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย') ? 'checked' : '' ?>>
                                                                        <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="and_made" hidden>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label class="col-md-1"></label>
                                                                <div class="col-sm-1 ">
                                                                    <label>และได้ทำการ</label>
                                                                </div>
                                                                <div class="col-sm-1 m-b-10 ">
                                                                    <input type="checkbox"
                                                                           class="check"
                                                                           name="num_of_hold"
                                                                           id="dis_num_of_freeze"
                                                                           data-checkbox="icheckbox_square-green"
                                                                           value="ยึด จำนวน"
                                                                    <?php echo ($data->num_of_hold == 'ยึด จำนวน') ? 'checked' : '' ?>>
                                                                    <label>ยึด จำนวน</label>
                                                                </div>
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="text"
                                                                           id="num_of_freeze1"
                                                                           name="num_of_hold_value"
                                                                           class="form-control"
                                                                           value="{{$data->num_of_hold_value}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label class="col-md-2"></label>
                                                                <div class="col-sm-1 m-b-10 ">
                                                                    <input type="checkbox"
                                                                           class="check"
                                                                           name="num_of_freeze"
                                                                           data-checkbox="icheckbox_square-green"
                                                                           id="show_num_of_freeze"
                                                                           value="อายัด จำนวน"
                                                                    <?php echo ($data->num_of_freeze == 'อายัด จำนวน') ? 'checked' : '' ?>>
                                                                    <label>อายัด จำนวน</label>
                                                                </div>
                                                                <div class="col-sm-4 m-b-10">
                                                                    <input type="text"
                                                                           id="num_of_freeze2"
                                                                           name="num_of_freeze_value"
                                                                           class="form-control"
                                                                           value="{{$data->num_of_freeze_value}}"
                                                                           style="display: none">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <label class="col-md-1"></label>
                                                            <div class="col-sm-2 ">
                                                                <label>อ้างอิงบันทึกการยึด/อายัดเลขที่</label>
                                                            </div>
                                                            <div class="col-sm-2 m-b-10 ">
                                                                <select name="reference_num" class="form-control"
                                                                        id="reference_num">
                                                                    @if($data->reference_num!='เลือก')
                                                                        <option>{{$data->reference_num}}</option>
                                                                    @else
                                                                        <option>เลือก</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row m-b-25 m-t-10">
                                                <div class="form-group">
                                                    <h3 class="col-md-2 text-right">รายงานการเข้าตรวจ</h3>
                                                </div>
                                            </div>
                                            <div class="row m-b-5">
                                                <div class="form-group m-b-5">
                                                    <label class="col-md-1"></label>
                                                    <div class="col-sm-2">

                                                        <label>พนักงานเจ้าหน้าที่เคยตรวจสถานที่</label>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="form-group">
                                                            <div class="col-sm-1 m-b-5">
                                                                <input type="radio"
                                                                       class="check"
                                                                       name="officer_check"
                                                                       id="ever"
                                                                       data-radio="iradio_square-green"
                                                                       value="เคย"
                                                                <?php echo ($data->officer_check == 'เคย') ? 'checked' : '' ?>>
                                                                <label>เคย (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-1 m-b-5">
                                                                <input type="radio"
                                                                       class="check"
                                                                       name="officer_check"
                                                                       id="not_ever"
                                                                       data-radio="iradio_square-red"
                                                                       value="ไม่เคย"
                                                                <?php echo ($data->officer_check == 'ไม่เคย') ? 'checked' : '' ?>
                                                                >
                                                                <label>ไม่เคย</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="ever_never" hidden>
                                                    <div class="col-md-11 m-b-5">
                                                        <div>
                                                            <label class="col-md-1"></label>
                                                            <label class="col-md-1 text-right">จำนวนครั้ง</label>
                                                            <div class="col-sm-2">
                                                                <input type="text"
                                                                       class="form-control"
                                                                       name="num_of_time"
                                                                       value="{{$data->num_of_time}}"
                                                                       id="">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="col-md-2 text-right">ครั้งล่าสุดเมื่อวันที่</label>
                                                            <div class="col-sm-2 input-group">
                                                                <input type="text"
                                                                       class="form-control pull-right"
                                                                       name="last_time"
                                                                       id="datepicker-time2"
                                                                       value="{{ $data->last_time }}">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-md-1"></label>
                                            <div class="col-sm-10">

                                                <label>เคยได้รับการตักเตือนหรือชี้แจงเกี่ยวกับผลิตภัณฑ์ที่ไม่เป็นไปตามกฏหมายหรือไม่</label>
                                            </div>
                                        </div>
                                        <div class="row m-b-10 ">
                                            <div class="col-md-12">
                                                <label class="col-md-2"></label>
                                                <div class="row ">
                                                    <div class="form-group">
                                                        <div class="col-sm-3 m-b-10">
                                                            <input type="radio"
                                                                   class="check"
                                                                   name="ever_warning"
                                                                   id="ever_warning_law"
                                                                   data-radio="iradio_square-green"
                                                                   value="เคย"
                                                            <?php echo ($data->ever_warning == 'เคย') ? 'checked' : '' ?>>
                                                            <label>เคย (โปรดระบุ)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12" id="ever_warning" hidden>
                                                    <label class="col-md-2"></label>
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-12 m-b-5 p-l-25">
                                                                    <input type="checkbox"
                                                                            class="check"
                                                                            name="ever_warned[]"
                                                                            data-checkbox="icheckbox_square-green"
                                                                           value="ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ การทำ/การจำหน่าย ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน"
                                                                           <?php echo in_array("ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ การทำ/การจำหน่าย ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน",$ever_warned) ? 'checked' : '' ?>>
                                                                       <label>ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ
                                                                        การทำ/การจำหน่าย
                                                                        ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-10 m-b-5 p-l-25">
                                                                    <input type="checkbox"
                                                                           class="check"
                                                                           name="ever_warned[]"
                                                                           data-checkbox="icheckbox_square-green"
                                                                           value="กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย"
                                                                           <?php echo in_array("กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย",$ever_warned) ? 'checked' : '' ?>>
                                                                    <label>กำชับให้มิให้ ทำ/นำ
                                                                        ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-10 m-b-5 p-l-25">
                                                                    <input type="checkbox"
                                                                            class="check"
                                                                            name="ever_warned[]"
                                                                            data-checkbox="icheckbox_square-green"
                                                                           value="แนะนำวิธีสังเกตผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมาย"
                                                                           <?php echo in_array("แนะนำวิธีสังเกตผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมาย",$ever_warned) ? 'checked' : '' ?>>
                                                                    <label>แนะนำวิธีสังเกตผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมาย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-10 p-l-25">
                                                                    <input type="checkbox"
                                                                            class="check"
                                                                            name="ever_warned[]"
                                                                            data-checkbox="icheckbox_square-green"
                                                                             value="แจกเอกสาร"
                                                                     <?php echo in_array("แจกเอกสาร",$ever_warned) ? 'checked' : '' ?>>
                                                                    <label>แจกเอกสาร</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label class="col-md-2"></label>
                                                <div class="row ">
                                                    <div class="form-group">
                                                        <div class="col-sm-4 m-b-10">
                                                            <input type="radio"
                                                                   class="check"
                                                                   name="ever_warning"
                                                                   data-radio="iradio_square-red"
                                                                   id="not_ever_warning_law"
                                                                   value="ไม่เคย"
                                                            <?php echo ($data->ever_warning == 'ไม่เคย') ? 'checked' : '' ?>
                                                            >
                                                            <label>ไม่เคย</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row m-b-25 m-t-10">
                                                <div class="form-group">
                                                    <h3 class="col-md-2 text-right">รายงานเพิ่มเติม</h3>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-1"></label>
                                                    <div class="col-sm-10">

                                                        <label>รายละเอียดเกี่ยวกับสถานที่ที่ตรวจพบการกระทำผิด (เช่น
                                                            ตรวจพบผลิตภัณฑ์อย่างไร สถานที่ดังกล่าว ประกอบกิจการอะไร
                                                            ระยะเวลาที่ประกอบกิจการ)</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="col-md-1"></label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control"
                                                                  name="detail_location_offense"> {{$data->detail_location_offense}} </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-5">
                                                <div class="form-group">
                                                    <label class="col-md-1"></label>
                                                    <div class="col-sm-10">

                                                        <label>รายละเอียดเกี่ยวกับผลิตภัณฑ์ที่ไม่เป็นไปตามมาตรฐาน/มีเหตุอันควรเชื่อว่าไม่เป็นไปตามมาตรฐานที่พนักงานเจ้าหน้าที่ตรวจพบ</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="col-md-1"></label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control"
                                                                  name="detail_product_not_standard"> {{$data->detail_product_not_standard}} </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-5">
                                                <div class="form-group">
                                                    <label class="col-md-1"></label>
                                                    <div class="col-sm-2">

                                                        <label>หลักฐานในการซื้อขาย</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check"
                                                               name="premise"
                                                               data-radio="iradio_square-green"
                                                               id="have_evidence"
                                                               value="มี"
                                                        <?php echo ($data->premise == 'มี') ? 'checked' : '' ?>>
                                                        <label>มี (โปรดระบุ)</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check"
                                                               name="premise"
                                                               data-radio="iradio_square-red"
                                                               id="not_have_evidence"
                                                               value="ไม่มี"
                                                        <?php echo ($data->premise == 'ไม่มี') ? 'checked' : '' ?>>
                                                        <label>ไม่มี</label>
                                                    </div>
                                                </div>
                                                <div id="seller">
                                                    <div class="form-group col-md-10 m-t-10 m-b-5">
                                                        <label class="col-md-1"></label>
                                                        <label class="col-md-1 text-right">ชื่อผู้ขาย</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" name="seller_name"
                                                                   value="{{$data->seller_name}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <label class="col-md-1"></label>
                                                        <label class="col-md-1 text-right">ที่อยู่ผู้ขาย</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control"
                                                                      name="seller_address"> {{$data->seller_address}} </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row m-b-25 m-t-10">
                                                <div class="form-group">
                                                    <h3 class="col-md-2 text-right">การปฏิบัติงานครั้งนี้</h3>
                                                </div>
                                            </div>
                                            <div class="row m-b-5">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="row m-b-10 ">
                                                            <div class="col-md-12">
                                                                <label class="col-md-1"></label>
                                                                <div class="col-md-10">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-11 m-b-10">
                                                                                <input type="checkbox"
                                                                                class="check"
                                                                                name="this_operation[]"
                                                                                data-checkbox="icheckbox_square-green"
                                                                                       value="ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ การทำ/การจำหน่าย ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน"
                                                                                <?php echo in_array("ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ การทำ/การจำหน่าย ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน",$this_operation) ? 'checked' : '' ?>>
                                                                                <label>ชี้แจงข้อกฏหมายที่เกี่ยวข้องกับ
                                                                                    การทำ/การจำหน่าย
                                                                                    ผลิตภัณฑ์ที่มีพระราชกฤษฎีกากำหนดให้ต้องเป็นไปตามมาตรฐาน</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-10 m-b-10">
                                                                                <input type="checkbox"
                                                                                        class="check"
                                                                                        name="this_operation[]"
                                                                                        data-checkbox="icheckbox_square-green"
                                                                                       value="กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย"
                                                                                       <?php echo in_array("กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย",$this_operation) ? 'checked' : '' ?>>
                                                                                <label>กำชับให้มิให้ ทำ/นำ
                                                                                    ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-10 m-b-10">
                                                                                <input type="checkbox"
                                                                                        class="check"
                                                                                        name="this_operation[]"
                                                                                        data-checkbox="icheckbox_square-green"
                                                                                       value="แนะนำวิธีสังเกตผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมาย"
                                                                                       <?php echo in_array("แนะนำวิธีสังเกตผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมาย",$this_operation) ? 'checked' : '' ?>>
                                                                                <label>แนะนำวิธีสังเกตผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมาย</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-10 m-b-10">
                                                                                <input type="checkbox"
                                                                                        class="check"
                                                                                        name="this_operation[]"
                                                                                        data-checkbox="icheckbox_square-green"
                                                                                       value="แจกเอกสาร"
                                                                                       <?php echo in_array("แจกเอกสาร",$this_operation) ? 'checked' : '' ?>>
                                                                                
                                                                                <label>แจกเอกสาร</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 m-b-5">
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 text-right">บันทึกเพิ่มเติม</label>
                                                                        <div class="col-sm-6">
                                                                            <textarea name="more_notes"
                                                                                      class="form-control"
                                                                            >{{$data->more_notes}}
                                                                        </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            @if($data_file_check==null)
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-md-10 m-b-20">
                                                                                <label class="col-md-3 text-right ">ไฟล์แนบเพิ่มเติม</label>
                                                                                <div class="col-md-8">
                                                                                    <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                            name="add_upload"
                                                                                            id="add_upload"
                                                                                            onClick="return false;">
                                                                                    <span class="btn-label"><i
                                                                                                class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <div class="col-md-10 form-group">
                                                                                <div class="col-md-2"></div>
                                                                                <div class="col-sm-3">
                                                                                    <input type="text"
                                                                                           name="remark_file[]"
                                                                                           placeholder="คำอธิบาย"
                                                                                           class="form-control"
                                                                                    >
                                                                                </div>
                                                                                <div class="col-sm-5 sub_file">
                                                                                    <input type="file"
                                                                                           name="file0"
                                                                                           id="file0"
                                                                                           class="form-control check_max_size_file"
                                                                                    >
                                                                                    <input type="text"
                                                                                           name="num_row_file[]"
                                                                                           hidden>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-2"></label>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-md-10 m-b-20">
                                                                                <label class="col-md-3 text-right ">ไฟล์แนบเพิ่มเติม</label>
                                                                                <div class="col-md-8">
                                                                                    <button class="btn btn-success btn-sm waves-effect waves-light"
                                                                                            name="add_upload"
                                                                                            id="add_upload"
                                                                                            onClick="return false;">
                                                                                    <span class="btn-label"><i
                                                                                                class="fa fa-plus"></i></span><b>เพิ่ม</b>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @for($j = 0 ; $j < count($data_file) ; $j++)
                                                                            <div class="form-group">
                                                                                <div class="col-md-10 form-group">
                                                                                    <div class="col-md-2"></div>
                                                                                    <div class="col-sm-3">
                                                                                        <input type="text"
                                                                                               name="remark_file[]"
                                                                                               placeholder="คำอธิบาย"
                                                                                               class="form-control"
                                                                                               value="{{$data_file[$j]->remark_file}}"
                                                                                        >
                                                                                    </div>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="form-control">
                                                                                            @if($data_file[$j]->file !='' && HP::checkFileStorage($attach_path.$data_file[$j]->file))
                                                                                                <a disabled  href="{{ HP::getFileStorage($attach_path.$data_file[$j]->file) }}" target="_blank" >
                                                                                                {{ $data_file[$j]->file }}
                                                                                                </a>
                                                                                          @endif
                                                                                            {{-- <a href="{{url('/csurv/control_check/download/'.$data_file[$j]->file)}}">{{$data_file[$j]->file}}</a> --}}
                                                                                        </div>
                                                                                        <input type="text"
                                                                                               id="file_old0"
                                                                                               name="file_old[]"
                                                                                               value="{{$data_file[$j]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <a class="btn btn-small btn-danger remove"
                                                                                       onclick="return false;"><span
                                                                                                class="fa fa-trash"></span></a>
                                                                                </div>
                                                                            </div>
                                                                        @endfor

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div id="file_upload"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @if(isset($data->status_history) && $data->status_history != null)
                        @php
                         $status_history =  json_decode($data->status_history);
                        $status =   ['0' => 'ฉบับร่าง',  '1' => 'อยู่ระหว่าง ผก.รับรอง','2' => 'ผก.รับรองแล้ว', '3' => 'อยู่ระหว่าง ผอ.รับรอง','4' => 'ผอ.รับรองแล้ว',  '5' => 'ปรับปรุงแก้ไข']; 
                        $person =   ['0' => 'ผู้บันทึกร่าง','1' => 'ผู้ส่งรายงาน','2' => 'ผู้ตรวจประเมิน','3' => 'ผู้ตรวจประเมิน','4' => 'ผู้ตรวจประเมิน','5' => 'ผู้ตรวจประเมิน']; 
                         $User = App\User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS titels"),'runrecno AS id') ->pluck('titels','id');
                        @endphp
                        <div class="row form-group">
                            <div class="col-md-12">
                                <fieldset style="border:#cccccc solid 0.1em" class="p-40">
                                    <legend><h3>ประวัติการประเมินตรวจควบคุมฯ</h3></legend> 
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
                                                       @if($item->poao_approve != null)
                                                        <label for="#" class="control-label col-md-1"></label>
                                                        <div class="control-label col-md-11">
                                                            <label for="#" class="control-label col-md-2">
                                                                   ประเมินผลการตรวจ
                                                            </label>
                                                            <div class="control-label col-md-10">
                                                                <strong>
                                                                        @if($item->poao_approve == 1)
                                                                           เห็นชอบและโปรดดำเนินการต่อไป
                                                                        @else 
                                                                            {{ 'อื่นๆ'.@$item->poao_approve_text}}
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



                        <div class="row form-group">
                            <div class="col-md-12" id="">
                                <fieldset style="border: solid 0.1em" class="p-40">
                                    <legend><h3>ประเมินผลการตรวจควบคุม</h3></legend>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row m-b-5">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="row m-b-10 ">
                                                            <div class="col-md-10 m-b-10">
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 text-right required">การดำเนินการ</label>
                                                                        <div class="col-sm-3">
                                                                            {!! Form::select('operation',
                                                                            ['1' => 'ไม่ดำเนินการใดๆ',
                                                                             '2' => 'ส่งให้กองกฏหมายดำเนินการ'],
                                                                            !empty($data->operation) ? $data->operation : null, 
                                                                            ['class' => 'form-control',
                                                                            'required'=>'required',
                                                                            'placeholder'=>'-เลือกการดำเนินการ-']) !!} 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 m-b-10">
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 text-right">ผู้ตรวจประเมิน
                                                                            :</label>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   name=""
                                                                                   class="form-control"
                                                                                   value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                                   disabled>
                                                                            <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                                   name="check_officer"
                                                                                   hidden>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10 m-b-20">
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="col-md-2 text-right">วันที่ตรวจประเมิน
                                                                            :</label>
                                                                        <div class="col-sm-3">
                                                                            <input type="text"
                                                                                   name=""
                                                                                   class="form-control"
                                                                                   value="{{date("m/d/Y")}}"
                                                                                   disabled>
                                                                            <input value="{{date("m/d/Y")}}"
                                                                                   name="date_now"
                                                                                   hidden>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="status_btn"></div>
                                                            <div class="col-md-12 ">
                                                                <div class="row">
                                                                    <div class="form-group text-center">
                                                                        <button class="btn bg-primary btn-sm waves-effect waves-light m-r-30"
                                                                                type="submit"
                                                                                onclick="add_status_btn('1')">
                                                                            <i class="fa fa-send"></i><b>ส่งรายงาน</b>
                                                                        </button>
                                                                        <button class="btn btn-info btn-sm waves-effect waves-light m-r-30"
                                                                                type="submit"
                                                                                onclick="add_status_btn('0')"><b>บันทึกร่าง</b>
                                                                        </button>

                                                                        <a class="btn btn-default btn-sm waves-effect waves-light"
                                                                           href="{{ url("$previousUrl") }}">
                                                                            <b>ยกเลิก</b>
                                                                        </a>
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
                            </div>
                        </div>
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
                markers.setMap(null);

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
                marker.setMap(null);
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

    <script src="{{ asset('plugins/components/timepicker/bootstrap-timepicker.min.js') }}"></script>
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
            add_filter_reference_num()
            @if($data->located_check=='สถานที่ผลิต')
            $('#located_gen').show();
            @endif
            @if($data->located_keep=='สถานที่เก็บ')
            $('#located_keep').show();
            @endif
            @if($data->located_sell=='สถานที่จำหน่าย')
            $('#located_keep').show();
            @endif
            @if($data->production_site=='มีการทำผลิตภัณฑ์อุตสาหกรรมไม่ถูกต้องตามกฏหมาย คือ')
            $('#Licensed').show();
            @endif
            @if($data->location_keep=='มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย ดังนี้')
            $('#industrial_products').show();
            @endif
            @if($data->location_check==='พบเจ้าของผู้ประกอบการชื่อ')
            $('#find_name').show();
            $('#not_find_name').hide();
            @endif
            @if($data->location_check==='ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ')
            $('#find_name').hide();
            $('#not_find_name').show();
            @endif
            @if($data->product_sell=='เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย')
            $('#and_made').show();
            @endif
            @if($data->num_of_hold=='ยึด จำนวน')
            document.getElementById('num_of_freeze1').style.display = 'block'
            @endif
            @if($data->num_of_freeze=='อายัด จำนวน')
            document.getElementById('num_of_freeze2').style.display = 'block'
            @endif
            @if($data->premise=='มี')
            $('#seller').show();
            @endif
            @if($data->premise=='ไม่มี')
            $('#seller').hide();
            @endif
            @if($data->officer_check == 'เคย')
            $('#ever_never').show();
            @endif
            @if($data->ever_warning == 'เคย')
            $('#ever_warning').show();
            @endif
            @if($data->address_province!=0)
            add_filter_address_province()
            @endif
            @if($data->address_amphoe!=0)
            add_filter_address_amphoe()
            @endif
        }

        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="status" value="' + status + '" hidden>');
        }

        function show_num_of_freeze() {
            var checkBox = document.getElementById("num_of_freeze");
            if (checkBox.checked == true) {
                document.getElementById('num_of_freeze2').style.display = 'block'
            } else {
                document.getElementById('num_of_freeze2').style.display = 'none'
            }
        }

        function dis_num_of_freeze() {
            var checkBox = document.getElementById("num_of_hold");
            if (checkBox.checked == true) {
                document.getElementById('num_of_freeze1').style.display = 'block'
            } else {
                document.getElementById('num_of_freeze1').style.display = 'none'
            }
        }

        function show_map() {
            $('#modal-default').modal('show')
            // get_map()
        }

        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_check/update')}}",
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
                            window.location.href = "{{url('/csurv/control_check')}}";
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

        function add_filter_reference_num() {
            var data_val = $("#tis_standard :selected").val();
            $.ajax({
                type: "GET",
                url: "{{url('/csurv/control_check/add_filter_reference_num')}}",
                datatype: "html",
                data: {
                    tb3_Tisno: data_val,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    var response = data;
                    var list = response.data;
                    var opt;
                    opt += "<option>เลือก</option>"
                    $.each(list, function (key, val) {
                        opt += "<option id=\"reference_num\" value='" + val.auto_id_doc + "'>" + val.auto_id_doc + "</option>"
                    });
                    $("#reference_num").html(opt);
                }
            });
        }

        function remove_filter_reference_num() {
            $('#reference_num').empty()
        }

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

        $('#check_all').on('ifChecked', function (event) {
            $('.license_ck').iCheck('check');
        });
        $('#check_all').on('ifUnchecked', function (event) {
            $('.license_ck').iCheck('uncheck');
        });

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

        $('#datepicker-time').datepicker({
            dateFormat: 'dd/mm/yy',
            autoclose: true
        });

        $('#datepicker-time2').datepicker({
            dateFormat: 'dd/mm/yy',
            autoclose: true
        });

        $('.timepicker').timepicker({
            showInputs: false
        });

        $('#show_num_of_freeze').on('ifChecked', function (event) {
            document.getElementById('num_of_freeze2').style.display = 'block'
        });
        $('#show_num_of_freeze').on('ifUnchecked', function (event) {
            document.getElementById('num_of_freeze2').style.display = 'none'
        });

        $('#dis_num_of_freeze').on('ifChecked', function (event) {
            document.getElementById('num_of_freeze1').style.display = 'block'
        });
        $('#dis_num_of_freeze').on('ifUnchecked', function (event) {
            document.getElementById('num_of_freeze1').style.display = 'none'
        });

        $('#check_located').on('ifChecked', function (event) {
            $('#find_name').show();
            $('#not_find_name').hide();
        });

        $('#not_check_located').on('ifChecked', function (event) {
            $('#not_find_name').show();
            $('#find_name').hide();
        });

        $('#location_gen').on('ifChecked', function (event) {
            $('#located_gen').show();
        });
        $('#location_gen').on('ifUnchecked', function (event) {
            $('#located_gen').hide();
        });

        $('#location_keep1').on('ifChecked', function (event) {
            $('#located_keep').show();
        });
        $('#location_keep1').on('ifUnchecked', function (event) {
            var checkBox2 = document.getElementById("located_sell1");
            if (checkBox2.checked == true) {
                $('#located_keep').show();
            } else {
                $('#located_keep').hide();
            }
        });

        $('#located_sell1').on('ifChecked', function (event) {
            $('#located_keep').show();
        });
        $('#located_sell1').on('ifUnchecked', function (event) {
            var checkBox2 = document.getElementById("location_keep1");
            if (checkBox2.checked == true) {
                $('#located_keep').show();
            } else {
                $('#located_keep').hide();
            }
        });

        $('#freeze_and_seize2').on('ifChecked', function (event) {
            $('#and_made').show();
        });
        $('.freeze_and_seize').on('ifChecked', function (event) {
            $('#and_made').hide();
        });

        $('#ever').on('ifChecked', function (event) {
            $('#ever_never').show();
        });
        $('#not_ever').on('ifChecked', function (event) {
            $('#ever_never').hide();
        });

        $('#invalid_product').on('ifChecked', function (event) {
            $('#Licensed').show();
        });
        $('.pro').on('ifChecked', function (event) {
            $('#Licensed').hide();
        });

        $('#industrial_sell').on('ifChecked', function (event) {
            $('#industrial_products').show();
        });
        $('#not_industrial_sell').on('ifChecked', function (event) {
            $('#industrial_products').hide();
        });

        $('#have_evidence').on('ifChecked', function (event) {
            $('#seller').show();
        });
        $('#not_have_evidence').on('ifChecked', function (event) {
            $('#seller').hide();
        });

        $('#ever_warning_law').on('ifChecked', function (event) {
            $('#ever_warning').show();
        });
        $('#not_ever_warning_law').on('ifChecked', function (event) {
            $('#ever_warning').hide();
        });

        function add_file_upload() {
            var next_num = $('.sub_file').length;

            // var html_add_item = '<div class="sub_file col-md-5">';
            var html_add_item = '<div class="form-group">' +
                '                                                                        <div class="col-md-10 form-group">' +
                '                                                                            <div class="col-md-2"></div>' +
                '                                                                            <div class="col-sm-3">' +
                '                                                                                <input type="text"' +
                '                                                                                       name="remark_file[]"' +
                '                                                                                       placeholder="คำอธิบาย"' +
                '                                                                                       class="form-control "' +
                '                                                                                >' +
                '                                                                            </div>' +
                '                                                                            <div class="col-sm-5 sub_file">' +
                '                                                                                <input type="file"' +
                '                                                                                       name="file' + next_num + '"' +
                '                                                                                       class="form-control sub_file_num check_max_size_file"' +
                '                                                                                >' +
                '                                                                            </div>' +
                '                                                                                <input type="text"' +
                '                                                                                       name="num_row_file[]"' +
                '                                                                                       hidden>' +
                '<a class="btn btn-small btn-danger remove" onclick="return false;">' + '<span class="fa fa-trash"></span>' + '</a>' +
                '                                                                        </div>' +
                '                                                                    </div>';
            $('#file_upload').append(html_add_item);
            check_max_size_file();
        }

        $("#add_upload").click(function () {
            add_file_upload();
        });

        $(document).on('click', '.remove', function () {
            var row_remove = $(this).parent();
            row_remove.remove();
            $('.sub_file').each(function (index, el) {
                $(el).find('.sub_file_num').attr("name", "file" + index);
            });
        });

    </script>
@endpush

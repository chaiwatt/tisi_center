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
                                                    <label class="col-md-2 text-right">ชื่อผู้รับใบอนุญาต</label>
                                                    <div class="col-md-8">
                                                        {{--                                                        {!! Form::select('tradeName', HP::get_tb4_name(), '-เลือกผู้รับใบอนูญาต-', ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ได้รับใบอนูญาต-']); !!}--}}
                                                        <select disabled name="tradeName"
                                                                id="tis_standard"
                                                                class="form-control"
                                                                onchange="add_filter_License();remove_filter_License()">
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
                                                        {{--                                                        {!! Form::select('tbl_tradeName', HP::get_tb4_name(), '-เลือกผเลขมาตรฐาน/ชื่อผลิตภัณฑ์-', ['class' => 'form-control', 'placeholder'=>'-เลือกผู้ได้รับใบอนูญาต-']); !!}--}}
                                                        <select disabled name="tbl_tisiNo" id="tbl_tisiNo"
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
                                            <div class="row  m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">ใบอนุญาต</label>
                                                    <div class="col-sm-8">
                                                        <input type="checkbox" name="check_all" id="check_all" disabled
                                                               class="check check-readonly" data-checkbox="icheckbox_square-green"
                                                               checked>
                                                        <label>เลือกทั้งหมด</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div>
                                                    <label class="col-md-2"></label>
                                                    <div class="col-sm-10 p-0">
                                                        <div class="row col-sm-12 p-0" id="license">
                                                            @foreach($data_permission as $list_permission)
                                                                <div class="col-sm-3"><label class="col-sm-12"><input
                                                                                type="checkbox"
                                                                                name="sub_license[]"
                                                                                class=" check check-readonly"
                                                                                disabled
                                                                                data-checkbox="icheckbox_square-green"
                                                                                value="{{$list_permission->license}}"
                                                                                checked> {{$list_permission->license}}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row  m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2 text-right">สถานที่ตรวจ</label>
                                                    <div class="col-sm-2">
                                                        <input type="checkbox"
                                                               class="check check-readonly"
                                                               name="located_check"
                                                               value="สถานที่ผลิต"
                                                               id="location_gen"
                                                               data-checkbox="icheckbox_square-green"
                                                               disabled
                                                        <?php echo ($data->located_check == 'สถานที่ผลิต') ? 'checked' : '' ?>>
                                                        <label>สถานที่ผลิต</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="checkbox"
                                                               class="check check-readonly"
                                                               name="located_keep"
                                                               value="สถานที่เก็บ"
                                                               id="location_keep1"
                                                               data-checkbox="icheckbox_square-green"
                                                               disabled
                                                        <?php echo ($data->located_keep == 'สถานที่เก็บ') ? 'checked' : '' ?>>
                                                        <label>สถานที่เก็บ</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="checkbox"
                                                               class="check check-readonly"
                                                               name="located_sell"
                                                               value="สถานที่จำหน่าย"
                                                               id="located_sell1"
                                                               data-checkbox="icheckbox_square-green"
                                                               disabled
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
                                                                   value="{{$data->address_no}}" disabled>
                                                        </div>
                                                        <label class="col-sm-2 text-right small">นิคมอุตสาหกรรม<br>(ถ้ามี)</label>
                                                        <div class="col-sm-6">
                                                            <input type="text"
                                                                   name="address_industrial_estate"
                                                                   class="form-control"
                                                                   value="{{$data->address_industrial_estate}}"
                                                                   disabled>
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
                                                                   value="{{$data->address_village_no}}"
                                                                   disabled>
                                                        </div>
                                                        <label class="col-sm-2 text-right small">ตรอก/ซอย</label>
                                                        <div class="col-sm-2">
                                                            <input type="text"
                                                                   name="address_alley"
                                                                   class="form-control"
                                                                   value="{{$data->address_alley}}"
                                                                   disabled>
                                                        </div>
                                                        <label class="col-sm-2 text-right small">ถนน</label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="address_road" class="form-control"
                                                                   value="{{$data->address_road}}" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <div class="col-md-11">
                                                        <label class="col-sm-2 text-right small">จังหวัด</label>
                                                        <div class="col-sm-2">
                                                            <select disabled
                                                                    name="address_province"
                                                                    id="address_province"
                                                                    class="form-control"
                                                                    onchange="add_filter_address_province();remove_filter_address_province()">
                                                                @if($data->address_province!='-เลือกจังหวัด-')
                                                                    <option value="{{$data->address_province}}">{{HP::gat_province($data->address_province)}}</option>
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
                                                            <select disabled
                                                                    name="address_amphoe"
                                                                    id="address_amphoe"
                                                                    class="form-control"
                                                                    onchange="add_filter_address_amphoe();remove_filter_address_amphoe()">
                                                                @if($data->address_amphoe!=0)
                                                                    <option value="{{$data->address_amphoe}}">{{HP::gat_amphur($data->address_amphoe)}}</option>
                                                                @else
                                                                    <option>-เลือกอำเภอ/เขต-</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">ตำบล/แขวง </label>
                                                        <div class="col-sm-2">
                                                            <select disabled
                                                                    name="address_district"
                                                                    id="address_district"
                                                                    class="form-control">
                                                                @if($data->address_district!=0)
                                                                    <option value="{{$data->address_district}}">{{HP::gat_district($data->address_district)}}</option>
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
                                                                   value="{{$data->address_zip_code}}"
                                                                   disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรศัพท์ </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="tel" class="form-control"
                                                                   value="{{$data->tel}}" disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">โทรสาร </label>
                                                        <div class="col-sm-2">
                                                            <input type="text" name="fax" class="form-control"
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
                                                            <input type="number"
                                                                   step=any
                                                                   class="form-control"
                                                                   name="latitude" id="lat"
                                                                   value="{{$data->latitude}}" disabled>
                                                        </div>

                                                        <label class="col-sm-2 text-right small">พิกัดที่ตั้ง
                                                            (ลองจิจูด)</label>
                                                        <div class="col-sm-2">
                                                            <input type="number"
                                                                   step=any
                                                                   class="form-control"
                                                                   name="Longitude"
                                                                   id="lng"
                                                                   value="{{$data->Longitude}}" disabled>
                                                        </div>

                                                        <div class="col-sm-4 text-right">
                                                            <button class="btn btn-default" onclick="show_map();"
                                                                    disabled>
                                                                ค้นหาจากแผนที่
                                                            </button>
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
                                                                        <div id="mapCanvas"
                                                                             style="height: 400px;"></div>
                                                                        <div id="infoPanel">
                                                                            <b>สถานะ Marker :</b>
                                                                            <div id="markerStatus"><i>คลิ๊กและลาก
                                                                                    Mark.</i></div>
                                                                            <b>ตำแหน่งปัจจุบัน:</b>
                                                                            <div id="info"></div>
                                                                        </div>
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
                                                                multiple disabled>
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
                                                                value="{{$data->checking_date}}"
                                                                disabled>
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>

                                                            <input type="text"
                                                                class="form-control timepicker"
                                                                name="checking_time"
                                                                value="{{$data->checking_time}}"
                                                                disabled>
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
                                                               value="{{$data->police_station}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-1"></label>
                                                    <label class="col-md-1 text-right">การตรวจครั้งนี้</label>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check check-readonly"
                                                               name="this_checking"
                                                               data-radio="iradio_square-green"
                                                               value="ไม่มีเจ้าหน้าที่ตำรวจมาร่วมด้วย"
                                                               disabled
                                                        <?php echo ($data->this_checking == 'ไม่มีเจ้าหน้าที่ตำรวจมาร่วมด้วย') ? 'checked' : '' ?>>
                                                        <label>ไม่มีเจ้าหน้าที่ตำรวจมาร่วมด้วย</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check check-readonly"
                                                               name="this_checking"
                                                               data-radio="iradio_square-green"
                                                               value="มีเจ้าหน้าที่แต่ไม่มีหมายค้น"
                                                               disabled
                                                        <?php echo ($data->this_checking == 'มีเจ้าหน้าที่แต่ไม่มีหมายค้น') ? 'checked' : '' ?>>
                                                        <label>มีเจ้าหน้าที่แต่ไม่มีหมายค้น</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check check-readonly"
                                                               name="this_checking"
                                                               data-radio="iradio_square-green"
                                                               value="มีหมายค้นพร้อมด้วยเจ้าหน้าที่ตำรวจ"
                                                               disabled
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
                                                               class="check check-readonly"
                                                               name="location_check"
                                                               id="check_located"
                                                               data-radio="iradio_square-green"
                                                               value="พบเจ้าของผู้ประกอบการชื่อ"
                                                               disabled
                                                        <?php echo ($data->location_check == 'พบเจ้าของผู้ประกอบการชื่อ') ? 'checked' : '' ?>>
                                                        <label>พบเจ้าของผู้ประกอบการชื่อ</label>
                                                    </div>
                                                    <div class="col-sm-3" id="find_name">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="remake_location_check1"
                                                               data-radio="iradio_square-green"
                                                               value="{{$data->remake_location_check}}"
                                                               disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-b-10">
                                                <div class="form-group">
                                                    <label class="col-md-2"></label>
                                                    <div class="col-sm-3 ">
                                                        <input type="radio"
                                                               class="check check-readonly"
                                                               name="location_check"
                                                               data-radio="iradio_square-green"
                                                               id="not_check_located"
                                                               value="ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ"
                                                               disabled
                                                        <?php echo ($data->location_check == 'ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ') ? 'checked' : '' ?>>
                                                        <label>ไม่พบเจ้าของผู้ประกอบการแต่พบบุคคลชื่อ</label>
                                                    </div>
                                                    <div class="col-sm-3" id="not_find_name" hidden>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="remake_location_check2"
                                                               value="{{$data->remake_location_check2}}"
                                                               disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="located_gen" hidden>
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
                                                                       class="check check-readonly"
                                                                       name="production_site"
                                                                       value="ไม่มีการทำและร่องรอยที่แสดงว่ามีการทำแต่ประการใด"
                                                                       id="pro"
                                                                       data-radio="iradio_square-green"
                                                                       disabled
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
                                                                       class="check check-readonly"
                                                                       name="production_site"
                                                                       id="pro"
                                                                       data-radio="iradio_square-green"
                                                                       value="ไม่มีการทำแต่มีเครื่องมือและอุปกรณ์ที่ใช้ในการทำผลิตภัณฑ์อุตสาหกรรม"
                                                                       disabled
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
                                                                       class="check check-readonly"
                                                                       name="production_site"
                                                                       id="pro"
                                                                       data-radio="iradio_square-green"
                                                                       value="พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ"
                                                                       disabled
                                                                <?php echo ($data->production_site == 'พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ') ? 'checked' : '' ?>>
                                                                <label>พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ</label>
                                                                @if($data->production_site == 'พร้อมด้วยผลิตภัณฑ์อุตสาหกรรมจำนวนประมาณ')
                                                                    <input type="text" name="police_station_value"
                                                                           class="form-control"
                                                                           value="{{$data->police_station_value}}" disabled>
                                                                @else
                                                                    <input type="text" name="police_station_value"
                                                                           class="form-control"
                                                                           value="" disabled>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <label class="col-md-2"></label>
                                                    <div class="row ">
                                                        <div class="form-group">
                                                            <div class="col-sm-4 m-b-10">
                                                                <input type="radio"
                                                                       class="check check-readonly"
                                                                       name="production_site"
                                                                       id="pro"
                                                                       data-radio="iradio_square-green"
                                                                       value="มีการทำผลิตภัณฑ์อุตสาหกรรมถูกต้องตามกฏหมาย"
                                                                       disabled
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
                                                                       class="check check-readonly"
                                                                       data-radio="iradio_square-green"
                                                                       name="production_site"
                                                                       id="invalid_product"
                                                                       value="มีการทำผลิตภัณฑ์อุตสาหกรรมไม่ถูกต้องตามกฏหมาย คือ"
                                                                       disabled
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
                                                                           class="check check-readonly"
                                                                           data-radio="iradio_square-green"
                                                                           name="product_not_legally"
                                                                           value="ไม่มีใบอนุญาต"
                                                                           disabled
                                                                    <?php echo ($data->product_not_legally == 'ไม่มีใบอนุญาต') ? 'checked' : '' ?>>
                                                                    <label>ไม่มีใบอนุญาต</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-5 m-b-10">
                                                                    <input type="radio"
                                                                           name="product_not_legally"
                                                                           data-radio="iradio_square-green"
                                                                           class="check check-readonly"
                                                                           value="มีใบอนุญาต แต่ทำนอกเหนือจากที่ระบุไว้ในใบอนุญาต"
                                                                           disabled
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
                                                                           class="check check-readonly"
                                                                           name="product_not_legally"
                                                                           data-radio="iradio_square-green"
                                                                           value="มีใบอนุญาต แต่มีเหตุผลอันควรเชื่อว่าไม่เป็นมาตรฐาน"
                                                                           disabled
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
                                                                           class="check check-readonly"
                                                                           name="product_not_legally"
                                                                           data-radio="iradio_square-green"
                                                                           value="มีใบอนุญาต แต่ไม่แสดงเครื่องหมายมาตรฐาน"
                                                                           disabled
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

                                    <div class="row" id="located_keep" hidden>
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
                                                                       name="location_keep"
                                                                       class="check check-readonly"
                                                                       data-radio="iradio_square-green"
                                                                       id="not_industrial_sell"
                                                                       value="ไม่มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย"
                                                                       disabled
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
                                                                       data-radio="iradio_square-green"
                                                                       class="check check-readonly"
                                                                       name="location_keep"
                                                                       id="industrial_sell"
                                                                       value="มีผลิตภัณฑ์อุตสาหกรรมไว้เพื่อจำหน่าย ดังนี้"
                                                                       disabled
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
                                                                           class="check check-readonly"
                                                                           data-radio="iradio_square-green"
                                                                           name="product_sell"
                                                                           value="เป็นผลิตภัณฑ์อุตสาหกรรมที่ถูกต้องตามกฏหมาย"
                                                                           disabled
                                                                    <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่ถูกต้องตามกฏหมาย') ? 'checked' : '' ?>>
                                                                    <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่ถูกต้องตามกฏหมาย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-10 m-b-10">
                                                                    <input type="radio"
                                                                           class="check check-readonly"
                                                                           data-radio="iradio_square-green"
                                                                           name="product_sell"
                                                                           value="เป็นผลิตภัณฑ์อุตสาหกรรมที่เชื่อได้ว่ามีไว้ก่อนกฏหมายใช้บังคับหรือผลิตภัณฑ์ชำรุด"
                                                                           disabled
                                                                    <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่เชื่อได้ว่ามีไว้ก่อนกฏหมายใช้บังคับหรือผลิตภัณฑ์ชำรุด') ? 'checked' : '' ?>>
                                                                    <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่เชื่อได้ว่ามีไว้ก่อนกฏหมายใช้บังคับหรือผลิตภัณฑ์ชำรุด</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-10 m-b-10">
                                                                    <input type="radio"
                                                                           class="check check-readonly"
                                                                           data-radio="iradio_square-green"
                                                                           name="product_sell"
                                                                           value="เป็นผลิตภัณฑ์อุตสาหกรรมที่มีเหตุอันควรเชื่อได้ว่าไม่เป็นไปตามกฏหมาย"
                                                                           disabled
                                                                    <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่มีเหตุอันควรเชื่อได้ว่าไม่เป็นไปตามกฏหมาย') ? 'checked' : '' ?>>
                                                                    <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่มีเหตุอันควรเชื่อได้ว่าไม่เป็นไปตามกฏหมาย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-sm-10 m-b-10">
                                                                    <input type="radio"
                                                                           class="check check-readonly"
                                                                           data-radio="iradio_square-green"
                                                                           name="product_sell"
                                                                           value="เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย"
                                                                           disabled
                                                                    <?php echo ($data->product_sell == 'เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย') ? 'checked' : '' ?>>
                                                                    <label>เป็นผลิตภัณฑ์อุตสาหกรรมที่ไม่เป็นไปตามกฏหมาย</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label class="col-md-1"></label>
                                                        <div class="col-sm-1 ">
                                                            <label>และได้ทำการ</label>
                                                        </div>
                                                        <div class="col-sm-1 m-b-10 ">
                                                            <input type="checkbox"
                                                                   class="check check-readonly"
                                                                   data-checkbox="icheckbox_square-green"
                                                                   name="num_of_hold"
                                                                   id="dis_num_of_freeze"
                                                                   value="ยึด จำนวน"
                                                                   disabled
                                                            <?php echo ($data->num_of_hold == 'ยึด จำนวน') ? 'checked' : '' ?>>
                                                            <label>ยึด จำนวน</label>
                                                        </div>
                                                        <div class="col-sm-4 m-b-10">
                                                            <input type="text"
                                                                   id="num_of_freeze1"
                                                                   name="num_of_hold_value"
                                                                   class="form-control"
                                                                   value="{{$data->num_of_hold_value}}"
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="col-md-2"></label>
                                                        <div class="col-sm-1 m-b-10 ">
                                                            <input type="checkbox"
                                                                   class="check check-readonly"
                                                                   name="num_of_freeze"
                                                                   data-checkbox="icheckbox_square-green"
                                                                   id="show_num_of_freeze"
                                                                   value="อายัด จำนวน"
                                                                   disabled
                                                            <?php echo ($data->num_of_freeze == 'อายัด จำนวน') ? 'checked' : '' ?>>
                                                            <label>อายัด จำนวน</label>
                                                        </div>
                                                        <div class="col-sm-4 m-b-10">
                                                            <input type="text"
                                                                   id="num_of_freeze2"
                                                                   name="num_of_freeze_value"
                                                                   class="form-control"
                                                                   value="{{$data->num_of_freeze_value}}"
                                                                   disabled
                                                                   style="display: none">
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
                                                            <select name="reference_num" class="form-control" disabled>
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
                                                                  name="detail_location_offense" disabled
                                                        > {{$data->detail_location_offense}} </textarea>
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
                                                                  name="detail_product_not_standard" disabled
                                                        > {{$data->detail_product_not_standard}} </textarea>
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
                                                               class="check check-readonly"
                                                               name="premise"
                                                               data-radio="iradio_square-green"
                                                               id="have_evidence"
                                                               value="มี"
                                                               disabled
                                                        <?php echo ($data->premise == 'มี') ? 'checked' : '' ?>>
                                                        <label>มี (โปรดระบุ)</label>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input type="radio"
                                                               class="check check-readonly"
                                                               name="premise"
                                                               data-radio="iradio_square-green"
                                                               id="not_have_evidence"
                                                               value="ไม่มี"
                                                               disabled
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
                                                                   value="{{$data->seller_name}}" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-10">
                                                        <label class="col-md-1"></label>
                                                        <label class="col-md-1 text-right">ที่อยู่ผู้ขาย</label>
                                                        <div class="col-sm-8">
                                                            <textarea class="form-control"
                                                                      name="seller_address"
                                                                      disabled> {{$data->seller_address}} </textarea>
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
                                                                       class="check check-readonly"
                                                                       name="officer_check"
                                                                       id="ever"
                                                                       data-radio="iradio_square-green"
                                                                       value="เคย"
                                                                       disabled
                                                                <?php echo ($data->officer_check == 'เคย') ? 'checked' : '' ?>>
                                                                <label>เคย (โปรดระบุ)</label>
                                                            </div>
                                                            <div class="col-sm-1 m-b-5">
                                                                <input type="radio"
                                                                       class="check check-readonly"
                                                                       name="officer_check"
                                                                       id="not_ever"
                                                                       data-radio="iradio_square-green"
                                                                       value="ไม่เคย"
                                                                       disabled
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
                                                                       disabled>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="col-md-2 text-right">ครั้งล่าสุดเมื่อวันที่</label>
                                                            <div class="col-sm-2 input-group">
                                                                <input type="text"
                                                                       class="form-control pull-right"
                                                                       name="last_time"
                                                                       id="datepicker-time2"
                                                                       value="{{$data->last_time}}"
                                                                       disabled>
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
                                                                   class="check check-readonly"
                                                                   name="ever_warning"
                                                                   data-radio="iradio_square-green"
                                                                   id="ever_warning_law"
                                                                   value="เคย"
                                                                   disabled
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
                                                                            class="check check-readonly"
                                                                            disabled
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
                                                                           class="check check-readonly"
                                                                           disabled
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
                                                                            class="check check-readonly"
                                                                            disabled
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
                                                                            class="check check-readonly"
                                                                            disabled
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
                                                                   class="check check-readonly"
                                                                   data-radio="iradio_square-green"
                                                                   name="ever_warning"
                                                                   id="not_ever_warning_law"
                                                                   value="ไม่เคย"
                                                                   disabled
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
                                                    <h3 class="col-md-2 text-right">การปฏิบัติงานครั้งนี้</h3>
                                                </div>
                                            </div>
                                            <div class="row m-b-5">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="row m-b-10 ">
                                                            <div class="col-md-12">
                                                                <label class="col-md-1"></label>
                                                                <div class="col-md-11">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-11 m-b-10">
                                                                                <input type="checkbox"
                                                                                class="check check-readonly"
                                                                                 disabled
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
                                                                                class="check check-readonly"
                                                                                disabled
                                                                                data-checkbox="icheckbox_square-green"
                                                                                value="กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย"
                                                                               <?php echo in_array("กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย",$this_operation) ? 'checked' : '' ?>>
                                                                                <label>กำชับให้มิให้ ทำ/นำ ผลิตภัณฑ์ที่ไม่เป็นไปตามกฎหมายมาจำหน่าย</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <div class="col-sm-10 m-b-10">
                                                                                <input type="checkbox"
                                                                                class="check check-readonly"
                                                                                disabled
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
                                                                                        class="check check-readonly"
                                                                                        disabled
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
                                                                                      id=""
                                                                                      class="form-control"
                                                                                      disabled
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
                                                                                    <button disabled
                                                                                            class="btn btn-success btn-sm waves-effect waves-light"
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
                                                                                           class="form-control" disabled
                                                                                    >
                                                                                </div>
                                                                                <div class="col-sm-5">
                                                                                    <input type="file"
                                                                                           name="file0"
                                                                                           id="file0"
                                                                                           class="form-control" disabled
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
                                                                                            id="add_upload" disabled
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
                                                                                               disabled
                                                                                               value="{{$data_file[$j]->remark_file}}"
                                                                                        >
                                                                                    </div>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="form-control"
                                                                                             disabled>
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
                                                                                               disabled
                                                                                               value="{{$data_file[$j]->file}}"
                                                                                               hidden>
                                                                                    </div>
                                                                                    <a class="btn btn-small btn-danger remove"
                                                                                       disabled
                                                                                       onclick="return false;"><span
                                                                                                class="fa fa-trash"></span></a>
                                                                                </div>
                                                                            </div>
                                                                        @endfor

                                                                    </div>
                                                                </div>
                                                            @endif

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
                                                                        <label class="col-md-3 text-right">การดำเนินการ :</label>
                                                                        <div class="col-sm-6">
                                                                            {!! Form::select('operation',
                                                                            ['1' => 'ไม่ดำเนินการใดๆ',
                                                                             '2' => 'ส่งให้กองกฏหมายดำเนินการ'],
                                                                            !empty($data->operation) ? $data->operation : null, 
                                                                            ['class' => 'form-control',
                                                                            'disabled'=> true,
                                                                            'placeholder'=>'-เลือกการดำเนินการ-']) !!} 
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
                                                                                   value="{{$data->check_officer}}"
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
                                                                        <label class="col-md-3 text-right">วันที่ตรวจประเมิน :</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text"
                                                                                   name=""
                                                                                   class="form-control"
                                                                                   value="{{$data->date_now}}"
                                                                                   disabled>
                                                                            <input value="{{date("m/d/Y")}}"
                                                                                   name="date_now"
                                                                                   hidden>
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
                                </fieldset>
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
                        

                        @can('poko_approve-'.str_slug('control_check'))

                        @if($data->status == 1)
                        <div class="row form-group">
                            <div class="col-md-12" id="">
                                <fieldset style="border: solid 0.1em" class="p-40">
                                    <legend><h3> สำหรับ ผก. รับรอง</h3></legend>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-4 ">
                                                        <input type="radio"
                                                               name="poko_approve"
                                                               value="1"
                                                               class="col-sm-1 check"
                                                               data-radio="iradio_square-green"
                                                               id="not_show_poko_remark"
                                                               checked>
                                                        <label>เห็นชอบและโปรดดำเนินการต่อไป</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-4 ">
                                                        <input type="radio"
                                                               name="poko_approve"
                                                               value="2"
                                                               class="col-sm-1 check"
                                                               id="show_poko_remark"
                                                               data-radio="iradio_square-green"
                                                        >
                                                        <label>อื่นๆ</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display: none" id="show_textarea_poko_remark">
                                                <div class="row">
                                                    <div class="form-group m-b-10 col-md-5">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-sm-4 ">
                                                            <textarea name="poko_approve_text"
                                                                      id="add_poko_remark" cols="100"
                                                                      rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10 m-b-10">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-md-1"></label>
                                                        <label class="col-md-2 text-right">ผู้ตรวจประเมิน :</label>
                                                        <div class="col-sm-5">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                   disabled>
                                                            <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                   name="poko_assessor" type="hidden">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10 m-b-40">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-md-1"></label>
                                                        <label class="col-md-2 text-right">วันที่ตรวจประเมิน
                                                            :</label>
                                                        <div class="col-sm-5">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   value="{{date("d/m/Y")}}"
                                                                   disabled>
                                                            <input value="{{date("Y-m-d")}}" name="poko_approve_date" type="hidden">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-4 ">
                                                        <input type="checkbox" name="send_to_poao" id="send_to_poao"
                                                        class="check " data-checkbox="icheckbox_square-red" value="y">
                                                        <label>ส่งให้ ผอ. ดำเนินการ</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="status_btn"></div>
                                            <div class="col-md-12 ">
                                                <div class="row">
                                                    <div class="form-group text-center">
                                                        <button class="btn bg-primary btn-sm waves-effect waves-light m-r-30"
                                                                type="submit" >
                                                            <i class="fa fa-send"></i>
                                                            <b>ส่ง</b>
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
                                </fieldset>
                            </div>
                        </div>
                        @endif

                        @endcan

                        @can('poao_approve-'.str_slug('control_check'))

                        @if($data->status == 3)
                        <div class="row form-group">
                            <div class="col-md-12" id="">
                                <fieldset style="border: solid 0.1em" class="p-40">
                                    <legend><h3> สำหรับ ผอ. รับรอง</h3></legend>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-4 ">
                                                        <input type="radio"
                                                               name="poao_approve"
                                                               value="1"
                                                               class="col-sm-1 check"
                                                               data-radio="iradio_square-green"
                                                               id="not_show_poao_remark"
                                                               checked>
                                                        <label>เห็นชอบและโปรดดำเนินการต่อไป</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group m-b-10">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-sm-4 ">
                                                        <input type="radio"
                                                               name="poao_approve"
                                                               value="2"
                                                               class="col-sm-1 check"
                                                               id="show_poao_remark"
                                                               data-radio="iradio_square-green"
                                                        >
                                                        <label>อื่นๆ</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display: none" id="show_textarea_poao_remark">
                                                <div class="row">
                                                    <div class="form-group m-b-10 col-md-5">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-sm-4 ">
                                                            <textarea name="poao_approve_text"
                                                                      id="add_poao_remark" cols="100"
                                                                      rows="5"></textarea>
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
                                                                   class="form-control"
                                                                   value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                   disabled>
                                                            <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                                                   name="poao_assessor" type="hidden">
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
                                                                   class="form-control"
                                                                   value="{{date("m/d/Y")}}"
                                                                   disabled>
                                                            <input value="{{date("Y-m-d")}}" name="poao_approve_date" type="hidden">
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
                                                                onclick="add_status_btn('อยู่ระหว่าง ผอ. รับรอง')">
                                                            <i class="fa fa-send"></i>
                                                            <b>ส่ง</b>
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
                                </fieldset>
                            </div>
                        </div>
                        @endif
                        @endcan

                        @if($data->status == 4 || ($data->status == 2 &&  $data->poko_approve == 1))
                            <a  href="{{ url("$previousUrl") }}">
                                <div class="alert alert-dark text-center" role="alert">
                                    <i class="fa fa-close"></i>
                                    <b>กลับ</b>
                                </div>
                            </a> 
                        @endif

                      

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')

    <script src="{{asset('plugins/components/timepicker/bootstrap-timepicker.min.js')}}"></script>
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
            @if($data->poko_approve == '2')
            document.getElementById('show_textarea_poko_remark').style.display = 'block';
            @endif
            @if($data->poao_approve == '2')
            document.getElementById('show_textarea_poao_remark').style.display = 'block';
            @endif
        };

        $('#not_show_poao_remark').on('ifChecked', function (event) {
            document.getElementById('show_textarea_poao_remark').style.display = 'none';
            $("#add_poao_remark").prop('required', false);
        });

        $('#show_poao_remark').on('ifChecked', function (event) {
            document.getElementById('show_textarea_poao_remark').style.display = 'block';
            $("#add_poao_remark").prop('required', true);
        });

        $('#not_show_poko_remark').on('ifChecked', function (event) {
            document.getElementById('show_textarea_poko_remark').style.display = 'none';
            $("#add_poko_remark").prop('required', false);
        });

        $('#show_poko_remark').on('ifChecked', function (event) {
            document.getElementById('show_textarea_poko_remark').style.display = 'block';
            $("#add_poko_remark").prop('required', true);
        });

        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="status" value="' + status + '" hidden>');
        }

        $('#form_data').on('submit', function (event) {
            event.preventDefault();
            var form_data = new FormData(this);

            $.ajax({
                type: "POST",
                url: "{{url('/csurv/control_check/update_status')}}",
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

    </script>
@endpush

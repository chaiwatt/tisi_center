@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css"/>
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
            padding: 20px;
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
                            <h1 class="box-title">แก้ไขใบรับ - นำส่งตัวอย่าง</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <form id="form_data" method="post" enctype="multipart/form-data">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <fieldset class="row">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="form-group m-b-10">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2 text-right"> มาตรฐาน :</div>
                                        <div class="col-md-9">
                                            <input name="example_id" value="<?=$data->id?>" hidden>
                                                <select name="tis_standard" class="form-control"
                                                    onclick="add_filter_tb4_License(this);" disabled>
                                                <option value="<?=$data->tis_standard?>">{{'มอก. '. $data->tis_standard. ' ' .$data->tis->tb3_TisThainame}}</option>
                                                @foreach(HP::TisListSample() as $tb3_Tisno=>$name)
                                                    <option id="tis_standard" value="{{$tb3_Tisno}}">{{$name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-b-10">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2 text-right"> ผู้รับใบอนุญาต :</div>
                                        <div class="col-sm-9">
                                            <select name="licensee" id="filter_tb4_License" class="form-control" disabled>
                                                <option>{{$data->licensee}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-b-10">
                                    <div class="col-sm-12">
                                        <div class="col-sm-2 text-right"> ใบอนุญาต :</div>
                                        <div class="col-sm-9">
                                            <select name="licensee_no" id="filter_tb4_License_no" class="form-control" disabled>
                                                <option>{{$data->licensee_no}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-b-5">
                                    <h5><b>รายละเอียดตัวอย่างผลิตภัณฑ์อุตสาหกรรม</b></h5>
                                </div>

                                <div class="table-responsive">
                                    <table class="table color-bordered-table primary-bordered-table" id="myTable">
                                        <thead>
                                        <tr>
                                            <th style="width: 3%; color: white">รายการที่</th>
                                            <th style="width: 30% ; color: white">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                                            <th style="width: 8% ; color: white">จำนวน</th>
                                            <th style="width: 10%; color: white">หมายเลขตัวอย่าง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data_detail as $key => $list_detail)
                                        <tr class="sub_detail_sample">
                                            <td><input type="hidden" value="{{$key+1}}"
                                                       name="num_row[]"/><span class="running-no"> {{$key+1}}.  </span>
                                            </td>
                                            <td><p align="left">{!! HP::map_lap_sizedetail($list_detail->detail_volume) !!}</p></td>
                                            <td>{{$list_detail->number}}</td>
                                            <td><input name="unit[]" value="{{$list_detail->unit}}" hidden>
                                                <div>{{$list_detail->num_ex}}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div id="sum_row" class="col-md-4"></div>
                                <div id="detail">
                                    @foreach($data_detail as $key => $list_detail)
                                        <input type="text" id="detail_volume{{$key}}" name="detail_volume[]"
                                               value="{{$list_detail->detail_volume}}" hidden>
                                    @endforeach
                                </div>
                            </div>
                            <br>

                            <div class="form-group m-b-5">
                                <div class="col-sm-6" >
                                    <h5><b>รูปแบบการตรวจ</b>&emsp;
                                        <span>
                                            <input type="radio" name="type_save" value="all" disabled <?php echo ($data->type_send == 'all') ? 'checked' : '' ?>> ทุกรายการทดสอบ
                                            <input type="radio" name="type_save" value="some" disabled <?php echo ($data->type_send == 'some') ? 'checked' : '' ?>>  บางรายการทดสอบ
                                        </span>
                                    </h5>
                                </div>
                            </div>

                            <div class="table-responsive">
                                    <table class="table color-bordered-table primary-bordered-table" id="myTable2">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%; color: white">ลำดับที่</th>
                                            <th style="width: 10%; color: white">ชื่อหน่วยตรวจสอบ</th>
                                            <th style="width: 20%; color: white">รายการตรวจ</th>
                                            <th style="width: 22%; color: white">รายการทดสอบ</th>
                                            <th style="width: 10%; color: white">เลขที่ใบนำส่งตัวอย่าง</th>
                                            <th style="width: 8%; color: white">สถานะ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data_lap as $key => $data_laps)
                                            <tr class="sub_detail_sample">
                                                <td style="vertical-align: text-top;"><input name="wsk_row[]" value="1" hidden><span class="running-no"> {{$key+1}}.</span></td>
                                                <td style="vertical-align: text-top;">
                                                    <select class="form-control" id="wksselect1" name="wksselect1" disabled>
                                                        <option>{{$data_laps->name_lap}}</option>
                                                    </select>
                                                </td>
                                                <td colspan="2">
                                                    <?php
                                                        $data_lap_detail = DB::table( 'save_example_map_lap' )->where('no_example_id', $data_laps->no_example_id)->get();
                                                    ?>
                                                    @foreach($data_lap_detail as $key => $details)
                                                        <div class="row">

                                                            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                                                {{-- รายการตรวจ --}}
                                                                <p align="left">
                                                                    <input type="checkbox" checked disabled> {!! HP::map_lap_sizedetail($details->detail_product_maplap) !!}
                                                                </p><br>
                                                            </div>
                                                            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                                                {{-- {!!HP::map_lap_detail($details->id, $data_laps->example_id)!!} --}}
                                                                {{-- รายการทดสอบ --}}
                                                                @foreach($data_laps->details as $detail)
                                                                    @php $test_item = $detail->test_item; @endphp
                                                                    @if(!is_null($test_item))
                                                                        @php $parent = $test_item->test_item_parent; @endphp
                                                                        @if(is_null($parent))
                                                                            <div>{{ $test_item->no.' '.$test_item->title }}</div>
                                                                        @else
                                                                            <div>
                                                                                {{ $test_item->no.' '.$test_item->title }}
                                                                                (ภายใต้ {{ $parent->no.' '.$parent->title }})
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach

                                                            </div>
                                                            
                                                        </div>
                                                    @endforeach

                                                </td>

                                                <td style="vertical-align: text-top;">{{$data_laps->no_example_id}}</td>
                                                <td style="vertical-align: text-top;">{{HP::map_lap_status($data_laps->status)}}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div id="wkslist"></div>

                                </div>

                                <div class="form-group m-b-10">
                                    <div class="col-sm-9">
                                        <div class="col-sm-3"> รายละเอียดเพิ่มเติม :</div>
                                        <div class="col-sm-9">
                                            <textarea name="more_details" cols="55" rows="5" disabled>{!! $data->more_details !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <fieldset class="row">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="form-group m-b-10">
                                    <div class="col-sm-8">
                                        <label class="col-sm-3 text-right"> การตรวจสอบ : </label>
                                        <div class="col-md-5">
                                            <input type="radio"
                                                   class="col-sm-1 checked_radio"
                                                   name="verification"
                                                   value="ตรวจสอบที่หน่วยตรวจสอบ"
                                                   disabled
                                            <?php echo ($data->verification == 'ตรวจสอบที่หน่วยตรวจสอบ') ? 'checked' : '' ?>>
                                            <label> ตรวจสอบที่หน่วยตรวจสอบ </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-8">
                                        <label class="col-sm-3 text-right"></label>
                                        <div class="col-md-5">
                                            <input type="radio"
                                                   class="col-sm-1 checked_radio"
                                                   name="verification"
                                                   value="ตรวจสอบที่โรงงาน"
                                                   disabled
                                            <?php echo ($data->verification == 'ตรวจสอบที่โรงงาน') ? 'checked' : '' ?>>
                                            <label> ตรวจสอบที่โรงงาน </label>
                                        </div>
                                    </div>
                                </div>
                                @if($data->verification == 'ตรวจสอบที่หน่วยตรวจสอบ')
                                <div id="sample_delivery" class="form-group">
                                    <div class="form-group">
                                        <div class="col-sm-8 m-b-10">
                                            <label class="col-sm-3 text-right"> การนำส่งตัวอย่าง : </label>
                                            <div class="col-md-7">
                                                <input type="radio" class="col-sm-1" name="sample_submission"
                                                       <?php echo ($data->sample_submission == 'ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง') ? 'checked' : '' ?>
                                                       value="ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง" disabled>
                                                <label> ผู้ยื่นคำขอ/ผู้รับใบอนุญาต นำส่งตัวอย่าง </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 m-b-10">
                                            <label class="col-sm-3 text-right"></label>
                                            <div class="col-md-7">
                                                <input type="radio" class="col-sm-1" name="sample_submission"
                                                       <?php echo ($data->sample_submission == 'กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง') ? 'checked' : '' ?>
                                                       value="กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง" disabled>
                                                <label> กลุ่มหน่วยตรวจสอบ กอ. นำส่งตัวอย่าง </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                            <p class="col-sm-2"></p>
                                            <label class="col-sm-3 text-right"> โดยเก็บตัวอย่างไว้ที่ </label>
                                            <input type="radio" class="col-sm-1" name="stored_add" disabled
                                                   value="โรงงาน" <?php echo ($data->stored_add == 'โรงงาน') ? 'checked' : '' ?>>
                                            <label class="col-sm-1"> โรงงาน </label>
                                            <input type="radio" class="col-sm-1" name="stored_add" disabled
                                                   value="สมอ. ห้อง" <?php echo ($data->stored_add == 'สมอ. ห้อง') ? 'checked' : '' ?>>
                                            <label class="col-sm-2"> สมอ. ห้อง </label>
                                            <div class="input-group col-sm-2">
                                                <input type="text" class="form-control pull-right" name="room_anchor" disabled
                                                       value="{{$data->room_anchor}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <p class="center" style="text-align: justify; padding: 10px; border: 2px solid black; line-height: 40px; width: 980px">
                                        ตามเงื่อนไขที่ผู้รับใบอนุญาตต้องปฏิบัติ ตามมาตรา 25 ทวิ สำนักงานขอแจ้งให้ท่านนำส่งตัวอย่างพร้อมชำระค่าใช้จ่ายในการตรวจสอบ<br>
                                        ที่หน่วยตรวจสอบตามที่ระบุไว้ ในใบรับ-นำส่งตัวอย่างนี้ ภายใน 15 วัน นับจากวันที่เก็บตัวอย่าง
                                    </p>
                                </div>

                                <div class="form-group m-b-10">
                                    <div class="col-sm-6">
                                        <div class="col-sm-4">วันที่เก็บตัวอย่าง :</div>
                                        <div class="input-group date col-sm-7">
                                            <input type="text" class="form-control pull-right datepicker"
                                                   name="sample_submission_date" id="datepicker-time"
                                                   value="{{$data->sample_submission_date}}" disabled>
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-10">
                                    <div class="col-sm-6">
                                        <div class="col-sm-4">ผู้จ่ายตัวอย่าง :</div>
                                        <div class="input-group col-sm-7">
                                            <input type="text" class="form-control pull-right" name="sample_pay"
                                                   value="{{$data->sample_pay}}" disabled>
                                        </div>
                                    </div>
                                     <div class="col-sm-6" >
                                        <div class="col-sm-4">ตำแหน่ง :</div>
                                        <div class="input-group col-sm-8">
                                            <input type="text" class="form-control pull-right" name="permission_submiss"
                                                   value="{{$data->permission_submiss}}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-10">
                                    <div class="col-sm-6" >
                                        <div class="col-sm-4">เบอร์โทรศัพท์ :</div>
                                        <div class="input-group col-sm-7">
                                            <input type="text" class="form-control pull-right" name="tel_submiss"
                                                   value="{{$data->tel_submiss}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-4">Email :</div>
                                        <div class="input-group col-sm-8">
                                            <input type="email" class="form-control pull-right" name="email_submiss"
                                                   value="{{$data->email_submiss}}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-10">
                                    <div class="col-sm-6" >
                                        <div class="col-sm-4">ผู้รับตัวอย่าง :</div>
                                        <div class="input-group col-sm-7">
                                            <input type="text" class="form-control pull-right" name="sample_recipient"
                                                   value="{{$data->sample_recipient}}" disabled>
                                        </div>
                                    </div>
                                     <div class="col-sm-6">
                                        <div class="col-sm-4"> ตำแหน่ง :</div>
                                        <div class="input-group col-sm-8">
                                            <input type="text" class="form-control pull-right"
                                                   name="permission_receive" value="{{$data->permission_receive}}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group m-b-10">
                                    <div class="col-sm-6">
                                        <div class="col-sm-4"> เบอร์โทรศัพท์ :</div>
                                        <div class="input-group col-sm-7">
                                            <input type="text" class="form-control pull-right" name="tel_receive"
                                                   value="{{$data->tel_receive}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-4"> Email :</div>
                                        <div class="input-group col-sm-8">
                                            <input type="email" class="form-control pull-right" name="email_receive"
                                                   value="{{$data->email_receive}}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-7">
                                        <label class="col-sm-4"> การรับคืนตัวอย่าง : </label>
                                        <input type="radio" name="sample_return" value="ไม่รับคืน" disabled
                                               class="col-md-1" <?php echo ($data->sample_return == 'ไม่รับคืน') ? 'checked' : '' ?> >
                                        <label class="col-md-2"> ไม่รับคืน </label>
                                        <input type="radio" name="sample_return" value="รับคืน" disabled
                                               class="col-md-1" <?php echo ($data->sample_return == 'รับคืน') ? 'checked' : '' ?> >
                                        <label class="col-md-2"> รับคืน </label>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-7">
                                        <label class="col-sm-3">ไฟล์แนบกลับ : </label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="single_attach" class="check_max_size_file">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                        </div>
                                        {!! $errors->first('show_manufacturer_image', '<p class="help-block">:message</p>') !!}
                                    </div>

                                    <div class="col-md-5">
                                        @if($single_attach->file_name!="" && HP::checkFileStorage($attach_path.$single_attach->file_name))
                                        <a href="{{ HP::getFileStorage($attach_path.$single_attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                        {{ $single_attach->file_client_name }}
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <div id="status_btn"></div>
                        <br>
                        <div align="center">
                            <a class="btn btn-primary btn-outline btn-lg waves-effect waves-light"
                                href="{{ url('/ssurv/save_example/export_word').'/'.$data->id }}" target="_blank">
                                <i class="fa fa-file-word-o"></i>
                                <b>พิมพ์</b>
                            </a>
                            <button class="btn btn-info btn-lg waves-effect waves-light" type="submit">
                                <i class="fa fa-save"></i>
                                <b>บันทึก</b>
                            </button>
                            {{-- <a class="btn btn-success btn-lg waves-effect waves-light"
                               href="{{ url('/ssurv/save_example/print').'/'.$data->id }}" target="_blank">
                                <i class="fa fa-print"></i>
                                <b>พิมพ์</b>
                            </a> --}}
                            <a class="btn btn-default btn-lg waves-effect waves-light"
                               href="{{ url('ssurv/save_example') }}">
                                <i class="fa fa-undo"></i>
                                <b>ยกเลิก</b>
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <input id="tis_no" value="{{$data->tis_standard}}" hidden>
        <input id="verification" value="{{$data->verification}}" hidden>
        @endsection

        @push('js')
            <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
            <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

            <!-- input file -->
            <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>

            <!-- input calendar thai -->
            <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
            <!-- thai extension -->
            <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
            <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

            <script type="text/javascript">
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var num_row2 = 1;
                var add_maplap_edit1 = 1;
                var add_maplap_edit2 = 1;
                var add_maplap_edit3 = 1;
                var num_row_onload = $('.sub_detail_sample').length;

                $('.datepicker').datepicker({language:'th-th',format:'dd/mm/yyyy'})


                window.onload = function () {
                    // $('#sum_row').html('<label class="col-md-1"> รวม </label><div class="col-md-3"><input id="sum_row_val" class="form-control" type="text" name="sum" style="text-align: center" value="' + num_row_onload + '" disabled><input id="sum_row_val" type="text" name="sum" value="' + num_row_onload + '" hidden></div><label class="col-md-3"> ชุดตัวอย่าง </label>');
                    // add_filter_tb4_License($('#tis_no').val())
                }
                function goBack() {
                    window.history.back();
                }
                $('#form_data').submit(function (event) {
                    event.preventDefault();
                    var form_data = new FormData(this);
                    $.ajax({
                        type: "POST",
                        url: "{{url('/ssurv/save_example/save_attach')}}",
                        datatype: "script",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            console.log(data);
                            if (data.status == "success") {
                                window.location.reload();
                            } else if (data.status == "error") {
                                alert(data.message);
                            } else {
                                alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                            }
                        }
                    });
                });

                function remove_example_detail(id) {
                    if (confirm('ยืนยันการลบข้อมูลออกจากฐานข้อมูล ?') === true) {
                        $.ajax({
                            type: "POST",
                            url: "{{url('/ssurv/save_example/delete_detail')}}",
                            datatype: "html",
                            data: {
                                id: id,
                                '_token': "{{ csrf_token() }}",
                            },
                            success: function () {
                                window.location.reload();
                            }
                        });
                    }
                }

                var temp_count_filter_head = 0;
                var wkslist = $('.wkslist_old').length + $('.wkslist_new').length;
                var temp_unit = 0;
                var temp_row = $('.sub_detail_sample').length + 1;
                var temp_detail = $('.sub_detail_sample').length;

                function add_detail_sample() {
                    var count_error = 0;
                    if (temp_count_filter_head != 0) {
                        for (check = 0; check <= temp_count_filter_head; check++) {
                            var input2 = $('#filter_list' + temp_fliter_list[check]).val().split('|');
                            if (input2[0] == "เลือก") {
                                count_error += check;
                            }
                            if ($('#input_detail_' + check).val() != undefined) {
                                if ($('#input_detail_' + check).val() == '') {
                                    count_error += check;
                                }
                            }
                        }
                    }

                    if (count_error != 0) {
                        alert("กรุณากรอกข้อมูลให้ครบ");
                    } else {
                        if (temp_count_filter_head != 0) {
                            var next_num = $('.sub_detail_sample').length + 1;
                            var html_add_item = '<tr class="sub_detail_sample">';
                            var add_detail;
                            var name;
                            var count_temp = 0;
                            $('#sum_row').html('<label> รวม <input type="text" name="sum" style="width: 20%; text-align: center" value="' + temp_row + '" disabled><input type="text" name="sum" style="width: 20%; text-align: center" value="' + temp_row + '" hidden> ชุดตัวอย่าง </label>');
                            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row[]"/><span class="running-no">' + next_num + '</span>.</td><td>';
                            if ($('#filter_type').val() != undefined) {
                                var filter_type = $('#filter_type').val().split('|')
                                html_add_item += '<label>' + filter_type[1] + ' ' + '</lable>';
                                name += $('#filter_type').val() + ' ';
                            }
                            for (check = 0; check <= temp_count_filter_head; check++) {
                                var input2 = $('#filter_list' + temp_fliter_list[check]).val().split('|');
                                if ($('#input_detail_' + check).val() != undefined) {
                                    if ($('#input_detail2_' + count_temp).val() != undefined) {
                                        html_add_item += '<label>' + $('#input1_' + check).text() + ' ' + $('#input_detail_' + check).val() + '-' + $('#input_detail2_' + count_temp).val() + ' ' + $('#type_input' + count_temp).val() + '</label>' + '  ';
                                    } else if ($('#type_select' + count_temp).val() != undefined) {
                                        html_add_item += '<label>' + $('#input1_' + check).text() + ' ' + $('#input_detail_' + check).val() + ' ' + $('#type_select' + count_temp).val() + '</label>' + '  ';
                                    } else {
                                        html_add_item += '<label>' + $('#input1_' + check).text() + ' ' + $('#input_detail_' + check).val() + ' ' + $('#type_input' + count_temp).text() + '</label>' + '  ';
                                    }
                                } else {
                                    html_add_item += '<label>' + $('#input1_' + check).text() + ' ' + input2[0] + '</label>' + '  ';
                                }
                                if ($('#input_detail_' + check).val() != undefined) {
                                    if ($('#input_detail2_' + count_temp).val() != undefined) {
                                        name += $('#input1_' + check).text() + ' ' + $('#input_detail_' + check).val() + '-' + $('#input_detail2_' + count_temp).val() + ' ' + $('#type_input' + count_temp).val() + '  ';
                                    } else if ($('#type_select' + count_temp).val() != undefined) {
                                        name += $('#input1_' + check).text() + ' ' + $('#input_detail_' + check).val() + ' ' + $('#type_select' + count_temp).val() + '  ';
                                    } else {
                                        name += $('#input1_' + check).text() + ' ' + $('#input_detail_' + check).val() + ' ' + $('#type_input' + count_temp).text() + '  ';
                                    }
                                    count_temp++;
                                } else {
                                    name += $('#input1_' + check).text() + ' ' + input2[0] + '  ';
                                }
                            }
                            var name_t = name.substring(9);
                            html_add_item += '</td><td><input type="text" class="form-control text-center" name="number[]"></td>';
                            html_add_item += '<td ><input name="unit[]" value="' + temp_unit_name + '" hidden><div style="text-align: center">' + temp_unit_name + '</div></td>';
                            html_add_item += '<td ><input type="text" class="form-control text-center" name="num_ex[]"></td>';
                            html_add_item += '<td><a class="btn btn-small btn-danger remove-sample" onclick="remove_row(' + temp_row + ',' + temp_detail + ')"><span class="fa fa-trash"></span></a></td>';
                            html_add_item += '</tr>';
                            add_detail = '<input type="text" id="detail_volume' + temp_detail + '" name="detail_volume[]" value="' + name_t + '" hidden>';
                            $('#detail').append(add_detail);
                            $('#myTable tbody').append(html_add_item);
                            temp_unit++;
                            temp_detail++;
                            temp_row++
                            wkslist++;


                            var li = $('<div id="row1_' + next_num + '"><input type="checkbox" name="wkslist_list1[]" id="wkslist_list_' + wkslist + '" onclick="hide_other(this,' + wkslist + ');" value="' + name_t + '"/>' +
                                '<label for="' + wkslist + '"></label></div>');
                            li.find('label').text(name_t);
                            $('#wkslist1').append(li);

                            var li = $('<div id="row2_' + next_num + '"><input type="checkbox" name="wkslist_list2[]" id="wkslist2_list_' + wkslist + '" onclick="hide_other2(this,' + wkslist + ');" value="' + name_t + '"/>' +
                                '<label for="' + wkslist + '"></label></div>');
                            li.find('label').text(name_t);
                            $('#wkslist2').append(li);

                            var li = $('<div id="row3_' + next_num + '"><input type="checkbox" name="wkslist_list3[]" id="wkslist3_list_' + wkslist + '" onclick="hide_other3(this,' + wkslist + ');" value="' + name_t + '"/>' +
                                '<label for="' + wkslist + '"></label></div>');
                            li.find('label').text(name_t);
                            $('#wkslist3').append(li);

                            $('#wkslist').append('<input name="wkslist[]" value="' + wkslist + '" hidden>');
                        } else {
                            alert("ไม่มีข้อมูล");
                        }
                    }
                }

                function remove_row(row, temp_detail) {
                    $('#detail_volume' + temp_detail).remove();
                    temp_row--;
                    $('#sum_row_val').val(temp_row - 1)
                    var num = temp_row - 1;
                    $('#sum_row').html('<label> รวม <input id="sum_row_val" type="text" name="sum" style="width: 20%; text-align: center" value="' + num + '" disabled><input id="sum_row_val" type="text" name="sum"  value="' + num + '" hidden> ชุดตัวอย่าง </label>');
                    $('#row1_' + row).remove();
                    $('#row2_' + row).remove();
                    $('#row3_' + row).remove();
                }

                function hide_other(item, list) {
                    //
                    if ($('#wkslist_list_' + list).is(':checked')) {
                        $('#wkslist2_list_' + list).closest('div').hide();
                        $('#wkslist3_list_' + list).closest('div').hide();
                        $('#wksid1').html('<label>Auto</label>')
                        $('#wksstate1').html('<label>Auto</label>')

                    } else {
                        $('#wkslist2_list_' + list).closest('div').show();
                        $('#wkslist3_list_' + list).closest('div').show();
                        $('#wksid1').empty();
                        $('#wksstate1').empty();
                    }
                    var check_wks = 0;
                    var check_wks2 = 0;
                    for (var i = 1; i <= wkslist; i++) {
                        if ($('#wkslist_list_' + i).is(':checked')) {
                            check_wks += 1;
                        }
                        if ($('#wkslist_list_' + i).is(':checked') || $('#wkslist2_list_' + i).is(':checked')) {
                            check_wks2 += 1;
                        }
                    }
                    if (wkslist == check_wks) {
                        $('#wksselect2').prop('disabled', true);
                        $('#wksselect3').prop('disabled', true);
                    } else {
                        $('#wksselect2').prop('disabled', false);
                        $('#wksselect3').prop('disabled', false);
                    }
                    if (wkslist == check_wks2) {
                        $('#wksselect3').prop('disabled', true);
                    } else {
                        $('#wksselect3').prop('disabled', false);
                    }
                }

                function hide_other2(item, list) {
                    //
                    //
                    if ($('#wkslist2_list_' + list).is(':checked')) {
                        $('#wkslist_list_' + list).closest('div').hide();
                        $('#wkslist3_list_' + list).closest('div').hide();
                        $('#wksstate2').html('<label>Auto</label>')
                        $('#wksid2').html('<label>Auto</label>')
                    } else {
                        $('#wkslist_list_' + list).closest('div').show();
                        $('#wkslist3_list_' + list).closest('div').show();
                        $('#wksid2').empty();
                        $('#wksstate2').empty();
                    }
                    var check_wks = 0;
                    for (var i = 1; i <= wkslist; i++) {
                        if ($('#wkslist_list_' + i).is(':checked') || $('#wkslist2_list_' + i).is(':checked')) {
                            check_wks += 1;
                        }
                    }
                    if (wkslist == check_wks) {
                        $('#wksselect3').prop('disabled', true);
                    } else {
                        $('#wksselect3').prop('disabled', false);
                    }
                }

                function hide_other3(item, list) {
                    if ($('#wkslist3_list_' + list).is(':checked')) {
                        $('#wkslist_list_' + list).closest('div').hide();
                        $('#wkslist2_list_' + list).closest('div').hide();
                        $('#wksid3').html('<label>Auto</label>')
                        $('#wksstate3').html('<label>Auto</label>')
                    } else {
                        $('#wkslist_list_' + list).closest('div').show();
                        $('#wkslist2_list_' + list).closest('div').show();
                        $('#wksid3').empty()
                        $('#wksstate3').empty()
                    }
                }

                $('#add').click(function () {
                    add_detail_sample();
                });

                $(document).on('click', '.remove-sample', function () {
                    var row_remove = $(this).parent().parent();
                    row_remove.fadeOut(300);
                    setTimeout(function () {
                        row_remove.remove();
                        $('.sub_detail_sample').each(function (index, el) {
                            $(el).find('.running-no').text(index + 1);
                        });

                    }, 500);
                });

                var temp_fliter_list = [];
                var temp_unit_name;

                function add_filter_tb4_License(select_item) {
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
                            if (list[0].length != 0) {
                                $('#filter_tb4_License').html(' <option>เลือกผู้รับใบอนุญาต</option>');
                                for (let i = 0; i < list.length; i++) {
                                    $('#filter_tb4_License').append('<option id="filter_tb4_License" name="' + list[i] + '">' + list[i] + '</option>');
                                }
                            } else {
                                $('#filter_tb4_License').empty();
                            }
                            var data_group = response.data_group;
                            var data_group_id = response.data_group_id;
                            var list = response.data_head;
                            var list_type = response.data_type;
                            var list_type_id = response.data_type_id;
                            if (data_group[0].length != 0) {
                                var arr = data_group[0];
                                var arr_id = data_group_id[0];
                                $('#filter_std_head_group').append('<p>ผลิตภัณฑ์ที่ขอรับใบอนุญาต:</p>');
                                $('#filter_head').append('<select onclick="remove_fill()" onchange="add_filter_head(this);" name="filter_type" id="filter_type" class="filter_type form-control" >' + '</select>' + '<div class="col-sm-7">' + '</div>');
                                $('.filter_type').append('<option>เลือกผลิตภัณฑ์ที่ขอรับใบอนุญาต</option>');
                                for (let i = 0; i < arr.length; i++) {
                                    $('.filter_type').append('<option value="' + arr_id[i] + '|' + arr[i] + '">' + arr[i] + '</option>');
                                }
                            } else if (data_group[0].length == 0 && list_type[0].length != 0) {
                                $('#filter_std_head_group').empty();
                                $('#filter_head').empty();
                                $('#filter_std_type').empty();
                                $('#filter_std_list').empty();

                                var arr = list[0];
                                var arr_type = list_type[0];
                                var arr_type_id = list_type_id[0];
                                for (let i = 0; i < arr.length; i++) {
                                    $('#filter_std_type').append('<div style="margin-bottom: 21px" id="filter_head' + i + '">' + arr[i].title + '<br></div>');
                                    $('#filter_std_list').append('<div style="display: flex; margin-bottom: 1px;"> <select disabled name="filter_list" onchange="add_filter_input(this);" id="filter_list' + arr[i].id + '" class="filter_list form-control"  >' + '</select><span style="display: flex; align-items: center;" class="col-md-10" id="filter_std_input' + i + '"></span></div>');
                                    $('#filter_input1').append('<div id="input1_' + i + '" hidden>' + arr[i].wording + '</div>');
                                    temp_fliter_list.push(arr[i].id);
                                }
                                $.each(arr_type_id, function (index, value) {
                                    $('#filter_list' + arr[index].id).append('<option>เลือก</option>');
                                    for (let i = 0; i < arr_type.length; i++) {

                                        if (arr_type[i].stdhead_id == value) {
                                            $('#filter_list' + arr[index].id).append('<option value="' + arr_type[i].producttype_title + '|' + arr_type[i].type + '|' + index + '|' + arr_type[i].unit_name + '|' + arr_type[i].int_option_min + '|' + arr_type[i].int_option_max + '|' + arr_type[i].id + '|' + arr_type[i].int_option + '">' + arr_type[i].producttype_title + '</option>');
                                        }
                                    }
                                    temp_count_filter_head = index;
                                });
                            }
                            var data_user = response.data_user;

                            if (data_user[0].length != 0) {
                                var arr_user = data_user[0];
                                for (let i = 0; i < arr_user.length; i++) {
                                    $('#wksselect1').append('<option value="' + arr_user[i].trader_operater_name + '|' + arr_user[i].trader_autonumber + '|' + tb3_Tisno + '">' + arr_user[i].trader_operater_name + '</option>');
                                }
                                for (let i = 0; i < arr_user.length; i++) {
                                    $('#wksselect2').append('<option value="' + arr_user[i].trader_operater_name + '|' + arr_user[i].trader_autonumber + '|' + tb3_Tisno + '">' + arr_user[i].trader_operater_name + '</option>');
                                }
                                for (let i = 0; i < arr_user.length; i++) {
                                    $('#wksselect3').append('<option value="' + arr_user[i].trader_operater_name + '|' + arr_user[i].trader_autonumber + '|' + tb3_Tisno + '">' + arr_user[i].trader_operater_name + '</option>');
                                }
                            }
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

                function add_filter_detail_maplap(select_item) {
                    var item = select_item.value;
                    var id = item.split('|');
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_detail_maplap')}}",
                        datatype: "html",
                        data: {
                            id: id[1],
                            Tis: id[2],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            if (list[0].length != 0) {
                                var arr = list[0];
                                var name;
                                for (let i = 0; i < arr.length; i++) {
                                    var li = $('<li><label></label></li>');
                                    li.find('label').text(arr[i].product_detail);
                                    $('#wksdetail1').append(li);
                                }

                            }
                        }
                    });
                }

                function add_filter_detail_maplap_edit(select_item) {
                    var item = select_item;
                    var id = item.split('|');
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_detail_maplap')}}",
                        datatype: "html",
                        data: {
                            id: id[1],
                            Tis: id[2],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            if (list[0].length != 0) {
                                var arr = list[0];
                                var name;
                                for (let i = 0; i < arr.length; i++) {
                                    var li = $('<li><label></label></li>');
                                    li.find('label').text(arr[i].product_detail);
                                    $('#wksdetail1').append(li);
                                }

                            }
                        }
                    });
                }

                function add_filter_detail_maplap2(select_item) {
                    var item = select_item.value;
                    var id = item.split('|');
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_detail_maplap')}}",
                        datatype: "html",
                        data: {
                            id: id[1],
                            Tis: id[2],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            if (list[0].length != 0) {
                                var arr = list[0];
                                for (let i = 0; i < arr.length; i++) {
                                    var li = $('<li><label></label></li>');
                                    li.find('label').text(arr[i].product_detail);
                                    $('#wksdetail2').append(li);
                                }

                            }
                        }
                    });
                }

                function add_filter_detail_maplap_edit2(select_item) {
                    var item = select_item;
                    var id = item.split('|');
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_detail_maplap')}}",
                        datatype: "html",
                        data: {
                            id: id[1],
                            Tis: id[2],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            if (list[0].length != 0) {
                                var arr = list[0];
                                var name;
                                for (let i = 0; i < arr.length; i++) {
                                    var li = $('<li><label></label></li>');
                                    li.find('label').text(arr[i].product_detail);
                                    $('#wksdetail2').append(li);
                                }

                            }
                        }
                    });
                }

                function add_filter_detail_maplap3(select_item) {
                    var item = select_item.value;
                    var id = item.split('|');
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_detail_maplap')}}",
                        datatype: "html",
                        data: {
                            id: id[1],
                            Tis: id[2],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            if (list[0].length != 0) {
                                var arr = list[0];
                                for (let i = 0; i < arr.length; i++) {
                                    var li = $('<li><label></label></li>');
                                    li.find('label').text(arr[i].product_detail);
                                    $('#wksdetail3').append(li);
                                }

                            }
                        }
                    });
                }

                function add_filter_detail_maplap_edit3(select_item) {
                    var item = select_item;
                    var id = item.split('|');
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_detail_maplap')}}",
                        datatype: "html",
                        data: {
                            id: id[1],
                            Tis: id[2],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            if (list[0].length != 0) {
                                var arr = list[0];
                                var name;
                                for (let i = 0; i < arr.length; i++) {
                                    var li = $('<li><label></label></li>');
                                    li.find('label').text(arr[i].product_detail);
                                    $('#wksdetail3').append(li);
                                }

                            }
                        }
                    });
                }

                function remove_filter_detail_maplap1() {
                    $('#wksdetail1').empty();
                }

                function remove_filter_detail_maplap2() {
                    $('#wksdetail2').empty();
                }

                function remove_filter_detail_maplap3() {
                    $('#wksdetail3').empty();
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
                    temp_count_filter_head = 0;
                    temp_fliter_list = [];
                    temp_input = 0;
                }

                function remove_fill() {
                    $('#filter_std_type').empty();
                    $('#filter_std_list').empty();
                }

                function add_filter_head(select_item) {
                    var head = select_item.value.split("|");
                    $.ajax({
                        type: "GET",
                        url: "{{url('/ssurv/save_example/get_head')}}",
                        datatype: "html",
                        data: {
                            head: head[0],
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            var response = data;
                            var list = response.data;
                            var list_type = response.data_type;
                            var list_type_id = response.data_type_id;
                            var list_head = response.data_head;
                            if (list != null) {
                                var arr = list[0];
                                var arr_type = list_type[0];
                                var arr_type_id = list_type_id[0];
                                var arr_head = list_head[0];
                                for (let i = 0; i < arr.length; i++) {
                                    $('#filter_std_type').append('<div style="margin-bottom: 21px" id="filter_head' + i + '">' + arr[i].title + '<br></div  >');
                                    $('#filter_std_list').append('<div style="display: flex; margin-bottom: 1px;"> <select name="filter_list" onchange="add_filter_input(this);" id="filter_list' + arr_type_id[i] + '" class="filter_list form-control" >' + '</select><span  class="col-md-10" id="filter_std_input' + i + '"></span ></div> ');
                                    $('#filter_input1').append('<div id="input1_' + i + '" hidden>' + arr[i].wording + '</div>');
                                    temp_fliter_list.push(arr_type_id[i]);
                                }
                                $.each(arr_type_id, function (index, value) {
                                    $('#filter_list' + arr_type_id[index]).append('<option>เลือก</option>');
                                    for (let i = 0; i < arr_type.length; i++) {
                                        if (arr_type[i].stdhead_id == value) {
                                            $('#filter_list' + arr_type_id[index]).append('<option value="' + arr_type[i].producttype_title + '|' + arr_type[i].type + '|' + index + '|' + arr_type[i].unit_name + '|' + arr_type[i].int_option_min + '|' + arr_type[i].int_option_max + '|' + arr_type[i].id + '|' + arr_type[i].int_option + '">' + arr_type[i].producttype_title + '</option>');
                                        }
                                    }
                                    temp_count_filter_head = index;
                                });
                            }
                        }
                    });
                }

                var temp_input = 0;

                function add_filter_input(select_item) {
                    if (select_item.value != 'เลือก') {
                        var type = select_item.value.split("|");
                        var check_type = type[3].split(",");
                        if (type[1] == 2) {
                            if (type[3] != "") {
                                if (check_type.length == 1) {
                                    $('#filter_std_input' + type[2]).html('<div style="display: flex; align-items: center;"><input class="form-control" type="number" id="input_detail_' + type[2] + '">' + '<div id="type_input' + temp_input + '" class="col-md-1">' + type[3] + '</div></div>');
                                    temp_input++;
                                } else {
                                    $('#filter_std_input' + type[2]).html('<input class="form-control" type="number" id="input_detail_' + type[2] + '">' + '<select id="type_select' + temp_input + '"></select >');
                                    for (let i = 0; i < check_type.length; i++) {
                                        $('#type_select' + temp_input).append('<option value="' + check_type[i] + '">' + check_type[i] + '</option>');
                                    }
                                    temp_input++;
                                }
                            } else {
                                $('#filter_std_input' + type[2]).html('<input class="form-control" type="text" id="input_detail_' + type[2] + '">');
                            }

                        } else if (type[1] == 3 || type[1] == 4) {
                            if (type[7] == 3) {
                                $('#filter_std_input' + type[2]).html('<div style="display: flex; align-items: center;"><input class="form-control" type="number"  id="input_detail_' + type[2] + '" >' + ' <span style="margin: 0 10px">ถึง</span> <input class="form-control test_check" type="number"  id="input_detail2_' + temp_input + '" >' + '<div id="type_input' + temp_input + '" class="col-md-1">' + type[3] + '</div></div>' + '<div id="alert_input" style="display: none;color: red;font-size: 12px">โปรดระบุจำนวนให้ถูกต้อง</div>');
                                $("#input_detail_" + type[2]).keyup(function () {
                                    if ($('#input_detail_' + type[2]).val() <= parseInt(type[4]) || $('#input_detail_' + type[2]).val() >= parseInt(type[5])) {
                                        // alert('โปรดระบุจำนวนให้ถูกต้อง!');
                                        // || $('.test_check').val() <=  parseInt(type[4]) ||$('.test_check').val() >= parseInt(type[5])
                                        $('#alert_input').show();
                                        // <div id="alert_input" style="display: none;color: red">โปรดระบุจำนวนให้ถูกต้อง</div>
                                        //     document.getElementById("input_detail_"+type[2]).value = "";
                                        // document.getElementsByClassName("test_check").value = "";
                                    } else {
                                        $('#alert_input').hide();
                                    }
                                });
                                temp_input++;
                            } else {
                                $('#filter_std_input' + type[2]).html('<div style="display: flex; align-items: center;"><input class="form-control" type="number"  id="input_detail_' + type[2] + '" >' + '<div id="type_input' + temp_input + '" class="col-md-1">' + type[3] + '</div></div>' + '<div id="alert_input" style="display: none;color: red;font-size: 12px">โปรดระบุจำนวนให้ถูกต้อง</div>');
                                $("#input_detail_" + type[2]).keyup(function () {
                                    if ($('#input_detail_' + type[2]).val() <= parseInt(type[4]) || $('#input_detail_' + type[2]).val() >= parseInt(type[5])) {
                                        // alert('โปรดระบุจำนวนให้ถูกต้อง!');

                                        // document.getElementById('#input_detail_'+type[2]).value = '';

                                        $('#alert_input').show();
                                    } else {
                                        $('#alert_input').hide();
                                    }

                                });
                                temp_input++;
                            }

                        } else if (type[1] == 1) {
                            $('#filter_std_input' + type[2]).empty();
                            var num = parseInt(type[2]) + 1;
                            $.ajax({
                                type: "GET",
                                url: "{{url('/ssurv/save_example/get_type2')}}",
                                datatype: "html",
                                data: {
                                    type: type[6],
                                    '_token': "{{ csrf_token() }}",
                                },
                                success: function (data) {
                                    var response = data;
                                    if (response != '') {
                                        var list = response.data;
                                        var arr_type = list[0];
                                        $.each(arr_type, function (key, value) {
                                            var html_option = '';
                                            for (let i = 0; i < arr_type.length; i++) {
                                                html_option += ('<option value="' + arr_type[i].producttype_title + '|' + arr_type[i].type + '|' + num + '|' + arr_type[i].unit_name + '|' + arr_type[i].int_option_min + '|' + arr_type[i].int_option_max + '|' + arr_type[i].id + '|' + arr_type[i].int_option + '">' + arr_type[i].producttype_title + '</option>');
                                            }
                                            $('#filter_list' + value.stdhead_id).html('<option>เลือก</option>' + html_option);
                                            $('#filter_list' + value.stdhead_id).trigger('liszt:updated');
                                        });
                                    }

                                }
                            });
                        } else {
                            $('#filter_std_input' + type[2]).empty();
                        }
                    }

                }

                // $(function () {
                //     $("#checkUnit").click(function () {
                //         if ($(this).is(":checked")) {
                //             $("#myTable2").fadeIn(500);
                //         } else {
                //             $("#myTable2").fadeOut(500);
                //         }
                //     });
                // });

                $('.checked_radio').on('click', function () {
                    if ($(this).val() == 'ตรวจสอบที่หน่วยตรวจสอบ') {
                        $('#sample_delivery').fadeIn(700);
                    } else {
                        $('#sample_delivery').fadeOut(700);
                    }
                });

                $("#add").click(function () {
                    // $.each(temp_count_filter_head, function (index, value) {
                    //     console.log(index,value)
                    //     $('#filter_list' + temp_fliter_list[index]).val("เลือก");
                    // });
                    for (let i = 0; i <= temp_count_filter_head; i++) {
                        $('#filter_list' + temp_fliter_list[i]).val("เลือก");
                    }
                    $('.filter_type').val("เลือกผลิตภัณฑ์ที่ขอรับใบอนุญาต")
                });

                function add_status_btn(status) {
                    $('#status_btn').html('<input type="text" name="check_status" value="' + status + '" hidden>');
                }

            </script>
    @endpush

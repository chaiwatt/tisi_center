@extends('layouts.master')
@push('css')
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />

    <style>

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: #FFF2CC;
        }

        .modal-header {
            padding: 9px 15px;
            border-bottom: 1px solid #eee;
            background-color: #317CC1;
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

        .wrapper-detail {
            border: solid 1px silver;
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
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title">ระบบรับคำขอการทำผลิตภัณฑ์เพื่อส่งออก (20 ตรี)</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>
                    {{--                    <a class="btn btn-success pull-right" href="{{ url('/accept_import/accept_import') }}">--}}
                    {{--                        <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ--}}
                    {{--                    </a>--}}

                    <div class="row wrapper-detail">
                        <div class="form-group">
                            <label class="control-label text-right col-md-4">ชื่อผลิตภัณฑ์อุตสาหกรรม</label>
                            <div class="col-md-6">
                                <input value="{{$data->title}}" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="form-group pull-right text-right">
                            <button type="button" class="btn btn-warning btn-xs add-row" id="plus-row" disabled>
                                <i class="fa fa-plus-circle" aria-hidden="true"></i> เพิ่มข้อมูล
                            </button>
                        </div>

                        <table class="table color-bordered-table primary-bordered-table">
                            <thead>
                            <tr>
                                <th class="text-center">รายการที่</th>
                                <th class="text-center" width="50%">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                                <th class="text-center">ปริมาณที่ขอทำ</th>
                                <th class="text-center">หน่วย</th>
                                <th class="text-center">ลบ</th>
                            </tr>
                            </thead>
                            <tbody id="table-body">
                            @foreach ($data_detail as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center align-top">
                                        <input class="form-control" value="{{$item->detail}}" disabled>
                                    </td>
                                    <td class="text-center align-top">
                                        <input class="form-control" value="{{$item->quantity}}" disabled>
                                    </td>
                                    <td class="text-center align-top">
                                        <input class="form-control" value="{{HP::get_unit_4($item->unit)}}" disabled>
                                    </td>
                                    <td class="text-center align-top">
                                        <button type="button" class="btn btn-danger btn-xs remove-row" disabled>
                                            <i class="fa fa-close" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            {{--<tfoot>--}}
                            {{--<tr>--}}
                            {{--<td colspan="3"><span class="pull-right">รวม:</span></td>--}}
                            {{--<td></td>--}}
                            {{--<td><input class="form-control" type="text" id="total-scholarship" value=""></td>--}}
                            {{--<td></td>--}}
                            {{--</tr>--}}
                            {{--</tfoot>--}}
                        </table>

                        <div class="form-group">
                            <label class="control-label text-right col-md-4">แตกต่างจากมาตรฐานเลขที่</label>
                            <div class="col-md-6">
                                @if($data_cut_full != null)
                                    <div class="form-control" disabled>
                                        @foreach($data_cut_full as $list_no)
                                            {{HP::get_different_no_4($list_no)}} ,
                                        @endforeach
                                    </div>

                                @else
                                    <input class="form-control" value="{{HP::get_different_no_4($data->different_no)}}" disabled>
                                @endif
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label text-right col-md-4">เหตุผลที่ขออนุญาต</label>
                            <div class="col-md-6">
                                <textarea class="form-control" rows="4" disabled>{{$data->reason}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-right col-md-4">เพื่อให้เป็นไปตามมาตรฐาน</label>
                            <div class="col-md-6">
                                <input value="{{$data->foreign_standard_ref}}" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label text-right col-md-4">ของประเทศ</label>
                            <div class="col-md-6">
                                <select class="select2 form-control" disabled>
                                    <option>{{HP::get_county_4($data->country_ref)}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label text-right col-md-4">ระยะเวลาที่ผลิต</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input value="{{$data->start_date}}" class="form-control datepicker" disabled>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            <label class="control-label text-right col-md-1">ถึง</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input value="{{$data->end_date}}" class="form-control datepicker" disabled>
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label text-right col-md-4">บริษัทที่สั่งทำผลิตภัณฑ์</label>
                            <div class="col-md-6">
                                <select class="select2 form-control" disabled>
                                    <option>{{$data->company_order}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="white-box">
                                <fieldset>
                                    <div class="col-md-8 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">สถานที่ผลิตผลิตภัณฑ์</label>
                                            <div class="col-md-6">
                                                <input type="checkbox"
                                                       class="check"
                                                       <?php echo ($data->made_factory_chk === '1') ? 'checked' : '' ?> disabled>
                                                &nbsp;ใช้ที่เดียวกับที่จดทะเบียน
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>

                                    <div class="col-md-12 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-2">โรงงานชื่อ</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_name}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ตั้งอยู่เลขที่</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_addr_no}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-5">นิคมอุตสาหกรรม
                                                (ถ้ามี)</label>
                                            <div class="col-md-7">
                                                <input value="{{$data->made_factory_nicom}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ตรอก/ซอย</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_soi}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ถนน</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_road}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">หมู่ที่</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_moo}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ตำบล/แขวง</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_subdistrict}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">อำเภอ/เขต</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_district}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">จังหวัด</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_province}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">รหัสไปรษณีย์</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_zipcode}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">โทรศัพท์</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_tel}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">โทรสาร</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->made_factory_fax}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                        </div>

                        <div class="row">

                            <div class="white-box">
                                <fieldset>

                                    <div class="col-md-8 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">สถานที่เก็บผลิตภัณฑ์</label>
                                            <div class="col-md-6">
                                                <input type="checkbox"
                                                       class="check"
                                                       <?php echo ($data->store_factory_chk === '1') ? 'checked' : '' ?> disabled>
                                                &nbsp;ใช้ที่เดียวกับที่ผลิตภัณฑ์
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-12 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-2">โรงงานชื่อ</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_name}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ตั้งอยู่เลขที่</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_addr_no}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-5">นิคมอุตสาหกรรม
                                                (ถ้ามี)</label>
                                            <div class="col-md-7">
                                                <input value="{{$data->store_factory_nicom}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ตรอก/ซอย</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_soi}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ถนน</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_road}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">หมู่ที่</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_moo}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">ตำบล/แขวง</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_subdistrict}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">อำเภอ/เขต</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_district}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">จังหวัด</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_province}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">รหัสไปรษณีย์</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_zipcode}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">โทรศัพท์</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_tel}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label class="control-label text-right col-md-4">โทรสาร</label>
                                            <div class="col-md-8">
                                                <input value="{{$data->store_factory_fax}}"
                                                       class="form-control"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        </div>

                        <div class="row">
                            <div class="white-box">
                                <fieldset>
                                    <legend>พร้อมแนบเอกสาร ดังนี้</legend>
                                    <div class="form-group {{ $errors->has('attach_product_plan') ? 'has-error' : ''}}">
                                        {!! Form::label('attach_product_plan', 'แผนการผลิต ระยะเวลาการทำ', ['class' => 'col-md-4 control-label required']) !!}
                                        {!! Form::hidden('attach_product_plan_file_name', $attach_product_plan->file_name); !!}
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new input-group pull-left col-md-8"
                                                 data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <div class="fileinput fileinput-new input-group pull-left col-md-10"
                                                         data-provides="fileinput">
                                                        <div>
                                                            <a href="{{url('/asurv/accept_import/download/'.$attach_product_plan->file_name)}}">{{$attach_product_plan->file_client_name}}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file"
                                                      disabled="true">
                    <span class="fileinput-new">เลือกไฟล์</span>
                  </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('attach_purchase_order') ? 'has-error' : ''}}">
                                        {!! Form::label('attach_purchase_order', 'สำเนาใบสั่งซื้อ', ['class' => 'col-md-4 control-label required']) !!}
                                        {!! Form::hidden('attach_purchase_order_file_name', $attach_purchase_order->file_name); !!}
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new input-group pull-left col-md-8"
                                                 data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <div>
                                                        <a href="{{url('/asurv/accept_import/download/'.$attach_purchase_order->file_name)}}">{{$attach_purchase_order->file_client_name}}</a>
                                                    </div>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file"
                                                      disabled="true">
                    <span class="fileinput-new">เลือกไฟล์</span>
                  </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('attach_factory_license') ? 'has-error' : ''}}">
                                        {!! Form::label('attach_factory_license', 'สำเนาใบอนุญาตประกอบกิจการโรงงาน', ['class' => 'col-md-4 control-label required']) !!}
                                        {!! Form::hidden('attach_factory_license_file_name', $attach_factory_license->file_name); !!}
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new input-group pull-left col-md-8"
                                                 data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <div>
                                                        <a href="{{url('/asurv/accept_import/download/'.$attach_factory_license->file_name)}}">{{$attach_factory_license->file_client_name}}</a>
                                                    </div>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file"
                                                      disabled="true">
                    <span class="fileinput-new">เลือกไฟล์</span>
                  </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('attach_standard_to_made') ? 'has-error' : ''}}">
                                        {!! Form::label('attach_standard_to_made', 'สำเนามาตรฐานของต่างประเทศหรือมาตรฐานระหว่างประเทศ ที่ใช้ทำผลิตภัณฑ์', ['class' => 'col-md-4 control-label required']) !!}
                                        {!! Form::hidden('attach_standard_to_made_file_name', $attach_standard_to_made->file_name); !!}
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new input-group pull-left col-md-8"
                                                 data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <div>
                                                        <a href="{{url('/asurv/accept_import/download/'.$attach_standard_to_made->file_name)}}">{{$attach_standard_to_made->file_client_name}}</a>
                                                    </div>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file"
                                                      disabled="true">
                    <span class="fileinput-new">เลือกไฟล์</span>
                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('attach_difference_standard') ? 'has-error' : ''}}">
                                        {!! Form::label('attach_difference_standard', 'เอกสารแสดงข้อแตกต่างของมาตรฐานต่างประเทศที่ขอทำกับมาตรฐานของไทย', ['class' => 'col-md-4 control-label required']) !!}
                                        {!! Form::hidden('attach_difference_standard_file_name', $attach_difference_standard->file_name); !!}
                                        <div class="col-md-8">
                                            <div class="fileinput fileinput-new input-group pull-left col-md-8"
                                                 data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <div>
                                                        <a href="{{url('/asurv/accept_import/download/'.$attach_difference_standard->file_name)}}">{{$attach_difference_standard->file_client_name}}</a>
                                                    </div>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file"
                                                      disabled="true">
                    <span>เลือกไฟล์</span>
                  </span>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('attach_other', 'เอกสารอื่นๆ (ถ้ามี)', ['class' => 'col-md-4 control-label']) !!}
                                        <div class="col-md-8">
                                            <button type="button" class="btn btn-sm btn-success" id="add-attach"
                                                    disabled>
                                                <i class="icon-plus"></i>&nbsp;เพิ่ม
                                            </button>
                                        </div>
                                    </div>

                                    @if($attachs!=null)
                                    <div id="other_attach_box">
                                        @foreach ($attachs as $key => $attach)
                                            <div class="form-group other_attach_item">
                                                <div class="col-md-4">
                                                    <input class="form-control" disabled value="{{$attach->file_note}}">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="fileinput fileinput-new input-group pull-left col-md-10"
                                                         data-provides="fileinput">
                                                        <div class="form-control">
                                                            <div>
                                                                <a href="{{url('/asurv/accept_import/download/'.$attach->file_name)}}">{{$attach->file_client_name}}</a>
                                                            </div>
                                                        </div>
                                                        <span class="input-group-addon btn btn-default btn-file"
                                                              disabled="true">
                      <span>เลือกไฟล์</span>
                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @else
{{--                                        <div class="form-group other_attach_item">--}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <input class="form-control" disabled >--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="fileinput fileinput-new input-group pull-left col-md-10"--}}
{{--                                                     data-provides="fileinput">--}}
{{--                                                    <div class="form-control">--}}
{{--                                                    </div>--}}
{{--                                                    <span class="input-group-addon btn btn-default btn-file"--}}
{{--                                                          disabled="true">--}}
{{--                      <span>เลือกไฟล์</span>--}}
{{--                    </span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    @endif

                                    <div class="form-group">
                                        <label class="col-md-4 control-label text-right">หมายเหตุ</label>
                                        <div class="col-md-6">
                                            <textarea rows="4" class="form-control"
                                                      disabled>{{$data->remark}}</textarea>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                        </div>

                        {{--                        <div class="form-group">--}}
                        {{--                            <div class="col-md-offset-4 col-md-4">--}}

                        {{--                                <button class="btn btn-primary" type="submit">--}}
                        {{--                                    <i class="fa fa-paper-plane"></i> ยื่นคำขอ--}}
                        {{--                                </button>--}}
                        {{--                                @can('view-'.str_slug('inform_calibrate'))--}}
                        {{--                                    <a class="btn btn-default" href="{{url('/esurv/applicant_20ter')}}">--}}
                        {{--                                        <i class="fa fa-rotate-left"></i> ยกเลิก--}}
                        {{--                                    </a>--}}
                        {{--                                @endcan--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>

                    <div class="col-sm-12" style="margin-bottom: 10px"></div>
                    <form id="form_data" method="post" enctype="multipart/form-data">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <input hidden name="id" value="{{$data->id}}">
                        <fieldset class="row wrapper-detail">
                            <legend> ผลการพิจารณา</legend>

                            <div class="form-group ">
                                <div class="col-sm-4 control-label text-right"> สถานะ :</div>
                                <div class="col-sm-6 m-b-10">
                                    <select class=" form-control" style="text-align: -webkit-center;" name="state" disabled="true">
                                        @if($data->state==1)
                                            <option value="1"> ยื่นคำขอ</option>
                                        @elseif($data->state==2)
                                            <option value="2"> อยู่ระหว่างดำเนินการ</option>
                                        @elseif($data->state==3)
                                            <option value="3"> เอกสารไม่ครบถ้วน</option>
                                        @elseif($data->state==4)
                                            <option value="4"> อนุมัติ</option>
                                        @elseif($data->state==5)
                                            <option value="5"> ไม่อนุมัติ</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="col-sm-4 control-label text-right"> ความคิดเห็นเพิ่มเติม :</div>
                                <div class="col-sm-6 m-b-10">
                                    <textarea name="remake_officer_export" rows="4" cols="50"
                                              class="form-control" disabled> {{$data->remake_officer_export}}</textarea>
                                </div>
                            </div>

                            <div class="form-group ">
                                <div class="col-sm-4 control-label" align="right"> ผู้พิจารณา :</div>
                                <div class="col-sm-6">
                                    <input class="form-control"
                                           type="text"
                                           disabled
                                           value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"/>
                                    <input name="officer_export"
                                           hidden
                                           value="{{auth()->user()->runrecno}}"/>
                                </div>
                            </div>

                        </fieldset>

                        <div class="col-sm-12" style="margin-bottom: 5px;"></div>
                        <div class="form-group text-center">
                            <a class="btn btn-default btn-sm waves-effect waves-light"
                               href="{{ url('/asurv/accept_import') }}">
                                <i class="fa fa-undo"></i><b> กลับ</b>
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>

        $('.datepicker').datepicker({
            language:'th-th',
            format:'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
	    });

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
                url: "{{url('/asurv/accept_import/save')}}",
                datatype: "script",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/asurv/accept_import')}}"
                    } else if (data.status == "error") {
                        // $("#alert").html('<div class="alert alert-danger"><strong>แจ้งเตือน !</strong> ' + data.message + ' <br></div>');
                        alert(data.message)
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });

        });
    </script>
@endpush

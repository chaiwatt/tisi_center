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

        .wrapper-detail {
            border: solid 1px silver;
            margin-top: 60px;
            margin-left: 20px;
            margin-right: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        option {
            text-align: left;
        }

        select {
            text-align-last: center;
            text-align: center;
        }

        fieldset {
            padding: 20px;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title"> ตั้งค่ารายการผลทดสอบผลิตภัณฑ์ </h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <form id="form_data" method="post" enctype="multipart/form-data">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <fieldset class="row">
                            <div class="white-box">
                                <div class="form-group">
                                    <div class="col-md-10">
                                        <strong class="col-sm-3 text-right"> มาตรฐาน : </strong>
                                        <div class="col-md-9">
                                            <select name="tis_standard"
                                                    class="form-control"
                                                    onclick="add_filter_tb4_License(this);">
                                                <option>เลือกมาตรฐาน</option>
                                                @foreach(HP::TisList() as $tb3_Tisno=>$name)
                                                    <option id="tis_standard" value="{{$tb3_Tisno}}">{{$name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-2 text-right form-group">
                                    <button class="btn btn-success btn-sm waves-effect waves-light"
                                            name="add_data"
                                            id="add_data"
                                            onClick="return false;">
                                        <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มข้อมูล</b>
                                    </button>
                                </div>

                                <table class="table color-bordered-table primary-bordered-table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%; color: white">ชื่อรายการผลทดสอบ</th>
                                            <th style="width: 25%; color: white">ประเภทข้อมูล</th>
                                            <th style="width: 4%; color: white">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                <div id="num_row" class="sub_detail"></div>

                                <div class="form-group">
                                    <label for="state" class="col-md-6 control-label text-right">สถานะ</label>
                                    <div class="col-md-6">
                                        <label><input class="check" data-radio="iradio_square-green" checked="checked" name="state" type="radio" value="1" id="state"> เปิด</label>
                                        <label><input class="check" data-radio="iradio_square-red" name="state" type="radio" value="0" id="state"> ปิด</label>
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-info btn-sm waves-effect waves-light"
                                            type="submit">
                                        <b>บันทึก</b>
                                    </button>
                                    <a class="btn btn-default btn-sm waves-effect waves-light"
                                       href="{{ url('/resurv/results_product') }}">
                                        <i class="fa fa-undo"></i><b> ยกเลิก</b>
                                    </a>
                                </div>

                            </div>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#form_data').submit(function (event) {
            event.preventDefault();
            var form_data = new FormData(this);
            console.log(form_data);
            $.ajax({
                type: "POST",
                url: "{{url('/resurv/results_product/save')}}",
                datatype: "script",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "success") {
                        alert('บันทึกข้อมูลสำเร็จ');
                        window.location.href = "{{url('/resurv/results_product')}}"
                    } else if (data.status == "error") {
                        alert( data.message)
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });
        });

        function add_detail_sample() {

            var html_add_item = '<tr>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control text-center" name="name_result[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><select style="width: 80%; " class="form-control text-center" name="type_result[]"> ' +
                '<option value="เลือกประเภทข้อมูล">' + "เลือกประเภทข้อมูล" + '</option>' +
                '<option value="ตัวเลข">' + "ตัวเลข" + '</option>' +
                '<option value="Yes / No">' + "Yes / No" + '</option>' +
                '<option value="Text">' + "Text" + '</option>' +
                '</select></td>';
            html_add_item += '<td><a class="btn btn-small btn-danger remove-data" onclick="return false;"><span class="fa fa-trash"></span></a></td>';
            html_add_item += '</tr>';
            $('#myTable tbody').append(html_add_item);
        }

        $('#add_data').click(function () {
            add_detail_sample();
        });

        $(document).on('click', '.remove-data', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(100);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_detail').each(function (index, el) {
                    $(el).find('.running-no').text(index + 1);
                });

            }, 500);
        });

    </script>

@endpush

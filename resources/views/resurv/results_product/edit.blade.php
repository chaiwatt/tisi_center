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

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <div class="white-box">
                    <h3 class="box-title pull-left">ตั้งค่ารายการผลทดสอบผลิตภัณฑ์</h3>

                    <div class="clearfix" style="border-bottom: solid 2px royalblue; margin-bottom: 44px;"></div>

                    <form id="form_data" method="post" enctype="multipart/form-data">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <input value="{{$data->id}}" name="result_id" hidden>
                        <div class="row wrapper-detail">
                            <div>
                                <div class="col-sm-6" style="display: flex; align-items: center;">
                                    <strong class="col-sm-3" style="text-align: right"> มาตรฐาน : </strong>
                                    <div class="col-md-10">
                                        <select name="tis_standard"
                                                class="form-control"
                                                disabled
                                                onclick="add_filter_tb4_License(this);">
                                            <option value="{{$data->tis_standard}}">{{'มอก. '.$data->tis_standard.' '.@$data->tis->tb3_TisThainame ?? 'n/a'}}</option>
                                            @foreach(HP::TisList() as $tb3_Tisno=>$name)
                                                <option id="tis_standard" value="{{$tb3_Tisno}}">{{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12" style="margin-bottom: 25px"></div>

                            <div class="col-sm-6" style="margin-top: 10px; margin-bottom: 20px;width: 86%"
                                 align="right">
                                <button class="btn btn-success btn-sm waves-effect waves-light"
                                        name="add_data"
                                        id="add_data"
                                        onClick="return false;">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มข้อมูล</b>
                                </button>
                            </div>

                            <div class="table-responsive" style="display: flex; justify-content: center;">
                                <table class="table table-bordered" id="myTable" style="width: 70%">
                                    <thead>
                                    <tr bgcolor="#5B9BD5">
                                        <th style="width: 25%; color: white">ชื่อรายการผลทดสอบ</th>
                                        <th style="width: 25%; color: white">ประเภทข้อมูล</th>
                                        <th style="width: 4%; color: white">ลบ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data_detail as $list)
                                        <tr class="sub_detail">
                                            <td align="centter"><input type="text"
                                                                       class="form-control" name="name_result[]"
                                                                       value="{{$list->name_result}}"></td>
                                            <td><select class="form-control" name="type_result[]">
                                                    <option value="{{$list->type_result}}">{{$list->type_result}}</option>
                                                    <option value="ตัวเลข">ตัวเลข</option>
                                                    <option value="Yes / No">Yes / No</option>
                                                    <option value="Text">Text</option>
                                                </select></td>
                                            <td><a class="btn btn-small btn-danger remove-data" onclick="return false;"><span
                                                            class="fa fa-trash"></span></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div id="action"></div>
                                <div id="id_detail"></div>
                            </div>
                            <div class="form-group">
                                <label for="state" class="col-md-6 control-label text-right">สถานะ</label>
                                <div class="col-md-6">
                                    <label><input class="check" data-radio="iradio_square-green"
                                                  <?php echo ($data->status == '1') ? 'checked' : '' ?>
                                                  name="state" type="radio" value="1" id="state"> เปิด</label>
                                    <label><input class="check" data-radio="iradio_square-red" name="state" type="radio"
                                                  <?php echo ($data->status == '0') ? 'checked' : '' ?>
                                                  value="0" id="state"> ปิด</label>
                                </div>
                            </div>

                            <br>
                            <div align="right" style="width: 86%">
                                <button class="btn btn-info btn-sm waves-effect waves-light"
                                        style="width: 7%; font-size: 14px" type="submit">บันทึก
                                </button>
                                <a class="btn btn-default btn-sm waves-effect waves-light"
                                   style="width: 7%; font-size: 14px"
                                   href="{{ url('/resurv/results_product') }}">
                                    <i class="fa fa-undo"></i><b> ยกเลิก</b>
                                </a>
                            </div>

                        </div>

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
        window.onload = function () {

        }
        $('#form_data').submit(function (event) {
            event.preventDefault();
            var form_data = new FormData(this);
            $.ajax({
                type: "POST",
                url: "{{url('/resurv/results_product/update')}}",
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
                        alert(data.message);
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });
        });

        function remove_detail(id) {
            if (confirm('ยืนยันการลบข้อมูลออกจากฐานข้อมูล ?') === true) {
                $.ajax({
                    type: "POST",
                    url: "{{url('/resurv/results_product/delete_detail')}}",
                    datatype: "html",
                    data: {
                        id: id,
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        if (data.status == "success") {
                            window.location.reload();
                        } else if (data.status == "error") {
                            $("#alert").html('<div class="alert alert-danger"><strong>แจ้งเตือน !</strong> ' + data.message + ' <br></div>');
                        } else {
                            alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                        }

                    }
                });
            }
        }

        function add_detail_sample() {
            var html_add_item = '<tr class="sub_detail">';
            var add_action;
            html_add_item += '<td align="centter"><input type="text"  class="form-control" name="name_result[]"></td>';
            html_add_item += '<td ><select  class="form-control" name="type_result[]"> ' +
                '<option value="เลือกประเภทข้อมูล">' + "เลือกประเภทข้อมูล" + '</option>' +
                '<option value="ตัวเลข">' + "ตัวเลข" + '</option>' +
                '<option value="Yes / No">' + "Yes / No" + '</option>' +
                '<option value="Text">' + "Text" + '</option>' +
                '</select></td>';
            html_add_item += '<td><a class="btn btn-small btn-danger remove-data" onclick="return false;"><span class="fa fa-trash"></span></a></td>';
            html_add_item += '</tr>';
            add_action = '<input type="text" name="action[]" value="" hidden>';
            $('#action').append(add_action);
            $('#myTable tbody').append(html_add_item);
        }

        $('#add_data').click(function () {
            add_detail_sample();
        });

        $(document).on('click', '.remove-data', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(300);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_detail').each(function (index, el) {
                    $(el).find('.running-no').text(index + 1);
                });

            }, 500);
        });

    </script>

@endpush

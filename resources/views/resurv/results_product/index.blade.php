@extends('layouts.master')

@push('css')

    <style>

        th {
            text-align: center;
        }

        td {
            text-align: center;
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

        fieldset {
            padding: 20px;
        }

    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title"> ระบบตั้งค่ารายการผลทดสอบผลิตภัณฑ์ </h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    @can('add-'.str_slug('receive_volume'))
                        <div class="form-group text-right">
                            <a class="btn btn-success btn-md waves-effect waves-light"
                               href="{{ url('/resurv/results_product/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                            <a class="btn btn-danger btn-md waves-effect waves-light"
                               onclick="confirm_delete()">
                                <span class="btn-label"><i class="fa fa-trash"></i></span><b>ลบ</b>
                            </a>
                            <a class="btn btn-success btn-sm btn-outline waves-effect waves-light"
                               onclick="update_status_on()">
                                <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                            </a>
                            <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light"
                               onclick="update_status_off()">
                                <span class="btn-label"><i class="fa fa-times"></i></span><b>ปิด</b>
                            </a>
                        </div>
                    @endcan


                    <fieldset class="row">
                        <div class="white-box">
                            <div class="form-group">
                              {!! Form::model($filter, ['url' => '/resurv/results_product', 'method' => 'get', 'id' => 'myFilter']) !!}

                                <div class="col-md-3" style="margin-bottom: 20px">
                                    {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {!! Form::label('filter_status', 'สถานะ:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status', array('1' => 'ใช้งาน', '0' => 'ปิดใช้งาน'), null , ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-', 'onchange'=>'this.form.submit()']); !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('filter_detail', 'ค้นหา:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                      <div class="input-group">
                                        {!! Form::input('text', 'filter_detail', null, ['class' => 'form-control', 'placeholder'=>'ค้นหาจากรายการผลทดสอบ']); !!}
                                        <span class="input-group-btn">
                                          <button type="submit" class="btn waves-effect waves-light btn-success">
                                            <i class="fa fa-search"></i>
                                          </button>
                                        </span>
                                      </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! Form::label('filter_tb3_Tisno', 'มาตรฐาน:', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                            {!! Form::select('filter_tb3_Tisno', HP::TisList(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกมาตรฐาน-', 'onchange'=>'this.form.submit()']); !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">&nbsp;</div>
                                    <div class="col-md-3">&nbsp;</div>
                                </div>

                              {!! Form::close() !!}
                            </div>

                            <div class="form-group">

                                <table class="table color-bordered-table primary-bordered-table" id="myTable">
                                    <thead>
                                    <tr bgcolor="#5B9BD5">
                                        <th style="width: 2%;color: white">No.</th>
                                        <th style="width: 2%;color: white"><input type="checkbox" id="checkall"></th>
                                        <th style="width: 6%;color: white">เลข มอก.</th>
                                        <th style="width: 20%;color: white">ชื่อมาตรฐาน</th>
                                        <th style="width: 20%;color: white">รายการผลทดสอบ</th>
                                        <th style="width: 6%;color: white">วันที่บันทึก</th>
                                        <th style="width: 6%;color: white">สถานะ</th>
                                        <th style="width: 6%;color: white">ผู้บันทึก</th>
                                        <th style="width: 6%;color: white">เครื่องมือ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td>{{ $temp_num++ }}</td>
                                            <td><input type="checkbox" name="cb[]" class="cb" value="{{$item->id}}"></td>
                                            <td>{{ $item->tis_standard }}</td>
                                            <td>{{ @$item->tis->tb3_TisThainame ?? 'n/a' }}</td>
                                            <td>
                                                @foreach ($item->detail as $name)
                                                    <span>{{ $name->name_result }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ HP::DateThai($item->created_at) }}</td>
                                            <td>
                                                @if($item->status=='0')
                                                    ปิดใช้งาน
                                                @elseif($item->status=='1')
                                                    ใช้งาน
                                                @else
                                                    ยกเลิก
                                                @endif
                                            </td>
                                            <td>{{ $item->user_create }}</td>
                                            <td>
                                                <a href="{{url('resurv/results_product/'.$item->id)}}"
                                                   class="btn btn-info btn-xs">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{url('/resurv/results_product/'.$item->id.'/edit')}}"
                                                   class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                </a>
                                                <button class="btn btn-danger btn-xs"
                                                        onclick="confirm_delete({{$item->id}});">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="pagination-wrapper">
                                    @php
                                        $page = array_merge($filter, ['sort' => Request::get('sort'),
                                                                      'direction' => Request::get('direction'),
                                                                      'perPage' => Request::get('perPage'),
                                                                     ]);
                                    @endphp
                                    {!!
                                        $data->appends($page)->links()
                                    !!}
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div id="getID"></div>

                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>

        $(document).ready(function () {
            //เลือกทั้งหมด
            $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.cb').prop('checked', true);
                } else {
                    $('#myTable').find('input.cb').prop('checked', false);
                }

            });

        });
        function getValue() {
            var checks = document.getElementsByClassName('cb');
            var str = '';

            for (var i = 0; checks[i]; i++) {
                if (checks[i].checked === true) {
                    str += checks[i].value + ',';
                }
            }
            $('#getID').html('<input id="id_del" name="id_del" value="'+str+'" hidden>');
        }

        function confirm_delete(id) {
            if (id!=undefined){
                $.ajax({
                    type: "POST",
                    url: "{{url('/resurv/results_product/delete')}}",
                    datatype: "html",
                    data: {
                        id: id,
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        if (data.status == "success") {
                            window.location.href = "{{url('/resurv/results_product')}}"
                        } else if (data.status == "error") {
                            alert(data.message);
                        } else {
                            alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                        }
                    }
                });
            } else{
                getValue()
                var get_id = $('#id_del').val()
                $.ajax({
                    type: "POST",
                    url: "{{url('/resurv/results_product/delete')}}",
                    datatype: "html",
                    data: {
                        id: get_id,
                        '_token': "{{ csrf_token() }}",
                    },
                    success: function (data) {
                        if (data.status == "success") {
                            window.location.href = "{{url('/resurv/results_product')}}"
                        } else if (data.status == "error") {
                            alert(data.message);
                        } else {
                            alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                        }
                    }
                });
            }
        }

        function update_status_on() {
            getValue()
            var get_id = $('#id_del').val()
            $.ajax({
                type: "POST",
                url: "{{url('/resurv/results_product/update_status_on')}}",
                datatype: "html",
                data: {
                    id: get_id,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/resurv/results_product')}}"
                    } else if (data.status == "error") {
                        alert(data.message);
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });
        }
        function update_status_off() {
            getValue()
            var get_id = $('#id_del').val()
            $.ajax({
                type: "POST",
                url: "{{url('/resurv/results_product/update_status_off')}}",
                datatype: "html",
                data: {
                    id: get_id,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/resurv/results_product')}}"
                    } else if (data.status == "error") {
                        alert(data.message);
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });
        }

        function UpdateState(state) {

            if ($('#myTable').find('input.cb:checked').length > 0) {//ถ้าเลือกแล้ว
                $('#myTable').find('input.cb:checked').appendTo("#myFormState");
                $('#state').val(state);
                $('#myFormState').submit();
            } else {//ยังไม่ได้เลือก
                if (state == '1') {
                    alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
                } else {
                    alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
                }
            }

        }

    </script>

@endpush

@extends('layouts.master')
@push('css')

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
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        @if($c_year!=null)
            <div class="alert alert-danger">
                <b>ทำแผนประจำปี {{$select_year}} แล้ว</b>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="box-title">ระบบการทำแผนตรวจติดตาม</h1>
                            <hr class="hr-line bg-primary">
                        </div>
                    </div>

                    <div style="margin-top: 20px">
                        {!! Form::model($filter, ['url' => '/csurv/control_follow/create', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="col-md-2" style="display: flex; align-items: center;">
                            {!! Form::label('perPage', 'Show:', ['class' => 'col-md-3 control-label label-filter']) !!}
                            <div class="col-md-6">
                                {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control', 'onchange'=>'this.form.submit()' , 'disabled']); !!}
                            </div>
                        </div>
                        <div class="col-md-4" style="display: flex; align-items: center;">
                            {!! Form::label('filter_tb3_Tisno', 'ทำแผนประจำปี:', ['class' => 'col-md-4 control-label label-filter']) !!}
                            <div class="col-md-6">
                                {{--                                {!! Form::select('filter_tb3_Tisno', HP::YearList(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกปี-', 'onchange'=>'this.form.submit()']); !!}--}}
                                <select class="form-control" name="select_year" onchange="this.form.submit();" disabled>
                                    @if($select_year!=null)
                                        <option>{{$select_year}}</option>
                                    @else
                                        <option>-เลือกปี-</option>
                                    @endif
                                    @foreach(HP::YearList() as $list)
                                        <option>{{$list}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <form id="form_data" method="post" enctype="multipart/form-data">
                            <meta name="csrf-token" content="{{ csrf_token() }}">

                            <input value="{{auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname}}"
                                   name="check_officer"
                                   hidden>
                            <input name="make_annual" value="{{$select_year}}" hidden>
                            <input name="id" value="{{$data->id}}" hidden>
                            <input name="status" id="status" hidden>

                            <div class="text-right m-b-10">
                                <a class="btn btn-warning btn-sm waves-effect waves-light m-r-10" href="{{ url('/csurv/control_follow') }}">
                                    <span class="btn-label"><i class="fa fa-arrow-left"></i></span><b>กลับ</b>
                                </a>
                                <button class="btn btn-info btn-sm waves-effect waves-light m-r-10"
                                        type="submit" onclick="add_status('บันทึก')">
                                    <b>บันทึก</b>
                                </button>
                                <button class="btn btn-success btn-sm waves-effect waves-light"
                                        type="submit" onclick="add_status('Excel')">
                                    <b>Export Excel</b>
                                </button>
                            </div>
                    </div>
                    <table class="table table-bordered" id="myTable">
                        <thead>
                        <tr bgcolor="#0283CC">
                            <th rowspan="2" style="width: 16%;color: white">ชื่อผู้ประกอบการ</th>
                            <th rowspan="2" style="width: 18%;color: white">ที่อยู่</th>
                            <th rowspan="2" style="width: 12%;color: white">เดือนที่ตรวจ</th>
                            <th rowspan="2" style="width: 6%;color: white">เกรดเดิม</th>
                            <th colspan="3" style="width: 10%;color: white">Self-Declaration</th>
                            <th colspan="2" style="width: 10%;color: white">ปีที่ตรวจครั้งล่าสุด</th>
                            <th rowspan="2" style="width: 10%;color: white">พิจารณาเกรด</th>
                        </tr>
                        <tr bgcolor="#0283CC">
                            <th style="width: 8%;color: white">การแจ้งข้อมูล</th>
                            <th style="width: 8%;color: white">การตรวจระบบควบคุมคุณภาพ</th>
                            <th style="width: 8%;color: white">ผลทดสอบผลิตภัณฑ์</th>
                            <th style="width: 8%;color: white">ตรวจติดตาม</th>
                            <th style="width: 8%;color: white">ตรวจควบคุม</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($control_follow!= null)
                            @foreach($control_follow as $list)
                                <input name="num_row[]" hidden>
                                <input name="id_Autono[]" value="{{$list->id_Autono}}" hidden>
                                <tr>
                                    <td>
                                        <input value="{{$list->operator_name}}" name="operator_name[]" hidden><label>{{$list->operator_name}}</label>
                                    </td>
                                    <td>
                                        <input value="{{$list->address}}" name="address[]" hidden><label>{{$list->address}}</label>
                                    </td>
                                    <td>
                                        <select name="month_check[]" class="form-control">
                                            @if($list->month_check != null)
                                                <option> {{$list->month_check}} </option>
                                            @endif
                                            <option> มกราคม </option>
                                            <option> กุมภาพันธ์ </option>
                                            <option> มีนาคม </option>
                                            <option> เมษายน </option>
                                            <option> พฤษภาคม </option>
                                            <option> มิถุนายน </option>
                                            <option> กรกฎาคม </option>
                                            <option> สิงหาคม </option>
                                            <option> กันยายน </option>
                                            <option> ตุลาคม </option>
                                            <option> พฤศจิกายน </option>
                                            <option> ธันวาคม </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input value="{{$list->original_grade}}" name="original_grade[]" hidden><label>{{$list->original_grade}}</label>
                                    </td>
                                    <td>
                                        <input value="{{$list->notification}}" name="notification[]" hidden><label>{{$list->notification}}</label>
                                    </td>
                                    <td>
                                        <input value="{{$list->system_control_check}}" name="system_control_check[]" hidden><label>{{$list->system_control_check}}</label>
                                    </td>
                                    <td>
                                        <input value="{{$list->Product_test_results}}" name="Product_test_results[]" hidden><label>{{$list->Product_test_results}}</label>
                                    </td>
                                    <td>
                                        <input value="{{$list->follow_check}}" name="follow_check[]" hidden><label>{{$list->follow_check}}</label>
                                    </td>
                                    <td>
                                        <input value="{{$list->control_check}}" name="control_check[]" hidden><label>{{$list->control_check}}</label>
                                    </td>
                                    <td>
                                        <select name="consider_grades[]" class="form-control">
                                            @if($list->consider_grades != null)
                                                <option> {{$list->consider_grades}} </option>
                                            @endif
                                            <option> X </option>
                                            <option> H </option>
                                            <option> M </option>
                                            <option> L </option>
                                            <option> อื่นๆ </option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="pagination-wrapper">
                        @if($control_follow!=null)
                            {!!
                                $control_follow->appends(['search' => Request::get('search'),
                                                        'sort' => Request::get('sort'),
                                                        'direction' => Request::get('direction'),
                                                        'perPage' => Request::get('perPage'),
                                                        'filter_state' => Request::get('filter_state')
                                                       ])->links()
                            !!}
                        @endif
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
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
                url: "{{url('/csurv/control_follow/update')}}",
                datatype: "script",
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/csurv/control_follow')}}"
                    } else if (data.status == "excel") {
                        window.location.href = "{{url('/csurv/control_follow/excel')}}" +'/'+ data.id
                    }else if (data.status == "error") {
                        alert(data.message)
                    } else {
                        alert('ระบบขัดข้อง โปรดตรวจสอบ !');
                    }
                }
            });

        });
        function add_status(name) {
            document.getElementById('status').value = name
        }

    </script>
@endpush

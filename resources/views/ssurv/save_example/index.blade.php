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
                    <h3 class="box-title pull-left">ระบบใบรับ - นำส่งตัวอย่าง</h3>
                    <div class="pull-right">
                    @can('add-'.str_slug('receive_volume'))
                        <a class="btn btn-success btn-sm waves-effect waves-light"
                            href="{{ url('/ssurv/save_example/create') }}">
                            <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                        </a>
                    @endcan
                        <button class="btn btn-danger btn-sm waves-effect waves-light" id="delete_data">
                            <span class="btn-label"><i class="fa fa-trash"></i></span><b>ลบ</b>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <fieldset class="row">
                    {!! Form::model($filter, ['url' => '/ssurv/save_example', 'method' => 'get', 'id' => 'myFilter']) !!}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    {{-- <div class="input-group"> --}}
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_text_search', 'placeholder'=>'ค้นหา ผู้ได้รับใบอนุญาต']); !!}
                                            {{-- <span class="input-group-btn">
                                                <button type="submit" class="btn btn-lg btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>
                                            </span> --}}
                                    {{-- </div> --}}
                                </div><!-- /form-group -->
                            </div><!-- /.col-lg-4 -->
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group  pull-left">
                                    <button type="submit" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;">ค้นหา</button>

                                </div>

                                <div class="form-group  pull-left m-l-15">
                                    <button type="button" class="btn btn-warning waves-effect waves-light" id="filter_clear">
                                        ล้าง
                                    </button>
                                </div>
                            </div><!-- /.col-lg-1 -->
                            <div class="col-lg-5">
                                <div class="form-group col-md-7">
                                    <div class="col-md-12">
                                        {!! Form::select('filter_status', ['0'=>'ฉบับร่าง','ยกเลิก'=>'ยกเลิก','9'=>'ส่งข้อมูลแล้ว'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5">
                                        {!! Form::label('perPage', 'Show', ['class' => 'col-md-4 control-label label-filter']) !!}
                                        <div class="col-md-8">
                                            {!! Form::select('perPage', ['10'=>'10', '20'=>'20', '50'=>'50', '100'=>'100', '500'=>'500'], null, ['class' => 'form-control']); !!}
                                        </div>
                                    </div>
                            </div><!-- /.col-lg-5 -->
                        </div><!-- /.row -->

                    	<div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_tb3_Tisno', 'มาตรฐาน', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_tb3_Tisno', HP::TisListSample(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกมาตรฐาน-']); !!}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_submission_date_start', 'วันที่ตรวจ', ['class' => 'col-md-3 control-label label-filter']) !!}
                                    <div class="col-md-9">
                                        <div class="input-daterange input-group" id="date-range">
                                            {!! Form::text('filter_submission_date_start', null, ['class' => 'form-control datepicker', 'placeholder'=>'เริ่มต้น']); !!}
                                            <span class="input-group-addon bg-info b-0 text-white">ถึง</span>
                                            {!! Form::text('filter_submission_date_end', null, ['class' => 'form-control datepicker', 'placeholder'=>'สิ้นสุด']); !!}
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_department', 'กลุ่มงานหลัก', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                        {!! Form::select('filter_department', App\Models\Besurv\Department::whereIn('did',[10,11,12])->pluck('depart_name', 'did'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลัก-']); !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_sub_department', 'กลุ่มงานย่อย', ['class' => 'col-md-3 control-label label-filter']) !!}
                                        <div class="col-md-9">
                                        {!! Form::select('filter_sub_department', !empty($subDepartments)?$subDepartments:[], null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานหลักย่อย-']); !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table color-bordered-table primary-bordered-table" id="myTable">
                                    <thead>
                                    <tr>
                                        <th style="width: 2%;">No.</th>
                                        <th style="width: 2%;"><input type="checkbox" id="checkall"></th>
                                        <th style="width: 7%;">เลขที่อ้างอิง</th>
                                        <th style="width: 12%;">ผู้ได้รับใบอนุญาต</th>
                                        <th style="width: 7%;">เลข มอก.</th>
                                        <th style="width: 15%;">ชื่อมาตรฐาน</th>
                                        <th style="width: 7%;">@sortablelink('sample_submission_date','วันที่ตรวจ', ['filter' => 'active, visible'], ['style'=>'color:white;'])</th>
                                        <th style="width: 7%;">ผู้จ่ายตัวอย่าง</th>
                                        <th style="width: 7%;">สถานะ</th>
                                        <th style="width: 8%;">เครื่องมือ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($save_example as $item)
                                        <tr>
                                            <td>{{ $temp_num++ }}</td>
                                            <td><input type="checkbox" name="cb[]" class="cb" value="{{ $item->id }}"></td>
                                            <td>{{ $item->no }}</td>
                                            <td>{{ $item->licensee }}</td>
                                            <td>{{ $item->tis_standard }}</td>
                                            <td>{{ $item->tis->tb3_TisThainame }}</td>

                                            <td>{{ HP::DateThai($item->sample_submission_date) }}</td>
                                            <td>
                                                @if($item->sample_pay!=null)
                                                    {{ $item->sample_pay }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status == '0')
                                                    ฉบับร่าง
                                                @elseif($item->status == 'ยกเลิก')
                                                    ยกเลิก
                                                @else
                                                    ส่งข้อมูลแล้ว
                                                @endif
                                            </td>
                                            <td>
                                                {{--                                                    <a href="{{url('ssurv/save_example/'.$item->id)}}" class="btn btn-info btn-xs">--}}
                                                {{--                                                        <i class="fa fa-eye" aria-hidden="true"></i>--}}
                                                {{--                                                    </a>--}}
                                                @if($item->status=='0')
                                                    <a href="{{url('ssurv/save_example/'.$item->id.'/edit')}}"
                                                       class="btn btn-primary btn-xs">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                    </a>
                                                @elseif($item->status!='0'||$item->status!='4')
                                                    <a href="{{url('/ssurv/save_example/detail/'.$item->id.'/')}}"
                                                       class="btn btn-info btn-xs">
                                                        <i class="fa fa-info-circle" aria-hidden="true"> </i>
                                                    </a>
                                                @else
                                                    <a disabled="true" class="btn btn-primary btn-xs">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"> </i>
                                                    </a>
                                                @endif
                                                @if($item->status=='0')
                                                    <button class="btn btn-danger btn-xs"
                                                            onclick="remove_example({{$item->id}});">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-danger btn-xs" disabled>
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </button>
                                                @endif
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
                                                                      'filter_search' => Request::get('filter_search'),
                                                                      'filter_status' => Request::get('filter_status'),
                                                                      'filter_tb3_Tisno' => Request::get('filter_tb3_Tisno'),
                                                                      'filter_submission_date_start' => Request::get('filter_submission_date_start'),
                                                                      'filter_submission_date_end' => Request::get('filter_submission_date_end'),
                                                                      'filter_department' => Request::get('filter_department'),
                                                                      'filter_sub_department' => Request::get('filter_sub_department')
                                                                     ]);
                                    @endphp
                                    {!!
                                        $save_example->appends($page)->links()
                                    !!}
                                </div>

                            </div>
                            <div id="getID"></div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>

    <script>

    $(function(){

        $("#filter_tb3_Tisno").select2({minimumInputLength: 2});

        $('.datepicker').datepicker({language:'th-th',format:'dd/mm/yyyy'})

            $( "#filter_clear" ).click(function() {
                // alert('sofksofk');
                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#filter_tb3_Tisno').val('').select2();
                $('#filter_submission_date_start').val('');
                $('#filter_submission_date_end').val('');
                $('#filter_department').val('').select2();
                $('#filter_sub_department').val('').select2();
                // $('form').submit();
                window.location.assign("{{url('/ssurv/save_example')}}");
            });
            if($('#filter_tb3_Tisno').val()!="" || $('#filter_submission_date_start').val()!="" || $('#filter_submission_date_start').val()!="" || $('#filter_department').val()!="" || $('#filter_sub_department').val()!=""){
                // alert('มีค่า');
                $("#search_btn_all").click();
                $("#search_btn_all").removeClass('btn-primary').addClass('btn-success');
                $("#search_btn_all > span").removeClass('glyphicon-menu-up').addClass('glyphicon-menu-down');

            }
            $("#search_btn_all").click(function(){
                $("#search_btn_all").toggleClass('btn-primary btn-success', 'btn-success btn-primary');
                $("#search_btn_all > span").toggleClass('glyphicon-menu-up glyphicon-menu-down', 'glyphicon-menu-down glyphicon-menu-up');
            });


         $('#filter_department').change(function(){
            //  alert('มาแล้ว');
            var department_id = $(this).val();
            if(department_id!=""){
              $.ajax({
                type: "GET",
                url: "{{url('/ssurv/save_example/add_sub_department')}}",
                datatype: "html",
                data: {
                    department_id: department_id,
                    '_token': "{{ csrf_token() }}",
                },
                success: function (data) {
                    $("#filter_sub_department").html('');
                    var response = data;
                    var list = response.data;
                    var opt;
                    opt += "<option value=''>-เลือกกลุ่มงานหลักย่อย-</option>";
                    $.each(list, function (key, val) {
                        opt += "<option value='" + key + "'>" + val + "</option>";
                    });
                    $("#filter_sub_department").html(opt).trigger("change");
                }
              });
            }
    });

    });





        function remove_example(id) {
            if (confirm('ยินยันการลบข้อมูล ?') === true) {
                // alert(Organize_ID);
                $.ajax({
                    type: "POST",
                    url: "{{url('/ssurv/save_example/delete')}}",
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

        function getValue() {
            var checks = document.getElementsByClassName('cb');
            var str = '';

            for (var i = 0; checks[i]; i++) {
                if (checks[i].checked === true) {
                    str += checks[i].value + ',';
                }
            }
            $('#getID').html('<input id="id_delete" name="id_delete" value="' + str + '" hidden>');
        }

        $('#delete_data').click(function () {
            getValue();
            var id = $('#id_delete').val();

            if (confirm('ยินยันการลบข้อมูล ?') === true) {
                if (id.length != 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{url('/ssurv/save_example/delete_select')}}",
                        datatype: "html",
                        data: {
                            id: id,
                            '_token': "{{ csrf_token() }}",
                        },
                        success: function (data) {
                            window.location.reload();
                        }
                    })
                }
            }
        });

        $('#checkall').change(function () {
            $('.cb').prop("checked", $(this).prop("checked"))
        })

        $(document).ready(function () {

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif

            // //เลือกทั้งหมด
            // $('#checkall').change(function(event) {
            //
            //   if($(this).prop('checked')){//เลือกทั้งหมด
            //     $('#myTable').find('input.cb').prop('checked', true);
            //   }else{
            //     $('#myTable').find('input.cb').prop('checked', false);
            //   }
            //
            // });

        });

        // function Delete(){
        //
        //   if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
        //     if(confirm_delete()){
        //       $('#myTable').find('input.cb:checked').appendTo("#myForm");
        //       $('#myForm').submit();
        //     }
        //   }else{//ยังไม่ได้เลือก
        //     alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
        //   }
        //
        // }
        //
        // function confirm_delete() {
        //     return confirm("ยืนยันการลบข้อมูล?");
        // }
        //
        // function UpdateState(state){
        //
        //   if($('#myTable').find('input.cb:checked').length > 0){//ถ้าเลือกแล้ว
        //       $('#myTable').find('input.cb:checked').appendTo("#myFormState");
        //       $('#state').val(state);
        //       $('#myFormState').submit();
        //   }else{//ยังไม่ได้เลือก
        //     if(state=='1'){
        //       alert("กรุณาเลือกข้อมูลที่ต้องการเปิด");
        //     }else{
        //       alert("กรุณาเลือกข้อมูลที่ต้องการปิด");
        //     }
        //   }
        //
        // }

    </script>

@endpush

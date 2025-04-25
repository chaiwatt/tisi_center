@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

@endpush

@section('content')

    @php
        $example_map_lab_model = new App\Models\Ssurv\SaveExampleMaplap;
        $option_tis_number = App\Models\Basic\Tis::select(DB::raw("CONCAT(tb3_Tisno, ' : ',tb3_TisThainame) AS title, tb3_Tisno"))
                                                 ->get()
                                                 ->pluck('title', 'tb3_Tisno');                                      
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานการทดสอบผลิตภัณฑ์ (กต.)</h3>

                    <div class="pull-right">

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก ชื่อผู้ตรวจสอบ, ชื่อผู้ประกอบการ, เลขใบรับ-นำส่งตัวอย่าง']); !!}
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                            <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                            ล้าง
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="form-group col-md-12">
                                        {!! Form::select('filter_tis_number',  $option_tis_number , null, ['class' => 'form-control', 'id' => 'filter_tis_number', 'placeholder'=>'-เลือกมอก.-']); !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_status', 'สถานะใบรับ-นำส่งตัวอย่าง:', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_status', $example_map_lab_model->status_list(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะใบรับ-นำส่งตัวอย่าง-']); !!}
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
                    <div class="clearfix"></div>

                    <h2 class="text-center">รายงานการทดสอบผลิตภัณฑ์ (กต.)</h2>
                    <div class="text-center">ข้อมูล ณ วันที่ {{ HP::DateTimeFullThai(date('Y-m-d H:i')) }}</div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="1%" class="text-center">ลำดับ</th>
                                        <th width="21%" class="text-center">ชื่อผู้ตรวจสอบ</th>
                                        <th width="10%" class="text-center">เลข มอก.</th>
                                        <th width="24%" class="text-center">ผู้ประกอบการ</th>
                                        <th width="19%" class="text-center">อ้างอิงใบรับ-นำส่งตัวอย่าง</th>
                                        <th width="12%" class="text-center">สถานะใบรับ-นำส่งตัวอย่าง</th>
                                        <th width="13%" class="text-center">รายละเอียดผลทดสอบ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script>

        $(function () {

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/report-example-lab/data_list') !!}',
                    data: function (d) {

                        d.filter_search     = $('#filter_search').val();
                        d.filter_tis_number = $('#filter_tis_number').val();
                        d.filter_status     = $('#filter_status').val();

                        d.filter_start_checking_date = $('#filter_start_checking_date').val();
                        d.filter_end_checking_date   = $('#filter_end_checking_date').val();

                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name_lap', name: 'name_lap' },
                    { data: 'tis_standard', name: 'tis_standard' },
                    { data: 'licensee', name: 'licensee' },
                    { data: 'no_example_id', name: 'no_example_id' },
                    { data: 'status', name: 'status' },
                    { data: 'test_result_link', name: 'test_result_link' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1] },
                    { className: "text-top text-left", targets:[6] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                },
                initComplete:function( settings, json ) {
                    $('[data-toggle="tooltip"]').tooltip({
                        animation: true,
                        trigger: 'hover focus',
                        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="font-size: 15px;"></div></div>'
                    });
                }
            });

            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_search, #filter_tis_number').val('');
                $('#search-btn').find('select').val('').select2();
                $('#search-btn').find('input').val('');
                $('#filter_tis_number').val('').select2();
                table.draw();
            });

        });

    </script>
@endpush

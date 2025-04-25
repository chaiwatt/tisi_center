@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

@endpush

@section('content')

    @php
        $factory_detail_model = new App\Models\Elicense\Rform\FactoryDetail;
        $option_tis_number = App\Models\Basic\Tis::select(DB::raw("CONCAT(tb3_Tisno, ' : ',tb3_TisThainame) AS title, tb3_Tisno"))
                                                 ->get()
                                                 ->pluck('title', 'tb3_Tisno');   
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานการตรวจโรงงาน</h3>

                    <div class="pull-right">

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก ชื่อหน่วยตรวจสอบ, เลขที่คำขอ']); !!}
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
                                        {!! Form::select('filter_tis_number',$option_tis_number , null, ['class' => 'form-control', 'id' => 'filter_tis_number', 'placeholder'=>'-เลือกมอก.-']); !!}
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
                                                        {!! Form::label('filter_status', 'สถานะตอบรับคำขอ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_status', $factory_detail_model->status_list(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะตอบรับคำขอ-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_inspect_status', 'สถานะการตรวจโรงงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_inspect_status', $factory_detail_model->inspect_status_list(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะการตรวจโรงงาน-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_inspect_result', 'ผลตรวจโรงงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_inspect_result', $factory_detail_model->inspect_result_list(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกผลตรวจโรงงาน-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_checking_date', 'วันที่ตอบรับคำขอ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_start_checking_date', null, ['class' => 'form-control', 'id' => 'filter_start_checking_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_end_checking_date', null, ['class' => 'form-control', 'id'=>'filter_end_checking_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_inspect_date', 'วันที่ตรวจ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_start_inspect_date', null, ['class' => 'form-control','id'=>'filter_start_inspect_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_end_inspect_date', null, ['class' => 'form-control','id'=>'filter_end_inspect_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_report_date', 'วันที่รายงานผล', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_start_report_date', null, ['class' => 'form-control', 'id' => 'filter_start_report_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_end_report_date', null, ['class' => 'form-control', 'id' => 'filter_end_report_date']) !!}
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
                    </div>
                    <div class="clearfix"></div>

                    <h2 class="text-center">รายงานการตรวจโรงงาน</h2>
                    <div class="text-center">ข้อมูล ณ วันที่ {{ HP::DateTimeFullThai(date('Y-m-d H:i')) }}</div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%" class="text-center">ลำดับ</th>
                                        <th width="10%" class="text-center">ชื่อผู้ตรวจสอบ</th>
                                        <th width="14%" class="text-center">เลข มอก.</th>
                                        <th width="14%" class="text-center">อ้างอิงคำขอเลขที่</th>
                                        <th width="10%" class="text-center">สถานะตอบรับคำขอ</th>
                                        <th width="14%" class="text-center">วันที่ตอบรับคำขอ</th>
                                        <th width="14%" class="text-center">สถานะการตรวจโรงงาน</th>
                                        <th width="8%" class="text-center">วันที่ตรวจ</th>
                                        <th width="5%" class="text-center">ผลตรวจโรงงาน</th>
                                        <th width="6%" class="text-center">วันที่รายงานผล</th>
                                        <th width="3%" class="text-center">ไฟล์ตรวจโรงงาน</th>
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
                    url: '{!! url('/section5/report-factory-inspection/data_list') !!}',
                    data: function (d) {

                        d.filter_search         = $('#filter_search').val();
                        d.filter_tis_number     = $('#filter_tis_number').val();
                        d.filter_status         = $('#filter_status').val();
                        d.filter_inspect_status = $('#filter_inspect_status').val();
                        d.filter_inspect_result = $('#filter_inspect_result').val();

                        d.filter_start_checking_date = $('#filter_start_checking_date').val();
                        d.filter_end_checking_date   = $('#filter_end_checking_date').val();

                        d.filter_start_inspect_date = $('#filter_start_inspect_date').val();
                        d.filter_end_inspect_date   = $('#filter_end_inspect_date').val();

                        d.filter_start_report_date = $('#filter_start_report_date').val();
                        d.filter_end_report_date   = $('#filter_end_report_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'auditor', name: 'auditor' },
                    { data: 'tis_number', name: 'tis_number' },
                    { data: 'refno', name: 'refno' },
                    { data: 'status', name: 'status' },
                    { data: 'checking_date', name: 'checking_date' },
                    { data: 'inspect_status', name: 'inspect_status' },
                    { data: 'inspect_date_period', name: 'inspect_date_period' },
                    { data: 'inspect_result', name: 'inspect_result' },
                    { data: 'report_date', name: 'report_date' },
                    { data: 'inspect_report_file', name: 'inspect_report_file' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2] },
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

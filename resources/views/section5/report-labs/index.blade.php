@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

@endpush

@section('content')

    @php
        $factory_detail_model = new App\Models\Elicense\Rform\FactoryDetail;

        $option_tis_number = App\Models\Basic\Tis::select(DB::raw("CONCAT(tb3_Tisno, ' : ',tb3_TisThainame) AS title, tb3_Tisno"))->get()
                                                 ->pluck('title', 'tb3_Tisno');
        $option_staff = App\Models\Section5\ApplicationLabStaff::leftJoin('user_register', 'section5_application_labs_staff.staff_id', '=', 'user_register.runrecno')->groupBy('staff_id')
                                                    ->orderby(DB::raw("CONVERT(`user_register`.`reg_fname` USING tis620)"),"asc")->get()
                                                    ->pluck('StaffName', 'staff_id');
        $option_status = App\Models\Section5\ApplicationLabStatus::get()->pluck('title', 'id');
    @endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานคำขอรับการแต่งตั้งเป็นผู้ตรวจสอบ LAB</h3>

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
                                        {!! Form::select('filter_tis_number', $option_tis_number, null, ['class' => 'form-control', 'id' => 'filter_tis_number', 'placeholder'=>'-เลือกมอก.-']); !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_audit_type', 'การประเมิน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_audit_type', [ '1' => '17025', '2' => 'ภาคผนวก ก'], null, ['class' => 'form-control', 'placeholder'=>'-เลือกการประเมิน-']); !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_status', $option_status, null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_staff', 'เจ้าหน้าที่รับผิดชอบ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_staff', $option_staff, null, ['class' => 'form-control', 'placeholder'=>'-เลือกเจ้าหน้าที่รับผิดชอบ-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_date', 'วันที่ยื่นคำขอ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_start_date', null, ['class' => 'form-control', 'id' => 'filter_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_end_date', null, ['class' => 'form-control', 'id'=>'filter_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_audit_start_date', 'วันที่ตรวจประเมิน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_audit_start_date', null, ['class' => 'form-control', 'id' => 'filter_audit_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_audit_end_date', null, ['class' => 'form-control', 'id'=>'filter_audit_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_board_meeting_start_date', 'วันที่ประชุม', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_board_meeting_start_date', null, ['class' => 'form-control','id'=>'filter_board_meeting_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_board_meeting_end_date', null, ['class' => 'form-control','id'=>'filter_board_meeting_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_announcement_start_date', 'วันที่ประกาศราชกิจจา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_announcement_start_date', null, ['class' => 'form-control', 'id' => 'filter_announcement_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_announcement_end_date', null, ['class' => 'form-control', 'id' => 'filter_announcement_end_date']) !!}
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

                    <h2 class="text-center">รายงานคำขอรับการแต่งตั้งเป็นผู้ตรวจสอบ LAB</h2>
                    <div class="text-center">ข้อมูล ณ วันที่ {{ HP::DateTimeFullThai(date('Y-m-d H:i')) }}</div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                @if(auth()->user()->can('export-'.str_slug('report-labs')))
                                    <button class="btn btn-success waves-effect waves-light m-l-5" type="button" name="btn_export" id="btn_export">Excel</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%" class="text-center">No.</th>
                                        <th width="2%"><input type="checkbox" id="checkall"></th>
                                        <th width="12%" class="text-center">เลขที่คำขอ/วันที่ยื่นคำขอ</th>
                                        <th width="8%" class="text-center">การประเมิน</th>
                                        <th width="" class="text-center">ชื่อห้องปฎิบัติการ<br>ผู้ยื่นคำขอ</th>
                                        <th width="9%" class="text-center">เลขผู้เสียภาษี</th>
                                        <th width="8%" class="text-center">เลขที่ มอก.</th>
                                        <th width="10%" class="text-center">วันที่ตรวจประเมิน</th>
                                        <th width="10%" class="text-center">วันที่ประชุม</th>
                                        <th width="10%" class="text-center">วันที่ประกาศราชกิจจา</th>
                                        <th width="10%" class="text-center">สถานะ</th>
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
                    url: '{!! url('/section5/report-labs/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_audit_type = $('#filter_audit_type').val();
                        d.filter_status = $('#filter_status').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();

                        d.filter_audit_start_date = $('#filter_audit_start_date').val();
                        d.filter_audit_end_date = $('#filter_audit_end_date').val();

                        d.filter_board_meeting_start_date = $('#filter_board_meeting_start_date').val();
                        d.filter_board_meeting_end_date = $('#filter_board_meeting_end_date').val();

                        d.filter_announcement_start_date = $('#filter_announcement_start_date').val();
                        d.filter_announcement_end_date = $('#filter_announcement_end_date').val();

                        d.filter_tis_number = $('#filter_tis_number').val();
                        d.filter_staff = $('#filter_staff').val();

                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'application_no', name: 'application_no' },
                    { data: 'audit_type', name: 'audit_type' },
                    { data: 'applicant_name', name: 'authorized_name' },
                    { data: 'applicant_taxid', name: 'applicant_taxid' },
                    { data: 'standards', name: 'standards' },
                    { data: 'audit_date', name: 'audit_date' },
                    { data: 'board_meeting_date', name: 'board_meeting_date' },
                    { data: 'announcement_date', name: 'announcement_date' },
                    { data: 'status_application', name: 'status_application' },
              
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
                        // placement: 'top',
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

            $(document).on('click', '#btn_export', function(){
                export_excel();
            });

        });

        function export_excel(){

            var url = 'section5/report-labs/export_excel';
                url += '?filter_search='    + $('#filter_search').val();
                url += '&filter_audit_type='              + $('#filter_audit_type').val();
                url += '&filter_status='              + $('#filter_status').val();
                url += '&filter_start_date='            + $('#filter_start_date').val();
                url += '&filter_end_date='        + $('#filter_end_date').val();
                url += '&filter_audit_start_date='              + $('#filter_audit_start_date').val();
                url += '&filter_audit_end_date='          + $('#filter_audit_end_date').val();
                url += '&filter_board_meeting_start_date='          + $('#filter_board_meeting_start_date').val();
                url += '&filter_board_meeting_end_date='          + $('#filter_board_meeting_end_date').val();
                url += '&filter_announcement_start_date='          + $('#filter_announcement_start_date').val();
                url += '&filter_announcement_end_date='          + $('#filter_announcement_end_date').val();
                url += '&filter_tis_number='          + $('#filter_tis_number').val();
                url += '&filter_staff='          + $('#filter_staff').val();

            window.location = '{!! url("'+ url +'") !!}' ;

        }

    </script>
@endpush

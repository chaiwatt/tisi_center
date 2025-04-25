@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">


                    <h3 class="box-title pull-left">ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('application-lab-audit'))

                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="btn_gen_report">
                                <i class="fa fa-pencil" aria-hidden="true"></i> สร้างรายงาน
                            </button>

                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light" id="btn_report">
                                <i class="fa fa-paste" aria-hidden="true"></i> บันทึกสรุปรายงาน
                            </button>
                                
                            {{-- <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_checkings">
                                <i class="fa fa-check-square-o" aria-hidden="true"></i> บันทึกผลตรวจประเมิน
                            </button>
                            
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light" id="btn_report">
                                <i class="fa fa-paste" aria-hidden="true"></i> บันทึกสรุปรายงาน
                            </button>

                            <button type="button" class="btn btn-warning btn-sm waves-effect waves-light" id="btn_approve">
                                <i class="icon-note" aria-hidden="true"></i> อนุมัติผลตรวจประเมิน
                            </button> --}}
                        @endcan

                    </div>
                    <hr class="hr-line bg-primary m-t-0">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB)</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขที่คำขอ/ผู้ยื่นคำขอ/เลขผู้เสียภาษี/มอก.']); !!}
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                                <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                            </button>
                                        </div>
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                        </div>   
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">ล้าง</button>
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::select('filter_status', App\Models\Section5\ApplicationLabStatus::pluck('title', 'id')->all(), null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_tis_id', 'มอก.', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::text('filter_tis_id', null, ['class' => 'form-control', 'id'=> 'filter_tis_id', 'placeholder'=>'-เลือกมอก-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_applicant_type', 'ประเภทคำขอ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_applicant_type', [ 1 => 'ขอขึ้นทะเบียนใหม่', 2 => 'ขอเพิ่มเติมขอบข่าย'], null, ['class' => 'form-control', 'id'=> 'filter_applicant_type', 'placeholder'=>'-เลือกประเภทคำขอ-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_audit_result', 'ผลตรวจประเมิน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_audit_result', ['-1' => 'รอดำเนินการ', '1' => 'ผ่าน', '2' => 'ไม่ผ่าน'], null, ['class' => 'form-control', 'id'=> 'filter_audit_result', 'placeholder'=>'-เลือกผลตรวจประเมิน-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_audit_type', 'ใบรับรองระบบงานตามฐาน 17025', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_audit_type', [ 1 => 'ตรวจตามใบรับรอง', 2 => 'ตรวจตามภาคผนวก ก.'], null, ['class' => 'form-control', 'id'=> 'filter_audit_type', 'placeholder'=>'-เลือกใบรับรองระบบงานตามฐาน 1702-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_assign_start_date', 'วันที่มอบหมาย', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_assign_start_date', null, ['class' => 'form-control','id'=>'filter_assign_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_assign_end_date', null, ['class' => 'form-control','id'=>'filter_assign_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_assign_start_date', 'วันที่สรุปรายงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_report_start_date', null, ['class' => 'form-control','id'=>'filter_report_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_report_end_date', null, ['class' => 'form-control','id'=>'filter_report_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_date', 'วันที่ยื่นคำขอ', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
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

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%"><input type="checkbox" id="checkall"></th>
                                        <th width="2%" class="text-center">No.</th>
                                        <th width="10%" class="text-center">เลขที่คำขอ/วันที่ยื่นคำขอ</th>
                                        <th width="16%" class="text-center">ชื่อห้องปฎิบัติการ<br>ผู้ยื่นคำขอ</th>
                                        <th width="12%" class="text-center">เลขผู้เสียภาษี</th>
                                        <th width="13%" class="text-center">เลขที่ มอก.</th>
                                        <th width="8%" class="text-center">วันที่สรุปรายงาน</th>
                                        <th width="10%" class="text-center">สถานะ/ผลประเมิน</th>
                                        <th width="13%" class="text-center">ผู้รับมอบหมาย</th>
                                        <th width="13%" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    
                    <!-- Modal ข้อมูลขอรับบริการ -->
                    @include ('section5/application-request-form/modals.application-lab-scope')

                    <!-- Modal บันทึกผลตรวจประเมิน -->
                    @include ('section5.application_lab_audit.modals.checkings')

                    <!-- Modal บันทึกสรุปรายงาน -->
                    @include ('section5.application_lab_audit.modals.reports')

                    <!-- Modal อนุมัติผลตรวจประเมิน -->
                    @include ('section5.application_lab_audit.modals.approves')

                    <!-- Modal สร้างรายงาน -->
                    @include ('section5.application_lab_audit.modals.gen-reports')

                    <!-- Modal สร้างรายงาน -->
                    @include ('section5.application_lab_audit.modals.print-reports')
                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script>
        var table = '';
        $(document).ready(function () {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                language:'th-th',
            });

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            $("#filter_tis_id").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-standards-td3') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

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

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/application_lab_audit/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();

                        d.filter_tis_id = $('#filter_tis_id').val();
                        d.filter_audit_result = $('#filter_audit_result').val();

                        d.filter_report_start_date = $('#filter_report_start_date').val();
                        d.filter_report_end_date = $('#filter_report_end_date').val();

                        d.filter_applicant_type = $('#filter_applicant_type').val();
                        d.filter_audit_type     = $('#filter_audit_type').val();
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'application_no', name: 'refno_application' },
                    { data: 'applicant_name', name: 'authorized_name' },
                    { data: 'applicant_taxid', name: 'applicant_taxid' },
                    { data: 'standards', name: 'standards' },
                    { data: 'report_date', name: 'report_date' },
                    { data: 'status_application', name: 'status_application' },
                    { data: 'assign_by', name: 'assign_by' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2,-3,-4] },
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
                $('#filter_search').val('');
                $('#search-btn').find('select').val('').select2();
                $('#search-btn').find('input').val('');
                $('#filter_status').val('').select2();
                $("#filter_tis_id").select2("val", "");
                table.draw();
            });

            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_status').change(function (e) { 
                table.draw();
            });

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked', false);
                }
            });


        });

        function LoadDataApplication(){ 

            if($('.item_checkbox:checked').length > 0){

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });

                $.ajax({
                    url: "{!! url('/section5/application_lab_audit/get-application-data') !!}" + "?id=" + id
                }).done(function( object ) {
                    $('#myTable-Mcheckings tbody').html(object);
                    $.LoadingOverlay("hide");
                });

            };
        }

    </script>

@endpush

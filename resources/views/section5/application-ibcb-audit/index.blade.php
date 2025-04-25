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


                    <h3 class="box-title pull-left">ระบบตรวจประเมิน IB/CB</h3>

                    <div class="pull-right">

                        @can('edit-'.str_slug('application-ibcb-audit'))
                            
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
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบบันทึกผลตรวจประเมิน IB/CB</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขผู้เสียภาษี/เลขที่คำขอ/เลขผู้เสียภาษี/ผู้รับมอบหมาย/สาขา/รายสาขา/มอก.']); !!}
                                    </div>
                                </div>

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
                                        {!! Form::select('filter_status', HP::ApplicationStatusIBCB() , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                                                        {!! Form::label('filter_branch_group', 'สาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_branch_group', App\Models\Basic\BranchGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_branch_group', 'placeholder'=>'-เลือกสาขา-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch', 'รายสาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_branch', App\Models\Basic\Branch::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_branch', 'placeholder'=>'-เลือกรายสาขา-']); !!}
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
                                                        {!! Form::label('filter_report_start_date', 'วันที่สรุปรายงาน', ['class' => 'col-md-12 label-filter']) !!}
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
                                        <th width="2%" class="text-center">ลำดับ</th>
                                        <th width="10%" class="text-center">เลขที่คำขอ</th>
                                        <th width="18%" class="text-center">ผู้ยื่นคำขอ/เลขผู้เสียภาษี</th>
                                        <th width="20%" class="text-center">สาขา </th>
                                        <th width="8%" class="text-center">วันที่ยื่นคำขอ</th>
                                        <th width="10%" class="text-center">สถานะ</th>
                                        <th width="8%" class="text-center">ผลตรวจประเมิน</th>
                                        <th width="13%" class="text-center">ผู้รับมอบหมาย</th>
                                        <th width="13%" class="text-center">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <!-- Modal บันทึกผลตรวจประเมิน -->
                    @include ('section5.application-ibcb-audit.modals.checkings')

                    <!-- Modal บันทึกสรุปรายงาน -->
                    @include ('section5.application-ibcb-audit.modals.reports')

                    <!-- Modal อนุมัติผลตรวจประเมิน -->
                    @include ('section5.application-ibcb-audit.modals.approves')

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
        $(document).ready(function () {
            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
        });

        $(function () {
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

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/application-ibcb-audit/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch = $('#filter_branch').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();

                        d.filter_report_start_date = $('#filter_report_start_date').val();
                        d.filter_report_end_date = $('#filter_report_end_date').val();

                        d.filter_audit_result = $('#filter_audit_result').val();
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'application_no', name: 'application_no' },
                    { data: 'applicant_name', name: 'applicant_name' },
                    { data: 'scope', name: 'scope' },
                    { data: 'application_date', name: 'application_date' },
                    { data: 'status_application', name: 'status_application' },
                    { data: 'audit_result', name: 'audit_result' },
                    { data: 'assign_by', name: 'assign_by' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,1,-1,-2,-3,-4,-5] },
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
                $('#filter_status,#filter_search,#filter_standard').val('');
                $('#search-btn').find('select').val('').select2();
                $('#search-btn').find('input').val('');
                $('#filter_status').val('').select2();
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


            $('#filter_branch_group').change(function (e) {

                $('#filter_branch').html('<option value=""> -เลือกรายสาขา- </option>');
                var value = ( $(this).val() != "" )?$(this).val():'ALL';
                if(value){
                    $.ajax({
                        url: "{!! url('/section5/get-branch-data') !!}" + "/" + value
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_branch').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    });
                }

            });

            $('#btn_checkings').click(function(event) {

                $('#myTable-Mcheckings tbody').html('');

                $('#modal_form_checkings').find('input,textarea').val('');
                $('#modal_form_checkings').find('select').val('').select2();


                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=ไม่อยู่ในเงื่อนไข
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        const announce_status = [3, 4, 7];
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะ
                            status_fail = true;
                        }
                    });

                    if(status_fail){//สถานะไม่เป็นไปตามเงื่อนไข
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกรายการ',
                            html: '<h5>ที่มีสถานะ เอกสารครบถ้วน อยู่ระหว่างตรวจประเมิน, เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน <br>หรือ ไม่ผ่านการตรวจประเมิน</h5>',
                            footer: '<h5>ตรวจสอบใหม่อีกครั้ง</h5>',
                            confirmButtonText: 'รับทราบ',
                            width:500
                        });
                        return false;
                    }

                    LoadDataApplication();

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_checkings').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขออย่างน้อย 1 คำขอ');
                }
            });

            $('#modal_form_checkings').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_checkings")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-audit/update_application_checkings') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_checkings').modal('hide');
                            $('#checkall').prop('checked', false);
                        }
                    }
                });

            });

            $('#btn_report').click(function(event) {
                
                $('#myTable-Mreport tbody').html('');
                $('#modal_form_report').find('input,textarea').val('');
                $('#modal_form_report').find('select').val('').select2();

                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=ไม่อยู่ในเงื่อนไข
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        const announce_status = [4, 7, 8];
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะ
                            status_fail = true;
                        }

                        tr_ += '<tr>';
                        tr_ += '<td class="text-top">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('application_no'))+'<input type="hidden" name="id[]" class="item_m_rp_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_taxid'))+'</td>';
                        tr_ += '</tr>';

                    });

                    if(status_fail){//สถานะไม่เป็นไปตามเงื่อนไข
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกรายการ',
                            html: '<h5>ที่มีสถานะ เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน, อนุมัติ ไม่ผ่านการตรวจประเมิน <br>หรือ อยู่ระหว่างการพิจารณาอนุมัติ</h5>',
                            footer: '<h5>ตรวจสอบใหม่อีกครั้ง</h5>',
                            confirmButtonText: 'รับทราบ',
                            width:500
                        });
                        return false;
                    }

                    $('#myTable-Mreport tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_reports').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขออย่างน้อย 1 คำขอ');
                }
            });

            $('#modal_form_report').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_report")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-audit/update_application_reports') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_reports').modal('hide');
                            $('#checkall').prop('checked', false);
                        }
                    }
                });

            });

            $('#btn_approve').click(function(event) {

                $('#myTable-Mapprove tbody').html('');
                $('#modal_form_approve').find('input,textarea').val('');
                $('#modal_form_approve').find('select').val('').select2();

                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=ไม่อยู่ในเงื่อนไข
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        const announce_status = [8, 9, 10];
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะ
                            status_fail = true;
                        }

                        tr_ += '<tr>';
                        tr_ += '<td class="text-top">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('application_no'))+'<input type="hidden" name="id[]" class="item_m_ap_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_taxid'))+'</td>';
                        tr_ += '</tr>';

                    });

                    if(status_fail){//สถานะไม่เป็นไปตามเงื่อนไข
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกรายการ',
                            html: '<h5>ที่มีสถานะ อยู่ระหว่างการพิจารณาอนุมัติ, อนุมัติ อยู่ระหว่างเสนอคณะอนุกรรมการ <br>หรือ ไม่อนุมัติ ตรวจสอบอีกครั้ง</h5>',
                            footer: '<h5>ตรวจสอบใหม่อีกครั้ง</h5>',
                            confirmButtonText: 'รับทราบ',
                            width:500
                        });
                        return false;
                    }

                    $('#myTable-Mapprove tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_approves').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขออย่างน้อย 1 คำขอ');
                }

            });

            $('#modal_form_approve').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_approve")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application-ibcb-audit/update_application_approve') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_approves').modal('hide');
                            $('#checkall').prop('checked', false);
                        }
                    }
                });

            });

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

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
                    url: "{!! url('/section5/application-ibcb-audit/get-application-data') !!}" + "?id=" + id
                }).done(function( object ) {
                    $('#myTable-Mcheckings tbody').html(object);
                    $.LoadingOverlay("hide");
                });

            };
        }

    </script>
@endpush

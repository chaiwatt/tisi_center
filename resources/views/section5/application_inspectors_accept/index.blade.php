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


                    <h3 class="box-title pull-left">ระบบตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</h3>

                    <div class="pull-right">

                        @can('assign_work-'.str_slug('application-inspectors-accept'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" type="button" id="btn_assign">
                                <b>มอบหมาย</b>
                            </a>
                        @endcan

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบตรวจสอบคำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขที่คำขอ/ผู้ยื่นคำขอ/เลขผู้เสียภาษี/ผู้รับมอบหมาย']); !!}
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
                                        {!! Form::select('filter_status', App\Models\Section5\ApplicationInspectorStatus::pluck('title', 'id')->all(), null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
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

                                            <div class="row">
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
                                        <th width="2%" class="text-center">No.</th>
                                        <th width="2%"><input type="checkbox" id="checkall"></th>
                                        <th width="10%" class="text-center">เลขที่คำขอ/วันที่ยื่นคำขอ</th>
                                        <th width="20%" class="text-center">ผู้ยื่นคำขอ/เลขผู้เสียภาษี</th>
                                        <th width="30%" class="text-center">สาขา/รายสาขา</th>
                                        <th width="13%" class="text-center">สถานะ</th>
                                        <th width="14%" class="text-center">ผู้รับมอบหมาย</th>
                                        <th width="9%" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    @cannot ('view_all-'.str_slug('application-inspectors-accept'))
                        <div class="alert alert-info"> <i class="fa fa-info"></i> แสดงเฉพาะรายการที่ได้รับมอบหมายเท่านั้น </div>
                    @endcannot

                    <div class="clearfix"></div>

                    @include ('section5.application_inspectors_accept.modals')

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
                    url: '{!! url('/section5/application_inspectors_accept/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch = $('#filter_branch').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'application_no', name: 'application_no' },
                    { data: 'applicant_full_name', name: 'applicant_full_name' },
                    { data: 'standards', name: 'standards' },
                    { data: 'application_status', name: 'application_status' },
                    { data: 'assign_by', name: 'assign_by' },
                    { data: 'action', name: 'action' },
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


            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                $(".item_checkbox").prop('checked', true);
                } else {
                $(".item_checkbox").prop('checked',false);
                }
            });

            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_status').change(function (e) { 
                table.draw();
            });


            $('#btn_assign').click(function (e) {

                $('#m_assign_by').val('').trigger('change.select2');
                $('#m_assign_comment').val('');
                $('#MyTable-Modal tbody').html('');
                var arrRowId = [];
                var arrAppNo = [];
                var tb = '';
                //Iterate over all checkboxes in the table
                $('.item_checkbox:checked').each(function (index, rowId) {
                    arrRowId.push(rowId.value);
                    arrAppNo.push($(rowId).data('app_no'));

                    tb += '<tr data-repeater-item>';
                    tb += '<td class="text-center">'+(index + 1)+'</td>';
                    tb += '<td>'+( $(rowId).data('app_no') )+'</td>';
                    tb += '</tr>';
                });

                if (arrRowId.length > 0) { //ถ้าเลือกคำขอ

                    //โหลดรายชื่อเจ้าหน้าที่รับผิดชอบตามกลุ่มผลิตภัณฑ์ ตามคำขอที่เลือก
                    $('#m_assign_by').html('');
                    $.ajax({
                        url: "{{ url('/section5/application/workgroup_ib_staff') }}",
                        data: {
                            ids: arrRowId,
                            app_type:'ins'
                        },
                        success:function(users){

                            $.each(users, function(runrecno, name) {
                                $('#m_assign_by').append('<option value="'+runrecno+'">'+name+'</option>');
                            });

                        }
                    });

                    $('#MyTable-Modal tbody').append(tb);
                    $('#modal-assign').modal('show');
                    $('#show_application_no').text(arrAppNo.join(', '));

                }else {
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
                }

            });

            $('#btn_save_modal').click(function (e) {

                var assign_by = $('#m_assign_by').val();
                var assign_commen = $('#m_assign_comment').val();

                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });


                if( checkNone(assign_by)  ){

                    $.ajax({
                        type:"POST",
                        url:  "{{ url('/section5/application_inspectors_accept/assing_data_update') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            id: id,
                            assign_by: assign_by,
                            assign_commen: assign_commen
                        },
                        success:function(data){

                            if( data == "success"){

                                $.toast({
                                    heading: 'Success!',
                                    position: 'top-center',
                                    text: 'มอบหมายสำเร็จ !',
                                    loaderBg: '#70b7d6',
                                    icon: 'success',
                                    hideAfter: 3000,
                                    stack: 6,
                                    afterShown: function () {
                                        table.draw();
                                        $('#checkall').prop('checked', false);
                                        $('#modal-assign').modal('hide');
                                    }
                                });

                            }else{

                                $.toast({
                                    heading: 'Error!',
                                    position: 'top-center',
                                    text: 'มอบหมายไม่สำเร็จ !',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 3000,
                                    stack: 6,
                                    afterShown: function () {
                                        table.draw();
                                        $('#checkall').prop('checked', false);
                                        $('#modal-assign').modal('hide');
                                    }
                                });

                            }


                        }
                    });

                }else{
                    alert("โปรดเลือกผู้รับมอบหมาย ?");
                }


            });


            $('#btn_search').click(function () {
                table.draw();
            });
            $('#btn_clean').click(function () {
                $('#filter_search').val('');
                $('#filter_status').val('').select2();
                $('#search-btn').find('select').val('').select2();
                $('#search-btn').find('input').val('');
                table.draw();
            });

            $('#filter_branch_group').change(function (e) {

                $('#filter_branch').html('<option value=""> -เลือกรายสาขา- </option>');
                var value = ($(this).val() != "") ? $(this).val() : 'ALL' ;
                if(value){
                    $.ajax({
                        url: "{!! url('/section5/get-branch-data') !!}" + "/" + value
                    }).done(function(object) {
                        $.each(object, function(index, data) {
                            $('#filter_branch').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    });
                }

            });


        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
@endpush

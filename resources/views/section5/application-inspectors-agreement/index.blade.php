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

                    <h3 class="box-title pull-left">ระบบขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB)</h3>

                    <div class="pull-right">

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบขึ้นทะเบียนผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม (IB)</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขที่คำขอ, ผู้ยื่นคำขอ, เลขผู้เสียภาษี']); !!}
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
                                        {!! Form::select('filter_status', App\Models\Section5\ApplicationInspectorStatus::whereIn('id', [8,9,10])->pluck('title', 'id')->all(), null, ['id' => 'filter_status', 'class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                                                        {!! Form::label('filter_agreement_status', 'แนบไฟล์เอกสารขึ้นทะเบียน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_agreement_status', [ '-1' => 'รอดำเนินการ', 1 => 'ออกเอกสารแล้ว', 2 => 'แนบไฟล์เอกสารแล้ว' ], null, ['class' => 'form-control', 'id'=> 'filter_agreement_status', 'placeholder'=>'-เลือกสถานะแนบไฟล์เอกสารขึ้นทะเบียน-']); !!}
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
                                                        {!! Form::label('filter_agreement_start_date', 'วันที่ขึ้นทะเบียน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_agreement_start_date', null, ['class' => 'form-control','id'=>'filter_agreement_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_agreement_end_date', null, ['class' => 'form-control','id'=>'filter_agreement_end_date']) !!}
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
                                        <th width="10%" class="text-center">เลขที่คำขอ/วันที่ยื่น</th>
                                        <th width="19%" class="text-center">ผู้ยื่นคำขอ/เลขผู้เสียภาษี</th>
                                        <th width="22%" class="text-center">สาขา/รายสาขา</th>
                                        <th width="10%" class="text-center">วันที่ขึ้นทะเบียน</th>
                                        <th width="13%" class="text-center">สถานะ</th>
                                        <th width="13%" class="text-center">เอกสารขึ้นทะเบียน</th>
                                        {{-- <th width="12%" class="text-center">เอกสารขึ้นทะเบียน<br>(เซ็นแล้ว)</th> --}}
                                        <th width="8%" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>

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
                    url: '{!! url('/section5/application-inspectors-agreement/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch = $('#filter_branch').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();

                        d.filter_agreement_start_date = $('#filter_agreement_start_date').val();
                        d.filter_agreement_end_date = $('#filter_agreement_end_date').val();

                        d.filter_agreement_status = $('#filter_agreement_status').val();
                        
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'refno_application', name: 'refno_application' },
                    { data: 'authorized_name', name: 'authorized_name' },
                    { data: 'scope', name: 'scope' },
                    { data: 'inspector_agreement_date', name: 'inspector_agreement_date' },
                    { data: 'status_application', name: 'status_application' },
                    { data: 'preview_document', name: 'preview_document' },
                    // { data: 'agreement_status', name: 'agreement_status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2,-3,-4] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

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

            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_status').change(function (e) { 
                table.draw();
            });

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

        });
    </script>
@endpush

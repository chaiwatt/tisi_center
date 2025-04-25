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


                    <h3 class="box-title pull-left">รายชื่อหน่วยตรวจสอบ IB/CB</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('manage-ibcb'))
                            <a class="btn btn-success waves-effect waves-light" type="button" href="{!! url('/section5/ibcb/create') !!}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มหน่วยตรวจสอบ IB/CB</b>
                            </a>
                        @endcan
                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบรายชื่อหน่วยตรวจสอบ IB/CB</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก รหัส/ชื่อหน่วยตรวจสอบ/เลขผู้เสียภาษี']); !!}
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

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::select('filter_status', [ 1=> 'Active', 2=> 'Not Active' ], null, ['id' => 'filter_status', 'class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::select('filter_branch_group', App\Models\Basic\BranchGroup::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'id'=> 'filter_branch_group', 'placeholder'=>'-เลือกสาขา-']); !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::select('filter_branch', App\Models\Basic\Branch::pluck('title', 'id')->all() , null, ['class' => 'form-control', 'id'=> 'filter_branch', 'placeholder'=>'-เลือกรายสาขา-']); !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::select('filter_type', [1 => 'IB', 2 => 'CB'], null, ['class' => 'form-control', 'id'=> 'filter_type', 'placeholder'=>'-เลือกประเภท-']); !!}
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
                                        <th width="10%" class="text-center">รหัส</th>
                                        <th width="20%" class="text-center">ชื่อหน่วยตรวจสอบ</th>
                                        <th width="10%" class="text-center">เลขผู้เสียภาษี</th>
                                        <th width="22%" class="text-center">หมวดอุตสาหกรรม/สาขา</th>
                                        <th width="10%" class="text-center">ประเภท</th>
                                        <th width="10%" class="text-center">วันที่เริ่มเป็นหน่วยตรวจสอบ</th>
                                        <th width="10%" class="text-center">สถานะ</th>
                                        <th width="6%" class="text-center">จัดการ</th>
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

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/section5/ibcb/data_list') !!}',
                    data: function (d) {

                        d.filter_search       = $('#filter_search').val();
                        d.filter_status       = $('#filter_status').val();
                        d.filter_branch_group = $('#filter_branch_group').val();
                        d.filter_branch       = $('#filter_branch').val();
                        d.filter_type         = $('#filter_type').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ibcb_code', name: 'ibcb_code' },
                    { data: 'ibcb_name', name: 'ibcb_name' },
                    { data: 'taxid', name: 'taxid' },
                    { data: 'scope_group', name: 'scope_group' },
                    { data: 'type', name: 'type' },
                    { data: 'ibcb_start_date', name: 'ibcb_start_date' },
                    { data: 'state', name: 'state' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0, -1, -2, -3, -4] },
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
                table.draw();
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

@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />

    <style>

    .label-filter{
        margin-top: 7px;
    }

    </style>

@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">เครื่องมือทดสอบ</h3>

                    <div class="pull-right">


                        @can('edit-'.str_slug('testtools'))

                            <a class="btn btn-success btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(1);">
                                <span class="btn-label"><i class="fa fa-check"></i></span><b>เปิด</b>
                            </a>

                            <a class="btn btn-danger btn-sm btn-outline waves-effect waves-light" href="#" onclick="UpdateState(0);">
                                <span class="btn-label"><i class="fa fa-close"></i></span><b>ปิด</b>
                            </a>

                        @endcan

                        @can('add-'.str_slug('testtools'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/bsection5/test_tools/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('delete-'.str_slug('testtools'))
                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Delete();">
                                <span class="btn-label"><i class="fa fa-trash-o"></i></span><b>ลบ</b>
                            </a>
                        @endcan

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เครื่องมือทดสอบ']); !!}
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
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                            ล้าง
                                        </button>
                                    </div>
                                </div><!-- /.col-lg-1 -->

                                <div class="col-lg-3">

                                </div><!-- /.col-lg-5 -->
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_status', [ 1=> 'เปิดใช้งาน', 2=> 'ปิดใช้งาน' ], null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">

                             
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
                                        <th>#</th>
                                        <th><input type="checkbox" id="checkall"></th>
                                        <th>เครื่องมือทดสอบ</th>
                                        <th>ผู้สร้าง</th>
                                        <th>วันที่สร้าง</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
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
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>
        var table = '';
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

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/bsection5/test_tools/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
 
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', name: 'checkbox' },
                    { data: 'title', name: 'title' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                }
            });

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            $(document).on('click', '.btn_update_state', function(e) {

                var id = $(this).data('id');
                var state = $(this).data('state');

                if( state == '1'){
                    var text_alert = 'เปิด';
                }else if( state == '0'){
                    var text_alert = 'ปิด';
                }

                if (confirm("ยืนยันการ"+text_alert+ "ข้อมูลแถว นี้ ?")) {

                    var ids = [];
                        ids.push(id);

                    $.ajax({
                        method: "put",
                        url: "{{ url('bsection5/test_tools/update-state') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_publish": ids,
                            "state": state
                        }
                    }).success(function (msg) {
                        if (msg == "success") {
                            if(state == 1){
                                toastr.success('เปิดการใช้งาน !');
                            }else{
                                toastr.error('ปิดการใช้งาน !');
                            }
                            table.draw();
                        }
                    });

                }


            });

        });

        function Delete(){

            var ids = [];

            //Iterate over all checkboxes in the table
            table.$('.item_checkbox:checked').each(function (index, rowId) {
                ids.push(rowId.value);
            });

            if (ids.length > 0) {

                if(confirm("ยืนยันการลบข้อมูล " + ids.length + " แถว นี้ ?")){

                    $.ajax({
                        method: "POST",
                        url: "{{ url('bsection5/test_tools/delete') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_publish": ids,
                        }
                    }).success(function (msg) {
                        if (msg == "success") {
                            toastr.success('ลบสำเร็จ !');
                            table.draw();
                            $('#checkall').prop('checked',false );
                        }
                    });
                }

            }else {
                alert("โปรดเลือกอย่างน้อย 1 รายการ");
            }

        }

        function confirm_delete() {
            return confirm("ยืนยันการลบข้อมูล?");
        }

        function UpdateState(state){

            if( state == '1'){
                var text_alert = 'เปิด';
            }else if( state == '0'){
                var text_alert = 'ปิด';
            }
            var ids = [];

            //Iterate over all checkboxes in the table
            table.$('.item_checkbox:checked').each(function (index, rowId) {
                ids.push(rowId.value);
            });

            if (ids.length > 0) {

                if (confirm("ยืนยันการ"+text_alert+" " + ids.length + " แถว นี้ ?")) {

                    $.ajax({
                        method: "put",
                        url: "{{ url('bsection5/test_tools/update-state') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_publish": ids,
                            "state": state
                        }
                    }).success(function (msg) {
                        if (msg == "success") {
                            if(state == 1){
                                toastr.success('เปิดการใช้งาน !');
                            }else{
                                toastr.error('ปิดการใช้งาน !');
                            }
                            table.draw();
                            $('#checkall').prop('checked',false );
                        }
                    });
                }

            }else {
                alert("โปรดเลือกอย่างน้อย 1 รายการ");
            }

        }

    </script>

@endpush

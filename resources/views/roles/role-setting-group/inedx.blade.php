@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        .Color-Picker {
            position: absolute;
            top: 0;
            right: -35px;
            height: 38px;
            width: 37px;
            border: 0;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">กลุ่มสิทธิ์ระบบงาน Url</h3>

                    <div class="pull-right">

                        @can('add-'.str_slug('role-setting-group'))
                            <a class="btn btn-sm btn-success pull-right waves-effect waves-light" href="{{url('role-setting-group/create')}}">
                            <i class="icon-plus"></i> เพิ่มกลุ่มสิทธิ์ระบบงาน Url
                            </a>
                        @endcan
                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row" id="myFilter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder' => 'ค้นจากชื่อกลุ่มผู้ใช้งาน', 'id' => 'filter_search']); !!}
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-success waves-effect waves-light" id="btn_search">ค้นหา</button>
                                                <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">ล้าง</button>
                                            </div>
                                        </div>
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_role', App\Role::pluck('name', 'id'), null, ['class' => 'form-control', 'id' => 'filter_role', 'placeholder'=>'-เลือกกลุ่มผู้ใช้งาน-']); !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_status', [ 1=> 'เปิดใช้งาน', 2=> 'ปิดใช้งาน' ], null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">ลำดับ</th>
                                        <th class="text-center" width="20%">ชื่อระบบ</th>
                                        <th class="text-center" width="15%">รายละเอียด</th>
                                        <th class="text-center" width="16%">URL</th>
                                        <th class="text-center" width="7%">ไอคอน</th>
                                        <th class="text-center" width="7%">สี</th>
                                        <th class="text-center" width="15%">สถานะ</th>
                                        <th class="text-center" width="15%">จัดการ</th>
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

        $(document).ready(function() {

            $(document).on('click', '.delete', function () {
                if (confirm('Are you sure want to delete?')) {

                }else {
                    return false;
                }

            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/role-setting-group/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_role  = $('#filter_role').val();
 
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'title', name: 'title' },
                    { data: 'details', name: 'details' },
                    { data: 'urls', name: 'urls' },
                    { data: 'icons', name: 'icons' },
                    { data: 'colors', name: 'colors' },
                    { data: 'state', name: 'state' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1, -2, -3, -4] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                }
            });

            $("#myTable > tbody").sortable({
                placeholder: "ui-state-highlight",
                start: function(event, ui) {
                    console.log(1);
                },
                stop:function(event, ui) {

                    var ids    = [];
                    var orders = [];

                    $('.item_checkbox').each(function(index, el) {
                        ids.push($(el).val());
                        orders.push($(el).closest('tr').find('.order').val());
                    });

                    orders.sort();

                    $.post('{{ url('role-setting-group/update_order') }}',
                        {
                            _token: "{{ csrf_token() }}",
                            ids: ids,
                            orders: orders,
                        },
                        function( data ) {
                            $.toast({
                                heading: 'Success!',
                                position: 'top-center',
                                text: data.message,
                                loaderBg: '#70b7d6',
                                icon: data.status,
                                hideAfter: 3000,
                                stack: 6
                            });

                            table.draw();
                        }
                    );
                }
            });

            
            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_role,#filter_status').change(function (e) { 
                table.draw();
            });


            $('#btn_search').click(function (e) { 
               table.draw();
            });
            
            $('#btn_clean').click(function (e) {
                $('#filter_search').val('');
                $('#filter_role,#filter_status').val('').select2();

               table.draw();

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
                        url: "{{ url('role-setting-group/update-state') }}",
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
                        url: "{{ url('role-setting-group/delete') }}",
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
                        url: "{{ url('role-setting-group/update-state') }}",
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

    </script>

@endpush

@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .has-dropdown {
            position: relative;
        }
        .show_status {
          border: 2px solid #00BFFF;
          padding: 0px 7px;
          -webkit-padding: 0px 7px;
          -moz-padding: 0px 7px;
          border-radius: 25px;
          -webkit-border-radius: 25px;
          -moz-border-radius: 25px;
          width: auto;
    }
    
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left ">จัดการข้อมูลห้องสมุด</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-book-manage'))
                            <button type="button"  class="btn btn-primary waves-effect waves-light"  id="ButtonModal">
                                <span class="btn-label"><i class="mdi mdi-file"></i></span>แนบไฟล์ห้องสมุด
                            </button>
                            <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/book/manage/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1' => 'ชื่อเรื่อง', '2' => 'ประเภท', '3' => 'หมวดหมู่'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:ชื่อเรื่อง, ประเภท, หมวดหมู่']); !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search btn_search"></i> ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                            ล้างค่า
                                        </button>
                                    </div>
                                    <div class="form-group pull-left m-l-15">
                                        <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                        <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-6 control-label text-right']) !!}
                                    <div class="col-md-6">
                                        {!! Form::select('filter_status', [ 1=> 'เผยแพร่', 2=> 'ไม่เผยแพร่' ], null, ['class' => 'form-control ', 'id' => 'filter_status', 'placeholder'=>'-ทั้งหมด-']); !!}
                                    </div>
                                </div>
                            </div>

                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_book_group', 'หมวดหมู่', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_book_group', App\Models\Law\Basic\LawBookGroup::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control ', 'id' => 'filter_book_group', 'placeholder'=>'-เลือกหมวดหมู่-']); !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_book_type', 'ประเภท', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_book_type', App\Models\Law\Basic\LawBookType::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control ', 'id' => 'filter_book_type', 'placeholder'=>'-เลือกประเภท-']); !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_access', 'สิทธิ์การเข้าถึงข้อมูล', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_access', [ 1=> 'บุคคลทั่วไป', 2=> 'เจ้าหน้าที่ สมอ.' ], null, ['class' => 'form-control ', 'id' => 'filter_access', 'placeholder'=>'-เลือกสิทธิ์การเข้าถึงข้อมูล-']); !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_created_at', 'วันที่บันทึก'.':', ['class' => 'col-md-12 control-label ']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('filter_created_at', null, ['class' => 'form-control mydatepicker ', 'id' => 'filter_created_at','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
                                                        <i class="icon-calender"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4{{ $errors->has('checkbox') ? 'has-error' : ''}}">
                                            <div class="col-md-12">&nbsp;</div> 
                                            <div class="col-md-12">
                                                {!! Form::checkbox('filter_checkfile', null,false, ['class' => 'form-control check', 'data-checkbox' => 'icheckbox_flat-blue', 'id'=>'filter_checkfile']) !!}
                                                {!! Form::label('filter_checkfile', 'ยังไม่แนบไฟล์', ['class' => 'control-label text-capitalize']) !!}
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
                            <div class="pull-right has-dropdown">
                                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm btn-outline" type="button"> Action <span class="caret"></span> </button>
                                <ul role="menu" class="dropdown-menu">
                                    @can('edit-'.str_slug('law-book-manage'))
                                        <li>
                                            <a class="" href="#" onclick="UpdateState(1);">
                                                เผยแพร่
                                            </a>
                                        </li>
                                        <li>
                                            <a class="" href="#" onclick="UpdateState(0);">
                                                ไม่เผยแพร่
                                            </a>
                                        </li>
                                    @endcan
                                    @can('delete-'.str_slug('law-book-manage'))
                                        <li>
                                            <a class="" href="#" onclick="Delete();">
                                               ลบ
                                            </a>
                                        </li>
                                    @endcan              
                                </ul>
                            </div>
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="1%"><input type="checkbox" id="checkall"></th>
                                        <th class="text-center" width="15%">ชื่อเรื่อง</th>
                                        <th class="text-center" width="10%">ประเภท/หมวดหมู่</th>
                                        <th class="text-center" width="10%">สิทธิ์การเข้าถึงข้อมูล</th>
                                        <th class="text-center" width="6%">จำนวนไฟล์</th>
                                        <th class="text-center" width="6%">เข้าชม</th>
                                        <th class="text-center" width="6%">ดาวน์โหลด</th> 
                                        <th class="text-center" width="8%">ผู้บันทึก<br>วันที่บันทึก</th>
                                        <th class="text-center" width="8%">สถานะ</th>
                                        <th class="text-center" width="10%">จัดการ</th>
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
        @include('laws.books.manage.modal')
    </div>

@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            //แนบไฟล์ห้องสมุด
            $("body").on("click", "#ButtonModal", function() {
                $('#table_tbody_file').html('');

                var ids = [];
                $('.item_checkbox:checked').each(function(index, element){
                            var $tr = '';
                                $tr += '<tr>';
                                $tr += '<td class="text-center text-top">' +(index+1)+ '<input type="hidden" name="id[]"  value="'+$(element).val()+'"></td>';
                                $tr += '<td class="text-top">' +($(element).data('title'))+ '</td>';
                                $tr += '</tr>';
                            $('#table_tbody_file').append($tr);
                
                    ids.push($(element).val());
                  });

                if(ids.length > 0){
                    $('#FileModals').modal('show');
                }else{
                    $('#FileModals').modal('hide');
                    Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'กรุณาเลือกชื่อเรื่อง',
                                showConfirmButton: false,
                                timer: 1500
                    });
                }
            });

            
    


            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });


            @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
            @endif

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/book/manage/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_book_group = $('#filter_book_group').val();
                        d.filter_book_type = $('#filter_book_type').val();
                        d.filter_access = $('#filter_access').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_created_at = $('#filter_created_at').val();
                        d.filter_checkfile = $('#filter_checkfile').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', name: 'checkbox' },
                    { data: 'title', name: 'title' },
                    { data: 'type_name', name: 'type_name' },
                    { data: 'manage_access', name: 'manage_access' },
                    { data: 'file_count', name: 'file_count' },
                    { data: 'manage_visit_view', name: 'manage_visit_view' },
                    { data: 'manage_visit_download', name: 'manage_visit_download' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2,-3,-4,-5,-6] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    $("div#myTable_length").find('.totalrec').remove();
                    var el = '<label class="m-l-5 totalrec">(ข้อมูลทั้งหมด '+ Comma(table.page.info().recordsTotal)  +' รายการ)</label>';
                    $("div#myTable_length").append(el);
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

        
            $('#filter_checkfile').on('ifChanged', function(event){
                if($(this).is(':checked',true)){
                    $(this).val('1');
                }else{
                    $(this).val('0');
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
                        url: "{{ url('law/book/manage/update-state') }}",
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

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });
            

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                $("#filter_checkfile").prop('checked',false);
                $("#filter_checkfile").iCheck('update');
                table.draw();
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
                        url: "{{ url('law/book/manage/delete') }}",
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
                        url: "{{ url('law/book/manage/update-state') }}",
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

        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>

@endpush

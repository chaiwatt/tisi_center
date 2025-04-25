@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        .has-dropdown {
            position: relative;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left text-primary">มาตราความผิด</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-sections'))
                            <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/basic/section/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12"  id="BoxSearching">

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหา', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="col-md-10">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขมาตรา, คำอธิบายมาตรา']); !!}
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
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {!! Form::label('filter_section_type', 'ประเภทมาตราความผิด', ['class' => 'col-md-12 control-label']) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('filter_section_type', App\Models\Law\Basic\LawSection::list_section_type(), null, ['class' => 'form-control ', 'id' => 'filter_section_type', 'placeholder'=>'-เลือกประเภทมาตราความผิด-']); !!}
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
                                    <div class="form-group col-md-4">
                                        {!! Form::label('filter_date_announce', 'วันที่ประกาศ'.':', ['class' => 'col-md-12 control-label ']) !!}
                                        <div class="col-md-12">
                                            <div class="inputWithIcon">
                                                {!! Form::text('filter_date_announce', null, ['class' => 'form-control mydatepicker ', 'id' => 'filter_date_announce','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
                                                    <i class="icon-calender"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        {!! Form::label('filter_section_id', 'มาตรา', ['class' => 'col-md-12 control-label']) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('filter_section_id', App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือกมาตรา -', 'id' => 'filter_section_id']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-12 control-label']) !!}
                                        <div class="col-md-12">
                                            {!! Form::select('filter_status', [ 1=> 'เปิดใช้งาน', 2=> 'ปิดใช้งาน' ], null, ['class' => 'form-control ', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                                    @can('edit-'.str_slug('law-sections'))
                                        <li>
                                            <a class="" href="#" onclick="UpdateState(1);">
                                                เปิดใช้งาน
                                            </a>
                                        </li>
                                        <li>
                                            <a class="" href="#" onclick="UpdateState(0);">
                                                ปิดใช้งาน
                                            </a>
                                        </li>
                                    @endcan
                                    @can('delete-'.str_slug('law-sections'))
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
                                        <th class="text-center" width="1%"><input type="checkbox" id="checkall"></th>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="8%">เลขมาตรา</th>
                                        <th class="text-center" width="20%">คำอธิบายมาตรา</th>
                                        <th class="text-center" width="20%">ประเภทมาตราความผิด</th>
                                        <th class="text-center" width="15%">วันที่ประกาศ</th>
                                        <th class="text-center" width="8%">วันที่บันทึก</th>
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
                processing: false,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{!! url('/law/basic/section/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_created_at = $('#filter_created_at').val();
                        d.filter_section_type = $('#filter_section_type').val();
                        d.filter_date_announce = $('#filter_date_announce').val();
                        d.filter_section_id = $('#filter_section_id').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'number', name: 'number' },
                    { data: 'title', name: 'title' },
                    { data: 'section_type', name: 'section_type' },
                    { data: 'date', name: 'date' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[1,-1,-2,-3,-4] },
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
                        url: "{{ url('law/basic/section/update-state') }}",
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
                $('#filter_standard').select2('val','');
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
                        url: "{{ url('law/basic/section/delete') }}",
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
                        url: "{{ url('law/basic/section/update-state') }}",
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

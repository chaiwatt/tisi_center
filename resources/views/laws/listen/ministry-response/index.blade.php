@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>

    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">ตรวจสอบข้อมูลความเห็น</h3>

                    <div class="pull-right">
                        @can('printing-'.str_slug('law-listen-ministry-response'))
                        <button type="button"  class="btn btn-success waves-effect waves-light"  id="ButtonPrintExcel">
                            <span class="btn-label"><i class="mdi mdi-file-excel"></i></span>Excel
                        </button>
                        @endcan
                        @can('add-'.str_slug('law-listen-ministry-response'))
                            <a class="btn btn-primary waves-effect waves-light" href="{{ url('/law/listen/ministry-response/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>บันทึกความเห็น</b>
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
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'ชื่อเรื่องประกาศ', '3' => 'ชื่อผู้ให้ความเห็น', '4' => 'เบอร์โทร', '5' => 'อีเมล'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search']); !!}
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
                                    {!! Form::select('filter_status', App\Models\Law\Listen\LawListenMinistry::list_status(), null, ['class' => 'form-control  text-center', 'id' => 'filter_status', 'placeholder'=>'-สถานะทั้งหมด-']); !!}
                                </div>
                            </div>

                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_standard', 'เลขที่ มอก. / ชื่อ มอก. ', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_standard',App\Models\Law\Listen\LawListenMinistry::selectRaw('CONCAT(tis_no," : ",tis_name) As tis_title, id')->pluck('tis_title', 'id'), null, ['class' => 'form-control  text-center', 'id' => 'filter_standard', 'placeholder'=>'-เลือก มอก.-']); !!}
                                            </div>
                                        </div>
               
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_access', 'วันที่ให้ความเห็น', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                                                    <div class="input-daterange input-group date-range">
                                                        {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                        {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_comment', 'ความเห็น ', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_comment', App\Models\Law\Listen\LawListenMinistryResponse::list_comment_point(), null, ['class' => 'form-control  text-center', 'id' => 'filter_comment', 'placeholder'=>'-เลือกความเห็น-']); !!}
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
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="10%">เลขอ้างอิง</th>
                                        <th class="text-center" width="15%">ชื่อผู้ให้ความเห็น</th>
                                        <th class="text-center" width="15%">ข้อมูลติดต่อ</th>
                                        <th class="text-center" width="10%">สังกัด/หน่วยงาน</th>
                                        <th class="text-center" width="15%">ความเห็น</th>
                                        <th class="text-center" width="15%">ข้อคิดเห็นเพิ่มเติม</th>
                                        <th class="text-center" width="10%">วันที่ให้ความเห็น</th>
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

    <div class="modal fade" id="CommentModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">ข้อคิดเห็นเพิ่มเติม</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <span id="comment_txt"></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ปิด') !!}
                        </button>
                    </div>
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

        $(document).ready(function () {
            $("body").on("click", ".show_comment_more", function() {// ส่งเรื่องถึง
               var comment_more = $(this).data('comment_more');
                $('#comment_txt').text(comment_more);
                 
                $('#CommentModals').modal('show');
             });


            //ปฎิทิน
            $('.date-range').datepicker({
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

            var groupColumn = 1;
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/listen/ministry-response/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_status     = $('#filter_status').val();
                        d.filter_search     = $('#filter_search').val();
                        d.filter_standard   = $('#filter_standard').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date   = $('#filter_end_date').val();
                        d.filter_comment    = $('#filter_comment').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'name', name: 'name' },     
                    { data: 'contact', name: 'contact' },
                    { data: 'agency', name: 'agency' },
                    { data: 'comment_point', name: 'comment_point' },
                    { data: 'comment_more', name: 'comment_more' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" },
                    { visible: false, targets: groupColumn }
                ],
                order: [[groupColumn, 'asc']],
                fnDrawCallback: function() {
                    var api  = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;
                    var el   = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
        
                    api.column(groupColumn, { page: 'current' })
                        .data()
                        .each(function (group, i) {
                            if (last !== group) {
                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group"><td colspan="9">' + group + '</td></tr>');
        
                                last = group;
                            }
                        });
                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    $('#myTable_length').find('.totalrec').remove();
                    $('#myTable_length').append(el);

                }
            });

            $('#ButtonPrintExcel').on('click', function() {
                var url = 'law/listen/ministry-response/export_excel';
                    url += '?filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_standard=' + $('#filter_standard').val();
                    url += '&filter_search=' + $('#filter_search').val();
                    url += '&filter_status=' + $('#filter_status').val();
                    url += '&filter_start_date=' + $('#filter_start_date').val();
                    url += '&filter_end_date=' + $('#filter_end_date').val();
                    url += '&filter_comment=' + $('#filter_comment').val();

                    window.location = '{!! url("'+url +'") !!}';
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
                        url: "{{ url('law/listen/ministry-response/update-state') }}",
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
            $(document).on('click', '.ministry_response_confirm_delete', function(e) {
                ministry_response_confirm_delete($(this).data('name'));
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
                        url: "{{ url('law/listen/ministry-response/delete') }}",
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
                        url: "{{ url('law/listen/ministry-response/update-state') }}",
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

        function ministry_response_confirm_delete(name) {
            Swal.fire({
                html:
                    '<div class="h5 text-bold-500 text-primary">ผู้ให้ความเห็น '+name+'</div> ' +
                    '<div class="h3 text-bold-800 ">ลบรายการนี้หรือไม่?</div> ' +
                    '<div class="h6 text-bold-500 text-muted">เมื่อรายการนี้ถูกลบ คุณไม่สามารถกู้คืนได้ !</div> ' ,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#808080',
                cancelButtonText: 'ยกเลิก',
                confirmButtonText: 'ยืนยัน',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form_ministry_response_delete').submit();  
                }
            })
        }

    </script>

@endpush

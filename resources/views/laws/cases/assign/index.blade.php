@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
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
    .circle {
        border-radius: 50%;
     }
     .not-allowed {
        cursor: not-allowed
    }
 

    .rounded-circle {
        border-radius: 50% !important;
    }

    .btn-light-secondary {
    background-color: #b0b0b1;
    color: #6c757d !important;
    }
    .btn-light-secondary:hover, .btn-light-secondary.hover {
    background-color: #6c757d;
    color: #fff !important;
    }

    .btn-light-primary {
    background-color: #719df0  ;
    color: #fff  !important;
    }
    .btn-light-primary:hover, .btn-light-primary.hover {
    background-color: #007bff ;
    color: #fff !important;
    }
    .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
    .bootstrap-tagsinput {
        min-height: 70px;
        border-radius: 0;
        width: 100% !important;
        -webkit-border-radius: 7px;
        -moz-border-radius: 7px;
    }
    .bootstrap-tagsinput input {
        padding: 6px 6px;
    }
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
        opacity: 1;
    }



    </style>
@endpush

@php
    $option_status = App\Models\Law\Cases\LawCasesForm::status_list();

    $arr_unset = [0, 13, 14, 15, 99 ];

    foreach ($arr_unset as $value) {
        unset(  $option_status[$value] );
    }

@endphp

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left ">มอบหมายงานคดีผลิตภัณฑ์ฯ</h3>

                    <div class="pull-right">
                        @can('assign_work-'.str_slug('law-cases-assign'))
                            <button type="button" class="btn btn-primary waves-effect waves-light btn-sm font-15" id="ButtonModal">
                                <span class="btn-label"><i class="fa fa-user"></i></span>  มอบหมาย
                            </button>
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
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'ผู้ประกอบการ/TAXID', '3' => 'เลขที่ใบอนุญาต', '4' => 'ชื่อผู้รับมอบหมาย', '5' => 'ชื่อนิติกร'), null, ['class' => 'form-control', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขที่อ้างอิง, ผู้ประกอบการ/TAXID, เลขที่ใบอนุญาต']); !!}
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
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status[]',  $option_status , null, ['class' => 'select2 select2-multiple', 'multiple'=>'multiple', 'id' => 'filter_status']) !!}
                                    </div>
                                </div>
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    @php
                                        $option_section = App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id');
                                    @endphp
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_tisi_no', 'เลข มอก.', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::text('tb3_tisno', null , ['class' => 'form-control ','id'=>'tb3_tisno', 'placeholder' => 'ค้นจาก เลข มอก. / ชื่อ มอก.']) !!}
                                                {!! Form::hidden('filter_tisi_no', null , ['id'=>'filter_tisi_no']) !!}                                           
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_basic_section_id', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_basic_section_id', $option_section ,null, ['class' => 'select2 select2-multiple',"multiple"=>"multiple",'id'=>'filter_basic_section_id']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_created_at', 'วันที่ยึด-อายัด', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('filter_created_at', null, ['class' => 'form-control mydatepicker', 'id' => 'filter_created_at','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                                                    <i class="icon-calender"></i>
                                                </div>
                                           </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_assign_status', 'สถานะมอบหมาย', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_assign_status', ['-1'=>'รอมอบหมาย','1'=>'มอบหมายแล้ว'] ,-1, ['class' => 'form-control', 'id'=>'filter_assign_status', 'placeholder'=>'-ทั้งหมด-']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_close_status', 'สถานะปิดงาน', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_close_status', [ '99' => 'รอดำเนินการ', '-1'=>'แจ้งปิดงาน','1'=>'ปิดงาน'], null, ['class' => 'form-control', 'id'=>'filter_close_status', 'placeholder'=>'-ทั้งหมด-']) !!}
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
                                        <th class="text-center" width="1%">#</th>
                                        <th  width="1%" ><input type="checkbox" id="checkall"></th> 
                                        <th class="text-center" width="10%">เลขที่อ้างอิง/เลขคดี</th>
                                        <th class="text-center" width="10%">ผู้ประกอบการ/TAXID</th>
                                        {{-- <th class="text-center" width="8%">มอก./เลขที่ใบอนุญาต</th> --}}
                                        <th class="text-center" width="10%">ฝ่าฝืนตามมาตรา</th>
                                        {{-- <th class="text-center" width="10%">กลุ่มงานที่รับผิดชอบ</th> --}}
                                        <th class="text-center" width="10%">มอบหมาย</th> 
                                        <th class="text-center" width="10%">นิติกรผู้รับผิดชอบ</th>
                                        <th class="text-center" width="16%">ส่งเรื่องถึง</th>
                                        <th class="text-center" width="8%">สถานะ</th>
                                        <th class="text-center" width="8%">ดูรายละเอียด</th>
                                        <th class="text-center" width="8%">ติดตาม</th>
                                        <th class="text-center" width="8%">ปิดงานคดี</th>
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

    <!-- มอบหมาย -->
    @include('laws.cases.assign.modals.assign')

    @can('edit-'.str_slug('law-cases-assign')) 
        <!-- ปิดงาน -->
        @include('laws.cases.assign.modals.close')
    @endcan

    @include('laws.cases.forms.modals.approve');
    @php      
        $subdepart_ids    = ['0600','0601','0602','0603','0604'];//เจ้าหน้าที่ กม.
        $subdepart_list   = json_encode($subdepart_ids);    
     @endphp
@endsection

@push('js')
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script>
        var table = '';
        var option_users = [];
        $(document).ready(function () {

            $("body").on("click", ".show_approve", function() {// ส่งเรื่องถึง
                $('#table_tbody_approve').html('');
                var url  = '{{ url('/law/cases/forms/get_level_approves') }}/' + $(this).data('id');
                    $.ajax({
                        url: url,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
                            console.log(data);
                            if (data.length > 0) {
                                var $tr = '';
                                $.each(data,function(index, value){
                                    $tr += '<tr>';
                                    $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                                    $tr += '<td class="text-top">' +(value.authorize_name)+ '</td>';
                                    $tr += '<td class="text-top">' +(value.position)+ '</td>';
                                    $tr += '<td class="text-top">' +(checkNone(value.status_text) ? value.status_text:'')+ '</td>';
                                    $tr += '<td class="text-top">' +(checkNone(value.remark) ? value.remark:'')+ '</td>';
                                    $tr += '<td class="text-top">' +(value.format_create_at_time)+ '</td>';
                                    $tr += '</tr>';
                                });
                                $('#table_tbody_approve').append($tr);
                            }
                        }
                    });

                $('#ApproveModals').modal('show');
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

            var followColumn = '{!! $visible_follow !!}';
            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                stateSave: true,
                ajax: {
                    url: '{!! url('/law/cases/assigns/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tisi_no = $('#filter_tisi_no').val();
                        d.filter_basic_section_id = $('#filter_basic_section_id').val();
                        d.filter_created_at = $('#filter_created_at').val();
                        d.filter_assign_status = $('#filter_assign_status').val();
                        d.filter_close_status = $('#filter_close_status').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'offend_name', name: 'offend_name' },
                    // { data: 'tis_name', name: 'tis_name' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    // { data: 'owner_department_name', name: 'owner_department_name' },
                    { data: 'assign_name', name: 'assign_name', searchable: false, orderable: false },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'approve', name: 'approve' },
                    { data: 'status', name: 'status' }, 
                    { data: 'view', name: 'view', searchable: false, orderable: false }, 
                    { data: 'follow', name: 'follow', searchable: false, orderable: false }, 
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2,-3,-4,-5] },
                    { className: "text-top", targets: "_all" },
                    { className: "text-center text-top", visible: followColumn, targets: -2},

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    //  $('.select2').val('');
                    //  $('.select2').prev().remove();
                    //  $('.select2').removeAttr('style');
                    //  $('.select2').select2();
                  
                }
            });

            @can('assign_work-'.str_slug('law-cases-assign'))   

                $("body").on("click", "#ButtonModal", function() {//มอบหมายผ่าน checkbox
                    if($('#myTable').find(".item_checkbox:checked").length > 0){

                        $('#AssignModals').find('select').select2('val','');
                        $('#assign_checkall').prop('checked',false);

         
                        $('#table_tbody_assign').html('');
                        var department_ids = [];
                        var assign_bys     = [];
                        var lawyer_bys      = [];
                        $.each($('#myTable').find(".item_checkbox:checked"),function (index,value) {
                            var $tr = '';
                                $tr += '<tr>';
                                $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                                $tr += '<td class="text-top">' +($(value).data('ref_no'))+ '</td>';
                                $tr += '<td class="text-top">' +($(value).data('offend_name'))+ '<br/>' +($(value).data('offend_taxid'))+'</td>';
                                $tr += '</tr>';
                            $('#table_tbody_assign').append($tr);

                            // กลุ่ม/กอง
                            if(checkNone($(value).data('sub_department_id')) && $.inArray($(value).data('sub_department_id'),department_ids) == '-1'){
                                 department_ids.push($(value).data('sub_department_id'));
                            }

                            // ผู้รับมอบหมาย
                            if(checkNone($(value).data('assign_by')) && $.inArray($(value).data('assign_by'),assign_bys) == '-1'){
                                assign_bys.push($(value).data('assign_by'));
                            }

                            // นิติกร
                            if(checkNone($(value).data('lawyer_by')) && $.inArray($(value).data('lawyer_by'),lawyer_bys) == '-1'){
                                lawyer_bys.push($(value).data('lawyer_by'));
                            }

                        });
          
                             // กลุ่ม/กอง
                            if(department_ids.length == '1'){
                                  // ผู้รับมอบหมาย.
                                $("select[id='sub_department_id']").val(department_ids[0]).change(); 
                                if(assign_bys.length == '1'){
                                    $("select[id='assign_id']").val(assign_bys[0]).change(); 
                                }else{
                                    $("select[id='assign_id']").val('').change(); 
                                }
                            }else{
                                $("select[id='sub_department_id']").val('').change(); 
                            }

                            // นิติกร
                            if(lawyer_bys.length == '1'){
                                $("#assign_checkall").prop('checked', true);
                                $("select[id='lawyer_ids']").val(lawyer_bys[0]).change(); 
                            }else{
                                $("#assign_checkall").prop('checked', false);
                                $("select[id='lawyer_ids']").val('').change(); 
                            }

                            // BoxLawyers();

                        $('#AssignModals').modal('show');
                    }else{
                        $('#AssignModals').modal('hide');
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'กรุณาเลือกเลขที่อ้างอิง/เลขคดี',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });

                $("body").on("click", ".single_assign", function() {// มอบหมายผ่าน record

                        $('#AssignModals').find('select').select2('val','');
                        $('#table_tbody_assign').html('');

                            var $tr = '';
                                $tr += '<tr>';
                                $tr += '<td class="text-center text-top">1</td>';
                                $tr += '<td class="text-top">' +($(this).data('ref_no'))+ '</td>';
                                $tr += '<td class="text-top">' +($(this).data('offend_name'))+ '<br/>' +($(this).data('offend_taxid'))+'</td>';
                                $tr += '</tr>';
                            $('#table_tbody_assign').append($tr);
           
                            $('#sub_department_id').val($(this).data('sub_department_id')).change();
                        
                            $('#assign_id').val($(this).data('assign_by')).change();
                            $('#assigns_id').val($(this).data('id'));
                            $('#form_assign').parsley().reset();
                            $('input[name="lawyer_check"]').prop('checked',false);
                            if($(this).data('lawyer_check') == '2'){
                                 $("select[id='lawyer_ids'] option") .removeClass('hide').addClass('show');
                                 $('input[name="lawyer_check"][valie="1"]').prop('checked',true);
                            }else{
                                $("select[id='lawyer_ids']").val('').change(); 
                                $("select[id='lawyer_ids'] option") .removeClass('show').addClass('hide');
                                var sub_department_id = $(this).data('sub_department_id');
                                if(sub_department_id == '0600'){
                                    var subdepart_list = jQuery.parseJSON('{!! $subdepart_list !!}');
                                    if( checkNone(subdepart_list) ){
                                        $.each(subdepart_list, function( index, data ) {
                                            $("select[id='lawyer_ids'] option[data-subdepart='" + data + "']") .removeClass('hide').addClass('show');
                                        });                          
                                    }
                                }else   if(sub_department_id !== ''){
                                    $("select[id='lawyer_ids'] option[data-subdepart='" + sub_department_id + "']") .removeClass('hide').addClass('show');
                                }else{
                                    $("select[id='lawyer_ids'] option[data-subdepart='" +sub_department_id + "']") .removeClass('hide').addClass('show');
                                }
                               
                            }
                          
                            if($(this).data('lawyer_by') != ''){

                                $('#lawyer_ids').val($(this).data('lawyer_by')).change();
                               
                            }else{
                                $('#lawyer_ids').val('').change();
                            }
                            $('input[name="lawyer_check"]').iCheck('update');
                                    BoxLawyers();
                            //   $("#department_checkall").prop('checked', false);
                            // if($(this).data('lawyer_by') != ''){
                            //     $('#lawyer_ids').val($(this).data('lawyer_by')).change();
                            //     $("#assign_checkall").prop('checked', true);
                            // }else{
                                // $("#assign_checkall").prop('checked', false);
                             
                            // }
                                // BoxLawyers();
                          
                        $('#AssignModals').modal('show');
                });

                $("body").on("change", ".assign_name", function() {// มอบหมายผ่าน record
                        var id = $(this).data('id');
                        var user_id = $(this).val();
                        var $this = $(this); 

                        if(checkNone(user_id)){
                            var email  = $this.find('option[value="'+user_id+'"]').data('email');
                            var html = '';
                              if(checkNone(email)){
                                html  = 'ส่งมอบหมายไปยังอีเมล : ' +email;
                                }

                                Swal.fire({
                                    title: 'ยืนยันการมอบหมาย ?',
                                    html: html,
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ตกลง',
                                    cancelButtonText: 'ยกเลิก'
                                    }).then((result) => {
                                        if (result.value) {
                                                 // Text
                                                $.LoadingOverlay("show", {
                                                        image       : "",
                                                        text  : "กำลังมอบหมาย กรุณารอสักครู่..."
                                                });
                                
                                                $.ajax({
                                                    method: "post",
                                                    url: "{{ url('law/cases/assigns/save_select_assign') }}",
                                                    data: {
                                                        "_token": "{{ csrf_token() }}",
                                                        "id": id,
                                                        "assign_id": user_id
                                                    }
                                                }).success(function (msg) {
                                                    $.LoadingOverlay("hide");
                                                    if (msg.message == true) {
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'success',
                                                            title: 'บันทึกเรียบร้อย',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                        });
                                                    }else{
                                                        Swal.fire({
                                                                position: 'center',
                                                                icon: 'error',
                                                                title: 'เกิดข้อผิดพลาด',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            });
                                                    }
                                                    table.draw();
                                                });    
                                        }else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                            $this.val('').trigger('change.select2');        
                                        }else{
                                            $this.val('').trigger('change.select2');        
                                        }
                                })
                        }
                     
                });

                $("body").on("change", ".lawyer_name", function() {// มอบหมายผ่าน record
                        var id = $(this).data('id');
                        var user_id = $(this).val();
                        var $this = $(this);
                        if(checkNone(user_id)){
                            var email  = $this.find('option[value="'+user_id+'"]').data('email');
                            var html = '';
                              if(checkNone(email)){
                                html  = 'ส่งมอบหมายไปยังอีเมล : ' +email;
                                }

                                Swal.fire({
                                    title: 'ยืนยันการมอบหมาย ?',
                                    html: html,
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ตกลง',
                                    cancelButtonText: 'ยกเลิก'
                                    }).then((result) => {
                                        if (result.value) {
                                                 // Text
                                                $.LoadingOverlay("show", {
                                                        image       : "",
                                                        text  : "กำลังมอบหมาย กรุณารอสักครู่..."
                                                });
                                
                                                $.ajax({
                                                    method: "post",
                                                    url: "{{ url('law/cases/assigns/save_select_assign') }}",
                                                    data: {
                                                        "_token": "{{ csrf_token() }}",
                                                        "id": id,
                                                        "lawyer_by": user_id
                                                    }
                                                }).success(function (msg) {
                                                    $.LoadingOverlay("hide");
                                                    if (msg.message == true) {
                                                        Swal.fire({
                                                            position: 'center',
                                                            icon: 'success',
                                                            title: 'บันทึกเรียบร้อย',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                        });
                                                    }else{
                                                        Swal.fire({
                                                                position: 'center',
                                                                icon: 'error',
                                                                title: 'เกิดข้อผิดพลาด',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            });
                                                    }
                                                    table.draw();
                                                });    
                                        }else if ( result.dismiss === Swal.DismissReason.cancel  ) {
                                            $this.val('').trigger('change.select2');        
                                        }else{
                                            $this.val('').trigger('change.select2');        
                                        }
                                })
                        }
                     
                });
                
            @endcan

            $("body").on("click", ".close_the_case", function() {

                $('#table_tbody_close').html('');
                $("#mail_list").tagsinput('removeAll');
                
                var $tr = '';
                    $tr += '<tr>';
                    $tr += '<td class="text-center text-top">1</td>';
                    $tr += '<td class="text-top">' +($(this).data('ref_no'))+ '</td>';
                    $tr += '<td class="text-top">' +($(this).data('offend_name'))+ '<br/>' +($(this).data('offend_taxid'))+'</td>';
                    $tr += '</tr>';
                $('#table_tbody_close').append($tr);
                    
                $('#close_id').val($(this).data('id'));
                $('#remark').val($(this).data('remark'));
                $('#created_by_show').val($(this).data('close_by'));

                $("#mail_list").tagsinput('add',$(this).data('owner_email'));
                
                $('#CloseCaseModals').modal('show');
            });
       
            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });
          
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#filter_assign_status, #filter_close_status').change(function () {
                table.draw();
            });

            $('#filter_assign_status, #filter_close_status').keyup(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

            $('#tb3_tisno').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    return $.get('{{ url("funtions/search-tb3tis") }}', { query: query }, function (data) {
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                    $('#tb3_tisno').val(jsondata.tb3_tisno+' : '+jsondata.tb3_tis_thainame);
                    $('#filter_tisi_no').val(jsondata.id);
          
                }
            });

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function BoxLawyers(){

            if( $('input[name="lawyer_check"]').is(':checked',true) ){
                console.log('====================================');
                console.log(1);
                console.log('====================================');
                $('.lawyers_box').show();
                $('.lawyers_box').find('#lawyer_ids').prop('required',true);
            }else{
                console.log('====================================');
                console.log(2);
                console.log('====================================');
                $('.lawyers_box').hide();
                $('.lawyers_box').find('#lawyer_ids').prop('required',false);

            }
        }

        function close_alert() {
            Swal.fire({
            title: 'ยังไม่สามารถปิดงานคดีได้ !',
            text: "เนื่องจาก นิติกรยังไม่มีการแจ้งปิดงาดคดีดังกล่าว",
            icon: 'warning',
            confirmButtonText: 'รับทราบ',

            })
        }
    </script>

@endpush

 
@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .swal-wide{
            width:450px !important;
        }
        </style>
    
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left">
                        แจ้งงานเข้ากองกฏหมาย
                    </h3>

                    <div class="pull-right">
                        @can('assign_work-'.str_slug('law-track-receive'))
                            <button class="btn btn-primary btn-sm waves-effect waves-light" id="ButtonModal" type="button">
                                <span class="btn-label"><i class="fa fa-user"></i></span>  มอบหมาย
                            </button>
                        @endcan

                        @can('add-'.str_slug('law-track-receive'))
                            <a class="btn btn-success btn-sm waves-effect waves-light" href="{{ url('/law/track/receive/create') }}">
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
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'เลขที่หนังสือ', '3' => 'ชื่อเรื่อง', '4'=>'ผู้รับผิดชอบ', '5'=> 'ผู้มอบหมาย'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
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
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status', App\Models\Law\Basic\LawStatusOperation::where('law_bs_category_operate_id', 1)->pluck('title','id')->all() , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                                                        {!! Form::label('filter_deperment_type', 'ประเภทหน่วยงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_deperment_type', [ 1 => 'หน่วยงานภายใน (สมอ.)', 2 => 'หน่วยงานภายนอก' ]  ,null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทหน่วยงาน -', 'required' => true ,'id'=>'filter_deperment_type' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 box_law_bs_deperment_id" style="display:none;">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_bs_deperment_id', 'หน่วยงานเจ้าของเรื่อง', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_bs_deperment_id',   App\Models\Law\Basic\LawDepartment::Where('state',1)->pluck('title', 'id') , null, ['class' => 'form-control', 'placeholder'=>'- เลือกหน่วยงานเจ้าของเรื่อง -', 'required' => true ,'id'=>'filter_bs_deperment_id']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 box_sub_departments_id" style="display:none;">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_department_id', 'กลุ่มงานหลัก', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_department_id',  App\Models\Besurv\Department::pluck('depart_name', 'did') ,null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มงานหลัก -', 'required' => true , 'id'=>'filter_department_id' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 box_sub_departments_id" style="display:none;">
                                                    <div class="form-group" >
                                                        {!! Form::label('filter_sub_departments_id', 'กลุ่มงานย่อย', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_sub_departments_id',App\Models\Basic\SubDepartment::pluck('sub_departname', 'did'),null, ['class' => 'form-control', 'placeholder'=>'- เลือกกลุ่มงานย่อย -' , 'id'=>'filter_sub_departments_id' ]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_law_job_type_id', 'ประเภทงาน', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_law_job_type_id', App\Models\Law\Basic\LawJobType::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_law_job_type_id', 'placeholder'=>'-เลือกประเภทงาน-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_start_date', 'วันที่บันทึก', ['class' => 'col-md-12 label-filter']) !!}
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
                                                        {!! Form::label('filter_lawyer_start_date', 'วันที่ได้รับมอบหมาย', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_lawyer_start_date', null, ['class' => 'form-control','id'=>'filter_lawyer_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_lawyer_end_date', null, ['class' => 'form-control','id'=>'filter_lawyer_end_date']) !!}
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
                                        <th class="text-center" width="1%"><input type="checkbox" id="checkall"></th>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="8%">เลขที่อ้างอิง</th>
                                        <th class="text-center" width="8%">เลขที่หนังสือ<br>/ประเภทงาน</th>
                                        <th class="text-center" width="8%">วันที่รับเรื่อง</th>
                                        <th class="text-center" width="13%">ชื่อเรื่อง</th>
                                        <th class="text-center" width="8%">หน่วยงาน<br>เจ้าของเรื่อง</th>
                                        <th class="text-center" width="12%">ผู้รับผิดชอบ</th>
                                        <th class="text-center" width="12%">ผู้มอบหมาย</th>
                                        <th class="text-center" width="10%">สถานะ</th>
                                        <th class="text-center" width="10%">ผู้บันทึก</th>
                                        <th class="text-center" width="11%">จัดการ</th>
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
    @include('laws.track.receive.modals.cancel');
    <!-- Modal ข้อมูลขอรับบริการ -->
    @include('laws.track.receive.modals.assign')

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                autoclose: true,
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
                stateSave: true,
                ajax: {
                    url: '{!! url('/law/track/receive/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();

                        d.filter_deperment_type    = $('#filter_deperment_type').val();
                        d.filter_bs_deperment_id = $('#filter_bs_deperment_id').val();
                        d.filter_department_id   = $('#filter_department_id').val();
                        d.filter_sub_departments_id  = $('#filter_sub_departments_id').val();
                        d.filter_law_job_type_id   = $('#filter_law_job_type_id').val();

                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date   = $('#filter_end_date').val();

                        d.filter_assign_start_date = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date = $('#filter_assign_end_date').val();

                        d.filter_lawyer_start_date = $('#filter_lawyer_start_date').val();
                        d.filter_lawyer_end_date = $('#filter_lawyer_end_date').val();
                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false },
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'reference_no', name: 'reference_no' },
                    { data: 'book_no', name: 'book_no' },
                    { data: 'receive_date', name: 'receive_date' },
                    { data: 'title', name: 'title' },
                    { data: 'law_deparment', name: 'law_deparment' },
                    { data: 'assing', name: 'assing' },
                    { data: 'lawyer', name: 'lawyer' },
                    { data: 'status', name: 'status' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1] },
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

            $('#btn_search,i.btn_search').click(function () {
                table.draw();
            });
            
            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

            $('#m_sub_department').change(function (e) {
                
                var value = $(this).val();

                //เลขที่ มอก.
                var select = $('#m_assign_by');
                $(select).html('');
                $(select).val('').trigger('change');

                if(value){

                    $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                    });

                    $.ajax({
                        url: "{!! url('/law/funtion/get-users-data') !!}" + "/" + value
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#m_assign_by').append('<option value="'+data.runrecno+'">'+data.name+'</option>');
                        });

                        $.LoadingOverlay("hide");
                    });
                }
                
            });


            
            $("body").on("click", "#ButtonModal", function() {
                    if($('#myTable').find(".item_checkbox:checked").length > 0){

                        $('#AssignModals').find('select').select2('val','');
                        // $('#assign_checkall').prop('checked',false);

         
                        $('#table_tbody_assign').html('');
                        var department_ids = [];
                        var assign_bys     = [];
                        var lawyer_bys      = [];
                        $.each($('#myTable').find(".item_checkbox:checked"),function (index,value) {
                            var $tr = '';
                                $tr += '<tr>';
                                $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                                $tr += '<td class="text-top">' +($(value).data('ref_no'))+ '</td>';
                                $tr += '<td class="text-top">' +($(value).data('book_no'))+ '</td>';
                                $tr += '<td class="text-top">' +($(value).data('title'))+ '</td>';
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
                                // $("#assign_checkall").prop('checked', true);
                                $("select[id='lawyer_ids']").val(lawyer_bys[0]).change(); 
                            }else{
                                // $("#assign_checkall").prop('checked', false);
                                $("select[id='lawyer_ids']").val('').change(); 
                            }

                            BoxLawyers();

                        $('#AssignModals').modal('show');
                    }else{
                        $('#AssignModals').modal('hide');
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'กรุณาเลือกเลขที่อ้างอิง',
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
                                $tr += '<td class="text-top">' +($(this).data('book_no'))+ '</td>';
                                $tr += '<td class="text-top">' +($(this).data('title'))+ '</td>';
                                $tr += '</tr>';
                            $('#table_tbody_assign').append($tr);

                            $('#sub_department_id').val($(this).data('sub_department_id')).change();
                            $('#lawyer_ids').val($(this).data('lawyer_by')).change();
                            $('#assign_id').val($(this).data('assign_by')).change();
                            $('#assigns_id').val($(this).data('id'));

                        $('#AssignModals').modal('show');
                });



                $("body").on("click", ".cancel_modal", function() {                
                    $('#show_status_modal').text($(this).data('cancel_remark'));
                    $('#show_date_modal').text($(this).data('cancel_at'));
                    $('#actionFour').modal('show');
                });

                $('#filter_deperment_type').change(function (e) { 
                    BoxDeparment();
                    
                });
            
                var sub_departments_id = $('#filter_sub_departments_id').html();
                $('#filter_department_id').change(function (e) {
                $('#filter_sub_departments_id').html('<option value=""> - เลือกกลุ่มงานย่อย - </option>');
                    if($(this).val()!=""){//ดึงประเภทตามหมวดหมู่
                        $.ajax({
                            url: "{!! url('/law/funtion/get-sub-departments') !!}" + "?id=" + $(this).val()
                        }).done(function( object ) {
                            $.each(object, function( index, data ) {
                                $('#filter_sub_departments_id').append('<option value="'+data.sub_id+'">'+data.sub_departname+'</option>');
                            });
                            $('#filter_sub_departments_id').val('').trigger('change.select2');
                        });
                    }else{
                        $('#filter_sub_departments_id').html(sub_departments_id);
                        $('#filter_sub_departments_id').val('').trigger('change.select2');
                    }
                });
        });

        function BoxDeparment(){

            var type = $('#filter_deperment_type').val();

            var type1 = $('.box_sub_departments_id');
            var type2 = $('.box_law_bs_deperment_id');

                if( type == 1){

                    type1.show();
                    type1.find('#filter_department_id').prop('disabled', false);
                    type1.find('#filter_department_id').prop('required', true);
                    
                    type2.hide();
                    type2.find('select').prop('disabled', true);
                    type2.find('select').prop('required', false);

                }else{

                    type1.hide();
                    type1.find('#filter_department_id').prop('disabled', true);
                    type1.find('#filter_department_id').prop('required', false);

                    type2.show();
                    type2.find('select').prop('disabled', false);
                    type2.find('select').prop('required', true);
                }

            }


        function confirm_cancel(row_id){//ยกเลิกคำขอ

        Swal.fire({
            title: 'โปรดกรอกเหตุผลที่ทำการยกเลิกคำขอ',
            input: 'textarea',
            customClass: 'swal-wide',
            showCancelButton: true,
            buttonsStyling: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'ยกเลิก',
            confirmButtonText: 'ยืนยัน',
            reverseButtons: true,
            preConfirm: (value) => {//บังคับกรอก
                if (!value) {
                    Swal.showValidationMessage(
                        'โปรดป้อนข้อมูลนี้'
                    )
                }
            }
            }).then(function (result) {       
            if (result.isConfirmed) {//เมื่ดกดยืนยัน
                $.ajax({
                    type: "POST",
                    url: "{{ url('law/track/receive/save-cancel') }}",
                    data: {
                            "_token": "{{ csrf_token() }}",
                            "id_publish": row_id,
                            "cancel_remark": result.value,
                        },
                    }).success(function (obj) {
                        if (obj.msg == "success") {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'ยกเลิกเลขที่อ้างอิง '+obj.ref_no+' เรียบร้อยแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.draw();
                        }else{
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด ยกเลิกคำขอเลขที่ '+obj.ref_no+' ไม่สำเร็จ',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.draw();
                        }
                    });
                }
            })
        }


        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function BoxLawyers(){
            console.log();
            if( $('input[name="lawyer_check"]').is(':checked') ){
                $('.lawyers_box').show();
                $('.lawyers_box').find('#lawyer_ids').prop('required', true);
            }else{
                $('.lawyers_box').hide();
                $('.lawyers_box').find('#lawyer_ids').prop('required', false);

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
    </script>

@endpush

@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
    .swal-wide{
        width:450px !important;
    }
    .tip {
    position: relative;
    display: inline-block;
    color: red;
     cursor: pointer
   }

.tip .tooltiptext {
  visibility: hidden;
  width: 350px;
  background-color: #fff;
  color: black;
  border: 1px solid  #e5ebec;
  border-radius: 6px;
  padding: 10px 10px;
  font-size:13px;
  position: absolute;
  z-index: 1;
}

.tip:hover .tooltiptext {
  visibility: visible;
}
.label-height{
        line-height: 16px;
    }
.font_size{
            font-size: 10px;
            color: #ccc;
    }
    .mb-3 {
    margin-bottom: 1rem !important;
}
table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
    opacity: 1;
}
    </style>

@endpush

@section('content')
@php
use App\Models\Basic\SubDepartment;
use App\Models\Besurv\Department;
use App\Models\Law\Basic\LawDepartment;

        $sql = "(CASE 
                    WHEN  sub_department.sub_depart_shortname IS NOT NUll && sub_department.sub_depart_shortname != '' THEN CONCAT(department.depart_nameShort,' (',sub_department.sub_depart_shortname,')')
                    ELSE  department.depart_nameShort
                END) AS title";

        $owner_sub_department = SubDepartment::leftjoin((new Department)->getTable().' AS department', 'department.did', '=', 'sub_department.did')
                                ->select( DB::raw($sql), 'sub_id' )
                                ->pluck('title','sub_id')->toArray();

        $owner_basic_department = LawDepartment::where('type', 2)->where('state',1)->pluck('title_short','id')->toArray();
@endphp

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">แจ้งงานคดี</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-cases-forms'))
                            <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/cases/forms/create') }}">
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
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1'=>'เลขที่อ้างอิง', '2'=>'ผู้ประกอบการ/TAXID', '3'=>'เลขที่ใบอนุญาต'), null, ['class' => 'form-control  text-center', 'placeholder'=>'-ทั้งหมดจาก-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-8">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขที่อ้างอิง, ผู้ประกอบการ/TAXID, เลขที่ใบอนุญาต']); !!}
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
                                        {!! Form::select('filter_status', App\Models\Law\Cases\LawCasesForm::status_list_filter(), null, ['class' => 'form-control  text-center', 'id' => 'filter_status', 'placeholder'=>'-สถานะทั้งหมด-']); !!}
                                </div>
                            </div>

                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_tisno', 'เลขที่ มอก./ชื่อ มอก.', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_tisno', App\Models\Basic\Tis::Where('status',1)->selectRaw('tb3_TisAutono, CONCAT(tb3_Tisno, " : ", tb3_TisThainame) As Title')->orderbyRaw('CONVERT(tb3_TisThainame USING tis620)')->pluck('Title', 'tb3_TisAutono'), null, ['class' => 'form-control  text-center', 'id' => 'filter_tisno', 'placeholder'=>'-เลือก เลข มอก./ชื่อ มอก.-']); !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_violate_section', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_violate_section[]', App\Models\Law\Basic\LawSection::Where('state',1)->orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id'), null, ['class' => '', 'required' => true, 'id' => 'filter_violate_section', 'multiple'=>'multiple']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_date_impound', 'วันที่ยึด-อายัด', ['class' => 'col-md-12 control-label ']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('filter_date_impound', null, ['class' => 'form-control mydatepicker  text-center', 'id' => 'filter_date_impound','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                                                        <i class="icon-calender"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                {!! Form::label('filter_offend_date', 'วันที่พบการกระทำความผิด', ['class' => 'col-md-12 control-label ']) !!}
                                                <div class="col-md-12">
                                                    <div class="inputWithIcon">
                                                        {!! Form::text('filter_offend_date', null, ['class' => 'form-control mydatepicker  text-center', 'id' => 'filter_offend_date','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                                                            <i class="icon-calender"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('filter_deperment_type', 'ประเภทหน่วยงาน', ['class' => 'col-md-12 control-label']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::select('filter_deperment_type', [ 1 => 'หน่วยงานภายใน (สมอ.)', 2 => 'หน่วยงานภายนอก' ]  ,null, ['class' => 'form-control', 'placeholder'=>'- เลือกประเภทหน่วยงาน -', 'required' => true ,'id'=>'filter_deperment_type' ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                       <!-- กรณีภายนอก -->
                                        <div class="col-md-4 box_law_bs_deperment_id" style="display:none;">
                                            <div class="form-group">
                                                {!! Form::label('filter_owner_basic_department_id', 'ชื่อหน่วยงาน/กอง/กลุ่ม', ['class' => 'col-md-12 control-label']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::select('filter_owner_basic_department_id',$owner_basic_department, null, ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อหน่วยงาน/กอง/กลุ่ม -', 'required' => true ,'id'=>'filter_owner_basic_department_id']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- กรณีภายใน -->
                                        <div class="col-md-4 box_sub_departments_id" style="display:none;">
                                            <div class="form-group">
                                                {!! Form::label('filter_owner_sub_department_id', 'ชื่อหน่วยงาน/กอง/กลุ่ม', ['class' => 'col-md-12 control-label']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::select('filter_owner_sub_department_id', $owner_sub_department,null, ['class' => 'form-control', 'placeholder'=>'- เลือกชื่อหน่วยงาน/กอง/กลุ่ม -', 'required' => true , 'id'=>'filter_owner_sub_department_id' ]) !!}
                                                </div>
                                            </div>
                                        </div>


                                   </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                    <div class="clearfix"></div>

                    @include('laws.cases.forms.modals.modal-status');

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="15%">เลขที่อ้างอิง/<br>เลขคดี</th>
                                        <th class="text-center" width="17%">ผู้ประกอบการ/TAXID</th>
                                        {{-- <th class="text-center" width="16%">มอก. / เลขที่<br>ใบอนุญาต</th> --}}
                                        <th class="text-center" width="8%">ฝ่าฝืนตาม<br>มาตรา</th>
                                        <th class="text-center" width="10%">ผู้แจ้ง</th>
                                        <th class="text-center" width="10%">ผู้บันทึก<br>วันที่บันทึก</th>
                                        <th class="text-center" width="10%">สถานะ</th>
                                        <th class="text-center" width="16%">ส่งเรื่องถึง</th>
                                        <th class="text-center" width="12%">จัดการ</th>
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




    @include('laws.cases.forms.modals.approve');

    {{-- Modal log Working --}}
    @include('laws.cases.forms.modals.log-working');

    @include('laws.cases.forms.modals.modal-file')
@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script>
        var table = '';
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
                                    $tr += '<td class="text-top">' +(checkNone(value.authorize_name) ? value.authorize_name:'')+ '</td>';
                                    $tr += '<td class="text-top">' +(checkNone(value.position) ? value.position:'')+ '</td>';
                                    $tr += '<td class="text-top">' +(checkNone(value.status_text) ? value.status_text:'')+ '</td>';
                                    $tr += '<td class="text-top">' +(checkNone(value.remark) ? value.remark:'')+ '</td>';
                                    $tr += '<td class="text-top text-center">' +(checkNone(value.attach) ? value.attach:'')+ '</td>';
                                    $tr += '<td class="text-top">' +(checkNone(value.format_create_at_time) ? value.format_create_at_time:'')+ '</td>';
                                    $tr += '</tr>';  
                                });
                                $('#table_tbody_approve').append($tr);
                            }
                        }
                    });

                $('#ApproveModals').modal('show');
             });

            // $("body").on("click", ".show_approve", function() {// ส่งเรื่องถึง
            //          var number =  $(this).data('number');
            //            $('#tax_group').children('option[value!=""]').remove();  
            //             for(i=1;i<=number;i++)
            //             {
            //                 $('#tax_group').append('<option value="'+i+'"  >กลุ่มการพิจารณางานคดี '+i+'</option>');
            //             }
            //            $('#tax_group').val("1").select2();
            //            $('#a_id').val($(this).data('id'));
            //            $('#table_tbody_approve').html('');
            //     var url  = '{{ url('/law/cases/forms_approved/get_level_approves') }}/' + $(this).data('id') + '/1';
            //         $.ajax({
            //             url: url,
            //             type: 'GET',
            //             cache: false, 
            //             success: function(data) {
                
            //                 if (data.length > 0) {
                   
            //                     $.each(data,function(index, value){
            //                         var $tr = '';
            //                         $tr += '<tr>';
            //                         $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
            //                         $tr += '<td class="text-top">' +(checkNone(value.authorize_name) ? value.authorize_name:'')+ '</td>';
            //                         $tr += '<td class="text-top">' +(checkNone(value.position) ? value.position:'')+ '</td>';
            //                         $tr += '<td class="text-top">' +(checkNone(value.status_text) ? value.status_text:'')+ '</td>';
            //                         $tr += '<td class="text-top">' +(checkNone(value.remark) ? value.remark:'')+ '</td>';
            //                         $tr += '<td class="text-top text-center">' +(checkNone(value.attach) ? value.attach:'')+ '</td>';
            //                         $tr += '<td class="text-top">' +(checkNone(value.format_create_at_time) ? value.format_create_at_time:'')+ '</td>';
            //                         $tr += '</tr>';  
            //                         $('#table_tbody_approve').append($tr);
            //                     });
                      
            //                 }else{
            //                      var $tr = '<tr><td colspan="7"  class="text-center text-top"> <b>ยังไม่มีการพิจารณางานคดี</b></td></tr>';
            //                 }    $('#table_tbody_approve').append($tr);
            //                   $('#ApproveModals').modal('show');
            //             }
            //         });

             
            //  });

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
                    url: '{!! url('/law/cases/forms/data_list') !!}',
                    data: function (d) {
                            d.filter_condition_search = $('#filter_condition_search').val();
                            d.filter_search = $('#filter_search').val();
                            d.filter_status = $('#filter_status').val();
                            d.filter_tisno = $('#filter_tisno').val();
                            d.filter_violate_section = $('#filter_violate_section').val();
                            d.filter_date_impound = $('#filter_date_impound').val();
                            d.filter_offend_date = $('#filter_offend_date').val();
                            
                            d.filter_deperment_type    = $('#filter_deperment_type').val();
                            d.filter_owner_basic_department_id = $('#filter_owner_basic_department_id').val();
                            d.filter_owner_sub_department_id   = $('#filter_owner_sub_department_id').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'name_taxid', name: 'name_taxid' },
                    // { data: 'tis_license_no', name: 'tis_license_no' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    { data: 'owner_name', name: 'owner_name' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'status', name: 'status' },
                    { data: 'approve', name: 'approve' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
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

            $('select[name="myTable_length"]').addClass('');

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
                        url: "{{ url('law/listen/ministry/update-state') }}",
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

            $(document).on('click', '.attachments', function(e) {
                var id = $(this).data('id');
                 $('#forms_id').val(id);
                $.ajax({
                        url:"{{ url('/law/cases/forms/get_file_additionals') }}" ,
                        data: { id:id},
                        type: 'GET',
                    }).done(function(  object ) {  
                            $('#id-file').html('');
                             $('#div_attach').find('.file_documents').val('');
                             $('#div_attach').find('.fileinput_exists').click();
                             $('#additionalsForm').parsley().reset();
                            if (object.attachs.length > 0) {
                                $.each(object.attachs,function(index, value){
                                var $html = '';
                                    $html +=   '<div class="row input_show_file mb-3" data-repeater-item>';
                                    $html +=   '    <label class="col-md-3 control-label personfile-label label-height" style="text-align: left !important" >เอกสารเพิ่มเติม<br/><span class="font_size">(เพิ่มได้ไม่เกิน 5 ไฟล์)</span></label>';
                                    $html +=   '    <div class="col-md-3">';
                                    $html +=   '        <input type="text" name="file_documents" value="'+value.caption+'" disabled class="form-control" placeholder="คำอธิบาย">';
                                    $html +=   '    </div>';
                                    $html +=   '    <div class="col-md-5">';
                                    $html +=   '        <a href="'+value.url+'" target="_blank" title="'+value.filename+'">';
                                    $html +=   '            <i class="fa fa-folder-open fa-lg" style="color:#FFC000;" aria-hidden="true"></i>';
                                    // $html +=   '            <span>'+value.filename+'</span>';
                                    $html +=   '        </a>';
                                    $html +=   '    </div>';
                                    $html +=   '    <div class="col-md-1" >';
                                    $html +=   '      <button type="button" class="btn btn-danger btn-outline btn_file_remove confirmation"  data-repeater-delete  data-id="'+value.id+'" title="ลบไฟล์" > <i class="fa fa-times"></i> </button> ';
                                    $html +=   '    </div>';
                                    $html +=   '</div>';

                                    $('#id-file').append($html); 
                                });
                            }
                          
                            if( $('#div_attach').find('.remove').length > 1){
                                $('#div_attach').find('.remove').each(function( index, data ) {
                                     if(index >= 1){
                                        $(data).closest('.input_show_file').remove();
                                     }
                               });
                            }else if( $('#div_attach').find('.remove').length == 0){
                                    $('#add_file_other').click();
                            }

                            $('#modal-file').modal('show');
                            resetOrderNoFile();
                    });
            });
 
            
            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input').val('').change();
                $('#BoxSearching').find('select').select2('val','');

                table.draw();
            });

            $("body").on("click", ".close_the_case", function() {                
                $('#show_status_modal').text($(this).data('cancel_remark'));
                $('#show_date_modal').text($(this).data('cancel_at'));
                $('#actionFour').modal('show');
            });

            $('#filter_deperment_type').change(function (e) { 
                 BoxDeparment();
                    
            });

        });

        function law_cases_delete(row_id){//ยกเลิกคำขอ

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
                        url: "{{ url('law/cases/forms/save-cancel') }}",
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
                                    title: 'ยกเลิกคำขอเลขที่ '+obj.ref_no+' เรียบร้อยแล้ว',
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
                        url: "{{ url('law/listen/ministry/update-state') }}",
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
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function BoxDeparment(){

            var type = $('#filter_deperment_type').val();

            var type1 = $('.box_sub_departments_id');
            var type2 = $('.box_law_bs_deperment_id');

                if( type == 1){

                    type1.show();
                    type1.find('select').prop('disabled', false);

                    type2.hide();
                    type2.find('select').prop('disabled', true);
                    type2.find('select').val('').change();

                }else{

                    type1.hide();
                    type1.find('select').prop('disabled', true);
                    type1.find('select').val('').change();

                    type2.show();
                    type2.find('select').prop('disabled', false);
                }

        }

    </script>

@endpush

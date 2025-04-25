@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
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

.text-sugar {
    font-size: 14px;
    color: #996633 !important;
    background-color: #FAE4D7;
    font-weight: 500;
    cursor: pointer;
    border-radius: 4px;
    padding: 2px 8px;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    min-width: 35px;
    margin-top: -2px;
}
 
.text-sugar:hover {
    background-color: #f7cdb5;
  }
  .mb-3{margin-bottom:1rem!important}
  .visually-hidden {
  clip: rect(0 0 0 0);
  clip-path: inset(50%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}
table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
    opacity: 1;
}
  </style>

@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">พิจารณางานคดี</h3>
 
                    <div class="clearfix"></div>
                    <hr> 
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                  <div class="col-md-6 form-group">
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1'=>'เลขที่อ้างอิง', '2'=>'ผู้ประกอบการ', '3'=>'TAXID'), null, ['class' => 'form-control  text-center', 'placeholder'=>'-ทั้งหมดจาก-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-8">
                                        {!! Form::text('filter_search', $filter_search, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขที่อ้างอิง, ผู้ประกอบการ, TAXID']); !!}
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
                                    {!! Form::select('filter_status_approve', ['1'=>'อยู่ระหว่างพิจารณา', '2'=>'พิจารณาครบถ้วน'], '1', ['class' => 'form-control  text-center', 'id' => 'filter_status_approve', 'placeholder'=>'-สถานะทั้งหมด-']); !!}
                                </div>
                            </div>

                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_violate_section', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_violate_section[]', App\Models\Law\Basic\LawSection::Where('state',1)->orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id'), null, ['class' => '', 'required' => true, 'id' => 'filter_violate_section', 'multiple'=>'multiple']) !!}
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
                                        <th class="text-center text-top" width="2%">#</th>
                                        <th class="text-center text-top" width="15%">เลขที่อ้างอิง/<br>เลขคดี</th>
                                        <th class="text-center text-top" width="17%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center text-top" width="10%">ฝ่าฝืนตาม<br>มาตรา</th>
                                        <th class="text-center text-top" width="9%">ลำดับที่ 1<br>(ผก.)</th>
                                        <th class="text-center text-top" width="9%">ลำดับที่ 2<br>(ผอ.)</th>
                                        <th class="text-center text-top" width="9%">ลำดับที่ 3<br>(รมอ.)</th>
                                        <th class="text-center text-top" width="9%">ลำดับที่ 4<br>(ลมอ.)</th>
                                        <th class="text-center text-top" width="10%">สถานะ</th>
                                        <th class="text-center text-top" width="8%">รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="clearfix"></div>
                      @include('laws.cases.forms_approved.modals.modal-status')

                      @if (Session::get('data-check-approve') == 'true' && count($approves) > 0)
                       <div class="clearfix"></div>
                       @include('laws.cases.forms_approved.modals.modal-check')
                      @endif
                     
              
                </div>
            </div>
        </div>

    </div>

    @include('laws.cases.forms_approved.modals.approve');

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
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
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/cases/forms_approved/data_list') !!}',
                    data: function (d) {
                            d.filter_condition_search = $('#filter_condition_search').val();
                            d.filter_search = $('#filter_search').val();
                            d.filter_status_approve = $('#filter_status_approve').val();
                            d.filter_violate_section = $('#filter_violate_section').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'name_taxid', name: 'name_taxid' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    { data: 'number1', name: 'number1' },
                    { data: 'number2', name: 'number2' },
                    { data: 'number3', name: 'number3' },
                    { data: 'number4', name: 'number4' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    $('.dataTables_empty').addClass('text-center');
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
 

            $("body").on("click", ".text-sugar", function() {     

                $('#m_id').val($(this).data('id'));  
                $('#approve_id').val($(this).data('approve_id'));
                $('#level').val($(this).data('level'));

                $('#span_no').html($(this).data('level'));
                $('#span_shortname').html($(this).data('shortname'));

                $('#fullname').val($(this).data('fullname'));
                $('#position').val($(this).data('position'));
                $('#send_position').val($(this).data('send_position'));

                $('#remark').val('');
                $('#attachs').val('');
                $('#attachs').prop('required', false);

                $('input[name=status][value=1]').prop('checked', true);
                $('input[name=status]').iCheck('update');
                 function_status();
                 
                 $('#status_cases').val('').select2();
                 $('#user_id').html("<option value='' >- เลือกส่งเรื่องกลับไปยัง -</option>").select2();
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ url('law/cases/forms_approved/get_user_approve') }}",
                    data: {
                            "_token": "{{ csrf_token() }}",
                            "id": $(this).data('id'),
                            "approve_id":$(this).data('approve_id'),
                            "level": $(this).data('level')
                        },
                }).success(function (obj) {
                    if(obj.message == true){
                        $.each(obj.datas, function( index, data ) {
                            $('#user_id').append('<option value="'+data.id+'"  >'+data.text+'</option>');
                        }); 
                    } 
                });
                 
                $('#actionStatus').modal('show');
                
            });

            $('input[name=status]').on('ifChecked', function(event){
                function_status();
            });
            function_status();
            
            $("body").on("click", ".show_approve", function() {// ส่งเรื่องถึง
                $('#table_tbody_approve').html('');
                var url  = '{{ url('/law/cases/forms/get_level_approves') }}/' + $(this).data('id');
                    $.ajax({
                        url: url,
                        type: 'GET',
                        cache: false,
                        success: function(data) {
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

            $('#form_status').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

   
                        var status = $('input[name="status"]:checked').val();
                      var html  = '';
                        if(status == '1'){
                          html = 'เห็นชอบ';
                        }else{
                          html = 'ไม่เห็นชอบ';
                        }
                            Swal.fire({
                                    title: 'ยืนยันการพิจารณา !',
                                    html: html,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'ยืนยัน',
                                    cancelButtonText: 'ยกเลิก'
                                }).then((result) => {
                                    if (result.value) {
                                        // Text
                                        $.LoadingOverlay("show", {
                                            image       : "",
                                            text        :   "กำลังบันทึกการพิจารณา กรุณารอสักครู่..." 
                                        });
                                        var formData = new FormData($("#form_status")[0]);
                                            formData.append('_token', "{{ csrf_token() }}");

                                            if($('#status_1').is(':checked',true)){
                                                formData.append('status', "1");    
                                            }else{
                                                formData.append('status', "2");    
                                            }
                                    
                                        $.ajax({
                                            type: "POST",
                                            url: "{{ url('/law/cases/forms_approved/save') }}",
                                            datatype: "script",
                                            data: formData,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            success: function (msg) {
                                                $.LoadingOverlay("hide");
                                                if(msg.message == true){
                                                    table.draw(); 
                                                    Swal.fire({
                                                            position: 'center',
                                                            icon: 'success',
                                                            title: 'บักทึกเรียบร้อยแล้ว!',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                    });
                                                    $('#actionStatus').modal('hide');
                                                    $('#form_status').find('ul.parsley-errors-list').remove();
                                                    $('#form_status').find('input,textarea').removeClass('parsley-success');
                                                    $('#form_status').find('input,textarea').removeClass('parsley-error'); 
                                                }else{
                                                    Swal.fire({
                                                            position: 'center',
                                                            icon: 'error',
                                                            title: 'เกิดข้อผิดพลาด!',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                    });
                                                }
                                            }
                                        });
                                    }
                                })
               
                return false;
            });

        });

          function function_status() {
            var status =  ($("input[name=status]:checked").val() == 1 )?'1':'2';
            if( status == 2){
                $('#box_status, #label_attachs , #box_attachs').removeClass('visually-hidden');
                $('#box_status').find('#status_cases, #user_id').prop('required', true);
                $('.send_position').addClass('visually-hidden');
            }else{
                $('#box_status, #label_attachs , #box_attachs').addClass('visually-hidden');
                $('#box_status').find('#status_cases, #user_id').prop('required', false);  
                $('.send_position').removeClass('visually-hidden');
            }
      
          }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
 
        


    </script>

@endpush

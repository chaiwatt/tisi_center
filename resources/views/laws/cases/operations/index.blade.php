@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
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
    
        .btn-light-info {
            background-color: #ccf5f8;
            color: #00CFDD !important;
        }
        .btn-light-info:hover, .btn-light-info.hover {
            background-color: #00CFDD;
            color: #fff !important;
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
          
                    <h3 class="box-title pull-left ">บันทึกการดำเนินงานคดี</h3>

                    <div class="pull-right"></div>

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">

                                <div class="col-md-6 form-group">
                                    <div class="form-group col-md-6">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขคดี', '2' => 'ผู้ประกอบการ/TAXID'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขคดี, ผู้ประกอบการ/TAXID']); !!}
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
                                        {!! Form::select('filter_status',   App\Models\Law\Cases\LawCasesForm::status_list(), null, ['class' => 'form-control ', 'id' => 'filter_status',  'placeholder'=>'-เลือกสถานะ-']) !!}
                                    </div>
                                </div>

                            </div>
                            @php
                                $option_tis     = App\Models\Basic\Tis::select(DB::Raw('CONCAT(tb3_Tisno," : ",tb3_TisThainame) AS title, tb3_TisAutono'))->pluck('title', 'tb3_TisAutono');

                                $option_section =  App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id');
                            @endphp
                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_tisi_no', 'เลข มอก.', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_tisi_no',$option_tis , null, ['class' => 'form-control ',  'placeholder'=>'- เลือก มอก. -', 'id'=>'filter_tisi_no']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_basic_section_id', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_basic_section_id',  $option_section ,null,  ['class' => 'select2 select2-multiple', "multiple"=>"multiple",'id'=>'filter_basic_section_id']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_created_at', 'บันทึกข้อมูลล่าสุด', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('filter_created_at', null, ['class' => 'form-control mydatepicker ', 'id' => 'filter_created_at','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                                                    <i class="icon-calender"></i>
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

                            <div class="alert alert-bg-secondary p-10">
                                คำอธิบาย : เมื่อดำเนินงานคดีเสร็จสิ้นแล้ว จะต้องแจ้งปิดงานคดี เพื่อส่งต่อข้อมูลไปยัง ผก./ผอ. กองกฎหมาย
                            </div>

                            {{-- @if( Auth::user()->can('edit-'.str_slug('law-cases-operations','-'))  )
                                <div class="pull-right">
                                    <button class="btn btn-info btn-sm btn-outline" id="closure_all" type="button"> <span class="font-15">แจ้งปิดงานคดี</span> </button>
                                </div> 
                            @endif --}}
                  
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">#</th>
                                        {{-- <th  width="1%" ><input type="checkbox" id="checkall"></th>  --}}
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="14%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="10%">มอก.</th>
                                        <th class="text-center" width="10%">มาตราความผิด</th>
                                        <th class="text-center" width="13%">รวมมูลค่าของกลาง/บาท</th> 
                                        <th class="text-center" width="10%">สถานะ</th> 
                                        <th class="text-center" width="10%">นิติกร</th>
                                        <th class="text-center" width="10%">บันทึกข้อมูลล่าสุด</th>
                                        <th class="text-center" width="8%">ดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    @if( Auth::user()->can('edit-'.str_slug('law-cases-operations','-'))  )
        @include('laws.cases.operations.modals.close')
    @endif

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
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
                    url: '{!! url('/law/cases/operations/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tisi_no = $('#filter_tisi_no').val();
                        d.filter_basic_section_id = $('#filter_basic_section_id').val();
                        d.filter_created_at = $('#filter_created_at').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    // { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'tis', name: 'tis' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    { data: 'total', name: 'total' },
                    { data: 'status', name: 'status' },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'created_at', name: 'created_at' }, 
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-2] },
                    { className: "text-right  text-top", targets:[5] },

                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    
                }
            });

            @can('edit-'.str_slug('law-cases-operations'))   

                // แจ้งปิดงานคดี 
                // $("body").on("click", "#closure_all", function() {
                //     $('#table_tbody_close').html('');
                //         var emails    = [];
                //     if($('#myTable').find(".item_checkbox:checked").length > 0){
                //         $.each($('#myTable').find(".item_checkbox:checked"),function (index,value) {
                //             var $tr = '';
                //                 $tr += '<tr>';
                //                 $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                //                 $tr += '<td class="text-top">' +($(value).data('case_number'))+ '</td>';
                //                 $tr += '<td class="text-top">' +($(value).data('offend_name'))+ '<br/>' +($(value).data('offend_taxid'))+'</td>';
                //                 $tr += '</tr>';
                //             $('#table_tbody_close').append($tr);

                //             // อีเมลผู้มอบหมาย
                //             if(checkNone($(value).data('emails')) && $.inArray($(value).data('emails'),emails) == '-1'){
                //                 emails.push($(value).data('emails'));
                //                 $('#email_results').tagsinput('add', $(value).data('emails')); 
                //             }

                //         });
     
                //         if(emails.length > 0){
                //             $("#checkbox1").prop('checked', true); 
                //         }else{
                //             $("#checkbox1").prop('checked', false); 
                //             $('#email_results').tagsinput('removeAll'); 
                //         }

                       
                //         $('#close_id').val('');
               
                //         $('#CloseCaseModals').modal('show');
                //     }else{
                //         $('#CloseCaseModals').modal('hide');
                //         Swal.fire({
                //             position: 'center',
                //             icon: 'warning',
                //             title: 'กรุณาเลือกเลขคดี',
                //             showConfirmButton: false,
                //             timer: 1500
                //         });
                //     }
              
                // });

                $("body").on("click", ".close_the_case", function() {
                    $('#table_tbody_close').html('');
                    var $tr = '';
                        $tr += '<tr>';
                        $tr += '<td class="text-center text-top">1</td>';
                        $tr += '<td class="text-top">' +($(this).data('case_number'))+ '</td>';
                        $tr += '<td class="text-top">' +($(this).data('offend_name'))+ '<br/>' +($(this).data('offend_taxid'))+'</td>';
                        $tr += '</tr>';
                        $('#table_tbody_close').append($tr);

                        $('#emails').val('');
                        if(checkNone($(this).data('emails'))){
                            $("#checkbox1").prop('checked', true); 
                            $('#email_results').tagsinput('add', $(this).data('emails')); 
                            $('#emails').val($(this).data('emails'));
                        }else{
                            $("#checkbox1").prop('checked', false); 
                            $('#email_results').tagsinput('removeAll'); 
                            $('#emails').val('');
                        }
                     

                    // $('#checkall').prop('checked',false );   
                    // table.$('.item_checkbox:checked').prop('checked',false );   
                    $('#close_id').val($(this).data('id'));
                    $('#CloseCaseModals').modal('show');
                });

            @endcan

            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            $('#checkbox1').on('click', function(e) {
                  $('#email_results').tagsinput('removeAll'); 
                if($(this).is(':checked',true)){
                    if(checkNone($('#emails').val())){
                        $('#email_results').tagsinput('add', $('#emails').val()); 
                    }
                } 
            });

          
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

        });
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
    </script>

@endpush

 
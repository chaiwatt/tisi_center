@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    
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
          
                    <h3 class="box-title pull-left ">ดำเนินการกับใบอนุญาต</h3>

                    <div class="pull-right">
                        @can('edit-'.str_slug('law-cases-manage-licenses'))
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="BtnModalCancel">
                                ยกเลิกพักใช้
                            </button>
                        @endcan
                    </div>
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
                                        {!! Form::select('filter_status', 
                                          [ 
                                            '-1'=> 'รอดำเนินการ',
                                            '1'=> 'ใช้งาน',
                                            '2'=> 'พักใช้',
                                            '3'=> 'เพิกถอน'
                                          ],
                                           null,
                                          ['class' => 'form-control ',
                                           'id' => 'filter_status',
                                           'placeholder'=>'-เลือกสถานะ-'])
                                          !!}
                                    </div>
                                </div>
                            </div>

                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_tisi_no', 'เลข มอก.', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_tisi_no',
                                                 App\Models\Basic\Tis::select(DB::Raw('CONCAT(tb3_Tisno," : ",tb3_TisThainame) AS title, tb3_TisAutono'))->pluck('title', 'tb3_TisAutono'), 
                                                 null, 
                                                 ['class' => 'form-control ',
                                                  'placeholder'=>'- เลือก มอก. -',
                                                  'id'=>'filter_tisi_no']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_basic_section_id', 'ฝ่าฝืนตามมาตรา', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_basic_section_id', 
                                                  App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'number'),
                                                 null, 
                                                 ['class' => 'select2 select2-multiple',
                                                 "multiple"=>"multiple",
                                                  'id'=>'filter_basic_section_id']) !!}
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
                                        <th class="text-center" width="14%">เลขคดี</th>
                                        <th class="text-center" width="15%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="10%">ใบอนุญาต</th>
                                        <th class="text-center" width="10%">กลุ่มผลิตภัณฑ์/เลข มอก.</th>
                                        <th class="text-center" width="10%">มาตราความผิด</th>
                                        <th class="text-center" width="10%">สถานะใบอนุญาต</th> 
                                        <th class="text-center" width="10%">สถานะ</th> 
                                        <th class="text-center" width="10%">นิติกร</th>
                                        <th class="text-center" width="8%">จัดการ</th>
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

    @include('laws.cases.manage_license.modals.cancel-pause')
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

            //สิทธิ์การแก้ไข
            var permisionedit = "{!! auth()->user()->can('edit-'.str_slug('law-cases-manage-licenses'))?true:false !!}";

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: '{!! url('/law/cases/manage_license/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tisi_no = $('#filter_tisi_no').val();
                        d.filter_basic_section_id = $('#filter_basic_section_id').val();
                    } 
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'offend_license_number', name: 'offend_license_number' },
                    { data: 'tis', name: 'tis' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    { data: 'status_result', name: 'status_result' },
                    { data: 'status_license', name: 'status_license' },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,6,7,8,-1] },
                    { className: "text-top", targets: "_all" },
                    { className: "text-center", visible: permisionedit, targets: 0 },

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

          
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });
            

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

            $('#BtnModalCancel').click(function () {

                var ids = [];
                var tr  = '';

                $('#table_tbody_cancel').html('');

                if($('#myTable').find(".item_checkbox:checked").length > 0){
           
                    //Iterate over all checkboxes in the table
                    table.$('.item_checkbox:checked').each(function (index, rowId) {
                        ids.push(rowId.value);

                        tr += '<tr>';
                        tr += '<td class="text-center text-top">'+( ++index )+' <input type="hidden" name="id[]" value="'+(rowId.value)+'" ></td>';
                        tr += '<td class="text-top">' +($(this).data('case_number'))+ '<div><em>(' +($(this).data('ref_no'))+ ')</em></div></td>';
                        tr += '<td class="text-top">' +($(this).data('offend_name'))+ '<br/>' +($(this).data('offend_taxid'))+'</td>';
                        tr += '</tr>';
                    });

                    $('#table_tbody_cancel').append(tr);
                
                    $('#CancelPauseModals').modal('show');

                }else{
                    $('#CancelPauseModals').modal('hide');
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกเลขที่อ้างอิง/เลขคดีอย่างน้อย 1 รายการ',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
   
            });

            $(document).on('click', '.btn_cancel_pause', function () {
                
                $('#table_tbody_cancel').html('');

                var tr  = '';
                    tr += '<tr>';
                    tr += '<td class="text-center text-top">1<input type="hidden" name="id[]" value="'+($(this).data('id'))+'" ></td>';
                    tr += '<td class="text-top">' +($(this).data('case_number'))+ '<div><em>(' +($(this).data('ref_no'))+ ')</em></div></td>';
                    tr += '<td class="text-top">' +($(this).data('offend_name'))+ '<br/>' +($(this).data('offend_taxid'))+'</td>';
                    tr += '</tr>';

                var form = $('#from_cancel_pause');

                    form.find('#date_pause_cancel').val($(this).data('date_pause_cancel'));
                    form.find('#remark_pause_cancel').val($(this).data('remark_pause_cancel'));

                $('#table_tbody_cancel').append(tr);
                $('#CancelPauseModals').modal('show');

            });

        });


        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined && value !== NaN;
        }

    </script>

@endpush

 
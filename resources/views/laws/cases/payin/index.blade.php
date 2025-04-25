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
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
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
 
    .btn-light-success {
    background-color: #c4e3ce;
    color: #28a745 !important;
    }
    .btn-light-success:hover, .btn-light-success.hover {
    background-color: #28a745;
    color: #fff !important;
    }

    .btn-light-warning {
    background-color: #eee0ce ;
    color: #ffc107 !important;
    }
    .btn-light-warning:hover, .btn-light-warning.hover {
    background-color: #ffc107;
    color: #fff !important;
    }

/* unvisited link */
        .record {
            color: blue;
        }
        /* mouse over link */
        .record:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
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
          
                    <h3 class="box-title pull-left ">ใบแจ้งชำระเงิน (Pay-in)</h3>

                    <div class="pull-right">
                        @can('delete-'.str_slug('law-cases-payin'))
                               <button type="button" class="btn btn-light-warning  waves-effect waves-light" id="ButtonModal">
                                   ยกเลิก pay-in
                                </button>
                              <div class="modal fade" id="PayinModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
                                <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="PayinModalLabel1">ยกเลิกใบแจ้งการชำระ (Pay-in)</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form_payin"  method="post" >
                                                {{ csrf_field() }}
                                         
                                                <div class="white-box">
                                                     <div class="row form-group">
                                                                {!! HTML::decode(Form::label('cancel_remark', 'เหตุผลยกเลิก'.' <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label font-medium-6  text-right'])) !!}
                                                          <div class="col-md-6 ">
                                                            {!! Form::textarea('cancel_remark',  null, ['class' => 'form-control', 'rows'=>'3' , "id"=>"cancel_remark",  'required' => true ]); !!}
                                                            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                                                         </div>
                                                     </div>
 
                                                     <div class="row form-group">
                                                            {!! HTML::decode(Form::label('created_by_show', 'ผู้ยกเลิก', ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                                                            <div class="col-md-6 ">
                                                                {!! Form::text('', auth()->user()->FullName, ['class' => 'form-control ',   'disabled' => true  ]) !!}
                                                                {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                                            </div>
                                                    </div>
                                                
                                                    <div class="row form-group">
                                                        {!! HTML::decode(Form::label('created_by_show', 'วันที่ยกเลิก', ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                                                        <div class="col-md-6 ">
                                                            {!! Form::text('',HP::DateTimeThai(date('Y-m-d H:i:s'))  , ['class' => 'form-control ',   'disabled' => true  ]) !!}
                                                            {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit"class="btn btn-primary" ><i class="icon-check"></i> บันทึก</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                        {!! __('ยกเลิก') !!}
                                                    </button>
                                                </div>
                                              </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {{-- {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!} --}}
                                    <div class="form-group col-md-6">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'ผู้ประกอบการ/TAXID', '3' => 'เลขที่ใบอนุญาต'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
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
                                    {{-- {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!} --}}
                                    <div class="col-md-12">
                                        {!! Form::select('filter_status', 
                                         ['2'=>'สร้าง Pay-in แล้ว','1'=>'ยังไม่สร้าง Pay-in'], 
                                           null,
                                          ['class' => 'form-control ',
                                          'id' => 'filter_status',
                                         'placeholder'=>'-สถานะทั้งหมด-']); !!}
                                    </div>
                                </div>
                            </div>

                                <div id="search-btn" class="panel-collapse collapse">
                                    <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_payments_detail', 'ชื่อรายการ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_payments_detail',
                                                   App\Models\Law\Cases\LawCasesPaymentsDetail::orderbyRaw('CONVERT(fee_name USING tis620)')->pluck('fee_name', 'fee_name'),
                                                 null, 
                                                 ['class' => 'form-control ',
                                                  'placeholder'=>'- เลือกชื่อรายการ -',
                                                  'id'=>'filter_payments_detail']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_ref1', 'เลขอ้างอิง', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::text('filter_ref1', null, ['class' => 'form-control  ', 'id' => 'filter_ref1'] ) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_created_at', 'ช่วงวันที่สร้าง', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                <div class="input-daterange input-group date-range">
                                                    {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                    {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                                                </div>
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_status_pay', 'สถานะการชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_status_pay', 
                                                ['2'=>'ชำระแล้ว','1'=>'ยังไม่ชำระ'], 
                                                  null,
                                                 ['class' => 'form-control ',
                                                 'id' => 'filter_status_pay',
                                                'placeholder'=>'- เลือกสถานะการชำระ -']); !!}
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
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="10%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="15%">ชื่อรายการ</th>
                                        <th class="text-center" width="10%">จำนวนเงิน</th>
                                        <th class="text-center" width="15%">สถานะ pay-in/สถานะชำระ</th>
                                        <th class="text-center" width="10%">เลขที่อ้างอิง (ref1)</th> 
                                        <th class="text-center" width="10%">สร้างเมื่อ</th>
                                        <th class="text-center" width="10%">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="clearfix"></div>


                    <div class="modal fade" id="PaymentModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="PaymentModalLabel1" aria-hidden="true">
                        <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
                             <div class="modal-content">
                                 <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                                     <h4 class="modal-title" id="PaymentModalLabel1">ข้อมูลการชำระ</h4>
                                 </div>
                                 <div class="modal-body">


                                         <div class="white-box">
                                              <div class="row form-group">
                                                <div class="col-md-12 ">
                                                    <div class="table">
                                                        <table class="table table-striped"  >
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center" width="2%">#</th>
                                                                    <th class="text-center" width="10%">ref1</th>
                                                                    <th class="text-center" width="10%">จำนวนเงิน</th>
                                                                    <th class="text-center" width="10%">เหตุผลยกเลิก</th>
                                                                    <th class="text-center" width="10%">ผู้สร้าง</th>
                                                                    <th class="text-center" width="10%">สร้างเมื่อ</th>
                                                                    <th class="text-center" width="10%">ไฟล์</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="table_tbody_payment">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                </div>
                                             </div>
                                         </div>
                                         <div class="text-right ">
                                             <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                 {!! __('ยกเลิก') !!}
                                             </button>
                                         </div>
                     
                                 </div>
                             </div>
                         </div>
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
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        var table = '';
        $(document).ready(function () {

        
            //ปฎิทิน
            $('.date-range').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
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
                    url: '{!! url('/law/cases/payin/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_payments_detail = $('#filter_payments_detail').val();
                        d.filter_ref1 = $('#filter_ref1').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                        d.filter_status_pay = $('#filter_status_pay').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'fee_name', name: 'fee_name' },
                    { data: 'amount', name: 'amount' },
                    { data: 'status', name: 'status' },
                    { data: 'ref', name: 'ref' },
                    { data: 'user_created', name: 'user_created' }, 
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1] },
                    { className: "text-right  text-top", targets:[5] },
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

          
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });
            

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });



            $("body").on("click", ".record", function() {
                          $('#table_tbody_payment').html('');
                          $.LoadingOverlay("show", {
                            image       : "",
                            text        :   "กำลังตรวจสอบ กรุณารอสักครู่..." 
                          });
                       $.ajax({
                                method: "get",
                                url: "{{ url('law/cases/payin/data_payments') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id":  $(this).data('id')
                                }
                            }).success(function (msg) {
    
                                if (msg.message == true) {
                                    $.each(msg.datas,function (index,value) {
                                       var  $tr = '';
                                            $tr += '<tr>';
                                                $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                                                $tr += '<td class="text-top">' +value.ref1+ '</td>';
                                                $tr += '<td class="text-top text-right">' +value.amount+ '</td>';
                                                $tr += '<td class="text-top">' +value.cancel_remark+ '</td>';
                                                $tr += '<td class="text-top">' +value.full_name+ '</td>';
                                                $tr += '<td class="text-top">' +value.created_at+ '</td>';
                                                $tr += '<td class="text-top text-center">' +value.button+ '</td>';
                                            $tr += '</tr>';
                                        $('#table_tbody_payment').append($tr);
                                    });
                                   
                                    $('#PaymentModals').modal('show');
                                    $.LoadingOverlay("hide");
                                    table.draw();
                                }else{
                                    $('#PaymentModals').modal('show');
                                    $.LoadingOverlay("hide");
                                    table.draw();
                                }
                            });
                  
                 });





            @can('delete-'.str_slug('law-cases-payin'))
                // มอบหมาย
                $('#form_payin').parsley().on('field:validated', function() {
                        var ok = $('.parsley-error').length === 0;
                        $('.bs-callout-info').toggleClass('hidden', !ok);
                        $('.bs-callout-warning').toggleClass('hidden', ok);
                    })  .on('form:submit', function() {
                        var ids = [];
                            //Iterate over all checkboxes in the table
                            table.$('.item_checkbox:checked').each(function (index, rowId) {
                                ids.push(rowId.value);
                            });
                            $.ajax({
                                method: "post",
                                url: "{{ url('law/cases/payin/save_payin') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "ids": ids,
                                    "cancel_remark": $('#cancel_remark').val()
                                }
                            }).success(function (msg) {
                                $('#form_payin').find('ul.parsley-errors-list').remove();
                                if (msg.message == true) {
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'บันทึกเรียบร้อย',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('#checkall').prop('checked',false );
                                    table.draw();
                                    $('#PayinModals').modal('hide');
                                    $("#cancel_remark").val(''); 
                                    $("select[id='assign_id']").val('').change(); 
                                    $("select[id='lawyer_ids']").val('').trigger('change');
                                }else{
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('#PayinModals').modal('hide');
                                    $("#cancel_remark").val(''); 
                                }
                            });

                        return false;
                    });

                    $("body").on("click", "#ButtonModal", function() {
                        if($('#myTable').find(".item_checkbox:checked").length > 0){
                            $('#PayinModals').modal('show');
                        }else{
                            $('#PayinModals').modal('hide');
                            Swal.fire({
                                        position: 'center',
                                        icon: 'warning',
                                        title: 'กรุณาเลือกเลขคดี',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                        }
                    });

 
             @endcan








        });



    </script>

@endpush

 
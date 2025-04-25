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
         .description {
            color: blue;
        }
        /* mouse over link */
        .description:hover {
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
          
                    <h3 class="box-title pull-left ">ตรวจสอบการชำระ</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-cases-payment'))
                            <button type="button" class="btn btn-light-warning  waves-effect waves-light" id="ButtonModal">
                                 เรียกข้อมูลการชำระ (API) 
                            </button>
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
                                          [         
                                            '1'=> 'ยังไม่ชำระเงิน',
                                            '2'=> 'ชำระเงินแล้ว'
                                          ], null,
                                          ['class' => 'form-control ',
                                          'id' => 'filter_status',
                                         'placeholder'=>'-สถานะชำระ-']); !!}
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
                                                    {!! Form::label('filter_created_at', 'ช่วงวันที่กำหนดชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                                <div class="col-md-12">
                                                    <div class="input-daterange input-group date-range">
                                                        {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                        {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                    {!! Form::label('filter_paid_channel', 'ช่องทางการชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::select('filter_paid_channel',
                                                       ['1'=> 'โอนเงิน', '2'=> 'เงินสด'], 
                                                         null, 
                                                         ['class' => 'form-control ',
                                                          'placeholder'=>'- เลือกชื่อรายการ -',
                                                          'id'=>'filter_paid_channel']) !!}
                                                   </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                {!! Form::label('filter_users', 'ผู้ตรวจสอบชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                                <div class="col-md-12">
                                                    @php
                                                        $user_ids =  App\Models\Law\Cases\LawCasesPayments::select('updated_by')->groupBy('updated_by')->pluck('updated_by');
                                                        $users    =   App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')
                                                                            ->whereIn('runrecno',$user_ids)
                                                                            ->orderbyRaw('CONVERT(title USING tis620)')
                                                                            ->pluck('title', 'id');
                                                     
                                 
                                                    @endphp
                                                    <select name="filter_users" id="filter_users" class="form-control">
                                                        <option value="">- เลือกผู้ตรวจสอบชำระ -</option>
                                                        <option value="null">e-Payment</option>
                                                        @if (count($users))
                                                                @foreach ($users as $key => $user)
                                                                <option value="{!!$key!!}">{!! $user !!}</option>
                                                                @endforeach
                                                        @endif
                                                      </select>
                                               </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                {!! Form::label('filter_amount_date', 'กำหนดชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::select('filter_amount_date',
                                                   ['1'=> 'เกินกำหนดชำระ', '2'=> 'ยังไม่ครบกำหนดชำระ'], 
                                                     null, 
                                                     ['class' => 'form-control ',
                                                      'placeholder'=>'- เลือกชื่อรายการ -',
                                                      'id'=>'filter_amount_date']) !!}
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
                                <span>คำอธิบาย : กรณีชำระเงินใบแจ้งการชำระ (Pay-in) ระบบจะตรวจสอบข้อมูลการชำระเงินอัตโนมัติ <span class="description">คลิกอ่านเพิ่มเติม</span></span>
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="10%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="15%">ชื่อรายการ</th>
                                        <th class="text-center" width="10%">ค่าปรับ</th>
                                        <th class="text-center" width="10%">วันครบกำหนดชำระ</th>
                                        <th class="text-center" width="10%">สถานะชำระ</th>
                                        <th class="text-center" width="10%">ช่องทางชำระ</th>
                                        <th class="text-center" width="10%">ผู้ตรวจสอบชำระ</th>
                                        <th class="text-center" width="10%">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
 

                    <div class="clearfix"></div>

 
                    <div class="modal fade" id="DescriptionModals" tabindex="-1" role="dialog" aria-labelledby="DescriptionModalsTitle" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document"> 
                             <div class="modal-content">
                                 
                                 <div class="modal-body">
                                    <p>
                                        {{-- ระบบจะเรียกข้อมูลการชำระอัตโนมัติ <br>
                                        ตามช่วงเวลา 7:53 , 11:53 , 15:53 ,18:53 , 23:53 <br>
                                        หากข้อมูลการชำระไม่อัพเดทสามารถคลิกปุ่ม "เรียกข้อมูลการชำระ" --}}
                                        กรณีชำระเงินผ่านใบแจ้งการชำระ (Pay-in)  ระบบจะตรวจสอบข้อมูลการชำระเงินอัตโนมัติ <br>
                                        ทั้งนี้ หากข้อมูลการชำระไม่อัพเดทสามารถคลิกปุ่ม "เรียกข้อมูลการชำระ"
                                    </p>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="modal fade" id="PaymentModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="PaymentModalLabel1" aria-hidden="true">
                        <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
                             <div class="modal-content">
                                 <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                                     <h4 class="modal-title" id="PaymentModalLabel1">ตรวจข้อมูลการชำระ (e-Payment)</h4>
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
                                                                    <th class="text-center" width="20%">เลขคดี</th>
                                                                    <th class="text-center" width="20%">ref1</th>
                                                                    <th class="text-center" width="20%">วันที่ชำระ</th>
                                                                    <th class="text-center" width="20%">เลขที่ใบเสร็จรับเงิน</th>
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
                    url: '{!! url('/law/cases/payment/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_payments_detail = $('#filter_payments_detail').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val(); 
                        d.filter_paid_channel = $('#filter_paid_channel').val();
                        d.filter_users        = $('#filter_users').val();
                        d.filter_amount_date = $('#filter_amount_date').val(); 
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'fee_name', name: 'fee_name' }, 
                    { data: 'amount', name: 'amount' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'status', name: 'status' },
                    { data: 'paid_channel', name: 'paid_channel' },
                    { data: 'user_updated', name: 'user_updated' }, 
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    // $('#myTable_length').find('.totalrec').remove();
                    // var el = ' <span class=" totalrec btn btn-link description">คำอธิบาย</span>';
                    // $('#myTable_length').append(el);
                    // $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
                }
            });
                $("body").on("click", ".description", function() {
                    $('#DescriptionModals').modal('show');
                 });

                 $("body").on("click", "#ButtonModal", function() {
                          $('#table_tbody_payment').html('');
                          $.LoadingOverlay("show", {
                            image       : "",
                            text        :   "กำลังตรวจสอบ กรุณารอสักครู่..." 
                          });
                       $.ajax({
                                method: "post",
                                url: "{{ url('api/v1/law_checkbill') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "paydate": "{{ date('Y-m-d') }}"
                                },
                                success:function (msg) {
                                if (msg.message == true) {
                                    $.each(msg.response,function (index,value) {
                                    var  $tr = '';
                                            $tr += '<tr>';
                                                $tr += '<td class="text-center text-top">' +(index+1)+ '</td>';
                                                $tr += '<td class="text-top">' +value.case_number+ '</td>';
                                                $tr += '<td class="text-top">' +value.ref1+ '</td>';
                                                $tr += '<td class="text-top">' +value.receipt_date+ '</td>';
                                                $tr += '<td class="text-top">' +value.receipt_code+ '</td>';
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
                            },
                            error:function(data){
                                $('#PaymentModals').modal('hide');
                                    $.LoadingOverlay("hide");
                                    Swal.fire({
                                        icon: 'warning',
                                        width: 600,
                                        position: 'center',
                                        title: 'ไม่สามารถเชื่อมข้อมูลได้ในขณะนี้',
                                        showConfirmButton: true,
                                    });
                                    table.draw();
                            }
                        })
                  
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
 
        });



    </script>

@endpush

 
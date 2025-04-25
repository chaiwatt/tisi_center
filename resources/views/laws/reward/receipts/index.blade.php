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
 
     .not-allowed {
        cursor: not-allowed
    }
    .rounded-circle {
        border-radius: 50% !important;
    }
        .mouse-link {
            color: blue;
        }
        /* mouse over link */
        .mouse-link:hover {
         color: blue;
        text-decoration: underline;
        cursor: pointer;
        }

        .pointer:hover {
            text-decoration: underline;
            cursor: pointer;
        }

    </style>
@endpush

@section('content')



    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left ">ใบสำคัญรับเงิน</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-reward-receipts'))
          
                                <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/reward/receipts/create') }}">
                                    <span class="btn-label"><i class="fa fa-plus"></i></span><b>สร้างใบสำคัญ</b>
                                </a>
                         @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row" id="BoxSearching">
    
                       <div class="col-md-12" >
                            <div class="row">
                                <div class="form-group col-md-3">
                                    {!! Form::select('filter_condition_search', array('1' => 'เลขคดี','2' => 'ชื่อผู้มีสิทธิ์','3' => 'เลขประจำตัวประชาชน'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขคดี,ชื่อผู้มีสิทธิ์,เลขประจำตัวประชาชน ']); !!}
                                </div>
                                <div class="col-md-3">
                                    <div class="  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search"> <i class="fa fa-search btn_search"></i> ค้นหา</button>
                                    </div>
                                    <div class="  pull-left m-l-15">
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                            ล้างค่า
                                        </button>
                                    </div>
                                </div>
                                <div class=" col-md-3 ">
                                        {!! 
                                        Form::select('filter_status',
                                            [ 'null'=> 'รอสร้างใบสำคัญรับเงิน', '1'=> 'สร้างใบสำคัญรับเงิน'  ], 
                                            null,
                                            ['class' => 'form-control text-center', 
                                            'id' => 'filter_status',
                                            'placeholder'=>'-เลือกสถานะ-'])
                                        !!}

                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-12" >
                            <div class="row">
                                <div class="form-group col-md-3">
                                        {!! Form::select('filter_type',
                                        [ '1'=> 'รายคดี',
                                            '2'=> 'รายเดือน',
                                            '3'=> 'ช่วงวันที่' 
                                        ],
                                            null, 
                                        ['class' => 'form-control ', 
                                        'id' => 'filter_type']); !!}
                                </div>
                                <div class="col-md-3" id="div_case_number">
                                    {!! Form::select('filter_case_number', 
                                                App\Models\Law\Reward\LawlRewardStaffLists::with(['law_reward_to'])  
                                                                                        ->whereHas('law_reward_to', function ($query2) {
                                                                                            return  $query2->WhereIn('status',['2','3','4','5']);
                                                                                        })
                                                                                        ->Where('created_by',auth()->user()->getKey())
                                                                                        ->orderbyRaw('CONVERT(case_number USING tis620)')
                                                                                        ->pluck('case_number', 'case_number'),
                                                null,
                                                ['class' => 'form-control ', 
                                                'id' => 'filter_case_number',
                                                'placeholder'=>'-เลือกรายคดี-']);
                                            !!}
                                </div>
                                <div class="col-md-3" id="div_paid_date">
                                    <div class="input-daterange  input-group  ">
                                            {!! Form::select('filter_paid_date_month',
                                                HP::MonthList(),
                                                null, 
                                                ['class' => 'form-control ', 
                                                'placeholder'=>'-เลือกเดือน-',
                                                'id' => 'filter_paid_date_month']);
                                             !!}
                                             <span class="input-group-addon"></span>
                                            {!! Form::select('filter_paid_date_year',
                                                HP::TenYearListReport(),
                                                null, 
                                              ['class' => 'form-control ', 
                                              'placeholder'=>'-เลือกปี-',
                                             'id' => 'filter_paid_date_year']);
                                         !!}
                                    </div>
                                </div>
                                <div class="col-md-3" id="div_paid_date_start">
                                    <div class="input-daterange input-group  date-range">
                                        {!! Form::text('filter_paid_date_start',   null, ['class' => 'form-control','id'=>'filter_paid_date_start']) !!}
                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                        {!! Form::text('filter_paid_date_end',  null, ['class' => 'form-control','id'=>'filter_paid_date_end']) !!}
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
                                        {{-- <th  width="1%"><input type="checkbox" id="checkall"></th> --}}
                                        <th class="text-center" width="1%">#</th>
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="15%">ชื่อผู้มีสิทธิ์ได้รับเงินรางวัล</th>
                                        <th class="text-center" width="12%">กลุ่มผู้มีสิทธิ์</th>
                                        <th class="text-center" width="9%">ร้อยละ(%)</th>
                                        <th class="text-center" width="10%">จำนวนเงิน</th>
                                        <th class="text-center" width="8%">หักไว้</th>
                                        <th class="text-center" width="8%">คงเหลือ</th>
                                        <th class="text-center" width="15%">หลักฐานใบสำคัญ</th> 
                                        <th class="text-center" width="10%">สถานะ</th>
                                        <th class="text-center" width="13%">พิมพ์ใบสำคัญ</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot style="background-color: rgb(245, 245, 245)">
                                    <tr>
    
                                    </tr>
                                 </tfoot>
                            </table>
                          
                        </div>
                    </div>

 

                    <div class="clearfix"></div>

                </div>
            </div>
        </div>
    </div>
    @include('laws.reward.receipts.modals.send-show')
    @include('laws.reward.receipts.modals.send-add')
        
@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('plugins/components/datatables-api-sum/api/sum().js')}}"></script>
    <script src="{{asset('js/function.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
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
            //ช่วงวันที่
            $('.date-range').datepicker({
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
                    url: '{!! url('/law/reward/receipts/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search   = $('#filter_condition_search').val();
                        d.filter_search             = $('#filter_search').val();
                        d.filter_status             = $('#filter_status').val();
                        d.filter_type               = $('#filter_type').val();
                        d.filter_case_number        = $('#filter_case_number').val();
                        d.filter_paid_date_month    = $('#filter_paid_date_month').val();
                        d.filter_paid_date_year     = $('#filter_paid_date_year').val();
                        d.filter_paid_date_start    = $('#filter_paid_date_start').val();
                        d.filter_paid_date_end      = $('#filter_paid_date_end').val();
                    } 
                },
                columns: [
                    // { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'name', name: 'name' },
                    { data: 'awardees', name: 'awardees' },
                    { data: 'division', name: 'division' },
                    { data: 'total', name: 'total' },
                    { data: 'deduct_amount', name: 'deduct_amount' },
                    { data: 'amount', name: 'amount' },
                    { data: 'evidence', name: 'evidence' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-3] },
                    { className: "text-right text-top", targets:[-6,-5,-4] },
                    { className: "text-center", visible: false, targets: 1 },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    var api = this.api();
                    var html = '';
                    var amount  =  api.column( 5, {page:'current'} ).data().sum().toFixed(2);
                    var amount1  =  api.column( 6, {page:'current'} ).data().sum().toFixed(2);
                    var amount2  =  api.column( 7, {page:'current'} ).data().sum().toFixed(2);
                    html += '<td class="text-right" colspan="4"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount2, 2)  +'</b></td>'+
                         '<td class="text-top text-right" colspan="3"></td>'
                    );

                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(1, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="10"><b>' + group + '</b></td></tr>'
                            );

                            last = group;
                        }
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


            $('body').on('change', '#filter_type', function(){

                  $('#filter_case_number,#filter_paid_date_month,#filter_paid_date_year,#filter_paid_date_start,#filter_paid_date_end').val('').change();         
                if($(this).val() == '1'){
                    $('#div_case_number').show(); 
                    $('#div_paid_date,#div_paid_date_start').hide(); 
                }else  if($(this).val() == '2'){
                    $('#div_paid_date').show(); 
                    $('#div_case_number,#div_paid_date_start').hide(); 
                }else  if($(this).val() == '3'){
                    $('#div_paid_date_start').show(); 
                    $('#div_case_number,#div_paid_date').hide(); 
                }
 
            });
            $('#filter_type').change();
 

            $('body').on('click', '.send-show', function(){
                    var   fullname = $(this).data('fullname');
                    var   send_remark = $(this).data('send_remark');
                    var   send_date = $(this).data('send_date');
                     $('#send_date,#fullname,#send_created').html('');
                    if(checkNone(send_remark)){
                        $('#remark').html(send_remark);
                    }
                    if(checkNone(fullname)){
                        $('#fullname').html(fullname);
                    }
                    if(checkNone(send_date)){
                        $('#send_date').html(send_date);
                    }
                    if(checkNone($(this).data('url')) && checkNone($(this).data('filename'))){
                    $('#div_attach').show();  
                    var   url = $(this).data('url');
                    var   filename = $(this).data('filename');
                    $('#p_attach').html('<a   href="'+ url  +'"   class="link_file"  target="_blank"> '+ filename +'</a>');   
                    }else{
                        $('#div_attach').hide();  
                        $('#p_attach').html('');  
                    }
                    $('#SendShowModals').modal('show');
            });
 
            
            $('body').on('click', '.send-add', function(){
                    var   conditon_type = $(this).data('conditon_type'); 
                    var   id = $(this).data('id'); 
                    $('#form_sends').find('ul.parsley-errors-list').remove();
                    $('#form_sends').find('input,textarea').removeClass('parsley-success');
                    $('#form_sends').find('input,textarea').removeClass('parsley-error');
                    if(conditon_type == '1'){
                        $('#SendAddModalLabel1').html('รอแนบหลักฐานกลับมา');
                    }else{
                        $('#SendAddModalLabel1').html('แนบหลักฐาน');
                    }
                    if(checkNone(id)){
                        $('#receipts_id').val(id);
                    }
             

                    $('#SendAddModals').modal('show');
            });
 
            
           
        });

 
           
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }


    </script>

@endpush

 
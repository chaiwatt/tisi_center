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
 

    .btn-light-primary {
    background-color: #719df0  ;
    color: #fff  !important;
    }
    .btn-light-primary:hover, .btn-light-primary.hover {
    background-color: #007bff ;
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
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
        opacity: 1;
    }

 
    </style>
@endpush
@php
    $option_status = App\Models\Law\Cases\LawCasesForm::status_list();

    $arr_unset     = [ 0,13, 14, 15, 99 ];
    foreach ($arr_unset as $value) {
        unset(  $option_status[$value] );
    }

    $option_section = App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id');
@endphp

@section('content')
 
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left "> เปรียบเทียบปรับ</h3>

                    <div class="pull-right">
                       
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <div class="form-group col-md-6">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'ผู้ประกอบการ/TAXID', '3' => 'เลขที่ใบอนุญาต','4' => 'เลขคดี'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
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
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status',  $option_status, null,  ['class' => 'select2 select2-multiple ','id' => 'filter_status',   "multiple"=>"multiple"]); !!}
                                    </div>
                                </div>
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                                {!! Form::label('filter_tisi_no', 'เลข มอก.', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::text('tb3_tisno', null , ['class' => 'form-control ','id'=>'tb3_tisno', 'placeholder' => 'ค้นจาก เลข มอก. / ชื่อ มอก.']) !!}
                                                {!! Form::hidden('filter_tisi_no', null , ['id'=>'filter_tisi_no']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_basic_section_id', 'มาตราความผิด', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_basic_section_id',  $option_section,  null,   ['class' => 'select2 select2-multiple',  "multiple"=>"multiple",  'id'=>'filter_basic_section_id']) !!}
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                             {!! Form::label('filter_created_at', 'วันที่ยึด-อายัด', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                <div class="inputWithIcon">
                                                    {!! Form::text('filter_created_at', null, ['class' => 'form-control mydatepicker ', 'id' => 'filter_created_at','placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off'] ) !!}
                                                    <i class="icon-calender"></i>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_case_number', 'เลขที่คดี', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::text('filter_case_number', null, ['class' => 'form-control', 'id' => 'filter_case_number']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_payment_status', 'สถานะใบแจ้งชำระ', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                {!! Form::select('filter_payment_status', ['1'=>'รอสร้างใบแจ้งชำระ', '2'=>'สร้างใบแจ้งชำระ'] , null,  ['class' => 'form-control  ','id' => 'filter_payment_status','placeholder' => '- เลือกสถานะใบแจ้งชำระ -' ]); !!}
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
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="14%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="10%">มอก.</th>
                                        <th class="text-center" width="10%">มาตราความผิด</th>
                                        <th class="text-center" width="13%">รวมมูลค่าของกลาง/บาท</th> 
                                        <th class="text-center" width="10%">ค่าปรับ/บาท</th>
                                        <th class="text-center" width="13%">นิติกร</th>
                                        <th class="text-center" width="10%">สถานะงานคดี</th>
                                        <th class="text-center" width="10%">จัดการ</th>
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

                    @can('edit-'.str_slug('law-cases-compares')) 
                        @include('laws.cases.compares.modals.compares')
                    @endcan

                    <div class="clearfix"></div>

                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/datatables-api-sum/api/sum().js')}}"></script>
    <script src="{{asset('js/function.js')}}"></script>
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
                    url: '{!! url('/law/cases/compares/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tisi_no = $('#filter_tisi_no').val(); 
                        d.filter_basic_section_id = $('#filter_basic_section_id').val();
                        d.filter_created_at = $('#filter_created_at').val();
                        d.filter_case_number = $('#filter_case_number').val();
                        d.filter_payment_status = $('#filter_payment_status').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'law_basic_section', name: 'law_basic_section' },
                    { data: 'total', name: 'total' },
                    { data: 'amount', name: 'amount' },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'status', name: 'status' }, 
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2,-3] },
                    { className: "text-right  text-top", targets:[5,6] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 5, {page:'current'} ).data().sum().toFixed(2);
                    var amount2  =  api.column( 6, {page:'current'} ).data();
                    var amount  = 0;
                        if(amount2.length > 0)  {
                            $.each(amount2, function( index, data ) {
                               var row =   amount2[index];
                               if(checkNone(row)){
                                row =   evitamos_script(row);
                                if(row != 'รอเปรียบเทียบปรับ'){
                                    amount += parseFloat(RemoveCommas(row));
                                }
                               }
                            });                   
                        }
                    html += '<td class="text-right" colspan="5"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount.toFixed(2), 2)  +'</b></td>'+
                         '<td class="text-top text-right" colspan="3"></td>'
                    );
                }
            });

            //เลือกทั้งหมด
            $('#compare_type').on('click', function(e) {
                compare_type();
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

        function compare_type(){
            if($('#compare_type').is(':checked',true)){
                $(".row-attachs").show();
                $("#attachs").prop('required', true);
            } else {
                $(".row-attachs").hide();
                $("#attachs").prop('required',false);
            }
        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        
        function evitamos_script($texto) {
             const strippedString = $texto.replace(/(<([^>]+)>)/gi, "");
             return strippedString;
         }

    </script>

@endpush

 
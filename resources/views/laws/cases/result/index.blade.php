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
table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
    opacity: 1;
}
    </style>
@endpush

@php
    $option_status = App\Models\Law\Cases\LawCasesForm::status_list();

    $arr_unset = [ 0, 13, 14, 99 ];

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
          
                    <h3 class="box-title pull-left ">พิจารณาความผิด</h3>

                    <div class="pull-right">
                       
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="form-group col-md-4">
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
                                    {!! Form::label('filter_status', 'สถานะ', ['class' => 'col-md-3 control-label text-right']) !!}
                                    <div class="col-md-9">
                                        {!! Form::select('filter_status',  $option_status , [2], [ 'class' => 'select2 select2-multiple',  "multiple"=>"multiple", 'id' => 'filter_status',  'data-placeholder'=>'-ทั้งหมด-']); !!}
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
                                                  App\Models\Law\Basic\LawSection::orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id'),
                                                 null, 
                                                 ['class' => 'select2 select2-multiple',
                                                 "multiple"=>"multiple",
                                                  'id'=>'filter_basic_section_id']) !!}
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
                                        <th class="text-center" width="10%">เลขที่อ้างอิง/เลขคดี</th>
                                        <th class="text-center" width="10%">ผู้ประกอบการ/TAXID</th>
                                        <th class="text-center" width="10%">มอก./เลขที่ใบอนุญาต</th>
                                        <th class="text-center" width="10%">มาตราความผิด</th>  
                                        <th class="text-center" width="10%">จำนวนของกลาง<br>(ยึด-อายัด)</th>  
                                        <th class="text-center" width="10%">นิติกร</th> 
                                        <th class="text-center" width="10%">สถานะงานคดี</th>
                                        <th class="text-center" width="10%">จัดการ</th>
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

    {{-- Modal log Working --}}
    @include('laws.cases.forms.modals.log-working');

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



            @if(\Session::has('results_error_message'))
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: '{{session()->get('results_error_message')}}',
                    showConfirmButton: true,
                    // timer: 1500
                });
            @endif

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
                    url: '{!! url('/law/cases/results/data_list') !!}',
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
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'punish', name: 'punish'},
                    { data: 'amount_impounds_and_keep', name: 'amount_impounds_and_keep' },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'status', name: 'status' }, 
                    { data: 'action', name: 'action', searchable: false, orderable: false}
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1] },
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

        });

    </script>

@endpush

 
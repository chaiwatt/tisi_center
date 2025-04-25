@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        .difference {
            color: blue;
        }
         .difference:hover {
         color: blue;
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

                <h3 class="box-title pull-left">รายงานการเบิกจ่ายเงินสินบนรางวัล</h3>

                <hr class="hr-line">
                <div class="row">
                    <div class="col-md-12" id="BoxSearching">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                <div class="form-group col-md-4">
                                    {!! Form::select('filter_condition_search',  array('1' => 'เลขคดี', '2' => 'ผู้ประกอบการ/TAXID', '3' => 'เลขที่ใบอนุญาต'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                </div>
                                <div class="col-md-6">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search','title'=>'ค้นหา:เลขคดี, ผู้ประกอบการ/TAXID, เลขที่ใบอนุญาต']); !!}
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
                                {!! Form::select('filter_status',  [ 1 => 'อยู่ระหว่างเบิกจ่าย', 2 => 'เบิกจ่ายเรียนร้อย'], null, ['class' => 'form-control ', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                            </div>
                        </div>

                        <div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="row">
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
                                                    'id' => 'filter_type',
                                                     'placeholder'=>'-เลือกประเภท-']); !!}
                                            </div>
                                            <div class="col-md-3" id="div_case_number">
                                                {!! Form::select('filter_case_number', 
                                                          App\Models\Law\Reward\LawlRewardWithdrawsDetails::orderbyRaw('CONVERT(case_number USING tis620)')->pluck('case_number', 'case_number'),
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
                                            <div class="col-md-5">
                                                {!! Form::text('filter_forerunner', null,['class' => 'form-control input-show auto-show typeahead',  'id' => 'filter_forerunner', 'autocomplete' => 'off', 'data-provide' => 'typeahead', 'placeholder' => 'ค้นหา:ผู้เบิก' ]) !!}
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
              
                        <p class="h2 text-bold-550 text-center">รายงานการเบิกจ่ายเงินสินบนรางวัล</p>
                        <p class="h4 text-bold-400 text-center">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right has-dropdown">
                            @if(auth()->user()->can('export-'.str_slug('law-report-rewards')))
                                <button type="button"  class="btn btn-success waves-effect waves-light"  id="ButtonPrintExcel">
                                    <i class="mdi mdi-file-excel"></i> Export Excel
                                </button>
                            @endif
                        </div>
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">ลำดับ</th>
                                    <th width="10%" class="text-center">เลขคดี</th>
                                    <th width="10%" class="text-center">ผู้ประกอบการ/TAXID</th>
                                    <th width="10%" class="text-center">ผู้เบิก/วันที่</th>
                                    <th width="10%" class="text-center">สถานะ</th>
                                    <th width="10%" class="text-center">ค่าปรับ</th>
                                    <th width="10%" class="text-center">หักรายได้แผ่นดิน</th>
                                    <th width="10%" class="text-center">ค่าดำเนินการ</th>
                                    <th width="10%" class="text-center">เงินสินบน</th>
                                    <th width="10%" class="text-center">เงินรางวัล</th>
                                    <th width="10%" class="text-center">ส่งรายได้แผ่นดิน</th>
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

@endsection

@push('js')
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables-api-sum/api/sum().js')}}"></script>
    <script src="{{asset('js/function.js')}}"></script>
    
    <script>
        var table = '';
        $(document).ready(function () {

             $(document).on('click', '#ButtonPrintExcel', function(){
                var url = 'law/report/rewards/export_excel';
                    url += '?filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_search=' + $('#filter_search').val();
                    url += '&filter_status=' + $('#filter_status').val();

                    if(checkNone($('#filter_type').val())){
                        url += '&filter_type=' + $('#filter_type').val();
                    }
                    if(checkNone($('#filter_case_number').val())){
                        url += '&filter_case_number=' + $('#filter_case_number').val();
                    }
                    if(checkNone($('#filter_paid_date_month').val())){
                        url += '&filter_paid_date_month=' + $('#filter_paid_date_month').val();
                    }
                    if(checkNone($('#filter_paid_date_year').val())){
                        url += '&filter_paid_date_year=' + $('#filter_paid_date_year').val();
                    }
                    if(checkNone($('#filter_paid_date_start').val())){
                        url += '&filter_paid_date_start=' + $('#filter_paid_date_start').val();
                    }
                    if(checkNone($('#filter_paid_date_end').val())){
                        url += '&filter_paid_date_end=' + $('#filter_paid_date_end').val();
                    }
                    if(checkNone($('#filter_forerunner').val())){
                        url += '&filter_forerunner=' + $('#filter_forerunner').val();
                    }
                    window.location = '{!! url("'+url +'") !!}';
                });


            //ปฎิทิน
            $('.date-range').datepicker({
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
                    url: '{!! url('/law/report/rewards/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search   = $('#filter_condition_search').val();
                        d.filter_status             = $('#filter_status').val();
                        d.filter_search             = $('#filter_search').val();
                        d.filter_type               = $('#filter_type').val();
                        d.filter_case_number        = $('#filter_case_number').val();
                        d.filter_paid_date_month    = $('#filter_paid_date_month').val();
                        d.filter_paid_date_year     = $('#filter_paid_date_year').val();
                        d.filter_paid_date_start    = $('#filter_paid_date_start').val(); 
                        d.filter_paid_date_end      = $('#filter_paid_date_end').val();
                        d.filter_forerunner         = $('#filter_forerunner').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'forerunner_name', name: 'forerunner_name' },
                    { data: 'status', name: 'status' },  
                    { data: 'paid_amount', name: 'paid_amount' },
                    { data: 'government_total', name: 'government_total' },
                    { data: 'operate_total', name: 'operate_total' },
                    { data: 'bribe_total', name: 'bribe_total' },
                    { data: 'reward_total', name: 'reward_total' },
                    { data: 'difference', name: 'difference' }
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0] },
                    { className: "text-right  text-top", targets:[-1,-2,-3,-4,-5,-6] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 5, {page:'current'} ).data().sum().toFixed(2);
                    var amount2  =  api.column( 6, {page:'current'} ).data().sum().toFixed(2);
                    var amount3  =  api.column( 7, {page:'current'} ).data().sum().toFixed(2);
                    var amount4  =  api.column( 8, {page:'current'} ).data().sum().toFixed(2);
                    var amount5  =  api.column( 9, {page:'current'} ).data().sum().toFixed(2);
                    var amount6  =  api.column( 10, {page:'current'} ).data();
                    var amount  = 0;
                        if(amount6.length > 0)  {
                            $.each(amount6, function( index, data ) {
                               var row =   amount6[index];
                               if(checkNone(row)){
                                  amount += parseFloat(RemoveCommas(evitamos_script(row)));
                               }
                            
                            });                   
                        }
                    html += '<td class="text-right" colspan="5"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount2, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount3, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount4, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount5, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount.toFixed(2), 2)  +'</b></td>'
                    );
                }
            });
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_search,#filter_created_at,#filter_type,#filter_case_number,#filter_paid_date_month,#filter_paid_date_year,#filter_paid_date_start,#filter_paid_date_end,#filter_forerunner').val('').change();
                $('#filter_status').val('').trigger('change.select2');
                $('#filter_type').change();
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
                }else{
                    $('#div_case_number,#div_paid_date,#div_paid_date_start').hide(); 
                }
            });
            $('#filter_type').change();
 
            var path = '{{ url('law/report/rewards/search_users') }}';
            $('#filter_forerunner').typeahead({
                minLength: 3,
                source:  function (query, process) {
                    return $.get(path, { query: query}, function (data) {
                        console.log(data);
                        return process(data);
                    });
                },
                autoSelect: true,
                afterSelect: function (jsondata) {

                    console.log(jsondata);
                    $('#filter_forerunner').val(jsondata.name);
                
                }
            });


            $('body').on('click', '.difference', function(){
                var id = $(this).data('id');
                    if(checkNone(id)){
                        window.location = '{!! url("law/report/rewards/'+ id +'") !!}';
                    }
            });

 
        });
           
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

        function evitamos_script($texto) {
             const strippedString = $texto.replace(/(<([^>]+)>)/gi, "");
             return strippedString;
         }

    </script>

@endpush

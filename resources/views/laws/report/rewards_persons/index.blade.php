@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
 
    </style>
@endpush

@section('content')

<div class="container-fluid">
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">

                <h3 class="box-title pull-left">รายงานผู้มีสิทธิ์ได้รับเงินรางวัล จำแนกตามบุคคล</h3>

                <hr class="hr-line">
                <div class="row">
                    <div class="col-md-12" id="BoxSearching">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                {{-- {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!} --}}
                                <div class="form-group col-md-6">
                                    {!! Form::select('filter_condition_search',  array( '1' => 'ชื่อ-สกุลผู้มีสิทธิ์', '2' => 'TAXID'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                </div>
                                <div class="col-md-6">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search','title'=>'ค้นหา:ชื่อ-สกุลผู้มีสิทธิ์, TAXID']); !!}
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
                                {!! Form::select('filter_reward_group',      
                                 App\Models\Law\Basic\LawRewardGroup::where('state',1)->orderBy('ordering', 'ASC')->pluck('title', 'id'),
                                 null, ['class' => 'form-control ', 
                                 'id' => 'filter_reward_group', 
                                 'placeholder'=>'-เลือกกลุ่มผู้มีสิทธิ์-']); !!}
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
                                            <div class="form-group col-md-3">
                                                    {!! Form::select('filter_law_arrest', 
                                                    App\Models\Law\Basic\LawArrest::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                                    null, 
                                                    ['class' => 'form-control', 
                                                    'id'=> 'filter_law_arrest', 
                                                    'placeholder'=>'-เลือกการจับกุม-']); !!}
                                            </div>
                                            <div class="col-md-3" >
                                                <span id="div_case_number">
                                                    {!! Form::select('filter_case_number', 
                                                     App\Models\Law\Reward\LawlRewardStaffLists::orderbyRaw('CONVERT(case_number USING tis620)')->pluck('case_number', 'case_number'),
                                                      null,
                                                      ['class' => 'form-control ', 
                                                      'id' => 'filter_case_number',
                                                      'placeholder'=>'-เลือกรายคดี-']);
                                                  !!}
                                                </span>
                                                <span id="div_paid_date">
                                                  <div class="input-daterange  input-group  ">
                                                        {!! Form::select('filter_paid_date_month',
                                                            HP::MonthList(),
                                                            null, 
                                                            ['class' => 'form-control ', 
                                                            'placeholder'=>'-เลือกเดือน-',
                                                            'id' => 'filter_paid_date_month']);
                                                         !!}
                                                         <span class="input-group-addon"></span>
                                                         <select name="filter_paid_date_year" id="filter_paid_date_year" class="form-control">
                                                            <option value="">-เลือกปี-</option>
                                                            @for ($start_year = date('Y'); $start_year >= 1880; $start_year--)
                                                              @php
                                                                $year = $start_year + 543;
                                                             @endphp   
                                                                 <option value="{{$start_year}}">{!!$year!!}</option>
                                                            @endfor
                                                        </select>
                                                  </div>
                                                </span>
                                                <span id="div_paid_date_start">
                                                    <div class="input-daterange input-group  date-range">
                                                        {!! Form::text('filter_paid_date_start',   null, ['class' => 'form-control','id'=>'filter_paid_date_start']) !!}
                                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                        {!! Form::text('filter_paid_date_end',  null, ['class' => 'form-control','id'=>'filter_paid_date_end']) !!}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                {!! Form::label('filter_condition_search', 'วันที่ออกใบสำคัญรับเงิน', ['class' => 'col-md-5 control-label text-right']) !!}
                                                <div class="form-group col-md-7">
                                                    <div class="input-daterange input-group  date-range">
                                                        {!! Form::text('filter_recepts_date_start',   null, ['class' => 'form-control','id'=>'filter_recepts_date_start']) !!}
                                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                        {!! Form::text('filter_recepts_date_end',  null, ['class' => 'form-control','id'=>'filter_recepts_date_end']) !!}
                                                    </div>
                                                </div>
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
              
                        <p class="h2 text-bold-550 text-center">รายงานผู้มีสิทธิ์ได้รับเงินรางวัล จำแนกตามบุคคล</p>
                        <p class="h4 text-bold-400 text-center">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right has-dropdown">
                            @if(auth()->user()->can('export-'.str_slug('law-report-rewards-persons')))
                                <button type="button"  class="btn btn-success waves-effect waves-light"  id="ButtonPrintExcel">
                                    <i class="mdi mdi-file-excel"></i> Export Excel
                                </button>
                            @endif
                        </div>
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">ลำดับ</th>
                                    <th width="10%" class="text-center">ชื่อ-สกุลผู้มีสิทธิ์</th>
                                    <th width="10%" class="text-center">การจับกุม/เลขคดี</th>
                                    <th width="10%" class="text-center">กลุ่มผู้มีสิทธิ์</th>
                                    <th width="10%" class="text-center">ร้อยละ</th>
                                    <th width="10%" class="text-center">จำนวนเงิน</th>
                                    <th width="10%" class="text-center">หักไว้</th>
                                    <th width="10%" class="text-center">คงเหลือ</th>
                                    <th width="10%" class="text-center">วันที่ออกใบสำคัญรับเงิน</th>
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
                var url = 'law/report/rewards_persons/export_excel';
                    url += '?filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_search=' + $('#filter_search').val();
                    url += '&filter_reward_group=' + $('#filter_reward_group').val();

                    if(checkNone($('#filter_type').val())){
                        url += '&filter_type=' + $('#filter_type').val();
                    }
                    
                    if(checkNone($('#filter_law_arrest').val())){
                        url += '&filter_law_arrest=' + $('#filter_law_arrest').val();
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
                    if(checkNone($('#filter_recepts_date_start').val())){
                        url += '&filter_recepts_date_start=' + $('#filter_recepts_date_start').val();
                    }
                    if(checkNone($('#filter_recepts_date_end').val())){
                        url += '&filter_recepts_date_end=' + $('#filter_recepts_date_end').val();
                    }
                    window.location = '{!! url("'+url +'") !!}';
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
                    url: '{!! url('/law/report/rewards_persons/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search           = $('#filter_condition_search').val();
                        d.filter_reward_group               = $('#filter_reward_group').val();
                        d.filter_search                     = $('#filter_search').val();
                        d.filter_type                       = $('#filter_type').val();
                        d.filter_law_arrest                 = $('#filter_law_arrest').val();
                        d.filter_case_number                = $('#filter_case_number').val();
                        d.filter_paid_date_month            = $('#filter_paid_date_month').val();
                        d.filter_paid_date_year             = $('#filter_paid_date_year').val();
                        d.filter_paid_date_start            = $('#filter_paid_date_start').val(); 
                        d.filter_paid_date_end              = $('#filter_paid_date_end').val();
                        d.filter_recepts_date_start         = $('#filter_recepts_date_start').val();
                        d.filter_recepts_date_end           = $('#filter_recepts_date_end').val();
               
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name', name: 'name' },
                    { data: 'arrest', name: 'arrest' },
                    { data: 'awardees', name: 'awardees' },
                    { data: 'division', name: 'division' },  
                    { data: 'total', name: 'total' },
                    { data: 'deduct_amount', name: 'deduct_amount' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_name', name: 'created_name' } 
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-5] },
                    { className: "text-right  text-top", targets:[-2,-3,-4] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 5, {page:'current'} ).data().sum().toFixed(2);
                    var amount2  =  api.column( 6, {page:'current'} ).data().sum().toFixed(2);
                    var amount3  =  api.column( 7, {page:'current'} ).data().sum().toFixed(2);
                    html += '<td class="text-right" colspan="5"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount2, 2)  +'</b></td>'+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount3, 2)  +'</b></td>'+
                         '<td class="text-top text-right"></td>'
                    );
                }
            });
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_search,#filter_created_at,#filter_type,#filter_law_arrest,#filter_case_number,#filter_paid_date_month,#filter_paid_date_year,#filter_paid_date_start,#filter_paid_date_end,#filter_forerunner').val('').change();
                $('#filter_reward_group').val('').trigger('change.select2');
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

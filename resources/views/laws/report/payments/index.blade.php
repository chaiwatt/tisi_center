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

@php
        $subdepart_ids    = ['0600','0601','0602','0603','0604'];//เจ้าหน้าที่ กม.

        $lawyers     = App\User::selectRaw('runrecno AS id, reg_subdepart, CONCAT(reg_fname," ",reg_lname) As title')
                                    ->whereIn('reg_subdepart',$subdepart_ids)
                                    ->pluck('title', 'id');  
@endphp

<div class="container-fluid">
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">

                <h3 class="box-title pull-left">รายงานการชำระเงินค่าปรับ</h3>

                <hr class="hr-line">
                <div class="row">
                    <div class="col-md-12" id="BoxSearching">
                        <div class="row">
                            <div class="col-md-6 form-group">
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
                                            {!! Form::label('filter_paid_channel', 'วันที่ชำระเงิน', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                            <div class="col-md-12">
                                                <div class="input-daterange input-group date-range">
                                                    {!! Form::text('filter_paid_start_date', null, ['id' => 'filter_paid_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                    <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                    {!! Form::text('filter_paid_end_date', null, ['id' => 'filter_paid_end_date','class' => 'form-control date', 'required' => true]) !!}
                                                </div>
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
                                                <option value="null">E-payment</option>
                                                @if (count($users))
                                                        @foreach ($users as $key => $user)
                                                        <option value="{!!$key!!}">{!! $user !!}</option>
                                                        @endforeach
                                                @endif
                                              </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        {!! Form::label('filter_lawyer_by', 'นิติกร', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                    <div class="col-md-12">
                                        {!! Form::select('filter_lawyer_by',
                                          $lawyers,
                                         null, 
                                         ['class' => 'form-control ',
                                          'placeholder'=>'- เลือกนิติกร -',
                                          'id'=>'filter_lawyer_by']) !!}
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
              
                        <p class="h2 text-bold-550 text-center">รายงานการชำระเงินค่าปรับ</p>
                        <p class="h4 text-bold-400 text-center">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right has-dropdown">
                            @if(auth()->user()->can('export-'.str_slug('law-report-payments')))
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
                                    <th width="10%" class="text-center">มาตราความผิด</th>
                                    <th width="10%" class="text-center">นิติกร</th>
                                    <th width="10%" class="text-center">ชื่อรายการ</th>
                                    <th width="10%" class="text-center">จำนวนเงิน/บาท</th>
                                    <th width="10%" class="text-center">วันครบกำหนดชำระ</th>
                                    <th width="10%" class="text-center">วันที่ชำระเงิน</th>
                                    <th width="10%" class="text-center">สถานะ</th>
                                    <th width="10%" class="text-center">ผู้ตรวจชำระ</th>
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
                var url = 'law/report/payments/export_excel';
                    url += '?filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_search=' + $('#filter_search').val();
 
                    if(checkNone($('#filter_status').val())){
                        url += '&filter_status=' + $('#filter_status').val();
                    }
                    if(checkNone($('#filter_payments_detail').val())){
                        url += '&filter_payments_detail=' + $('#filter_payments_detail').val();
                    }
                    if(checkNone($('#filter_start_date').val())){
                        url += '&filter_start_date=' + $('#filter_start_date').val();
                    }
                    if(checkNone($('#filter_end_date').val())){
                        url += '&filter_end_date=' + $('#filter_end_date').val();
                    }
           
                    if(checkNone($('#filter_paid_start_date').val())){
                        url += '&filter_paid_start_date=' + $('#filter_paid_start_date').val();
                    }
                    if(checkNone($('#filter_paid_end_date').val())){
                        url += '&filter_paid_end_date=' + $('#filter_paid_end_date').val();
                    }
                    if(checkNone($('#filter_users').val())){
                        url += '&filter_users=' + $('#filter_users').val();
                    }
                    if(checkNone($('#filter_lawyer_by').val())){
                        url += '&filter_lawyer_by=' + $('#filter_lawyer_by').val();
                    }
                    window.location = '{!! url("'+url +'") !!}';
                });

           //ช่วงวันที่
           $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });


 

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/report/payments/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_payments_detail = $('#filter_payments_detail').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val(); 
                        d.filter_paid_start_date = $('#filter_paid_start_date').val();
                        d.filter_paid_end_date = $('#filter_paid_end_date').val();
                        d.filter_users        = $('#filter_users').val();
                        d.filter_lawyer_by   = $('#filter_lawyer_by').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'offend_name', name: 'offend_name' },
                    { data: 'punish', name: 'punish' },
                    { data: 'lawyer_by', name: 'lawyer_by' },
                    { data: 'fee_name', name: 'fee_name' },
                    { data: 'amount', name: 'amount' },  
                    { data: 'end_date', name: 'end_date' },
                    { data: 'paid_date', name: 'paid_date' },
                    { data: 'status', name: 'status' },
                    { data: 'user_updated', name: 'user_updated' }
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0] },
                    { className: "text-right  text-top", targets:[-5] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 6, {page:'current'} ).data().sum().toFixed(2);
                    console.log(amount1);
                    html += '<td class="text-right" colspan="6"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-top text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-top text-right" colspan="4"></td>'
                    );
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

        function evitamos_script($texto) {
             const strippedString = $texto.replace(/(<([^>]+)>)/gi, "");
             return strippedString;
         }

    </script>

@endpush

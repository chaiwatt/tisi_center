@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
 

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงาน e-Payment</h3>

                    <div class="pull-right">
                        <button type="button"  class="btn btn-success waves-effect waves-light"  id="ButtonPrintExcel">
                                <i class="mdi mdi-file-excel"></i> Export Excel
                        </button>
                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div id="BoxSearching">
                        <div class="row">
                            <div class="col-md-12">
              
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก เลขที่คำขอ']); !!}
                                        </div><!-- /form-group -->
                                    </div><!-- /.col-lg-4 -->
                                    <div class="col-lg-4">
                                             {!! Form::select('filter_certify', array('1' => 'ห้องปฏิบัติการ', '2' => 'หน่วยตรวจ','3'=>'หน่วยรับรอง','4'=>'ห้องปฏิบัติการ(ติดตาม)','5'=>'หน่วยตรวจ(ติดตาม)','6'=>'หน่วยรับรอง(ติดตาม)'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกการรับรอง-','id'=>'filter_certify']); !!}
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                                <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group  pull-left">
                                            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                        </div>
                                        <div class="form-group  pull-left m-l-15">
                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                                ล้าง
                                            </button>
                                        </div>
                                    </div><!-- /.col-lg-1 -->
                             
                                </div><!-- /.row -->
              
                            </div>
                        </div>
                        <div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                             
                                <div class="row">
              
                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_start_date', 'วันที่แจ้งชำระ:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                        <div class="col-md-8">
                                          <div class="input-daterange input-group" id="date-range">
                                            {!! Form::text('filter_start_date', null, ['class' => 'form-control','id'=>'filter_start_date']) !!}
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            {!! Form::text('filter_end_date', null, ['class' => 'form-control','id'=>'filter_end_date']) !!}
                                          </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        {!! Form::label('filter_start_date', 'วันที่ตรวจประเมิน:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                        <div class="col-md-8">
                                          <div class="input-daterange input-group" id="date-check-range">
                                            {!! Form::text('filter_check_start_date', null, ['class' => 'form-control','id'=>'filter_check_start_date']) !!}
                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                            {!! Form::text('filter_check_end_date', null, ['class' => 'form-control','id'=>'filter_check_end_date']) !!}
                                          </div>
                                        </div>
                                    </div>
              
                                  <div class="form-group col-md-6">
                                    {!! Form::label('filter_status_confirmed', 'สถานะ:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('filter_status_confirmed', array('2' => 'แจ้งชำระค่าธรรมเนียม', '1' => 'ชำระค่าธรรมเนียมเรียบร้อย'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-','id'=>'filter_status_confirmed']); !!}
                                    </div>
                                  </div>

                                <div class="form-group col-md-6">
                                    {!! Form::label('filter_type', 'ประเภทค่าใช้จ่าย:', ['class' => 'col-md-4 control-label label-filter text-right']) !!}
                                    <div class="col-md-8">
                                         {!! Form::select('filter_type', array('1' => 'ค่าตรวจประเมิน', '2' => 'ค่าตรวจธรรมเนียมใบรับรอง'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกประเภทค่าใช้จ่าย-','id'=>'filter_type']); !!}
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
                                        {{-- <th width="1%"><input type="checkbox" id="checkall"></th> --}}
                                        <th width="1%"  class="text-center">No.</th>
                                        <th width="9%" class="text-center">เลขที่คำขอ</th>
                                        <th width="10%" class="text-center">ประเภทค่าใช้จ่าย</th>
                                        <th width="10%" class="text-center">การรับรอง</th>
                                        <th width="10%" class="text-center">วันที่ตรวจประเมิน</th>
                                        <th width="10%"  class="text-center">เลขอ้างอิงการแจ้งชำระ</th>
                                        <th width="10%" class="text-center">วันที่แจ้งชำระ</th>   
                                        <th width="8%" class="text-center">จำนวนเงิน</th>
                                        {{-- <th width="10%" class="text-center">วันที่ชำระ</th>    --}}
                                        <th width="10%" class="text-center">รหัสใบเสร็จรับเงิน</th>
                                        <th width="8%"  class="text-center">จำนวนที่ชำระ</th>
                                        <th width="10%" class="text-center">สถานะ</th> 
                                        <th width="3%" class="text-center">จัดการ</th>            
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
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <script src="{{asset('plugins/components/datatables-api-sum/api/sum().js')}}"></script>
    <script src="{{asset('js/function.js')}}"></script>
    <script>
        $(document).ready(function () {

            $(document).on('click', '#ButtonPrintExcel', function(){
                var url = 'cerreport/epayments/export_excel';
                    url += '?filter_search=' + $('#filter_search').val();
                    url += '&filter_type=' + $('#filter_type').val();
                    url += '&filter_certify=' + $('#filter_certify').val();
                    url += '&filter_status_confirmed=' + $('#filter_status_confirmed').val();
                    url += '&filter_start_date=' + $('#filter_start_date').val();
                    url += '&filter_end_date=' + $('#filter_end_date').val();
                    url += '&filter_check_start_date=' + $('#filter_check_start_date').val();
                    url += '&filter_check_end_date=' + $('#filter_check_end_date').val();
                    window.location = '{!! url("'+url +'") !!}';
                });



            //ช่วงวันที่
            jQuery('#date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });
            jQuery('#date-check-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

           
            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
        });

        $(function () {

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                autoWidth: false,
                ajax: {
                    url: '{!! url('/cerreport/epayments/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_type = $('#filter_type').val();            
                        d.filter_certify = $('#filter_certify').val();           
                        d.filter_status_confirmed = $('#filter_status_confirmed').val();           
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                        d.filter_check_start_date = $('#filter_check_start_date').val();
                        d.filter_check_end_date = $('#filter_check_end_date').val();
                    }
                },
                columns: [
                    // { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'appno', name: 'appno' },
                    { data: 'state', name: 'state' },
                    { data: 'certify', name: 'certify' },
                    { data: 'date_exam', name: 'date_exam' },
                    { data: 'Ref', name: 'Ref' },
                    { data: 'invoiceStartDate', name: 'invoiceStartDate' },
                    { data: 'amount', name: 'amount' },
                    { data: 'ReceiptCode', name: 'ReceiptCode' },
                    { data: 'amount_bill', name: 'amount_bill' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center", targets:[0] },
                    { className: "text-right", targets:[-3,-5] }
                ],
                fnDrawCallback: function() {

                    $('#myTable_length').find('.totalrec').remove();
                    var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
                    $('#myTable_length').append(el);
                    $('#myTable tbody').find('.dataTables_empty').addClass('text-center');

                    var api = this.api();
                    var html = '';
                    var amount1  =  api.column( 7, {page:'current'} ).data().sum().toFixed(2);
                    var amount2  =  api.column( 9, {page:'current'} ).data().sum().toFixed(2);
 
                    html += '<td class="text-center" colspan="7"><b>รวม</b></td>';
                   $(api.table().footer()).html(html+
                         '<td class="text-right"> <b>'+  addCommas(amount1, 2)  +'</b></td>'+
                         '<td class="text-right" ></td>'+
                         '<td class="text-right"> <b>'+  addCommas(amount2, 2)  +'</b></td>'+
                         '<td class="text-right" colspan="2"></td>'
                    );
                }
            });


            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                $(".item_checkbox").prop('checked', true);
                } else {
                $(".item_checkbox").prop('checked',false);
                }
            });


            $('#btn_search').click(function () {
                table.draw();
            });


            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });

            
            //เลือกสถานะ
            $('#myTable tbody').on('click', '.transaction_payin', function(){
                var ref1 = $(this).data('ref1');
                if (checkNone(ref1)) {
                    $.ajax({
                        method: "GET",
                        url: "{{ url('api/v1/checkbill') }}",
                        data: {
                            "ref1": ref1 
                        }
                    }).success(function (msg) {
                        
                        if(msg.message == true){
                           Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'ชำระเรียบร้อยแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                             });
                             table.draw();
                        }else{
                            Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'ยังไม่ชำระเรียบร้อย',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }

                    });
                }
            });

 

            

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }

    </script> 
@endpush

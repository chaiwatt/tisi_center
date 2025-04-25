@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <style>
        table.dataTable tbody td {
            vertical-align: top;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

        
                    <h3 class="box-title pull-left">รายงานผลตรวจติดตาม (IB)</h3>

                    <div class="pull-right">

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก รหัสหน่วย,มอก,ชื่อผู้ประกอบการ,เลขผู้เสียภาษี']); !!}
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->

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

                                <div class="col-lg-5">

                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">

                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    {!! Form::label('filter_tis_id', 'มอก.', ['class' => 'col-md-12 control-label']) !!}
                                                    {!! Form::select('filter_tis_id', App\Models\Elicense\Tis\RosStandardTisi::select(DB::Raw('CONCAT(tis_number," : ",tis_name) AS title, tis_number'))->orderBy('tis_number')->pluck('title', 'tis_number'), null, ['class' => 'form-control', 'placeholder'=>'- เลือกมอก. -', 'id' => 'filter_tis_id']) !!}
                                                </div>
                                                <div class="col-md-4 form-group ">
                                                    <div class=" {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                                                        {!! Form::label('filter_date_finish', 'วันที่ตรวจสอบเสร็จ'.' :', ['class' => 'col-md-12 control-label']) !!}
                                                        <div class="col-md-12">
                                                          <div class="input-daterange input-group" id="date-range">
                                                            {!! Form::text('filter_date_finish_start',null, ['class' => 'form-control mydatepicker', 'id' => 'filter_date_finish_start', 'placeholder'=>'วว/ดด/ปปปป']) !!}
                                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                            {!! Form::text('filter_date_finish_end', null, ['class' => 'form-control mydatepicker', 'id' => 'filter_date_finish_end', 'placeholder'=>'วว/ดด/ปปปป']) !!}
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 form-group ">
                                                    <div class=" {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                                                        {!! Form::label('filter_payment_date', 'วันที่ชำระค่าตรวจสอบ'.' :', ['class' => 'col-md-12 control-label']) !!}
                                                        <div class="col-md-12">
                                                          <div class="input-daterange input-group" id="date-range">
                                                            {!! Form::text('filter_payment_date_start',null, ['class' => 'form-control mydatepicker', 'id' => 'filter_payment_date_start', 'placeholder'=>'วว/ดด/ปปปป']) !!}
                                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                            {!! Form::text('filter_payment_date_end', null, ['class' => 'form-control mydatepicker', 'id' => 'filter_payment_date_end', 'placeholder'=>'วว/ดด/ปปปป']) !!}
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
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <h2> รายงานผลตรวจติดตาม IB</h2>
                                <p class="show_time">ข้อมูล ณ วันที่ {!!  HP::DateThai(date('Y-m-d')).' เวลา '.date('H.i').' น.' !!}  </p>
                            </center>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="table-responsive"> --}}
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th vertical-align="top" class="text-center" width="5%">#</th>
                                            <th vertical-align="top" class="text-center" width="10%">รหัสหน่วย<br>ตรวจสอบ</th>
                                            <th vertical-align="top" class="text-center" width="10%">มอก.</th>
                                            <th vertical-align="top" class="text-center" width="15%">ชื่อผู้ประกอบการ/<br>เลขผู้เสียภาษี</th>
                                            <th vertical-align="top" class="text-center" width="10%">เลขที่คำขอตรวจโรงงาน</th>
                                            <th vertical-align="top" class="text-center" width="10%">ค่าตรวจสอบ</th>
                                            <th vertical-align="top" class="text-center" width="10%">วันที่ชำระค่าตรวจสอบ</th>
                                            <th vertical-align="top" class="text-center" width="10%">วันที่ตรวจสอบเสร็จ</th>
                                            <th vertical-align="top" class="text-center" width="10%">ผลการตรวจสอบ</th>
                                            <th vertical-align="top" class="text-center" width="10%">ไฟล์ผลทดสอบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            {{-- </div> --}}

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

    <script>
        $(document).ready(function () {
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
                ajax: {
                    url: '{!! url('/report/test-factory/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_tis_id = $('#filter_tis_id').val();
                        d.filter_date_finish_start = $('#filter_date_finish_start').val();
                        d.filter_date_finish_end = $('#filter_date_finish_end').val();
                        d.filter_payment_date_start = $('#filter_payment_date_start').val();
                        d.filter_payment_date_end = $('#filter_payment_date_end').val();
                    }
                },
                columns: [
                    {'data': 'DT_Row_Index', name: 'DT_Row_Index' , orderable: true},
                    {'data': 'ib_code', name: 'ib_code', orderable: false},
                    {'data': 'tis_no', name: 'tis_no', orderable: false},
                    {'data': 'trader_name', name: 'trader_name', orderable: false},
                    {'data': 'factory_request_no', name: 'factory_request_no', orderable: false},
                    {'data': 'test_price', name: 'test_price', orderable: false},
                    {'data': 'payment_date', name: 'payment_date', orderable: false},
                    {'data': 'test_finish_date', name: 'test_finish_date', orderable: false},
                    {'data': 'test_result', name: 'test_result', orderable: false},
                    {'data': 'action', name: 'action', orderable: false}
                ],
                columnDefs: [
                    { className: "text-center", targets:[0,1,2,-1, -3, -4] },
                    { className: "text-right", targets:[5] },
                ],
                fnDrawCallback: function() {

                    $('#myTable_length').find('.totalrec').remove();
                    var el = '<label class="totalrec" style="color:green;margin-left:10px;">(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</label>';
                    $('#myTable_length').append(el);

                    ShowTime();
                }
            });

            $('#btn_search').click(function () {
                table.draw();
                ShowTime();
            });

            $('#btn_clean').click(function () {
                $('#filter_status,#filter_search').val('');
                table.draw();
                ShowTime();
            });

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

        });

        function ShowTime(){
            $.ajax({
                url: "{!! url('/funtions/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('.show_time').text(object);
                }
            });
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
@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

    <style>

    </style>
@endpush
@php
    $option_section =  App\Models\Law\Basic\LawSection::Where('state',1)->orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id');
@endphp
@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานสืบค้นประวัติการกระทำความผิด </h3>
                    <div class="clearfix"></div>

                    <hr class="m-t-0">
                    <div class="row box_filter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('filter_search', 'ค้นหาจาก'.':', ['class' => 'col-md-2 control-label text-right']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('filter_condition_search', [ '1'=>'เลขคดี', '2'=>'ผู้ประกอบการ/TAXID', '3'=>'เลขที่ใบอนุญาต', '4' => 'มอก.' ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="inputWithIcon">
                                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหา:']); !!}
                                                <i class="fa fa-search btn_search"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-info waves-effect waves-light" id="btn_search">ค้นหา</button>
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">ล้าง</button>
                                        <button type="button" class="btn btn-default btn-outline"  data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    {{-- {!! Form::select('filter_status', $option_status , null, ['class' => 'select2 select2-multiple', 'multiple'=>'multiple', 'id' => 'filter_status', 'data-placeholder'=>'-เลือกสถานะ-']); !!} --}}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">        

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_standard', 'มอก.', ['class' => 'col-md-12 control-label']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::text('filter_standard', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา มอก.-', 'id' => 'filter_standard']); !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_license_number', 'ใบอนุญาต', ['class' => 'col-md-12 control-label']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::text('filter_license_number', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา เลขที่ใบอนุญาต-', 'id' => 'filter_license_number']); !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_section', 'มาตราความผิด', ['class' => 'col-md-12 control-label']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_section', $option_section, null, ['class' => '',  'id' => 'filter_section', 'multiple'=>'multiple']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_date_publish', 'วันที่มอบหมาย'.':', ['class' => 'col-md-12 control-label ']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-daterange input-group" id="date-range">
                                                                {!! Form::text('filter_assign_start_date', null, ['class' => 'form-control','id'=>'filter_assign_start_date']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_assign_end_date', null, ['class' => 'form-control','id'=>'filter_assign_end_date']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_amount', 'ช่วงมูลค่าของกลาง'.':', ['class' => 'col-md-12 control-label ']) !!}
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                {!! Form::text('filter_amount_min', null, ['class' => 'form-control number_only','id'=>'filter_amount_min']) !!}
                                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                                {!! Form::text('filter_amount_max', null, ['class' => 'form-control number_only','id'=>'filter_amount_max']) !!}
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
                            <p class="h2 text-bold-500 text-center">รายงานสืบค้นประวัติการกระทำความผิด <span id="show_book_group"></span> <span id="show_book_type"></span> </p>
                            <p class="h4 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                @if(auth()->user()->can('export-'.str_slug('law-report-summary-law-offender-cases')))
                                    <button class="btn btn-success waves-effect waves-light m-l-5" type="button" name="btn_export" id="btn_export">Excel</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="clearfix"></div>

                            <div class="table-responsive-xl overflow-auto">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">เลขคดี</th>
                                            <th scope="col" class="text-center">ผู้ประกอบการ</th>
                                            <th scope="col" class="text-center">เลขที่ มอก.</th>
                                            <th scope="col" class="text-center">เลขที่ใบอนุญาต</th>
                                            <th scope="col" class="text-center">นิติกร</th>
                                            <th scope="col" class="text-center">กลุ่มงานแจ้งคดี</th>
                                            <th scope="col" class="text-center">มาตราความผิด</th>
                                            <th scope="col" class="text-center">จำนวนของกลาง</th>
                                            <th scope="col" class="text-center">มูลค่าของกลาง</th>
                                            <th scope="col" class="text-center">ค่าปรับ</th>
                                            <th scope="col" class="text-center">วันที่มอบหมาย</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </div>
            </div>
        </div>

    </div>

@endsection


@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            $(".number_only").on("keypress keyup blur",function (event) {
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });


            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                autoclose: true,
            });

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/report/summary-law-offender-cases/data_list') !!}',
                    data: function (d) {

                        d.filter_condition_search    = $('#filter_condition_search').val();
                        d.filter_search              = $('#filter_search').val();

                        d.filter_standard            = $('#filter_standard').val();
                        d.filter_license_number      = $('#filter_license_number').val();
                        d.filter_section             = (($('#filter_section').val() != '' || $('#filter_section').val() != 'null')?$('#filter_section').val():'');

                        d.filter_assign_start_date   = $('#filter_assign_start_date').val();
                        d.filter_assign_end_date     = $('#filter_assign_end_date').val();

                        d.filter_amount_min          = $('#filter_amount_min').val();
                        d.filter_amount_max          = $('#filter_amount_max').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'case_number', name: 'case_number' },
                    { data: 'owner_name', name: 'owner_name' },
                    { data: 'tis', name: 'tis' },
                    { data: 'license', name: 'license' },
                    { data: 'lawyer_name', name: 'lawyer_name' },
                    { data: 'department_name', name: 'department_name' },
                    { data: 'section', name: 'section' },
                    { data: 'total_product', name: 'total_product' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'penalty', name: 'penalty' },                   
                    { data: 'assign_at', name: 'assign_at' },
                ],
                columnDefs: [
                    { className: "text-top",    targets: "_all" },
                ],
                fnDrawCallback: function() {
                    ShowTime();

                    $("div#myTable_length").find('.totalrec').remove();
                    var el = '<label class="m-l-5 totalrec">(ข้อมูลทั้งหมด '+ Comma(table.page.info().recordsTotal)  +' รายการ)</label>';
                    $("div#myTable_length").append(el);
                }
            });

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('.box_filter').find('input').val('');
                $('.box_filter').find('select').val('').trigger('change.select2');
                $('.box_filter').find('#filter_standard,#filter_license_number').select2('val', "");
                $('.check').iCheck('check');
                table.draw();

            });

            $("#filter_standard").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-standards-td3') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $("#filter_license_number").select2({
                dropdownAutoWidth: true,
                width: '100%',
                ajax: {
                    url: "{{ url('/law/funtion/search-license-tb4') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params // search term
                        };
                    },
                    results: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true,
                },
                placeholder: 'คำค้นหา',
                minimumInputLength: 1,
            });

            $(document).on('click', '#btn_export', function(){
                export_excel();
            });
        });


        function ShowTime(){

            $.ajax({
                url: "{!! url('/law/funtion/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('#show_time').text(object);
                }
            });
        }

        function export_excel(){

            var url = 'law/report/summary-law-offender-cases/export_excel';
                url += '?filter_condition_search='    + $('#filter_condition_search').val();
                url += '&filter_search='              + $('#filter_search').val();
  
                url += '&filter_standard='            + $('#filter_standard').val();
                url += '&filter_license_number='      + $('#filter_license_number').val();
                url += '&filter_section='             + (($('#filter_section').val() != '' || $('#filter_section').val() != 'null')?$('#filter_section').val():'');

                url += '&filter_assign_start_date='   + $('#filter_assign_start_date').val();
                url += '&filter_assign_end_date='     + $('#filter_assign_end_date').val();

                url += '&filter_amount_min='          + $('#filter_amount_min').val();
                url += '&filter_amount_max='          + $('#filter_amount_max').val();

                // เลือก Column
                // url += '&column_select_row='            + $('#column_select_row:checked').val(); //ลำดับ
                // url += '&column_select_case_number='    + $('#column_select_case_number:checked').val(); //เลขคดี
                // url += '&column_select_owner_name='     + $('#column_select_owner_name:checked').val(); //ผู้ประกอบการ
                // url += '&column_select_tb3_tisno='      + $('#column_select_tb3_tisno:checked').val(); // เลขที่ มอก.
                // url += '&column_select_tb3_tis='        + $('#column_select_tb3_tis:checked').val(); // ชื่อ มอก.
                // url += '&column_select_license_number=' + $('#column_select_license_number:checked').val(); // เลขที่ใบอนุญาต
                // url += '&column_select_lawyer_by='      + $('#column_select_lawyer_by:checked').val(); // นิติกร
                // url += '&column_select_department='     + $('#column_select_department:checked').val(); // กลุ่มงานแจ้งคดี
                // url += '&column_select_section='        + $('#column_select_section:checked').val(); // มาตราความผิด
                // url += '&column_select_product='        + $('#column_select_product:checked').val(); //   จำนวนของกลาง
                // url += '&column_select_amount='         + $('#column_select_amount:checked').val(); //  มูลค่าของกลาง
                // url += '&column_select_penalty='        + $('#column_select_penalty:checked').val(); // ค่าปรับ
                // url += '&column_select_status='         + $('#column_select_status:checked').val(); //  สถานะคดี
                // url += '&column_select_assign_at='      + $('#column_select_assign_at:checked').val(); // วันที่มอบหมาย

                // url += '&column_select_created_at='     + $('#column_select_created_at:checked').val(); //  วันที่แจ้ง
                // url += '&column_select_law_arrest='     + $('#column_select_law_arrest:checked').val(); // การจับกุม
                // url += '&column_select_payment='        + $('#column_select_payment:checked').val(); //  สถานะค่าปรับ

            window.location = '{!! url("'+ url +'") !!}' ;

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
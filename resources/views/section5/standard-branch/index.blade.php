@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

    <style>

    </style>
@endpush
@php
    // $option_section =  App\Models\Law\Basic\LawSection::Where('state',1)->orderbyRaw('CONVERT(number USING tis620)')->pluck('number', 'id');
@endphp
@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานรายสาขาแยกกตามมาตรฐานมอก.</h3>
                    <div class="clearfix"></div>

                    <hr class="m-t-0">
                    <div class="row box_filter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('filter_search', 'ค้นหาจาก'.':', ['class' => 'col-md-2 control-label text-right']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('filter_condition_search', ['1'=>'มอก.','2'=>'หมวดสาขา/สาขา','3' => 'รายสาขา' ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="inputWithIcon">
                                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'กรอก']); !!}
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
                                    {!! Form::select('filter_branch_tis', [ 1 => 'มาตรฐานที่มีรายสาขา', 2 => 'มาตรฐานที่ไม่มีรายสาขา' ] , null, ['class' => 'form-control', 'id' => 'filter_branch_tis', 'placeholder'=>'-เลือกสถานะ-']); !!}
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
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch_group', 'สาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_branch_group', App\Models\Basic\BranchGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_branch_group', 'placeholder'=>'-เลือกสาขา-']); !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('filter_branch', 'รายสาขา', ['class' => 'col-md-12 label-filter']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_branch', App\Models\Basic\Branch::pluck('title', 'id'), null, ['class' => 'form-control', 'id'=> 'filter_branch', 'placeholder'=>'-เลือกรายสาขา-']); !!}
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
                            <p class="h2 text-bold-500 text-center">รายงานรายสาขาแยกกตามมาตรฐานมอก. <span id="show_book_group"></span> <span id="show_book_type"></span> </p>
                            <p class="h4 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                @if(auth()->user()->can('view-'.str_slug('report-standard-branch')))
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
                                            <th scope="col" class="text-center">เลขที่ มอก.</th>
                                            <th scope="col" class="text-center">ผลิตภัณฑ์ ฯ</th>
                                            <th scope="col" class="text-center">หมวดสาขา/สาขา</th>
                                            <th scope="col" class="text-center">รายสาขา</th>
                                            {{-- <th scope="col" class="text-center">เลขที่ใบอนุญาต</th>
                                            <th scope="col" class="text-center">นิติกร</th>
                                            <th scope="col" class="text-center">กลุ่มงานแจ้งคดี</th>
                                            <th scope="col" class="text-center">มาตราความผิด</th>
                                            <th scope="col" class="text-center">จำนวนของกลาง</th>
                                            <th scope="col" class="text-center">มูลค่าของกลาง</th>
                                            <th scope="col" class="text-center">ค่าปรับ</th>
                                            <th scope="col" class="text-center">วันที่มอบหมาย</th> --}}
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
                    url: '{!! url('/section5/report-standard-branch/data_list') !!}',
                    data: function (d) {

                        d.filter_condition_search    = $('#filter_condition_search').val();
                        d.filter_search              = $('#filter_search').val();

                        d.filter_branch_tis          = $('#filter_branch_tis').val();

                        d.filter_standard            = $('#filter_standard').val();
                        d.filter_branch_group        = $('#filter_branch_group').val();
                        d.filter_branch              = $('#filter_branch').val();

                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tis_no', name: 'tis_no' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'branch_group_name', name: 'branch_group_name' },
                    { data: 'branch_name', name: 'branch_name' },
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
                $('.box_filter').find('#filter_standard').select2('val', "");
                table.draw();

            });

            $('#filter_branch_group').change(function (e) {

                $('#filter_branch').html('<option value=""> -เลือกรายสาขา- </option>');
                var value = ( $(this).val() != "" )?$(this).val():'ALL';
                if(value){
                    $.ajax({
                        url: "{!! url('/section5/get-branch-data') !!}" + "/" + value
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#filter_branch').append('<option value="'+data.id+'">'+data.title+'</option>');
                        });
                    });
                }
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

            var url = 'section5/report-standard-branch/export_excel';
                url += '?filter_condition_search='    + $('#filter_condition_search').val();
                url += '&filter_search='              + $('#filter_search').val();
                url += '&filter_standard='            + $('#filter_standard').val();
                url += '&filter_branch_group='        + $('#filter_branch_group').val();
                url += '&filter_branch='              + $('#filter_branch').val();
                url += '&filter_branch_tis='          + $('#filter_branch_tis').val();

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
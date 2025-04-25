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

                    <h3 class="box-title pull-left">รายงานสรุปข้อมูลห้องสมุด </h3>
                    <div class="clearfix"></div>

                    <hr class="m-t-0">
                    <div class="row box_filter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('filter_search', 'ค้นหาจาก'.':', ['class' => 'col-md-2 control-label text-right']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('filter_condition_search', ['1' => 'ชื่อเรื่อง', '2' => 'ประเภท', '3' => 'หมวดหมู่' ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
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
                                    {!! Form::select('filter_status', [ 1=> 'เผยแพร่', 2=> 'ไม่เผยแพร่' ] , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">        

                                            <div class="row">

                                                <div class="form-group col-md-4">
                                                    {!! Form::label('filter_book_group', 'หมวดหมู่', ['class' => 'col-md-12 control-label']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::select('filter_book_group', App\Models\Law\Basic\LawBookGroup::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control', 'id' => 'filter_book_group', 'placeholder'=>'-เลือกหมวดหมู่-']); !!}
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    {!! Form::label('filter_book_type', 'ประเภท', ['class' => 'col-md-12 control-label']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::select('filter_book_type', App\Models\Law\Basic\LawBookType::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), null, ['class' => 'form-control', 'id' => 'filter_book_type', 'placeholder'=>'-เลือกประเภท-']); !!}
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    {!! Form::label('filter_date_publish', 'วันที่เผ่ยแพร่'.':', ['class' => 'col-md-12 control-label ']) !!}
                                                    <div class="col-md-12">
                                                        <div class="input-daterange input-group" id="date-range">
                                                            {!! Form::text('filter_publish_start_date', null, ['class' => 'form-control','id'=>'filter_publish_start_date']) !!}
                                                            <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                            {!! Form::text('filter_publish_end_date', null, ['class' => 'form-control','id'=>'filter_publish_end_date']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
            
                                            <div class="row">
                                                <div class="col-md-12 m-t-10">
                                                    <center>
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-info waves-effect waves-light" id="btn_search">ค้นหา</button>
                                                            <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean"> ล้าง </button>
                                                        </div>
                                                    </center>
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
                            <p class="h2 text-bold-500 text-center">รายงานสรุปข้อมูลห้องสมุด <span id="show_book_group"></span> <span id="show_book_type"></span> </p>
                            <p class="h4 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                @if(auth()->user()->can('export-'.str_slug('law-report-book-list')))
                                    <button class="btn btn-success waves-effect waves-light m-l-5" type="button" name="btn_export" id="btn_export">Excel</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%" class="text-center">#</th>
                                        <th width="13%" class="text-center">หมวดหมู่</th>
                                        <th width="13%" class="text-center">ประเภท</th>
                                        <th width="22%" class="text-center">ชื่อเรื่อง</th>
                                        <th width="10%" class="text-center">เผยแพร่เมื่อ</th>
                                        <th width="10%" class="text-center">เข้าชม</th>
                                        <th width="10%" class="text-center">ดาวน์โหลด</th>
                                        <th width="10%" class="text-center">สิทธิ์การเข้าถึง</th>
                                        <th width="10%" class="text-center">สถานะ</th>
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

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

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
                    url: '{!! url('/law/report/book-list/data_list') !!}',
                    data: function (d) {

                        d.filter_condition_search   = $('#filter_condition_search').val();
                        d.filter_search             = $('#filter_search').val();
                        d.filter_status             = $('#filter_status').val();
                        d.filter_book_group         = $('#filter_book_group').val();
                        d.filter_book_type          = $('#filter_book_type').val();
                        d.filter_publish_start_date = $('#filter_publish_start_date').val();
                        d.filter_publish_end_date   = $('#filter_publish_end_date').val();

                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'book_group', name: 'book_group' },
                    { data: 'book_type', name: 'book_type' },
                    { data: 'title', name: 'title' },
                    { data: 'date_publish', name: 'date_publish' },
                    { data: 'manage_visit_view', name: 'manage_visit_view' },
                    { data: 'manage_visit_download', name: 'manage_visit_download' },
                    { data: 'manage_access', name: 'manage_access' },
                    { data: 'status', name: 'status' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0, -1, -2, -3, -4] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {
                    ShowTime();
                }
            });

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('.box_filter').find('input').val('');
                $('.box_filter').find('select').val('').trigger('change.select2');
                table.draw();
                ShowBookType();
                ShowBookGroup();

            });

            $(document).on('click', '#btn_export', function(){

                var url = 'law/report/book-list/export_excel';
                    url += '?filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_search=' + $('#filter_search').val();
                    url += '&filter_status=' + $('#filter_status').val();

                    url += '&filter_book_group=' + $('#filter_book_group').val();
                    url += '&filter_book_type=' + $('#filter_book_type').val();
                    url += '&filter_publish_start_date=' + $('#filter_publish_start_date').val();
                    url += '&filter_publish_end_date=' + $('#filter_publish_end_date').val();

                    if(  $('#filter_book_group').val() != ''){
                        var book_group = $('#filter_book_group').find('option:selected').text();
                        url += '&book_group=' + book_group;
                    }

                    if(  $('#filter_book_group').val() != ''){
                        var book_type = $('#filter_book_type').find('option:selected').text();
                        url += '&book_type=' + book_type;
                    }
                window.location = '{!! url("'+url +'") !!}';
            });

            $('#filter_book_type').change(function (e) { 
                ShowBookType();
            });
            ShowBookType();

            $('#filter_book_group').change(function (e) { 
                ShowBookGroup();
            });
            ShowBookGroup();
        });


        function ShowBookType(){
            var txt = '';
            if(  $('#filter_book_type').val() != ''  ){
                var book = $('#filter_book_type').find('option:selected').text();
                txt = 'ประเภท '+book;
            }

            $('#show_book_type').text(txt);
        }

        function ShowBookGroup(){
            var txt = '';
            if(  $('#filter_book_group').val() != ''  ){
                var book = $('#filter_book_group').find('option:selected').text();
                txt = 'หมวดหมู่ '+book;
            }

            $('#show_book_group').text(txt);
        } 

        function ShowTime(){

            $.ajax({
                url: "{!! url('/law/funtion/get-time-now') !!}"
            }).done(function( object ) {
                if(object != ''){
                    $('#show_time').text(object);
                }
            });
        }

    </script>

@endpush
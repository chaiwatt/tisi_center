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

                    <h3 class="box-title pull-left">รายงานประวัติการส่งเมลประกาศร่างกฎกระทรวง </h3>
                    <div class="clearfix"></div>

                    <hr class="m-t-0">
                    <div class="row box_filter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::label('filter_search', 'ค้นหา'.':', ['class' => 'col-md-3 control-label text-right']) !!}
                                        <div class="col-md-9">
                                            <div class="inputWithIcon">
                                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'กรอกอีเมล']); !!}
                                                <i class="fa fa-search btn_search"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::label('filter_date_created_at', 'วันที่'.':', ['class' => 'col-md-2 control-label ']) !!}
                                        <div class="col-md-10">
                                            <div class="input-daterange input-group" id="date-range">
                                                {!! Form::text('filter_created_at_start', null, ['class' => 'form-control','id'=>'filter_created_at_start']) !!}
                                                <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                {!! Form::text('filter_created_at_end', null, ['class' => 'form-control','id'=>'filter_created_at_end']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-info waves-effect waves-light" id="btn_search">ค้นหา</button>
                                        <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">ล้าง</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="h2 text-bold-500 text-center">รายงานประวัติการส่งเมลประกาศร่างกฎกระทรวง</p>
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
                                        <th width="40%" class="text-center">ชื่อ</th>
                                        <th width="30%" class="text-center">อีเมล</th>
                                        <th width="28%" class="text-center">วันเวลา</th>
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
                    url: '{!! url('/law/report/listen/ministry/mail/data_list') !!}',
                    data: function (d) {

                        d.filter_search             = $('#filter_search').val();
                        d.filter_created_at_start   = $('#filter_created_at_start').val();
                        d.filter_created_at_end     = $('#filter_created_at_end').val();

                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0, -1] },
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

                var url = 'law/report/listen/ministry/mail/export_excel';
                    url += '?filter_search=' + $('#filter_search').val();
                    url += '&filter_publish_start_date=' + $('#filter_publish_start_date').val();
                    url += '&filter_publish_end_date=' + $('#filter_publish_end_date').val();

                window.location = '{!! url("'+url +'") !!}';
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

    </script>

@endpush
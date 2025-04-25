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

                    <h3 class="box-title pull-left">รายงานผู้มีส่วนได้ส่วนเสีย </h3>
                    <div class="clearfix"></div>

                    <hr class="m-t-0">
                    <div class="row box_filter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {!! Form::label('filter_search', 'ค้นหาจาก'.':', ['class' => 'col-md-2 control-label text-right']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('filter_condition_search', ['1' => 'หน่วยงาน/ผู้ได้รับใบอนุญาต', '2' => 'ที่อยู่', '3' => 'มอก.' ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
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
                                    {!! Form::select('filter_status', [ 1=> 'หน่วยงานอื่นๆ', 2=> 'ผู้ได้รับใบอนุญาต' ] , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-ประเภทข้อมูลทั้งหมด-']); !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">        

                                        </div>
                                    </div>
                                </div>
                            </div>
                   
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="h2 text-bold-500 text-center">รายงานผู้มีส่วนได้ส่วนเสีย</p>
                            <p class="h4 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                @if(auth()->user()->can('export-'.str_slug('law-report-department-stakeholder')))
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
                                        <th width="13%" class="text-center">ประเภทข้อมูล</th>
                                        <th width="13%" class="text-center">ชื่อหน่วยงาน/<br>ชื่อผู้ได้รับใบอนุญาต</th>
                                        <th width="22%" class="text-center">ที่อยู่</th>
                                        <th width="10%" class="text-center">เบอร์โทร/<br>เบอร์เฟกต์</th>
                                        <th width="10%" class="text-center">อีเมล</th>
                                        <th width="10%" class="text-center">มอก.ที่เกี่ยวข้อง</th>
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

            $('#myTablfe').DataTable(
                {
                    pageLength: 10,
                    paging: true,
                    searching: true,
                }
            );

            table = $('#myTable').DataTable({
                processing: false,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/report/department-stakeholder/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_created_at = $('#filter_created_at').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'type', name: 'type' },
                    { data: 'title', name: 'title' },
                    { data: 'address_no', name: 'address_no' },
                    { data: 'tel', name: 'tel' },
                    { data: 'email', name: 'email' },
                    { data: 'tis_id', name: 'tis_id' }
                ],
                columnDefs: [
                    

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
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

                var url = 'law/report/department-stakeholder/export_excel';
                    url += '?filter_condition_search=' + $('#filter_condition_search').val();
                    url += '&filter_search=' + $('#filter_search').val();
                    url += '&filter_status=' + $('#filter_status').val();

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
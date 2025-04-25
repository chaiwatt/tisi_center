@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท</h3>

                    <div class="pull-right">

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก กลุ่มบทบาท/ชื่อเจ้าหน้าที่ หรือ ชื่อผู้ประกอบการ/เลขผู้เสียภาษี']); !!}
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                            <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light m-l-5" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                        <button type="button" class="btn btn-warning waves-effect waves-light m-l-5" id="btn_clean">
                                            ล้าง
                                        </button>
                                        <button class="btn btn-success waves-effect waves-light m-l-5" type="button" name="btn_export" id="btn_export">Excel</button>

                                    </div>
                                </div><!-- /.col-lg-1 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">

                                            <div class="row">

         

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
                            <p class="h2 text-bold-600 text-center">รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท (Elicense)</p>
                            <p class="h4 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="6%" class="text-center">No.</th>
                                        <th width="50%" class="text-center">กลุ่มบทบาท</th>
                                        <th width="22%" class="text-center">จำนวนระบบที่ใช้งาน</th>
                                        <th width="22%" class="text-center">จำนวนผู้ใช้งาน</th>
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
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {
            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
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
                    url: '{!! url('/report/elicense-roles/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();

                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name', name: 'name' },
                    { data: 'sytems', name: 'sytems' },
                    { data: 'users', name: 'users' },
                ],
                columnDefs: [
                    { className: "text-top text-right", targets:[-1,-2] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {
                    ShowTime();
                },
                initComplete:function( settings, json ) {

                }
            });

            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_status,#filter_search').val('');
                $('#search-btn').find('select').val('').select2();
                $('#search-btn').find('input').val('');
                $('#filter_status').val('').select2();
                table.draw();
            });

            $('#btn_export').click(function (e) {

                var url = 'report/elicense-roles/export';
                    url += '?filter_search=' + $('#filter_search').val();

                window.location = '{!! url("'+ url +'") !!}';

            });

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
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

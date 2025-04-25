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
                <h3 class="box-title pull-left">สืบค้นข้อมูลใบอนุญาต</h3>
                <div class="clearfix"></div>
                <hr>
                <div id="BoxSearching">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <div class="form-group col-md-5">
                                {!! Form::select('filter_condition_search', array('1' => 'เลขที่ใบอนุญาต', '2' => 'เลขมอก', '3' => 'ชือผู้ประกอบการ', '4' => 'เลขประจำตัวผู้เสียภาษี'), null, ['class' => 'form-control ', 'placeholder'=>'-ค้นหาจาก-', 'id'=>'filter_condition_search']); !!}
                            </div>
                            <div class="col-md-7">
                                <div class="inputWithIcon">
                                    {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขมาตรา, คำอธิบายมาตรา']); !!}
                                    <i class="fa fa-search btn_search"></i>
                                </div>
                            </div>
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
                                <button type="button" class="btn btn-default waves-effect waves-light" id="btn_clean">
                                    ล้าง
                                </button>
                            </div>
                        </div>
              
                    </div>

                    <div id="search-btn" class="panel-collapse collapse">
                        <div class="white-box" style="display: flex; flex-direction: column;">

                        <div class="row">
                            <div class="form-group col-md-4">
                                {!! Form::label('filter_type', 'ประเภทใบอนุญาต', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                <div class="col-md-12">
                                {!! Form::select('filter_license_type', [ 'ท'=> 'ทำ','ส'=> 'แสดง','น'=> 'นำเข้า','นค'=> 'นำเข้าเฉพาะครั้ง',], null, ['class' => 'form-control ', 'placeholder'=>'-เลือกประเภทใบอนุญาต-','id'=>'filter_license_type']); !!}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('filter_license_date', 'วันที่ออกใบอนุญาต', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                <div class="col-md-12">
                                    <div class="inputWithIcon">
                                        {!! Form::text('filter_license_date', null, ['class' => 'form-control mydatepicker ', 'id' => 'filter_license_date','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off'] ) !!}
                                            <i class="icon-calender"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('filter_tisi_no', 'เลข มอก.', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                <div class="col-md-12">
                                    {!! Form::select('filter_tisi_no', App\Models\Basic\Tis::select(DB::Raw('CONCAT(tb3_Tisno," : ",tb3_TisThainame) AS title, tb3_Tisno'))->pluck('title', 'tb3_Tisno'), null, ['class' => 'form-control ', 'placeholder'=>'- เลือก มอก. -','id'=>'filter_tisi_no']) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('filter_status', 'สถานะใช้งาน', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                <div class="col-md-12">
                                    {!! Form::select('filter_status', [ 1=> 'ใช้งาน', 2=> 'ไม่ใช้งาน' ], null, ['class' => 'form-control ', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะใช้งาน-']); !!}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('filter_is_pause', 'ใบอนุญาตพักใช้', ['class' => 'col-md-12 control-label label-filter ']) !!}
                                <div class="col-md-12">
                                    {!! Form::select('filter_is_pause', [ 1=> 'พักใช้', 2=> 'ไม่พักใช้' ], null, ['class' => 'form-control ', 'id' => 'filter_is_pause', 'placeholder'=>'-เลือกสถานะใบอนุญาต-']); !!}
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
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="15%">เลขที่ใบอนุญาต</th>
                                        <th class="text-center" width="10%">ประเภทใบอนุญาต</th>
                                        <th class="text-center" width="10%">วันที่ออก<br>ใบอนุญาต</th>
                                        <th class="text-center" width="25%">เลข มอก.</th>
                                        <th class="text-center" width="15%">ชื่อผู้ประกอบการ/<br>เลขประจำตัวผู้เสียภาษี</th>
                                        <th class="text-center" width="10%">ไฟล์ใบอนุญาต</th>
                                        <th class="text-center" width="10%">สถานะ</th>
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

    @include('laws.search.license-report.modals.histiry')

@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            $(document).on('click','.btn_modal_history', function () {

                $('div.box_history').html("");

                var id = $(this).data('id');
                if( id != ''  ){

                    $.LoadingOverlay("show", {
                        image       : "",
                        fontawesome : "fa fa-circle-o-notch fa-spin",
                    });

                    $.ajax({
                        url: "{!! url('/law/search/license-report/history') !!}" + "?id=" + id
                    }).done(function( msg ) {
                        $('div.box_history').html(msg);
                        $.LoadingOverlay("hide");
                    });

                    $('#HistoryModals').modal('show');
                }
                
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
                    url: '{!! url('/law/search/license-report/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search           = $('#filter_search').val();
                        d.filter_is_pause         = $('#filter_is_pause').val();
                        d.filter_status           = $('#filter_status').val();
                        d.filter_license_type     = $('#filter_license_type').val();
                        d.filter_tisi_no          = $('#filter_tisi_no').val();
                        d.filter_license_date     = $('#filter_license_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'tbl_licenseNo', name: 'tbl_licenseNo' },
                    { data: 'tbl_licenseType', name: 'tbl_licenseType' },
                    { data: 'tbl_licenseDate', name: 'tbl_licenseDate' },
                    { data: 'tbl_tisiNo', name: 'tbl_tisiNo' },
                    { data: 'tbl_tradeName', name: 'tbl_tradeName' },
                    { data: 'tbl_pdf_path', name: 'tbl_pdf_path' },
                    { data: 'tbl_licenseStatus', name: 'tbl_licenseStatus' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-2] },
                    { className: "text-top", targets: "_all" }

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
                $('#BoxSearching').find('input, select').val('').change();
                table.draw();
            });


        });

    </script>

@endpush

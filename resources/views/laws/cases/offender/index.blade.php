@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
            opacity: 1;
        }
    </style>
@endpush

@php
    $filter_search = !empty( Request::get('filter_search') )?Request::get('filter_search'):null;
@endphp

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">สืบค้นประวัติการกระทำความผิด</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-cases-offender'))
                            <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/cases/offender/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr class="m-t-0">

                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="col-md-4">
                                        {!! Form::select('filter_condition_search', ['1'=>'ผู้ประกอบการ','2'=>'เลขประตัวผู้เสียภาษี','3' => 'เลขที่ใบอนุญาต' ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
                                    </div>
                                    <div class="col-md-8">
                                        <div class="inputWithIcon">
                                            {!! Form::text('filter_search', $filter_search, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'กรอก']); !!}
                                            <i class="fa fa-search btn_search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
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
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    {!! Form::text('filter_standard', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา มอก.-', 'id' => 'filter_standard']); !!}
                                                </div>
                                            </div>
                                        </div><!-- /.col-lg-5 -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    {!! Form::text('filter_license_number', null, ['class' => 'form-control', 'placeholder'=>'-ค้นหา เลขที่ใบอนุญาต-', 'id' => 'filter_license_number']); !!}
                                                </div>
                                            </div>
                                        </div><!-- /.col-lg-5 -->
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12"> 
                            <p class="h5 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="19%">ผู้ประกอบการ</th>
                                        <th class="text-center" width="12%">เลขประตัวผู้เสียภาษี</th>
                                        <th class="text-center" width="20%">ที่ตั้งสำนักงานใหญ่</th>
                                        <th class="text-center" width="17%">อีเมล/เบอร์</th>
                                        <th class="text-center" width="10%">ใบอนุญาตที่ได้รับ</th>
                                        <th class="text-center" width="10%">มีความผิด</th>
                                        <th class="text-center" width="10%">รายละเอียด</th>

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
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        var table = '';
        $(document).ready(function () {

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });


            @if(\Session::has('flash_message'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{session()->get('flash_message')}}',
                showConfirmButton: false,
                timer: 1500
                });
            @endif

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,

                ajax: {
                    url: '{!! url('/law/cases/offender/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search           = $('#filter_search').val();
                        d.filter_standard         = $('#filter_standard').val();
                        d.filter_license_number   = $('#filter_license_number').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'offender_name', name: 'offender_name' },
                    { data: 'offender_taxid', name: 'offender_taxid' },
                    { data: 'offender_address', name: 'offender_address' },
                    { data: 'offender_email', name: 'offender_email' },
                    { data: 'offender_certify', name: 'offender_certify' },
                    { data: 'offender_total', name: 'offender_total' },
                    { data: 'action', name: 'action' }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0, 2, -1, -2, -3] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    ShowTime();

                    $("div#myTable_length").find('.totalrec').remove();
                    var el = '<label class="m-l-5 totalrec">(ข้อมูลทั้งหมด '+ Comma(table.page.info().recordsTotal)  +' รายการ)</label>';
                    $("div#myTable_length").append(el);
                }
            });


            //เลือกทั้งหมด
            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

          
            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });
            

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input').val('').change();
                $('#BoxSearching').find('select').select2('val','');
                $('#BoxSearching').find('#filter_standard,#filter_license_number').select2('val', "");
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

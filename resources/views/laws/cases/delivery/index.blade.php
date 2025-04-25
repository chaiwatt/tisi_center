@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <style>
        table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
            opacity: 1;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

          
                    <h3 class="box-title pull-left">บันทึกจัดส่งหนังสือ</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-cases-delivery'))
                            <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/cases/delivery/create') }}">
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
                                        {!! Form::select('filter_condition_search', ['1'=>'เลขคดี','2'=>'ผู้ประกอบการ','3' => 'เลขประตัวผู้เสียภาษี', '4' => 'เรื่อง' ],null,['class' => 'form-control','placeholder' => '- ค้นหาทั้งหมด -','id' => 'filter_condition_search']) !!}
                                    </div>
                                    <div class="col-md-8">
                                        <div class="inputWithIcon">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'กรอก']); !!}
                                            <i class="fa fa-search btn_search"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-2">
                                    {!! Form::select('filter_status', [ 1 => 'ส่งแล้ว'  , 2 => 'ตอบกลับ' ] , null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                </div>
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('filter_send_type', 'ประเภท', ['class' => 'col-md-12 label-filter']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::select('filter_send_type', App\Models\Law\Basic\LawDelivery::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id') , null, ['class' => 'form-control', 'id'=> 'filter_send_type', 'placeholder'=>'- เลือกประเภท -']); !!}
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
                            <p class="h5 text-bold-400 text-center" id="show_time">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="10%">เลขคดี</th>
                                        <th class="text-center" width="17%">ผู้ประกอบการ<br>เลขประตัวผู้เสียภาษี</th>
                                        <th class="text-center" width="13%">ประเภท</th>
                                        <th class="text-center" width="13%">เรื่อง</th>
                                        <th class="text-center" width="10%">วันครบกำหนด</th>
                                        <th class="text-center" width="10%">สถานะ</th>
                                        <th class="text-center" width="10%">ผู้บันทึก/วันที่บันทึก</th>
                                        <th class="text-center" width="15%">จัดการ</th>
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
                    url: '{!! url('/law/cases/delivery/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_search           = $('#filter_search').val();
                        d.filter_send_type        = $('#filter_send_type').val();
                        d.filter_status           = $('#filter_status').val();
                    } 
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'law_case_no', name: 'law_case_no' },
                    { data: 'law_case_name', name: 'law_case_name' },
                    { data: 'law_send_type', name: 'law_send_type' },
                    { data: 'law_title', name: 'law_title' },
                    { data: 'law_date_due', name: 'law_date_due' },
                    { data: 'law_state', name: 'law_state' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'action', name: 'action', searchable: false, orderable: false }
                ],  
                columnDefs: [
                    { className: "text-center text-top", targets:[0, 1, -1, -2, -3] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    ShowTime();
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

                table.draw();
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

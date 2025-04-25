@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
          
                    <h3 class="box-title pull-left">จัดทำแบบรับฟังความเห็นฯ</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('law-listen-ministry'))
                            <a class="btn btn-success waves-effect waves-light" href="{{ url('/law/listen/ministry/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="BoxSearching">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                    <div class="form-group col-md-4">
                                        {!! Form::select('filter_condition_search', array('1' => 'เลขที่อ้างอิง', '2' => 'ชื่อเรื่องประกาศ', '3' => 'มาตรฐาน (มอก.)'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                    </div>
                                    <div class="col-md-6">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search']); !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                <div class="form-group col-md-3">
                                        {!! Form::select('filter_status', App\Models\Law\Listen\LawListenMinistry::list_status(), null, ['class' => 'form-control  text-center', 'id' => 'filter_status', 'placeholder'=>'-สถานะทั้งหมด-']); !!}
                                </div>
                            </div>

                            <div id="search-btn" class="panel-collapse collapse">
                                <div class="white-box" style="display: flex; flex-direction: column;">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_standard', 'เลขที่ มอก. / ชื่อ มอก. ', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                            {!! Form::select('filter_standard',App\Models\Law\Listen\LawListenMinistry::selectRaw('CONCAT(tis_no," : ",tis_name) As tis_title, id')->pluck('tis_title', 'id'), null, ['class' => 'form-control  text-center', 'id' => 'filter_standard', 'placeholder'=>'-เลือก มอก.-']); !!}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            {!! Form::label('filter_access', 'วันที่ประกาศ', ['class' => 'col-md-12 control-label']) !!}
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('filter_start_date') ? 'has-error' : ''}}">
                                                    <div class="input-daterange input-group date-range">
                                                        {!! Form::text('filter_start_date', null, ['id' => 'filter_start_date','class' => 'form-control date', 'required' => true]) !!}
                                                        <span class="input-group-addon bg-info b-0 text-white"> ถึง </span>
                                                        {!! Form::text('filter_end_date', null, ['id' => 'filter_end_date','class' => 'form-control date', 'required' => true]) !!}
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
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="13%">เลขที่อ้างอิง</th>
                                        <th class="text-center" width="20%">ชื่อเรื่องประกาศ</th>
                                        <th class="text-center" width="15%">มาตรฐาน (มอก.)</th>
                                        <th class="text-center" width="15%">สถานะ</th>
                                        <th class="text-center" width="15%">เปิดรับฟัง<br>ความคิดเห็น</th>
                                        <th class="text-center" width="12%">ผู้บันทึก<br>วันที่บันทึก</th>
                                        <th class="text-center" width="13%">จัดการ</th>
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
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

           //ปฎิทิน
            $('.date-range').datepicker({
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

                  @if (\Session::has('flash_message') == 'บันทึกข้อมูลสำเร็จ')
                             $.ajax({
                                method: "get",
                                url: "{{ url('api/v1/mail_listen_ministry') }}",
                            }).success(function (msg) {
                                 
                            });
                  @endif
            @endif

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/listen/ministry/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_standard = $('#filter_standard').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'title', name: 'title' },
                    { data: 'tis_name', name: 'tis_name' },
                    { data: 'status', name: 'status' },
                    { data: 'state', name: 'state' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1,-3] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' , color: '#00BFFF'});
                    });
                }
            });


            $(document).on('change', '.js-switch', function(e) {

                var id = $(this).val();
                var state = $(this).is(":checked")?1:0;

                if( state == '1'){
                    var text_alert = 'เปิดรับฟังความเห็น';
                }else if( state == '0'){
                    var text_alert = 'ปิดรับฟังความเห็น';
                }

                if (confirm("ยืนยันการ"+text_alert+ "ข้อมูลแถว นี้ ?")) {

                    var ids = [];
                        ids.push(id);
                    $.ajax({
                        method: "put",
                        url: "{{ url('law/listen/ministry/update-state') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id_publish": ids,
                            "state": state
                        }
                    }).success(function (msg) {
                        if (msg == "success") {
                            if(state == 1){
                                toastr.success('เปิดรับฟังความเห็น !');
                            }else{
                                toastr.error('ปิดรับฟังความเห็น !');
                            }
                            table.draw();
                        }
                    });

                }


            });

            $('#btn_search,.btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#BoxSearching').find('input, select').val('').change();
                $('#filter_standard').select2('val','');
                table.draw();
            });

        });


    </script>

@endpush

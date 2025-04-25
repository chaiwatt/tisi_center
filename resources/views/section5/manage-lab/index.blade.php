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


                    <h3 class="box-title pull-left">รายชื่อหน่วยตรวจสอบ (LAB)</h3>

                    <div class="pull-right">

                        @can('add-'.str_slug('manage-lab'))
                            <a class="btn btn-success waves-effect waves-light" type="button" href="{!! url('/section5/labs/create') !!}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มหน่วยตรวจสอบ (LAB)</b>
                            </a>
                        @endcan

                    </div>
                    <div class="pull-right" style="margin-right: 7px">

                        @if ($labs->count() > 0)
                            <button class="fcbtn btn btn-info btn-outline btn-1e pull-right" data-toggle="modal" data-target="#Lab-Modal">
                                LAB หมดอายุรับรอง 
                            </button>
                        @endif

                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <p class="text-muted m-b-30 font-13"><em>ระบบรายชื่อหน่วยตรวจสอบ (LAB)</em></p>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก หน่วยงาน/เลขนิติบุคคล']); !!}
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#search-btn" data-toggle="collapse" id="search_btn_all">
                                                <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                            </button>
                                        </div>
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                        </div>   
                                        <div class="btn-group mr-2" role="group">
                                            <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">ล้าง</button>
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::select('filter_status', [ 1=> 'Active', 2=> 'Not Active' ], null, ['class' => 'form-control', 'id' => 'filter_status',  'placeholder'=>'-เลือกสถานะ-']); !!}
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->



                            <div class="row">
                                <div class="col-md-12">
                                    <div id="search-btn" class="panel-collapse collapse">
                                        <div class="white-box" style="display: flex; flex-direction: column;">
                                            <div class="row">

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            {!! Form::text('filter_tis_id', null, ['class' => 'form-control', 'id'=> 'filter_tis_id', 'placeholder'=>'-เลือกมอก-']); !!}
                                                        </div>
                                                    </div>
                                                </div><!-- /.col-lg-5 -->

                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            {!! Form::select('filter_expired', [ 1=> 'LAB หมดอายุการรับรอง', 2=> 'LAB ที่ไม่มีวันหมดอายุรับรอง' ], null, ['class' => 'form-control', 'id' => 'filter_expired',  'placeholder'=>'-เลือกสถานะหมดอายุ-']); !!}
                                                        </div>
                                                    </div>
                                                </div><!-- /.col-lg-5 -->

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
                                        <th width="2%" class="text-center">No.</th>
                                        <th width="10%" class="text-center">รหัส</th>
                                        <th width="" class="text-center">ห้องปฏิบัติการ</th>
                                        <th width="12%" class="text-center">เลขนิติบุคคล</th>
                                        <th width="17%" class="text-center">มอก.ที่ตรวจสอบได้</th>
                                        <th width="12%" class="text-center">วันที่เป็นหน่วยตรวจสอบ</th>
                                        <th width="12%" class="text-center">วันที่สิ้นสุดเป็นหน่วยตรวจสอบ</th>
                                        <th width="5%" class="text-center">สถานะ</th>
                                        <th width="5%" class="text-center">จัดการ</th>
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

    @if ($labs->count() > 0)
    {{-- Modal Lab --}}
    <div class="modal fade" id="Lab-Modal" tabindex="-1" role="dialog" aria-labelledby="Lab-ModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="Lab-ModalLabel">แจ้งเตือน รายชื่อ LAB หมดอายุ</h4> 
                </div>
                <div class="modal-body" style="max-height: calc(100vh - 225px); overflow-y: auto;">
                    <h4>รายชื่อ LAB หมดอายุ จำนวน {{ $labs->count() }} ราย</h4>

                    <table class="font-14" width="100%">
                        @foreach ($labs as $lab)
                            <tr>
                                <td width="10%">{{ $lab->lab_code }}</td>
                                <td width="70%">{{ $lab->lab_name }}</td>
                                <td width="15%">{{ HP::DateThai($lab->lab_end_date) }}</td>
                                <td width="5%"><a href="{{ url('section5/labs/'.$lab->id) }}?tab_active=1" class="fcbtn btn btn-link">ดู</a></td>
                            </tr>
                        @endforeach
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script>
        $(document).ready(function () {

            @if($labs->count() > 0)
                $('#Lab-Modal').modal('show');
            @endif

            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
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
                    url: '{!! url('/section5/labs/data_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_tis_id = $('#filter_tis_id').val();
                        d.filter_expired = $('#filter_expired').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'lab_code', name: 'lab_code' },
                    { data: 'lab_name', name: 'lab_name' },
                    { data: 'taxid', name: 'taxid' },
                    { data: 'standards', name: 'standards' },
                    { data: 'lab_start_date', name: 'lab_start_date' },
                    { data: 'lab_end_date', name: 'lab_end_date' },
                    { data: 'state', name: 'state' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1,-2,-3] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });

            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_search').val(''); 
                $('#filter_status').val('').select2();
                $("#filter_tis_id").select2("val", "");
                $('#filter_expired').val('').select2();
                table.draw();
            });

            $("#filter_tis_id").select2({
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

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
@endpush

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

                <h3 class="box-title pull-left">รายงานประวัติการดำเนินงาน</h3>

                <hr class="hr-line">
                <div class="row">
                    <div class="col-md-12" id="BoxSearching">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                {!! Form::label('filter_condition_search', 'ค้นหาจาก', ['class' => 'col-md-2 control-label text-right']) !!}
                                <div class="form-group col-md-4">
                                    {!! Form::select('filter_condition_search', array('1' => 'ระบบงานหลัก', '2' => 'ระบบงานย่อย', '3' => 'เลขที่อ้างอิง', '4' => 'สถานะ'), null, ['class' => 'form-control ', 'placeholder'=>'-ทั้งหมด-', 'id'=>'filter_condition_search']); !!}
                                </div>
                                <div class="col-md-6">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control ', 'id' => 'filter_search', 'title'=>'ค้นหา:เลขมาตรา, คำอธิบายมาตรา']); !!}
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
                                {!! Form::select('filter_status', App\Models\Law\Log\LawLogWorking::selectRaw('distinct `status`')->groupBy('status')->pluck('status','status'), null, ['class' => 'form-control  text-center', 'id' => 'filter_status', 'placeholder'=>'-สถานะทั้งหมด-']); !!}
                            </div>
                        </div>

                        <div id="search-btn" class="panel-collapse collapse">
                            <div class="white-box" style="display: flex; flex-direction: column;">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {!! Form::label('filter_date', 'วันที่บันทึก', ['class' => 'col-md-12 control-label']) !!}
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
              
                        <p class="h2 text-bold-550 text-center">รายงานประวัติการดำเนินงาน</p>
                        <p class="h4 text-bold-400 text-center">ข้อมูล ณ วันที่ {!! HP::formatDateThaiFull(date('Y-m-d')) !!}  เวลา {!! (\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i'))  !!} น.</p>
                        
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">ลำดับ</th>
                                    <th width="15%" class="text-center">ระบบงานหลัก</th>
                                    <th width="15%" class="text-center">ระบบงานย่อย</th>
                                    <th width="12%" class="text-center">เลขที่อ้างอิง</th>
                                    <th width="15%" class="text-center">สถานะ</th>
                                    <th width="15%" class="text-center">รายละเอียด</th>
                                    <th width="15%" class="text-center">วันที่บันทีก</th>
                                    <th width="15%" class="text-center">ผู้บันทึก</th>
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

<div class="modal fade" id="RemarkModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">รายละเอียดเพิ่มเติม</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <span id="remark_txt"></span>
                    </div>
                </div>
                <div class="text-center"><br>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {!! __('ปิด') !!}
                    </button>
                </div>
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

            $("body").on("click", ".show_remark", function() {// รายละเอียด
               var comment_more = $(this).data('remark');
                $('#remark_txt').text(comment_more);
                 
                $('#RemarkModals').modal('show');
             });

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
            @endif

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/law/report/log-working/data_list') !!}',
                    data: function (d) {
                        d.filter_condition_search = $('#filter_condition_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_search = $('#filter_search').val();
                        d.filter_start_date = $('#filter_start_date').val();
                        d.filter_end_date = $('#filter_end_date').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'system', name: 'system' },
                    { data: 'ref_system', name: 'ref_system' },
                    { data: 'ref_no', name: 'ref_no' },
                    { data: 'status', name: 'status' },
                    { data: 'remark', name: 'remark' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
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
                $('#filter_search,#filter_created_at').val('');
                $('#filter_status').val('').trigger('change.select2');
                table.draw();
            });


        });

    </script>

@endpush

@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

@endpush

@section('content')

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">


                    <h3 class="box-title pull-left">จัดการผู้ประกอบการ</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('user-sso'))
                            <a class="btn btn-success btn-sm waves-effect waves-light m-r-10" href="{{ url('/sso/user-sso/create') }}">
                                <span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่ม</b>
                            </a>
                        @endcan

                        @can('edit-'.str_slug('user-sso'))

                            <a class="btn btn-success btn-sm waves-effect waves-light" href="#" onclick="Unblock();">
                                <span class="btn-label"><i class="mdi mdi-account-check"></i></span><b> ยกเลิกบล็อก</b>
                            </a>

                            <a class="btn btn-danger btn-sm waves-effect waves-light" href="#" onclick="Block();">
                                <span class="btn-label"><i class="mdi mdi-account-remove"></i></span><b> บล็อก</b>
                            </a>

                            <a class="btn btn-primary btn-sm waves-effect waves-light" href="#" onclick="ConfirmStatus();">
                                <span class="btn-label"><i class="mdi mdi-account-star"></i></span><b> ยันยืนผู้ใช้งาน</b>
                            </a>

                        @endcan
                    </div>

                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นจากชื่อ เลขผู้เสียภาษี หรืออีเมล์']); !!}
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary waves-effect waves-light" data-parent="#capital_detail" href="#advance-box" data-toggle="collapse" id="advance-btn">
                                            <small>เครื่องมือค้นหา</small> <span class="glyphicon glyphicon-menu-up"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group  pull-left">
                                        <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                    </div>
                                    <div class="form-group  pull-left m-l-15">
                                        <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                            ล้าง
                                        </button>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group ">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_state', (new App\Models\Sso\User)->states(), null, ['class' => 'form-control', 'placeholder'=>'-เลือกสถานะ-', 'id'=> 'filter_state']); !!}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div id="advance-box" class="panel-collapse collapse">
                                        <div class="white-box form-horizontal" style="display: flex; flex-direction: column;">

                                            <div class="row">
                                                <div class="col-md-5 form-group">
                                                    {!! Form::label('filter_block', 'สถานะใช้งาน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::select('filter_block', ['0' => 'ใช้งาน','1' => 'บล็อค' ], null, ['class' => 'form-control', 'id'=>'filter_block', 'placeholder' => '-เลือกสถานะใช้งาน-']); !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-7 form-group">
                                                    {!! Form::label('state', 'วันที่ลงทะเบียน:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-daterange input-group date-range">
                                                            <div class="input-group">
                                                                {!! Form::text('registerDate_start', null, ['class' => 'form-control datepicker', 'placeholder' => "dd/mm/yyyy", 'id' => 'registerDate_start']) !!}
                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                            </div>
                                                            <label class="input-group-addon bg-white b-0 control-label">ถึง</label>
                                                            <div class="input-group">
                                                                {!! Form::text('registerDate_end', null, ['class' => 'form-control datepicker', 'placeholder' => "dd/mm/yyyy", 'id' => 'registerDate_end']) !!}
                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-5 form-group">
                                                    {!! Form::label('filter_type', 'ประเภท:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::select('filter_type',  HP::applicant_types(), null, ['class' => 'form-control', 'id'=>'filter_type', 'placeholder' => '-เลือกประเภท-']); !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-7 form-group">
                                                    {!! Form::label('state', 'วันที่เข้าใช้งานล่าสุด:', ['class' => 'col-md-4 control-label label-filter']) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-daterange input-group date-range">
                                                            <div class="input-group">
                                                                {!! Form::text('lastvisitDate_start', null, ['class' => 'form-control datepicker','placeholder'=>"dd/mm/yyyy", 'required' => false, 'id' => 'lastvisitDate_start']) !!}
                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                            </div>
                                                            <label class="input-group-addon bg-white b-0 control-label">ถึง</label>
                                                            <div class="input-group">
                                                                {!! Form::text('lastvisitDate_end', null, ['class' => 'form-control datepicker','placeholder'=>"dd/mm/yyyy", 'required' => false, 'id' => 'lastvisitDate_end']) !!}
                                                                <span class="input-group-addon"><i class="icon-calender"></i></span>
                                                            </div>
                                                        </div>
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
                                        <th>#</th>
                                        <th><input type="checkbox" id="checkall"></th>
                                        <th>ประเภท</th>
                                        <th>ชื่อผู้ประกอบการ/เลขผู้เสียภาษี</th>
                                        <th>วันที่จดทะเบียน</th>
                                        <th>รหัสสาขา</th>
                                        <th>อีเมล (ชื่อผู้ใช้งาน)</th>
                                        <th>วันที่ลงทะเบียน</th>
                                        <th>วันที่เข้าใช้งานล่าสุด</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
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

    <!-- Modal Block -->
    <div id="block-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">

                {!! Form::open(['url' => '/sso/user-sso/block', 'method' => 'post', 'id' => 'form-block']) !!}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">เหตุผลที่บล็อค</h4>
                    </div>
                    <div class="modal-body">

                            <div class="form-group">
                                <label for="message-text" class="control-label">เหตุผล:</label>
                                <textarea class="form-control" id="remark" name="remark" placeholder="กรุณากรอกเหตุผล" required></textarea>
                            </div>

                            <span class="hide-id hide">

                            </span>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger waves-effect waves-light">บันทึก</button>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ปิด</button>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script>

        var table = '';
        $(document).ready(function () {

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

            //ช่วงวันที่
            jQuery('.input-daterange').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            //ช่วงวันที่
            jQuery('#date-range').datepicker({
                toggleActive: true,
                language:'th-th',
                format: 'dd/mm/yyyy',
            });

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/sso/user-sso/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_state  = $('#filter_state').val();
                        d.filter_type   = $('#filter_type').val();
                        d.filter_block   = $('#filter_block').val();

                        d.registerDate_start = $('#registerDate_start').val();
                        d.registerDate_end   = $('#registerDate_end').val();

                        d.lastvisitDate_start = $('#lastvisitDate_start').val();
                        d.lastvisitDate_end   = $('#lastvisitDate_end').val();


                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'applicant_types', name: 'applicant_types' },
                    { data: 'name', name: 'name' },
                    { data: 'date_birth', name: 'date_birth' },
                    { data: 'branch_code', name: 'branch_code' },
                    { data: 'email', name: 'email' },
                    { data: 'register_date', name: 'register_date' },
                    { data: 'lastvisit_date', name: 'lastvisit_date' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });


            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                $(".item_checkbox").prop('checked', true);
                } else {
                $(".item_checkbox").prop('checked',false);
                }
            });

            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_search').val('');
                $('#filter_state').val('').select2();
                $('#advance-box').find('select').val('').select2();
                $('#advance-box').find('input').val('');
                $("#filter_tis_id").select2("val", "");
                table.draw();
            });

            $('#filter_search').keyup(function (e) {
                table.draw();
            });

            $('#filter_state').change(function (e) {
                table.draw();
            });

            //เมื่อแสดง ค้นหาชั้นสูง
            $('#advance-box').on('show.bs.collapse', function () {
                $("#advance-btn").addClass('btn-inverse').removeClass('btn-primary');
                $("#advance-btn > span").addClass('glyphicon-menu-up').removeClass('glyphicon-menu-down');
            });

            //เมื่อซ่อน ค้นหาชั้นสูง
            $('#advance-box').on('hidden.bs.collapse', function () {
                $("#advance-btn").addClass('btn-primary').removeClass('btn-inverse');
                $("#advance-btn > span").addClass('glyphicon-menu-down').removeClass('glyphicon-menu-up');
            });




        });

        function Block(){

            var id = [];
            $('.item_checkbox:checked').each(function(index, element){
                id.push($(element).val());
            });


            if(id.length > 0){
                $('#block-modal').modal('show');

                $("#form-block").find('.hide-id').html('');//clear ค่าเดิม
                $('.item_checkbox:checked').clone().appendTo($("#form-block").find('.hide-id'));// clone ค่าที่เลือก


            }else{
                alert("กรุณาเลือกผู้ใช้งานที่ต้องการบล็อค");
            }

        }

        function confirm_block() {
            return confirm("ยืนยันการบล็อคผู้ใช้งาน?");
        }

        function Unblock(){

            var id = [];
            $('.item_checkbox:checked').each(function(index, element){
                id.push($(element).val());
            });

            if(id.length > 0){

                if(confirm("ยืนยันการยกเลิกบล็อคผู้ใช้งาน " + id.length + " แถว นี้ ?")){
                    $.ajax({
                        type:"POST",
                        url:  "{{ url('/sso/user-sso/unblock') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            cb: id
                        },
                         success:function(data){

                            if( data == 'success'){
                                table.draw();
                                toastr.success('ยกเลิกบล็อคผู้ใช้งานสำเร็จ !');
                                $('#checkall').prop('checked', false);
                            }

                        }
                    });
                }

            }else{
                alert("กรุณาเลือกผู้ใช้งานที่ต้องการยกเลิกการบล็อค");
            }

        }

        function ConfirmStatus(){


            var id = [];
            $('.item_checkbox:checked').each(function(index, element){
                id.push($(element).val());
            });

            if(id.length > 0){

                if( $('.item_checkbox:checked[data-state!="3"]').length == 0 ){
                    if(confirm("ยืนยันเปิดใช้ผู้ใช้งาน " + id.length + " แถว นี้ ?")){
                        $.ajax({
                            type:"POST",
                            url:  "{{ url('/sso/user-sso/confirm-status') }}",
                            data:{
                                _token: "{{ csrf_token() }}",
                                cb: id
                            },
                            success:function(data){

                                if( data == 'success'){
                                    table.draw();
                                    toastr.success('เปิดใช้ผู้ใช้งานสำเร็จ !');
                                    $('#checkall').prop('checked', false);
                                }

                            }
                        });
                    }
                }else{
                    alert('กรุณาเลือกเฉพาะผู้ใช้ที่สถานะ "รอเจ้าหน้าที่เปิดใช้งาน"');
                }

            }else{
                alert("กรุณาเลือกผู้ใช้งานที่ต้องการยืนยัน");
            }

        }

    </script>
@endpush

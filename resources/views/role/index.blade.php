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

          
                    <h3 class="box-title pull-left">จัดการสิทธิ์การใช้งาน</h3>

                    <div class="pull-right">

                        @can('add-'.str_slug('permission'))
                            <a class="btn btn-success pull-right waves-effect waves-light" href="{{url('role/create')}}">
                            <i class="icon-plus"></i> เพิ่มกลุ่มผู้ใช้งาน
                            </a>
                        @endcan
                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row" id="myFilter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            {!! Form::text('filter_search', null, ['class' => 'form-control', 'placeholder' => 'ค้นจากชื่อกลุ่มผู้ใช้งาน', 'id' => 'filter_search']); !!}
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-success waves-effect waves-light" id="btn_search">ค้นหา</button>
                                                <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">ล้าง</button>
                                            </div>
                                        </div>
                                    </div><!-- /form-group -->
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_group', App\RoleSettingGroup::pluck('title', 'id'), null, ['class' => 'form-control', 'id' => 'filter_group', 'placeholder'=>'-เลือกระบบงาน-']); !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_status', [ 1=> 'เปิดใช้งาน', 2=> 'ปิดใช้งาน' ], null, ['class' => 'form-control', 'id' => 'filter_status', 'placeholder'=>'-เลือกสถานะ-']); !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div><!-- /.row -->

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">ลำดับ</th>
                                        <th class="text-center" width="25%">ชื่อกลุ่มผู้ใช้งาน</th>
                                        <th class="text-center" width="10%">ส่วนควบคุม</th>
                                        <th class="text-center" width="15%">ระบบงาน</th>
                                        <th class="text-center" width="12%">สถานะ</th>
                                        <th class="text-center" width="15%">ผู้แก้ไขล่าสุด</th>
                                        <th class="text-center" width="18%">จัดการ</th>
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

        $(document).ready(function() {

            $(document).on('click', '.delete', function () {
                if (confirm('Are you sure want to delete?')) {

                }else {
                    return false;
                }

            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/role/data_list') !!}',
                    data: function (d) {

                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_group  = $('#filter_group').val();
 
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name', name: 'name' },
                    { data: 'label', name: 'label' },
                    { data: 'group', name: 'group' },
                    { data: 'status', name: 'status' },
                    { data: 'updated_name', name: 'updated_name' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,-1, -2, -3,-4] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });
                }
            });


            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_group,#filter_status').change(function (e) { 
                table.draw();
            });


            $('#btn_search').click(function (e) { 
               table.draw();
            });
            
            $('#btn_clean').click(function (e) {
                $('#myFilter').find('input').val('');
                $('#myFilter').find('select').val('').select2();
                $('#myFilter').submit();
               table.draw();

            });

        });

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

    </script>

@endpush

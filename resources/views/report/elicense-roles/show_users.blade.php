@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
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
                    <h3 class="box-title pull-left">รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท (Elicense)# {{ $usergroup->id }}</h3>
                    @can('view-'.str_slug('report-roles'))
                        <a class="btn btn-success pull-right" href="{{ url('/report/elicense-roles/') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <center>
                                <h2 class="text-dark">กลุ่มบทบาท : {!! $usergroup->title !!}</h2>
                            </center>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก ชื่อ-สกุล/เลขผู้เสียภาษี']); !!}
                            </div><!-- /form-group -->
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-3">
                            <div class="pull-left">
                                <button type="button" class="btn btn-info waves-effect waves-light m-l-5" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                <button type="button" class="btn btn-warning waves-effect waves-light m-l-5" id="btn_clean">
                                    ล้าง
                                </button>
                            </div>
                        </div><!-- /.col-lg-1 -->
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            
                            
                            <table class="table table-borderless" id="myTable">
                                <thead>
                                    <tr>
                                        <th >#</th>
                                        <th class="text-center"  width="35%">ชื่อ-สกุล</th>
                                        <th class="text-center"  width="25%">เลขประจำตัวประชาชน</th>
                                        <th class="text-center"  width="25%">อีเมล</th>
                                        <th class="text-center"  width="10%">สถานะ</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
           

                        </div>
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
    
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/report/elicense-roles/data_users_list') !!}',
                    data: function (d) {
                        d.filter_search  = $('#filter_search').val();
                        d.filter_role_id = '{!! $usergroup->id !!}';
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'name', name: 'name' },
                    { data: 'tax_number', name: 'tax_number' },
                    { data: 'reg_email', name: 'reg_email' },
                    { data: 'block', name: 'block' },

                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                            
                }
            });

            
            $('#btn_search').click(function () {
                table.draw();
            });

            $('#btn_clean').click(function () {
                $('#filter_status,#filter_search').val('');
                table.draw();
            });
        });
    </script>
@endpush
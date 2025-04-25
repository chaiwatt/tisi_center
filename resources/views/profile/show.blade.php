@extends('layouts.master')
@push('css')
    <link rel="stylesheet" href="{!! asset('plugins/components/datatables/media/css/dataTables.bootstrap.min.css') !!}" />
@endpush

@section('content')

    @php
        $user = auth()->user();
        $picture = $user->profile == null || $user->profile->pic == null ? asset('storage/uploads/users/no_avatar.jpg') : HP::getFileStorage('users/'.$user->profile->pic);
        $subdepart = $user->subdepart;
    @endphp

<div class="container-fluid">
    <!-- /.row -->
    <!-- .row -->
    <div class="row">
        {{-- <div class="col-md-4 col-xs-12">
            <div class="white-box">
                <div class="user-bg"> <img width="100%" alt="user" src="{{asset('plugins/images/large/factory.jpg')}}">
                    <div class="overlay-box">
                        <div class="user-content">
                            <a href="javascript:void(0)">
                                <img src="{{ $picture }}" class="thumb-lg img-circle" alt="img">
                            </a>
                            <h4 class="text-white">อีเมล</h4>
                            <h5 class="text-white">{{ $user->reg_email }}</h5> </div>
                    </div>
                </div>
                <div class="user-btm-box">
                    <div class="col-md-4 col-sm-4 text-center">
                        <p class="text-purple"><i class="ti-facebook"></i></p>
                        <h1>258</h1> </div>
                    <div class="col-md-4 col-sm-4 text-center">
                        <p class="text-blue"><i class="ti-twitter"></i></p>
                        <h1>125</h1> </div>
                    <div class="col-md-4 col-sm-4 text-center">
                        <p class="text-danger"><i class="ti-dribbble"></i></p>
                        <h1>556</h1> </div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-12 col-xs-12">
            <div class="white-box">
                <ul class="nav nav-tabs tabs customtab">
                    <li class="active tab">
                        <a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">โปรไฟล์</span> </a>
                    </li>
                    <li class="tab">
                        <a href="#messages" data-toggle="tab" aria-expanded="true"> <span class="visible-xs"><i class="fa fa-envelope-o"></i></span> <span class="hidden-xs">ประวัติการเข้าใช้งาน</span> </a>
                    </li>
                </ul>

                <div class="tab-content m-t-0">

                    <div class="tab-pane active" id="profile">

                        <div class="row">
                            <a href="{{ url('account-settings') }}" class="btn btn-success btn-sm pull-right">
                                <i class="fa fa-edit"></i> แก้ไข
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-xs-6 b-r"> <strong>ชื่อเต็ม</strong>
                                <br>
                                <p class="text-muted">{{ $user->FullName }}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>อีเมล</strong>
                                <br>
                                <p class="text-muted">{{ $user->reg_email }}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>เบอร์ที่ทำงาน</strong>
                                <br>
                                <p class="text-muted">{{ $user->reg_wphone }}</p>
                            </div>
                            <div class="col-md-3 col-xs-6 b-r"> <strong>เบอร์มือถือ</strong>
                                <br>
                                <p class="text-muted">{{ $user->reg_phone }}</p>
                            </div>
                        </div>

                        <div class="form-horizontal" role="form">
                            <div class="form-body">
                                <h4 class="font-bold">ข้อมูลการผู้ใช้งาน</h4>
                                <hr class="m-t-0 m-b-10">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-4">เลขประจำตัวประชาชน:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> {{ $user->reg_13ID }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-8 p-r-10">ชื่อ:</label>
                                            <div class="col-md-4">
                                                <p class="form-control-static p-l-5"> {{ $user->reg_fname }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-6">สกุล:</label>
                                            <div class="col-md-6">
                                                <p class="form-control-static"> {{ $user->reg_lname }} </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-4">อีเมล (ชื่อผู้ใช้งาน):</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> {{ $user->reg_email }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-8 p-r-10">เบอร์ที่ทำงาน:</label>
                                            <div class="col-md-4">
                                                <p class="form-control-static p-l-5"> {{ $user->reg_wphone }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-6">เบอร์มือถือ:</label>
                                            <div class="col-md-6">
                                                <p class="form-control-static"> {{ $user->reg_phone }} </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <h4 class="font-bold">หน่วยงาน</h4>
                                <hr class="m-t-0 m-b-10">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-4 p-r-10">กอง:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> {{ !is_null($subdepart) && !is_null($subdepart->department) ? $subdepart->department->depart_name : '-' }} </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group m-b-0">
                                            <label class="control-label col-md-4 p-r-10">กลุ่ม:</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> {{ !is_null($subdepart) ? $subdepart->sub_departname : '-' }} </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <h4 class="font-bold">กลุ่มผู้ใช้งาน</h4>
                                <hr class="m-t-0 m-b-10">
                                <div class="row">

                                    @foreach ($user->data_list_roles as $role_user)

                                        @php
                                            $role = $role_user->role;
                                            if(is_null($role)){
                                                continue;
                                            }
                                        @endphp


                                        <div class="col-md-12">
                                            <div class="form-group m-b-0">
                                                <label class="control-label col-md-1"></label>
                                                <div class="col-md-11">
                                                    <p class="form-control-static"> {{ $role->name }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="messages">

                        <div class="clearfix m-b-20"></div>

                        <div class="row">
                            <div class="col-md-12">

                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th width="2%" class="text-center">No.</th>
                                            <th width="30%">วันเวลาเข้าใช้งาน</th>
                                            <th width="30%">วันเวลาออกจากระบบ</th>
                                            <th width="9%">IP Address</th>
                                            <th width="12%">Web Browser</th>
                                            <th width="17%" class="text-center">ช่องทาง</th>
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
    </div>

</div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/profile/login_list') !!}',
                    data: function (d) {
                        d.filter_search = $('#filter_search').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_standard = $('#filter_standard').val();
                    }
                },
                columns: [
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'login_at', name: 'login_at' },
                    { data: 'logout_at', name: 'logout_at' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'user_agent', name: 'user_agent' },
                    { data: 'channel', name: 'channel' },
                ],
                columnDefs: [
                    { className: "text-top text-center", targets:[0,-1] },
                    { className: "text-top", targets: "_all" },
                ],
                fnDrawCallback: function() {

                }
            });

        });

    </script>
@endpush

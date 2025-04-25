@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
    @php
        $max_input_vars = ini_get('max_input_vars');
        $is_over_system = ($max_input_vars>(count($to)+count($from)+10))?false:true;
    @endphp

    <div class="container-fluid">
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title pull-left">แก้ไขสิทธิ์การใช้งาน</h3>

                <a class="btn btn-success pull-right waves-effect waves-light" href="{{url('role-management')}}">
                    <i class="icon-arrow-left-circle"></i> กลับ
                </a>

                <div class="clearfix"></div>
                <hr>

                @if($is_over_system)
                    <ul class="alert alert-danger">
                        <li>ค่า max_input_vars น้อยกว่าจำนวนผู้ใช้งานโปรดตั้งค่า ที่ php.ini</li>
                    </ul>
                @endif

                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <form class="form-horizontal" method="post" action="{{url('role/edit_right/'.$role->id)}}">

                            {{csrf_field()}}

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">ชื่อกลุ่มผู้ใช้งาน</label>
                                <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" value="{{ $role->name }}" disabled="disabled">
                                    <span class="input-group-addon"><i class="icon-user-following"></i></span>
                                </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="label" class="col-sm-3 control-label">ส่วนการควบคุม</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $role->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ' }}" disabled="disabled">
                                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                                    </div>
                                </div>
                            </div>

                            @if($role->label=='staff')
                                @include('role.form.users_trader')
                            @elseif($role->label=='trader')
                                @include('role.form.users_trader')
                            @endif

                            <div class="form-group m-b-0">
                                <div class="col-md-12 text-center">

                                    @if($is_over_system===false)
                                        <button class="btn btn-primary  waves-effect waves-light m-t-10" type="submit">
                                            <i class="fa fa-paper-plane"></i> บันทึก
                                        </button>
                                    @endif

                                    <a href="{{ url('role-management') }}" class="btn btn-default waves-effect waves-light m-t-10">
                                        <i class="fa fa-rotate-left"></i> ยกเลิก
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('js/multiselect.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            @if(\Session::has('flash_message'))
                $.toast({
                    heading: 'Success!',
                    position: 'top-center',
                    text: '{{session()->get('
                    message ')}}',
                    loaderBg: '#70b7d6',
                    icon: 'success',
                    hideAfter: 3000,
                    stack: 6
                });
            @endif
            $('#lstview').multiselect();
        });
    </script>
@endpush

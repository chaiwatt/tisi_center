@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/components/bootstrap-toggle/bootstrap-toggle.min.css')}}" rel="stylesheet" rel="stylesheet">
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <style>
        /* .list-unstyled {
            list-style: none;
        } */
    </style>
@endpush

@section('content')



    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">จัดการผู้ใช้งาน</h3>

                    <div class="pull-right">
                        @can('add-'.str_slug('user'))
                            <a class="btn btn-success pull-right" href="{{url('user/create')}}">
                                <i class="icon-plus"></i> เพิ่ม
                            </a>
                        @endcan
                    </div>
                    <hr class="hr-line bg-primary">
                    <div class="clearfix"></div>

                    <div class="row" id="myFilter">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
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
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_department',  App\Models\Besurv\Department::pluck('depart_name', 'did'),  null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงาน-', 'id'=>'filter_department'] ) !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_sub_department',  App\Models\Besurv\Department::with('sub_department')->get()->pluck('sub_departments', 'depart_name'),  null, ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มงานย่อย-', 'id'=>'filter_sub_department'] ) !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->

                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select('filter_roles', App\Role::orderbyRaw('CONVERT(name USING tis620)')->pluck('name', 'id'),   null,  ['class' => 'form-control', 'placeholder'=>'-เลือกกลุ่มผู้ใช้งานและหน่วยงาน-', 'id' => 'filter_roles'] )  !!}
                                        </div>
                                    </div>
                                </div><!-- /.col-lg-5 -->
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="2%"><input type="checkbox" id="checkall"></th>
                                        <th class="text-center"  width="1%">#</th>
                                        <th class="text-center"  width="20%">ชื่อ-สกุล/<br>เลขประจำตัวประชาชน</th>
                                        <th class="text-center"  width="20%">อีเมล</th>
                                        <th class="text-center"  width="18%">กลุ่มงานย่อย</th>
                                        <th class="text-center"  width="22%">กลุ่มผู้ใช้งาน</th>
                                        <th class="text-center"  width="17%">เครื่องมือ</th>
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

    @include('users.modals.role')
    @include('users.modals.user-groups')

@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{ asset('plugins/components/bootstrap-toggle/bootstrap-toggle.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>

    <script>
        var table = '';
        $(document).ready(function () {

            $( "#filter_clear" ).click(function() {
                window.location.assign("{{url('/users')}}");
            });

            $(document).on('click','.delete',function (e) {
                if(confirm('Are you sure want to delete?')) {

                } else{
                    return false;
                }
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

            table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{!! url('/user/data_list') !!}',
                    data: function (d) {

                        d.filter_search         = $('#filter_search').val();
                        d.filter_sub_department = $('#filter_sub_department').val();
                        d.filter_roles          = $('#filter_roles').val();
                        d.filter_department     = $('#filter_department').val();

                    }
                },
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false},
                    { data: 'DT_Row_Index', searchable: false, orderable: false},
                    { data: 'reg_fname', name: 'reg_fname' },
                    { data: 'reg_email', name: 'reg_email' },
                    { data: 'sub_departname', name: 'sub_departname' },
                    { data: 'roles', name: 'roles' },
                    { data: 'action', name: 'action' },
                ],
                columnDefs: [
                    { className: "text-center text-top", targets:[0,1,-1] },
                    { className: "text-top", targets: "_all" }

                ],
                fnDrawCallback: function() {

                    $(".js-switch").each(function() {
                        new Switchery($(this)[0], { size: 'small' });
                    });

                    var group = '<label for="collapse_switch"> กลุ่มผู้ใช้งาน <input class="collapse_switch" type="checkbox" data-toggle="toggle" data-on="แสดง" data-off="ซ่อน" data-onstyle="success" data-offstyle="danger" data-width="100" data-height="25" id="collapse_switch"></label>';
                    
                    var modal = '';

                    @if(auth()->user()->can('edit-user'))
                        modal += '<button class="btn btn-sm btn-primary m-l-15 modal_roles"><i class="fa fa-users"></i> กำหนดกลุ่มผู้ใช้</button>';
                    @endif

                    var el = '<div class="pull-right">'+group+''+modal+'</div>';

                    var div = ($('#myTable_length').parent().parent().find('.col-sm-6').last());
                        div.html('');
                        div.append(el)
                        $('#collapse_switch').bootstrapToggle();

                    if($('.collapse_switch').is(':checked',true)){
                        $('.collapse_show_span').collapse('show');
                    } else {
                        $('.collapse_show_span').collapse('hide');
                    }   
                            
                }
            });

            $(document).on('change', '.collapse_switch', function(){

                if($(this).is(':checked',true)){
                    $('.collapse_show_span').collapse('show');
                } else {
                    $('.collapse_show_span').collapse('hide');
                }

            });


            $('#filter_search').keyup(function (e) { 
                table.draw();
            });

            $('#filter_sub_department,#filter_roles').change(function (e) { 
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


            $(document).on('click', '.modal_show_role', function(){

                var val = $(this).data('id');
                var name = $(this).data('name');

                $('.show_role_box').html('');
                $('#RoleModalLabel').html('');

                if( val != '' ){

                    $.LoadingOverlay("show", {
                        image: "",
                        text: "กรุณารอสักครู่..."
                    });

                    $('#RoleModalLabel').html(name);

                    $('#RoleModal').modal('show');
                    $.ajax({
                        url: "{!! url('/user/load_data_role') !!}" + "/" + val
                    }).done(function( object ) {

                        $('.show_role_box').html(object);
                        $.LoadingOverlay("hide");

                    });
                }

                
            });

            $('#checkall').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_checkbox").prop('checked', true);
                } else {
                    $(".item_checkbox").prop('checked',false);
                }
            });

            $("#collapse_role").each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            $(document).on('click', '.modal_roles', function(){

                
                var arrRowId = [];

                var html = '';
                var i = 0;
                //Iterate over all checkboxes in the table
                table.$('.item_checkbox:checked').each(function (index, rowId) {

                    var input = '<input type="hidden" name="id[]" value="'+($(rowId).val())+'">';
                    html += '<tr>';
                    html += '<td class="text-top text-center">'+(++i)+'</td>';
                    html += '<td class="text-top">'+( $(rowId).data('name') )+'<div>'+( $(rowId).data('taxid') )+'</div>'+input+'</td>';
                    html += '</tr>';

                    arrRowId.push($(rowId).val());

                });

                $(".collapse_role").prop('checked',true);
                $(".input_roles_checkbox").prop('checked',false);


                if (arrRowId.length > 0) {
                    $('#myTableSelect tbody').html(html);   
                    $('#UserGroupsModal').modal('show');
                }else{
                    Swal.fire({
                        type: 'warning',
                        title: 'เลือกอย่างน้อย 1 รายการ',
                        confirmButtonText: 'รับทราบ',
                    });
                }

            });

        });

    </script>

@endpush

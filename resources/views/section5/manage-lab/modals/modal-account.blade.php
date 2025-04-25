{!! Form::model($labs, [
    'method' => 'PATCH',
    'url' => ['/section5/labs/account-save', $labs->id],
    'class' => 'form-horizontal',
    'files' => true,
]) !!}


<div id="MdAccount" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width: 1140px; max-width: 1140px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">เปลี่ยนบัญชีผู้ใช้งานของห้องปฎิบัติการ</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            {!! Form::label('sso_username', 'ชื่อผู้ใช้งาน :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                <div class="input-group">
                                    {!! Form::text('sso_username', null, ['class' => 'form-control', 'required' => true]) !!}
                                    <span class="input-group-btn">
                                        <button id="sso_search" type="button" class="btn waves-effect waves-light btn-inverse" data-placement="right">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                                <span id="sso_username_status"></span>
                                <span class="hidden">
                                    {!! Form::text('lab_user_id_new', null, ['id' => 'lab_user_id_new', 'required' => true]) !!}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('email', 'อีเมล :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                <p class="form-control-static" id="sso_email">  </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('name', 'ชื่อหน่วยงาน :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                <p class="form-control-static" id="sso_name">  </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('branch_code', 'รหัสสาขา :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                <p class="form-control-static" id="sso_branch_code">  </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group required">
                            {!! Form::label('remark', 'หมายเหตุ :', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 2, 'required' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success waves-effect" type="submit" >บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#sso_search').click(function(event) {

                var sso_username = $('#sso_username').val();
                if(sso_username!=''){

                    $.ajax({
                        url: "{!! url('/section5/labs/search_user') !!}" + "/" + sso_username
                    }).done(function(object) {

                        if(object.status==true){//พบข้อมูล
                            var branch_code = object.user.branch_type == 2 ? object.user.branch_code : '<i class="text-muted">สำนักงานใหญ่</i>' ;
                            $('#sso_username_status').html('<span class="text-success">พบข้อมูลผู้ใช้งาน</span>');
                            $('#lab_user_id_new').val(object.user.id);
                            $('#sso_email').html(object.user.email);
                            $('#sso_name').html(object.user.name);
                            $('#sso_branch_code').html(branch_code);
                        }else{
                            $('#sso_username_status').html('<span class="text-danger">ไม่พบข้อมูลผู้ใช้งาน</span>');
                            $('#lab_user_id_new').val('');
                            $('#sso_email').html('');
                            $('#sso_name').html('');
                            $('#sso_branch_code').html('');
                        }

                    });

                }

            });
        });

    </script>
@endpush

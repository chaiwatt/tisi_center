
@php
    $data_users_assign =  App\User::where('status', 1)->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"), 'runrecno')->pluck('name', 'runrecno' );
@endphp

<!-- /.modal-dialog -->
<div id="modal-assign" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">มอบหมายคำขอ</h4>
            </div>
            <div class="modal-body">

                <form class="form-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('m_applicant_no', 'เลขที่คำขอ', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    <span id="show_application_no"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required row">
                                {!! Form::label('m_assign_by', 'มอบหมาย'.' :', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('m_assign_by', $data_users_assign, null, ['class' => 'select2-multiple', 'multiple' => 'multiple', 'required' => 'required', 'data-placeholder'=>'- เลือกเจ้าหน้าที่ผู้รับผิดชอบ -']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('m_assign_comment', 'หมายเหตุ', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::textarea('m_assign_comment', null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                {!! Form::label('created_at', 'วันที่มอบหมาย', ['class' => 'col-md-4 control-label text-right']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('created_at', ( HP::DateThaiFull(date('Y-m-d')) ),  ['class' => 'form-control', 'disabled' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="MyTable-Modal">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th scope="col">เลขที่คำขอ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> --}}


                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect mr-auto" id="btn_save_modal">บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

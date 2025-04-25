<div id="modal_approve" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">บันทึกผลเสนอคณะอนุกรรมการ</h4> 
            </div>
            <div class="modal-body">
                <form id="modal_form_approve" enctype="multipart/form-data" class="form-horizontal repeater-file">
                    <div class="row">

                        <div class="form-group">
                            {!! HTML::decode(Form::label('m_board_meeting_result', 'มติคณะอนุกรรมการ'.' :', ['class' => 'col-md-3 control-label text-right'])) !!}
                            <div class="col-md-2">
                                {!! Form::radio('m_board_meeting_result', '1', null , ['class' => 'form-control check m_board_meeting_result', 'data-radio' => 'iradio_flat-blue', 'id'=>'m_board_meeting_result-1']) !!}
                                {!! Html::decode(Form::label('m_board_meeting_result-1', 'ผ่าน', ['class' => 'control-label text-capitalize'])) !!}
                            </div>
                            <div class="col-md-7">
                                {!! Form::radio('m_board_meeting_result', '2', null , ['class' => 'form-control check m_board_meeting_result ', 'data-radio' => 'iradio_flat-blue', 'id'=>'m_board_meeting_result-2']) !!}
                                {!! Form::label('m_board_meeting_result-2', 'ไม่ผ่าน', ['class' => 'control-label text-capitalize']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('m_board_meeting_date', 'วันที่ประชุมคณะอนุกรรมการ'.' :', ['class' => 'col-md-3 control-label  text-right']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('m_board_meeting_date', null ,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
          
                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('m_file_approve', 'เอกสารมติคณะอนุกรรมการ'.' :', ['class' => 'col-md-3 control-label  text-right']) !!}
                            <div class="col-md-4">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                        <span class="input-group-text btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="m_file_approve" id="m_file_approve">
                                        </span>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row" data-repeater-list="repeater-file-approve">
                        <div class="form-group" data-repeater-item>
                            {!! Form::label('file_approve_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label  text-right']) !!}
                            <div class="col-md-4">
                                {!! Form::text('m_file_approve_documents', null,['class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                        <span class="input-group-text btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="m_file_approve_other">
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-danger btn_file_remove" data-repeater-delete type="button">
                                    ลบ
                                </button>
                                <button type="button" class="btn btn-success btn_file_add" data-repeater-create><i class="icon-plus"></i>เพิ่ม</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('m_board_meeting_description', 'รายละเอียด/หมายเหตุ'.' :', ['class' => 'col-md-3 control-label  text-right']) !!}
                            <div class="col-md-7">
                                {!! Form::textarea('m_board_meeting_description',null,  ['class' => 'form-control', 'rows' => 4]) !!}
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mapprove">
                                <thead>
                                    <tr>
                                        <td width="5%" class="text-center">#</td>
                                        <th width="20%" class="text-center">เลขที่คำขอ</th>
                                        <th width="25%" class="text-center">ผู้ยื่นคำขอ</th>
                                        <th width="25%" class="text-center">ชื่อห้องปฏิบัติการ</th>
                                        <th width="25%" class="text-center">เลขที่ มอก.</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_approve">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#btn_save_approve').click(function (e) { 
                SaveApprove();
            });

            $('.repeater-file').repeater({
                show: function () {
                    $(this).slideDown();

                    $(this).find('.btn_file_add').hide();

                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ใช่หรือไม่ ?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });
        
        });

    </script>
@endpush

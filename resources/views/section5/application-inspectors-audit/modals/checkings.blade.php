<div id="modal_checkings" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">บันทึกผลตรวจประเมิน</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_checkings" enctype="multipart/form-data" class="form-horizontal repeater-file" onsubmit="return false">

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group required" >
                                {!! Form::label('m_audit_date', 'วันที่ตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::text('m_audit_date', null, ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-9">
                            <div class="form-group required">
                                {!! Form::label('m_audit_result', 'ผลตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('m_audit_result', ['1' => 'ผ่าน', '2' => 'ไม่ผ่าน'], null , ['class' => 'form-control m_audit_result', 'placeholder' => '-เลือกสถานะ-', 'required' => true, 'id' => 'm_audit_result']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 box_scope">
                            <label for="checkbox_branch_all"><input type="checkbox" id="checkbox_branch_all"  value="1"> ผ่านทุกรายสาขา</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                {!! HTML::decode(Form::label('m_audit_file', 'เอกสารการตรวจประเมิน'.' :', ['class' => 'col-md-4 control-label'])) !!}
                                <div class="col-md-8" >
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="m_audit_file">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists"  data-dismiss="fileinput">ลบ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group ">
                                {!! Form::label('m_audit_remark', 'หมายเหตุ'.' :', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('m_audit_remark', null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mcheckings">
                                <thead>
                                    <tr>
                                        <td width="5%" class="text-center">#</td>
                                        <th width="10%" class="text-center">เลขที่คำขอ</th>
                                        <th width="25%" class="text-center">ผู้ยื่นคำขอ</th>
                                        <th width="10%" class="text-center">เลขผู้เสียภาษี</th>
                                        <th width="25%" class="text-center">รายสาขา</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_checkings">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#modal_checkings').on('shown.bs.modal', function () {
                BtnDeleteDate();
                CheckBoxResule();
            });

            $('.repeater-form-date').repeater({
                show: function () {
                    $(this).slideDown();
                    BtnDeleteDate();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){
                            BtnDeleteDate();
                        }, 500);
                    }
                }
            });
            BtnDeleteDate();

            $('#m_audit_result').change(function (e) {
                CheckBoxResule();
            });
            CheckBoxResule();

            $('#checkbox_branch_all').on('click', function(e) {
                if($(this).is(':checked',true)){
                    $(".item_scope_checkbox_all").prop('checked', true);
                } else {
                    $(".item_scope_checkbox_all").prop('checked',false);
                }
            });

            $('#btn_save_checkings').click(function (e) {
                $('#modal_form_checkings').submit();   
            });

            
        });

        function BtnDeleteDate(){
            if( $('.btn_date_remove').length >= 2 ){
                $('.btn_date_remove').show();
            }else{
                $('.btn_date_remove').hide();
            }
        }

        function CheckBoxResule(){
            var audit_result = $('#m_audit_result').val();
            var box_scope = $('.box_scope');
            if( audit_result == 1 ){
                box_scope.show();
                $(document).find('.item_scope_checkbox_all').prop('disabled', false);
                $(document).find('.item_scope_checkbox_all').show();
            }else{
                $('.item_scope_checkbox_all').prop('checked', false);
                box_scope.hide();
                $(document).find('.item_scope_checkbox_all').prop('disabled', true);
                $(document).find('.item_scope_checkbox_all').hide();
                $('#checkbox_branch_all').prop('checked', false);
            }
        }

    </script>
@endpush

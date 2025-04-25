<div id="modal_board_approve" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">บันทึกประกาศราชกิจจานุเบกษา</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_board_approve" enctype="multipart/form-data" class="form-horizontal">

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('mb_government_gazette_date', 'วันที่ประกาศราชกิจจา', ['class' => 'col-md-3 control-label text-right']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('mb_government_gazette_date', null,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('mb_lab_start_date', 'วันที่มีผลเป็นหน่วยตรวจสอบ', ['class' => 'col-md-3 control-label text-right']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('mb_lab_start_date', null,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('mb_lab_end_date', 'วันที่สิ้นสุดเป็นหน่วยตรวจสอบ', ['class' => 'col-md-3 control-label text-right']) !!}
                            <div class="col-md-4">
                                <div class="input-group">
                                    {!! Form::text('mb_lab_end_date', null,  ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required">
                            {!! Form::label('mb_file_gazette', 'เอกสารประกาศราชกิจจา'.' :', ['class' => 'col-md-3 control-label text-right']) !!}
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
                                            <input type="file" name="mb_file_gazette" id="mb_file_gazette" required>
                                        </span>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('mb_government_gazette_description', 'รายละเอียด/หมายเหตุ'.' :', ['class' => 'col-md-3 control-label text-right']) !!}
                            <div class="col-md-8">
                                {!! Form::textarea('mb_government_gazette_description', null,  ['class' => 'form-control', 'rows' => 4]) !!}
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mboard_approve">
                                <thead>
                                    <tr>
                                        <td width="5%" class="text-center">#</td>
                                        <th width="15%" class="text-center">เลขที่คำขอ</th>
                                        <th width="25%" class="text-center">ชื่อห้องปฏิบัติการ<br>(ผู้ยื่นคำขอ)</th>
                                        <th width="25%" class="text-center">เลขที่ มอก.</th>
                                        <th width="20%" class="text-center">วันประชุมคณะอนุฯ</th>
                                        <th width="10%" class="text-center">อีเมลรับบัญชีผู้ใช้งานที่จะใช้ในระบบ e-License</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_board_approve">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#btn_save_board_approve').click(function (e) {
                SaveBoradApprove();
            });

            $('#mb_lab_start_date').change(function (e) {
                var val = $(this).val();
                if( val != ''){
                    var expire_date = CalExpireDate(val);
                    $('#mb_lab_end_date').val(expire_date);
                }else{
                    $('#mb_lab_end_date').val('');
                }
            });

        });

        function CalExpireDate(date){

            var dates = date.split("/");
            var date_start = new Date(dates[2]-543, dates[1]-1, dates[0]);
                date_start.setFullYear(date_start.getFullYear() + 3); // + 3 ปี
                date_start.setDate(date_start.getDate() - 1); // + 1 วัน

            var YB = date_start.getFullYear() + 543; //เปลี่ยนเป็น พ.ศ.
            var MB = str_pad(date_start.getMonth() + 1); //เดือนเริ่มจาก 0
            var DB = str_pad(date_start.getDate());

            var date = DB+'/'+MB+'/'+YB;
            return date;

        }

        function str_pad(str) {
            if (String(str).length === 2) return str;
            return '0' + str;
        }

    </script>
@endpush

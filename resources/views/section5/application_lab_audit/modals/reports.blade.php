<div id="modal_reports" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">บันทึกสรุปรายงาน</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_report" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! HTML::decode(Form::label('file_attach_report', 'เอกสารสรุปรายงาน'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-8" >
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            <input type="file" name="file_attach_report" required>
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists delete_personfile"  data-dismiss="fileinput">ลบ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('report_date', 'วันที่สรุปรายงาน'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-7">
                                    <div class="input-group">
                                        {!! Form::text('report_date', HP::revertDate( date('Y-m-d'),true) , ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                              
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('report_by', 'ผู้จัดทำรายงาน'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('report_by', App\User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS titels"),'runrecno AS id')->pluck('titels', 'id'),  (auth()->user()->getKey()), ['class' => 'form-control', 'placeholder' => '-เลือกผู้จัดทำรายงาน-', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('report_description', 'หมายเหตุ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('report_description', null,  ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group repeater-form-file">
                                {!! HTML::decode(Form::label('file_attach_other', 'เอกสารอื่นๆ'.' :', ['class' => 'col-md-3 control-label'])) !!}
                                <div class="col-md-8" data-repeater-list="repeater-file">
                                    <div class="row" data-repeater-item>
                                        <div class="col-md-5 col-custom-1">
                                            {!! Form::text('caption', null, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="col-md-5 col-custom-2">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">เลือกไฟล์</span>
                                                    <span class="fileinput-exists">เปลี่ยน</span>
                                                    <input type="file" name="file_attach_other">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists"  data-dismiss="fileinput">ลบ</a>
                                            </div>
                                            {!! $errors->first('activity_file', '<p class="help-block">:message</p>') !!}
                                        </div>
                                        <div class="col-md-2 col-custom-3">
                                            <button class="btn btn-danger btn_file_remove" data-repeater-delete type="button">
                                                ลบ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-custom-4">
                                    <button type="button" class="btn btn-success btn-sm pull-left" data-repeater-create><i class="icon-plus"></i> เพิ่ม</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mreport">
                                <thead>
                                    <tr>
                                        <td width="5%" class="text-center">#</td>
                                        <th width="20%" class="text-center">เลขที่คำขอ</th>
                                        <th width="25%" class="text-center">ชื่อห้องปฎิบัติการ<br>ผู้ยื่นคำขอ</th>
                                        <th width="25%" class="text-center">เลขผู้เสียภาษี</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_reports">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            
            $('#btn_report').click(function(event) {
                
                $('#myTable-Mreport tbody').html('');
                $('#modal_form_report').find('input,textarea').val('');
                $('#modal_form_report').find('select').val('').select2();

                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=ไม่อยู่ในเงื่อนไข
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        const announce_status = [4, 7, 8];
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะ
                            status_fail = true;
                        }

                        tr_ += '<tr>';
                        tr_ += '<td class="text-top">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('application_no'))+'<input type="hidden" name="id[]" class="item_m_rp_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('lab_name'))+'<br>'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_taxid'))+'</td>';
                        tr_ += '</tr>';

                    });

                    if(status_fail){//สถานะไม่เป็นไปตามเงื่อนไข
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกรายการ',
                            html: '<h5>ที่มีสถานะ เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน, อนุมัติ ไม่ผ่านการตรวจประเมิน <br>หรือ อยู่ระหว่างการพิจารณาอนุมัติ</h5>',
                            footer: '<h5>ตรวจสอบใหม่อีกครั้ง</h5>',
                            confirmButtonText: 'รับทราบ',
                            width:500
                        });
                        return false;
                    }

                    $('#myTable-Mreport tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_reports').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขออย่างน้อย 1 คำขอ');
                }
            });

            $('#modal_form_report').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_report")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application_lab_audit/update_lab_reports') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_reports').modal('hide');
                            $('#checkall').prop('checked', false);
                        }
                    }
                });

            });


            $('#modal_reports').on('shown.bs.modal', function () {
                BtnDeleteFile();
            });

            $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 500);
                    }
                }
            });

            
            $('#btn_save_reports').click(function (e) {
                $('#modal_form_report').submit();   
            })
        });

        function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }else{
                $('.btn_file_remove').hide();
            }
        }

    </script>
@endpush

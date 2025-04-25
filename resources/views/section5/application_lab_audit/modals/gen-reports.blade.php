<div id="modal_gen_reports" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">สร้างสรุปรายงาน</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_gen_report" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('meeting_date', 'วันที่ประชุม'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-7">
                                    <div class="input-group">
                                        {!! Form::text('meeting_date', HP::revertDate( date('Y-m-d'),true) , ['class' => 'form-control mydatepicker', 'required' => true]) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('meeting_no', 'ครั้งที่ประชุม'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('meeting_no', null , ['class' => 'form-control', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('meeting_description', 'รายละเอียด (ถ้ามี)'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-7">
                                    {!! Form::textarea('meeting_description', null , ['class' => 'form-control', 'rows' => 3]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mgen-report">
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
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_gen_reports">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            

            $('#btn_gen_reports').click(function (e) {
                $('#modal_form_gen_report').submit();   
            });
            
            $('#btn_gen_report').click(function(event) {

                $('#modal_gen_reports').find('input,textarea').val('');
                $('#modal_gen_reports').find('select').val('').select2();

                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=ไม่อยู่ในเงื่อนไข
                    var tr_ = '';
                    var audit_type = '';
                    

                    $('.item_checkbox:checked').each(function(index, el) {

                        if( audit_type == '' ){
                            audit_type = $(el).data('audit_type');
                        }

                        if( audit_type != $(el).data('audit_type') ){ //ไม่อยู่ประเภทการตรวจเดียวกัน
                            status_fail = true;
                        }

                        tr_ += '<tr>';
                        tr_ += '<td class="text-top text-center">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('application_no'))+'<input type="hidden" name="id[]" class="item_m_ap_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('lab_name'))+'<br>'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top text-center">'+($(el).data('applicant_taxid'))+'</td>';
                        tr_ += '</tr>';

                    });

                    if(status_fail){//สถานะไม่เป็นไปตามเงื่อนไข
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกรายการ',
                            html: '<h5>ที่มีใบรับรองระบบงานตามฐาน 17025 เดียวกัน</h5>',
                            footer: '<h5>ตรวจสอบใหม่อีกครั้ง</h5>',
                            confirmButtonText: 'รับทราบ',
                            width:500
                        });
                        return false;
                    }

                    $('#myTable-Mgen-report tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_gen_reports').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขออย่างน้อย 1 คำขอ');
                }

            });

            $('#modal_form_gen_report').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_gen_report")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application_lab_audit/gen_lab_reports') }}",
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
                            $('#modal_gen_reports').modal('hide');
                            $('#checkall').prop('checked', false);
                        }
                    }
                });

            });
            
        });


    </script>
@endpush

<div id="modal_approves" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">อนุมัติผลตรวจประเมิน</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_approve" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('report_approve', 'สถานะ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('report_approve',  App\Models\Section5\ApplicationLabStatus::whereIn('id', [9,10])->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('report_approve_description', 'รายละเอียด'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('report_approve_description', null ,  ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
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
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_approve">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            
            $('#btn_approve').click(function(event) {

                $('#myTable-Mapprove tbody').html('');
                $('#modal_form_approve').find('input,textarea').val('');
                $('#modal_form_approve').find('select').val('').select2();

                if($('.item_checkbox:checked').length > 0){
                    var status_fail = false;//true=ไม่อยู่ในเงื่อนไข
                    var tr_ = '';
                    $('.item_checkbox:checked').each(function(index, el) {

                        const announce_status = [8, 9, 10];
                        if(!announce_status.includes($(el).data('application_status'))){//ไม่อยู่ในสถานะ
                            status_fail = true;
                        }

                        tr_ += '<tr>';
                        tr_ += '<td class="text-top">'+(index+1)+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('application_no'))+'<input type="hidden" name="id[]" class="item_m_ap_id" value="'+($(el).val())+'"> </td>';
                        tr_ += '<td class="text-top">'+($(el).data('lab_name'))+'<br>'+($(el).data('applicant_name'))+'</td>';
                        tr_ += '<td class="text-top">'+($(el).data('applicant_taxid'))+'</td>';
                        tr_ += '</tr>';

                    });

                    if(status_fail){//สถานะไม่เป็นไปตามเงื่อนไข
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกรายการ',
                            html: '<h5>ที่มีสถานะ อยู่ระหว่างการพิจารณาอนุมัติ, อนุมัติ อยู่ระหว่างเสนอคณะอนุกรรมการ <br>หรือ ไม่อนุมัติ ตรวจสอบอีกครั้ง</h5>',
                            footer: '<h5>ตรวจสอบใหม่อีกครั้ง</h5>',
                            confirmButtonText: 'รับทราบ',
                            width:500
                        });
                        return false;
                    }

                    $('#myTable-Mapprove tbody').html(tr_);

                    //เปิด Modal บันทึกข้อมูล
                    $('#modal_approves').modal('show');
                }else{
                    alert('กรุณาเลือกรายการคำขออย่างน้อย 1 คำขอ');
                }

            });

            $('#modal_form_approve').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_approve")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/application_lab_audit/update_lab_approve') }}",
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
                            $('#modal_approves').modal('hide');
                            $('#checkall').prop('checked', false);
                        }
                    }
                });

            });


            
            $('#btn_save_approve').click(function (e) {
                $('#modal_form_approve').submit();   
            })
        });

    </script>
@endpush
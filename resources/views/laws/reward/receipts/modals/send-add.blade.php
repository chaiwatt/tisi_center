 

<div class="modal fade" id="SendAddModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="SendAddModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > 
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                 <h4 class="modal-title" id="SendAddModalLabel1">แนบหลักฐานแล้ว</h4>
             </div>
             <div class="modal-body ">
                <form id="form_sends" class="form-horizontal"  method="post" >

                      {{ csrf_field() }}
                      <input type="hidden" id="receipts_id" name="receipts_id" >
                     <div class="form-group m-0"  >
                        <label class="control-label col-md-4  font-medium-6 required"> หลักฐานใบสำคัญรับ (ลงชื่อ)  :</label>
                        <div class="col-md-7">
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                <div class="form-control " data-trigger="fileinput" >
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                    <span class="input-group-text btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="attach" accept=".pdf"  id="attach" required class="check_max_size_file">
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-0"  >
                        <label class="control-label col-md-4  font-medium-6 ">หมายเหตุ  :</label>
                        <div class="col-md-7">
                            <textarea  name="send_remark" id="send_remark"  class="form-control" rows="3"></textarea> 
                        </div>
                    </div>
                    <br>
                     <div class="text-center ">
                        <button type="button"class="btn btn-primary"  id="save_form"><i class="icon-check"></i> บันทึก</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                        </button>
                    </div>
                </form>
             </div>
         </div>
     </div>
 </div>

 @push('js')
 
    <script>
          $(document).ready(function () {
                $("body").on("click", "#save_form", function() {
                    $('#form_sends').submit();
                });

                $('#form_sends').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
                })  .on('form:submit', function() {

                    // Text
                    $.LoadingOverlay("show", {
                                        image       : "",
                                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                                        });
                    var formData = new FormData($("#form_sends")[0]);
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('id', $('#receipts_id').val());

                        var attach = $('#attach').prop('files')[0];
                        if( checkNone(attach) ){
                            formData.append('attach', $('#attach')[0].files[0]); 
                        }
                    
                    $.ajax({
                        type: "POST",
                        url: "{{  url('law/reward/receipts/update_receipts') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {
                            if (msg != "") {
                            $.LoadingOverlay("hide");
                            $('#form_sends').find('ul.parsley-errors-list').remove();
                            $('#form_sends').find('input,textarea').removeClass('parsley-success');
                            $('#form_sends').find('input,textarea').removeClass('parsley-error');
                            if (msg.message == true) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'บันทึกเรียบร้อย',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                table.draw(); 
                                $('#SendAddModals').modal('hide');
                                $('#receipts_id,#send_remark').val('');
                            }else{ 
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                 $('#SendAddModals').modal('hide');
                                 $('#receipts_id,#send_remark').val('');
                            }


                            }   
                        }
                    });
                   return false;
            });

        });
              
        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>
@endpush
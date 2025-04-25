

 
<div class="modal fade" id="WithdrawsModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="AssignModalLabel1">บันทึกผลการเบิกเงินรางวัล</h4>
            </div>
            <div class="modal-body">
                <form id="form_withdraws" class="form-horizontal"  method="post" >

                    {{ csrf_field() }}

                    <input type="hidden" id="withdraws_id" name="withdraws_id" >

                    <div class="form-group">
                        {!! HTML::decode(Form::label('approve_date', 'วันที่อนุมัติเบิกจ่าย'.' <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label  text-right'])) !!}
                        <div class="col-md-3">
                            <div class="inputWithIcon">
                                {!! Form::text('approve_date',null, ['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'autocomplete' => 'off','id'=>'approve_date', 'required' => true] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </div>
             
                    <div class="form-group">
                        {!! HTML::decode(Form::label('end_date', 'หลักฐานการอนุมัติเบิกจ่าย', ['class' => 'col-md-4 control-label  text-right'])) !!}
                        <div class="col-md-6">
                               <div class="fileinput fileinput-new input-group " data-provides="fileinput"  id="div_attach">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="attach"  accept=".jpg,.png,.pdf" id="attach" class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists delete-exists" data-dismiss="fileinput">ลบ</a>
                               </div>
                               <span id="span_attach"></span>
                        </div>
                    </div>
                
                    <div class="form-group">
                        {!! HTML::decode(Form::label('approve_remark', 'หมายเหตุ', ['class' => 'col-md-4 control-label  text-right'])) !!}
                        <div class="col-md-6">
                             {!! Form::textarea('approve_remark', null, ['class' => 'form-control ','id' =>'approve_remark', 'rows'=>'3']); !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! HTML::decode(Form::label('end_date', 'แจ้งเตือนไปยังเมล', ['class' => 'col-md-4 control-label  text-right'])) !!}
                        <div class="col-md-6">
                            <div class="checkbox checkbox-warning">
                                <input id="approve_status" name="approve_status"   type="checkbox"  value="1"  >
                                <label for="approve_status"> ผู้สิทธิ์ได้รับเงินรางวัล </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('', '', ['class' => 'col-md-4 control-label  text-right'])) !!}
                        <div class="col-md-6">
                             <input type="text" value="" data-role="tagsinput"  name="approve_emails"  id="approve_emails"  /> 
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit"class="btn btn-primary" id="button_save"><i class="icon-check"></i> บันทึก</button>
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
        <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#attach').change( function () {
                var fileExtension = ['jpg','png','pdf'];
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1 && $(this).val() != '') {
                    alert("ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .jpg,.png,.pdf");
                    this.value = '';
                return false;
                }
            });

 

              $('#approve_status').on('click', function(e) {
                    if($(this).is(':checked',true)){
                         $('#approve_emails').tagsinput('add', $(this).data('emails')); 
                    } else {
                        $('#approve_emails').tagsinput('removeAll'); 
                    }
                });

            // มอบหมาย
            $('#form_withdraws').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {

                   // Text
                   $.LoadingOverlay("show", {
                                        image       : "",
                                        text  : "กำลังบันทึก กรุณารอสักครู่..."
                                      });
                     var formData = new FormData($("#form_withdraws")[0]);
                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('id', $('#withdraws_id').val());

                        var attach = $('#attach').prop('files')[0];
                            if( checkNone(attach) ){
                                formData.append('attach', $('#attach')[0].files[0]); 
                            }
                     
                    $.ajax({
                        type: "POST",
                        url: "{{  url('law/reward/withdraws/update_withdraws') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {
                            if (msg != "") {
                             $.LoadingOverlay("hide");
                            $('#form_withdraws').find('ul.parsley-errors-list').remove();
                            $('#form_withdraws').find('input,textarea').removeClass('parsley-success');
                            $('#form_withdraws').find('input,textarea').removeClass('parsley-error');
                            if (msg.message == true) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'บันทึกเรียบร้อย',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                table.draw(); 
                                $('#WithdrawsModals').modal('hide');
                                $('#withdraws_id,#approve_date,#approve_remark,#approve_emails').val('');
                                $("#approve_status").prop('checked', false);
                                $("#div_attach").show(); 
                                $('#span_attach').html('');
                            }else{ 
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#WithdrawsModals').modal('hide');
                                $('#withdraws_id,#approve_date,#approve_remark,#approve_emails').val('');
                                $("#approve_status").prop('checked', false);
                                $("#div_attach").show(); 
                                $('#span_attach').html('');
                            }
   

                            }   
                        }
                    });


                     
                //  $.ajax({
                //     method: "post",
                //     url: "{{ url('law/reward/withdraws/update_withdraws') }}",
                //     data: {
                //         "_token": "{{ csrf_token() }}",
                //         "approve_date": $('#approve_date').val(), 
                //         "withdraws_id": $('#withdraws_id').val(),
                //         "approve_status": $('#approve_status:checked').val(),
                //         "approve_remark": $('#approve_remark').val(),
                //         "approve_emails": $('#approve_emails').val()
                //     }
                // }).success(function (msg) {

                //     $('#form_withdraws').find('ul.parsley-errors-list').remove();
                //     $('#form_withdraws').find('input,textarea').removeClass('parsley-success');
                //      $('#form_withdraws').find('input,textarea').removeClass('parsley-error');
                //     if (msg.message == true) {
                //         Swal.fire({
                //             position: 'center',
                //             icon: 'success',
                //             title: 'บันทึกเรียบร้อย',
                //             showConfirmButton: false,
                //             timer: 1500
                //         });
                //            table.draw(); 
                //         $('#WithdrawsModals').modal('hide');
                //         $('#withdraws_id,#approve_date,#approve_remark,#approve_emails').val('');
                //         $("#approve_status").prop('checked', false);
                //          $("#div_attach").show(); 
                //          $('#span_attach').html('');
                //     }else{ 
                //         Swal.fire({
                //             position: 'center',
                //             icon: 'error',
                //             title: 'เกิดข้อผิดพลาด',
                //             showConfirmButton: false,
                //             timer: 1500
                //         });
                //         $('#WithdrawsModals').modal('hide');
                //         $('#withdraws_id,#approve_date,#approve_remark,#approve_emails').val('');
                //         $("#approve_status").prop('checked', false);
                //         $("#div_attach").show(); 
                //         $('#span_attach').html('');
                //     }
                // });

                return false;
            });

        });

    </script>
@endpush

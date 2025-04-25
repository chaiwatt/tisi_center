{!! Form::open(['url' => 'law/cases/forms/save_additionals', 'id' => 'additionalsForm']) !!}   
<div class="modal fade text-left" id="modal-file"   data-bs-backdrop="static" data-bs-keyboard="false"  aria-hidden="true">
    <div class="modal-dialog  modal-xl"  >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    แนบไฟล์เพิ่มเติม
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </h4>
            </div>
            <div class="modal-body">
           
@php
    $file_image_cover_max = HP::get_upload_max_filesize('15MB');
@endphp
<input type="hidden" id="forms_id" name="forms_id" >
<h5 style="text-muted">อัพโหลดได้เฉพาะไฟล์ .jpg .png หรือ .pdf ขนาดไฟล์ละไม่เกิน {{$file_image_cover_max}}</h5>

<div class="row repeater-form" id="div_attach">
    <div class="col-md-12" data-repeater-list="additionals">
        <span id="id-file"></span>
        <div class="row  input_show_file" data-repeater-item>
            {!! HTML::decode(Form::label('personfile', 'เอกสารเพิ่มเติม'.'<br/><span class="font_size">(เพิ่มได้ไม่เกิน 5 ไฟล์)</span>', ['class' => 'col-md-3 control-label personfile-label label-height','style'=>'text-align: left !important'])) !!}
            <div class="col-md-3">
                {!! Form::text('file_documents', null , ['class' => 'form-control file_documents' , 'placeholder' => 'ชื่อเอกสาร']) !!}
            </div>
            <div class="col-md-5">
                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                    <div class="form-control" data-trigger="fileinput">
                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">เลือกไฟล์</span>
                        <span class="fileinput-exists">เปลี่ยน</span>
                        <input type="file" name="additionals_file" id="additionals_file"  required class="evidence_file_config" max-size="{{ $file_image_cover_max }}">
                    </span>
                    <a href="#" class="input-group-addon btn btn-default fileinput-exists fileinput_exists" data-dismiss="fileinput">ลบ</a>
                </div>
            </div>
            <div class="col-md-1   ">
                <button class="btn btn-danger btn-outline btn_file_remove remove" data-repeater-delete type="button">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>


   </div>
   <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-11"></div>
            <div class="col-md-1 text-center ">
                <button type="button" id="add_file_other" class="btn btn-success btn-outline" data-repeater-create><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
</div>
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-additionals">บันทึก</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@push('js')
    <script>
        $(document).ready(function() {
            $('#save-additionals').on('click', function () {
                $('#additionalsForm').submit();
             });
         
              $('#additionalsForm').parsley().on('field:validated', function() {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                })
                .on('form:submit', function() {
                    
                            $.LoadingOverlay("show", {
                                image       : "",
                                text  : "กำลังบทึกข้อมูล กรุณารอสักครู่..."
                            });
                          var formData = new FormData($("#additionalsForm")[0]);
                             formData.append('_token', "{{ csrf_token() }}");
                             formData.append('id', $('#forms_id').val());

                            $.ajax({
                                url: $('#additionalsForm').prop('action'),
                                type: 'POST',
                                datatype: "script",
                                data: formData,
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function success(response) {

                                     table.draw();
                                     $.LoadingOverlay("hide");
                                    $('#modal-file').modal('hide');

                                    // sweetalert
                                    Swal.fire({
                                        icon: 'success',
                                        title: "บันทึกสำเร็จ",
                                        text: "ข้อมูลโครงการถูกบันทึกเรียบร้อยแล้ว",
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });
                                },
                                error: function error(err) {
                                    $.LoadingOverlay("hide");
                                    Swal.fire({
                                        title: 'บันทึกล้มเหลว!',
                                        text: 'เกิดข้อผิดพลาดในการบันทึกมูล',
                                        icon: 'error',
                                        customClass: {
                                            confirmButton: 'btn btn-info'
                                        }
                                    });
                                }
                            });
                    return false; // Don't submit form for this demo
                });

 



         
            $('.evidence_file_config').change(function (e) {

                var result = true;

                if(!!$(this).val()){
                    var max_size = "{{ ini_get('upload_max_filesize') }}";
                    var res = max_size.replace("M", "");
                    var filesize = this.files[0].size;
                    var fileName = $(this).val();
                    var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
                        res = $(this).attr('max-size')!=undefined ? parseInt($(this).attr('max-size')) : res ; //ถ้ามีกำหนดขนาดไฟล์ที่อัพโหลดได้โดยเฉพาะ
                        console.log(res);
                        var size = (this.files[0].size)/1024/1024 ; // หน่วย MB
                    if(size > res ){
                        Swal.fire(
                                'ไฟล์ขนาดต้องไม่เกิน '+res+' MB',
                                '',
                                'info'
                            );
                        $(this).val('');
                        $(this).next(".custom-file-label").html('Choose file')

                        result = false;
                    }
                      /* ตรวจสอบนามสกุลไฟล์ */
                    if($(this).attr('accept')!=undefined){//ถ้ากำหนดนามสกุลไฟล์ที่อัพโหลดได้ไว้
                        let accepts = $(this).attr('accept').split(',');
                        let names  = this.files[0].name.split('.');//ชื่อเต็มไฟล์
                        let ext    = names.at(-1);//นามสกุลไฟล์
                        let result = false;
                        $.each(accepts, function(index, accept) {
                            if('.'+ext==$.trim(accept)){
                                result = true;
                                return false;
                            }
                        });
                        if(result===false){
                            Swal.fire(
                                'อนุญาตให้อัพโหลดไฟล์นามสกุล '+accepts+' เท่านั้น',
                                '',
                                'info'
                            );
                             $(this).val('');
                             $(this).next(".custom-file-label").html('Choose file');
                            return false;
                        }
                    }
                    
 
                }

                return result;

            });

      

            $('.repeater-form').repeater({
                show: function () {
                    $(this).slideDown();
                    resetOrderNoFile();
                    var all_row = $('.btn_file_remove').length;
                    if(all_row > 5){
                        Swal.fire(
                                "กรุณาเลือก เอกสารเพิ่มเติม ไม่เกิน 5 แถว",
                                '',
                                'info'
                            );
                        $(this).remove();
                    }
                },
                hide: function (deleteElement) {
                    Swal.fire({
                        title: 'คุณต้องการลบแถวนี้ ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ตกลง',
                        cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.value) {
                                var id =  $(this).find('.confirmation').data('id')
                                 if(checkNone(id)){
                                        $.ajax({
                                            url:"{{ url('/law/cases/forms/delete_file_additionals') }}" ,
                                            data: { id:id},
                                            type: 'GET',
                                        }).done(function(  object ) {  
                                            
                                        });
                                 }
                               $(this).slideUp(deleteElement); 
                                setTimeout(function(){
                                    resetOrderNoFile();
                                }, 400);
                            }
                     })
                    
                    // if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    //     $(this).slideUp(deleteElement);
                    //     setTimeout(function(){
                    //         resetOrderNoFile();
                    //     }, 400);
                    // }
                }
            });
        });

        
        function countFiveFile(addElement){

            var all_row = $('.input_show_file').length;
            if(all_row > 5){
                Swal.fire(
                            "กรุณาเลือก เอกสารเพิ่มเติม ไม่เกิน 5 แถว",
                            '',
                            'info'
                        );
                $(this).slideUp(addElement);
                return false;
            }

        }

        function resetOrderNoFile(){
            if($('.btn_file_remove').length >= 2){
                $('.btn_file_remove').show();
           
            }else{
                $('.btn_file_remove').hide();
            }
            $('.personfile-label:eq(0)').html('เอกสารเพิ่มเติม');
            $('.personfile-label:eq(1), .personfile-label:eq(2), .personfile-label:eq(3), .personfile-label:eq(4)').html(''); 
        } 
    </script>
@endpush

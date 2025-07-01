
<div id="modal_request_isbn" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">ขอเลข ISBN <span id="request_status"></span> </h4>
            </div>
            <div class="modal-body form-horizontal">
                
                <form id="modal_form_request_isbn" enctype="multipart/form-data" class="form-horizontal" action="{{ url('certify/standards/cover_pdf') }}">
                    <input type="hidden" name="standard_id" id="std_id">
                    <div class="row">
                        <!-- Tis Type -->
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group required">
                                    <label for="tistype" class="control-label text-right col-md-3">Tis Type:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="tistype" id="tistype" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tis No -->
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group required">
                                    <label for="tisno" class="control-label text-right col-md-3">Tis No:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="tisno" id="tisno" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tis Name -->
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group required">
                                    <label for="tisname" class="control-label text-right col-md-3">Tis Name:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="tisname" id="tisname" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Page -->
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group required">
                                    <label for="page" class="control-label text-right col-md-3">Page:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="page" id="page" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group required">
                                    <label for="cover_file" class="control-label text-right col-md-3">Cover:</label>
                                    <div class="col-md-8">
                                        <div id="box-isbn_file"></div>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput" id="cover_file_container">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="cover_file" id="cover_file" accept="image/jpeg,image/png" required>
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ISBN By -->
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="isbn_by" class="control-label text-right col-md-3">ผู้ขอ: </label>
                                    <div class="col-md-8">
                                         <label for="isbn_by" class="control-label text-right ">{{ e(Auth::user()->reg_fname . ' ' . Auth::user()->reg_lname) }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="id" id="standard_id" value="{{ $id ?? '' }}">
                        <input type="hidden" name="submit" id="standard_pdf" value="request_isbn">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn waves-effect waves-light" id="btn_request_isbn" >บันทึก</button>
                <button type="button" class="btn btn-danger btn waves-effect waves-light" id="btn_close_isbn" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#btn_request_isbn').click(function (e) { 
                 
                //  console.log("aha");
                SaveIsbn(); 
          
            });
        
            $('body').on('click', '.btn_request_isbn', function () {
                var id = $(this).data('id');
                // var isbn_req_count = $(this).data('isbn_req_count');
                // console.log(isbn_req_count)

                // if(isbn_req_count != 0 )
                // {
                //      Swal.fire({
                //         icon: 'info',
                //         title: 'อยู่ระหว่างการขอเลข ISBN',
                //     });
                //     return ;
                // }
                
                if( checkNone(id) ){
                    LoadDataisbn(id);
                    $('#modal_request_isbn').modal('show');
                  $('#std_id').val(id)
                }

            });

        });

        function SaveIsbn(){
    
            var tistype = $('#tistype').val();
            var tisno = $('#tisno').val();
            var tisname = $('#tisname').val();
            var page = $('#page').val();
            var coverFile = $('#cover_file')[0].files[0];

            // ตรวจสอบฟิลด์ที่ต้องกรอก
            if (!tistype || !tisno || !tisname || !page) {
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    showConfirmButton: true
                });
                return false;
            }

            // ตรวจสอบว่า page เป็นตัวเลขและมากกว่า 0
            if (isNaN(page) || page <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'จำนวนหน้าต้องเป็นตัวเลข',
                    showConfirmButton: true
                });
                return false;
            }

            // ตรวจสอบไฟล์
            if (!coverFile) {
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณาเลือกไฟล์ปก',
                    showConfirmButton: true
                });
                return false;
            }

            // ตรวจสอบประเภทไฟล์
            // var allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            // if (!allowedTypes.includes(coverFile.type)) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'ไฟล์ต้องเป็น JPG, PNG หรือ PDF เท่านั้น',
            //         showConfirmButton: true
            //     });
            //     return false;
            // }
                


                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });
                var formData = new FormData($("#modal_form_request_isbn")[0]);
                    formData.append('_token', "{{ csrf_token() }}")

                    $.ajax({
                        method: "POST",
                        url: "{{ url('/certify/isbn/upload') }}",
                        data: formData,
                        async: true, // เปลี่ยนเป็น true เพื่อประสิทธิภาพ
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (obj) {
                            console.log(obj); // เก็บไว้สำหรับ debug
                            if (obj.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'บันทึกสำเร็จ !',
                                    text: obj.message || 'The request created successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#modal_form_request_isbn')[0].reset(); // รีเซ็ตฟอร์ม
                                $('#modal_request_isbn').modal('hide');
                            } else if (obj.status === 'error') {
                                // จัดการข้อความ error
                                let errorMessage = 'เกิดข้อผิดพลาด';
                                if (obj.message && typeof obj.message === 'object') {
                                    // ดึงข้อความจาก object message (เช่น message.request_no)
                                    errorMessage = Object.values(obj.message).flat().join(' ');
                                } else if (obj.message) {
                                    errorMessage = obj.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: errorMessage,
                                    showConfirmButton: true
                                });
                            } else {
                                // กรณี status ไม่ใช่ success หรือ error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถประมวลผลคำร้องได้',
                                    showConfirmButton: true
                                });
                            }
                            $.LoadingOverlay("hide");
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้: ' + (xhr.statusText || 'Unknown error'),
                                showConfirmButton: true
                            });
                            $.LoadingOverlay("hide");
                        }
                    });

                // $.ajax({
                //     method: "POST",
                //     url: "{{ url('/certify/isbn/upload') }}",
                //     data: formData,
                //     async: false,
                //     cache: false,
                //     contentType: false,
                //     processData: false,
                //     success : function (obj){
                //         console.log(obj)
                //         // if (obj.msg == "success") {                      

                //             Swal.fire({
                //                 icon: 'success',
                //                 title: 'บันทึกสำเร็จ !',
                //                 showConfirmButton: false,
                //                 timer: 1500
                //             });
                    
                //             $.LoadingOverlay("hide");
                //             $('#modal_request_isbn').modal('hide');
                //         // }
                //     }
                // });


        }


        function LoadDataisbn(id){

            $('#modal_request_isbn').find('input').val('');
            $('#box-isbn_file').html('');

            $.LoadingOverlay("show", {
                image       : "",
                text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
            });

            $.ajax({
                url: "{!! url('/certify/standards/load-isbn-req-info') !!}" + "/" + id
            }).done(function( object ) {

                
                if(object.isbnRequest != null){
                    $('#tistype').val(object.isbnRequest['tistype']);
                    $('#tisno').val(object.isbnRequest['tisno']);
                    $('#tisname').val(object.isbnRequest['tisname']);
                    $('#page').val(object.isbnRequest['page']);
                }
                if(object.status_text != ""){
                    $('#request_status').text("(" + object.status_text + ")");
                }
                

            
      

                    $.LoadingOverlay("hide");

            });
        }

        function submit_form(status) {

            $('#standard_pdf').val(status);
            if(status  == 'print'){
                var url = "{!! url('certify/standards/cover_pdf') !!}"
                    url += "?isbn_no=" + $('#isbn_no').val();
                    url += "&id=" + $('#standard_id').val();
                    window.open(url, '_blank');
            }else{
                $('#modal_form_request_isbn').attr('target', '');
                $('#modal_form_request_isbn').submit();
            }
        }

        
    </script>
@endpush
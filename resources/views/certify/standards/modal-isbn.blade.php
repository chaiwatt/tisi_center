<div id="modal_isbn" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">เลข ISBN</h4> 
            </div>
            <div class="modal-body form-horizontal">
                <form id="modal_form_isbn" enctype="multipart/form-data" class="form-horizontal"  action="{{ url('certify/standards/cover_pdf') }}">
                <div class="row">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group ">
                                {!! Form::label('', 'ไฟล์หน้าปก', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    <button class="btn" style="background-color:white" name="submit" type="button" value="print" id="print" onclick="submit_form('print')">
                                        <i class="glyphicon glyphicon-download-alt fa-2x icon_print" style="top:-5px; color:#0000FF"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group required">
                                {!! Form::label('isbn_no', 'เลข ISBN:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('isbn_no', null, ['class' => 'form-control','id'=>'isbn_no','required'=>'required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group required">
                                {!! Form::label('isbn_issue_at', 'วันที่ออกให้', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::text('isbn_issue_at', null,  ['class' => 'form-control mydatepicker','id'=>'isbn_issue_at','required'=>'required']) !!}
                                        <span class="input-group-addon"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('isbn_file', 'หลักฐานไฟล์แนบ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    <div id="box-isbn_file"></div>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput" id="isbn_file">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">เลือกไฟล์</span>
                                            <span class="fileinput-exists">เปลี่ยน</span>
                                            {!! Form::file('isbn_file', null, ['required']) !!}
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                   <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('isbn_by', 'ผู้ดำเนินการ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('isbn_by', null, ['class' => 'form-control isbn_by','disabled' => true ,'id'=>'isbn_by' ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {!! Form::label('isbn_at', 'วันที่ดำเนินการ:', ['class' => 'control-label text-right col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('isbn_at', null, ['class' => 'form-control','disabled' => true,'id'=>'isbn_at' ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::hidden('id', null, ['class' => 'form-control','id'=>'standard_id' ]) !!}
                </div>
                <input type="hidden" name="submit"  id="standard_pdf">
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_isbn">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" id="btn_close_isbn" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            $('#btn_save_isbn').click(function (e) { 
                SaveIsbn(); 
          
            });
        
            $('body').on('click', '.btn_edit_isbn', function () {
                var id = $(this).data('id');
                if( checkNone(id) ){
                    LoadDataisbn(id);
                    $('#modal_isbn').modal('show');
                  
                }

            });

        });

        function SaveIsbn(){
    
            var isbn_no = $('#isbn_no').val();
            var isbn_issue_at = $('#isbn_issue_at').val();

            if(checkNone(isbn_no) && checkNone(isbn_issue_at)){

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });
                var formData = new FormData($("#modal_form_isbn")[0]);
                    formData.append('_token', "{{ csrf_token() }}")

                $.ajax({
                    method: "POST",
                    url: "{{ url('/certify/standards/update_isbn') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj.msg == "success") {                      

                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                    
                            $.LoadingOverlay("hide");
                            $('#modal_isbn').modal('hide');
                        }
                    }
                });
            }else{
                alert('กรุณากรอกข้อมูลให้ครบถ้วน ?');
            }

        }


        function LoadDataisbn(id){

            $('#modal_isbn').find('input').val('');
            $('#box-isbn_file').html('');

            $.LoadingOverlay("show", {
                image       : "",
                text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
            });

            $.ajax({
                url: "{!! url('/certify/standards/load_data_isbn') !!}" + "/" + id
            }).done(function( object ) {
            
                if( checkNone(object) ){
        
                    if( checkNone(object.isbn_file) ){
                        var html  = '<a href="'+object.isbn_file+'" target="_blank">'+object.isbn_file_extension+'</a>';
                            $('#box-isbn_file').append(html);
                            $('#isbn_file').hide();
                    }else{
                        $('#isbn_file').show();

                    }
                    $('#standard_id').val(object.id);
                    $('#isbn_no').val(object.isbn_no);
                    $('#isbn_issue_at').val(object.isbn_issue_at);
                    $('#isbn_by').val(object.isbn_by);
                    $('#isbn_at').val(object.isbn_at);

                    $.LoadingOverlay("hide");

                }

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
                $('#modal_form_isbn').attr('target', '');
                $('#modal_form_isbn').submit();
            }
        }

        
    </script>
@endpush
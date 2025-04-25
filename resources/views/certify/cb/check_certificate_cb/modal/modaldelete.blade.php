<!-- Modal เลข 4 Delete -->
<div class="modal fade modalDelete" id="modalDelete{{$id}}" tabindex="-1" role="dialog" aria-labelledby="addBrand">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">ยกเลิกคำขอ</h4>
            </div>
            {!! Form::open(['url' => 'certify/check_certificate-cb/update_delete', 
                            'class' => 'form-horizontal',
                            'files' => true]) !!}

            <div class="modal-body">
                <input  type="hidden" name="del_id"  value="{{ $id ?? null}}">
                {{-- <input  type="hidden" name="token"  value="{{ $token ?? null}}"> --}}
                <label for="desc_delete"><span class="text-danger">*</span> ระบุเหตุผล :</label>
                <textarea name="desc_delete" id="desc_delete" cols="30" rows="5" class="form-control" required></textarea>
                <div class="clearfix"></div>
                <div class="col-md-12  form-group" style="margin-bottom: 10px;margin-top: 50px">
                    {!! Form::label('attach_files_del', 'ไฟล์แนบอื่นๆ:', ['class' => 'm-t-5']) !!}
                    <button type="button" class="btn btn-sm btn-success m-l-10" id="attach_add{{$id}}">
                        <i class="icon-plus"></i>&nbsp;เพิ่ม
                    </button>
                    <div class="clearfix"></div>
                    <div id="attach_files_box{{$id}}">
                        <div class="form-group attach_files_del_list{{$id}}">
                            <div class="col-md-4">
                                {!! Form::text('attach_files_del_name[]', null, ['class' => 'form-control m-t-10', 'placeholder' => 'ชื่อไฟล์']) !!}
                            </div>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new input-group m-t-10" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                            {!! Form::file('attach_files_del[]', null) !!}
                                        </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                {!! $errors->first('attach_files_del', '<p class="help-block">:message</p>') !!}

                            </div>
                            <div class="col-md-2 text-left m-t-15" style="margin-top: 3px">
                                <div class="box_button_del_attach{{$id}}"></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-success" >บันทึก</button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>



    @push('js')
        <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
        <script>
            $(document).ready(function () {
                //เพิ่มไฟล์แนบ
                $('#attach_add{{$id}}').click(function(event) {

                    $('.attach_files_del_list{{$id}}:first').clone().appendTo('#attach_files_box{{$id}}');

                    $('.attach_files_del_list{{$id}}:last').find('input').val('');
                    $('.attach_files_del_list{{$id}}:last').find('a.fileinput-exists').click();
                    $('.attach_files_del_list{{$id}}:last').find('a.view-attach').remove();
                    $('.attach_files_del_list{{$id}}:last').find('.box_button_del_attach{{$id}}').html('<button class="btn btn-danger btn-sm attach_remove{{$id}}" type="button"> <i class="icon-close"></i>  </button>');
                    ShowHideRemoveBtn();

                });

                //ลบไฟล์แนบ
                $('body').on('click', '.attach_remove{{$id}}', function(event) {
                    $(this).parent().parent().parent().remove();
                    ShowHideRemoveBtn();
                });

                ShowHideRemoveBtn();
            });

            function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ
     
                if ($('.attach_files_del_list{{$id}}').length > 1) {
                    $('.attach_remove{{$id}}').show();
                } else {
                    $('.attach_remove{{$id}}').hide();
                }

            }
        </script>

    @endpush

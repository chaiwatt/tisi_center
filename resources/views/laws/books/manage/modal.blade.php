<div class="modal fade" id="FileModals">
    <div  class="modal-dialog modal-xl"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="AssignModalLabel1">แนบไฟล์ข้อมูลห้องสมุด</h4>
            </div>
            <div class="modal-body form-horizontal">
            {!! Form::open(['url' => '/law/book/manage/save_file', 'class' => 'form-horizontal', 'files' => true]) !!}
                {{ csrf_field() }}

    
                <div class="form-group">
                    {!! Form::label('title', 'ชื่อเรื่อง ', ['class' => 'col-md-2 control-label font-medium-6']) !!}
                    <div class="col-md-9">
                        <div class="table">
                            <table class="table table-bordered"  >
                                <tbody id="table_tbody_file">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-9">
                        <small class="text-warning">อัพโหลดได้เฉพาะไฟล์ .jpg .docx .png .xlsx และ.pdf ขนาดไฟล์ละไม่เกิน 8 MB </small><span class="text-muted m-b-30 font-14"><i>(เพิ่มได้ไม่เกิน 5 ไฟล์)</i></span>
                      
                    </div>
                </div>
    
                <div class=" required{{ $errors->has('attach') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('file_book_manage', 'ไฟล์เเนบ', ['class' => 'col-md-2 control-label'])) !!}
                    <div class="col-md-10  repeater-form-file" >
                        <div class="row" data-repeater-list="repeater-attach">
                            <div class="form-group repeater_form_file4" data-repeater-item>
                                <div class="col-md-10">
                                    <div class="fileinput fileinput-new input-group " data-provides="fileinput">
                                        <div class="form-control " data-trigger="fileinput" >
                                            <span class="fileinput-filename"></span>
                                        </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="input-group-text fileinput-exists" data-dismiss="fileinput">ลบ</span>
                                            <span class="input-group-text btn-file">
                                                <span class="fileinput-new">เลือกไฟล์</span>
                                                <span class="fileinput-exists">เปลี่ยน</span>
                                                <input type="file" name="file_book_manage" class="check_max_size_file file_max" required>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success btn-sm btn-outline btn_file_add" data-repeater-create>
                                        <i class="fa fa-plus"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn_file_remove btn-outline" data-repeater-delete type="button">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <input type="hidden" name="manage_id"  id="manage_id" value="">
            <div class="form-group">
                <div class="col-md-offset-4 col-md-3">

                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> บันทึก
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {!! __('ยกเลิก') !!}
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function() {

    //เพิ่มลบไฟล์แนบ
    $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.btn_file_add').remove();
                    BtnDeleteFile();
                },
                hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);

                        setTimeout(function(){
                            BtnDeleteFile();
                        }, 200);
                }
            });
            BtnDeleteFile();
    });
    function BtnDeleteFile(){
            if( $('.btn_file_remove').length >= 2 ){
                $('.btn_file_remove').show();
            }
              $('.btn_file_remove:first').hide();
              $('.btn_file_add:first').show();
              check_max_size_file();

              
            if( $('.file_max').length >= 5 ){//เพิ่มได้ไม่เกิน 5 ไฟล์
                $('.btn_file_add:first').prop('disabled', true); 
            }else{
                $('.btn_file_add:first').prop('disabled', false); 
            }
     }

    </script>
@endpush
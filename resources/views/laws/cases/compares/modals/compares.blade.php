<div class="modal fade" id="CompareCaseModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                <h4 class="modal-title" id="CloseCaseModalLabel1">บันทึกผลยินยอมเปรียบเทียบปรับ</h4>
            </div>
            <div class="modal-body">
                <form id="form_compares" class="form-horizontal" method="post" >

                    {{ csrf_field() }}

                    <input type="hidden" id="compare_id" name="compare_id" >

                    <div class="form-group required">
                        {!! HTML::decode(Form::label('status_id', 'สถานะ', ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-4 ">
                            {!! Form::select('status_id', ['9'=>'ยินยอมเปรียบเทียบปรับ', '10'=>'ไม่ยินยอมเปรียบเทียบปรับ'],  null,  ['class' => 'form-control',  'placeholder'=>'-เลือกสถานะ-', 'required' => true,  'id'=>'status_id']) !!}
                        </div>
                    </div>

                    <div class="form-group required">
                        {!! HTML::decode(Form::label('compare_date', 'วันที่ยินยอมปรับ', ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-4">
                            <div class="inputWithIcon">
                                {!! Form::text('compare_date', null,['class' => 'form-control mydatepicker','placeholder' => 'dd/mm/yyyy', 'id'=>'compare_date'  , 'autocomplete' => 'off',  'required' => true   ] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-6">
                            <div class="checkbox checkbox-info">
                                <input type="checkbox" value="1"  name="compare_type"  id="compare_type"  >
                                <label for="compare_type"> ส่งหลักฐานกลับมายัง สมอ.  </label>
                           </div>
                        </div>
                    </div>

                    <div class="form-group row-attachs required">
                        {!! HTML::decode(Form::label('', 'หลักฐานบันทึกคำให้การ', ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-6 ">
                            <span id="span_attachs"></span>
                            <div class="fileinput fileinput-new input-group " data-provides="fileinput" id="div_attachs">
                                 <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                 </div>
                                 <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                    <input type="file" name="attachs" id="attachs"  accept=".jpg,.png,.pdf" class="check_max_size_file">
                                 </span>
                                 <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('compare_remark', 'หมายเหตุ', ['class' => 'col-md-4 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-6 ">
                            {!! Form::textarea('compare_remark', null, ['class' => 'form-control compare_remark','id' =>'compare_remark', 'rows'=>'3']); !!}
                       </div>
                    </div>
          
                    <div class="form-group ">
                        <div class="col-md-offset-4 col-md-8">
                            <p class="text-warning font-medium-6"><i>* กรณีที่ผู้กระทำผิดยินยอมเปรียบเทียบปรับ ระบบจะจัดเก็บประวัติการกระทำความผิด</i></p>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit"class="btn btn-primary"  id="save_form_compare"><i class="icon-check"></i> บันทึก</button>
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

            $("body").on("click", ".compare_case", function() {
                    
                $('#compare_id').val($(this).data('id'));


                if($(this).data('status') != ''){
                    $('button#save_form_compare').hide();
                    $('#status_id').val($(this).data('status')).select2();
                    $('#status_id,#compare_type,#compare_remark,#compare_date').attr('disabled',true);
                }else{
                    $('button#save_form_compare').show();
                    $('#status_id').val('').select2();
                    $('#status_id,#compare_type,#compare_remark,#compare_date').attr('disabled',false);
                }

                if( checkNone($(this).data('compare_type')) && $(this).data('compare_type') == '1' ){
                    $("#compare_type").prop('checked', true);
                }else{
                    $("#compare_type").prop('checked', false);
                } 

                if( checkNone($(this).data('compare_remark'))   ){
                    $("#compare_remark").val($(this).data('compare_remark'));
                } else{
                    $("#compare_remark").val('');
                } 


                if( checkNone($(this).data('compare_date'))   ){
                    $("#compare_date").val($(this).data('compare_date'));
                }else{
                    $("#compare_date").val('');
                }
                    
                compare_type();

                if( checkNone($(this).data('filename'))   ){
                    $("#div_attachs").hide();
                    $("#attachs").prop('required',false);
                    $("#span_attachs").html(' <a href="'+$(this).data('url')+'" target="_blank"> '+$(this).data('filename')+' </a>');
                }else{
                    $("#span_attachs").html('');
                }

                $('#CompareCaseModals').modal('show');
            });

            $('#form_compares').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {
                    // Text
                    $.LoadingOverlay("show", {
                        image       : "",
                        text        :   "กำลังบันทึก กรุณารอสักครู่..." 
                    });
                var formData = new FormData($("#form_compares")[0]);
                formData.append('_token', "{{ csrf_token() }}");
                if( checkNone($('#attachs').prop('files')[0]) ){
                    formData.append('attachs',$('#attachs')[0].files[0] );
                }else{
                    formData.append('attachs',"");
                }

                $.ajax({
                    method: "post",
                    url: "{{ url('law/cases/compares/save_compares') }}",
                    data: formData,
                    contentType : false,
                    processData : false,       
                }).success(function (msg) {
                    $('#form_compares').find('ul.parsley-errors-list').remove();
                    $.LoadingOverlay("hide");
                    if (msg.message == true) {

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'บันทึกเรียบร้อย',
                            showConfirmButton: false,
                            timer: 1500
                        });
                 
                        table.draw();
                        $('#CompareCaseModals').modal('hide');
                        $("select[id='status_id']").val('').change(); 
                        $("#compare_id,#compare_remark").val(''); 
                    }else{
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#CompareCaseModals').modal('hide');
                        $("select[id='status_id']").val('').change(); 
                        $("#compare_id,#compare_remark").val(''); 
                    }
                });

                return false;
            });

        });
    </script>
@endpush
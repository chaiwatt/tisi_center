<div class="modal fade" id="CloseCaseModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                 <h4 class="modal-title" id="CloseCaseModalLabel1">ปิดงานคดี</h4>
             </div>
             <div class="modal-body">
                 <form id="form_close" class="form-horizontal"  method="post" >

                    {{ csrf_field() }}

                    <input type="hidden" id="close_id" name="close_id" >
                    <input type="hidden" id="status_id" name="status_id" value="0" >

                    <div class="form-group">
                        {!! Form::label('status_show', 'ข้อมูลคดี', ['class' => 'col-md-2 control-label font-medium-6']) !!}
                        <div class="col-md-9">
                            <div class="table">
                                <table class="table table-striped"  >
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="2%">#</th>
                                        <th class="text-center" width="48%">เลขที่อ้างอิง</th>
                                        <th class="text-center" width="50%">ผู้ประกอบการ/TAXID</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table_tbody_close">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                 
                    <div class="form-group">
                        {!! Form::label('status_show', 'สถานะ', ['class' => 'col-md-2 control-label font-medium-6']) !!}
                        <div class="col-md-6">
                            {!! Form::text('status_show', 'ปิดงานคดี', ['class' => 'form-control', 'disabled' => true]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('remark', 'หมายเหตุ', ['class' => 'col-md-2 control-label font-medium-6'])) !!}
                        <div class="col-md-6 ">
                            {!! Form::textarea('remark', null, ['class' => 'form-control remark','id' =>'remark', 'rows'=>'3']); !!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-2 control-label font-medium-6']) !!}
                        <div class="col-md-6">
                            {!! Form::text('created_by_show',null, ['class' => 'form-control', 'disabled' => true ,'id'=>'created_by_show']) !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : ''}}">
                        {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            <input type="checkbox" class="check send_mail" id="send_mail"  value="1" name="send_mail" data-checkbox="icheckbox_square-green">
                                <label for="send_mail">แจ้งเตือนไปยังผู้แจ้งงานคดี</label>      
                        </div>
                    </div> 
                    <div class="form-group {{ $errors->has('mail_list') ? 'has-error' : ''}}">
                        {!! HTML::decode(Form::label('', '', ['class' => 'col-md-2 control-label'])) !!}
                            <div class="col-md-6">
                                {!! Form::text('mail_list', null,  ['class' => 'form-control tag', 'id'=>'mail_list', 'data-role' => "tagsinput", 'readonly'=>'readonly']) !!}
                            </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-md-2"></div>
                        <div class="col-md-9 ">
                            <p class="text-warning font-medium-6"><i>* หากปิดงานคดีแล้ว จะไม่สามารถดำเนินการใดๆ กับงานคดีในระบบได้</i></p>
                        </div>
                    </div>

                     <div class="text-center">
                         <button type="button"class="btn btn-primary"  id="save_form_close"><i class="icon-check"></i> บันทึก</button>
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

            $("body").on("click", "#save_form_close", function() {
                Swal.fire({
                    title: 'ยืนยันปิดงาน',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#808080',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $('#form_close').submit();
                    }
                });
            });

                             
            $('#form_close').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {

                    $.ajax({
                        method: "post",
                        url: "{{ url('law/cases/assigns/save_close_assign') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": $('#close_id').val(),
                            "status_id": $('#status_id').val(),
                            "remark": $('#remark').val(),
                            "send_mail": $('#send_mail').val(),
                            "mail_list": $('#mail_list').tagsinput('items')
                        }
                    }).success(function (msg) {

                        $('#form_close').find('ul.parsley-errors-list').remove();
                        if (msg.message == true) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'บันทึกเรียบร้อย',
                                showConfirmButton: false,
                                timer: 1500
                            });
                 
                            table.draw();
                            $('#CloseCaseModals').modal('hide');
                            $("select[id='status_id']").val('').change(); 
                            $("#close_id,#remark").val(''); 
                        }else{
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#CloseCaseModals').modal('hide');
                            $("select[id='status_id']").val('').change(); 
                            $("#close_id,#remark").val(''); 
                        }
                });

                return false;
            });

        });
    </script>
@endpush
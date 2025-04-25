<form id="form_close"  method="post" class="form-horizontal">
    <div class="modal fade" id="CloseCaseModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span> </button>
                    <h4 class="modal-title" id="CloseCaseModalLabel1">แจ้งปิดงานคดี</h4>
                </div>
                <div class="modal-body">
            

                    {{ csrf_field() }}
                    
                    <input type="hidden" id="close_id" name="close_id" >
                    <input type="hidden" id="status_id" name="status_id" value="1" >
                    <input type="hidden" id="emails" name="emails" >
                    <div class="form-group">
                        {!! HTML::decode(Form::label('', 'งานคดี' , ['class' => 'col-md-2 control-label font-medium-6  text-right'])) !!}
                        <div class="col-md-8 ">
                            <div class="table">
                                <table class="table table-striped"  >
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="2%">#</th>
                                            <th class="text-center" width="48%">เลขคดี</th>
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
                        {!! HTML::decode(Form::label('remark', 'หมายเหตุ', ['class' => 'col-md-2 control-label font-medium-6 text-right'])) !!}
                        <div class="col-md-6 ">
                            {!! Form::textarea('remark', null, ['class' => 'form-control remark','id' =>'remark', 'rows'=>'3']); !!}
                        </div>
                    </div>

                    
                    <div class="form-group">
                        {!! Form::label('created_by_show', 'วันที่บันทึก', ['class' => 'col-md-2 control-label font-medium-6']) !!}
                        <div class="col-md-6">
                            {!! Form::text('created_by_show',  auth()->user()->Fullname.' | '.HP::revertDate( date('Y-m-d'), true), ['class' => 'form-control', 'disabled' => true]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox1" type="checkbox" value="1" id="checkbox1">
                                <label for="checkbox1"> แจ้งเตือนไปยังอีเมลผู้มอบหมาย </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('', '', ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            <input type="text" value="" data-role="tagsinput"  name="email_results"  id="email_results"  /> 
                        </div>
                    </div>

                    {{--  <div class="form-group ">
                        <div class="col-md-2"></div>
                        <div class="col-md-9 ">
                           <p class="text-warning font-medium-6"><i>* หากปิดงานคดีแล้ว จะไม่สามารถดำเนินการใดๆ กับงานคดีในระบบได้</i></p> 
                            <p class="text-warning font-medium-6"><i>*แจ้งเตือนไปยัง ผก. กองกฎหมายเพื่อตรวจสอบ และ ผอ. ยืนยันการปิดงาน</i></p>
                        </div>
                    </div> --}}
                    
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button"class="btn btn-primary"  id="save_form_close"><i class="icon-check"></i> บันทึก</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ยกเลิก') !!}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('js')
    <script>
        $(document).ready(function () {

            $("body").on("click", "#save_form_close", function() {
                Swal.fire({
                    title: 'ยืนยันแจ้งปิดงานคดี !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
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
            }).on('form:submit', function() {
                // Text
                $.LoadingOverlay("show", {
                        image       : "",
                        text        :   "กำลังบันทึก กรุณารอสักครู่..." 
                    });
                var ids = [];
                //Iterate over all checkboxes in the table
                table.$('.item_checkbox:checked').each(function (index, rowId) {
                    ids.push(rowId.value);
                });

                $.ajax({
                    method: "post",
                    url: "{{ url('law/cases/operations/save_close_assign') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id":   $('#close_id').val(),
                        "ids":  ids, 
                        "status_id": $('#status_id').val(),
                        "email_results": $('#email_results').val(),
                        "remark": $('#remark').val()
                    }
                }).success(function (msg) {

                    $('#form_close').find('ul.parsley-errors-list').remove();
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
                        $('#checkall').prop('checked',false );
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
                        $('#checkall').prop('checked',false );
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
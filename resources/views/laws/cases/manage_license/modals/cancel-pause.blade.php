<form  enctype="multipart/form-data" class="form-horizontal" id="from_cancel_pause" onsubmit="return false">

    <div class="modal fade" id="CancelPauseModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div  class="modal-dialog modal-xl" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="CancelPauseModalLabel1">ยกเลิกพักใช้</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group required "  >
                        {!! Form::label('date_pause_cancel', 'วันที่ยกเลิกพักใช้', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-4">
                            <div class="inputWithIcon">
                                {!! Form::text('date_pause_cancel', null , ['class' => 'form-control mydatepicker', 'placeholder' => 'วว/ดด/ปปปป', 'autocomplete' => 'off' , 'required' => true ] ) !!}
                                <i class="icon-calender"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required">
                        {!! Form::label('remark_pause_cancel', 'หมายเหตุ', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('remark_pause_cancel', null , ['class' => 'form-control', 'required' => true, 'rows' => 4 ] ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! HTML::decode(Form::label('created_by_show', 'ผู้บันทึก', ['class' => 'col-md-3 control-label'])) !!}
                        <div class="col-md-6">
                            <p class="font-medium-6"> {{ auth()->user()->FullName.' | '.HP::DateTimeThai(date('Y-m-d H:i:s')) }}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('status_show', 'ข้อมูลคดี', ['class' => 'col-md-3 control-label']) !!}
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
                                    <tbody id="table_tbody_cancel">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="submit"class="btn btn-primary btn-sm" ><i class="icon-check"></i> บันทึก</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" aria-label="Close">
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


            // มอบหมาย
            $('#from_cancel_pause').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            })  .on('form:submit', function() {

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังบันทึก กรุณารอสักครู่..."
                });

                var formData = new FormData($("#from_cancel_pause")[0]);
                formData.append('_token', "{{ csrf_token() }}");

                $.ajax({
                        type: "POST",
                        url: "{{ url('/law/cases/manage_license/update_cancel_cancel') }}",
                        datatype: "script",
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (msg) {

                            $.LoadingOverlay("hide");

                            if(checkNone( msg ) && msg =='success' ){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'บันทึกเรียบร้อย',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#checkall').prop('checked',false );
                                table.draw();
                                $('#CancelPauseModals').modal('hide');
                            }else{
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#CancelPauseModals').modal('hide');
                            }
                
                        }
                    });

                return false;
            });


        });

    </script>
@endpush

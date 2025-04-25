<div class="modal fade" id="receiverModals">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span
                        aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="AssignModalLabel1">รับเรื่องตรวจติดตาม</h4>
            </div>
            <div class="modal-body">

                <div class="white-box">
                    <div class="container-fluid">
                        <p class="h2 text-bold-300 text-center">ยืนยันการรับเรื่องตรวจติดตามใบรับรองระบบงาน</p>
                        <p class="h2 text-bold-300 text-center"> จำนวน <span id="ids_length"></span> รายการ</p>
                    </div>
                    <div class="form-group {{ $errors->has('checkbox') ? 'has-error' : '' }}" style="padding: 3%">
                        {!! Form::label('', '', ['class' => 'col-md-1 control-label']) !!}
                        <div class="col-md-8">
                            <input type="checkbox" class="check send_mail" id="send_mail" value="1"
                                name="mail_status_diagnosis" data-checkbox="icheckbox_square-blue" checked>
                            <label for="send_mail"> แจ้งเตือนผู้รับใบรับรอง</label>

                        </div>
                    </div>
                </div>
                <input type="hidden" name="tracking_id" id="tracking_ids" value="">
                <div class="text-center">
                    <button type="button"class="btn btn-success" id="submit_receiver"><i class="icon-check"></i>
                        บันทึก</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {!! __('ยกเลิก') !!}
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
  
            $("body").on("click", "#button_receiver", function() {
                var ids = [];false
                var status = false;
                $('.item_checkbox:checked').each(function(index, element) {
                    ids.push($(element).val());
                    if($(element).data('status') != 1){//รอดำเนินการตรวจ
                        status = true;
                    }
                });

                var length = ids.length;
                if(status){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'มีบางรายการที่รับเรื่องแล้ว',
                        showConfirmButton: true,

                    });   
                }else if(length > 0 ) {
                    $("#ids_length").text(length);
                    $('#receiverModals').modal('show');

                }else{
                    $('#receiverModals').modal('hide');
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'กรุณาเลือกเลขที่อ้างอิง',
                        showConfirmButton: false,
                        timer: 1500

                    });
                }
            });


            $("body").on('click', '#submit_receiver', function() {
                var ids = [];
                var send_mail = $('#send_mail').is(":checked") ? 1 : 0;

                $('.item_checkbox:checked').each(function(index, element) {
                    ids.push($(element).val());
                });
                if (ids.length > 0) {
                    $.LoadingOverlay("show", {
                        image: "",
                        text: "กําลังบันทึก กรุณารอสักครู่..."
                    });
                    $.ajax({
                        type: "post",
                        url: "{{ url('/certificate/tracking-cb/save_receiver') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            ids: ids,
                            send_mail: send_mail
                        },
                        success: function(data) {
                            $.LoadingOverlay("hide");
                            $('#receiverModals').modal('hide');
                            $('#myTable').DataTable().draw();
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'บันทึกเรียบร้อย',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });

                }
            });


        });
    </script>
@endpush

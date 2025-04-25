<form id="modal_form_result" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">

    <div id="modal_result" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">ผลตรวจทดสอบ</h4>
                </div>
                <div class="modal-body">
                    <div class="box_result"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

</form>

@push('js')

    <script type="text/javascript">

        $(document).ready(function() {

            $('.btn_form_result').click(function (e) {

                var id = $(this).data('id');
                $('.box_result').html('');

                if(id){

                    $.LoadingOverlay("show", {
                        image : "",
                        text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                    });

                    $.ajax({
                        url: "{!! url('/section5/report-example-lab/test_result_item') !!}" + "/" + id
                    }).done(function( object ) {
                        $('.box_result').html(object);
                        $.LoadingOverlay("hide");
                        $('#modal_result').modal('show');
                    });
                }


            });

            $('#btn_save_resulte').click(function (e) {
                $('#modal_form_result').submit();
            });


            $('#modal_form_result').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_result")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/resurv/report_product/update_sample_result') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {

                            Swal.fire({
                                type: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#modal_result').modal('hide');

                        }
                    }
                });

            });
        });

    </script>
@endpush

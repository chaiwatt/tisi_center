<div id="MdScopeDtail" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ข้อมูลรายการทดสอบ</h4>
            </div>
            <div class="modal-body">
                
                <form enctype="multipart/form-data" class="form-horizontal" id="from_tools" onsubmit="return false">
                    <div id="show_box_scope_deatil"></div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('js')
    <script>
        jQuery(document).ready(function() {
            
            //Open Modal
            $('#MdScopeDtail').on('show.bs.modal', function (e) {
                reBuiltSelect2($('#show_box_scope_deatil').find('select'));

                $('.box_edit_tools').hide();
                $('.box_edit_tools').find('input').val('');
                $('.box_edit_tools').find('select').val('').trigger('change.select2');

                $('#btn_add_tools').text('เพิ่ม');
                $('#btn_add_tools').val(1);
            });

            //เพิ่ม แสดง Box Detail
            $(document).on('click', '#btn_add_tools', function () {

                var value = $(this).val();

                if( value == 1){
                    $('#btn_add_tools').text('ยกเลิกการเพิ่ม');
                    $('#btn_add_tools').val(0);
                    $('.box_edit_tools').show();
                }else{
                    $('#btn_add_tools').text('เพิ่ม');
                    $('#btn_add_tools').val(1);
                    $('.box_edit_tools').hide();

                    $('.box_edit_tools').find('input').val('');
                    $('.box_edit_tools').find('select').val('').trigger('change.select2');

                }
            });

            //ยกเลิกเพิ่มข้อมูล
            $(document).on('click', '#btn_cancel_tools', function () {
                $('.box_edit_tools').find('input').val('');
                $('.box_edit_tools').find('select').val('').trigger('change.select2');
                $('.box_edit_tools').hide();
            });

            //แก้ไข
            $(document).on('click', '.mt_edit_tools', function () {
                $('.box_edit_tools').find('input').val('');
                $('.box_edit_tools').find('select').val('').trigger('change.select2');
                $('.box_edit_tools').show();

                var id            = $(this).data('id');
                var test_tools_id = $(this).data('test_tools_id');
                var test_tools_no = $(this).data('test_tools_no');
                var capacity      = $(this).data('capacity');
                var range         = $(this).data('range');
                var true_value    = $(this).data('true_value');
                var fault_value   = $(this).data('fault_value');
                var test_duration = $(this).data('test_duration');
                var test_price    = $(this).data('test_price');

                $('#mt_id').val(id);
                $('#mt_test_tools').val(test_tools_id).trigger('change.select2');
                $('#mt_test_tools_no').val(test_tools_no);
                $('#mt_capacity').val(capacity);
                $('#mt_range').val(range);
                $('#mt_true_value').val(true_value);
                $('#mt_fault_value').val(fault_value);
                $('#mt_test_duration').val(test_duration);
                $('#mt_test_price').val(test_price);
                
            });

            $(document).on('click', '.mt_delete_tools', function () {

                if( confirm("ต้องการลบข้อมูลแถวนี้ใช่หรือไม่ ?") ){
                    var id  = $(this).data('id');
                    $.ajax({
                        url: "{!! url('/section5/labs/delete_std_tools') !!}" + "/" + id
                    }).done(function( obj ) {
                        if (obj == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            LoadDetail();
                        }
                    });
                }

            });
            

            $(document).on("keypress keyup blur",".mt_number_only",function (event) {
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });

            $('#from_tools').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var lab_scope_id = $('#input_labs_scopes_id').val();

                var formData = new FormData($("#from_tools")[0]);
                    formData.append('_token', "{{ csrf_token() }}");
                    formData.append('id', "{{ $labs->id }}");
                    formData.append('lab_scope_id', lab_scope_id );

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังบันทึก กรุณารอสักครู่..."
                });

                $.ajax({
                    method: "POST",
                    url: "{{ url('/section5/labs/save_std_tools') }}",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success : function (obj){

                        if (obj == "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            LoadDetail();
                            $.LoadingOverlay("hide");

                        }
                    }
                });
            });

        });

        function LoadDetail(){

            var id = $('#input_labs_scopes_id').val();

            $('#show_box_scope_deatil').html('');

            $.LoadingOverlay("show", {
                image       : "",
                text        : "Loading..."
            });

            $.ajax({
                url: "{!! url('/section5/labs/get-scope-detail') !!}" + "/" + id
            }).done(function( object ) {
                $('#show_box_scope_deatil').html(object);
                $.LoadingOverlay("hide", true); 
                
                reBuiltSelect2($('#show_box_scope_deatil').find('select'));

                $('.box_edit_tools').hide();
                $('.box_edit_tools').find('input').val('');
                $('.box_edit_tools').find('select').val('').trigger('change.select2');

                $('#btn_add_tools').text('เพิ่ม');
                $('#btn_add_tools').val(1);
            });

        }

        function reBuiltSelect2(select ){

            //Clear value select
            $(select).val('');
            $(select).next().remove();
            $(select).removeClass('select2-hidden-accessible');

            $(select).removeAttr('data-select2-id');
            $(select).removeAttr('tabindex');
            $(select).removeAttr('aria-hidden');
            $(select).children().removeAttr('data-select2-id');

            $(select).select2({
                dropdownAutoWidth: true,
                width: '100%'
            });


        }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
    </script>
@endpush

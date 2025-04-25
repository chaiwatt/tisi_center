<form id="modal_form_cases" enctype="multipart/form-data" class="form-horizontal repeater-file" onsubmit="return false">
    <div class="modal fade" id="OffenderCaseModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="OffenderCaseModalLabel1" aria-hidden="true">
        <div  class="modal-dialog  modal-xl" > <!-- modal-dialog-scrollable-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><i class="bx bx-x"></i></button>
                    <h4 class="modal-title" id="OffenderCaseModalLabel1"> ประวัติการกระทำความผิด</h4>
                </div>
                <div class="modal-body form-horizontal">

                    <div class="form_cases_edit"></div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</form>
@push('js')
    <script>
        $(document).ready(function () {
            
            $('#modal_form_cases').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_cases")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                $.LoadingOverlay("show", {
                    image       : "",
                    text  : "กำลังบันทึกข้อมูล กรุณารอสักครู่..."
                });
                
                $.ajax({
                    method: "POST",
                    url: "{{ url('/law/cases/offender/update_cases') }}",
                    data: formData,
                    contentType : false,
                    processData : false,
                    success : function (obj){

                        if (obj.msg == "success") {
                            table_cases.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ !',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $.LoadingOverlay("hide");
                            $('#OffenderCaseModal').modal('hide');
                        }
                    }
                });

            });



            $(document).on("click", "#btn_seleted_tis_id", function() {
                
                var std                 = $('#seleted_tis_id');

                if( checkNone(std.val()) ){

                    var tis_id          = std.val();
                    var tis_num         = std.select2('data').text;
                    var explode_tis_num = tis_num.split(':');

                    var values          =  $('.repeater-standard').find(".tis_id").map(function(){return $(this).val(); }).get();

                    if(values.indexOf(tis_id) == -1){

                        var input       =  '<input type="hidden" name="tis_id" class="tis_id" value="'+(tis_id)+'">';
                            input       += '<input type="hidden" name="tb3_tisno" value="'+( $.trim(explode_tis_num[0]) )+'">';
                            input       += '<input type="hidden" name="tis_name" value="'+( $.trim(explode_tis_num[1]) )+'">';
                            input       += '<input type="hidden" name="id" value="">';

                        var tr          = '';
                            tr          += '<tr data-repeater-item>';
                            tr          += '<td class="text-top text-center standard_no">1</td>';
                            tr          += '<td class="text-top">'+( $.trim(explode_tis_num[0]) )+'</td>';
                            tr          += '<td class="text-top">'+( $.trim(explode_tis_num[1]) )+'</td>';
                            tr          += '<td class="text-top text-center"><button class="btn btn-icon btn-danger btn-sm standard_delete" type="button" data-repeater-delete><i class="bx bx-x"></i></button>'+(input)+'</td>';
                            tr          += '</tr>';

                        $('#myTableStandard tbody').append( tr );
                    }

                    std.select2("val", '');
                    resetStandardNo();
                    
                }else{
                    alert("กรุณาเลือก มอก.");
                }

            });


        });

        function resetStandardNo(){

            $('.standard_no').each(function(index, el) {
                $(el).text(index+1);
            });

            if($('.standard_no').length > 1){
                $('.standard_delete').show();
            }else{
                $('.standard_delete').hide();
            }

            $('.repeater-standard').repeater();

        }
    </script>
@endpush



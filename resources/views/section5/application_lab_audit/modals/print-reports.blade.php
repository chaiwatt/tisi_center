<div id="modal_print_reports" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">พิมพ์รายงาน</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped m-b-0" id="myTable-Mword">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="15%" class="text-center">วันที่ประชุม</th>
                                    <th width="15%" class="text-center">ครั้งที่ประชุม</th>
                                    <th width="10%" class="text-center">พิมพ์</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $(document).on('click','.btn_print_reports', function(e) {

                var ids = [];

                var id = $(this).data('id');

                if( id != ''){

                    ids.push( id );

                    $.LoadingOverlay("show", {
                        image       : "",
                        text  : "กำลังโหลดข้อมูล กรุณารอสักครู่..."
                    });

                    $.ajax({
                        url: "{!! url('/section5/application_lab_audit/get-application-summary') !!}" + "?id=" + ids
                    }).done(function( object ) {
                        $('#myTable-Mword tbody').html(object);
                        $.LoadingOverlay("hide");
                    });

                    $('#modal_print_reports').modal('show');


                }

            });

            

        });

    </script>
@endpush
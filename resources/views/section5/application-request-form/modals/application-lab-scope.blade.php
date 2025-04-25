@push('css')
    <link href="{{asset('plugins/components/bootstrap-treeview/css/bootstrap-treeview.min.css')}}" rel="stylesheet" />
@endpush


<div id="modal_app_lab_scope" class="modal fade" role="dialog" aria-labelledby="myModalLabScopeLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">ข้อมูลขอรับบริการ</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div  class="row">
                    <div class="col-md-offset-1 col-md-11">
                        <div  class="row">
                            <p id="show_app_no_labs"></p>
                        </div>
                        <div class="row" id="show_box_scope_deatil"></div>
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
    <script src="{{asset('plugins/components/bootstrap-treeview/js/bootstrap-treeview.min.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function() {

            $(document).on('click', '.modal_show_scope', function () {

                $.LoadingOverlay("show", {
                    image: "",
                    text: "กำลังโหลด กรุณารอสักครู่..."
                });

                var id = $(this).data('id');
                var application_no = $(this).data('application_no');

                $('#show_box_scope_deatil').html('');

                $('#show_app_no_labs').text('เลขที่อ้างอิงคำขอ : '+application_no);

                $.ajax({
                    url: "{!! url('section5/application-lab-scope') !!}" + "/" +  id
                }).done(function( object ) {
                    $('#show_box_scope_deatil').treeview({
                        data: object,
                        collapseIcon:'fa fa-minus',
                        expandIcon:'fa fa-plus',
                        showBorder: false,
                        showTags: false,
                        highlightSelected: false,
                    });
                    $('#show_box_scope_deatil').treeview('expandAll', { levels: 10, silent: true });

                    $.LoadingOverlay("hide", true);  
                });

                $('#modal_app_lab_scope').modal('show');
                
            });

        });

    </script>
@endpush

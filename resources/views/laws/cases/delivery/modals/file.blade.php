<!-- /.modal-dialog -->
<div id="ModalFile" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ไฟล์จากฐานข้อมูล</h4>
            </div>
            <div class="modal-body form-horizontal">

                <div class="col-md-12">

                    <div class="form-group">
                        {!! Form::label('modal_file_search', 'คำค้น.', ['class' => 'col-md-3 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('modal_file_search', null, ['class' => 'form-control', 'placeholder'=> 'ชื่อไฟล์', 'id' => 'modal_file_search']) !!}
                        </div>
                    </div>

                </div>

                <input type="hidden" id="input_other_row" value="">

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="MyTable-File">
                            <thead>
                                <tr>
                                    <th class="text-center" width="2%">#</th>
                                    <th class="text-center" width="35%">ชื่อไฟล์</th>
                                    <th class="text-center" width="35%">คำอธิบายไฟล์</th>
                                    <th class="text-center" width="18%">วันที่</th>
                                    <th class="text-center" width="10%">เลือก</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-file">

                            </tbody>
                        </table>
                    </div>
                </div>

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
        $(document).ready(function () {
            $('body').on('click', '.btn_file_document', function(){

                var id = $(this).data('id');
                var url  = "{!! url('/funtions/get-view-file') !!}";

                var html = '<a class="btn btn-link" href="'+($(this).data('url'))+'" target="_blank">' + $(this).data('filename') + '</a>';

                var row = $('button.btn_file_law[value="'+$(this).data('row')+'"]');

                    row.closest('div.form-group').find('div.file_in_case_db').html(html);
                    row.closest('div.form-group').find('input[name*="attach_description"]').val( $(this).data('caption') );
                    row.closest('div.form-group').find('input[name*="attachfilein_id"]').val( $(this).data('id') );
                    row.closest('div.form-group').find('.input-group').hide();

                    $('#ModalFile').modal('hide');
              
            });
        });
    </script>
@endpush
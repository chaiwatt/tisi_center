<!--form Modal -->
<div class="modal fade text-left" tabindex="10" id="MFileInfo" role="dialog" aria-labelledby="MFileInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="MFileInfoLabel">Properties</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2 text-right"><b>Name:</b></div>
                            <div class="col-md-9 M_txt" id="M_name"></div>
                        </div>
                        <div class="row m_input_show">
                            <div class="col-md-2 text-right"><b>Type:</b></div>
                            <div class="col-md-9 M_txt" id="M_type"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 text-right"><b>Path:</b></div>
                            <div class="col-md-9 M_txt" id="M_path"></div>
                        </div>
                        <div class="row m_input_show">
                            <div class="col-md-2 text-right"><b>Size:</b></div>
                            <div class="col-md-9 M_txt" id="M_size"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 text-right"><b>Modified:</b></div>
                            <div class="col-md-9 M_txt" id="M_modified"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">ปิด</span></button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function () {

            $('body').on('click', '.btn_file_info', function () {

                $('.M_txt').text('');

                var type = $(this).data('type');
                var path = $(this).data('path');
                var name = $(this).data('name');
                var time = $(this).data('time');
                

                if(type == 'folder'){

                    $('.m_input_show').hide();
                    $('#M_name').text(name);
                    $('#M_path').text(path);
                    $('#M_modified').text(time);

                }else{
             
                    $('.m_input_show').show();

                    var type = $(this).data('type');
                    var size = $(this).data('size')

                    $('#M_name').text(name);
                    $('#M_type').text(type);
                    $('#M_path').text(path);
                    $('#M_size').text(size);
                    $('#M_modified').text(time);

                }
 
                $('#MFileInfo').modal('show');
            });


        });
    </script>
@endpush
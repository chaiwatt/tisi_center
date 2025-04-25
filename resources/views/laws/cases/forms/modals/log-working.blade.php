<div id="log-working-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">ประวัติแจ้งแก้ไข เลขที่อ้างอิง <span id="log-working-ref_no"></span></h4> 
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <table class="table color-bordered-table danger-bordered-table">
                        <thead>
                            <tr>
                                <th width="5%">ครั้งที่</th>
                                <th width="70%">คำอธิบาย</th>
                                <th width="10%">ไฟล์แนบ</th>
                                <th width="15%">คนพิจารณา</th>
                            </tr>
                        </thead>
                        <tbody id="log-working-tbody">
                            
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')

    <script>
        $(document).ready(function () {

            //View Log
            $(document).on('click', '.view-log-modal', function(){

                $('#log-working-modal').modal('show');
                $('#log-working-ref_no').text($(this).attr('data-ref_no'));

                $('#log-working-tbody').text('');
                let data_log_encode = $(this).attr('data-log');
                    data_log_encode = atob(data_log_encode);
                let data_logs = JSON.parse(data_log_encode);
                
                $.each(data_logs, function (index, data_log) { 

                    //ไฟล์แนบ
                    let attach = '';
                    $.each(data_log.file_attach, function (index_attach, file_attach) {
                        attach += '<a href="'+file_attach+'" target="_blank"><i class="fa fa-paperclip m-l-5 font-22" aria-hidden="true"></i></a>';
                    });
                    
                    let tr  = '<tr>';
                            tr += '<td class="text-top text-center">'+data_log.index+'</td>';
                            tr += '<td class="text-top">'+data_log.remark+'</td>';
                            tr += '<td class="text-top">'+attach+'</td>';
                            tr += '<td class="text-top">'+data_log.user_created+'</td>';
                        tr += '</tr>';
                    $('#log-working-tbody').append(tr);
                });

            });

        });
    </script>

@endpush
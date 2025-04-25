
<div class="modal fade" id="ApproveModals"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div  class="modal-dialog   modal-xl" > <!-- modal-dialog-scrollable-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" tabindex="-1" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">การพิจารณา</h4>
            </div>
            <div class="modal-body">

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="table">
                                <table class="table color-bordered-table info-bordered-table table-bordered table-sm ">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">ลำดับ</th>
                                            <th class="text-center" width="15%">ผู้มีอำนาจ</th>
                                            <th class="text-center" width="15%">ตำแหน่ง</th>
                                            <th class="text-center" width="15%">สถานะ</th>
                                            <th class="text-center" width="15%">ความคิดเห็น</th>
                                            <th class="text-center" width="10%">ไฟล์แนบ</th>
                                            <th class="text-center" width="15%">เมื่อวันที่</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_tbody_approve">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                            {!! __('ปิด') !!}
                        </button>
                    </div>
            </div>
        </div>
    </div>
</div>

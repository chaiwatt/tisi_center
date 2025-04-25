<!-- Modal เลข 4 Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="addBrand">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">
                    ยกเลิกแต่งตั้งคณะผู้ตรวจประเมิน
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 </h4>
            </div>
            {!! Form::open(['url' => 'certificate/auditor-ibs/update_delete', 
                            'class' => 'form-horizontal',
                            'id' => 'form-modal-delete',
                            'method' => 'POST',
                            'files' => true]) !!}

            <div class="modal-body">
                <label for="reason_cancel"><span class="text-danger">*</span> ระบุเหตุผล :</label>
                <textarea name="reason_cancel"  id="reason_cancel"  cols="30" rows="5" class="form-control" required></textarea>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-success" >บันทึก</button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>


 

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ยืนยันการใช้มาตรฐาน</h4>
            </div>
            <div class="modal-body">
                <p>
                    <label>{!! Form::radio('tis_force', 'ท', true, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'tis_force1']) !!} ใช้มาตรฐานเดิม</label><br/>
                    <label>{!! Form::radio('tis_force', 'บ', false, ['class'=>'check', 'data-radio'=>'iradio_square-green', 'id'=>'tis_force2']) !!} เวียนทบทวนมาตรฐาน</label>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">บันทึก</button>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade modal-upload-file" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">ไฟล์</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            {!! Form::label('upload_file', 'แนบไฟล์:', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="upload_file" id="upload_file" class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                 </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info waves-effect" id="btn_upload_file">Upload</button>
                            </div>
                        </div>
        
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="box_file"></div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('js')
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function() {


        });

    </script>
@endpush
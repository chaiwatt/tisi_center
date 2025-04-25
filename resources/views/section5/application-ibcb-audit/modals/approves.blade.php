<div id="modal_approves" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">อนุมัติผลตรวจประเมิน</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_approve" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required">
                                {!! Form::label('report_approve', 'สถานะ'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select('report_approve',  App\Models\Section5\ApplicationIbcbStatus::whereIn('id', [9,10])->pluck('title', 'id'), null, ['class' => 'form-control', 'placeholder' => '-เลือกสถานะ-', 'required' => true]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('report_approve_description', 'รายละเอียด'.' :', ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('report_approve_description', null ,  ['class' => 'form-control', 'rows' => 4]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="clearfix">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped m-b-0" id="myTable-Mapprove">
                                <thead>
                                    <tr>
                                        <td width="5%" class="text-center">#</td>
                                        <th width="20%" class="text-center">เลขที่คำขอ</th>
                                        <th width="25%" class="text-center">ผู้ยื่นคำขอ</th>
                                        <th width="25%" class="text-center">เลขผู้เสียภาษี</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_save_approve">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {
            
            $('#btn_save_approve').click(function (e) {
                $('#modal_form_approve').submit();   
            })
        });

    </script>
@endpush
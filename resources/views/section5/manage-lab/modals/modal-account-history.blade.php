<div id="MdAccount-History" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width: 1140px; max-width: 1140px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ประวัติการแก้ไขบัญชีผู้ใช้งานของห้องปฎิบัติการ</h4>
            </div>
            <div class="modal-body">

                @php
                    $historys = $labs->historys->where('data_field', 'lab_user_id');
                @endphp

                <div class="table-responsive">
                    <table class="table color-bordered-table info-bordered-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่ดำเนินการ</th>
                                <th>ผู้แก้ไข</th>
                                <th>หมายเหตุ</th>
                                <th>ชื่อผู้ใช้งานเดิม</th>
                                <th>ชื่อผู้ใช้งานใหม่</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historys as $key => $history)
                                @php
                                    $old = HP::getSsoUser($history->data_old);
                                    $new = HP::getSsoUser($history->data_new);
                                @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ HP::dateTimeFormatN($history->created_at) }}</td>
                                    <td>{{ !is_null($history->user_created) ? $history->user_created->FullName : '-' }}</td>
                                    <td>{{ $history->remark }}</td>
                                    <td>{{ !is_null($old) ? $old->username : '' }}</td>
                                    <td>{{ !is_null($new) ? $new->username : '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

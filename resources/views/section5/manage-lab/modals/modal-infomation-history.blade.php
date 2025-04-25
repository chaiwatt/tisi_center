<div id="MInfomation-History" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="width: 1140px; max-width: 1140px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">ประวัติการแก้ไขข้อมูลของห้องปฎิบัติการ</h4>
            </div>
            <div class="modal-body">

                @php
                        $columns = [
                                        "lab_name",
                                        "lab_address",
                                        "lab_building",
                                        "lab_soi",
                                        "lab_moo",
                                        "lab_phone",
                                        "lab_fax",
                                        "lab_subdistrict_id",
                                        "lab_district_id",
                                        "lab_province_id",
                                        "lab_zipcode",
                                        "lab_end_date"
                                    ];
                    $historys_infoamtion = $labs->historys->whereIn('data_field', $columns );
                @endphp

                <div class="table-responsive">
                    <table class="table color-bordered-table info-bordered-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่ดำเนินการ</th>
                                <th>ผู้แก้ไข</th>
                                <th>ชื่อข้อมูล</th>
                                <th>ข้อมูลเดิม</th>
                                <th>ข้อมูลใหม่</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($historys_infoamtion as $key => $history)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ HP::dateTimeFormatN($history->created_at) }}</td>
                                    <td>{{ !is_null($history->user_created) ? $history->user_created->FullName : '-' }}</td>
                                    <td>{{ $history->DataFieldName }}</td>
                                    <td>{!! $history->DataOldName !!}</td>
                                    <td>{!! $history->DataNewName !!}</td>
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

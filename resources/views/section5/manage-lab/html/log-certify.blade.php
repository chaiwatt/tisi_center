<div class="table-responsive">
    <table class="table color-bordered-table info-bordered-table">
        <thead>
            <tr>
                <th>#</th>
                <th>วันที่ดำเนินการ</th>
                <th>ผู้แก้ไข</th>
                <th>ใบรับรองเลขที่</th>
                <th>วันหมดอายุเดิม</th>
                <th>วันหมดอายุใหม่</th>
                <th>ขอบข่าย</th>
            </tr>
        </thead>
        <tbody>

            @foreach ( $datalog as $key => $log )

                <tr>
                    <td class="text-top">{!! $key+1 !!}</td>
                    <td class="text-top">
                        {!! !empty($log->created_at)?HP::DateThai($log->created_at):null; !!}
                    </td>
                    <td class="text-top">
                        {!! !empty($log->CreatedName)?$log->CreatedName:'-'; !!}
                    </td>
                    <td class="text-top">
                        {!! !empty($log->lab_certify)?$log->lab_certify->certificate_no:'-'; !!}
                    </td>
                    <td class="text-top">
                        {!! !empty($log->old_end_date)?HP::DateThai($log->old_end_date):null; !!}
                    </td>
                    <td class="text-top">
                        {!! !empty($log->new_end_date)?HP::DateThai($log->new_end_date):null; !!}
                    </td>
                    <td class="text-top">
                        <ol>
                            @foreach (  $log->scope_logs as  $scope_logs )

                                <li>
                                    {!! !empty($scope_logs->labs_scopes) && !empty($scope_logs->labs_scopes->test_item) ?$scope_logs->labs_scopes->test_item->ItemHtml:null  !!}
                                </li>
                                
                            @endforeach
                        </ol>
                    </td>

                </tr>
                
            @endforeach

        </tbody>

    </table>
</div>
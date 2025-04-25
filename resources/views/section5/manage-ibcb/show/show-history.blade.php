@php
    $historys_infoamtion =  $ibcb->historys;
@endphp

<div class="col-md-12 col-sm-12">
  
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
                        <td>
                            {!! $history->DataOldName !!}
                        </td>
                        <td>{!! $history->DataNewName !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <h4>{!! $title !!}</h4>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table">
                <thead>
                    <tr>
                        <th class="text-center">ชื่อข้อมูล</th>
                        <th class="text-center">ข้อมูลเดิม</th>
                        <th class="text-center">ข้อมูลใหม่</th>
                    </tr>
                </thead>
                <tbody>
                    @if( isset($data_changes) )
                        @foreach ( $data_changes as $data_change )
                            <tr>
                                <td><b>{!! $data_change['label'] !!}</b></td>
                                <td class="danger">{!! $data_change['old'] !!}</td>
                                <td class="success">{!! $data_change['new'] !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
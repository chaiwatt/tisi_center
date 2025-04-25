
@php
    $details = App\Models\Bsection5\ReportTestFactoryDetail::where('test_factory_id', $testfactory->id )->get()
@endphp

<div class="table-responsive">
    <table width="100%" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="5%">#</th>
                <th class="text-center" width="20%">วันที่ตรวจ</th>
                <th class="text-center" width="20%">ผลตรวจ</th>
                <th class="text-center" width="20%">ข้อบกหร่อง</th>
                <th class="text-center" width="20%">หมายเหตุ</th>
                <th class="text-center" width="15%">เอกสารแนบ</th>

            </tr>
        </thead>
        <tbody>
            @foreach(  $details AS $keyD => $item )
                <tr>
                    <td  class="text-center">{!! $keyD+1 !!}</td>
                    <td  class="text-center">{!! !empty($item->test_date)? HP::DateThai($item->test_date):null !!}</td>
                    <td>{!! !empty($item->test_result)? $item->test_result:null !!}</td>
                    <td>{!! !empty($item->test_defect)? $item->test_defect:null !!}</td>
                    <td>{!! !empty($item->test_description)? $item->test_description:null !!}</td>
                    <td  class="text-center">{!! !empty($item->test_result_file)? '<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>' :null !!}</td>

                </tr>
            @endforeach

            @if( count($details) == 0 )
                <tr><td class="text-center" colspan="5">ไม่พบข้อมูล</td></tr>
            @endif

        </tbody>
    </table>
</div>
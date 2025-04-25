
@php
    $i = 0;
@endphp
@foreach ( $application as $key => $item )

    <tr>
        <td class="text-top text-left" colspan="4">
            Ref: {!! $item->application_no !!} : ชื่อห้องปฎิบัติการ {!! (!empty($item->lab_name)?$item->lab_name:'-').(!empty($item->applicant_name)?' ('.$item->applicant_name.')':'-') !!}
        </td>
    </tr>

    @foreach (  $item->app_summary_detail  as $detail )

        @php
            $i++;

            $summary = $detail->app_summary;
        @endphp

        <tr>
            <td>
                {!! $i !!}
            </td>
            <td class="text-top text-center">{!! HP::DateThai($summary->meeting_date,true) !!}</td>
            <td class="text-top text-center">{!! $summary->meeting_no !!}</td>
            <td class="text-top text-center">
                <a href="{!! url('section5/application_lab_audit/word?id='.$detail->id) !!}" class="btn btn-success btn-xs waves-effect waves-light"  target=”_blank” data-toggle="tooltip" data-placement="top" title="พิมพ์">พิมพ์</a>
            </td>
        </tr>
        
    @endforeach

@endforeach
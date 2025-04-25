@extends('layouts.master')

@push('css')
    <style>

    /*
    Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
    */
    @media
      only screen
      and (max-width: 760px), (min-device-width: 768px)
      and (max-device-width: 1024px)  {

        /* Force table to not be like tables anymore */
        table, thead, tbody, th, td, tr {
            display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            margin: 0 0 1rem 0;
        }

        tr:nth-child(odd) {
            background: #eee;
        }

        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            text-align: left !important;
        }

        td:before {
            top: 0;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
        }

        /*
        Label the data
        You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
        */
        td:nth-of-type(1):before { content: "#: "; }
        td:nth-of-type(2):before { content: "ชื่อรายงาน: "; }
        td:nth-of-type(3):before { content: "เข้าชม (ครั้ง): "; }
        td:nth-of-type(4):before { content: "วันที่สร้าง: "; }

    }*/

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">รายงาน Power BI</h3>

                    <div class="clearfix"></div>
                    <hr class="m-t-0">

                    @php
                        $user_roles = auth()->user()->roles()->pluck('role_id');
                    @endphp
                    @foreach ($groups as $key => $group)

                        <h4 class="box-title font-22" data-group="{{ $group->id }}">{{ $group->title }}</h4>

                        <div class="table-responsive box-data" data-group="{{ $group->id }}">
                            <table class="table color-bordered-table primary-bordered-table">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th class="col-md-7">ชื่อรายงาน</th>
                                        <th class="col-md-2 text-right p-r-30">เข้าชม (ครั้ง)</th>
                                        <th class="col-md-2">วันที่สร้าง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 0;
                                        $config_report_power_bis = $group->config_report_power_bis->where('state', 1)->sortBy('ordering');
                                    @endphp
                                    @foreach ($config_report_power_bis as $report_power_bi)
                                        @if($report_power_bi->check_role($user_roles))
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>
                                                    <a href="{{ url('report-power-bi/'.$report_power_bi->id) }}" target="_blank">
                                                        {{ $report_power_bi->title }}
                                                    </a>
                                                </td>
                                                <td class="text-right p-r-30">{{ number_format($report_power_bi->visit_count()) }}</td>
                                                <td>{{ HP::dateTimeFormatN($report_power_bi->created_at) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            $('.box-data').each(function(index, el) {
                if($(el).find('tbody').children('tr').length==0){
                    var group_id = $(el).data('group');
                    $('[data-group="'+group_id+'"]').hide();
                }
            });
        });

    </script>
@endpush


<style>
    tr {
        padding: 0;
    }
    td {
        padding: 0;
    }
    .table-one {
        margin-left: 3px;    
    }
    .table-two {
        margin-left: 5px
    }
    .table-three {
        margin-left: 5px
    }
    .table-four {
        margin-left: 5px
    }
</style>
<table width="100%"  cellspacing="0" cellpadding="5">
    {{-- <thead class="table-light">
        <tr>
            <th>สาขาการสอบเทียบ</th>
            <th>รายการสอบเทียบ</th>
            <th>ค่าขีดความสามารถของ</th>
            <th>วิธีการที่ใช้</th>
        </tr>
    </thead> --}}
    <tbody>
        @php
        $scopes = collect($company->scope); // แปลง $company->scope เป็น Collection
        $categoryCounts = []; // เก็บจำนวนของแต่ละ category
    @endphp
    
    @foreach ($scopes as $item)
        @php
            // นับจำนวน category
            if (!isset($categoryCounts[$item->category])) {
                $categoryCounts[$item->category] = $scopes->where('category', $item->category)->count();
            }
            $isFirstOccurrence = $categoryCounts[$item->category] > 0; // เช็คว่าค่า category นี้ถูกแสดงแล้วหรือยัง
        @endphp
        <tr style="">
            {{-- @if ($isFirstOccurrence) --}}
            <td style="vertical-align: top;width:14%;padding-bottom: 90px;padding-left:10px">{{ $item->category }}</td>
            {{-- @endif --}}
            <td style="vertical-align: top;width:25%">
                <table class="table-one" cellspacing="0" border="1"  width="100%">
                    <tr>
                        <td><span >{{ $item->instrument }}</span></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table-two" cellspacing="0"  width="100%">
                                @if (!empty($item->description))
                                    <tr><td><span>{{ $item->description }}</span></td></tr>
                                @endif
                                <tr>
                                    <td style="@if ($item->description !== '') margin-left:15px @endif">
                                        <table class="table-three" cellspacing="0"  width="100%">
                                            @foreach ($item->measurements as $key => $measurement)
                                                <tr>
                                                    <td style="@if ($key > 0) padding-top: 15px; @endif">
                                                        <span>{{ $measurement->name }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="table-four" cellspacing="0" width="100%">
                                                            @foreach ($measurement->ranges as $i => $range)
                                                                @if (!empty($range->description))
                                                                    <tr>
                                                                        {{-- <td> --}}
                                                                        <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                                            <span>{!! $range->description !!}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td  style="padding-left: 7px">
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->range))
                                                                            <img src="{{ asset('assets/images/scopes/'. $range->range) }}" alt="Image" style="width:300px" />
                                                                        @else
                                                                            <span>{!! $range->range !!}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
       
            <td style="vertical-align: top;width:25%">
                <table class="table-one" cellspacing="0" border="1" width="100%">
                    <tr>
                        <td><span style="visibility: hidden;">{{ $item->instrument }}</span></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table-two" cellspacing="0"  width="100%">
                                @if (!empty($item->description))
                                    <tr><td><span style="visibility: hidden;">{{ $item->description }}</span></td></tr>
                                @endif
                                <tr>
                                    <td style="@if ($item->description !== '') margin-left:15px @endif">
                                        <table class="table-three" cellspacing="0"  width="100%">
                                            @foreach ($item->measurements as $key => $measurement)
                                                <tr>
                                                    <td style="@if ($key > 0) padding-top: 15px; @endif">
                                                        <span style="visibility: hidden;">{{ $measurement->name }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="table-four" cellspacing="0" width="100%" style="text-align: center">
                                                            @foreach ($measurement->ranges as $i => $range)
                                                                @if (!empty($range->description))
                                                                    <tr>
                                                                        {{-- <td> --}}
                                                                        <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                                            <span style="visibility: hidden;">{!! $range->description !!}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td  style="padding-left: 7px">
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->range))
                                                                            <img src="{{ asset('assets/images/scopes/'. $range->range) }}" alt="Image" style="width:300px" />
                                                                        @else
                                                                            <span>{!! $range->uncertainty !!}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: top;width:25%">
                <table class="table-one" cellspacing="0" border="1" width="100%">
                    <tr>
                        <td><span style="visibility: hidden;">{{ $item->instrument }}</span></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table-two" cellspacing="0"  width="100%">
                                @if (!empty($item->description))
                                    <tr><td><span style="visibility: hidden;">{{ $item->description }}</span></td></tr>
                                @endif
                                <tr>
                                    <td style="@if ($item->description !== '') margin-left:15px @endif">
                                        <table class="table-three" cellspacing="0"  width="100%">
                                            @foreach ($item->measurements as $key => $measurement)
                                                <tr>
                                                    <td style="@if ($key > 0) padding-top: 15px; @endif">
                                                        <span >{{ $item->standard }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="table-four" cellspacing="0" width="100%" style="text-align: center">
                                                            @foreach ($measurement->ranges as $i => $range)
                                                                @if (!empty($range->description))
                                                                    <tr>
                                                                        {{-- <td> --}}
                                                                        <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                                            <span style="visibility: hidden;">{!! $range->description !!}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td  style="padding-left: 7px">
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->range))
                                                                            <img src="{{ asset('assets/images/scopes/'. $range->range) }}" alt="Image" style="width:300px" />
                                                                        @else
                                                                            <span style="visibility: hidden;">{!! $range->uncertainty !!}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            {{-- <td style="vertical-align: top;width:30%">
                <table class="table-one">
                    <tr>
                        <td><span style="visibility: hidden;">{{ $item->instrument }}</span></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table-two">
                                @if (!empty($item->description))
                                    <tr><td><span style="visibility: hidden;">{{ $item->description }}</span></td></tr>
                                @endif
                                <tr>
                                    <td style="@if ($item->description !== '') margin-left:15px @endif">
                                        <table class="table-three">
                                            @foreach ($item->measurements as $measurement)
                                                <tr>
                                                    <td style="@if ($key > 0) padding-top: 15px; @endif">
                                                        <span style="visibility: hidden;">{{ $measurement->name }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <table class="table-four">
                                                            @foreach ($measurement->ranges as $range)
                                                                <tr><td><span style="visibility: hidden;">{!! $range->description !!}</span> </td></tr>
                                                                <tr>
                                                                    <td>
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->range))
                                                                            <img src="{{ asset('assets/images/scopes/'. $range->range) }}" alt="Image" style="width:300px" />
                                                                        @else
                                                                            
                                                                            <span>{!! $range->uncertainty !!}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                               
                            </table>
    
                        </td>
                    </tr>
                </table>
            </td> --}}
            {{-- <td style="vertical-align: top;width:30%">
                <table class="table-one">
                    <tr>
                        <td><span style="visibility: hidden;">{{ $item->instrument }}</span></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table-two">
                                @if (!empty($item->description))
                                    <tr><td><span style="visibility: hidden;">{{ $item->description }}</span></td></tr>
                                @endif
                                <tr>
                                    <td style="@if ($item->description !== '') margin-left:15px @endif">
                                        <table class="table-three">
                                            @foreach ($item->measurements as $measurement)
                                                <tr><td><span>{{ $item->standard }}</span></td></tr>
                        
                                                <tr>
                                                    <td>
                                                        <table class="table-four">
                                                            @foreach ($measurement->ranges as $range)
                                                                <tr><td><span style="visibility: hidden;">{!! $range->description !!}</span></td></tr>
                                                                <tr>
                                                                    <td>
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->range))
                                                                            <img src="{{ asset('assets/images/scopes/'. $range->range) }}" alt="Image" style="width:300px" />
                                                                        @else
                                                                            
                                                                            <span style="visibility: hidden;">{!! $range->range !!}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                               
                            </table>
    
                        </td>
                    </tr>
                </table>

                
             
            </td> --}}
        </tr>
    @endforeach
    
    </tbody>
</table>





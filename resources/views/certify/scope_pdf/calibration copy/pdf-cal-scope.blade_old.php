
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

<table width="100%"  cellspacing="0" cellpadding="5" >
    <tbody>
        @php
            $categoryCounts = [];
        @endphp
        
        @foreach ($scopes as $key => $item)
            <tr>
                <td style="vertical-align: top;width:15%;padding-bottom: 90px;padding-left:5px;font-size:22px">
                    <span @if ($key != 0)
                            style="visibility: hidden"
                    @endif >สาขา{{ $item->category_th }} <br><span style="font-size: 16px">({{$item->category}} field)</span> </span>
                </td>
                <td style="vertical-align: top;width:25%">
                    <table class="table-one" cellspacing="0" width="100%" >
                        <tr>
                            <td >
                                <span style="margin-top:5px">{{ $item->instrument }} </span><span style="font-size:1px;visibility: hidden">*{{$key}}*</span>
                                @if ($item->instrument !== "")
                                    <span><br>{{ $item->instrument_two }} </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table-two" cellspacing="0"  width="100%" >
                                    @if (!empty($item->description))
                                        <tr><td><span>{{ $item->description }}</span></td></tr>
                                    @endif
                                    <tr>
                                        <td style="@if ($item->description !== '') margin-left:15px @endif">
                                            <table class="table-three" cellspacing="0"  width="100%">
                                                @foreach ($item->measurements as $i => $measurement)
                                                    <tr>
                                                        <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                            <span>{{ $measurement->name }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table class="table-four" cellspacing="0" width="100%" style="padding-right:3px">
                                                                @foreach ($measurement->ranges as $i => $range)
                                                                    @if (!empty($range->description))
                                                                        <tr>
                                                                            <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                                                <span>{!! $range->description !!}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                  
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->uncertainty))
                                                                            <td  style="padding-left: 0px">
                                                                                <img src="{{$range->uncertainty}}" alt="Image" style="max-width:160px;visibility: hidden" />                                                                                  
                                                                            </td>
                                                                        @else
                                                                            <td  style="padding-left: 7px">
                                                                                <span>{!! $range->range !!}</span>
                                                                            </td>
                                                                        @endif
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
                    <table class="table-one" cellspacing="0" width="100%" >
                        <tr>
                            <td >
                                <span style="visibility: hidden;margin-top:5px">{{ $item->instrument }}</span><span style="font-size:1px;visibility: hidden">*{{$key}}*</span>
                                @if ($item->instrument !== "")
                                    <span style="visibility: hidden;"><br>{{ $item->instrument_two }} </span>
                                @endif
                            </td>
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
                                                @foreach ($item->measurements as $j => $measurement)
                                                    <tr>
                                                        <td style="@if ($j > 0) padding-top: 15px; @endif">
                                                            <span style="visibility: hidden;">{{ $measurement->name }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table class="table-four" cellspacing="0" width="100%" style="text-align: center;padding-right:3px">
                                                                @foreach ($measurement->ranges as $i => $range)
                                                                    @if (!empty($range->description))
                                                                        <tr>
                                                                            <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                                                <span style="visibility: hidden;">{!! $range->description !!}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->uncertainty))
                                                                            <td  style="padding-left: 0px">
                                                                                <img src="{{$range->uncertainty}}" alt="Image" style="max-width:160px" />                                                                                  
                                                                            </td>
                                                                        @else
                                                                            <td  style="padding-left: -35px">
                                                                                <span>{!! $range->uncertainty !!}</span>
                                                                            </td>
                                                                        @endif
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
                    <table class="table-one" cellspacing="0" width="100%" >
                        <tr>
                            <td>
                                <span style="visibility: hidden;margin-top:5px">{{ $item->instrument }}</span><span style="font-size:1px;visibility: hidden">*{{$key}}*</span>
                                @if ($item->instrument !== "")
                                    <span style="visibility: hidden;"><br>{{ $item->instrument_two }} </span>
                                @endif
                            </td>
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
                                                @foreach ($item->measurements as $k => $measurement)
                                                    <tr>
                                                       
                                                        @if ($k == 0)
                                                                <td style="@if ($k > 0) padding-top: 15px; @endif">
                                                                    <span >{!! $item->standard !!}</span>
                                                                </td>
                                                            @else
                                                            <td style="@if ($k > 0) padding-top: 15px; @endif">
                                                                <span >{!! $item->standard !!}</span>
                                                            </td>
                                                        @endif

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table class="table-four" cellspacing="0" width="100%" style="text-align: center;padding-right:3px">
                                                                @foreach ($measurement->ranges as $i => $range)
                                                                    @if (!empty($range->description))
                                                                        <tr>
                                                                            <td style="@if ($i > 0) padding-top: 15px; @endif">
                                                                                <span style="visibility: hidden;">{!! $range->description !!}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                        @if (preg_match('/\.(png|jpg|jpeg|gif)$/i', $range->uncertainty))
                                                                            <td  style="padding-left: 0px">
                                                                                <img src="{{$range->uncertainty}}" alt="Image" style="max-width:160px;visibility: hidden" />                                                                                  
                                                                            </td>
                                                                        @else
                                                                            <td  style="padding-left: 7px">
                                                                                <span style="visibility: hidden;">{!! $range->range !!}</span>
                                                                            </td>
                                                                        @endif
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
            </tr>
        @endforeach
    
    </tbody>
</table>





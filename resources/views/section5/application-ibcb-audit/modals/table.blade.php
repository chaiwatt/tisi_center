

@foreach ( $application as $key => $item )
    @php
        $scope_groups =  $item->scopes_group
    @endphp
    <tr>
        <td class="text-top text-center">
            {!! $key+1 !!}
            <input type="hidden" name="id[]" class="item_m_ck_id" value="{!! $item->id !!}">
        </td>
        <td class="text-top text-center">{!! $item->application_no !!}</td>
        <td class="text-top">{!! !empty($item->applicant_name)?$item->applicant_name:'-' !!}</td>
        <td class="text-top  text-center">{!! $item->applicant_taxid !!}</td>
        <td class="text-top">

            @foreach ( $scope_groups as $ks => $Iscope )
                <ul class="list-unstyled">
                    <li>
                        สาขา {!! !empty($Iscope->bs_branch_group->title)?$Iscope->bs_branch_group->title:'-'  !!}
                    </li>
                    @foreach ($Iscope->scopes_details as $scope )
                        <li>
                            <label>
                                &nbsp; &nbsp;
                                <input type="checkbox" name="scope_id[]" class="item_scope_checkbox_all"  value="{!! $scope->id !!}">
                                {!! !is_null($scope->bs_branch) ? $scope->bs_branch->title : '-' !!}
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </td>
    </tr>

@endforeach


@foreach ( $application as $key => $item )
    @php
        $scope_groups = $item->app_scope()->get()->keyBy('id')->groupBy('branch_group_id');
        $branch_group = $item->app_scope()
                                ->with( 
                                    ['bs_branch_group' => function ($query) {
                                        $query->select('id', 'title');
                                    }]
                                )
                                ->groupBy('branch_group_id')
                                ->get()
                                ->pluck('bs_branch_group.title', 'bs_branch_group.id')
                                ->toArray();
    @endphp
    <tr>
        <td class="text-top text-center">
            {!! $key+1 !!}
            <input type="hidden" name="id[]" class="item_m_ck_id" value="{!! $item->id !!}">
        </td>
        <td class="text-top text-center">{!! $item->application_no !!}</td>
        <td class="text-top">{!! !empty($item->applicant_full_name)?$item->applicant_full_name:'-' !!}</td>
        <td class="text-top  text-center">{!! $item->applicant_taxid !!}</td>
        <td class="text-top">
            @foreach($scope_groups as $branch_group_id => $application_scope)
                <ul class="list-unstyled">
                    <li>
                        สาขา{!! array_key_exists($branch_group_id, $branch_group)?$branch_group[$branch_group_id]:null !!}
                    </li>
                    @foreach ($application_scope as $key => $scope)
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
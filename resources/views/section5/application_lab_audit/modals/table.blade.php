

@foreach ( $application as $key => $item )
    @php
        $scope_groups = $item->app_scope_standard()->get()->keyBy('id')->groupBy('tis_id');
        $standards    = $item->app_scope_standard()
                                ->with( 
                                    ['tis_standards' => function ($query) {
                                        $query->select('id', DB::Raw('CONCAT_WS(" : ",tis_tisno, title) AS standard_title'));
                                    }]
                                )
                                ->groupBy('tis_id')
                                ->get()
                                ->pluck('tis_standards.standard_title', 'tis_standards.id')
                                ->toArray();
    @endphp
    <tr>
        <td class="text-top text-center">
            {!! $key+1 !!}
            <input type="hidden" name="id[]" class="item_m_ck_id" value="{!! $item->id !!}">
        </td>
        <td class="text-top text-center">{!! $item->application_no !!}</td>
        <td class="text-top">{!! '<div>'.(!empty($item->lab_name)?$item->lab_name:'-').'</div>'.(!empty($item->applicant_name)?'('.$item->applicant_name.')':'-') !!}</td>
        <td class="text-top  text-center">{!! $item->applicant_taxid !!}</td>
        <td class="text-top">
            @foreach($scope_groups as $tis_id => $application_scope)
                <ul class="list-unstyled">
                    <li>
                        มอก. {!! array_key_exists($tis_id, $standards)?$standards[$tis_id]:null !!}
                    </li>
                    @foreach ($application_scope as $key => $scope)
                        <li>
                            <label>
                                &nbsp; &nbsp;
                                <input type="checkbox" name="scope_id[]" class="item_scope_checkbox{!! $tis_id !!} item_scope_checkbox_all"  value="{!! $scope->id !!}">
                                {!! !is_null($scope->test_item) ? $scope->test_item->TestItemHtml : '-' !!}
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </td>
    </tr>

@endforeach
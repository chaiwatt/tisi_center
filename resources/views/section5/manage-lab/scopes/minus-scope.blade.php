


<table class="table">
    <tbody>
        @foreach ( $scope_group as $itemStd )
            @php
                $tis_standards = $itemStd->tis_standards;
            @endphp
            <tr class="group">
                <td colspan="2">{!! !empty( $tis_standards->tb3_Tisno )?$tis_standards->tb3_Tisno.' : ':null !!} {!! !empty( $tis_standards->tb3_TisThainame )?$tis_standards->tb3_TisThainame:null !!}</td>
            </tr>

            @if(!is_null($tis_standards))
                @foreach ($scope->where('tis_id', $tis_standards->getKey()) as $item )

                    @php
                        $test_item = $item->test_item;
                        if(!is_null($test_item)){
                            if(  $test_item->type == 1 ){
                                $item->test_item_title = ( !empty( $test_item->no )?$test_item->no.' ' :null ).$test_item->title;
                            }else{
                                $mains  =  $test_item->test_item_main;
                                $item->test_item_title = ( !empty( $test_item->no )?$test_item->no.' ' :null ).$test_item->title.' <em>(ภายใต้หัวข้อทดสอบ '.(  ( !empty( $mains->no )?$mains->no.' ' :null ).$mains->title ).')</em>';
                            }
                        }
                    @endphp
                    <tr>
                        <td width="5%" class="text-center">
                            <input type="checkbox" name="scope_id[]" class="scope_id_checkbox"  value="{!! $item->id !!}">
                        </td>
                        <td width="95%">
                            {!! !empty( $item->test_item_title )?$item->test_item_title:null !!}
                            Exp. {!! !empty($item->end_date)?HP::revertDate($item->end_date,true):'-' !!}
                            {!! ($item->type == 2) ?'<span class="text-muted"><em>(นำเข้าข้อมูลเมื่อ :'.(HP::revertDate($item->created_at,true)).')</em></span>':'' !!}
                        </td>
                    </tr>
                @endforeach
            @endif
    
        @endforeach
    </tbody>
</table>



@php
$i = 1;
$std_ids = [];
$scope_active = 0;
$scope_active_not = 0;
@endphp
<div class="col-md-12 col-sm-12">
<div id="accordion">
    @isset($list_scope)

        @php
            $list_item = [];


            $StateHtml = [ 1 => '<span class="text-success">Active</span>', 2 => '<span class="text-danger">Not Active</span>' ];

            foreach ($list_scope as $key => $scope) {
                $tis_standards = $scope->tis_standards;
                if(!is_null($tis_standards)){
                    $list_item[$tis_standards->id] = $tis_standards;
                }
            }

            $labs_scope = App\Models\Section5\LabsScope::where('lab_id', $labs->id);

            $standards = App\Models\Tis\Standard::selectRaw('id, CONCAT_WS(" : ", tis_tisno, title) AS standard_title')->whereIn('id', $labs_scope->select('tis_id'))->pluck('standard_title', 'id')->toArray();

            $Alllabscope = DB::table((new App\Models\Section5\LabsScope)->getTable().' AS scope')
                            ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                            ->where('scope.lab_id', $labs->id )
                            ->select('scope.id', 'scope.test_item_id', 'scope.state', 'scope.end_date', 'test.main_topic_id', 'test.type', 'test.level')
                            ->get();

        @endphp

        @foreach($list_item as $tis_standards_id => $items )
            @php

                $main_test_item = DB::table((new App\Models\Section5\LabsScope)->getTable().' AS scope')
                                    ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                    ->where('scope.lab_id', $labs->id )
                                    ->where('test.tis_id', $tis_standards_id )
                                    ->select('test.main_topic_id')
                                    ->groupBy('main_topic_id')
                                    ->get();



                $maxsL = DB::table((new App\Models\Section5\LabsScope)->getTable().' AS scope')
                                    ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                    ->where('scope.lab_id', $labs->id )
                                    ->where('test.tis_id', $tis_standards_id )
                                    ->select('test.test')
                                    ->whereNotNull('test.level')
                                    ->max('level');

                $Allitem = DB::table((new App\Models\Section5\LabsScope)->getTable().' AS scope')
                                    ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                    ->where('scope.lab_id', $labs->id )
                                    ->where('test.tis_id', $tis_standards_id )
                                    ->select('test.main_topic_id','test.type', 'test.id', 'test.level', 'test.parent_id')
                                    // ->groupBy('main_topic_id')
                                    ->get();

                $All_parent_type2 =[];
                $All_parent_type3 =[];

                foreach ($Allitem as $value) {

                    // if( $value->type == 2 ){
                        $All_parent_type2[$value->main_topic_id][ $value->parent_id ] = $value->parent_id;
                    // }else if( $value->type == 3 ){
                        $All_parent_type3[$value->main_topic_id][ $value->parent_id ] = $value->parent_id;
                    // }

                }

                $test_item_id_scope =  $labs_scope->select('test_item_id');


                $test_item_all = App\Models\Bsection5\TestItem::where('tis_id', $tis_standards_id )
                                                                    ->select('title','id', 'no')
                                                                    ->groupBy('title','id', 'no')
                                                                    ->get();

            @endphp
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{!! $tis_standards_id !!}" aria-expanded="true" aria-controls="collapse-{!! $tis_standards_id !!}">
                                {!! array_key_exists( $tis_standards_id, $standards)?$standards[ $tis_standards_id ]:null !!}
                            </button>
                        </h5>
                    </div>

                    <div id="collapse-{!! $tis_standards_id !!}" class="collapse in" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="col-sm-12 col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="2%" class="text-center">ที่.</th>
                                            <th class="text-center" colspan="{!! !is_null($maxsL)? (int)$maxsL-1:2 !!}">รายการทดสอบ</th>
                                            <th width="15%" class="text-center">วันที่หมดอายุ</th>
                                            {{-- <th width="15%" class="text-center">ข้อ,ตาราง</th>
                                            <th width="15%" class="text-center">หน่วย</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php

                                            $range = HP::RangeData(2,$maxsL);
                                            $list_x = [];
                                            foreach($main_test_item as $main){

                                                $main_topic_id = $main->main_topic_id;
                                                $row_all = 0;

                                                $exit_list2 = [];
                                                $exit_list3 = [];
                                                $exit_list1 = [];

                                                $AllitemType3 =  DB::table((new App\Models\Bsection5\TestItem)->getTable().' AS test')
                                                                                // ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                                                                // ->where('scope.lab_id', $labs->id )
                                                                                ->where('test.tis_id', $tis_standards_id )
                                                                                ->where('test.main_topic_id', $main_topic_id )
                                                                                ->where(function($query) use($All_parent_type3, $main_topic_id){

                                                                                    $parent_ids = array_key_exists( $main_topic_id , $All_parent_type3 ) ?$All_parent_type3[ $main_topic_id ]:[];
                                                                                    if( count($parent_ids) > 0 ){
                                                                                        $query->whereIn('test.parent_id', $parent_ids)->OrwhereIn('test.id', $parent_ids);
                                                                                    }else{
                                                                                        $query->whereRaw('1=0');
                                                                                    }

                                                                                })
                                                                                ->select('test.main_topic_id','test.type', 'test.id', 'test.level', 'test.parent_id')
                                                                                ->get();



                                                $AllitemType2 = DB::table((new App\Models\Bsection5\TestItem)->getTable().' AS test')
                                                                                // ->leftJoin((new App\Models\Bsection5\TestItem)->getTable().' AS test', 'test.id', '=', 'scope.test_item_id')
                                                                                // ->where('scope.lab_id', $labs->id )
                                                                                ->where('test.tis_id', $tis_standards_id )
                                                                                ->where('test.main_topic_id', $main_topic_id )
                                                                                ->where(function($query) use($All_parent_type2, $main_topic_id){

                                                                                    $parent_ids = array_key_exists( $main_topic_id , $All_parent_type2 ) ?$All_parent_type2[ $main_topic_id ]:[];
                                                                                    if( count($parent_ids) > 0 ){
                                                                                        $query->whereIn('test.parent_id', $parent_ids)->OrwhereIn('test.id', $parent_ids);
                                                                                    }else{
                                                                                        $query->whereRaw('1=0');
                                                                                    }

                                                                                })
                                                                                ->select('test.main_topic_id','test.type', 'test.id', 'test.level', 'test.parent_id')
                                                                                ->get();



                                                $type2_ids = [];

                                                if( count($AllitemType2) != 0 ){

                                                    $list_Type2 = [];

                                                    foreach ( $range as  $ranges ) {


                                                        $type2 =  $AllitemType2->where('level', $ranges )->where('type', 2);

                                                        // dd( $AllitemType2 );

                                                        foreach ($type2 as $type2s ) {

                                                            $type3Data =  $AllitemType3->where('parent_id', $type2s->id );

                                                            $list_Type3 = [];

                                                            if(  count($type3Data) != 0  ){
                                                                $row3 = 0;
                                                                foreach ($type3Data as $type3s ) {

                                                                    if( !array_key_exists( $type3s->id , $exit_list3 ) ){

                                                                        $scopeList3 = $Alllabscope->where('type', 3 )->where('test_item_id', $type3s->id );
                                                                        if( count($scopeList3) != 0 ){
                                                                            $DataType3 = new stdClass;
                                                                            $DataType3->id = $type3s->id;
                                                                            $DataType3->level = $ranges;
                                                                            $DataType3->scope = $scopeList3;
                                                                            $list_Type3[ $type3s->id ] = $DataType3;

                                                                            $row_all++;
                                                                            // $row3++;
                                                                            $row3 += count($scopeList3);
                                                                            $exit_list3[ $type3s->id ] = $type3s->id;
                                                                        }

                                                                    }

                                                                }

                                                                if( !array_key_exists( $type2s->id , $exit_list2 ) ){

                                                                    // $scopeList2 = $Alllabscope->where('type', 2 )->where('test_item_id', $type2s->id );
                                                                    // if( count($scopeList3) != 0 ){
                                                                        $DataType2 = new stdClass;
                                                                        $DataType2->id = $type2s->id;
                                                                        $DataType2->level = $ranges;
                                                                        $DataType2->row_type3 = $row3;
                                                                        $DataType2->list_type3 = $list_Type3;
                                                                        // $DataType2->scope = $scopeList2;
                                                                        $list_Type2[ $type2s->id ] = $DataType2;

                                                                        $exit_list2[ $type2s->id ] = $type2s->id;
                                                                    // }
                                                                }

                                                            }else{

                                                                if( !array_key_exists( $type2s->id , $exit_list2 ) ){

                                                                    $scopeList2 = $Alllabscope->where('type', 2 )->where('test_item_id', $type2s->id );

                                                                    if( count($scopeList2) != 0 ){
                                                                        $DataType2 = new stdClass;
                                                                        $DataType2->id = $type2s->id;
                                                                        $DataType2->level = $ranges;
                                                                        $DataType2->row_type3 = 1;
                                                                        $DataType2->list_type3 = [];
                                                                        $DataType2->scope = $scopeList2;

                                                                        $list_Type2[ $type2s->id ] = $DataType2;

                                                                        $exit_list2[ $type2s->id ] = $type2s->id;
                                                                    }

                                                                }
                                                            }

                                                            $type2_ids[ $type2s->id ] = $type2s->id;

                                                        }

                                                    }

                                                    $DataType1 = new stdClass;
                                                    $DataType1->id = $main_topic_id;
                                                    $DataType1->list_Type2 = $list_Type2;
                                                    $list_x[   $main_topic_id ][] = $DataType1;
                                                }



                                                if( count($AllitemType3) != 0 ){

                                                    $list_Type3 = [];

                                                    foreach ( $range as  $ranges ) {

                                                        $type3Data =  $AllitemType3->where('level', $ranges )->where('type', 3);

                                                        foreach ($type3Data as $type3s ) {

                                                            if( !array_key_exists( $type3s->id , $exit_list3 ) ){

                                                                $scopeList3 = $Alllabscope->where('type', 3 )->where('test_item_id', $type3s->id );

                                                                if( count($scopeList3) != 0 ){
                                                                    $DataType3 = new stdClass;
                                                                    $DataType3->id = $type3s->id;
                                                                    $DataType3->level = $ranges;
                                                                    $DataType3->scope = $scopeList3;
                                                                    $list_Type3[ $type3s->id ] = $DataType3;
                                                                    $row_all++;
                                                                    $exit_list3[ $type3s->id ] = $type3s->id;
                                                                }


                                                            }

                                                        }

                                                    }

                                                    if( count($list_Type3) != 0  ){
                                                        $DataType1 = new stdClass;
                                                        $DataType1->id = $main_topic_id;
                                                        $DataType1->list_Type3 = $list_Type3;
                                                        $list_x[  $main_topic_id ][] = $DataType1;
                                                    }

                                                }

                                                $type1 = App\Models\Bsection5\TestItem::where('tis_id', $tis_standards_id )
                                                                                        ->whereIn('id',$test_item_id_scope )
                                                                                        ->where('type', 1)
                                                                                        ->where('main_topic_id', $main_topic_id )
                                                                                        ->whereNull('parent_id')
                                                                                        ->select('main_topic_id','type', 'id', 'level', 'parent_id')
                                                                                        ->groupBy('main_topic_id','type', 'id', 'level', 'parent_id')
                                                                                        ->orderBy('level')
                                                                                        ->get();

                                                if( count($type1) != 0 ){

                                                    foreach ( $type1 as  $type1s ) {

                                                        $scopeList1 = $Alllabscope->where('type', 1 )->where('test_item_id', $type1s->id );

                                                        if( count($scopeList1) != 0  ){
                                                            $DataType1 = new stdClass;
                                                            $DataType1->id = $type1s->id;
                                                            $DataType1->scope = $scopeList1;
                                                            $list_x[  $main_topic_id ][] = $DataType1;
                                                        }
                                                    }

                                                }

                                            }

                                        @endphp
                                        @php
                                            $main_exit = [];
                                        @endphp
                                        @foreach ( $list_x as $main_id => $ItemLsit )

                                            @php
                                                $TestItemData = $test_item_all->where('id',  $main_id )->first();

                                                $maxsL = !is_null($maxsL) && ( (int)$maxsL > 1) ? (int)$maxsL:2;
                                            @endphp

                                            @foreach ( $ItemLsit as $item )

                                                @if( !array_key_exists( $main_id , $main_exit )  )

                                                    @if ( isset($item->scope) )
                                                        @php
                                                            $type1_exit = [];
                                                        @endphp

                                                        <tr>
                                                            <td  rowspan="{!! count($item->scope) !!}" colspan="{!! !is_null($maxsL)? (int)$maxsL:2 !!}">{!!  $TestItemData->title  !!}</td>
                                                            <td>
                                                                @foreach (  $item->scope as $scope )
                                                                @if ( !array_key_exists( $scope->id , $type1_exit ) )
                                                                    <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::DateThaiFull($scope->end_date) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!}</span>
                                                                        @php
                                                                            $type1_exit[$scope->id] = $scope->id;
                                                                            break;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>

                                                        @if(  count($item->scope) != 0)
                                                            @foreach ( $item->scope as $scope )

                                                                @if (  !array_key_exists( $scope->id , $type1_exit ) )
                                                                    <tr>
                                                                        <td>
                                                                            <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::DateThaiFull($scope->end_date) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!} </span>
                                                                        </td>
                                                                    </tr>

                                                                    @php
                                                                        $type1_exit[$scope->id] = $scope->id;
                                                                    @endphp
                                                                @endif

                                                            @endforeach
                                                        @endif
                                                    @else
                                                        <tr>
                                                            <td colspan="{!! !is_null($maxsL)? (int)$maxsL+1:2 !!}">{!!  $TestItemData->title  !!}</td>
                                                        </tr>
                                                    @endif

                                                    @php
                                                        $main_exit[$main_id] = $main_id;
                                                    @endphp
                                                @endif

                                                @if( isset($item->list_Type2) )

                                                    @php
                                                        $i_num = 0;

                                                        $list_Type2 = $item->list_Type2;
                                                    @endphp


                                                    @foreach (  $list_Type2  as   $list_Type2s  )
                                                        @php
                                                            $i_num++;
                                                            $TestItemData2 = $test_item_all->where('id',  $list_Type2s->id )->first();
                                                            $list_Type3 = $list_Type2s->list_type3;
                                                            $type3_first = current($list_Type3);
                                                            $TestItemData3f = null;
                                                        @endphp

                                                        @if ( count($list_Type3) == 0  )
                                                            @php
                                                                $list_Type2s->scope;
                                                                $type2_exit = [];
                                                            @endphp
                                                            <tr>
                                                                <td>{!!  $i_num  !!}</td>
                                                                <td rowspan="{!! count($list_Type2s->scope) !!}" colspan="{!! !is_null($maxsL)? (int)$maxsL - 1:2 !!}">{!!  $TestItemData2->title  !!}</td>
                                                                <td>
                                                                    @foreach (  $list_Type2s->scope as $scope )
                                                                        @if ( !array_key_exists( $scope->id , $type2_exit ) )
                                                                            <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!}</span>
                                                                            <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>

                                                                            @php
                                                                                $type2_exit[$scope->id] = $scope->id;
                                                                                break;
                                                                            @endphp
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                            </tr>


                                                            @if(  count($list_Type2s->scope) != 0)
                                                                @foreach ( $list_Type2s->scope as $scope )

                                                                    @if (  !array_key_exists( $scope->id , $type2_exit ) )
                                                                        <tr>
                                                                            <td>
                                                                                <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!} </span>
                                                                                <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                                            </td>
                                                                        </tr>

                                                                        @php
                                                                            $type2_exit[$scope->id] = $scope->id;
                                                                        @endphp
                                                                    @endif

                                                                @endforeach
                                                            @endif
                                                        @else
                                                            @php
                                                                $sk3 = 0;
                                                                $type3_exit = [];
                                                            @endphp
                                                            <tr>
                                                                <td rowspan="{!! !empty($list_Type2s->row_type3)? (int)$list_Type2s->row_type3:1 !!}">{!!  $i_num  !!}</td>
                                                                <td rowspan="{!! !empty($list_Type2s->row_type3)? (int)$list_Type2s->row_type3:1 !!}">{!!  $TestItemData2->title  !!}</td>
                                                                <td rowspan="{!! count($type3_first->scope) !!}">
                                                                    @php
                                                                        if(!empty($type3_first->id)){
                                                                            $TestItemData3f = $test_item_all->where('id',  $type3_first->id )->first();
                                                                        }
                                                                    @endphp
                                                                    {!! !empty( $TestItemData3f->title )? $TestItemData3f->title:null  !!}
                                                                </td>
                                                                <td>
                                                                    @foreach (  $type3_first->scope as $scope )
                                                                        @if ( !array_key_exists( $scope->id , $type3_exit ) )
                                                                            <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!}</span>
                                                                            <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                                           @php
                                                                                $type3_exit[$scope->id] = $scope->id;
                                                                                break;
                                                                            @endphp


                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                            </tr>


                                                            @foreach ( $list_Type3 as   $list_Type3s   )

                                                                @if( !is_null($TestItemData3f) && $list_Type3s->id != $TestItemData3f->id  )
                                                                    <tr>
                                                                        @php
                                                                            $TestItemData3 = $test_item_all->where('id',  $list_Type3s->id )->first();
                                                                        @endphp
                                                                        <td rowspan="{!! count($list_Type3s->scope) !!}">{!!  $TestItemData3->title  !!}</td>
                                                                        <td>
                                                                            @foreach ( $list_Type3s->scope as $scope )
                                                                                @if ( !array_key_exists( $scope->id , $type3_exit ) )

                                                                                    <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!}</span>
                                                                                    <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                                                    @php
                                                                                        $type3_exit[$scope->id] = $scope->id;
                                                                                        break;
                                                                                    @endphp

                                                                                @endif

                                                                            @endforeach
                                                                        </td>
                                                                    </tr>

                                                                    @if(  count($list_Type3s->scope) != 0)
                                                                        @foreach ( $list_Type3s->scope as $scope )

                                                                            @if (  !array_key_exists( $scope->id , $type3_exit ) )
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!} </span>
                                                                                        <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                                                    </td>
                                                                                </tr>

                                                                                @php
                                                                                    $type3_exit[$scope->id] = $scope->id;
                                                                                @endphp
                                                                            @endif

                                                                        @endforeach
                                                                    @endif
                                                                @endif

                                                            @endforeach

                                                        @endif

                                                    @endforeach


                                                @elseif ( isset($item->list_Type3) )
                                                    @php
                                                        $i_num = 0;
                                                        $list_Type3 = $item->list_Type3;
                                                    @endphp

                                                    @foreach (  $list_Type3  as   $list_Type3s  )
                                                        @php
                                                            $i_num++;
                                                            $TestItemData3 = $test_item_all->where('id',  $list_Type3s->id )->first();

                                                            $sk = 0;
                                                            $type3_exit = [];
                                                        @endphp
                                                        <tr>
                                                            <td rowspan="{!! count($list_Type3s->scope) !!}">{!!  $i_num  !!}</td>
                                                            <td rowspan="{!! count($list_Type3s->scope) !!}" colspan="{!! !is_null($maxsL)? (int)$maxsL-1:2 !!}">{!!  $TestItemData3->title  !!}</td>
                                                            <td>
                                                                @foreach ( $list_Type3s->scope as $scope )

                                                                    @if ( !array_key_exists( $scope->id , $type3_exit )  )
                                                                        <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!} </span>
                                                                        <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>

                                                                        @php
                                                                            $sk++;
                                                                            $type3_exit[$scope->id] = $scope->id;
                                                                            break;
                                                                        @endphp
                                                                    @endif

                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                        @if(  count($list_Type3s->scope) >= 2)
                                                            @foreach ( $list_Type3s->scope as $scope )

                                                                @if ( !array_key_exists( $scope->id , $type3_exit ) )
                                                                    <tr>
                                                                        <td>
                                                                           <span class=" @if($scope->state == 1) text-success @else text-danger  @endif "> {!! HP::revertDate($scope->end_date,true) !!} {!! array_key_exists( $scope->state , $StateHtml )? $StateHtml[ $scope->state ] : $StateHtml[ 2 ] !!} </span>
                                                                           <button class="btn btn-warning btn-xs pull-right modal_tools" data-id="{!! $scope->id !!}" ><i class="fa fa-eye" aria-hidden="true"></i></button>

                                                                            @php
                                                                                $sk++;
                                                                            @endphp
                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                            @endforeach
                                                        @endif

                                                    @endforeach

                                                @endif

                                            @endforeach

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach


    @endisset
</div>
</div>

{{-- <div class="col-sm-12 col-md-12">
<div class="text-bold-600 text-right">ขอบข่ายที่ตรวจสอบได้ {!!  $scope_active  !!} รายการทดสอบ</div>
<div class="text-bold-600 text-right">ขอบข่ายไม่สามารถตรวจสอบได้  {!!  $scope_active_not  !!} รายการทดสอบ</div>
</div> --}}

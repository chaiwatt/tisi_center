@php
    $scope_list        = App\Models\Section5\IbcbsScope::where('ibcb_id', $ibcb->id )->orderBy('branch_group_id','end_date')->get()->groupBy('branch_group_id');

    $branch_group_arr  = App\Models\Section5\IbcbsScope::where('ibcb_id', $ibcb->id )->with('bs_branch_group:id,title')->get()->pluck('bs_branch_group.title','bs_branch_group.id')->toArray();

    $StateHtml         = [ 1 => '<span class="text-success">Active</span>', 2 => '<span class="text-danger">Not Active</span>' ];

    $scopeActive       = 0;
    $scopeNotActive    = 0;

    $GroupActive       = [];
    $GroupNotActive    = [];
@endphp

<div class="col-md-12 col-sm-12">
    <div class="pull-right">

        @can('poko_approve-'.str_slug('manage-ibcb'))
            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#PlusScopeModal"><span class="btn-label"><i class="fa fa-plus"></i></span><b>เพิ่มขอบข่าย</b></button>
            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#MinusScopeModal"><span class="btn-label"><i class="fa fa-minus"></i></span><b>ลดขอบข่าย</b></button>
        @endcan

    </div>
</div>

<div class="col-md-12 col-sm-12">
    <div id="accordion">

        @foreach ( $scope_list as $branch_group_id =>  $group )

            @php

                $bs_branch_group = array_key_exists( $branch_group_id, $branch_group_arr  )?$branch_group_arr[ $branch_group_id ]:null;
                
                $count_tis_cancel = App\Models\Section5\IbcbsScopeTis::whereIn('ibcb_scope_id', $group->pluck('id'))
                                    ->whereHas('scope_tis_std', function($query){
                                        $query->where('status', 5);
                                    })->count();

            @endphp
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{!! $branch_group_id !!}" aria-expanded="true" aria-controls="collapse-{!! $branch_group_id !!}">
                            {!! !empty($bs_branch_group)?$bs_branch_group:null !!} 
                        </button>
                        {!! $count_tis_cancel > 0 ? '<span class="label label-rounded label-danger font-15" style="margin-bottom:-20px;">มอก. ยกเลิก</span>' : '' !!}
                    </h5>
                </div>
                <div id="collapse-{!! $branch_group_id !!}" class="collapse in" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <ul>
                            @foreach (  $group as  $branch_group )

                                @php
                                    $color = ( $branch_group->state == 1 &&  $branch_group->end_date >= date('Y-m-d') )?'text-success':'text-danger';
                                    if( $branch_group->state != 1  || ($branch_group->state == 1 && $branch_group->end_date < date('Y-m-d'))  ){
                                        $branch_group->state = 2;
                                        $GroupNotActive[$branch_group_id] = $branch_group_id;
                                    }else{
                                        $GroupActive[$branch_group_id] = $branch_group_id;
                                    }
                                @endphp
                                
                                @foreach ( $branch_group->scopes_details as $Idetail )
                                    @php
                                        $bs_branch = $Idetail->bs_branch;
                                        if( $branch_group->state != 1  || ($branch_group->state == 1 && $branch_group->end_date < date('Y-m-d'))  ){
                                            $scopeNotActive++;
                                        }else{
                                            $scopeActive++;
                                        }
                                    @endphp
                                    <li>
                                        <span class="pull-left">
                                            {!! !empty($bs_branch->title)?$bs_branch->title:null !!}
                                        </span>
                                        <span class="pull-right {!! $color !!}">
                                            วันที่ประกาศ {!! HP::revertDate($branch_group->start_date,true) !!} |  Exp. {!! HP::revertDate($branch_group->end_date,true) !!} | {!! array_key_exists( $branch_group->state, $StateHtml )?$StateHtml[ $branch_group->state ]:null !!}
                                        </span>
                                    </li>
                                @endforeach

                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>
<hr>
@php
    foreach( $GroupActive AS $item ){
        unset($GroupNotActive[$item]);
    }
@endphp
<div class="col-sm-12 col-md-12">
    <div class="text-bold-400 text-right">ขอบข่ายที่ตรวจสอบได้ {!!  count($GroupActive)  !!} สาขา</div>
    <div class="text-bold-400 text-right">ขอบข่ายไม่สามารถตรวจสอบได้  {!!  count($GroupNotActive)  !!} สาขา</div>
</div> 
<div class="col-sm-12 col-md-12">
    <div class="text-bold-400 text-right">รายสาขาที่ตรวจสอบได้ {!!  $scopeActive  !!} รายสาขา</div>
    <div class="text-bold-400 text-right">รายสาขาไม่สามารถตรวจสอบได้  {!!  $scopeNotActive  !!} รายสาขา</div>
</div> 
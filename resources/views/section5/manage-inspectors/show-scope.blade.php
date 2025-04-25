@php

    $scopes = $inspector->scopes;
    $scope_groups = count($scopes) > 0 ? $scopes->groupBy('agency_taxid') : [] ;

    $agency_scope = App\Models\Section5\InspectorsScope::where('inspectors_id', $inspector->id)
                                                            ->with('agency_user')
                                                            ->groupBy('agency_id')
                                                            ->get()
                                                            ->pluck('agency_user.name','agency_user.tax_number')
                                                            ->toArray();


@endphp

@foreach ($scope_groups as $key => $group)

    @php
        $collapse_id =  str_random(10);
    @endphp

    <div class="row">
        <div class="col-md-12">
            <div class="card " id="headingOne">
                <div class="card-header">
                    <h5 class="mb-0">
                        <div class="col-md-12">
                            <button class="btn btn-link pull-left" data-toggle="collapse" data-target="#collapse-{!! $collapse_id !!}" aria-expanded="true" aria-controls="collapse-{!! $collapse_id !!}">
                                {!! ( array_key_exists( $key, $agency_scope  )?$agency_scope[ $key ]:null ).( !empty($key)?' ('.$key.')':null ) !!}
                            </button>
                        </div>
                    </h5>
                </div>

                <div id="collapse-{!! $collapse_id !!}" class="collapse in " aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <div class="col-sm-12 col-md-12 ">

                            <div class="col-md-12 m-l-15">
                                <ul class="list-unstyled">
                                    @foreach ( $group->groupBy('branch_group_id') as $branch_group_id => $Ibranch )

                                        @php
                                            $bs_branch_group = !empty($Ibranch->first())?$Ibranch->first()->bs_branch_group:null;
                                        @endphp


                                        <li><h4>{!! !empty( $bs_branch_group->title )? $bs_branch_group->title:null !!}</h4></li>
                                        <li>
                                            <ul>
                                                @foreach ( $Ibranch as $branch )
                                                    @php
                                                        $bs_branch = $branch->bs_branch;

                                                        
                                                        if( empty($branch->end_date) || $branch->end_date < date('Y-m-d') ){
                                                            $branch->state = 2;
                                                        }
                                                        $status_color = ( @$branch->state == 1 )?'<span class="text-success"> | Active</span>':'<span class="text-danger"> | Not Active</span>';

                                                        $date_color = '<span class="text-danger">Exp. - </span>';
                                                        if( !empty($branch->end_date) && $branch->state == 1 ){
                                                            $date_color = ( @$branch->end_date >= date('Y-m-d') )?'<span class="text-success">Exp. '.HP::DateThai( @$branch->end_date ).'</span>':'<span class="text-danger">Exp. '.HP::DateThai( @$branch->end_date ).'</span>'; 
                                                        }else{
                                                            $date_color = '<span class="text-danger"> Exp. '.(HP::DateThai( @$branch->end_date )).'</span>';
                                                        }
                                                      
                                                        $import   = null;
                                                        if( !empty($branch->type) && $branch->type == 2 ){
                                                            $import = '<span class="text-muted"><em>(นำเข้าข้อมูลเมื่อ :'.(HP::revertDate($branch->created_at,true)).')</em></span>';
                                                        }
                                                    @endphp
                                                    <li>
                                                        <a href="javascript:void(0)" class="modal_scope_detail" data-id="{!! $branch->id !!}" >  {!! !empty( $bs_branch->title )? $bs_branch->title:null !!}</a>
                                                        <span class="pull-right">{!! $import !!} {!! $date_color !!} {!! $status_color !!} </span>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                        
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endforeach

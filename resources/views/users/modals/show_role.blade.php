
<div class="row">
    <div class="col-md-12">
        <h4 class="box-title m-b-0">กลุ่มผู้ใช้งาน</h4>

        
        @php

            $setting_system = App\RoleSettingGroup::count();
            $roles_all =   App\Role::Has('role_group', '>=',  $setting_system  )->whereIn('id', $users->role_users()->pluck('id') )->where('label', 'staff' )->get();
            $groups = App\RoleGroup::with('setting_system')
                                    ->whereHas('role', function($query) use ($setting_system, $users ){
                                        $query->WhereNotIn('id', App\Role::Has('role_group', '>=',  $setting_system  )->select('id') )->whereIn('id', $users->role_users()->pluck('id') )->where('label', 'staff' );
                                    })
                                    ->groupby('setting_systems_id')
                                    ->get()
                                    ->pluck('setting_system');

            $roles =  App\Role::Has('role_group','=',0)->whereIn('id', $users->role_users()->pluck('id') )->where('label', 'staff' )->get();

        @endphp

        @if( count( $roles_all ) >= 1 )
            <div class="panel-group" id="accordion_all_group" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingAllGroup">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion_all_group" href="#collapseAllGroup" aria-expanded="true" aria-controls="collapseAllGroup">
                                All Group <small><em>(อยู่ทุกระบบงาน)</em></small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseAllGroup" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingAllGroup">
                        <div class="panel-body">
                            <ul class="list-unstyled">
                                @foreach ( $roles_all as $role )
                                    
                                    <li>
                                        <div class="form-check">
                                            <div class="state icheckbox_flat-blue checked"></div>
                                            {!! !empty($role->name) ? $role->name:null !!}
                                        </div>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @foreach ($groups as $group)

            @php
                $accordion_id = uniqid('accordion_');
                $collapse_id = uniqid();
            @endphp

            <div class="panel-group" id="{!! $accordion_id !!}" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading{!! $collapse_id !!}">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#{!! $accordion_id !!}" href="#collapse_{!! $collapse_id !!}" aria-expanded="true" aria-controls="collapse_{!! $collapse_id !!}">
                            {!! $group->title !!} <small><em>({!! $group->description !!})</em></small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_{!! $collapse_id !!}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{!! $collapse_id !!}">
                        <div class="panel-body">
                            <ul class="list-unstyled">
                                @foreach ( $group->role()->WhereNotIn('id', App\Role::Has('role_group', '>=',  $setting_system  )->select('id') )->whereIn('id', $users->role_users()->pluck('id') )->get() as $role )
                                    <li>
                                        <div class="form-check">
                                            <div class="state icheckbox_flat-blue checked"></div>
                                            {!! !empty($role->name) ? $role->name:null !!}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

        @if( count( $roles ) >= 1 )
            <div class="panel-group" id="accordion_other" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOther">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion_other" href="#collapseOther" aria-expanded="true" aria-controls="collapseOther">
                                อื่นๆ <small><em>(ไม่มีระบบงาน)</em></small>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOther" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOther">
                        <div class="panel-body">
                            <ul class="list-unstyled">
                                @foreach ( $roles as $role )
                                    <li>
                                        <div class="form-check">
                                            <div class="state icheckbox_flat-blue checked"></div>
                                            {!! !empty($role->name) ? $role->name:null !!}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </div>
</div>
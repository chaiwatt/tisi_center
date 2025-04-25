@php

    $modalRole             = new App\Role;
    $modalRoleGroup        = new App\RoleGroup;
    $modalRoleSettingGroup = new App\RoleSettingGroup;

    $user_id               = Auth::user()->getKey();

    $setting_system        = $modalRoleSettingGroup->count();
    $roles_all             = $modalRole->Has('role_group', '>=',  $setting_system  )
                                        ->where(function ($query) use($modalRoleSettingGroup, $user_id ){

                                            $check =  Auth::user()->roles()->select('level')->get();

                                          
                                            //ที่ไม่ใช่ level 1
                                            if( $check->where('level', 1 )->count() == 0 ){
                                                //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                                $checklevel = $check->whereIn('level', [2,3] )->count();

                                             
                                                if( $checklevel >= 1 ){
                                                    $role_group = $modalRoleSettingGroup->whereHas('role.users',function($query) use($user_id){
                                                                                        $query->where('user_runrecno', $user_id );
                                                                                    })
                                                                                    ->select('id');

                                                    $query->whereHas('role_setting_group',function($query) use($role_group){
                                                                $query->whereIn( 'id' , $role_group );
                                                            });
                                                }
                                            }
                                         
                                        })
                                        ->when(!auth()->user()->isAdmin(), function ($query){
                                            $query->where('name', '!=', 'admin');
                                        })
                                        ->where('label', 'staff' )
                                        ->orderByRaw('CONVERT( name USING tis620 ) ASC')
                                        ->get();

    $groups                =  $modalRoleGroup->with('setting_system')
                                            ->whereHas('role', function($query) use ($setting_system , $modalRole ){
                                                $query->WhereNotIn('id', $modalRole->Has('role_group', '>=',  $setting_system  )->select('id') )->where('label', 'staff' );
                                            })
                                            ->where(function ($query) use($modalRoleSettingGroup, $user_id ){

                                                $check =  Auth::user()->roles()->select('level')->get();
                                                //ที่ไม่ใช่ level 1
                                                if( $check->where('level', 1 )->count() == 0 ){
                                                    //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                                    $checklevel = $check->whereIn('level', [2,3] )->count();
                                                    if( $checklevel >= 1 ){
                                                        $role_group = $modalRoleSettingGroup->whereHas('role.users',function($query) use($user_id){
                                                                                            $query->where('user_runrecno', $user_id );
                                                                                        })
                                                                                        ->select('id');

                                                        $query->whereIn( 'setting_systems_id' , $role_group );
                                                    }
                                                }

                                                })
                                            ->groupby('setting_systems_id')
                                            ->get()
                                            ->pluck('setting_system');

    $roles                 =   $modalRole->Has('role_group','=',0)
                                            ->where(function ($query) use($modalRoleSettingGroup, $user_id ){

                                                $check =  Auth::user()->roles()->select('level')->get();
                                                //ที่ไม่ใช่ level 1
                                                if( $check->where('level', 1 )->count() == 0 ){
                                                    //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                                    $checklevel = $check->whereIn('level', [2,3] )->count();
                                                    if( $checklevel >= 1 ){
                                                        $role_group = $modalRoleSettingGroup->whereHas('role.users',function($query) use($user_id){
                                                                                            $query->where('user_runrecno', $user_id );
                                                                                        })
                                                                                        ->select('id');

                                                        $query->whereHas('role_setting_group',function($query) use($role_group){
                                                                    $query->whereIn( 'id' , $role_group );
                                                                });
                                                    }
                                                }

                                            })
                                            ->when(!auth()->user()->isAdmin(), function ($query){
                                                $query->where('name', '!=', 'admin');
                                            })
                                            ->where('label', 'staff' )
                                            ->orderByRaw('CONVERT( name USING tis620 ) ASC')
                                            ->get();
@endphp
<div class="modal fade" id="UserGroupsModal" tabindex="-1" role="dialog" aria-labelledby="UserGroupsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="UserGroupsModalLabel">กําหนดสิทธิ์ผู้ใช้งาน</h4>
            </div>
            <div class="modal-body">
                <form id="modal_form_user_group" enctype="multipart/form-data" class="form-horizontal" onsubmit="return false">
                   
                    <div class="row">
                        <div class="col-md-6">
                            <p><h4>ข้อมูลผู้ใช้งาน</h4></p>
                            <table class="table table-striped" id="myTableSelect">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">ลำดับ</th>
                                        <th class="text-center" width="95%">ผู้ใช้งาน</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6" style="border-left: 1px solid #dee2e6 !important">
                            <p><h4>สิทธิ์การใช้งาน</h4></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('collapse_role', 'แสดงกลุ่มผู้ใช้งาน'.' :', ['class' => 'col-md-8 control-label']) !!}
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                {!! Form::checkbox('collapse_role', '1',  1, ['class' => 'collapse_role', 'data-color'=>'#449d44']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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
                                        <div id="collapseAllGroup" class="panel-collapse collapse in collapse_show_tab" role="tabpanel" aria-labelledby="headingAllGroup">
                                            <div class="panel-body">
                                                <ul class="list-unstyled">
                                                    @foreach ( $roles_all as $role )
                                                        
                                                        <li>
                                                            <div class="checkbox checkbox-success">
                                                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'form-control input_roles_checkbox input_role_id_'.($role->id).'']) !!}
                                                                <label for="roles">&nbsp;{{ $role->name }}</label>
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
                                        <div id="collapse_{!! $collapse_id !!}" class="panel-collapse collapse in collapse_show_tab" role="tabpanel" aria-labelledby="heading{!! $collapse_id !!}">
                                            <div class="panel-body">
                                                <ul class="list-unstyled">
                                                    @foreach ( $group->role()->WhereNotIn('id',  $modalRole->Has('role_group', '>=',  $setting_system  )->select('id') )->get() as $role )
                                                        <li>
                                                            <div class="checkbox checkbox-success">
                                                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'form-control input_roles_checkbox input_role_id_'.($role->id).'']) !!}
                                                                <label for="roles">&nbsp;{{ $role->name }}</label>
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
                                        <div id="collapseOther" class="panel-collapse collapse in collapse_show_tab" role="tabpanel" aria-labelledby="headingOther">
                                            <div class="panel-body">
                                                <ul class="list-unstyled">
                                                    @foreach ( $roles as $role )
                                                        <li>
                                                            <div class="checkbox checkbox-success">
                                                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'form-control input_roles_checkbox input_role_id_'.($role->id).'']) !!}
                                                                <label for="roles">&nbsp;{{ $role->name }}</label>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" id="btn_gen_role">บันทึก</button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $(document).on('change', '.collapse_role', function(){

                if($(this).is(':checked',true)){
                    $('.collapse_show_tab').collapse('show');
                } else {
                    $('.collapse_show_tab').collapse('hide');
                }

            });
            
            $('#btn_gen_role').click(function (e) {
                $('#modal_form_user_group').submit();   
            });

            $('#modal_form_user_group').parsley().on('field:validated', function() {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            }).on('form:submit', function() {

                var formData = new FormData($("#modal_form_user_group")[0]);
                    formData.append('_token', "{{ csrf_token() }}");

                var roles = $('.input_roles_checkbox:checked').length;

                if( roles > 0 ){
                    // $.LoadingOverlay("show", {
                    //     image       : "",
                    //     text  : "กำลังบันทึกข้อมูล กรุณารอสักครู่..."
                    // });
                    
                    $.ajax({
                        method: "POST",
                        url: "{{ url('/user/update_user_group') }}",
                        data: formData,
                        contentType : false,
                        processData : false,
                        success : function (obj){

                            if (obj.msg == "success") {
                                table.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'บันทึกสำเร็จ !',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                // $.LoadingOverlay("hide");
                                $('#UserGroupsModal').modal('hide');
                                $('#checkall').prop('checked', false);
                            }
                        }
                    });
                }else{
                    Swal.fire({
                        type: 'warning',
                        title: 'เลือกกลุ่มผู้ใช้อย่างน้อย 1 รายการ',
                        confirmButtonText: 'รับทราบ',
                    });
                }



            });
            
        });


    </script>
@endpush

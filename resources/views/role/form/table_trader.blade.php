<table class="table">
    <tr>
        <th></th>
        <th class="text-center">ดู</th>
        <th class="text-center">เพิ่ม</th>
        <th class="text-center">แก้ไข</th>
        <th class="text-center">ลบ</th>
    </tr>
    <tr>
        <td></td>
        <td class="text-center">
            <input type="checkbox" value="" name="all_view" id="all_view">
        </td>
        <td class="text-center">
            <input type="checkbox" value="" name="all_add" id="all_add">
        </td>
        <td class="text-center">
            <input type="checkbox" value="" name="all_edit" id="all_edit">
        </td>
        <td class="text-center">
            <input type="checkbox" value="" name="all_delete" id="all_delete">
        </td>
    </tr>

    @foreach (  HP::MenuTraderSidebar() as $section )

        @if( isset($section->_comment) )
            @php
                $data_tr_id = uniqid();
            @endphp
            <tr class="info">
                <td>
                    <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์{!! $section->_comment  !!}" data-control="{!!  $data_tr_id  !!}">
                        <i class="fa fa-angle-double-down"></i>
                    </button>
                    <b>{!! $section->_comment !!}</b>
                </td>
                <td colspan="5"></td>
            </tr>
            @foreach ( $section->items as $menu )
                @if( isset($menu->sub_menus) )
                    <tr data-menu="{!!  $data_tr_id  !!}" class="group" >
                        <td colspan="6"><b>{!! $menu->display !!}</b></td>
                    </tr>
                    @foreach( $menu->sub_menus as $sub_menus )
                        @if(property_exists($sub_menus, 'title'))
                            @php
                                $permissions = \App\Permission::permissionList($sub_menus->title);
                                $key = uniqid();
                            @endphp
                            @include( 'role.form.table_tr', ['data_tr_id' => $data_tr_id, 'role_permissions' => $role_permissions , 'menu' => $sub_menus, 'permissions' => $permissions, 'key', $key] )
                        @endif
                    @endforeach
                @else
                    @php
                        $permissions = \App\Permission::permissionList($menu->title);
                        $key = uniqid();
                    @endphp
                    @include( 'role.form.table_tr', ['data_tr_id' => $data_tr_id, 'role_permissions' => $role_permissions , 'menu' => $menu, 'permissions' => $permissions, 'key', $key] )
                @endif

            @endforeach
        @endif

    @endforeach

</table>


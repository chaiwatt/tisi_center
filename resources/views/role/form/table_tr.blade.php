<tr data-menu="{!!  $data_tr_id  !!}">
    <td>{!!  $menu->display !!}</td>
    <td class="text-center">
        
        <input
            @if( !empty($permissions['view']) && in_array($permissions['view'], $role_permissions))
                checked
            @endif

            @if(empty($permissions['view']))
                disabled
            @endif
            type="checkbox"
            class="@if(isset( $auth_allow_menu  ) && $auth_allow_menu ) view @endif"
            name="permissions[]"
            value="{{$permissions['view']}}">
    </td>
    <td class="text-center">
        <input
            @if( !empty($permissions['add']) && in_array($permissions['add'], $role_permissions))
                checked
            @endif
            @if(empty($permissions['add']))
                disabled
            @endif
            type="checkbox"
            class="@if(isset( $auth_allow_menu  ) && $auth_allow_menu ) add @endif"
            name="permissions[]"
            value="{{$permissions['add']}}">
    </td>
    <td class="text-center">
        <input
            @if( !empty($permissions['edit']) && in_array($permissions['edit'], $role_permissions))
                checked
            @endif
            @if(empty($permissions['edit']))
                disabled
            @endif
            type="checkbox"

            class="@if(isset( $auth_allow_menu  ) && $auth_allow_menu ) edit @endif"
            name="permissions[]"
            value="{{ $permissions['edit'] }}">
    </td>
    <td class="text-center">
        <input
            @if( !empty($permissions['delete']) &&  in_array($permissions['delete'], $role_permissions))
                checked
            @endif
            @if(empty($permissions['delete']))
                disabled
            @endif
            type="checkbox"
            class="@if(isset( $auth_allow_menu  ) && $auth_allow_menu ) delete @endif"
            name="permissions[]"
            value="{{ $permissions['delete'] }}">
    </td>
    <td class="text-center">

        <input
            @if(in_array( !empty($permissions['other']) &&  $permissions['other'], $role_permissions))
                checked
            @endif
            @if(empty($permissions['other']))
                disabled
            @endif
            type="checkbox"
            class="@if(isset( $auth_allow_menu  ) && $auth_allow_menu ) other @endif"
            name="permissions[]"
            value="{{ $permissions['other'] }}" style="display: none;">

        <!-- กรณีที่มีนอกเหนือจาก ดู เพิ่ม แก้ไข ลบ -->
        @if( !empty($permissions['printing']) || !empty($permissions['export']) || !empty($permissions['assign_work']) || !empty($permissions['poko_approve']) || !empty($permissions['poao_approve'])  || !empty($permissions['view_all']) || !empty($permissions['sync_to_elicense']) || !empty($permissions['follow_up_before']) || !empty($permissions['receive_inspection']))
            <button type="button" class="btn btn-sm view-menu-detail" title="อื่นๆ" data-control="{{"other_".$key}}">
                <i class="fa fa-angle-double-down"></i>
            </button>
        @endif

    </td>
</tr>

<!-- กรณีที่มีนอกเหนือจาก ดู เพิ่ม แก้ไข ลบ -->
@if( !empty($permissions['printing']) || !empty($permissions['export']) || !empty($permissions['assign_work']) || !empty($permissions['poko_approve']) || !empty($permissions['poao_approve'])  || !empty($permissions['view_all']) || !empty($permissions['sync_to_elicense']) || !empty($permissions['follow_up_before']) || !empty($permissions['receive_inspection']))
    <tr data-menu="report" data-submenu="{{"other_".$key}}">
        <td colspan="6">
            ตัวเลือกอื่นๆ &nbsp;
            <input
                @if(  !empty($permissions['printing']) && in_array($permissions['printing'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['printing']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['printing']}}"> พิมพ์ &nbsp;

            <input
                @if(  !empty($permissions['export']) && in_array($permissions['export'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['export']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['export']}}"> ส่งออก &nbsp;

            <input
                @if( !empty($permissions['assign_work']) && in_array($permissions['assign_work'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['assign_work']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['assign_work']}}"> มอบหมาย &nbsp;

            <input
                @if( !empty($permissions['poko_approve']) && in_array($permissions['poko_approve'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['poko_approve']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['poko_approve']}}"> ผก. อนุมัติ &nbsp;

            <input
                @if( !empty($permissions['poao_approve']) && in_array($permissions['poao_approve'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['poao_approve']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['poao_approve']}}"> ผอ. อนุมัติ &nbsp;

            <input
                @if( !empty($permissions['view_all']) && in_array($permissions['view_all'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['view_all']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['view_all']}}"> ดูได้ทุกรายการ <em>(ถ้าไม่เลือกดูเฉพาะรายการที่ได้รับมอบหมาย)</em>

            <input
                @if( !empty($permissions['sync_to_elicense']) && in_array($permissions['sync_to_elicense'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['sync_to_elicense']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['sync_to_elicense']}}"> อัพเดทข้อมูลไป e-License <br>

                
            <input
                @if( !empty($permissions['follow_up_before']) && in_array($permissions['follow_up_before'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['follow_up_before']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['follow_up_before']}}"> ตรวจติดตามก่อนกำหนด &nbsp;

            <input
                @if( !empty($permissions['receive_inspection']) && in_array($permissions['receive_inspection'], $role_permissions))
                    checked
                @endif
                @if(empty($permissions['receive_inspection']))
                    disabled
                @endif
                type="checkbox"
                class="other view-menu-detail"
                name="permissions[]"
                value="{{$permissions['receive_inspection']}}"> รับเรื่องตรวจติดตาม &nbsp;

        </td>
    </tr>
@endif
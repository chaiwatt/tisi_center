<table class="table">
    <tr>
        <th></th>
        <th class="text-center">ดู</th>
        <th class="text-center">เพิ่ม</th>
        <th class="text-center">แก้ไข</th>
        <th class="text-center">ลบ</th>
        <th class="text-center">อื่นๆ</th>
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
        <td class="text-center">
            <input type="checkbox" value="" name="all_other" id="all_other">
        </td>
    </tr>

    @foreach (  HP::MenuSidebar() as $section )
        @php
            $data_tr_id = uniqid();
        @endphp
        <tr class="info">
            <td colspan="6">
                <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์{!! $section->_comment  !!}" data-control="{!!  $data_tr_id  !!}">
                    <i class="fa fa-angle-double-down"></i>
                </button>
                <b>{!! $section->_comment  !!}</b>
            </td>
        </tr>
        @foreach ( $section->items as $menu )

            @if( isset($menu->sub_menus) )
                <tr data-menu="{!!  $data_tr_id  !!}" class="group" >
                    <td colspan="6">{!!  $menu->display !!}</td>
                </tr>
                @foreach( $menu->sub_menus as $sub_menus )
                    @php
                        $permissions = \App\Permission::permissionList($sub_menus->title);
                    @endphp
                    <tr data-menu="{!!  $data_tr_id  !!}">
                        <td>{!!  $sub_menus->display !!} ({!!  $sub_menus->title !!})</td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['view'], $role_permissions))
                                    checked
                                @endif
                                @if(is_null($permissions['view']))
                                    disabled
                                @endif
                                type="checkbox"
                                class="view"
                                name="permissions[]"
                                value="{{$permissions['view']}}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['add'], $role_permissions))
                                    checked
                                @endif
                                @if(is_null($permissions['add']))
                                    disabled
                                @endif
                                type="checkbox"
                                class="add"
                                name="permissions[]"
                                value="{{$permissions['add']}}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['edit'], $role_permissions))
                                    checked
                                @endif
                                @if(is_null($permissions['edit']))
                                    disabled
                                @endif
                                type="checkbox"
                                class="edit"
                                name="permissions[]"
                                value="{{ $permissions['edit'] }}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['delete'], $role_permissions))
                                    checked
                                @endif
                                @if(is_null($permissions['delete']))
                                    disabled
                                @endif
                                type="checkbox"
                                class="delete"
                                name="permissions[]"
                                value="{{ $permissions['delete'] }}">
                        </td>
                        <td class="text-center info">
                            <input
                                @if(in_array($permissions['other'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['other']))
                                disabled
                                @endif
                                type="checkbox"
                                class="other"
                                name="permissions[]"
                                value="{{ $permissions['other'] }}">
                        </td>
                    </tr>
                @endforeach
            @else
                @php
                    $permissions = \App\Permission::permissionList($menu->title);
                @endphp
                <tr data-menu="{!!  $data_tr_id  !!}">
                    <td>{!!  $menu->display !!}</td>
                    <td class="text-center">
                        <input
                              @if(in_array($permissions['view'], $role_permissions))
                                checked
                              @endif
                              @if(is_null($permissions['view']))
                                disabled
                              @endif
                              type="checkbox"
                              class="view"
                              name="permissions[]"
                              value="{{$permissions['view']}}">
                    </td>
                    <td class="text-center">
                    <input
                          @if(in_array($permissions['add'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['add']))
                            disabled
                          @endif
                          type="checkbox"
                          class="add"
                          name="permissions[]"
                          value="{{$permissions['add']}}">
                    </td>
                    <td class="text-center">
                    <input
                          @if(in_array($permissions['edit'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['edit']))
                            disabled
                          @endif
                          type="checkbox"
                          class="edit"
                          name="permissions[]"
                          value="{{ $permissions['edit'] }}">
                    </td>
                    <td class="text-center">
                        <input
                          @if(in_array($permissions['delete'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['delete']))
                            disabled
                          @endif
                          type="checkbox"
                          class="delete"
                          name="permissions[]"
                          value="{{ $permissions['delete'] }}">
                    </td>
                    <td class="text-center info">
                        <input
                            @if(in_array($permissions['other'], $role_permissions))
                              checked
                            @endif
                            @if(is_null($permissions['other']))
                              disabled
                            @endif
                            type="checkbox"
                            class="other"
                            name="permissions[]"
                            value="{{ $permissions['other'] }}">
                    </td>
                </tr>
            @endif
            
        @endforeach

    @endforeach

    <tr class="info">
        <td>
        <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ผู้ใช้งาน" data-control="user">
            <i class="fa fa-angle-double-down"></i>
        </button>
            <b>ผู้ใช้งาน</b>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

  @foreach($laravelMenuUser->menus as $section)
    @if(count(collect($section->items)) > 0)
      @foreach($section->items as $key=>$menu)

        <tr data-menu="user">
          <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
          @php
          $permissions = \App\Permission::permissionList($menu->title);
          @endphp

          <td class="text-center">
            <input
                  @if(in_array($permissions['view'], $role_permissions))
                    checked
                  @endif
                  @if(is_null($permissions['view']))
                    disabled
                  @endif
                  type="checkbox"
                  class="view"
                  name="permissions[]"
                  value="{{$permissions['view']}}">
          </td>
          <td class="text-center">
            <input
                  @if(in_array($permissions['add'], $role_permissions))
                    checked
                  @endif
                  @if(is_null($permissions['add']))
                    disabled
                  @endif
                  type="checkbox"
                  class="add"
                  name="permissions[]"
                  value="{{$permissions['add']}}">
          </td>
          <td class="text-center">
            <input
                  @if(in_array($permissions['edit'], $role_permissions))
                    checked
                  @endif
                  @if(is_null($permissions['edit']))
                    disabled
                  @endif
                  type="checkbox"
                  class="edit"
                  name="permissions[]"
                  value="{{$permissions['edit']}}">
          </td>
          <td class="text-center">
            <input
                  @if(in_array($permissions['delete'], $role_permissions))
                    checked
                  @endif
                  @if(is_null($permissions['delete']))
                    disabled
                  @endif
                  type="checkbox"
                  class="delete"
                  name="permissions[]"
                  value="{{$permissions['delete']}}">
          </td>
          <td class="text-center info">
              <input
                    @if(in_array($permissions['other'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['other']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other"
                    name="permissions[]"

                    value="{{$permissions['other']}}">
            </td>
        </tr>
      @endforeach
    @endif
  @endforeach

    <tr class="info">
        <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ผู้ใช้งาน" data-control="permission">
                <i class="fa fa-angle-double-down"></i>
            </button>
            <b>สิทธิ์การใช้งาน</b>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr data-menu="permission">
        <td><i class="mdi mdi-lock"></i>&nbsp;&nbsp;สิทธิ์การใช้งาน</td>

        @php
            $permissions = \App\Permission::permissionList('permission');
        @endphp

        <td class="text-center">
            <input
                  @if(in_array($permissions['view'], $role_permissions))
                    checked
                  @endif
                  @if(is_null($permissions['view']))
                    disabled
                  @endif
                  type="checkbox"
                  class="view"
                  name="permissions[]"
                  value="{{$permissions['view']}}">
        </td>
        <td class="text-center">
        <input
              @if(in_array($permissions['add'], $role_permissions))
                checked
              @endif
              @if(is_null($permissions['add']))
                disabled
              @endif
              type="checkbox"
              class="add"
              name="permissions[]"
              value="{{$permissions['add']}}">
        </td>
        <td class="text-center">
        <input
              @if(in_array($permissions['edit'], $role_permissions))
                checked
              @endif
              @if(is_null($permissions['edit']))
                disabled
              @endif
              type="checkbox"
              class="edit"
              name="permissions[]"
              value="{{ $permissions['edit'] }}">
        </td>
        <td class="text-center">
            <input
              @if(in_array($permissions['delete'], $role_permissions))
                checked
              @endif
              @if(is_null($permissions['delete']))
                disabled
              @endif
              type="checkbox"
              class="delete"
              name="permissions[]"
              value="{{ $permissions['delete'] }}">
        </td>
        <td class="text-center info">
            <input
                @if(in_array($permissions['other'], $role_permissions))
                  checked
                @endif
                @if(is_null($permissions['other']))
                  disabled
                @endif
                type="checkbox"
                class="other"
                name="permissions[]"
                value="{{ $permissions['other'] }}">
        </td>
    </tr>

    <tr class="info">
        <td>
        <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ข้อมูลพื้นฐาน (กก.)" data-control="btis">
            <i class="fa fa-angle-double-down"></i>
        </button>
        <b>เมนูข้อมูลพื้นฐาน (กก.)</b>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    @foreach($laravelAdminMenus->menus as $section)
        @if(count(collect($section->items)) > 0)
        @foreach($section->items as $key=>$menu)

            <tr data-menu="btis">
            <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
            @php
                $permissions = \App\Permission::permissionList($menu->title);
            @endphp

            <td class="text-center">
                <input
                    @if(in_array($permissions['view'], $role_permissions))
                        checked
                    @endif
                    @if(is_null($permissions['view']))
                        disabled
                    @endif
                    type="checkbox"
                    class="view"
                    name="permissions[]"
                    value="{{$permissions['view']}}">
            </td>
            <td class="text-center">
                <input
                    @if(in_array($permissions['add'], $role_permissions))
                        checked
                    @endif
                    @if(is_null($permissions['add']))
                        disabled
                    @endif
                    type="checkbox"
                    class="add"
                    name="permissions[]"
                    value="{{$permissions['add']}}">
            </td>
            <td class="text-center">
                <input
                    @if(in_array($permissions['edit'], $role_permissions))
                        checked
                    @endif
                    @if(is_null($permissions['edit']))
                        disabled
                    @endif
                    type="checkbox"
                    class="edit"
                    name="permissions[]"
                    value="{{$permissions['edit']}}">
            </td>
            <td class="text-center">
                <input
                    @if(in_array($permissions['delete'], $role_permissions))
                        checked
                    @endif
                    @if(is_null($permissions['delete']))
                        disabled
                    @endif
                    type="checkbox"
                    class="delete"
                    name="permissions[]"
                    value="{{$permissions['delete']}}">
            </td>
            <td class="text-center">
                <input
                        @if(in_array($permissions['other'], $role_permissions))
                        checked
                        @endif
                        @if(is_null($permissions['other']))
                        disabled
                        @endif
                        type="checkbox"
                        class="other"
                        name="permissions[]"
                        value="{{$permissions['other']}}">
                </td>
            </tr>
            @endforeach
        @endif
        @endforeach

    <tr class="info">
      <td>
        <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์กำหนดมาตรฐาน" data-control="tis">
          <i class="fa fa-angle-double-down"></i>
        </button>
        <b>กำหนดมาตรฐาน</b>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>

    @foreach($laravelMenuTis->menus as $section)
      @if(count(collect($section->items)) > 0)
        @foreach($section->items as $key=>$menu)

          <tr data-menu="tis">
            <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
            @php
            $permissions = \App\Permission::permissionList($menu->title);
            @endphp

            <td class="text-center">
              <input
                    @if(in_array($permissions['view'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['view']))
                      disabled
                    @endif
                    type="checkbox"
                    class="view"
                    name="permissions[]"
                    value="{{$permissions['view']}}">
            </td>
            <td class="text-center">
              <input
                    @if(in_array($permissions['add'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['add']))
                      disabled
                    @endif
                    type="checkbox"
                    class="add"
                    name="permissions[]"
                    value="{{$permissions['add']}}">
            </td>
            <td class="text-center">
              <input
                    @if(in_array($permissions['edit'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['edit']))
                      disabled
                    @endif
                    type="checkbox"
                    class="edit"
                    name="permissions[]"
                    value="{{$permissions['edit']}}">
            </td>
            <td class="text-center">
              <input
                    @if(in_array($permissions['delete'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['delete']))
                      disabled
                    @endif
                    type="checkbox"
                    class="delete"
                    name="permissions[]"
                    value="{{$permissions['delete']}}">
            </td>
            <td class="text-center">
                <input
                      @if(in_array($permissions['other'], $role_permissions))
                        checked
                      @endif
                      @if(is_null($permissions['other']))
                        disabled
                      @endif
                      type="checkbox"
                      class="other"
                      name="permissions[]"
                      value="{{$permissions['other']}}">
              </td>
          </tr>
          @endforeach
        @endif
      @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์กำหนดมาตรฐาน" data-control="bcertify">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>เมนูข้อมูลพื้นฐาน (สก.)</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuBcertify->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="bcertify">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์กำหนดมาตรฐาน" data-control="certify">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>รับรองระบบงาน</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuCertify->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="certify">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                {{-- <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td> --}}
                  <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other view-menu-detail"
                          name="permissions[]"
                          data-control="{{"certify_other".$key}}"
                          value="{{$permissions['other']}}">
                          @if(in_array($permissions['other'], $role_permissions))
                          <button type="button" class="view-menu-detail" title="certify_other" data-control="{{"certify_other".$key}}">
                              <i class="fa fa-angle-double-down"></i>
                            </button>
                        @endif

                  </td>
              </tr>
              @if(in_array($permissions['other'], $role_permissions))
              <tr data-menu="certify" data-submenu="{{"certify_other".$key}}">
                  <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                  <input
                    @if(in_array($permissions['assign_work'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['assign_work']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                    value="{{$permissions['assign_work']}}"> มอบหมาย
                  </td>
                </tr>
                @endif
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์กำหนดมาตรฐาน" data-control="sign_certify">
              <i class="fa fa-angle-double-down"></i>
            </button>
              <b>ลงนามอิเล็กทรอนิกส์ (สก.)</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        @foreach($laravelMenuSignCertify->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="sign_certify">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                  <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other view-menu-detail"
                          name="permissions[]"
                          data-control="{{"certify_other".$key}}"
                          value="{{$permissions['other']}}">
                          @if(in_array($permissions['other'], $role_permissions))
                          <button type="button" class="view-menu-detail" title="certify_other" data-control="{{"certify_other".$key}}">
                              <i class="fa fa-angle-double-down"></i>
                            </button>
                        @endif

                  </td>
              </tr>
              @if(in_array($permissions['other'], $role_permissions))
              <tr data-menu="certify" data-submenu="{{"certify_other".$key}}">
                  <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                  <input
                    @if(in_array($permissions['assign_work'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['assign_work']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                    value="{{$permissions['assign_work']}}"> มอบหมาย
                  </td>
                </tr>
                @endif
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์กำหนดมาตรฐาน" data-control="certificate">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>ติดตามใบรับรอง (สก.) </b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuCertificate->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="certificate">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                  <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other view-menu-detail"
                          name="permissions[]"
                          data-control="{{"certify_other".$key}}"
                          value="{{$permissions['other']}}">
                          @if(in_array($permissions['other'], $role_permissions))
                          <button type="button" class="view-menu-detail" title="certify_other" data-control="{{"certify_other".$key}}">
                              <i class="fa fa-angle-double-down"></i>
                            </button>
                        @endif

                  </td>
              </tr>
              @if(in_array($permissions['other'], $role_permissions))
              <tr data-menu="certify" data-submenu="{{"certify_other".$key}}">
                  <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                  <input
                    @if(in_array($permissions['assign_work'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['assign_work']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                    value="{{$permissions['assign_work']}}"> มอบหมาย
                  </td>
                </tr>
                @endif
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์กำหนดมาตรฐาน" data-control="standards">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>กำหนดมาตรฐานรับรอง (สก.) </b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuStandards->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="standards">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                  <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other view-menu-detail"
                          name="permissions[]"
                          data-control="{{"certify_other".$key}}"
                          value="{{$permissions['other']}}">
                          @if(in_array($permissions['other'], $role_permissions))
                          <button type="button" class="view-menu-detail" title="certify_other" data-control="{{"certify_other".$key}}">
                              <i class="fa fa-angle-double-down"></i>
                            </button>
                        @endif

                  </td>
              </tr>
              @if(in_array($permissions['other'], $role_permissions))
              <tr data-menu="certify" data-submenu="{{"certify_other".$key}}">
                  <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                  <input
                    @if(in_array($permissions['assign_work'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['assign_work']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                    value="{{$permissions['assign_work']}}"> มอบหมาย
                  </td>
                </tr>
                @endif
            @endforeach
          @endif
        @endforeach



        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ข้อมูลพื้นฐาน (กต.)" data-control="besurv">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>เมนูข้อมูลพื้นฐาน (กต.)</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuBesurv->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="besurv">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ตรวจติดตามออนไลน์" data-control="esurv">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>เมนูตรวจติดตามออนไลน์</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuEsurv->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="esurv">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other view-menu-detail"
                          name="permissions[]"
                          data-control="{{"testtest".$key}}"
                          value="{{$permissions['other']}}">
                          @if(in_array($permissions['other'], $role_permissions))
                          <button type="button" class="view-menu-detail" title="testtest" data-control="{{"testtest".$key}}">
                              <i class="fa fa-angle-double-down"></i>
                            </button>
                        @endif

                  </td>
              </tr>
              @if(in_array($permissions['other'], $role_permissions))
              <tr data-menu="esurv" data-submenu="{{"testtest".$key}}">
                  <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                  <input
                    @if(in_array($permissions['poko_approve'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['poko_approve']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                    value="{{$permissions['poko_approve']}}"> ผก. อนุมัติ &nbsp;
                  <input
                    @if(in_array($permissions['poao_approve'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['poao_approve']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                    value="{{$permissions['poao_approve']}}"> ผอ. อนุมัติ
                  </td>
                </tr>
                @endif
            @endforeach
          @endif

        @endforeach




        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ตรวจติดตามออนไลน์" data-control="rsurv">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>รายงาน (กต.)</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuRsurv->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="rsurv">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ค้นหาข้อมูลบุคคลธรรมดาและนิติบุคคล" data-control="i-industry">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>ค้นหาข้อมูลบุคคลธรรมดาและนิติบุคคล</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuiIndustry->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="i-industry">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ตั้งค่า" data-control="config">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>เมนูตั้งค่า</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuConfig->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="config">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
            <td>
                <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ตั้งค่า" data-control="ws">
                  <i class="fa fa-angle-double-down"></i>
                </button>
                <b>เว็บเซอร์วิส</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @foreach($laravelMenuWS->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="ws">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                    $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
          <td>
            <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์ข่าวประชาสัมพันธ์" data-control="blog">
              <i class="fa fa-angle-double-down"></i>
            </button>
            <b>ข่าวประชาสัมพันธ์</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>

        @foreach($laravelMenuBlog->menus as $section)
          @if(count(collect($section->items)) > 0)
            @foreach($section->items as $key=>$menu)

              <tr data-menu="blog">
                <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                  $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                <td class="text-center">
                  <input
                        @if(in_array($permissions['view'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view']))
                          disabled
                        @endif
                        type="checkbox"
                        class="view"
                        name="permissions[]"
                        value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['add'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['add']))
                          disabled
                        @endif
                        type="checkbox"
                        class="add"
                        name="permissions[]"
                        value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['edit'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['edit']))
                          disabled
                        @endif
                        type="checkbox"
                        class="edit"
                        name="permissions[]"
                        value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                  <input
                        @if(in_array($permissions['delete'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['delete']))
                          disabled
                        @endif
                        type="checkbox"
                        class="delete"
                        name="permissions[]"
                        value="{{$permissions['delete']}}">
                </td>
                <td class="text-center">
                    <input
                          @if(in_array($permissions['other'], $role_permissions))
                            checked
                          @endif
                          @if(is_null($permissions['other']))
                            disabled
                          @endif
                          type="checkbox"
                          class="other"
                          name="permissions[]"
                          value="{{$permissions['other']}}">
                  </td>
              </tr>
            @endforeach
          @endif
        @endforeach

        <tr class="info">
            <td>
                <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการข้อมูลพื้นฐาน มาตรา 5" data-control="bsection5">
                    <i class="fa fa-angle-double-down"></i>
                </button>
                <b>ข้อมูลพื้นฐาน มาตรา 5</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($laravelMenuBsection5->menus as $section)
            @if(count(collect($section->items)) > 0)
                @foreach($section->items as $key=>$menu)
                    <tr data-menu="bsection5">
                        <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                        @php
                            $permissions = \App\Permission::permissionList($menu->title);
                        @endphp

                        <td class="text-center">
                            <input
                                @if(in_array($permissions['view'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['view']))
                                disabled
                                @endif
                                type="checkbox"
                                class="view"
                                name="permissions[]"
                                value="{{$permissions['view']}}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['add'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['add']))
                                disabled
                                @endif
                                type="checkbox"
                                class="add"
                                name="permissions[]"
                                value="{{$permissions['add']}}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['edit'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['edit']))
                                disabled
                                @endif
                                type="checkbox"
                                class="edit"
                                name="permissions[]"
                                value="{{$permissions['edit']}}">
                        </td>
                        <td class="text-center">
                            <input
                                    @if(in_array($permissions['delete'], $role_permissions))
                                    checked
                                    @endif
                                    @if(is_null($permissions['delete']))
                                    disabled
                                    @endif
                                    type="checkbox"
                                    class="delete"
                                    name="permissions[]"
                                    value="{{$permissions['delete']}}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['other'], $role_permissions))
                                    checked
                                @endif
                                @if(is_null($permissions['other']))
                                    disabled
                                @endif
                                type="checkbox"
                                class="other view-menu-detail"
                                name="permissions[]"
                                data-control="{{"section5_other".$key}}"
                                value="{{$permissions['other']}}">

                                @if(in_array($permissions['other'], $role_permissions))
                                    <button type="button" class="view-menu-detail" title="section5_other" data-control="{{"section5_other".$key}}">
                                        <i class="fa fa-angle-double-down"></i>
                                    </button>
                                @endif
                        </td>

                    </tr>
                    @if(in_array($permissions['other'], $role_permissions))
                        <tr data-menu="bsection5" data-submenu="{{"section5_other".$key}}">
                            <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                                <input
                                    @if(in_array($permissions['assign_work'], $role_permissions))
                                    checked
                                    @endif
                                    @if(is_null($permissions['assign_work']))
                                    disabled
                                    @endif
                                    type="checkbox"
                                    class="other view-menu-detail"
                                    name="permissions[]"
                                    value="{{$permissions['assign_work']}}"> มอบหมาย
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach

        <tr class="info">
          <td>
                    <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการข้อมูลพื้นฐาน มาตรา 5" data-control="cerreport">
                        <i class="fa fa-angle-double-down"></i>
                    </button>
                    <b>รายงาน</b>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
            @foreach($laravelMenuCerreport->menus as $section)
                @if(count(collect($section->items)) > 0)
                    @foreach($section->items as $key=>$menu)
                        <tr data-menu="cerreport">
                            <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                @php
                    $permissions = \App\Permission::permissionList($menu->title);
                @endphp

                            <td class="text-center">
                    <input
                    @if(in_array($permissions['view'], $role_permissions))
                    checked
                    @endif
                    @if(is_null($permissions['view']))
                    disabled
                    @endif
                    type="checkbox"
                    class="view"
                    name="permissions[]"
                    value="{{$permissions['view']}}">
                </td>
                <td class="text-center">
                    <input
                    @if(in_array($permissions['add'], $role_permissions))
                    checked
                    @endif
                    @if(is_null($permissions['add']))
                    disabled
                    @endif
                    type="checkbox"
                    class="add"
                    name="permissions[]"
                    value="{{$permissions['add']}}">
                </td>
                <td class="text-center">
                    <input
                    @if(in_array($permissions['edit'], $role_permissions))
                    checked
                    @endif
                    @if(is_null($permissions['edit']))
                    disabled
                    @endif
                    type="checkbox"
                    class="edit"
                    name="permissions[]"
                    value="{{$permissions['edit']}}">
                </td>
                <td class="text-center">
                                <input
                                        @if(in_array($permissions['delete'], $role_permissions))
                                        checked
                                        @endif
                                        @if(is_null($permissions['delete']))
                                        disabled
                                        @endif
                                        type="checkbox"
                                        class="delete"
                                        name="permissions[]"
                                        value="{{$permissions['delete']}}">
                            </td>
                            <td class="text-center">
                  <input
                    @if(in_array($permissions['other'], $role_permissions))
                      checked
                    @endif
                    @if(is_null($permissions['other']))
                      disabled
                    @endif
                    type="checkbox"
                    class="other view-menu-detail"
                    name="permissions[]"
                                    data-control="{{"section5_other".$key}}"
                                    value="{{$permissions['other']}}">

                                    @if(in_array($permissions['other'], $role_permissions))
                                        <button type="button" class="view-menu-detail" title="section5_other" data-control="{{"section5_other".$key}}">
                                            <i class="fa fa-angle-double-down"></i>
                                        </button>
                                    @endif
                </td>

                        </tr>
                        @if(in_array($permissions['other'], $role_permissions))
                            <tr data-menu="cerreport" data-submenu="{{"section5_other".$key}}">
                                <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                                    <input
                                        @if(in_array($permissions['assign_work'], $role_permissions))
                                        checked
                                        @endif
                                        @if(is_null($permissions['assign_work']))
                                        disabled
                                        @endif
                                        type="checkbox"
                                        class="other view-menu-detail"
                                        name="permissions[]"
                                        value="{{$permissions['assign_work']}}"> มอบหมาย
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach

        <tr class="info">
            <td>
              <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์รับคำขอสำหรับ IB และ LAB" data-control="section5">
                <i class="fa fa-angle-double-down"></i>
              </button>
              <b>รับคำขอสำหรับ IB และ LAB</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
         </tr>
        @foreach($laravelMenuSection5->menus as $section)
            @if(count(collect($section->items)) > 0)
                @foreach($section->items as $key=>$menu)
                    <tr data-menu="section5">
                        <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                        @php
                        $permissions = \App\Permission::permissionList($menu->title);
                        @endphp

                        <td class="text-center">
                        <input
                                @if(in_array($permissions['view'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['view']))
                                disabled
                                @endif
                                type="checkbox"
                                class="view"
                                name="permissions[]"
                                value="{{$permissions['view']}}">
                        </td>
                        <td class="text-center">
                        <input
                                @if(in_array($permissions['add'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['add']))
                                disabled
                                @endif
                                type="checkbox"
                                class="add"
                                name="permissions[]"
                                value="{{$permissions['add']}}">
                        </td>
                        <td class="text-center">
                        <input
                                @if(in_array($permissions['edit'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['edit']))
                                disabled
                                @endif
                                type="checkbox"
                                class="edit"
                                name="permissions[]"
                                value="{{$permissions['edit']}}">
                        </td>
                        <td class="text-center">
                        <input
                                @if(in_array($permissions['delete'], $role_permissions))
                                checked
                                @endif
                                @if(is_null($permissions['delete']))
                                disabled
                                @endif
                                type="checkbox"
                                class="delete"
                                name="permissions[]"
                                value="{{$permissions['delete']}}">
                        </td>
                        <td class="text-center">
                            <input
                                @if(in_array($permissions['other'], $role_permissions))
                                    checked
                                @endif
                                @if(is_null($permissions['other']))
                                    disabled
                                @endif
                                type="checkbox"
                                class="other view-menu-detail"
                                name="permissions[]"
                data-control="{{"section5_other".$key}}"
                                value="{{$permissions['other']}}">
                @if(in_array($permissions['other'], $role_permissions))
                  <button type="button" class="view-menu-detail" title="section5_other" data-control="{{"section5_other".$key}}">
                    <i class="fa fa-angle-double-down"></i>
                  </button>
                @endif
                        </td>
                    </tr>

          @if(in_array($permissions['other'], $role_permissions))
            <tr data-menu="section5" data-submenu="{{"section5_other".$key}}">
                <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                    <input
                        @if(in_array($permissions['assign_work'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['assign_work']))
                          disabled
                        @endif
                        type="checkbox"
                        class="other view-menu-detail"
                        name="permissions[]"
                        value="{{$permissions['assign_work']}}"> มอบหมาย

                    <input
                        @if(in_array($permissions['view_all'], $role_permissions))
                          checked
                        @endif
                        @if(is_null($permissions['view_all']))
                          disabled
                        @endif
                        type="checkbox"
                        class="other view-menu-detail"
                        name="permissions[]"
                        value="{{$permissions['view_all']}}"> แสดงทุกรายการคำขอ (ถ้าไม่เลือกแสดงเฉพาะที่ได้รับมอบหมาย)
              </td>
            </tr>
          @endif
                @endforeach
            @endif
        @endforeach

        <tr class="info">
            <td>
              <button type="button" class="btn btn-primary btn-circle view-menu-detail" title="คลิกเพื่อดูรายการสิทธิ์รายงาน" data-control="report">
                <i class="fa fa-angle-double-down"></i>
              </button>
              <b>รายงาน</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @foreach($laravelMenuReport->menus as $section)
            @if(count(collect($section->items)) > 0)
                @foreach($section->items as $key=>$menu)
                <tr data-menu="report">
                    <td><i class="{{ $menu->icon }}"></i>&nbsp;&nbsp;{{ $menu->display }}</td>
                    @php
                    $permissions = \App\Permission::permissionList($menu->title);
                    @endphp

                    <td class="text-center">
                    <input
                            @if(in_array($permissions['view'], $role_permissions))
                            checked
                            @endif
                            @if(is_null($permissions['view']))
                            disabled
                            @endif
                            type="checkbox"
                            class="view"
                            name="permissions[]"
                            value="{{$permissions['view']}}">
                    </td>
                    <td class="text-center">
                    <input
                            @if(in_array($permissions['add'], $role_permissions))
                            checked
                            @endif
                            @if(is_null($permissions['add']))
                            disabled
                            @endif
                            type="checkbox"
                            class="add"
                            name="permissions[]"
                            value="{{$permissions['add']}}">
                    </td>
                    <td class="text-center">
                    <input
                            @if(in_array($permissions['edit'], $role_permissions))
                            checked
                            @endif
                            @if(is_null($permissions['edit']))
                            disabled
                            @endif
                            type="checkbox"
                            class="edit"
                            name="permissions[]"
                            value="{{$permissions['edit']}}">
                    </td>
                    <td class="text-center">
                    <input
                            @if(in_array($permissions['delete'], $role_permissions))
                            checked
                            @endif
                            @if(is_null($permissions['delete']))
                            disabled
                            @endif
                            type="checkbox"
                            class="delete"
                            name="permissions[]"
                            value="{{$permissions['delete']}}">
                    </td>
                    <td class="text-center">
                        <input
                            @if(in_array($permissions['other'], $role_permissions))
                                checked
                            @endif
                            @if(is_null($permissions['other']))
                                disabled
                            @endif
                            type="checkbox"
                            class="other view-menu-detail"
                            name="permissions[]"
                            data-control="{{"report_other".$key}}"
                                            value="{{$permissions['other']}}">
                        @if(in_array($permissions['other'], $role_permissions))
                            <button type="button" class="view-menu-detail" title="report_other" data-control="{{"report_other".$key}}">
                                <i class="fa fa-angle-double-down"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @if(in_array($permissions['other'], $role_permissions))
                    <tr data-menu="report" data-submenu="{{"report_other".$key}}">
                        <td colspan="6">ตัวเลือกอื่นๆ &nbsp;
                        <input
                            @if(in_array($permissions['assign_work'], $role_permissions))
                            checked
                            @endif
                            @if(is_null($permissions['assign_work']))
                            disabled
                            @endif
                            type="checkbox"
                            class="other view-menu-detail"
                            name="permissions[]"
                            value="{{$permissions['assign_work']}}"> มอบหมาย
                        </td>
                    </tr>
                @endif
                @endforeach
            @endif
        @endforeach

</table>


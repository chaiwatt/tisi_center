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

		</td>
	</tr>
	@php
		$arr_permissions   = $permissions->pluck('id', 'name')->toArray();

		// dd($arr_permissions);

		$auth_role         = Auth::user()->roles()->pluck('level')->toArray();

		$check_level_admin = in_array( 1 , $auth_role )?true:false; //เช็คว่าผู้login มี role.level 1 ไหม
		//เช็คว่าเป็น level 1
		if( $check_level_admin  ){
			$menus = 'all'; //แสดงทั้งหมด
		}else{
			$menus = [];
			foreach ( Auth::user()->roles()->get() as $Irole) {
				$lsit_json = $Irole->role_setting_group->pluck('menu_jsons')->toArray();//แสดงเฉพาะเมนูที่อยู่ใน roles setting
				$menus     = array_merge( $menus ,$lsit_json );
			}
			$menus[] = 'new-menu-search.json'; 
			$menus[] = 'new-menu-users.json'; 
			$menus   = array_diff( $menus, [null] );
		}
	@endphp
	@foreach (  HP::MenuSidebar(false) as $section )
  
		@if( isset($section->_comment) )
		
			@php
				$data_tr_id      = uniqid();
				$auth_allow_menu = is_array( $menus ) && !in_array( $section->file , $menus )?false:true;//เช็คว่าให้ใช้ค่า class checkbox all ได้หรือไม่
			@endphp
			<tr class="info" @if( is_array( $menus ) && !in_array( $section->file , $menus ) ) style="display:none;"   @endif >
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
						<td colspan="6"><b>{!! $menu->display !!}</b>
						
							{{-- @if ($menu->display == 'ลงนามอิเล็กทรอนิกส์')
							ddddd
							@endif  --}}
						
						
						</td>
					</tr>
					@foreach( $menu->sub_menus as $sub_menus )

						@if(property_exists($sub_menus, 'title'))
							@php
								$permissions = HP::permissionList( $sub_menus->title , $arr_permissions   );
								$key         = uniqid();
							@endphp
							
							{{-- @if ($sub_menus->title == 'AssessmentReportAssignment')
							{{ $arr_permissions}}
							@endif --}}
							@include( 'role.form.table_tr', ['data_tr_id' => $data_tr_id, 'role_permissions' => $role_permissions , 'menu' => $sub_menus, 'permissions' => $permissions, 'key' => $key, 'auth_allow_menu' => $auth_allow_menu  ] )
						@endif
					@endforeach
				@else
					@php
						$permissions = HP::permissionList( $menu->title , $arr_permissions   );
						$key         = uniqid();
					@endphp
					@include( 'role.form.table_tr', ['data_tr_id' => $data_tr_id, 'role_permissions' => $role_permissions , 'menu' => $menu, 'permissions' => $permissions, 'key' => $key, 'auth_allow_menu' => $auth_allow_menu] )
				@endif

			@endforeach
		@endif

	@endforeach

</table>


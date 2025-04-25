<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Role;
use App\RoleUser;
use App\RoleGroup;
use App\RoleSettingGroup;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Support\Facades\Auth;
use HP;

class RoleController extends Controller
{


    function data_list(Request $request)
    {
        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        $filter_group  = $request->input('filter_group');

        $query = Role::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                        $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                ->OrwhereHas('role_setting_group', function ($query) use($search_full) {
                                                    $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                });
                                    });
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        return $query->where('state', $filter_status);
                                    }else{
                                        return $query->where('state', '<>', 1)->orWhereNull('state');
                                    }
                                })
                                ->when($filter_group, function ($query, $filter_group){
                                    $query->whereHas('role_setting_group', function($query) use ($filter_group){
                                        $query->where('id', $filter_group);
                                    });
                                })
                                ->when(!auth()->user()->isAdmin(), function ($query){
                                    $query->where('name', '!=', 'admin');
                                })
                                ->where(function ($query){

                                    $check =  Auth::user()->roles()->select('level')->get();
                                    //ที่ไม่ใช่ level 1
                                    if( $check->where('level', 1 )->count() == 0 ){
                                        //หาที่เฉพาะที่อยู่ใน ระบบงาน
                                        $checklevel = $check->whereIn('level', [2,3] )->count();
                                        if( $checklevel >= 1 ){
                                            $role_group = RoleSettingGroup::whereHas('role.users',function($query){
                                                                                $user_id = Auth::user()->getKey();
                                                                                $query->where('user_runrecno', $user_id );
                                                                            })
                                                                            ->select('id');

                                            $query->whereHas('role_setting_group',function($query) use($role_group){
                                                        $query->whereIn( 'id' , $role_group );
                                                    });
                                        }
                                    }

                                });

        return Datatables::of($query)
                            ->addIndexColumn()      
                            ->addColumn('name', function ($item) {
                                return !empty($item->name)?$item->name:'-';
                            })
                            ->addColumn('label', function ($item) {
                                return !empty($item->label)?($item->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ'):'-';
                            })
                            ->addColumn('status', function ($item) {
                                return  $item->state=='1'?'ใช้งาน':'ไม่ใช้งาน' ;
                            })
                            ->addColumn('updated_name', function ($item) {
                                return  $item->LatestUpdate;
                            })
                            ->addColumn('group', function ($item) {
                                return  @$item->GroupName;
                            })
                            ->addColumn('action', function ($item) {

                                $model = str_slug('permission','-');

                                $btn = '';
                                if(auth()->user()->can('edit-'.$model)) {
                                    $btn .= ' <a class="btn btn-info btn-sm waves-effect waves-light" href="'.url('role/edit/'.$item->id).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> แก้ไข</a>';
                                    $btn .= ' <a class="btn btn-inverse btn-sm waves-effect waves-light" href="'.url('role/edit_right/'.$item->id).'"><i class="fa fa-users" aria-hidden="true"></i> กำหนดผู้ใช้</a>';
                                }

                                if(auth()->user()->can('delete-'.$model)) {
                                    $btn .= ' <a class="delete btn btn-danger btn-sm waves-effect waves-light" ref="'.url('role/delete/'.$item->id).'"><i class="fa fa-trash-o"></i> ลบ</a>';
                                }

                                return $btn;

                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'tools', 'title', 'type', 'updated_name'])
                            ->make(true);


    }

    public function getIndex(Request $request){

        $model = str_slug('permission','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('role.index');
        }

        abort(403);

    }

    public function create(){
        $model = str_slug('permission','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('role.create');
        }

        abort(403);
    }

    public function save(Request $request){

        $model = str_slug('permission','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request,[
               'name' => 'required',
               'label' => 'required'
            ]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create

            $role = Role::firstOrCreate(['name' => $request->name, 'label' => $request->label, 'created_by' => $request->created_by]);
 
            $requestData = $request->all();

            $role->update([
                            'description' => ( !empty($requestData['description'])?$requestData['description']:null ), 
                            'state' => ( !empty($requestData['state'])?$requestData['state']:0 ) ,
                            // 'group' => (!empty($requestData['group'])? json_encode( $requestData['group'], JSON_NUMERIC_CHECK):null )
                        ]);

            if( isset( $requestData['group'] ) ){
                $setting_system = $requestData['group'];
                RoleGroup::where('role_id', $role->id )->delete();
                foreach( $setting_system  as $item ){
                    $data                     = new RoleGroup;
                    $data->role_id            = $role->id;
                    $data->setting_systems_id = $item;
                    $data->save();
                }
            }
              
            
            if($request->permissions != '' || $request->permissions != null){
                $role->permissions()->sync($request->permissions);
            }
            Session::flash('message','Role has been added');
            return back();
        }

        abort(403);
    }

    public function delete(Request $request){
        $model = str_slug('permission','-');
        if(auth()->user()->can('delete-'.$model)) {
            $role = Role::findOrfail($request->id);

            if($role->name=='admin' && !auth()->user()->isAdmin()){//admin ให้ admin แก้ได้เท่านั้น
                abort(403);
            }

            $role->delete();
            Session::flash('message','Role has been deleted');
            return back();
        }

        abort(403);

    }

    public function edit(Request $request){
        $model = str_slug('permission','-');
        if(auth()->user()->can('edit-'.$model)) {

            $role = Role::findOrfail($request->id);
            if($role->name=='admin' && !auth()->user()->isAdmin()){//admin ให้ admin แก้ได้เท่านั้น
                abort(403);
            }

            $role_permissions = $role->permissions()->pluck('id')->toArray();

            $permissions = Permission::all();
            $blog_permissions = Permission::permissionList('blog');

            return view('role.edit', compact('role', 'permissions', 'role_permissions', 'blog_permissions'));

        }

        abort(403);
    }

    public function update(Request $request){
        
        $model = str_slug('permission','-');
        if(auth()->user()->can('edit-'.$model)) {

            $this->validate($request,[
                'name' => 'required'
            ]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user create

            $role = Role::findOrfail($request->id);

            if($role->name=='admin' && !auth()->user()->isAdmin()){//admin ให้ admin แก้ได้เท่านั้น
                abort(403);
            }

            $role->name = $request->name;
            $role->updated_by = $request->updated_by;
            $role->save();

            $requestData = $request->all();

            $role->update([
                            'description' => ( !empty($requestData['description'])?$requestData['description']:null ), 
                            'state' => ( !empty($requestData['state'])?$requestData['state']:0 ) ,
                            'level' => ( !empty($requestData['level'])?$requestData['level']:3 ) ,
                            // 'group' => (!empty($requestData['group'])? json_encode( $requestData['group'], JSON_NUMERIC_CHECK):null )
                        ]);
            if( isset( $requestData['group'] ) ){
                $setting_system = $requestData['group'];
                RoleGroup::where('role_id', $role->id )->delete();
                foreach( $setting_system  as $item ){
                    $data                     = new RoleGroup;
                    $data->role_id            = $role->id;
                    $data->setting_systems_id = $item;
                    $data->save();
                }
            }else{
                RoleGroup::where('role_id', $role->id )->delete();
            }
                  
            if($request->permissions == null){
                $role->permissions()->detach();
            }else{
                $role->permissions()->sync($request->permissions);
            }

            Session::flash('message','Role has been updated');
            return redirect('role-management');
        }

        abort(403);
    }

    public function edit_right(Request $request){ //แก้ไขสิทธิ์

        $model = str_slug('permission','-');
        if(auth()->user()->can('edit-'.$model)) {
            $role = Role::findOrfail($request->id);

            if($role->name=='admin' && !auth()->user()->isAdmin()){//admin ให้ admin แก้ได้เท่านั้น
                abort(403);
            }

            $to   = [];
            $from = [];

            if($role->label=='trader'){//ฝั่งผปก.

              $trader_all = SSO_User::orderby('name')->pluck('name', 'id');
              $user_roles = RoleUser::where('role_id', $request->id)->pluck('user_id', 'user_id')->toArray();

              foreach ($trader_all as $key => $value) {//แบ่งอยู่ในกลุ่มกับไม่อยู่
                if(in_array($key, $user_roles)){
                  $to[$key] = $value;
                }else{
                  $from[$key] = $value;
                }
              }

            }else{

              $user_all = User::select(
                                  DB::raw("CONCAT(reg_fname, ' ', reg_lname) AS name, runrecno")
                                )->pluck('name', 'runrecno');

              $user_roles = RoleUser::where('role_id', $request->id)->pluck('user_runrecno', 'user_runrecno')->toArray();

              foreach ($user_all as $key => $value) {//แบ่งอยู่ในกลุ่มกับไม่อยู่
                if(in_array($key, $user_roles)){
                  $to[$key] = $value;
                }else{
                  $from[$key] = $value;
                }
              }

            }

            return view('role.edit_right', compact('role', 'to', 'from'));
        }

        abort(403);
    }

    public function update_right(Request $request){

        $model = str_slug('permission','-');
        if(auth()->user()->can('edit-'.$model)) {

            $role = Role::findOrfail($request->id);

            if($role->name=='admin' && !auth()->user()->isAdmin()){//admin ให้ admin แก้ได้เท่านั้น
                abort(403);
            }

            if($role->label=='trader'){//ผปก.

              //ลบออกจากกลุ่ม
              RoleUser::whereNotNull('user_id')->where('role_id', $request->id)->delete();

              //เพิ่มเข้ากลุ่ม
              $input = [];
              foreach ((array)$request['to'] as $key => $value) {
                $input[] = ['role_id' => $request->id, 'user_id' => $value];
              }

              RoleUser::insert($input);

            }elseif($role->label=='staff'){//เจ้าหน้าที่

              //ลบออกจากกลุ่ม
              RoleUser::whereNotNull('user_runrecno')->where('role_id', $request->id)->delete();

              //เพิ่มเข้ากลุ่ม
              $input = [];
              foreach ((array)$request['to'] as $key => $value) {
                $input[] = ['role_id' => $request->id, 'user_runrecno' => $value];
              }

              RoleUser::insert($input);

            }

            Session::flash('message','Role has been updated');
            return redirect('role-management');
        }

        abort(403);
    }

}

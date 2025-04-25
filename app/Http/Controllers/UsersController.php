<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Role;
use App\RoleUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Cache\RetrievesMultipleKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use Storage;
use File;
use Imagick;
use Response;


class UsersController extends Controller
{

    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('user','-');
    }

    public function data_list(Request $request)
    {

        if(auth()->user()->can('view-'.$this->permission)) {

            $filter_search         = $request->input('filter_search');
            $filter_sub_department = $request->input('filter_sub_department');
            $filter_roles          = $request->input('filter_roles');
            $filter_department     = $request->input('filter_department');

            $can_edit = auth()->user()->can('edit-'.$this->permission);

            $query = User::query()->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->where( function($query) use($search_full) {
                                                    $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%")
                                                            ->Orwhere(DB::Raw("REPLACE(reg_13ID,' ','')"),  'LIKE', "%$search_full%")
                                                            ->Orwhere(DB::Raw("REPLACE(reg_13ID,'-','')"),  'LIKE', "%$search_full%")
                                                            ->Orwhere(DB::Raw("REPLACE(reg_email,' ','')"),  'LIKE', "%$search_full%");
                                                });
                                    })
                                    ->when($filter_sub_department, function ($query, $filter_sub_department){
                                        $query->where('reg_subdepart', $filter_sub_department);
                                    })
                                    ->when($filter_roles, function ($query, $filter_roles){
                                        $query->whereHas('data_list_roles', function($query) use ($filter_roles){
                                            $query->where('role_id', $filter_roles);
                                        });
                                    })
                                    ->when($filter_department, function ($query, $filter_department){
                                        $query->whereHas('subdepart', function($query) use ($filter_department){
                                            $query->where('did', $filter_department);
                                        });
                                    });

            return Datatables::of($query)
                                    ->addIndexColumn()  
                                    ->addColumn('checkbox', function ($item) {
                                        return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-taxid="'.($item->reg_13ID).'" data-name="'.( @$item->reg_fname.' '.@$item->reg_lname ) .'" value="'. $item->getKey() .'">';
                                    })    
                                    ->addColumn('reg_fname', function ($item) {
                                        return ($item->reg_fname.' '.$item->reg_lname).'<div>('.($item->reg_13ID).')</div>';
                                    })
                                    ->addColumn('reg_email', function ($item) {
                                        return @$item->reg_email;
                                    })
                                    ->addColumn('sub_departname', function ($item) {
                                        return @$item->subdepart->sub_departname  ?? null;
                                    })
                                    ->addColumn('roles', function ($item) {
                                        $roles = $item->roles()->count();
                                        if($roles >= 1){
                                            return   @$item->GroupRoleName;
                                        }else{
                                        return '<i class="text-danger">ไม่มีกลุ่ม</i>';
                                        }
                                    })
                                    ->addColumn('action', function ($item) use ($can_edit) {

                                        $btn = '';
                                        if($can_edit){
                                            $btn .= ' <a class="btn btn-info btn-sm" href="'.url('user/edit/'.$item->getKey()).'"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> แก้ไข </a>';
                                        }

                                        if( ( $item->getKey() != auth()->user()->getKey() )&& auth()->user()->can('delete-'.$this->permission) ){
                                            $btn .= '  <a class="delete btn btn-danger btn-sm" href="'.url('user/delete/'.$item->getKey()).'"><i class="fa fa-trash-o"></i> ลบ</a>';
                                        }

                                        return $btn;
        
                                    })
                                    ->rawColumns(['checkbox', 'reg_fname', 'roles', 'action'])
                                    ->make(true); 

        }
        abort(403);

    }

    public function getIndex(Request $request){
        if(auth()->user()->can('view-'.$this->permission)) {
            return view('users.index');
        }
        abort(403);
    }

    public function create(){
        if(auth()->user()->can('add-'.$this->permission)) {
            $roles = Role::all();
            return view('users.create',compact('roles'));
        }
        abort(403);
    }

    public function save(Request $request){

        if(auth()->user()->can('add-'.$this->permission)) {

            $this->validate($request, [
            'reg_fname' => 'required',
            'reg_lname' => 'required',
            'reg_email' => 'required',
            'password' => 'required'
            ]);

            $input = $request->all();
            if($request->password){
                $input['reg_pword'] = md5($request->password);
                $input['reg_unmd5'] = $request->password;
            }
            // $input['reg_13ID'] = preg_replace("/[^a-z\d]/i", '', $input['reg_13ID']);

            $user =  new User;
            $user = $user->create($input);

            if(isset($input['roles'])){
                $roles = [];
                $check = [];
                foreach ((array)$input['roles'] as $role_id) {
                
                    if( !array_key_exists(  $role_id, $check ) ){
                        $roles[] = ['role_id' => $role_id, 'user_runrecno' => $user->getKey()];
                        $check[  $role_id ] =  $role_id;
                    }
                }

                RoleUser::insert($roles);
            }

            return redirect('users')->with('message', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function edit(Request $request){

        if(auth()->user()->can('edit-'.$this->permission)) {
            $user = User::findOrfail($request->id);
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        }
        abort(403);
    }

    public function update(Request $request){

        if(auth()->user()->can('edit-'.$this->permission)) {

            $this->validate($request,[
                'reg_fname' => 'required',
                'reg_lname' => 'required',
                'reg_email' => 'required'
            ]);

            $input = $request->all();
            if($request->password){
                $input['reg_pword'] = md5($request->password);
                $input['reg_unmd5'] = $request->password;
            }
            // $input['reg_13ID'] = preg_replace("/[^a-z\d]/i", '', $input['reg_13ID']);

            $user =  User::findOrfail($request->id);
            $user->update($input);

            //บันทึก Roles
            RoleUser::where('user_runrecno', $request->id)->delete();//ลบออกก่อน

            if(isset($input['roles'])){
            $roles = [];
            $check = [];
            foreach ((array)$input['roles'] as $role_id) {

                if( !array_key_exists(  $role_id, $check ) ){
                    $roles[] = ['role_id' => $role_id, 'user_runrecno' => $request->id];
                    $check[  $role_id ] =  $role_id;
                }
               
            }

            RoleUser::insert($roles);
            }

            Session::flash('message', 'บันทึกข้อมูลเรียบร้อยแล้ว');

            return redirect()->back();

        }
        abort(403);
    }

    //ลบ (ลงถังขยะ)
    public function delete($id){

        if(auth()->user()->can('delete-'.$this->permission)) {

            $user = User::findOrfail($id);
            $user->delete();

            $user->status = '0';
            $user->save();

            Session::flash('message','User has been deleted');
            return back();
        }
        abort(403);
    }

    public function getDeletedUsers(){
        if(auth()->user()->can('delete-'.$this->permission)) {
            $users = User::onlyTrashed()->get();
            return view('users.deleted', compact('users'));
        }
        abort(403);
    }

    //กู้คืน
    public function restoreUser(Request $request){
        if(auth()->user()->can('edit-'.$this->permission)) {

            $user = User::onlyTrashed()->where((new User)->getKeyName(), $request->id);
            $user->restore();

            $user = User::find($request->id);
            $user->status = '1';
            $user->save();

            Session::flash('message', 'User has been restored');
            return back();
        }
        abort(403);
    }

    public function getSettings(){
        $user = auth()->user();
        return view('users.account-settings',compact('user'));
    }

    public function saveSettings(Request $request){
        $this->validate($request, [
            'reg_fname' => 'required',
            'reg_lname' => 'required',
            'reg_email' => 'required'
        ]);

        $input = $request->all();
        if($request->password){
            $input['reg_pword'] = md5($request->password);
            $input['reg_unmd5'] = $request->password;
        }

        $user = auth()->user();
        $user->update($input);

        Session::flash('message', 'บันทึกข้อมูลเรียบร้อยแล้ว');

        return redirect()->back();

    }

    //วิวหน้า Crop ภาพโปรไฟล์
    public function imageCrop()
    {
        return view('users.image-crop');
    }

    //บันทึกภาพ จาก ajax request
    public function imageCropPost(Request $request)
    {

        $status = false;
        $image_url = '';

        if($request->has('image')){

            $user = auth()->user();
            $profile = $user->profile;

            $data = $request->image;

            list($type, $data) = explode(';', $data);

            list(, $data) = explode(',', $data);

            $data = base64_decode($data);

            $image_name = uniqid() . '.png';

            //Upload File
            $result = Storage::put('users/' . $image_name, $data);
            if($result){
                if(!is_null($profile->pic) && $profile->pic!=''){
                    Storage::delete('users/' . $profile->pic);//ลบไฟล์เก่า
                }

                //Save Profile
                $profile->pic = $image_name;
                $profile->save();

                //ดึงไฟล์มา
                $image_url = HP::getFileStorage('users/'.$image_name);
                $status = true;
            }

        }

        return response()->json(['status' => $status, 'image' => $image_url]);

    }

    //อัพเดทรูปแบบ sidebar
    public function update_type_sidebar($theme_layout)
    {

        $this->save_param($theme_layout, 'theme_layout');

    }

    //Save Theme Style
    public function savetheme($theme_name){

      $this->save_param($theme_name, 'theme_name');

    }

    //Save Fix Header
    public function savefix_header($fix_header){

      $this->save_param($fix_header, 'fix_header');

    }

    //Save Fix Header
    public function savefix_sidebar($fix_sidebar){

      $this->save_param($fix_sidebar, 'fix_sidebar');

    }

    //Save User parameter
    private function save_param($value, $key){

      $user = User::findOrFail(auth()->user()->getKey());

      $params = (object)json_decode($user->params);
      $params->$key = $value;

      $user->params = json_encode($params);
      $user->save();

    }

    public function check_email_repeat($email, $id_edit=null){

        $user = User::where('reg_email', $email)
                    ->when($id_edit, function($query, $id_edit){
                        $query->where((new User)->getKeyName(), '!=', $id_edit);
                    })
                    ->withTrashed()
                    ->first();

        $result = is_null($user) ? false : true ;

        return response()->json([
                'result' => $result
            ]);
    }

    
    public function check_taxid($tax_id){
        if( !is_null($tax_id) ){
            $tax_id_check = preg_replace("/[^a-z\d]/i", '', $tax_id);
            if(strlen( $tax_id_check ) == 13 ){
                $profile_officials = User::where('reg_13ID', $tax_id_check)->first();
                if(!is_null($profile_officials)){
                    $resort = 'error1';
                }else{
                    $resort = 'ok';
                }
            }else{
                $resort = 'ok';
            }

            return response()->json($resort);
        }
    }

    public function load_data_role($id)
    {
        $users = User::findOrfail($id);

        return view('users.modals.show_role', compact('users'));

    }

    public function update_user_group(Request $request)
    {

        $msg = 'error';

        try {

            $requestData = $request->all();

            $arr_publish      = $request->input('id');
            $arr_role_publish = $request->input('roles');
           
            $db   = new User;
            $user =  User::whereIn( $db->getKeyName() ,$arr_publish)->get();

            foreach( $user AS $item ){
    
                foreach(  $arr_role_publish AS $role_id ){

                    RoleUser::updateOrCreate(
                        [
                            'user_runrecno'    => $item->getKey(),
                            'role_id'          => $role_id
                        ],
                        [
                            'user_runrecno'    => $item->getKey(),
                            'role_id'          => $role_id
                        ]
        
                    );
       
                }
                $msg = 'success';
            }
            return response()->json(['msg' => $msg ]);


            // all good
        } catch (\Exception $e) {

            echo $e->getMessage();
            exit;
            // DB::rollback();
            // something went wrong

        }

    }
}

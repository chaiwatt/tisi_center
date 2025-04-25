<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Basic\ConfigRoles as config_roles;
use App\RoleUser as role_user;
class RightController extends Controller
{

    public function __construct()
    {

    }
    // start สิทธิ์การใช้งาน กต และ สก
    public function rights(Request $request){

        $input = $request->only(
                                'tax_id'
                             );
    //   $config_roles =    config_roles::whereIn('group_type',[1,2])->get()->pluck('role_id')->toArray();
    //   return response()->json([
    //                             'config_roles' => $config_roles
    //                          ]);
    // เพิ่มสิทธิ์ roles
        $config_roles  = config_roles::select('role_id')->whereIn('group_type',[1,2])->get();
         $role_user     = role_user::select('tax_id')->where('tax_id',$input['tax_id'])->get();
        if(count($config_roles) > 0 && count($role_user) == 0 ){
                foreach($config_roles as $role){
                     $object             = [];
                     $object['role_id']  = $role->role_id;
                     $object['tax_id']   = $input['tax_id'];
                    //  $objects[] = $object;
                     role_user::insert($object);
                }
        }
      return response()->json(['config_roles' => $config_roles]);
    }
    // end สิทธิ์การใช้งาน กต และ สก

}

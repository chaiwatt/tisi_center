<?php

namespace App\Http\Controllers\Basic;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Basic\SubDepartment;

class SubDepartmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //กลุ่มงานจาก id กอง เป็น json
    public function get_json_by_department($did=null){
        $sub_departments = SubDepartment::when($did, function($query, $did){
                                $query->where('did', $did);
                            })
                            ->get();

        return response()->json($sub_departments);
    }

}

<?php

namespace App\Http\Controllers\Reference;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Role;
use HP;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $roles = Role::where('label', 'staff')->get();
        return view('reference.role.index', compact('roles'));
    }

}

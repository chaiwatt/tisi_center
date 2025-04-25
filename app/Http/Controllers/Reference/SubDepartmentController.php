<?php

namespace App\Http\Controllers\Reference;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Basic\SubDepartment;
use HP;

class SubDepartmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $departments = SubDepartment::with('department')->get();
        return view('reference.department.index', compact('departments'));
    }

}

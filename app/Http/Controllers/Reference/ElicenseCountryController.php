<?php

namespace App\Http\Controllers\Reference;

use App\Http\Controllers\Controller;
use DB;

use App\Models\Elicense\Basic\Country;

class ElicenseCountryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $countrys = Country::get();
        return view('reference.country.index', compact('countrys'));
    }

}

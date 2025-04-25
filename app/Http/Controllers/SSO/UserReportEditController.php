<?php

namespace App\Http\Controllers\SSO;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserReportEditController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function production()
    {
        $model = str_slug('user-sso-report-edit-production', '-');
        if(auth()->user()->can('view-'.$model)) {
            return view('sso.user_report_edit.production');
        }

        abort(403);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function test()
    {
        $model = str_slug('user-sso-report-edit-test', '-');
        if(auth()->user()->can('view-'.$model)) {
            return view('sso.user_report_edit.test');
        }

        abort(403);

    }

}

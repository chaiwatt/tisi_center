<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Config as config;
use Illuminate\Http\Request;

use HP;
use HP_WS;

class PersonalController extends Controller
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

    public function index(Request $request)
    {
        $user = auth()->user();
        $model = str_slug('personal','-');
        if($user->can('view-'.$model)) {

            $data = [];

            if($request->method()=='POST'){

                $data['PersonalID'] = $request->get('PersonalID', '');

                if(!empty($data['PersonalID'])){
                    if(HP::check_number_counter($data['PersonalID'], 13)){
                        $data['result'] = HP_WS::getPersonal($data['PersonalID'], $request->ip());
                    }else{
                        $data['result'] = (object)['status' => 'fail', 'Message' => 'รูปแบบเลขประจำตัวประชาชนไม่ถูกต้อง'];
                    }
                }
            }

            return view('ws.personal.index', compact('data'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {

    }

}

<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Config as config;
use Illuminate\Http\Request;

use HP;
use HP_WS;

class RdVatController extends Controller
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
        $model = str_slug('rd-vat','-');
        if($user->can('view-'.$model)) {

            $data = [];

            if($request->method()=='POST'){
                $data['JuristicID'] = $request->get('JuristicID', '');

                if(!empty($data['JuristicID'])){
                    if(HP::check_number_counter($data['JuristicID'], 13)){
                        $data['result'] = HP_WS::getRdVat($data['JuristicID'], $request->ip());
                    }else{
                        $data['result'] = (object)['status' => 'fail', 'vMessageErr' => 'รูปแบบเลขประจำตัวผู้เสียภาษีไม่ถูกต้อง'];
                    }
                }
            }

            return view('ws.rd_vat.index', compact('data'));
        }
        abort(403);

    }

}

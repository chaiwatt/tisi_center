<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Config as config;
use Illuminate\Http\Request;

use HP;
use HP_WS;

class IndustryController extends Controller
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
        $model = str_slug('industry','-');
        if($user->can('view-'.$model)) {

            $data = [];

            if($request->method()=='POST'){

                $data['JuristicID'] = $request->get('JuristicID', '');
                $data['api_name'] = $request->get('api_name', '');

                if(!empty($data['JuristicID'])){

                    if( $data['api_name'] == 1 ){//pid=4
                        if(HP::check_number_counter($data['JuristicID'], 14)){
                            $data['result'] = HP_WS::getIndustry($data['JuristicID'], $request->ip());
                        }else{
                            $data['result'] = (object)['status' => 'fail', 'result' => 'รูปแบบเลขทะเบียนโรงงานไม่ถูกต้อง'];
                        }
                    }elseif( $data['api_name'] == 2 ){//ค้นจากเลขทะเบียนเดิมได้
                        if(HP::check_number_counter($data['JuristicID'], 14) || HP::check_factory_format_old($data['JuristicID'])){
                            $data['result'] = HP_WS::getIndustry2($data['JuristicID'], $request->ip());
                        }else{
                            $data['result'] = (object)['status' => 'fail', 'result' => 'รูปแบบเลขทะเบียนโรงงานไม่ถูกต้อง'];
                        }
                    }elseif( $data['api_name'] == 3 ){//pid=9
                        if(HP::check_number_counter($data['JuristicID'], 14)){
                            $data['result'] = HP_WS::getIndustry3($data['JuristicID'], $request->ip());
                        }else{
                            $data['result'] = (object)['status' => 'fail', 'result' => 'รูปแบบเลขทะเบียนโรงงานไม่ถูกต้อง'];
                        }
                    }

                }
            }

            return view('ws.industry.index', compact('data'));
        }
        abort(403);

    }

}

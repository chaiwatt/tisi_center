<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

use App\LoginLog;
use HP;

class ProfileController extends Controller
{

    //แสดง profile
    public function show(){
        return view('profile/show');
    }

    //รายการข้อมูลประวัติการเข้าใช้งานระบบ
    public function login_list(Request $request)
    {

        $user_id = auth()->user()->getKey();

        $query = LoginLog::query()->where('user_id', $user_id);


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('login_at', function ($item) {
                                return HP::DateTimeThai($item->login_at);
                            })
                            ->addColumn('logout_at', function ($item) {
                                return is_null($item->logout_at) ? '-' : HP::DateTimeThai($item->logout_at) ;
                            })
                            ->addColumn('ip_address', function ($item) {
                                return $item->ip_address;
                            })
                            ->addColumn('user_agent', function ($item) {
                                return HP::FormatUserAgent($item->user_agent, true);
                            })
                            ->addColumn('channel', function ($item) {
                                return $item->channel;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['user_agent'])
                            ->make(true);
    }

}

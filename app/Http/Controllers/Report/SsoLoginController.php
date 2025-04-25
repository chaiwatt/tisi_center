<?php

namespace App\Http\Controllers\Report;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WS\Client;
use App\Models\Sso\User AS SSO_User;
use App\Models\Sso\LoginLog;
use Illuminate\Http\Request;
use HP;

class SsoLoginController extends Controller
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
        $model = str_slug('ws-log','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = $request->all();
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new LoginLog;

            $table_user = (new SSO_User)->getTable();
            $table_log = (new LoginLog)->getTable();
            $Query = $Query->leftJoin($table_user, "$table_user.id", '=', "$table_log.user_id");

            if (!empty($filter['search'])) {
                $Query = $Query->where(function($query_search) use ($filter){
                            $user_query = SSO_User::where('username', 'LIKE', '%'.$filter['search'].'%')
                                                  ->orWhere('name', 'LIKE', '%'.$filter['search'].'%')
                                                  ->select('id');
                            $query_search->whereIn('user_id', $user_query)
                                         ->orWhere('ip_address', 'LIKE', '%'.$filter['search'].'%');
                        });

            }

            if (!empty($filter['channel'])) {
                $Query = $Query->where('channel', $filter['channel']);
            }

            if(!empty($filter['start_date'])) {
                $start_date = HP::convertDate($filter['start_date'], true);
                $Query = $Query->whereDate('login_at', '>=', $start_date);
            }

            if(!empty($filter['end_date'])) {
                $end_date = HP::convertDate($filter['end_date'], true);
                $Query = $Query->whereDate('login_at', '<=', $end_date);
            }

            $log = $Query->sortable(['login_at' => 'desc'])
                         ->paginate($filter['perPage']);

            return view('report/sso-login.index', compact('log', 'filter'));
        }
        abort(403);

    }

}

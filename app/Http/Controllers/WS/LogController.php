<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WS\Log;
use App\Models\WS\Client;
use Illuminate\Http\Request;
use HP_API;
use HP;

class LogController extends Controller
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

            $Query = new Log;

            if (!empty($filter['search'])) {
                $Query = $Query->where(function($query_search) use ($filter){
                            $query_search->where('client_title', 'LIKE',  '%'.$filter['search'].'%')
                                         ->orWhere('ip', 'LIKE',  '%'.$filter['search'].'%');
                        });

            }

            if (!empty($filter['api_name'])) {
                $Query = $Query->where('api_name', $filter['api_name']);
            }

            if (!empty($filter['app_name'])) {
                $Query = $Query->where('app_name', $filter['app_name']);
            }

            if (!empty($filter['status'])) {
                $Query = $Query->where('status', $filter['status']);
            }

            if (!empty($filter['request_date_start'])) {
                $request_date_start = HP::convertDate($filter['request_date_start'], true);
                $Query = $Query->whereDate('request_time', '>=', $request_date_start);
            }

            if (!empty($filter['request_date_end'])) {
                $request_date_end = HP::convertDate($filter['request_date_end'], true);
                $Query = $Query->whereDate('request_time', '<=', $request_date_end);
            }

            $log = $Query->sortable(['request_time' => 'desc'])
                         ->paginate($filter['perPage']);

            $clients = Client::pluck('title', 'app_name');

            $status_list = $status_tmp = HP_API::APIStatus();
            foreach ($status_list as $key => $status) {
                $status_list[$key] = '<span class="label label-'.$status['css'].'">'.$status['code'].'</span> '.$status['name'];
            }

            $status_css = collect($status_tmp)->pluck('css', 'code')->toArray();

            return view('ws/log.index', compact('clients', 'status_list', 'status_css', 'log', 'filter'));
        }
        abort(403);

    }

}

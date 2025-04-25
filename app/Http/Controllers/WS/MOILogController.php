<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WS\Log;
use App\Models\WS\Client;
use App\Models\WS\MOILog;
use Illuminate\Http\Request;
use HP_API;
use HP;

class MOILogController extends Controller
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
        $model = str_slug('ws-moi-log','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = $request->all();
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new MOILog;

            if (!empty($filter['search'])) {
                $Query = $Query->where(function($query_search) use ($filter){
                            $query_search->where('input_number', 'LIKE',  '%'.$filter['search'].'%')
                                         ->orWhere('client_ip', 'LIKE',  '%'.$filter['search'].'%');
                        });

            }

            if (!empty($filter['destination_type'])) {
                $Query = $Query->where('destination_type', $filter['destination_type']);
            }

            if (!empty($filter['source_site'])) {
                $Query = $Query->where('source_url', 'LIKE',  '%'.$filter['source_site'].'%');
            }

            if (!empty($filter['data_status'])) {
                if($filter['data_status']=='1'){ //พบข้อมูล
                    $Query = $Query->where(function($query){
                                $query->whereNotNull('response_http')->whereNull('response_error');
                             });
                }elseif($filter['data_status']=='2'){ //ไม่ได้ข้อมูล
                    $Query = $Query->where(function($query){
                                $query->whereNotNull('response_http')->whereNotNull('response_error');
                             });
                }else{ //เชื่อมต่อไม่ได้
                    $Query = $Query->where(function($query){
                                $query->whereNull('response_http')->whereNull('response_error');
                             });
                }
            }

            if (!empty($filter['request_date_start'])) {
                $request_date_start = HP::convertDate($filter['request_date_start'], true);
                $Query = $Query->whereDate('request_start', '>=', $request_date_start);
            }

            if (!empty($filter['request_date_end'])) {
                $request_date_end = HP::convertDate($filter['request_date_end'], true);
                $Query = $Query->whereDate('request_start', '<=', $request_date_end);
            }

            $log = $Query->sortable(['request_start' => 'desc'])
                         ->paginate($filter['perPage']);

            $clients = Client::pluck('title', 'app_name');

            $status_list = $status_tmp = HP_API::APIStatus();
            foreach ($status_list as $key => $status) {
                $status_list[$key] = '<span class="label label-'.$status['css'].'">'.$status['code'].'</span> '.$status['name'];
            }

            return view('ws/moi_log.index', compact('clients', 'status_list', 'log', 'filter'));
        }
        abort(403);

    }

}

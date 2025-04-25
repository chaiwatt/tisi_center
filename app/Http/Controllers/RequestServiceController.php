<?php

namespace App\Http\Controllers;

use App\Models\Certify\Applicant\CertiLab;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage','');
        $filter['filter_type'] = $request->get('filter_type', '');
        $filter['formula_select'] = $request->get('formula_select', '');
        $filter['branch_select'] = $request->get('branch_select', '');
        $filter['filter_start_date'] = $request->get('filter_start_date', '');
        $filter['filter_end_date'] = $request->get('filter_end_date', '');
        $filter['status_select'] = $request->get('status_select', '');
        $filter['search_text'] = $request->get('search_text', '');

        $Query = new CertiLab;

        if ($filter['search_text']!='') {
            $Query = $Query->where('app_no','LIKE','%'.$filter['search_text'].'%');
        }

        if ($filter['formula_select'] != ''){
            $Query = $Query->where('standard_id',$filter['formula_select']);
            $_SESSION['formula_text'] = $request->formula_text;
        }

        if ($filter['filter_type'] != ''){
//            if ($filter['filter_type'] == 3){
//                $Query = $Query->whereIn('lab_type', [3,4]);
//            }
            $Query = $Query->where('lab_type', $filter['filter_type']);
        }

        if ($filter['filter_start_date'] !='' && $filter['filter_end_date'] != ''){
            $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
            $end = Carbon::createFromFormat('d/m/Y',$filter['filter_end_date']);
            $Query = $Query->whereBetween('created_at', [$start->toDateString(),$end->toDateString()]);
        }

        if ($filter['branch_select']!='') {
            $Query = $Query->where('branch_name',$filter['branch_select']);
            $_SESSION['branch_text'] = $request->branch_text;
        }

        if ($filter['status_select']!='') {
            $Query = $Query->where('status',$filter['status_select']);
        }

        if ($filter['perPage'] == ''){
            $request_service = $Query->get();
        }else{
            $request_service = $Query->paginate($filter['perPage']);
        }

        if (!isset($_GET['perPage'])){
            $request_service = null;
        }

        return view('certify/request_service_summary/index', compact('request_service', 'filter'));

//        $model = str_slug('request_service_summary','-');
//        if(auth()->user()->can('view-'.$model)) {
//        }
//        abort(403);
    }
}

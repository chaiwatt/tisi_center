<?php

namespace App\Http\Controllers\Laws\Report;

use HP;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Law\Log\LawLogWorking;
use App\Models\Law\Log\LawSystemCategory;

class LawReportLogWorkingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_status           = $request->input('filter_status');
        $filter_search           = $request->input('filter_search');
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;

        $query = LawLogWorking::query()->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                        switch ( $filter_condition_search ):
                                                            case "1":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                $law_system_category_id  = LawSystemCategory::Where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")->select('id');
                                                                return  $query->whereIn('law_system_category_id',$law_system_category_id);  
                                                            break;
                                                            case "2":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('ref_system', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                            break;
                                                            case "3":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('ref_no', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                            break;
                                                            case "4":
                                                                return  $query->where('status', 'LIKE', '%'.$filter_search.'%');
                                                            break;
                                                           
                                                            default:
                                                            $search_full = str_replace(' ', '', $filter_search );
                                                            $query->where( function($query) use($search_full) {
                                                                $law_system_category_id  = LawSystemCategory::Where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")->select('id');
                                                                $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")
                                                                      ->orWhere('ref_table', 'LIKE', '%' . $search_full . '%')
                                                                      ->orWhere('ref_system', 'LIKE', '%' . $search_full . '%')
                                                                      ->orWhere('ref_no', 'LIKE', '%' . $search_full . '%')
                                                                      ->orwhereIn('law_system_category_id',$law_system_category_id);
                                                            });
                                                
                                                                break;
                                                        endswitch;
                                                    })
                                                    ->when($filter_status, function ($query, $filter_standard){
                                                        return $query->Where('status', 'LIKE', '%' . $filter_standard . '%');
                                                    })
                                                    ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                            return $query->whereBetween('created_at',[$filter_start_date,$filter_end_date]);
                                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                            return $query->whereDate('created_at',$filter_start_date);
                                                        }
                                                    });
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('system', function ($item) {
                                return $item->SystemName;
                            })
                            ->addColumn('ref_no', function ($item) {
                                return $item->ref_no;
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('ref_system', function ($item) {
                                return $item->ref_system;
                            })
                            ->addColumn('status', function ($item) {
                                return $item->status;
                            })
                            ->addColumn('remark', function ($item) {
                                $remark = mb_strimwidth($item->remark, 0, 40, '......' );
                                return '<a  href="javascript:void(0)" class="show_remark"  data-remark="'.( $item->remark ).'" >'.$remark.'</a>';

                            })
                            ->addColumn('created_by', function ($item) {
                                return $item->CreatedName;
                            })
                            ->addColumn('created_at', function ($item) {
                             return  !empty($item->created_at)?HP::DateThai($item->created_at):null;
                            
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'remark', 'status', 'created_by', 'color', 'condition', 'created_at'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-report-log-working','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/log-working",  "name" => 'รายงานประวัติการดำเนินงาน' ],
            ];
            return view('laws.report.log-working.index',compact('breadcrumbs'));
        }
        abort(403);
    }

}

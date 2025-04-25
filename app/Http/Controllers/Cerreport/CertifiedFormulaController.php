<?php

namespace App\Http\Controllers\Cerreport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\Formula;

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantIB\CertiIb;
use DB;

class CertifiedFormulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model = str_slug('cerreport-certified-formula','-');
        if(auth()->user()->can('view-'.$model)) {

        $filter = [];
        $filter['filter_formula'] = $request->get('filter_formula', '');
        $filter_formula =      $filter['filter_formula'];
        $filter['filter_years'] = $request->get('filter_years', '');
        $filter_years =      $filter['filter_years'];
 
        $app_labs = CertiLab::query()
                                ->select(DB::raw('SUM(IF(`standard_id`>0, 1, 0)) AS sum_app,standard_id AS standard_id,year(start_date) AS year'))
                                ->when($filter_formula, function ($query, $filter_formula){
                                    return $query->where('standard_id',$filter_formula);
                                 })
                                 ->when($filter_years, function ($query, $filter_years){
                                    return $query->where(DB::raw('year(start_date)'),$filter_years);
                                })
                                ->whereNotNull('standard_id')
                                ->whereNotNull(DB::raw('year(start_date)')) 
                                ->where('status','>=','1')
                                ->groupBy('standard_id')
                                ->orderby('id','desc')->get();
        // return $app_labs->pluck('year', 'standard_id')->toArray() ; 
         $app_cbs = CertiCb::query()
                                ->select(DB::raw('SUM(IF(`type_standard`>0, 1, 0)) AS sum_app,type_standard AS standard_id,year(start_date) AS year'))
                                ->when($filter_formula, function ($query, $filter_formula){
                                    return $query->where('type_standard',$filter_formula);
                                 })
                                 ->when($filter_years, function ($query, $filter_years){
                                    return $query->where(DB::raw('year(start_date)'),$filter_years);
                                })
                                ->whereNotNull('type_standard') 
                                 ->whereNotNull(DB::raw('year(start_date)')) 
                                 ->where('status','>=','1')
                                ->groupBy('type_standard')
                                ->orderby('id','desc')->get();

            
         $app_ibs = CertiIb::query()->select(DB::raw('SUM(IF(`type_standard`>0, 1, 0)) AS sum_app,type_standard AS standard_id,year(start_date) AS year'))
                                    ->when($filter_formula, function ($query, $filter_formula){
                                        return $query->where('type_standard',$filter_formula);
                                    })
                                    ->when($filter_years, function ($query, $filter_years){
                                        return $query->where(DB::raw('year(start_date)'),$filter_years);
                                    })
                                    ->whereNotNull('type_standard') 
                                    ->whereNotNull(DB::raw('year(start_date)')) 
                                    ->where('status','>=','1')
                                    ->groupBy('type_standard')->get();

          $formula  = Formula::when($filter_formula, function ($query, $filter_formula){
                                    return $query->where('id',$filter_formula);
                                })->get();                        
 
            return view('cerreport.certified-formula.index', compact('filter','formula','app_labs','app_cbs','app_ibs'));
        }
        abort(403);
    }
}

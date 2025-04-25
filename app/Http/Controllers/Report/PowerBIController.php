<?php

namespace App\Http\Controllers\Report;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Config\ConfigsReportPowerBI;
use App\Models\Config\ConfigsReportPowerBIGroup;
use App\Models\Config\ConfigsReportPowerBIVisit;

use Illuminate\Http\Request;

class PowerBIController extends Controller
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
        $model = str_slug('report-power-bi', '-');
        if(auth()->user()->can('view-'.$model)) {

            $groups = ConfigsReportPowerBIGroup::orderby('ordering')->with('config_report_power_bis')->get();

            return view('report.power-bi.index', compact('groups'));
        }

        abort(403);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function show($id)
    {
        $model = str_slug('report-power-bi', '-');
        if(auth()->user()->can('view-'.$model)) {

            $item = ConfigsReportPowerBI::findOrfail($id);
            if($item->check_role()){

                $session_id = session()->getId();
                $count = ConfigsReportPowerBIVisit::where('session_id', $session_id)->where('power_bi_id', $id)->count();
                if($count==0){
                    $visit = new ConfigsReportPowerBIVisit;
                    $visit->session_id = $session_id;
                    $visit->power_bi_id = $id;
                    $visit->visit_at = date('Y-m-d H:i:s');
                    $visit->save();
                }

                return view('report.power-bi.show', compact('item'));
            }else{
                abort(403);
            }
        }else{
            abort(403);
        }

    }

}

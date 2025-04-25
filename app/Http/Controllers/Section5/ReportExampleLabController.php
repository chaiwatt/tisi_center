<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Section5\ApplicationIbcbStaff;
use App\Models\Section5\ApplicationIbcb;
use App\Models\Section5\ApplicationIbcbScope;
use App\Models\Section5\ApplicationIbcbScopeDetail;
use App\Models\Section5\ApplicationIbcbAccept;
use App\Models\Section5\ApplicationIbcbAudit;
use App\Models\Tis\Standard;
use App\Models\Ssurv\Tis;

use App\Mail\Section5\ApplicationIBCBAcceptMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\Rform\Sample;
use App\Models\Elicense\Rform\Product;
use App\Models\Elicense\Rform\ProductLab;
use App\Models\Ssurv\SaveExample;
use App\Models\Ssurv\SaveExampleMaplap;

class ReportExampleLabController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/report_product/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('report-example-lab','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('section5.report-example-lab.index');
        }
        abort(403);
    }

    //รายการข้อมูล
    public function data_list(Request $request)
    {

        $config = HP::getConfig();
        $elicense_url = $config->url_elicense_trader.'/media/com_rform/factoryresult';

        $filter_search = $request->input('filter_search');
        $filter_tis_number = $request->input('filter_tis_number');

        $filter_status = $request->input('filter_status');

        $filter_start_checking_date = $request->input('filter_start_checking_date');
        $filter_end_checking_date   = $request->input('filter_end_checking_date');

        $query = SaveExampleMaplap::query()->when($filter_search, function ($query, $filter_search){
                            $query->Where('name_lap', 'LIKE', "%".$filter_search."%")
                                  ->OrWhere('licensee', 'LIKE', "%".$filter_search."%")
                                  ->OrWhere('no_example_id', 'LIKE', "%".$filter_search."%");
                        })
                        ->when($filter_tis_number , function ($query, $filter_tis_number){
                            return $query->where('tis_standard', $filter_tis_number);
                        })
                        ->when($filter_status, function ($query, $filter_status){
                            return $query->where('status', $filter_status);
                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('name_lap', function ($item) {
                                return $item->name_lap;
                            })
                            ->addColumn('tis_standard', function ($item) {
                                return $item->tis_standard;
                            })
                            ->addColumn('licensee', function ($item) {
                                return $item->licensee;
                            })
                            ->addColumn('no_example_id', function ($item) {
                                return $item->no_example_id;
                            })
                            ->addColumn('status', function ($item) {
                                return HP::map_lap_status($item->status);
                            })
                            ->addColumn('test_result_link', function ($item){
                                return '<a href="'.url("section5/report-example-lab/test_result/{$item->id}").'">ผลทดสอบ</a>';
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['test_result_link'])
                            ->make(true);
    }

    /* ผลการทดสอบผลิตภัณฑ์ */
    public function test_result($save_example_map_lap_id){
        $model = str_slug('report-example-lab','-');
        if(auth()->user()->can('view-'.$model)) {

            $sample       = SaveExampleMaplap::findOrFail($save_example_map_lap_id);
            $example      = !is_null($sample->example) ? $sample->example : new SaveExample ;
            $map_lap_list = SaveExampleMaplap::where('example_id', $sample->example_id)->whereNotNull('detail_product_maplap')->where('no_example_id', $sample->no_example_id)->get();

            $standard = !is_null($sample->tis) ? $sample->tis : new Tis ;
            $attach_path = $this->attach_path;

            return view('section5.report-example-lab.test_result', compact('sample', 'example', 'map_lap_list', 'standard', 'attach_path'));

        }
        abort(403);
    }

    /* ผลการทดสอบผลิตภัณฑ์ */
    public function test_result_item($save_example_map_lap_id){
        $model = str_slug('report-example-lab','-');
        if(auth()->user()->can('view-'.$model)) {

            $data_map = SaveExampleMaplap::findOrFail($save_example_map_lap_id);
            return view('section5.report-example-lab.modal/form', compact('data_map'));

        }
        abort(403);
    }

}

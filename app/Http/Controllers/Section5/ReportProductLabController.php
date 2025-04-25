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

use App\Mail\Section5\ApplicationIBCBAcceptMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\Rform\Sample;
use App\Models\Elicense\Rform\Product;
use App\Models\Elicense\Rform\ProductLab;

class ReportProductLabController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('report-product-lab','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('section5.report-product-lab.index');
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

        $db_e = DB::connection('mysql_elicense');

        $product_lab_model = new ProductLab;
        $query =   $db_e->table($product_lab_model->getTable().' AS a')
                        ->leftJoin((new RosUsers)->getTable().' AS user', 'user.id', '=', 'a.lab_id')
                        ->leftJoin((new Product)->getTable().' AS product', 'product.id', '=', 'a.product_id')
                        ->leftJoin((new RosUsers)->getTable().' AS user_trader', 'user_trader.id', '=', 'product.created_by')
                        ->select(DB::Raw("a.*, user.name AS lab, product.tis_number, product.refno, user_trader.name AS trader"))
                        ->whereNotNull('status')
                        ->when($filter_search, function ($query, $filter_search){
                                            $query->Where('product.refno', 'LIKE', "%".$filter_search."%")
                                                  ->OrWhere('user.name', 'LIKE', "%".$filter_search."%");
                        })
                        ->when($filter_tis_number , function ($query, $filter_tis_number){
                            return $query->where('product.tis_number', $filter_tis_number);
                        })
                        ->when($filter_status, function ($query, $filter_status){
                            return $query->where('a.status', $filter_status);
                        })
                        ->when($filter_start_checking_date, function ($query, $filter_start_checking_date){
                            $filter_start_checking_date = HP::convertDate($filter_start_checking_date, true);
                            return $query->whereDate('a.checking_date', '>=', $filter_start_checking_date);
                        })
                        ->when($filter_end_checking_date, function ($query, $filter_end_checking_date){
                            $filter_end_checking_date = HP::convertDate($filter_end_checking_date, true);
                            return $query->whereDate('a.checking_date', '<=', $filter_end_checking_date);
                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('lab', function ($item) {
                                return $item->lab;
                            })
                            ->addColumn('trader', function ($item) {
                                return $item->trader;
                            })
                            ->addColumn('tis_number', function ($item) {
                                return $item->tis_number;
                            })
                            ->addColumn('refno', function ($item) {
                                return $item->refno;
                            })
                            ->addColumn('status', function ($item) use ($product_lab_model) {
                                $status_list = $product_lab_model->status_list();
                                return array_key_exists($item->status, $status_list) ? $status_list[$item->status] : '-';
                            })
                            ->addColumn('checking_date', function ($item) {
                                return HP::DateThai($item->checking_date);
                            })
                            ->addColumn('sample_link', function ($item) {
                                $htmls = [];
                                $samples = Sample::where('product_lab_id', $item->id)->get();
                                foreach ($samples as $key => $sample) {
                                    $htmls[] = '<li><a href="'.url("section5/report-product-lab/sample/{$sample->id}").'">- '.$sample->refno.'</a></li>';
                                }
                                return count($htmls) > 0 ? '<ul style="list-style-type: none" class="p-l-0">'.implode('', $htmls).'</ul>' : '-' ;
                            })
                            ->addColumn('test_result_link', function ($item) use ($product_lab_model) {
                                $htmls = [];
                                $samples = Sample::where('product_lab_id', $item->id)->get();
                                foreach ($samples as $key => $sample) {
                                    $htmls[] = '<li><a href="'.url("section5/report-product-lab/test_result/{$sample->id}").'">- '.$sample->refno.'</a></li>';
                                }
                                return count($htmls) > 0 ? '<ul style="list-style-type: none" class="p-l-0">'.implode('', $htmls).'</ul>' : '-' ;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['sample_link', 'test_result_link'])
                            ->make(true);
    }

    /* ใบรับนำส่งตัวอย่าง */
    public function sample($sample_id){
        $model = str_slug('report-product-lab','-');
        if(auth()->user()->can('view-'.$model)) {

            $sample = Sample::findOrFail($sample_id);
            $product = !is_null($sample->product) ? $sample->product : new Product ;
            $standard = !is_null($product->tis_standard) ? $product->tis_standard : new Standard ;
            $user_created = !is_null($product->user_created) ? $product->user_created : new RosUsers ;
            $product_lab = !is_null($sample->product_lab) ? $sample->product_lab : new ProductLab ;
            $lab = !is_null($product_lab->lab) ? $product_lab->lab : new RosUsers ;

            return view('section5.report-product-lab.sample', compact('sample', 'product', 'standard', 'user_created', 'lab'));

        }
        abort(403);
    }

    /* ผลการทดสอบผลิตภัณฑ์ */
    public function test_result($sample_id){
        $model = str_slug('report-product-lab','-');
        if(auth()->user()->can('view-'.$model)) {

            $sample = Sample::findOrFail($sample_id);
            $product = !is_null($sample->product) ? $sample->product : new Product ;
            $standard = !is_null($product->tis_standard) ? $product->tis_standard : new Standard ;
            $user_created = !is_null($product->user_created) ? $product->user_created : new RosUsers ;
            $product_lab = !is_null($sample->product_lab) ? $sample->product_lab : new ProductLab ;
            $lab = !is_null($product_lab->lab) ? $product_lab->lab : new RosUsers ;
            $config = HP::getConfig();

            return view('section5.report-product-lab.test_result', compact('sample', 'product', 'standard', 'user_created', 'lab', 'config'));

        }
        abort(403);
    }

}

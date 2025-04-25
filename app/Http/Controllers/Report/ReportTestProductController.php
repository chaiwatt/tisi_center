<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Bsection5\ReportTestProduct;
use App\Models\Bsection5\ReportTestProductDetail;
use App\Models\Bsection5\ReportTestProductDetailItem;
use App\Models\Bsection5\ReportTestProductDetailResult;

class ReportTestProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_tis_id = $request->input('filter_tis_id');

        $filter_payment_date_start = $request->input('filter_payment_date_start');
        $filter_payment_date_end = $request->input('filter_payment_date_end');

        $filter_date_start = $request->input('filter_date_finish_start');
        $filter_date_end = $request->input('filter_date_finish_end');

        $filter_test_date_start = $request->input('filter_test_date_start');
        $filter_test_date_end = $request->input('filter_test_date_end');

        $query = ReportTestProduct::query()->when($filter_search, function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search );
                                                $query->where('lab_code',  'LIKE', "%$search_full%")
                                                        ->Orwhere('lab_name',  'LIKE', "%$search_full%")
                                                        ->Orwhere('tis_no',  'LIKE', "%$search_full%")
                                                        ->Orwhere('trader_name',  'LIKE', "%$search_full%")
                                                        ->Orwhere('trader_taxid',  'LIKE', "%$search_full%")
                                                        ->Orwhere('sample_bill_no',  'LIKE', "%$search_full%")
                                                        ->Orwhere('ref_report_no',  'LIKE', "%$search_full%");
                                            })
                                            ->when($filter_tis_id, function ($query, $filter_tis_id){
                                                $ids = ReportTestProductDetail::where('tis_no', $filter_tis_id)->select('test_product_id');
                                                return  $query->whereIn('id',$ids);
                                            })
                                            ->when($filter_payment_date_start, function ($query, $filter_payment_date_start) use($filter_payment_date_end){
                                                if(!is_null($filter_payment_date_start) && !is_null($filter_payment_date_end) ){
                                                    return $query->whereBetween('payment_date',[$filter_payment_date_start,$filter_payment_date_end]);
                                                }else if(!is_null($filter_payment_date_start) && is_null($filter_payment_date_end)){
                                                    return $query->whereDate('payment_date',$filter_payment_date_start);
                                                }
                                            })
                                            ->when($filter_date_start, function ($query, $filter_date_start) use($filter_date_end){
                                                if(!is_null($filter_date_start) && !is_null($filter_date_end) ){
                                                    return $query->whereBetween('test_finish_date',[$filter_date_start,$filter_date_end]);
                                                }else if(!is_null($filter_date_start) && is_null($filter_date_end)){
                                                    return $query->whereDate('test_finish_date',$filter_date_start);
                                                }
                                            })
                                            ->when($filter_test_date_start, function ($query, $filter_test_date_start) use($filter_test_date_end){
                                                if(!is_null($filter_test_date_start) && !is_null($filter_test_date_end) ){
                                                    return $query->whereBetween('test_date',[$filter_test_date_start,$filter_test_date_end]);
                                                }else if(!is_null($filter_test_date_start) && is_null($filter_test_date_end)){
                                                    return $query->whereDate('test_date',$filter_test_date_start);
                                                }
                                            });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('lab_code', function ($item) {
                                return (!empty($item->lab_code)?$item->lab_code:null);
                            })
                            ->addColumn('tis_no', function ($item) {
                                return (!empty($item->tis_no)?$item->tis_no:null);
                            })
                            ->addColumn('trader_name', function ($item) {
                                return (!empty($item->trader_name)?$item->trader_name:null).(!empty($item->trader_taxid)?'<br>( '.$item->trader_taxid.' )':null);
                            })
                            ->addColumn('sample_bill_no', function ($item) {
                                return (!empty($item->sample_bill_no)?$item->sample_bill_no:null);
                            })
                            ->addColumn('total_sample_qty', function ($item) {
                                $total_sample_qty  =  $item->TestProductDetailData->sum('sample_qty');
                                return !empty($total_sample_qty)?number_format($total_sample_qty):'0';
                            })
                            ->addColumn('receive_date', function ($item) {
                                return !empty($item->receive_date)?HP::DateThai($item->receive_date):'-';
                            })
                            ->addColumn('test_finish_date', function ($item) {
                                return !empty($item->test_finish_date)?HP::DateThai($item->test_finish_date):'-';
                            })
                            ->addColumn('test_duration', function ($item) {
                                return (!empty($item->test_duration)?$item->test_duration:null);
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'report/test-product','Report\\ReportTestProductController@destroy', 'report-test-product', true, false, false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'trader_name', 'tools','title', 'type'])
                            ->make(true);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('report-test-product','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('report.test-product.index');
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('report-test-factory','-');
        if(auth()->user()->can('view-'.$model)) {

            $testproduct = ReportTestProduct::findOrFail($id);

            $testproduct->total_sample_qty  =  $testproduct->TestProductDetailData->sum('sample_qty');

            return view('report.test-product.show',compact('testproduct'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Bsection5\ReportTestFactory;
use App\Models\Bsection5\ReportTestFactoryDetail;

class ReportTestFacctoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_tis_id = $request->input('filter_tis_id');
        $filter_date_start = $request->input('filter_date_finish_start');
        $filter_date_end = $request->input('filter_date_finish_end');
        $filter_payment_date_start = $request->input('filter_payment_date_start');
        $filter_payment_date_end = $request->input('filter_payment_date_end');

        $query = ReportTestFactory::query()->when($filter_search, function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search );
                                                $query->where('ib_code',  'LIKE', "%$search_full%")
                                                        ->Orwhere('ib_name',  'LIKE', "%$search_full%")
                                                        ->Orwhere('tis_no',  'LIKE', "%$search_full%")
                                                        ->Orwhere('trader_name',  'LIKE', "%$search_full%")
                                                        ->Orwhere('trader_taxid',  'LIKE', "%$search_full%")
                                                        ->Orwhere('factory_request_no',  'LIKE', "%$search_full%")
                                                        ->Orwhere('ref_report_no',  'LIKE', "%$search_full%");
                                            })
                                            ->when($filter_tis_id, function ($query, $filter_tis_id){
                                                return $query->where('tis_no', $filter_tis_id);
                                            })
                                            ->when($filter_date_start, function ($query, $filter_date_start) use($filter_date_end){
                                                if(!is_null($filter_date_start) && !is_null($filter_date_end) ){
                                                    $ids =   ReportTestFactoryDetail::whereBetween('test_finish_date',[$filter_date_start,$filter_date_end])->select('test_factory_id');
                                                    return $query->whereIn('id',$ids);
                                                }else if(!is_null($filter_date_start) && is_null($filter_date_end)){
                                                    $ids =  ReportTestFactoryDetail::whereDate('test_finish_date',$filter_date_start)->select('test_factory_id');
                                                    return $query->whereIn('id',$ids);
                                                }
                                            })
                                            ->when($filter_payment_date_start, function ($query, $filter_payment_date_start) use($filter_payment_date_end){
                                                if(!is_null($filter_payment_date_start) && !is_null($filter_payment_date_end) ){
                                                    return $query->whereBetween('payment_date',[$filter_payment_date_start,$filter_payment_date_end]);
                                                }else if(!is_null($filter_payment_date_start) && is_null($filter_payment_date_end)){
                                                    return $query->whereDate('payment_date',$filter_payment_date_start);
                                                }
                                            });
                                    

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('ib_code', function ($item) {
                                return (!empty($item->ib_code)?$item->ib_code:null);
                            })
                            ->addColumn('tis_no', function ($item) {  
                                return (!empty($item->tis_no)?$item->tis_no:null);
                            })
                            ->addColumn('trader_name', function ($item) {
                                return (!empty($item->trader_name)?$item->trader_name:null).(!empty($item->trader_taxid)?'<br>( '.$item->trader_taxid.' )':null);
                            })
                            ->addColumn('factory_request_no', function ($item) {
                                return !empty($item->factory_request_no)?$item->factory_request_no:'-';
                            })
                            ->addColumn('test_price', function ($item) {
                                return !empty($item->test_price)?number_format($item->test_price):'0';
                            })
                            ->addColumn('payment_date', function ($item) {
                                return !empty($item->payment_date)?HP::DateThai($item->payment_date):'-';
                            })
                            ->addColumn('test_finish_date', function ($item) {
                                $test_finish_date = $item->TestFactoryDetailData->max('test_finish_date');
                                return !empty($test_finish_date)?HP::DateThai($test_finish_date):'-';
                            })
                            ->addColumn('test_result', function ($item) {
                                return !empty($item->test_result)?$item->test_result:'-';
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'report/test-factory','Report\\ReportTestFacctoryController@destroy', 'report-test-factory', true, false, false);
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
        $model = str_slug('report-test-factory','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('report.test-factory.index');
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

            $testfactory = ReportTestFactory::findOrFail($id);

            return view('report.test-factory.show',compact('testfactory'));
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

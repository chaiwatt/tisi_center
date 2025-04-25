<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Accounting\Bank;
use App\Models\Accounting\ReceiptInfo;
use App\Models\Accounting\ReceiptInfoDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Yajra\Datatables\Datatables;
use HP;

class ReceiptInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /* Data List Display For Datatables*/
    public function data_list(Request $request){

        $filter_search = $request->input('filter_search');
    
        $query = ReceiptInfo::query();
    
        return Datatables::of($query)
                            ->addIndexColumn()

                            ->rawColumns(['checkbox', 'state', 'action'])
                            ->make(true);
    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('accounting.receipt-info.index');
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
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('accounting.receipt-info.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {

            
            DB::beginTransaction();
            try {

                $requestData = $request->all();
                $requestData['created_by'] = auth()->user()->getKey();

                $receiptinfo = ReceiptInfo::create($requestData);

                if( isset($requestData['repeater-details']) ){
                    $details = $requestData['repeater-details'];

					$list_id_data = [];
					foreach($details as $lists){
						if(isset($lists['id'])){
							$list_id_data[] = $lists['id'];
						}
					}
					$lists_id = array_diff($list_id_data, [null]);
					ReceiptInfoDetail::when($lists_id, function ($query, $lists_id){
											return $query->whereNotIn('id', $lists_id);
										})
										->where('receipt_info_id', $receiptinfo->id )
										->delete();

                    foreach( $details as $item ){

                        $detail['receipt_info_id']   = $receiptinfo->id;
                        $detail['taxid']             = !empty($item['taxid'])?$item['taxid']:null;
                        $detail['taxid']             = !empty($item['taxid']) ? preg_replace("/[^a-z\d]/i", '', $item['taxid']) : null;
                        $detail['email']             = !empty($item['email'])?$item['email']:null;
                        $detail['tel']               = !empty($item['tel'])?$item['tel']:null;
                        $detail['address']           = !empty($item['address'])?$item['address']:null;

                        $detail['bs_bank_id']        = !empty($item['bs_bank_id'])?$item['bs_bank_id']:null;
                        $detail['bank_book_name']    = !empty($item['bank_book_name'])?$item['bank_book_name']:null;
                        $detail['bank_book_number']  = !empty($item['bank_book_number'])?$item['bank_book_number']:null;
                        // $detail['bank_book_file']    = !empty($item['bank_book_file'])?$item['bank_book_file']:null;

                        $detail['status']          = !empty($item['state'])?1:0;

                        if( empty($item['id']) ){
                            ReceiptInfoDetail::create($detail);
                        }else{
                            ReceiptInfoDetail::where('id', $item['id'] )->update($detail);
                        }

                    }
                }else{
                    ReceiptInfoDetail::where('receipt_info_id', $receiptinfo->id )->delete();
                }

                DB::commit();
                // all good

                return redirect('accounting/receipt-info')->with('flash_message', 'ReceiptInfo added!');
            } catch (\Exception $e) {

                DB::rollback();
                // something went wrong

                echo $e->getMessage();
                exit;

                return redirect('accounting/receipt-info/create')->with('flash_message', 'ReceiptInfo added!');
            }
        
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {
        
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
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {
        
        }
        abort(403);
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
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {
        
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Str::slug('accounting-receipt-info','-');
        if(auth()->user()->can('view-'.$model)) {
        
        }
        abort(403);
    }
}

<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\SettingFee;
use HP;
class SettingFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    { 
        $model = str_slug('bcertify-setting-fee','-');
        if(auth()->user()->can('view-'.$model)) {
            $setting_fee = SettingFee::all();
            return view('bcertify.setting-fee.index',['setting_fee'=>$setting_fee]);
        }
        abort(403);

    }
    public function store(Request $request)
    {
        $model = str_slug('bcertify-setting-fee','-');
        if(auth()->user()->can('add-'.$model)) {
            $requestData = $request->all();

           if(isset($requestData['fee_id'])){
                foreach($requestData['fee_id'] as $item){
                        $input = [];
                        $input['fee_ref']   =  !empty($requestData['fee_ref'][$item]) && array_key_exists($item,$requestData['fee_ref']) ? $requestData['fee_ref'][$item] : null  ;
                        $input['fee_ib']    =  !empty($requestData['fee_ib'][$item]) &&   array_key_exists($item,$requestData['fee_ib'])  ? str_replace(",","", $requestData['fee_ib'][$item] ) : null  ;
                        $input['fee_cb']    =  !empty($requestData['fee_cb'][$item]) &&   array_key_exists($item,$requestData['fee_cb'])  ? str_replace(",","",  $requestData['fee_cb'][$item] )  : null  ;
                        $input['fee_lab']   =  !empty($requestData['fee_lab'][$item]) &&   array_key_exists($item,$requestData['fee_lab'])  ? str_replace(",","",  $requestData['fee_lab'][$item] )  : null  ;
                        $input['fee_start'] =  !empty($requestData['fee_start'][$item]) &&  array_key_exists($item,$requestData['fee_start'])  ? HP::convertDate( $requestData['fee_start'][$item],true) : null  ;
                        $setting_fee = SettingFee::findOrFail($item);
                        if(!is_null($setting_fee)){
                            $setting_fee->update($input);
                        }
                }
           }
           return redirect('bcertify/setting-fee')->with('flash_message', 'เรียบร้อยแล้ว');
        }
        abort(403);
    }

}

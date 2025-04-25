<?php

namespace App\Http\Controllers\Basic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use HP;
use App\Models\Basic\Config;

class SettingTrackingController extends Controller
{
 
    public function __construct()
   {
       $this->middleware('auth');
 
   }

   public function index(Request $request)
   {
    if(auth()->user()->isAdmin() === true) {
        $config = HP::getConfig(false);
        $reference_refno_lab =   $config->reference_refno_lab;
        if(!empty($reference_refno_lab)){
            $config->reference_refno_lab = explode(',', $reference_refno_lab);
        }else{
            $config->reference_refno_lab = [new  Config];
        }
        $reference_refno_ib =   $config->reference_refno_ib;
        if(!empty($reference_refno_ib)){
            $config->reference_refno_ib = explode(',', $reference_refno_ib);
        }else{
            $config->reference_refno_ib = [new  Config];
        }

        $reference_refno_cb =   $config->reference_refno_cb;
        if(!empty($reference_refno_cb)){
            $config->reference_refno_cb = explode(',', $reference_refno_cb);
        }else{
            $config->reference_refno_cb = [new  Config];
        }
        
        return view('basic.tracking-certify.index',['config'   => $config  ]);
     }
     abort(403);
   }



   public function store(Request $request)
   {
 
       if(auth()->user()->isAdmin() === true) {
           $requestData = $request->all();

            if(!empty($request->select_refno_lab) && $request->text_refno_lab){
              $requestData['reference_refno_lab']  =  self::combine_data($request->select_refno_lab, $request->text_refno_lab);
            }
            if(!empty($request->select_refno_ib) && $request->text_refno_ib){
                $requestData['reference_refno_ib']  =  self::combine_data($request->select_refno_ib, $request->text_refno_ib);
             }
             if(!empty($request->select_refno_cb) && $request->text_refno_cb){
                $requestData['reference_refno_cb']  =  self::combine_data($request->select_refno_cb, $request->text_refno_cb);
             }

           foreach ($requestData as $key => $value) {
             $config = config::where('variable', $key)->first();
                if(!is_null($config)){
                    $config->data = $value;
                    $config->save();
                }
           }
        return redirect('basic/setting-tracking')->with('flash_message', 'แก้ไขการตั้งค่าระบบเรียบร้อยแล้ว');
       }
       abort(403);
   }
 

   public static function combine_data( $vals ,$texts )
   {
         $request = [];
        foreach($vals as $key => $item){
                $text = array_key_exists($key,$texts) ? $texts[$key] : '';
                $request[] =  $item.$text;
        }   

        return  implode(",",$request);
       
   }



}

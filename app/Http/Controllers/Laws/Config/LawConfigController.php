<?php

namespace App\Http\Controllers\Laws\Config;

use HP;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Basic\Config as config;
use App\Models\Law\Config\LawConfigEmailNotis;

class LawConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model = str_slug('config-law','-');
        if(auth()->user()->can('add-'.$model)) {

            $config = HP::getConfig(false);
            $law_config_email_notis =  LawConfigEmailNotis::all();
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/config/config-law",  "name" => 'ตั้งค่าระบบงานคดี' ],
            ];

            return view('laws.config.config-law.index', compact('config','law_config_email_notis','breadcrumbs'));
        }
        abort(403);

    }

    public function store(Request $request)
    {
        $model = str_slug('config-law','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();
            foreach ($requestData as $key => $value) {
              $config = config::where('variable', $key)->first();
              if(!is_null($config)){
                $config->data = $value;
                $config->save();
              }
            }
            return redirect('law/config/config-law')->with('flash_message', 'แก้ไขการตั้งค่าระบบเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function sendemail( Request $request ){

        $requestData = $request->all();

        if( isset($requestData['title']) && !empty($requestData['title']) ){
            $requestData['created_by'] = auth()->user()->getKey();
            if( is_null(LawConfigEmailNotis::where('title', $requestData['title'])->first()) ){
                LawConfigEmailNotis::create($requestData);
                echo 'success';
            }else{
                echo 'error';
            }
        }else{
            echo 'error';
        }

    }

    public function sendemail_update(Request $request)
    {
        $model = str_slug('config-law','-');
        if(auth()->user()->can('add-'.$model)) {
           $requestData = $request->all();

            if(isset($request->law_config_email_notis_id)){
              foreach($request->law_config_email_notis_id as $key => $item) {

                 $config =  LawConfigEmailNotis::findOrFail($item);
                    if(!is_null($config) && isset($requestData['email_list'][$item]) ){
                        $email  = implode($requestData['email_list'][$item]);
                        $config->email_list = json_encode(explode(',',$email));
                        $config->created_by = auth()->user()->getKey();
                        $config->save();
                    }
                }
            }

            return redirect('law/config/config-law')->with('flash_message', 'System updated!');
        }
        abort(403);

    }
    
    public function save_receipt(Request $request)
    {
        $model = str_slug('config-law','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();
         
            foreach ($requestData as $key => $value) {
              $config = config::where('variable', $key)->first();
              if(!is_null($config)){

                if($key == "agency_deduct_money" || $key == "agency_deduct_vat"){
                    $config->data = json_encode($value);
                }else{
                    $config->data = $value;
                }
             
                $config->save();
              }
            }
            return redirect('law/config/config-law')->with('flash_message', 'System updated!');
        }
        abort(403);
    }
 
    
}

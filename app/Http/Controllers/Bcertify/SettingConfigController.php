<?php

namespace App\Http\Controllers\Bcertify;

use HP;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Bcertify\SettingConfig;

class SettingConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    { 
        $model = str_slug('bcertify-setting-config','-');
        if(auth()->user()->can('view-'.$model)) {
            $setting_config = SettingConfig::all();
            return view('bcertify.setting-config.index',['setting_config'=>$setting_config]);
        }
        abort(403);

    }
    public function store(Request $request)
    {
        $model = str_slug('bcertify-setting-config','-');
        if(auth()->user()->can('add-'.$model)) {
            $requestData = $request->all();
            $setting_config = SettingConfig::where('grop_type',$requestData['grop_type'])->first();

            if(is_null($setting_config)){
                $setting_config = new SettingConfig;
                $setting_config->created_by = @auth()->user()->runrecno;
            }else{
                $setting_config->updated_by = @auth()->user()->runrecno;
            }
                $setting_config->grop_type                 = $requestData['grop_type'];
                $setting_config->from_filed                = $requestData['from_filed'];
                $setting_config->warning_day               = $requestData['warning_day'];
                $setting_config->condition_check           = $requestData['condition_check'];
                $setting_config->check_first               = isset($requestData['check_first'])?1:0;
                $setting_config->save();


           return redirect('bcertify/setting-config')->with('flash_message', 'เรียบร้อยแล้ว');
        }
        abort(403);
    }

}

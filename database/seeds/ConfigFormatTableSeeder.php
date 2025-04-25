<?php

use Illuminate\Database\Seeder;

use App\Models\Config\ConfigsFormatCode;
use App\Models\Config\ConfigsFormatCodeSub;
use App\Models\Config\ConfigsFormatCodeLog;

class ConfigFormatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config_app_ib_cb = ConfigsFormatCode::where('system', 'APP-IB-CB')->first();
        if(is_null($config_app_ib_cb)){
            ConfigsFormatCode::insert([
                                'system' => 'APP-IB-CB',
                                'created_by' => '0',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
        }

        $config_app_lab = ConfigsFormatCode::where('system', 'APP-LAB')->first();
        if(is_null($config_app_lab)){
            ConfigsFormatCode::insert([
                                'system' => 'APP-LAB',
                                'created_by' => '0',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
        }

        $config_app_ins = ConfigsFormatCode::where('system', 'APP-Inspectors')->first();
        if(is_null($config_app_ins)){
            ConfigsFormatCode::insert([
                                'system' => 'APP-Inspectors',
                                'created_by' => '0',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
        }


        if(!is_null($config_app_ib_cb)){
            $json_app_ib_cb_sub = '[{"format":"application_type","data":null,"sub_data":null},{"format":"separator","data":"-","sub_data":null},{"format":"year-be","data":"2","sub_data":null},{"format":"separator","data":"-","sub_data":null},{"format":"no","data":"4","sub_data":"o"}]';

            $form_fotmat = json_decode( $json_app_ib_cb_sub, true);

            ConfigsFormatCodeSub::where('format_id', $config_app_ib_cb->id)->delete();

            foreach( $form_fotmat AS $item ){
                $sub = new ConfigsFormatCodeSub;
                $sub->format_id = $config_app_ib_cb->id;
                $sub->format = $item['format'];
                $sub->data = !empty($item['data'])?$item['data']:null;
                $sub->sub_data = !empty($item['sub_data'])?$item['sub_data']:null;
                $sub->save();
            }

            $log_old = ConfigsFormatCodeLog::where('format_id', $config_app_ib_cb->id )->orderBy('verstion', 'desc')->first();

            if(  is_null($log_old)  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $config_app_ib_cb->id;
                $log->data = $json_app_ib_cb_sub;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $config_app_ib_cb->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $config_app_ib_cb->system;
                $log->save();
    
            }else if( strpos($log_old->data, $json_app_ib_cb_sub ) === false  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $config_app_ib_cb->id;
                $log->data = $json_app_ib_cb_sub;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $config_app_ib_cb->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $config_app_ib_cb->system;
                $log->save();

                // Set Log เดิม วันที่สิ้นสุดใช่งาน
                $log_old->end_date = date('Y-m-d H:i:s');
                $log_old->state = 0;
                $log_old->save();

            }


        }

        if(!is_null($config_app_lab)){
            $json_app_lab = '[{"format":"character","data":"LAB","sub_data":null},{"format":"separator","data":"-","sub_data":null},{"format":"year-be","data":"2","sub_data":null},{"format":"separator","data":"-","sub_data":null},{"format":"no","data":"4","sub_data":"o"}]';
            $form_fotmat = json_decode( $json_app_lab, true);

            ConfigsFormatCodeSub::where('format_id', $config_app_lab->id)->delete();

            foreach( $form_fotmat AS $item ){
                $sub = new ConfigsFormatCodeSub;
                $sub->format_id = $config_app_lab->id;
                $sub->format = $item['format'];
                $sub->data = !empty($item['data'])?$item['data']:null;
                $sub->sub_data = !empty($item['sub_data'])?$item['sub_data']:null;
                $sub->save();
            }

            $log_old = ConfigsFormatCodeLog::where('format_id', $config_app_lab->id )->orderBy('verstion', 'desc')->first();

            if(  is_null($log_old)  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $config_app_lab->id;
                $log->data = $json_app_lab;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $config_app_lab->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $config_app_lab->system;
                $log->save();
    
            }else if( strpos($log_old->data, $json_app_lab ) === false  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $config_app_lab->id;
                $log->data = $json_app_lab;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $config_app_lab->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $config_app_lab->system;
                $log->save();

                // Set Log เดิม วันที่สิ้นสุดใช่งาน
                $log_old->end_date = date('Y-m-d H:i:s');
                $log_old->state = 0;
                $log_old->save();

            }

        }

        if(!is_null($config_app_ins)){
            $json_app_ins = '[{"format":"character","data":"INS","sub_data":null},{"format":"separator","data":"-","sub_data":null},{"format":"year-be","data":"2","sub_data":null},{"format":"separator","data":"-","sub_data":null},{"format":"no","data":"4","sub_data":"o"}]'; 
            $form_fotmat = json_decode( $json_app_ins, true);

            ConfigsFormatCodeSub::where('format_id', $config_app_ins->id)->delete();

            foreach( $form_fotmat AS $item ){
                $sub = new ConfigsFormatCodeSub;
                $sub->format_id = $config_app_ins->id;
                $sub->format = $item['format'];
                $sub->data = !empty($item['data'])?$item['data']:null;
                $sub->sub_data = !empty($item['sub_data'])?$item['sub_data']:null;
                $sub->save();
            }

            $log_old = ConfigsFormatCodeLog::where('format_id', $config_app_ins->id )->orderBy('verstion', 'desc')->first();

            if(  is_null($log_old)  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $config_app_ins->id;
                $log->data = $json_app_ins;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $config_app_ins->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $config_app_ins->system;
                $log->save();
    
            }else if( strpos($log_old->data, $json_app_ins ) === false  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $config_app_ins->id;
                $log->data = $json_app_ins;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $config_app_ins->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $config_app_ins->system;
                $log->save();

                // Set Log เดิม วันที่สิ้นสุดใช่งาน
                $log_old->end_date = date('Y-m-d H:i:s');
                $log_old->state = 0;
                $log_old->save();

            }

        }

    }
}

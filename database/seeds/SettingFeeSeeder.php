<?php

use Illuminate\Database\Seeder;
use App\Models\Bcertify\SettingFee;
class SettingFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = SettingFee::where('fee_ref', 'new_certifcate')->first();
        if(is_null($setting)){
            SettingFee::insert([
                              'fee_name' => 'ค่าธรรมเนียมคำขอขอการใบรับรอง สก.',
                              'fee_ref' => 'new_certifcate',
                              'fee_ib' => '3000.00',
                              'fee_cb' => '3000.00',
                              'fee_lab' => '3000.00',
                              'fee_start' => '2022-11-02',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $setting = SettingFee::where('fee_ref', 'check_req')->first();
        if(is_null($setting)){
            SettingFee::insert([
                              'fee_name' => 'ค่าตรวจสอบคำขอ',
                              'fee_ref' => 'check_req',
                              'fee_ib' => '1000.00',
                              'fee_cb' => '1000.00',
                              'fee_lab' => null,
                              'fee_start' => '2022-11-02',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $setting = SettingFee::where('fee_ref', 'expand_scope')->first();
        if(is_null($setting)){
            SettingFee::insert([
                              'fee_name' => 'ค่าธรรมเนียมใบรับรอง สก. (ขยายขอบข่าย)',
                              'fee_ref' => 'expand_scope',
                              'fee_ib' => '1500.00',
                              'fee_cb' => '1500.00',
                              'fee_lab' => '1500.00',
                              'fee_start' => '2022-11-02',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $setting = SettingFee::where('fee_ref', 'renew_certificate')->first();
        if(is_null($setting)){
            SettingFee::insert([
                              'fee_name' => 'ค่าธรรมเนียมใบรับรอง สก. (ต่ออายุ)',
                              'fee_ref' => 'renew_certificate',
                              'fee_ib' => '1500.00',
                              'fee_cb' => '1500.00',
                              'fee_lab' => '1500.00',
                              'fee_start' => '2022-11-02',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $setting = SettingFee::where('fee_ref', 'verify_certificate')->first();
        if(is_null($setting)){
            SettingFee::insert([
                              'fee_name' => 'ค่าธรรมเนียมใบรับรอง สก. (ตรวจติดตาม)',
                              'fee_ref' => 'verify_certificate',
                              'fee_ib' => '1500.00',
                              'fee_cb' => '1500.00',
                              'fee_lab' => '1500.00',
                              'fee_start' => '2022-11-02',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

    }
}

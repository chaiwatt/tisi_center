<?php

use Illuminate\Database\Seeder;

class SettingSystemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('setting_systems')->delete();

        \DB::table('setting_systems')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => 'e-License',
                'details' => 'ระบบออกใบอนุญาต',
                'urls' => 'https://i.tisi.go.th/e-license-test/index.php?option=com_users&task=checklogin.allowlogin',
                'icons' => 'mdi-wunderlist',
                'colors' => 'btn-success',
                'state' => 1,
                'created_by' => 1,
                'created_at' => '2022-01-26 00:00:00',
                'updated_by' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'title' => 'NSW',
                'details' => 'ระบบ NSW',
                'urls' => 'http://110.78.208.17/tisi_sso/public',
                'icons' => 'mdi-ferry',
                'colors' => 'btn-warning',
                'state' => 1,
                'created_by' => 1,
                'created_at' => '2022-01-26 00:00:00',
                'updated_by' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'title' => 'e-Surveillance',
                'details' => 'ระบบตรวจติดตามออนไลน์',
                'urls' => 'http://110.78.208.17/esur65/public/home',
                'icons' => 'mdi-clipboard-text',
                'colors' => 'btn-info',
                'state' => 1,
                'created_by' => 1,
                'created_at' => '2022-01-26 00:00:00',
                'updated_by' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'title' => 'รับรองระบบงาน',
                'details' => 'ระบบการรับรองระบบงาน',
                'urls' => 'http://110.78.208.17/e-acc/public/home',
                'icons' => 'mdi-certificate',
                'colors' => 'btn-primary',
                'state' => 1,
                'created_by' => 1,
                'created_at' => '2022-01-26 00:00:00',
                'updated_by' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}

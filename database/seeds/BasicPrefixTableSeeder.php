<?php

use Illuminate\Database\Seeder;

class BasicPrefixTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('basic_prefix')->delete();

        \DB::table('basic_prefix')->insert(array (
            0 =>
            array (
                'id' => 1,
                'code' => '',
                'title' => 'นาย',
                'title_en' => 'Mr.',
                'initial' => 'นาย',
                'ordering' => 1,
                'state' => 1,
                'checked_out_time' => null,
                'checked_out' => 0,
                'created' => '2016-12-14 04:35:07',
                'created_by' => 137,
                'modified' => NULL,
                'modified_by' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'code' => '',
                'title' => 'นาง',
                'title_en' => 'Ms.',
                'initial' => 'นาง',
                'ordering' => 2,
                'state' => 1,
                'checked_out_time' => null,
                'checked_out' => 0,
                'created' => '2016-12-14 04:35:51',
                'created_by' => 137,
                'modified' => NULL,
                'modified_by' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'code' => '',
                'title' => 'นางสาว',
                'title_en' => 'Mrs.',
                'initial' => 'น.ส.',
                'ordering' => 3,
                'state' => 1,
                'checked_out_time' => null,
                'checked_out' => 0,
                'created' => '2016-12-14 04:36:18',
                'created_by' => 137,
                'modified' => NULL,
                'modified_by' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'code' => '',
                'title' => 'อื่นๆ',
                'title_en' => '',
                'initial' => 'อื่นๆ',
                'ordering' => 4,
                'state' => 1,
                'checked_out_time' => null,
                'checked_out' => 0,
                'created' => '2017-03-08 08:23:45',
                'created_by' => 137,
                'modified' => NULL,
                'modified_by' => NULL,
            ),
        ));


    }
}

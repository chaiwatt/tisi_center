<?php

use Illuminate\Database\Seeder;

class LawDivisionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('law_basic_division_type')->delete();

        \DB::table('law_basic_division_type')->insert(array (
            array (
                'id' => 1,
                'title' => 'หักเข้าหลวง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 2,
                'title' => 'แบ่งเงินรางวัล',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 3,
                'title' => 'ค่าใช้จ่ายในการดำเนินการ',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));

    }
}

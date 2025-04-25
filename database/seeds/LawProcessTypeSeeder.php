<?php

use Illuminate\Database\Seeder;

class LawProcessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('law_basic_process_type')->delete();

        \DB::table('law_basic_process_type')->insert(array (
            array (
                'id' => 1,
                'title' => 'ดำเนินการทางอาญา',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 2,
                'title' => 'ดำเนินการทางปกครอง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 3,
                'title' => 'ดำเนินการกับผลิตภัณฑ์',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));

    }
}

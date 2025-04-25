<?php

use Illuminate\Database\Seeder;

class LawArrestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('law_basic_arrest')->delete();

        \DB::table('law_basic_arrest')->insert(array (
            array (
                'id' => 1,
                'title' => 'ไม่มีการจับกุม',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 2,
                'title' => 'มีการจับกุม',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));

    }
}


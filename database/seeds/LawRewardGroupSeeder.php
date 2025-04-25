<?php

use Illuminate\Database\Seeder;

class LawRewardGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('law_basic_reward_group')->delete();

        \DB::table('law_basic_reward_group')->insert(array (
            array (
                'id' => 1,
                'title' => 'เจ้าของเรื่อง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 2,
                'title' => 'ผู้มีส่วนร่วมในการจับกุม',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 3,
                'title' => 'นิติกร',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 4,
                'title' => 'ผก.กต/ผก.กค.',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 5,
                'title' => 'ผอ.กม.',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 6,
                'title' => 'ผก.กม.',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 7,
                'title' => 'รองเลขาธิการ',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 8,
                'title' => 'เลขาธิการ',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));

    }
}

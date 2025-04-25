<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_types')->insert([
            [
                'name' => 'Single Site'
            ],
            [
                'name' => 'Multiple Site'
            ]
        ]);
    }
}

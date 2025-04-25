<?php

use Illuminate\Database\Seeder;
use App\Models\Law\Log\LawSystemCategory;

class LawSystemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $system_category = LawSystemCategory::where('id', '1')->first();
        if(is_null($system_category)){
            LawSystemCategory::insert([
                              'id' => '1',
                              'name' => 'ระบบงานคดี',
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $system_category = LawSystemCategory::where('id', '2')->first();
        if(is_null($system_category)){
            LawSystemCategory::insert([
                              'id' => '2',
                              'name' => 'ระบบงานสินบน',
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $system_category = LawSystemCategory::where('id', '3')->first();
        if(is_null($system_category)){
            LawSystemCategory::insert([
                              'id' => '3',
                              'name' => 'ระบบงานร่างกฏกระทรวง',
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $system_category = LawSystemCategory::where('id', '4')->first();
        if(is_null($system_category)){
            LawSystemCategory::insert([
                              'id' => '4',
                              'name' => 'ระบบงานห้องสมุดกฏหมาย',
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $system_category = LawSystemCategory::where('id', '5')->first();
        if(is_null($system_category)){
            LawSystemCategory::insert([
                              'id' => '5',
                              'name' => 'ระบบติดตามงานต่างๆ',
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }
    }
}

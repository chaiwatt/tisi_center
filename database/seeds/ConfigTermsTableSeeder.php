<?php

use Illuminate\Database\Seeder;
use App\Models\Basic\ConfigTerm;

class ConfigTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $config = ConfigTerm::where('variable', 'age')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'age',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'amount')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'amount',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'state1')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'state1',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'condition1')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'condition1',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'alert1')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'alert1',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'state2')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'state2',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'condition2')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'condition2',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'alert2')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'alert2',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'state3')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'state3',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'condition3')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'condition3',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $config = ConfigTerm::where('variable', 'alert3')->first();
      if(is_null($config)){
        ConfigTerm::insert([
                          'variable' => 'alert3',
                          'note' => '',
                          'data' => '',
                          'created_at' => date('Y-m-d H:i:s')
                      ]);
      }

    }
}

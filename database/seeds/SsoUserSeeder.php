<?php

use Faker\Factory;
use App\Models\Sso\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\Permission;

class SsoUserSeeder extends DatabaseSeeder
{

    public function run()
    {

        $admin = User::firstOrCreate(array(
                    'username' => 'admin',
                    'email' => 'admin@ramintrasoft.com',
                    'name' => 'admin',
                    'contact_name' => 'ผู้ดูแลระบบ',
                    'params' => '{}',
                    // 'department_id' => 0,
                    //'agency_tel' => '',
                    //'authorize_data' => ''
                ));
        $admin->password = bcrypt("1234");
        $admin->save();

        $this->command->info('Admin User created with username admin@ramintrasoft.com and password 1234');
    }

}

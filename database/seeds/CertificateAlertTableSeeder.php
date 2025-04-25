<?php

use Illuminate\Database\Seeder;
use App\Models\Bcertify\CertificateAlert;

class CertificateAlertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $certificate_alert = CertificateAlert::find(1);
      if(is_null($certificate_alert)){
        CertificateAlert::insert([
                          'id' => '1',
                          'color' => 'red',
                          'date_start' => '5',
                          'date_end' => NULL,
                          'status' => 'on',
                          'created_at' => date('Y-m-d H:i:s'),
                          'updated_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $certificate_alert = CertificateAlert::find(2);
      if(is_null($certificate_alert)){
        CertificateAlert::insert([
                          'id' => '2',
                          'color' => 'yellow',
                          'date_start' => '6',
                          'date_end' => '10',
                          'status' => 'off',
                          'created_at' => date('Y-m-d H:i:s'),
                          'updated_at' => date('Y-m-d H:i:s')
                      ]);
      }

      $certificate_alert = CertificateAlert::find(3);
      if(is_null($certificate_alert)){
        CertificateAlert::insert([
                          'id' => '3',
                          'color' => 'green',
                          'date_start' => '11',
                          'date_end' => NULL,
                          'status' => 'off',
                          'created_at' => date('Y-m-d H:i:s'),
                          'updated_at' => date('Y-m-d H:i:s')
                      ]);
      }

    }
}

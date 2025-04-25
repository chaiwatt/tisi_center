<?php

use Illuminate\Database\Seeder;
use App\Models\Certificate\TrackingStatus AS Status;
class TrackingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = Status::where('id', '1')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '1',
                              'title' => 'รอดำเนินการตรวจ',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '2')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '2',
                              'title' => 'มอบหมายการตรวจติดตาม',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '3')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '3',
                              'title' => 'อยู่ระหว่างดำเนินการ',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '4')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '4',
                              'title' => 'อยู่ระหว่างสรุปผลตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '5')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '5',
                              'title' => 'อยู่ระหว่างยืนยัน Scope',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '6')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '6',
                              'title' => 'สรุปรายงานและเสนออนุกรรมการฯ',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '7')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '7',
                              'title' => 'รอยืนยันขอบข่ายตามมติคณะกรรมการฯ',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '8')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '8',
                              'title' => 'ทบทวนฯ',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '9')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '9',
                              'title' => 'อยู่ระหว่างแจ้งรายละเอียดการชำระค่าใบรับรอง',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '10')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '10',
                              'title' => 'แจ้งรายละเอียดการชำระค่าใบรับรอง',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '11')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '11',
                              'title' => 'แจ้งหลักฐานการชำระค่าใบรับรอง',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $status = Status::where('id', '12')->first();
        if(is_null($status)){
            Status::insert([
                              'id' => '12',
                              'title' => 'ต่อขอบข่ายเรียบร้อย',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }
    }
}

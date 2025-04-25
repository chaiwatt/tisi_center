<?php

use Illuminate\Database\Seeder;
use App\Models\Certificate\TrackingAuditorsStep AS Step;
class TrackingAuditorsStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $step = Step::where('id', '1')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '1',
                              'title' => 'อยู่ระหว่างแต่งตั้งคณะผู้ตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $step = Step::where('id', '2')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '2',
                              'title' => 'ขอความเห็นแต่งคณะผู้ตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $step = Step::where('id', '3')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '3',
                              'title' => 'ขอความเห็นแต่งคณะผู้ตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $step = Step::where('id', '4')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '4',
                              'title' => 'แจ้งรายละเอียดค่าตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        $step = Step::where('id', '5')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '5',
                              'title' => 'แจ้งหลักฐานการชำระเงิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

    
        
        $step = Step::where('id', '6')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '6',
                              'title' => 'ยืนยันการชำระเงินค่าตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        
        $step = Step::where('id', '7')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '7',
                              'title' => 'ผ่านการตรวจสอบประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        
        $step = Step::where('id', '8')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '8',
                              'title' => 'แก้ไขข้อบกพร่อง/ข้อสังเกต',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        
        $step = Step::where('id', '9')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '9',
                              'title' => 'ปิดการตรวจสอบประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        
        $step = Step::where('id', '10')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '10',
                              'title' => 'ยืนยัน Scope',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        
        $step = Step::where('id', '11')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '11',
                              'title' => 'ขอแก้ไข Scope',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }

        
        $step = Step::where('id', '12')->first();
        if(is_null($step)){
            Step::insert([
                              'id' => '12',
                              'title' => 'ยกเลิกคณะผู้ตรวจประเมิน',
                              'created_at' => date('Y-m-d H:i:s'),   
                              'updated_at' => date('Y-m-d H:i:s')
                          ]);
        }
    }
}

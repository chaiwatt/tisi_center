<?php

use Illuminate\Database\Seeder;

class ApplicationLabStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('section5_application_labs_status')->delete();

        \DB::table('section5_application_labs_status')->insert(array (
            array (
                'id' => 1,
                'title' => 'อยู่ระหว่างการตรวจสอบ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 2,
                'title' => 'เอกสารไม่ครบถ้วน',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 3,
                'title' => 'เอกสารครบถ้วน อยู่ระหว่างตรวจประเมิน',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 4,
                'title' => 'เอกสารครบถ้วน อยู่ระหว่างสรุปรายงาน',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 5,
                'title' => 'ตรวจสอบเอกสารอีกครั้ง',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 6,
                'title' => 'ไม่รับคำขอ/Reject',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 7,
                'title' => 'ไม่ผ่านการตรวจประเมิน',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 8,
                'title' => 'อยู่ระหว่างการพิจารณาอนุมัติ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 9,
                'title' => 'อนุมัติ อยู่ระหว่างเสนอคณะอนุกรรมการ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 10,
                'title' => 'ไม่อนุมัติ ตรวจสอบอีกครั้ง',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 11,
                'title' => 'อยู่ระหว่างเสนอ. กมอ.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 12,
                'title' => 'กมอ. ไม่อนุมัติ ตรวจสอบอีกครั้ง',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 13,
                'title' => 'อยู่ระหว่างจัดทำประกาศ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 14,
                'title' => 'จัดทำประกาศแล้ว รอประกาศราชกิจจาฯ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 99,
                'title' => 'ประกาศราชกิจจาฯ แล้ว',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 100,
                'title' => 'ยกเลิก',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));


    }
}

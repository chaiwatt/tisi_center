<?php

use Illuminate\Database\Seeder;

class LawTypeFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('law_basic_type_files')->delete();

        \DB::table('law_basic_type_files')->insert(array (
            array (
                'id' => 1,
                'title' => 'พระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ.2511',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 2,
                'title' => 'ประกาศราชกฤษฎีกา',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 3,
                'title' => 'กฏหมายกระทรวง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 4,
                'title' => 'ประกาศกระทรวง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 5,
                'title' => 'ประกาศ สมอ.',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 6,
                'title' => 'คำสั่ง และระเบียบที่เกี่ยวข้อง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 7,
                'title' => 'กฏหมาย/ระเบียบ/ข้อบังคับ ของหน่วยงานที่เกี่ยวข้อง',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            array (
                'id' => 8,
                'title' => 'รายงานการประชุม',
                'state' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => NULL,
            )
        ));

    }
}

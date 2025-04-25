<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->date('offend_date')->nullable()->comment('วันที่พบการกระทำความผิด')->after('owner_contact_email')->change();
            $table->text('law_basic_section_id')->nullable()->comment('ฝ่าฝืนตามมาตรา (่json)')->change();
            $table->integer('status')->nullable()->comment('สถานะ 99 = ยกเลิก 0 = ฉบับร่าง 1 = แจ้งงานคดีสำเร็จ 2 = อยู่ระหว่างตรวจสอบข้อมูล 3 = ขอข้อมูลเพิ่มเติม (ตีกลับ) 4 = ข้อมูลครบถ้วนอยู่ระหว่างพิจารณา 5 = พบการกระทำความผิด 6 = ไม่พบการกระทำความผิด 7 = ส่งเรื่องดำเนินคดี 8 = แจ้งการกระทำความผิด 9 = อยู่ระหว่างเปรียบเทียบปรับ 10 = เปรียบปรับแล้ว ')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->date('offend_date')->comment('วันที่พบการกระทำความผิด')->after('owner_contact_email')->change();
            $table->text('law_basic_section_id')->comment('ฝ่าฝืนตามมาตรา (่json)')->change();
            $table->integer('status')->comment('สถานะ 99 = ยกเลิก 0 = ฉบับร่าง 1 = แจ้งงานคดีสำเร็จ 2 = อยู่ระหว่างตรวจสอบข้อมูล 3 = ขอข้อมูลเพิ่มเติม (ตีกลับ) 4 = ข้อมูลครบถ้วนอยู่ระหว่างพิจารณา 5 = พบการกระทำความผิด 6 = ไม่พบการกระทำความผิด 7 = ส่งเรื่องดำเนินคดี 8 = แจ้งการกระทำความผิด 9 = อยู่ระหว่างเปรียบเทียบปรับ 10 = เปรียบปรับแล้ว ')->change();
        });
    }
}

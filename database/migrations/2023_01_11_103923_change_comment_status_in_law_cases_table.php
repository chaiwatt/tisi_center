<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCommentStatusInLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->integer('status')->comment('สถานะการดำเนินงาน 99 = ยกเลิก 0 = ฉบับร่าง 1 = แจ้งงานคดีสำเร็จ 2 = อยู่ระหว่างตรวจสอบข้อมูล 3 = ขอข้อมูลเพิ่มเติม (ตีกลับ) 4 = ข้อมูลครบถ้วน (อยู่ระหว่างพิจารณา) 5 = พบการกระทำความผิด 6 = ไม่พบการกระทำความผิด 7 = ส่งเรื่องดำเนินคดี 8 = แจ้งการกระทำความผิด 9 = ยินยอมเปรียบเทียบปรับ 10 = ไม่ยินยอมเปรียบเทียบปรับ 11 = บันทึกผลแจ้งเปรียบเทียบปรับ 12 = ตรวจสอบการชำระเงินแล้ว 13 = ดำเนินการกับใบอนุญาต 14 = ดำเนินการกับผลิตภัณฑ์ 15 = ดำเนินการเสร็จสิ้น')->change();
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
            $table->integer('status')->comment('สถานะ 99 = ยกเลิก 0 = ฉบับร่าง 1 = แจ้งงานคดีสำเร็จ 2 = อยู่ระหว่างตรวจสอบข้อมูล 3 = ขอข้อมูลเพิ่มเติม (ตีกลับ) 4 = ข้อมูลครบถ้วนอยู่ระหว่างพิจารณา 5 = พบการกระทำความผิด 6 = ไม่พบการกระทำความผิด 7 = ส่งเรื่องดำเนินคดี 8 = แจ้งการกระทำความผิด 9 = อยู่ระหว่างเปรียบเทียบปรับ 10 = เปรียบเทียบปรับแล้ว')->change();
        });
    }
}

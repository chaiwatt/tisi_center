<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabCodeToSection5ApplicationLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->integer('applicant_type')->default(1)->comment('ประเภทคำขอ: 1 ขอขึ้นทะเบียนใหม่, 2 ขอเพิ่มเติมขอบข่าย, 3 ขอลดขอบข่าย, 4 ขอแก้ไขข้อมูล')->after('id');
            $table->integer('lab_id')->nullable()->comment('ID : รหัสปฏิบัติการ ตาราง section5_labs')->after('hq_zipcode');
            $table->string('lab_code', 255)->nullable()->comment('ห้องปฏิบัติการ: รหัสปฏิบัติการ')->after('lab_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->dropColumn(['applicant_type', 'lab_id', 'lab_code']);
        });
    }
}

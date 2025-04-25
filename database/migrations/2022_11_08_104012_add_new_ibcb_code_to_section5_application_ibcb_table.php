<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewIbcbCodeToSection5ApplicationIbcbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb', function (Blueprint $table) {
            $table->integer('applicant_request_type')->default(1)->comment('ประเภทคำขอ: 1 ขอขึ้นทะเบียนใหม่, 2 ขอเพิ่มเติมขอบข่าย, 3 ขอลดขอบข่าย, 4 ขอแก้ไขข้อมูล')->after('id');
            $table->integer('ibcb_id')->nullable()->comment('ID : ตาราง section5_ibcbs')->after('hq_zipcode');
            $table->string('ibcb_code', 255)->nullable()->comment('หน่วยตรวจสอบ: รหัสหน่วยตรวจสอบ')->after('ibcb_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb', function (Blueprint $table) {
            $table->dropColumn(['applicant_request_type', 'ibcb_id', 'ibcb_code']);
            
        });
    }
}

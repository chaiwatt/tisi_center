<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToSection5ApplicationLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->date('applicant_date_niti')->nullable()->comment('ข้อมูลผู้ยื่นขอ วันเกิด/วันที่จดทะเบียนนิติบุคคล')->after('applicant_name');
            $table->string('hq_building',255)->nullable()->comment('ข้อมูลสำนักงานใหญ่: ข้อมูลผู้ยื่นขอ อาคาร/หมู่บ้าน')->after('hq_road');
            $table->string('lab_building',255)->nullable()->comment('ห้องปฏิบัติการ: ข้อมูลผู้ยื่นขอ อาคาร/หมู่บ้าน')->after('lab_road');
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
            $table->dropColumn(['applicant_date_niti','hq_building', 'lab_building']);
        });
    }
}

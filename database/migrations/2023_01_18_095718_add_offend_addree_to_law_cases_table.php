<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffendAddreeToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->string('offend_moo')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: หมู่');
            $table->string('offend_soi')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ซอย');
            $table->string('offend_building',255)->nullable()->comment('ที่ตั้งสำนักงานใหญ่: อาคาร/หมู่บ้าน');
            $table->string('offend_street')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ซอยถนน');
            $table->integer('offend_subdistrict_id')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ตำบล/แขวง');
            $table->integer('offend_district_id')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: อำเภอ/เขต');
            $table->integer('offend_province_id')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: จังหวัด');
            $table->string('offend_zipcode')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: รหัสไปรษณีย์');
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
            $table->dropColumn(['offend_moo', 'offend_soi', 'offend_building', 'offend_street','offend_subdistrict_id','offend_district_id','offend_province_id','offend_zipcode']);
            
        });
    }
}

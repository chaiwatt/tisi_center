<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAppCertiLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->string('hq_address')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ที่อยู่สำนักงานใหญ่');
            $table->string('hq_moo')->nullable()->comment('ข้อมูลสำนักงานใหญ่: หมู่');
            $table->string('hq_soi')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอย');
            $table->string('hq_road')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอยถนน');
            $table->integer('hq_subdistrict_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ตำบล/แขวง');
            $table->integer('hq_district_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: อำเภอ/เขต');
            $table->integer('hq_province_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: จังหวัด');
            $table->string('hq_zipcode')->nullable()->comment('ข้อมูลสำนักงานใหญ่: รหัสไปรษณีย์');
            $table->date('hq_date_registered')->nullable()->comment('ข้อมูลสำนักงานใหญ่: จดทะเบียนเป็นนิติบุคคลเมื่อวันที่');
            $table->string('hq_telephone')->nullable()->comment('ข้อมูลสำนักงานใหญ่: โทรศัพท์');
            $table->string('hq_fax')->nullable()->comment('ข้อมูลสำนักงานใหญ่: โทรสาร');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            $table->dropColumn([
                'hq_address',
                'hq_moo',
                'hq_soi',
                'hq_road',
                'hq_subdistrict_id',
                'hq_district_id',
                'hq_province_id',
                'hq_zipcode',
                'hq_date_registered',
                'hq_telephone',
                'hq_fax'
            ]);
        });
    }
}

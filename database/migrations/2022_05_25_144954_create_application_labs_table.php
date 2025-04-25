<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs', function (Blueprint $table) {

            $table->increments('id');
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->date('application_date')->nullable()->comment('วันที่ยื่นคำขอ');
            $table->integer('application_status')->nullable()->comment('สถานะ');
            $table->string('applicant_taxid')->nullable()->comment('ข้อมูลผู้ยื่นขอ เลขที่ประจำตัว/เลขผู้เสียภาษี');
            $table->string('applicant_name')->nullable()->comment('ข้อมูลผู้ยื่นขอ ชื่อ-สกุล');

            $table->string('hq_address')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ที่อยู่สำนักงานใหญ่');
            $table->string('hq_moo')->nullable()->comment('ข้อมูลสำนักงานใหญ่: หมู่');
            $table->string('hq_soi')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอย');
            $table->string('hq_road')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอยถนน');
            $table->integer('hq_subdistrict_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ตำบล/แขวง');
            $table->integer('hq_district_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: อำเภอ/เขต');
            $table->integer('hq_province_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: จังหวัด');
            $table->string('hq_zipcode')->nullable()->comment('ข้อมูลสำนักงานใหญ่: รหัสไปรษณีย์');

            $table->string('lab_name')->nullable()->comment('ห้องปฏิบัติการ: ชื่อห้องปฏิบัติการ');
            $table->string('lab_address')->nullable()->comment('ห้องปฏิบัติการ: ที่อยู่');
            $table->string('lab_moo')->nullable()->comment('ห้องปฏิบัติการ: หมู่');
            $table->string('lab_soi')->nullable()->comment('ห้องปฏิบัติการ: ซอย');
            $table->string('lab_road')->nullable()->comment('ห้องปฏิบัติการ: ซอยถนน');
            $table->integer('lab_subdistrict_id')->nullable()->comment('ห้องปฏิบัติการ: ตำบล/แขวง');
            $table->integer('lab_district_id')->nullable()->comment('ห้องปฏิบัติการ: อำเภอ/เขต');
            $table->integer('lab_province_id')->nullable()->comment('ห้องปฏิบัติการ: จังหวัด');
            $table->string('lab_zipcode')->nullable()->comment('ห้องปฏิบัติการ: รหัสไปรษณีย์');
            $table->string('lab_phone')->nullable()->comment('ห้องปฏิบัติการ: เบอร์โทรศัพท์');
            $table->string('lab_fax')->nullable()->comment('ห้องปฏิบัติการ: เบอร์แฟกซ์');

            $table->string('co_name')->nullable()->comment('ผู้ประสานงาน: ชื่อผู้ประสานงาน');
            $table->string('co_position')->nullable()->comment('ผู้ประสานงาน: ตำแหน่งผู้ประสานงาน');
            $table->string('co_mobile')->nullable()->comment('ผู้ประสานงาน: เบอร์มือถือ');
            $table->string('co_phone')->nullable()->comment('ผู้ประสานงาน: เบอร์โทรศัพท์');
            $table->string('co_fax')->nullable()->comment('ผู้ประสานงาน: เบอร์แฟกซ์');
            $table->string('co_email')->nullable()->comment('ผู้ประสานงาน: อีเมล');

            $table->integer('audit_type')->nullable()->comment('ประเภทการตรวจ 1 = ตรวจตามใบรับรอง 2 = ตรวจตามภาคผนวก ก.');
            $table->text('audit_date')->nullable()->comment('ช่วงวันที่พร้อมให้เข้าตรวจประเมิน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section5_application_labs');
    }
}

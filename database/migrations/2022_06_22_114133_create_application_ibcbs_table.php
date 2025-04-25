<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb', function (Blueprint $table) {
            $table->increments('id');
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->date('application_date')->nullable()->comment('วันที่ยื่นคำขอ');
            $table->integer('application_status')->nullable()->comment('สถานะ');
            $table->integer('application_type')->nullable()->comment('ประเภทคำขอ 1 = IB 2 = CB');
            $table->string('applicant_taxid')->nullable()->comment('ข้อมูลผู้ยื่นขอ เลขที่ประจำตัว/เลขผู้เสียภาษี');
            $table->string('applicant_name')->nullable()->comment('ข้อมูลผู้ยื่นขอ ชื่อ-สกุล');
            $table->date('applicant_date_niti')->nullable()->comment('ข้อมูลผู้ยื่นขอ วันเกิด/วันที่จดทะเบียนนิติบุคคล');

            $table->string('hq_address')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ที่อยู่สำนักงานใหญ่');
            $table->string('hq_building')->nullable()->comment('ข้อมูลสำนักงานใหญ่: อาคาร/หมู่บ้าน');
            $table->string('hq_moo')->nullable()->comment('ข้อมูลสำนักงานใหญ่: หมู่');
            $table->string('hq_soi')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอย');
            $table->string('hq_road')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอยถนน');
            $table->integer('hq_subdistrict_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ตำบล/แขวง');
            $table->integer('hq_district_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: อำเภอ/เขต');
            $table->integer('hq_province_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: จังหวัด');
            $table->string('hq_zipcode')->nullable()->comment('ข้อมูลสำนักงานใหญ่: รหัสไปรษณีย์');

            $table->string('ibcb_name')->nullable()->comment('หน่วยงาน: ชื่อหน่วยงาน');
            $table->string('ibcb_address')->nullable()->comment('หน่วยงาน: ที่อยู่');
            $table->string('ibcb_building')->nullable()->comment('หน่วยงาน: อาคาร/หมู่บ้าน');
            $table->string('ibcb_moo')->nullable()->comment('หน่วยงาน: หมู่');
            $table->string('ibcb_soi')->nullable()->comment('หน่วยงาน: ซอย');
            $table->string('ibcb_road')->nullable()->comment('หน่วยงาน: ซอยถนน');
            $table->integer('ibcb_subdistrict_id')->nullable()->comment('หน่วยงาน: ตำบล/แขวง');
            $table->integer('ibcb_district_id')->nullable()->comment('หน่วยงาน: อำเภอ/เขต');
            $table->integer('ibcb_province_id')->nullable()->comment('หน่วยงาน: จังหวัด');
            $table->string('ibcb_zipcode')->nullable()->comment('หน่วยงาน: รหัสไปรษณีย์');
            $table->string('ibcb_phone')->nullable()->comment('หน่วยงาน: เบอร์โทรศัพท์');
            $table->string('ibcb_fax')->nullable()->comment('หน่วยงาน: เบอร์แฟกซ์');

            $table->string('co_name')->nullable()->comment('ผู้ประสานงาน: ชื่อผู้ประสานงาน');
            $table->string('co_position')->nullable()->comment('ผู้ประสานงาน: ตำแหน่งผู้ประสานงาน');
            $table->string('co_mobile')->nullable()->comment('ผู้ประสานงาน: เบอร์มือถือ');
            $table->string('co_phone')->nullable()->comment('ผู้ประสานงาน: เบอร์โทรศัพท์');
            $table->string('co_fax')->nullable()->comment('ผู้ประสานงาน: เบอร์แฟกซ์');
            $table->string('co_email')->nullable()->comment('ผู้ประสานงาน: อีเมล');
 
            $table->integer('audit_type')->nullable()->comment('ประเภทการตรวจ 1 = ได้รับ มีหลักฐาน 2 = ไม่ได้รับ ทำการตรวจตามภาคผนวก ก.');

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
        Schema::dropIfExists('section5_application_ibcb');
    }
}

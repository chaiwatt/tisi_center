<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationInspectionUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_application_inspection_units', function (Blueprint $table) {
            $table->increments('id');

            $table->string('refno_application', 255)->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->date('date_application')->nullable()->comment('วันที่ยื่นคำขอ');

            $table->string('authorized_taxid', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ : เลขที่ประจำตัว/เลขผู้เสียภาษี');
            $table->string('authorized_name', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ ชื่อ-สกุล');
            $table->date('authorized_date_niti')->nullable()->comment('วันเกิด/วันที่จดทะเบียนนิติบุคคล');
            $table->string('authorized_email', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ อีเมล');
            $table->string('authorized_mobile', 50)->nullable()->comment('ข้อมูลผู้ยื่นขอ เบอร์มือถือ');
            $table->string('authorized_tel', 50)->nullable()->comment('ข้อมูลผู้ยื่นขอ เบอร์โทรศัพท์');
            $table->string('authorized_fax', 100)->nullable()->comment('ข้อมูลผู้ยื่นขอ เบอร์โทรสาร');

            $table->string('authorized_address', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ ที่อยู่สำนักงานแห่งใหญ่ ตั้งอยู่เลขที่');
            $table->string('authorized_moo', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ หมู่');
            $table->string('authorized_soi', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ ซอย');
            $table->string('authorized_road', 255)->nullable()->comment('ข้อมูลผู้ยื่นขอ ถนน');
            $table->integer('authorized_subdistrict_id')->nullable()->comment('ข้อมูลผู้ยื่นขอ ตำบล');
            $table->integer('authorized_district_id')->nullable()->comment('ข้อมูลผู้ยื่นขอ อำเภอ');
            $table->integer('authorized_province_id')->nullable()->comment('ข้อมูลผู้ยื่นขอ จังหวัด');
            $table->string('authorized_postcode', 50)->nullable()->comment('ข้อมูลผู้ยื่นขอ รหัสไปรษณีย์');

            $table->string('laboratory_address', 255)->nullable()->comment('ห้องปฏิบัติการ ที่อยู่สำนักงานแห่งใหญ่ ตั้งอยู่เลขที่');
            $table->string('laboratory_moo', 255)->nullable()->comment('ห้องปฏิบัติการ หมู่');
            $table->string('laboratory_soi', 255)->nullable()->comment('ห้องปฏิบัติการ ซอย');
            $table->string('laboratory_road', 255)->nullable()->comment('ห้องปฏิบัติการ ถนน');
            $table->integer('laboratory_subdistrict_id')->nullable()->comment('ห้องปฏิบัติการ ตำบล');
            $table->integer('laboratory_district_id')->nullable()->comment('ห้องปฏิบัติการ อำเภอ');
            $table->integer('laboratory_province_id')->nullable()->comment('ห้องปฏิบัติการ จังหวัด');
            $table->string('laboratory_postcode', 50)->nullable()->comment('ห้องปฏิบัติการ รหัสไปรษณีย์');
            $table->string('laboratory_tel', 50)->nullable()->comment('ห้องปฏิบัติการ เบอร์โทรศัพท์');
            $table->string('laboratory_fax', 50)->nullable()->comment('ห้องปฏิบัติการ เบอร์โทรสาร');

            $table->string('contact_name', 255)->nullable()->comment('ข้อมูลติดต่อ ชื่อ-นามสกุล');
            $table->string('contact_position', 255)->nullable()->comment('ข้อมูลติดต่อ ตำแหน่ง');
            $table->string('contact_mobile', 255)->nullable()->comment('ข้อมูลติดต่อ เบอร์มือถือ');
            $table->string('contact_tel', 255)->nullable()->comment('ข้อมูลติดต่อ เบอร์โทรศัพท์');
            $table->string('contact_fax', 255)->nullable()->comment('ข้อมูลติดต่อ เบอร์โทรสาร');
            $table->string('contact_email', 255)->nullable()->comment('ข้อมูลติดต่อ อีเมล');
            
            $table->integer('status_application')->nullable()->comment('สถานะ');
            $table->text('remarks')->nullable()->comment('หมายเหตุ');

            $table->text('checking_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมตรวจสอบคำขอ');
            $table->integer('checking_by')->nullable()->comment('ID ผู้ตรวจสอบคำขอ');
            $table->date('checking_date')->nullable()->comment('วันที่ตรวจสอบคำขอสถานะ');

            $table->text('approve_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมพิจารณาอนุมัติ');
            $table->integer('approve_by')->nullable()->comment('ID ผู้พิจารณาอนุมัติ');
            $table->date('approve_date')->nullable()->comment('วันที่พิจารณาอนุมัติสถานะ');

            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('sso_application_inspection_units');
    }
}

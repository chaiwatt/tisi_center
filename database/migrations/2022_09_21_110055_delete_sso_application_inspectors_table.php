<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSsoApplicationInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sso_application_inspectors');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sso_application_inspectors', function (Blueprint $table) {
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
            $table->string('authorized_building',255)->nullable()->comment('ข้อมูลผู้ยื่นขอ อาคาร/หมู่บ้าน');
            $table->integer('authorized_subdistrict_id')->nullable()->comment('ข้อมูลผู้ยื่นขอ ตำบล');
            $table->integer('authorized_district_id')->nullable()->comment('ข้อมูลผู้ยื่นขอ อำเภอ');
            $table->integer('authorized_province_id')->nullable()->comment('ข้อมูลผู้ยื่นขอ จังหวัด');
            $table->string('authorized_postcode', 50)->nullable()->comment('ข้อมูลผู้ยื่นขอ รหัสไปรษณีย์');

            $table->string('contact_name', 255)->nullable()->comment('ผู้ขอรับการขึ้นทะเบียน ชื่อ-นามสกุล');
            $table->string('contact_position', 255)->nullable()->comment('ผู้ขอรับการขึ้นทะเบียน ตำแหน่ง');
            $table->string('contact_mobile', 255)->nullable()->comment('ผู้ขอรับการขึ้นทะเบียน เบอร์มือถือ');
            $table->string('contact_tel', 255)->nullable()->comment('ผู้ขอรับการขึ้นทะเบียน เบอร์โทรศัพท์');
            $table->string('contact_fax', 255)->nullable()->comment('ผู้ขอรับการขึ้นทะเบียน เบอร์โทรสาร');
            $table->string('contact_email', 255)->nullable()->comment('ผู้ขอรับการขึ้นทะเบียน อีเมล');

            $table->integer('tis_industry_id')->nullable()->comment('หมวดอุตสาหกรรม');

            $table->integer('status_application')->nullable()->comment('สถานะ');
            $table->text('remarks')->nullable()->comment('หมายเหตุ');

            $table->text('checking_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมตรวจสอบคำขอ');
            $table->integer('checking_by')->nullable()->comment('ID ผู้ตรวจสอบคำขอ');
            $table->date('checking_date')->nullable()->comment('วันที่ตรวจสอบคำขอสถานะ');
            $table->integer('checking_status')->nullable()->comment('สถานะ ตรวจสอบคำขอ');

            $table->text('approve_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมพิจารณาอนุมัติ');
            $table->integer('approve_by')->nullable()->comment('ID ผู้พิจารณาอนุมัติ');
            $table->date('approve_date')->nullable()->comment('วันที่พิจารณาอนุมัติสถานะ');
            $table->integer('approve_status')->nullable()->comment('สถานะ พิจารณาอนุมัติ');

            $table->string('assign_by')->comment('ผู้ได้รับมอบหมาย');
            $table->dateTime('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->text('assign_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมมอบหมาย');

            $table->timestamps();
        });
    }
}

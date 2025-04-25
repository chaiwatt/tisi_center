<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSsoApplicationInspectorRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sso_application_inspector_registers');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sso_application_inspector_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refno_application')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->date('date_application')->nullable()->comment('วันที่ยื่นคำขอ');
            $table->string('register_taxid')->nullable()->comment('ข้อมูลผู้ยื่นขอ เลขที่ประจำตัว/เลขผู้เสียภาษี');
            $table->string('register_name')->nullable()->comment('ข้อมูลผู้ยื่นขอ ชื่อ-สกุล');
            $table->string('register_position')->nullable()->comment('ข้อมูลผู้ยื่นขอ ตำแน่ง');
            $table->date('register_date_niti')->nullable()->comment('วันเกิด/วันที่จดทะเบียนนิติบุคคล');
            $table->string('register_email')->nullable()->comment('ข้อมูลผู้ยื่นขอ อีเมล');
            $table->string('register_mobile')->nullable()->comment('ข้อมูลผู้ยื่นขอ เบอร์มือถือ');
            $table->string('register_tel')->nullable()->comment('ข้อมูลผู้ยื่นขอ เบอร์โทรศัพท์');
            $table->string('register_fax')->nullable()->comment('ข้อมูลผู้ยื่นขอ เบอร์โทรสาร');
            $table->integer('agency_id')->nullable()->comment('ID หน่วยงาน');
            $table->string('agency_address')->nullable()->comment('หน่วยงาน ตั้งอยู่เลขที่');
            $table->string('agency_moo')->nullable()->comment('หน่วยงาน หมู่');
            $table->string('agency_soi')->nullable()->comment('หน่วยงาน ซอย');
            $table->string('agency_road')->nullable()->comment('หน่วยงาน ถนน');
            $table->string('agency_building')->nullable()->comment('หน่วยงาน อาคาร/หมู่บ้าน');
            $table->integer('agency_subdistrict_id')->nullable()->comment('หน่วยงาน แขวง/ตำบล');
            $table->integer('agency_district_id')->nullable()->comment('หน่วยงาน เขต/อำเภอ');
            $table->integer('agency_province_id')->nullable()->comment('หน่วยงาน จังหวัด');
            $table->integer('agency_postcode')->nullable()->comment('หน่วยงาน หัสไปรษณีย์');
            $table->integer('status_application')->nullable()->comment('สถานะ');
            $table->text('remarks')->nullable()->comment('หมายเหตุ');
            $table->text('config_evidencce')->nullable()->comment('ตั้งค่าไฟล์แนบ');
            $table->text('checking_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมตรวจสอบคำขอ');
            $table->integer('checking_by')->nullable()->comment('ID ผู้ตรวจสอบคำขอ');
            $table->date('checking_date')->nullable()->comment('วันที่ตรวจสอบคำขอสถานะ');
            $table->integer('checking_status')->nullable()->comment('สถานะ ตรวจสอบคำขอ');
            $table->text('approve_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมพิจารณาอนุมัติ');
            $table->integer('approve_by')->nullable()->comment('ID ผู้พิจารณาอนุมัติ');
            $table->date('approve_date')->nullable()->comment('วันที่พิจารณาอนุมัติสถานะ');
            $table->integer('approve_status')->nullable()->comment('สถานะ พิจารณาอนุมัติ');
            $table->integer('assign_by')->nullable()->comment('ผู้ได้รับมอบหมาย');
            $table->date('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->text('assign_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมมอบหมาย');
            $table->timestamps();
        });
    }
}

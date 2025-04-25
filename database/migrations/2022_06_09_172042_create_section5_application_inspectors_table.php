<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_inspectors', function (Blueprint $table) {
            $table->increments('id')->comment('ไอดีประจำตาราง');

            $table->string('application_no',30)->nullable()->comment('เลขที่คำขอ');
            $table->datetime('application_date')->nullable()->comment('วันที่สมัคร');
            $table->integer('application_status')->nullable()->comment('สถานะ');
            $table->string('applicant_prefix',50)->nullable()->comment('คำนำหน้าผู้ยื่นคำขอ');
            $table->string('applicant_first_name',100)->nullable()->comment('ชื่อผู้ยื่นคำขอ');
            $table->string('applicant_last_name',100)->nullable()->comment('นามสกุลผู้ยื่นคำขอ');
            $table->string('applicant_full_name',255)->nullable()->comment('ชื่อเต็มผู้ยื่นคำขอ');
            $table->string('applicant_taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');
            $table->string('applicant_address',30)->nullable()->comment('ที่อยู่สำนักงานใหญ่');
            $table->string('applicant_moo',30)->nullable()->comment('หมู่');
            $table->string('applicant_soi',30)->nullable()->comment('ซอย');
            $table->string('applicant_road',30)->nullable()->comment('ถนน');
            $table->integer('applicant_subdistrict')->nullable()->comment('ไอดีแขวง');
            $table->integer('applicant_district')->nullable()->comment('ไอดีเขต');
            $table->integer('applicant_province')->nullable()->comment('ไอดีจังหวัด');
            $table->string('applicant_zipcode',5)->nullable()->comment('รหัสไปรษณีย์');
            $table->string('applicant_position',255)->nullable()->comment('ตำแหน่ง');
            $table->string('applicant_phone',30)->nullable()->comment('เบอร์โทร');
            $table->string('applicant_fax',30)->nullable()->comment('เบอร์แฟกซ์');
            $table->string('applicant_mobile',30)->nullable()->comment('เบอร์มือถือ');
            $table->string('applicant_email',30)->nullable()->comment('อีเมล');

            $table->string('agency_name',255)->nullable()->comment('ชื่อหน่วยงาน');
            $table->string('agency_taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');
            $table->string('agency_address',30)->nullable()->comment('ที่อยู่สำนักงานใหญ่');
            $table->string('agency_moo',30)->nullable()->comment('หมู่');
            $table->string('agency_soi',30)->nullable()->comment('ซอย');
            $table->string('agency_road',30)->nullable()->comment('ถนน');
            $table->integer('agency_subdistrict')->nullable()->comment('ไอดีแขวง');
            $table->integer('agency_district')->nullable()->comment('ไอดีเขต');
            $table->integer('agency_province')->nullable()->comment('ไอดีจังหวัด');
            $table->string('agency_zipcode',5)->nullable()->comment('รหัสไปรษณีย์');
    
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้อัพเดท');
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
        Schema::dropIfExists('section5_application_inspectors');
    }
}

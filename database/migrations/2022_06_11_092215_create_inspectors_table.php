<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_inspectors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('inspectors_code',255)->nullable()->comment('รหัสผู้ตรวจ/ผู้ประเมิน');

            $table->string('inspectors_prefix',50)->nullable()->comment('คำนำหน้า');
            $table->string('inspectors_first_name',255)->nullable()->comment('ชื่อ');
            $table->string('inspectors_last_name',255)->nullable()->comment('นามสกุล');
            $table->string('inspectors_taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');

            $table->string('inspectors_address',255)->nullable()->comment('ที่อยู่สำนักงานใหญ่');
            $table->string('inspectors_moo',255)->nullable()->comment('หมู่');
            $table->string('inspectors_soi',255)->nullable()->comment('ซอย');
            $table->string('inspectors_road',255)->nullable()->comment('ถนน');
            $table->integer('inspectors_subdistrict')->nullable()->comment('ไอดีแขวง');
            $table->integer('inspectors_district')->nullable()->comment('ไอดีเขต');
            $table->integer('inspectors_province')->nullable()->comment('ไอดีจังหวัด');
            $table->string('inspectors_zipcode',5)->nullable()->comment('รหัสไปรษณีย์');
            $table->string('inspectors_position',255)->nullable()->comment('ตำแหน่ง');

            $table->string('inspectors_phone',30)->nullable()->comment('เบอร์โทร');
            $table->string('inspectors_fax',30)->nullable()->comment('เบอร์แฟกซ์');
            $table->string('inspectors_mobile',30)->nullable()->comment('เบอร์มือถือ');
            $table->string('inspectors_email',255)->nullable()->comment('อีเมล');

            $table->string('agency_name',255)->nullable()->comment('ชื่อหน่วยงาน');
            $table->string('agency_taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');
            $table->string('agency_address',255)->nullable()->comment('ที่อยู่สำนักงานใหญ่');
            $table->string('agency_moo',30)->nullable()->comment('หมู่');
            $table->string('agency_soi',255)->nullable()->comment('ซอย');
            $table->string('agency_road',255)->nullable()->comment('ถนน');
            $table->integer('agency_subdistrict')->nullable()->comment('ไอดีแขวง');
            $table->integer('agency_district')->nullable()->comment('ไอดีเขต');
            $table->integer('agency_province')->nullable()->comment('ไอดีจังหวัด');
            $table->string('agency_zipcode',5)->nullable()->comment('รหัสไปรษณีย์');

            $table->datetime('inspector_first_date')->nullable()->comment('วันที่ขึ้นทะเบียนเป้นผู้ตรวจ');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
            $table->string('ref_inspector_application_no',100)->nullable()->comment('เลขที่คำขอ');

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
        Schema::dropIfExists('section5_inspectors');
    }
}

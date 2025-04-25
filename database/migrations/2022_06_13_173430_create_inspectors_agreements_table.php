<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorsAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_inspectors_agreements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->unsignedInteger('inspectors_id')->nullable();
            $table->string('inspectors_code',255)->nullable()->comment('รหัสผู้ตรวจ/ผู้ประเมิน');
            $table->string('inspectors_prefix',50)->nullable()->comment('คำนำหน้า');
            $table->string('inspectors_first_name',255)->nullable()->comment('ชื่อ');
            $table->string('inspectors_last_name',255)->nullable()->comment('นามสกุล');
            $table->string('inspectors_taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');
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
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->date('first_date')->nullable()->comment('วันที่ขึ้นทะเบียนเป็นผู้ตรวจ');
            $table->tinyInteger('agreement_status')->nullable()->comment('สถานะ 1 = ออกเอกสารแล้ว, 2 = แนบไฟล์เอกสารแล้ว');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();

            $table->foreign('application_id')
                    ->references('id')
                    ->on('section5_application_inspectors')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section5_inspectors_agreements');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationLabAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_audit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_lab_id')->nullable()->comment('ID ตาราง section5_application_labs');
            $table->string('application_no')->nullable()->comment('เลขที่คำขอ');
            $table->text('audit_date')->nullable()->comment('วันที่ตรวจประเมิน');
            $table->tinyInteger('audit_result')->nullable()->comment('ผลตรวจประเมิน 1 = ผ่าน, 2 = ไม่ผ่าน');
            $table->text('audit_remark')->nullable()->comment('หมายเหตุ');
            $table->tinyInteger('send_mail_status')->nullable()->comment('สถานะอีเมลแจ้งผล 1 = ไม่ส่งอีเมลแจ้งผล, 2 = ส่งอีเมลแจ้งผล');
            $table->text('noti_email')->nullable()->comment('อีเมลที่แจ้งผล');
            $table->text('created_by')->nullable()->comment('ผู้บันทึก');
            $table->text('updated_by')->nullable()->comment('ผู้อัพเดท');
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
        Schema::drop('section5_application_labs_audit');
    }
}

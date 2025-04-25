<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationInspectorAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_inspectors_audit', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');

            $table->date('audit_date')->nullable()->comment('วันที่ตรวจประเมิน');
            $table->integer('audit_result')->nullable()->comment('ผลตรวจประเมิน 1 ผ่าน, 2 ไม่ผ่าน');
            $table->text('audit_remark')->nullable()->comment('หมายเหตุ');
            $table->text('noti_email')->nullable()->comment('อีเมลที่แจ้ง');
  
            $table->integer('audit_approve')->nullable()->comment('อนุมัติ : สถานะการพิจารณา');
            $table->text('audit_approve_description')->nullable()->comment('อนุมัติ: รายละเอียดการพิจารณา สรุปรายงาน');
            $table->integer('audit_approve_by')->nullable()->comment('อนุมัติ: ผู้บันทึก');
            $table->integer('audit_updated_by')->nullable()->comment('อนุมัติ: ผู้แก้ไข');
            $table->dateTime('audit_approve_at')->nullable()->comment('อนุมัติ: ผู้บันทึก');
            $table->dateTime('audit_updated_at')->nullable()->comment('อนุมัติ: ผู้แก้ไข');

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
        Schema::dropIfExists('section5_application_inspectors_audit');
    }
}

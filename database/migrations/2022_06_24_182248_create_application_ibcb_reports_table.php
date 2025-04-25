<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->date('report_date')->nullable()->comment('วันที่สรุปรายงาน');
            $table->integer('report_by')->nullable()->comment('ผู้จัดทำสรุปรายงาน');
            $table->text('report_description')->nullable()->comment('รายละเอียด');
            $table->tinyInteger('report_approve')->nullable()->comment('สถานะการพิจารณาสรุปรายงาน');
            $table->text('report_approve_description')->nullable()->comment('รายละเอียดการพิจารณาสรุปรายงาน');
            $table->integer('report_approve_by')->nullable()->comment('อนุมัติโดย');
            $table->integer('report_updated_by')->nullable()->comment('แก้ไขการอนุมัติโดย');
            $table->timestamp('report_approve_at')->nullable()->comment('วันที่อนุมัติ');
            $table->timestamp('report_updated_at')->nullable()->comment('วันที่แก้ไขการอนุมัติ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
                $table->foreign('application_id')
                ->references('id')
                ->on('section5_application_ibcb')
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
        Schema::dropIfExists('section5_application_ibcb_reports');
    }
}

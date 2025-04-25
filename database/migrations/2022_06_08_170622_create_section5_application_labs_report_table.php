<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationLabsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_lab_id')->nullable()->comment('ID ตาราง section5_application_labs');
            $table->string('application_no')->nullable()->comment('เลขที่คำขอ');
            $table->date('report_date')->nullable()->comment('วันที่สรุปรายงาน');
            $table->integer('report_by')->nullable()->comment('ผู้จัดทำสรุปรายงาน');
            $table->text('report_description')->nullable()->comment('รายละเอียด');
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
        Schema::dropIfExists('section5_application_labs_report');
    }
}

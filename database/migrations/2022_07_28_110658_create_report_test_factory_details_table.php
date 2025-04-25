<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTestFactoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_report_test_factory_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('test_factory_id')->nullable();

            $table->date('test_date')->nullable()->comment('วันที่เริ่มตรวจสอบ');
            $table->date('test_finish_date')->nullable()->comment('วันที่ทดสอบเสร็จ');
            $table->text('test_result')->nullable()->comment('ผลการตรวจสอบ');
            $table->text('test_defect')->nullable()->comment('ข้อมพบพร่อง');
            $table->text('test_description')->nullable()->comment('รายละเอียดผลการตรวจสอบ');
            $table->text('test_result_file')->nullable()->comment('ไฟล์ผลการตรวจสอบ');
            
            $table->timestamps();

            $table->foreign('test_factory_id')
                    ->references('id')
                    ->on('bsection5_report_test_factory')
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
        Schema::dropIfExists('bsection5_report_test_factory_details');
    }
}

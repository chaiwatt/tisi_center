<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTestFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_report_test_factory', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('ib_code',255)->nullable()->comment('รหัสหน่วยตรวจสอบ');
            $table->string('ib_name',255)->nullable()->comment('ชื่อหน่วยตรวจสอบ');
            $table->string('tis_no')->nullable()->comment('มอก.');

            $table->string('trader_name', 255)->nullable()->comment('ชื่อผู้ประกอบการ');
            $table->string('trader_taxid', 255)->nullable()->comment('เลขผู้ภาษี');

            $table->string('factory_request_no', 255)->nullable()->comment('อ้างอิงเลขที่คำขอโรงงาน');

            $table->text('test_price')->nullable()->comment('ค่าตรวจสอบ');
            $table->date('payment_date')->nullable()->comment('วันที่ชำระเงินค่าตรวจสอบ');

            $table->text('test_result')->nullable()->comment('ผลการตรวจสอบ (ผ่าน/ไม่ผ่านการตรวจโรงงาน)');

            $table->text('ref_report_no')->nullable()->comment('เลขที่รายงานตามระบบ IB ');
            $table->text('test_result_file')->nullable()->comment('ไฟล์ผลทดสอบ IB ');

            $table->text('remark')->nullable()->comment('รายละเอียด');

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
        Schema::dropIfExists('bsection5_report_test_factory');
    }
}

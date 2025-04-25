<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_report_test_product', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('sample_id')->nullable()->comment('ID : ros_rform_sample (elicense)');
            $table->text('sample_bill_no')->nullable()->comment('เลขที่ใบนำส่งตัวอย่าง');
            $table->string('lab_code', 255)->nullable()->comment('รหัสหน่วยทดสอบ');
            $table->string('tis_no')->nullable()->comment('มอก.');
            $table->string('trader_name', 255)->nullable()->comment('ชื่อผู้ประกอบการ');
            $table->string('trader_taxid', 255)->nullable()->comment('เลขผู้ภาษี');
            $table->string('trader_email', 255)->nullable()->comment('รับส่งตัวอย่าง');
            $table->string('sample_from', 255)->nullable()->comment('รับตัวอย่างจาก'); 
            $table->string('department', 255)->nullable()->comment('กอง');
            $table->string('sub_department', 255)->nullable()->comment('กลุ่ม');

            $table->date('receive_date')->nullable()->comment('วันที่รับตัวอย่าง');
            $table->date('test_date')->nullable()->comment('วันที่ทดสอบ');
            $table->date('test_finish_date')->nullable()->comment('วันที่ทดสอบเสร็จ');

            $table->text('test_duration')->nullable()->comment('ระยะเวลาทดสอบ (วัน)');
            $table->text('test_price')->nullable()->comment('ราคาทดสอบ/ชุดทดสอบ');
            $table->text('total_test_price')->nullable()->comment('รวมราคาทั้งหมด');

            $table->date('report_date')->nullable()->comment('วันที่ออกรายงาน');
            $table->date('payment_date')->nullable()->comment('วันที่ชำระเงินค่าตรวจสอบ');

            $table->text('ref_report_no')->nullable()->comment('เลขที่รายงายตามระบบ LAB');
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
        Schema::dropIfExists('bsection5_report_test_product');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabsScopeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_labs_scopes_details', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('lab_id')->nullable();
            $table->string('lab_code', 255)->nullable()->comment('ห้องปฏิบัติการ: รหัสปฏิบัติการ');
            $table->integer('lab_scope_id')->nullable()->comment('ไอดีรายการทดสอบ LAB');
            $table->integer('test_tools_id')->nullable()->comment('ไอดีเครื่องมือทดสอบ');
            $table->string('test_tools_no')->nullable()->comment('รหัส/หมายเลขเครื่องมือทดสอบ');
            $table->text('capacity')->nullable()->comment('ขีดความสามารถ');
            $table->text('range')->nullable()->comment('ช่วงการใช้งาน');
            $table->text('true_value')->nullable()->comment('ความละเอียดที่อ่านได้');
            $table->text('fault_value')->nullable()->comment('ความคลาดเคลื่อนที่ยอมรับ');
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
            $table->string('ref_lab_application_no', 255)->nullable()->comment('อ้างอิงเลขที่คำขอ');
            $table->integer('ref_lab_application_scope_id')->nullable()->comment('ID คำขอ รายการทดสอบ ');

            $table->timestamps();
            $table->foreign('lab_id')
                    ->references('id')
                    ->on('section5_labs')
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
        Schema::dropIfExists('section5_labs_scopes_details');
    }
}

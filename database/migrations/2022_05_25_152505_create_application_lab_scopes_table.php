<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_scope', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_lab_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->string('tis_tisno')->nullable()->comment('เลขมอก.');
            $table->integer('test_item_id')->nullable()->comment('ไอดีรายการทดสอบ');
            $table->integer('test_tools_id')->nullable()->comment('ไอดีเครื่องมือทดสอบ');
            $table->string('test_tools_no')->nullable()->comment('รหัส/หมายเลขเครื่องมือทดสอบ');
            $table->text('capacity')->nullable()->comment('ขีดความสามารถ');
            $table->text('range')->nullable()->comment('ช่วงการใช้งาน');
            $table->text('true_value')->nullable()->comment('ความละเอียดที่อ่านได้');
            $table->text('fault_value')->nullable()->comment('ความคลาดเคลื่อนที่ยอมรับ');
            
            $table->timestamps();
            $table->foreign('application_lab_id')
                    ->references('id')
                    ->on('section5_application_labs')
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
        Schema::dropIfExists('section5_application_labs_scope');
    }
}

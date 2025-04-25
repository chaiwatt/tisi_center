<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsetion5TestItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_test_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tis_id')->nullable()->comment('มอก.');
            $table->string('tis_tisno')->nullable()->comment('เลขมอก.');
            $table->string('title')->nullable()->comment('หัวข้อ/รายการทดสอบ');
            $table->integer('type')->nullable()->comment('ประเภท 1 = หัวข้อทดสอบ, 2 = รายทดสอบ');
            $table->string('no')->nullable()->comment('ข้อ');
            $table->integer('unit_id')->nullable()->comment('หน่วย');
            $table->integer('parent_id')->nullable()->comment('ภายใต้หัวข้อทดสอบ');
            $table->integer('test_method_id')->nullable()->comment('วิธีทดสอบ');
            $table->integer('test_tools_id')->nullable()->comment('เครื่องมือทดสอบ');
            $table->integer('input_result')->nullable()->comment('กรอกผลการทดสอบ 1 = ได้, 2 = ไม่ได้');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = ใช้งาน, 2 = ไม่ใช้งาน');
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
        Schema::dropIfExists('bsection5_test_item');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsection5TestToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_test_tools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('ชื่อเครื่องมือทดสอบ');
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
        Schema::dropIfExists('bsection5_test_tools');
    }
}

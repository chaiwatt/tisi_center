<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBsection5StandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_standards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('เลข มอก.');
            $table->text('description')->nullable()->comment('รายละเอียด');
            $table->text('standard_type')->nullable()->comment('มาตรฐานใช้สำหรับ 1 = IB, 2 = CB');
            $table->boolean('state')->nullable()->comment('สถานะ 1 = ใช้งาน, 2 = ไม่ใช้งาน');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::drop('bsection5_standards');
    }
}

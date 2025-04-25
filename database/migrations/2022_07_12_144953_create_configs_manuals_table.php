<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsManualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_manuals', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->nullable()->comment('ชื่อคู่มือ');
            $table->text('details')->nullable()->comment('รายละเอียด');
            $table->string('site')->nullable()->comment('ไซต์');
            $table->text('file')->nullable()->comment('ไฟล์');
            $table->text('file_url')->nullable()->comment('url ไฟล์');
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
        Schema::dropIfExists('configs_manuals');
    }
}

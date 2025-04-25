<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsFormatCodeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_format_codes_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('format_id')->nullable();
            $table->text('data')->nullable()->comment('json ตาราง configs_format_codes_sub');
            $table->string('verstion')->nullable()->comment('ver. ที่เปลี่ยนแปลง');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();

            $table->foreign('format_id')
            ->references('id')
            ->on('configs_format_codes')
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
        Schema::dropIfExists('configs_format_codes_log');
    }
}

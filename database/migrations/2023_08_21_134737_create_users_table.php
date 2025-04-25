<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ws_request_moi_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source_url', 255)->nullable()->comment('หน้าเว็บที่ร้องขอข้อมูล');
            $table->string('input_number', 255)->nullable()->comment('ข้อมูลที่ต้องการร้องขอ');
            $table->string('destination_url', 255)->nullable()->comment('URL API ปลายทางที่ร้องขอข้อมูล');
            $table->string('destination_type', 255)->nullable()->comment('ประเภทข้อมูลที่ร้องขอ');
            $table->string('client_ip', 255)->nullable()->comment('IP เครื่องผู้ร้องขอ');
            $table->dateTime('request_start')->nullable()->comment('เวลาเริ่ม Request ข้อมูล');
            $table->dateTime('request_end')->nullable()->comment('เวลาสิ้นสุด Request ข้อมูล');
            $table->string('response_http', 255)->nullable()->comment('HTTP Response');
            $table->text('response_error')->nullable()->comment('ข้อมูลข้อผิดพลาด');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_request_moi_log');
    }
}

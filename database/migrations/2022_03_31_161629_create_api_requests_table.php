<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requester')->nullable()->comment('ผู้เรียกใช้งาน API');
            $table->string('api_code',255)->nullable()->comment('รหัส API');
            $table->string('agent_id',255)->nullable()->comment('เลขบัตรประจำตัวประชาชน/เลขนิติบุคคล/เลขทะเบียนโรงงาน');
            $table->text('parameter')->nullable()->comment('ค่าที่ส่งไป');
            $table->text('url')->nullable()->comment('URL ที่เรียกใช้ API');
            $table->string('ip_request',255)->nullable()->comment('IP Address ที่เรียกใช้งาน');
            $table->string('user_agent',255)->nullable()->comment('โปรแกรม Browser ที่ใช้งาน');
            $table->string('request_status',20)->nullable()->comment('สถานะการส่งข้อมูล');
            $table->string('response_status',20)->nullable()->comment('สถานะตอบกลับ');
            $table->string('response_msg',255)->nullable()->comment('ข้อความตอบกลับ');
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
        Schema::dropIfExists('api_requests');
    }
}

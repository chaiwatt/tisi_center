<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('login_log_id')->nullable()->comment('id ตาราง user_login_logs');
            $table->dateTime('last_visit_at')->nullable()->comment('วันเวลาเข้าถึงระบบล่าสุด');
            $table->string('app_name')->nullable()->comment('app_name ของ api ที่ใช้งาน');
            $table->foreign('login_log_id')
                  ->references('id')
                  ->on('user_login_logs')
                  ->onDelete('cascade');
        });
        DB::statement("ALTER TABLE `user_access_logs` comment 'ประวัติการเข้าใช้งานระบบต่างๆ ของเจ้าหน้าที่สมอ.'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_access_logs');
    }
}

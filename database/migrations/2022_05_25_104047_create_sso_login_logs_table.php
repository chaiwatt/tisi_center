<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSsoLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_login_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id')->comment('id ตาราง sso_sessions');
			$table->bigInteger('user_id')->nullable()->comment('id ตาราง sso_users');
			$table->string('ip_address', 45)->nullable()->comment('ไอพีแอดเดรส');
			$table->string('user_agent')->nullable()->comment('โปรแกรมเบราเซอร์ที่ใช้งาน');
			$table->dateTime('login_at')->comment('วันเวลา Login เข้าสู่ระบบ');
            $table->dateTime('logout_at')->nullable()->comment('วันเวลา Logout ออกจากระบบ');
            $table->dateTime('last_visit_at')->nullable()->comment('วันเวลาเข้าถึงระบบล่าสุด');
            $table->integer('act_instead')->nullable()->comment('ดำเนินการแทน user ใด เก็บค่า id ตาราง sso_users ถ้าเป็น null ดำเนินการในฐานะตัวเอง');
            $table->string('channel')->nullable()->comment('ช่องทางการเข้าสู่ระบบ web, api');
            $table->string('app_name')->nullable()->comment('app_name ของ api ที่ Login');
        });
        DB::statement("ALTER TABLE `sso_login_logs` comment 'ประวัติการ Login ของผู้ประกอบการ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_login_logs');
    }
}

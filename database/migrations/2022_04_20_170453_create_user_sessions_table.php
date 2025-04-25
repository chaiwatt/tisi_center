<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->string('id')->primary()->comment('session id เป็น string');
			$table->bigInteger('user_id')->nullable()->comment('runrecno ตาราง user_register');
			$table->string('ip_address', 45)->nullable()->comment('ไอพีแอดเดรส');
			$table->string('user_agent')->nullable()->comment('โปรแกรมเบราเซอร์ที่ใช้งาน');
			$table->dateTime('login_at')->comment('เวลา login ที่ใช้งานล่าสุด');
        });
        DB::statement("ALTER TABLE `user_sessions` comment 'ตารางเก็บ session ของเจ้าหน้าที่สมอ.'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sessions');
    }
}

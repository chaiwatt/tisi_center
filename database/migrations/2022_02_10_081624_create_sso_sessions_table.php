<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSsoSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sso_sessions', function(Blueprint $table)
		{
			$table->string('id')->primary()->comment('session id เป็น string');
			$table->bigInteger('user_id')->nullable()->comment('ไอพี ตาราง ros_users');
			$table->string('ip_address', 45)->nullable()->comment('ไอพีแอดเดรส');
			$table->string('user_agent')->nullable()->comment('โปรแกรมเบราเซอร์ที่ใช้งาน');
			$table->string('payload')->comment('ข้อความที่เก็บ');
			$table->dateTime('login_at')->comment('เวลา login ที่ใช้งานล่าสุด');
			$table->integer('status')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sso_sessions');
	}

}

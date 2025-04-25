<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingSystemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_systems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('details', 65535)->nullable()->comment('รายละเอียด');
			$table->string('urls')->nullable()->comment('link ระบบ');
			$table->string('icons')->nullable()->comment('icons');
			$table->string('colors')->nullable()->comment('colors');
			$table->integer('state')->nullable()->comment('1. ใช้งาน');
			$table->integer('created_by');
			$table->timestamps();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ros_setting_systems');
	}

}

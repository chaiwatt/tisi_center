<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasicPrefixTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('basic_prefix', function(Blueprint $table)
		{
			$table->smallInteger('id', true)->unsigned()->comment('รหัสประจำตาราง');
			$table->string('code', 3)->comment('รหัสคำนำหน้าชื่อ');
			$table->string('title')->nullable()->comment('ชื่อ');
			$table->string('title_en')->comment('คำนำหน้าชื่ออังกฤษ');
			$table->string('initial', 10)->comment('อักษรย่อ');
			$table->smallInteger('ordering')->unsigned()->nullable()->comment('การเรียงลำดับ');
			$table->boolean('state')->nullable()->comment('สถานะข้อมูล');
			$table->dateTime('checked_out_time')->nullable()->comment('เวลาที่เข้าใช้งาน');
			$table->integer('checked_out')->unsigned()->nullable()->default(0)->comment('รหัสผู้ใช้งานที่กำลังใช้งานอยู่');
			$table->timestamp('created')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('เวลาที่สร้างข้อมูล');
			$table->integer('created_by')->unsigned()->nullable()->comment('รหัสผู้สร้างข้อมูล');
			$table->dateTime('modified')->nullable()->comment('รหัสผู้แก้ไขข้อมูล');
			$table->integer('modified_by')->unsigned()->nullable()->comment('เวลาที่แก้ไขข้อมูลล่าสุด');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ros_rbasicdata_prefix');
	}

}

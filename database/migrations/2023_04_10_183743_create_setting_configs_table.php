<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_setting_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('grop_type')->comment('lap, ib, cb');
            $table->integer('from_filed')->comment('1.วันที่ออกใบรับรอง, 2.วันที่เริ่มต้นในขอบข่าย 3.วันที่ตรวจครั้งล่าสุด');
            $table->string('warning_day')->nullable()->comment('แจ้งเตือนล้วงหน้า');
            $table->string('condition_check')->nullable()->comment('เงื่อนไขการตรวจติดตาม');
            $table->boolean('check_first')->nullable()->comment('1=ตรวจติดตามครั้งแรก 6 เดือน');
            $table->integer('created_by')->nullable()->comment('ผู้สร้าง runrecno ตาราง user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข runrecno ตาราง user_register');
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
        Schema::dropIfExists('bcertify_setting_config');
    }
}

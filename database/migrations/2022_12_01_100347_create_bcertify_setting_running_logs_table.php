<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifySettingRunningLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_setting_running_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('format_id')->nullable();
            $table->text('data')->nullable()->comment('json ตาราง bcertify_setting_running_subs');
            $table->string('version')->nullable()->comment('ver. ที่เปลี่ยนแปลง');
            $table->dateTime('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->dateTime('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 0 = Not Active');
            $table->string('system')->nullable()->comment('ระบบงาน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('format_id')
            ->references('id')
            ->on('bcertify_setting_runnings')
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
        Schema::dropIfExists('bcertify_setting_running_logs');
    }
}

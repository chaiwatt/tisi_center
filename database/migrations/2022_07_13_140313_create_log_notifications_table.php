<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title')->nullable()->comment('ชื่อเรื่อง');
            $table->text('details')->nullable()->comment('รายละเอียด');
            $table->string('ref_applition_no',255)->nullable()->comment('เลขอ้างอิง');
            $table->text('ref_table')->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('id ตาราง');
            $table->integer('status')->nullable();
            $table->string('site')->nullable()->comment('ไซต์');
            $table->text('root_site')->nullable()->comment('url ไซต์');
            $table->text('url')->nullable()->comment('url ระบบ');
            $table->integer('read')->nullable()->comment('1 อ่านแล้ว');
            $table->integer('users_id')->nullable()->comment('คนรับแจ้งเตือน');
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
        Schema::dropIfExists('logs_notifications');
    }
}

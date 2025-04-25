<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsSendMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_send_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title','255')->nullable()->comment('หัวเรื่อง');
            $table->string('subject', '255')->nullable()->comment('ชื่อเรื่อง');
            $table->string('learn', '255')->nullable()->comment('เรียน');
            $table->string('email')->nullable()->comment('อีเมลปลายทาง');
            $table->text('content')->nullable()->comment('เนื้อหาในอีเมล');
            $table->text('url_send')->nullable()->comment('url ระบบที่ส่งแจ้งเตือน');
            $table->string('tb_ref', '255')->nullable()->comment('ตารางที่บันทึกมา');
            $table->integer('id_ref')->nullable()->comment('ไอดีที่บันทึกมา');
            $table->string('system_code')->nullable()->comment('หมวดระบบที่บันทึก');
            $table->string('site_code')->nullable()->comment('ไชต์ที่บันทึกมา');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('logs_send_mails');
    }
}

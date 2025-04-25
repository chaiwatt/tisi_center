<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationLabsAcceptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_accept', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_lab_id')->nullable()->comment('ID ตาราง section5_application_labs');
            $table->string('application_no')->nullable()->comment('เลขที่คำขอ');
            $table->integer('application_status')->nullable()->comment('สถานะคำขอ');
            $table->text('description')->nullable()->comment('รายละเอียด');
            $table->text('appointment_date')->nullable()->comment('วันที่นัดเข้าตรวจประเมิน');
            $table->integer('send_mail_status')->nullable()->comment('สถานะแจ้งเตือนอีเมล');
            $table->string('noti_email')->nullable()->comment('อีเมลที่แจ้งเตือน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
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
        Schema::dropIfExists('section5_application_labs_accept');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationIbcbAcceptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_accept', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->nullable()->comment('ไอดีคำขอ');
            $table->string('application_no',30)->nullable()->comment('เลขที่คำขอ');
            $table->integer('application_status')->nullable()->comment('สถานะคำขอ');
            $table->text('description')->nullable()->comment('รายละเอียด');
            $table->text('appointment_date')->nullable()->comment('วันที่นัดเข้าตรวจประเมิน ///เก็บข้อมูลเป็นชุดๆ');
            $table->integer('send_mail_status')->nullable()->comment('สถานะแจ้งเตือนอีเมล');
            $table->text('noti_email')->nullable()->comment('อีเมลที่แจ้งผล');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('section5_application_ibcb_accept');
    }
}

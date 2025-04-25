<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_audits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');

            $table->text('audit_date')->nullable()->comment('วันที่ตรวจประเมิน');
            $table->tinyInteger('audit_result')->nullable()->comment('ผลตรวจประเมิน 1 = ผ่าน, 2 = ไม่ผ่าน');
            $table->text('audit_remark')->nullable()->comment('หมายเหตุ');
            $table->tinyInteger('send_mail_status')->nullable()->comment('สถานะอีเมลแจ้งผล 1 = ไม่ส่งอีเมลแจ้งผล, 2 = ส่งอีเมลแจ้งผล');
            $table->text('noti_email')->nullable()->comment('อีเมลที่แจ้งผล');

            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('application_id')
                    ->references('id')
                    ->on('section5_application_ibcb')
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
        Schema::dropIfExists('section5_application_ibcb_audits');
    }
}

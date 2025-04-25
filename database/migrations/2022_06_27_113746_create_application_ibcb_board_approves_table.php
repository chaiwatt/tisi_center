<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbBoardApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_board_approves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');

            $table->date('board_meeting_date')->nullable()->comment('วันที่ประชุมคณะอนุกรรมการ');
            $table->integer('board_meeting_result')->nullable()->comment('มติคณะอนุกรรมการ 1 = ผ่าน 2 = ไม่ผ่าน');
            $table->text('board_meeting_description')->nullable()->comment('รายละเอียด/หมายเหตุ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');

            $table->date('government_gazette_date')->nullable()->comment('วันที่ประกาศราชกิจจา');
            $table->date('start_date')->nullable()->comment('วันที่มีผลเป็นหน่วยตรวจสอบ');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดเป็นหน่วยตรวจสอบ');
            $table->text('government_gazette_description')->nullable()->comment('รายละเอียด/หมายเหตุ');

            $table->integer('government_gazette_created_by')->nullable()->comment('ผู้บันทึกประกาศราชกิจจา');
            $table->integer('government_gazette_updated_by')->nullable()->comment('ผู้แก้ไขประกาศราชกิจจา');
            $table->dateTime('government_gazette_created_at')->nullable()->comment('วันที่สิ้นสุดเป็นหน่วยตรวจสอบ');
            $table->dateTime('government_gazette_updated_at')->nullable()->comment('วันที่สิ้นสุดเป็นหน่วยตรวจสอบ');

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
        Schema::dropIfExists('section5_application_ibcb_board_approves');
    }
}

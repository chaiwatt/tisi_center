<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabBoardApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_board_approves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->date('board_meeting_date')->nullable()->comment('วันที่ประชุมคณะอนุกรรมการ');
            $table->integer('board_meeting_result')->nullable()->comment('มติคณะอนุกรรมการ 1 = ผ่าน 2 = ไม่ผ่าน');
            $table->text('board_meeting_description')->nullable()->comment('รายละเอียด/หมายเหตุ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('app_id')
                    ->references('id')
                    ->on('section5_application_labs')
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
        Schema::dropIfExists('section5_application_labs_board_approves');
    }
}

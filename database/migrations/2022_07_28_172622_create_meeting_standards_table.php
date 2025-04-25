<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMeetingStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_meeting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable()->comment('หัวข้อการประชุม');
            $table->integer('meeting_type_id')->nullable()->comment('ID bcertify_meetingtype');
            $table->date('start_date')->nullable()->comment('วันที่นัดหมาย');
            $table->time('start_time')->nullable()->comment('เวลาที่นัดหมาย');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดนัดหมาย');
            $table->time('end_time')->nullable()->comment('เวลาที่สิ้นสุดนัดหมาย');
            $table->string('meeting_place', 255)->nullable()->comment('สถานที่นัดหมาย');
            $table->text('meeting_detail')->nullable()->comment('รายละเอียดวาระการประชุม');
            $table->text('attach')->nullable()->comment('เอกสารการประชุม');
            $table->integer('status_id')->nullable()->comment('สถานะการกำหนดมาตรฐาน 1.นัดหมายประชุม 2.บันทึกผลการประชุม 3.ยกเลิกนัดหมาย');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('certify_setstandard_meeting');
    }
}

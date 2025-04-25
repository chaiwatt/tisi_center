<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingAssessmentBugTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_assessment_bug', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assessment_id')->nullable()->comment('TB :app_certi_tracking_assessment');
            $table->text('report')->nullable()->comment('รายงานที่');
            $table->text('remark')->nullable()->comment('รายละเอียด ข้อบกพร่อง/ข้อสังเกต');
            $table->string('no',255)->nullable()->comment('มอก.');  
            $table->integer('type')->nullable()->comment('1.ข้อบกพร่อง 2.ข้อสังเกต');
            $table->integer('status')->nullable()->comment('สถานะแนวทางการแก้ไข 1.ผ่าน 2.ไม่ผ่าน');
            $table->text('details')->nullable()->comment('แนวทางการแก้ไข');
            $table->text('comment')->nullable()->comment('ข้อคิดเห็นของคณะผู้ตรวจประเมิน');
            $table->integer('file_status')->nullable()->comment('สถานะไฟล์แนบ 1.ผ่าน 2.ไม่ผ่าน');
            $table->text('file_comment')->nullable()->comment('หมายเหตุเจ้าหน้าที่ไม่รับหลักฐาน');
            $table->integer('reporter_id')->nullable()->comment('ผู้พบ');
            $table->timestamps();
            
            $table->foreign('assessment_id')
                ->references('id')
                ->on('app_certi_tracking_assessment')
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
        Schema::dropIfExists('app_certi_tracking_assessment_bug');
    }
}

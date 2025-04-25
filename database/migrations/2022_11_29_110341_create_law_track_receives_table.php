<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_track_receives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no', 255)->nullable()->comment('เลขที่อ้างอิง');
            $table->string('book_number', 255)->nullable()->comment('เลขที่หนังสือ');
            $table->string('receive_number', 255)->nullable()->comment('เลขรับ');
            $table->date('receive_date')->nullable()->comment('วันที่รับ');
            $table->time('receive_time')->nullable()->comment('เวลา');
            $table->integer('law_bs_deperment_id')->nullable()->comment('หน่วยงานเจ้าของเรื่อง');
            $table->integer('law_bs_job_type_id')->nullable()->comment('ประเภทงาน');
            $table->text('subject')->nullable()->comment('ชื่อเรื่อง');
            $table->text('description')->nullable()->comment('คำอธิบาย (ถ้ามี)');
            $table->integer('status')->nullable()->comment('สถานะ');
            $table->date('close_date')->nullable()->comment('วันที่ปิดงาน');
            $table->integer('close_by')->nullable()->comment('ผู้ปิดงาน runrecno ตาราง user_register');
            $table->integer('created_by')->nullable()->comment('ผู้สร้าง runrecno ตาราง user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข runrecno ตาราง user_register');
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
        Schema::dropIfExists('law_track_receives');
    }
}

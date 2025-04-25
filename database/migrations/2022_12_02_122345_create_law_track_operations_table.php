<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawTrackOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_track_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_track_receives_id')->nullable();
            $table->date('operation_date')->nullable()->comment('วันที่ดำเนินการ');
            $table->date('due_date')->nullable()->comment('วันที่ครบกำหนด');
            $table->integer('status_job_track_id')->nullable()->comment('ID : law_basic_status_operate');
            $table->text('detail')->nullable()->comment('รายละเอียดการดำเนินงาน');
            $table->integer('created_by')->nullable()->comment('ผู้สร้าง runrecno ตาราง user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข runrecno ตาราง user_register');
            $table->timestamps();
            $table->foreign('law_track_receives_id')
                    ->references('id')
                    ->on('law_track_receives')
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
        Schema::dropIfExists('law_track_operations');
    }
}

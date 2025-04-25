<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardDraftPlanLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_draft_plan_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id')->nullable()->comment('id ตาราง tisi_estandard_draft_plan');
            $table->string('reverse_user', 255)->nullable()->comment('ชื่อ ผก. ที่ตีกลับแก้ไขแผน');
            $table->text('reverse_detail')->nullable()->comment('รายละเอียดการตีกลับแก้ไขแผน');
            $table->date('reverse_date')->nullable()->comment('วันที่ตีกลับแก้ไขแผน');
            $table->string('update_user', 255)->nullable()->comment('คนที่แก้ไขแผน');
            $table->text('update_detail')->nullable()->comment('รายละเอียดแก้ไขแผน');
            $table->date('update_date')->nullable()->comment('วันที่แก้ไขแผน');
            $table->string('update_status', 255)->nullable()->comment('สถานะ');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `tisi_estandard_draft_plan_logs` comment 'ตารางเก็บ logs การแก้ไขแผน'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tisi_estandard_draft_plan_logs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterExpertsHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_experts_historys', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('expert_id')->nullable()->comment('id ตาราง register_experts');
            $table->date('operation_at')->nullable()->comment('วันที่ดำเนินการ');
            $table->integer('department_id')->nullable()->comment('id ตาราง basic_appoint_departments หน่วยงาน');
            $table->string('committee_no',255)->nullable()->comment('คำสั่งที่');
            $table->integer('expert_group_id')->nullable()->comment('id ตาราง basic_expert_groups ความเชี่ยวชาญด้าน');
            $table->integer('position_id')->nullable()->comment('id ตาราง basic_board_types ตำแหน่ง');
            $table->foreign('expert_id')
                  ->references('id')
                  ->on('register_experts')
                  ->onDelete('SET NULL')
                  ->onUpdate('SET NULL');
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
        Schema::dropIfExists('register_experts_historys');
    }
}

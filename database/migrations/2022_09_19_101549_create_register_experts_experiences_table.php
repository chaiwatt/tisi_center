<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterExpertsExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_experts_experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('expert_id')->nullable()->comment('id ตาราง register_experts');
            $table->string('years',4)->nullable()->comment('ปี');
            $table->integer('department_id')->nullable()->comment('id ตาราง basic_appoint_departments หน่วยงาน');
            $table->string('position',255)->nullable()->comment('ตำแหน่ง');
            $table->string('role',255)->nullable()->comment('บทบาทหน้าท');
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
        Schema::dropIfExists('register_experts_experiences');
    }
}

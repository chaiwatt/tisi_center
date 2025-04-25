<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterExpertEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_expert_education', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('expert_id')->comment('TB : register_experts . id');
            $table->string('year',4)->nullable()->comment('ปีที่สำเร็จ');
            $table->string('education_id',11)->nullable()->comment('วุฒิการศึกษา');
            $table->string('academy',255)->nullable()->comment('สถานศึกษา');
            $table->text('faculty')->nullable()->comment('คณะ/สาขา');
            $table->foreign('expert_id')
                  ->references('id')
                  ->on('register_experts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
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
        Schema::dropIfExists('register_expert_education');
    }
}

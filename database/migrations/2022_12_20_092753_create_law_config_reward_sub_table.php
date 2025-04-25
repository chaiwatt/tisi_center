<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawConfigRewardSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_reward_sub', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_config_reward_id')->nullable()->comment('ID : law_config_reward');
            $table->integer('reward_group_id')->nullable()->comment('ID ตาราง law_basic_reward_group');
            $table->decimal('amount',15,2)->nullable()->comment('จำนวนเงิน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');

            $table->foreign('law_config_reward_id')
                    ->references('id')
                    ->on('law_config_reward')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('law_config_reward_sub');
    }
}

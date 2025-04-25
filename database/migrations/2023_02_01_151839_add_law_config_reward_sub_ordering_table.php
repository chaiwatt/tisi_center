<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawConfigRewardSubOrderingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_config_reward_sub', function (Blueprint $table) {
            $table->smallInteger('ordering')->unsigned()->nullable()->comment('การเรียงลำดับ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_config_reward_sub', function (Blueprint $table) {
            $table->dropColumn(['ordering']);
        });
    }
}

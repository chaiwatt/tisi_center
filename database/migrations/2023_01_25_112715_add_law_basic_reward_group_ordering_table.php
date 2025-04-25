<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawBasicRewardGroupOrderingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_reward_group', function (Blueprint $table) {
            $table->smallInteger('ordering')->unsigned()->nullable()->comment('การเรียงลำดับ')->after('title');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_reward_group', function (Blueprint $table) {
            $table->dropColumn(['ordering']);
        });
    }
}

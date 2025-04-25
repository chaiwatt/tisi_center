<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardsEditRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_rewards', function (Blueprint $table) {
            $table->enum('edit_reward', array('0','1'))->default('0')->comment('แก้ไขสัดส่วนเงินคำนวณ(ส่วนที่ 3 : คำนวณสัดส่วนเงินรางวัล) 1.แก้ไข, 2.ไม่แก้ไข')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_rewards', function (Blueprint $table) {
            $table->dropColumn(['edit_reward']);
        });
    }
}

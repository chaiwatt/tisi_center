<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardWithdrawsDetailsSubLawRewardStaffListsIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_reward_withdraws_details_sub', function (Blueprint $table) {
            $table->integer('law_reward_staff_lists_id')->nullable()->comment('รายชื่อผู้มีสิทธิ์ได้รับเงิน ID : law_reward_staff_lists')->after('law_reward_recepts_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_reward_withdraws_details_sub', function (Blueprint $table) {
            $table->dropColumn(['law_reward_staff_lists_id']);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardStaffListsBankAccountNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_reward_staff_lists', function (Blueprint $table) {
            $table->renameColumn('bank_accoun_number', 'bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_reward_staff_lists', function (Blueprint $table) {
            $table->renameColumn('bank_account_number', 'bank_accoun_number');
        });
    }
}

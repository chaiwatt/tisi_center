<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardWithdrawsFilterPaidDateMonthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_reward_withdraws', function (Blueprint $table) {
            $table->string('filter_paid_date_month',2)->nullable()->comment('เบิกค่าใช้จ่ายในคดี : เดือน')->after('filter_case_number');
            $table->string('filter_paid_date_year',5)->nullable()->comment('เบิกค่าใช้จ่ายในคดี : เดือน')->after('filter_paid_date_month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_reward_withdraws', function (Blueprint $table) {
            $table->dropColumn(['filter_paid_date_month','filter_paid_date_year']);
        });
    }
}

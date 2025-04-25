<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardCalculation3DivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_reward_calculation_3', function (Blueprint $table) {
            $table->string('division',5)->nullable()->comment('คำนวณเงิน : สัดส่วน(%)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_reward_calculation_3', function (Blueprint $table) {
            $table->string('division',5)->nullable()->comment('คำนวณเงิน : สัดส่วน(%)')->change();
        });
    }
}

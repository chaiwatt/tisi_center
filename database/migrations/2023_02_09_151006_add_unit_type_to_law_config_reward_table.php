<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitTypeToLawConfigRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_config_reward', function (Blueprint $table) {
            $table->integer('unit_type')->nullable()->comment('สัดส่วนคิดเป็น 1 = ร้อยล่ะ (%) 2 = จำนวนเงิน')->after('operation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_config_reward', function (Blueprint $table) {
            $table->dropColumn([ 'unit_type' ]);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConditionFormscaseToLawBasicRewardGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_reward_group', function (Blueprint $table) {
            $table->boolean('condition_formscase')->nullable()->comment('สำหรับดึงไปแสดงที่ระบบแจ้งงานคดี')->after('ordering');
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
            $table->dropColumn(['condition_formscase']);
        });
    }
}

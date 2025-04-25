<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEsurvFollowUpsTradenameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_follow_ups', function (Blueprint $table) {
            $table->string('tradename',255)->nullable()->after('trader_autonumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esurv_follow_ups', function (Blueprint $table) {
            $table->dropColumn(['tradename']);
        });
    }
}

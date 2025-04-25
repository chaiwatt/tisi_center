<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubIdToEsurvFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_follow_ups', function (Blueprint $table) {
            $table->string('sub_id', 5)->nullable()->comment('sub_id ตาราง sub_department');
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
            $table->dropColumn(['sub_id']);  
        });
    }
}

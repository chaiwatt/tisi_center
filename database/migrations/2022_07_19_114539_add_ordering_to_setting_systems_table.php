<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderingToSettingSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->integer('ordering')->default(0)->comment('การเรียงลำดับ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->dropColumn(['ordering']);
        });
    }
}

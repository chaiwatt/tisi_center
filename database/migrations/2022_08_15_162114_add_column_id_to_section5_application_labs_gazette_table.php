<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIdToSection5ApplicationLabsGazetteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_gazette', function (Blueprint $table) {
            $table->increments('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_gazette', function (Blueprint $table) {
            $table->dropColumn(['id']);
        });
    }
}

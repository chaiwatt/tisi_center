<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestResultToSaveExampleMapLapDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('save_example_map_lap_detail', function (Blueprint $table) {
            $table->text('test_result')->nullable()->comment('สรุปผลทดสอบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('save_example_map_lap_detail', function (Blueprint $table) {
            $table->dropColumn(['test_result']);
        });
    }
}

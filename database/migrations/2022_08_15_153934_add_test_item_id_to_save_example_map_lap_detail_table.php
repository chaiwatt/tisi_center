<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestItemIdToSaveExampleMapLapDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('save_example_map_lap_detail', function (Blueprint $table) {
            $table->integer('test_item_id')->nullable()->after('id')->comment('id ตาราง bsection5_test_item');
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
            $table->dropColumn(['test_item_id']);
        });
    }
}

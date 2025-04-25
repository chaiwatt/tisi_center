<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToAmphurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amphur', function (Blueprint $table) {
            $table->index(['AMPHUR_ID', 'AMPHUR_CODE', 'AMPHUR_NAME']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amphur', function (Blueprint $table) {
            $table->dropIndex(['AMPHUR_ID', 'AMPHUR_CODE', 'AMPHUR_NAME']);
        });
    }
}

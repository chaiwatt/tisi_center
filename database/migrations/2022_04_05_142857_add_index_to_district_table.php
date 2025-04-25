<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('district', function (Blueprint $table) {
            $table->index(['DISTRICT_ID', 'DISTRICT_CODE', 'DISTRICT_NAME']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('district', function (Blueprint $table) {
            $table->dropIndex(['DISTRICT_ID', 'DISTRICT_CODE', 'DISTRICT_NAME']);
        });
    }
}

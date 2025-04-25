<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToProvinceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('province', function (Blueprint $table) {
            $table->index(['PROVINCE_ID', 'PROVINCE_CODE', 'PROVINCE_NAME']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('province', function (Blueprint $table) {
            $table->dropIndex(['PROVINCE_ID', 'PROVINCE_CODE', 'PROVINCE_NAME']);
        });
    }
}

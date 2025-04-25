<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToBasicZipcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_zipcode', function (Blueprint $table) {
            $table->index(['district_code', 'zipcode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_zipcode', function (Blueprint $table) {
            $table->dropIndex(['district_code', 'zipcode']);
        });
    }
}

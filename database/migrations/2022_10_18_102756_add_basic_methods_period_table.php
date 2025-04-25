<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasicMethodsPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_methods', function (Blueprint $table) {
            $table->integer('period')->nullable()->comment('ระยะเวลา');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_methods', function (Blueprint $table) {
            $table->dropColumn(['period']);  
        });
    }
}

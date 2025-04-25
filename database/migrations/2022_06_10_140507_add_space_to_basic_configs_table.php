<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpaceToBasicConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_configs', function (Blueprint $table) {
            $table->string('data', 512)->nullable()->comment('ค่าที่เก็บ')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_configs', function (Blueprint $table) {
            $table->string('data', 255)->nullable()->comment('ค่าที่เก็บ')->change();
        });
    }
}

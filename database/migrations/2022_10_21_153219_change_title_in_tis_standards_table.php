<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTitleInTisStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tis_standards', function (Blueprint $table) {
            $table->string('title', 500)->nullable()->change();
            $table->string('title_en', 500)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tis_standards', function (Blueprint $table) {
            $table->string('title', 191)->nullable()->change();
            $table->string('title_en', 191)->nullable()->change();
        });
    }
}

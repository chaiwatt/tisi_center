<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCriteriaToBsection5TestItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->text('criteria')->nullable()->comment('เกณฑ์กำหนด')->after('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->dropColumn([ 'criteria']);
        });
    }
}

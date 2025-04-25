<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisTisnoToTisStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tis_standards', function (Blueprint $table) {
            $table->string('tis_tisno')->nullable()->after('gaz_space')->comment('เลขที่ มอก.');
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
            $table->dropColumn('tis_tisno');
        });
    }
}

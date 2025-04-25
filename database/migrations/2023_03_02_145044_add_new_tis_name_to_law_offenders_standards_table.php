<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewTisNameToLawOffendersStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_offenders_standards', function (Blueprint $table) {
            $table->text('tis_name')->nullable()->comment('ชื่อ มอก');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_offenders_standards', function (Blueprint $table) {
            $table->dropColumn([ 'tis_name' ]);
        });
    }
}

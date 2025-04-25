<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnOffendCasesIdToLawOffendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_offenders_licenses', function (Blueprint $table) {
            $table->integer('law_offenders_cases_id')->nullable()->comment('ID : law_offenders_cases');
        });

        Schema::table('law_offenders_products', function (Blueprint $table) {
            $table->integer('law_offenders_cases_id')->nullable()->comment('ID : law_offenders_cases');
        });

        Schema::table('law_offenders_standards', function (Blueprint $table) {
            $table->integer('law_offenders_cases_id')->nullable()->comment('ID : law_offenders_cases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_offenders_licenses', function (Blueprint $table) {
            $table->dropColumn([ 'law_offenders_cases_id' ]);
        });

        Schema::table('law_offenders_products', function (Blueprint $table) {
            $table->dropColumn([ 'law_offenders_cases_id' ]);
        });

        Schema::table('law_offenders_standards', function (Blueprint $table) {
            $table->dropColumn([ 'law_offenders_cases_id' ]);
        });
    }
}

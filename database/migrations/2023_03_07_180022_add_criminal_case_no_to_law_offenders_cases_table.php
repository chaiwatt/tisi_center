<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCriminalCaseNoToLawOffendersCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_offenders_cases', function (Blueprint $table) {
            $table->text('criminal_case_no')->nullable()->comment('กลุ่ม/คดีอาญาที่');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_offenders_cases', function (Blueprint $table) {
            $table->dropColumn([ 'criminal_case_no' ]);
        });
    }
}

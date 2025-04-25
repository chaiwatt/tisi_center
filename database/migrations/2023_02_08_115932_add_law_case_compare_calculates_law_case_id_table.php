<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseCompareCalculatesLawCaseIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_compare_calculates', function (Blueprint $table) {
            $table->dropForeign('law_case_compare_calculates_law_case_compare_id_foreign');
            $table->dropColumn([ 'law_case_compare_id' ]);
            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
        });
    }

    /**
     * Reverse the migrations.
     *  
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_compare_calculates', function (Blueprint $table) {
            $table->dropColumn([ 'law_cases_id' ]);
            $table->unsignedInteger('law_case_compare_id')->nullable()->comment('ID ตาราง law_case_compare');
            $table->foreign('law_case_compare_id')
                    ->references('id')
                    ->on('law_case_compare')
                    ->onDelete('cascade');

        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseCompareCalculatesMistakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_compare_calculates', function (Blueprint $table) {
            $table->integer('mistake')->nullable()->comment('ความผิดครั้งที่')->after('law_case_result_section_id');
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
            $table->dropColumn('mistake');
        });
    }
}

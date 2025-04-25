<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLawCaseOperationToLawCaseOperationDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_operations', function (Blueprint $table) {
            Schema::rename('law_case_operations', 'law_case_operation_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_operation_detail', function (Blueprint $table) {
            Schema::rename('law_case_operation_detail', 'law_case_operations');
        });
    }
}

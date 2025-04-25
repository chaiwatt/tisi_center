<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnToLawCasesProductResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases_product_results', function (Blueprint $table) {
            $table->dropColumn(['law_case_impound_id','law_case_impound_products_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases_product_results', function (Blueprint $table) {
            $table->integer('law_case_impound_id')->nullable()->comment('ID ตาราง law_case_impounds');
            $table->integer('law_case_impound_products_id')->nullable()->comment('ID ตาราง law_case_impound_products');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSetNullColumnToLawCasesProductResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases_product_results', function (Blueprint $table) {
            $table->text('result_amount')->nullable()->comment('ภายในจำนวนวัน')->change();
            $table->text('result_remark')->nullable()->comment('หมายเหตุ')->change();
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
            $table->text('result_amount')->comment('ภายในจำนวนวัน')->change();
            $table->text('result_remark')->comment('หมายเหตุ')->change();
        });
    }
}

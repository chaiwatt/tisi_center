<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTotalValueColumnTypeToDecimalLawCaseImpoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_impounds', function (Blueprint $table) {
            $table->decimal('total_value',13,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_impounds', function (Blueprint $table) {
            $table->mediumInteger('total_value')->nullable()->change();
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseImpoundProductsTotalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_impound_products', function (Blueprint $table) {
            $table->string('total')->nullable()->comment('จำนวนของทั้งหมด')->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_impound_products', function (Blueprint $table) {
            $table->dropColumn(['total']);
        });
    }
}

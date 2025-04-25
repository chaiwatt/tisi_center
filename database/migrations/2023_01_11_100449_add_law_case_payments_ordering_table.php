<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasePaymentsOrderingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
            $table->smallInteger('ordering')->unsigned()->nullable()->comment('ลำดับการสร้าง pay-in')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void 
     */
    public function down()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
            $table->dropColumn(['ordering']);
        });
    }
}

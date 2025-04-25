<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatePauseAmountToLawCaseLicenseResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_license_result', function (Blueprint $table) {
                        
            $table->integer('date_pause_amount')->nullable()->comment('จำนวนวันที่พักใช้');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_license_result', function (Blueprint $table) {
            $table->dropColumn([
                'date_pause_amount'
            ]);
        });
    }
}

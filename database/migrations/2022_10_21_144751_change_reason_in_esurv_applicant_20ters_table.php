<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeReasonInEsurvApplicant20tersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_applicant_20ters', function (Blueprint $table) {
            $table->string('reason', 1000)->nullable()->change();
            $table->string('country_export', 800)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esurv_applicant_20ters', function (Blueprint $table) {
            $table->string('reason', 191)->nullable()->change();
            $table->string('country_export', 191)->nullable()->change();
        });
    }
}

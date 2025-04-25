<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiLabCostAssessmentsStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_lab_cost_assessments', function (Blueprint $table) {
            $table->integer('state')->nullable()->comment('1.แจ้งเตือน ผปก. , 2.แจ้งแตือน จนท.  ,3.ยืนยันการชำระ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_lab_cost_assessments', function (Blueprint $table) {
            $table->dropColumn(['state']);
        });
    }
}

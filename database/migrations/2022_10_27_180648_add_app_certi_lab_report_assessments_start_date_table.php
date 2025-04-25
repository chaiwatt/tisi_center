<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiLabReportAssessmentsStartDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_lab_report_assessments', function (Blueprint $table) {
            $table->renameColumn('start_date', 'confirm_date');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_lab_report_assessments', function (Blueprint $table) {
            $table->renameColumn('confirm_date', 'start_date');
           
        });
    }
}

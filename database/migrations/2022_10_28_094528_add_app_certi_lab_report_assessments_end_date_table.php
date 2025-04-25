<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiLabReportAssessmentsEndDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_lab_report_assessments', function (Blueprint $table) {
            $table->date('start_date')->nullable()->comment('วันที่เริ่มออกให้ตั้งแต่วันที่')->after('confirm_date');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุดออกให้ตั้งแต่วันที่')->after('start_date');
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
             $table->dropColumn(['start_date','end_date']);
        });
    }
}

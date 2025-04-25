<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDateToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->date('offend_report_date')->nullable()->comment('วันที่แจ้งเรื่อง');
            $table->date('offend_accept_date')->nullable()->comment('วันที่ลงรับเรื่อง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->dropColumn([
                'offend_report_date','offend_accept_date'
            ]);
        });
    }
}

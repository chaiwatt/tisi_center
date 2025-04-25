<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignAtToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->date('assign_at')->nullable()->comment('วันที่มอบหมาย')->after('assign_by');
            $table->date('lawyer_at')->nullable()->comment('วันที่ได้รับมอบหมาย')->after('lawyer_by');
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
            $table->dropColumn(['assign_at','lawyer_at']);
        });
    }
}

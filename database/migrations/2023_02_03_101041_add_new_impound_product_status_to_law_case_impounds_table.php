<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewImpoundProductStatusToLawCaseImpoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            
            $table->integer('offend_impound_type')->nullable()->comment('มีผลิตภัณฑ์ยึด-อายัด หรือไม่ 1=มี 2=ไม่มี');
            
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
                'offend_impound_type'
            ]);
        });
    }
}

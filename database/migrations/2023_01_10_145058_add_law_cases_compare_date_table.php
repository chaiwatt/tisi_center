<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasesCompareDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->date('compare_date')->nullable()->comment('บันทึกผลยินยอมเปรียบเทียบปรับ : วันที่ยินยอมปรับ')->after('compare_type');
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
            $table->dropColumn(['compare_date']);
        });
    }
}

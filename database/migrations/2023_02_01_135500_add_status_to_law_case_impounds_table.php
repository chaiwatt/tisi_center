<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToLawCaseImpoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_impounds', function (Blueprint $table) {
            $table->integer('status')->nullable()->comment('สถานะ : ดำเนินการผลิตภัณฑ์')->after('total_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_impounds', function (Blueprint $table) {
            $table->dropColumn([
                'status'
            ]);
        });
    }
}

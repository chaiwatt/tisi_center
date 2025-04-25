<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasePaymentsNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
            $table->string('name',255)->nullable()->comment('ชื่อผู้ชำระ')->after('condition_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}

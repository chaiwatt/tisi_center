<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasePaymentsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
            $table->integer('status')->default('1')->comment('สถานะใบแจ้งชำระ (1.รอสร้างใบแจ้งชำระ, 2.สร้างใบแจ้งชำระ)')->after('ref_id');
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
            $table->dropColumn(['status']);
        });
    }
}

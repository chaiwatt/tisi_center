<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseOperationsIdToLawCaseOperationDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_operation_detail', function (Blueprint $table) {
            $table->dropColumn(['case_number']);
            $table->integer('law_case_operations_id')->nullable()->comment(' id อ้างอิงตาราง law_case_operations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_operation_detail', function (Blueprint $table) {
            $table->string('case_number', 255)->nullable()->comment('เลขคดี  ตาราง law_cases');
            $table->dropColumn(['law_case_operations_id']);
        });
    }
}

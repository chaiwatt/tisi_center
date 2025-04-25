<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTransactionPayInCGDRef1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_transaction_pay_in', function (Blueprint $table) {
            $table->string('CGDRef1',255)->nullable()->comment('CGDRef1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_transaction_pay_in', function (Blueprint $table) {
            $table->dropColumn(['CGDRef1']);
        });
    }
}

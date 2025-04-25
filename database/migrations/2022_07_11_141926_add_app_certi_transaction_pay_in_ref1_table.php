<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTransactionPayInRef1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_transaction_pay_in', function (Blueprint $table) {
            $table->string('ref1',255)->nullable()->after('table_name')->comment('เลขที่คำขอ และ IB คณะประเมิน');
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
            $table->dropColumn(['ref1']);
        });
    }
} 

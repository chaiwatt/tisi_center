<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTransactionPayInAppNoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_transaction_pay_in', function (Blueprint $table) {
            $table->string('app_no',255)->nullable()->after('id')->comment('เลขที่คำขอ (pay in ครั้งที่ 1 ID คณะผู้ตรวจประเมิน)');
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
            $table->dropColumn(['app_no']);
        });
    }
}

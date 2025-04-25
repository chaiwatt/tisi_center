<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiIbPayIn1ConditionPayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib_pay_in1', function (Blueprint $table) {
            $table->integer('condition_pay')->nullable()->comment('เงื่อนไขการชำร 1.pay-in เกินกำหนด (ชำระที่ สมอ.), 2.ได้รับการยกเว้นค่าธรรมเนียม, 3.ชำระเงินนอกระบบ, กรณีอื่นๆ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib_pay_in1', function (Blueprint $table) {
            $table->dropColumn(['condition_pay']); 
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardReceptsDeductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_reward_recepts', function (Blueprint $table) {
            $table->integer('deduct')->nullable()->comment('หักเงินเก็บเป็นสวัสดีการ สมอ.')->after('address');
            $table->integer('deduct_vat')->nullable()->comment('หักภาษีมูลค่าเพิ่ม VAT')->after('deduct');
            $table->decimal('total',15,2)->nullable()->comment('รวมจำนวนเงินทั้งหมด')->after('deduct_vat');
            $table->decimal('deduct_amount',15,2)->nullable()->comment('รวมจำนวนเงิน หักเงินเก็บเป็นสวัสดีการ สมอ.')->after('total');
            $table->decimal('deduct_vat_amount',15,2)->nullable()->comment('รวมจำนวนเงิน หักภาษีมูลค่าเพิ่ม VAT')->after('deduct_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_reward_recepts', function (Blueprint $table) {
            $table->dropColumn(['deduct','deduct_vat','total','deduct_amount','deduct_vat_amount']);
        });  
    }
}

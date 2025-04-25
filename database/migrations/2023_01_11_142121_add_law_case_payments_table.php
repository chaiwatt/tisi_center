<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
            $table->integer('cancel_status')->nullable()->comment('ยกเลิก  : สถานะ 1.ยกเลิก');
            $table->text('cancel_remark')->nullable()->comment('ยกเลิก : หมายเหตุ');
            $table->integer('cancel_by')->nullable()->comment('ยกเลิก ID : user_register');
            $table->dateTime('cancel_at')->nullable()->comment('ยกเลิก  : วันที่บันทึก');
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
            $table->dropColumn(['cancel_status','cancel_remark','cancel_by','cancel_at']);
        });
    }
}

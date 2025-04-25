<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasePaymentsDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_payments_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_case_payments_id')->nullable()->comment('ID : law_case_payments');
            $table->text('fee_name')->nullable()->comment('ชื่อค่าธรรมเนียม/ค่าปรับ');
            $table->decimal('amount',12,2)->nullable()->comment('จำนวนเงิน');
            $table->text('remark_fee_name')->nullable()->comment('หมายเหตุค่าธรรมเนียม');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');
            $table->foreign('law_case_payments_id')
                    ->references('id')
                    ->on('law_case_payments')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_case_payments_detail');
    }
}

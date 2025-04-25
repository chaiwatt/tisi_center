<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasePaymentsAmountDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_payments', function (Blueprint $table) {
             $table->integer('amount_date')->nullable()->comment('ชำระภายใน/วัน') ->change();
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
               $table->date('amount_date')->nullable()->comment('ชำระภายใน/วัน') ->change();
        });
    }
}
 
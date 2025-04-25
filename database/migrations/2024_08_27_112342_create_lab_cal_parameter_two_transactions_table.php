<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabCalParameterTwoTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_cal_parameter_two_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('app_certi_lab_id')->nullable();
            $table->unsignedBigInteger('bcertify_calibration_branche_id')->nullable();
            $table->unsignedBigInteger('calibration_branch_instrument_group_id')->nullable();
            $table->unsignedBigInteger('calibration_branch_parameter_two_id')->nullable();
            $table->longText('value')->nullable();
            $table->char('status',1)->default(1);
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
        Schema::dropIfExists('lab_cal_parameter_two_transactions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibrationBranchInstrumentGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibration_branch_instrument_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('bcertify_calibration_branche_id');
            $table->string('name')->nullable();
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
        Schema::dropIfExists('calibration_branch_instrument_groups');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabEndDateToSection5LabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs', function (Blueprint $table) {
            $table->date('lab_end_date')->nullable()->comment('วันที่สิ้นสุด LAB')->after('lab_start_date');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs', function (Blueprint $table) {
            $table->dropColumn(['lab_end_date']);
        });
    }
}

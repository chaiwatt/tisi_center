<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->string('ref_no',255)->nullable()->comment('เลขที่อ้างอิงแจ้งงานคดี');
            $table->string('license_number',255)->comment('เลขใบอนุญาต');
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
        Schema::dropIfExists('law_case_licenses');
    }
}

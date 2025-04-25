<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseStandardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_standard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->string('ref_no',255)->nullable()->comment('เลขที่อ้างอิงแจ้งงานคดี');
            $table->integer('tis_id')->nullable()->comment('มอก. id อ้างอิงตาราง tb3_tis');
            $table->string('tb3_tisno')->nullable()->comment('มอก. number');
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
        Schema::dropIfExists('law_case_standard');
    }
}

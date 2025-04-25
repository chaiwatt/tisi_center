<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawOffenderStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_offenders_standards', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('law_offender_id')->nullable()->comment('ID : law_offenders_cases');
            $table->integer('tis_id')->nullable()->comment('มอก. id อ้างอิงตาราง tb3_tis');
            $table->string('tb3_tisno')->nullable()->comment('มอก. number');
            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->string('case_number', 255)->nullable()->comment('เลขคดี');
            $table->foreign('law_offender_id')
                    ->references('id')
                    ->on('law_offenders_cases')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_offenders_standards');
    }
}

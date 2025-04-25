<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseResultSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_result_section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_case_result_id')->nullable()->comment('ID ตาราง law_case_result');
            $table->integer('section')->comment('ID : ตาราง law_basic_section');
            $table->integer('punish')->comment('ID : ตาราง law_basic_section');
            $table->integer('power')->comment('อำนาจพิจารณาเปรียบเทียบปรับ 1.เลขาธิการสำนักงานมาตรฐานอตสาหกรรม(สมอ), 2.คณะกรรมการเปรียบเทียบ');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
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
        Schema::dropIfExists('law_case_result_section');
    }
}

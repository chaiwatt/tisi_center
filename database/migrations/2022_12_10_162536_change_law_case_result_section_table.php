<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLawCaseResultSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_result_section', function (Blueprint $table) {
            $table->integer('section')->nullable()->comment('ID : ตาราง law_basic_section')->change();
            $table->integer('punish')->nullable()->comment('ID : ตาราง law_basic_section')->change();
            $table->integer('power')->nullable()->comment('อำนาจพิจารณาเปรียบเทียบปรับ 1.เลขาธิการสำนักงานมาตรฐานอตสาหกรรม(สมอ), 2.คณะกรรมการเปรียบเทียบ')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_result_section', function (Blueprint $table) {
            $table->integer('section')->comment('ID : ตาราง law_basic_section')->change();
            $table->integer('punish')->comment('ID : ตาราง law_basic_section')->change();
            $table->integer('power')->comment('อำนาจพิจารณาเปรียบเทียบปรับ 1.เลขาธิการสำนักงานมาตรฐานอตสาหกรรม(สมอ), 2.คณะกรรมการเปรียบเทียบ')->change();
        });
    }
}

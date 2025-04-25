<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffendApplicanttypeIdToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->boolean('offend_condition')->nullable()->comment('1 = กรณีดึงข้อมูลจาก api')->after('offend_license_type');
            $table->string('offend_applicanttype_id')->nullable()->comment('ประเภท')->after('offend_condition');
            $table->string('offend_person_type')->nullable()->comment('ประเภทข้อมูล')->after('offend_applicanttype_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->dropColumn(['offend_condition','offend_applicanttype_id','offend_person_type']);
        });
    }
}

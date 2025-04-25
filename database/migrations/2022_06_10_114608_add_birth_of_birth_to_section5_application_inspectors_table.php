<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBirthOfBirthToSection5ApplicationInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->date('applicant_date_of_birth')->nullable()->comment('วัน/เดือน/ปี เกิด')->after('applicant_taxid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->dropColumn(['applicant_date_of_birth']);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeApplicantEmailInSection5ApplicationInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->string('applicant_email', 255)->nullable()->comment('อีเมล')->change();
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
            $table->string('applicant_email', 30)->nullable()->comment('อีเมล')->change();
        });
    }
}

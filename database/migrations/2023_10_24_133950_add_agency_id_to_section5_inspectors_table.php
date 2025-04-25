<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgencyIdToSection5InspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->integer('agency_id')->nullable()->comment('หน่วยงาน id ตาราง sso_users')->after('inspectors_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->dropColumn(['agency_id']);
        });
    }
}

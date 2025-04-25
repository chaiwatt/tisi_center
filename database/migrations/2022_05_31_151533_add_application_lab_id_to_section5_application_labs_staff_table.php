<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplicationLabIdToSection5ApplicationLabsStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_staff', function (Blueprint $table) {
            $table->integer('application_lab_id')->nullable()->after('id')->comment('ID ตาราง section5_application_labs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_staff', function (Blueprint $table) {
            $table->dropColumn(['application_lab_id']);
        });
    }
}

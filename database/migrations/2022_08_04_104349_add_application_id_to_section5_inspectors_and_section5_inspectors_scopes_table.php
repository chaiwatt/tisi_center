<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplicationIdToSection5InspectorsAndSection5InspectorsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_inspectors', function (Blueprint $table) {
            $table->unsignedInteger('application_id')->after('state')->nullable();
        });
        Schema::table('section5_inspectors_scopes', function (Blueprint $table) {
            $table->unsignedInteger('application_id')->after('state')->nullable();
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
            $table->dropColumn(['application_id']);
        });
        Schema::table('section5_inspectors_scopes', function (Blueprint $table) {
            $table->dropColumn(['application_id']);
        });
    }
}

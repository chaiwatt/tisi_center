<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAssignByFromApplicationInspectorAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_application_inspectors', function (Blueprint $table) {
            $table->string('assign_by')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE sso_application_inspectors MODIFY assign_by INTEGER;");
        Schema::table('sso_application_inspectors', function (Blueprint $table) {

        });
    }
}

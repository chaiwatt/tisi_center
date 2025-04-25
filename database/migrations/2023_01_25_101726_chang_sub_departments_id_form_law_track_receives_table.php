<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangSubDepartmentsIdFormLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->string('sub_departments_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->integer('sub_departments_id')->nullable()->change();
        });
    }
}

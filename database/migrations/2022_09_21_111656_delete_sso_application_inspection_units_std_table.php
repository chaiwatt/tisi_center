<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSsoApplicationInspectionUnitsStdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sso_application_inspection_units_std');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sso_application_inspection_units_std', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tis_standards_id')->nullable()->comment('มอก.');
            $table->unsignedInteger('app_units_id')->comment('ID ตาราง sso_application_inspection_units');
            $table->timestamps();

        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationInspectionUnitStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_application_inspection_units_std', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tis_standards_id')->nullable()->comment('มอก.');
            $table->unsignedInteger('app_units_id')->comment('ID ตาราง sso_application_inspection_units');
            $table->timestamps();

            $table->foreign('app_units_id')
                    ->references('id')
                    ->on('sso_application_inspection_units')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_application_inspection_units_std');
    }
}

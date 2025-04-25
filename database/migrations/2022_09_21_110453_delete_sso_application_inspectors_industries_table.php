<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSsoApplicationInspectorsIndustriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sso_application_inspectors_industries');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sso_application_inspectors_industries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tis_industry_branch_id')->nullable()->comment('หมวดอุตสากรรม รายสาขา.');
            $table->unsignedInteger('app_inspector_id')->comment('ID ตาราง sso_application_inspectors');
            $table->timestamps();

            $table->foreign('app_inspector_id')
            ->references('id')
            ->on('sso_application_inspection_units')
            ->onDelete('cascade');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationInspectorsScopeTis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_inspectors_scope_tis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inspector_scope_id')->nullable()->comment('id ตาราง section5_application_inspectors_scope');
            $table->string('application_no', 30)->nullable()->comment('application_no ตาราง section5_application_inspectors');
            $table->integer('tis_id')->nullable()->comment('id ตาราง tis_standards');
            $table->string('tis_no', 255)->nullable()->comment('เลข มอก.');
            $table->string('tis_name')->nullable()->comment('ชื่อ มอก.');
            $table->timestamps();
            $table->foreign('inspector_scope_id', 'inspector_scope_id_fk_scope')
                    ->references('id')
                    ->on('section5_application_inspectors_scope')
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
        Schema::dropIfExists('section5_application_inspectors_scope_tis');
    }
}

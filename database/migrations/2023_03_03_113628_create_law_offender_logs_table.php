<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawOffenderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_offenders_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_offender_id')->nullable()->comment('ID : law_offenders_cases');
            $table->text('ref_table')->nullable()->comment('Table ที่แก้ไข');
            $table->integer('ref_id')->nullable()->comment('ID ที่แก้ไข');
            $table->text('column')->nullable()->comment('column ที่แก้ไข');
            $table->text('data_old')->nullable()->comment('ข้อมูลเดิม');
            $table->text('data_new')->nullable()->comment('ข้อมูลใหม่');
            $table->bigInteger('created_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();

            $table->foreign('law_offender_id')
                    ->references('id')
                    ->on('law_offenders_cases')
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
        Schema::dropIfExists('law_offenders_logs');
    }
}

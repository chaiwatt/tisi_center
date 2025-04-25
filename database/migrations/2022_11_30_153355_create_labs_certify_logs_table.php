<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabsCertifyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_labs_certificates_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('labs_certify_id')->nullable();
            $table->date('old_end_date')->nullable()->comment('วันหมดอายุเก่า');
            $table->date('new_end_date')->nullable()->comment('วันหมดอายุใหม่');
            $table->integer('app_cert_lab_file_all_id')->nullable()->comment('id : app_cert_lab_file_all');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->timestamps();
            $table->foreign('labs_certify_id')
                ->references('id')
                ->on('section5_labs_certificates')
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
        Schema::dropIfExists('section5_labs_certificates_logs');
    }
}

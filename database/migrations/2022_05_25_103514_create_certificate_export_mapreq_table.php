<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateExportMapreqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_export_mapreq', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_certi_lab_id')->nullable()->comment('id คำขอ');
            $table->integer('certificate_exports_id')->nullable()->comment('id ใบรับรอง');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate_export_mapreq');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateIbExportMapreqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_ib_export_mapreq', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_certi_ib_id')->nullable()->comment('ID : app_certi_ib');
            $table->integer('certificate_exports_id')->nullable()->comment('ID: certificate_exports');
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
        Schema::dropIfExists('certificate_ib_export_mapreq');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabGazetteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_gazette_details', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('app_lab_id')->nullable()->comment('ID : คำขอ');
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');

            $table->unsignedInteger('app_gazette_id')->nullable();
            $table->foreign('app_gazette_id')
                ->references('id')
                ->on('section5_application_labs_gazette')
                ->onDelete('cascade');

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
        Schema::dropIfExists('section5_application_labs_gazette_details');
    }
}

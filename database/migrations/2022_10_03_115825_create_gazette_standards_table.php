<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGazetteStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_gazette_standard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gazette_id')->nullable()->comment('id ตาราง certify_gazette');
            $table->integer('standard_id')->nullable()->comment('id ตาราง certify_standards');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('certify_gazette_standard');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetStandardExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_experts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setstandard_id')->nullable()->comment('id ตาราง certify_setstandards');
            $table->integer('experts_id')->nullable()->comment('id ตาราง basic_methods'); 
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
        Schema::dropIfExists('certify_setstandard_experts');
    }
}

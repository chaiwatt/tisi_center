<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStandardIcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_standard_ics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('std_id')->nullable()->comment('id ตาราง certify_standards');
            $table->integer('ics_id')->nullable()->comment('id ตาราง basic_ics');
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
        Schema::dropIfExists('certify_standard_ics');
    }
}

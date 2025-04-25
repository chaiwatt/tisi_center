<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaveExampleMapLapResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('save_example_map_lap_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('map_lab_detail_id')->nullable()->comment('id ตาราง save_example_map_lap_detail');
            $table->integer('test_no')->nullable()->comment('ครั้งที่ทดสอบ');
            $table->text('test_result')->nullable()->comment('ผลทดสอบ');
            $table->timestamps();
            $table->foreign('map_lab_detail_id')
                  ->references('id')
                  ->on('save_example_map_lap_detail')
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
        Schema::dropIfExists('save_example_map_lap_result');
    }
}
